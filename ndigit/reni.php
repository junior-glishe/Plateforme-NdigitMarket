<?php
require 'vendor/autoload.php';
require('header.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';

if (isset($_SESSION['user_id'])) {
      echo '<meta  http-equiv="refresh" content="0;URL=index">'; // Rediriger vers la page d'accueil si déjà connecté
    exit();
}


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Réinitialisation du mot de passe</title>
</head>
<body>

<!-- Breadcrumb Section Start -->
<section class="breadcrumb-section pt-0">
    <div class="container-fluid-lg">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-contain">
                    <h2>Réinitialisation du mot de passe</h2>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="index.html"><i class="fa-solid fa-house"></i></a></li>
                            <li class="breadcrumb-item active">Réinitialisation du mot de passe</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Log in Section Start -->
<section class="log-in-section section-b-space forgot-section">
    <div class="container-fluid-lg w-100">
        <div class="row">
            <div class="col-xxl-4 col-xl-5 col-lg-6 col-sm-8 mx-auto">
                <div class="d-flex align-items-center justify-content-center h-100">
                    <div class="log-in-box">
                        <div class="log-in-title">
                            <h4>Réinitialisation du mot de passe</h4>
                        </div>
<?php

if (isset($_GET['email'])) {
    // Récupérer et décrypter l'email
    $key = 'Dine';
    $encryptedEmail = urldecode($_GET['email']);
    $decryptedEmail = openssl_decrypt($encryptedEmail, 'aes-256-cbc', $key, 0, '1234567890123456');

    // Vérifier si le décryptage a réussi
    if ($decryptedEmail !== false) {
        // Vérifier si l'email existe dans la base de données
        $resultats = $database->prepare('SELECT * FROM utilisateur WHERE email = :email');
        $resultats->bindParam(':email', $decryptedEmail);
        $resultats->execute();
        $donnee = $resultats->fetch();

        if (!$donnee) {
            echo '<meta http-equiv="refresh" content="0;URL=index">';  // Si l'email n'existe pas dans la base de données, rediriger
            exit();
        }
    } else {
        echo '<meta http-equiv="refresh" content="0;URL=index">';  // Si le décryptage échoue, rediriger
        exit();
    }
}

if (isset($_POST['submit'])) {
    $newPassword = htmlspecialchars($_POST['newPassword']);
    $confirmPassword = htmlspecialchars($_POST['confirmPassword']);

    if ($newPassword != $confirmPassword) {
        echo '<p style="color:red;text-align:center">Les mots de passe ne correspondent pas.</p>';
    } else {
        // Connecter à la base de données et mettre à jour le mot de passe
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $database->prepare("UPDATE utilisateur SET mdp = :mot_de_passe WHERE email = :email");
        $stmt->bindParam(':mot_de_passe', $hashedPassword);
        $stmt->bindParam(':email', $decryptedEmail);
        $stmt->execute();

        echo '<p style="color:#0da487;text-align:center">Votre mot de passe a été réinitialisé avec succès.</p>';
         echo '
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script>
                        Swal.fire({
                            icon: "success",
                            title: "Succès !",
                            text: "Votre mot de passe a été réinitialisé avec succès. Cliquez sur ok pour vous connecter",
                            confirmButtonColor: "#0a4e83" // Couleur du bouton de confirmation
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "login";
                            }
                        });
                    </script>';
    }
}
?>

                        <!-- Formulaire de réinitialisation du mot de passe -->
                        <form method="POST" action="">
                            <div class="input-box">
                              





                                     <div class="col-12">
    <div class="form-floating theme-form-floating">
        <input name="newPassword" type="password" class="form-control" id="password"
            placeholder="Mot de Passe" onkeyup="validatePasswords()">
        <label for="password">Mot de Passe</label>
    </div>
    <small id="password-requirements" style="color: red; display: block;">
        ⚠️ Minimum 8 caractères, incluant une majuscule, un chiffre et un symbole.
    </small>
    <div class="progress mt-2">
        <div id="password-strength-bar" class="progress-bar" role="progressbar" 
            style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
</div>

<div class="col-12">
    <div class="form-floating theme-form-floating">
        <input name="confirmPassword" type="password" class="form-control" id="confirm-password"
            placeholder="Reconfirmer le mot de passe" onkeyup="validatePasswords()">
        <label for="confirm-password">Reconfirmer le mot de passe</label>
    </div>
    <small id="password-match-message" style="color: red;"></small>
</div>



<script>
function validatePasswords() {
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirm-password").value;
    let strengthBar = document.getElementById("password-strength-bar");
    let requirementsText = document.getElementById("password-requirements");
    let matchMessage = document.getElementById("password-match-message");
    let submitBtn = document.getElementById("submit-btn");

    let strength = 0;
    
    if (password.length >= 8) strength += 25;
    if (/[A-Z]/.test(password)) strength += 25;
    if (/[0-9]/.test(password)) strength += 25;
    if (/[\W]/.test(password)) strength += 25;

    strengthBar.style.width = strength + "%";

    if (strength < 50) {
        strengthBar.style.backgroundColor = "red";
    } else if (strength < 75) {
        strengthBar.style.backgroundColor = "orange";
    } else {
        strengthBar.style.backgroundColor = "green";
    }

    let isValid = strength === 100;
    requirementsText.style.color = isValid ? "green" : "red";

    if (password !== confirmPassword || !isValid) {
        matchMessage.textContent = "❌ Les mots de passe ne correspondent pas ou ne respectent pas les critères.";
        matchMessage.style.color = "red";
        submitBtn.disabled = true;
    } else {
        matchMessage.textContent = "✅ Les mots de passe sont valides.";
        matchMessage.style.color = "green";
        submitBtn.disabled = false;
    }
}
</script>

<!-- Styles Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

                                <div class="col-12">
                                    <button class="btn btn-animation w-100" type="submit" name="submit">Réinitialiser le mot de passe</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Log in Section End -->

<?php require('footer.php'); ?>
</body>
</html>
