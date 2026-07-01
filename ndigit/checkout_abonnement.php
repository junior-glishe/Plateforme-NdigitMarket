<?php
require('header.php');
require_once('fedapay/init.php');

// Plans disponibles
$plans = [
    'Débutant'  => ['total' => 800,   'telechargement' => 10],
    'Explorer'  => ['total' => 1500,  'telechargement' => 20],
    'Passionné' => ['total' => 3000,  'telechargement' => 45],
    'Essentiel' => ['total' => 6000,  'telechargement' => 95],
    'Avancé'    => ['total' => 10000, 'telechargement' => 150],
    'Élite'     => ['total' => 20000, 'telechargement' => 300]
];

// Vérifier que le choix est valide
if (!isset($_GET['choix']) || !array_key_exists($_GET['choix'], $plans)) {
    echo '<meta http-equiv="refresh" content="0;URL=abonnement">';
    exit;
}

$choix          = $_GET['choix'];
$total          = $plans[$choix]['total'];
$telechargement = $plans[$choix]['telechargement'];

// Stocker en session
$_SESSION['choix']          = $choix;
$_SESSION['telechargement'] = $telechargement;

// Infos utilisateur connecté
$user_info = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $database->prepare("SELECT nom, prenom, email FROM utilisateur WHERE email = ?");
    $stmt->execute([$_SESSION['email']]);
    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);
}

$is_client_connected = isset($_SESSION['user_id']);

// ----------------------------------------------------------------
// Créer la transaction FedaPay (seulement si connecté)
// ----------------------------------------------------------------
use FedaPay\FedaPay;
use FedaPay\Transaction;

FedaPay::setEnvironment('live');
FedaPay::setApiKey('sk_live_4ZBrSu1MUy-TPU2kNv8GI-IO');

$paymentUrl   = '';
$fedapayError = '';

if ($is_client_connected) {
    $nom    = $user_info['nom']    ?? 'Inconnu';
    $prenom = $user_info['prenom'] ?? 'Inconnu';
    $email  = $user_info['email']  ?? 'inconnu@example.com';

    $transactionToken = uniqid('ABN_', true);
    $callback_url = "https://www.ndigitmarket.com/transaction_abonnement.php?status=approved&transaction_id=" . urlencode($transactionToken);
    $cancel_url   = "https://www.ndigitmarket.com/transaction_abonnement.php?status=cancelled&transaction_id=" . urlencode($transactionToken);

    try {
        $transaction = Transaction::create([
            "description"  => "Abonnement " . $choix . " sur NDIGITMARKET",
            "amount"       => (int) $total,
            "currency"     => ["iso" => "XOF"],
            "callback_url" => $callback_url,
            "cancel_url"   => $cancel_url,
            "metadata"     => [
                "nom"    => $nom,
                "prenom" => $prenom,
                "email"  => $email,
                "total"  => $total,
                "choix"  => $choix
            ]
        ]);

        $paymentUrl = $transaction->generateToken()->url;

        // ----------------------------------------------------------------
        // SAUVEGARDE EN BASE pour survivre à la perte de session
        // ----------------------------------------------------------------
        $stmt = $database->prepare("DELETE FROM abonnement_temp WHERE user_email = ?");
        $stmt->execute([$email]);

        $stmt = $database->prepare(
            "INSERT INTO abonnement_temp (transaction_token, user_email, choix, telechargement, total, created_at)
             VALUES (?, ?, ?, ?, ?, NOW())"
        );
        $stmt->execute([
            $transactionToken,
            $email,
            $choix,
            $telechargement,
            $total
        ]);

        $_SESSION['last_abonnement_token'] = $transactionToken;

    } catch (Exception $e) {
        $fedapayError = "Erreur de paiement : " . $e->getMessage();
    }
}
?>

