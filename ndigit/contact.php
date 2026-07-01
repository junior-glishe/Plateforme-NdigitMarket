<?php 
require 'vendor/autoload.php';// transaction_status.php - Cette page gère la redirection en fonction du statut de la transaction
require('header.php');
  // Une seule ligne suffit pour inclure toutes les classes nécessaires

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception; 
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';
 ?>
<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="assets/images/favi.png" type="image/x-icon">
    <title>NDIGITMARKET - Votre marché en ligne pour des produits de qualité à prix abordables</title>

    <!-- Description -->
    <meta name="description" content="Découvrez une large gamme de produits sur NDIGITMARKET, votre plateforme de confiance pour acheter des articles électroniques, mode, et bien plus encore à des prix compétitifs. Livraison rapide et sécurité garantie.">

    <!-- Keywords (facultatif mais utile pour le SEO) -->
    <meta name="keywords" content="NDIGITMARKET, marché en ligne, produits électroniques, mode, accessoires, achats en ligne, livraison rapide, produits de qualité">

    <!-- Open Graph (pour les réseaux sociaux, Facebook, etc.) -->
    <meta property="og:title" content="NDIGITMARKET - Votre marché en ligne pour des produits de qualité à prix abordables">
    <meta property="og:description" content="Découvrez une large gamme de produits sur NDIGITMARKET, votre plateforme de confiance pour acheter des articles électroniques, mode, et bien plus encore à des prix compétitifs. Livraison rapide et sécurité garantie.">
    <meta property="og:image" content="URL_de_l'image_de_votre_site.jpg"> <!-- Remplace par l'URL de l'image à utiliser -->
    <meta property="og:url" content="https://www.ndigitmarket.com"> <!-- Remplace par l'URL de ton site -->

    <!-- Twitter Card (pour Twitter) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="NDIGITMARKET - Votre marché en ligne pour des produits de qualité à prix abordables">
    <meta name="twitter:description" content="Découvrez une large gamme de produits sur NDIGITMARKET, votre plateforme de confiance pour acheter des articles électroniques, mode, et bien plus encore à des prix compétitifs. Livraison rapide et sécurité garantie.">
    <meta name="twitter:image" content="assets/images/favi.png"> <!-- Remplace par l'URL de l'image à utiliser -->
</head>
<body>

