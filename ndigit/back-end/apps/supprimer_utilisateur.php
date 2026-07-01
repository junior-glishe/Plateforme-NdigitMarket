<?php
require('header.php');
require('../../include/connect.php');

$id_uti = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Récupérer les infos de l'utilisateur
$stmt = $database->prepare("SELECT * FROM utilisateur WHERE id_uti = ?");
$stmt->execute([$id_uti]);
$user = $stmt->fetch();

if (!$user) {
    echo '<script>alert("Utilisateur introuvable."); window.location.href="client.php";</script>';
    exit;
}

// Empêcher la suppression de l'admin principal (id_uti = 1)
if ($id_uti == 1) {
    echo '<script>alert("Vous ne pouvez pas supprimer le compte administrateur principal."); window.location.href="client.php";</script>';
    exit;
}

// Traitement de la suppression
if (isset($_POST['confirmer'])) {
    // Supprimer les commandes
    $database->prepare("DELETE FROM commande WHERE id_client = ?")->execute([$id_uti]);
    // Supprimer les téléchargements
    $database->prepare("DELETE FROM telechargements WHERE id_user = ?")->execute([$id_uti]);
    // Supprimer les produits du vendeur
    $database->prepare("DELETE FROM produits WHERE id_vendeur = ?")->execute([$id_uti]);
    // Supprimer la demande vendeur
    $database->prepare("DELETE FROM demandes_vendeur WHERE id_uti = ?")->execute([$id_uti]);
    // Supprimer le portefeuille
    $database->prepare("DELETE FROM portefeuille_vendeur WHERE id_uti = ?")->execute([$id_uti]);
    // Supprimer les retraits
    $database->prepare("DELETE FROM retraits WHERE id_uti = ?")->execute([$id_uti]);
    // Supprimer l'abonnement
    $database->prepare("DELETE FROM abonnement WHERE id_uti2 = ?")->execute([$id_uti]);
    // Supprimer l'utilisateur
    $database->prepare("DELETE FROM utilisateur WHERE id_uti = ?")->execute([$id_uti]);
    
    echo '<script>alert("Utilisateur supprimé avec succès."); window.location.href="client.php";</script>';
    exit;
}
?>

<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-5 mx-auto">
                <div class="card" style="border-radius:16px; border:1px solid #e5e7eb; box-shadow:0 10px 30px -10px rgba(0,0,0,0.1);">
                    <div class="card-header" style="background:white; border-bottom:1px solid #e5e7eb; padding:20px 25px;">
                        <h5 style="margin:0; font-weight:700; color:#ef4444;">⚠️ Supprimer l'utilisateur</h5>
                    </div>
                    <div class="card-body" style="padding:25px;">
                        <div class="text-center mb-4">
                            <i class="fa-solid fa-triangle-exclamation" style="font-size:60px; color:#ef4444;"></i>
                        </div>
                        
                        <p style="font-size:15px; line-height:1.6; text-align:center;">
                            Vous êtes sur le point de supprimer définitivement l'utilisateur :
                        </p>
                        
                        <div style="background:#f9fafb; border-radius:12px; padding:15px; margin-bottom:20px; text-align:center;">
                            <strong style="font-size:18px;"><?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></strong><br>
                            <span style="color:#6b7280;"><?php echo htmlspecialchars($user['email']); ?></span><br>
                            <span style="color:#6b7280;">ID: #<?php echo $user['id_uti']; ?></span>
                        </div>

                        <p style="font-size:14px; color:#ef4444; text-align:center;">
                            <strong>Cette action est irréversible.</strong> Toutes les données associées (commandes, produits, abonnements) seront supprimées.
                        </p>

                        <form method="POST" class="d-flex gap-3 mt-4">
                            <a href="client.php" class="btn btn-outline-secondary w-50" style="border-radius:12px; padding:12px;">Annuler</a>
                            <button type="submit" name="confirmer" class="btn w-50" style="background:#ef4444; color:white; border-radius:12px; padding:12px; font-weight:600;">
                                <i class="fa-solid fa-trash me-2"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('footer.php'); ?>