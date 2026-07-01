<?php
require('header.php');
require('include/connect.php');
if (!isset($_SESSION)) {
    session_start();
}
$cfa = "CFA";

if (!isset($_GET['nom_article'])) {
    echo '<meta http-equiv="refresh" content="0;URL=index">';
    exit;
}

$nom_article = htmlspecialchars($_GET['nom_article']);

// Récupérer le produit + nom de la boutique du vendeur
$resultats = $database->prepare('
    SELECT p.*, c.nom_categorie, dv.nom_boutique AS vendeur_boutique
    FROM produits p
    INNER JOIN categories c ON p.categorie_id = c.id
    LEFT JOIN demandes_vendeur dv ON p.id_vendeur = dv.id_uti AND dv.statut = "acceptee"
    WHERE p.nom_article = :nom_article
');
$resultats->execute([':nom_article' => $nom_article]);
$produit = $resultats->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
   echo '<meta http-equiv="refresh" content="0;URL=index">';
    exit;
}

// Vérifier si le produit est approuvé
if ($produit['statut'] !== 'approuve') {
    // Produit non approuvé → page "non disponible"
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="assets/images/favi.png" type="image/x-icon">
        <title>Produit non disponible - NDIGITMARKET</title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <style>
          
            .unavailable-card {
                background: white;
                border-radius: 24px;
                box-shadow: 0 20px 50px rgba(0,0,0,0.1);
                max-width: 550px;
                width: 100%;
                padding: 50px 40px;
                text-align: center;
            }
            .unavailable-icon {
                font-size: 70px;
                color: #f59e0b;
                margin-bottom: 25px;
            }
            .unavailable-card h1 {
                font-size: 24px;
                font-weight: 700;
                color: #1a2634;
                margin-bottom: 15px;
            }
            .unavailable-card p {
                color: #6b7280;
                line-height: 1.7;
                margin-bottom: 30px;
                font-size: 15px;
            }
            .btn-back {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: #087d67;
                color: white;
                padding: 14px 30px;
                border-radius: 12px;
                text-decoration: none;
                font-weight: 600;
                transition: 0.3s;
            }
            .btn-back:hover {
                background: #065a4a;
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(8,125,103,0.3);
                color: white;
            }
        </style>
    </head>
    <body>
        <center>
        <div class="unavailable-card">
            <div class="unavailable-icon">
                <i class="fa-solid fa-circle-exclamation"></i>
            </div>
            <h1>Ce produit n'est pas disponible pour le moment</h1>
            <p>Le produit <strong>"<?php echo htmlspecialchars($produit['nom_article']); ?>"</strong> est en cours de validation ou a été retiré. Veuillez réessayer plus tard ou explorer d'autres produits.</p>
            <a href="index.php" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i> Retour à l'accueil
            </a>
            <a href="shop.php" style="display: inline-flex; align-items: center; gap: 8px; color: #087d67; text-decoration: none; font-weight: 600; margin-top: 20px;">
                <i class="fa-solid fa-store"></i> Voir la boutique
            </a>
        </div>
</center>
    </body>
    </html>
    <?php
    require('footer.php');
    exit;
}

// Vérifier si l'utilisateur est le propriétaire (vendeur du produit)
$is_owner = false;
if (isset($_SESSION['user_id'], $_SESSION['email'])) {
    $stmtUser = $database->prepare("SELECT id_uti FROM utilisateur WHERE email = ?");
    $stmtUser->execute([$_SESSION['email']]);
    $user = $stmtUser->fetch();
    if ($user && $user['id_uti'] == $produit['id_vendeur']) {
        $is_owner = true;
    }
}

// Calcul des téléchargements
$stmt_free_sub = $database->prepare("SELECT COUNT(*) as cnt FROM telechargements WHERE id_produit = :id");
$stmt_free_sub->execute([':id' => $produit['id']]);
$count_free_sub = $stmt_free_sub->fetch(PDO::FETCH_ASSOC)['cnt'];

$stmt_paid = $database->prepare("SELECT COUNT(*) as cnt FROM commande WHERE id_article = :id");
$stmt_paid->execute([':id' => $produit['id']]);
$count_paid = $stmt_paid->fetch(PDO::FETCH_ASSOC)['cnt'];

$total_downloads = $count_free_sub + $count_paid;
$prix_affiche = $produit['prix_reduction'] > 0 ? $produit['prix_reduction'] : $produit['prix'];
$has_promo = $produit['prix_reduction'] > 0;
$pourcentage_reduction = $has_promo ? round((($produit['prix'] - $produit['prix_reduction']) / $produit['prix']) * 100) : 0;