<section class="breadcrumb-section pt-0">
    <div class="container-fluid-lg">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-contain">
                    <h2>Abonnement</h2>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="index"><i class="fa-solid fa-house"></i></a>
                            </li>
                            <li class="breadcrumb-item active">Abonnement</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="checkout-section-2 section-b-space">
    <div class="container-fluid-lg">
        <div class="row g-sm-4 g-3">

            <!-- Colonne gauche -->
            <div class="col-lg-8">
                <div class="left-sidebar-checkout">

                    <?php if (!$is_client_connected): ?>
                    <!-- Formulaire connexion / inscription -->
                    <div class="checkout-detail-box">
                        <?php
                        // ---- CONNEXION ----
                        if (isset($_POST['connexion'])) {
                            $mdp_post   = htmlspecialchars($_POST['mdp']);
                            $email_post = htmlspecialchars($_POST['email']);

                            $resultats = $database->query('SELECT * FROM utilisateur');
                            $found = false;
                            while ($donnee = $resultats->fetch()) {
                                if ($donnee['email'] == $email_post && password_verify($mdp_post, $donnee['mdp'])) {
                                    $_SESSION['user_id'] = $donnee['id_uti'];
                                    $_SESSION['email']   = $email_post;
                                    echo '<meta http-equiv="refresh" content="0;URL=checkout_abonnement?choix=' . urlencode($choix) . '">';
                                    $found = true;
                                    break;
                                }
                            }
                            if (!$found) {
                                echo '<p style="color:red;text-align:center">Adresse ou mot de passe ne correspondent pas</p>';
                            }
                        }

                        // ---- INSCRIPTION ----
                        if (isset($_POST['inscrire'])) {
                            $nom_post    = htmlspecialchars($_POST['nom']);
                            $prenom_post = htmlspecialchars($_POST['prenom']);
                            $email_post  = htmlspecialchars($_POST['email']);
                            $mdp1        = htmlspecialchars($_POST['mdp1']);
                            $mdp2        = htmlspecialchars($_POST['mdp2']);

                            if ($mdp1 != $mdp2) {
                                echo '<p style="color:red;text-align:center">Les mots de passe ne correspondent pas</p>';
                            } else {
                                $stmt = $database->prepare("SELECT id_uti FROM utilisateur WHERE email = ?");
                                $stmt->execute([$email_post]);
                                if ($stmt->fetch()) {
                                    echo '<p style="color:red;text-align:center">Cette adresse e-mail est déjà utilisée.</p>';
                                } else {
                                    $hashedPassword = password_hash($mdp1, PASSWORD_DEFAULT);
                                    $stmt = $database->prepare(
                                        "INSERT INTO utilisateur (nom, prenom, email, type, statut, mdp) VALUES (?, ?, ?, '-', '-', ?)"
                                    );
                                    $stmt->execute([$nom_post, $prenom_post, $email_post, $hashedPassword]);
                                    $_SESSION['user_id'] = $database->lastInsertId();
                                    $_SESSION['email']   = $email_post;
                                    echo '<meta http-equiv="refresh" content="0;URL=checkout_abonnement?choix=' . urlencode($choix) . '">';
                                }
                            }
                        }
                        ?>

                        <ul>
                            <li>
                                <div class="checkout-box">
                                    <h4>Abonnement <?php echo htmlspecialchars($choix); ?> — 1 mois / <?php echo number_format($total, 0); ?> CFA</h4><br>
                                    <div class="checkout-title">
                                        <h4>Connexion ou Inscription</h4>
                                    </div>

                                    <!-- Formulaire connexion -->
                                    <div class="checkout-detail" id="login-form">
                                        <form method="post">
                                            <div class="row g-4">
                                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="email" name="email" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                                    <div class="form-group">
                                                        <label>Mot de passe</label>
                                                        <input type="password" name="mdp" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" name="connexion" class="btn theme-bg-color text-light mt-3">Se connecter</button>
                                        </form>
                                        <br>
                                        <p>
                                            <a href="javascript:void(0);" class="text-theme" onclick="toggleForm()">
                                                Pas encore inscrit ? Inscrivez-vous ici
                                            </a>
                                        </p>
                                    </div>

                                    <!-- Formulaire inscription -->
                                    <div class="checkout-detail" id="register-form" style="display:none;">
                                        <form method="post">
                                            <div class="row g-4">
                                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                                    <div class="form-group">
                                                        <label>Nom</label>
                                                        <input type="text" name="nom" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                                    <div class="form-group">
                                                        <label>Prénom</label>
                                                        <input type="text" name="prenom" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-xxl-12">
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="email" name="email" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-xxl-12">
                                                    <div class="form-floating theme-form-floating">
                                                        <input name="mdp1" type="password" class="form-control" id="password"
                                                            placeholder="Mot de Passe" onkeyup="validatePasswords()">
                                                        <label for="password">Mot de Passe</label>
                                                    </div>
                                                    <small id="password-requirements" style="color:red;display:block;">
                                                        Minimum 8 caractères, incluant une majuscule, un chiffre et un symbole.
                                                    </small>
                                                    <div class="progress mt-2">
                                                        <div id="password-strength-bar" class="progress-bar" role="progressbar"
                                                            style="width:0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="col-xxl-12">
                                                    <div class="form-floating theme-form-floating">
                                                        <input name="mdp2" type="password" class="form-control" id="confirm-password"
                                                            placeholder="Reconfirmer le mot de passe" onkeyup="validatePasswords()">
                                                        <label for="confirm-password">Reconfirmer le mot de passe</label>
                                                    </div>
                                                    <small id="password-match-message" style="color:red;"></small>
                                                </div>
                                            </div>
                                            <button type="submit" id="submit-btn" name="inscrire"
                                                class="btn theme-bg-color text-light mt-3" disabled>S'inscrire</button>
                                        </form>
                                        <br>
                                        <p>
                                            <a href="javascript:void(0);" class="text-theme" onclick="toggleForm()">
                                                Déjà inscrit ? Se connecter
                                            </a>
                                        </p>
                                    </div>

                                </div>
                            </li>
                        </ul>
                    </div>

                    <?php else: ?>
                    <!-- Utilisateur connecté -->
                    <div class="checkout-detail-box">
                        <ul>
                            <li>
                                <div class="checkout-icon">
                                    <lord-icon target=".nav-item"
                                        src="https://cdn.lordicon.com/ggihhudh.json"
                                        trigger="loop-on-hover"
                                        colors="primary:#121331,secondary:#646e78,tertiary:#0baf9a"
                                        class="lord-icon">
                                    </lord-icon>
                                </div>
                                <div class="checkout-box">
                                    <h4>Abonnement <?php echo htmlspecialchars($choix); ?> — 1 mois / <?php echo number_format($total, 0); ?> CFA</h4><br>
                                    <div class="checkout-title">
                                        <h4>Vos Informations</h4>
                                    </div>
                                    <div class="checkout-detail">
                                        <div class="row g-4">
                                            <div class="col-xxl-12">
                                                <div class="delivery-address-box">
                                                    <h4>
                                                        <?php echo htmlspecialchars($user_info['prenom']); ?>
                                                        <?php echo htmlspecialchars($user_info['nom']); ?>
                                                    </h4><br>
                                                    <p>Email : <?php echo htmlspecialchars($user_info['email']); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <?php endif; ?>

                </div>
            </div>

            <!-- Colonne droite : récapitulatif -->
            <div class="col-lg-4">
                <div class="right-side-summery-box">
                    <div class="summery-box-2">
                        <div class="summery-header">
                            <h3>Récapitulatif</h3>
                        </div>
                        <ul class="summery-contain">
                            <li>
                                <h4>Abonnement <?php echo htmlspecialchars($choix); ?> — 1 mois</h4>
                                <h4 class="price"><?php echo number_format($total, 0); ?> CFA</h4>
                            </li>
                            <li>
                                <h4>Téléchargements inclus</h4>
                                <h4 class="price"><?php echo $telechargement; ?> / mois</h4>
                            </li>
                        </ul>
                        <ul class="summery-total">
                            <li class="list-total">
                                <h4>Total (CFA)</h4>
                                <h4 class="price"><?php echo number_format($total, 0); ?> CFA</h4>
                            </li>
                        </ul>
                    </div>

                    <!-- Options de paiement -->
                    <div class="checkout-offer col-lg-12">
                        <div class="offer-title">
                            <div class="offer-icon">
                                <img src="assets/images/feda.png" class="img-fluid" alt="FedaPay Logo">
                            </div>
                            <div class="offer-name">
                                <h6>Options de Paiement avec FedaPay</h6>
                            </div>
                        </div>
                        <ul class="offer-detail">
                            <li>
                                <p><strong>Mobile Money :</strong> MTN, Moov, Celtiis, Wave, Orange, Free Sénégal, Airtel…
                                    <a href="https://docs.fedapay.com/payment-methods/fr/payment-methods-fr" target="_blank">En savoir plus</a>
                                </p>
                            </li>
                            <li>
                                <p><strong>Cartes de Crédit :</strong> Visa et MasterCard.
                                    <a href="https://docs.fedapay.com/payment-methods/fr/payment-methods-fr" target="_blank">Détails ici</a>
                                </p>
                            </li>
                        </ul>
                    </div>

                    <!-- Erreur FedaPay -->
                    <?php if ($fedapayError): ?>
                        <p style="color:red;text-align:center;margin-top:10px;">
                            <?php echo htmlspecialchars($fedapayError); ?>
                        </p>
                    <?php endif; ?>

                    <!-- Bouton paiement -->
                    <?php if ($is_client_connected && $paymentUrl): ?>
                        <button class="btn pay-btn theme-bg-color text-white btn-md w-100 mt-4 fw-bold"
                            onclick="window.location.href='<?php echo htmlspecialchars($paymentUrl); ?>';">
                            Prendre votre abonnement
                        </button>
                    <?php elseif ($is_client_connected && $fedapayError): ?>
                        <button class="btn pay-btn btn-danger btn-md w-100 mt-4 fw-bold" disabled>
                            Erreur — Réessayez
                        </button>
                    <?php else: ?>
                        <button class="btn pay-btn theme-bg-color text-white btn-md w-100 mt-4 fw-bold" disabled>
                            Prendre votre abonnement
                        </button>
                        <p style="color:red;text-align:center;margin-top:8px;">
                            Veuillez vous connecter pour continuer.
                        </p>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>
