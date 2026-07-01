<?php
require('header.php');
require('../../include/connect.php');

$id_uti = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $database->prepare("SELECT * FROM utilisateur WHERE id_uti = ?");
$stmt->execute([$id_uti]);
$user = $stmt->fetch();

if (!$user) {
    echo '<script>alert("Utilisateur introuvable."); window.location.href="client.php";</script>';
    exit;
}

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $type = $_POST['type'] ?? '-';
    
    if (empty($nom) || empty($prenom) || empty($email)) {
        $message = "Tous les champs sont obligatoires.";
        $messageType = 'error';
    } else {
        $update = $database->prepare("UPDATE utilisateur SET nom = ?, prenom = ?, email = ?, type = ? WHERE id_uti = ?");
        $update->execute([$nom, $prenom, $email, $type, $id_uti]);
        $message = "Utilisateur modifié avec succès !";
        $messageType = 'success';
        
        // Recharger les données
        $stmt->execute([$id_uti]);
        $user = $stmt->fetch();
    }
}
?>

<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                
                <?php if (!empty($message)): ?>
                    <div class="alert alert-<?php echo $messageType === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert" style="border-radius:12px;">
                        <?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card" style="border-radius:16px; border:1px solid #e5e7eb; box-shadow:0 10px 30px -10px rgba(0,0,0,0.1);">
                    <div class="card-header" style="background:white; border-bottom:1px solid #e5e7eb; padding:20px 25px;">
                        <h5 style="margin:0; font-weight:700;">✏️ Modifier l'utilisateur</h5>
                    </div>
                    <div class="card-body" style="padding:25px;">
                        <form method="POST">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nom</label>
                                    <input type="text" name="nom" class="form-control" value="<?php echo htmlspecialchars($user['nom']); ?>" required style="border-radius:10px;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Prénom</label>
                                    <input type="text" name="prenom" class="form-control" value="<?php echo htmlspecialchars($user['prenom']); ?>" required style="border-radius:10px;">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required style="border-radius:10px;">
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Type de compte</label>
                                <select name="type" class="form-select" style="border-radius:10px;">
                                    <option value="-" <?php echo $user['type'] == '-' ? 'selected' : ''; ?>>👤 Simple</option>
                                    <option value="pro" <?php echo $user['type'] == 'pro' ? 'selected' : ''; ?>>⭐ Premium</option>
                                </select>
                            </div>

                            <div class="d-flex gap-3">
                                <a href="client.php" class="btn btn-outline-secondary w-50" style="border-radius:12px; padding:12px;">Annuler</a>
                                <button type="submit" class="btn w-50" style="background:#087d67; color:white; border-radius:12px; padding:12px; font-weight:600;">
                                    <i class="fa-solid fa-floppy-disk me-2"></i> Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('footer.php'); ?>