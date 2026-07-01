<?php
require('header.php');

$boutique = isset($_GET['boutique']) ? trim($_GET['boutique']) : '';

if (empty($boutique)) {
    header('Location: vendeurs.php');
    exit;
}

// Récupérer le vendeur par nom de boutique
$stmt = $database->prepare("
    SELECT dv.*, COUNT(p.id) AS nb_produits
    FROM demandes_vendeur dv
    LEFT JOIN produits p ON dv.id_uti = p.id_vendeur AND p.statut = 'approuve'
    WHERE dv.nom_boutique = ? AND dv.statut = 'acceptee'
    GROUP BY dv.id_uti
");
$stmt->execute([$boutique]);
$vendeur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$vendeur) {
    echo '<div style="text-align:center; padding:100px 20px;"><h2>Vendeur introuvable</h2><a href="vendeurs.php" class="btn btn-primary mt-3">Voir tous les vendeurs</a></div>';
    require('footer.php');
    exit;
}
// Pagination des produits
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 12;
$offset = ($page - 1) * $perPage;

$countProd = $database->prepare("SELECT COUNT(*) FROM produits WHERE id_vendeur = :id_vendeur AND statut = 'approuve'");
$countProd->execute([':id_vendeur' => $vendeur['id_uti']]);
$totalProds = $countProd->fetchColumn();
$pagesProd = ceil($totalProds / $perPage);

