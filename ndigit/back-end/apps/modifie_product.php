<?php
// Démarrer la session si nécessaire
require('header.php');

// Récupérer l'ID du produit passé dans l'URL
if (isset($_GET['id'])) {
    $encodedId = $_GET['id'];
    $productId = base64_decode($encodedId);

    // Vérifier si l'ID est valide (non vide et numérique)
    if (!is_numeric($productId)) {
        $_SESSION['error'] = "ID de produit invalide.";
        echo '<meta http-equiv="refresh" content="0;URL=produits">';
        exit();
    }

    // Préparer la requête pour récupérer les informations du produit
    $query = $database->prepare("SELECT * FROM produits WHERE id = :id");
    $query->execute(['id' => $productId]);
    $product = $query->fetch(PDO::FETCH_ASSOC);

    // Si le produit n'existe pas, rediriger vers une page d'erreur
    if (!$product) {
        $_SESSION['error'] = "Ce produit n'existe pas.";
        echo '<meta http-equiv="refresh" content="0;URL=produits">';
        exit();
    }
} else {
    $_SESSION['error'] = "Produit introuvable.";
    echo '<meta http-equiv="refresh" content="0;URL=produits">';
    exit();
}


$aa=$product['description'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier le produit</title>
</head>
<body>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="col-sm-8 m-auto">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-header-2">
                                    <h5>Modifier l'article</h5>
                                </div>

                                <form id="editProductForm" class="theme-form theme-form-2 mega-form" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="product_id" value="<?= $product['id']; ?>">

                                    <!-- Nom de l'article -->
                                    <div class="mb-4 row align-items-center">
                                        <label class="form-label-title col-sm-3 mb-0">Nom de l'article</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text" name="nom_article" id="nom_article" value="<?= $product['nom_article']; ?>" placeholder="Nom de l'article" required>
                                        </div>
                                    </div>

<div class="mb-4 row align-items-center">
    <label class="form-label-title col-sm-3 mb-0">Lien aperçu</label>
    <div class="col-sm-9">
        <?php if ($product['apercue'] != '-') { ?>
            <!-- Afficher le lien actuel en texte -->
            <p id="currentLink"><?= $product['apercue']; ?></p>
            <!-- Bouton pour modifier le lien -->
            <button type="button" class="btn btn-warning mt-2" id="modifyLinkButton">Modifier le lien</button>

            <!-- Champ pour modifier le lien, caché au départ -->
            <div id="linkInput" style="display: none;">
                <input class="form-control mt-2" type="text" id="apercueInput" name="apercue" placeholder="Entrez le nouveau lien" value="<?= $product['apercue']; ?>">
            </div>
        <?php } else { ?>
            <!-- Si aucun lien n'est présent, afficher un bouton pour ajouter un lien -->
            <button type="button" class="btn btn-primary" id="addLinkButton">Ajouter un lien</button>

            <!-- Champ pour ajouter un lien, caché au départ -->
            <div id="linkInput" style="display: none;">
                <input class="form-control mt-2" type="text" id="apercueInput" name="apercue" placeholder="Entrez l'URL d'aperçu">
            </div>
        <?php } ?>
    </div>
</div>



<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function() {
    // Récupérer les éléments du DOM
    const modifyLinkButton = document.getElementById("modifyLinkButton");
    const addLinkButton = document.getElementById("addLinkButton");
    const linkInputDiv = document.getElementById("linkInput");
    const currentLinkText = document.getElementById("currentLink");

    // Afficher le champ de saisie pour ajouter un lien
    if (addLinkButton) {
        addLinkButton.addEventListener("click", function() {
            linkInputDiv.style.display = "block"; // Afficher le champ pour ajouter un lien
            addLinkButton.style.display = "none"; // Cacher le bouton "Ajouter un lien"
        });
    }

    // Afficher le champ de saisie pour modifier le lien actuel
    if (modifyLinkButton) {
        modifyLinkButton.addEventListener("click", function() {
            linkInputDiv.style.display = "block"; // Afficher le champ pour modifier le lien
            currentLinkText.style.display = "none"; // Cacher le lien actuel en texte
            modifyLinkButton.style.display = "none"; // Cacher le bouton "Modifier le lien"
        });
    }
});

