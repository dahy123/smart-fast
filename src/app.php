<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMART-MG - Plateforme d'Investissement</title>
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
    <!-- Header Desktop -->
    <header class="bg-white shadow-sm border-b border-gray-200 hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-primary">SMART-MG</h1>
                    </div>
                    <nav class="ml-10 flex space-x-8">
                        <a href="#" onclick="showPage('dashboard')" class="nav-link text-primary border-b-2 border-primary px-1 pb-4 text-sm font-medium">Tableau de bord</a>
                        <a href="#" onclick="showPage('deposit')" class="nav-link text-gray-500 hover:text-gray-700 px-1 pb-4 text-sm font-medium">Dépôt</a>
                        <a href="#" onclick="showPage('withdraw')" class="nav-link text-gray-500 hover:text-gray-700 px-1 pb-4 text-sm font-medium">Retrait</a>
                        <a href="#" onclick="showPage('referral')" class="nav-link text-gray-500 hover:text-gray-700 px-1 pb-4 text-sm font-medium">Parrainage</a>
                        <a href="#" onclick="showPage('levels')" class="nav-link text-gray-500 hover:text-gray-700 px-1 pb-4 text-sm font-medium">Niveaux</a>
                    </nav>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-700">Bienvenue, <strong>Jean Dupont</strong></span>
                    <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                        <i class="bi bi-box-arrow-right mr-1"></i>Déconnexion
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Header Mobile -->
    <header class="bg-white shadow-sm border-b border-gray-200 md:hidden">
        <div class="px-4 py-3">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-bold text-primary">SMART-MG</h1>
                <button id="menuToggle" class="text-gray-600">
                    <i class="bi bi-list text-2xl"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden md:hidden bg-white border-b border-gray-200">
        <div class="px-4 py-2 space-y-1">
            <a href="#" onclick="showPage('dashboard')" class="mobile-nav-link block px-3 py-2 text-primary bg-green-50 rounded-md text-sm font-medium">Tableau de bord</a>
            <a href="#" onclick="showPage('deposit')" class="mobile-nav-link block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-md text-sm font-medium">Dépôt</a>
            <a href="#" onclick="showPage('withdraw')" class="mobile-nav-link block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-md text-sm font-medium">Retrait</a>
            <a href="#" onclick="showPage('referral')" class="mobile-nav-link block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-md text-sm font-medium">Parrainage</a>
            <a href="#" onclick="showPage('levels')" class="mobile-nav-link block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-md text-sm font-medium">Niveaux</a>
            <div class="border-t pt-2">
                <button class="w-full text-left px-3 py-2 text-red-600 hover:bg-red-50 rounded-md text-sm font-medium">
                    <i class="bi bi-box-arrow-right mr-2"></i>Déconnexion
                </button>
            </div>
        </div>
    </div>

    <!-- PAGE DASHBOARD -->
    <div id="dashboard-page" class="page-content">
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-20 md:pb-6">
            <!-- Solde Principal -->
            <div class="bg-gradient-to-r from-primary to-primary-light rounded-xl p-6 text-white mb-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm">Solde général</p>
                        <p class="text-3xl font-bold">125 450 Ar</p>
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
                <button onclick="showPage('deposit')" class="bg-primary hover:bg-primary-dark text-white p-4 rounded-xl shadow-md transition-all duration-200 transform hover:scale-105">
                    <i class="bi bi-plus-circle text-2xl mb-2 block"></i>
                    <span class="font-semibold">Dépôt</span>
                </button>
                <button onclick="showPage('withdraw')" class="bg-orange-500 hover:bg-orange-600 text-white p-4 rounded-xl shadow-md transition-all duration-200 transform hover:scale-105">
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
                            <p class="text-2xl font-bold text-gray-900">450 000 Ar</p>
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
                            <p class="text-2xl font-bold text-gray-900">324 550 Ar</p>
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
                            <p class="text-2xl font-bold text-gray-900">125 450 Ar</p>
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
                            <p class="font-semibold text-gray-900">Niveau 3</p>
                            <p class="text-sm text-gray-600">Débloquer le niveau 4 avec 15 000 Ar</p>
                        </div>
                    </div>
                    <button onclick="showPage('levels')" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Voir niveaux
                    </button>
                </div>
                <div class="mt-4">
                    <div class="bg-gray-200 rounded-full h-2">
                        <div class="bg-primary h-2 rounded-full" style="width: 83.6%"></div>
                    </div>
                    <p class="text-xs text-gray-600 mt-1">12 540 / 15 000 Ar pour le niveau suivant</p>
                </div>
            </div>

            <!-- Graphique -->
            <div class="bg-white rounded-xl p-6 shadow-md mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Évolution du solde</h3>
                <canvas id="balanceChart" height="100"></canvas>
            </div>

            <!-- Historique Récent -->
            <div class="bg-white rounded-xl p-6 shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Historique récent</h3>
                    <button class="text-primary hover:text-primary-dark text-sm font-medium">Voir tout</button>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-2 rounded-lg">
                                <i class="bi bi-plus-circle text-primary"></i>
                            </div>
                            <div class="ml-3">
                                <p class="font-medium text-gray-900">Dépôt</p>
                                <p class="text-sm text-gray-600">Aujourd'hui, 14:30</p>
                            </div>
                        </div>
                        <span class="font-semibold text-primary">+50 000 Ar</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="bg-orange-100 p-2 rounded-lg">
                                <i class="bi bi-dash-circle text-orange-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="font-medium text-gray-900">Retrait</p>
                                <p class="text-sm text-gray-600">Hier, 09:15</p>
                            </div>
                        </div>
                        <span class="font-semibold text-orange-600">-25 000 Ar</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-2 rounded-lg">
                                <i class="bi bi-people text-blue-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="font-medium text-gray-900">Bonus parrainage</p>
                                <p class="text-sm text-gray-600">2 jours, 16:45</p>
                            </div>
                        </div>
                        <span class="font-semibold text-blue-600">+5 000 Ar</span>
                    </div>
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
                            Effectuez votre dépôt vers ce numéro puis remplissez le formulaire ci-dessous pour confirmation
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
                            <input type="number" id="quickDepositAmount" class="px-3 py-2 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent w-32" placeholder="1000" min="1000">
                            <span class="text-green-700">Ar</span>
                        </div>
                        <div class="bg-white p-3 rounded border border-green-200">
                            <p class="text-sm text-green-700 mb-2">Code USSD généré:</p>
                            <div class="flex items-center space-x-2">
                                <code id="ussdCode" class="bg-gray-100 px-3 py-2 rounded text-sm font-mono flex-1">#144*1*1*032274356*0322743567*1000#</code>
                                <button onclick="copyUSSDCode()" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm">
                                    <i class="bi bi-copy"></i>
                                </button>
                            </div>
                        </div>
                        <button onclick="callUSSD()" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg font-medium">
                            <i class="bi bi-telephone mr-2"></i>Appeler maintenant
                        </button>
                    </div>
                </div>

                <!-- Formulaire de dépôt -->
                <form id="depositForm" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Montant du dépôt</label>
                        <div class="relative">
                            <input type="number" id="depositAmount" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Entrez le montant" min="1000">
                            <span class="absolute right-3 top-3 text-gray-500">Ar</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Montant minimum: 1 000 Ar</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Méthode de paiement</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="paymentMethod" value="mobile" class="text-primary focus:ring-primary">
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <i class="bi bi-phone text-blue-600 text-xl mr-2"></i>
                                        <span class="font-medium">Mobile Money</span>
                                    </div>
                                    <p class="text-sm text-gray-500">Orange Money, Airtel Money</p>
                                </div>
                            </label>
                            <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="paymentMethod" value="bank" class="text-primary focus:ring-primary">
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <i class="bi bi-bank text-green-600 text-xl mr-2"></i>
                                        <span class="font-medium">Virement bancaire</span>
                                    </div>
                                    <p class="text-sm text-gray-500">BNI, BOA, BFV</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div id="mobileMoneyDetails" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Numéro de téléphone</label>
                        <input type="tel" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="034 XX XXX XX">
                    </div>

                    <div id="bankDetails" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Référence de virement</label>
                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Référence du virement">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ID de transaction</label>
                        <input type="text" id="transactionId" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Entrez l'ID de votre transaction" required>
                        <p class="text-sm text-gray-500 mt-1">ID reçu après votre paiement (SMS ou reçu)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preuve de dépôt</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary transition-colors">
                            <input type="file" id="proofUpload" accept="image/*" class="hidden" onchange="handleFileUpload(event)">
                            <div id="uploadArea" onclick="document.getElementById('proofUpload').click()" class="cursor-pointer">
                                <i class="bi bi-cloud-upload text-4xl text-gray-400 mb-2"></i>
                                <p class="text-gray-600 mb-1">Cliquez pour télécharger une image</p>
                                <p class="text-sm text-gray-500">PNG, JPG jusqu'à 5MB</p>
                            </div>
                            <div id="uploadPreview" class="hidden">
                                <img id="previewImage" class="max-w-full h-32 object-cover rounded-lg mx-auto mb-2">
                                <p id="fileName" class="text-sm text-gray-600 mb-2"></p>
                                <button type="button" onclick="removeFile()" class="text-red-600 hover:text-red-700 text-sm">
                                    <i class="bi bi-trash mr-1"></i>Supprimer
                                </button>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Capture d'écran du SMS de confirmation ou reçu de paiement</p>
                    </div>

                    <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white py-3 px-6 rounded-lg font-semibold transition-colors">
                        <i class="bi bi-plus-circle mr-2"></i>Confirmer le dépôt
                    </button>
                </form>
            </div>

            <!-- Historique des dépôts -->
            <div class="bg-white rounded-xl p-6 shadow-md">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Historique des dépôts</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg border border-green-200">
                        <div>
                            <p class="font-medium text-gray-900">50 000 Ar</p>
                            <p class="text-sm text-gray-600">Mobile Money - Aujourd'hui 14:30</p>
                        </div>
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Confirmé</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <div>
                            <p class="font-medium text-gray-900">25 000 Ar</p>
                            <p class="text-sm text-gray-600">Virement bancaire - Hier 10:15</p>
                        </div>
                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">En attente</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg border border-green-200">
                        <div>
                            <p class="font-medium text-gray-900">75 000 Ar</p>
                            <p class="text-sm text-gray-600">Mobile Money - 3 jours 16:20</p>
                        </div>
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Confirmé</span>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- PAGE RETRAIT -->
    <div id="withdraw-page" class="page-content hidden">
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-20 md:pb-6">
            <div class="bg-white rounded-xl p-6 shadow-md mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Demander un retrait</h2>
                
                <!-- Solde disponible -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Solde disponible:</span>
                        <span class="text-xl font-bold text-primary">125 450 Ar</span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Montant minimum de retrait: 5 000 Ar</p>
                </div>

                <!-- Formulaire de retrait -->
                <form id="withdrawForm" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Montant du retrait</label>
                        <div class="relative">
                            <input type="number" id="withdrawAmount" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Entrez le montant" min="5000" max="125450">
                            <span class="absolute right-3 top-3 text-gray-500">Ar</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Méthode de retrait</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="withdrawMethod" value="mobile" class="text-primary focus:ring-primary">
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <i class="bi bi-phone text-blue-600 text-xl mr-2"></i>
                                        <span class="font-medium">Mobile Money</span>
                                    </div>
                                    <p class="text-sm text-gray-500">Frais: 2%</p>
                                </div>
                            </label>
                            <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="withdrawMethod" value="bank" class="text-primary focus:ring-primary">
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <i class="bi bi-bank text-green-600 text-xl mr-2"></i>
                                        <span class="font-medium">Virement bancaire</span>
                                    </div>
                                    <p class="text-sm text-gray-500">Frais: 1 000 Ar</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div id="withdrawMobileDetails" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Numéro de téléphone</label>
                        <input type="tel" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="034 XX XXX XX">
                    </div>

                    <div id="withdrawBankDetails" class="hidden space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nom de la banque</label>
                            <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option>Choisir une banque</option>
                                <option>BNI Madagascar</option>
                                <option>BOA Madagascar</option>
                                <option>BFV-SG</option>
                                <option>Autre</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Numéro de compte</label>
                            <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Numéro de compte bancaire">
                        </div>
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

                    <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white py-3 px-6 rounded-lg font-semibold transition-colors">
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
                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">En cours</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg border border-green-200">
                        <div>
                            <p class="font-medium text-gray-900">50 000 Ar</p>
                            <p class="text-sm text-gray-600">Virement bancaire - 2 jours 14:30</p>
                        </div>
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Terminé</span>
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
        </main>
    </div>

    <!-- PAGE PARRAINAGE -->
    <div id="referral-page" class="page-content hidden">
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
                        <input type="text" id="referralLink" value="https://smart-mg.com/ref/JD2024" readonly class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-700">
                    </div>
                    <button onclick="copyReferralLink()" class="bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-lg font-medium">
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
                        <div class="bg-primary text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                            <span class="font-bold">1</span>
                        </div>
                        <p class="font-medium text-gray-900">Niveau 1</p>
                        <p class="text-primary font-bold">5%</p>
                        <p class="text-sm text-gray-600">Filleuls directs</p>
                    </div>
                    <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="bg-blue-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                            <span class="font-bold">2</span>
                        </div>
                        <p class="font-medium text-gray-900">Niveau 2</p>
                        <p class="text-blue-600 font-bold">3%</p>
                        <p class="text-sm text-gray-600">Filleuls indirects</p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg border border-purple-200">
                        <div class="bg-purple-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
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
                    <button onclick="showLevelTab(1)" class="level-tab flex-1 py-2 px-4 rounded-md text-sm font-medium bg-primary text-white">
                        Niveau 1 <span class="ml-1 bg-white bg-opacity-20 px-2 py-1 rounded-full text-xs">2</span>
                    </button>
                    <button onclick="showLevelTab(2)" class="level-tab flex-1 py-2 px-4 rounded-md text-sm font-medium text-gray-600 hover:text-gray-900">
                        Niveau 2 <span class="ml-1 bg-gray-200 px-2 py-1 rounded-full text-xs">3</span>
                    </button>
                    <button onclick="showLevelTab(3)" class="level-tab flex-1 py-2 px-4 rounded-md text-sm font-medium text-gray-600 hover:text-gray-900">
                        Niveau 3 <span class="ml-1 bg-gray-200 px-2 py-1 rounded-full text-xs">1</span>
                    </button>
                    <button onclick="showLevelTab(4)" class="level-tab flex-1 py-2 px-4 rounded-md text-sm font-medium text-gray-600 hover:text-gray-900">
                        Niveau 4 <span class="ml-1 bg-gray-200 px-2 py-1 rounded-full text-xs">0</span>
                    </button>
                </div>
                
                <!-- Contenu Niveau 1 -->
                <div id="level-1-content" class="level-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <div class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold">
                                        MR
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-900">Marie Rakoto</p>
                                        <p class="text-sm text-gray-500">ID: MR001</p>
                                    </div>
                                </div>
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Actif</span>
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
                                    <div class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold">
                                        JK
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-900">Jean Koto</p>
                                        <p class="text-sm text-gray-500">ID: JK002</p>
                                    </div>
                                </div>
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Actif</span>
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
                                    <div class="bg-green-500 text-white w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold">
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
                                    <div class="bg-green-500 text-white w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold">
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
                                    <div class="bg-green-500 text-white w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold">
                                        SF
                                    </div>
                                    <div class="ml-2">
                                        <p class="font-medium text-gray-900 text-sm">Sophie Faly</p>
                                        <p class="text-xs text-gray-500">Parrain: Jean K.</p>
                                    </div>
                                </div>
                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Inactif</span>
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
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date d'inscription</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Niveau</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dépôts totaux</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vos gains</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <div class="bg-primary text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium">
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
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Niveau 2</span>
                                </td>
                                <td class="px-4 py-4 text-sm font-medium text-gray-900">125 000 Ar</td>
                                <td class="px-4 py-4 text-sm font-bold text-primary">6 250 Ar</td>
                                <td class="px-4 py-4">
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Actif</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <div class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium">
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
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-medium">Niveau 1</span>
                                </td>
                                <td class="px-4 py-4 text-sm font-medium text-gray-900">75 000 Ar</td>
                                <td class="px-4 py-4 text-sm font-bold text-primary">3 750 Ar</td>
                                <td class="px-4 py-4">
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Actif</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <div class="bg-purple-600 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium">
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
                                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs font-medium">Niveau 0</span>
                                </td>
                                <td class="px-4 py-4 text-sm font-medium text-gray-900">0 Ar</td>
                                <td class="px-4 py-4 text-sm font-bold text-gray-400">0 Ar</td>
                                <td class="px-4 py-4">
                                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs font-medium">Inactif</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- PAGE NIVEAUX -->
    <div id="levels-page" class="page-content hidden">
        <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-20 md:pb-6">
            <!-- Niveau actuel -->
            <div class="bg-gradient-to-r from-primary to-primary-light rounded-xl p-6 text-white mb-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm">Votre niveau actuel</p>
                        <p class="text-3xl font-bold">Niveau 3</p>
                        <p class="text-green-100 text-sm mt-1">Solde: 125 450 Ar</p>
                    </div>
                    <div class="text-right">
                        <div class="bg-white bg-opacity-20 rounded-lg p-3">
                            <i class="bi bi-star-fill text-3xl"></i>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="bg-white bg-opacity-20 rounded-full h-2">
                        <div class="bg-white h-2 rounded-full" style="width: 83.6%"></div>
                    </div>
                    <p class="text-green-100 text-sm mt-1">12 540 / 15 000 Ar pour le niveau suivant</p>
                </div>
            </div>

            <!-- Liste des niveaux -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Niveau 1 -->
                <div class="bg-white rounded-xl p-6 shadow-md border-2 border-green-200">
                    <div class="text-center">
                        <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="bi bi-star-fill text-primary text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Niveau 1</h3>
                        <p class="text-primary font-bold text-lg mb-4">1 000 Ar</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-6">
                            <p>✓ Accès au tableau de bord</p>
                            <p>✓ Dépôts et retraits</p>
                            <p>✓ Support client</p>
                        </div>
                        <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-medium">
                            <i class="bi bi-check-circle mr-1"></i>Débloqué
                        </span>
                    </div>
                </div>

                <!-- Niveau 2 -->
                <div class="bg-white rounded-xl p-6 shadow-md border-2 border-green-200">
                    <div class="text-center">
                        <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="bi bi-star-fill text-primary text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Niveau 2</h3>
                        <p class="text-primary font-bold text-lg mb-4">5 000 Ar</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-6">
                            <p>✓ Toutes fonctions niveau 1</p>
                            <p>✓ Parrainage niveau 1</p>
                            <p>✓ Bonus 3%</p>
                        </div>
                        <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-medium">
                            <i class="bi bi-check-circle mr-1"></i>Débloqué
                        </span>
                    </div>
                </div>

                <!-- Niveau 3 -->
                <div class="bg-white rounded-xl p-6 shadow-md border-2 border-green-200">
                    <div class="text-center">
                        <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="bi bi-star-fill text-primary text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Niveau 3</h3>
                        <p class="text-primary font-bold text-lg mb-4">10 000 Ar</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-6">
                            <p>✓ Toutes fonctions niveau 2</p>
                            <p>✓ Parrainage niveau 2</p>
                            <p>✓ Bonus 5%</p>
                            <p>✓ Support prioritaire</p>
                        </div>
                        <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-medium">
                            <i class="bi bi-check-circle mr-1"></i>Actuel
                        </span>
                    </div>
                </div>

                <!-- Niveau 4 -->
                <div class="bg-white rounded-xl p-6 shadow-md border-2 border-yellow-300 relative">
                    <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                        <span class="bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full text-xs font-bold">NOUVEAU</span>
                    </div>
                    <div class="text-center">
                        <div class="bg-yellow-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="bi bi-star-fill text-yellow-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Niveau 4</h3>
                        <p class="text-yellow-600 font-bold text-lg mb-4">15 000 Ar</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-6">
                            <p>✓ Toutes fonctions niveau 3</p>
                            <p>✓ Parrainage niveau 3</p>
                            <p>✓ Bonus 7%</p>
                            <p>✓ Retraits prioritaires</p>
                            <p>✓ Gestionnaire dédié</p>
                        </div>
                        <button onclick="unlockLevel(4)" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-full text-sm font-medium transition-colors">
                            <i class="bi bi-unlock mr-1"></i>Débloquer
                        </button>
                        <p class="text-xs text-gray-500 mt-2">Il vous manque 2 460 Ar</p>
                    </div>
                </div>
            </div>

            <!-- Avantages par niveau -->
            <div class="bg-white rounded-xl p-6 shadow-md mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Comparaison des avantages</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fonctionnalité</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Niveau 1</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Niveau 2</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Niveau 3</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Niveau 4</th>
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
            <a href="#" onclick="showPage('dashboard')" class="mobile-bottom-nav flex flex-col items-center py-2 text-primary">
                <i class="bi bi-house-fill text-xl"></i>
                <span class="text-xs mt-1">Accueil</span>
            </a>
            <a href="#" onclick="showPage('deposit')" class="mobile-bottom-nav flex flex-col items-center py-2 text-gray-600">
                <i class="bi bi-plus-circle text-xl"></i>
                <span class="text-xs mt-1">Dépôt</span>
            </a>
            <a href="#" onclick="showPage('withdraw')" class="mobile-bottom-nav flex flex-col items-center py-2 text-gray-600">
                <i class="bi bi-dash-circle text-xl"></i>
                <span class="text-xs mt-1">Retrait</span>
            </a>
            <a href="#" onclick="showPage('referral')" class="mobile-bottom-nav flex flex-col items-center py-2 text-gray-600">
                <i class="bi bi-people text-xl"></i>
                <span class="text-xs mt-1">Parrainage</span>
            </a>
            <a href="#" onclick="showPage('levels')" class="mobile-bottom-nav flex flex-col items-center py-2 text-gray-600">
                <i class="bi bi-star text-xl"></i>
                <span class="text-xs mt-1">Niveaux</span>
            </a>
        </div>
    </nav>

    <script>
        // Variables globales
        let currentPage = 'dashboard';
        let balanceChart;

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
        document.getElementById('menuToggle').addEventListener('click', function() {
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
                                callback: function(value) {
                                    return value.toLocaleString() + ' Ar';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Gestion du formulaire de dépôt
        document.addEventListener('DOMContentLoaded', function() {
            // Formulaire de dépôt
            const depositForm = document.getElementById('depositForm');
            if (depositForm) {
                const paymentMethods = depositForm.querySelectorAll('input[name="paymentMethod"]');
                const mobileDetails = document.getElementById('mobileMoneyDetails');
                const bankDetails = document.getElementById('bankDetails');
                
                paymentMethods.forEach(method => {
                    method.addEventListener('change', function() {
                        if (this.value === 'mobile') {
                            mobileDetails.classList.remove('hidden');
                            bankDetails.classList.add('hidden');
                        } else if (this.value === 'bank') {
                            bankDetails.classList.remove('hidden');
                            mobileDetails.classList.add('hidden');
                        }
                    });
                });
                
                depositForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const amount = document.getElementById('depositAmount').value;
                    const transactionId = document.getElementById('transactionId').value;
                    const proofFile = document.getElementById('proofUpload').files[0];
                    
                    if (amount && amount >= 1000 && transactionId && proofFile) {
                        alert(`Demande de dépôt de ${parseInt(amount).toLocaleString()} Ar envoyée avec succès !\nID transaction: ${transactionId}\nPreuve jointe: ${proofFile.name}`);
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
            const withdrawForm = document.getElementById('withdrawForm');
            if (withdrawForm) {
                const withdrawMethods = withdrawForm.querySelectorAll('input[name="withdrawMethod"]');
                const withdrawMobileDetails = document.getElementById('withdrawMobileDetails');
                const withdrawBankDetails = document.getElementById('withdrawBankDetails');
                const withdrawSummary = document.getElementById('withdrawSummary');
                const withdrawAmount = document.getElementById('withdrawAmount');
                
                withdrawMethods.forEach(method => {
                    method.addEventListener('change', function() {
                        if (this.value === 'mobile') {
                            withdrawMobileDetails.classList.remove('hidden');
                            withdrawBankDetails.classList.add('hidden');
                        } else if (this.value === 'bank') {
                            withdrawBankDetails.classList.remove('hidden');
                            withdrawMobileDetails.classList.add('hidden');
                        }
                        updateWithdrawSummary();
                    });
                });
                
                withdrawAmount.addEventListener('input', updateWithdrawSummary);
                
                function updateWithdrawSummary() {
                    const amount = parseInt(withdrawAmount.value) || 0;
                    const selectedMethod = withdrawForm.querySelector('input[name="withdrawMethod"]:checked');
                    
                    if (amount > 0 && selectedMethod) {
                        let fees = 0;
                        if (selectedMethod.value === 'mobile') {
                            fees = Math.round(amount * 0.02); // 2%
                        } else if (selectedMethod.value === 'bank') {
                            fees = 1000; // 1000 Ar fixe
                        }
                        
                        const finalAmount = amount - fees;
                        
                        document.getElementById('requestedAmount').textContent = amount.toLocaleString() + ' Ar';
                        document.getElementById('withdrawFees').textContent = fees.toLocaleString() + ' Ar';
                        document.getElementById('finalAmount').textContent = finalAmount.toLocaleString() + ' Ar';
                        
                        withdrawSummary.classList.remove('hidden');
                    } else {
                        withdrawSummary.classList.add('hidden');
                    }
                }
                
                withdrawForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const amount = parseInt(withdrawAmount.value);
                    if (amount && amount >= 5000 && amount <= 125450) {
                        alert(`Demande de retrait de ${amount.toLocaleString()} Ar envoyée avec succès !`);
                        this.reset();
                        withdrawMobileDetails.classList.add('hidden');
                        withdrawBankDetails.classList.add('hidden');
                        withdrawSummary.classList.add('hidden');
                    } else {
                        alert('Veuillez entrer un montant valide (entre 5 000 et 125 450 Ar)');
                    }
                });
            }
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
            const currentBalance = 125450;
            const requiredAmount = 15000;
            
            if (currentBalance >= requiredAmount) {
                alert(`Félicitations ! Vous avez débloqué le niveau ${level} !`);
                // Ici, vous pourriez mettre à jour l'interface pour refléter le nouveau niveau
            } else {
                const missing = requiredAmount - currentBalance;
                alert(`Il vous manque ${missing.toLocaleString()} Ar pour débloquer ce niveau.`);
            }
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
            reader.onload = function(e) {
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
        document.addEventListener('DOMContentLoaded', function() {
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
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'96f062e247164ec8',t:'MTc1NTE3MzgxNy4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