// Déterminer si l'utilisateur connecté a un abonnement actif ET est sur un produit de l'admin (id_vendeur = 1)
$is_admin_product = ($produit['id_vendeur'] == 1);
$can_download_pro = false; // téléchargement direct même payant
if (!$is_owner && isset($_SESSION['user_id'])) {
    $email = $_SESSION['email'];
    $stmtU = $database->prepare("SELECT id_uti, type FROM utilisateur WHERE email = ?");
    $stmtU->execute([$email]);
    $u = $stmtU->fetch();
    if ($u && $u['type'] === 'pro') {
        $abo = $database->prepare("SELECT date_fin FROM abonnement WHERE id_uti2 = ?");
        $abo->execute([$u['id_uti']]);
        $a = $abo->fetch();
        if ($a && new DateTime($a['date_fin']) > new DateTime()) {
            // Abonnement actif
            if ($is_admin_product) {
                $can_download_pro = true; // produit de l'admin → téléchargement direct
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/images/favi.png" type="image/x-icon">
    <title>NDIGITMARKET - <?php echo htmlspecialchars($produit['nom_categorie']); ?></title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
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

        /* ===== LOADER ===== */
        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
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

        /* ===== BREADCRUMB ===== */
        .breadcrumb-modern {
            background: linear-gradient(135deg, var(--dark) 0%, var(--dark-2) 100%);
            padding: 30px 0;
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
        }

        .breadcrumb-modern::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(8,125,103,0.2) 0%, transparent 70%);
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
            font-size: 28px;
            font-weight: 700;
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

        /* ===== PAGE PRODUIT ===== */
        .product-page {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px 60px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 30px;
            margin-bottom: 50px;
        }

        @media (max-width: 992px) {
            .product-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ===== COLONNE GAUCHE ===== */
        .product-left {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .product-image-container {
            position: relative;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e7eb 100%);
            padding: 20px;
            border-bottom: 1px solid var(--border);
        }

        .product-image {
            position: relative;
            aspect-ratio: 16/9;
            overflow: hidden;
            border-radius: var(--radius-sm);
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: transform 0.3s;
        }

        .product-image:hover img {
            transform: scale(1.05);
        }

        .product-badges {
            position: absolute;
            top: 20px;
            left: 20px;
            display: flex;
            gap: 10px;
            z-index: 2;
            flex-wrap: wrap;
        }

        .badge {
            padding: 6px 15px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-free {
            background: var(--success);
            color: white;
        }

        .badge-new {
            background: var(--primary);
            color: white;
        }

        .badge-sale {
            background: var(--accent);
            color: white;
        }

        .zoom-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 44px;
            height: 44px;
            background: white;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark);
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            z-index: 2;
        }

        .zoom-btn:hover {
            background: var(--primary);
            color: white;
            transform: scale(1.1);
        }

        /* ===== TABS ===== */
        .product-tabs {
            padding: 25px;
        }

        .tabs-header {
            display: flex;
            gap: 5px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 12px 25px;
            background: transparent;
            border: none;
            font-weight: 600;
            font-size: 15px;
            color: var(--text-light);
            cursor: pointer;
            position: relative;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .tab-btn::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 2px;
            background: var(--primary);
            transform: scaleX(0);
            transition: transform 0.3s;
        }

        .tab-btn:hover {
            color: var(--primary);
        }

        .tab-btn.active {
            color: var(--primary);
        }

        .tab-btn.active::after {
            transform: scaleX(1);
        }

        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }

        .tab-content {
            line-height: 1.8;
            color: var(--text);
            font-size: 15px;
        }

        .tab-content p {
            margin-bottom: 15px;
        }

        .tab-content ul {
            list-style: none;
            padding-left: 0;
        }

        .tab-content li {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .tab-content li i {
            color: var(--success);
            font-size: 16px;
            margin-top: 3px;
            flex-shrink: 0;
        }

        .tab-content strong {
            color: var(--dark);
        }

        .information-box {
            padding: 0 5px;
        }

        /* ===== COLONNE DROITE ===== */
        .product-right {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            padding: 25px;
            position: sticky;
            top: 100px;
            height: fit-content;
        }

        .product-header {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border);
        }

        .product-header h2 {
            font-size: 22px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .product-meta {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .product-category {
            background: var(--primary-light);
            color: var(--primary);
            padding: 6px 15px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .product-category:hover {
            background: var(--primary);
            color: white;
        }

        .product-downloads {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-light);
            font-size: 14px;
        }

        .product-downloads i {
            color: var(--accent);
        }

        .vendor-name {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text);
            font-size: 14px;
            font-weight: 500;
        }

        .vendor-name i {
            color: var(--primary);
        }

        /* ===== PRIX ===== */
        .price-section {
            background: linear-gradient(135deg, var(--bg) 0%, white 100%);
            border-radius: var(--radius-sm);
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid var(--border);
        }

        .price-box {
            display: flex;
            align-items: baseline;
            flex-wrap: wrap;
            gap: 10px;
        }

        .current-price {
            font-size: 32px;
            font-weight: 800;
            color: var(--primary);
            line-height: 1.2;
        }

        .old-price {
            font-size: 18px;
            color: var(--text-light);
            text-decoration: line-through;
        }

        .discount-badge {
            background: var(--danger);
            color: white;
            padding: 5px 12px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
        }

        .price-free {
            font-size: 32px;
            font-weight: 800;
            color: var(--success);
        }

        .price-premium {
            font-size: 32px;
            font-weight: 800;
            color: var(--accent);
        }

        /* ===== BOUTONS ===== */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 25px;
        }

        .btn-action {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(8,125,103,0.3);
        }

        .btn-accent {
            background: var(--accent);
            color: white;
        }

        .btn-accent:hover {
            background: var(--accent-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(249,115,22,0.3);
        }

        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary-light);
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-success:hover {
            background: #0e9f6e;
            transform: translateY(-2px);
        }

        .owner-alert {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 12px;
            padding: 20px;
            color: #856404;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .owner-alert i {
            font-size: 24px;
            color: #f59e0b;
        }

        /* ===== LICENCE GPL ===== */
        .license-box {
            background: linear-gradient(135deg, var(--primary-light) 0%, white 100%);
            border-radius: var(--radius-sm);
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid var(--primary);
        }

        .license-title {
            background: var(--primary);
            color: white;
            padding: 12px;
            border-radius: var(--radius-sm);
            font-weight: 700;
            font-size: 18px;
            text-align: center;
            margin-bottom: 15px;
        }

        .license-list {
            list-style: none;
            padding: 0;
        }

        .license-list li {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
            font-size: 14px;
            border-bottom: 1px dashed rgba(8,125,103,0.2);
        }

        .license-list li:last-child {
            border-bottom: none;
        }

        .license-list i {
            color: var(--success);
            font-size: 16px;
        }

        /* ===== PARTAGE ===== */
        .share-section {
            margin: 20px 0;
        }

        .share-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 15px;
        }

        .share-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .share-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .share-btn:hover {
            transform: translateY(-3px);
        }

        .share-whatsapp { background: #25D366; }
        .share-twitter { background: #1DA1F2; }
        .share-facebook { background: #4267B2; }
        .share-copy { background: var(--text-light); }

        /* ===== INFORMATIONS ===== */
        .info-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        .info-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 15px;
        }

        .info-list {
            list-style: none;
            padding: 0;
        }

        .info-list li {
            padding: 8px 0;
            font-size: 14px;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-list li a {
            color: var(--primary);
            text-decoration: none;
        }

        .info-list li a:hover {
            text-decoration: underline;
        }

        /* ===== PRODUITS SIMILAIRES ===== */
        .similar-products {
            margin-top: 50px;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .section-header h2 {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark);
        }

        .section-header a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: gap 0.3s;
        }

        .section-header a:hover {
            gap: 12px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        @media (max-width: 1200px) {
            .products-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: 1fr;
            }
        }

        .product-card {
            background: white;
            border-radius: var(--radius);
            overflow: hidden;
            border: 1px solid var(--border);
            transition: all 0.3s;
        }

        .product-card:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-hover);
            transform: translateY(-5px);
        }

        .card-image {
            position: relative;
            aspect-ratio: 1/1;
            overflow: hidden;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .product-card:hover .card-image img {
            transform: scale(1.08);
        }

        .card-body {
            padding: 15px;
        }

        .card-category {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .card-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--dark);
            text-decoration: none;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .card-title:hover {
            color: var(--primary);
        }

        .card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-price {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
        }

        .card-price.free {
            color: var(--success);
        }

        .card-price.premium {
            color: var(--accent);
        }

        .card-btn {
            width: 36px;
            height: 36px;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s;
        }

        .card-btn:hover {
            background: var(--primary);
            color: white;
            transform: rotate(360deg);
        }

        /* ===== STICKY BOTTOM BAR ===== */
        .sticky-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.1);
            padding: 15px 0;
            z-index: 1000;
            transform: translateY(100%);
            transition: transform 0.3s;
            border-top: 1px solid var(--border);
        }

        .sticky-bar.visible {
            transform: translateY(0);
        }

        .sticky-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .sticky-info {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
            min-width: 250px;
        }

        .sticky-info img {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            object-fit: cover;
        }

        .sticky-info h4 {
            font-size: 16px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .sticky-price {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
        }

        .sticky-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .sticky-btn {
            padding: 12px 25px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .sticky-btn-primary {
            background: var(--primary);
            color: white;
        }

        .sticky-btn-primary:hover {
            background: var(--primary-dark);
        }

        .sticky-btn-accent {
            background: var(--accent);
            color: white;
        }

        .sticky-btn-accent:hover {
            background: var(--accent-dark);
        }

        .sticky-btn-success {
            background: var(--success);
            color: white;
        }

        .sticky-btn-success:hover {
            background: #0e9f6e;
        }

        /* ===== MODAL ===== */
        .modal-modern .modal-content {
            border-radius: 20px;
            border: none;
            padding: 20px;
        }

        .modal-modern .modal-header {
            border-bottom: 1px solid var(--border);
            padding: 0 0 20px 0;
        }

        .modal-modern .modal-title {
            font-weight: 700;
            color: var(--dark);
        }

        .modal-modern .modal-body {
            padding: 20px 0;
        }

        .modal-modern .btn-close {
            background: var(--bg);
            opacity: 1;
            padding: 8px;
            border-radius: 50%;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            font-size: 14px;
            font-weight: 500;
            color: var(--dark);
            margin-bottom: 5px;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(8,125,103,0.1);
        }

        .text-theme {
            color: var(--primary);
            text-decoration: none;
        }

        .text-theme:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Offre abonnement */
        .discount-box {
            background: linear-gradient(135deg, #fff7ed 0%, #ffffff 100%);
            border-radius: 16px;
            padding: 25px 20px;
            margin-top: 20px;
            border: 1px solid var(--accent);
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(249, 115, 22, 0.2);
            transition: all 0.3s ease;
        }

        .discount-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px -5px rgba(249, 115, 22, 0.3);
        }

        .discount-box::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(249, 115, 22, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            z-index: 0;
        }

        .discount-box h4 {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 12px;
            position: relative;
            z-index: 1;
        }

        .discount-box h4 span {
            color: var(--accent);
            position: relative;
            display: inline-block;
        }

        .discount-box p {
            font-size: 14px;
            color: var(--text-light);
            margin-bottom: 20px;
            line-height: 1.6;
            position: relative;
            z-index: 1;
        }

        .discount-btn {
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
            color: white;
            padding: 14px 25px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 5px 15px rgba(249, 115, 22, 0.3);
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        .discount-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(249, 115, 22, 0.4);
            color: white;
        }
    </style>
</head>
<body>

<!-- Loader -->
<div class="loader-overlay" id="loader"><div class="loader-spinner"></div></div>

<!-- Breadcrumb -->
<section class="breadcrumb-modern">
    <div class="breadcrumb-content">
        <h1>Détail du produit</h1>
        <div class="breadcrumb-links">
            <a href="index.php"><i class="fa-solid fa-house"></i> Accueil</a>
            <i class="fa-solid fa-chevron-right"></i>
            <a href="shop.php">Boutique</a>
            <i class="fa-solid fa-chevron-right"></i>
            <a href="categorie?category_name=<?php echo urlencode($produit['nom_categorie']); ?>"><?php echo htmlspecialchars($produit['nom_categorie']); ?></a>
            <i class="fa-solid fa-chevron-right"></i>
            <span>Détail</span>
        </div>
    </div>
</section>

<!-- Contenu principal -->
<div class="product-page">
    <div class="product-grid">
        
        <!-- Colonne gauche (image + tabs) -->
        <div class="product-left">
            <div class="product-image-container">
                <div class="product-image">
                    <img src="back-end/apps/<?php echo htmlspecialchars($produit['image']); ?>" alt="<?php echo htmlspecialchars($produit['nom_article']); ?>">
                    <div class="product-badges">
                        <?php if (time() - strtotime($produit['date_ajout']) < 7 * 24 * 60 * 60): ?>
                            <span class="badge badge-new">Nouveau</span>
                        <?php endif; ?>
                        <?php if ($produit['prix'] == 0): ?>
                            <span class="badge badge-free">Gratuit</span>
                        <?php elseif ($has_promo): ?>
                            <span class="badge badge-sale">Promo</span>
                        <?php endif; ?>
                    </div>
                    <button class="zoom-btn" onclick="openFullscreen(this)"><i class="fa-solid fa-expand"></i></button>
                </div>
            </div>
            
            <div class="product-tabs">
                <div class="tabs-header">
                    <button class="tab-btn active" onclick="showTab('description')">Description</button>
                    <button class="tab-btn" onclick="showTab('installation')">Installation & Contenu</button>
                </div>
                <div class="tab-pane active" id="tab-description">
                    <div class="tab-content"><?php echo html_entity_decode($produit['description']); ?></div>
                </div>
                <div class="tab-pane" id="tab-installation">
                    <div class="tab-content information-box">
                        <p><i class="fa-solid fa-circle-info" style="color:var(--primary)"></i> Les fichiers sont au format ZIP. Décompressez après téléchargement.</p>
                        <ul>
                            <li><i class="fa-solid fa-check-circle"></i> Compatible WordPress (Shopify si mentionné). Documentation incluse.</li>
                            <li><i class="fa-solid fa-check-circle"></i> Installation WordPress : Apparence > Thèmes > Ajouter.</li>
                            <li><i class="fa-solid fa-exclamation-triangle" style="color:var(--warning)"></i> Images de démo non incluses.</li>
                            <li><i class="fa-solid fa-gavel"></i> Licence GPL.</li>
                            <li><i class="fa-solid fa-ban" style="color:var(--danger)"></i> Fichiers ni repris ni remboursés.</li>
                            <li><i class="fa-solid fa-headset"></i> Besoin d'aide ? Contact WhatsApp.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Colonne droite -->
        <div class="product-right">
            <div class="product-header">
                <h2><?php echo htmlspecialchars($produit['nom_article']); ?></h2>
                <div class="product-meta">
                    <a href="categorie?category_name=<?php echo urlencode($produit['nom_categorie']); ?>" class="product-category">
                        <i class="fa-regular fa-folder"></i> <?php echo htmlspecialchars($produit['nom_categorie']); ?>
                    </a>
                    <div class="product-downloads">
                        <i class="fa-solid fa-download"></i> <?php echo $total_downloads; ?> Téléchargements
                    </div>
                    <?php if (!empty($produit['vendeur_boutique'])): ?>
                    <div class="vendor-name">
                        <i class="fa-solid fa-store"></i> Vendu par <strong><?php echo htmlspecialchars($produit['vendeur_boutique']); ?></strong>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Prix -->
            <div class="price-section">
                <div class="price-box">
                    <?php if ($produit['nom_categorie'] === 'PSD'): ?>
                        <?php if ($produit['prix'] == 0): ?>
                            <span class="price-free">Gratuit</span>
                        <?php else: ?>
                            <span class="price-premium">Premium</span>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if ($has_promo): ?>
                            <span class="current-price"><?php echo number_format($prix_affiche, 0); ?> <?php echo $cfa; ?></span>
                            <span class="old-price"><?php echo number_format($produit['prix'], 0); ?> <?php echo $cfa; ?></span>
                            <span class="discount-badge">-<?php echo $pourcentage_reduction; ?>%</span>
                        <?php else: ?>
                            <span class="current-price"><?php echo number_format($produit['prix'], 0); ?> <?php echo $cfa; ?></span>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Licence -->
            <div class="license-box">
                <div class="license-title">Licence GPL</div>
                <ul class="license-list">
                    <li><i class="fa-solid fa-check-circle"></i> Thème sous licence GPL</li>
                    <li><i class="fa-solid fa-check-circle"></i> Basé sur un thème premium</li>
                    <li><i class="fa-solid fa-check-circle"></i> Installation et assistance incluses</li>
                    <li><i class="fa-solid fa-check-circle"></i> Vérifié VirusTotal</li>
                    <li><i class="fa-solid fa-check-circle"></i> Utilisation illimitée</li>
                </ul>
            </div>
            
            <!-- Actions -->
            <form action="add_product.php" method="POST" class="action-form">
                <input type="hidden" name="nom" value="<?php echo htmlspecialchars($produit['nom_article']); ?>">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($produit['id']); ?>">
                <input type="hidden" name="nombre" value="1">
                <div class="action-buttons">
                    <?php if ($is_owner): ?>
                        <div class="owner-alert">
                            <i class="fa-solid fa-circle-info"></i> Ce produit vous appartient. Vous ne pouvez pas l'acheter ni le télécharger ici.
                        </div>
                    <?php else: ?>
                        <?php
                        // Produits PSD
                        if ($produit['nom_categorie'] === 'PSD') {
                            if ($produit['prix'] == 0) {
                                if (!isset($_SESSION['user_id'])) {
                                    echo '<button type="button" class="btn-action btn-success" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="fa-solid fa-download"></i> Télécharger gratuitement</button>';
                                } else {
                                    echo '<button type="submit" name="telecharge_gratuit" class="btn-action btn-success download-btn"><i class="fa-solid fa-download"></i> Télécharger gratuitement</button>';
                                }
                            } else {
                                if ($can_download_pro) {
                                    echo '<button type="submit" name="telecharge_pro" class="btn-action btn-success download-btn"><i class="fa-solid fa-download"></i> Téléchargement Direct</button>';
                                } else {
                                    if (!isset($_SESSION['user_id'])) {
                                        echo '<a href="abonnement" class="btn-action btn-accent"><i class="fa-solid fa-crown"></i> Téléchargement Premium</a>';
                                    } else {
                                        $email = $_SESSION['email'];
                                        $stmt = $database->prepare("SELECT * FROM utilisateur WHERE email = :email");
                                        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                                        $stmt->execute();
                                        $donnee1 = $stmt->fetch(PDO::FETCH_ASSOC);
                                        if ($donnee1) {
                                            if ($donnee1['type'] === 'pro') {
                                                $stmtAbo = $database->prepare("SELECT * FROM abonnement WHERE id_uti2 = ?");
                                                $stmtAbo->execute([$donnee1['id_uti']]);
                                                $abonnement = $stmtAbo->fetch(PDO::FETCH_ASSOC);
                                                if ($abonnement && new DateTime($abonnement['date_fin']) > new DateTime()) {
                                                    echo '<button type="submit" name="telecharge_pro" class="btn-action btn-success download-btn"><i class="fa-solid fa-download"></i> Téléchargement Direct</button>';
                                                } else {
                                                    echo '<a href="abonnement" class="btn-action btn-accent"><i class="fa-solid fa-crown"></i> Téléchargement Premium</a>';
                                                }
                                            } else {
                                                echo '<a href="abonnement" class="btn-action btn-accent"><i class="fa-solid fa-crown"></i> Téléchargement Premium</a>';
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            // Autres catégories
                            if ($produit['prix'] == 0) {
                                if (!isset($_SESSION['user_id'])) {
                                    echo '<button type="button" class="btn-action btn-success" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="fa-solid fa-download"></i> Télécharger gratuitement</button>';
                                } else {
                                    echo '<button type="submit" name="telecharge_gratuit" class="btn-action btn-success download-btn"><i class="fa-solid fa-download"></i> Télécharger gratuitement</button>';
                                }
                            } else {
                                if (!isset($_SESSION['user_id'])) {
                                    echo '<button type="submit" name="ajouter_panier" class="btn-action btn-primary"><i class="fa-solid fa-cart-plus"></i> Ajouter au panier</button>
                                          <button type="submit" name="telecharge" class="btn-action btn-accent"><i class="fa-solid fa-bolt"></i> Acheter Maintenant</button>';
                                } else {
                                    $email = $_SESSION['email'];
                                    $stmt = $database->prepare("SELECT * FROM utilisateur WHERE email = :email");
                                    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                                    $stmt->execute();
                                    $donnee1 = $stmt->fetch(PDO::FETCH_ASSOC);
                                    if ($donnee1) {
                                        if ($donnee1['type'] === '-') {
                                            echo '<button type="submit" name="ajouter_panier" class="btn-action btn-primary"><i class="fa-solid fa-cart-plus"></i> Ajouter au panier</button>
                                                  <button type="submit" name="telecharge" class="btn-action btn-accent"><i class="fa-solid fa-bolt"></i> Acheter Maintenant</button>';
                                        } elseif ($donnee1['type'] === 'pro') {
                                            $stmtAbo = $database->prepare("SELECT * FROM abonnement WHERE id_uti2 = ?");
                                            $stmtAbo->execute([$donnee1['id_uti']]);
                                            $abonnement = $stmtAbo->fetch(PDO::FETCH_ASSOC);
                                            if ($abonnement && new DateTime($abonnement['date_fin']) > new DateTime()) {
                                                if ($can_download_pro) {
                                                    echo '<button type="submit" name="telecharge_pro" class="btn-action btn-success download-btn"><i class="fa-solid fa-download"></i> Téléchargement Direct</button>';
                                                } else {
                                                    echo '<button type="submit" name="ajouter_panier" class="btn-action btn-primary"><i class="fa-solid fa-cart-plus"></i> Ajouter au panier</button>
                                                          <button type="submit" name="telecharge" class="btn-action btn-accent"><i class="fa-solid fa-bolt"></i> Acheter Maintenant</button>';
                                                }
                                            } else {
                                                echo '<button type="submit" name="ajouter_panier" class="btn-action btn-primary"><i class="fa-solid fa-cart-plus"></i> Ajouter au panier</button>
                                                      <button type="submit" name="telecharge" class="btn-action btn-accent"><i class="fa-solid fa-bolt"></i> Acheter Maintenant</button>';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        ?>
                    <?php endif; ?>
                </div>
            </form>
            
            <!-- Aperçu -->
            <?php if (!$is_owner && $produit['prix'] > 0 && $produit['apercue'] !== '-'): ?>
                <a target="_blank" class="btn-action btn-outline" href="<?php echo htmlspecialchars($produit['apercue']); ?>">
                    <i class="fa-solid fa-eye"></i> Aperçu en direct
                </a>
            <?php endif; ?>
            
            <!-- Offre abonnement -->
            <?php if (!$is_owner && !$can_download_pro): ?>
                <?php
                $affiche_offre = false;
                if (!isset($_SESSION['user_id'])) {
                    $affiche_offre = true;
                } else {
                    $email = $_SESSION['email'];
                    $stmtU = $database->prepare("SELECT type, id_uti FROM utilisateur WHERE email = ?");
                    $stmtU->execute([$email]);
                    $u = $stmtU->fetch();
                    if ($u) {
                        if ($u['type'] === '-') $affiche_offre = true;
                        elseif ($u['type'] === 'pro') {
                            $abo = $database->prepare("SELECT date_fin FROM abonnement WHERE id_uti2 = ?");
                            $abo->execute([$u['id_uti']]);
                            $a = $abo->fetch();
                            if (!$a || new DateTime($a['date_fin']) < new DateTime()) $affiche_offre = true;
                        }
                    }
                }
                if ($affiche_offre && ($produit['nom_categorie'] !== 'PSD' || $produit['prix'] > 0)):
                ?>
                <div class="pt-4">
                    <div class="discount-box">
                        <h4>Obtenez un accès <span>illimité</span></h4>
                        <p>Accédez à des téléchargements illimités et à des ressources exclusives avec notre abonnement premium.</p>
                        <a href="abonnement" class="btn discount-btn">Obtenez un accès illimité</a>
                    </div>
                </div>
                <?php endif; ?>
            <?php endif; ?>
            
            <!-- Partage -->
            <div class="share-section">
                <div class="share-title">Partager ce produit</div>
                <div class="share-buttons">
                    <a href="https://api.whatsapp.com/send?text=<?php echo urlencode('https://www.ndigitmarket.com/product_detail.php?nom_article=' . urlencode($produit['nom_article'])); ?>" class="share-btn share-whatsapp" target="_blank"><i class="fab fa-whatsapp"></i></a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('https://www.ndigitmarket.com/product_detail.php?nom_article=' . urlencode($produit['nom_article'])); ?>" class="share-btn share-twitter" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://www.ndigitmarket.com/product_detail.php?nom_article=' . urlencode($produit['nom_article'])); ?>" class="share-btn share-facebook" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <button class="share-btn share-copy" onclick="copyToClipboard('https://www.ndigitmarket.com/product_detail.php?nom_article=<?php echo urlencode($produit['nom_article']); ?>')"><i class="fa-solid fa-link"></i></button>
                </div>
            </div>
            
            <!-- Informations -->
            <div class="info-section">
                <div class="info-title">Informations</div>
                <ul class="info-list">
                    <li><i class="fa-regular fa-folder"></i> Catégorie : <a href="categorie?category_name=<?php echo urlencode($produit['nom_categorie']); ?>"><?php echo htmlspecialchars($produit['nom_categorie']); ?></a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Produits similaires -->
    <?php
    $similar = $database->prepare('SELECT p.*, c.nom_categorie, dv.nom_boutique AS vendeur_boutique
        FROM produits p
        INNER JOIN categories c ON p.categorie_id = c.id
        LEFT JOIN demandes_vendeur dv ON p.id_vendeur = dv.id_uti AND dv.statut = "acceptee"
        WHERE c.nom_categorie = :cat AND p.nom_article != :nom AND p.statut = "approuve"
        ORDER BY RAND() LIMIT 8');
    $similar->execute([':cat' => $produit['nom_categorie'], ':nom' => $produit['nom_article']]);
    $similarProds = $similar->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <?php if (count($similarProds) > 0): ?>
    <div class="similar-products">
        <div class="section-header">
            <h2>Autres Modèles</h2>
            <a href="categorie?category_name=<?php echo urlencode($produit['nom_categorie']); ?>">Voir tout <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="products-grid">
            <?php foreach ($similarProds as $s): ?>
            <div class="product-card">
                <div class="card-image">
                    <img src="back-end/apps/<?php echo htmlspecialchars($s['image']); ?>" alt="<?php echo htmlspecialchars($s['nom_article']); ?>">
                    <?php if ($s['prix_reduction'] > 0): ?>
                        <span class="badge badge-sale" style="position:absolute; top:10px; right:10px;">-<?php echo round((($s['prix']-$s['prix_reduction'])/$s['prix'])*100); ?>%</span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="card-category"><?php echo htmlspecialchars($s['nom_categorie']); ?></div>
                    <a href="product_detail.php?nom_article=<?php echo urlencode($s['nom_article']); ?>" class="card-title"><?php echo htmlspecialchars($s['nom_article']); ?></a>
                    <div class="card-footer">
                        <span class="card-price">
                            <?php if ($s['prix'] == 0): ?>
                                <span class="card-price free">Gratuit</span>
                            <?php else: echo number_format($s['prix_reduction'] ? $s['prix_reduction'] : $s['prix'], 0).' '.$cfa; endif; ?>
                        </span>
                        <a href="product_detail.php?nom_article=<?php echo urlencode($s['nom_article']); ?>" class="card-btn"><i class="fa-solid fa-eye"></i></a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Sticky bar -->
<div class="sticky-bar" id="stickyBar">
    <div class="sticky-content">
        <div class="sticky-info">
            <img src="back-end/apps/<?php echo htmlspecialchars($produit['image']); ?>" alt="">
            <div>
                <h4><?php echo htmlspecialchars($produit['nom_article']); ?></h4>
                <div class="sticky-price">
                    <?php if ($produit['prix'] == 0): ?>Gratuit<?php else: echo number_format($prix_affiche, 0).' '.$cfa; endif; ?>
                </div>
            </div>
        </div>
        <div class="sticky-buttons">
            <?php if ($is_owner): ?>
                <div class="owner-alert" style="margin:0; padding:10px 20px;">
                    <i class="fa-solid fa-circle-info"></i> Ce produit vous appartient.
                </div>
            <?php else: ?>
                <?php
                if ($produit['prix'] == 0) {
                    if (!isset($_SESSION['user_id'])) {
                        echo '<button class="sticky-btn sticky-btn-success" data-bs-toggle="modal" data-bs-target="#loginModal">Télécharger gratuitement</button>';
                    } else {
                        echo '<form action="add_product.php" method="POST" style="display:inline;">
                            <input type="hidden" name="nom" value="'.htmlspecialchars($produit['nom_article']).'">
                            <input type="hidden" name="id" value="'.htmlspecialchars($produit['id']).'">
                            <input type="hidden" name="nombre" value="1">
                            <button type="submit" name="telecharge_gratuit" class="sticky-btn sticky-btn-success">Télécharger gratuitement</button></form>';
                    }
                } else {
                    if (!isset($_SESSION['user_id'])) {
                        echo '<form action="add_product.php" method="POST" style="display:inline;">
                            <input type="hidden" name="nom" value="'.htmlspecialchars($produit['nom_article']).'">
                            <input type="hidden" name="id" value="'.htmlspecialchars($produit['id']).'">
                            <input type="hidden" name="nombre" value="1">
                            <button type="submit" name="ajouter_panier" class="sticky-btn sticky-btn-primary">Ajouter</button>
                            <button type="submit" name="telecharge" class="sticky-btn sticky-btn-accent">Acheter</button></form>';
                    } else {
                        $email = $_SESSION['email'];
                        $stmt = $database->prepare("SELECT type, id_uti FROM utilisateur WHERE email = ?");
                        $stmt->execute([$email]);
                        $u = $stmt->fetch();
                        if ($u) {
                            if ($u['type'] === '-') {
                                echo '<form action="add_product.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="nom" value="'.htmlspecialchars($produit['nom_article']).'">
                                    <input type="hidden" name="id" value="'.htmlspecialchars($produit['id']).'">
                                    <input type="hidden" name="nombre" value="1">
                                    <button type="submit" name="ajouter_panier" class="sticky-btn sticky-btn-primary">Ajouter</button>
                                    <button type="submit" name="telecharge" class="sticky-btn sticky-btn-accent">Acheter</button></form>';
                            } elseif ($u['type'] === 'pro') {
                                $abo = $database->prepare("SELECT date_fin FROM abonnement WHERE id_uti2 = ?");
                                $abo->execute([$u['id_uti']]);
                                $a = $abo->fetch();
                                if ($a && new DateTime($a['date_fin']) > new DateTime()) {
                                    if ($can_download_pro) {
                                        echo '<form action="add_product.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="nom" value="'.htmlspecialchars($produit['nom_article']).'">
                                            <input type="hidden" name="id" value="'.htmlspecialchars($produit['id']).'">
                                            <input type="hidden" name="nombre" value="1">
                                            <button type="submit" name="telecharge_pro" class="sticky-btn sticky-btn-success">Télécharger directement</button></form>';
                                    } else {
                                        echo '<form action="add_product.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="nom" value="'.htmlspecialchars($produit['nom_article']).'">
                                            <input type="hidden" name="id" value="'.htmlspecialchars($produit['id']).'">
                                            <input type="hidden" name="nombre" value="1">
                                            <button type="submit" name="ajouter_panier" class="sticky-btn sticky-btn-primary">Ajouter</button>
                                            <button type="submit" name="telecharge" class="sticky-btn sticky-btn-accent">Acheter</button></form>';
                                    }
                                } else {
                                    echo '<form action="add_product.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="nom" value="'.htmlspecialchars($produit['nom_article']).'">
                                        <input type="hidden" name="id" value="'.htmlspecialchars($produit['id']).'">
                                        <input type="hidden" name="nombre" value="1">
                                        <button type="submit" name="ajouter_panier" class="sticky-btn sticky-btn-primary">Ajouter</button>
                                        <button type="submit" name="telecharge" class="sticky-btn sticky-btn-accent">Acheter</button></form>';
                                }
                            }
                        }
                    }
                }
                ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Connexion/Inscription -->
<div class="modal fade modal-modern" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Connexion ou Inscription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="checkout-detail" id="login-form">
                    <p class="mb-4">Vous devez vous connecter à votre compte pour télécharger ce modèle.</p>
                    <?php
                    if (isset($_POST['connexion'])) {
                        $mdp = htmlspecialchars($_POST['mdp']);
                        $email = htmlspecialchars($_POST['email']);
                        $resultats = $database->query('SELECT * FROM utilisateur');
                        $a = false;
                        while ($donnee = $resultats->fetch()) {
                            if ($donnee['email'] == $email && password_verify($mdp, $donnee['mdp'])) {
                                $_SESSION["user_id"] = "oui";
                                $_SESSION["email"] = $email;
                                echo '<script>alert("Connexion réussie !"); window.location.href="product_detail.php?nom_article='.urlencode($_GET['nom_article']).'";</script>';
                                $a = true;
                                break;
                            }
                        }
                        if ($a == false) {
                            echo '<p style="color:red;text-align:center">Adresse ou mot de passe incorrect.</p>';
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
                        if ($mdp1 != $mdp2) {
                            echo '<p style="color:red;">Mots de passe non identiques</p>';
                        } else {
                            $check = $database->query("SELECT * FROM utilisateur WHERE email='$email'");
                            if ($check->rowCount() > 0) {
                                echo '<p style="color:red;">Email déjà utilisé</p>';
                            } else {
                                $hashed = password_hash($mdp1, PASSWORD_DEFAULT);
                                $database->exec("INSERT INTO utilisateur (nom, prenom, email, type, statut, mdp) VALUES ('$nom','$prenom','$email','$type','$statut','$hashed')");
                                $_SESSION["user_id"] = "oui";
                                $_SESSION["email"] = $email;
                                echo '<script>alert("Inscription réussie !"); window.location.href="product_detail.php?nom_article='.urlencode($_GET['nom_article']).'";</script>';
                            }
                        }
                    }
                    ?>
                    <form method="post">
                        <div class="form-group"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Mot de passe</label><input type="password" name="mdp" class="form-control" required></div>
                        <button type="submit" name="connexion" class="btn-action btn-primary w-100">Se connecter</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="oublier" class="text-theme">Mot de passe oublié ?</a><br><br>
                        <a href="javascript:void(0);" class="text-theme" onclick="toggleForm()">Pas encore inscrit ? Inscrivez-vous</a>
                    </div>
                </div>
                <div class="checkout-detail" id="register-form" style="display: none;">
                    <p class="mb-4">Inscrivez-vous pour télécharger ce modèle.</p>
                    <form method="post">
                        <div class="row">
                            <div class="col-md-6"><div class="form-group"><label class="form-label">Nom</label><input type="text" name="nom" class="form-control" required></div></div>
                            <div class="col-md-6"><div class="form-group"><label class="form-label">Prénom</label><input type="text" name="prenom" class="form-control" required></div></div>
                        </div>
                        <div class="form-group"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Mot de passe</label><input type="password" name="mdp1" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Confirmer le mot de passe</label><input type="password" name="mdp2" class="form-control" required></div>
                        <button type="submit" name="inscrire" class="btn-action btn-primary w-100">S'inscrire</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="javascript:void(0);" class="text-theme" onclick="toggleForm()">Déjà inscrit ? Se connecter</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showTab(tab) {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
        document.getElementById('tab-' + tab).classList.add('active');
        event.target.classList.add('active');
    }
    function openFullscreen(btn) {
        Swal.fire({
            imageUrl: btn.closest('.product-image').querySelector('img').src,
            showCloseButton: true,
            showConfirmButton: false,
            width: '90%',
            backdrop: true
        });
    }
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => Swal.fire({icon:'success', title:'Lien copié', timer:1500, showConfirmButton:false}));
    }
    window.addEventListener('scroll', function() {
        document.getElementById('stickyBar').classList.toggle('visible', window.scrollY > 500);
    });
    function toggleForm() {
        var loginForm = document.getElementById("login-form");
        var registerForm = document.getElementById("register-form");
        loginForm.style.display = (loginForm.style.display === "none") ? "block" : "none";
        registerForm.style.display = (registerForm.style.display === "block") ? "none" : "block";
    }
</script>
</body>
</html>