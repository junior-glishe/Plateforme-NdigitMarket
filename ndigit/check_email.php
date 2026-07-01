<?php
// Inclure votre fichier de connexion à la base de données ici
// Par exemple, si vous avez un fichier db.php pour la connexion à la base de données, vous pouvez l'inclure.
include('header.php');  // Remplacez par le chemin réel vers votre fichier de connexion à la base de données.

if (isset($_GET['email'])) {
    $email = htmlspecialchars($_GET['email']);

    // Vérifier si l'email existe déjà dans la base de données
    $query = $database->prepare('SELECT * FROM utilisateur WHERE email = :email');
    $query->bindParam(':email', $email);
    $query->execute();
    
    // Si l'email existe déjà, on renvoie un message d'erreur
    if ($query->rowCount() > 0) {
        echo 'Email déjà utilisé';
    } else {
        echo 'Email disponible';
    }
} else {
    echo 'Paramètre email manquant';
}
?>
