<?php
require('header.php');

// Vérifier si l'utilisateur est connecté et est un vendeur accepté
if (!isset($_SESSION['user_id'])) {
    echo '<meta http-equiv="refresh" content="0;URL=login">';
    exit;
}
// Récupérer id_uti
$stmt = $database->prepare("SELECT id_uti FROM utilisateur WHERE email = ?");
$stmt->execute([$_SESSION['email']]);
$user = $stmt->fetch();
if (!$user) { exit; }
$id_uti = $user['id_uti'];

// Vérifier statut vendeur
$checkVendeur = $database->prepare("SELECT id FROM demandes_vendeur WHERE id_uti = ? AND statut = 'acceptee'");
$checkVendeur->execute([$id_uti]);
if (!$checkVendeur->fetch()) {
    echo '<script>alert("Vous devez être un vendeur accepté pour accéder à cette page."); window.location.href="compte.php";</script>';
    exit;
}

// Pagination, filtre et recherche
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

$statut_filter = isset($_GET['statut']) ? $_GET['statut'] : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$conditions = [];
$params = [];

if (!empty($statut_filter) && in_array($statut_filter, ['en_attente','approuve','refuse'])) {
    $conditions[] = "p.statut = :statut";
    $params[':statut'] = $statut_filter;
}

if (!empty($search)) {
    $conditions[] = "p.nom_article LIKE :search";
    $params[':search'] = '%' . $search . '%';
}

$where = '';
if (!empty($conditions)) {
    $where = ' AND ' . implode(' AND ', $conditions);
}

// Compter total
$countSql = "SELECT COUNT(*) FROM produits p WHERE p.id_vendeur = :id_uti" . $where;
$stmtCount = $database->prepare($countSql);
$stmtCount->bindValue(':id_uti', $id_uti, PDO::PARAM_INT);
foreach ($params as $k => $v) {
    $stmtCount->bindValue($k, $v);
}
$stmtCount->execute();
$total = $stmtCount->fetchColumn();
$pages = ceil($total / $perPage);

