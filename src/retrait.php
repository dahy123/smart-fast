<?php
session_start();
require_once __DIR__ . '/../app/database.php';

date_default_timezone_set('Indian/Antananarivo');
header('Content-Type: application/json');

// 🔐 Vérification de session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non authentifié.']);
    exit;
}

$pdo = Database::pdo();
$user_id = $_SESSION['user_id'];

// 🔹 Lecture des données JSON
$data = json_decode(file_get_contents('php://input'), true);
$amount = isset($data['amount']) ? (int) $data['amount'] : 0;
$method = isset($data['method']) ? trim($data['method']) : '';

// 🔍 Validation des données
if ($amount < 2500 || empty($method)) {
    echo json_encode(['success' => false, 'message' => 'Montant ou méthode invalide.']);
    exit;
}

// 🔹 Vérification du solde
$stmt = $pdo->prepare("SELECT total FROM balances WHERE utilisateur_id = ?");
$stmt->execute([$user_id]);
$solde = (int) $stmt->fetchColumn();

if ($solde < $amount) {
    echo json_encode(['success' => false, 'message' => 'Solde insuffisant.']);
    exit;
}

try {
    // 🔄 Début de transaction
    $pdo->beginTransaction();

    // 💸 Débit du solde
    $stmt = $pdo->prepare("UPDATE balances SET total = total - ? WHERE utilisateur_id = ?");
    $stmt->execute([$amount, $user_id]);

    // 📝 Enregistrement du retrait
    $stmt = $pdo->prepare("
        INSERT INTO retraits (utilisateur_id, montant, methode, statut, date_retrait)
        VALUES (?, ?, ?, 'en_attente', NOW())
    ");
    $stmt->execute([$user_id, $amount, $method]);

    // ✅ Validation
    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Demande de retrait enregistrée.']);
} catch (Exception $e) {
    // ❌ Annulation et remboursement
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la demande.']);
}
exit;
