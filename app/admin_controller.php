<?php
date_default_timezone_set('Indian/Antananarivo');
session_start();

require_once __DIR__ . '/database.php';

class AdminController {
    private $pdo;
    
    public function __construct() {
        $this->pdo = Database::pdo();
        if (!$this->pdo) {
            throw new Exception("Erreur de connexion à la base de données");
        }
    }
    
    // Vérifier si l'utilisateur est admin
    public function isAdmin($user_id) {
        $stmt = $this->pdo->prepare("SELECT role FROM utilisateurs WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user && $user['role'] === Database::ROLE_ADMIN;
    }
    
    // === GESTION DES DÉPÔTS ===
    
    // Récupérer tous les dépôts avec filtres
    public function getDeposits($filters = []) {
        $where = "WHERE 1=1";
        $params = [];
        
        if (!empty($filters['statut'])) {
            $where .= " AND d.statut = ?";
            $params[] = $filters['statut'];
        }
        
        if (!empty($filters['date_debut'])) {
            $where .= " AND DATE(d.date_depot) >= ?";
            $params[] = $filters['date_debut'];
        }
        
        if (!empty($filters['date_fin'])) {
            $where .= " AND DATE(d.date_depot) <= ?";
            $params[] = $filters['date_fin'];
        }
        
        if (!empty($filters['search'])) {
            $where .= " AND (u.nom LIKE ? OR u.prenom LIKE ? OR u.telephone LIKE ? OR d.reference LIKE ?)";
            $search = "%" . $filters['search'] . "%";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }
        
        $sql = "SELECT d.*, u.nom, u.prenom, u.telephone 
                FROM depots d 
                JOIN utilisateurs u ON d.utilisateur_id = u.id 
                $where 
                ORDER BY d.date_depot DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Valider un dépôt
    public function validateDeposit($deposit_id, $admin_id) {
        if (!$this->isAdmin($admin_id)) {
            return ['success' => false, 'message' => 'Accès non autorisé'];
        }
        
        try {
            $this->pdo->beginTransaction();
            
            // Récupérer les infos du dépôt
            $stmt = $this->pdo->prepare("SELECT d.*, u.nom, u.prenom FROM depots d JOIN utilisateurs u ON d.utilisateur_id = u.id WHERE d.id = ?");
            $stmt->execute([$deposit_id]);
            $deposit = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$deposit) {
                throw new Exception("Dépôt non trouvé");
            }
            
            if ($deposit['statut'] !== Database::STATUT_ATTENTE) {
                throw new Exception("Ce dépôt a déjà été traité");
            }
            
            // Mettre à jour le statut du dépôt
            $stmt = $this->pdo->prepare("UPDATE depots SET statut = ? WHERE id = ?");
            $stmt->execute([Database::STATUT_VALIDE, $deposit_id]);
            
            // Mettre à jour le solde de l'utilisateur
            $stmt = $this->pdo->prepare("UPDATE balances SET total = total + ? WHERE utilisateur_id = ?");
            $stmt->execute([$deposit['montant'], $deposit['utilisateur_id']]);
            
            // Si la balance n'existe pas, la créer
            if ($stmt->rowCount() === 0) {
                $stmt = $this->pdo->prepare("INSERT INTO balances (utilisateur_id, total) VALUES (?, ?)");
                $stmt->execute([$deposit['utilisateur_id'], $deposit['montant']]);
            }
            
            $this->pdo->commit();
            
            return [
                'success' => true, 
                'message' => "Dépôt de " . number_format($deposit['montant'], 2) . " € validé pour " . $deposit['prenom'] . " " . $deposit['nom']
            ];
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    // Rejeter un dépôt
    public function rejectDeposit($deposit_id, $admin_id, $raison = '') {
        if (!$this->isAdmin($admin_id)) {
            return ['success' => false, 'message' => 'Accès non autorisé'];
        }
        
        try {
            $stmt = $this->pdo->prepare("UPDATE depots SET statut = ? WHERE id = ?");
            $stmt->execute([Database::STATUT_REJETE, $deposit_id]);
            
            return ['success' => true, 'message' => 'Dépôt rejeté avec succès'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    // === GESTION DES RETRAITS ===
    
    // Récupérer tous les retraits avec filtres
    public function getWithdrawals($filters = []) {
        $where = "WHERE 1=1";
        $params = [];
        
        if (!empty($filters['statut'])) {
            $where .= " AND r.statut = ?";
            $params[] = $filters['statut'];
        }
        
        if (!empty($filters['date_debut'])) {
            $where .= " AND DATE(r.date_retrait) >= ?";
            $params[] = $filters['date_debut'];
        }
        
        if (!empty($filters['date_fin'])) {
            $where .= " AND DATE(r.date_retrait) <= ?";
            $params[] = $filters['date_fin'];
        }
        
        if (!empty($filters['search'])) {
            $where .= " AND (u.nom LIKE ? OR u.prenom LIKE ? OR u.telephone LIKE ?)";
            $search = "%" . $filters['search'] . "%";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }
        
        $sql = "SELECT r.*, u.nom, u.prenom, u.telephone 
                FROM retraits r 
                JOIN utilisateurs u ON r.utilisateur_id = u.id 
                $where 
                ORDER BY r.date_retrait DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Valider un retrait
    public function validateWithdrawal($withdrawal_id, $admin_id) {
        if (!$this->isAdmin($admin_id)) {
            return ['success' => false, 'message' => 'Accès non autorisé'];
        }
        
        try {
            $this->pdo->beginTransaction();
            
            // Récupérer les infos du retrait
            $stmt = $this->pdo->prepare("SELECT r.*, u.nom, u.prenom FROM retraits r JOIN utilisateurs u ON r.utilisateur_id = u.id WHERE r.id = ?");
            $stmt->execute([$withdrawal_id]);
            $withdrawal = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$withdrawal) {
                throw new Exception("Retrait non trouvé");
            }
            
            if ($withdrawal['statut'] !== Database::STATUT_ATTENTE) {
                throw new Exception("Ce retrait a déjà été traité");
            }
            
            // Vérifier le solde disponible
            $stmt = $this->pdo->prepare("SELECT total FROM balances WHERE utilisateur_id = ?");
            $stmt->execute([$withdrawal['utilisateur_id']]);
            $balance = $stmt->fetchColumn();
            
            if ($balance < $withdrawal['montant']) {
                throw new Exception("Solde insuffisant pour ce retrait");
            }
            
            // Mettre à jour le statut du retrait
            $stmt = $this->pdo->prepare("UPDATE retraits SET statut = ? WHERE id = ?");
            $stmt->execute([Database::STATUT_VALIDE, $withdrawal_id]);
            
            // Déduire le montant du solde
            $stmt = $this->pdo->prepare("UPDATE balances SET total = total - ? WHERE utilisateur_id = ?");
            $stmt->execute([$withdrawal['montant'], $withdrawal['utilisateur_id']]);
            
            $this->pdo->commit();
            
            return [
                'success' => true, 
                'message' => "Retrait de " . number_format($withdrawal['montant'], 2) . " € validé pour " . $withdrawal['prenom'] . " " . $withdrawal['nom']
            ];
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    // Rejeter un retrait
    public function rejectWithdrawal($withdrawal_id, $admin_id, $raison = '') {
        if (!$this->isAdmin($admin_id)) {
            return ['success' => false, 'message' => 'Accès non autorisé'];
        }
        
        try {
            $stmt = $this->pdo->prepare("UPDATE retraits SET statut = ? WHERE id = ?");
            $stmt->execute([Database::STATUT_REJETE, $withdrawal_id]);
            
            return ['success' => true, 'message' => 'Retrait rejeté avec succès'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    // === GESTION DES INVESTISSEMENTS ===
    
    // Récupérer tous les investissements avec filtres
    public function getInvestments($filters = []) {
        $where = "WHERE 1=1";
        $params = [];
        
        if (!empty($filters['niveau'])) {
            $where .= " AND i.niveau_id = ?";
            $params[] = $filters['niveau'];
        }
        
        if (!empty($filters['date_debut'])) {
            $where .= " AND DATE(i.date_investissement) >= ?";
            $params[] = $filters['date_debut'];
        }
        
        if (!empty($filters['date_fin'])) {
            $where .= " AND DATE(i.date_investissement) <= ?";
            $params[] = $filters['date_fin'];
        }
        
        if (!empty($filters['search'])) {
            $where .= " AND (u.nom LIKE ? OR u.prenom LIKE ? OR u.telephone LIKE ?)";
            $search = "%" . $filters['search'] . "%";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }
        
        $sql = "SELECT i.*, u.nom, u.prenom, u.telephone, n.investissement as niveau_montant
                FROM investissements i 
                JOIN utilisateurs u ON i.utilisateur_id = u.id 
                JOIN niveaux n ON i.niveau_id = n.niveau
                $where 
                ORDER BY i.date_investissement DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // === STATISTIQUES DU DASHBOARD ===
    
    public function getDashboardStats() {
        try {
            // Total des dépôts validés
            $stmt = $this->pdo->query("SELECT COUNT(*) as total, SUM(montant) as montant_total FROM depots WHERE statut = 'valide'");
            $deposits_stats = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Total des retraits validés
            $stmt = $this->pdo->query("SELECT COUNT(*) as total, SUM(montant) as montant_total FROM retraits WHERE statut = 'valide'");
            $withdrawals_stats = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Total des investissements
            $stmt = $this->pdo->query("SELECT COUNT(*) as total, SUM(montant) as montant_total FROM investissements");
            $investments_stats = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Utilisateurs totaux
            $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM utilisateurs WHERE role = 'user'");
            $users_total = $stmt->fetchColumn();
            
            // Dépôts en attente
            $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM depots WHERE statut = 'en_attente'");
            $pending_deposits = $stmt->fetchColumn();
            
            // Retraits en attente
            $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM retraits WHERE statut = 'en_attente'");
            $pending_withdrawals = $stmt->fetchColumn();
            
            // Dépôts par jour (7 derniers jours)
            $stmt = $this->pdo->query("
                SELECT DATE(date_depot) as date, COUNT(*) as count, SUM(montant) as total
                FROM depots 
                WHERE date_depot >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                GROUP BY DATE(date_depot)
                ORDER BY date
            ");
            $deposits_by_day = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'deposits' => $deposits_stats,
                'withdrawals' => $withdrawals_stats,
                'investments' => $investments_stats,
                'users_total' => $users_total,
                'pending_deposits' => $pending_deposits,
                'pending_withdrawals' => $pending_withdrawals,
                'deposits_by_day' => $deposits_by_day
            ];
            
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
    
    // === GESTION DES UTILISATEURS ===
    
    public function getUsers($filters = []) {
        $where = "WHERE role = 'user'";
        $params = [];
        
        if (!empty($filters['search'])) {
            $where .= " AND (nom LIKE ? OR prenom LIKE ? OR telephone LIKE ?)";
            $search = "%" . $filters['search'] . "%";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }
        
        $sql = "SELECT u.*, 
                       COALESCE(b.total, 0) as solde,
                       COUNT(DISTINCT d.id) as total_deposits,
                       COUNT(DISTINCT r.id) as total_withdrawals,
                       COUNT(DISTINCT i.id) as total_investments
                FROM utilisateurs u 
                LEFT JOIN balances b ON u.id = b.utilisateur_id
                LEFT JOIN depots d ON u.id = d.utilisateur_id
                LEFT JOIN retraits r ON u.id = r.utilisateur_id
                LEFT JOIN investissements i ON u.id = i.utilisateur_id
                $where 
                GROUP BY u.id
                ORDER BY u.date_inscription DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // === EXPORT DES DONNÉES ===
    
    public function exportDeposits($filters = []) {
        $deposits = $this->getDeposits($filters);
        
        $csv = "ID,Utilisateur,Téléphone,Montant,Référence,Statut,Date\n";
        foreach ($deposits as $deposit) {
            $csv .= sprintf(
                "%s,%s %s,%s,%s,%s,%s,%s\n",
                $deposit['id'],
                $deposit['prenom'],
                $deposit['nom'],
                $deposit['telephone'],
                number_format($deposit['montant'], 2),
                $deposit['reference'],
                $deposit['statut'],
                $deposit['date_depot']
            );
        }
        
        return $csv;
    }
    
    public function exportWithdrawals($filters = []) {
        $withdrawals = $this->getWithdrawals($filters);
        
        $csv = "ID,Utilisateur,Téléphone,Montant,Méthode,Statut,Date\n";
        foreach ($withdrawals as $withdrawal) {
            $csv .= sprintf(
                "%s,%s %s,%s,%s,%s,%s,%s\n",
                $withdrawal['id'],
                $withdrawal['prenom'],
                $withdrawal['nom'],
                $withdrawal['telephone'],
                number_format($withdrawal['montant'], 2),
                $withdrawal['methode'],
                $withdrawal['statut'],
                $withdrawal['date_retrait']
            );
        }
        
        return $csv;
    }
    
    public function exportInvestments($filters = []) {
        $investments = $this->getInvestments($filters);
        
        $csv = "ID,Utilisateur,Téléphone,Niveau,Montant,Date\n";
        foreach ($investments as $investment) {
            $csv .= sprintf(
                "%s,%s %s,%s,%s,%s,%s\n",
                $investment['id'],
                $investment['prenom'],
                $investment['nom'],
                $investment['telephone'],
                $investment['niveau_id'],
                number_format($investment['montant'], 2),
                $investment['date_investissement']
            );
        }
        
        return $csv;
    }
}
?>
