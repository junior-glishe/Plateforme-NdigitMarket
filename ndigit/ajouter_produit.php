<?php
require('header.php');

// ── Vérification : utilisateur connecté ──
    if (!isset($_SESSION['user_id'])) {
        echo '<meta http-equiv="refresh" content="0;URL=login">';
        exit;
    }

// Récupération de l'utilisateur via l'email (plus fiable)
$stmt = $database->prepare("SELECT * FROM utilisateur WHERE email = ?");
$stmt->execute([$_SESSION['email']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<script>
        Swal.fire({
            icon: "error",
            title: "Erreur",
            text: "Utilisateur introuvable.",
            confirmButtonColor: "#ef4444"
        }).then(() => {
            window.location.href = "login";
        });
    </script>';
    require('footer.php');
    exit;
}

// Vérification du statut vendeur (via la demande associée à cet utilisateur)
$checkVendeur = $database->prepare("
    SELECT statut 
    FROM demandes_vendeur 
    WHERE id_uti = ?
");
$checkVendeur->execute([$user['id_uti']]);
$demande = $checkVendeur->fetch(PDO::FETCH_ASSOC);

if (!$demande) {
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<script>
        Swal.fire({
            icon: "error",
            title: "Aucune demande",
            text: "Aucune demande de vendeur trouvée pour votre compte. Faites d\'abord une demande depuis votre tableau de bord.",
            confirmButtonColor: "#ef4444"
        }).then(() => {
            window.location.href = "liste_produits";
        });
    </script>';
    require('footer.php');
    exit;
}

$statut = strtolower(trim($demande['statut']));
if ($statut !== 'acceptee') {
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<script>
        Swal.fire({
            icon: "error",
            title: "Statut invalide",
            text: "Votre demande a le statut « ' . addslashes($demande['statut']) . ' ». Seul le statut « acceptee » permet d\'ajouter des produits.",
            confirmButtonColor: "#ef4444"
        }).then(() => {
            window.location.href = "liste_produits";
        });
    </script>';
    require('footer.php');
    exit;
}

// ── Tout est OK, on affiche le formulaire ──
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajouter un produit - NDIGITMARKET</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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
        .progress { height: 30px; border-radius: 15px; }
        .progress-bar { background: var(--primary); color: white; font-weight: 600; transition: width 0.4s ease; }
        .progress-container { display: none; margin-top: 15px; }
        .progress-text { text-align: center; margin-top: 5px; font-size: 13px; color: var(--dark); }
    </style>
</head>
<body>

<div class="page-container">
    <div class="card">
        <div class="card-header">
            <h5><i class="fa-solid fa-plus-circle me-2" style="color:#087d67;"></i>Ajouter un nouveau produit</h5>
            <p class="text-muted">Remplissez tous les champs pour publier un produit téléchargeable. Prix en francs CFA (XOF).</p>
        </div>
        <div class="card-body">
            <form id="uploadForm" method="POST" action="upload.php" enctype="multipart/form-data">

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label">Nom de l'article <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="nom_article" placeholder="Ex: Template WordPress Premium" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Lien d'aperçu <span class="text-muted">(optionnel)</span></label>
                            <input class="form-control" type="url" name="apercu" placeholder="https://demo.example.com">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Prix <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input class="form-control" type="number" name="prix" placeholder="0" step="0.01" required>
                                <span class="input-group-text">CFA</span>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Prix promotionnel <span class="text-muted">(optionnel)</span></label>
                            <div class="input-group">
                                <input class="form-control" type="number" name="prix_reduction" placeholder="0" step="0.01">
                                <span class="input-group-text">CFA</span>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Auteur <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="auteur" value="<?php echo htmlspecialchars($user['nom'] . ' ' . $user['prenom']); ?>" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label">Image du produit <span class="text-danger">*</span></label>
                            <div class="upload-area">
                                <input class="form-control" type="file" name="imageProduit" id="imageProduit" accept="image/jpeg,image/png,image/gif" required>
                                <div class="text-center mt-2">
                                    <img id="imagePreview" src="#" alt="Aperçu" class="img-thumbnail d-none" style="max-width:200px;">
                                </div>
                                <div id="imageError" class="text-danger small mt-1 d-none"></div>
                                <small class="text-muted">JPG, PNG, GIF - Max 2 Mo</small>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                            <select class="form-control" name="categorie" id="categorie" required>
                                <option value="">Sélectionnez une catégorie</option>
                                <?php
                                $cats = $database->query("SELECT id, nom_categorie FROM categories ORDER BY nom_categorie");
                                while ($cat = $cats->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<option value="'.$cat['id'].'">'.htmlspecialchars($cat['nom_categorie']).'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Sous-catégorie <span class="text-muted">(optionnel)</span></label>
                            <select class="form-control" name="sous_categorie[]" id="sous_categorie" multiple>
                                <option value="">Sélectionnez une sous-catégorie</option>
                            </select>
                            <small class="text-muted">Maintenez Ctrl pour sélectionner plusieurs</small>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Fichier ZIP <span class="text-danger">*</span></label>
                            <div class="file-upload-area">
                                <input class="form-control" type="file" name="fichierUpload" id="fichierUpload" accept=".zip,application/zip" required>
                                <!-- Barre de progression déplacée en dehors de l'upload-area pour plus de visibilité -->
                                <div class="progress-container" id="progressContainer">
                                    <div class="progress">
                                        <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%">0%</div>
                                    </div>
                                    <div class="progress-text" id="progressText">Téléversement en cours...</div>
                                </div>
                                <small class="text-muted">Format ZIP uniquement - Taille max: 500 Mo</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Description détaillée <span class="text-danger">*</span></label>
                    <textarea name="description" id="description" class="form-control" rows="10"></textarea>
                </div>

                <div class="text-end mt-4">
                    <a href="liste_produits.php" class="btn btn-outline-secondary me-2">
                        <i class="fa-regular fa-times"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fa-solid fa-cloud-upload-alt"></i> Publier le produit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- TinyMCE -->
<script src="assets/js/tinymce/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#description',
        height: 400,
        plugins: 'link lists image code table',
        toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent | link image | code',
        menubar: false,
        branding: false
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Aperçu de l'image
    document.getElementById('imageProduit').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('imagePreview');
        const error = document.getElementById('imageError');
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                error.textContent = "L'image ne doit pas dépasser 2 Mo.";
                error.classList.remove('d-none');
                preview.classList.add('d-none');
                return;
            }
            const validTypes = ['image/jpeg','image/png','image/gif'];
            if (!validTypes.includes(file.type)) {
                error.textContent = "Formats acceptés : JPG, PNG, GIF.";
                error.classList.remove('d-none');
                preview.classList.add('d-none');
                return;
            }
            error.classList.add('d-none');
            const reader = new FileReader();
            reader.onload = ev => { preview.src = ev.target.result; preview.classList.remove('d-none'); };
            reader.readAsDataURL(file);
        }
    });

    // Chargement dynamique des sous-catégories
    document.getElementById('categorie').addEventListener('change', function() {
        const categId = this.value;
        const sousCat = document.getElementById('sous_categorie');
        sousCat.innerHTML = '<option value="">Chargement...</option>';
        if (categId) {
            fetch('get_sous_categories.php?categorie_id=' + categId)
                .then(r => r.json())
                .then(data => {
                    sousCat.innerHTML = '<option value="">Sélectionnez une sous-catégorie</option>';
                    data.forEach(sc => {
                        sousCat.innerHTML += `<option value="${sc.id}">${sc.nom_sous_categorie}</option>`;
                    });
                })
                .catch(() => {
                    sousCat.innerHTML = '<option value="">Erreur de chargement</option>';
                });
        } else {
            sousCat.innerHTML = '<option value="">Sélectionnez d\'abord une catégorie</option>';
        }
    });

    // Soumission avec barre de progression
    const form = document.getElementById('uploadForm');
    const progressContainer = document.getElementById('progressContainer');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        document.getElementById('description').value = tinymce.get('description').getContent();

        const fileInput = document.getElementById('fichierUpload');
        const file = fileInput.files[0];
        if (!file) {
            Swal.fire('Erreur', 'Veuillez sélectionner un fichier ZIP.', 'error');
            return;
        }
        if (file.size > 500 * 1024 * 1024) {
            Swal.fire('Erreur', 'Le fichier ne doit pas dépasser 500 Mo.', 'error');
            return;
        }
        if (!file.name.toLowerCase().endsWith('.zip')) {
            Swal.fire('Erreur', 'Seul le format ZIP est accepté.', 'error');
            return;
        }

        // Afficher la barre de progression
        progressContainer.style.display = 'block';
        progressBar.style.width = '0%';
        progressBar.textContent = '0%';
        progressText.textContent = 'Téléversement en cours...';
        submitBtn.disabled = true;

        const formData = new FormData(form);
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'upload.php', true);

        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
                const percent = Math.round((e.loaded / e.total) * 100);
                progressBar.style.width = percent + '%';
                progressBar.textContent = percent + '%';
                progressText.textContent = `Téléversement en cours... ${percent}%`;
            }
        };

        xhr.onload = function() {
            progressContainer.style.display = 'none';
            submitBtn.disabled = false;
            if (xhr.status === 200) {
                try {
                    const res = JSON.parse(xhr.responseText);
                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Produit ajouté avec succès !',
                            text: res.message || 'Votre produit a été soumis et est en attente de validation.',
                            confirmButtonColor: '#087d67'
                        }).then(() => {
                            window.location.href = 'liste_produits.php';
                        });
                    } else {
                        Swal.fire('Erreur', res.message || 'Une erreur est survenue.', 'error');
                    }
                } catch(er) {
                    Swal.fire('Erreur', 'Réponse inattendue du serveur.', 'error');
                }
            } else {
                Swal.fire('Erreur', 'Échec du téléversement.', 'error');
            }
        };

        xhr.onerror = function() {
            progressContainer.style.display = 'none';
            submitBtn.disabled = false;
            Swal.fire('Erreur', 'Problème de connexion.', 'error');
        };

        xhr.send(formData);
    });
</script>

<?php require('footer.php'); ?>
</body>
</html>