$stmtProd = $database->prepare("
    SELECT p.*, c.nom_categorie
    FROM produits p
    LEFT JOIN categories c ON p.categorie_id = c.id
    WHERE p.id_vendeur = :id_vendeur AND p.statut = 'approuve'
    ORDER BY p.date_ajout DESC
    LIMIT :limit OFFSET :offset
");
$stmtProd->bindValue(':id_vendeur', $vendeur['id_uti'], PDO::PARAM_INT);
$stmtProd->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmtProd->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmtProd->execute();
$produits = $stmtProd->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($vendeur['nom_boutique']); ?> - NDIGITMARKET</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
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
            --radius: 16px;
            --shadow: 0 10px 30px -10px rgba(0,0,0,0.1);
            --shadow-hover: 0 20px 40px -15px rgba(8,125,103,0.2);
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); }
        
        /* Bandeau hero vendeur */
        .vendor-hero {
            background: linear-gradient(135deg, var(--dark) 0%, #1a2634 100%);
            padding: 60px 20px;
            position: relative;
            overflow: hidden;
            margin-bottom: 40px;
        }
        .vendor-hero::before {
            content: ''; position: absolute; top: -80px; right: -80px;
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(8,125,103,0.25) 0%, transparent 70%);
            border-radius: 50%;
        }
        .vendor-hero::after {
            content: ''; position: absolute; bottom: -60px; left: -60px;
            width: 250px; height: 250px;
            background: radial-gradient(circle, rgba(249,115,22,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }
        .vendor-hero-content {
            position: relative; z-index: 2;
            max-width: 1200px; margin: 0 auto;
            display: flex; align-items: center; gap: 30px;
            flex-wrap: wrap;
        }
        .vendor-avatar {
            width: 100px; height: 100px;
            background: rgba(255,255,255,0.15);
            border-radius: 24px;
            display: flex; align-items: center; justify-content: center;
            font-size: 45px; color: white;
            border: 2px solid rgba(255,255,255,0.3);
            flex-shrink: 0;
        }
        .vendor-info { color: white; }
        .vendor-info h1 { font-size: 32px; font-weight: 800; margin-bottom: 8px; }
        .vendor-info .category-badge {
            display: inline-block;
            background: var(--accent);
            color: white;
            padding: 5px 15px;
            border-radius: 30px;
            font-size: 13px; font-weight: 600;
            margin-bottom: 12px;
        }
        .vendor-info .description {
            color: rgba(255,255,255,0.75);
            font-size: 15px; line-height: 1.7; max-width: 600px;
        }
        .vendor-stats-row {
            display: flex; gap: 25px; margin-top: 15px; flex-wrap: wrap;
        }
        .vendor-stat-item {
            background: rgba(255,255,255,0.1);
            padding: 12px 20px; border-radius: 12px;
            text-align: center; color: white;
        }
        .vendor-stat-item .val { font-size: 22px; font-weight: 800; }
        .vendor-stat-item .lbl { font-size: 12px; opacity: 0.8; }
        
        .container-custom { max-width: 1200px; margin: 0 auto; padding: 0 20px 60px; }
        
        .section-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 25px; flex-wrap: wrap; gap: 15px;
            background: white; padding: 15px 20px; border-radius: 60px;
            box-shadow: var(--shadow); border: 1px solid var(--border);
        }
        .section-header h2 { font-size: 22px; font-weight: 700; color: var(--dark); }
        .products-count { color: var(--text-light); font-size: 14px; }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .product-card {
            background: white;
            border-radius: var(--radius);
            overflow: hidden;
            border: 1px solid var(--border);
            transition: all 0.3s;
            text-decoration: none; color: var(--text);
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
            border-color: var(--primary);
        }
        .product-image {
            aspect-ratio: 16/10; overflow: hidden; background: #f5f5f5;
        }
        .product-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s; }
        .product-card:hover .product-image img { transform: scale(1.05); }
        .product-body { padding: 15px; }
        .product-cat { font-size: 11px; font-weight: 600; text-transform: uppercase; color: var(--primary); margin-bottom: 5px; }
        .product-name { font-size: 14px; font-weight: 700; color: var(--dark); margin-bottom: 10px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .product-footer { display: flex; justify-content: space-between; align-items: center; }
        .product-price { font-size: 16px; font-weight: 800; color: var(--primary); }
        .product-price.free { color: #10b981; }
        
        .pagination { display: flex; justify-content: center; gap: 8px; flex-wrap: wrap; }
        .page-link {
            padding: 10px 18px; border-radius: 10px;
            background: white; border: 1px solid var(--border);
            text-decoration: none; color: var(--text); font-weight: 500; transition: all 0.2s;
        }
        .page-link.active { background: var(--primary); color: white; border-color: var(--primary); }
        .page-link:hover:not(.active) { background: var(--primary-light); color: var(--primary); }
        
        @media (max-width: 992px) { .products-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 768px) { 
            .products-grid { grid-template-columns: repeat(2, 1fr); }
            .vendor-hero-content { flex-direction: column; text-align: center; }
            .vendor-stats-row { justify-content: center; }
            .vendor-info .description { margin: 0 auto; }
        }
        @media (max-width: 480px) { .products-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<!-- Bandeau vendeur -->
<section class="vendor-hero">
    <div class="vendor-hero-content">
        <div class="vendor-avatar"><i class="fa-solid fa-store"></i></div>
        <div class="vendor-info">
            <h1><?php echo htmlspecialchars($vendeur['nom_boutique']); ?></h1>
          
            <?php if (!empty($vendeur['description'])): ?>
                <p class="description"><?php echo nl2br(htmlspecialchars($vendeur['description'])); ?></p>
            <?php endif; ?>
            <div class="vendor-stats-row">
                <div class="vendor-stat-item">
                    <div class="val"><?php echo $vendeur['nb_produits']; ?></div>
                    <div class="lbl">Produits actifs</div>
                </div>
                <!-- <div class="vendor-stat-item">
                    <div class="val"><?php echo date('d/m/Y', strtotime($vendeur['date_demande'])); ?></div>
                    <div class="lbl">Vendeur depuis</div>
                </div> -->
            </div>
        </div>
    </div>
</section>

<div class="container-custom">
    <div class="section-header">
        <h2><i class="fa-solid fa-box me-2" style="color:var(--primary);"></i>Produits de <?php echo htmlspecialchars($vendeur['nom_boutique']); ?></h2>
        <span class="products-count"><?php echo $totalProds; ?> produit(s)</span>
    </div>

    <?php if (count($produits) > 0): ?>
        <div class="products-grid">
            <?php foreach ($produits as $prod): 
                $imagePath = 'back-end/apps/' . htmlspecialchars($prod['image']);
                if (!file_exists($imagePath)) $imagePath = 'back-end/apps/Blue.jpg';
            ?>
                <a href="product_detail.php?nom_article=<?php echo urlencode($prod['nom_article']); ?>" class="product-card">
                    <div class="product-image">
                        <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($prod['nom_article']); ?>" loading="lazy">
                    </div>
                    <div class="product-body">
                        <div class="product-cat"><?php echo htmlspecialchars($prod['nom_categorie'] ?? ''); ?></div>
                        <div class="product-name"><?php echo htmlspecialchars($prod['nom_article']); ?></div>
                        <div class="product-footer">
                            <span class="product-price <?php echo $prod['prix'] == 0 ? 'free' : ''; ?>">
                                <?php echo $prod['prix'] == 0 ? 'Gratuit' : number_format($prod['prix_reduction'] > 0 ? $prod['prix_reduction'] : $prod['prix'], 0, ',', ' ') . ' CFA'; ?>
                            </span>
                            <i class="fa-solid fa-eye" style="color:var(--primary);"></i>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

  <!-- Pagination -->
<?php if ($pagesProd > 1): 
    $qs = 'boutique=' . urlencode($boutique);
?>
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?php echo $page-1 . '&' . $qs; ?>" class="page-link"><i class="fa-solid fa-chevron-left"></i></a>
    <?php endif; ?>
    
    <?php
    // Toujours afficher page 1
    if ($page > 3): ?>
        <a href="?page=1&<?php echo $qs; ?>" class="page-link">1</a>
        <?php if ($page > 4): ?>
            <span class="page-link disabled">...</span>
        <?php endif; ?>
    <?php endif; ?>
    
    <?php
    $start = max(1, $page - 2);
    $end = min($pagesProd, $page + 2);
    for ($i = $start; $i <= $end; $i++): ?>
        <a href="?page=<?php echo $i . '&' . $qs; ?>" class="page-link <?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>
    
    <?php if ($page < $pagesProd - 2): ?>
        <?php if ($page < $pagesProd - 3): ?>
            <span class="page-link disabled">...</span>
        <?php endif; ?>
        <a href="?page=<?php echo $pagesProd . '&' . $qs; ?>" class="page-link"><?php echo $pagesProd; ?></a>
    <?php endif; ?>
    
    <?php if ($page < $pagesProd): ?>
        <a href="?page=<?php echo $page+1 . '&' . $qs; ?>" class="page-link"><i class="fa-solid fa-chevron-right"></i></a>
    <?php endif; ?>
</div>
<?php endif; ?>


    <?php else: ?>
        <div style="text-align:center; padding:50px; background:white; border-radius:16px; border:1px solid var(--border);">
            <i class="fa-solid fa-box-open" style="font-size:50px; color:#d1d5db; margin-bottom:15px;"></i>
            <h3 style="color:var(--text-light);">Aucun produit pour le moment</h3>
        </div>
    <?php endif; ?>
</div>


<style>
/* Pagination responsive */
.pagination { 
    display: flex; 
    justify-content: center; 
    align-items: center;
    gap: 6px; 
    flex-wrap: nowrap;
    overflow-x: auto;
    padding: 5px 0;
}

.page-link {
    padding: 10px 16px; 
    border-radius: 10px;
    background: white; 
    border: 1px solid var(--border);
    text-decoration: none; 
    color: var(--text); 
    font-weight: 500; 
    transition: all 0.2s;
    white-space: nowrap;
    flex-shrink: 0;
    min-width: 42px;
    text-align: center;
}

.page-link.active { 
    background: var(--primary); 
    color: white; 
    border-color: var(--primary); 
}

.page-link:hover:not(.active):not(.disabled) { 
    background: var(--primary-light); 
    color: var(--primary); 
}

.page-link.disabled {
    opacity: 0.5;
    pointer-events: none;
    cursor: not-allowed;
}

/* Sur mobile, réduire la taille */
@media (max-width: 480px) {
    .page-link {
        padding: 8px 10px;
        font-size: 13px;
        min-width: 32px;
    }
}

    </style>
<?php require('footer.php'); ?>
</body>
</html>