</section>

<script src="https://cdn.fedapay.com/checkout.js?v=1.1.7"></script>
<script>
function toggleForm() {
    var loginForm    = document.getElementById('login-form');
    var registerForm = document.getElementById('register-form');
    if (loginForm.style.display === 'none') {
        loginForm.style.display    = 'block';
        registerForm.style.display = 'none';
    } else {
        loginForm.style.display    = 'none';
        registerForm.style.display = 'block';
    }
}

function validatePasswords() {
    var password         = document.getElementById('password').value;
    var confirmPassword  = document.getElementById('confirm-password').value;
    var strengthBar      = document.getElementById('password-strength-bar');
    var requirementsText = document.getElementById('password-requirements');
    var matchMessage     = document.getElementById('password-match-message');
    var submitBtn        = document.getElementById('submit-btn');

    var strength = 0;
    if (password.length >= 8)   strength += 25;
    if (/[A-Z]/.test(password)) strength += 25;
    if (/[0-9]/.test(password)) strength += 25;
    if (/[\W]/.test(password))  strength += 25;

    strengthBar.style.width = strength + '%';
    strengthBar.style.backgroundColor = strength < 50 ? 'red' : strength < 75 ? 'orange' : 'green';

    var isValid = strength === 100;
    requirementsText.style.color = isValid ? 'green' : 'red';

    if (password !== confirmPassword || !isValid) {
        matchMessage.textContent = 'Les mots de passe ne correspondent pas ou ne respectent pas les critères.';
        matchMessage.style.color = 'red';
        if (submitBtn) submitBtn.disabled = true;
    } else {
        matchMessage.textContent = 'Les mots de passe sont valides.';
        matchMessage.style.color = 'green';
        if (submitBtn) submitBtn.disabled = false;
    }
}
</script>

<?php require('footer.php'); ?>