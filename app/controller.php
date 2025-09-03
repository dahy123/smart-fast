<?php
date_default_timezone_set('Indian/Antananarivo');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit;
}

require_once __DIR__ . '/../app/database.php';

$pdo = Database::pdo();

if ($pdo) {
    $user_id = $_SESSION['user_id'];

    // Infos utilisateur
    $stmt = $pdo->prepare("SELECT id, nom, prenom, telephone, parrain_id, date_inscription FROM utilisateurs WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $user_fullname = $user['prenom'] . ' ' . $user['nom'];
    $initiale = strtoupper(substr($user['prenom'], 0, 1));
    $user_phone = $user['telephone'];
    $user_creation_date = $user['date_inscription'];

    // Solde
    $stmt = $pdo->prepare("SELECT total FROM balances WHERE utilisateur_id = ?");
    $stmt->execute([$user_id]);
    $balance = $stmt->fetchColumn();

    // Dépôts récents (2 derniers)
    $stmt = $pdo->prepare("SELECT id, montant, preuve_image, statut, date_depot FROM depots WHERE utilisateur_id = ? ORDER BY date_depot DESC LIMIT 3");
    $stmt->execute([$user_id]);
    $depots_recents = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Historique complet
    $stmt = $pdo->prepare("SELECT id, montant,reference, preuve_image, statut, date_depot FROM depots WHERE utilisateur_id = ? ORDER BY date_depot DESC");
    $stmt->execute([$user_id]);
    $depots_hist = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Historique de tous les dépôts validés (tous utilisateurs)
    $stmt = $pdo->query("SELECT d.id, d.montant, d.date_depot, u.nom, u.prenom 
                         FROM depots d 
                         JOIN utilisateurs u ON d.utilisateur_id = u.id
                         WHERE d.statut = 'valide'
                         ORDER BY d.date_depot DESC");
    $depots_valides = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Historique de tous les retraits validés (tous utilisateurs)
    $stmt = $pdo->query("SELECT r.id, r.montant, r.methode, r.date_retrait, u.nom, u.prenom
                     FROM retraits r
                     JOIN utilisateurs u ON r.utilisateur_id = u.id
                     WHERE r.statut = 'valide'
                     ORDER BY r.date_retrait DESC");
    $retraits_valides = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Historique des retraits de l'utilisateur authentifié
    $stmt = $pdo->prepare("SELECT id, montant, methode, preuve_image, statut, date_retrait 
                       FROM retraits 
                       WHERE utilisateur_id = ? 
                       ORDER BY date_retrait DESC");
    $stmt->execute([$user_id]);
    $retraits_hist = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer le niveau actuel de l'utilisateur (niveau 0 par défaut)
    $stmt = $pdo->prepare("SELECT MAX(niveau_id) FROM investissements WHERE utilisateur_id = ?");
    $stmt->execute([$user_id]);
    $niveau_actuel = (int) $stmt->fetchColumn();
    if ($niveau_actuel === 0)
        $niveau_actuel = 0;

    // Récupérer le solde utilisateur (ex: somme des investissements ou autre logique)
    $stmt = $pdo->prepare("SELECT SUM(montant) FROM investissements WHERE utilisateur_id = ?");
    $stmt->execute([$user_id]);
    $solde_user = (float) $stmt->fetchColumn();
    if (!$solde_user)
        $solde_user = 0;

    // Récupérer le prochain niveau pour le calcul de progression
    $stmt = $pdo->prepare("SELECT * FROM niveaux WHERE niveau > ? ORDER BY niveau ASC LIMIT 1");
    $stmt->execute([$niveau_actuel]);
    $prochain_niveau = $stmt->fetch(PDO::FETCH_ASSOC);

    // Calcul de la progression en pourcentage
    $next_price = $prochain_niveau ? (float) $prochain_niveau['investissement'] : 1;
    $progress = min(100, ($solde_user / $next_price) * 100);
    $restant = $prochain_niveau ? max(0, $next_price - $solde_user) : 0;

    // Récupérer les niveaux à parcourir (supérieurs au niveau actuel)
    $stmt = $pdo->prepare("SELECT * FROM niveaux WHERE niveau > ? ORDER BY niveau ASC");
    $stmt->execute([$niveau_actuel]);
    $niveaux_a_parcourir = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer la structure des commissions (niveaux)
    $stmt = $pdo->query("SELECT * FROM niveaux ORDER BY niveau ASC");
    $structure_commissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Définition des avantages dynamiques
    $avantages = [
        1 => ['✓ Accès au tableau de bord', '✓ Dépôts et retraits', '✓ Bonus 100%'],
        2 => ['✓ Toutes fonctions niveau 1', '✓ Parrainage niveau 1', '✓ Bonus 75%'],
        3 => ['✓ Toutes fonctions niveau 2', '✓ Parrainage niveau 2', '✓ Bonus 50%',],
        4 => ['✓ Toutes fonctions niveau 3', '✓ Parrainage niveau 3', '✓ Bonus 25%', '✓ Minage automatique'],
    ];

    // Fusionner chaque niveau avec ses avantages
    // foreach ($structure_commissions as &$niveau) {
    //     $id = (int) $niveau['niveau'];
    //     $niveau['avantages'] = $avantages[$id] ?? [];
    // }
    // Comparaison de ses avantages
    $features = [
        "Parrainage" => [2, 2, 4, 8, 16],         // disponible dès niveau 2
        "Bonus parrainage" => [
            1 => "100%",
            2 => "75%%",
            3 => "50%",
            4 => "25%"
        ],
        "Minage automatique" => [4],
    ];

    // ID de parrainage (ex: SMRT-xxxxx)
    $stmt = $pdo->prepare("SELECT id, parrain_id FROM utilisateurs WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $id_parrainage = '' . str_pad($user['id'], 2, '0', STR_PAD_LEFT);

    // ID du parrain
    $id_parrain = $user['parrain_id'] ? 'SMRT-' . str_pad($user['parrain_id'], 5, '0', STR_PAD_LEFT) : 'Aucun';

    // Filleuls directs
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE parrain_id = ?");
    $stmt->execute([$user_id]);
    $filleuls_directs = (int) $stmt->fetchColumn();

    // Commissions gagnées (exemple sur table investissements ou autre)
    $stmt = $pdo->prepare("SELECT SUM(mise_a_niveau) FROM investissements i JOIN niveaux n ON i.niveau_id = n.id WHERE i.utilisateur_id = ?");
    $stmt->execute([$user_id]);
    $commissions_gagnees = (int) $stmt->fetchColumn();

    // Niveau atteint
    $niveau_atteint = $niveau_actuel;

    // Commissions gagnées via filleuls directs (niveau 1)
    $stmt = $pdo->prepare("
        SELECT id FROM utilisateurs WHERE parrain_id = ?
    ");
    $stmt->execute([$user_id]);
    $filleuls_directs_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Commissions via filleuls directs
    $commissions_directes = 0;
    if ($filleuls_directs_ids) {
        $in = str_repeat('?,', count($filleuls_directs_ids) - 1) . '?';
        $stmt = $pdo->prepare("
            SELECT SUM(n.mise_a_niveau)
            FROM investissements i
            JOIN niveaux n ON i.niveau_id = n.id
            WHERE i.utilisateur_id IN ($in)
        ");
        $stmt->execute($filleuls_directs_ids);
        $commissions_directes = (int) $stmt->fetchColumn();
    }

    // Commissions via filleuls indirects (niveau 2 et plus)
    $commissions_indirectes = 0;
    $ids_niveau_courant = $filleuls_directs_ids;
    $ids_indirects = [];

    while (!empty($ids_niveau_courant)) {
        $in = str_repeat('?,', count($ids_niveau_courant) - 1) . '?';
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE parrain_id IN ($in)");
        $stmt->execute($ids_niveau_courant);
        $ids_niveau_suivant = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (!empty($ids_niveau_suivant)) {
            $ids_indirects = array_merge($ids_indirects, $ids_niveau_suivant);
        }
        $ids_niveau_courant = $ids_niveau_suivant;
    }

    if (!empty($ids_indirects)) {
        $in = str_repeat('?,', count($ids_indirects) - 1) . '?';
        $stmt = $pdo->prepare("
            SELECT SUM(n.mise_a_niveau)
            FROM investissements i
            JOIN niveaux n ON i.niveau_id = n.id
            WHERE i.utilisateur_id IN ($in)
        ");
        $stmt->execute($ids_indirects);
        $commissions_indirectes = (int) $stmt->fetchColumn();
    }

    // Commission totale (directs + indirects)
    $commissions_gagnees = $commissions_directes + $commissions_indirectes;

    // Lien de parrainage
    $lien_parrainage = "https://smart-mg.is-best.net/src/auth.php";
    $lien_admin = "https://smart-mg.is-best.net/src/admin/dashboard.php";
    $lien_moderator = "https://smart-mg.is-best.net/src/admin/depot.php";

    // Arbre de parrainage
    function getReferralTree($pdo, $user_id, $niveau = 1, $max_niveau = 5)
    {
        if ($niveau > $max_niveau)
            return [];
        $stmt = $pdo->prepare("SELECT id, nom, prenom FROM utilisateurs WHERE parrain_id = ?");
        $stmt->execute([$user_id]);
        $filleuls = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($filleuls as &$filleul) {
            $filleul['children'] = getReferralTree($pdo, $filleul['id'], $niveau + 1, $max_niveau);
        }
        return $filleuls;
    }

    // Exemple d'utilisation (max_niveau = 5, tu peux augmenter si besoin)
    $referral_tree = getReferralTree($pdo, $user_id, 1, 5);

    // Nombre total d'utilisateurs
    $stmt = $pdo->query("SELECT COUNT(*) FROM utilisateurs");
    $total_users = (int) $stmt->fetchColumn();

    // Total des dépôts validés
    $stmt = $pdo->query("SELECT SUM(montant) FROM depots WHERE statut = 'valide'");
    $total_depots = (int) $stmt->fetchColumn();

    // Total des retraits validés
    $stmt = $pdo->query("SELECT SUM(montant) FROM retraits WHERE statut = 'valide'");
    $total_retraits = (int) $stmt->fetchColumn();

    // Total des investissements
    $stmt = $pdo->query("SELECT SUM(montant) FROM investissements");
    $total_investissements = (int) $stmt->fetchColumn();

    // Historique de tous les réinvestissements validés (tous utilisateurs)
    $stmt = $pdo->query("SELECT i.id, i.montant, i.date_investissement, u.nom, u.prenom, 'Réinvestissement' AS methode
                     FROM investissements i
                     JOIN utilisateurs u ON i.utilisateur_id = u.id
                     ORDER BY i.date_investissement DESC");
    $reinvestissements_valides = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Niveau actuel de l'utilisateur
    $stmt = $pdo->prepare("SELECT MAX(niveau_id) FROM investissements WHERE utilisateur_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $niveau_actuel = (int) $stmt->fetchColumn();

    // Niveau suivant
    $stmt = $pdo->prepare("SELECT mise_a_niveau FROM niveaux WHERE niveau = ?");
    $stmt->execute([$niveau_actuel + 1]);
    $mise_a_niveau = (int) $stmt->fetchColumn() ?: 0; // 0 si non trouvé


    function getFinanceStats($pdo)
    {
        // Exemple : total par jour pour les 7 derniers jours
        $labels = [];
        $depots = [];
        $retraits = [];
        $investissements = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $labels[] = date('D', strtotime($date));

            // Dépôts validés
            $stmt = $pdo->prepare("SELECT SUM(montant) FROM depots WHERE DATE(date_depot)=? AND statut='valide'");
            $stmt->execute([$date]);
            $depots[] = (int) ($stmt->fetchColumn() ?: 0);

            // Retraits validés
            $stmt = $pdo->prepare("SELECT SUM(montant) FROM retraits WHERE DATE(date_retrait)=? AND statut='valide'");
            $stmt->execute([$date]);
            $retraits[] = (int) ($stmt->fetchColumn() ?: 0);

            // Investissements validés (adaptez selon votre table)
            $stmt = $pdo->prepare("SELECT SUM(montant) FROM investissements WHERE DATE(date_investissement)=?");
            $stmt->execute([$date]);
            $investissements[] = (int) ($stmt->fetchColumn() ?: 0);
        }
        return [
            'labels' => $labels,
            'depots' => $depots,
            'retraits' => $retraits,
            'investissements' => $investissements,
        ];
    }

    function getUsersStats($pdo)
    {
        $labels = [];
        $usersData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $labels[] = date('D', strtotime($date));
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE DATE(date_inscription)=?");
            $stmt->execute([$date]);
            $usersData[] = (int) ($stmt->fetchColumn() ?: 0);
        }
        return [
            'labels' => $labels,
            'data' => $usersData
        ];
    }

    // Après avoir récupéré $niveau_actuel et $filleuls_directs
    $stmt = $pdo->prepare("SELECT mise_a_niveau FROM niveaux WHERE id = ?");
    $stmt->execute([$niveau_actuel]);
    $commission_par_parrainage = (int) $stmt->fetchColumn();

    $commission_totale_directs = $commission_par_parrainage * $filleuls_directs;

    // Compte récursivement tous les filleuls (directs et sous-filleuls)
    function countAllFilleuls($pdo, $user_id)
    {
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE parrain_id = ?");
        $stmt->execute([$user_id]);
        $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $count = count($ids);
        foreach ($ids as $id) {
            $count += countAllFilleuls($pdo, $id);
        }
        return $count;
    }

    // Utilisation :
    $filleuls_totaux = countAllFilleuls($pdo, $user_id);

    function getAllAffiliations($pdo, $parrain_id, $niveau = 1)
    {
        $stmt = $pdo->prepare("
        SELECT u.id, u.nom, u.prenom,u.telephone,u.parrain_id,
            IFNULL(n.niveau, '-') AS niveau_investissement
        FROM utilisateurs u
        LEFT JOIN (
            SELECT utilisateur_id, MAX(niveau_id) AS niveau_id
            FROM investissements
            GROUP BY utilisateur_id
        ) AS inv ON u.id = inv.utilisateur_id
        LEFT JOIN niveaux n ON inv.niveau_id = n.id
        WHERE u.parrain_id = ?
    ");
        $stmt->execute([$parrain_id]);
        $filleuls = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $resultats = [];

        foreach ($filleuls as $filleul) {
            $filleul['niveau_affiliation'] = $niveau;
            $resultats[] = $filleul;

            // Récursion : récupérer les enfants du filleul
            $descendants = getAllAffiliations($pdo, $filleul['id'], $niveau + 1);
            $resultats = array_merge($resultats, $descendants);
        }

        return $resultats;
    }

    // Utilisation : récupère tout l'arbre de filleuls
    $affiliations = getAllAffiliations($pdo, $user_id);
} else {
    echo "Erreur de connexion à la base de données.";
}

function getAllInvestissements($pdo)
{
    $stmt = $pdo->query("
        SELECT i.id, i.utilisateur_id, u.nom, u.prenom, i.niveau_id, n.niveau, i.montant, i.date_investissement
        FROM investissements i
        JOIN utilisateurs u ON i.utilisateur_id = u.id
        JOIN niveaux n ON i.niveau_id = n.id
        ORDER BY i.date_investissement DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Exemple de construction d'un historique global
$sql = "
    SELECT 'Dépôt' as type, d.montant, d.date_depot as date, u.nom, u.prenom
    FROM depots d
    INNER JOIN utilisateurs u ON d.utilisateur_id = u.id

    UNION ALL

    SELECT 'Retrait' as type, r.montant, r.date_retrait as date, u.nom, u.prenom
    FROM retraits r
    INNER JOIN utilisateurs u ON r.utilisateur_id = u.id

    UNION ALL

    SELECT 'Investissement' as type, i.montant, i.date_investissement as date, u.nom, u.prenom
    FROM investissements i
    INNER JOIN utilisateurs u ON i.utilisateur_id = u.id

    ORDER BY date DESC
    LIMIT 7
";
$stmt = $pdo->query($sql);
$historique_global = $stmt->fetchAll(PDO::FETCH_ASSOC);
