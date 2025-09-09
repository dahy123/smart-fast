<?php
require_once __DIR__ . '/../app/database.php';

header('Content-Type: application/json');
date_default_timezone_set('Indian/Antananarivo');

// Vérifier la méthode
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

$pdo = Database::pdo();

// Récupérer le code utilisateur du parrain
$parrain_code = trim($_POST['parrain_code'] ?? '');

if (empty($parrain_code)) {
    echo json_encode(['success' => false, 'message' => 'Code parrain manquant.']);
    exit;
}

// Vérifier si le parrain existe via son code utilisateur
$stmt = $pdo->prepare("SELECT id, nom, prenom, code_utilisateur 
                       FROM utilisateurs 
                       WHERE code_utilisateur = ?");
$stmt->execute([$parrain_code]);
$parrain = $stmt->fetch(PDO::FETCH_ASSOC);

if ($parrain) {
    echo json_encode([
        'success' => true,
        'id' => $parrain['id'],  // utile si tu veux le stocker en interne
        'code_utilisateur' => $parrain['code_utilisateur'],
        'nom' => $parrain['nom'],
        'prenom' => $parrain['prenom']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Parrain introuvable.']);
}
exit;
