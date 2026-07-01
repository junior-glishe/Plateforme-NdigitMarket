

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="assets/images/favi.png" type="image/x-icon">
    <title>NDIGITMARKET - Votre marché en ligne pour des produits de qualité à prix abordables</title>

    <!-- Description -->
    <meta name="description" content="Découvrez une large gamme de produits sur NDIGITMARKET, votre plateforme de confiance pour acheter des articles électroniques, mode, et bien plus encore à des prix compétitifs. Livraison rapide et sécurité garantie.">

    <!-- Keywords (facultatif mais utile pour le SEO) -->
    <meta name="keywords" content="NDIGITMARKET, marché en ligne, produits électroniques, mode, accessoires, achats en ligne, livraison rapide, produits de qualité">

    <!-- Open Graph (pour les réseaux sociaux, Facebook, etc.) -->
    <meta property="og:title" content="NDIGITMARKET - Votre marché en ligne pour des produits de qualité à prix abordables">
    <meta property="og:description" content="Découvrez une large gamme de produits sur NDIGITMARKET, votre plateforme de confiance pour acheter des articles électroniques, mode, et bien plus encore à des prix compétitifs. Livraison rapide et sécurité garantie.">
    <meta property="og:image" content="URL_de_l'image_de_votre_site.jpg"> <!-- Remplace par l'URL de l'image à utiliser -->
    <meta property="og:url" content="https://www.ndigitmarket.com"> <!-- Remplace par l'URL de ton site -->

    <!-- Twitter Card (pour Twitter) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="NDIGITMARKET - Votre marché en ligne pour des produits de qualité à prix abordables">
    <meta name="twitter:description" content="Découvrez une large gamme de produits sur NDIGITMARKET, votre plateforme de confiance pour acheter des articles électroniques, mode, et bien plus encore à des prix compétitifs. Livraison rapide et sécurité garantie.">
    <meta name="twitter:image" content="assets/images/favi.png"> <!-- Remplace par l'URL de l'image à utiliser -->
</head>
<body>
	 <?php require('header.php');

	 if (isset($_SESSION['user_id'])) {
    // Si l'utilisateur est connecté
    echo '<meta  http-equiv="refresh" content="0;URL=index">';
} ?>

 <section class="breadcrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-contain">
                        <h2>Sign In</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="index.html">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active">Sign In</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>


     <section class="log-in-section section-b-space">
        <div class="container-fluid-lg w-100">
            <div class="row">
                <div class="col-xxl-6 col-xl-5 col-lg-6 d-lg-block d-none ms-auto">
                    <div class="image-contain">
                        <img src="assets/images/inner-page/sign-up.png" class="img-fluid" alt="">
                    </div>
                </div>

                <div class="col-xxl-4 col-xl-5 col-lg-6 col-sm-8 mx-auto">
                    <div class="log-in-box">
                        <div class="log-in-title">
                            <h3>Bienvenue sur NdigitMarket</h3>
                            <h4>Créer un nouveau compte</h4>
                        </div>


                                				         <?php
                  
                      

 if(isset($_POST['envoyer'])){
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
 $hashedPassword = password_hash($mdp1, PASSWORD_DEFAULT);

            // Insertion de l'utilisateur avec le mot de passe haché
            $inserer = "INSERT INTO utilisateur (nom, prenom, email, type, statut, mdp) 
                        VALUES ('$nom', '$prenom', '$email', '$type', '$statut', '$hashedPassword')";
            $query1 = $database->prepare($inserer);
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
                                            window.location.href = "compte";
                                        }
                                    });
                                </script>';
}
  






}

}

