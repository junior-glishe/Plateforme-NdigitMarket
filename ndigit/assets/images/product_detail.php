<!DOCTYPE html>
<html>

<body>
	<?php require('header.php') ?>


<?php
// Connexion à la base de données
// require('header.php'); // Inclure la connexion à la base de données ici

// Vérifier si le nom de l'article est passé dans l'URL
if (isset($_GET['nom_article'])) {
    $nom_article = $_GET['nom_article'];

    // Requête pour récupérer les détails du produit en utilisant le nom de l'article
    $resultats = $database->prepare('
        SELECT produits.*, categories.nom_categorie
        FROM produits
        INNER JOIN categories ON produits.categorie_id = categories.id
        WHERE produits.nom_article = :nom_article
    ');
    $resultats->execute([':nom_article' => $nom_article]);
    $produit = $resultats->fetch(PDO::FETCH_ASSOC);

    // Si le produit n'existe pas
    if (!$produit) {
            echo '<meta http-equiv="refresh" content="0;URL=index">'; 
        exit;
    }
}
//  else {
//     echo "Aucun produit sélectionné.";
//     exit;
// }


?>
<head>


    <link rel="icon" href="assets/images/favi.png" type="image/x-icon">
    <title>NDIGITMARKET - <?php echo htmlspecialchars($produit['nom_categorie']); ?></title>

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
	<section class="breadcrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-contain">
                        <h2>Détail du produit</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="index.html">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>

                                <li class="breadcrumb-item active"><?php echo htmlspecialchars($produit['nom_categorie']); ?></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>


        <section class="product-section theme-product-section">
        <div class="container-fluid-lg">
            <div class="row g-sm-4 g-3">
                <div class="col-xl-8 col-lg-7 wow fadeInUp">
                    <div class="product-left-box">
                        <div class="row g-sm-4 g-3">
                            <div class="col-12 wow fadeInUp">
                                <div class="position-relative">
                                    <div class="product-title m-0">
                                        <h2 class="name"><?php echo htmlspecialchars($produit['nom_article']); ?></h2>
                                        <ul class="title-content-list">
                                           <!--  <li>
                                                <h6 class="content">par <a href="#!"></a></h6>
                                            </li>
                                            <li>
                                                <h6 class="content">
                                                    <i data-feather="shopping-cart"></i>
                                                    131 Sales
                                                </h6>
                                            </li> -->
                                        </ul>
                                     <!--    <p>Oslo Theme is specially designed for every kind of online shops: fashion,
                                            cosmetic, beauty, beauty products, beauty shop, minimal shop, modern shop
                                            and so on. Oslo Theme is specially designed for every kind of online shops:
                                            fashion, cosmetic.</p> -->
                                    </div>

                                    <div class="theme-option-box">
                                        <div class="theme-image-option">
                                            <img src="back-end/apps/<?php echo htmlspecialchars($produit['image']); ?>"
                                                class="img-fluid blur-up w-100 h-100 lazyloaded" alt="">
                                            <button class="theme-image-icon">
                                                <i data-feather="search"></i>
                                            </button>
                                        </div>

                                     
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="product-section-box mt-md-4 mt-2">
                                    <ul class="nav nav-tabs custom-nav" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                                                data-bs-target="#description" type="button">Description</button>
                                        </li>

                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="care-tab" data-bs-toggle="tab"
                                                data-bs-target="#care" type="button">Instructions d’entretien</button>
                                        </li>

                                      <!--   <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="review-tab" data-bs-toggle="tab"
                                                data-bs-target="#review" type="button">Review</button>
                                        </li> -->
                                    </ul>

                                    <div class="tab-content custom-tab" id="myTabContent">
                                        <div class="tab-pane fade show active" id="description" role="tabpanel">
                                            <div class="product-description">
                                                <div class="nav-desh">
                                                    <p><?php echo html_entity_decode($produit['description']); ?></p>


                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="info" role="tabpanel">
                                            <div class="table-responsive">
                                                <table class="table info-table">
                                                    <tbody>
                                                        <tr>
                                                            <td>Specialty</td>
                                                            <td>Vegetarian</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Ingredient Type</td>
                                                            <td>Vegetarian</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Brand</td>
                                                            <td>Lavian Exotique</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Form</td>
                                                            <td>Bar Brownie</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Package Information</td>
                                                            <td>Box</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Manufacturer</td>
                                                            <td>Prayagh Nutri Product Pvt Ltd</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Item part number</td>
                                                            <td>LE 014 - 20pcs Crème Bakes (Pack of 2)</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Net Quantity</td>
                                                            <td>40.00 count</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="care" role="tabpanel">
                                            <div class="information-box">
                                                <p>Les fichiers à télécharger sont fournis au format ZIP. Une fois le fichier téléchargé, veuillez le décompresser pour accéder aux fichiers sources nécessaires à l'installation du thème.</p>
                                                <ul>
                                                     <li>
    ⚙️ <strong>Compatibilité CMS :</strong> Installez votre modèle sur WordPress ou Shopify sans difficulté. Une documentation claire est fournie.
  </li>

  <li><strong>Installation WordPress :</strong> Rendez-vous dans <em>Apparence &gt; Thèmes &gt; Ajouter</em> et téléversez le fichier ZIP extrait.</li>
  
  <li><strong>Installation Shopify :</strong> Décompressez le fichier, puis suivez les instructions incluses dans le dossier.</li>

  <li class="information-title"><strong>Informations utiles :</strong></li>

  <li>🎨 <strong>Visuels de démonstration :</strong> Les images utilisées sont là pour vous inspirer. Elles ne sont pas incluses dans le pack.</li>

  <li>🧩 <strong>Plugins et outils intégrés :</strong> Certains modèles incluent des plugins premium pour booster vos performances ou enrichir votre design.</li>

  <li>🔁 <strong>Compatibilité garantie :</strong> Avant achat, lisez bien la description pour vérifier la compatibilité avec votre plateforme.</li>

  <li>📌 <strong>Important :</strong> Pour des raisons de sécurité numérique, les fichiers téléchargés ne sont pas remboursables. Assurez-vous d’avoir vérifié tous les détails avant l’achat.</li>
</ul>

<p><strong>💡 Besoin d'aide ou d'installation personnalisée ?</strong> Contactez notre équipe via WhatsApp, nous sommes là pour vous accompagner.</p>


                                                   
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="review" role="tabpanel">
                                            <div class="review-box">
                                                <div class="row">
                                                    <div class="col-xl-5">
                                                        <div class="product-rating-box">
                                                            <div class="row">
                                                                <div class="col-xl-12">
                                                                    <div class="product-main-rating">
                                                                        <h2>3.40
                                                                            <i data-feather="star"></i>
                                                                        </h2>

                                                                        <h5>5 Overall Rating</h5>
                                                                    </div>
                                                                </div>

                                                                <div class="col-xl-12">
                                                                    <ul class="product-rating-list">
                                                                        <li>
                                                                            <div class="rating-product">
                                                                                <h5>5<i data-feather="star"></i></h5>
                                                                                <div class="progress">
                                                                                    <div class="progress-bar"
                                                                                        style="width: 40%;">
                                                                                    </div>
                                                                                </div>
                                                                                <h5 class="total">2</h5>
                                                                            </div>
                                                                        </li>
                                                                        <li>
                                                                            <div class="rating-product">
                                                                                <h5>4<i data-feather="star"></i></h5>
                                                                                <div class="progress">
                                                                                    <div class="progress-bar"
                                                                                        style="width: 20%;">
                                                                                    </div>
                                                                                </div>
                                                                                <h5 class="total">1</h5>
                                                                            </div>
                                                                        </li>
                                                                        <li>
                                                                            <div class="rating-product">
                                                                                <h5>3<i data-feather="star"></i></h5>
                                                                                <div class="progress">
                                                                                    <div class="progress-bar"
                                                                                        style="width: 0%;">
                                                                                    </div>
                                                                                </div>
                                                                                <h5 class="total">0</h5>
                                                                            </div>
                                                                        </li>
                                                                        <li>
                                                                            <div class="rating-product">
                                                                                <h5>2<i data-feather="star"></i></h5>
                                                                                <div class="progress">
                                                                                    <div class="progress-bar"
                                                                                        style="width: 20%;">
                                                                                    </div>
                                                                                </div>
                                                                                <h5 class="total">1</h5>
                                                                            </div>
                                                                        </li>
                                                                        <li>
                                                                            <div class="rating-product">
                                                                                <h5>1<i data-feather="star"></i></h5>
                                                                                <div class="progress">
                                                                                    <div class="progress-bar"
                                                                                        style="width: 20%;">
                                                                                    </div>
                                                                                </div>
                                                                                <h5 class="total">1</h5>
                                                                            </div>
                                                                        </li>

                                                                    </ul>

                                                                    <div class="review-title-2">
                                                                        <h4 class="fw-bold">Review this product</h4>
                                                                        <p>Let other customers know what you think</p>
                                                                        <button class="btn" type="button"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#writereview">Write a
                                                                            review</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-7">
                                                        <div class="review-people">
                                                            <ul class="review-list">
                                                                <li>
                                                                    <div class="people-box">
                                                                        <div>
                                                                            <div class="people-image people-text">
                                                                                <img alt="user" class="img-fluid "
                                                                                    src="assets/images/review/1.jpg">
                                                                            </div>
                                                                        </div>
                                                                        <div class="people-comment">
                                                                            <div class="people-name"><a
                                                                                    href="javascript:void(0)"
                                                                                    class="name">Jack Doe</a>
                                                                                <div class="date-time">
                                                                                    <h6 class="text-content"> 29 Sep
                                                                                        2023
                                                                                        06:40:PM
                                                                                    </h6>
                                                                                    <div class="product-rating">
                                                                                        <ul class="rating">
                                                                                            <li>
                                                                                                <i data-feather="star"
                                                                                                    class="fill"></i>
                                                                                            </li>
                                                                                            <li>
                                                                                                <i data-feather="star"
                                                                                                    class="fill"></i>
                                                                                            </li>
                                                                                            <li>
                                                                                                <i data-feather="star"
                                                                                                    class="fill"></i>
                                                                                            </li>
                                                                                            <li>
                                                                                                <i data-feather="star"
                                                                                                    class="fill"></i>
                                                                                            </li>
                                                                                            <li>
                                                                                                <i
                                                                                                    data-feather="star"></i>
                                                                                            </li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="reply">
                                                                                <p>Avoid this product. The quality is
                                                                                    terrible, and
                                                                                    it started falling apart almost
                                                                                    immediately. I
                                                                                    wish I had read more reviews before
                                                                                    buying.
                                                                                    Lesson learned.</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="people-box">
                                                                        <div>
                                                                            <div class="people-image people-text">
                                                                                <img alt="user" class="img-fluid "
                                                                                    src="assets/images/review/2.jpg">
                                                                            </div>
                                                                        </div>
                                                                        <div class="people-comment">
                                                                            <div class="people-name"><a
                                                                                    href="javascript:void(0)"
                                                                                    class="name">Jessica
                                                                                    Miller</a>
                                                                                <div class="date-time">
                                                                                    <h6 class="text-content"> 29 Sep
                                                                                        2023
                                                                                        06:34:PM
                                                                                    </h6>
                                                                                    <div class="product-rating">
                                                                                        <div class="product-rating">
                                                                                            <ul class="rating">
                                                                                                <li>
                                                                                                    <i data-feather="star"
                                                                                                        class="fill"></i>
                                                                                                </li>
                                                                                                <li>
                                                                                                    <i data-feather="star"
                                                                                                        class="fill"></i>
                                                                                                </li>
                                                                                                <li>
                                                                                                    <i data-feather="star"
                                                                                                        class="fill"></i>
                                                                                                </li>
                                                                                                <li>
                                                                                                    <i data-feather="star"
                                                                                                        class="fill"></i>
                                                                                                </li>
                                                                                                <li>
                                                                                                    <i
                                                                                                        data-feather="star"></i>
                                                                                                </li>
                                                                                            </ul>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="reply">
                                                                                <p>Honestly, I regret buying this item.
                                                                                    The
                                                                                    quality
                                                                                    is subpar, and it feels like a waste
                                                                                    of
                                                                                    money. I
                                                                                    wouldn't recommend it to anyone.</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="people-box">
                                                                        <div>
                                                                            <div class="people-image people-text">
                                                                                <img alt="user" class="img-fluid "
                                                                                    src="assets/images/review/3.jpg">
                                                                            </div>
                                                                        </div>
                                                                        <div class="people-comment">
                                                                            <div class="people-name"><a
                                                                                    href="javascript:void(0)"
                                                                                    class="name">Rome Doe</a>
                                                                                <div class="date-time">
                                                                                    <h6 class="text-content"> 29 Sep
                                                                                        2023
                                                                                        06:18:PM
                                                                                    </h6>
                                                                                    <div class="product-rating">
                                                                                        <ul class="rating">
                                                                                            <li>
                                                                                                <i data-feather="star"
                                                                                                    class="fill"></i>
                                                                                            </li>
                                                                                            <li>
                                                                                                <i data-feather="star"
                                                                                                    class="fill"></i>
                                                                                            </li>
                                                                                            <li>
                                                                                                <i data-feather="star"
                                                                                                    class="fill"></i>
                                                                                            </li>
                                                                                            <li>
                                                                                                <i data-feather="star"
                                                                                                    class="fill"></i>
                                                                                            </li>
                                                                                            <li>
                                                                                                <i
                                                                                                    data-feather="star"></i>
                                                                                            </li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="reply">
                                                                                <p>I am extremely satisfied with this
                                                                                    purchase. The
                                                                                    item arrived promptly, and the
                                                                                    quality
                                                                                    is
                                                                                    exceptional. It's evident that the
                                                                                    makers paid
                                                                                    attention to detail. Overall, a
                                                                                    fantastic buy!
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="people-box">
                                                                        <div>
                                                                            <div class="people-image people-text">
                                                                                <img alt="user" class="img-fluid "
                                                                                    src="assets/images/review/4.jpg">
                                                                            </div>
                                                                        </div>
                                                                        <div class="people-comment">
                                                                            <div class="people-name"><a
                                                                                    href="javascript:void(0)"
                                                                                    class="name">Sarah
                                                                                    Davis</a>
                                                                                <div class="date-time">
                                                                                    <h6 class="text-content"> 29 Sep
                                                                                        2023
                                                                                        05:58:PM
                                                                                    </h6>
                                                                                    <div class="product-rating">
                                                                                        <ul class="rating">
                                                                                            <li>
                                                                                                <i data-feather="star"
                                                                                                    class="fill"></i>
                                                                                            </li>
                                                                                            <li>
                                                                                                <i data-feather="star"
                                                                                                    class="fill"></i>
                                                                                            </li>
                                                                                            <li>
                                                                                                <i data-feather="star"
                                                                                                    class="fill"></i>
                                                                                            </li>
                                                                                            <li>
                                                                                                <i data-feather="star"
                                                                                                    class="fill"></i>
                                                                                            </li>
                                                                                            <li>
                                                                                                <i
                                                                                                    data-feather="star"></i>
                                                                                            </li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="reply">
                                                                                <p>I am genuinely delighted with this
                                                                                    item.
                                                                                    It's a
                                                                                    total winner! The quality is superb,
                                                                                    and
                                                                                    it has
                                                                                    added so much convenience to my
                                                                                    daily
                                                                                    routine.
                                                                                    Highly satisfied customer!</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="people-box">
                                                                        <div>
                                                                            <div class="people-image people-text">
                                                                                <img alt="user" class="img-fluid "
                                                                                    src="assets/images/review/5.jpg">
                                                                            </div>
                                                                        </div>
                                                                        <div class="people-comment">
                                                                            <div class="people-name"><a
                                                                                    href="javascript:void(0)"
                                                                                    class="name">John Doe</a>
                                                                                <div class="date-time">
                                                                                    <h6 class="text-content"> 29 Sep
                                                                                        2023
                                                                                        05:22:PM
                                                                                    </h6>
                                                                                    <div class="product-rating">
                                                                                        <ul class="rating">
                                                                                            <li>
                                                                                                <i data-feather="star"
                                                                                                    class="fill"></i>
                                                                                            </li>
                                                                                            <li>
                                                                                                <i data-feather="star"
                                                                                                    class="fill"></i>
                                                                                            </li>
                                                                                            <li>
                                                                                                <i data-feather="star"
                                                                                                    class="fill"></i>
                                                                                            </li>
                                                                                            <li>
                                                                                                <i data-feather="star"
                                                                                                    class="fill"></i>
                                                                                            </li>
                                                                                            <li>
                                                                                                <i
                                                                                                    data-feather="star"></i>
                                                                                            </li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="reply">
                                                                                <p>Very impressed with this purchase.
                                                                                    The
                                                                                    item is of
                                                                                    excellent quality, and it has
                                                                                    exceeded
                                                                                    my
                                                                                    expectations.</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-5 wow fadeInUp">
                    <div class="right-box-contain">
                        <div class="main-right-box-contain">
                            <div class="vendor-box">
                                <div class="vendor-contain">
                                    <?php
// Supposons que $produit['auteur'] contient le nom de l'auteur récupéré
$auteur = $produit['auteur'];

// Effectuer la requête pour récupérer l'image de l'auteur dans la table admin
$query = $database->prepare('
    SELECT image_auteur
    FROM admin
    WHERE nom = :auteur
');
$query->bindParam(':auteur', $auteur);
$query->execute();

// Récupérer l'image de l'auteur
$image_auteur = $query->fetchColumn();

// Vérifier si l'image existe, sinon utiliser une image par défaut
if (!$image_auteur) {
    $image_auteur = "https://s3.envato.com/files/252668307/Icon.jpg";  // Image par défaut
}
?>



                                    <div class="vendor-name">
                                    
                                        <div class="product-info">
    <ul>
        <li class="license-gpl"><strong style="text-align: center;">Licence GPL</strong></li>
        <li><i class="check-icon">&#10004;</i><strong>Vérifié à partir de VirusTotal</strong></li>
        <li><i class="check-icon">&#10004;</i><strong>Produit 100 % original et sans virus.</strong></li>
 
        <li><i class="check-icon">&#10004;</i><strong>Utilisation illimitée du site Web</strong></li>
     
    </ul>
</div>



<style type="text/css">
.product-info ul {
    list-style-type: none;  /* Supprime les puces par défaut */
    padding-left: 0;        /* Supprime l'espace à gauche */
}

.product-info li {
    font-size: 16px;        /* Taille de texte normale */
    margin-bottom: 10px;     /* Espacement entre les éléments */
    display: flex;           /* Utilise Flexbox pour aligner les éléments */
    align-items: center;     /* Aligne verticalement les éléments */
}

.check-icon {
    color: green;           /* Icône de validation verte */
    margin-right: 10px;      /* Espacement entre l'icône et le texte */
    font-size: 18px;         /* Taille de l'icône */
}

.license-gpl {
    background-color: #0ea487;  /* Une couleur de fond bien visible */
    color: white;               /* Texte en blanc */
    padding: 10px;              /* Un peu d'espace autour du texte */
    font-size: 18px;            /* Augmente la taille du texte */
    font-weight: bold;          /* Met le texte en gras */
    border-radius: 5px;         /* Bords arrondis pour un effet plus doux */
    margin-bottom: 10px;        /* Un peu d'espace sous l'élément */
    text-align: center;         /* Centrer le texte */
    width: 100%;                /* Occupe toute la largeur disponible */
}

.product-info ul li.license-gpl {
    text-align: center;         /* Centre le texte "Licence GPL" */
    justify-content: center;    /* Assure que l'élément est centré */
    display: flex;              /* Utilise flexbox pour centrer l'élément */
    width: 100%;                /* Occupe toute la largeur du parent */
}


</style>
                                    </div>
                                </div>
                            </div>

<p style="color: #e45a0f; font-weight: bold;">
  🎉 Offre spéciale : -20% avec le code <span style="background: #1d2355; color: white; padding: 2px 6px; border-radius: 4px;">NDIGIT20</span><br>
  Saisissez-le lors du paiement pour en profiter !
</p>

                            <div class="product-package">
                                <div class="product-title m-0">
    <h4>Prix</h4>
    <div class="product-price">
        <?php if ($produit['prix_reduction'] > 0): ?>
            <!-- Afficher le prix réduit et le prix barré avec réduction -->
            <h6 style="font-size: 20px;">
                <?php echo number_format($produit['prix_reduction'], 2); ?> <?php echo $cfa ?>
                <del class="text-danger"><?php echo number_format($produit['prix'], 2); ?> <?php echo $cfa ?></del>
                <span class="text-success">
                    <?php 
                        // Calcul du pourcentage de réduction
                        $pourcentage_reduction = (($produit['prix'] - $produit['prix_reduction']) / $produit['prix']) * 100;
                        echo number_format($pourcentage_reduction, 0); 
                    ?>% off
                </span>
            </h6>
        <?php else: ?>
            <!-- Si pas de réduction, afficher seulement le prix normal -->
            <h6 style="font-size: 20px;"><?php echo number_format($produit['prix'], 2); ?> <?php echo $cfa ?></h6>
        <?php endif; ?>
    </div>
</div>

                              <!--   <ul class="license-list">
                                    <li class="form-check">
                                        <input class="form-check-input" type="radio" name="box2" id="l4" checked="">
                                        <label class="form-check-label" for="l4">
                                            <span class="circle-box">
                                                <span class="circle"></span>
                                                <span class="name">Regular License <span><span>$</span>89</span></span>
                                            </span>
                                        </label>
                                    </li>

                                    <li class="form-check">
                                        <input class="form-check-input" type="radio" name="box2" id="l5">
                                        <label class="form-check-label" for="l5">
                                            <span class="circle-box">
                                                <span class="circle"></span>
                                                <span class="name">Multiple Use<span><span>$</span>2546</span></span>
                                            </span>
                                        </label>
                                    </li>
                                    <li class="form-check">
                                        <input class="form-check-input" type="radio" name="box2" id="l6">
                                        <label class="form-check-label" for="l6">
                                            <span class="circle-box">
                                                <span class="circle"></span>
                                                <span class="name">Extended License
                                                    <span><span>$</span>2546</span></span>
                                            </span>
                                        </label>
                                    </li>
                                </ul> -->

                              


                                      <div class="note-box product-package">
                                  <!-- Bouton Aperçu en direct si la valeur de 'aperçu' n'est pas '-' -->
                                  <?php if ($produit['prix'] > 0): ?>
                                       <a target="_bank" class="btn border-btn cart-button" href="<?php echo htmlspecialchars($produit['apercue']); ?>" class="btn btn-outline">Aperçu en direct</a>
                                  <?php endif; ?>
                              
<form  style="width: 100%;" class="btt-button" action="add_product" method="POST">                                 <!-- Champ caché pour le nom du produit -->
<!-- Champ caché pour le nom du produit -->
<input type="hidden" name="nom" value="<?php echo htmlspecialchars($produit['nom_article']); ?>" />

<!-- Champ caché pour l'ID du produit -->
<input type="hidden" name="id" value="<?php echo htmlspecialchars($produit['id']); ?>" />

<!-- Champ pour saisir la quantité du produit -->
<input 
       type="hidden" 
       value="1" 
       class="quantity__number quickview__value--number" 
       name="nombre" 
       min="1" /> <!-- Minimum de 1 pour éviter de mettre 0 -->

<!-- Bouton Ajouter au panier avec un attribut onclick -->

<?php
// Vérification de la session utilisateur
if (!isset($_SESSION['user_id'])) {
    if ($produit['prix'] == 0) {
        // L'utilisateur n'est pas connecté et le produit est gratuit
        echo '
            <button type="button" class="btn fill-btn cart-button" style="background: #c56405;" data-toggle="modal" data-target="#loginModal">
                Télécharger gratuitement
            </button>';
    } else {
        // L'utilisateur n'est pas connecté et le produit est payant
        echo '
            <button style="border:none;width: 100%;" type="submit" name="ajouter_panier">
                <a class="btn fill-btn cart-button">Ajouter au panier</a>
            </button>
            <button style="border:none;width: 100%;" type="submit" name="telecharge">
                <a class="btn fill-btn cart-button" style="background: #c56405;">Acheter Maintenant</a>
            </button>';
    }
      echo'
                    <div class="pt-4">
                            <div class="discount-box">
                                <h4>Obtenez un accès  <span>illimité</span></h4>
                                <p>Accédez à des téléchargements illimités et à des ressources exclusives avec notre abonnement premium.</p>
                                <a href="abonnement" class="btn discount-btn">Obtenez un accès illimité</a>
                            </div>
                    </div>';

}


if (isset($_SESSION['user_id'])) {
    // Sécurisation de l'email
    $email = $_SESSION['email'];

    // Requête préparée pour éviter l'injection SQL
    $stmt = $database->prepare("SELECT * FROM utilisateur WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $donnee1 = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($donnee1) {
        // L'utilisateur existe bien, on peut traiter les informations
        if ($donnee1['type'] == "-") {
            if ($produit['prix'] == 0) {
                echo '
                    <button style="border:none;width: 100%;" type="submit" name="telecharge_gratuit">
                        <a class="btn fill-btn cart-button" style="background: #c56405;">Télécharger gratuitement</a>
                    </button>';
            } else {
                echo '
                    <button style="border:none;width: 100%;" type="submit" name="ajouter_panier">
                        <a class="btn fill-btn cart-button">Ajouter au panier</a>
                    </button>
                    <button style="border:none;width: 100%;" type="submit" name="telecharge">
                        <a class="btn fill-btn cart-button" style="background: #c56405;">Acheter Maintenant</a>
                    </button>';
            }

            echo '
                <div class="pt-4">
                    <div class="discount-box">
                        <h4>Obtenez un accès  <span>illimité</span></h4>
                        <p>Accédez à des téléchargements illimités et à des ressources exclusives avec notre abonnement premium.</p>
                      <a href="abonnement" class="btn discount-btn">Obtenez un accès illimité</a>
                    </div>
                </div>';

        } elseif ($donnee1['type'] == "pro") {
            // Récupérer les informations d'abonnement pour cet utilisateur
            $stmt = $database->prepare("SELECT * FROM abonnement WHERE id_uti2 = ?");
            $stmt->execute([$donnee1['id_uti']]);
            $abonnement = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($abonnement) {
                // Calculer la date d'expiration
                $date_fin = new DateTime($abonnement['date_fin']);
                $date_aujourdhui = new DateTime(); // Définir la date d'aujourd'hui

                // Vérifier si la date d'aujourd'hui est inférieure à la date d'expiration
                if ($date_aujourdhui < $date_fin) {
                    // Si l'abonnement est encore valide
                    $diff = $date_aujourdhui->diff($date_fin);

                    echo '

                        <button style="border:none;width: 100%;" type="submit" name="telecharge_pro">
                            <a class="btn fill-btn cart-button" style="background: #c56405;">Téléchargement Direct</a>
                        </button>
                        ';
                } else {
                    if ($produit['prix'] == 0) {
                echo '
                    <button style="border:none;width: 100%;" type="submit" name="telecharge_gratuit">
                        <a class="btn fill-btn cart-button" style="background: #c56405;">Télécharger gratuitement</a>
                    </button>';
            } else {
                echo '
                    <button style="border:none;width: 100%;" type="submit" name="ajouter_panier">
                        <a class="btn fill-btn cart-button">Ajouter au panier</a>
                    </button>
                    <button style="border:none;width: 100%;" type="submit" name="telecharge">
                        <a class="btn fill-btn cart-button" style="background: #c56405;">Acheter Maintenant</a>
                    </button>';
            }

                        echo'
                    <div class="pt-4">
                            <div class="discount-box">
                                <h4>Obtenez un accès  <span>illimité</span></h4>
                                <p>Accédez à des téléchargements illimités et à des ressources exclusives avec notre abonnement premium.</p>
                                <a href="abonnement" class="btn discount-btn">Obtenez un accès illimité</a>
                            </div>
                    </div>';
                }
            }
        }
    } else {
        // Si aucun utilisateur trouvé
        echo "<p style='color: red;'>Erreur : utilisateur non trouvé.</p>";
    }
}

?>

        </form>

                     <!--  <div class="pt-4">
                            <div class="discount-box">
                                <h4>Obtenez un accès  <span>illimité</span></h4>
                                <p>Accédez à ce produit et profitez d’une utilisation illimitée de 10 000+ outils de premier ordre.</p>
                                <button class="btn discount-btn">Obtenez un accès illimité</button>
                            </div>
                        </div> -->

<?php
// Vérifier si un message de succès existe dans la session
if (isset($_SESSION['panier_message'])) {
    // Afficher l'alerte SweetAlert2 avec un délai de 3 secondes (3000 ms)
    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: "success",
            title: "Produit ajouté au panier",
            text: "' . $_SESSION['panier_message'] . '",
            confirmButtonColor: "#0a4e83", // Couleur du bouton de confirmation
            timer: 1800, // L\'alerte disparaît après 3000ms (3 secondes)
            timerProgressBar: true // Affiche une barre de progression
        }).then((result) => {
            // Redirection après la confirmation (lorsque l\'utilisateur ferme l\'alerte)
            window.location.href = "product_detail.php?nom_article=' . urlencode($_GET['nom_article']) . '";
        });
    </script>';

    // Supprimer le message de la session après l'affichage
    unset($_SESSION['panier_message']);
}
?>



                                 
                              </div>
                            </div>


                            <script type="text/javascript">
                                <script type="text/javascript">
    function addToCart(productId) {
        const quantity = document.querySelector('input[name="nombre"]').value;
        
        fetch('add_product.php?id=' + productId + '&nombre=' + quantity + '&nom=' + encodeURIComponent('<?php echo $produit['nom_article']; ?>'))
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Succès !",
                        text: data.message,
                        confirmButtonColor: "#0a4e83"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Mise à jour du nombre d'articles dans le panier
                            document.getElementById('cart-total').innerText = data.cart_total;
                            
                            // Mise à jour de la notification de l'icône du panier
                            document.querySelector('.notification-badge').innerText = data.cart_total;
                        }
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Erreur",
                        text: data.message,
                        confirmButtonColor: "#0a4e83"
                    });
                }
            })
            .catch(error => {
                console.error('Erreur lors de l\'ajout au panier :', error);
            });
    }
