<?php
require_once "../app/controller.php";

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMART-FAST - Plateforme d'Investissement</title>
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

    <!-- Alert -->
    <div id="alerts" class="fixed top-2 right-4 z-50 w-72">
        <div id="alertSuccess"
            class="hidden relative border-l-4 border-green-500 bg-green-100 text-green-800 px-4 py-3 my-2 rounded shadow-md"
            role="alert">
            <span id="alertSuccessMessage">Opération réussie !</span>
        </div>

        <div id="alertError"
            class="hidden relative border-l-4 border-red-500 bg-red-100 text-red-800 px-4 py-3 my-2 rounded shadow-md"
            role="alert">
            <span id="alertErrorMessage">Erreur inconnue.</span>
        </div>
    </div>


    <!-- Header Desktop -->
    <header class="bg-white shadow-sm border-b border-gray-200 hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-primary">SMART-FAST</h1>
                    </div>
                    <nav class="ml-10 flex space-x-8">
                        <a href="#" onclick="showPage('dashboard')"
                            class="nav-link text-primary border-b-2 border-primary px-1 pb-4 text-sm font-medium">Tableau
                            de bord</a>
                        <a href="#" onclick="showPage('deposit')"
                            class="nav-link text-gray-500 hover:text-gray-700 px-1 pb-4 text-sm font-medium">Dépôt</a>
                        <a href="#" onclick="showPage('withdraw')"
                            class="nav-link text-gray-500 hover:text-gray-700 px-1 pb-4 text-sm font-medium">Retrait</a>
                        <a href="#" onclick="showPage('referral')"
                            class="nav-link text-gray-500 hover:text-gray-700 px-1 pb-4 text-sm font-medium">Parrainage</a>
                        <a href="#" onclick="showPage('levels')"
                            class="nav-link text-gray-500 hover:text-gray-700 px-1 pb-4 text-sm font-medium">Niveaux</a>
                    </nav>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-700">Bienvenue,
                        <strong><?php echo htmlspecialchars($user_fullname); ?></strong></span>

                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="admin.php"
                            class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm inline-flex items-center">
                            <i class="bi bi-gear mr-1"></i>Administration
                        </a>
                    <?php endif; ?>
                    <a href="logout.php"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm inline-flex items-center">
                        <i class="bi bi-box-arrow-right mr-1"></i>Déconnexion
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Header Mobile -->
    <header class="bg-white shadow-sm border-b border-gray-200 md:hidden">
        <div class="px-4 py-3 flex justify-between items-center">
            <h1 class="text-xl font-bold text-primary">SMART-FAST</h1>

            <!-- User profile & dropdown -->
            <div class="relative inline-block text-left flex items-center space-x-2">
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <a href="admin.php"
                        class="bg-gray-800 hover:bg-gray-900 text-white px-2 py-1 rounded-full text-sm inline-flex items-center">
                        <i class="bi bi-gear text-2xl"></i>
                    </a>
                <?php endif; ?>
                <button id="userDropdownBtn" class="flex items-center focus:outline-none">
                    <div
                        class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold mr-2">
                        <?php echo $initiale; ?>
                    </div>
                    <span class="text-gray-700 font-medium">
                        <?php echo htmlspecialchars($user_fullname); ?>
                    </span>
                    <i class="bi bi-chevron-down ml-1 text-gray-500"></i>
                </button>

                <div id="userDropdown"
                    class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">

                    <div class="py-1">
                        <form action="logout.php" method="POST">
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100 flex items-center">
                                <i class="bi bi-box-arrow-right mr-2"></i>Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- PAGE DASHBOARD -->
    <div id="dashboard-page" class="page-content">
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-20 md:pb-6">

            <!-- Solde Principal -->
            <div class="bg-gradient-to-r from-primary to-primary-light rounded-xl p-6 text-white mb-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm">Solde général</p>
                        <p class="text-3xl font-bold"><?= number_format($balance ?? 0, 0, '', ' ') ?> Ar</p>
                        <p class="text-green-100 text-sm mt-1">Dernière mise à jour: il y a 2 min</p>
                    </div>
                    <div class="text-right">
                        <div class="bg-white bg-opacity-20 rounded-lg p-3">
                            <i class="bi bi-wallet2 text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions Rapides -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <button onclick="showPage('deposit')"
                    class="bg-primary hover:bg-primary-dark text-white p-4 rounded-xl shadow-md transition-all duration-200 transform hover:scale-105">
                    <i class="bi bi-plus-circle text-2xl mb-2 block"></i>
                    <span class="font-semibold">Dépôt</span>
                </button>
                <button onclick="showPage('withdraw')"
                    class="bg-orange-500 hover:bg-orange-600 text-white p-4 rounded-xl shadow-md transition-all duration-200 transform hover:scale-105">
                    <i class="bi bi-dash-circle text-2xl mb-2 block"></i>
                    <span class="font-semibold">Retrait</span>
                </button>
            </div>

            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <i class="bi bi-arrow-down-circle text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Total Dépôts</p>
                            <p class="text-2xl font-bold text-gray-900">
                                <?= number_format($total_depots ?? 0, 0, '', ' ') ?> Ar
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <div class="flex items-center">
                        <div class="bg-orange-100 p-3 rounded-lg">
                            <i class="bi bi-arrow-up-circle text-orange-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Total Retraits</p>
                            <p class="text-2xl font-bold text-gray-900">
                                <?= number_format($total_retraits ?? 0, 0, '', ' ') ?> Ar
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-lg">
                            <i class="bi bi-graph-up text-primary text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Investissements</p>
                            <p class="text-2xl font-bold text-gray-900">
                                <?= number_format($total_investissements ?? 0, 0, '', ' ') ?> Ar
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Niveau Actuel -->
            <div class="bg-white rounded-xl p-6 shadow-md mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Votre Niveau</h3>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-primary p-3 rounded-full">
                            <i class="bi bi-star-fill text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="font-semibold text-gray-900">Niveau <?= $niveau_actuel  ?></p>
                            <p class="text-sm text-gray-600">
                                Débloquer le niveau <?= $niveau_actuel + 1 ?> avec
                                <?= number_format($mise_a_niveau ?? 0, 0, '', ' ') ?> Ar
                            </p>
                        </div>
                    </div>
                    <button onclick="showPage('levels')"
                        class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Voir niveaux
                    </button>
                </div>
                <div class="mt-4">
                    <?php
                    $progress = $balance && $mise_a_niveau ? min(($balance / $mise_a_niveau) * 100, 100) : 0;
                    ?>
                    <div class="bg-gray-200 rounded-full h-2">
                        <div class="bg-primary h-2 rounded-full" style="width: <?= $progress ?>%"></div>
                    </div>
                    <p class="text-xs text-gray-600 mt-1"><?= number_format($balance ?? 0, 0, '', ' ') ?> /
                        <?= number_format($mise_a_niveau ?? 0, 0, '', ' ') ?> Ar pour le niveau suivant
                    </p>
                </div>
            </div>

            <!-- Historique Récent -->
            <div class="bg-white rounded-xl p-4 shadow-md">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="text-lg font-semibold text-gray-900">Historique récent</h3>
                    <a href="historique.php" class="text-primary hover:text-primary-dark text-sm font-medium">Voir
                        tout</a>
                </div>

                <div class="space-y-3">
                    <?php if (!empty($historique_global)): ?>
                        <?php foreach ($historique_global as $histo): ?>
                            <?php
                            switch ($histo['type']) {
                                case 'Dépôt':
                                    $bg = 'bg-green-100';
                                    $icon = 'bi-plus-circle text-green-600';
                                    $sign = '+';
                                    break;
                                case 'Retrait':
                                    $bg = 'bg-red-100';
                                    $icon = 'bi-dash-circle text-red-600';
                                    $sign = '-';
                                    break;
                                case 'Investissement':
                                    $bg = 'bg-blue-100';
                                    $icon = 'bi-graph-up-arrow text-blue-600';
                                    $sign = '-';
                                    break;
                                default:
                                    $bg = 'bg-gray-100';
                                    $icon = 'bi-clock text-gray-600';
                                    $sign = '';
                            }
                            ?>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg flex-wrap">
                                <div class="flex items-center space-x-3 flex-1 min-w-0">
                                    <div class="<?= $bg ?> p-2 rounded-lg flex-shrink-0">
                                        <i class="bi <?= $icon ?>"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-900 truncate">
                                            <?= $histo['type'] ?>
                                            <span class="text-sm text-gray-500">
                                                (<?= htmlspecialchars($histo['prenom'] . ' ' . $histo['nom']) ?>)
                                            </span>
                                        </p>
                                        <p class="text-sm text-gray-600 truncate">
                                            <?= date('d M, H:i', strtotime($histo['date'])) ?>
                                        </p>
                                    </div>
                                </div>
                                <span class="font-semibold text-gray-900 ml-3 flex-shrink-0">
                                    <?= $sign ?>         <?= number_format($histo['montant'], 0, '', ' ') ?> Ar
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-sm text-gray-500">Aucune activité récente.</p>
                    <?php endif; ?>
                </div>
            </div>

        </main>
    </div>


    <!-- PAGE DEPOT -->
    <div id="deposit-page" class="page-content hidden">
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-20 md:pb-6">
            <div class="bg-white rounded-xl p-6 shadow-md mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Effectuer un dépôt</h2>

                <!-- Solde actuel -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Solde actuel:</span>
                        <span class="text-xl font-bold text-primary"><?= $balance ?> Ar</span>
                    </div>
                </div>

                <!-- Informations de dépôt -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6 border border-blue-200">
                    <h3 class="font-semibold text-blue-900 mb-3 flex items-center">
                        <i class="bi bi-info-circle mr-2"></i>Informations de dépôt
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-blue-700 font-medium">Nom du compte:</p>
                            <p class="text-blue-900 font-bold">ZAFINDRAVO</p>
                        </div>
                        <div>
                            <p class="text-sm text-blue-700 font-medium">Numéro de compte:</p>
                            <p class="text-blue-900 font-bold">032 93 751 54</p>
                        </div>
                    </div>
                    <div class="mt-3 p-3 bg-white rounded border border-blue-200">
                        <p class="text-xs text-blue-600">
                            <i class="bi bi-exclamation-triangle mr-1"></i>
                            Effectuez votre dépôt vers ce numéro puis remplissez le formulaire ci-dessous pour
                            confirmation
                        </p>
                    </div>
                </div>
                <!-- Dépôt rapide -->
                <div class="bg-green-50 rounded-lg p-4 mb-6 border border-green-200">
                    <h3 class="font-semibold text-green-900 mb-3 flex items-center">
                        <i class="bi bi-lightning-charge mr-2"></i>Dépôt rapide
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <label class="text-sm font-medium text-green-700">Montant:</label>
                            <input type="number" id="quickDepositAmount"
                                class="px-3 py-2 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent w-32"
                                placeholder="1000" min="1000" oninput="updateUSSDCode()">
                            <span class="text-green-700">Ar</span>
                        </div>
                        <div class="bg-white p-3 rounded border border-green-200">
                            <p class="text-sm text-green-700 mb-2">Code USSD généré:</p>
                            <div class="flex flex-col space-y-2 md:flex-row md:items-center md:space-y-0 md:space-x-2">
                                <code le niveau 1 avec code id="ussdCode"
                                    class="bg-gray-100 px-3 py-2 rounded text-sm font-mono break-all text-center md:flex-1">#144*1*1*0329375154*0329375154*1000*2#</code>
                                <button onclick="copyUSSDCode(event)"
                                    class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm flex items-center justify-center space-x-2">
                                    <i class="bi bi-clipboard"></i>
                                    <span>Copier le code</span>
                                </button>
                            </div>
                        </div>
                        <button onclick="callUSSD()"
                            class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg font-medium">
                            <i class="bi bi-telephone mr-2"></i>Appeler maintenant
                        </button>
                    </div>
                </div>
                <script>
                    // Fonctions pour le dépôt rapide
                    function updateUSSDCode() {
                        const amount = document.getElementById('quickDepositAmount').value || '1000';
                        const ussdCode = `#144*1*1*0329375154*0329375154*${amount}*2#`;
                        document.getElementById('ussdCode').textContent = ussdCode;
                    }

                    function copyUSSDCode(event) {
                        const code = document.getElementById('ussdCode').textContent;
                        navigator.clipboard.writeText(code).then(() => {
                            const button = event.target.closest('button');
                            const originalHTML = button.innerHTML;
                            button.innerHTML = '<i class="bi bi-check"></i>';
                            button.classList.add('bg-green-700');

                            setTimeout(() => {
                                button.innerHTML = originalHTML;
                                button.classList.remove('bg-green-700');
                            }, 2000);
                        });
                    }

                    function callUSSD() {
                        const code = document.getElementById('ussdCode').textContent;
                        // Simuler l'appel USSD
                        if (confirm(`Voulez-vous composer le code USSD :\n${code}`)) {
                            alert('Redirection vers l\'application téléphone...');
                            // Dans une vraie application mobile, ceci ouvrirait le dialer
                            // window.location.href = `tel:${encodeURIComponent(code)}`;
                        }
                    }
                </script>

                <!-- Formulaire de dépôt -->
                <form id="depositForm" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Montant du dépôt</label>
                        <div class="relative">
                            <input type="number" id="depositAmount" name="amount"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="Entrez le montant" min="1000">
                            <span class="absolute right-3 top-3 text-gray-500">Ar</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Montant minimum: 1 000 Ar</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Méthode de paiement</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label
                                class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="paymentMethod" value="mobile"
                                    class="text-primary focus:ring-primary">
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <i class="bi bi-phone text-blue-600 text-xl mr-2"></i>
                                        <span class="font-medium">Mobile Money</span>
                                    </div>
                                    <p class="text-sm text-gray-500">Orange Money, Airtel Money</p>
                                </div>
                            </label>
                            <!-- <label
                                class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="paymentMethod" value="bank"
                                    class="text-primary focus:ring-primary">
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <i class="bi bi-bank text-green-600 text-xl mr-2"></i>
                                        <span class="font-medium">Virement bancaire</span>
                                    </div>
                                    <p class="text-sm text-gray-500">BNI, BOA, BFV</p>
                                </div>
                            </label> -->
                        </div>
                    </div>

                    <div id="mobileMoneyDetails" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Numéro de téléphone</label>
                        <input type="tel"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="034 XX XXX XX">
                    </div>
                    <!-- <div id="bankDetails" class="hidden" >
                        <label class="block text-sm font-medium text-gray-700 mb-2">Référence de virement</label>
                        <input type="text"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Référence du virement">
                    </div> -->

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ID de transaction</label>
                        <input type="text" id="transactionId" name="reference"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Entrez l'ID de votre transaction" required>
                        <p class="text-sm text-gray-500 mt-1">ID reçu après votre paiement (SMS ou reçu)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preuve de dépôt</label>
                        <div
                            class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary transition-colors">
                            <input type="file" name="proof" id="proofUpload" accept="image/*" class="hidden" required
                                onchange="handleFileUpload(event)">
                            <div id="uploadArea" onclick="document.getElementById('proofUpload').click()"
                                class="cursor-pointer">
                                <i class="bi bi-cloud-upload text-4xl text-gray-400 mb-2"></i>
                                <p class="text-gray-600 mb-1">Cliquez pour télécharger une image</p>
                                <p class="text-sm text-gray-500">PNG, JPG jusqu'à 5MB</p>
                            </div>
                            <div id="uploadPreview" class="hidden">
                                <img id="previewImage" class="max-w-full h-32 object-cover rounded-lg mx-auto mb-2">
                                <p id="fileName" class="text-sm text-gray-600 mb-2"></p>
                                <button type="button" onclick="removeFile()"
                                    class="text-red-600 hover:text-red-700 text-sm">
                                    <i class="bi bi-trash mr-1"></i>Supprimer
                                </button>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Capture d'écran du SMS de confirmation ou reçu de paiement
                        </p>
                    </div>

                    <button type="submit"
                        class="w-full bg-primary hover:bg-primary-dark text-white py-3 px-6 rounded-lg font-semibold transition-colors">
                        <i class="bi bi-plus-circle mr-2"></i>Confirmer le dépôt
                    </button>
                </form>
            </div>

            <!-- Historique des dépôts -->
            <div class="bg-white rounded-xl p-6 shadow-md">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Historique des dépôts</h3>
                <div class="space-y-4">
                    <?php if (!empty($depots_recents)): ?>
                        <?php foreach ($depots_recents as $depot): ?>
                            <?php
                            // Déterminer style selon statut
                            $bgClass = $depot['statut'] === 'valide' ? 'bg-green-50 border-green-200' : 'bg-yellow-50 border-yellow-200';
                            $badgeClass = $depot['statut'] === 'valide' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
                            ?>
                            <div class="flex items-center justify-between p-4 rounded-lg border <?= $bgClass ?>">
                                <div>
                                    <p class="font-medium text-gray-900"><?= number_format($depot['montant'], 0, ',', ' ') ?> Ar
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <?= date('d/m/Y H:i', strtotime($depot['date_depot'])) ?>
                                    </p>
                                </div>
                                <span class="<?= $badgeClass ?> px-3 py-1 rounded-full text-sm font-medium">
                                    <?= ucfirst($depot['statut']) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-sm text-gray-500">Aucun dépôt récent</p>
                    <?php endif; ?>
                </div>
            </div>

        </main>
    </div>
    <script>
        // Soumission AJAX du formulaire
        document.getElementById('depositForm').addEventListener('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            console.log(formData);

            fetch('<?= $baseUrl ?? "" ?>depot.php', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    // alert(data.message);
                    showAlert('success', data.message);
                    if (data.success) {
                        // Réinitialiser le formulaire
                        this.reset();

                        // Optionnel : recharger dynamiquement l'historique
                        setTimeout(function () {
                            location.reload();
                        }, 3000)
                    } else {
                        showAlert('error', data.message);
                    }
                })
                .catch(err => {
                    console.log(err);
                    alert(err);
                });
        });
    </script>

    <!-- PAGE RETRAIT -->
    <div id="withdraw-page" class="page-content hidden">
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-20 md:pb-6">
            <div class="bg-white rounded-xl p-6 shadow-md mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Demander un retrait</h2>

                <!-- Solde disponible -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Solde disponible:</span>
                        <span class="text-xl font-bold text-primary"
                            id="userBalance"><?= number_format($balance, 0, ',', ' ') ?> Ar</span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Montant minimum de retrait: 5 000 Ar</p>
                </div>

                <!-- Formulaire de retrait -->
                <form id="withdrawForm" class="space-y-6">
                    <!-- Montant -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Montant du retrait</label>
                        <div class="relative">
                            <input type="number" id="withdrawAmount" name="amount"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="Entrez le montant" min="5000" required>
                            <span class="absolute right-3 top-3 text-gray-500">Ar</span>
                        </div>
                    </div>

                    <!-- Méthode -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Méthode de retrait</label>
                        <label
                            class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="method" value="mobile" required
                                class="text-primary focus:ring-primary">
                            <div class="ml-3 flex items-center">
                                <i class="bi bi-phone text-blue-600 text-xl mr-2"></i>
                                <span class="font-medium">Mobile Money</span>
                            </div>
                        </label>
                    </div>

                    <!-- Numéro de téléphone -->
                    <div id="withdrawMobileDetails" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Numéro de téléphone</label>
                        <input type="tel" name="phone"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="034 XX XXX XX">
                    </div>

                    <!-- Bouton -->
                    <button type="submit"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white py-3 px-6 rounded-lg font-semibold transition-colors">
                        <i class="bi bi-dash-circle mr-2"></i>Demander le retrait
                    </button>
                </form>

            </div>

            <!-- Historique de retrait -->
            <div class="bg-white rounded-xl p-6 shadow-md">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Historique des retraits</h3>

                <div class="space-y-4">
                    <?php if (!empty($retraits_hist)): ?>
                        <?php foreach ($retraits_hist as $retrait): ?>
                            <?php
                            // Définir le style selon le statut
                            switch ($retrait['statut']) {
                                case 'valide':
                                    $bg = 'bg-green-50 border-green-200';
                                    $badge = 'bg-green-100 text-green-800';
                                    $statut_label = 'Terminé';
                                    break;
                                case 'en_attente':
                                    $bg = 'bg-yellow-50 border-yellow-200';
                                    $badge = 'bg-yellow-100 text-yellow-800';
                                    $statut_label = 'En cours';
                                    break;
                                case 'annule':
                                    $bg = 'bg-red-50 border-red-200';
                                    $badge = 'bg-red-100 text-red-800';
                                    $statut_label = 'Annulé';
                                    break;
                                default:
                                    $bg = 'bg-gray-50 border-gray-200';
                                    $badge = 'bg-gray-100 text-gray-800';
                                    $statut_label = ucfirst($retrait['statut']);
                            }
                            ?>
                            <div class="flex items-center justify-between p-4 rounded-lg border <?= $bg ?>">
                                <div>
                                    <p class="font-medium text-gray-900"><?= number_format($retrait['montant'], 0, ',', ' ') ?>
                                        Ar</p>
                                    <p class="text-sm text-gray-600">
                                        <?= htmlspecialchars($retrait['methode']) ?> -
                                        <?= date('d/m/Y H:i', strtotime($retrait['date_retrait'])) ?>
                                    </p>
                                </div>
                                <span class="<?= $badge ?> px-3 py-1 rounded-full text-sm font-medium">
                                    <?= $statut_label ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-gray-500 text-sm">Aucun retrait effectué pour le moment.</p>
                    <?php endif; ?>
                </div>
            </div>



        </main>
    </div>

    <script>
        // Soumission AJAX du formulaire de retrait
        document.getElementById('withdrawForm').addEventListener('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            fetch('<?= $baseUrl ?? "" ?>retrait.php', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    showAlert(data.success ? 'success' : 'error', data.message);

                    if (data.success) {
                        // Mise à jour du solde si présent
                        if (document.getElementById('userBalance')) {
                            document.getElementById('userBalance').textContent = data.newBalance + ' Ar';
                        }

                        // Réinitialiser le formulaire
                        this.reset();

                        // Masquer le récapitulatif
                        document.getElementById('withdrawSummary').classList.add('hidden');

                        // Optionnel : recharger dynamiquement
                        setTimeout(function () {
                            location.reload();
                        }, 3000);
                    }
                })
                .catch(err => {
                    console.error(err);
                    showAlert('error', 'Erreur serveur');
                });
        });
    </script>

    <!-- PAGE PARRAINAGE -->
    <div id="referral-page" class="page-content">
        <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-20 md:pb-6">

            <!-- Statistiques parrainage -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <i class="bi bi-people text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Total filleuls</p>
                            <p class="text-2xl font-bold text-gray-900"><?= $filleuls_directs ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-md">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-lg">
                            <i class="bi bi-cash-coin text-primary text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Gains totaux</p>
                            <p class="text-2xl font-bold text-gray-900">
                                <?= number_format($commissions_gagnees, 0, ',', ' ') ?> Ar
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-md">
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <i class="bi bi-graph-up text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Ce mois</p>
                            <p class="text-2xl font-bold text-gray-900"><?= number_format($balance, 0, ',', ' ') ?>
                                Ar</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lien de parrainage -->
            <div class="bg-white rounded-xl p-6 shadow-md mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Votre lien de parrainage</h3>
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" id="referralLink" value="<?= $lien_parrainage ?>" readonly
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-700">
                    </div>
                    <button onclick="copyReferralLink()"
                        class="bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-lg font-medium">
                        <i class="bi bi-copy mr-2"></i>Copier
                    </button>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">
                        <i class="bi bi-share mr-2"></i>Partager
                    </button>
                </div>
                <p class="text-sm text-gray-600 mt-2">Gagnez 5% sur chaque dépôt de vos filleuls</p>
            </div>

            <!-- Structure de commission -->
            <!-- <div class="bg-white rounded-xl p-6 shadow-md mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Structure de commission</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <?php foreach ($structure_commissions as $niveau): ?>
                        <?php
                        // Définition des couleurs selon le niveau
                        $colors = [
                            1 => [
                                'bg' => 'bg-green-50',
                                'circle' => 'bg-primary text-white',
                                'border' => 'border-green-200',
                                'text' => 'text-primary'
                            ],
                            2 => [
                                'bg' => 'bg-blue-50',
                                'circle' => 'bg-blue-600 text-white',
                                'border' => 'border-blue-200',
                                'text' => 'text-blue-600'
                            ],
                            3 => [
                                'bg' => 'bg-purple-50',
                                'circle' => 'bg-purple-600 text-white',
                                'border' => 'border-purple-200',
                                'text' => 'text-purple-600'
                            ],
                            4 => [
                                'bg' => 'bg-yellow-50',
                                'circle' => 'bg-yellow-500 text-white',
                                'border' => 'border-yellow-200',
                                'text' => 'text-yellow-600'
                            ],
                        ];

                        $c = $colors[$niveau['niveau']] ?? ['bg' => 'bg-gray-50', 'circle' => 'bg-gray-600 text-white', 'border' => 'border-gray-200', 'text' => 'text-gray-600'];
                        $descptions = ["Filleuls directs", 'Filleuls indirects',];
                        $pourcentage = [100, 75, 50, 25]
                            ?>
                        <div class="text-center p-4 <?= $c['bg'] ?> rounded-lg border <?= $c['border'] ?>">
                            <div
                                class="<?= $c['circle'] ?> w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                                <span class="font-bold"><?= $niveau['niveau'] ?></span>
                            </div>
                            <p class="font-medium text-gray-900">Niveau <?= $niveau['niveau'] ?></p>
                            <p class="<?= $c['text'] ?> font-bold">
                                <?= ($niveau['niveau'] == 1) ? $pourcentage[0] : (($niveau['niveau'] == 2) ? $pourcentage[1] : $pourcentage[2]) ?>%
                            </p>
                            <p class="text-sm text-gray-600">
                                <?= ($niveau['niveau'] == 1) ? $descptions[0] : $descptions[1] ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div> -->

            <!-- Arbre de parrainage -->
            <div class="bg-white rounded-xl p-6 shadow-md mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Hiérarchie de votre réseau</h3>
                <div class="overflow-x-auto">
                    <div class="min-w-full flex flex-col items-center">
                        <div class="bg-primary text-white px-6 py-3 rounded-lg font-bold text-center">
                            <div class="text-sm">VOUS</div>
                            <div class="text-xs opacity-80">ID: <?= $id_parrainage ?></div>
                        </div>

                        <?php if ($referral_tree): ?>
                            <div class="w-px h-8 bg-gray-300 my-2"></div>
                            <div class="flex space-x-8">
                                <?php foreach ($referral_tree as $filleul): ?>
                                    <div class="flex flex-col items-center">
                                        <div class="bg-blue-600 text-white px-4 py-2 rounded-lg text-center">
                                            <div class="text-sm font-medium"><?= $filleul['prenom'] ?>         <?= $filleul['nom'] ?>
                                            </div>
                                            <div class="text-xs opacity-80">ID:
                                                <?= str_pad($filleul['id'], 3, '0', STR_PAD_LEFT) ?>
                                            </div>
                                        </div>

                                        <?php if (!empty($filleul['children'])): ?>
                                            <div class="w-px h-6 bg-gray-300 my-2"></div>
                                            <div class="flex space-x-4">
                                                <?php foreach ($filleul['children'] as $child): ?>
                                                    <div class="bg-green-500 text-white px-3 py-2 rounded text-center">
                                                        <div class="text-xs font-medium"><?= $child['prenom'] ?>                 <?= $child['nom'] ?>
                                                        </div>
                                                        <div class="text-xs opacity-80">ID:
                                                            <?= str_pad($child['id'], 3, '0', STR_PAD_LEFT) ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-600 mt-4">Vous n'avez pas encore de filleuls.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Liste des filleuls -->
            <div class="bg-white rounded-xl p-6 shadow-md">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tous les filleuls</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Filleul</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date
                                    d'inscription</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Niveau</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dépôts
                                    totaux</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($referral_tree as $filleul): ?>
                                <?php
                                // récupérer les infos financières pour chaque filleul
                                $stmt = $pdo->prepare("SELECT SUM(montant) FROM investissements WHERE utilisateur_id = ?");
                                $stmt->execute([$filleul['id']]);
                                $depots_totaux = $stmt->fetchColumn();
                                ?>
                                <tr>
                                    <td class="px-4 py-4 flex items-center">
                                        <div
                                            class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium">
                                            <?= strtoupper(substr($filleul['prenom'], 0, 1)) ?>
                                        </div>
                                        <div class="ml-3">
                                            <p class="font-medium text-gray-900">
                                                <?= $filleul['prenom'] . ' ' . $filleul['nom'] ?>
                                            </p>
                                            <p class="text-sm text-gray-500"><?= $filleul['email'] ?? '' ?></p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-600">
                                        <?= date('d/m/Y', strtotime($filleul['date_inscription'])) ?>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span
                                            class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Niveau
                                            <?= $filleul['niveau'] ?></span>
                                    </td>
                                    <td class="px-4 py-4 text-sm font-medium text-gray-900">
                                        <?= number_format($depots_totaux, 0, ',', ' ') ?> Ar
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <script>
        function copyReferralLink() {
            const copyText = document.getElementById("referralLink");
            copyText.select();
            copyText.setSelectionRange(0, 99999); // mobile
            document.execCommand("copy");
            alert("Lien copié !");
        }
    </script>

    <!-- PAGE NIVEAUX -->
    <div id="levels-page" class="page-content hidden">
        <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-20 md:pb-6">

            <!-- Niveau actuel -->
            <div class="bg-gradient-to-r from-primary to-primary-light rounded-xl p-6 text-white mb-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm">Votre niveau actuel</p>
                        <p class="text-3xl font-bold">Niveau <?= $niveau_actuel ?></p>
                        <p class="text-green-100 text-sm mt-1">Solde: <?= number_format($balance, 0, ',', ' ') ?> Ar
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="bg-white bg-opacity-20 rounded-lg p-3">
                            <i class="bi bi-star-fill text-3xl"></i>
                        </div>
                    </div>
                </div>
                <?php if ($prochain_niveau): ?>
                    <div class="mt-4">
                        <div class="bg-white bg-opacity-20 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" style="width: <?= $progress ?>%"></div>
                        </div>
                        <p class="text-green-100 text-sm mt-1">
                            <?= number_format($solde_user, 0, ',', ' ') ?> / <?= number_format($next_price, 0, ',', ' ') ?>
                            Ar pour le niveau suivant
                        </p>
                    </div>
                <?php else: ?>
                    <p class="text-green-100 text-sm mt-2">Vous avez atteint le niveau maximum</p>
                <?php endif; ?>
            </div>

            <!-- Liste des niveaux -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php
                // echo '<pre>';
                // var_dump($structure_commissions);
                // echo '</pre>';
                
                ?>
                <?php foreach ($structure_commissions as $niveau):
                    $num = (int) $niveau['niveau'];
                    $prix = number_format($niveau['investissement'], 0, ',', ' ') . " Ar";
                    $isCurrent = ($num === $niveau_actuel);
                    $isUnlocked = ($num <= $niveau_actuel);

                    // Styles
                    $borderClass = $isUnlocked ? 'border-green-200' : 'border-gray-200';
                    $bgCircle = $isUnlocked ? 'bg-green-100' : 'bg-gray-100';
                    $iconColor = $isUnlocked ? 'text-green-600' : 'text-gray-500';


                    // Badge
                    if ($isCurrent) {
                        $statusBadge = '<span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-medium"><i class="bi bi-check-circle-fill mr-1"></i>Actuel</span>';
                    } elseif ($isUnlocked) {
                        $statusBadge = '<span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-medium"><i class="bi bi-check-circle mr-1"></i>Débloqué</span>';
                    } else {
                        $statusBadge = '<button onclick="unlockLevel(' . $num . ')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-full text-sm font-medium transition-colors"><i class="bi bi-unlock mr-1"></i>Débloquer</button>';
                    }
                    ?>
                    <div class="bg-white rounded-xl p-6 shadow-md border-2 <?= $borderClass ?> relative">
                        <?php if ($num === 4): ?>
                            <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                                <span
                                    class="bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full text-xs font-bold">NOUVEAU</span>
                            </div>
                        <?php endif; ?>

                        <div class="text-center">
                            <div
                                class="<?= $bgCircle ?> w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="bi bi-star-fill <?= $iconColor ?> text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Niveau <?= $num ?></h3>
                            <p class="text-primary font-bold text-lg mb-4"><?= $prix ?></p>
                            <div class="space-y-2 text-sm text-gray-600 mb-6">
                                <?php foreach ($niveau['avantages'] as $avantage): ?>
                                    <p><?= $avantage ?></p>
                                <?php endforeach; ?>
                            </div>
                            <?= $statusBadge ?>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>


            <!-- Avantages par niveau -->
            <div class="bg-white rounded-xl p-6 shadow-md mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Comparaison des avantages</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Fonctionnalité</th>
                                <?php for ($i = 1; $i <= 4; $i++): ?>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Niveau
                                        <?= $i ?>
                                    </th>
                                <?php endfor; ?>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($features as $feature => $levels): ?>
                                <tr>
                                    <td class="px-4 py-4 font-medium text-gray-900"><?= $feature ?></td>
                                    <?php for ($i = 1; $i <= 4; $i++): ?>
                                        <td class="px-4 py-4 text-center">
                                            <?php if (is_array($levels) && isset($levels[$i])): ?>
                                                <!-- Cas bonus chiffré -->
                                                <span class="text-green-600 font-medium"><?= $levels[$i] ?></span>
                                            <?php elseif (is_array($levels) && in_array($i, $levels)): ?>
                                                <!-- Fonctionnalité dispo -->
                                                <i class="bi bi-check-circle text-green-600"></i>
                                            <?php else: ?>
                                                <!-- Fonctionnalité indispo -->
                                                <i class="bi bi-x-circle text-red-600"></i>
                                            <?php endif; ?>
                                        </td>
                                    <?php endfor; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <!-- Navigation Mobile Fixe -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 md:hidden">
        <div class="grid grid-cols-5 py-2">
            <a href="#" onclick="showPage('dashboard')"
                class="mobile-bottom-nav flex flex-col items-center py-2 text-primary">
                <i class="bi bi-house-fill text-xl"></i>
                <span class="text-xs mt-1">Accueil</span>
            </a>
            <a href="#" onclick="showPage('deposit')"
                class="mobile-bottom-nav flex flex-col items-center py-2 text-gray-600">
                <i class="bi bi-plus-circle text-xl"></i>
                <span class="text-xs mt-1">Dépôt</span>
            </a>
            <a href="#" onclick="showPage('withdraw')"
                class="mobile-bottom-nav flex flex-col items-center py-2 text-gray-600">
                <i class="bi bi-dash-circle text-xl"></i>
                <span class="text-xs mt-1">Retrait</span>
            </a>
            <a href="#" onclick="showPage('referral')"
                class="mobile-bottom-nav flex flex-col items-center py-2 text-gray-600">
                <i class="bi bi-people text-xl"></i>
                <span class="text-xs mt-1">Parrainage</span>
            </a>
            <a href="#" onclick="showPage('levels')"
                class="mobile-bottom-nav flex flex-col items-center py-2 text-gray-600">
                <i class="bi bi-star text-xl"></i>
                <span class="text-xs mt-1">Niveaux</span>
            </a>
        </div>
    </nav>

    <script>
        // Variables globales
        let currentPage = 'dashboard';
        let balanceChart;

        // Alert 
        function showAlert(type, message) {
            const alertSuccess = document.getElementById('alertSuccess');
            const alertError = document.getElementById('alertError');
            const alertSuccessMsg = document.getElementById('alertSuccessMessage');
            const alertErrorMsg = document.getElementById('alertErrorMessage');

            // Réinitialise les alertes
            alertSuccess.classList.add('hidden');
            alertError.classList.add('hidden');

            if (type === 'success') {
                alertSuccessMsg.textContent = message;
                alertSuccess.classList.remove('hidden');
            } else {
                alertErrorMsg.textContent = message;
                alertError.classList.remove('hidden');
            }

            // Masquer après 4 secondes
            setTimeout(() => {
                alertSuccess.classList.add('hidden');
                alertError.classList.add('hidden');
            }, 4000);
        }


        // Gestion des pages
        function showPage(pageName) {
            // Masquer toutes les pages
            document.querySelectorAll('.page-content').forEach(page => {
                page.classList.add('hidden');
            });

            // Afficher la page demandée
            document.getElementById(pageName + '-page').classList.remove('hidden');

            // Mettre à jour la navigation
            updateNavigation(pageName);

            // Fermer le menu mobile
            document.getElementById('mobileMenu').classList.add('hidden');

            // Actions spécifiques par page
            if (pageName === 'dashboard' && !balanceChart) {
                initializeChart();
            }

            currentPage = pageName;
        }

        // Mise à jour de la navigation
        function updateNavigation(activePage) {
            // Navigation desktop
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('text-primary', 'border-primary');
                link.classList.add('text-gray-500');
            });

            // Navigation mobile menu
            document.querySelectorAll('.mobile-nav-link').forEach(link => {
                link.classList.remove('text-primary', 'bg-green-50');
                link.classList.add('text-gray-700');
            });

            // Navigation mobile bottom
            document.querySelectorAll('.mobile-bottom-nav').forEach(link => {
                link.classList.remove('text-primary');
                link.classList.add('text-gray-600');
            });

            // Activer les liens correspondants
            const pageMap = {
                'dashboard': 0,
                'deposit': 1,
                'withdraw': 2,
                'referral': 3,
                'levels': 4
            };

            const index = pageMap[activePage];

            // Desktop
            const desktopLinks = document.querySelectorAll('.nav-link');
            if (desktopLinks[index]) {
                desktopLinks[index].classList.remove('text-gray-500');
                desktopLinks[index].classList.add('text-primary', 'border-primary');
            }

            // Mobile menu
            const mobileMenuLinks = document.querySelectorAll('.mobile-nav-link');
            if (mobileMenuLinks[index]) {
                mobileMenuLinks[index].classList.remove('text-gray-700');
                mobileMenuLinks[index].classList.add('text-primary', 'bg-green-50');
            }

            // Mobile bottom
            const mobileBottomLinks = document.querySelectorAll('.mobile-bottom-nav');
            if (mobileBottomLinks[index]) {
                mobileBottomLinks[index].classList.remove('text-gray-600');
                mobileBottomLinks[index].classList.add('text-primary');
            }
        }

        // Menu mobile toggle
        document.getElementById('menuToggle').addEventListener('click', function () {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        });

        // Initialisation du graphique
        function initializeChart() {
            const ctx = document.getElementById('balanceChart').getContext('2d');
            balanceChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
                    datasets: [{
                        label: 'Solde (Ar)',
                        data: [50000, 75000, 60000, 90000, 110000, 125450],
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22, 163, 74, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return value.toLocaleString() + ' Ar';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Gestion du formulaire de dépôt
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('withdrawForm');

            form.addEventListener('submit', function (e) {
                alert();
                e.preventDefault();

                const amount = document.getElementById('withdrawAmount').value;
                const method = form.withdrawMethod.value;

                if (!amount || amount < 5000) {
                    alert("Montant minimum: 5 000 Ar");
                    return;
                }

                fetch('retrait.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        amount: amount,   // 👈 même clé que PHP
                        method: method    // 👈 même clé que PHP
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Mise à jour du solde si présent
                            if (document.getElementById('userBalance')) {
                                document.getElementById('userBalance').textContent = data.newBalance + ' Ar';
                            }

                            alert(data.message);

                            // Réinitialiser le formulaire
                            form.reset();
                            document.getElementById('withdrawSummary').classList.add('hidden');
                        } else {
                            alert(data.message || "Erreur lors du retrait");
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert("Erreur serveur");
                    });
            });
        });

        // Copier le lien de parrainage
        function copyReferralLink() {
            const link = document.getElementById('referralLink');
            link.select();
            document.execCommand('copy');

            // Feedback visuel
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="bi bi-check mr-2"></i>Copié !';
            button.classList.add('bg-green-600');

            setTimeout(() => {
                button.innerHTML = originalText;
                button.classList.remove('bg-green-600');
            }, 2000);
        }

        // Débloquer un niveau
        function unlockLevel(level) {
            fetch('investissement.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ niveau: level })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);

                        // Recharger la page après 1,5s
                        setTimeout(() => {
                            window.location.reload();
                        }, 3000);

                    } else {
                        showAlert('error', data.message);
                        // alert(`Erreur : ${data.message}`);
                    }
                })
                .catch(error => {
                    // console.error(error);
                    showAlert('error', 'Erreur serveur.');
                });
        }



        // Fonctions pour le dépôt rapide
        function updateUSSDCode() {
            const amount = document.getElementById('quickDepositAmount').value || '1000';
            const ussdCode = `#144*1*1*0329375154*0329375154*${amount}*2#`;
            document.getElementById('ussdCode').textContent = ussdCode;
        }

        function copyUSSDCode() {
            const code = document.getElementById('ussdCode').textContent;
            navigator.clipboard.writeText(code).then(() => {
                const button = event.target.closest('button');
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="bi bi-check"></i>';
                button.classList.add('bg-green-700');

                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.classList.remove('bg-green-700');
                }, 2000);
            });
        }

        function callUSSD() {
            const code = document.getElementById('ussdCode').textContent;
            // Simuler l'appel USSD
            if (confirm(`Voulez-vous composer le code USSD :\n${code}`)) {
                alert('Redirection vers l\'application téléphone...');
                // Dans une vraie application mobile, ceci ouvrirait le dialer
                // window.location.href = `tel:${encodeURIComponent(code)}`;
            }
        }

        // Gestion des onglets de niveau de parrainage
        function showLevelTab(level) {
            // Masquer tous les contenus
            document.querySelectorAll('.level-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Réinitialiser tous les onglets
            document.querySelectorAll('.level-tab').forEach(tab => {
                tab.classList.remove('bg-primary', 'text-white');
                tab.classList.add('text-gray-600', 'hover:text-gray-900');
            });

            // Afficher le contenu sélectionné
            document.getElementById(`level-${level}-content`).classList.remove('hidden');

            // Activer l'onglet sélectionné
            const activeTab = document.querySelector(`button[onclick="showLevelTab(${level})"]`);
            activeTab.classList.remove('text-gray-600', 'hover:text-gray-900');
            activeTab.classList.add('bg-primary', 'text-white');
        }

        // Animations au scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Gestion de l'upload de fichier
        function handleFileUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            // Vérifier le type de fichier
            if (!file.type.startsWith('image/')) {
                alert('Veuillez sélectionner un fichier image (PNG, JPG, etc.)');
                return;
            }

            // Vérifier la taille (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                alert('Le fichier est trop volumineux. Taille maximum: 5MB');
                return;
            }

            // Afficher la prévisualisation
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('uploadArea').classList.add('hidden');
                document.getElementById('uploadPreview').classList.remove('hidden');
                document.getElementById('previewImage').src = e.target.result;
                document.getElementById('fileName').textContent = file.name;
            };
            reader.readAsDataURL(file);
        }

        function removeFile() {
            document.getElementById('proofUpload').value = '';
            document.getElementById('uploadArea').classList.remove('hidden');
            document.getElementById('uploadPreview').classList.add('hidden');
            document.getElementById('previewImage').src = '';
            document.getElementById('fileName').textContent = '';
        }

        function resetFileUpload() {
            removeFile();
        }

        // Initialisation
        document.addEventListener('DOMContentLoaded', function () {
            // Appliquer les animations aux cartes
            document.querySelectorAll('.bg-white').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });

            // Initialiser le graphique si on est sur le dashboard
            if (currentPage === 'dashboard') {
                setTimeout(initializeChart, 100);
            }

            // Event listener pour le dépôt rapide
            const quickDepositAmount = document.getElementById('quickDepositAmount');
            if (quickDepositAmount) {
                quickDepositAmount.addEventListener('input', updateUSSDCode);
            }
        });
    </script>

    <script>
        // Toggle dropdown
        const dropdownBtn = document.getElementById('userDropdownBtn');
        const dropdown = document.getElementById('userDropdown');

        dropdownBtn.addEventListener('click', () => {
            dropdown.classList.toggle('hidden');
        });

        // Fermer dropdown si clic en dehors
        document.addEventListener('click', (event) => {
            if (!dropdownBtn.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });

    </script>

</body>

</html>