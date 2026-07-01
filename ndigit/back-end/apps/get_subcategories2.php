<?php
require('../../include/connect.php');

if (isset($_GET['categorie_id'])) {
    $categorie_id = intval($_GET['categorie_id']); // Vérifier que c'est un entier valide

    // Récupérer les sous-catégories de la catégorie sélectionnée
    $query = $database->prepare("SELECT sous_categories FROM categories WHERE id = :id");
    $query->execute(['id' => $categorie_id]);
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if ($result && !empty($result['sous_categories'])) {
        // Récupérer les sous-catégories
        $sous_categories = explode(',', $result['sous_categories']); // Sous-catégories séparées par des virgules

        // Créer un tableau de sous-catégories
        $sous_categories_data = [];
        foreach ($sous_categories as $sous_cat) {
            $sous_categories_data[] = [
                'id' => trim($sous_cat),
                'nom_sous_categorie' => trim($sous_cat),
            ];
        }

        // Retourner les sous-catégories sous forme de JSON
        echo json_encode($sous_categories_data);
    } else {
        echo json_encode([]); // Aucune sous-catégorie trouvée
    }
}
?>
