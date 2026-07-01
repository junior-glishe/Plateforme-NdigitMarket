<?php
// header.php
session_start();
require_once('include/connect.php');

// Définir la devise
$cfa = "CFA";

// Récupérer le nombre d'articles dans le panier
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = array();
}
$total_articles = array_sum($_SESSION['panier']);

// Récupérer toutes les catégories
$categoriesQuery = "SELECT * FROM categories ORDER BY nom_categorie ASC";
$categoriesStmt = $database->prepare($categoriesQuery);
$categoriesStmt->execute();
$allCategories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);

// Organiser les catégories avec leurs sous-catégories
$categoriesAffichees = [];
foreach ($allCategories as $categorie) {
    $sousCategories = !empty($categorie['sous_categories']) ? explode(',', $categorie['sous_categories']) : [];
    $categoriesAffichees[] = [
        'categorie' => $categorie,
        'sous_categories' => $sousCategories
    ];
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>nDigitMarket - Votre marché de produits numériques</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="responsive-reset.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="icon" type="image/png" href="logo/logof.png">
</head>
<body>

<!-- Navigation -->
<nav class="navbar">
    <div class="navbar-container">
        <a href="index.php" class="nav__logo" aria-label="ndigitmarket">
            <img src="logo/logondigit.png" alt="ndigitmarket" class="logo">
        </a>

        <ul class="navbar-menu">
            <li><a href="index.php">Accueil</a></li>
            <li><a href="products.php">Produits</a></li>
            <li><a href="abonnement.php">Abonnements</a></li>
            <li><a href="about.php">À propos</a></li>
            <li><a href="contact.php">Contact</a></li>
            <?php if (!isset($_SESSION['user_id'])): ?>
            <li id="mobile-login-btn" class="mobile-auth-item">
                <a href="login.php" class="btn-mobile-login">Se connecter</a>
            </li>
            <?php endif; ?>
        </ul>

        <div class="navbar-search">
            <form action="products.php" method="GET">
                <input type="text" name="search_query" id="search-input" class="search-input" placeholder="Rechercher des produits..." value="<?php echo isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : ''; ?>">
                <button type="submit" id="search-btn" class="search-btn" title="Rechercher">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <div class="navbar-auth">
            <button class="btn-cart" id="cartBtn" aria-label="Panier">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count <?php echo $total_articles > 0 ? '' : 'hidden'; ?>"><?php echo $total_articles; ?></span>
            </button>
            
            <?php if (isset($_SESSION['user_id'])): ?>
            <div class="user-menu">
                <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)); ?></div>
                <div class="dropdown-menu">
                    <a href="dashboard.php">Dashboard</a>
                    <a href="dashboard.php#downloads">Mes téléchargements</a>
                    <a href="dashboard.php#subscription">Mon abonnement</a>
                    <a href="deconnexion.php" class="btn-logout">Déconnexion</a>
                </div>
            </div>
            <?php else: ?>
            <div class="user-menu hidden">
                <div class="user-avatar"></div>
                <div class="dropdown-menu">
                    <a href="dashboard.php">Dashboard</a>
                    <a href="dashboard.php#downloads">Mes téléchargements</a>
                    <a href="dashboard.php#subscription">Mon abonnement</a>
                    <a class="btn-logout">Déconnexion</a>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <button class="navbar-toggle">☰</button>
    </div>
</nav>