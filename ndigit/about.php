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
	 <?php require('header.php');
     ?>
  <section class="ndigit-about-section">
    <div class="ndigit-container">
        <h1 class="ndigit-title">À propos de NdigitMarket</h1>
        <p class="ndigit-intro">Bienvenue sur NdigitMarket, la plateforme dédiée aux produits digitaux premium pour les créateurs et professionnels du web.</p>

        <div class="ndigit-about-content">
            <p>NdigitMarket est une plateforme de vente en ligne spécialisée dans les produits digitaux de haute qualité. Nous proposons une large gamme de ressources pour les développeurs, designers et créateurs. Vous trouverez des thèmes WordPress, des modèles HTML, des polices de caractères, des fichiers PSD, des icônes, des images, des vidéos et bien plus encore.</p>

            <p>Nous nous engageons à fournir des produits modernes et faciles à utiliser, pour vous permettre de réussir vos projets numériques rapidement et efficacement.</p>

            <h2 class="ndigit-section-title">Nos Produits</h2>
            <ul class="ndigit-product-list">
                <li><strong>Thèmes WordPress :</strong> Des thèmes responsive, modernes et optimisés, fournis avec une <strong>licence GPL</strong> pour une utilisation libre et flexible.</li>
                <li><strong>Modèles HTML :</strong> Des modèles HTML professionnels, entièrement personnalisables pour vos projets web.</li>
                <li><strong>Polices :</strong> Une collection variée de polices modernes et créatives pour enrichir vos designs.</li>
                <li><strong>Fichiers PSD :</strong> Des fichiers PSD modifiables pour la création de visuels, maquettes ou projets graphiques.</li>
                <li><strong>Autres ressources :</strong> Icônes, photos, vidéos, et autres éléments pour compléter vos projets créatifs.</li>
            </ul>

            <h2 class="ndigit-section-title">Pourquoi choisir NdigitMarket ?</h2>
            <ul class="ndigit-benefit-list">
                <li><span class="check-icon">&#10003;</span>Produits de qualité supérieure, régulièrement mis à jour.</li>
                <li><span class="check-icon">&#10003;</span>Licence commerciale à vie, pour une totale liberté d'utilisation.</li>
                <li><span class="check-icon">&#10003;</span>Support prioritaire 24/7 pour répondre à vos questions techniques.</li>
                <li><span class="check-icon">&#10003;</span>Accès instantané aux fichiers après l'achat, sans délai.</li>
                <li><span class="check-icon">&#10003;</span>Livraison gratuite pour toutes les commandes.</li>
            </ul>

            <h2 class="ndigit-section-title">Notre Engagement</h2>
            <p>Notre objectif est de rendre les produits digitaux de haute qualité accessibles à tous. Nous nous efforçons de vous fournir des ressources qui vous aideront à concrétiser vos projets, tout en vous offrant un support et une expérience d'achat exceptionnels.</p>
        </div>
    </div>
</section>

<br><br>
 <?php require('promotion.php') ?>

<?php require('banniere.php') ?>

<style type="text/css">
    /* Général */
    body {
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f9f9f9;
        color: #333;
    }

    /* Section À propos */
    .ndigit-about-section {
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

    .ndigit-about-content {
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

    /* Liste des produits */
    .ndigit-product-list,
    .ndigit-benefit-list {
        list-style: none;
        padding: 0;
    }

    .ndigit-product-list li,
    .ndigit-benefit-list li {
        margin-bottom: 15px;
        font-size: 1rem;
    }

    .ndigit-product-list li strong,
    .ndigit-benefit-list li strong {
        color: #0da487; /* Texte avec couleur principale */
    }

    /* Icône de réussite pour les avantages */
    .ndigit-benefit-list li {
        display: flex;
        align-items: center;
    }

    .ndigit-benefit-list li .check-icon {
        content: "âï¸";
        margin-right: 10px;
        color: #28a745;  /* Green check icon */
        font-size: 1.3rem;
    }

    /* Icône de succès */
    .check-icon {
        color: #28a745;
    }

    /* Responsive Design */
    @media screen and (max-width: 768px) {
        .ndigit-title {
            font-size: 1.8rem;
        }

        .ndigit-intro {
            font-size: 1rem;
        }

        .ndigit-product-list li,
        .ndigit-benefit-list li {
            font-size: 0.95rem;
        }

        .ndigit-section-title {
            font-size: 1.3rem;
        }
    }
</style>

        <?php require('popup.php') ?>
	  <?php require('footer.php') ?>

</body>
</html>