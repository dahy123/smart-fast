<?php
require_once __DIR__ . '/../app/database.php';

header('Content-Type: application/json');
date_default_timezone_set('Indian/Antananarivo');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

$pdo = Database::pdo();
$parrain_id = intval($_POST['parrain_id'] ?? 0);

if (!$parrain_id) {
    echo json_encode(['success' => false, 'message' => 'ID parrain manquant.']);
    exit;
}

//  Vérifier si le parrain existe
$stmt = $pdo->prepare("SELECT id, nom, prenom FROM utilisateurs WHERE id = ?");
$stmt->execute([$parrain_id]);
$parrain = $stmt->fetch(PDO::FETCH_ASSOC);

if ($parrain) {
    echo json_encode([
        'success' => true,
        'nom' => $parrain['nom'],
        'prenom' => $parrain['prenom']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Parrain introuvable.']);
}
exit;
