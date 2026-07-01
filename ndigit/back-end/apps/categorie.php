<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion des catégories</title>
    <style>
        /* Style pour la page */
        .page-body {
            display: flex;
            justify-content: space-between;
        }
        .form-container {
            width: 48%;
        }
        .table-container {
            width: 48%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <?php require('header.php') ?>

    <div class="page-body">

        <!-- Formulaire d'ajout de catégorie -->
        <div class="form-container">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-4 row align-items-center">
                    <label class="col-sm-3 col-form-label form-label-title">Catégorie Image</label>
                    <div class="form-group col-sm-9">
                        <input type="file" name="image" class="form-control" id="imageInput" required accept="image/*">
                        <div class="mt-2" id="previewContainer" style="display: none;">
                            <img id="imagePreview" src="#" alt="Aperçu de l'image" style="max-width: 100px; border-radius: 5px;">
                            <button type="button" id="removeImage" class="btn btn-danger btn-sm mt-2">Supprimer</button>
                            <p id="errorMessage" style="color: red; font-size: 14px; display: none;"></p>
                        </div>
                    </div>
                </div>
                <div class="mb-4 row align-items-center">
                    <label class="form-label-title col-sm-3 mb-0">Nom de la catégorie</label>
                    <div class="col-sm-9">
                        <input class="form-control" type="text" name="nom_categorie" required placeholder="Ex: PSD">
                    </div>
                </div>
                <div class="mb-4 row align-items-center">
                    <label class="form-label-title col-sm-3 mb-0">Écrivez les sous-catégories</label>
                    <div class="col-sm-9" id="sousCategorieContainer">
                        <div class="d-flex">
                            <input class="form-control me-2 sous-categorie" type="text" name="sous_categorie[]" required placeholder="Ex: Affiche">
                            <button type="button" class="btn btn-success btn-sm" id="addSousCategorie">+</button>
                        </div>
                    </div>
                </div>
                <div class="card-submit-button">
                    <button class="btn btn-animation ms-auto" type="submit" name="submit">Envoyer</button>
                </div>
            </form>

            <?php
// require 'config.php'; // Connexion à la base de données

if (isset($_POST['submit'])) {
    $nom_categorie = htmlspecialchars($_POST['nom_categorie']);
    $sous_categories = isset($_POST['sous_categorie']) ? array_filter($_POST['sous_categorie']) : [];

    if (empty($sous_categories)) {
        echo "Veuillez ajouter au moins une sous-catégorie.";
        exit();
    }

    $sous_categories_str = implode(",", array_map('htmlspecialchars', $sous_categories));

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image'];
        $imageName = time() . "_" . basename($image['name']);
        $imagePath = __DIR__ . "/uploads/" . $imageName; // Chemin absolu
        $imageSize = $image['size'];
        $imageTmp = $image['tmp_name'];
        $imageType = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

        $formats_autorises = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageType, $formats_autorises)) {
            echo "Seules les images aux formats JPG, JPEG, PNG et GIF sont acceptées.";
            exit();
        }

        if ($imageSize > 1000 * 1024) {
            echo "L'image dépasse la taille maximale de 500 Ko.";
            exit();
        }

        if (!is_dir(__DIR__ . '/uploads')) {
            mkdir(__DIR__ . '/uploads', 0777, true);
        }

        if (move_uploaded_file($imageTmp, $imagePath)) {
            echo "â Fichier déplacé avec succès : $imagePath<br>";
            try {
                $stmt = $database->prepare("INSERT INTO categories (nom_categorie, sous_categories, image_cat) VALUES (?, ?, ?)");
                $stmt->execute([$nom_categorie, $sous_categories_str, $imageName]);

                echo "ð Catégorie enregistrée avec succès.";
            } catch (PDOException $e) {
                echo "â Erreur lors de l'enregistrement : " . $e->getMessage();
            }
        } else {
            echo "â Erreur lors du déplacement du fichier.<br>";
            echo "Code d'erreur : " . $_FILES['image']['error'] . "<br>";
            echo "Fichier temporaire : " . $imageTmp . "<br>";
            echo "Chemin de destination : " . $imagePath . "<br>";
        }
    } else {
        echo "â Veuillez ajouter une image.";
    }
}
?>


            <script>
                // Logic to preview image and manage the file input
                document.getElementById("imageInput").addEventListener("change", function(event) {
                    const file = event.target.files[0];
                    const previewContainer = document.getElementById("previewContainer");
                    const previewImage = document.getElementById("imagePreview");
                    const removeButton = document.getElementById("removeImage");
                    const errorMessage = document.getElementById("errorMessage");

                    if (file) {
                        if (file.size > 1000 * 1024) {
                            errorMessage.textContent = "L'image dépasse 500 Ko, veuillez choisir une image plus légère.";
                            errorMessage.style.display = "block";
                            previewContainer.style.display = "none";
                            event.target.value = "";
                        } else {
                            errorMessage.style.display = "none";
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                previewImage.src = e.target.result;
                                previewContainer.style.display = "block";
                            }
                            reader.readAsDataURL(file);
                        }
                    }
                });

                document.getElementById("removeImage").addEventListener("click", function() {
                    document.getElementById("imageInput").value = "";
                    document.getElementById("previewContainer").style.display = "none";
                });

                document.getElementById("addSousCategorie").addEventListener("click", function() {
                    const container = document.getElementById("sousCategorieContainer");
                    const newInput = document.createElement("div");
                    newInput.classList.add("d-flex", "mt-2");
                    newInput.innerHTML = `
                        <input class="form-control me-2 sous-categorie" type="text" name="sous_categorie[]" required placeholder="Ex: Affiche">
                        <button type="button" class="btn btn-danger btn-sm removeSousCategorie">-</button>
                    `;
                    container.appendChild(newInput);
                });

                document.getElementById("sousCategorieContainer").addEventListener("click", function(event) {
                    if (event.target.classList.contains("removeSousCategorie")) {
                        event.target.parentElement.remove();
                    }
                });
            </script>
        </div>

        <!-- Affichage des catégories -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Catégorie</th>
                        <th>Sous-catégories</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = $database->query("SELECT * FROM categories");
                    $categories = $query->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($categories as $category) {
                        $sous_categories = explode(",", $category['sous_categories']);
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($category['nom_categorie']); ?></td>
                            <td><?php echo implode(", ", $sous_categories); ?></td>
                            <td><img src="uploads/<?php echo htmlspecialchars($category['image_cat']); ?>" alt="Image" style="max-width: 100px;"></td>
                            <td>
                                <!-- Bouton pour modifier -->
                                <button type="button" class="btn btn-primary" onclick="openEditModal(<?= $category['id']; ?>)">Modifier</button>
                                <!-- Bouton pour supprimer -->
                                <a href="delete_cat?delete_id=<?php echo $category['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">Supprimer</a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

    <?php 


// Modifier une catégorie
if (isset($_POST['edit_id'])) {
    $edit_id = $_POST['edit_id'];
    $nom_categorie = htmlspecialchars($_POST['nom_categorie']);
    $sous_categories = isset($_POST['sous_categorie']) ? array_filter($_POST['sous_categorie']) : [];
    $sous_categories_str = implode(",", array_map('htmlspecialchars', $sous_categories));

    if (!empty($_FILES['image']['name'])) {
        // Traiter l'image
        $image = $_FILES['image'];
        $imageName = time() . "_" . basename($image['name']);
        $imagePath = __DIR__ . "/uploads/" . $imageName; // Chemin absolu
        $imageSize = $image['size'];
        $imageTmp = $image['tmp_name'];
        $imageType = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

        $formats_autorises = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageType, $formats_autorises)) {
            echo "Seules les images aux formats JPG, JPEG, PNG et GIF sont acceptées.";
            exit();
        }

        if ($imageSize > 1000 * 1024) {
            echo "L'image dépasse la taille maximale de 1000 Ko.";
            exit();
        }

        if (!is_dir(__DIR__ . '/uploads')) {
            mkdir(__DIR__ . '/uploads', 0777, true);
        }

        if (move_uploaded_file($imageTmp, $imagePath)) {
            // Mettre à jour dans la base de données
            $stmt = $database->prepare("UPDATE categories SET nom_categorie = ?, sous_categories = ?, image_cat = ? WHERE id = ?");
            $stmt->execute([$nom_categorie, $sous_categories_str, $imageName, $edit_id]);
             echo '<meta  http-equiv="refresh" content="0;URL=categorie">';
            exit();
        } else {
            echo "Erreur lors du téléchargement de l'image.";
        }
    } else {
        // Mettre à jour sans image
        $stmt = $database->prepare("UPDATE categories SET nom_categorie = ?, sous_categories = ? WHERE id = ?");
        $stmt->execute([$nom_categorie, $sous_categories_str, $edit_id]);
         echo '<meta  http-equiv="refresh" content="0;URL=categorie">';
        exit();
    }
} ?>

