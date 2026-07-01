<?php
// Démarrer la session (si ce n'est pas déjà fait)
require('header.php');

// Connexion à la base de données
require('include/connect.php'); 

// Vérifier si le panier est vide
if (empty($_SESSION['panier']) || !isset($_SESSION['panier'])) {
    echo '
    <div class="cart-empty-container" style="max-width: 1400px; margin: 0 auto; padding: 40px 20px 80px;">
        <div class="empty-state" style="text-align: center; padding: 80px 20px; background: white; border-radius: 20px; border: 1px solid var(--border); box-shadow: 0 10px 30px -10px rgba(0,0,0,0.1);">
            <i class="fa-regular fa-cart-shopping" style="font-size: 80px; color: #e5e7eb; margin-bottom: 20px;"></i>
            <h3 style="font-size: 24px; font-weight: 700; color: #0f1923; margin-bottom: 10px;">Votre panier est vide</h3>
            <p style="color: #6b7280; margin-bottom: 25px;">Découvrez nos produits et ajoutez-les à votre panier !</p>
            <a href="shop.php" class="btn-primary" style="background: #087d67; color: white; padding: 12px 30px; border-radius: 40px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s;">
                <i class="fa-regular fa-store"></i> Découvrir la boutique
            </a>
        </div>
    </div>';
    require('footer.php');
    exit;
}

// Récupérer les identifiants des produits dans le panier
$ids = array_keys($_SESSION['panier']);

// Si aucun produit dans le panier
if (empty($ids)) {
    echo '
    <div class="cart-empty-container" style="max-width: 1400px; margin: 0 auto; padding: 40px 20px 80px;">
        <div class="empty-state" style="text-align: center; padding: 80px 20px; background: white; border-radius: 20px; border: 1px solid var(--border); box-shadow: 0 10px 30px -10px rgba(0,0,0,0.1);">
            <i class="fa-regular fa-cart-shopping" style="font-size: 80px; color: #e5e7eb; margin-bottom: 20px;"></i>
            <h3 style="font-size: 24px; font-weight: 700; color: #0f1923; margin-bottom: 10px;">Votre panier est vide</h3>
            <p style="color: #6b7280; margin-bottom: 25px;">Découvrez nos produits et ajoutez-les à votre panier !</p>
            <a href="shop.php" class="btn-primary" style="background: #087d67; color: white; padding: 12px 30px; border-radius: 40px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s;">
                <i class="fa-regular fa-store"></i> Découvrir la boutique
            </a>
        </div>
    </div>';
    require('footer.php');
    exit;
}

