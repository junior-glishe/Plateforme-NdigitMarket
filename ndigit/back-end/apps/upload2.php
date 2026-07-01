<?php

// Dossier où les fichiers seront enregistrés
$target_dir = "uploads/";

// Créer le dossier uploads si il n'existe pas encore
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// Vérifier si le fichier a été téléchargé sans erreur
if ($_FILES["file"]["error"] == UPLOAD_ERR_OK) {
    // Récupérer le nom du fichier
    $fileName = basename($_FILES["file"]["name"]);
    $target_file = $target_dir . $fileName;

    // Vérifier l'extension du fichier
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if ($fileType != "zip") {
        echo "Désolé, seuls les fichiers ZIP sont autorisés.";
        exit;
    }

    // Vérifier la taille du fichier (maximum 500 Mo)
    if ($_FILES["file"]["size"] > 500 * 1024 * 1024) {
        echo "Désolé, votre fichier est trop grand.";
        exit;
    }

    // Déplacer le fichier téléchargé vers le dossier cible
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo "Le fichier " . htmlspecialchars($fileName) . " a été téléchargé avec succès.";
    } else {
        echo "Désolé, une erreur est survenue lors du téléchargement du fichier.";
    }
} else {
    echo "Désolé, il y a eu un problème avec votre fichier.";
}

?>