<!-- Fenêtre de modification -->
<div class="modal" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Modifier la catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="edit_id" id="edit_id" value="">

                    <div class="mb-4 row align-items-center">
                        <label class="col-sm-3 col-form-label form-label-title">Nom de la catégorie</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" name="nom_categorie" id="edit_nom_categorie" required>
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label class="form-label-title col-sm-3 mb-0">Sous-catégories</label>
                        <div class="col-sm-9" id="editSousCategorieContainer">
                            <!-- Champs sous-catégorie seront ajoutés ici dynamiquement -->
                        </div>
                        <button type="button" class="btn btn-success btn-sm mt-2" id="editAddSousCategorie">Ajouter une sous-catégorie</button>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label class="col-sm-3 col-form-label form-label-title">Catégorie Image</label>
                        <div class="col-sm-9">
                            <input type="file" name="image" class="form-control" id="editImageInput" accept="image/*">
                        </div>
                    </div>

                    <div class="card-submit-button">
                        <button class="btn btn-animation ms-auto" type="submit" name="editSubmit">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    // Lorsqu'on clique sur le bouton Modifier
    function openEditModal(id) {
        // Ouvrir la fenêtre modale
        const modal = new bootstrap.Modal(document.getElementById('editModal'));
        modal.show();

        // Récupérer les données de la catégorie à modifier
        fetch('get_category.php?id=' + id) // Utiliser un script pour récupérer les informations de la catégorie
            .then(response => response.json())
            .then(data => {
                // Remplir les champs du formulaire avec les données
                document.getElementById('edit_id').value = data.id;
                document.getElementById('edit_nom_categorie').value = data.nom_categorie;

                // Ajouter les sous-catégories existantes
                const sousCategorieContainer = document.getElementById('editSousCategorieContainer');
                sousCategorieContainer.innerHTML = ''; // Réinitialiser les sous-catégories existantes
                data.sous_categories.forEach(sousCategorie => {
                    const inputField = document.createElement('div');
                    inputField.classList.add('d-flex', 'mt-2');
                    inputField.innerHTML = `
                        <input class="form-control me-2 sous-categorie" type="text" name="sous_categorie[]" value="${sousCategorie}" required>
                        <button type="button" class="btn btn-danger btn-sm removeSousCategorie">-</button>
                    `;
                    sousCategorieContainer.appendChild(inputField);
                });
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des données :', error);
            });
    }

    // Ajouter une sous-catégorie
    document.getElementById('editAddSousCategorie').addEventListener('click', function() {
        const container = document.getElementById('editSousCategorieContainer');
        const newInput = document.createElement('div');
        newInput.classList.add('d-flex', 'mt-2');
        newInput.innerHTML = `
            <input class="form-control me-2 sous-categorie" type="text" name="sous_categorie[]" required placeholder="Ex: Affiche">
            <button type="button" class="btn btn-danger btn-sm removeSousCategorie">-</button>
        `;
        container.appendChild(newInput);
    });

    // Supprimer une sous-catégorie
    document.getElementById('editSousCategorieContainer').addEventListener('click', function(event) {
        if (event.target.classList.contains('removeSousCategorie')) {
            event.target.parentElement.remove();
        }
    });
