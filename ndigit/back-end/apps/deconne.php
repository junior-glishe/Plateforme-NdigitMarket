<?php
session_start();

// Supprimer uniquement la variable 'admin' de la session
unset($_SESSION['admin']);

// Optionnel : rediriger l'utilisateur vers la page d'accueil (ou une autre page)
header("Location: ../index"); 
exit();
?>
