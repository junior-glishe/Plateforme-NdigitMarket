<?php
// filter_products.php
header('Content-Type: application/json; charset=utf-8');

// Désactiver l'affichage des erreurs pour éviter les sorties parasites
ini_set('display_errors', '0');
error_reporting(E_ALL);

// Connexion à la base de données
try {
    require_once 'include/connect.php';
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur de connexion à la base de données : ' . $e->getMessage()
    ]);
    exit;
}

try {
    $produitsParPage = 24;
    $pageActuelle = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $pageActuelle = max(1, $pageActuelle);
    $offset = ($pageActuelle - 1) * $produitsParPage;
    $category = isset($_GET['category']) ? trim($_GET['category']) : '';

    // Construire la requête SQL pour compter les produits
    $countQuery = 'SELECT COUNT(*) AS total FROM produits';
    if ($category) {
        $countQuery .= ' INNER JOIN categories ON produits.categorie_id = categories.id WHERE categories.nom_categorie = :category';
    }

    // Récupérer le nombre total de produits
    $stmt = $database->prepare($countQuery);
    if ($category) {
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);
    }
    $stmt->execute();
    $totalProduits = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalProduits / $produitsParPage);

    // Construire la requête SQL pour récupérer les produits
    $query = 'SELECT produits.*, categories.nom_categorie 
              FROM produits
              INNER JOIN categories ON produits.categorie_id = categories.id';
    if ($category) {
        $query .= ' WHERE categories.nom_categorie = :category';
    }
    $query .= ' ORDER BY produits.nom_article ASC
                LIMIT :offset, :limit';

    // Récupérer les produits
    $stmt = $database->prepare($query);
    if ($category) {
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);
    }
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $produitsParPage, PDO::PARAM_INT);
    $stmt->execute();
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retourner les données en JSON
    echo json_encode([
        'success' => true,
        'produits' => $produits ?: [],
        'totalProduits' => (int)$totalProduits,
        'totalPages' => (int)$totalPages,
        'pageActuelle' => (int)$pageActuelle,
        'produitsParPage' => (int)$produitsParPage
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur serveur : ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>