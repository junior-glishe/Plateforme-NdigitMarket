<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion </title>
    <link rel="stylesheet" href="./assets/CSS/style.css">

    <meta name="description" content="Connectez-vous à votre espace MediTrace pour accéder à la gestion des hospitalisations.">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#0d6b4e",
                        accent: "#0891b2"
                    },
                    fontFamily: {
                        sans: ["Inter", "sans-serif"]
                    }
                }
            }
        };
    </script>

    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>

<body class="bg-gradient-custom min-h-screen">

    <?php
    // session_start();
    // require('../include/connect.php');

    $error = false;
    $success = false;

    if (isset($_POST['envoyer'])) {
        $mdp = htmlspecialchars($_POST['mdp']);
        $email = htmlspecialchars($_POST['email']);

        // À remplacer par votre requête SQL réelle
        $resultats = $database->query('SELECT * FROM admin');
        $a = false;
        while ($donnee = $resultats->fetch()) {
            if ($donnee['email'] == $email && $donnee['mdp'] == $mdp) {
                $_SESSION["admin"] = "oui";
                $_SESSION["email"] = $email;
                $success = true;
                $a = true;
            }
        }
        if ($a == false) {
            $error = true;
        }

        // Exemple de validation - À remplacer par votre logique BDD
        $admin_email = "";
        $admin_password = "";

        if ($email == $admin_email && $mdp == $admin_password) {
            $_SESSION["admin"] = "oui";
            $_SESSION["email"] = $email;
            $success = true;
        } else {
            $error = true;
        }
    }
    ?>

    <?php if ($success == true): ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: "success",
                title: "Connexion réussie !",
                text: "Vous allez être redirigé vers le tableau de bord.",
                confirmButtonColor: "#087d67",
                timer: 2000,
                timerProgressBar: true
            }).then((result) => {
                window.location.href = "apps/index";
            });
        </script>
    <?php endif; ?>

    <div class="flex min-h-screen">
        <!-- Section gauche - Formulaire -->
        <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12 overflow-y-auto">
            <div class="max-w-md w-full">
                <!-- Logo -->
                <div class="text-center mb-8">
                    <div class="flex justify-center mb-4">
                        <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center overflow-hidden">

                            <img
                                src="./assets/images/favi.png"
                                alt="icon"
                                class="w-8 h-8 object-contain">

                        </div>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800">NDIGIT<span class="text-primary">MARKET</span></h1>
                    <p class="text-gray-500 text-sm mt-2">Connectez-vous à votre espace</p>
                </div>

                <!-- Message d'erreur PHP -->
                <?php if ($error == true): ?>
                    <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm text-center flex items-center justify-center gap-2">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        Email ou mot de passe incorrect
                    </div>
                <?php endif; ?>

                <!-- Formulaire -->
                <form id="loginForm" method="POST" class="space-y-6">
                    <input type="hidden" id="selectedRole" name="role" value="">

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Adresse email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white input-focus transition"
                            placeholder="exemple@gmail.com"
                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>

                    <!-- Mot de passe -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                        <input
                            type="password"
                            id="password"
                            name="mdp"
                            required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white input-focus transition"
                            placeholder="••••••••">
                    </div>

                    <!-- Options -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/20">
                            <span class="text-sm text-gray-600">Se souvenir de moi</span>
                        </label>
                    </div>

                    <!-- Bouton connexion -->
                    <button
                        type="submit"
                        name="envoyer"
                        class="w-full py-3 bg-primary text-white rounded-xl font-medium hover:bg-primary/90 transition">
                        Se connecter
                    </button>
                </form>

                <!-- Message d'erreur (JS - conservé) -->
                <div id="errorMsg" class="hidden mt-4 p-3 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm text-center">
                    Email, mot de passe ou rôle incorrect
                </div>

                <!-- Message alerte rôle (JS - conservé) -->
                <div id="roleAlert" class="hidden mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-xl text-yellow-700 text-sm text-center">
                    Veuillez sélectionner votre rôle
                </div>

                <!-- Lien retour accueil -->
                <div class="text-center mt-6">
                    <a href="index.html" class="text-sm text-gray-500 hover:text-primary transition">← Retour à l'accueil</a>
                </div>

                <!-- Footer -->
                <div class="text-center mt-8">
                    <p class="text-xs text-gray-400">&copy; <?php echo date('Y'); ?> NDIGITMARKET. Tous droits réservés.</p>
                </div>
            </div>
        </div>

        <!-- Section droite - Image  FULL HEIGHT -->
        <div class="hidden lg:flex lg:w-1/2 relative items-center justify-center bg-gray-900">
            <img
                src="./assets/images/LOGO.svg"
                alt="NDIGITMARKET Logo"
                class="w-2/3 h-auto object-contain">

            <!-- Overlay léger -->
            <div class="absolute inset-0 bg-black/10"></div>

            <!-- Texte -->
            <div class="absolute bottom-10 text-center px-6">
                <h3 class="text-2xl font-bold text-white mb-2">NDIGITMARKET</h3>
                <p class="text-white/80 text-sm">NDIGITMARKET est une plateforme
                    digitale béninoise qui permet aux développeurs et créateurs
                    de sites web d’accéder facilement à des templates, scripts et
                    ressources numériques pour accélérer leurs projets.</p>
            </div>
        </div>
    </div>


    <script src="./assets/JS/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
