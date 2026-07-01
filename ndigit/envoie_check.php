   <?php
    
 if(isset($_POST['connexion'])){

$mdp=htmlspecialchars($_POST['mdp']);
$email=htmlspecialchars($_POST['email']);


 $resultats = $database->query('SELECT * FROM utilisateur ');
             
$a=false;


    while ($donnee = $resultats->fetch()) {

        if ($donnee['email']==$email AND $donnee['mdp']==$mdp) {
            $_SESSION["user_id"]="oui";
              $_SESSION["email"]=$email;
            
            echo '
                                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                <script>
                                    Swal.fire({
                                        icon: "success",
                                        title: "Succès !",
                                        text: "Connexion réussite. Cliquez sur ok pour continuer",
                                        confirmButtonColor: "#0a4e83" // Couleur du bouton de confirmation
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = "checkout";
                                        }
                                    });
                                </script>';

             // echo  $_SESSION['statut'];
             $a=true;
        }

}




if ($a==false) {
    echo'<p style="color:red;text-align:center">Adresse ou mot de passe ne conrespondes pas</p>';


}
  








}

               

 if(isset($_POST['inscrire'])){
$nom=htmlspecialchars($_POST['nom']);
$prenom=htmlspecialchars($_POST['prenom']);

$email=htmlspecialchars($_POST['email']);
$mdp1=htmlspecialchars($_POST['mdp1']);
$mdp2=htmlspecialchars($_POST['mdp2']);
$statut="-";
$type="-";

 $resultats = $database->query('SELECT * FROM utilisateur ');
             
$a=false;


if ($mdp1!=$mdp2) {
  echo'<p style="color:red;text-align:center">Les mots de passe ne conrespondes pas</p>';
}

else{

	while ($donnee = $resultats->fetch()) {

		if ($donnee['email']==$email) {
			 echo'<p style="color:red;text-align:center">Cette adresse mail est déjà utiliser</p>';
			 $a=true;
		}

}




if ($a==false) {
	$inserer="INSERT INTO utilisateur (nom,prenom,email,type,statut,mdp) VALUES ('$nom','$prenom','$email','$type','$statut','$mdp1') ";
  $query1=$database->prepare($inserer);
  $database->exec($inserer);
  $_SESSION["user_id"]="oui";
  $_SESSION["email"]=$email;

 
                            echo '
                                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                <script>
                                    Swal.fire({
                                        icon: "success",
                                        title: "Succès !",
                                        text: "Votre compte a été créé avec succès. Cliquez sur OK pour vous connecter.",
                                        confirmButtonColor: "#0a4e83" // Couleur du bouton de confirmation
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = "checkout";
                                        }
                                    });
                                </script>';
}
  






}

}

?>
