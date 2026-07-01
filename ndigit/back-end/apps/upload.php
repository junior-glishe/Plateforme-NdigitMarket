<?php
require('../../include/connect.php'); // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérifier si l'image et le fichier sont bien présents
    if (isset($_FILES['imageProduit']) && isset($_FILES['fichierUpload'])) {

        // Vérification de l'image
        if ($_FILES['imageProduit']['error'] != UPLOAD_ERR_OK) {
            echo json_encode(["status" => "error", "message" => "Erreur lors de l'upload de l'image."]);
            exit;
        }

        // Récupérer les données envoyées par le formulaire
        $nom_article = isset($_POST['nom_article']) ? $_POST['nom_article'] : '';
        $description = isset($_POST['description']) ? $_POST['description'] : '';

        // Debug pour la description
        if (empty($description)) {
            echo json_encode(["status" => "error", "message" => "La description est vide."]);
            exit;
        }

        // Supprimer le BOM si présent
        $description = htmlspecialchars_decode($description);

        $prix = isset($_POST['prix']) ? $_POST['prix'] : 0;
        $prix_reduction = isset($_POST['prix_reduction']) ? $_POST['prix_reduction'] : NULL;

        // Nom de la catégorie
        $categorie_id = isset($_POST['categorie']) ? $_POST['categorie'] : '';

        // Sous-catégories sélectionnées
        $sous_categorie_names = isset($_POST['sous_categorie']) ? implode(',', $_POST['sous_categorie']) : '';
        $date_heure = date('Y-m-d H:i:s');
        $auteur = isset($_POST['auteur']) ? $_POST['auteur'] : "NTECH DIGIT";

        // Récupérer les fichiers téléchargés
        $image_produit = $_FILES['imageProduit']['name'];
        $fichier_produit = $_FILES['fichierUpload']['name'];

        // Récupérer le lien d'aperçu, ou mettre "-" si non renseigné
        $apercu = isset($_POST['apercu']) && !empty($_POST['apercu']) ? $_POST['apercu'] : '-';

        // Définir le dossier de destination (déjà dans back-end/apps/)
        $targetDir = 'uploads/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Définir les noms des fichiers
        $image_name = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($image_produit));
        $fichier_name = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($fichier_produit));
        
        // Chemins physiques
        $image_target = $targetDir . $image_name;
        $fichier_target = $targetDir . $fichier_name;

        // Chemins à stocker en base (relatifs depuis la racine du site)
        $image_db = 'uploads/' . $image_name;
        $fichier_db = 'uploads/' . $fichier_name;

        // Vérifications des extensions
        $image_ext = strtolower(pathinfo($image_produit, PATHINFO_EXTENSION));
        $valid_image_exts = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($image_ext, $valid_image_exts)) {
            echo json_encode(["status" => "error", "message" => "Seuls les fichiers image (jpg, jpeg, png, gif) sont autorisés!"]);
            exit;
        }

        $fichier_ext = strtolower(pathinfo($fichier_produit, PATHINFO_EXTENSION));
        if ($fichier_ext !== 'zip') {
            echo json_encode(["status" => "error", "message" => "Le fichier doit être au format ZIP!"]);
            exit;
        }

        // Déplacer les fichiers
        if (!move_uploaded_file($_FILES['imageProduit']['tmp_name'], $image_target)) {
            echo json_encode(["status" => "error", "message" => "Erreur lors du téléchargement de l'image."]);
            exit;
        }

        if (!move_uploaded_file($_FILES['fichierUpload']['tmp_name'], $fichier_target)) {
            unlink($image_target);
            echo json_encode(["status" => "error", "message" => "Erreur lors du téléchargement du fichier ZIP."]);
            exit;
        }

        // Insertion dans la base de données
        try {
            $stmt = $database->prepare("INSERT INTO produits 
                (nom_article, prix, prix_reduction, image, fichier, categorie_id, sous_categorie, date_ajout, auteur, description, apercue, id_vendeur, statut) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->execute([
                $nom_article,
                $prix,
                $prix_reduction,
                $image_db,                      // uploads/image.jpg
                $fichier_db,                    // uploads/fichier.zip
                $categorie_id,
                $sous_categorie_names,
                $date_heure,
                $auteur,
                $description,
                $apercu,
                1,                              // id_vendeur = 1 (admin)
                'approuve'                      // Statut directement approuvé pour l'admin
            ]);

            echo json_encode(["status" => "success", "message" => "Produit ajouté et publié avec succès!"]);

        } catch (PDOException $e) {
            unlink($image_target);
            unlink($fichier_target);
            echo json_encode(["status" => "error", "message" => "Erreur dans l'insertion en base de données: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Fichiers manquants."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Aucune donnée reçue."]);
}
?>