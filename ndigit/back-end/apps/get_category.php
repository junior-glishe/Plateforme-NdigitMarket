<?php
// Connexion à la base de données
require('../../include/connect.php');

// Vérifier si un ID est passé dans l'URL
if (isset($_GET['id'])) {
    // Récupérer l'ID de la catégorie
    $id = $_GET['id'];

    // Préparer la requête SQL pour récupérer les données de la catégorie
    $stmt = $database->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);

    // Vérifier si la catégorie existe
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($category) {
        // Récupérer les sous-catégories, les convertir en tableau
        $sous_categories = explode(",", $category['sous_categories']);

        // Retourner les données au format JSON
        echo json_encode([
            'id' => $category['id'],
            'nom_categorie' => $category['nom_categorie'],
            'sous_categories' => $sous_categories,

        ]);
    } else {
        // Si la catégorie n'existe pas
        echo json_encode(['error' => 'Catégorie non trouvée']);
    }
} else {
    // Si l'ID n'est pas passé dans l'URL
    echo json_encode(['error' => 'ID manquant']);
}
?>

