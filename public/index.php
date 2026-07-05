<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion </title>
    <link rel="stylesheet" href="../assets/CSS/style.css">

    <meta name="description" content="Connectez-vous à votre espace .">

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

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>

<body class="bg-gradient-custom min-h-screen">

    <?php
    $baseUrl = '/ndigitmarket';

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Déconnexion
    if (isset($_GET['logout'])) {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
        header('Location: ' . $baseUrl . '/index.php');
        exit;
    }


    $error = false;
    $success = false;
    $errorMessage = '';


    if (isset($_POST['envoyer'])) {
        // Récupérer et nettoyer les données
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $mdp = isset($_POST['mdp']) ? $_POST['mdp'] : '';


        if (empty($email) || empty($mdp)) {
            $error = true;
            $errorMessage = "Tous les champs sont obligatoires.";
        } else {
            try {

                require_once __DIR__ . '/../config/database.php';
                require_once __DIR__ . '/../App/Models/AdminModel.php';

                // Instancier le modèle Admin
                $adminModel = new AdminModel();

                // Chercher l'admin par email
                $admin = $adminModel->findByEmail($email);

                if ($admin) {
                    // Vérifier le mot de passe (hashé ou en clair)
                    $passwordValid = false;

                    // Vérifier si le mot de passe est hashé
                    if (password_verify($mdp, $admin['mdp'])) {
                        $passwordValid = true;
                    }
                    // Vérifier si le mot de passe est en clair (migration douce vers le hash)
                    elseif ($mdp === $admin['mdp']) {
                        $adminId = $admin['id_gestion'] ?? $admin['id'] ?? null;
                        $hashedPassword = password_hash($mdp, PASSWORD_DEFAULT);
                        if ($adminId !== null) {
                            $adminModel->updatePassword((int) $adminId, $hashedPassword);
                        }
                        $passwordValid = true;
                    }

                    if ($passwordValid) {
                        $adminRole = strtolower(trim((string) ($admin['role'] ?? '')));
                        if ($adminRole !== 'admin') {
                            $error = true;
                            $errorMessage = "Accès réservé aux administrateurs.";
                        } else {
                            session_regenerate_id(true);

                            $_SESSION["admin"] = "oui";
                            $_SESSION["email"] = $email;
                            $_SESSION["user_id"] = $admin['id_gestion'] ?? $admin['id'] ?? null;
                            $_SESSION["user_name"] = $admin['nom'] ?? $admin['name'] ?? 'Admin';
                            $_SESSION["user_role"] = $adminRole;
                            $_SESSION['user'] = [
                                'id' => $admin['id_gestion'] ?? $admin['id'] ?? null,
                                'name' => $_SESSION["user_name"],
                                'email' => $email,
                                'role' => $adminRole
                            ];

                            $success = true;
                        }
                    } else {
                        $error = true;
                        $errorMessage = "Email ou mot de passe incorrect.";
                    }
                } else {
                    $error = true;
                    $errorMessage = "Email ou mot de passe incorrect.";
                }
            } catch (Exception $e) {
                $error = true;
                $errorMessage = "Erreur de connexion : " . $e->getMessage();
            }
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
                confirmButtonColor: "#0d6b4e",
                timer: 2000,
                timerProgressBar: true
            }).then((result) => {
                window.location.href = "<?php echo $baseUrl; ?>/index.php?route=admin/dashboard";
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
                                src="../assets/images/favi.png"
                                alt="icon"
                                class="w-8 h-8 object-contain"
                                onerror="this.src='../assets/images/logo.png'">
                        </div>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800">NDIGIT<span class="text-primary">MARKET</span></h1>
                    <p class="text-gray-500 text-sm mt-2">Connectez-vous à votre espace</p>
                </div>

                <!-- Message d'erreur PHP -->
                <?php if ($error == true): ?>
                    <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm text-center flex items-center justify-center gap-2">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <?php echo htmlspecialchars($errorMessage); ?>
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
                            placeholder="admin@ndigitmarket.com"
                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>

                    <!-- Mot de passe -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                        <div class="relative">
                            <input
                                type="password"
                                id="password"
                                name="mdp"
                                required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white input-focus transition pr-12"
                                placeholder="••••••••">
                            <button
                                type="button"
                                id="togglePassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/20">
                            <span class="text-sm text-gray-600">Se souvenir de moi</span>
                        </label>
                    </div>

                    <!-- Bouton connexion -->
                    <button
                        type="submit"
                        name="envoyer"
                        value="1"
                        class="w-full py-3 bg-primary text-white rounded-xl font-medium hover:bg-primary/90 transition duration-200">
                        Se connecter
                    </button>
                </form>

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

        <!-- Section droite - Image FULL HEIGHT -->
        <div class="hidden lg:flex lg:w-1/2 relative items-center justify-center bg-gray-900">
            <img
                src="../assets/images/LOGO.svg"
                alt="NDIGITMARKET Logo"
                class="w-2/3 h-auto object-contain"
                onerror="this.style.display='none'">

            <!-- Overlay léger -->
            <div class="absolute inset-0 bg-black/10"></div>

            <!-- Texte -->
            <div class="absolute bottom-10 text-center px-6">
                <h3 class="text-2xl font-bold text-white mb-2">NDIGITMARKET</h3>
                <p class="text-white/80 text-sm">NDIGITMARKET est une plateforme
                    digitale béninoise qui permet aux développeurs et créateurs
                    de sites web d'accéder facilement à des templates, scripts et
                    ressources numériques pour accélérer leurs projets.</p>
            </div>
        </div>
    </div>

    <script>
        // Afficher/masquer le mot de passe
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.innerHTML = type === 'password' ?
                        '<i class="fa-regular fa-eye"></i>' :
                        '<i class="fa-regular fa-eye-slash"></i>';
                });
            }
        });
    </script>

    <script src="../assets/JS/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>