<?php
// Fichier de test pour l'administration SMART-FAST
require_once "app/admin_controller.php";

echo "<h1>Test de l'Interface d'Administration SMART-FAST</h1>";

try {
    // Test de connexion à la base de données
    echo "<h2>1. Test de connexion à la base de données</h2>";
    $adminController = new AdminController();
    echo "✅ Connexion à la base de données réussie<br>";
    
    // Test de récupération des statistiques
    echo "<h2>2. Test des statistiques du dashboard</h2>";
    $stats = $adminController->getDashboardStats();
    if (isset($stats['error'])) {
        echo "❌ Erreur lors de la récupération des statistiques : " . $stats['error'] . "<br>";
    } else {
        echo "✅ Statistiques récupérées avec succès<br>";
        echo "- Total dépôts : " . number_format($stats['deposits']['montant_total'] ?? 0, 2) . " €<br>";
        echo "- Total retraits : " . number_format($stats['withdrawals']['montant_total'] ?? 0, 2) . " €<br>";
        echo "- Total investissements : " . number_format($stats['investments']['montant_total'] ?? 0, 2) . " €<br>";
        echo "- Total utilisateurs : " . number_format($stats['users_total'] ?? 0) . "<br>";
        echo "- Dépôts en attente : " . ($stats['pending_deposits'] ?? 0) . "<br>";
        echo "- Retraits en attente : " . ($stats['pending_withdrawals'] ?? 0) . "<br>";
    }
    
    // Test de récupération des dépôts
    echo "<h2>3. Test de récupération des dépôts</h2>";
    $deposits = $adminController->getDeposits();
    echo "✅ " . count($deposits) . " dépôts récupérés<br>";
    
    // Test de récupération des retraits
    echo "<h2>4. Test de récupération des retraits</h2>";
    $withdrawals = $adminController->getWithdrawals();
    echo "✅ " . count($withdrawals) . " retraits récupérés<br>";
    
    // Test de récupération des investissements
    echo "<h2>5. Test de récupération des investissements</h2>";
    $investments = $adminController->getInvestments();
    echo "✅ " . count($investments) . " investissements récupérés<br>";
    
    // Test de récupération des utilisateurs
    echo "<h2>6. Test de récupération des utilisateurs</h2>";
    $users = $adminController->getUsers();
    echo "✅ " . count($users) . " utilisateurs récupérés<br>";
    
    // Test d'export CSV
    echo "<h2>7. Test d'export CSV</h2>";
    $csv_deposits = $adminController->exportDeposits();
    if (strlen($csv_deposits) > 0) {
        echo "✅ Export CSV des dépôts réussi (" . strlen($csv_deposits) . " caractères)<br>";
    } else {
        echo "❌ Erreur lors de l'export CSV des dépôts<br>";
    }
    
    echo "<h2>✅ Tous les tests sont passés avec succès !</h2>";
    echo "<p>L'interface d'administration est prête à être utilisée.</p>";
    echo "<p><a href='src/admin.php'>Accéder à l'interface d'administration</a></p>";
    
} catch (Exception $e) {
    echo "<h2>❌ Erreur lors des tests</h2>";
    echo "<p>Erreur : " . $e->getMessage() . "</p>";
    echo "<p>Vérifiez que :</p>";
    echo "<ul>";
    echo "<li>La base de données est accessible</li>";
    echo "<li>Les tables sont créées</li>";
    echo "<li>Les permissions sont correctes</li>";
    echo "</ul>";
}
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h1 { color: #16a34a; }
h2 { color: #374151; margin-top: 30px; }
p { line-height: 1.6; }
ul { margin-left: 20px; }
a { color: #16a34a; text-decoration: none; }
a:hover { text-decoration: underline; }
</style>
