<?php
// ---- INITIALISATION SESSION ET CONNEXION ----
require('header.php');

// Vérification connexion + vendeur
if (!isset($_SESSION['user_id'])) {
    echo '<meta http-equiv="refresh" content="0;URL=login">';
    exit;
}
$stmtUser = $database->prepare("SELECT id_uti FROM utilisateur WHERE email = ?");
$stmtUser->execute([$_SESSION['email']]);
$user = $stmtUser->fetch();
if (!$user) { echo 'Utilisateur introuvable'; exit; }

$checkVendeur = $database->prepare("SELECT id FROM demandes_vendeur WHERE id_uti = ? AND statut = 'acceptee'");
$checkVendeur->execute([$user['id_uti']]);
if (!$checkVendeur->fetch()) {
    echo '<script>alert("Accès refusé."); window.location.href="compte.php";</script>';
    exit;
}

// Récupérer l'ID du produit
$id_produit = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmtProd = $database->prepare("SELECT * FROM produits WHERE id = ? AND id_vendeur = ?");
$stmtProd->execute([$id_produit, $user['id_uti']]);
$produit = $stmtProd->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    echo '<script>alert("Produit introuvable."); window.location.href="liste_produits.php";</script>';
    exit;
}

$erreur = '';
$success = false;

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_article   = trim($_POST['nom_article'] ?? '');
    $prix          = $_POST['prix'] ?? 0;
    $prix_reduction = $_POST['prix_reduction'] ?? 0;
    $categorie_id  = $_POST['categorie'] ?? '';
    $sous_categorie = isset($_POST['sous_categorie']) && is_array($_POST['sous_categorie']) ? implode(',', $_POST['sous_categorie']) : '';
    $description   = $_POST['description'] ?? '';
    $apercu        = $_POST['apercu'] ?? '';
    $auteur        = $_POST['auteur'] ?? '';

    if (empty($nom_article)) {
        $erreur = "Le nom du produit est obligatoire.";
    } else {
        // Mise à jour des champs textuels + remise en attente
        $update = $database->prepare("UPDATE produits SET nom_article=?, prix=?, prix_reduction=?, categorie_id=?, sous_categorie=?, description=?, apercue=?, auteur=?, statut='en_attente' WHERE id=? AND id_vendeur=?");
        $update->execute([$nom_article, $prix, $prix_reduction, $categorie_id, $sous_categorie, $description, $apercu, $auteur, $id_produit, $user['id_uti']]);

        // Dossier de destination
        $targetDir = 'back-end/apps/uploads/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Gestion de l'image (si un fichier est envoyé)
        if (!empty($_FILES['imageProduit']['name']) && $_FILES['imageProduit']['error'] === UPLOAD_ERR_OK) {
            $imgExt = strtolower(pathinfo($_FILES['imageProduit']['name'], PATHINFO_EXTENSION));
            if (in_array($imgExt, ['jpg','jpeg','png','gif'])) {
                $imgName = uniqid() . '_' . basename($_FILES['imageProduit']['name']);
                $imgPath = $targetDir . $imgName;
                if (move_uploaded_file($_FILES['imageProduit']['tmp_name'], $imgPath)) {
                    // Supprimer ancienne image
                    if (!empty($produit['image']) && file_exists('back-end/apps/' . $produit['image'])) {
                        unlink('back-end/apps/' . $produit['image']);
                    }
                    // Stocker le chemin relatif (sans back-end/apps/)
                    $imgDbPath = 'uploads/' . $imgName;
                    $database->prepare("UPDATE produits SET image=? WHERE id=?")->execute([$imgDbPath, $id_produit]);
                }
            }
        }

        // Gestion du fichier ZIP (si envoyé)
        if (!empty($_FILES['fichierUpload']['name']) && $_FILES['fichierUpload']['error'] === UPLOAD_ERR_OK) {
            if (pathinfo($_FILES['fichierUpload']['name'], PATHINFO_EXTENSION) === 'zip') {
                $zipName = uniqid() . '_' . basename($_FILES['fichierUpload']['name']);
                $zipPath = $targetDir . $zipName;
                if (move_uploaded_file($_FILES['fichierUpload']['tmp_name'], $zipPath)) {
                    // Supprimer ancien fichier
                    if (!empty($produit['fichier']) && file_exists('back-end/apps/' . $produit['fichier'])) {
                        unlink('back-end/apps/' . $produit['fichier']);
                    }
                    // Stocker le chemin relatif (sans back-end/apps/)
                    $zipDbPath = 'uploads/' . $zipName;
                    $database->prepare("UPDATE produits SET fichier=? WHERE id=?")->execute([$zipDbPath, $id_produit]);
                }
            }
        }

        $success = true;
        // Recharger le produit fraîchement mis à jour
        $stmtProd->execute([$id_produit, $user['id_uti']]);
        $produit = $stmtProd->fetch();
    }
}

