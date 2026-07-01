
    <?php
// Inclure le header (contient la connexion à la base de données)
require_once 'header.php';
require_once 'include/connect.php';

// Nombre de produits par page
$produitsParPage = 20;

// Récupérer les paramètres de filtre depuis l'URL
$pageActuelle = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$pageActuelle = max(1, $pageActuelle);
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$prix_min = isset($_GET['prix_min']) && is_numeric($_GET['prix_min']) ? (float)$_GET['prix_min'] : '';
$prix_max = isset($_GET['prix_max']) && is_numeric($_GET['prix_max']) ? (float)$_GET['prix_max'] : '';
$tri = isset($_GET['tri']) ? $_GET['tri'] : 'recent';

// Calculer l'offset pour la requête SQL
$offset = ($pageActuelle - 1) * $produitsParPage;

// Récupérer toutes les catégories avec le nombre de produits (uniquement approuvés)
try {
    $categoriesQuery = $database->query('
        SELECT c.nom_categorie, COUNT(p.id) as nb_produits 
        FROM categories c 
        LEFT JOIN produits p ON c.id = p.categorie_id AND p.statut = "approuve"
        GROUP BY c.id 
        ORDER BY c.nom_categorie
    ');
    $categories = $categoriesQuery->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $categories = [];
}

// Construire la requête de comptage avec filtres (uniquement approuvés)
$countQuery = 'SELECT COUNT(DISTINCT p.id) AS total FROM produits p INNER JOIN categories c ON p.categorie_id = c.id WHERE p.statut = "approuve"';
$params = [];

if ($category) {
    $countQuery .= ' AND c.nom_categorie = :category';
    $params[':category'] = $category;
}

if ($prix_min !== '') {
    $countQuery .= ' AND (CASE WHEN p.prix_reduction > 0 THEN p.prix_reduction ELSE p.prix END) >= :prix_min';
    $params[':prix_min'] = $prix_min;
}
if ($prix_max !== '') {
    $countQuery .= ' AND (CASE WHEN p.prix_reduction > 0 THEN p.prix_reduction ELSE p.prix END) <= :prix_max';
    $params[':prix_max'] = $prix_max;
}

try {
    $stmt = $database->prepare($countQuery);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    $totalProduits = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Calculer le nombre total de pages
$totalPages = ceil($totalProduits / $produitsParPage);

// Construire la requête de récupération avec jointure vendeur et filtre statut approuvé
$query = 'SELECT p.*, c.nom_categorie,
          dv.nom_boutique AS vendeur_boutique,
          CASE WHEN p.prix_reduction > 0 THEN p.prix_reduction ELSE p.prix END as prix_actuel
          FROM produits p
          INNER JOIN categories c ON p.categorie_id = c.id
          LEFT JOIN demandes_vendeur dv ON p.id_vendeur = dv.id_uti AND dv.statut = "acceptee"
          WHERE p.statut = "approuve"';

if ($category) {
    $query .= ' AND c.nom_categorie = :category';
}
if ($prix_min !== '') {
    $query .= ' AND (CASE WHEN p.prix_reduction > 0 THEN p.prix_reduction ELSE p.prix END) >= :prix_min';
}
if ($prix_max !== '') {
    $query .= ' AND (CASE WHEN p.prix_reduction > 0 THEN p.prix_reduction ELSE p.prix END) <= :prix_max';
}

// Appliquer le tri
switch ($tri) {
    case 'prix_croissant':
        $query .= ' ORDER BY prix_actuel ASC';
        break;
    case 'prix_decroissant':
        $query .= ' ORDER BY prix_actuel DESC';
        break;
    case 'nom_asc':
        $query .= ' ORDER BY p.nom_article ASC';
        break;
    case 'nom_desc':
        $query .= ' ORDER BY p.nom_article DESC';
        break;
    case 'recent':
    default:
        $query .= ' ORDER BY p.date_ajout DESC';
        break;
}

$query .= ' LIMIT :offset, :limit';

try {
    $stmt = $database->prepare($query);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $produitsParPage, PDO::PARAM_INT);
    $stmt->execute();
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des produits : " . $e->getMessage());
}

// Déterminer le titre dynamique
$title = $category ? "Modèles " . htmlspecialchars($category) : "Tous nos modèles";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/images/favi.png" type="image/x-icon">
    <title>NDIGITMARKET - Boutique de templates professionnels</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Toastify CSS -->
    <link href="https://cdn.jsdelivr.net/npm/toastify-js@1.12.0/src/toastify.min.css" rel="stylesheet">
    
    <!-- CSS inchangé (le même que votre fichier actuel) -->
    <style>
        :root {
            --primary: #087d67;
            --primary-dark: #065a4a;
            --primary-light: #e8f5f2;
            --accent: #f97316;
            --dark: #0f1923;
            --text: #374151;
            --text-light: #6b7280;
            --border: #e5e7eb;
            --bg: #f9fafb;
            --white: #ffffff;
            --shadow: 0 10px 30px -10px rgba(0,0,0,0.1);
            --shadow-hover: 0 20px 40px -15px rgba(8,125,103,0.3);
            --radius: 16px;
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
        }

        /* ===== BREADCRUMB MODERNE ===== */
        .breadcrumb-modern {
            background: linear-gradient(135deg, var(--dark) 0%, #1e2a3a 100%);
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
            width: 200px;
            height: 200px;
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
        }

        .breadcrumb-content h1 {
            color: white;
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .breadcrumb-content h1 span {
            color: var(--accent);
            background: rgba(255,255,255,0.1);
            padding: 5px 15px;
            border-radius: 40px;
            font-size: 16px;
            margin-left: 10px;
        }

        .breadcrumb-links {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255,255,255,0.6);
        }

        .breadcrumb-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: color 0.3s;
        }

        .breadcrumb-links a:hover {
            color: white;
        }

        .breadcrumb-links i {
            font-size: 12px;
        }

        /* ===== LAYOUT PRINCIPAL ===== */
        .shop-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px 60px;
        }

        .shop-layout {
            display: flex;
            gap: 30px;
            position: relative;
        }

        /* ===== FILTRE DESKTOP ===== */
        .filter-sidebar {
            width: 300px;
            flex-shrink: 0;
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            overflow: hidden;
            position: sticky;
            top: 100px;
            height: fit-content;
            transition: all 0.3s;
        }

        .filter-header {
            padding: 20px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .filter-header h3 {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-header h3 i {
            font-size: 20px;
        }

        .reset-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            font-size: 13px;
            padding: 6px 12px;
            border-radius: 30px;
            transition: all 0.3s;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .reset-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        .filter-body {
            padding: 20px;
        }

        .filter-section {
            margin-bottom: 25px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 20px;
        }

        .filter-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .filter-title {
            font-weight: 700;
            font-size: 16px;
            color: var(--dark);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-title i {
            color: var(--primary);
            font-size: 18px;
        }

        /* Liste des catégories */
        .category-list {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 300px;
            overflow-y: auto;
        }

        .category-item {
            margin-bottom: 8px;
        }

        .category-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 12px;
            border-radius: 12px;
            background: var(--bg);
            color: var(--text);
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .category-link:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            transform: translateX(5px);
        }

        .category-link.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .category-name {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .category-count {
            background: white;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 11px;
            color: var(--text);
            border: 1px solid var(--border);
        }

        .category-link.active .category-count {
            background: rgba(255,255,255,0.2);
            color: white;
            border-color: rgba(255,255,255,0.3);
        }

        /* Filtre prix */
        .price-range {
            padding: 5px 0;
        }

        .price-inputs {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .price-field {
            flex: 1;
            position: relative;
        }

        .price-field span {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 12px;
        }

        .price-field input {
            width: 100%;
            padding: 10px 10px 10px 25px;
            border: 1px solid var(--border);
            border-radius: 12px;
            font-size: 13px;
            transition: all 0.3s;
        }

        .price-field input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(8,125,103,0.1);
        }

        .price-separator {
            color: var(--text-light);
            font-weight: 600;
        }

        .btn-apply {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-apply:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(8,125,103,0.3);
        }

        /* Filtre tri */
        .sort-select {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 12px;
            font-size: 14px;
            color: var(--text);
            background: white;
            cursor: pointer;
            transition: all 0.3s;
        }

        .sort-select:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(8,125,103,0.1);
        }

        /* ===== CONTENU PRODUITS ===== */
        .products-content {
            flex: 1;
        }

        .products-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
            background: white;
            padding: 15px 20px;
            border-radius: 60px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }

        .products-count {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: var(--text);
        }

        .products-count i {
            color: var(--primary);
        }

        .products-count strong {
            color: var(--primary);
            font-size: 18px;
            margin: 0 2px;
        }

        .mobile-filter-btn {
            display: none;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 14px;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(8,125,103,0.3);
        }

        .mobile-filter-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(8,125,103,0.4);
        }

        /* Grille de produits */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }

        /* Carte produit moderne */
        .product-card-modern {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid var(--border);
            transition: all 0.3s;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .product-card-modern:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-hover);
            transform: translateY(-8px);
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

        .product-card-modern:hover .product-image-wrapper img {
            transform: scale(1.1);
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
            background: #10b981;
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

        .discount-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #ef4444;
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

        .product-card-modern:hover .product-overlay {
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

        .product-card-modern:hover .btn-quickview {
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
            color: #10b981;
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

        /* ===== PAGINATION CORRIGÉE ===== */
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

        /* Responsive pagination */
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
                padding: 0 8px;
            }
        }

        /* ===== FILTER MOBILE CORRIGÉ ===== */
        .mobile-filter-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 10000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
            backdrop-filter: blur(5px);
        }

        .mobile-filter-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .mobile-filter-panel {
            position: fixed;
            top: 0;
            left: -100%;
            width: 85%;
            max-width: 350px;
            height: 100vh;
            background: white;
            z-index: 10001;
            overflow-y: auto;
            transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 5px 0 30px rgba(0,0,0,0.2);
            display: flex;
            flex-direction: column;
        }

        .mobile-filter-overlay.active .mobile-filter-panel {
            left: 0;
        }

        .mobile-filter-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .mobile-filter-header h4 {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .close-mobile-filter {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border: none;
            border-radius: 50%;
            color: white;
            font-size: 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .close-mobile-filter:hover {
            background: rgba(255,255,255,0.3);
            transform: rotate(90deg);
        }

        .mobile-filter-body {
            padding: 20px;
            flex: 1;
            overflow-y: auto;
        }

        .mobile-filter-footer {
            padding: 20px;
            border-top: 1px solid var(--border);
            background: white;
            position: sticky;
            bottom: 0;
            display: flex;
            gap: 10px;
            box-shadow: 0 -5px 15px rgba(0,0,0,0.05);
        }

        .mobile-filter-footer .btn-apply {
            flex: 2;
            padding: 14px;
            font-size: 15px;
        }

        .btn-reset-mobile {
            flex: 1;
            padding: 14px;
            background: var(--bg);
            color: var(--text);
            border: 1px solid var(--border);
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-reset-mobile:hover {
            background: var(--border);
        }

        /* Loader */
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

        /* Responsive */
        @media (max-width: 1200px) {
            .products-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 992px) {
            .filter-sidebar {
                display: none;
            }
            
            .mobile-filter-btn {
                display: flex;
            }
            
            .products-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
            
            .breadcrumb-content h1 {
                font-size: 28px;
            }
            
            .products-header {
                border-radius: 20px;
                padding: 12px 15px;
            }
            
            .products-count {
                font-size: 13px;
            }
            
            .products-count strong {
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .products-grid {
                gap: 10px;
            }
            
            .product-title {
                font-size: 13px;
            }
            
            .current-price {
                font-size: 15px;
            }
            
            .btn-detail {
                width: 32px;
                height: 32px;
            }
            
            .products-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .mobile-filter-btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Toastify personnalisé */
        .toastify-custom {
            background: var(--primary) !important;
            border-radius: 30px !important;
            padding: 12px 20px !important;
            font-weight: 500 !important;
            box-shadow: 0 10px 30px rgba(8,125,103,0.3) !important;
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
    <div class="container">
        <div class="breadcrumb-content">
            <h1>
                <?php echo $title; ?>
                <?php if ($category): ?>
                    <span><?php echo htmlspecialchars($category); ?></span>
                <?php endif; ?>
            </h1>
            <div class="breadcrumb-links">
                <a href="index.php"><i class="fa-solid fa-house"></i> Accueil</a>
                <i class="fa-solid fa-chevron-right"></i>
                <span>Boutique</span>
                <?php if ($category): ?>
                    <i class="fa-solid fa-chevron-right"></i>
                    <span><?php echo htmlspecialchars($category); ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Contenu principal -->
<div class="shop-container">
    <div class="shop-layout">
        
        <!-- FILTER SIDEBAR DESKTOP -->
        <div class="filter-sidebar" id="desktopFilter">
            <div class="filter-header">
                <h3><i class="fa-solid fa-sliders"></i> Filtres</h3>
                <button class="reset-btn" id="resetFiltersDesktop">
                    <i class="fa-solid fa-rotate"></i> Réinitialiser
                </button>
            </div>
            <div class="filter-body">
                
                <!-- Filtre par catégorie -->
                <div class="filter-section">
                    <div class="filter-title">
                        <i class="fa-solid fa-tags"></i> Catégories
                    </div>
                    <ul class="category-list">
                        <li class="category-item">
                            <a href="shop.php" class="category-link <?php echo !$category ? 'active' : ''; ?>">
                                <span class="category-name"><i class="fa-regular fa-folder"></i> Toutes</span>
                                <span class="category-count"><?php echo array_sum(array_column($categories, 'nb_produits')); ?></span>
                            </a>
                        </li>
                        <?php foreach ($categories as $cat): ?>
                            <li class="category-item">
                                <a href="?category=<?php echo urlencode($cat['nom_categorie']); ?>&tri=<?php echo $tri; ?>" 
                                   class="category-link <?php echo $category === $cat['nom_categorie'] ? 'active' : ''; ?>">
                                    <span class="category-name">
                                        <i class="fa-regular fa-folder"></i> <?php echo htmlspecialchars($cat['nom_categorie']); ?>
                                    </span>
                                    <span class="category-count"><?php echo $cat['nb_produits']; ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- Filtre par prix -->
                <div class="filter-section">
                    <div class="filter-title">
                        <i class="fa-solid fa-coins"></i> Prix (CFA)
                    </div>
                    <div class="price-range">
                        <div class="price-inputs">
                            <div class="price-field">
                                <span>Min</span>
                                <input type="number" id="prix_min" placeholder="0" value="<?php echo htmlspecialchars($prix_min ?: ''); ?>" min="0">
                            </div>
                            <span class="price-separator">-</span>
                            <div class="price-field">
                                <span>Max</span>
                                <input type="number" id="prix_max" placeholder="500000" value="<?php echo htmlspecialchars($prix_max ?: ''); ?>" min="0">
                            </div>
                        </div>
                        <button class="btn-apply" id="applyPriceFilter">
                            <i class="fa-solid fa-check"></i> Appliquer
                        </button>
                    </div>
                </div>
                
                <!-- Filtre tri -->
                <div class="filter-section">
                    <div class="filter-title">
                        <i class="fa-solid fa-arrow-down-wide-short"></i> Trier par
                    </div>
                    <select class="sort-select" id="sortSelect">
                        <option value="recent" <?php echo $tri == 'recent' ? 'selected' : ''; ?>>Plus récents</option>
                        <option value="prix_croissant" <?php echo $tri == 'prix_croissant' ? 'selected' : ''; ?>>Prix croissant</option>
                        <option value="prix_decroissant" <?php echo $tri == 'prix_decroissant' ? 'selected' : ''; ?>>Prix décroissant</option>
                        <option value="nom_asc" <?php echo $tri == 'nom_asc' ? 'selected' : ''; ?>>Nom (A-Z)</option>
                        <option value="nom_desc" <?php echo $tri == 'nom_desc' ? 'selected' : ''; ?>>Nom (Z-A)</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- CONTENU PRODUITS -->
        <div class="products-content">
            
            <!-- Header avec compteur et bouton filtre mobile -->
            <div class="products-header">
                <div class="products-count">
                    <i class="fa-regular fa-eye"></i>
                    <span>Affichage <strong><?php echo $totalProduits > 0 ? $offset + 1 : 0; ?></strong> - <strong><?php echo min($offset + $produitsParPage, $totalProduits); ?></strong> sur <strong><?php echo $totalProduits; ?></strong> produits</span>
                </div>
                <button class="mobile-filter-btn" id="mobileFilterBtn">
                    <i class="fa-solid fa-sliders"></i> Filtres
                </button>
            </div>
            
            <!-- Grille de produits -->
            <div class="products-grid" id="productsGrid">
                <?php foreach ($produits as $produit): 
                    $imagePath = 'back-end/apps/' . htmlspecialchars($produit['image'] ?? 'Blue.jpg');
                    if (!file_exists($imagePath)) {
                        $imagePath = 'back-end/apps/Blue.jpg';
                    }
                    $hasSale = $produit['prix_reduction'] > 0;
                    $isFree = $produit['prix'] == 0;
                    $discount = $hasSale ? round((($produit['prix'] - $produit['prix_reduction']) / $produit['prix']) * 100) : 0;
                    $vendeurBoutique = $produit['vendeur_boutique'] ?? '';
                ?>
                    <div class="product-card-modern">
                        <div class="product-image-wrapper">
                            <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($produit['nom_article']); ?>" loading="lazy">
                            
                            <div class="product-badges">
                                <?php if ($isFree): ?>
                                    <span class="badge badge-free">Gratuit</span>
                                <?php endif; ?>
                                <?php 
                                $dateAjout = strtotime($produit['date_ajout']);
                                if (time() - $dateAjout < 7 * 24 * 60 * 60): 
                                ?>
                                    <span class="badge badge-new">Nouveau</span>
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
                            <?php if (!empty($vendeurBoutique)): ?>
                                <div class="seller-name" style="font-size:12px;color:var(--text-light);display:flex;align-items:center;gap:4px;margin-top:4px;">
                                    <i class="fa-solid fa-store" style="color:var(--primary);font-size:13px;"></i>
                                    <?php echo htmlspecialchars($vendeurBoutique); ?>
                                </div>
                            <?php endif; ?>
                            
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
            
            <!-- Pagination moderne corrigée -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination-modern">
                    <a href="?page=<?php echo $pageActuelle - 1; ?>&category=<?php echo urlencode($category); ?>&prix_min=<?php echo $prix_min; ?>&prix_max=<?php echo $prix_max; ?>&tri=<?php echo $tri; ?>" 
                       class="page-btn <?php echo $pageActuelle == 1 ? 'disabled' : ''; ?>">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                    
                    <?php 
                    if ($pageActuelle > 3): ?>
                        <a href="?page=1&category=<?php echo urlencode($category); ?>&prix_min=<?php echo $prix_min; ?>&prix_max=<?php echo $prix_max; ?>&tri=<?php echo $tri; ?>" class="page-btn">1</a>
                        <?php if ($pageActuelle > 4): ?>
                            <span class="page-btn disabled">...</span>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <?php 
                    $startPage = max(1, $pageActuelle - 2);
                    $endPage = min($totalPages, $pageActuelle + 2);
                    
                    for ($i = $startPage; $i <= $endPage; $i++): 
                    ?>
                        <a href="?page=<?php echo $i; ?>&category=<?php echo urlencode($category); ?>&prix_min=<?php echo $prix_min; ?>&prix_max=<?php echo $prix_max; ?>&tri=<?php echo $tri; ?>" 
                           class="page-btn <?php echo $pageActuelle == $i ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php 
                    if ($pageActuelle < $totalPages - 2): ?>
                        <?php if ($pageActuelle < $totalPages - 3): ?>
                            <span class="page-btn disabled">...</span>
                        <?php endif; ?>
                        <a href="?page=<?php echo $totalPages; ?>&category=<?php echo urlencode($category); ?>&prix_min=<?php echo $prix_min; ?>&prix_max=<?php echo $prix_max; ?>&tri=<?php echo $tri; ?>" class="page-btn"><?php echo $totalPages; ?></a>
                    <?php endif; ?>
                    
                    <a href="?page=<?php echo $pageActuelle + 1; ?>&category=<?php echo urlencode($category); ?>&prix_min=<?php echo $prix_min; ?>&prix_max=<?php echo $prix_max; ?>&tri=<?php echo $tri; ?>" 
                       class="page-btn <?php echo $pageActuelle == $totalPages ? 'disabled' : ''; ?>">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- FILTER MOBILE OVERLAY CORRIGÉ -->
<div class="mobile-filter-overlay" id="mobileFilterOverlay">
    <div class="mobile-filter-panel">
        <div class="mobile-filter-header">
            <h4><i class="fa-solid fa-sliders"></i> Filtres</h4>
            <button class="close-mobile-filter" id="closeMobileFilter">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div class="mobile-filter-body">
            <!-- Catégories -->
            <div class="filter-section">
                <div class="filter-title">
                    <i class="fa-solid fa-tags"></i> Catégories
                </div>
                <ul class="category-list">
                    <li class="category-item">
                        <a href="shop.php" class="category-link <?php echo !$category ? 'active' : ''; ?>">
                            <span class="category-name"><i class="fa-regular fa-folder"></i> Toutes</span>
                            <span class="category-count"><?php echo array_sum(array_column($categories, 'nb_produits')); ?></span>
                        </a>
                    </li>
                    <?php foreach ($categories as $cat): ?>
                        <li class="category-item">
                            <a href="?category=<?php echo urlencode($cat['nom_categorie']); ?>" 
                               class="category-link <?php echo $category === $cat['nom_categorie'] ? 'active' : ''; ?>">
                                <span class="category-name">
                                    <i class="fa-regular fa-folder"></i> <?php echo htmlspecialchars($cat['nom_categorie']); ?>
                                </span>
                                <span class="category-count"><?php echo $cat['nb_produits']; ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <!-- Prix -->
            <div class="filter-section">
                <div class="filter-title">
                    <i class="fa-solid fa-coins"></i> Prix (CFA)
                </div>
                <div class="price-range">
                    <div class="price-inputs">
                        <div class="price-field">
                            <span>Min</span>
                            <input type="number" id="mobile_prix_min" placeholder="0" value="<?php echo htmlspecialchars($prix_min ?: ''); ?>" min="0">
                        </div>
                        <span class="price-separator">-</span>
                        <div class="price-field">
                            <span>Max</span>
                            <input type="number" id="mobile_prix_max" placeholder="500000" value="<?php echo htmlspecialchars($prix_max ?: ''); ?>" min="0">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tri -->
            <div class="filter-section">
                <div class="filter-title">
                    <i class="fa-solid fa-arrow-down-wide-short"></i> Trier par
                </div>
                <select class="sort-select" id="mobile_sortSelect">
                    <option value="recent" <?php echo $tri == 'recent' ? 'selected' : ''; ?>>Plus récents</option>
                    <option value="prix_croissant" <?php echo $tri == 'prix_croissant' ? 'selected' : ''; ?>>Prix croissant</option>
                    <option value="prix_decroissant" <?php echo $tri == 'prix_decroissant' ? 'selected' : ''; ?>>Prix décroissant</option>
                    <option value="nom_asc" <?php echo $tri == 'nom_asc' ? 'selected' : ''; ?>>Nom (A-Z)</option>
                    <option value="nom_desc" <?php echo $tri == 'nom_desc' ? 'selected' : ''; ?>>Nom (Z-A)</option>
                </select>
            </div>
            
            <div class="mobile-filter-footer">
                <button class="btn-apply" id="mobileApplyFilters">
                    <i class="fa-solid fa-check"></i> Appliquer
                </button>
                <button class="btn-reset-mobile" id="mobileResetFilters">
                    <i class="fa-solid fa-rotate"></i> Reset
                </button>
            </div>
        </div>
    </div>
</div>

<?php require('footer.php'); ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js@1.12.0/src/toastify.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loader = document.getElementById('loader');
    const mobileFilterBtn = document.getElementById('mobileFilterBtn');
    const mobileFilterOverlay = document.getElementById('mobileFilterOverlay');
    const closeMobileFilter = document.getElementById('closeMobileFilter');
    
    // Éléments des filtres
    const prixMin = document.getElementById('prix_min');
    const prixMax = document.getElementById('prix_max');
    const sortSelect = document.getElementById('sortSelect');
    const applyPriceBtn = document.getElementById('applyPriceFilter');
    const resetBtn = document.getElementById('resetFiltersDesktop');
    
    // Éléments mobiles
    const mobilePrixMin = document.getElementById('mobile_prix_min');
    const mobilePrixMax = document.getElementById('mobile_prix_max');
    const mobileSortSelect = document.getElementById('mobile_sortSelect');
    const mobileApplyBtn = document.getElementById('mobileApplyFilters');
    const mobileResetBtn = document.getElementById('mobileResetFilters');
    
    // Fonction pour construire l'URL avec les filtres
    function buildFilterUrl(params = {}) {
        const url = new URL(window.location.href);
        
        if (params.category !== undefined) {
            if (params.category) url.searchParams.set('category', params.category);
            else url.searchParams.delete('category');
        }
        
        if (params.prix_min !== undefined) {
            if (params.prix_min) url.searchParams.set('prix_min', params.prix_min);
            else url.searchParams.delete('prix_min');
        }
        
        if (params.prix_max !== undefined) {
            if (params.prix_max) url.searchParams.set('prix_max', params.prix_max);
            else url.searchParams.delete('prix_max');
        }
        
        if (params.tri !== undefined) {
            if (params.tri && params.tri !== 'recent') url.searchParams.set('tri', params.tri);
            else url.searchParams.delete('tri');
        }
        
        // Reset à la page 1 lors d'un changement de filtre
        url.searchParams.set('page', '1');
        
        return url.toString();
    }
    
    // Fonction pour appliquer les filtres et recharger
    function applyFilters(params) {
        loader.style.display = 'flex';
        window.location.href = buildFilterUrl(params);
    }
    
    // Fonction pour réinitialiser tous les filtres
    function resetFilters() {
        applyFilters({
            category: '',
            prix_min: '',
            prix_max: '',
            tri: 'recent'
        });
    }
    
    // Événements desktop
    if (applyPriceBtn) {
        applyPriceBtn.addEventListener('click', function() {
            applyFilters({
                category: '<?php echo $category; ?>',
                prix_min: prixMin.value,
                prix_max: prixMax.value,
                tri: sortSelect.value
            });
        });
    }
    
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            applyFilters({
                category: '<?php echo $category; ?>',
                prix_min: prixMin.value,
                prix_max: prixMax.value,
                tri: this.value
            });
        });
    }
    
    if (resetBtn) {
        resetBtn.addEventListener('click', resetFilters);
    }
    
    // Gestion des clics sur les catégories
    document.querySelectorAll('.category-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            loader.style.display = 'flex';
            window.location.href = href;
        });
    });
    
    // Mobile : ouvrir/fermer le filtre
    if (mobileFilterBtn) {
        mobileFilterBtn.addEventListener('click', function() {
            mobileFilterOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }
    
    if (closeMobileFilter) {
        closeMobileFilter.addEventListener('click', function() {
            mobileFilterOverlay.classList.remove('active');
            document.body.style.overflow = '';
        });
    }
    
    mobileFilterOverlay.addEventListener('click', function(e) {
        if (e.target === mobileFilterOverlay) {
            mobileFilterOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
    
    // Mobile : appliquer les filtres
    if (mobileApplyBtn) {
        mobileApplyBtn.addEventListener('click', function() {
            applyFilters({
                category: '<?php echo $category; ?>',
                prix_min: mobilePrixMin.value,
                prix_max: mobilePrixMax.value,
                tri: mobileSortSelect.value
            });
        });
    }
    
    // Mobile : réinitialiser
    if (mobileResetBtn) {
        mobileResetBtn.addEventListener('click', resetFilters);
    }
    
    // Afficher une notification Toastify
    function showNotification(message, type = 'success') {
        Toastify({
            text: message,
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: type === 'success' ? '#087d67' : '#e63946',
            className: 'toastify-custom',
            stopOnFocus: true,
        }).showToast();
    }
    
    <?php if ($category || $prix_min || $prix_max || $tri != 'recent'): ?>
        showNotification('Filtres appliqués avec succès');
    <?php endif; ?>
});
</script>

</body>
</html>