</script>



                                    <!-- Image du produit -->
                                    <div class="mb-4 row align-items-center">
                                        <label class="col-sm-3 col-form-label form-label-title">Image du produit</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="file" name="imageProduit" id="imageProduit">
                                            
                                            <!-- Aperçu de l'image -->
                                            <div class="mt-2">
                                                <img id="imagePreview" src="<?= $product['image']; ?>" alt="Aperçu de l'image" class="img-thumbnail d-block" style="max-width: 200px;">
                                            </div>
                                        </div>
                                    </div>

                                <div class="mb-4 row align-items-center">
<label class="col-sm-3 col-form-label form-label-title">Catégorie</label>
<div class="col-sm-9">
    <select class="form-control" name="categorie" id="categorie" required>
        <option value="">Sélectionnez une catégorie</option>
        <?php
        // Récupérer les catégories depuis la base de données
        $query = $database->query("SELECT * FROM categories");
        $categories = $query->fetchAll(PDO::FETCH_ASSOC);

        // Vérifier si une catégorie est déjà sélectionnée pour ce produit
        foreach ($categories as $category) {
            // On vérifie si l'ID de la catégorie correspond à celui du produit
            $selected = $category['id'] == $product['categorie_id'] ? 'selected' : '';
            echo "<option value='" . $category['id'] . "' $selected>" . $category['nom_categorie'] . "</option>";
        }
        ?>
    </select>
</div>
</div>




<!-- Sélection des sous-catégories -->
<div class="mb-4 row align-items-center" id="selectSousCategories">
    <label class="col-sm-3 col-form-label form-label-title">Sous-catégories</label>
    <div class="col-sm-9">
        <select class="form-control" name="sous_categorie[]" id="sous_categorie" multiple>
            <!-- Les sous-catégories seront ajoutées ici dynamiquement -->
        </select>
    </div>
</div>

                            <!-- Prix -->
                                    <div class="mb-4 row align-items-center">
                                        <label class="form-label-title col-sm-3 mb-0">Prix</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="number" name="prix" id="prix" value="<?= $product['prix']; ?>" placeholder="Prix de l'article" step="0.01" required>
                                        </div>
                                    </div>

                                    <!-- Prix de réduction -->
                                    <div class="mb-4 row align-items-center">
                                        <label class="form-label-title col-sm-3 mb-0">Prix de réduction</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="number" name="prix_reduction" id="prix_reduction" value="<?= $product['prix_reduction']; ?>" placeholder="Prix de réduction" step="0.01">
                                        </div>
                                    </div>


                                    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>





 <script src="assets/js/tinymce/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#description',
            plugins: 'link lists image',
            toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent | link image',
            menubar: false
        });
    </script>


<!-- Description -->
<div class="mb-4 row align-items-center">
    <label class="form-label-title col-sm-3 mb-0">Description</label>
    <div class="col-sm-9">
        <!-- <div id="descriptionEditor" class="form-control" style="height: 150px;"></div> -->
        <textarea name="description" id="description" ><?php echo'' . $aa. '' ?></textarea>
    </div>
</div>



                                    <!-- Fichier ZIP -->
                                    <div class="mb-4 row align-items-center">
                                        <label class="col-sm-3 col-form-label form-label-title">Fichier ZIP</label>
                                        <div class="col-sm-9">
                                            <!-- Afficher le nom du fichier actuel -->
                                            <?php if ($product['fichier']) { ?>
                                                <p>Nom du fichier: <?= basename($product['fichier']); ?></p>
                                                <button type="button" class="btn btn-warning" id="changeFileBtn">Changer le fichier</button>
                                            <?php } ?>
                                            
                                            <!-- Champ pour télécharger le nouveau fichier -->
                                            <input class="form-control" type="file" name="fichierUpload" id="fichierUpload" accept=".zip" style="display:none;">
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary" name="envoyer">Sauvegarder les modifications</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
 
