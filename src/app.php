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
                    <!-- <button type="button" id="logoutBtn"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                        <i class="bi bi-box-arrow-right mr-1"></i>Déconnexion
                    </button> -->
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
            <div class="relative inline-block text-left">
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
                            <p class="font-semibold text-gray-900">Niveau <?= $niveau_actuel ?: 1 ?></p>
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
            <div class="bg-white rounded-xl p-6 shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Historique récent</h3>
                    <button class="text-primary hover:text-primary-dark text-sm font-medium">Voir tout</button>
                </div>
                <div class="space-y-4">
                    <?php foreach ($depots_valides as $depot): ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-2 rounded-lg">
                                    <i class="bi bi-plus-circle text-primary"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="font-medium text-gray-900">Dépôt</p>
                                    <p class="text-sm text-gray-600">
                                        <?= date('d M, H:i', strtotime($depot['date_depot'])) ?>
                                    </p>
                                </div>
                            </div>
                            <span class="font-semibold text-primary">+<?= number_format($depot['montant'], 0, '', ' ') ?>
                                Ar</span>
                        </div>
                    <?php endforeach; ?>
                    <!-- On peut ajouter retraits récents de même manière -->
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
                        <span class="text-xl font-bold text-primary">125 450 Ar</span>
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
                            <p class="text-blue-900 font-bold">RAMIZAKA</p>
                        </div>
                        <div>
                            <p class="text-sm text-blue-700 font-medium">Numéro de compte:</p>
                            <p class="text-blue-900 font-bold">032 27 435 67</p>
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
                                placeholder="1000" min="1000">
                            <span class="text-green-700">Ar</span>
                        </div>
                        <div class="bg-white p-3 rounded border border-green-200">
                            <p class="text-sm text-green-700 mb-2">Code USSD généré:</p>
                            <div class="flex items-center space-x-2">
                                <code id="ussdCode"
                                    class="bg-gray-100 px-3 py-2 rounded text-sm font-mono flex-1">#144*1*1*032274356*0322743567*1000#</code>
                                <button onclick="copyUSSDCode()"
                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm">
                                    <i class="bi bi-copy"></i>
                                </button>
                            </div>
                        </div>
                        <button onclick="callUSSD()"
                            class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg font-medium">
                            <i class="bi bi-telephone mr-2"></i>Appeler maintenant
                        </button>
                    </div>
                </div>

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
                            id="userBalance"><?= number_format($solde_user, 0, ',', ' ') ?> Ar</span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Montant minimum de retrait: 5 000 Ar</p>
                </div>

                <!-- Formulaire de retrait -->
                <form id="withdrawForm" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Montant du retrait</label>
                        <div class="relative">
                            <input type="number" id="withdrawAmount"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="Entrez le montant" min="5000" max="125450">
                            <span class="absolute right-3 top-3 text-gray-500">Ar</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Méthode de retrait</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label
                                class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="withdrawMethod" value="mobile"
                                    class="text-primary focus:ring-primary">
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <i class="bi bi-phone text-blue-600 text-xl mr-2"></i>
                                        <span class="font-medium">Mobile Money</span>
                                    </div>
                                    <p class="text-sm text-gray-500">Frais: 2%</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div id="withdrawMobileDetails" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Numéro de téléphone</label>
                        <input type="tel"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="034 XX XXX XX">
                    </div>

                    <!-- Récapitulatif -->
                    <div id="withdrawSummary" class="bg-gray-50 rounded-lg p-4 hidden">
                        <h4 class="font-medium text-gray-900 mb-2">Récapitulatif</h4>
                        <div class="space-y-1 text-sm">
                            <div class="flex justify-between">
                                <span>Montant demandé:</span>
                                <span id="requestedAmount">0 Ar</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Frais:</span>
                                <span id="withdrawFees">0 Ar</span>
                            </div>
                            <div class="flex justify-between font-medium border-t pt-1">
                                <span>Montant à recevoir:</span>
                                <span id="finalAmount">0 Ar</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white py-3 px-6 rounded-lg font-semibold transition-colors">
                        <i class="bi bi-dash-circle mr-2"></i>Demander le retrait
                    </button>
                </form>
            </div>

            <!-- Historique des retraits -->
            <div class="bg-white rounded-xl p-6 shadow-md">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Historique des retraits</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <div>
                            <p class="font-medium text-gray-900">25 000 Ar</p>
                            <p class="text-sm text-gray-600">Mobile Money - Aujourd'hui 09:15</p>
                        </div>
                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">En
                            cours</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg border border-green-200">
                        <div>
                            <p class="font-medium text-gray-900">50 000 Ar</p>
                            <p class="text-sm text-gray-600">Virement bancaire - 2 jours 14:30</p>
                        </div>
                        <span
                            class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Terminé</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg border border-red-200">
                        <div>
                            <p class="font-medium text-gray-900">15 000 Ar</p>
                            <p class="text-sm text-gray-600">Mobile Money - 5 jours 11:20</p>
                        </div>
                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">Annulé</span>
                    </div>
                </div>
            </div>

            <!-- Historique des retraits -->
            <div class="space-y-4" id="withdrawHistory">
                <?php foreach ($retraits_hist as $retrait): ?>
                    <?php
                    $bg = 'bg-yellow-50 border-yellow-200 text-yellow-800'; // en cours
                    if ($retrait['statut'] === 'valide') {
                        $bg = 'bg-green-50 border-green-200 text-green-800';
                    } elseif ($retrait['statut'] === 'annule') {
                        $bg = 'bg-red-50 border-red-200 text-red-800';
                    }
                    $methode = ucfirst($retrait['methode']);
                    $date = date('d/m/Y H:i', strtotime($retrait['date_retrait']));
                    ?>
                    <div class="flex items-center justify-between p-4 rounded-lg border <?= $bg ?>">
                        <div>
                            <p class="font-medium text-gray-900"><?= number_format($retrait['montant'], 0, ',', ' ') ?> Ar
                            </p>
                            <p class="text-sm text-gray-600"><?= $methode ?> - <?= $date ?></p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm font-medium">
                            <?= $retrait['statut'] === 'valide' ? 'Terminé' : ($retrait['statut'] === 'annule' ? 'Annulé' : 'En cours') ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>

    <!-- PAGE PARRAINAGE -->
    <div id="" class="page-content hidden">
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
                            <p class="text-2xl font-bold text-gray-900">24</p>
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
                            <p class="text-2xl font-bold text-gray-900">45 600 Ar</p>
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
                            <p class="text-2xl font-bold text-gray-900">8 500 Ar</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lien de parrainage -->
            <div class="bg-white rounded-xl p-6 shadow-md mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Votre lien de parrainage</h3>
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" id="referralLink" value="https://smart-mg.com/ref/JD2024" readonly
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

            <!-- Structure de parrainage -->
            <div class="bg-white rounded-xl p-6 shadow-md mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Structure de commission</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                        <div
                            class="bg-primary text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                            <span class="font-bold">1</span>
                        </div>
                        <p class="font-medium text-gray-900">Niveau 1</p>
                        <p class="text-primary font-bold">5%</p>
                        <p class="text-sm text-gray-600">Filleuls directs</p>
                    </div>
                    <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div
                            class="bg-blue-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                            <span class="font-bold">2</span>
                        </div>
                        <p class="font-medium text-gray-900">Niveau 2</p>
                        <p class="text-blue-600 font-bold">3%</p>
                        <p class="text-sm text-gray-600">Filleuls indirects</p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg border border-purple-200">
                        <div
                            class="bg-purple-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                            <span class="font-bold">3</span>
                        </div>
                        <p class="font-medium text-gray-900">Niveau 3</p>
                        <p class="text-purple-600 font-bold">1%</p>
                        <p class="text-sm text-gray-600">Bonus spécial</p>
                    </div>
                </div>
            </div>

            <!-- Hiérarchie des affiliations -->
            <div class="bg-white rounded-xl p-6 shadow-md mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Hiérarchie de votre réseau</h3>
                <div class="overflow-x-auto">
                    <div class="min-w-full">
                        <!-- Vous (racine) -->
                        <div class="flex flex-col items-center mb-8">
                            <div class="bg-primary text-white px-6 py-3 rounded-lg font-bold text-center">
                                <div class="text-sm">VOUS</div>
                                <div class="text-xs opacity-80">ID: JD2024</div>
                            </div>

                            <!-- Niveau 1 -->
                            <div class="w-px h-8 bg-gray-300 my-2"></div>
                            <div class="flex space-x-8">
                                <!-- Filleul 1 -->
                                <div class="flex flex-col items-center">
                                    <div class="bg-blue-600 text-white px-4 py-2 rounded-lg text-center">
                                        <div class="text-sm font-medium">Marie R.</div>
                                        <div class="text-xs opacity-80">ID: MR001</div>
                                    </div>

                                    <!-- Sous-filleuls de Marie -->
                                    <div class="w-px h-6 bg-gray-300 my-2"></div>
                                    <div class="flex space-x-4">
                                        <div class="bg-green-500 text-white px-3 py-2 rounded text-center">
                                            <div class="text-xs font-medium">Paul A.</div>
                                            <div class="text-xs opacity-80">PA001</div>
                                        </div>
                                        <div class="bg-green-500 text-white px-3 py-2 rounded text-center">
                                            <div class="text-xs font-medium">Lisa M.</div>
                                            <div class="text-xs opacity-80">LM001</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Filleul 2 -->
                                <div class="flex flex-col items-center">
                                    <div class="bg-blue-600 text-white px-4 py-2 rounded-lg text-center">
                                        <div class="text-sm font-medium">Jean K.</div>
                                        <div class="text-xs opacity-80">ID: JK002</div>
                                    </div>

                                    <!-- Sous-filleuls de Jean -->
                                    <div class="w-px h-6 bg-gray-300 my-2"></div>
                                    <div class="flex space-x-4">
                                        <div class="bg-green-500 text-white px-3 py-2 rounded text-center">
                                            <div class="text-xs font-medium">Sophie F.</div>
                                            <div class="text-xs opacity-80">SF001</div>
                                        </div>
                                        <div class="bg-gray-400 text-white px-3 py-2 rounded text-center">
                                            <div class="text-xs font-medium">Libre</div>
                                            <div class="text-xs opacity-80">---</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Légende -->
                        <div class="flex justify-center space-x-6 text-sm">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-primary rounded mr-2"></div>
                                <span>Vous</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-blue-600 rounded mr-2"></div>
                                <span>Niveau 1 (max 2)</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                                <span>Niveau 2 (max 2 chacun)</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-gray-400 rounded mr-2"></div>
                                <span>Place libre</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Affiliations par niveau -->
            <div class="bg-white rounded-xl p-6 shadow-md mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Affiliations par niveau</h3>

                <!-- Onglets -->
                <div class="flex space-x-1 mb-6 bg-gray-100 p-1 rounded-lg">
                    <button onclick="showLevelTab(1)"
                        class="level-tab flex-1 py-2 px-4 rounded-md text-sm font-medium bg-primary text-white">
                        Niveau 1 <span class="ml-1 bg-white bg-opacity-20 px-2 py-1 rounded-full text-xs">2</span>
                    </button>
                    <button onclick="showLevelTab(2)"
                        class="level-tab flex-1 py-2 px-4 rounded-md text-sm font-medium text-gray-600 hover:text-gray-900">
                        Niveau 2 <span class="ml-1 bg-gray-200 px-2 py-1 rounded-full text-xs">3</span>
                    </button>
                    <button onclick="showLevelTab(3)"
                        class="level-tab flex-1 py-2 px-4 rounded-md text-sm font-medium text-gray-600 hover:text-gray-900">
                        Niveau 3 <span class="ml-1 bg-gray-200 px-2 py-1 rounded-full text-xs">1</span>
                    </button>
                    <button onclick="showLevelTab(4)"
                        class="level-tab flex-1 py-2 px-4 rounded-md text-sm font-medium text-gray-600 hover:text-gray-900">
                        Niveau 4 <span class="ml-1 bg-gray-200 px-2 py-1 rounded-full text-xs">0</span>
                    </button>
                </div>

                <!-- Contenu Niveau 1 -->
                <div id="level-1-content" class="level-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <div
                                        class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold">
                                        MR
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-900">Marie Rakoto</p>
                                        <p class="text-sm text-gray-500">ID: MR001</p>
                                    </div>
                                </div>
                                <span
                                    class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Actif</span>
                            </div>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Inscrit le:</span>
                                    <span>15/01/2024</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Dépôts totaux:</span>
                                    <span class="font-medium">125 000 Ar</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Vos gains:</span>
                                    <span class="font-bold text-primary">6 250 Ar</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Sous-filleuls:</span>
                                    <span class="font-medium">2/2</span>
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <div
                                        class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold">
                                        JK
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-900">Jean Koto</p>
                                        <p class="text-sm text-gray-500">ID: JK002</p>
                                    </div>
                                </div>
                                <span
                                    class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Actif</span>
                            </div>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Inscrit le:</span>
                                    <span>20/01/2024</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Dépôts totaux:</span>
                                    <span class="font-medium">75 000 Ar</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Vos gains:</span>
                                    <span class="font-bold text-primary">3 750 Ar</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Sous-filleuls:</span>
                                    <span class="font-medium">1/2</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contenu Niveau 2 -->
                <div id="level-2-content" class="level-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <div
                                        class="bg-green-500 text-white w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold">
                                        PA
                                    </div>
                                    <div class="ml-2">
                                        <p class="font-medium text-gray-900 text-sm">Paul Andry</p>
                                        <p class="text-xs text-gray-500">Parrain: Marie R.</p>
                                    </div>
                                </div>
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Actif</span>
                            </div>
                            <div class="text-xs space-y-1">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Dépôts:</span>
                                    <span class="font-medium">50 000 Ar</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Vos gains:</span>
                                    <span class="font-bold text-primary">1 500 Ar</span>
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <div
                                        class="bg-green-500 text-white w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold">
                                        LM
                                    </div>
                                    <div class="ml-2">
                                        <p class="font-medium text-gray-900 text-sm">Lisa Martin</p>
                                        <p class="text-xs text-gray-500">Parrain: Marie R.</p>
                                    </div>
                                </div>
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Actif</span>
                            </div>
                            <div class="text-xs space-y-1">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Dépôts:</span>
                                    <span class="font-medium">30 000 Ar</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Vos gains:</span>
                                    <span class="font-bold text-primary">900 Ar</span>
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <div
                                        class="bg-green-500 text-white w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold">
                                        SF
                                    </div>
                                    <div class="ml-2">
                                        <p class="font-medium text-gray-900 text-sm">Sophie Faly</p>
                                        <p class="text-xs text-gray-500">Parrain: Jean K.</p>
                                    </div>
                                </div>
                                <span
                                    class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Inactif</span>
                            </div>
                            <div class="text-xs space-y-1">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Dépôts:</span>
                                    <span class="font-medium">0 Ar</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Vos gains:</span>
                                    <span class="font-bold text-gray-400">0 Ar</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contenu Niveau 3 -->
                <div id="level-3-content" class="level-content hidden">
                    <div class="text-center py-8">
                        <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="bi bi-people text-purple-600 text-2xl"></i>
                        </div>
                        <p class="text-gray-600 mb-2">1 affiliation de niveau 3</p>
                        <p class="text-sm text-gray-500">Bonus spécial: 1% sur leurs dépôts</p>
                    </div>
                </div>

                <!-- Contenu Niveau 4 -->
                <div id="level-4-content" class="level-content hidden">
                    <div class="text-center py-8">
                        <div class="bg-gray-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="bi bi-people text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-600 mb-2">Aucune affiliation de niveau 4</p>
                        <p class="text-sm text-gray-500">Développez votre réseau pour atteindre ce niveau</p>
                    </div>
                </div>
            </div>

            <!-- Liste des filleuls -->
            <div class="bg-white rounded-xl p-6 shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Tous les filleuls</h3>
                    <div class="flex space-x-2">
                        <button class="text-sm bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded-lg">Tous</button>
                        <button class="text-sm bg-primary text-white px-3 py-1 rounded-lg">Actifs</button>
                        <button class="text-sm bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded-lg">Inactifs</button>
                    </div>
                </div>

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
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vos gains
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <div
                                            class="bg-primary text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium">
                                            MR
                                        </div>
                                        <div class="ml-3">
                                            <p class="font-medium text-gray-900">Marie Rakoto</p>
                                            <p class="text-sm text-gray-500">marie.r@email.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-600">15/01/2024</td>
                                <td class="px-4 py-4">
                                    <span
                                        class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Niveau
                                        2</span>
                                </td>
                                <td class="px-4 py-4 text-sm font-medium text-gray-900">125 000 Ar</td>
                                <td class="px-4 py-4 text-sm font-bold text-primary">6 250 Ar</td>
                                <td class="px-4 py-4">
                                    <span
                                        class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Actif</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <div
                                            class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium">
                                            PA
                                        </div>
                                        <div class="ml-3">
                                            <p class="font-medium text-gray-900">Paul Andry</p>
                                            <p class="text-sm text-gray-500">paul.a@email.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-600">20/01/2024</td>
                                <td class="px-4 py-4">
                                    <span
                                        class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-medium">Niveau
                                        1</span>
                                </td>
                                <td class="px-4 py-4 text-sm font-medium text-gray-900">75 000 Ar</td>
                                <td class="px-4 py-4 text-sm font-bold text-primary">3 750 Ar</td>
                                <td class="px-4 py-4">
                                    <span
                                        class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Actif</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <div
                                            class="bg-purple-600 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium">
                                            SF
                                        </div>
                                        <div class="ml-3">
                                            <p class="font-medium text-gray-900">Sophie Faly</p>
                                            <p class="text-sm text-gray-500">sophie.f@email.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-600">25/01/2024</td>
                                <td class="px-4 py-4">
                                    <span
                                        class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs font-medium">Niveau
                                        0</span>
                                </td>
                                <td class="px-4 py-4 text-sm font-medium text-gray-900">0 Ar</td>
                                <td class="px-4 py-4 text-sm font-bold text-gray-400">0 Ar</td>
                                <td class="px-4 py-4">
                                    <span
                                        class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs font-medium">Inactif</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
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
                            <p class="text-2xl font-bold text-gray-900"><?= $filleuls_directs ?></p>
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
                            <p class="text-2xl font-bold text-gray-900"><?= number_format($solde_user, 0, ',', ' ') ?>
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
            <div class="bg-white rounded-xl p-6 shadow-md mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Structure de commission</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <?php foreach ($structure_commissions as $niveau): ?>
                        <?php
                        // Définition des couleurs selon le niveau
                        $colors = [
                            1 => ['bg' => 'bg-green-50', 'circle' => 'bg-primary text-white', 'border' => 'border-green-200', 'text' => 'text-primary'],
                            2 => ['bg' => 'bg-blue-50', 'circle' => 'bg-blue-600 text-white', 'border' => 'border-blue-200', 'text' => 'text-blue-600'],
                            3 => ['bg' => 'bg-purple-50', 'circle' => 'bg-purple-600 text-white', 'border' => 'border-purple-200', 'text' => 'text-purple-600'],
                        ];
                        $c = $colors[$niveau['niveau']] ?? ['bg' => 'bg-gray-50', 'circle' => 'bg-gray-600 text-white', 'border' => 'border-gray-200', 'text' => 'text-gray-600'];
                        ?>
                        <div class="text-center p-4 <?= $c['bg'] ?> rounded-lg border <?= $c['border'] ?>">
                            <div
                                class="<?= $c['circle'] ?> w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                                <span class="font-bold"><?= $niveau['niveau'] ?></span>
                            </div>
                            <p class="font-medium text-gray-900">Niveau <?= $niveau['niveau'] ?></p>
                            <p class="<?= $c['text'] ?> font-bold"><?= $niveau['pourcentage'] ?>%</p>
                            <p class="text-sm text-gray-600"><?= $niveau['description'] ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>


            <div class="bg-white rounded-xl p-6 shadow-md mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Structure de commission</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                        <div
                            class="bg-primary text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                            <span class="font-bold">1</span>
                        </div>
                        <p class="font-medium text-gray-900">Niveau 1</p>
                        <p class="text-primary font-bold">5%</p>
                        <p class="text-sm text-gray-600">Filleuls directs</p>
                    </div>
                    <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div
                            class="bg-blue-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                            <span class="font-bold">2</span>
                        </div>
                        <p class="font-medium text-gray-900">Niveau 2</p>
                        <p class="text-blue-600 font-bold">3%</p>
                        <p class="text-sm text-gray-600">Filleuls indirects</p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg border border-purple-200">
                        <div
                            class="bg-purple-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                            <span class="font-bold">3</span>
                        </div>
                        <p class="font-medium text-gray-900">Niveau 3</p>
                        <p class="text-purple-600 font-bold">1%</p>
                        <p class="text-sm text-gray-600">Bonus spécial</p>
                    </div>
                </div>
            </div>


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
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vos gains
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
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
                                    <td class="px-4 py-4 text-sm font-bold text-primary">
                                        <?= number_format($filleul['gains'], 0, ',', ' ') ?> Ar
                                    </td>
                                    <td class="px-4 py-4">
                                        <span
                                            class="bg-<?= $filleul['statut'] == 'Actif' ? 'green-100' : 'gray-100' ?> text-<?= $filleul['statut'] == 'Actif' ? 'green-800' : 'gray-800' ?> px-2 py-1 rounded-full text-xs font-medium"><?= $filleul['statut'] ?></span>
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
                // Définition des avantages fixes pour les niveaux 1 à 3
                $avantages = [
                    1 => [
                        '✓ Accès au tableau de bord',
                        '✓ Dépôts et retraits',
                        '✓ Support client'
                    ],
                    2 => [
                        '✓ Toutes fonctions niveau 1',
                        '✓ Parrainage niveau 1',
                        '✓ Bonus 3%'
                    ],
                    3 => [
                        '✓ Toutes fonctions niveau 2',
                        '✓ Parrainage niveau 2',
                        '✓ Bonus 5%',
                        '✓ Support prioritaire'
                    ]
                ];
                ?>

                <?php foreach ($structure_commissions as $niveau): ?>
                    <?php
                    $num = (int) $niveau['niveau'];
                    $prix = number_format($niveau['investissement'], 0, ',', ' ') . " Ar";
                    $isCurrent = ($num === $niveau_actuel);
                    $isUnlocked = ($num <= $niveau_actuel);

                    // Styles par défaut
                    $borderClass = $isUnlocked ? 'border-green-200' : 'border-gray-200';
                    $bgCircle = $isUnlocked ? 'bg-green-100' : 'bg-gray-100';
                    $iconColor = $isUnlocked ? 'text-green-600' : 'text-gray-500';
                    $statusBadge = '';

                    // Badge selon état
                    if ($num !== 4) { // pour tous sauf niveau 4
                        if ($isUnlocked && !$isCurrent) {
                            $statusBadge = '<span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-medium">
                                        <i class="bi bi-check-circle mr-1"></i>Débloqué
                                    </span>';
                        } elseif ($isCurrent) {
                            $statusBadge = '<span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-medium">
                                        <i class="bi bi-check-circle-fill mr-1"></i>Actuel
                                    </span>';
                        } else {
                            $statusBadge = '<button onclick="unlockLevel(' . $num . ')"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-full text-sm font-medium transition-colors">
                                    <i class="bi bi-unlock mr-1"></i>Débloquer
                                </button>';
                        }
                    }
                    ?>

                    <!-- Exception : Niveau 4 -->
                    <?php if ($num === 4): ?>
                        <div class="bg-white rounded-xl p-6 shadow-md border-2 border-yellow-300 relative">
                            <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                                <span
                                    class="bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full text-xs font-bold">NOUVEAU</span>
                            </div>
                            <div class="text-center">
                                <div class="bg-yellow-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="bi bi-star-fill text-yellow-600 text-2xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Niveau 4</h3>
                                <p class="text-yellow-600 font-bold text-lg mb-4"><?= $prix ?></p>
                                <div class="space-y-2 text-sm text-gray-600 mb-6">
                                    <p>✓ Toutes fonctions niveau 3</p>
                                    <p>✓ Parrainage niveau 3</p>
                                    <p>✓ Bonus 7%</p>
                                    <p>✓ Retraits prioritaires</p>
                                    <p>✓ Gestionnaire dédié</p>
                                </div>
                                <p class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                                    ⚡ Minage automatique activé
                                </p>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Niveaux 1 à 3 dynamiques -->
                        <div class="bg-white rounded-xl p-6 shadow-md border-2 <?= $borderClass ?>">
                            <div class="text-center">
                                <div
                                    class="<?= $bgCircle ?> w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="bi bi-star-fill <?= $iconColor ?> text-2xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Niveau <?= $num ?></h3>
                                <p class="text-primary font-bold text-lg mb-4"><?= $prix ?></p>
                                <div class="space-y-2 text-sm text-gray-600 mb-6">
                                    <?php foreach ($avantages[$num] as $avantage): ?>
                                        <p><?= $avantage ?></p>
                                    <?php endforeach; ?>
                                </div>
                                <?= $statusBadge ?>
                            </div>
                        </div>
                    <?php endif; ?>
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
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Niveau 1
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Niveau 2
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Niveau 3
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Niveau 4
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-4 py-4 font-medium text-gray-900">Dépôts/Retraits</td>
                                <td class="px-4 py-4 text-center"><i class="bi bi-check-circle text-green-600"></i></td>
                                <td class="px-4 py-4 text-center"><i class="bi bi-check-circle text-green-600"></i></td>
                                <td class="px-4 py-4 text-center"><i class="bi bi-check-circle text-green-600"></i></td>
                                <td class="px-4 py-4 text-center"><i class="bi bi-check-circle text-green-600"></i></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-4 font-medium text-gray-900">Parrainage</td>
                                <td class="px-4 py-4 text-center"><i class="bi bi-x-circle text-red-600"></i></td>
                                <td class="px-4 py-4 text-center"><i class="bi bi-check-circle text-green-600"></i></td>
                                <td class="px-4 py-4 text-center"><i class="bi bi-check-circle text-green-600"></i></td>
                                <td class="px-4 py-4 text-center"><i class="bi bi-check-circle text-green-600"></i></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-4 font-medium text-gray-900">Bonus parrainage</td>
                                <td class="px-4 py-4 text-center text-gray-400">-</td>
                                <td class="px-4 py-4 text-center text-green-600 font-medium">3%</td>
                                <td class="px-4 py-4 text-center text-green-600 font-medium">5%</td>
                                <td class="px-4 py-4 text-center text-green-600 font-medium">7%</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-4 font-medium text-gray-900">Support prioritaire</td>
                                <td class="px-4 py-4 text-center"><i class="bi bi-x-circle text-red-600"></i></td>
                                <td class="px-4 py-4 text-center"><i class="bi bi-x-circle text-red-600"></i></td>
                                <td class="px-4 py-4 text-center"><i class="bi bi-check-circle text-green-600"></i></td>
                                <td class="px-4 py-4 text-center"><i class="bi bi-check-circle text-green-600"></i></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-4 font-medium text-gray-900">Gestionnaire dédié</td>
                                <td class="px-4 py-4 text-center"><i class="bi bi-x-circle text-red-600"></i></td>
                                <td class="px-4 py-4 text-center"><i class="bi bi-x-circle text-red-600"></i></td>
                                <td class="px-4 py-4 text-center"><i class="bi bi-x-circle text-red-600"></i></td>
                                <td class="px-4 py-4 text-center"><i class="bi bi-check-circle text-green-600"></i></td>
                            </tr>
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
            // Formulaire de dépôt
            const depositForm = document.getElementById('depositForm');
            if (depositForm) {
                const paymentMethods = depositForm.querySelectorAll('input[name="paymentMethod"]');
                const mobileDetails = document.getElementById('mobileMoneyDetails');
                const bankDetails = document.getElementById('bankDetails');

                paymentMethods.forEach(method => {
                    method.addEventListener('change', function () {
                        if (this.value === 'mobile') {
                            mobileDetails.classList.remove('hidden');
                            bankDetails.classList.add('hidden');
                        } else if (this.value === 'bank') {
                            bankDetails.classList.remove('hidden');
                            mobileDetails.classList.add('hidden');
                        }
                    });
                });

                depositForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const amount = document.getElementById('depositAmount').value;
                    const transactionId = document.getElementById('transactionId').value;
                    const proofFile = document.getElementById('proofUpload').files[0];

                    if (amount && amount >= 1000 && transactionId && proofFile) {
                        alert(
                            `Demande de dépôt de ${parseInt(amount).toLocaleString()} Ar envoyée avec succès !\nID transaction: ${transactionId}\nPreuve jointe: ${proofFile.name}`
                        );
                        this.reset();
                        mobileDetails.classList.add('hidden');
                        bankDetails.classList.add('hidden');
                        resetFileUpload();
                    } else {
                        let errorMsg = 'Veuillez compléter tous les champs requis:\n';
                        if (!amount || amount < 1000) errorMsg += '- Montant valide (minimum 1 000 Ar)\n';
                        if (!transactionId) errorMsg += '- ID de transaction\n';
                        if (!proofFile) errorMsg += '- Preuve de dépôt (image)';
                        alert(errorMsg);
                    }
                });
            }

            // Formulaire de retrait
            const form = document.getElementById('withdrawForm');

            form.addEventListener('submit', function (e) {
               alert()
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
                        montant: amount,
                        methode: method
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Mise à jour du solde
                            document.getElementById('userBalance').textContent = data.newBalance + ' Ar';

                            // Réinitialiser le formulaire
                            form.reset();
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
            const ussdCode = `#144*1*1*032274356*0322743567*${amount}#`;
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