<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion des produits - NDIGITMARKET</title>
</head>
<body>
<?php 
$pageTitle = 'Gestion des produits';
require('header.php'); 
require('../../include/connect.php');

// Paramètres
$produitsParPage = 12;
$pageActuelle = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($pageActuelle - 1) * $produitsParPage;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$statut_filter = isset($_GET['statut']) ? $_GET['statut'] : '';
$vendeur_filter = isset($_GET['vendeur']) ? $_GET['vendeur'] : '';

$conditions = [];
$params = [];

if (!empty($search)) {
    $conditions[] = "p.nom_article LIKE :search";
    $params[':search'] = '%' . $search . '%';
}
if (!empty($statut_filter) && in_array($statut_filter, ['en_attente', 'approuve', 'refuse'])) {
    $conditions[] = "p.statut = :statut";
    $params[':statut'] = $statut_filter;
}
if ($vendeur_filter === 'admin') {
    $conditions[] = "p.id_vendeur = 1";
} elseif ($vendeur_filter === 'vendeurs') {
    $conditions[] = "p.id_vendeur != 1";
}

$where = '';
if (!empty($conditions)) {
    $where = ' WHERE ' . implode(' AND ', $conditions);
}

$queryCount = "SELECT COUNT(*) FROM produits p" . $where;
$stmtCount = $database->prepare($queryCount);
foreach ($params as $k => $v) $stmtCount->bindValue($k, $v);
$stmtCount->execute();
$totalProduits = $stmtCount->fetchColumn();
$totalPages = ceil($totalProduits / $produitsParPage);

$query = "SELECT p.*, c.nom_categorie, c.sous_categories AS cat_sous_categories,
          dv.nom_boutique AS vendeur_boutique,
          u.nom AS vendeur_nom, u.prenom AS vendeur_prenom
          FROM produits p
          LEFT JOIN categories c ON p.categorie_id = c.id
          LEFT JOIN demandes_vendeur dv ON p.id_vendeur = dv.id_uti AND dv.statut = 'acceptee'
          LEFT JOIN utilisateur u ON p.id_vendeur = u.id_uti
          " . $where . "
          ORDER BY p.date_ajout DESC
          LIMIT :limit OFFSET :offset";

