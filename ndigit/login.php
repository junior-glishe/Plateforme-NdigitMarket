<?php
// ---- INITIALISATION SIMPLE (sans header.php) ----
session_start();
require('include/connect.php');

// Si déjà connecté → rediriger vers l'accueil
if (isset($_SESSION['user_id'])) {
    echo '<meta http-equiv="refresh" content="0;URL=index">';
    exit;
}

$erreur = '';
$success = false;

// Traitement du formulaire
if (isset($_POST['envoyer'])) {
    $mdp   = htmlspecialchars($_POST['mdp']);
    $email = htmlspecialchars($_POST['email']);

    $resultats = $database->query('SELECT * FROM utilisateur');

    while ($donnee = $resultats->fetch()) {
        if ($donnee['email'] == $email && password_verify($mdp, $donnee['mdp'])) {
            $_SESSION['user_id'] = 'oui';
            $_SESSION['email']   = $email;
            $success = true;
            break;
        }
    }

    if (!$success) {
        $erreur = 'Adresse email ou mot de passe incorrect.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/images/favi.png" type="image/x-icon">
    <title>Connexion - NDIGITMARKET</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #087d67;
            --primary-dark: #065a4a;
            --dark: #0f1923;
            --border: #e5e7eb;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at 20% 80%, rgba(8,125,103,0.15), transparent 50%),
                        linear-gradient(135deg, var(--dark) 0%, #1a2634 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-container {
            background: white;
            border-radius: 28px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.3);
            max-width: 480px;
            width: 100%;
            padding: 40px 36px;
            position: relative;
            overflow: hidden;
        }
        .login-container::before {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 120px;
            height: 120px;
            background: var(--primary);
            border-radius: 50%;
            opacity: 0.1;
        }
        .login-container::after {
            content: '';
            position: absolute;
            bottom: -60px;
            left: -60px;
            width: 160px;
            height: 160px;
            background: var(--primary-dark);
            border-radius: 50%;
            opacity: 0.08;
        }
        .login-content {
            position: relative;
            z-index: 1;
        }
        .brand-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .brand-logo img {
            height: 50px;
        }
        h2 {
            font-size: 26px;
            font-weight: 800;
            color: var(--dark);
            text-align: center;
            margin-bottom: 8px;
        }
        .subtitle {
            color: #6b7280;
            text-align: center;
            margin-bottom: 32px;
            font-size: 15px;
        }
        .form-floating {
            margin-bottom: 20px;
        }
        .form-floating input {
            border-radius: 14px;
            border: 1px solid var(--border);
            padding: 14px 12px;
            font-size: 15px;
            transition: border 0.2s, box-shadow 0.2s;
        }
        .form-floating input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(8,125,103,0.15);
            outline: none;
        }
        .btn-primary {
            background: var(--primary);
            border: none;
            border-radius: 14px;
            padding: 14px;
            font-weight: 600;
            font-size: 16px;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(8,125,103,0.35);
        }
        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
        }
        .signup-link {
            text-align: center;
            margin-top: 25px;
            color: #6b7280;
            font-size: 15px;
        }
        .signup-link a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }
        .error-message {
            background: #fee2e2;
            color: #b91c1c;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }
        .options-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        .remember-me {
            font-size: 14px;
            color: #4b5563;
        }

        /* Écran de succès */
        .success-overlay {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
        }
        .success-icon {
            font-size: 60px;
            color: #10b981;
            margin-bottom: 20px;
        }
        .success-text {
            font-size: 22px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
        }
        .success-sub {
            color: #6b7280;
            font-size: 15px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-content">
        <?php if ($success): ?>
            <!-- Message de succès avant redirection -->
            <div class="success-overlay" id="successOverlay">
                <i class="fa-solid fa-circle-check success-icon"></i>
                <div class="success-text">Connexion réussie !</div>
                <div class="success-sub">Redirection vers votre compte...</div>
            </div>
            <!-- SweetAlert2 pour une notification élégante + redirection -->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Connexion réussie',
                    text: 'Vous allez être redirigé vers votre compte.',
                    confirmButtonColor: '#087d67',
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = 'compte';
                });
                // Redirection automatique après 2.5 secondes si SweetAlert ne se ferme pas
                setTimeout(function() {
                    window.location.href = 'compte';
                }, 2500);
            </script>
        <?php else: ?>
            <!-- Formulaire de connexion -->
            <div class="brand-logo">
                <img src="assets/images/loo.png" alt="NDIGITMARKET">
            </div>
            <h2>Bienvenue</h2>
            <p class="subtitle">Connectez-vous à votre espace</p>

            <?php if (!empty($erreur)): ?>
                <div class="error-message"><?php echo $erreur; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-floating">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Adresse Email" required>
                    <label for="email">Adresse Email</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" id="password" name="mdp" placeholder="Mot de passe" required>
                    <label for="password">Mot de passe</label>
                </div>

                <div class="options-row">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember">
                        <label class="form-check-label remember-me" for="remember">Se souvenir de moi</label>
                    </div>
                    <a href="oublier" class="forgot-link">Mot de passe oublié ?</a>
                </div>

                <button type="submit" name="envoyer" class="btn btn-primary w-100">Se connecter</button>
            </form>

            <div class="signup-link">
                Pas encore de compte ? <a href="inscrire">Inscrivez-vous</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>