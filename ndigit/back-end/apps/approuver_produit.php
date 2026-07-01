<?php
$pageTitle = 'Valider un produit';
require('header.php');
require('../../include/connect.php');

// Vérifier si l'ID est passé
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo '<script>alert("ID produit invalide."); window.location.href="produits";</script>';
    exit;
}

$id_produit = (int)$_GET['id'];

// Récupérer les infos du produit avec jointures
$stmt = $database->prepare("
    SELECT p.*, c.nom_categorie, 
           dv.nom_boutique AS vendeur_boutique,
           u.nom AS vendeur_nom, u.prenom AS vendeur_prenom
    FROM produits p
    LEFT JOIN categories c ON p.categorie_id = c.id
    LEFT JOIN demandes_vendeur dv ON p.id_vendeur = dv.id_uti AND dv.statut = 'acceptee'
    LEFT JOIN utilisateur u ON p.id_vendeur = u.id_uti
    WHERE p.id = ?
");
$stmt->execute([$id_produit]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    echo '<script>alert("Produit introuvable."); window.location.href="produits";</script>';
    exit;
}

// Déterminer le nom du vendeur
if ($produit['id_vendeur'] == 1) {
    $vendeurAffichage = '🏪 NDIGITMARKET (Admin)';
} elseif (!empty($produit['vendeur_boutique'])) {
    $vendeurAffichage = htmlspecialchars($produit['vendeur_boutique']);
} else {
    $vendeurAffichage = htmlspecialchars(($produit['vendeur_prenom'] ?? '') . ' ' . ($produit['vendeur_nom'] ?? 'Inconnu'));
}

// Traitement des actions
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'approuver') {
            // Approuver : statut = approuve, commentaire = NULL
            $update = $database->prepare("UPDATE produits SET statut = 'approuve', commentaire = NULL WHERE id = ?");
            $update->execute([$id_produit]);
            $message = "✅ Produit approuvé avec succès ! Il est maintenant visible sur le site.";
            $messageType = 'success';
            $produit['statut'] = 'approuve';
            $produit['commentaire'] = null;
        } 
        elseif ($_POST['action'] === 'refuser') {
            $motif = trim($_POST['motif'] ?? '');
            if (empty($motif)) {
                $message = "❌ Veuillez fournir un motif de refus.";
                $messageType = 'error';
            } else {
                // Refuser : statut = refuse, commentaire = motif
                $update = $database->prepare("UPDATE produits SET statut = 'refuse', commentaire = ? WHERE id = ?");
                $update->execute([$motif, $id_produit]);
                $message = "❌ Produit refusé. Le vendeur sera informé du motif.";
                $messageType = 'warning';
                $produit['statut'] = 'refuse';
                $produit['commentaire'] = $motif;
            }
        }
    }
}

// Chemins des fichiers
$imagePath = '../../back-end/apps/' . htmlspecialchars($produit['image']);
if (!file_exists($imagePath)) {
    $imagePath = '../../back-end/apps/Blue.jpg';
}
$fichierPath = '../../back-end/apps/' . htmlspecialchars($produit['fichier']);

// Extraire le nom du fichier sans le chemin
$nomFichier = $produit['fichier'];
if (strpos($nomFichier, 'uploads/') === 0) {
    $nomFichier = substr($nomFichier, 8);
}
?>

