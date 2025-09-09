<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMART-FAST - Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

<body class="bg-gradient-to-br from-green-50 to-blue-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm ">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-center items-center h-16">
                <h1 class="text-3xl font-bold text-primary " style="font-family: 'Courier New', Courier, monospace;">
                    SMART-FAST</h1>
            </div>
        </div>
    </header>

    <!-- Container Principal -->
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo et titre -->
            <div class="text-center">
                <!-- <div class=" bg-primary w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-graph-up-arrow text-white text-3xl"></i>
                </div> -->
                <h2 class="text-3xl font-bold text-gray-900 mb-2"
                    style="font-family: 'Courier New', Courier, monospace;">Bienvenue sur <span
                        style="font-family: 'Courier New', Courier, monospace;">SMART-FAST</span></h2>
                <p class="text-gray-600">Votre plateforme d'investissement de confiance</p>
            </div>

            <div id="alertSuccess"
                class="hidden relative border-l-4 border-green-500 bg-green-100 text-green-800 px-4 py-3 my-2 rounded"
                role="alert">
                <span id="alertSuccessMessage">Opération réussie !</span>
            </div>

            <div id="alertError" class="hidden border-l-4 border-red-500 bg-red-100 text-red-800 px-4 py-3 my-2 rounded"
                role="alert">
                Numéro de téléphone ou mot de passe incorrect
            </div>

            <!-- Onglets Connexion/Inscription -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="flex mb-6 bg-gray-100 p-1 rounded-lg">
                    <button id="loginTab" onclick="showTab('login')"
                        class="flex-1 py-2 px-4 rounded-md text-sm font-medium bg-primary text-white transition-all">
                        Connexion
                    </button>
                    <button id="registerTab" onclick="showTab('register')"
                        class="flex-1 py-2 px-4 rounded-md text-sm font-medium text-gray-600 hover:text-gray-900 transition-all">
                        Inscription
                    </button>
                </div>

                <!-- FORMULAIRE DE CONNEXION -->
                <div id="loginForm" class="auth-form">
                    <!-- <form action="auth.php" class="space-y-6" method="POST"> -->
                    <form onsubmit="handleLogin(event)" class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bi bi-phone mr-2"></i>Numéro de téléphone
                            </label>
                            <input type="tel" name="telephone" id="loginPhone" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                placeholder="034 XX XXX XX">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bi bi-lock mr-2"></i>Mot de passe
                            </label>
                            <div class="relative">
                                <input type="password" name="mot_de_passe" id="loginPassword" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all pr-12"
                                    placeholder="Votre mot de passe">
                                <button type="button" onclick="togglePassword('loginPassword')"
                                    class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                                    <i class="bi bi-eye" id="loginPasswordIcon"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full bg-primary hover:bg-primary-dark text-white py-3 px-6 rounded-lg font-semibold transition-all duration-200 transform hover:scale-105">
                            <i class="bi bi-box-arrow-in-right mr-2"></i>Se connecter
                        </button>
                    </form>

                    <!-- Séparateur -->
                    <div class="mt-6 mb-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-gray-500">Première fois ?</span>
                            </div>
                        </div>
                    </div>

                    <button onclick="showTab('register')"
                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 px-6 rounded-lg font-medium transition-all">
                        Créer un compte
                    </button>
                </div>

                <!-- FORMULAIRE D'INSCRIPTION -->
                <div id="registerForm" class="auth-form hidden">
                    <form onsubmit="handleRegister(event)" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="bi bi-person mr-2"></i>Nom
                                </label>
                                <input type="text" id="registerLastName" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                    placeholder="Votre nom">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="bi bi-person mr-2"></i>Prénom
                                </label>
                                <input type="text" id="registerFirstName" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                    placeholder="Votre prénom">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bi bi-phone mr-2"></i>Numéro de téléphone
                            </label>
                            <input type="tel" id="registerPhone" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                placeholder="034 XX XXX XX">
                            <p class="text-xs text-gray-500 mt-1">Ce numéro servira pour vos connexions</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bi bi-lock mr-2"></i>Mot de passe
                            </label>
                            <div class="relative">
                                <input type="password" id="registerPassword" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all pr-12"
                                    placeholder="Choisissez un mot de passe">
                                <button type="button" onclick="togglePassword('registerPassword')"
                                    class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                                    <i class="bi bi-eye" id="registerPasswordIcon"></i>
                                </button>
                            </div>
                            <div id="passwordStrength" class="mt-2 hidden">
                                <div class="flex space-x-1">
                                    <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                                    <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                                    <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                                    <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Force du mot de passe</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bi bi-shield-check mr-2"></i>Confirmer le mot de passe
                            </label>
                            <div class="relative">
                                <input type="password" id="registerConfirmPassword" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all pr-12"
                                    placeholder="Confirmez votre mot de passe">
                                <button type="button" onclick="togglePassword('registerConfirmPassword')"
                                    class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                                    <i class="bi bi-eye" id="registerConfirmPasswordIcon"></i>
                                </button>
                            </div>
                            <div id="passwordMatch" class="mt-1 text-xs hidden">
                                <span class="text-red-600"><i class="bi bi-x-circle mr-1"></i>Les mots de passe ne
                                    correspondent pas</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bi bi-people mr-2"></i>Code Parrain <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="registerSponsorId" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                placeholder="Ex: JD2024">
                            <p class="text-xs text-gray-500 mt-1">Entrez l'ID de votre parrain (obligatoire)</p>
                            <div id="sponsorValidation" class="mt-1 text-xs hidden">
                                <span class="text-green-600"><i class="bi bi-check-circle mr-1"></i>Parrain trouvé: Jean
                                    Dupont</span>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full bg-primary hover:bg-primary-dark text-white py-3 px-6 rounded-lg font-semibold transition-all duration-200 transform hover:scale-105">
                            <i class="bi bi-person-plus mr-2"></i>Créer mon compte
                        </button>
                    </form>

                    <!-- Séparateur -->
                    <div class="mt-6 mb-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-gray-500">Déjà inscrit ?</span>
                            </div>
                        </div>
                    </div>

                    <button onclick="showTab('login')"
                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 px-6 rounded-lg font-medium transition-all">
                        Se connecter
                    </button>
                </div>
            </div>

            <!-- Avantages -->
            <div class="bg-white rounded-xl shadow-lg p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 text-center">Pourquoi choisir SMART-FAST ?</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="bi bi-shield-check text-primary text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-900">Sécurisé</p>
                        <p class="text-xs text-gray-600">Plateforme 100% sécurisée</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="bi bi-graph-up text-blue-600 text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-900">Rentable</p>
                        <p class="text-xs text-gray-600">Investissements performants</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-purple-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="bi bi-people text-purple-600 text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-900">Parrainage</p>
                        <p class="text-xs text-gray-600">Gagnez avec vos filleuls</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-gray-600 text-sm mb-4">© 2025 SMART-FAST. Tous droits réservés.</p>

                <!-- Réseaux sociaux -->
                <div class="flex justify-center space-x-4 mb-4">
                    <a href="https://facebook.com" target="_blank"
                        class="bg-blue-600 hover:bg-blue-700 text-white w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200 transform hover:scale-110">
                        <i class="bi bi-facebook text-lg"></i>
                    </a>
                    <a href="https://wa.me/261341234567" target="_blank"
                        class="bg-green-500 hover:bg-green-600 text-white w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200 transform hover:scale-110">
                        <i class="bi bi-whatsapp text-lg"></i>
                    </a>
                </div>

                <div class="space-x-4">
                    <a href="#" class="text-gray-500 hover:text-primary text-sm">Support</a>
                    <a href="#" class="text-gray-500 hover:text-primary text-sm">FAQ</a>
                    <a href="#" class="text-gray-500 hover:text-primary text-sm">Contact</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- CDN POP UP -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Variables globales
        let currentTab = 'login';

        // Gestion des onglets
        function showTab(tabName) {
            // Masquer tous les formulaires
            document.querySelectorAll('.auth-form').forEach(form => {
                form.classList.add('hidden');
            });

            // Réinitialiser les onglets
            document.getElementById('loginTab').classList.remove('bg-primary', 'text-white');
            document.getElementById('loginTab').classList.add('text-gray-600', 'hover:text-gray-900');
            document.getElementById('registerTab').classList.remove('bg-primary', 'text-white');
            document.getElementById('registerTab').classList.add('text-gray-600', 'hover:text-gray-900');

            // Afficher le formulaire sélectionné
            if (tabName === 'login') {
                document.getElementById('loginForm').classList.remove('hidden');
                document.getElementById('loginTab').classList.remove('text-gray-600', 'hover:text-gray-900');
                document.getElementById('loginTab').classList.add('bg-primary', 'text-white');
            } else {
                document.getElementById('registerForm').classList.remove('hidden');
                document.getElementById('registerTab').classList.remove('text-gray-600', 'hover:text-gray-900');
                document.getElementById('registerTab').classList.add('bg-primary', 'text-white');
            }

            currentTab = tabName;
        }

        // Basculer la visibilité du mot de passe
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId + 'Icon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }

        // Validation du mot de passe en temps réel
        function checkPasswordStrength(password) {
            const strengthIndicator = document.getElementById('passwordStrength');
            const bars = strengthIndicator.querySelectorAll('div div');

            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            // Réinitialiser les barres
            bars.forEach(bar => {
                bar.classList.remove('bg-red-500', 'bg-yellow-500', 'bg-green-500');
                bar.classList.add('bg-gray-200');
            });

            // Colorer selon la force
            const colors = ['bg-red-500', 'bg-red-500', 'bg-yellow-500', 'bg-green-500'];
            const color = colors[strength - 1] || 'bg-gray-200';

            for (let i = 0; i < strength; i++) {
                bars[i].classList.remove('bg-gray-200');
                bars[i].classList.add(color);
            }

            strengthIndicator.classList.remove('hidden');
        }

        // Vérifier la correspondance des mots de passe
        function checkPasswordMatch() {
            const password = document.getElementById('registerPassword').value;
            const confirmPassword = document.getElementById('registerConfirmPassword').value;
            const matchIndicator = document.getElementById('passwordMatch');

            if (confirmPassword.length > 0) {
                if (password === confirmPassword) {
                    matchIndicator.innerHTML =
                        '<span class="text-green-600"><i class="bi bi-check-circle mr-1"></i>Les mots de passe correspondent</span>';
                    matchIndicator.classList.remove('hidden');
                } else {
                    matchIndicator.innerHTML =
                        '<span class="text-red-600"><i class="bi bi-x-circle mr-1"></i>Les mots de passe ne correspondent pas</span>';
                    matchIndicator.classList.remove('hidden');
                }
            } else {
                matchIndicator.classList.add('hidden');
            }
        }

        // Valider l'ID du parrain
        let sponsorTimeout;

        async function validateSponsorId(sponsorId) {
            const validationDiv = document.getElementById('sponsorValidation');
            clearTimeout(sponsorTimeout);

            if (sponsorId.length > 0) {
                sponsorTimeout = setTimeout(async () => {
                    try {
                        const response = await fetch('validate_sponsor_id.php', {
                            method: 'POST',
                            body: new URLSearchParams({
                                // parrain_id: sponsorId
                                parrain_code: sponsorId 
                            })
                        });

                        const result = await response.json();

                        if (result.success) {
                            validationDiv.innerHTML =
                                `<span class="text-green-600"><i class="bi bi-check-circle mr-1"></i>Parrain trouvé </span>`;
                        } else {
                            validationDiv.innerHTML =
                                '<span class="text-red-600"><i class="bi bi-x-circle mr-1"></i>ID parrain non trouvé</span>';
                        }

                        validationDiv.classList.remove('hidden');
                    } catch (error) {
                        console.error('Erreur lors de la validation du parrain:', error);
                        validationDiv.innerHTML =
                            '<span class="text-red-600"><i class="bi bi-exclamation-triangle mr-1"></i>Erreur serveur</span>';
                        validationDiv.classList.remove('hidden');
                    }
                }, 500); // petit délai pour éviter trop de requêtes
            } else {
                validationDiv.classList.add('hidden');
            }
        }


        // Gestion de la connexion
        async function handleLogin(event) {
            event.preventDefault();

            const phone = document.getElementById('loginPhone').value.trim();
            const password = document.getElementById('loginPassword').value.trim();
            const alertDiv = document.getElementById('alertError');
            const alertDivSucces = document.getElementById('alertSuccess');

            // Masquer l'alerte au début
            alertDiv.classList.add('hidden');

            if (!phone || !password) {
                alertDiv.textContent = 'Veuillez remplir tous les champs';
                alertDiv.classList.remove('hidden');
                return;
            }

            const loadingButton = event.target.querySelector('button[type="submit"]');
            const originalText = loadingButton.innerHTML;
            loadingButton.innerHTML = '<i class="bi bi-hourglass-split mr-2"></i>Connexion...';
            loadingButton.disabled = true;

            try {
                const response = await fetch('auth.php', {
                    method: 'POST',
                    body: new URLSearchParams({
                        telephone: phone,
                        mot_de_passe: password
                    })
                });

                const result = await response.json();

                if (result.success) {
                    // Masquer l'alerte d'erreur si elle est affichée
                    alertDiv.classList.add('hidden');

                    // Afficher l'alerte succès
                    alertDivSucces.textContent = `Connexion réussie ! Bonjour ${result.user.prenom} `;
                    alertDivSucces.classList.remove('hidden');

                    // Masquer automatiquement après 3 secondes
                    setTimeout(() => {
                        alertDivSucces.classList.add('hidden');
                        window.location.href = "app.php";
                    }, 1500);
                } else {
                    // Afficher l'alerte erreur
                    alertDiv.textContent = result.message;
                    alertDiv.classList.remove('hidden');
                }

            } catch (error) {
                console.error('Erreur:', error);
                alertDiv.textContent = 'Erreur de connexion au serveur.'; // message serveur
                alertDiv.classList.remove('hidden'); // afficher
            } finally {
                loadingButton.innerHTML = originalText;
                loadingButton.disabled = false;
            }
        }


        // Gestion de l'inscription
        async function handleRegister(event) {
            console.log('Cliquer')
            event.preventDefault();

            const firstName = document.getElementById('registerFirstName').value.trim();
            const lastName = document.getElementById('registerLastName').value.trim();
            const phone = document.getElementById('registerPhone').value.trim();
            const password = document.getElementById('registerPassword').value;
            const confirmPassword = document.getElementById('registerConfirmPassword').value;
            const sponsorId = document.getElementById('registerSponsorId').value.trim();
            // const acceptTerms = document.getElementById('acceptTerms').checked;

            // 🔹 Validations côté client
            if (!firstName || !lastName || !phone || !password || !confirmPassword || !sponsorId) {
                alert('Veuillez remplir tous les champs obligatoires');
                return;
            }

            if (password !== confirmPassword) {
                alert('Les mots de passe ne correspondent pas');
                return;
            }

            if (password.length < 6) {
                alert('Le mot de passe doit contenir au moins 6 caractères');
                return;
            }

            const phoneRegex = /^(032|033|034|038)\d{7}$/;
            if (!phoneRegex.test(phone.replace(/\s/g, ''))) {
                alert('Format de numéro de téléphone invalide');
                return;
            }

            // 🔹 Bouton en état "chargement"
            const loadingButton = event.target.querySelector('button[type="submit"]');
            const originalText = loadingButton.innerHTML;
            loadingButton.innerHTML = '<i class="bi bi-hourglass-split mr-2"></i>Création du compte...';
            loadingButton.disabled = true;

            try {
                // 🔹 Envoi au backend
                const response = await fetch('register.php', {
                    method: 'POST',
                    body: new URLSearchParams({
                        nom: lastName,
                        prenom: firstName,
                        telephone: phone,
                        mot_de_passe: password,
                        confirm_mot_de_passe: confirmPassword,
                        // parrain_id: sponsorId
                        parrain_code: sponsorId
                    })
                });

                const result = await response.json();

                if (result.success) {
                    // alert(`Inscription réussie ! Bienvenue ${firstName} ${lastName} 🎉\nVotre ID: ${result.user_id}`);
                    Swal.fire({
                        title: '🎉 Inscription réussie !',
                        html: `Bienvenue <b>${firstName} ${lastName}</b><br>Votre code parrain : <b>${result.code_utilisateur}</b>`,
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#0ED584', // Vert
                        background: '#FFFFFF', // Blanc
                        color: '#000000', // Noir (texte)
                    });
                    // Réinitialiser le formulaire et basculer vers login
                    event.target.reset();
                    document.getElementById('passwordStrength').classList.add('hidden');
                    document.getElementById('passwordMatch').classList.add('hidden');
                    document.getElementById('sponsorValidation').classList.add('hidden');
                    showTab('login');
                } else {
                    alert(result.message);
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur lors de l\'inscription. Veuillez réessayer.');
            } finally {
                loadingButton.innerHTML = originalText;
                loadingButton.disabled = false;
            }
        }


        // Event listeners
        document.addEventListener('DOMContentLoaded', function () {
            // Validation du mot de passe en temps réel
            const registerPassword = document.getElementById('registerPassword');
            const registerConfirmPassword = document.getElementById('registerConfirmPassword');
            const registerSponsorId = document.getElementById('registerSponsorId');

            if (registerPassword) {
                registerPassword.addEventListener('input', function () {
                    checkPasswordStrength(this.value);
                    if (registerConfirmPassword.value) {
                        checkPasswordMatch();
                    }
                });
            }

            if (registerConfirmPassword) {
                registerConfirmPassword.addEventListener('input', checkPasswordMatch);
            }

            if (registerSponsorId) {
                registerSponsorId.addEventListener('input', function () {
                    validateSponsorId(this.value);
                });
            }

            // Formatage automatique du numéro de téléphone
            const phoneInputs = document.querySelectorAll('input[type="tel"]');
            phoneInputs.forEach(input => {
                input.addEventListener('input', function () {
                    let value = this.value.replace(/\D/g, ''); // garder uniquement chiffres
                    let formatted = '';

                    if (value.length > 0) {
                        formatted = value.substring(0, 3); // les 3 premiers
                    }
                    if (value.length > 3) {
                        formatted += ' ' + value.substring(3, 5); // 2 chiffres
                    }
                    if (value.length > 5) {
                        formatted += ' ' + value.substring(5, 8); // 3 chiffres
                    }
                    if (value.length > 8) {
                        formatted += ' ' + value.substring(8, 10); // 2 chiffres
                    }

                    this.value = formatted;
                });
            });


            // Animation d'entrée
            const container = document.querySelector('.max-w-md');
            container.style.opacity = '0';
            container.style.transform = 'translateY(20px)';

            setTimeout(() => {
                container.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>

</body>

</html>