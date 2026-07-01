<?php
// Inclure la connexion à la base de données
require('include/connect.php');  // Fichier de connexion

// Récupérer les commandes récentes
$sql = "SELECT c.commande_id, u.nom AS user_nom, u.prenom AS user_prenom, p.nom_article, p.image 
        FROM commande c
        INNER JOIN utilisateur u ON c.id_client = u.id_uti
        INNER JOIN produits p ON c.id_article = p.id
        ORDER BY c.date_commande DESC LIMIT 20"; // Limité aux 5 dernières commandes

// Préparer la requête
$stmt = $database->query($sql);

$orders = [];

// Vérifiez si des résultats sont retournés
if ($stmt->rowCount() > 0) {
    // Récupérer toutes les commandes
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $orders[] = $row;
    }
} else {
    // Si aucune commande n'est trouvée
    $orders = [];
}

// Renvoi des données sous format JSON
echo json_encode($orders);

// Fermez la connexion
$database = null;
?>