// Récupérer les informations des produits depuis la base de données
$query = "SELECT * FROM produits WHERE id IN (" . implode(',', $ids) . ")";
$stmt = $database->prepare($query);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialiser le total du panier
$total = 0; 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/images/favi.png" type="image/x-icon">
    <title>Mon Panier - NDIGITMARKET</title>
    
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
            --warning: #f59e0b;
            --shadow: 0 10px 30px -10px rgba(0,0,0,0.1);
            --shadow-hover: 0 20px 40px -15px rgba(8,125,103,0.3);
            --radius: 20px;
            --radius-sm: 12px;
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

        /* ===== CONTENU PRINCIPAL ===== */
        .cart-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px 60px;
        }

        .cart-header {
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

        .cart-header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .cart-icon {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 5px 15px rgba(8,125,103,0.3);
        }

        .cart-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
        }

        .items-count {
            background: var(--primary-light);
            color: var(--primary);
            padding: 8px 20px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .items-count i {
            font-size: 16px;
        }

        .items-count strong {
            font-size: 18px;
            margin: 0 2px;
        }

        /* ===== TABLEAU DU PANIER ===== */
        .cart-table {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table thead th {
            background: var(--bg);
            padding: 15px 20px;
            font-weight: 600;
            color: var(--dark);
            border-bottom: 2px solid var(--border);
            font-size: 14px;
        }

        .table tbody td {
            padding: 20px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr:hover {
            background: var(--bg);
        }

        /* ===== PRODUIT DANS LE PANIER ===== */
        .product-cart {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .product-cart-image {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--border);
            background: var(--bg);
            flex-shrink: 0;
        }

        .product-cart-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-cart-info h4 {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .product-cart-info h4 a {
            color: var(--dark);
            text-decoration: none;
            transition: color 0.3s;
        }

        .product-cart-info h4 a:hover {
            color: var(--primary);
        }

        .product-cart-price {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .current-price {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
        }

        .old-price {
            font-size: 14px;
            color: var(--text-light);
            text-decoration: line-through;
        }

        /* ===== TOTAL LIGNE ===== */
        .line-total {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
        }

        /* ===== ACTION ===== */
        .action-buttons {
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: center;
        }

        .btn-remove {
            color: var(--danger);
            background: #fee2e2;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 18px;
        }

        .btn-remove:hover {
            background: var(--danger);
            color: white;
            transform: scale(1.1);
        }

        /* ===== RÉCAPITULATIF ===== */
        .summary-box {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            padding: 25px;
            position: sticky;
            top: 100px;
        }

        .summary-header {
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 15px;
        }

        .summary-header h3 {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark);
        }

        .summary-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px dashed var(--border);
        }

        .summary-item h4 {
            font-size: 15px;
            font-weight: 500;
            color: var(--text);
        }

        .summary-item .price {
            font-weight: 600;
            color: var(--dark);
        }

        .summary-total {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 0;
        }

        .summary-total h4 {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark);
        }

        .summary-total .total-price {
            font-size: 24px;
            font-weight: 800;
            color: var(--primary);
        }

        .action-buttons-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 20px;
        }

        .btn-checkout {
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
            color: white;
            padding: 15px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 16px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(249,115,22,0.3);
        }

        .btn-continue {
            background: var(--bg);
            color: var(--text);
            padding: 12px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s;
            border: 1px solid var(--border);
        }

        .btn-continue:hover {
            background: var(--border);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 992px) {
            .table thead {
                display: none;
            }
            
            .table tbody td {
                display: block;
                padding: 15px;
                text-align: center;
            }
            
            .table tbody tr {
                display: block;
                margin-bottom: 20px;
                border: 1px solid var(--border);
                border-radius: var(--radius-sm);
            }
            
            .product-cart {
                flex-direction: column;
                text-align: center;
            }
            
            .action-buttons {
                justify-content: center;
            }
            
            .line-total {
                text-align: center;
                display: block;
            }
            
            td:before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--dark);
                display: block;
                margin-bottom: 5px;
            }
        }

        /* ===== PROMOTION ===== */
        .promotion-section {
            margin: 40px 0;
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
        <h1>Mon Panier</h1>
        <div class="breadcrumb-links">
            <a href="index.php"><i class="fa-solid fa-house"></i> Accueil</a>
            <i class="fa-solid fa-chevron-right"></i>
            <span>Panier</span>
        </div>
    </div>
</section>

<div class="cart-container">
    
    <!-- Header du panier -->
    <div class="cart-header">
        <div class="cart-header-left">
            <div class="cart-icon">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <h2>Mon Panier</h2>
        </div>
        <div class="items-count">
            <i class="fa-regular fa-file-lines"></i>
            <span><strong><?php echo count($products); ?></strong> article<?php echo count($products) > 1 ? 's' : ''; ?></span>
        </div>
    </div>

    <div class="row g-5">
        <!-- Liste des produits -->
        <div class="col-xxl-8">
            <div class="cart-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                         
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($products as $product) {
                            $productId = $product['id'];
                            $quantity = $_SESSION['panier'][$productId];

                            if (isset($product['prix_reduction']) && $product['prix_reduction'] > 0) {
                                $priceAfterDiscount = $product['prix_reduction'];
                            } else {
                                $priceAfterDiscount = $product['prix'];
                            }

                            $totalProductPrice = $priceAfterDiscount * $quantity;
                            $total += $totalProductPrice;

                            $imagePath = 'back-end/apps/' . htmlspecialchars($product['image']);
                            if (!file_exists($imagePath)) {
                                $imagePath = 'back-end/apps/Blue.jpg';
                            }
                        ?>
                        <tr>
                            <td data-label="Produit">
                                <div class="product-cart">
                                    <div class="product-cart-image">
                                        <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($product['nom_article']); ?>">
                                    </div>
                                    <div class="product-cart-info">
                                        <h4>
                                            <a href="product_detail.php?nom_article=<?php echo urlencode($product['nom_article']); ?>">
                                                <?php echo htmlspecialchars($product['nom_article']); ?>
                                            </a>
                                        </h4>
                                        <div class="product-cart-price">
                                            <span class="current-price"><?php echo number_format($priceAfterDiscount, 0, ',', ' '); ?> CFA</span>
                                            <?php if ($priceAfterDiscount < $product['prix']): ?>
                                                <span class="old-price"><?php echo number_format($product['prix'], 0, ',', ' '); ?> CFA</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                           
                            <td data-label="Action">
                                <div class="action-buttons">
                                    <a href="add_product?del=<?php echo $product['id']; ?>" class="btn-remove" title="Supprimer">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Récapitulatif -->
        <div class="col-xxl-4">
            <div class="summary-box">
                <div class="summary-header">
                    <h3>Récapitulatif</h3>
                </div>

                <?php foreach ($products as $product): 
                    $productId = $product['id'];
                    $quantity = $_SESSION['panier'][$productId];
                    
                    if (isset($product['prix_reduction']) && $product['prix_reduction'] > 0) {
                        $priceAfterDiscount = $product['prix_reduction'];
                    } else {
                        $priceAfterDiscount = $product['prix'];
                    }
                    
                    $totalProductPrice = $priceAfterDiscount * $quantity;
                ?>
                <div class="summary-item">
                    <h4><?php echo htmlspecialchars(mb_strimwidth($product['nom_article'], 0, 30, '...')); ?> (x<?php echo $quantity; ?>)</h4>
                    <span class="price"><?php echo number_format($totalProductPrice, 0, ',', ' '); ?> CFA</span>
                </div>
                <?php endforeach; ?>

                <div class="summary-total">
                    <h4>Total</h4>
                    <span class="total-price"><?php echo number_format($total, 0, ',', ' '); ?> CFA</span>
                </div>

                <div class="action-buttons-group">
                    <a href="checkout?verifie=nonconnect" class="btn-checkout">
                        <i class="fa-regular fa-credit-card"></i> Passer à la commande
                    </a>
                    <a href="shop.php" class="btn-continue">
                        <i class="fa-regular fa-store"></i> Continuer les achats
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Promotion -->
    <div class="promotion-section">
        <?php require('promotion.php'); ?>
    </div>
</div>

<?php require('footer.php'); ?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Afficher le loader sur les clics des boutons
document.querySelectorAll('.btn-remove, .btn-checkout, .btn-continue').forEach(btn => {
    btn.addEventListener('click', function(e) {
        document.getElementById('loader').style.display = 'flex';
    });
});
</script>

</body>
</html>