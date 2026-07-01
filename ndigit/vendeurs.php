<?php
require('header.php');

// Filtre par catégorie
$categorie_filter = isset($_GET['categorie']) ? trim($_GET['categorie']) : '';

// Pagination
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 12;
$offset = ($page - 1) * $perPage;

// Conditions
$where = "WHERE dv.statut = 'acceptee'";
$params = [];

if (!empty($categorie_filter)) {
    $where .= " AND dv.categorie = :categorie";
    $params[':categorie'] = $categorie_filter;
}

// Compter
$countSql = "SELECT COUNT(*) FROM demandes_vendeur dv $where";
$stmtCount = $database->prepare($countSql);
$stmtCount->execute($params);
$total = $stmtCount->fetchColumn();
$pages = ceil($total / $perPage);

// Récupérer les vendeurs
$sql = "SELECT dv.id_uti, dv.nom_boutique, dv.categorie, dv.date_demande,
               COUNT(p.id) AS nb_produits
        FROM demandes_vendeur dv
        LEFT JOIN produits p ON dv.id_uti = p.id_vendeur AND p.statut = 'approuve'
        $where
        GROUP BY dv.id_uti
        ORDER BY nb_produits DESC, dv.date_demande DESC
        LIMIT :limit OFFSET :offset";
$stmt = $database->prepare($sql);
foreach ($params as $k => $v) $stmt->bindValue($k, $v);
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$vendeurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer toutes les catégories distinctes pour le filtre
$cats = $database->query("SELECT DISTINCT categorie FROM demandes_vendeur WHERE statut = 'acceptee' ORDER BY categorie")->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos vendeurs - NDIGITMARKET</title>
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
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); }
        
        .breadcrumb-modern {
            background: linear-gradient(135deg, var(--dark) 0%, #1a2634 100%);
            padding: 40px 0;
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
        }
        .breadcrumb-modern::before {
            content: ''; position: absolute; top: -50px; right: -50px;
            width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(8,125,103,0.2) 0%, transparent 70%);
            border-radius: 50%;
        }
        .breadcrumb-content { position: relative; z-index: 2; max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .breadcrumb-content h1 { color: white; font-size: 36px; font-weight: 800; }
        .breadcrumb-links { display: flex; align-items: center; gap: 10px; color: rgba(255,255,255,0.6); margin-top: 10px; font-size: 14px; }
        .breadcrumb-links a { color: rgba(255,255,255,0.8); text-decoration: none; }
        .breadcrumb-links a:hover { color: white; }
        
        .container-custom { max-width: 1200px; margin: 0 auto; padding: 0 20px 60px; }
        
        .section-header { text-align: center; margin-bottom: 30px; }
        .section-label { display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: var(--primary); margin-bottom: 10px; }
        .section-label::before { content: ''; width: 20px; height: 3px; background: var(--primary); border-radius: 2px; }
        .section-title { font-size: 28px; font-weight: 800; color: var(--dark); }
        .section-subtitle { color: var(--text-light); font-size: 15px; margin-top: 8px; }
        
        /* Filtre */
        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-bottom: 30px;
        }
        .filter-btn {
            padding: 10px 20px;
            border-radius: 30px;
            border: 1px solid var(--border);
            background: white;
            color: var(--text);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }
        .filter-btn:hover, .filter-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .vendeurs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .vendeur-card {
            background: white;
            border-radius: var(--radius);
            padding: 25px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            transition: all 0.3s;
            text-decoration: none;
            color: var(--text);
            text-align: center;
        }
        .vendeur-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(8,125,103,0.15);
            border-color: var(--primary);
        }
        
        .vendeur-icon {
            width: 70px; height: 70px;
            background: var(--primary-light);
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 15px;
            font-size: 32px; color: var(--primary);
        }
        .vendeur-boutique {
            font-size: 18px; font-weight: 700; color: var(--dark);
            margin-bottom: 8px;
        }
        .vendeur-stats {
            display: flex; justify-content: center; gap: 15px;
            font-size: 13px; color: var(--text-light);
            margin-top: 10px;
            padding-top: 15px;
            border-top: 1px solid var(--border);
        }
        .vendeur-stats i { color: var(--primary); margin-right: 5px; }
        .badge-cat {
            display: inline-block;
            background: var(--accent);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            margin-top: 8px;
        }
        
        /* Pagination */
        .pagination { display: flex; justify-content: center; gap: 8px; flex-wrap: wrap; }
        .page-link {
            padding: 10px 18px; border-radius: 10px;
            background: white; border: 1px solid var(--border);
            text-decoration: none; color: var(--text);
            font-weight: 500; transition: all 0.2s;
        }
        .page-link.active { background: var(--primary); color: white; border-color: var(--primary); }
        .page-link:hover:not(.active) { background: var(--primary-light); color: var(--primary); }
        
        @media (max-width: 768px) { .vendeurs-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<section class="breadcrumb-modern">
    <div class="breadcrumb-content">
        <h1>Nos vendeurs</h1>
        <div class="breadcrumb-links">
            <a href="index.php"><i class="fa-solid fa-house"></i> Accueil</a>
            <i class="fa-solid fa-chevron-right"></i>
            <span>Vendeurs</span>
        </div>
    </div>
</section>

<div class="container-custom">
    <div class="section-header">
        <div class="section-label">🏪 Communauté</div>
        <h2 class="section-title">Découvrez nos vendeurs</h2>
        <p class="section-subtitle">Des créateurs passionnés qui partagent leurs meilleurs templates</p>
    </div>

 
    <div class="vendeurs-grid">
        <?php foreach ($vendeurs as $v): ?>
            <a href="profil_vendeur.php?boutique=<?php echo urlencode($v['nom_boutique']); ?>" class="vendeur-card">
                <div class="vendeur-icon"><i class="fa-solid fa-store"></i></div>
                <div class="vendeur-boutique"><?php echo htmlspecialchars($v['nom_boutique']); ?></div>
              
                <div class="vendeur-stats">
                    <span><i class="fa-solid fa-box"></i> <strong><?php echo $v['nb_produits']; ?></strong> produit(s)</span>
                </div>
            </a>
        <?php endforeach; ?>
        
        <?php if (empty($vendeurs)): ?>
            <div style="text-align:center; padding:60px; grid-column:1/-1;">
                <i class="fa-solid fa-store-slash" style="font-size:60px; color:#d1d5db; margin-bottom:15px;"></i>
                <h3 style="color:var(--text-light);">Aucun vendeur trouvé</h3>
            </div>
        <?php endif; ?>
    </div>

   <!-- Pagination -->
<?php if ($pages > 1): 
    $qs = !empty($categorie_filter) ? '&categorie=' . urlencode($categorie_filter) : '';
?>
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?php echo $page-1 . $qs; ?>" class="page-link"><i class="fa-solid fa-chevron-left"></i></a>
    <?php endif; ?>
    
    <?php
    // Toujours afficher page 1
    if ($page > 3): ?>
        <a href="?page=1<?php echo $qs; ?>" class="page-link">1</a>
        <?php if ($page > 4): ?>
            <span class="page-link disabled">...</span>
        <?php endif; ?>
    <?php endif; ?>
    
    <?php
    $start = max(1, $page - 2);
    $end = min($pages, $page + 2);
    for ($i = $start; $i <= $end; $i++): ?>
        <a href="?page=<?php echo $i . $qs; ?>" class="page-link <?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>
    
    <?php if ($page < $pages - 2): ?>
        <?php if ($page < $pages - 3): ?>
            <span class="page-link disabled">...</span>
        <?php endif; ?>
        <a href="?page=<?php echo $pages . $qs; ?>" class="page-link"><?php echo $pages; ?></a>
    <?php endif; ?>
    
    <?php if ($page < $pages): ?>
        <a href="?page=<?php echo $page+1 . $qs; ?>" class="page-link"><i class="fa-solid fa-chevron-right"></i></a>
    <?php endif; ?>
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