<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Message de confirmation -->
                <?php if (!empty($message)): ?>
                    <div class="alert alert-<?php echo $messageType === 'success' ? 'success' : ($messageType === 'warning' ? 'warning' : 'danger'); ?> alert-dismissible fade show" role="alert" style="border-radius:12px;">
                        <?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <!-- Colonne gauche : Image + Infos -->
                    <div class="col-lg-8">
                        <div class="card" style="border-radius:16px; border:1px solid #e5e7eb; box-shadow:0 10px 30px -10px rgba(0,0,0,0.1);">
                            <div class="card-header d-flex justify-content-between align-items-center" style="background:white; border-bottom:1px solid #e5e7eb; padding:20px 25px;">
                                <h5 style="margin:0; font-weight:700; color:#1a2634;">📋 Aperçu du produit</h5>
                                <span class="badge" style="font-size:14px; padding:8px 15px; border-radius:30px; <?php 
                                    echo $produit['statut'] === 'en_attente' ? 'background:#fef3c7; color:#92400e;' : 
                                        ($produit['statut'] === 'approuve' ? 'background:#d1fae5; color:#065f46;' : 'background:#fee2e2; color:#991b1b;'); 
                                ?>">
                                    <?php 
                                        echo $produit['statut'] === 'en_attente' ? '⏳ En attente' : 
                                            ($produit['statut'] === 'approuve' ? '✅ Approuvé' : '❌ Refusé'); 
                                    ?>
                                </span>
                            </div>
                            <div class="card-body" style="padding:25px;">
                                <!-- Image -->
                                <div style="background:#f8f9fa; border-radius:12px; padding:20px; text-align:center; margin-bottom:25px;">
                                    <img src="<?php echo $imagePath; ?>" 
                                         alt="<?php echo htmlspecialchars($produit['nom_article']); ?>" 
                                         style="max-width:100%; max-height:400px; border-radius:8px; box-shadow:0 5px 20px rgba(0,0,0,0.1); object-fit:contain;">
                                </div>

                                <!-- Détails -->
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="info-box" style="background:#f8f9fa; border-radius:10px; padding:15px;">
                                            <label class="text-muted mb-1" style="font-size:12px; text-transform:uppercase;">Nom du produit</label>
                                            <h4 style="color:#1a2634; margin:0;"><?php echo htmlspecialchars($produit['nom_article']); ?></h4>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-box" style="background:#f8f9fa; border-radius:10px; padding:15px;">
                                            <label class="text-muted mb-1" style="font-size:12px; text-transform:uppercase;">Catégorie</label>
                                            <h5 style="margin:0;"><?php echo htmlspecialchars($produit['nom_categorie'] ?? 'N/A'); ?></h5>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-box" style="background:#f8f9fa; border-radius:10px; padding:15px;">
                                            <label class="text-muted mb-1" style="font-size:12px; text-transform:uppercase;">Prix</label>
                                            <h4 style="color:#087d67; margin:0;">
                                                <?php echo $produit['prix'] == 0 ? 'Gratuit' : number_format($produit['prix'], 0, ',', ' ') . ' CFA'; ?>
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-box" style="background:#f8f9fa; border-radius:10px; padding:15px;">
                                            <label class="text-muted mb-1" style="font-size:12px; text-transform:uppercase;">Prix promo</label>
                                            <h4 style="color:#f97316; margin:0;">
                                                <?php echo $produit['prix_reduction'] > 0 ? number_format($produit['prix_reduction'], 0, ',', ' ') . ' CFA' : '-'; ?>
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-box" style="background:#f8f9fa; border-radius:10px; padding:15px;">
                                            <label class="text-muted mb-1" style="font-size:12px; text-transform:uppercase;">Sous-catégorie</label>
                                            <p style="margin:0;"><?php echo !empty($produit['sous_categorie']) ? htmlspecialchars($produit['sous_categorie']) : '-'; ?></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div style="margin-top:30px; padding-top:20px; border-top:1px solid #e5e7eb;">
                                    <h5 style="margin-bottom:15px; font-weight:700;">📝 Description</h5>
                                    <div style="line-height:1.8; color:#374151;">
                                        <?php echo html_entity_decode($produit['description']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Colonne droite : Actions + Fichiers -->
                    <div class="col-lg-4">
                        <!-- Carte Vendeur -->
                        <div class="card mb-4" style="border-radius:16px; border:1px solid #e5e7eb; box-shadow:0 5px 15px rgba(0,0,0,0.05);">
                            <div class="card-header" style="background:white; border-bottom:1px solid #e5e7eb; padding:15px 20px;">
                                <h5 style="margin:0; font-weight:700;"><i class="fa-solid fa-user me-2" style="color:#087d67;"></i>Vendeur</h5>
                            </div>
                            <div class="card-body" style="padding:20px;">
                                <p style="font-size:15px; margin-bottom:5px;"><strong><?php echo $vendeurAffichage; ?></strong></p>
                                <p style="font-size:13px; color:#6b7280; margin:0;">Auteur : <?php echo htmlspecialchars($produit['auteur']); ?></p>
                                <p style="font-size:13px; color:#6b7280; margin:0;">Ajouté le : <?php echo date('d/m/Y à H:i', strtotime($produit['date_ajout'])); ?></p>
                            </div>
                        </div>

                        <!-- Carte Fichiers -->
                        <div class="card mb-4" style="border-radius:16px; border:1px solid #e5e7eb; box-shadow:0 5px 15px rgba(0,0,0,0.05);">
                            <div class="card-header" style="background:white; border-bottom:1px solid #e5e7eb; padding:15px 20px;">
                                <h5 style="margin:0; font-weight:700;"><i class="fa-solid fa-file-zipper me-2" style="color:#f97316;"></i>Fichiers</h5>
                            </div>
                            <div class="card-body" style="padding:20px;">
                                <div class="mb-3">
                                    <label class="text-muted mb-2" style="font-size:13px;">🖼️ Image du produit</label>
                                    <a href="<?php echo $imagePath; ?>" target="_blank" class="btn btn-outline-primary btn-sm w-100" style="border-radius:10px; padding:10px;">
                                        <i class="fa-solid fa-image"></i> Voir l'image en grand
                                    </a>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted mb-2" style="font-size:13px;">📦 Fichier ZIP</label>
                                    <a href="<?php echo $fichierPath; ?>" class="btn btn-outline-success btn-sm w-100" style="border-radius:10px; padding:10px;" download>
                                        <i class="fa-solid fa-download"></i> Télécharger le ZIP
                                    </a>
                                    <small class="text-muted d-block mt-1" style="font-size:12px;">
                                        <?php echo htmlspecialchars($nomFichier); ?>
                                    </small>
                                </div>
                                <?php if (!empty($produit['apercue']) && $produit['apercue'] !== '-'): ?>
                                    <div>
                                        <label class="text-muted mb-2" style="font-size:13px;">🔗 Lien d'aperçu</label>
                                        <a href="<?php echo htmlspecialchars($produit['apercue']); ?>" target="_blank" class="btn btn-outline-info btn-sm w-100" style="border-radius:10px; padding:10px;">
                                            <i class="fa-solid fa-eye"></i> Voir la démo
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Carte Actions -->
                        <div class="card" style="border-radius:16px; border:1px solid #e5e7eb; box-shadow:0 5px 15px rgba(0,0,0,0.05);">
                            <div class="card-header" style="background:white; border-bottom:1px solid #e5e7eb; padding:15px 20px;">
                                <h5 style="margin:0; font-weight:700;"><i class="fa-solid fa-gavel me-2" style="color:#f59e0b;"></i>Actions</h5>
                            </div>
                            <div class="card-body" style="padding:20px;">
                                <?php if ($produit['statut'] === 'en_attente'): ?>
                                    <!-- Bouton Approuver -->
                                    <form method="POST" class="mb-3">
                                        <input type="hidden" name="action" value="approuver">
                                        <button type="submit" class="btn w-100" style="background:#10b981; color:white; border-radius:12px; padding:12px; font-weight:600;">
                                            <i class="fa-solid fa-check-circle me-2"></i> Approuver le produit
                                        </button>
                                    </form>

                                    <!-- Formulaire Refuser -->
                                    <form method="POST">
                                        <input type="hidden" name="action" value="refuser">
                                        <label class="form-label fw-bold" style="font-size:14px;">Motif du refus <span class="text-danger">*</span></label>
                                        <textarea name="motif" class="form-control mb-3" rows="3" placeholder="Expliquez pourquoi le produit est refusé..." required style="border-radius:10px;"></textarea>
                                        <button type="submit" class="btn w-100" style="background:#ef4444; color:white; border-radius:12px; padding:12px; font-weight:600;">
                                            <i class="fa-solid fa-times-circle me-2"></i> Refuser le produit
                                        </button>
                                    </form>
                                <?php elseif ($produit['statut'] === 'approuve'): ?>
                                    <div class="text-center">
                                        <i class="fa-solid fa-circle-check" style="font-size:50px; color:#10b981; margin-bottom:15px;"></i>
                                        <h5 style="color:#065f46;">Produit approuvé</h5>
                                        <p class="text-muted">Ce produit est visible sur le site.</p>
                                        <form method="POST" class="mt-3">
                                            <input type="hidden" name="action" value="refuser">
                                            <label class="form-label fw-bold" style="font-size:14px;">Motif du refus <span class="text-danger">*</span></label>
                                            <textarea name="motif" class="form-control mb-3" rows="3" placeholder="Expliquez pourquoi vous changez d'avis..." required style="border-radius:10px;"></textarea>
                                            <button type="submit" class="btn w-100" style="background:#ef4444; color:white; border-radius:12px; padding:12px; font-weight:600;">
                                                <i class="fa-solid fa-times-circle me-2"></i> Refuser le produit
                                            </button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center">
                                        <i class="fa-solid fa-circle-xmark" style="font-size:50px; color:#ef4444; margin-bottom:15px;"></i>
                                        <h5 style="color:#991b1b;">Produit refusé</h5>
                                        <?php if (!empty($produit['commentaire'])): ?>
                                            <div style="background:#fee2e2; border-radius:10px; padding:15px; margin-top:10px; text-align:left;">
                                                <strong style="font-size:13px;">Motif du refus :</strong>
                                                <p style="margin:5px 0 0 0; font-size:14px;"><?php echo nl2br(htmlspecialchars($produit['commentaire'])); ?></p>
                                            </div>
                                        <?php endif; ?>
                                        <form method="POST" class="mt-3">
                                            <input type="hidden" name="action" value="approuver">
                                            <button type="submit" class="btn w-100" style="background:#10b981; color:white; border-radius:12px; padding:12px; font-weight:600;">
                                                <i class="fa-solid fa-check-circle me-2"></i> Approuver finalement
                                            </button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        transition: all 0.2s;
    }
</style>

<?php require('footer.php'); ?>