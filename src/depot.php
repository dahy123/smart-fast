<?php
session_start();
require_once __DIR__ . '/../app/database.php';

date_default_timezone_set('Indian/Antananarivo');
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non authentifié.']);
    exit;
}

$pdo = Database::pdo();
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

// 🔹 Récupération des données
$montant = isset($_POST['amount']) ? floatval($_POST['amount']) : null;
$reference = isset($_POST['reference']) ? trim($_POST['reference']) : '';
$preuve_image = '';

// 🔹 Validation des données
if (!$montant || $montant <= 0 || empty($reference)) {
    echo json_encode(['success' => false, 'message' => 'Montant ou référence invalide.']);
    exit;
}

// 🔹 Vérification de la référence
$stmt = $pdo->prepare("SELECT COUNT(*) FROM depots WHERE reference = ?");
$stmt->execute([$reference]);
if ($stmt->fetchColumn() > 0) {
    echo json_encode(['success' => false, 'message' => 'Référence déjà utilisée.']);
    exit;
}

// 🔹 Upload de la preuve
if (!empty($_FILES['proof']) && $_FILES['proof']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $extension = pathinfo($_FILES['proof']['name'], PATHINFO_EXTENSION);
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    if (!in_array(strtolower($extension), $allowed)) {
        echo json_encode(['success' => false, 'message' => 'Format de fichier non autorisé.']);
        exit;
    }

    $fileName = uniqid('preuve_', true) . '.' . $extension;
    $uploadFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['proof']['tmp_name'], $uploadFile)) {
        $preuve_image = $fileName;
    } else {
        echo json_encode(['success' => false, 'message' => 'Échec de l\'upload de la preuve.']);
        exit;
    }
}

// 🔹 Enregistrement du dépôt
$stmt = $pdo->prepare("
    INSERT INTO depots (utilisateur_id, montant, reference, preuve_image, statut, date_depot)
    VALUES (?, ?, ?, ?, ?, NOW())
");
$success = $stmt->execute([$user_id, $montant, $reference, $preuve_image, 'en_attente']);

if ($success) {
    echo json_encode(['success' => true, 'message' => 'Dépôt enregistré avec succès.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement du dépôt.']);
}
exit;
