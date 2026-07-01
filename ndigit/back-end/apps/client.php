<?php 

    require('header.php'); 


// Paramètres de pagination et filtres
$utilisateursParPage = 10;
$pageActuelle = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($pageActuelle - 1) * $utilisateursParPage;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$type_filter = isset($_GET['type']) ? $_GET['type'] : '';
$statut_vendeur = isset($_GET['vendeur']) ? $_GET['vendeur'] : '';

// Construction des conditions
$conditions = [];
$params = [];
$extraJoin = ''; // Jointure supplémentaire si nécessaire

if (!empty($search)) {
    $conditions[] = "(u.nom LIKE :search OR u.prenom LIKE :search OR u.email LIKE :search)";
    $params[':search'] = '%' . $search . '%';
}

if ($type_filter === 'simple') {
    $conditions[] = "u.type = '-'";
} elseif ($type_filter === 'pro') {
    $conditions[] = "u.type = 'pro'";
}

// Gestion des filtres vendeur
if ($statut_vendeur === 'vendeur_accepte') {
    $extraJoin = "INNER JOIN demandes_vendeur dv2 ON u.id_uti = dv2.id_uti AND dv2.statut = 'acceptee'";
} elseif ($statut_vendeur === 'vendeur_attente') {
    $extraJoin = "INNER JOIN demandes_vendeur dv2 ON u.id_uti = dv2.id_uti AND dv2.statut = 'en_attente'";
} elseif ($statut_vendeur === 'non_vendeur') {
    $conditions[] = "dv.id IS NULL";
}

$where = '';
if (!empty($conditions)) {
    $where = ' WHERE ' . implode(' AND ', $conditions);
}

// Requête de comptage
$countQuery = "SELECT COUNT(DISTINCT u.id_uti) FROM utilisateur u 
               LEFT JOIN demandes_vendeur dv ON u.id_uti = dv.id_uti
               " . $extraJoin . "
               " . $where;
$stmtCount = $database->prepare($countQuery);
foreach ($params as $k => $v) {
    $stmtCount->bindValue($k, $v);
}
$stmtCount->execute();
$totalUtilisateurs = $stmtCount->fetchColumn();
$totalPages = ceil($totalUtilisateurs / $utilisateursParPage);

// Requête de récupération
$query = "SELECT DISTINCT u.*, 
          dv.id AS dv_id, dv.statut AS dv_statut, dv.nom_boutique,
          a.nombre_total, a.nombre_telecharge, a.date_debut, a.date_fin
          FROM utilisateur u
          LEFT JOIN demandes_vendeur dv ON u.id_uti = dv.id_uti
          LEFT JOIN abonnement a ON u.id_uti = a.id_uti2
          " . $extraJoin . "
          " . $where . "
          ORDER BY u.id_uti DESC
          LIMIT :limit OFFSET :offset";