// Récupérer produits (avec commentaire)
$sql = "SELECT p.*, c.nom_categorie FROM produits p LEFT JOIN categories c ON p.categorie_id = c.id WHERE p.id_vendeur = :id_uti" . $where . " ORDER BY p.date_ajout DESC LIMIT :offset, :limit";
$stmtProd = $database->prepare($sql);
$stmtProd->bindValue(':id_uti', $id_uti, PDO::PARAM_INT);
foreach ($params as $k => $v) {
    $stmtProd->bindValue($k, $v);
}
$stmtProd->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmtProd->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmtProd->execute();
$produits = $stmtProd->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes produits - NDIGITMARKET</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #087d67;
            --border: #e5e7eb;
            --text: #374151;
        }
        body { font-family: 'Inter', sans-serif; background: #f9fafb; color: var(--text); }
        .container-custom { max-width: 1200px; margin: 0 auto; padding: 30px 20px; }
        .card { background: white; border-radius: 16px; box-shadow: 0 10px 30px -10px rgba(0,0,0,0.1); border: 1px solid var(--border); padding: 20px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 12px; border-bottom: 1px solid var(--border); vertical-align: middle; font-size: 14px; }
        .table th { background: #f9fafb; font-weight: 600; }
        .badge-statut { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block; }
        .motif-refus { font-size: 12px; color: #991b1b; margin-top: 4px; font-style: italic; background: #fee2e2; padding: 4px 8px; border-radius: 6px; }
        .pagination { display: flex; flex-wrap: wrap; justify-content: center; gap: 6px; margin-top: 25px; }
        .page-link { padding: 10px 16px; border-radius: 10px; background: white; border: 1px solid var(--border); text-decoration: none; color: var(--text); font-weight: 500; transition: all 0.2s; min-width: 40px; text-align: center; }
        .page-link.active { background: var(--primary); color: white; border-color: var(--primary); }
        .page-link:hover { background: #e8f5f2; color: var(--primary); }
        .page-link.disabled { opacity: 0.5; pointer-events: none; }
        .btn-action { padding: 8px 14px; border-radius: 8px; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: 0.2s; border: none; cursor: pointer; }
        .btn-modifier { color: var(--primary); background: #e8f5f2; }
        .btn-modifier:hover { background: var(--primary); color: white; }
        .btn-supprimer { color: #ef4444; background: #fee2e2; }
        .btn-supprimer:hover { background: #ef4444; color: white; }
        .btn-telecharger { background: #e8f5f2; color: var(--primary); }
        .btn-telecharger:hover { background: var(--primary); color: white; }
        .search-box {
            display: flex;
            align-items: center;
            background: #f9fafb;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 8px 16px;
            transition: 0.2s;
        }
        .search-box:focus-within { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(8,125,103,0.1); }
        .search-box input {
            border: none;
            background: transparent;
            outline: none;
            flex: 1;
            padding: 6px;
            font-size: 14px;
        }
        .search-box button {
            background: none;
            border: none;
            color: var(--primary);
            cursor: pointer;
            font-size: 16px;
        }
        @media (max-width: 600px) {
            .page-link { padding: 8px 10px; font-size: 13px; }
            .page-link.ellipsis, .page-link.extra { display: none; }
            .table th, .table td { padding: 8px 6px; font-size: 12px; }
        }
    </style>
</head>
<body>

<!-- Breadcrumb -->
<section class="breadcrumb-modern" style="background: linear-gradient(135deg, #0f1923 0%, #1a2634 100%); padding: 30px 0; margin-bottom: 30px; position: relative;">
    <div class="container-custom" style="max-width:1400px; margin:0 auto; padding:0 20px;">
        <h1 style="color:white; font-size:28px; font-weight:700;">Mes produits</h1>
        <div class="breadcrumb-links" style="display:flex; gap:10px; color:rgba(255,255,255,0.6);">
            <a href="index.php" style="color:rgba(255,255,255,0.8); text-decoration:none;"><i class="fa-solid fa-house"></i> Accueil</a>
            <i class="fa-solid fa-chevron-right"></i>
            <a href="compte.php" style="color:rgba(255,255,255,0.8); text-decoration:none;">Compte</a>
            <i class="fa-solid fa-chevron-right"></i>
            <span>Mes produits</span>
        </div>
    </div>
</section>

<div class="container-custom">
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 20px;">
            <h2 style="font-size:22px; font-weight:700;">Gérer mes produits</h2>
            <a href="ajouter_produit.php" class="btn btn-primary" style="background: var(--primary); border:none; padding:10px 20px; border-radius:12px; color:white; text-decoration:none; display:inline-flex; align-items:center; gap:8px;">
                <i class="fa-solid fa-plus"></i> Ajouter un produit
            </a>
        </div>

        <!-- Filtre + Recherche -->
        <form method="get" class="row g-3 mb-4" id="filterForm">
            <div class="col-md-3">
                <select name="statut" class="form-select" style="border-radius:12px;" onchange="this.form.submit()">
                    <option value="">Tous les statuts</option>
                    <option value="en_attente" <?php echo $statut_filter == 'en_attente' ? 'selected' : ''; ?>>En attente</option>
                    <option value="approuve" <?php echo $statut_filter == 'approuve' ? 'selected' : ''; ?>>Approuvé</option>
                    <option value="refuse" <?php echo $statut_filter == 'refuse' ? 'selected' : ''; ?>>Refusé</option>
                </select>
            </div>
            <div class="col-md-6">
                <div class="search-box">
                    <input type="text" name="search" placeholder="Rechercher un produit par nom..." value="<?php echo htmlspecialchars($search); ?>" id="searchInput">
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                    <?php if (!empty($search)): ?>
                        <a href="liste_produits.php" class="btn btn-sm btn-outline-secondary ms-2" style="border-radius:8px; text-decoration:none;">Réinitialiser</a>
                    <?php endif; ?>
                </div>
            </div>
        </form>

        <!-- Recherche automatique -->
        <script>
        let typingTimer;
        const searchInput = document.getElementById('searchInput');
        const filterForm = document.getElementById('filterForm');
        searchInput.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(function() { filterForm.submit(); }, 600);
        });
        </script>

        <?php if (empty($produits)): ?>
            <div style="text-align:center; padding:40px; color:#6b7280;">
                <i class="fa-solid fa-box-open" style="font-size:48px; margin-bottom:15px;"></i>
                <h4>Aucun produit trouvé</h4>
                <?php if (!empty($search) || !empty($statut_filter)): ?>
                    <p>Essayez de modifier vos critères de recherche ou de filtre.</p>
                    <a href="liste_produits.php" class="btn btn-outline-primary mt-3" style="border-radius:12px;">Voir tous les produits</a>
                <?php else: ?>
                    <p>Commencez par ajouter un produit à vendre.</p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p class="text-muted mb-3"><?php echo $total; ?> produit(s) trouvé(s)</p>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Nom</th>
                            <th>Catégorie</th>
                            <th>Prix (CFA)</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produits as $prod): 
                            $imagePath = 'back-end/apps/' . htmlspecialchars($prod['image']);
                            if (!file_exists($imagePath)) $imagePath = 'back-end/apps/Blue.jpg';
                        ?>
                        <tr>
                            <td><img src="<?php echo $imagePath; ?>" alt="" style="width:50px; height:50px; object-fit:cover; border-radius:8px;"></td>
                            <td><strong><?php echo htmlspecialchars($prod['nom_article']); ?></strong></td>
                            <td><?php echo htmlspecialchars($prod['nom_categorie'] ?? 'N/A'); ?></td>
                            <td><?php echo number_format($prod['prix'], 0, ',', ' '); ?> CFA</td>
                            <td>
                                <?php 
                                switch ($prod['statut']) {
                                    case 'en_attente': 
                                        $badgeStyle = 'background:#fef3c7; color:#92400e;'; 
                                        $statutText = '⏳ En attente'; 
                                        break;
                                    case 'approuve': 
                                        $badgeStyle = 'background:#d1fae5; color:#065f46;'; 
                                        $statutText = '✅ Approuvé'; 
                                        break;
                                    case 'refuse': 
                                        $badgeStyle = 'background:#fee2e2; color:#991b1b;'; 
                                        $statutText = '❌ Refusé'; 
                                        break;
                                    default: 
                                        $badgeStyle = ''; 
                                        $statutText = $prod['statut'];
                                }
                                ?>
                                <span class="badge-statut" style="<?php echo $badgeStyle; ?>"><?php echo $statutText; ?></span>
                                <?php if ($prod['statut'] === 'refuse' && !empty($prod['commentaire'])): ?>
                                    <div class="motif-refus">
                                        <i class="fa-solid fa-circle-info"></i> <?php echo htmlspecialchars($prod['commentaire']); ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td style="white-space: nowrap;">
                                <a href="modifier_produit.php?id=<?php echo $prod['id']; ?>" class="btn-action btn-modifier"><i class="fa-solid fa-pen-to-square"></i> Modifier</a>
                                <button onclick="confirmDelete(<?php echo $prod['id']; ?>, '<?php echo addslashes($prod['nom_article']); ?>')" class="btn-action btn-supprimer"><i class="fa-solid fa-trash"></i> Supprimer</button>
                                <a href="back-end/apps/<?php echo htmlspecialchars($prod['fichier']); ?>" class="btn-action btn-telecharger" download><i class="fa-solid fa-download"></i> Télécharger</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($pages > 1): ?>
            <div class="pagination">
                <?php
                $queryString = '';
                if (!empty($statut_filter)) $queryString .= '&statut=' . urlencode($statut_filter);
                if (!empty($search)) $queryString .= '&search=' . urlencode($search);

                if ($page > 1) {
                    echo '<a href="?page='.($page-1).$queryString.'" class="page-link"><i class="fa-solid fa-chevron-left"></i></a>';
                }

                $max_pages_to_show = 5;
                $start = max(1, $page - 2);
                $end = min($pages, $start + $max_pages_to_show - 1);
                if ($end - $start + 1 < $max_pages_to_show) {
                    $start = max(1, $end - $max_pages_to_show + 1);
                }

                if ($start > 1) {
                    echo '<a href="?page=1'.$queryString.'" class="page-link">1</a>';
                    if ($start > 2) echo '<span class="page-link disabled ellipsis">...</span>';
                }

                for ($i = $start; $i <= $end; $i++): ?>
                    <a href="?page=<?php echo $i.$queryString; ?>" class="page-link <?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
                <?php endfor;

                if ($end < $pages) {
                    if ($end < $pages - 1) echo '<span class="page-link disabled ellipsis">...</span>';
                    echo '<a href="?page='.$pages.$queryString.'" class="page-link">'.$pages.'</a>';
                }

                if ($page < $pages) {
                    echo '<a href="?page='.($page+1).$queryString.'" class="page-link"><i class="fa-solid fa-chevron-right"></i></a>';
                }
                ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(productId, productName) {
    Swal.fire({
        title: 'Confirmer la suppression',
        html: 'Voulez-vous vraiment supprimer <strong>' + productName + '</strong> ?<br><small class="text-danger">Cette action est irréversible.</small>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'supprimer_produit.php?id=' + productId;
        }
    });
}
</script>

<?php require('footer.php'); ?>
</body>
</html>