<?php
// Assure-toi que tu as inclus l'autoloader
   session_start();
    require 'vendor/autoload.php';// transaction_status.php - Cette page gère la redirection en fonction du statut de la transaction

  require('include/connect.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['envoyer'])) {
    // Récupérer l'email envoyé par le formulaire
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

        // Si l'email existe, envoyer l'email pour réinitialisation
        if ($donnee) {
            // Récupérer le prénom et le nom de l'utilisateur
            $prenom = $donnee['prenom'];
            $nom = $donnee['nom'];

            // URL de réinitialisation
            $lien = 'https://afarka.com/fr/reni.php?email=' . urlencode($email); // Correction ici

            // Créer et envoyer l'email via PHPMailer
            $mail = new PHPMailer(true);
            try {
                // Configuration de PHPMailer
                $mail->isSMTP();
                $mail->Host = 'mail.ndigitmarket.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'contact@ndigitmarket.com';
                $mail->Password = 'Nawane@2023';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->CharSet = 'UTF-8';

                // Expéditeur et destinataire
                $mail->setFrom('contact@ndigitmarket.com', 'NDIGIT MARKET');
                $mail->addAddress($email);

                // Ajouter le logo de l'entreprise
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
                                    transition: background-color 0.3s ease;
                                }
                                .container {
                                    font-family: Arial, sans-serif;
                                    color: #333;
                                    text-align: center;
                                    margin: 0 auto;
                                    max-width: 600px;
                                }
                                .header {
                                    text-align: center;
                                    margin-top: 20px;
                                }
                                .footer {
                                    color: #aaa;
                                    font-size: 12px;
                                    text-align: center;
                                    margin-top: 20px;
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
                                <a href='$lien' class='button'>Réinitialiser votre mot de passe</a> <!-- Correction ici -->
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
                // Si une erreur se produit avec PHPMailer
                echo "<p style=\"color:red;text-align:center\">Erreur lors de l'envoi de l'e-mail: {$mail->ErrorInfo}</p>";
            }

        } else {
            // Si l'email n'existe pas dans la base de données
            echo '<p style="color:red;text-align:center">Cette adresse e-mail n\'est pas dans notre base de données.</p>';
        }
    }
}
?>