$stmt = $database->prepare($query);
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v);
}
$stmt->bindValue(':limit', $utilisateursParPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion des utilisateurs - NDIGITMARKET</title>
</head>
<body>
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <!-- En-tête -->
                        <div class="title-header option-title d-sm-flex d-block mb-4">
                            <div>
                                <h5 style="font-size:22px; font-weight:700; color:#1a2634;">👥 Gestion des utilisateurs</h5>
                                <p class="text-muted mb-0"><?php echo $totalUtilisateurs; ?> utilisateur(s) trouvé(s)</p>
                            </div>
                            <div>
                                <a class="btn btn-solid" href="#">
                                    <i class="fa-regular fa-plus"></i> Ajouter un utilisateur
                                </a>
                            </div>
                        </div>

                        <!-- Barre de filtres -->
                        <form method="GET" action="" class="search-section mb-4">
                            <div class="row g-3 align-items-end">
                                <div class="col-lg-4 col-md-6">
                                    <label class="form-label fw-bold mb-2">🔍 Recherche</label>
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" 
                                               placeholder="Nom, prénom ou email..." 
                                               value="<?php echo htmlspecialchars($search); ?>"
                                               style="border-radius:10px 0 0 10px;">
                                        <button class="btn btn-primary" type="submit" style="border-radius:0 10px 10px 0;">
                                            <i class="fa-regular fa-magnifying-glass"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="col-lg-2 col-md-4 col-6">
                                    <label class="form-label fw-bold mb-2">💳 Type compte</label>
                                    <select name="type" class="form-select" onchange="this.form.submit()" style="border-radius:10px;">
                                        <option value="">Tous</option>
                                        <option value="simple" <?php echo $type_filter == 'simple' ? 'selected' : ''; ?>>Simple</option>
                                        <option value="pro" <?php echo $type_filter == 'pro' ? 'selected' : ''; ?>>Premium</option>
                                    </select>
                                </div>
                                
                                <div class="col-lg-3 col-md-4 col-6">
                                    <label class="form-label fw-bold mb-2">🏪 Statut vendeur</label>
                                    <select name="vendeur" class="form-select" onchange="this.form.submit()" style="border-radius:10px;">
                                        <option value="">Tous</option>
                                        <option value="vendeur_accepte" <?php echo $statut_vendeur == 'vendeur_accepte' ? 'selected' : ''; ?>>Vendeur accepté</option>
                                        <option value="vendeur_attente" <?php echo $statut_vendeur == 'vendeur_attente' ? 'selected' : ''; ?>>Demande en attente</option>
                                        <option value="non_vendeur" <?php echo $statut_vendeur == 'non_vendeur' ? 'selected' : ''; ?>>Non vendeur</option>
                                    </select>
                                </div>
                                
                                <div class="col-lg-3 col-md-4">
                                    <?php if (!empty($search) || !empty($type_filter) || !empty($statut_vendeur)): ?>
                                        <a href="utilisateurs.php" class="btn btn-outline-secondary w-100" style="border-radius:10px;">
                                            <i class="fa-solid fa-rotate"></i> Réinitialiser
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </form>

                        <!-- Table des utilisateurs -->
                        <div class="table-responsive">
                            <table class="table all-package theme-table table-user">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom complet</th>
                                        <th>Email</th>
                                        <th>Type</th>
                                        <th>Vendeur</th>
                                        <th>Abonnement</th>
                                        <th>Téléchargements</th>
                                        <th>Date fin</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($utilisateurs)): ?>
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <i class="fa-regular fa-user-slash" style="font-size:40px; color:#d1d5db;"></i>
                                                <p class="mt-2 text-muted">Aucun utilisateur trouvé</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>

                                    <?php foreach ($utilisateurs as $utilisateur): 
                                        $date_fin_obj = !empty($utilisateur['date_fin']) ? new DateTime($utilisateur['date_fin']) : null;
                                        $date_aujourdhui = new DateTime();
                                        $abonnementStatus = 'Aucun';
                                        $abonnementBadge = 'background:#f3f4f6; color:#6b7280;';
                                        $nombre_total = $utilisateur['nombre_total'] ?? 0;
                                        $nombre_telecharge = $utilisateur['nombre_telecharge'] ?? 0;
                                        $nombre_restant = $nombre_total - $nombre_telecharge;
                                        
                                        if ($utilisateur['type'] === 'pro' && $date_fin_obj) {
                                            if ($date_aujourdhui < $date_fin_obj) {
                                                $abonnementStatus = 'Actif';
                                                $abonnementBadge = 'background:#d1fae5; color:#065f46;';
                                            } else {
                                                $abonnementStatus = 'Expiré';
                                                $abonnementBadge = 'background:#fee2e2; color:#991b1b;';
                                            }
                                        }
                                        
                                        // Statut vendeur
                                        $vendeurStatus = '';
                                        $vendeurBadge = '';
                                        if (!empty($utilisateur['dv_id'])) {
                                            if ($utilisateur['dv_statut'] === 'acceptee') {
                                                $vendeurStatus = '✅ Accepté';
                                                $vendeurBadge = 'background:#d1fae5; color:#065f46;';
                                            } elseif ($utilisateur['dv_statut'] === 'en_attente') {
                                                $vendeurStatus = '⏳ En attente';
                                                $vendeurBadge = 'background:#fef3c7; color:#92400e;';
                                            } elseif ($utilisateur['dv_statut'] === 'refusee') {
                                                $vendeurStatus = '❌ Refusé';
                                                $vendeurBadge = 'background:#fee2e2; color:#991b1b;';
                                            }
                                        } else {
                                            $vendeurStatus = 'Non vendeur';
                                            $vendeurBadge = 'background:#f3f4f6; color:#9ca3af;';
                                        }
                                    ?>
                                        <tr>
                                            <td>#<?php echo $utilisateur['id_uti']; ?></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($utilisateur['prenom'] . ' ' . $utilisateur['nom']); ?></strong>
                                                <?php if (!empty($utilisateur['nom_boutique'])): ?>
                                                    <br><small style="color:#087d67;">🏪 <?php echo htmlspecialchars($utilisateur['nom_boutique']); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td><small><?php echo htmlspecialchars($utilisateur['email']); ?></small></td>
                                            <td>
                                                <span class="badge" style="<?php echo $utilisateur['type'] === 'pro' ? 'background:#fff7ed; color:#f97316;' : 'background:#e8f5f2; color:#087d67;'; ?> padding:6px 12px; border-radius:20px;">
                                                    <?php echo $utilisateur['type'] === 'pro' ? '⭐ Premium' : '👤 Simple'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge" style="<?php echo $vendeurBadge; ?> padding:6px 12px; border-radius:20px; font-size:12px;">
                                                    <?php echo $vendeurStatus; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge" style="<?php echo $abonnementBadge; ?> padding:6px 12px; border-radius:20px; font-size:12px;">
                                                    <?php echo $abonnementStatus; ?>
                                                </span>
                                            </td>
                                            <td style="font-size:13px;">
                                                <?php if ($nombre_total > 0): ?>
                                                    <strong><?php echo $nombre_restant; ?></strong> / <?php echo $nombre_total; ?> restants
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td style="font-size:13px;">
                                                <?php echo !empty($utilisateur['date_fin']) ? date('d/m/Y', strtotime($utilisateur['date_fin'])) : '-'; ?>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <!-- Bouton Valider/Refuser vendeur (si demande en attente) -->
                                                    <?php if ($utilisateur['dv_statut'] === 'en_attente'): ?>
                                                        <a href="valider_vendeur.php?id=<?php echo $utilisateur['dv_id']; ?>&action=accepter" 
                                                           class="btn-approve" title="Accepter le vendeur" style="width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;background:#d1fae5;color:#065f46;text-decoration:none;">
                                                            <i class="fa-solid fa-check"></i>
                                                        </a>
                                                        <a href="valider_vendeur.php?id=<?php echo $utilisateur['dv_id']; ?>&action=refuser" 
                                                           class="btn-reject" title="Refuser le vendeur" style="width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;background:#fee2e2;color:#991b1b;text-decoration:none;">
                                                            <i class="fa-solid fa-times"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <a href="modifier_utilisateur.php?id=<?php echo $utilisateur['id_uti']; ?>" class="btn-edit" title="Modifier" style="width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;background:#fff7ed;color:#f97316;text-decoration:none;">
                                                        <i class="fa-regular fa-pen-to-square"></i>
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
                            if (!empty($type_filter)) $queryParams .= '&type=' . urlencode($type_filter);
                            if (!empty($statut_vendeur)) $queryParams .= '&vendeur=' . urlencode($statut_vendeur);
                        ?>
                            <div class="pagination-section">
                                <nav>
                                    <ul class="pagination">
                                        <?php if ($pageActuelle > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?php echo $pageActuelle - 1 . $queryParams; ?>">
                                                    <i class="fa-regular fa-chevron-left"></i>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php
                                        $startPage = max(1, $pageActuelle - 2);
                                        $endPage = min($totalPages, $pageActuelle + 2);
                                        if ($startPage > 1): ?>
                                            <li class="page-item"><a class="page-link" href="?page=1<?php echo $queryParams; ?>">1</a></li>
                                            <?php if ($startPage > 2): ?>
                                                <li class="page-item disabled"><span class="page-link">...</span></li>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                            <li class="page-item <?php echo ($i == $pageActuelle) ? 'active' : ''; ?>">
                                                <a class="page-link" href="?page=<?php echo $i . $queryParams; ?>"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>

                                        <?php if ($endPage < $totalPages): ?>
                                            <?php if ($endPage < $totalPages - 1): ?>
                                                <li class="page-item disabled"><span class="page-link">...</span></li>
                                            <?php endif; ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?php echo $totalPages . $queryParams; ?>"><?php echo $totalPages; ?></a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($pageActuelle < $totalPages): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?php echo $pageActuelle + 1 . $queryParams; ?>">
                                                    <i class="fa-regular fa-chevron-right"></i>
                                                </a>
                                            </li>
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
    .search-section {
        background: #ffffff;
        padding: 20px;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }
    .form-select {
        border-radius: 10px !important;
        padding: 10px 15px;
        border: 1px solid #e5e7eb;
        cursor: pointer;
    }
    .table-user thead th {
        background: #f8f9fa;
        padding: 14px 12px;
        font-weight: 600;
        font-size: 13px;
        color: #374151;
        border-bottom: 2px solid #e5e7eb;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .table-user tbody td {
        padding: 14px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
        font-size: 14px;
    }
    .table-user tbody tr:hover { background: #f9fafb; }
    .action-buttons { display: flex; gap: 6px; align-items: center; }
    .btn-approve:hover { background: #10b981 !important; color: white !important; }
    .btn-reject:hover { background: #ef4444 !important; color: white !important; }
    .btn-edit:hover { background: #f97316 !important; color: white !important; }
    .pagination-section { margin-top: 30px; display: flex; justify-content: center; }
    .pagination { gap: 5px; }
    .page-link {
        border-radius: 10px;
        color: #374151;
        border: 1px solid #e5e7eb;
        padding: 10px 16px;
        transition: all 0.2s;
        font-weight: 500;
    }
    .page-link:hover { background: #087d67; color: white; border-color: #087d67; }
    .page-item.active .page-link { background: #087d67; border-color: #087d67; color: white; }
    .page-item.disabled .page-link { background: #f8f9fa; color: #d1d5db; cursor: not-allowed; }
    .btn-solid {
        background: linear-gradient(135deg, #087d67 0%, #065a4a 100%);
        color: white;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        transition: all 0.3s;
    }
    .btn-solid:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(8,125,103,0.3); color: white; }
</style>

<?php require('footer.php'); ?>
</body>
</html>