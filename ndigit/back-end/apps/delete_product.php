<?php
require('header.php'); // Assurez-vous que la connexion à la base de données est incluse

// Vérification de l'ID du produit passé dans l'URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    // Récupérer l'ID du produit
    $productId = $_GET['id'];

    // Préparer la requête pour récupérer les informations du produit
    $query = $database->prepare("SELECT * FROM produits WHERE id = :id");
    $query->execute(['id' => $productId]);
    $product = $query->fetch(PDO::FETCH_ASSOC);

    // Si le produit existe, procéder à la suppression
    if ($product) {
        // Supprimer l'image du produit si elle existe
        if ($product['image'] && file_exists($product['image'])) {
            unlink($product['image']); // Supprimer le fichier image
        }

        // Supprimer le fichier ZIP du produit si il existe
        if ($product['fichier'] && file_exists($product['fichier'])) {
            unlink($product['fichier']); // Supprimer le fichier ZIP
        }

        // Supprimer le produit de la base de données
        $deleteQuery = $database->prepare("DELETE FROM produits WHERE id = :id");
        $deleteQuery->execute(['id' => $productId]);

        // Rediriger vers la page des produits après la suppression
        echo '<meta http-equiv="refresh" content="0;URL=produits">';
        exit();
    }
}

// Si l'ID n'est pas valide ou le produit n'existe pas, rediriger vers la page des produits
  echo '<meta http-equiv="refresh" content="0;URL=produits">';
exit();
?>
