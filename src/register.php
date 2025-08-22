<?php
require_once __DIR__ . '/../app/database.php';

date_default_timezone_set('Indian/Antananarivo');
header('Content-Type: application/json');

$pdo = Database::pdo();

// 🔹 Fonction de normalisation du téléphone
function normalize_phone($telephone) {
    $telephone = preg_replace('/\s+/', '', $telephone);
    if (strpos($telephone, '+261') === 0) {
        $telephone = '0' . substr($telephone, 4);
    } elseif (strpos($telephone, '261') === 0) {
        $telephone = '0' . substr($telephone, 3);
    }
    return $telephone;
}

// 🔹 Vérification de la méthode
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

// 🔹 Récupération des données
$nom = trim($_POST['nom'] ?? '');
$prenom = trim($_POST['prenom'] ?? '');
$telephone = normalize_phone(trim($_POST['telephone'] ?? ''));
$mot_de_passe = $_POST['mot_de_passe'] ?? '';
$confirm_mot_de_passe = $_POST['confirm_mot_de_passe'] ?? '';
$parrain_id = isset($_POST['parrain_id']) ? intval($_POST['parrain_id']) : null;

// 🔹 Validation des champs
if (!$nom || !$prenom || !$telephone || !$mot_de_passe || !$confirm_mot_de_passe || !$parrain_id) {
    echo json_encode(['success' => false, 'message' => 'Tous les champs sont obligatoires.']);
    exit;
}

if ($mot_de_passe !== $confirm_mot_de_passe) {
    echo json_encode(['success' => false, 'message' => 'Les mots de passe ne correspondent pas.']);
    exit;
}

// 🔹 Vérification du téléphone
$stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE telephone = ?");
$stmt->execute([$telephone]);
if ($stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Ce numéro de téléphone est déjà utilisé.']);
    exit;
}

// 🔹 Vérification du parrain
$stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE id = ?");
$stmt->execute([$parrain_id]);
if (!$stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'ID de parrain invalide.']);
    exit;
}

// 🔹 Limite de filleuls
$stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE parrain_id = ?");
$stmt->execute([$parrain_id]);
if ($stmt->fetchColumn() >= 2) {
    echo json_encode(['success' => false, 'message' => 'Ce parrain a déjà deux filleuls.']);
    exit;
}

// 🔹 Hachage du mot de passe
$hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

try {
    // 🔄 Transaction
    $pdo->beginTransaction();

    // 🔹 Insertion utilisateur
    $stmt = $pdo->prepare("
        INSERT INTO utilisateurs (nom, prenom, telephone, mot_de_passe, parrain_id, date_inscription)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    $stmt->execute([$nom, $prenom, $telephone, $hash, $parrain_id]);

    $userId = $pdo->lastInsertId();

    // 🔹 Création de la balance
    $stmt = $pdo->prepare("INSERT INTO balances (utilisateur_id, total) VALUES (?, 0)");
    $stmt->execute([$userId]);

    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Inscription réussie.', 'user_id' => $userId]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'inscription.']);
}
exit;
