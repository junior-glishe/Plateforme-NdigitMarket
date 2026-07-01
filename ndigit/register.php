<?php
// Inclure votre fichier de connexion à la base de données ici
include('header.php');  // Remplacez par le chemin réel vers votre fichier de connexion à la base de données.

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération et sécurisation des données
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $mdp1 = htmlspecialchars($_POST['mdp1']);
    $mdp2 = htmlspecialchars($_POST['mdp2']);

    // Vérification que les mots de passe correspondent
    if ($mdp1 !== $mdp2) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier si l'email existe déjà dans la base de données
        $query = $database->prepare('SELECT * FROM utilisateur WHERE email = :email');
        $query->bindParam(':email', $email);
        $query->execute();

        if ($query->rowCount() > 0) {
            $error = "Cette adresse e-mail est déjà utilisée.";
        } else {
            // Insérer le nouvel utilisateur dans la base de données
            $statut = "-";
            $type = "-";
            $inserer = "INSERT INTO utilisateur (nom, prenom, email, type, statut, mdp) VALUES (:nom, :prenom, :email, :type, :statut, :mdp)";
            $stmt = $database->prepare($inserer);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':statut', $statut);
            $stmt->bindParam(':mdp', password_hash($mdp1, PASSWORD_DEFAULT));  // On hache le mot de passe

            // Exécuter la requête
            if ($stmt->execute()) {
                session_start();
                $_SESSION["user_id"] = $database->lastInsertId();
                $_SESSION["email"] = $email;
                header("Location: product_detail?nom_article=" . $_GET['nom_article']); // Redirection vers la page du produit
                exit;
            } else {
                $error = "Erreur lors de l'inscription. Veuillez réessayer.";
            }
        }
    }
}
?>