document.addEventListener("DOMContentLoaded", function() {
    let categorieSelect = document.getElementById("categorie");
    let sousCategorieSelect = document.getElementById("sous_categorie");
    let selectSousCategories = document.getElementById("selectSousCategories");

    // Fonction pour charger les sous-catégories de la catégorie choisie
    function loadSousCategories(categorieId) {
        sousCategorieSelect.innerHTML = "<option value=''>Sélectionnez une sous-catégorie</option>"; // Réinitialiser les sous-catégories

        if (categorieId) {
            fetch("get_subcategories2.php?categorie_id=" + categorieId)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        // Ajouter toutes les options de sous-catégories dans le select
                        data.forEach(subcat => {
                            let option = document.createElement("option");
                            option.value = subcat.id;
                            option.textContent = subcat.nom_sous_categorie;
                            sousCategorieSelect.appendChild(option);
                        });

                        // Pré-sélectionner les sous-catégories déjà associées au produit
                        const selectedSousCategories = "<?= $product['sous_categorie']; ?>".split(',');
                        console.log('Sous-catégories à sélectionner : ', selectedSousCategories);

                        // Parcours des options pour les marquer comme sélectionnées
                        Array.from(sousCategorieSelect.options).forEach(option => {
                            if (selectedSousCategories.includes(option.value)) {
                                option.selected = true; // Marquer l'option comme sélectionnée
                            }
                        });

                    } else {
                        let option = document.createElement("option");
                        option.value = "";
                        option.textContent = "Aucune sous-catégorie disponible";
                        sousCategorieSelect.appendChild(option);
                    }
                })
                .catch(error => {
                    console.error("Erreur lors du chargement des sous-catégories", error);
                    let option = document.createElement("option");
                    option.value = "";
                    option.textContent = "Erreur lors du chargement";
                    sousCategorieSelect.appendChild(option);
                });
        } else {
            sousCategorieSelect.innerHTML = "<option value=''>Sélectionnez une sous-catégorie</option>";
        }
    }

    // Lorsque l'utilisateur change de catégorie
    categorieSelect.addEventListener("change", function() {
        let categorieId = this.value;
        if (categorieId) {
            loadSousCategories(categorieId);
            selectSousCategories.classList.add("show"); // Afficher les sous-catégories
        } else {
            sousCategorieSelect.innerHTML = "<option value=''>Sélectionnez une sous-catégorie</option>";
            selectSousCategories.classList.remove("show"); // Masquer les sous-catégories si aucune catégorie sélectionnée
        }
    });

    // Lorsque la page est chargée, on charge les sous-catégories pour la catégorie déjà sélectionnée
    let initialCategorieId = categorieSelect.value;
    if (initialCategorieId) {
        loadSousCategories(initialCategorieId);
        selectSousCategories.classList.add("show"); // Afficher les sous-catégories si une catégorie est choisie
    }
});
    

// Afficher le champ de fichier lorsque l'on clique sur "Changer le fichier"
document.getElementById("changeFileBtn").addEventListener("click", function() {
    document.getElementById("fichierUpload").style.display = "block"; // Afficher le champ pour télécharger un fichier
});


    </script>
    <style type="text/css">
     #selectSousCategories {
    visibility: hidden;  /* Masquer sans affecter l'espace */
    transition: visibility 0.3s ease-in-out;
}

#selectSousCategories.show {
    visibility: visible;  /* Afficher quand nécessaire */
}

}



    </style>




 <?php
// Inclure le fichier de connexion à la base de données ou de configuration (assurez-vous que la connexion à la base est active)
// require('header.php');

// Démarrer la session si nécessaire
// require('header.php');

// Récupérer l'ID du produit passé dans l'URL
if (isset($_GET['id'])) {
    $encodedId = $_GET['id'];
    $productId = base64_decode($encodedId);

    // Vérifier si l'ID est valide (non vide et numérique)
    if (!is_numeric($productId)) {
        $_SESSION['error'] = "ID de produit invalide.";
        echo '<meta http-equiv="refresh" content="0;URL=produits">'; 
        exit();
    }

    // Préparer la requête pour récupérer les informations du produit
    $query = $database->prepare("SELECT * FROM produits WHERE id = :id");
    $query->execute(['id' => $productId]);
    $product = $query->fetch(PDO::FETCH_ASSOC);

    // Si le produit n'existe pas, rediriger vers une page d'erreur
    if (!$product) {
        $_SESSION['error'] = "Ce produit n'existe pas.";
        echo '<meta http-equiv="refresh" content="0;URL=produits">'; 
        exit();
    }
} else {
    $_SESSION['error'] = "Produit introuvable.";
    echo '<meta http-equiv="refresh" content="0;URL=produits">'; 
    exit();
}

