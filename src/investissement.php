<?php
session_start();
require_once __DIR__ . '/../app/database.php';

date_default_timezone_set('Indian/Antananarivo');
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non authentifié.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$niveau = (int) ($data['niveau'] ?? 0);
$montant = (float) ($data['montant'] ?? 0);

if ($niveau <= 0 || $montant <= 0) {
    echo json_encode(['success' => false, 'message' => 'Données invalides.']);
    exit;
}

$pdo = Database::pdo();
$user_id = $_SESSION['user_id'];
$admin_id = 1;

try {
    // 🔄 Démarrer la transaction
    $pdo->beginTransaction();

    // 🔹 Vérifier le solde
    $stmt = $pdo->prepare("SELECT total FROM balances WHERE utilisateur_id = ?");
    $stmt->execute([$user_id]);
    $solde = (float) $stmt->fetchColumn();

    if ($solde < $montant) {
        throw new Exception("Solde insuffisant.");
    }

    // 🔹 Vérifier les niveaux précédents
    if ($niveau > 1) {
        $niveau_precedent = $niveau - 1;
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM investissements WHERE utilisateur_id = ? AND niveau_id = ?");
        $stmt->execute([$user_id, $niveau_precedent]);
        if ($stmt->fetchColumn() == 0) {
            throw new Exception("Veuillez d'abord débloquer le niveau $niveau_precedent.");
        }
    }

    // 🔹 Vérifier si déjà investi à ce niveau
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM investissements WHERE utilisateur_id = ? AND niveau_id = ?");
    $stmt->execute([$user_id, $niveau]);
    if ($stmt->fetchColumn() > 0) {
        throw new Exception("Niveau déjà débloqué.");
    }

    // 🔹 Débiter le solde de l'utilisateur
    $stmt = $pdo->prepare("UPDATE balances SET total = total - ? WHERE utilisateur_id = ?");
    $stmt->execute([$montant, $user_id]);

    // 🔹 Rechercher le bénéficiaire
    $beneficiaire_id = $admin_id;
    $parrain_courant = $user_id;

    for ($i = 0; $i < $niveau; $i++) {
        $stmt = $pdo->prepare("SELECT parrain_id FROM utilisateurs WHERE id = ?");
        $stmt->execute([$parrain_courant]);
        $parrain_courant = $stmt->fetchColumn();

        if (!$parrain_courant) {
            $parrain_courant = null;
            break;
        }
    }

    if ($parrain_courant) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM investissements WHERE utilisateur_id = ? AND niveau_id = ?");
        $stmt->execute([$parrain_courant, $niveau]);
        if ($stmt->fetchColumn() > 0) {
            $beneficiaire_id = $parrain_courant;
        }
    }

    // 🔹 Créditer le bénéficiaire
    $stmt = $pdo->prepare("UPDATE balances SET total = total + ? WHERE utilisateur_id = ?");
    $stmt->execute([$montant, $beneficiaire_id]);

    // 🔹 Enregistrer l’investissement
    $stmt = $pdo->prepare("
        INSERT INTO investissements (utilisateur_id, niveau_id, montant, date_investissement)
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->execute([$user_id, $niveau, $montant]);

    // 🔹 Enregistrer le gain
    $stmt = $pdo->prepare("
        INSERT INTO gains (source_id, destinataire_id, niveau, montant, date_gain)
        VALUES (?, ?, ?, ?, NOW())
    ");
    $stmt->execute([$user_id, $beneficiaire_id, $niveau, $montant]);

    // ✅ Valider la transaction
    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => "Niveau $niveau débloqué avec succès. Bonus versé à l'utilisateur ID $beneficiaire_id"
    ]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
exit;
