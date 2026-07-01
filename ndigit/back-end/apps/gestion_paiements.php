<?php
$pageTitle = 'Gestion des retraits vendeurs';
require('header.php');
require('../../include/connect.php');

// Traitement des actions
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_retrait = isset($_POST['id_retrait']) ? (int)$_POST['id_retrait'] : 0;
    $action = $_POST['action'] ?? '';

    $stmtR = $database->prepare("SELECT r.*, u.email, u.nom, u.prenom, pv.solde, pv.total_gagne 
                                  FROM retraits r 
                                  JOIN utilisateur u ON r.id_uti = u.id_uti 
                                  LEFT JOIN portefeuille_vendeur pv ON r.id_uti = pv.id_uti 
                                  WHERE r.id = ?");
    $stmtR->execute([$id_retrait]);
    $retrait = $stmtR->fetch();

    if (!$retrait) {
        $message = "Retrait introuvable.";
        $messageType = 'error';
    } elseif ($retrait['statut'] !== 'en_attente') {
        $message = "Ce retrait a déjà été traité.";
        $messageType = 'warning';
    } else {
        if ($action === 'valider') {
            $update = $database->prepare("UPDATE retraits SET statut = 'accepte' WHERE id = ?");
            $update->execute([$id_retrait]);
            
            $updateWallet = $database->prepare("UPDATE portefeuille_vendeur SET solde = solde - ?, total_gagne = total_gagne + ? WHERE id_uti = ?");
            $updateWallet->execute([$retrait['montant'], $retrait['montant'], $retrait['id_uti']]);
            
            $message = "✅ Retrait #{$id_retrait} validé avec succès. Le montant de " . number_format($retrait['montant'], 0, ',', ' ') . " CFA a été débité du portefeuille.";
            $messageType = 'success';
        } 
        elseif ($action === 'rejeter') {
            $motif = trim($_POST['motif'] ?? '');
            if (empty($motif)) {
                $message = "❌ Veuillez fournir un motif de rejet.";
                $messageType = 'error';
            } else {
                $update = $database->prepare("UPDATE retraits SET statut = 'refuse', commentaire = ? WHERE id = ?");
                $update->execute([$motif, $id_retrait]);
                
                $updateWallet = $database->prepare("UPDATE portefeuille_vendeur SET solde = solde + ? WHERE id_uti = ?");
                $updateWallet->execute([$retrait['montant'], $retrait['id_uti']]);
                
                $message = "❌ Retrait #{$id_retrait} rejeté. Le montant a été remboursé au vendeur.";
                $messageType = 'warning';
            }
        }
    }
}

// Pagination et filtres
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 15;
$offset = ($page - 1) * $perPage;

$statut_filter = isset($_GET['statut']) ? $_GET['statut'] : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$conditions = [];
$params = [];

if (!empty($statut_filter) && in_array($statut_filter, ['en_attente', 'accepte', 'refuse'])) {
    $conditions[] = "r.statut = :statut";
    $params[':statut'] = $statut_filter;
}

if (!empty($search)) {
    $conditions[] = "(u.nom LIKE :search OR u.prenom LIKE :search OR u.email LIKE :search OR r.telephone LIKE :search)";
    $params[':search'] = '%' . $search . '%';
}

$where = '';
if (!empty($conditions)) {
    $where = ' WHERE ' . implode(' AND ', $conditions);
}

// Comptage
$countSql = "SELECT COUNT(*) FROM retraits r JOIN utilisateur u ON r.id_uti = u.id_uti" . $where;
$stmtCount = $database->prepare($countSql);
foreach ($params as $k => $v) {
    $stmtCount->bindValue($k, $v);
}
$stmtCount->execute();
$total = $stmtCount->fetchColumn();
$pages = ceil($total / $perPage);

// Récupération
$sql = "SELECT r.*, u.nom, u.prenom, u.email, 
        pv.solde AS solde_portefeuille, pv.total_gagne,
        dv.nom_boutique
        FROM retraits r 
        JOIN utilisateur u ON r.id_uti = u.id_uti 
        LEFT JOIN portefeuille_vendeur pv ON r.id_uti = pv.id_uti
        LEFT JOIN demandes_vendeur dv ON r.id_uti = dv.id_uti AND dv.statut = 'acceptee'
        " . $where . "
        ORDER BY r.date_demande DESC 
        LIMIT :limit OFFSET :offset";
