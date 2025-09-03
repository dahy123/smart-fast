<?php
require_once "../app/admin_controller.php";

// Vérifier si l'utilisateur est connecté et est admin
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$adminController = new AdminController();
if (!$adminController->isAdmin($_SESSION['user_id'])) {
    header('Location: app.php');
    exit;
}

// Récupérer le type d'export et les filtres
$type = $_GET['type'] ?? '';
$filters = [
    'statut' => $_GET['statut'] ?? '',
    'date_debut' => $_GET['date_debut'] ?? '',
    'date_fin' => $_GET['date_fin'] ?? '',
    'search' => $_GET['search'] ?? '',
    'niveau' => $_GET['niveau'] ?? ''
];

try {
    switch ($type) {
        case 'deposits':
            $csv_data = $adminController->exportDeposits($filters);
            $filename = 'deposits_' . date('Y-m-d_H-i-s') . '.csv';
            break;
            
        case 'withdrawals':
            $csv_data = $adminController->exportWithdrawals($filters);
            $filename = 'withdrawals_' . date('Y-m-d_H-i-s') . '.csv';
            break;
            
        case 'investments':
            $csv_data = $adminController->exportInvestments($filters);
            $filename = 'investments_' . date('Y-m-d_H-i-s') . '.csv';
            break;
            
        default:
            throw new Exception('Type d\'export non reconnu');
    }
    
    // En-têtes pour le téléchargement
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
    
    // Afficher le contenu CSV
    echo $csv_data;
    
} catch (Exception $e) {
    // En cas d'erreur, rediriger vers la page admin
    header('Location: admin.php?error=' . urlencode($e->getMessage()));
    exit;
}
?>
