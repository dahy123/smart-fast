<?php
require_once "../app/admin_controller.php";

// Vérifier si l'utilisateur est connecté et est admin
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
    exit;
}

$adminController = new AdminController();
if (!$adminController->isAdmin($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Accès refusé']);
    exit;
}

// Récupérer l'action demandée
$action = $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'validate_deposit':
            $deposit_id = $_POST['deposit_id'] ?? 0;
            if (!$deposit_id) {
                throw new Exception('ID du dépôt manquant');
            }
            
            $result = $adminController->validateDeposit($deposit_id, $_SESSION['user_id']);
            echo json_encode($result);
            break;
            
        case 'reject_deposit':
            $deposit_id = $_POST['deposit_id'] ?? 0;
            $raison = $_POST['raison'] ?? '';
            
            if (!$deposit_id) {
                throw new Exception('ID du dépôt manquant');
            }
            
            $result = $adminController->rejectDeposit($deposit_id, $_SESSION['user_id'], $raison);
            echo json_encode($result);
            break;
            
        case 'validate_withdrawal':
            $withdrawal_id = $_POST['withdrawal_id'] ?? 0;
            if (!$withdrawal_id) {
                throw new Exception('ID du retrait manquant');
            }
            
            $result = $adminController->validateWithdrawal($withdrawal_id, $_SESSION['user_id']);
            echo json_encode($result);
            break;
            
        case 'reject_withdrawal':
            $withdrawal_id = $_POST['withdrawal_id'] ?? 0;
            $raison = $_POST['raison'] ?? '';
            
            if (!$withdrawal_id) {
                throw new Exception('ID du retrait manquant');
            }
            
            $result = $adminController->rejectWithdrawal($withdrawal_id, $_SESSION['user_id'], $raison);
            echo json_encode($result);
            break;
            
        default:
            throw new Exception('Action non reconnue');
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage()
    ]);
}
?>
