<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="assets/images/favi.png" type="image/x-icon">
    <title>NDIGITMARKET - Politique de confidentialité</title>
    <meta name="description" content="Politique de confidentialité de NDIGITMARKET : gestion des données personnelles et respect de la vie privée.">
</head>
<body>
    <?php require('header.php'); ?>

   <section class="ndigit-terms-section">
    <div class="ndigit-container">
        <h1 class="ndigit-title">Politique de confidentialité</h1>
        <p class="ndigit-intro">
            NdigitMarket accorde une importance particulière à la protection de vos données personnelles.
            Cette politique explique comment nous collectons, utilisons et protégeons vos informations
            lorsque vous utilisez notre site et nos services.
        </p>

        <div class="ndigit-terms-content">

            <h2 class="ndigit-section-title">1. Collecte des données personnelles</h2>
            <p>
                Nous collectons uniquement les informations strictement nécessaires lors de :
            </p>
            <ul>
                <li>la création d’un compte utilisateur,</li>
                <li>la passation d’une commande,</li>
                <li>un contact via notre formulaire ou WhatsApp.</li>
            </ul>
            <p>
                Les données collectées peuvent inclure : nom, prénom, adresse e-mail, numéro de téléphone
                et informations liées à la commande.
            </p>

            <h2 class="ndigit-section-title">2. Utilisation des données</h2>
            <p>
                Les données collectées sont utilisées uniquement pour :
            </p>
            <ul>
                <li>le traitement et la gestion des commandes,</li>
                <li>la fourniture des produits numériques achetés,</li>
                <li>l’assistance client et l’accompagnement à l’installation,</li>
                <li>l’amélioration de l’expérience utilisateur et des services proposés.</li>
            </ul>
            <p>
                Vos données personnelles ne sont <strong>ni vendues, ni louées, ni cédées</strong> à des tiers.
            </p>

            <h2 class="ndigit-section-title">3. Paiements et données sensibles</h2>
            <p>
                NdigitMarket ne stocke aucune information bancaire sensible. Les paiements sont traités
                exclusivement via des plateformes de paiement sécurisées et agréées.
            </p>

            <h2 class="ndigit-section-title">4. Protection et sécurité des données</h2>
            <p>
                Nous mettons en œuvre des mesures techniques et organisationnelles afin de protéger
                vos données contre tout accès non autorisé, perte, altération ou divulgation.
            </p>

            <h2 class="ndigit-section-title">5. Conservation des données</h2>
            <p>
                Les données personnelles sont conservées uniquement pendant la durée nécessaire
                à la gestion des commandes, au support client et aux obligations légales.
            </p>

            <h2 class="ndigit-section-title">6. Vos droits</h2>
            <p>
                Conformément à la réglementation en vigueur, vous disposez d’un droit d’accès,
                de rectification et de suppression de vos données personnelles.
                Vous pouvez exercer ces droits en nous contactant via notre
                <a href="contact" class="theme-color">formulaire de contact</a>.
            </p>

            <h2 class="ndigit-section-title">7. Cookies</h2>
            <p>
                NdigitMarket peut utiliser des cookies pour améliorer la navigation, analyser
                le trafic et optimiser l’expérience utilisateur. Vous pouvez configurer
                votre navigateur pour refuser les cookies.
            </p>

            <h2 class="ndigit-section-title">8. Modifications de la politique</h2>
            <p>
                Cette politique de confidentialité peut être mise à jour à tout moment.
                Les modifications prennent effet dès leur publication sur le site.
            </p>

            <h2 class="ndigit-section-title">9. Contact</h2>
            <p>
                Pour toute question relative à cette politique de confidentialité,
                vous pouvez nous contacter via la page
                <a href="contact" class="theme-color">Contact</a> ou par WhatsApp.
            </p>

        </div>
    </div>
</section>

    
    <style type="text/css">
    /* Général */
    body {
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f9f9f9;
        color: #333;
    }

    /* Section Termes et Conditions */
    .ndigit-terms-section {
        padding: 50px 0;
        background-color: #ffffff;
        border-top: 5px solid #0da487; /* Couleur principale */
    }

    .ndigit-container {
        width: 90%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }

    .ndigit-title {
        font-size: 2rem;
        font-weight: 600;
        text-align: center;
        color: #0da487; /* Titre avec la couleur principale */
        margin-bottom: 20px;
    }

    .ndigit-intro {
        font-size: 1.1rem;
        text-align: center;
        color: #555;
        margin-bottom: 30px;
    }

    .ndigit-terms-content {
        line-height: 1.6;
        color: #555;
        font-size: 1rem;
    }

    /* Titre de sous-section */
    .ndigit-section-title {
        font-size: 1.4rem;
        color: #0da487; /* Titre avec couleur principale */
        font-weight: 600;
        margin-top: 30px;
    }

    /* Liens */
    .theme-color {
        color: #0da487;
        text-decoration: none;
    }

    .theme-color:hover {
        text-decoration: underline;
    }

    /* Responsiveness */
    @media screen and (max-width: 768px) {
        .ndigit-title {
            font-size: 1.8rem;
        }

        .ndigit-intro {
            font-size: 1rem;
        }

        .ndigit-terms-content p,
        .ndigit-section-title {
            font-size: 0.95rem;
        }
    }
</style>

    <?php require('banniere.php') ?>
    <?php require('popup.php') ?>
    <?php require('footer.php') ?>
</body>
</html>
