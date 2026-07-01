<?php
// Connexion à la base de données
require('header.php');

// Vérifier si un terme de recherche est passé dans l'URL
$searchQuery = isset($_GET['search_query']) ? $_GET['search_query'] : '';

// Récupérer les catégories (optionnel si vous souhaitez afficher une liste de catégories également)
$categories = $database->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

// Si un terme de recherche est spécifié
if (!empty($searchQuery)) {
    // Requête pour récupérer les produits correspondant à la recherche
    $produitsParPage = 12;
    $pageActuelle = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($pageActuelle - 1) * $produitsParPage;

    // Requête pour rechercher les produits dont le nom contient la recherche (avec LIKE pour correspondances partielles)
    $queryProduits = 'SELECT produits.*, categories.nom_categorie FROM produits
                      INNER JOIN categories ON produits.categorie_id = categories.id
                      WHERE produits.nom_article LIKE :search_query
                      ORDER BY produits.nom_article ASC
                      LIMIT :offset, :limit';

    $stmtProduits = $database->prepare($queryProduits);
    $stmtProduits->bindValue(':search_query', '%' . $searchQuery . '%', PDO::PARAM_STR);
    $stmtProduits->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmtProduits->bindValue(':limit', $produitsParPage, PDO::PARAM_INT);
    $stmtProduits->execute();
    $produits = $stmtProduits->fetchAll(PDO::FETCH_ASSOC);

    // Calculer le nombre total de produits pour la pagination
    $resultatTotal = $database->prepare('SELECT COUNT(*) AS total FROM produits
                                         WHERE nom_article LIKE :search_query');
    $resultatTotal->bindValue(':search_query', '%' . $searchQuery . '%', PDO::PARAM_STR);
    $resultatTotal->execute();
    $totalProduits = $resultatTotal->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalProduits / $produitsParPage);
} else {
    echo "<p>Aucun produit trouvé pour cette recherche.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche - NDIGITMARKET</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        :root {
            --primary: #087d67;
            --primary-dark: #065a4a;
            --primary-light: #e8f5f2;
            --accent: #f97316;
            --accent-dark: #e67e22;
            --dark: #0f1923;
            --dark-2: #1a2634;
            --text: #374151;
            --text-light: #6b7280;
            --border: #e5e7eb;
            --bg: #f9fafb;
            --white: #ffffff;
            --success: #10b981;
            --danger: #ef4444;
            --shadow: 0 10px 30px -10px rgba(0,0,0,0.1);
            --shadow-hover: 0 20px 40px -15px rgba(8,125,103,0.3);
            --radius: 16px;
            --radius-sm: 8px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
        }

        /* ===== BREADCRUMB ===== */
        .breadcrumb-modern {
            background: linear-gradient(135deg, var(--dark) 0%, var(--dark-2) 100%);
            padding: 40px 0;
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
        }

        .breadcrumb-modern::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(8,125,103,0.2) 0%, transparent 70%);
            border-radius: 50%;
        }

        .breadcrumb-modern::after {
            content: '';
            position: absolute;
            bottom: -50px;
            left: -50px;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(249,115,22,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .breadcrumb-content {
            position: relative;
            z-index: 2;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .breadcrumb-content h1 {
            color: white;
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .breadcrumb-links {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255,255,255,0.6);
            flex-wrap: wrap;
        }

        .breadcrumb-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: color 0.3s;
            font-size: 14px;
        }

        .breadcrumb-links a:hover {
            color: white;
        }

        .breadcrumb-links i {
            font-size: 12px;
        }

        /* ===== RÉSULTATS DE RECHERCHE ===== */
        .search-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px 60px;
        }

        .search-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
            background: white;
            padding: 20px 25px;
            border-radius: 60px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }

        .search-info {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .search-info h2 {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark);
        }

        .search-query {
            background: var(--primary-light);
            color: var(--primary);
            padding: 8px 20px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 16px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .search-query i {
            font-size: 14px;
        }

        .results-count {
            background: var(--bg);
            padding: 8px 20px;
            border-radius: 40px;
            font-size: 15px;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .results-count i {
            color: var(--primary);
        }

        .results-count strong {
            color: var(--primary);
            font-size: 18px;
            margin: 0 2px;
        }

       .products-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 25px;
    margin-bottom: 50px;
}

@media (max-width: 1200px) {
    .products-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 992px) {
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
}

@media (max-width: 768px) {
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
}

@media (max-width: 480px) {
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
}
        /* ===== CARTE PRODUIT MODERNE ===== */
        .product-card {
            background: white;
            border-radius: var(--radius);
            overflow: hidden;
            border: 1px solid var(--border);
            transition: all 0.3s;
            position: relative;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .product-card:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-hover);
            transform: translateY(-5px);
        }

        .product-image-wrapper {
            position: relative;
            aspect-ratio: 1/1;
            overflow: hidden;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e7eb 100%);
        }

        .product-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .product-card:hover .product-image-wrapper img {
            transform: scale(1.08);
        }

        .product-badges {
            position: absolute;
            top: 10px;
            left: 10px;
            display: flex;
            flex-direction: column;
            gap: 5px;
            z-index: 2;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-free {
            background: var(--success);
            color: white;
        }

        .badge-sale {
            background: var(--accent);
            color: white;
        }

        .discount-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--danger);
            color: white;
            padding: 4px 8px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 700;
            z-index: 2;
        }

        .product-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.3);
            backdrop-filter: blur(2px);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 3;
        }

        .product-card:hover .product-overlay {
            opacity: 1;
        }

        .btn-quickview {
            background: white;
            color: var(--dark);
            border: none;
            border-radius: 40px;
            padding: 10px 20px;
            font-weight: 600;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transform: translateY(20px);
            transition: all 0.3s;
        }

        .product-card:hover .btn-quickview {
            transform: translateY(0);
        }

        .btn-quickview:hover {
            background: var(--primary);
            color: white;
        }

        .product-info {
            padding: 15px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .product-category {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--primary);
            letter-spacing: 0.5px;
        }

        .product-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--dark);
            line-height: 1.4;
            text-decoration: none;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-title:hover {
            color: var(--primary);
        }

        .product-author {
            font-size: 12px;
            color: var(--text-light);
            margin-bottom: 5px;
        }

        .product-author a {
            color: var(--primary);
            text-decoration: none;
        }

        .product-author a:hover {
            text-decoration: underline;
        }

        .product-price-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
        }

        .price-box {
            display: flex;
            flex-direction: column;
        }

        .old-price {
            font-size: 11px;
            color: var(--text-light);
            text-decoration: line-through;
        }

        .current-price {
            font-size: 18px;
            font-weight: 800;
            color: var(--primary);
        }

        .price-free {
            font-size: 18px;
            font-weight: 800;
            color: var(--success);
        }

        .btn-detail {
            width: 36px;
            height: 36px;
            background: var(--primary-light);
            color: var(--primary);
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-detail:hover {
            background: var(--primary);
            color: white;
            transform: rotate(360deg);
        }

        /* ===== PAGINATION MODERNE ===== */
        .pagination-modern {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        .page-btn {
            min-width: 45px;
            height: 45px;
            border-radius: 12px;
            background: white;
            border: 1px solid var(--border);
            color: var(--text);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            padding: 0 12px;
        }

        .page-btn:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary);
        }

        .page-btn.active {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .page-btn.disabled {
            opacity: 0.5;
            pointer-events: none;
        }

        @media (max-width: 768px) {
            .page-btn {
                min-width: 40px;
                height: 40px;
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .page-btn {
                min-width: 35px;
                height: 35px;
                font-size: 13px;
            }
        }

        /* ===== ÉTAT VIDE ===== */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }

        .empty-state i {
            font-size: 80px;
            color: var(--border);
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .empty-state p {
            color: var(--text-light);
            margin-bottom: 25px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            padding: 12px 30px;
            border-radius: 40px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(8,125,103,0.3);
        }

        /* ===== LOADER ===== */
        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(3px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 99999;
        }

        .loader-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid var(--border);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

<!-- Loader -->
<div class="loader-overlay" id="loader">
    <div class="loader-spinner"></div>
</div>

<!-- Breadcrumb moderne -->
<section class="breadcrumb-modern">
    <div class="breadcrumb-content">
        <h1>Recherche</h1>
        <div class="breadcrumb-links">
            <a href="index.php"><i class="fa-solid fa-house"></i> Accueil</a>
            <i class="fa-solid fa-chevron-right"></i>
            <span>Recherche</span>
        </div>
    </div>
</section>

<div class="search-container">
    
    <!-- Header des résultats -->
    <div class="search-header">
        <div class="search-info">
            <h2>Résultats pour</h2>
            <span class="search-query">
                <i class="fa-solid fa-magnifying-glass"></i>
                <?php echo htmlspecialchars($searchQuery); ?>
            </span>
        </div>
        <div class="results-count">
            <i class="fa-regular fa-file-lines"></i>
            <span><strong><?php echo $totalProduits; ?></strong> produit<?php echo $totalProduits > 1 ? 's' : ''; ?> trouvé<?php echo $totalProduits > 1 ? 's' : ''; ?></span>
        </div>
    </div>

    <!-- Grille de produits -->
    <?php if (!empty($produits)): ?>
        <div class="products-grid">
            <?php foreach ($produits as $produit): 
                $imagePath = 'back-end/apps/' . htmlspecialchars($produit['image']);
                if (!file_exists($imagePath)) {
                    $imagePath = 'back-end/apps/Blue.jpg';
                }
                $hasSale = $produit['prix_reduction'] > 0;
                $isFree = $produit['prix'] == 0;
                $discount = $hasSale ? round((($produit['prix'] - $produit['prix_reduction']) / $produit['prix']) * 100) : 0;
            ?>
                <div class="product-card">
                    <div class="product-image-wrapper">
                        <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($produit['nom_article']); ?>" loading="lazy">
                        
                        <div class="product-badges">
                            <?php if ($isFree): ?>
                                <span class="badge badge-free">Gratuit</span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($hasSale): ?>
                            <span class="discount-badge">-<?php echo $discount; ?>%</span>
                        <?php endif; ?>
                        
                        <div class="product-overlay">
                            <a href="product_detail.php?nom_article=<?php echo urlencode($produit['nom_article']); ?>" class="btn-quickview">
                                <i class="fa-solid fa-eye"></i> Voir détail
                            </a>
                        </div>
                    </div>
                    
                    <div class="product-info">
                        <span class="product-category"><?php echo htmlspecialchars($produit['nom_categorie']); ?></span>
                        <a href="product_detail.php?nom_article=<?php echo urlencode($produit['nom_article']); ?>" class="product-title">
                            <?php echo htmlspecialchars($produit['nom_article']); ?>
                        </a>
                        <div class="product-author">
                            par <a href="#"><?php echo htmlspecialchars($produit['auteur']); ?></a>
                        </div>
                        
                        <div class="product-price-wrapper">
                            <div class="price-box">
                                <?php if ($isFree): ?>
                                    <span class="price-free">Gratuit</span>
                                <?php elseif ($hasSale): ?>
                                    <span class="old-price"><?php echo number_format($produit['prix'], 0); ?> CFA</span>
                                    <span class="current-price"><?php echo number_format($produit['prix_reduction'], 0); ?> CFA</span>
                                <?php else: ?>
                                    <span class="current-price"><?php echo number_format($produit['prix'], 0); ?> CFA</span>
                                <?php endif; ?>
                            </div>
                            <a href="product_detail.php?nom_article=<?php echo urlencode($produit['nom_article']); ?>" class="btn-detail">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination moderne -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination-modern">
                <a href="?search_query=<?php echo urlencode($searchQuery); ?>&page=<?php echo $pageActuelle - 1; ?>" 
                   class="page-btn <?php echo $pageActuelle == 1 ? 'disabled' : ''; ?>">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
                
                <?php 
                // Afficher la première page si nécessaire
                if ($pageActuelle > 3): ?>
                    <a href="?search_query=<?php echo urlencode($searchQuery); ?>&page=1" class="page-btn">1</a>
                    <?php if ($pageActuelle > 4): ?>
                        <span class="page-btn disabled">...</span>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php 
                $startPage = max(1, $pageActuelle - 2);
                $endPage = min($totalPages, $pageActuelle + 2);
                
                for ($i = $startPage; $i <= $endPage; $i++): 
                ?>
                    <a href="?search_query=<?php echo urlencode($searchQuery); ?>&page=<?php echo $i; ?>" 
                       class="page-btn <?php echo $pageActuelle == $i ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                
                <?php 
                // Afficher la dernière page si nécessaire
                if ($pageActuelle < $totalPages - 2): ?>
                    <?php if ($pageActuelle < $totalPages - 3): ?>
                        <span class="page-btn disabled">...</span>
                    <?php endif; ?>
                    <a href="?search_query=<?php echo urlencode($searchQuery); ?>&page=<?php echo $totalPages; ?>" class="page-btn"><?php echo $totalPages; ?></a>
                <?php endif; ?>
                
                <a href="?search_query=<?php echo urlencode($searchQuery); ?>&page=<?php echo $pageActuelle + 1; ?>" 
                   class="page-btn <?php echo $pageActuelle == $totalPages ? 'disabled' : ''; ?>">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <!-- État vide -->
        <div class="empty-state">
            <i class="fa-regular fa-face-frown"></i>
            <h3>Aucun résultat trouvé</h3>
            <p>Désolé, nous n'avons trouvé aucun produit correspondant à "<?php echo htmlspecialchars($searchQuery); ?>"</p>
            <a href="shop.php" class="btn-primary">
                <i class="fa-regular fa-store"></i> Voir tous les produits
            </a>
        </div>
    <?php endif; ?>
</div>

<?php require('popup.php'); ?>
<?php require('liste_cat.php'); ?>
<?php require('banniere.php'); ?>
<?php require('footer.php'); ?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Afficher le loader sur les clics de pagination
document.querySelectorAll('.page-btn:not(.disabled)').forEach(btn => {
    btn.addEventListener('click', function(e) {
        if (!this.classList.contains('disabled')) {
            document.getElementById('loader').style.display = 'flex';
        }
    });
});
</script>

</body>
</html>