// Extraire le nom du fichier pour l'affichage (sans le chemin uploads/)
$nomFichierAffiche = $produit['fichier'];
if (strpos($nomFichierAffiche, 'uploads/') === 0) {
    $nomFichierAffiche = substr($nomFichierAffiche, 8); // Enlève "uploads/"
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le produit – NDIGITMARKET</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #087d67;
            --primary-dark: #065a4a;
            --dark: #0f1923;
            --bg: #f9fafb;
            --border: #e5e7eb;
            --radius: 20px;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); }
        .page-container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        .card { border-radius: var(--radius); border: 1px solid var(--border); box-shadow: 0 15px 40px rgba(0,0,0,0.08); background: white; }
        .card-header { background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); border-bottom: 1px solid var(--border); padding: 25px 30px; border-radius: var(--radius) var(--radius) 0 0; }
        .card-header h5 { font-size: 22px; font-weight: 700; color: var(--dark); margin-bottom: 5px; }
        .card-body { padding: 30px; }
        .form-label { font-weight: 600; color: var(--dark); margin-bottom: 6px; font-size: 14px; }
        .form-control, .input-group-text { border-radius: 12px; border: 1px solid var(--border); padding: 10px 15px; }
        .input-group-text { background: #f8f9fa; font-weight: 600; }
        .btn-primary { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border: none; border-radius: 40px; padding: 12px 35px; font-weight: 600; transition: 0.3s; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(8,125,103,0.35); }
        .btn-outline-secondary { border-radius: 40px; padding: 12px 30px; font-weight: 600; border: 1px solid var(--border); background: white; color: var(--dark); }
        .btn-outline-secondary:hover { background: #f3f4f6; }
        .upload-area { border: 2px dashed var(--border); border-radius: 12px; padding: 20px; background: #f8f9fa; transition: 0.3s; }
        .upload-area:hover { border-color: var(--primary); background: #e8f5f2; }
        .product-thumb { max-height: 80px; border-radius: 8px; margin-top: 10px; }
        .progress { height: 30px; border-radius: 15px; display: none; }
        .progress-bar { background: var(--primary); color: white; font-weight: 600; }
    </style>
</head>
<body>

<div class="page-container">
    <div class="card">
        <div class="card-header">
            <h5><i class="fa-solid fa-pen-to-square me-2" style="color:#087d67;"></i>Modifier le produit</h5>
            <p class="text-muted">Modifiez les informations du produit. Toute modification remettra le produit en attente de validation.</p>
        </div>

        <!-- Message si le produit a été refusé -->
<?php if ($produit['statut'] === 'refuse' && !empty($produit['commentaire'])): ?>
    <div style="background:#fee2e2; border:1px solid #fecaca; border-radius:12px; padding:15px; margin-bottom:20px; display:flex; align-items:flex-start; gap:10px;">
        <i class="fa-solid fa-circle-exclamation" style="color:#ef4444; font-size:20px; margin-top:2px;"></i>
        <div>
            <strong style="color:#991b1b;">Produit refusé</strong>
            <p style="margin:5px 0 0 0; color:#991b1b; font-size:14px;">
                <strong>Motif :</strong> <?php echo nl2br(htmlspecialchars($produit['commentaire'])); ?>
            </p>
            <p style="margin:10px 0 0 0; font-size:13px; color:#7f1d1d;">
                Veuillez corriger les éléments demandés et soumettre à nouveau.
            </p>
        </div>
    </div>
<?php endif; ?>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data" id="editForm" onsubmit="return handleSubmit(event)">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nom de l'article <span class="text-danger">*</span></label>
                        <input type="text" name="nom_article" class="form-control" value="<?php echo htmlspecialchars($produit['nom_article']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Auteur <span class="text-danger">*</span></label>
                        <input type="text" name="auteur" class="form-control" value="<?php echo htmlspecialchars($produit['auteur']); ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Prix (CFA)</label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="prix" class="form-control" value="<?php echo $produit['prix']; ?>" required>
                            <span class="input-group-text">CFA</span>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Prix promo (optionnel)</label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="prix_reduction" class="form-control" value="<?php echo $produit['prix_reduction']; ?>">
                            <span class="input-group-text">CFA</span>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Lien d'aperçu</label>
                        <input type="url" name="apercu" class="form-control" value="<?php echo htmlspecialchars($produit['apercue']); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                        <select name="categorie" id="categorie" class="form-control" required>
                            <option value="">Sélectionnez une catégorie</option>
                            <?php
                            $cats = $database->query("SELECT id, nom_categorie FROM categories ORDER BY nom_categorie");
                            while($cat = $cats->fetch(PDO::FETCH_ASSOC)):
                                $selected = $cat['id'] == $produit['categorie_id'] ? 'selected' : '';
                            ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($cat['nom_categorie']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Sous-catégorie (optionnel)</label>
                        <select class="form-control" name="sous_categorie[]" id="sous_categorie" multiple>
                            <option value="">Chargement…</option>
                        </select>
                        <small class="text-muted">Maintenez Ctrl pour sélectionner plusieurs</small>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description détaillée <span class="text-danger">*</span></label>
                    <textarea name="description" id="description" class="form-control" rows="10"><?php echo htmlspecialchars($produit['description']); ?></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Image (laisser vide pour conserver)</label>
                        <div class="upload-area">
                            <input type="file" name="imageProduit" id="imageProduit" class="form-control" accept="image/*">
                            <?php if($produit['image']): ?>
                                <img src="back-end/apps/<?php echo htmlspecialchars($produit['image']); ?>" class="product-thumb" id="imagePreview">
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fichier ZIP (laisser vide pour conserver)</label>
                        <input type="file" name="fichierUpload" id="fichierUpload" class="form-control" accept=".zip">
                        <small class="text-muted">Fichier actuel : <strong><?php echo htmlspecialchars($nomFichierAffiche); ?></strong></small>
                        <div class="progress mt-2" id="progressContainer">
                            <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%">0%</div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-2"></i>Enregistrer les modifications</button>
                    <a href="liste_produits.php" class="btn btn-outline-secondary ms-2">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- TinyMCE -->
<script src="assets/js/tinymce/tinymce.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    tinymce.init({
        selector: '#description',
        height: 400,
        plugins: 'link lists image code table',
        toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent | link image | code',
        menubar: false,
        branding: false
    });

    // Chargement dynamique des sous-catégories
    const categorieSelect = document.getElementById('categorie');
    const sousCatSelect   = document.getElementById('sous_categorie');
    const selectedSous    = "<?php echo $produit['sous_categorie']; ?>".split(',').filter(Boolean);

    async function loadSousCategories(catId) {
        sousCatSelect.innerHTML = '<option value="">Chargement…</option>';
        if (!catId) {
            sousCatSelect.innerHTML = '<option value="">Sélectionnez d\'abord une catégorie</option>';
            return;
        }
        try {
            const resp = await fetch('get_sous_categories.php?categorie_id=' + catId);
            const data = await resp.json();
            sousCatSelect.innerHTML = '<option value="">Sélectionnez une sous-catégorie</option>';
            data.forEach(sc => {
                const opt = document.createElement('option');
                opt.value = sc.id;
                opt.textContent = sc.nom_sous_categorie;
                if (selectedSous.includes(sc.id)) opt.selected = true;
                sousCatSelect.appendChild(opt);
            });
        } catch (e) {
            sousCatSelect.innerHTML = '<option value="">Erreur de chargement</option>';
        }
    }

    categorieSelect.addEventListener('change', function() {
        loadSousCategories(this.value);
    });

    if (categorieSelect.value) {
        loadSousCategories(categorieSelect.value);
    }

    // Aperçu de l'image
    document.getElementById('imageProduit').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('imagePreview');
        if (file) {
            const reader = new FileReader();
            reader.onload = ev => { 
                if (preview) {
                    preview.src = ev.target.result;
                } else {
                    const img = document.createElement('img');
                    img.src = ev.target.result;
                    img.classList.add('product-thumb');
                    img.id = 'imagePreview';
                    this.parentElement.appendChild(img);
                }
            };
            reader.readAsDataURL(file);
        }
    });

    // Barre de progression pour le ZIP
    const form = document.getElementById('editForm');
    const progressContainer = document.getElementById('progressContainer');
    const progressBar = document.getElementById('progressBar');
    const fichierUpload = document.getElementById('fichierUpload');

    function handleSubmit(e) {
        const file = fichierUpload.files[0];
        if (!file) {
            return true;
        }

        e.preventDefault();
        document.getElementById('description').value = tinymce.get('description').getContent();

        if (file.size > 500 * 1024 * 1024) {
            Swal.fire('Erreur', 'Le fichier ne doit pas dépasser 500 Mo.', 'error');
            return false;
        }
        if (!file.name.toLowerCase().endsWith('.zip')) {
            Swal.fire('Erreur', 'Seul le format ZIP est accepté.', 'error');
            return false;
        }

        progressContainer.style.display = 'block';
        progressBar.style.width = '0%';
        progressBar.textContent = '0%';

        const formData = new FormData(form);
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '', true);

        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
                const percent = Math.round((e.loaded / e.total) * 100);
                progressBar.style.width = percent + '%';
                progressBar.textContent = percent + '%';
            }
        };

        xhr.onload = function() {
            progressContainer.style.display = 'none';
            if (xhr.status === 200) {
                Swal.fire({
                    icon: 'success',
                    title: 'Modification réussie !',
                    text: 'Votre produit a été mis à jour et remis en attente de validation.',
                    confirmButtonColor: '#087d67'
                }).then(() => {
                    window.location.href = 'liste_produits.php';
                });
            } else {
                Swal.fire('Erreur', 'Échec de la modification.', 'error');
            }
        };

        xhr.onerror = function() {
            progressContainer.style.display = 'none';
            Swal.fire('Erreur', 'Problème de connexion.', 'error');
        };

        xhr.send(formData);
        return false;
    }

    <?php if ($success): ?>
        Swal.fire({
            icon: 'success',
            title: 'Modification réussie !',
            text: 'Votre produit a été mis à jour et remis en attente de validation.',
            confirmButtonColor: '#087d67'
        }).then(() => {
            window.location.href = 'liste_produits.php';
        });
    <?php endif; ?>

    <?php if ($erreur): ?>
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: '<?php echo addslashes($erreur); ?>',
            confirmButtonColor: '#ef4444'
        });
    <?php endif; ?>
</script>

<?php require('footer.php'); ?>
</body>
</html>