?>


                        <div class="input-box">
                            <form class="row g-4" method="POST">
                                <div class="col-12">
                                    <div class="form-floating theme-form-floating">
                                        <input name="nom" type="text" class="form-control" id="fullname" placeholder="Votre Nom">
                                        <label for="fullname">Votre Nom</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating theme-form-floating">
                                        <input name="prenom" type="text" class="form-control" id="fullname" placeholder="Votre Prénom">
                                        <label for="fullname">Votre Prénom</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating theme-form-floating">
                                        <input name="email" type="email" class="form-control" id="email" placeholder="Adresse Email">
                                        <label for="email">Adresse Email</label>
                                    </div>
                                </div>

                          <div class="col-12">
    <div class="form-floating theme-form-floating">
        <input name="mdp1" type="password" class="form-control" id="password"
            placeholder="Mot de Passe" onkeyup="validatePasswords()">
        <label for="password">Mot de Passe</label>
    </div>
    <small id="password-requirements" style="color: red; display: block;">
        ⚠️ Minimum 8 caractères, incluant une majuscule, un chiffre et un symbole.
    </small>
    <div class="progress mt-2">
        <div id="password-strength-bar" class="progress-bar" role="progressbar" 
            style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
</div>

<div class="col-12">
    <div class="form-floating theme-form-floating">
        <input name="mdp2" type="password" class="form-control" id="confirm-password"
            placeholder="Reconfirmer le mot de passe" onkeyup="validatePasswords()">
        <label for="confirm-password">Reconfirmer le mot de passe</label>
    </div>
    <small id="password-match-message" style="color: red;"></small>
</div>



<script>
function validatePasswords() {
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirm-password").value;
    let strengthBar = document.getElementById("password-strength-bar");
    let requirementsText = document.getElementById("password-requirements");
    let matchMessage = document.getElementById("password-match-message");
    let submitBtn = document.getElementById("submit-btn");

    let strength = 0;
    
    if (password.length >= 8) strength += 25;
    if (/[A-Z]/.test(password)) strength += 25;
    if (/[0-9]/.test(password)) strength += 25;
    if (/[\W]/.test(password)) strength += 25;

    strengthBar.style.width = strength + "%";

    if (strength < 50) {
        strengthBar.style.backgroundColor = "red";
    } else if (strength < 75) {
        strengthBar.style.backgroundColor = "orange";
    } else {
        strengthBar.style.backgroundColor = "green";
    }

    let isValid = strength === 100;
    requirementsText.style.color = isValid ? "green" : "red";

    if (password !== confirmPassword || !isValid) {
        matchMessage.textContent = "❌ Les mots de passe ne correspondent pas ou ne respectent pas les critères.";
        matchMessage.style.color = "red";
        submitBtn.disabled = true;
    } else {
        matchMessage.textContent = "✅ Les mots de passe sont valides.";
        matchMessage.style.color = "green";
        submitBtn.disabled = false;
    }
}
</script>

<!-- Styles Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

                                <div class="col-12">
                                    <div class="forgot-box">
                                        <div class="form-check ps-0 m-0 remember-box">
                                            <input class="checkbox_animated check-box" type="checkbox"
                                                id="flexCheckDefault">
                                            <label class="form-check-label" for="flexCheckDefault">Je suis d’accord avec les
                                                <span>Termes</span> et <span>Confidentialité</span></label>
                                        </div>
                                    </div>
                                </div>
                                 <div class="col-12">
                                    <button id="submit-btn" style="background-color: teal;border: none;" name="envoyer" disabled class="btn btn-animation w-100" type="submit">Créer votre compte</button>
                                </div>
                            </form>
                        </div>
<!-- 
                        <div class="other-log-in">
                            <h6>or</h6>
                        </div>

                        <div class="log-in-button">
                            <ul>
                                <li>
                                    <a href="https://accounts.google.com/signin/v2/identifier?flowName=GlifWebSignIn&flowEntry=ServiceLogin"
                                        class="btn google-button w-100">
                                        <img src="assets/images/inner-page/google.png" class="blur-up lazyload"
                                            alt="">
                                        Sign up with Google
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.facebook.com/" class="btn google-button w-100">
                                        <img src="assets/images/inner-page/facebook.png" class="blur-up lazyload"
                                            alt=""> Sign up with Facebook
                                    </a>
                                </li>
                            </ul>
                        </div> -->

                        <div class="other-log-in">
                            <h6></h6>
                        </div>

                        <div class="sign-up-box">
                            <h4>Vous avez déjà un compte ?</h4>
                            <a href="login">S'identifier</a>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-7 col-xl-6 col-lg-6"></div>
            </div>
        </div>
    </section>
	 

	  <?php require('footer.php') ?>

</body>
</html>