</script>


<?php
// Inclure la connexion à la base de données
// Assurez-vous que votre fichier contient la bonne variable $database

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editSubmit'])) {
    // Récupérer l'ID de la catégorie à modifier
    $category_id = $_POST['edit_id'];
    $nom_categorie = $_POST['nom_categorie'];
    $sous_categories = isset($_POST['sous_categorie']) ? $_POST['sous_categorie'] : [];

    // Gérer l'image (si elle est ajoutée)
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // L'image est téléchargée
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_name = $_FILES['image']['name'];
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);

        // Vérification de l'extension d'image
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($image_extension), $allowed_extensions)) {
            // Créer un nouveau nom pour l'image pour éviter les conflits
            $new_image_name = uniqid('category_', true) . '.' . $image_extension;
            $image_path = 'uploads/' . $new_image_name; // Le répertoire où l'image sera enregistrée

            // Déplacer l'image dans le répertoire de destination
            move_uploaded_file($image_tmp_name, $image_path);
        } else {
            $error_message = "Format d'image non autorisé. Veuillez télécharger une image JPG, JPEG, PNG ou GIF.";
        }
    }

    // Mettre à jour la catégorie dans la base de données
    try {
        $database->beginTransaction(); // Commencer une transaction

        // Mettre à jour le nom de la catégorie
        $sql = "UPDATE categories SET nom_categorie = :nom_categorie WHERE id = :category_id";
        $stmt = $database->prepare($sql);
        $stmt->bindParam(':nom_categorie', $nom_categorie);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->execute();

        // Mettre à jour l'image si une nouvelle image est téléchargée
        if ($image_path) {
            $sql = "UPDATE categories SET image_cat = :image WHERE id = :category_id";
            $stmt = $database->prepare($sql);
            $stmt->bindParam(':image', $image_path);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->execute();
        }

        // Mettre à jour les sous-catégories
        if (!empty($sous_categories)) {
            // Supprimer les sous-catégories existantes
            $sql = "DELETE FROM sous_categories WHERE category_id = :category_id";
            $stmt = $database->prepare($sql);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->execute();

            // Insérer les nouvelles sous-catégories
            foreach ($sous_categories as $sous_categorie) {
                $sql = "INSERT INTO sous_categories (category_id, sous_categorie) VALUES (:category_id, :sous_categorie)";
                $stmt = $database->prepare($sql);
                $stmt->bindParam(':category_id', $category_id);
                $stmt->bindParam(':sous_categorie', $sous_categorie);
                $stmt->execute();
            }
        }

        $database->commit(); // Confirmer la transaction
        echo "La catégorie a été mise à jour avec succès.";
    } catch (Exception $e) {
        $database->rollBack(); // Annuler la transaction en cas d'erreur
        echo "Erreur lors de la mise à jour de la catégorie : " . $e->getMessage();
    }
}
?>


    <?php require('footer.php') ?>

</body>
</html>
