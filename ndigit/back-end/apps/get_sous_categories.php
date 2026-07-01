<?php
require('../../include/connect.php');

if (isset($_GET['categorie_id'])) {
    $categorie_id = intval($_GET['categorie_id']);

    // Récupérer les sous-catégories de la catégorie sélectionnée
    $query = $database->prepare("SELECT sous_categories FROM categories WHERE id = ?");
    $query->execute([$categorie_id]);
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if ($result && !empty($result['sous_categories'])) {
        // Séparer les sous-catégories par des virgules
        $sous_categories = explode(',', $result['sous_categories']);

        // Créer un tableau associatif pour chaque sous-catégorie
        $sous_categories_data = [];
        foreach ($sous_categories as $sous_cat) {
            $sous_categories_data[] = [
                'id' => trim($sous_cat),  // Utiliser le nom de la sous-catégorie comme ID
                'nom_sous_categorie' => trim($sous_cat) // Utiliser le même nom pour le texte de l'option
            ];
        }

        // Retourner les sous-catégories sous forme de JSON
        echo json_encode($sous_categories_data);
    } else {
        // Aucun sous-catégorie trouvée
        echo json_encode([]);
    }
}
?>