</script>

        
                            </script>

                           <!--  <div class="time deal-timer product-deal-timer mx-md-0" id="clockdiv-1" data-hours="1"
                                data-minutes="2" data-seconds="3">
                                <div class="product-title">
                                    <h4>Hurry up! Sales Ends In</h4>
                                </div>
                                <ul>
                                    <li>
                                        <div class="counter d-block">
                                            <div class="days d-block">14</div>
                                            <h6>Days</h6>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="counter d-block">
                                            <div class="hours d-block">23</div>
                                            <h6>Hours</h6>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="counter d-block">
                                            <div class="minutes d-block">48</div>
                                            <h6>Min</h6>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="counter d-block">
                                            <div class="seconds d-block">27</div>
                                            <h6>Sec</h6>
                                        </div>
                                    </li>
                                </ul>
                            </div> -->

                            <div class="buy-box">
                          <!--       <a href="wishlist.html">
                                    <i data-feather="heart"></i>
                                    <span>Ajouter à la liste de souhaits</span>
                                </a>
 -->
                               
                            </div>

                            <div class="pickup-box">
                                <div class="product-title">
                                    <h4>Informations</h4>
                                </div>

                                <div class="product-info">
                                    <ul class="product-info-list product-info-list-2">
                                   

                                        <li>Catégorie <a href="javascript:void(0)"><?php echo htmlspecialchars($produit['nom_categorie']); ?>, <?php echo htmlspecialchars($produit['sous_categorie']); ?></a></li>
                                      <!--   <li>Version : <a href="javascript:void(0)">2.0.0</a></li>
                                        <li>Tags : <a href="javascript:void(0)">HTML, CSS, Bootstrap 5, Javascript,
                                                SCSS</a></li> -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                     <!--    <div class="pt-4">
                            <div class="discount-box">
                                <h4>Get this asset for free & <span>save up to 25%</span></h4>
                                <p>Join now to receive monthly free assets, site-wide discounts, and a free download of
                                    your first item.</p>
                                <button class="btn discount-btn">Subscribe & Download</button>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </section>



 <?php
