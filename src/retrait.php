<?php
session_start();
require_once __DIR__ . '/../app/database.php';

date_default_timezone_set('Indian/Antananarivo');
header('Content-Type: application/json');

// Vérification session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non authentifié.']);
    exit;
}

$pdo = Database::pdo();
$user_id = $_SESSION['user_id'];

// Lecture via $_POST car FormData est utilisé
$amount = isset($_POST['amount']) ? (int) $_POST['amount'] : 0;
$method = isset($_POST['method']) ? trim($_POST['method']) : '';

// Validation
if ($amount < 5000 || empty($method)) {
    echo json_encode(['success' => false, 'message' => 'Montant ou méthode invalide.']);
    exit;
}

// Vérification du solde
$stmt = $pdo->prepare("SELECT total FROM balances WHERE utilisateur_id = ?");
$stmt->execute([$user_id]);
$solde = (int) $stmt->fetchColumn();

if ($solde < $amount) {
    echo json_encode(['success' => false, 'message' => 'Solde insuffisant.']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Débit
    $stmt = $pdo->prepare("UPDATE balances SET total = total - ? WHERE utilisateur_id = ?");
    $stmt->execute([$amount, $user_id]);

    // Enregistrement du retrait
    $stmt = $pdo->prepare("
        INSERT INTO retraits (utilisateur_id, montant, methode, statut, date_retrait)
        VALUES (?, ?, ?, 'en_attente', NOW())
    ");
    $stmt->execute([$user_id, $amount, $method]);

    // Nouveau solde
    $stmt = $pdo->prepare("SELECT total FROM balances WHERE utilisateur_id = ?");
    $stmt->execute([$user_id]);
    $newBalance = (int) $stmt->fetchColumn();

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Demande de retrait enregistrée.',
        'newBalance' => $newBalance
    ]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la demande.']);
}
