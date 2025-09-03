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

// Récupérer les statistiques du dashboard
$stats = $adminController->getDashboardStats();

// Récupérer les données avec filtres
$filters = [
    'statut' => $_GET['statut'] ?? '',
    'date_debut' => $_GET['date_debut'] ?? '',
    'date_fin' => $_GET['date_fin'] ?? '',
    'search' => $_GET['search'] ?? '',
    'niveau' => $_GET['niveau'] ?? ''
];

$deposits = $adminController->getDeposits($filters);
$withdrawals = $adminController->getWithdrawals($filters);
$investments = $adminController->getInvestments($filters);
$users = $adminController->getUsers($filters);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - SMART-FAST</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#16a34a',
                        'primary-dark': '#15803d',
                        'primary-light': '#22c55e'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- Header Admin -->
    <header class="bg-gray-900 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-primary">SMART-FAST ADMIN</h1>
                    <nav class="ml-4 md:ml-10 flex space-x-6 md:space-x-8 overflow-x-auto whitespace-nowrap">
                        <a href="#" onclick="showAdminPage('dashboard')" class="nav-link text-primary border-b-2 border-primary px-1 pb-4 text-sm font-medium">Dashboard</a>
                        <a href="#" onclick="showAdminPage('deposits')" class="nav-link text-gray-300 hover:text-white px-1 pb-4 text-sm font-medium">Dépôts</a>
                        <a href="#" onclick="showAdminPage('withdrawals')" class="nav-link text-gray-300 hover:text-white px-1 pb-4 text-sm font-medium">Retraits</a>
                        <a href="#" onclick="showAdminPage('investments')" class="nav-link text-gray-300 hover:text-white px-1 pb-4 text-sm font-medium">Investissements</a>
                        <a href="#" onclick="showAdminPage('users')" class="nav-link text-gray-300 hover:text-white px-1 pb-4 text-sm font-medium">Utilisateurs</a>
                    </nav>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-300">Administrateur</span>
                    <a href="app.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                        <i class="bi bi-arrow-left mr-1"></i>Retour App
                    </a>
                    <a href="logout.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                        <i class="bi bi-box-arrow-right mr-1"></i>Déconnexion
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Alerts -->
    <div id="alerts" class="fixed top-2 right-4 z-50 w-72"></div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Dashboard Page -->
        <div id="dashboard-page" class="admin-page">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Tableau de Bord</h2>
                <p class="text-gray-600">Vue d'ensemble de la plateforme SMART-FAST</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="bi bi-cash-coin text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Dépôts</p>
                            <p class="text-2xl font-semibold text-gray-900">
                                <?php echo number_format($stats['deposits']['montant_total'] ?? 0, 2); ?> Ar
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 text-red-600">
                            <i class="bi bi-arrow-up-circle text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Retraits</p>
                            <p class="text-2xl font-semibold text-gray-900">
                                <?php echo number_format($stats['withdrawals']['montant_total'] ?? 0, 2); ?> Ar
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <i class="bi bi-graph-up text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Investissements</p>
                            <p class="text-2xl font-semibold text-gray-900">
                                <?php echo number_format($stats['investments']['montant_total'] ?? 0, 2); ?> Ar
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <i class="bi bi-people text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Utilisateurs</p>
                            <p class="text-2xl font-semibold text-gray-900">
                                <?php echo number_format($stats['users_total'] ?? 0); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions en Attente</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                            <span class="text-sm text-gray-600">Dépôts en attente</span>
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                <?php echo $stats['pending_deposits'] ?? 0; ?>
                            </span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                            <span class="text-sm text-gray-600">Retraits en attente</span>
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                <?php echo $stats['pending_withdrawals'] ?? 0; ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Graphique des Dépôts</h3>
                    <canvas id="depositsChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Deposits Page -->
        <div id="deposits-page" class="admin-page hidden">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Gestion des Dépôts</h2>
                <p class="text-gray-600">Validez ou rejetez les demandes de dépôt des utilisateurs</p>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <form class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="text" name="search" placeholder="Rechercher..." value="<?php echo htmlspecialchars($filters['search']); ?>" class="border border-gray-300 rounded-lg px-3 py-2">
                    <select name="statut" class="border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente" <?php echo $filters['statut'] === 'en_attente' ? 'selected' : ''; ?>>En attente</option>
                        <option value="valide" <?php echo $filters['statut'] === 'valide' ? 'selected' : ''; ?>>Validé</option>
                        <option value="rejete" <?php echo $filters['statut'] === 'rejete' ? 'selected' : ''; ?>>Rejeté</option>
                    </select>
                    <input type="date" name="date_debut" value="<?php echo $filters['date_debut']; ?>" class="border border-gray-300 rounded-lg px-3 py-2">
                    <input type="date" name="date_fin" value="<?php echo $filters['date_fin']; ?>" class="border border-gray-300 rounded-lg px-3 py-2">
                    <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg">
                        <i class="bi bi-search mr-2"></i>Filtrer
                    </button>
                </form>
            </div>

            <!-- Deposits Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Liste des Dépôts</h3>
                        <button onclick="exportDeposits()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                            <i class="bi bi-download mr-2"></i>Exporter CSV
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Référence</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preuve</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($deposits as $deposit): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($deposit['prenom'] . ' ' . $deposit['nom']); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($deposit['telephone']); ?></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?php echo number_format($deposit['montant'], 2); ?> Ar
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">
                                    <?php echo htmlspecialchars($deposit['reference']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                        $imgPath = $deposit['preuve_image'] ?? '';
                                        if ($imgPath) {
                                            $src = (preg_match('/^(https?:\\/\\/|\\/)/', $imgPath)) ? $imgPath : '../assets/uploads/' . $imgPath;
                                            echo '<a href="' . htmlspecialchars($src) . '" target="_blank" class="inline-block">'
                                                . '<img src="' . htmlspecialchars($src) . '" alt="Preuve" class="h-10 w-10 object-cover rounded border" />'
                                                . '</a>';
                                        } else {
                                            echo '<span class="text-gray-400 text-sm">-</span>';
                                        }
                                    ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?php echo $deposit['statut'] === 'valide' ? 'bg-green-100 text-green-800' : 
                                              ($deposit['statut'] === 'rejete' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                        <?php echo ucfirst($deposit['statut']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">
                                    <?php echo date('d/m/Y H:i', strtotime($deposit['date_depot'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <?php if ($deposit['statut'] === 'en_attente'): ?>
                                    <button onclick="validateDeposit(<?php echo $deposit['id']; ?>)" class="text-green-600 hover:text-green-900 mr-3 text-sm md:text-base">
                                        <i class="bi bi-check-circle"></i> Valider
                                    </button>
                                    <button onclick="rejectDeposit(<?php echo $deposit['id']; ?>)" class="text-red-600 hover:text-red-900 text-sm md:text-base">
                                        <i class="bi bi-x-circle"></i> Rejeter
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Withdrawals Page -->
        <div id="withdrawals-page" class="admin-page hidden">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Gestion des Retraits</h2>
                <p class="text-gray-600">Validez ou rejetez les demandes de retrait des utilisateurs</p>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <form class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="text" name="search" placeholder="Rechercher..." value="<?php echo htmlspecialchars($filters['search']); ?>" class="border border-gray-300 rounded-lg px-3 py-2">
                    <select name="statut" class="border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente" <?php echo $filters['statut'] === 'en_attente' ? 'selected' : ''; ?>>En attente</option>
                        <option value="valide" <?php echo $filters['statut'] === 'valide' ? 'selected' : ''; ?>>Validé</option>
                        <option value="rejete" <?php echo $filters['statut'] === 'rejete' ? 'selected' : ''; ?>>Rejeté</option>
                    </select>
                    <input type="date" name="date_debut" value="<?php echo $filters['date_debut']; ?>" class="border border-gray-300 rounded-lg px-3 py-2">
                    <input type="date" name="date_fin" value="<?php echo $filters['date_fin']; ?>" class="border border-gray-300 rounded-lg px-3 py-2">
                    <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg">
                        <i class="bi bi-search mr-2"></i>Filtrer
                    </button>
                </form>
            </div>

            <!-- Withdrawals Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Liste des Retraits</h3>
                        <button onclick="exportWithdrawals()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                            <i class="bi bi-download mr-2"></i>Exporter CSV
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Méthode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($withdrawals as $withdrawal): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($withdrawal['prenom'] . ' ' . $withdrawal['nom']); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($withdrawal['telephone']); ?></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?php echo number_format($withdrawal['montant'], 2); ?> Ar
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">
                                    <?php echo htmlspecialchars($withdrawal['methode']); ?>
                                </td>
                               
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?php echo $withdrawal['statut'] === 'valide' ? 'bg-green-100 text-green-800' : 
                                              ($withdrawal['statut'] === 'rejete' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                        <?php echo ucfirst($withdrawal['statut']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">
                                    <?php echo date('d/m/Y H:i', strtotime($withdrawal['date_retrait'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <?php if ($withdrawal['statut'] === 'en_attente'): ?>
                                    <button onclick="validateWithdrawal(<?php echo $withdrawal['id']; ?>)" class="text-green-600 hover:text-green-900 mr-3 text-sm md:text-base">
                                        <i class="bi bi-check-circle"></i> Valider
                                    </button>
                                    <button onclick="rejectWithdrawal(<?php echo $withdrawal['id']; ?>)" class="text-red-600 hover:text-red-900 text-sm md:text-base">
                                        <i class="bi bi-x-circle"></i> Rejeter
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Investments Page -->
        <div id="investments-page" class="admin-page hidden">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Historique des Investissements</h2>
                <p class="text-gray-600">Suivez tous les investissements des utilisateurs</p>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <form class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="text" name="search" placeholder="Rechercher..." value="<?php echo htmlspecialchars($filters['search']); ?>" class="border border-gray-300 rounded-lg px-3 py-2">
                    <select name="niveau" class="border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Tous les niveaux</option>
                        <option value="1" <?php echo $filters['niveau'] === '1' ? 'selected' : ''; ?>>Niveau 1</option>
                        <option value="2" <?php echo $filters['niveau'] === '2' ? 'selected' : ''; ?>>Niveau 2</option>
                        <option value="3" <?php echo $filters['niveau'] === '3' ? 'selected' : ''; ?>>Niveau 3</option>
                    </select>
                    <input type="date" name="date_debut" value="<?php echo $filters['date_debut']; ?>" class="border border-gray-300 rounded-lg px-3 py-2">
                    <input type="date" name="date_fin" value="<?php echo $filters['date_fin']; ?>" class="border border-gray-300 rounded-lg px-3 py-2">
                    <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg">
                        <i class="bi bi-search mr-2"></i>Filtrer
                    </button>
                </form>
            </div>

            <!-- Investments Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Liste des Investissements</h3>
                        <button onclick="exportInvestments()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                            <i class="bi bi-download mr-2"></i>Exporter CSV
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Niveau</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($investments as $investment): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($investment['prenom'] . ' ' . $investment['nom']); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($investment['telephone']); ?></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Niveau <?php echo $investment['niveau_id']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?php echo number_format($investment['montant'], 2); ?> Ar
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo date('d/m/Y H:i', strtotime($investment['date_investissement'])); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Users Page -->
        <div id="users-page" class="admin-page hidden">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Gestion des Utilisateurs</h2>
                <p class="text-gray-600">Consultez les informations et activités des utilisateurs</p>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <form class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="search" placeholder="Rechercher par nom, prénom ou téléphone..." value="<?php echo htmlspecialchars($filters['search']); ?>" class="border border-gray-300 rounded-lg px-3 py-2">
                    <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg">
                        <i class="bi bi-search mr-2"></i>Rechercher
                    </button>
                </form>
            </div>

            <!-- Users Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Liste des Utilisateurs</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Solde</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dépôts</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Retraits</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Investissements</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date d'inscription</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($user['telephone']); ?></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?php echo number_format($user['solde'], 2); ?> Ar
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo $user['total_deposits']; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo $user['total_withdrawals']; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo $user['total_investments']; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo date('d/m/Y', strtotime($user['date_inscription'])); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Navigation entre les pages admin
        function showAdminPage(pageName) {
            // Masquer toutes les pages
            document.querySelectorAll('.admin-page').forEach(page => {
                page.classList.add('hidden');
            });
            
            // Afficher la page sélectionnée
            document.getElementById(pageName + '-page').classList.remove('hidden');
            
            // Mettre à jour la navigation active
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('text-primary', 'border-b-2', 'border-primary');
                link.classList.add('text-gray-300', 'hover:text-white');
            });
            
            event.target.classList.remove('text-gray-300', 'hover:text-white');
            event.target.classList.add('text-primary', 'border-b-2', 'border-primary');
        }

        // Fonctions pour valider/rejeter les dépôts et retraits
        function validateDeposit(depositId) {
            if (confirm('Êtes-vous sûr de vouloir valider ce dépôt ?')) {
                // Appel AJAX pour valider le dépôt
                fetch('admin_actions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=validate_deposit&deposit_id=' + depositId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showAlert('error', data.message);
                    }
                });
            }
        }

        function rejectDeposit(depositId) {
            if (confirm('Êtes-vous sûr de vouloir rejeter ce dépôt ?')) {
                fetch('admin_actions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=reject_deposit&deposit_id=' + depositId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showAlert('error', data.message);
                    }
                });
            }
        }

        function validateWithdrawal(withdrawalId) {
            if (confirm('Êtes-vous sûr de vouloir valider ce retrait ?')) {
                fetch('admin_actions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=validate_withdrawal&withdrawal_id=' + withdrawalId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showAlert('error', data.message);
                    }
                });
            }
        }

        function rejectWithdrawal(withdrawalId) {
            if (confirm('Êtes-vous sûr de vouloir rejeter ce retrait ?')) {
                fetch('admin_actions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=reject_withdrawal&withdrawal_id=' + withdrawalId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showAlert('error', data.message);
                    }
                });
            }
        }

        // Fonctions d'export
        function exportDeposits() {
            window.location.href = 'admin_export.php?type=deposits&' + new URLSearchParams(window.location.search);
        }

        function exportWithdrawals() {
            window.location.href = 'admin_export.php?type=withdrawals&' + new URLSearchParams(window.location.search);
        }

        function exportInvestments() {
            window.location.href = 'admin_export.php?type=investments&' + new URLSearchParams(window.location.search);
        }

        // Affichage des alertes
        function showAlert(type, message) {
            const alertsContainer = document.getElementById('alerts');
            const alertId = 'alert' + Math.random().toString(36).substr(2, 9);
            
            const alertHtml = `
                <div id="${alertId}" class="relative border-l-4 ${type === 'success' ? 'border-green-500 bg-green-100 text-green-800' : 'border-red-500 bg-red-100 text-red-800'} px-4 py-3 my-2 rounded shadow-md" role="alert">
                    <span>${message}</span>
                    <button onclick="document.getElementById('${alertId}').remove()" class="absolute top-0 right-0 p-2">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            `;
            
            alertsContainer.insertAdjacentHTML('beforeend', alertHtml);
            
            // Auto-remove après 5 secondes
            setTimeout(() => {
                const alert = document.getElementById(alertId);
                if (alert) alert.remove();
            }, 5000);
        }

        // Graphique des dépôts
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('depositsChart');
            if (ctx) {
                const depositsData = <?php echo json_encode($stats['deposits_by_day'] ?? []); ?>;
                
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: depositsData.map(item => item.date),
                        datasets: [{
                            label: 'Dépôts (Ar)',
                            data: depositsData.map(item => parseFloat(item.total) || 0),
                            borderColor: '#16a34a',
                            backgroundColor: 'rgba(22, 163, 74, 0.1)',
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