// Connexion à la base de données
// require('header.php'); // Inclure la connexion à la base de données ici
// Utilisation de la préparation de la requête pour éviter les injections SQL
$query = $database->prepare('
    SELECT *
    FROM produits
    INNER JOIN categories ON produits.categorie_id = categories.id
    WHERE categories.nom_categorie = :nom_categorie
    ORDER BY RAND()
    LIMIT 8
');


// Lier la valeur de la catégorie au paramètre de la requête
$query->bindParam(':nom_categorie', $produit['nom_categorie'], PDO::PARAM_STR);

// Exécuter la requête
$query->execute();

// Récupérer les résultats
$produits = $query->fetchAll(PDO::FETCH_ASSOC);




    // $donnee = $resultats->fetch()


?>
<section class="product-section">
    <div class="container-fluid-lg">
        <div class="title">
            <h2>Autre Modèles</h2>
        </div>
        <div class="row g-4">
            <?php foreach ($produits as $produit2): ?>
                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <div class="product-theme-box">
                        <div class="img-box ratio_50">
                            <a href="product_detail.php?nom_article=<?php echo htmlspecialchars($produit2['nom_article']); ?>">
                             <?php 
$imagePath = 'back-end/apps/' . htmlspecialchars($produit2['image']);
if (!file_exists($imagePath)) {
    $imagePath = 'back-end/apps/default-image.jpg'; // Remplace avec l'image par défaut
}
?>
<img src="<?php echo $imagePath; ?>" class="bg-img" alt="<?php echo htmlspecialchars($produit2['nom_article']); ?>">

                            </a>
                            <a href="#!" class="heart-icon">
                                <i data-feather="heart"></i>
                            </a>


                          <?php if ($produit2['prix_reduction'] > 0): ?>
                                <?php
                                    // Calcul du pourcentage de réduction
                                    $pourcentage_reduction = (($produit2['prix'] - $produit2['prix_reduction']) / $produit2['prix']) * 100;
                                ?>
                                <p class="discount-percentage" style="background-color: red; color: white; font-weight: bold; padding: 5px 10px; border-radius: 5px; position: absolute; top: 10px; right: 50px;">
                                    -<?php echo number_format($pourcentage_reduction, 0); ?>%
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="content-box">
                            <div class="top-content">
                                <a href="product_detail.php?nom_article=<?php echo htmlspecialchars($produit2['nom_article']); ?>">
                                    <h5><?php echo htmlspecialchars($produit2['nom_article']); ?></h5>
                                </a>
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6> Catégorie: <a href="#!"><?php echo htmlspecialchars($produit2['nom_categorie']); ?></a></h6>
                                  <!-- reviec -->
                                </div>
                            </div>
                            <div class="bottom-content">
                              <div>
    <!-- <span>2 Sales</span> -->
    
    <?php if ($produit2['prix_reduction'] > 0): ?>
        <!-- Prix barré (normal) -->
        <h6 style="text-decoration: line-through;font-size: 11px" class=""><?php echo number_format($produit2['prix'], 2); ?> <?php echo $cfa ?></h6>
        <!-- Prix de réduction -->
        <h5 class="price new-price"><?php echo number_format($produit2['prix_reduction'], 2); ?> <?php echo $cfa ?></h5>
    <?php else: ?>
        <!-- Si pas de réduction, afficher juste le prix normal -->
        <h5 class="price"><?php echo number_format($produit2['prix'], 2); ?> <?php echo $cfa ?></h5>
    <?php endif; ?>
</div>

                                <div class="btn-grp">
                                    <!-- <a class="btn btn-outline" href="#!"> <i data-feather="shopping-cart"></i></a> -->
                                    <a class="btn" href="product_detail.php?nom_article=<?php echo htmlspecialchars($produit2['nom_article']); ?>">Voir les détails</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>






       <div class="sticky-bottom-cart">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="cart-content">
                        <div class="product-image">
                            <img src="back-end/apps/<?php echo htmlspecialchars($produit['image']); ?>" class="img-fluid blur-up lazyload" alt="">
                            <div class="content">
                                <h5><?php echo htmlspecialchars($produit['nom_article']); ?></h5>
                             <div class="product-price">
    <?php if ($produit['prix_reduction'] > 0): ?>
        <!-- Afficher le prix barré et la réduction -->
        <h6>
            <?php echo number_format($produit['prix_reduction'], 2); ?> <?php echo $cfa ?>
            <del class="text-danger"><?php echo number_format($produit['prix'], 2); ?> <?php echo $cfa ?></del>
            <span><?php 
                // Calcul du pourcentage de réduction
                $pourcentage_reduction = (($produit['prix'] - $produit['prix_reduction']) / $produit['prix']) * 100;
                echo number_format($pourcentage_reduction, 0); 
            ?>% off</span>
        </h6>
    <?php else: ?>
        <!-- Si pas de réduction, afficher seulement le prix normal -->
        <h6><?php echo number_format($produit['prix'], 2); ?> <?php echo $cfa ?></h6>
    <?php endif; ?>
</div>

                            </div>
                        </div>
                        <div class="selection-section">
                           <!--  <div class="form-group mb-0">
                                <select id="input-state" class="form-control form-select">
                                    <option selected disabled>Choose Weight...</option>
                                    <option>1/2 KG</option>
                                    <option>1 KG</option>
                                    <option>1.5 KG</option>
                                </select>
                            </div> -->
                          <!--   <div class="cart_qty qty-box product-qty m-0">
                                <div class="input-group h-100">
                                    <button type="button" class="qty-left-minus" data-type="minus" data-field="">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                    <input class="form-control input-number qty-input" type="text" name="quantity"
                                        value="1">
                                    <button type="button" class="qty-right-plus" data-type="plus" data-field="">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div> -->
                        </div>

                 <form class="btn fill-btn cart-button" action="add_product" method="POST">                                 <!-- Champ caché pour le nom du produit -->
<!-- Champ caché pour le nom du produit -->

<input type="hidden" name="nom" value="<?php echo htmlspecialchars($produit['nom_article']); ?>" />

<!-- Champ caché pour l'ID du produit -->
<input type="hidden" name="id" value="<?php echo htmlspecialchars($produit['id']); ?>" />

<!-- Champ pour saisir la quantité du produit -->
<input 
       type="hidden" 
       value="1" 
       class="quantity__number quickview__value--number" 
       name="nombre" 
       min="1" /> <!-- Minimum de 1 pour éviter de mettre 0 -->

   
                        <div class="add-btn">

<?php
// Vérification de l'état de connexion de l'utilisateur
if (isset($_SESSION['user_id'])) {
    // L'utilisateur est connecté, on récupère ses informations
    $email = $_SESSION['email'];

    // Requête préparée pour éviter l'injection SQL
    $stmt = $database->prepare("SELECT * FROM utilisateur WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $donnee1 = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($donnee1) {
        // L'utilisateur existe, vérifier son type
        if ($donnee1['type'] == "-") {
            // Utilisateur normal
            if ($produit['prix'] == 0) {
                // Si le produit est gratuit
                echo '
                    <form class="btn fill-btn cart-button" action="add_product" method="POST">
                        <button style="border:none" type="submit" name="telecharge_gratuit">
                            <a style="background: #c56405" class="btn theme-bgcolor text-white">
                                <i class="fas fa-shopping-cart"></i> Télécharger gratuitement
                            </a>
                        </button>
                    </form>';
            } else {
                // Produit payant
                echo '
                    <form class="btn fill-btn cart-button" action="add_product" method="POST">
                        <button style="border:none" type="submit" name="ajouter_panier">
                            <a class="btn theme-bg-color text-white wishlist-btn">
                                <i class="fa fa-bookmark"></i> Ajouter au panier
                            </a>
                        </button>
                        <button style="border:none" type="submit" name="telecharge">
                            <a style="background: #c56405" class="btn theme-bgcolor text-white">
                                <i class="fas fa-shopping-cart"></i> Acheter Maintenant
                            </a>
                        </button>
                    </form>';
            }
        } elseif ($donnee1['type'] == "pro") {
            // Utilisateur Pro
            $stmt = $database->prepare("SELECT * FROM abonnement WHERE id_uti2 = ?");
            $stmt->execute([$donnee1['id_uti']]);
            $abonnement = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($abonnement) {
                // Calculer la date d'expiration
                $date_fin = new DateTime($abonnement['date_fin']);
                $date_aujourdhui = new DateTime(); // Date d'aujourd'hui

                if ($date_aujourdhui < $date_fin) {
                    // Si l'abonnement est encore valide
                    echo '
                        <form class="btn fill-btn cart-button" action="add_product" method="POST">
                            <button style="border:none" type="submit" name="telecharge_pro">
                                <a style="background: #c56405" class="btn theme-bgcolor text-white">
                                    <i class="fas fa-shopping-cart"></i> Télécharger gratuitement
                                </a>
                            </button>
                        </form>';

                } else {
                    // Abonnement expiré
                    if ($produit['prix'] == 0) {
        echo '
            <button type="button" class="btn theme-bgcolor text-white" style="background: #c56405;" data-toggle="modal" data-target="#loginModal">
                <i class="fas fa-shopping-cart"></i> Télécharger gratuitement
            </button>';
    } else {
    echo '
                    <form class="btn fill-btn cart-button" action="add_product" method="POST">
                        <button style="border:none" type="submit" name="ajouter_panier">
                            <a class="btn theme-bg-color text-white wishlist-btn">
                                <i class="fa fa-bookmark"></i> Ajouter au panier
                            </a>
                        </button>
                        <button style="border:none" type="submit" name="telecharge">
                            <a style="background: #c56405" class="btn theme-bgcolor text-white">
                                <i class="fas fa-shopping-cart"></i> Acheter Maintenant
                            </a>
                        </button>
                    </form>';
    }
                }
            } else {
                // Si l'abonnement n'existe pas
                echo '
                    <form class="btn fill-btn cart-button" action="add_product" method="POST">
                        <button style="border:none" type="submit" name="ajouter_panier">
                            <a class="btn theme-bg-color text-white wishlist-btn">
                                <i class="fa fa-bookmark"></i> Ajouter au panier
                            </a>
                        </button>
                        <button style="border:none" type="submit" name="telecharge">
                            <a style="background: #c56405" class="btn theme-bgcolor text-white">
                                <i class="fas fa-shopping-cart"></i> Acheter Maintenant
                            </a>
                        </button>
                    </form>';
            }
        }
    } else {
        // Si l'utilisateur n'existe pas dans la base de données
        echo "<p style='color: red;'>Erreur : utilisateur non trouvé.</p>";
    }
} else {
    // Si l'utilisateur n'est pas connecté, afficher le pop-up de connexion
    if ($produit['prix'] == 0) {
        echo '
            <button type="button" class="btn theme-bgcolor text-white" style="background: #c56405;" data-toggle="modal" data-target="#loginModal">
                <i class="fas fa-shopping-cart"></i> Télécharger gratuitement
            </button>';
    } else {
    echo '
                    <form class="btn fill-btn cart-button" action="add_product" method="POST">
                        <button style="border:none" type="submit" name="ajouter_panier">
                            <a class="btn theme-bg-color text-white wishlist-btn">
                                <i class="fa fa-bookmark"></i> Ajouter au panier
                            </a>
                        </button>
                        <button style="border:none" type="submit" name="telecharge">
                            <a style="background: #c56405" class="btn theme-bgcolor text-white">
                                <i class="fas fa-shopping-cart"></i> Acheter Maintenant
                            </a>
                        </button>
                    </form>';
    }
}
?>

                           
                        </div>
                    </div>
                         </form>
                </div>
            </div>
        </div>
    </div>

          
<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Connexion ou Inscription</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <!-- Formulaire de connexion -->
                <div class="checkout-detail" id="login-form">
                    <p>Vous devez vous connecter à votre compte pour télécharger gratuitement ce modèle.</p>
                   <?php
    
if (isset($_POST['connexion'])) {
    $mdp = htmlspecialchars($_POST['mdp']);
    $email = htmlspecialchars($_POST['email']);

    $resultats = $database->query('SELECT * FROM utilisateur');

    $a = false;

    while ($donnee = $resultats->fetch()) {
        // Vérification du mot de passe avec password_verify
        if ($donnee['email'] == $email && password_verify($mdp, $donnee['mdp'])) {
            $_SESSION["user_id"] = "oui";
            $_SESSION["email"] = $email;
             echo '<script>alert("Connexion réussie !"); window.location.href="product_detail?nom_article='.$_GET['nom_article'].'";</script>';
            $a = true;
            break;  // Sortir de la boucle si une correspondance est trouvée
        }
    }

    if ($a == false) {
        echo '<p style="color:red;text-align:center">Adresse ou mot de passe ne correspondent pas</p>';
    }
}

if (isset($_POST['inscrire'])) {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $mdp1 = htmlspecialchars($_POST['mdp1']);
    $mdp2 = htmlspecialchars($_POST['mdp2']);
    $statut = "-";
    $type = "-";

    // Vérification si les mots de passe correspondent
    if ($mdp1 != $mdp2) {
        echo '<p style="color:red;text-align:center">Les mots de passe ne correspondent pas</p>';
    } else {
        // Vérification si l'email est déjà utilisé
        $resultats = $database->query('SELECT * FROM utilisateur');
        $a = false;

        while ($donnee = $resultats->fetch()) {
            if ($donnee['email'] == $email) {
                echo '<p style="color:red;text-align:center">Cette adresse e-mail est déjà utilisée. Veuillez vous connecter avec celle-ci ou modifier le mot de passe.</p>';
                $a = true;
                break; // Sortir de la boucle si l'email est déjà utilisé
            }
        }

        // Si l'email n'est pas déjà utilisé, on insère les données
        if ($a == false) {
            // Hachage du mot de passe avant de l'enregistrer
            $hashedPassword = password_hash($mdp1, PASSWORD_DEFAULT);

            // Préparer et exécuter la requête d'insertion
            $inserer = "INSERT INTO utilisateur (nom, prenom, email, type, statut, mdp) VALUES ('$nom', '$prenom', '$email', '$type', '$statut', '$hashedPassword')";
            $query1 = $database->prepare($inserer);
            $database->exec($inserer);

            $_SESSION["user_id"] = "oui";
            $_SESSION["email"] = $email;

              echo '<script>alert("Connexion réussie !"); window.location.href="product_detail?nom_article='.$_GET['nom_article'].'";</script>';
        }
    }
}
?>


                    <form method="post">
                        <div class="row g-4">
                            <div class="col-xxl-6 col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-xxl-6 col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label for="mdp">Mot de passe</label>
                                    <input type="password" id="mdp" name="mdp" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="connexion" class="btn theme-bg-color text-light mt-3">Se connecter</button>
                    </form><br>
                    <p>
                         <a href="oublier" class="text-theme" >Mot de passe Oublier?</a><br><br>
                        <a href="javascript:void(0);" class="text-theme" onclick="toggleForm()">Pas encore inscrit ? Inscrivez-vous ici</a>
                    </p>
                </div>

                <!-- Formulaire d'inscription -->
                <div class="checkout-detail" id="register-form" style="display: none;">
                      <p>Vous devez vous inscrire à votre compte pour télécharger gratuitement ce modèle.</p>
                    <form method="post">
                        <div class="row g-4">
                            <div class="col-xxl-6 col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label for="nom">Nom</label>
                                    <input type="text" id="nom" name="nom" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-xxl-6 col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label for="prenom">Prénom</label>
                                    <input type="text" id="prenom" name="prenom" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-xxl-12 col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="register-email" name="email" class="form-control" required>
                                </div>
                            </div>
                           <div class="col-xxl-12 col-lg-6 col-md-6">
    <div class="form-floating theme-form-floating">
        <input name="mdp1" type="password" class="form-control" id="password"
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

<div class="col-xxl-12 col-lg-6 col-md-6">
    <div class="form-floating theme-form-floating">
        <input name="mdp2" type="password" class="form-control" id="confirm-password"
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
                        </div>
                        <button type="submit" name="inscrire" class="btn theme-bg-color text-light mt-3">S'inscrire</button>
                    </form><br>
                    <p>
                        <a href="javascript:void(0);" class="text-theme" onclick="toggleForm()">Déjà inscrit ? Se connecter</a>
                    </p>
                </div>

            </div>
        </div>
    </div>
</div> 

<!-- JavaScript pour basculer entre les formulaires -->
<script>
function toggleForm() {
    var loginForm = document.getElementById("login-form");
    var registerForm = document.getElementById("register-form");

    if (loginForm.style.display === "none") {
        loginForm.style.display = "block";
        registerForm.style.display = "none";
    } else {
        loginForm.style.display = "none";
        registerForm.style.display = "block";
    }
}
</script>

<!-- JavaScript et Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    
    <!-- Feature category section end -->

  

    <!-- Category Section Start -->



<br><br>

    <?php require('footer.php') ?>

</body>
</html>