$stmt = $database->prepare($sql);
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v);
}
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$retraits = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Statistiques
$totalEnAttente = $database->query("SELECT COUNT(*) FROM retraits WHERE statut = 'en_attente'")->fetchColumn();
$totalValides = $database->query("SELECT SUM(montant) FROM retraits WHERE statut = 'accepte'")->fetchColumn() ?? 0;
$totalEnAttenteMontant = $database->query("SELECT SUM(montant) FROM retraits WHERE statut = 'en_attente'")->fetchColumn() ?? 0;
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                
                <?php if (!empty($message)): ?>
                    <div class="alert alert-<?php echo $messageType === 'success' ? 'success' : ($messageType === 'warning' ? 'warning' : 'danger'); ?> alert-dismissible fade show" role="alert" style="border-radius:12px;">
                        <i class="fa-solid fa-<?php echo $messageType === 'success' ? 'circle-check' : ($messageType === 'warning' ? 'triangle-exclamation' : 'circle-xmark'); ?> me-2"></i>
                        <?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Stats rapides -->
                <div class="row g-3 mb-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="card" style="border-radius:16px; border:1px solid #e5e7eb; background:white;">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div style="width:55px; height:55px; background:#fef3c7; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:24px;">⏳</div>
                                <div>
                                    <h3 style="margin:0; font-weight:700; color:#92400e;"><?php echo $totalEnAttente; ?></h3>
                                    <p style="margin:0; color:#6b7280; font-size:13px;">Demandes en attente</p>
                                    <strong style="color:#92400e;"><?php echo number_format($totalEnAttenteMontant, 0, ',', ' '); ?> CFA</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card" style="border-radius:16px; border:1px solid #e5e7eb; background:white;">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div style="width:55px; height:55px; background:#d1fae5; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:24px;">✅</div>
                                <div>
                                    <h3 style="margin:0; font-weight:700; color:#065f46;"><?php echo number_format($totalValides, 0, ',', ' '); ?> CFA</h3>
                                    <p style="margin:0; color:#6b7280; font-size:13px;">Total payé aux vendeurs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card" style="border-radius:16px; border:1px solid #e5e7eb; background:white;">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div style="width:55px; height:55px; background:#e8f5f2; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:24px;">💰</div>
                                <div>
                                    <h3 style="margin:0; font-weight:700; color:#087d67;"><?php echo $total; ?></h3>
                                    <p style="margin:0; color:#6b7280; font-size:13px;">Total demandes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-table" style="border-radius:16px; border:1px solid #e5e7eb; box-shadow:0 10px 30px -10px rgba(0,0,0,0.05);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 style="font-size:22px; font-weight:700; color:#1a2634; margin:0;">💸 Demandes de retrait</h5>
                                <p class="text-muted mb-0"><?php echo $total; ?> demande(s) trouvée(s)</p>
                            </div>
                        </div>

                        <!-- Filtres -->
                        <form method="GET" class="search-section mb-4">
                            <div class="row g-3 align-items-end">
                                <div class="col-lg-5 col-md-6">
                                    <label class="form-label fw-bold mb-2 text-dark">🔍 Recherche</label>
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" 
                                               placeholder="Nom, email ou téléphone..." 
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
                                        <option value="accepte" <?php echo $statut_filter == 'accepte' ? 'selected' : ''; ?>>✅ Accepté</option>
                                        <option value="refuse" <?php echo $statut_filter == 'refuse' ? 'selected' : ''; ?>>❌ Refusé</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <?php if (!empty($search) || !empty($statut_filter)): ?>
                                        <a href="gestion_paiements.php" class="btn btn-outline-secondary w-100" style="border-radius:10px;">
                                            <i class="fa-solid fa-rotate"></i> Réinitialiser les filtres
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </form>

                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-payment">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Vendeur</th>
                                        <th>Boutique</th>
                                        <th>Numéro</th>
                                        <th>Montant</th>
                                        <th>Solde</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($retraits)): ?>
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <i class="fa-regular fa-receipt" style="font-size:50px; color:#d1d5db;"></i>
                                                <p class="mt-3 text-muted">Aucune demande trouvée</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>

                                    <?php foreach ($retraits as $r): 
                                        $vendeurNom = htmlspecialchars($r['prenom'] . ' ' . $r['nom']);
                                        $boutique = !empty($r['nom_boutique']) ? htmlspecialchars($r['nom_boutique']) : 'N/A';
                                        $soldeActuel = $r['solde_portefeuille'] ?? 0;
                                        $statutRetrait = $r['statut'];
                                        $modalIdValidate = 'validateModal' . $r['id'];
                                        $modalIdReject = 'rejectModal' . $r['id'];
                                    ?>
                                        <tr>
                                            <td><strong>#<?php echo $r['id']; ?></strong></td>
                                            <td>
                                                <strong><?php echo $vendeurNom; ?></strong><br>
                                                <small class="text-muted"><?php echo htmlspecialchars($r['email']); ?></small>
                                            </td>
                                            <td><?php echo $boutique; ?></td>
                                            <td><code style="font-size:13px;"><?php echo htmlspecialchars($r['telephone'] ?? 'N/A'); ?></code></td>
                                            <td><strong style="color:#087d67; font-size:15px;"><?php echo number_format($r['montant'], 0, ',', ' '); ?> CFA</strong></td>
                                            <td><strong style="color:#f97316;"><?php echo number_format($soldeActuel, 0, ',', ' '); ?> CFA</strong></td>
                                            <td><small><?php echo date('d/m/Y H:i', strtotime($r['date_demande'])); ?></small></td>
                                            <td>
                                                <?php
                                                switch ($statutRetrait) {
                                                    case 'en_attente':
                                                        echo '<span class="badge" style="background:#fef3c7; color:#92400e; padding:6px 12px; border-radius:20px;">⏳ En attente</span>';
                                                        break;
                                                    case 'accepte':
                                                        echo '<span class="badge" style="background:#d1fae5; color:#065f46; padding:6px 12px; border-radius:20px;">✅ Accepté</span>';
                                                        break;
                                                    case 'refuse':
                                                        echo '<span class="badge" style="background:#fee2e2; color:#991b1b; padding:6px 12px; border-radius:20px;">❌ Refusé</span>';
                                                        if (!empty($r['commentaire'])) {
                                                            echo '<br><small class="text-danger" style="font-size:11px;"><i class="fa-solid fa-circle-info"></i> ' . htmlspecialchars(substr($r['commentaire'], 0, 40)) . '</small>';
                                                        }
                                                        break;
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if ($statutRetrait === 'en_attente'): ?>
                                                    <div class="action-buttons" style="display:flex; gap:6px;">
                                                        <!-- Bouton Valider -->
                                                        <button type="button" 
                                                                class="btn btn-sm" 
                                                                style="background:#d1fae5; color:#065f46; border:none; border-radius:8px; width:34px; height:34px; display:flex; align-items:center; justify-content:center; cursor:pointer;"
                                                                onclick="openValidateModal(<?php echo $r['id']; ?>, '<?php echo addslashes($vendeurNom); ?>', '<?php echo addslashes($r['telephone'] ?? ''); ?>', <?php echo $r['montant']; ?>, <?php echo $soldeActuel; ?>)">
                                                            <i class="fa-solid fa-check"></i>
                                                        </button>
                                                        
                                                        <!-- Bouton Rejeter -->
                                                        <button type="button" 
                                                                class="btn btn-sm" 
                                                                style="background:#fee2e2; color:#991b1b; border:none; border-radius:8px; width:34px; height:34px; display:flex; align-items:center; justify-content:center; cursor:pointer;"
                                                                onclick="openRejectModal(<?php echo $r['id']; ?>, '<?php echo addslashes($vendeurNom); ?>', <?php echo $r['montant']; ?>)">
                                                            <i class="fa-solid fa-times"></i>
                                                        </button>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted" style="font-size:12px;">-</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if ($pages > 1): 
                            $queryParams = '';
                            if (!empty($search)) $queryParams .= '&search=' . urlencode($search);
                            if (!empty($statut_filter)) $queryParams .= '&statut=' . urlencode($statut_filter);
                        ?>
                            <div class="pagination-section">
                                <nav>
                                    <ul class="pagination">
                                        <?php if ($page > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?php echo $page - 1 . $queryParams; ?>"><i class="fa-regular fa-chevron-left"></i></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php for ($i = 1; $i <= $pages; $i++): ?>
                                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                                <a class="page-link" href="?page=<?php echo $i . $queryParams; ?>"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        <?php if ($page < $pages): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?php echo $page + 1 . $queryParams; ?>"><i class="fa-regular fa-chevron-right"></i></a>
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

<!-- MODAL DE VALIDATION -->
<div class="modal fade" id="validateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; border:none;">
            <div class="modal-header" style="border-bottom:1px solid #e5e7eb; padding:20px 25px; background:#d1fae5;">
                <h5 class="modal-title" style="color:#065f46; font-weight:700;">
                    <i class="fa-solid fa-check-circle me-2"></i>Confirmer la validation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:25px;">
                <div class="text-center mb-4">
                    <i class="fa-solid fa-circle-check" style="font-size:50px; color:#10b981;"></i>
                </div>
                
                <div style="background:#f9fafb; border-radius:12px; padding:20px; margin-bottom:20px;">
                    <table style="width:100%; font-size:14px;">
                        <tr>
                            <td style="padding:8px 0; color:#6b7280;">Vendeur :</td>
                            <td style="padding:8px 0; font-weight:600; text-align:right;" id="valVendeur"></td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0; color:#6b7280;">Numéro MTN :</td>
                            <td style="padding:8px 0; font-weight:600; text-align:right;" id="valNumero"></td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0; color:#6b7280;">Montant à retirer :</td>
                            <td style="padding:8px 0; font-weight:700; color:#087d67; text-align:right; font-size:16px;" id="valMontant"></td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0; color:#6b7280;">Solde actuel :</td>
                            <td style="padding:8px 0; font-weight:600; color:#f97316; text-align:right;" id="valSoldeAvant"></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding:12px 0 0 0; border-top:1px dashed #e5e7eb; margin-top:12px;">
                                <strong style="color:#065f46;">Solde après validation :</strong>
                                <strong style="color:#065f46; float:right; font-size:16px;" id="valSoldeApres"></strong>
                            </td>
                        </tr>
                    </table>
                </div>

                <p style="font-size:14px; color:#6b7280; text-align:center;">
                    <i class="fa-solid fa-triangle-exclamation" style="color:#f59e0b;"></i> 
                    Cette action est irréversible. Le montant sera débité du portefeuille.
                </p>

                <form method="POST" id="validateForm">
                    <input type="hidden" name="id_retrait" id="valIdRetrait">
                    <input type="hidden" name="action" value="valider">
                    <div class="d-flex gap-3 mt-4">
                        <button type="button" class="btn btn-outline-secondary w-50" data-bs-dismiss="modal" style="border-radius:12px; padding:12px;">Annuler</button>
                        <button type="submit" class="btn w-50" style="background:#10b981; color:white; border-radius:12px; padding:12px; font-weight:600;">
                            <i class="fa-solid fa-check me-2"></i> Valider le paiement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DE REJET -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; border:none;">
            <div class="modal-header" style="border-bottom:1px solid #e5e7eb; padding:20px 25px; background:#fee2e2;">
                <h5 class="modal-title" style="color:#991b1b; font-weight:700;">
                    <i class="fa-solid fa-times-circle me-2"></i>Rejeter le retrait
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="rejectForm">
                <div class="modal-body" style="padding:25px;">
                    <input type="hidden" name="id_retrait" id="rejIdRetrait">
                    <input type="hidden" name="action" value="rejeter">
                    
                    <div style="background:#f9fafb; border-radius:12px; padding:15px; margin-bottom:20px;">
                        <p style="margin:0;"><strong>Vendeur :</strong> <span id="rejVendeur"></span></p>
                        <p style="margin:5px 0 0 0;"><strong>Montant :</strong> <span id="rejMontant"></span></p>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label fw-bold">Motif du rejet <span class="text-danger">*</span></label>
                        <textarea name="motif" class="form-control" rows="3" placeholder="Expliquez pourquoi ce retrait est rejeté..." required style="border-radius:10px;"></textarea>
                        <small class="text-muted"><i class="fa-solid fa-circle-info"></i> Le montant sera remboursé dans le portefeuille du vendeur.</small>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #e5e7eb; padding:20px 25px;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:10px;">Annuler</button>
                    <button type="submit" class="btn" style="background:#ef4444; color:white; border-radius:10px; font-weight:600;">
                        <i class="fa-solid fa-times me-2"></i> Confirmer le rejet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
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
    .table-payment thead th {
        background: #f8f9fa;
        padding: 14px 12px;
        font-weight: 600;
        font-size: 13px;
        color: #374151;
        border-bottom: 2px solid #e5e7eb;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .table-payment tbody td {
        padding: 14px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
        font-size: 14px;
    }
    .table-payment tbody tr:hover { background: #f9fafb; }
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
</style>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Fonctions pour ouvrir les modaux
function openValidateModal(id, vendeur, numero, montant, soldeAvant) {
    document.getElementById('valIdRetrait').value = id;
    document.getElementById('valVendeur').textContent = vendeur;
    document.getElementById('valNumero').textContent = numero || 'N/A';
    document.getElementById('valMontant').textContent = montant.toLocaleString('fr-FR') + ' CFA';
    document.getElementById('valSoldeAvant').textContent = soldeAvant.toLocaleString('fr-FR') + ' CFA';
    document.getElementById('valSoldeApres').textContent = (soldeAvant - montant).toLocaleString('fr-FR') + ' CFA';
    
    var myModal = new bootstrap.Modal(document.getElementById('validateModal'));
    myModal.show();
}

function openRejectModal(id, vendeur, montant) {
    document.getElementById('rejIdRetrait').value = id;
    document.getElementById('rejVendeur').textContent = vendeur;
    document.getElementById('rejMontant').textContent = montant.toLocaleString('fr-FR') + ' CFA';
    
    var myModal = new bootstrap.Modal(document.getElementById('rejectModal'));
    myModal.show();
}
</script>

<?php require('footer.php'); ?>
<?php require('footer.php'); ?>