$stmt = $database->prepare($query);
foreach ($params as $k => $v) $stmt->bindValue($k, $v);
$stmt->bindValue(':limit', $produitsParPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <div class="title-header option-title d-sm-flex d-block mb-4">
                            <div>
                                <h5 style="font-size:22px; font-weight:700; color:#1a2634;">📦 Liste des produits</h5>
                                <p class="text-muted mb-0"><?php echo $totalProduits; ?> produit(s) trouvé(s)</p>
                            </div>
                            <div>
                                <a class="btn btn-solid" href="add_product">
                                    <i class="fa-regular fa-plus"></i> Ajouter un produit
                                </a>
                            </div>
                        </div>

                        <!-- Barre de recherche et filtres -->
                        <form method="GET" action="" class="search-section mb-4">
                            <div class="row g-3 align-items-end">
                                <div class="col-lg-4 col-md-6">
                                    <label class="form-label fw-bold mb-2 text-dark">🔍 Recherche</label>
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" placeholder="Nom du produit..." 
                                               value="<?php echo htmlspecialchars($search); ?>"
                                               style="border-radius:10px 0 0 10px;">
                                        <button class="btn btn-primary" type="submit" style="border-radius:0 10px 10px 0;">
                                            <i class="fa-regular fa-magnifying-glass"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-4 col-6">
                                    <label class="form-label fw-bold mb-2 text-dark">📊 Statut</label>
                                    <select name="statut" class="form-select" onchange="this.form.submit()" style="border-radius:10px;">
                                        <option value="">Tous les statuts</option>
                                        <option value="en_attente" <?php echo $statut_filter == 'en_attente' ? 'selected' : ''; ?>>⏳ En attente</option>
                                        <option value="approuve" <?php echo $statut_filter == 'approuve' ? 'selected' : ''; ?>>✅ Approuvé</option>
                                        <option value="refuse" <?php echo $statut_filter == 'refuse' ? 'selected' : ''; ?>>❌ Refusé</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-4 col-6">
                                    <label class="form-label fw-bold mb-2 text-dark">🏪 Vendeur</label>
                                    <select name="vendeur" class="form-select" onchange="this.form.submit()" style="border-radius:10px;">
                                        <option value="">Tous les vendeurs</option>
                                        <option value="admin" <?php echo $vendeur_filter == 'admin' ? 'selected' : ''; ?>>Admin (NDIGITMARKET)</option>
                                        <option value="vendeurs" <?php echo $vendeur_filter == 'vendeurs' ? 'selected' : ''; ?>>Vendeurs tiers</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-4">
                                    <?php if (!empty($search) || !empty($statut_filter) || !empty($vendeur_filter)): ?>
                                        <a href="produits" class="btn btn-outline-secondary w-100" style="border-radius:10px;">
                                            <i class="fa-solid fa-rotate"></i> Réinitialiser
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </form>

                        <!-- Table des produits -->
                        <div class="table-responsive">
                            <table class="table all-package theme-table table-product">
                                <thead>
                                    <tr>
                                        <th style="width:50px;">Img</th>
                                        <th>Produit</th>
                                        <th>Cat.</th>
                                        <th>Sous-catégories</th>
                                        <th>Vendeur</th>
                                        <th>Prix</th>
                                        <th>Statut</th>
                                        <th style="width:130px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($produits)): ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <i class="fa-regular fa-box-open" style="font-size: 50px; color: #d1d5db;"></i>
                                                <p class="mt-3 text-muted">Aucun produit trouvé</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>

                                    <?php foreach ($produits as $produit): 
                                        $imgSrc = '../../back-end/apps/' . htmlspecialchars($produit['image']);
                                        if (!file_exists($imgSrc)) $imgSrc = '../../back-end/apps/Blue.jpg';
                                        
                                        if ($produit['id_vendeur'] == 1) {
                                            $vendeurAffichage = '🏪 NDIGITMARKET';
                                        } elseif (!empty($produit['vendeur_boutique'])) {
                                            $vendeurAffichage = htmlspecialchars($produit['vendeur_boutique']);
                                        } else {
                                            $vendeurAffichage = htmlspecialchars(($produit['vendeur_prenom'] ?? '') . ' ' . ($produit['vendeur_nom'] ?? 'Inconnu'));
                                        }
                                        
                                        $statutBadge = ''; $statutIcon = ''; $statutText = '';
                                        switch ($produit['statut'] ?? 'en_attente') {
                                            case 'en_attente': $statutBadge = 'background:#fef3c7; color:#92400e;'; $statutIcon = '⏳'; $statutText = 'En attente'; break;
                                            case 'approuve': $statutBadge = 'background:#d1fae5; color:#065f46;'; $statutIcon = '✅'; $statutText = 'Approuvé'; break;
                                            case 'refuse': $statutBadge = 'background:#fee2e2; color:#991b1b;'; $statutIcon = '❌'; $statutText = 'Refusé'; break;
                                            default: $statutBadge = 'background:#f3f4f6; color:#374151;'; $statutIcon = ''; $statutText = $produit['statut'];
                                        }
                                        
                                        // Sous-catégories actuelles
                                        $currentSousCats = array_map('trim', explode(',', $produit['sous_categorie'] ?? ''));
                                        $catSousCats = !empty($produit['cat_sous_categories']) ? array_map('trim', explode(',', $produit['cat_sous_categories'])) : [];
                                    ?>
                                        <tr>
                                            <td>
                                                <div class="table-image">
                                                    <img src="<?php echo $imgSrc; ?>" alt="" onerror="this.src='assets/images/placeholder.jpg'">
                                                </div>
                                            </td>
                                            <td>
                                                <strong style="font-size:13px;"><?php echo htmlspecialchars(substr($produit['nom_article'], 0, 30)) . (strlen($produit['nom_article']) > 30 ? '...' : ''); ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge" style="background:#e8f5f2; color:#087d67; font-size:11px;">
                                                    <?php echo htmlspecialchars($produit['nom_categorie'] ?? 'N/A'); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <!-- Sélecteur MULTIPLE de sous-catégories -->
                                                <div class="multiselect-wrapper" style="position:relative;">
                                                    <div class="multiselect-display" 
                                                         onclick="this.nextElementSibling.classList.toggle('show')"
                                                         style="border:1px solid #e5e7eb; border-radius:8px; padding:6px 10px; cursor:pointer; font-size:12px; min-width:140px; background:white;">
                                                        <span class="selected-text">
                                                            <?php echo !empty($currentSousCats) ? htmlspecialchars(implode(', ', $currentSousCats)) : '-- Aucune --'; ?>
                                                        </span>
                                                        <i class="fa-solid fa-chevron-down" style="float:right; margin-top:3px; font-size:10px; color:#9ca3af;"></i>
                                                    </div>
                                                    <div class="multiselect-dropdown" 
                                                         style="display:none; position:absolute; z-index:10; background:white; border:1px solid #e5e7eb; border-radius:8px; padding:8px; max-height:200px; overflow-y:auto; box-shadow:0 10px 30px rgba(0,0,0,0.1); min-width:200px;">
                                                        <?php foreach ($catSousCats as $sc): 
                                                            $sc = trim($sc);
                                                            if (empty($sc)) continue;
                                                            $checked = in_array($sc, $currentSousCats) ? 'checked' : '';
                                                        ?>
                                                            <label style="display:flex; align-items:center; gap:8px; padding:5px 8px; border-radius:6px; cursor:pointer; font-size:12px; margin:0; transition:0.15s;"
                                                                   onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background=''">
                                                                <input type="checkbox" value="<?php echo htmlspecialchars($sc); ?>" 
                                                                       <?php echo $checked; ?>
                                                                       onchange="updateMultiSelect(this)" 
                                                                       data-product-id="<?php echo $produit['id']; ?>"
                                                                       style="accent-color:#087d67;">
                                                                <?php echo htmlspecialchars($sc); ?>
                                                            </label>
                                                        <?php endforeach; ?>
                                                        <?php if (empty($catSousCats)): ?>
                                                            <span style="font-size:11px; color:#9ca3af; padding:5px 8px;">Aucune sous-catégorie</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span style="font-size:12px;"><?php echo $vendeurAffichage; ?></span></td>
                                            <td>
                                                <?php if ($produit['prix'] == 0): ?>
                                                    <span class="badge" style="background:#d1fae5; color:#10b981; font-size:11px;">Gratuit</span>
                                                <?php else: ?>
                                                    <strong style="font-size:13px;"><?php echo number_format($produit['prix'], 0, ',', ' '); ?> CFA</strong>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge" style="<?php echo $statutBadge; ?> font-size:11px; padding:5px 10px;">
                                                    <?php echo $statutIcon . ' ' . $statutText; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="approuver_produit.php?id=<?php echo $produit['id']; ?>" class="btn-view" title="Valider"
                                                       style="background:<?php echo ($produit['statut'] ?? '') === 'en_attente' ? '#fef3c7' : '#f3f4f6'; ?>; color:<?php echo ($produit['statut'] ?? '') === 'en_attente' ? '#92400e' : '#6b7280'; ?>;">
                                                        <i class="fa-solid fa-gavel"></i>
                                                    </a>
                                                    <?php $encodedId = base64_encode($produit['id']); ?>
                                                    <a href="modifie_product.php?id=<?= $encodedId; ?>" class="btn-edit" title="Modifier">
                                                        <i class="fa-regular fa-pen-to-square"></i>
                                                    </a>
                                                    <a href="javascript:void(0)" onclick="confirmDelete(<?= $produit['id']; ?>)" class="btn-delete" title="Supprimer">
                                                        <i class="fa-regular fa-trash-can"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if ($totalPages > 1): 
                            $queryParams = '';
                            if (!empty($search)) $queryParams .= '&search=' . urlencode($search);
                            if (!empty($statut_filter)) $queryParams .= '&statut=' . urlencode($statut_filter);
                            if (!empty($vendeur_filter)) $queryParams .= '&vendeur=' . urlencode($vendeur_filter);
                        ?>
                            <div class="pagination-section">
                                <nav>
                                    <ul class="pagination">
                                        <?php if ($pageActuelle > 1): ?>
                                            <li class="page-item"><a class="page-link" href="?page=<?php echo $pageActuelle - 1 . $queryParams; ?>"><i class="fa-regular fa-chevron-left"></i></a></li>
                                        <?php endif; ?>
                                        <?php
                                        $startPage = max(1, $pageActuelle - 2);
                                        $endPage = min($totalPages, $pageActuelle + 2);
                                        if ($startPage > 1): ?>
                                            <li class="page-item"><a class="page-link" href="?page=1<?php echo $queryParams; ?>">1</a></li>
                                            <?php if ($startPage > 2): ?><li class="page-item disabled"><span class="page-link">...</span></li><?php endif; ?>
                                        <?php endif; ?>
                                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                            <li class="page-item <?php echo ($i == $pageActuelle) ? 'active' : ''; ?>"><a class="page-link" href="?page=<?php echo $i . $queryParams; ?>"><?php echo $i; ?></a></li>
                                        <?php endfor; ?>
                                        <?php if ($endPage < $totalPages): ?>
                                            <?php if ($endPage < $totalPages - 1): ?><li class="page-item disabled"><span class="page-link">...</span></li><?php endif; ?>
                                            <li class="page-item"><a class="page-link" href="?page=<?php echo $totalPages . $queryParams; ?>"><?php echo $totalPages; ?></a></li>
                                        <?php endif; ?>
                                        <?php if ($pageActuelle < $totalPages): ?>
                                            <li class="page-item"><a class="page-link" href="?page=<?php echo $pageActuelle + 1 . $queryParams; ?>"><i class="fa-regular fa-chevron-right"></i></a></li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .search-section { background: #ffffff; padding: 20px; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 2px 10px rgba(0,0,0,0.03); }
    .form-select { border-radius: 10px !important; padding: 10px 15px; border: 1px solid #e5e7eb; cursor: pointer; }
    .table-product { margin-top: 20px; }
    .table-product thead th { background: #f8f9fa; padding: 12px 10px; font-weight: 600; font-size: 12px; color: #374151; border-bottom: 2px solid #e5e7eb; text-transform: uppercase; letter-spacing: 0.5px; }
    .table-product tbody td { padding: 10px; vertical-align: middle; border-bottom: 1px solid #f3f4f6; font-size: 13px; }
    .table-product tbody tr:hover { background: #f9fafb; }
    .table-image { width: 45px; height: 45px; border-radius: 8px; overflow: hidden; border: 1px solid #e5e7eb; background: #f9fafb; }
    .table-image img { width: 100%; height: 100%; object-fit: cover; }
    .action-buttons { display: flex; gap: 5px; align-items: center; }
    .btn-view, .btn-edit, .btn-delete { width: 30px; height: 30px; border-radius: 7px; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.2s; font-size: 13px; }
    .btn-view { background: #e8f5f2; color: #087d67; }
    .btn-view:hover { background: #087d67; color: white; }
    .btn-edit { background: #fff7ed; color: #f97316; }
    .btn-edit:hover { background: #f97316; color: white; }
    .btn-delete { background: #fee2e2; color: #ef4444; }
    .btn-delete:hover { background: #ef4444; color: white; }
    .pagination-section { margin-top: 30px; display: flex; justify-content: center; }
    .pagination { gap: 5px; }
    .page-link { border-radius: 10px; color: #374151; border: 1px solid #e5e7eb; padding: 10px 16px; transition: all 0.2s; font-weight: 500; }
    .page-link:hover { background: #087d67; color: white; border-color: #087d67; }
    .page-item.active .page-link { background: #087d67; border-color: #087d67; color: white; }
    .page-item.disabled .page-link { background: #f8f9fa; color: #d1d5db; cursor: not-allowed; }
    .btn-solid { background: linear-gradient(135deg, #087d67 0%, #065a4a 100%); color: white; padding: 12px 24px; border-radius: 12px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s; border: none; }
    .btn-solid:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(8,125,103,0.3); color: white; }
    .multiselect-dropdown.show { display: block !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Fermer les dropdowns quand on clique ailleurs
document.addEventListener('click', function(e) {
    if (!e.target.closest('.multiselect-wrapper')) {
        document.querySelectorAll('.multiselect-dropdown').forEach(d => d.classList.remove('show'));
    }
});

function updateMultiSelect(checkbox) {
    const productId = checkbox.dataset.productId;
    const wrapper = checkbox.closest('.multiselect-wrapper');
    const checkboxes = wrapper.querySelectorAll('input[type="checkbox"]:checked');
    
    // Récupérer les valeurs sélectionnées
    const selectedValues = Array.from(checkboxes).map(cb => cb.value);
    
    // Mettre à jour le texte affiché
    const displayText = wrapper.querySelector('.selected-text');
    displayText.textContent = selectedValues.length > 0 ? selectedValues.join(', ') : '-- Aucune --';
    
    // Envoyer au serveur
    const sousCategorie = selectedValues.join(',');
    
    fetch('update_sous_categorie.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'product_id=' + productId + '&sous_categorie=' + encodeURIComponent(sousCategorie)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Animation flash vert
            const display = wrapper.querySelector('.multiselect-display');
            display.style.borderColor = '#10b981';
            display.style.backgroundColor = '#d1fae5';
            setTimeout(() => {
                display.style.borderColor = '#e5e7eb';
                display.style.backgroundColor = 'white';
            }, 1200);
        }
    });
}

function confirmDelete(productId) {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Cette action est irréversible !",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "delete_product.php?id=" + productId;
        }
    });
}
</script>

<?php require('footer.php'); ?>
</body>
</html>