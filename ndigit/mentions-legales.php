<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="assets/images/favi.png" type="image/x-icon">
    <title>NDIGITMARKET - Mentions légales</title>
    <meta name="description" content="Mentions légales de NDIGITMARKET : informations sur l’éditeur, l’hébergement et la propriété intellectuelle.">
</head>
<body>
    <?php require('header.php'); ?>
<section class="ndigit-terms-section">
    <div class="ndigit-container">
        <h1 class="ndigit-title">Mentions légales</h1>
        <p class="ndigit-intro">
            Conformément aux dispositions légales en vigueur, vous trouverez ci-dessous
            les informations relatives au site NDIGITMARKET.
        </p>

        <div class="ndigit-terms-content">

            <h2 class="ndigit-section-title">1. Éditeur du site</h2>
            <p>
                <strong>NDIGITMARKET</strong> est une plateforme en ligne développée et exploitée par
                <strong>NTECH DIGIT</strong>.<br>
                Siège social : Porto-Novo, République du Bénin.<br>
                Adresse e-mail : <a href="mailto:contact@ndigitmarket.com" class="theme-color">contact@ndigitmarket.com</a>
            </p>

            <h2 class="ndigit-section-title">2. Responsable de la publication</h2>
            <p>
                Le responsable de la publication est NTECH DIGIT.
            </p>

            <h2 class="ndigit-section-title">3. Hébergement</h2>
            <p>
                Le site est hébergé par <strong>LWS (Ligne Web Services)</strong>.<br>
                Adresse : 10 Rue Penthièvre, 75008 Paris, France.<br>
                Site web :
                <a href="https://www.lws.fr" target="_blank" rel="noopener" class="theme-color">
                    www.lws.fr
                </a>
            </p>

            <h2 class="ndigit-section-title">4. Propriété intellectuelle</h2>
            <p>
                L’ensemble des éléments présents sur le site NDIGITMARKET (textes, graphismes,
                logos, icônes, mises en page) est protégé par les lois relatives à la propriété
                intellectuelle, sauf mention contraire. Toute reproduction, représentation ou
                exploitation sans autorisation préalable est interdite.
            </p>

            <h2 class="ndigit-section-title">5. Données personnelles</h2>
            <p>
                Les informations personnelles collectées sur le site sont traitées conformément
                à notre <a href="privacy-policy" class="theme-color">Politique de confidentialité</a>.
            </p>

            <h2 class="ndigit-section-title">6. Responsabilité</h2>
            <p>
                NDIGITMARKET s’efforce de fournir des informations fiables et à jour. Toutefois,
                aucune garantie n’est donnée quant à l’exactitude ou à l’exhaustivité des contenus.
                L’utilisateur reconnaît utiliser le site sous sa responsabilité exclusive.
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
