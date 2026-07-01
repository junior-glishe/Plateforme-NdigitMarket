<?php
require('../../include/connect.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée']);
    exit;
}

$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$sous_categorie = isset($_POST['sous_categorie']) ? trim($_POST['sous_categorie']) : '';

if ($product_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'ID produit invalide']);
    exit;
}

try {
    $stmt = $database->prepare("UPDATE produits SET sous_categorie = ? WHERE id = ?");
    $stmt->execute([$sous_categorie, $product_id]);
    echo json_encode(['status' => 'success', 'message' => 'Sous-catégories mises à jour']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erreur base de données']);
}