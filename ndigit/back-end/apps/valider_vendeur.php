<?php
$pageTitle = 'Valider un vendeur';
require('header.php');
require('../../include/connect.php');

// Récupérer l'ID de la demande
$id_demande = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Récupérer les infos de la demande
$stmt = $database->prepare("SELECT dv.*, u.nom, u.prenom, u.email FROM demandes_vendeur dv JOIN utilisateur u ON dv.id_uti = u.id_uti WHERE dv.id = ?");
$stmt->execute([$id_demande]);
$demande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$demande) {
    echo '<script>alert("Demande introuvable."); window.location.href="client.php";</script>';
    exit;
}

$message = '';
$messageType = '';

// Traitement si formulaire soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action_post = $_POST['action'] ?? '';
    
    if ($action_post === 'accepter') {
        // Mettre à jour le statut
        $update = $database->prepare("UPDATE demandes_vendeur SET statut = 'acceptee' WHERE id = ?");
        $update->execute([$id_demande]);
        
        // Créer le portefeuille vendeur si inexistant
        $checkWallet = $database->prepare("SELECT id FROM portefeuille_vendeur WHERE id_uti = ?");
        $checkWallet->execute([$demande['id_uti']]);
        if (!$checkWallet->fetch()) {
            $database->prepare("INSERT INTO portefeuille_vendeur (id_uti, solde, total_gagne) VALUES (?, 0.00, 0.00)")->execute([$demande['id_uti']]);
        }
        
        $message = "✅ Le vendeur a été accepté avec succès !";
        $messageType = 'success';
        $demande['statut'] = 'acceptee';
    } 
    elseif ($action_post === 'refuser') {
        $motif = trim($_POST['motif'] ?? '');
        if (empty($motif)) {
            $message = "❌ Veuillez fournir un motif de refus.";
            $messageType = 'error';
        } else {
            $update = $database->prepare("UPDATE demandes_vendeur SET statut = 'refusee' WHERE id = ?");
            $update->execute([$id_demande]);
            $message = "❌ Le vendeur a été refusé.";
            $messageType = 'warning';
            $demande['statut'] = 'refusee';
        }
    }
}
?>

<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                
                <?php if (!empty($message)): ?>
                    <div class="alert alert-<?php echo $messageType === 'success' ? 'success' : ($messageType === 'warning' ? 'warning' : 'danger'); ?> alert-dismissible fade show" role="alert" style="border-radius:12px;">
                        <?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card" style="border-radius:16px; border:1px solid #e5e7eb; box-shadow:0 10px 30px -10px rgba(0,0,0,0.1);">
                    <div class="card-header" style="background:white; border-bottom:1px solid #e5e7eb; padding:20px 25px;">
                        <h5 style="margin:0; font-weight:700;">📋 Demande de vendeur</h5>
                    </div>
                    <div class="card-body" style="padding:25px;">
                        
                        <!-- Infos du demandeur -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="text-muted mb-1" style="font-size:12px; text-transform:uppercase;">Demandeur</label>
                                <p style="font-size:16px; font-weight:600; margin:0;"><?php echo htmlspecialchars($demande['prenom'] . ' ' . $demande['nom']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted mb-1" style="font-size:12px; text-transform:uppercase;">Email</label>
                                <p style="font-size:14px; margin:0;"><?php echo htmlspecialchars($demande['email']); ?></p>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="text-muted mb-1" style="font-size:12px; text-transform:uppercase;">Nom boutique</label>
                                <p style="font-size:16px; font-weight:600; margin:0;"><?php echo htmlspecialchars($demande['nom_boutique']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted mb-1" style="font-size:12px; text-transform:uppercase;">Catégorie</label>
                                <p style="font-size:14px; margin:0;"><?php echo htmlspecialchars($demande['categorie']); ?></p>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="text-muted mb-1" style="font-size:12px; text-transform:uppercase;">Téléphone</label>
                                <p style="font-size:14px; margin:0;"><?php echo htmlspecialchars($demande['telephone']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted mb-1" style="font-size:12px; text-transform:uppercase;">Adresse</label>
                                <p style="font-size:14px; margin:0;"><?php echo htmlspecialchars($demande['adresse'] ?? 'Non renseignée'); ?></p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="text-muted mb-1" style="font-size:12px; text-transform:uppercase;">Description</label>
                            <p style="font-size:14px; line-height:1.6; background:#f9fafb; padding:15px; border-radius:10px;"><?php echo nl2br(htmlspecialchars($demande['description'])); ?></p>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted mb-1" style="font-size:12px; text-transform:uppercase;">Statut actuel</label>
                            <span class="badge" style="<?php 
                                echo $demande['statut'] === 'en_attente' ? 'background:#fef3c7; color:#92400e;' : 
                                    ($demande['statut'] === 'acceptee' ? 'background:#d1fae5; color:#065f46;' : 'background:#fee2e2; color:#991b1b;'); 
                            ?> padding:8px 15px; border-radius:20px;">
                                <?php echo $demande['statut'] === 'en_attente' ? '⏳ En attente' : 
                                    ($demande['statut'] === 'acceptee' ? '✅ Accepté' : '❌ Refusé'); ?>
                            </span>
                        </div>

                        <!-- Boutons d'action -->
                        <?php if ($demande['statut'] === 'en_attente'): ?>
                            <div class="d-flex gap-3 mt-4">
                                <!-- Bouton Accepter -->
                                <form method="POST" style="flex:1;">
                                    <input type="hidden" name="action" value="accepter">
                                    <button type="submit" class="btn w-100" style="background:#10b981; color:white; border-radius:12px; padding:14px; font-weight:600;">
                                        <i class="fa-solid fa-check-circle me-2"></i> Accepter le vendeur
                                    </button>
                                </form>

                                <!-- Bouton Refuser (ouvre le modal) -->
                                <button type="button" class="btn w-100" style="background:#ef4444; color:white; border-radius:12px; padding:14px; font-weight:600;" data-bs-toggle="modal" data-bs-target="#refusModal">
                                    <i class="fa-solid fa-times-circle me-2"></i> Refuser le vendeur
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="text-center mt-4">
                                <a href="client.php" class="btn btn-outline-secondary" style="border-radius:12px; padding:12px 30px;">
                                    <i class="fa-solid fa-arrow-left me-2"></i> Retour à la liste
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de refus -->
<div class="modal fade" id="refusModal" tabindex="-1" aria-labelledby="refusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; border:none;">
            <div class="modal-header" style="border-bottom:1px solid #e5e7eb; padding:20px 25px;">
                <h5 class="modal-title" id="refusModalLabel">❌ Motif du refus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                <div class="modal-body" style="padding:25px;">
                    <input type="hidden" name="action" value="refuser">
                    <div class="form-group">
                        <label class="form-label fw-bold">Motif du refus <span class="text-danger">*</span></label>
                        <textarea name="motif" class="form-control" rows="4" placeholder="Expliquez pourquoi la demande est refusée..." required style="border-radius:10px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #e5e7eb; padding:20px 25px;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:10px;">Annuler</button>
                    <button type="submit" class="btn" style="background:#ef4444; color:white; border-radius:10px; font-weight:600;">
                        <i class="fa-solid fa-times me-2"></i> Confirmer le refus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require('footer.php'); ?>