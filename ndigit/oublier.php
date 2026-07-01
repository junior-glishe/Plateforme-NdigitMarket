<?php
// transaction_status.php - Cette page gère la redirection en fonction du statut de la transaction
require('header.php');
require 'vendor/autoload.php';
  // Une seule ligne suffit pour inclure toutes les classes nécessaires

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception; 
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';


// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");  // Redirection vers la page d'accueil si déjà connecté
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
                    <h2>Mot de passe oublié</h2>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="index.html"><i class="fa-solid fa-house"></i></a></li>
                            <li class="breadcrumb-item active">Mot de passe oublié</li>
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
            <div class="col-xxl-6 col-xl-5 col-lg-6 d-lg-block d-none ms-auto">
                <div class="image-contain">
                    <img src="assets/images/inner-page/forgot.png" class="img-fluid" alt="">
                </div>
            </div>

            <div class="col-xxl-4 col-xl-5 col-lg-6 col-sm-8 mx-auto">
                <div class="d-flex align-items-center justify-content-center h-100">
                    <div class="log-in-box">
                        <div class="log-in-title">
                            <h4>Mot de passe oublié</h4>
                        </div>




<!-- Formulaire pour la réinitialisation du mot de passe -->
<div class="input-box">

 <?php



// Traitement du formulaire de réinitialisation du mot de passe
if (isset($_POST['envoyer'])) {
    $email = htmlspecialchars($_POST['email']);

    // Vérifier si l'email a un format valide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<p style="color:red;text-align:center">L\'adresse e-mail n\'est pas valide.</p>';
    } else {
        // Vérifier si l'email existe dans la base de données
        $resultats = $database->prepare('SELECT * FROM utilisateur WHERE email = :email');
        $resultats->bindParam(':email', $email);
        $resultats->execute();
        $donnee = $resultats->fetch();

        if ($donnee) {
            $prenom = $donnee['prenom'];
            $nom = $donnee['nom'];

            // Crypter l'email avant de l'ajouter à l'URL
            $key = 'Dine';
            $encryptedEmail = openssl_encrypt($email, 'aes-256-cbc', $key, 0, '1234567890123456');
            $lien = 'https://www.ndigitmarket.com/reni.php?email=' . urlencode($encryptedEmail);

            // Envoi de l'email via PHPMailer
            $mail = new PHPMailer(true);
            try {
                // Configuration de PHPMailer
                $mail->isSMTP();
                $mail->Host = 'mail03.lwspanel.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'contact@ndigitmarket.com';
                $mail->Password = 'Nawane@2023';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('contact@ndigitmarket.com', 'Ntech Digit');
                $mail->addAddress($email);

                // Logo de l'entreprise
                $logoUrl = "https://www.ndigitmarket.com/assets/images/loo.png"; 

                // Contenu de l'email
                $mail->isHTML(true);
                $mail->Subject = 'Réinitialisation de votre mot de passe';
                $mail->Body = "
                    <html>
                        <head>
                            <meta charset='utf-8'>
                            <style>
                                .button {
                                    display: inline-block;
                                    padding: 10px 20px;
                                    background-color: #007bff;
                                    color: #fff;
                                    text-decoration: none;
                                    border-radius: 5px;
                                }
                                .container {
                                    font-family: Arial, sans-serif;
                                    color: #333;
                                    text-align: center;
                                }
                                .footer {
                                    color: #aaa;
                                    font-size: 12px;
                                    text-align: center;
                                }
                            </style>
                        </head>
                        <body>
                            <div class='container'>
                                <div class='header'>
                                    <img src='$logoUrl' alt='Logo NDIGIT MARKET' style='width: 150px;'>
                                </div>
                                <h2>Bonjour $prenom $nom,</h2>
                                <p>Nous avons reçu une demande de réinitialisation de votre mot de passe.</p>
                                <p>Veuillez cliquer sur le bouton ci-dessous pour réinitialiser votre mot de passe :</p>
                                <a href='$lien' class='button'>Réinitialiser votre mot de passe</a>
                                <div class='footer'>
                                    <p>Si vous n'êtes pas à l'origine de cette demande, veuillez ignorer cet e-mail.</p>
                                    <p>NDIGIT MARKET vous remercie pour votre confiance.</p>
                                </div>
                            </div>
                        </body>
                    </html>
                ";

                // Envoyer l'email
                $mail->send();
                echo '<p style="color:#0da487;text-align:center">Le lien de réinitialisation vous a été envoyé à votre adresse e-mail. Veuillez le consulter pour modifier votre mot de passe.</p>';
            } catch (Exception $e) {
                echo "<p style=\"color:red;text-align:center\">Erreur lors de l'envoi de l'e-mail: {$mail->ErrorInfo}</p>";
            }
        } else {
            echo '<p style="color:red;text-align:center">Cette adresse e-mail n\'est pas dans notre base de données.</p>';
        }
    }
}
?>

    <form class="row g-4" method="POST" action="">
        <div class="col-12">
            <div class="form-floating theme-form-floating log-in-form">
                <input type="email" class="form-control" id="email" placeholder="Email Address" name="email" required>
                <label for="email">Votre Adresse Mail</label>
            </div>
        </div>

        <div class="col-12">
            <button class="btn btn-animation w-100" type="submit" name="envoyer" 
            >Réinitialiser le mot de passe</button>
        </div>
    </form>

   

</div>

</div>
</div></div></div></div></section>
<?php require('footer.php'); ?>