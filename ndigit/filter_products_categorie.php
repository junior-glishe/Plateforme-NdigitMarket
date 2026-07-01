<?php
require_once 'include/connect.php';

header('Content-Type: application/json');

$produitsParPage = 24;
$pageActuelle = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$pageActuelle = max(1, $pageActuelle);
$offset = ($pageActuelle - 1) * $produitsParPage;
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$sousCategory = isset($_GET['sous_category']) ? trim($_GET['sous_category']) : '';

try {
    // Requête pour compter le nombre total de produits
    $countQuery = 'SELECT COUNT(*) AS total FROM produits
                   INNER JOIN categories ON produits.categorie_id = categories.id';
    if ($category) {
        $countQuery .= ' WHERE categories.nom_categorie = :category';
        if ($sousCategory) {
            $countQuery .= ' AND FIND_IN_SET(:sous_category, produits.sous_categorie) > 0';
        }
    }
    $stmt = $database->prepare($countQuery);
    if ($category) {
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);
        if ($sousCategory) {
            $stmt->bindValue(':sous_category', $sousCategory, PDO::PARAM_STR);
        }
    }
    $stmt->execute();
    $totalProduits = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalProduits / $produitsParPage);

    // Requête pour récupérer les produits
    $query = 'SELECT produits.*, categories.nom_categorie 
              FROM produits
              INNER JOIN categories ON produits.categorie_id = categories.id';
    if ($category) {
        $query .= ' WHERE categories.nom_categorie = :category';
        if ($sousCategory) {
            $query .= ' AND FIND_IN_SET(:sous_category, produits.sous_categorie) > 0';
        }
    }
    $query .= ' ORDER BY produits.nom_article ASC
                LIMIT :offset, :limit';
    $stmt = $database->prepare($query);
    if ($category) {
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);
        if ($sousCategory) {
            $stmt->bindValue(':sous_category', $sousCategory, PDO::PARAM_STR);
        }
    }
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $produitsParPage, PDO::PARAM_INT);
    $stmt->execute();
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retourner la réponse JSON
    echo json_encode([
        'success' => true,
        'produits' => $produits,
        'totalProduits' => $totalProduits,
        'totalPages' => $totalPages,
        'pageActuelle' => $pageActuelle,
        'produitsParPage' => $produitsParPage
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>