<?php


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitiser les données d'entrée
    $first_name = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
    $last_name = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

    // Valider les entrées
    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($message)) {
        $response = "Tous les champs sont requis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = "Adresse e-mail invalide.";
    } else {
        try {
            $mail = new PHPMailer(true);

            // Paramètres du serveur
            $mail->isSMTP();
            $mail->Host = 'mail03.lwspanel.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'contact@ndigitmarket.com';
            $mail->Password = 'Nawane@2023';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Destinataires
            $mail->setFrom('contact@ndigitmarket.com', 'Formulaire de Contact');
            $mail->addAddress('contact@ndigitmarket.com');

            // Contenu
            $mail->isHTML(true);
            $mail->Subject = 'Nouveau Message de ndigitmarket.com';
            $mail->Body = "
                <h3>Nouveau Message de ndigitmarket.com</h3>
                <p><strong>Nom :</strong> $first_name</p>
                <p><strong>Prénom :</strong> $last_name</p>
                <p><strong>Email :</strong> $email</p>
                <p><strong>Téléphone :</strong> $phone</p>
                <p><strong>Message :</strong><br>$message</p>
            ";
            $mail->AltBody = "Nom : $first_name\nPrénom : $last_name\nEmail : $email\nTéléphone : $phone\nMessage : $message";

            $mail->send();
            $response = "Message envoyé avec succès !";
        } catch (Exception $e) {
            $response = "Échec de l'envoi du message. Erreur : {$mail->ErrorInfo}";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactez-nous - NDigitMarket</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Styles pour l'alerte personnalisée */
        .custom-alert {
            position: relative;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-size: 1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            animation: slideIn 0.3s ease-in-out;
            max-width: 100%;
            transition: all 0.3s ease;
        }

        .custom-alert-success {
            background-color: #e6ffed;
            border: 1px solid #34c759;
            color: #1a7c34;
        }

        .custom-alert-danger {
            background-color: #ffe6e6;
            border: 1px solid #ff4d4d;
            color: #a10000;
        }

        .custom-alert i {
            margin-right: 0.75rem;
            font-size: 1.2rem;
        }

        .custom-alert .close-btn {
            margin-left: auto;
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: inherit;
            opacity: 0.7;
        }

        .custom-alert .close-btn:hover {
            opacity: 1;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Assurer la responsivité de l'alerte */
        @media (max-width: 576px) {
            .custom-alert {
                font-size: 0.9rem;
                padding: 0.75rem 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Section Fil d'Ariane Début -->
    <section class="breadcrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-contain">
                        <h2>Contactez-nous</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="index.html">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active">Contactez-nous</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Section Fil d'Ariane Fin -->

    <!-- Section Boîte de Contact Début -->
    <section class="contact-box-section">
        <div class="container-fluid-lg">
            <div class="row g-lg-5 g-3">
                <div class="col-lg-6">
                    <div class="left-sidebar-box">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="contact-image">
                                    <img src="assets/images/inner-page/contact-us.png" class="img-fluid blur-up lazyloaded" alt="Contactez-nous">
                                </div>
                            </div>
                            <div class="col-xl-12">
                            <!--     <div class="contact-title">
                                    <h3>Contactez-nous</h3>
                                </div> -->
                             <!--    <div class="contact-detail">
                                    <div class="row g-4">
                                        <div class="col-xxl-6 col-lg-12 col-sm-6">
                                            <div class="contact-detail-box">
                                                <div class="contact-icon">
                                                    <i class="fa-solid fa-phone"></i>
                                                </div>
                                                <div class="contact-detail-title">
                                                    <h4>Téléphone</h4>
                                                </div>
                                                <div class="contact-detail-contain">
                                                    <p><a href="tel:+1618190496">(+1) 618 190 496</a></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-lg-12 col-sm-6">
                                            <div class="contact-detail-box">
                                                <div class="contact-icon">
                                                    <i class="fa-solid fa-envelope"></i>
                                                </div>
                                                <div class="contact-detail-title">
                                                    <h4>Email</h4>
                                                </div>
                                                <div class="contact-detail-contain">
                                                    <p><a href="mailto:support@ndigitmarket.com">support@ndigitmarket.com</a></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-lg-12 col-sm-6">
                                            <div class="contact-detail-box">
                                                <div class="contact-icon">
                                                    <i class="fa-brands fa-whatsapp"></i>
                                                </div>
                                                <div class="contact-detail-title">
                                                    <h4>WhatsApp</h4>
                                                </div>
                                                <div class="contact-detail-contain">
                                                    <p><a href="https://wa.me/+1618190496">+1 618 190 496</a></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="title d-xxl-none d-block">
                        <h2>Contactez-nous</h2>
                    </div>
                    <div class="right-sidebar-box">
                        <?php if (isset($response)) { ?>
                            <div class="custom-alert <?php echo strpos($response, 'succès') !== false ? 'custom-alert-success' : 'custom-alert-danger'; ?>">
                                <i class="fas <?php echo strpos($response, 'succès') !== false ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                                <?php echo $response; ?>
                                <button type="button" class="close-btn" onclick="this.parentElement.style.display='none';">×</button>
                            </div>
                        <?php } ?>
                        <form action="contact.php" method="POST">
                            <div class="row">
                                <div class="col-xxl-6 col-lg-12 col-sm-6">
                                    <div class="mb-md-4 mb-3 custom-form">
                                        <label for="first_name" class="form-label">Nom</label>
                                        <div class="custom-input">
                                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Entrez votre nom" required>
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xxl-6 col-lg-12 col-sm-6">
                                    <div class="mb-md-4 mb-3 custom-form">
                                        <label for="last_name" class="form-label">Prénom</label>
                                        <div class="custom-input">
                                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Entrez votre prénom" required>
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xxl-12 col-lg-12 col-sm-6">
                                    <div class="mb-md-4 mb-3 custom-form">
                                        <label for="email" class="form-label">Adresse e-mail</label>
                                        <div class="custom-input">
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Entrez votre adresse e-mail" required>
                                            <i class="fa-solid fa-envelope"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xxl-12 col-lg-12 col-sm-6">
                                    <div class="mb-md-4 mb-3 custom-form">
                                        <label for="phone" class="form-label">Téléphone</label>
                                        <div class="custom-input">
                                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="Entrez votre numéro de téléphone" required>
                                            <i class="fa-solid fa-phone"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-md-4 mb-3 custom-form">
                                        <label for="message" class="form-label">Message</label>
                                        <div class="custom-textarea">
                                            <textarea class="form-control" id="message" name="message" placeholder="Entrez votre message" rows="6" required></textarea>
                                            <i class="fa-solid fa-message"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-animation btn-md fw-bold ms-auto">Envoyer votre message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <br><br>
 <?php require('promotion.php') ?>
    <!-- Section Boîte de Contact Fin -->

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
    <!-- Contact Box Section End -->

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

	  <?php require('footer.php') ?>

</body>
</html>