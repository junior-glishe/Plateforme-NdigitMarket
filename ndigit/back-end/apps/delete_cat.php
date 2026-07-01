<?php 
require('../../include/connect.php');
    if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $database->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$delete_id]);
      echo '<meta  http-equiv="refresh" content="0;URL=categorie">';
    exit();
} ?>