<?php
session_start(); // Démarre la session

// Sauvegarde de la variable du panier si elle existe
$panier = isset($_SESSION['panier']) ? $_SESSION['panier'] : null;

// Vide toutes les variables de session
$_SESSION = array();

// Restaure la variable du panier
if ($panier !== null) {
    $_SESSION['panier'] = $panier;
}

// Redirige l'utilisateur vers la page d'accueil
header("Location: index.php");
exit(); // Termine le script pour s'assurer que la redirection est effectuée
?>