// Vérification des informations du formulaire (si la méthode POST est utilisée)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $product_id = $_POST['product_id'];
    $nom_article = $_POST['nom_article'];
    $categorie = $_POST['categorie'];
    $prix = $_POST['prix'];
    $prix_reduction = $_POST['prix_reduction'];
    $description = $_POST['description'];

    // Récupérer le lien de l'aperçu (si modifié)
    $apercue = $_POST['apercue'] !== '' ? $_POST['apercue'] : $product['apercue'];  // Si le lien est modifié, on le prend, sinon on garde l'existant

    // Récupérer les sous-catégories sélectionnées (si elles existent)
    $sous_categories = isset($_POST['sous_categorie']) ? implode(',', $_POST['sous_categorie']) : '';

    // Définir le dossier d'upload pour l'image et le fichier
    $image_target_dir = 'uploads/';
    $fichier_target_dir = 'uploads/';

    // Traiter l'image si elle est téléchargée
    if ($_FILES['imageProduit']['error'] == UPLOAD_ERR_OK) {
        $image_produit = $_FILES['imageProduit']['name'];
        $image_target = $image_target_dir . uniqid() . '_' . basename($image_produit);

        // Déplacer l'image téléchargée vers le dossier 'uploads'
        if (move_uploaded_file($_FILES['imageProduit']['tmp_name'], $image_target)) {
            echo "Image téléchargée avec succès : " . $image_target;
        } else {
            $_SESSION['error'] = "Erreur lors du téléchargement de l'image.";
            echo '<meta http-equiv="refresh" content="0;URL=modifier_produit.php?id=' . base64_encode($product_id) . '">';
            exit();
        }
    } else {
        // Si aucune nouvelle image n'est envoyée, conserver l'image actuelle
        $image_target = $product['image'];
    }

    // Traiter le fichier ZIP si il est téléchargé
    if ($_FILES['fichierUpload']['error'] == UPLOAD_ERR_OK) {
        $fichier_upload = $_FILES['fichierUpload']['name'];
        $fichier_target = $fichier_target_dir . uniqid() . '_' . basename($fichier_upload);

        // Déplacer le fichier téléchargé vers le dossier 'uploads'
        if (move_uploaded_file($_FILES['fichierUpload']['tmp_name'], $fichier_target)) {
            echo "Fichier ZIP téléchargé avec succès : " . $fichier_target;
        } else {
            $_SESSION['error'] = "Erreur lors du téléchargement du fichier ZIP.";
            echo '<meta http-equiv="refresh" content="0;URL=modifier_produit.php?id=' . base64_encode($product_id) . '">';
            exit();
        }
    } else {
        // Si aucun fichier ZIP n'est téléchargé, conserver le fichier actuel
        $fichier_target = $product['fichier'];
    }

    // Préparer la requête de mise à jour du produit
    $query = "UPDATE produits SET 
                nom_article = :nom_article,
                categorie_id = :categorie,
                prix = :prix,
                prix_reduction = :prix_reduction,
                description = :description,
                apercue = :apercue,  -- Mise à jour du lien d'aperçu
                sous_categorie = :sous_categorie,
                image = :image,
                fichier = :fichier
              WHERE id = :product_id";

    $stmt = $database->prepare($query);
    $stmt->execute([
        ':nom_article' => $nom_article,
        ':categorie' => $categorie,
        ':prix' => $prix,
        ':prix_reduction' => $prix_reduction,
        ':description' => $description,
        ':apercue' => $apercue,  // On insère la nouvelle valeur ou celle existante
        ':sous_categorie' => $sous_categories,
        ':image' => $image_target,
        ':fichier' => $fichier_target,
        ':product_id' => $product_id
    ]);

    // Vérifier si la mise à jour a réussi
    if ($stmt->rowCount() > 0) {
        $_SESSION['success'] = "Produit mis à jour avec succès.";
    } else {
        $_SESSION['error'] = "Erreur lors de la mise à jour du produit.";
    }

    // Rediriger l'utilisateur après la mise à jour
    echo '<meta http-equiv="refresh" content="0;URL=produits.php">';
    exit();
}

?>






<?php require('footer.php'); ?>
</body>
</html>
