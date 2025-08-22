<?php
require_once __DIR__ . '/../app/database.php';

date_default_timezone_set('Indian/Antananarivo');
header('Content-Type: application/json');

// 🔹 Fonction de normalisation du téléphone
function normalize_phone($telephone) {
    $telephone = preg_replace('/\s+/', '', $telephone);
    if (strpos($telephone, '+261') === 0) {
        return '0' . substr($telephone, 4);
    }
    if (strpos($telephone, '261') === 0) {
        return '0' . substr($telephone, 3);
    }
    return $telephone;
}

// 🔹 Vérification de la méthode
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

// 🔹 Récupération des données
$telephone = normalize_phone(trim($_POST['telephone'] ?? ''));
$mot_de_passe = $_POST['mot_de_passe'] ?? '';

// 🔹 Validation des champs
if (!$telephone || !$mot_de_passe) {
    echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs.']);
    exit;
}

// 🔹 Connexion à la base
$pdo = Database::pdo();

// 🔍 Recherche de l'utilisateur
$stmt = $pdo->prepare("SELECT id, mot_de_passe, nom, prenom, role FROM utilisateurs WHERE telephone = ?");
$stmt->execute([$telephone]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
    // ✅ Connexion réussie
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nom'] = $user['nom'];
    $_SESSION['prenom'] = $user['prenom'];
    $_SESSION['role'] = $user['role'];

    echo json_encode([
        'success' => true,
        'message' => 'Connexion réussie.',
        'user' => [
            'id' => $user['id'],
            'nom' => $user['nom'],
            'prenom' => $user['prenom'],
            'role' => $user['role']
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Numéro ou mot de passe incorrect.']);
}
exit;
