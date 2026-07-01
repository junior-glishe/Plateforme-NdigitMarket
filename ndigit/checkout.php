<?php
require('header.php');
require_once('fedapay/init.php');

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    $show_login = true;
} else {
    $user_email = $_SESSION['email'];
    $stmt = $database->prepare("SELECT nom, prenom, email FROM utilisateur WHERE email = ?");
    $stmt->execute([$user_email]);
    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);

    $panier = isset($_SESSION['panier']) ? $_SESSION['panier'] : [];
}

// Panier vide
if (empty($_SESSION['panier']) || !isset($_SESSION['panier'])) {
    echo '
<div class="confirmation-message" style="text-align: center; padding: 20px; background-color: #f8f8f8; border-radius: 10px;">
    <h2>Votre panier est vide.</h2><br>
    <li>
        <button onclick="location.href = \'shop\';" class="btn btn-light shopping-button text-dark">
            <i class="fa-solid fa-arrow-left-long"></i> Retourner au shopping
        </button>
    </li>
</div>';
    require('footer.php');
    exit;
}

$ids = array_keys($_SESSION['panier']);

if (empty($ids)) {
    echo '
<div class="confirmation-message" style="text-align: center; padding: 20px; background-color: #f8f8f8; border-radius: 10px;">
    <h2>Votre panier est vide.</h2><br>
    <li>
        <button onclick="location.href = \'shop\';" class="btn btn-light shopping-button text-dark">
            <i class="fa-solid fa-arrow-left-long"></i> Retourner au shopping
        </button>
    </li>
</div>';
    require('footer.php');
    exit;
}

// Récupérer les produits
$query = "SELECT * FROM produits WHERE id IN (" . implode(',', array_map('intval', $ids)) . ")";
$stmt = $database->prepare($query);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcul du total
$total = 0;
foreach ($products as $product) {
    $priceAfterDiscount = ($product['prix_reduction'] > 0) ? $product['prix_reduction'] : $product['prix'];
    $quantity = $_SESSION['panier'][$product['id']];
    $total += $priceAfterDiscount * $quantity;
}

// Gestion du code promo
$discount      = 0;
$final_total   = $total;
$error_message = '';
$promo_applied = false;
$promo_expiry_date = '2026-04-30';
$current_date  = date('Y-m-d');

if (isset($_POST['codePromo'])) {
    $code_promo = trim($_POST['codePromo']);
    if (strtoupper($code_promo) === 'NDIGIT20') {
        if ($current_date <= $promo_expiry_date) {
            $promo_applied = true;
            $discount      = $total * 0.25;
            $final_total   = $total - $discount;
        } else {
            $error_message = 'La promotion est déjà passée pour ce code promo';
        }
    } else {
        $error_message = 'Code promo invalide';
    }
}

// ----------------------------------------------------------------
// Créer la transaction FedaPay
// ----------------------------------------------------------------
use FedaPay\FedaPay;
use FedaPay\Transaction;

FedaPay::setEnvironment('live');
FedaPay::setApiKey('sk_live_4ZBrSu1MUy-TPU2kNv8GI-IO');

$nom    = ($user_info['nom']    ?? 'Inconnu');
$prenom = ($user_info['prenom'] ?? 'Inconnu');
$email  = ($user_info['email']  ?? 'inconnu@example.com');

$transactionToken = uniqid('TXN_', true);
$callback_url = "https://www.ndigitmarket.com/transaction_status.php?status=success&transaction_id=" . urlencode($transactionToken);
$cancel_url   = "https://www.ndigitmarket.com/transaction_status.php?status=cancelled&transaction_id=" . urlencode($transactionToken);

$paymentUrl      = '';
$fedapayError    = '';
$is_client_connected = isset($_SESSION['user_id']);

if ($is_client_connected) {
    try {
        $transaction = Transaction::create([
            "description"  => "Achat sur NDIGIT MARKET",
            "amount"       => (int) $final_total,
            "currency"     => ["iso" => "XOF"],
            "callback_url" => $callback_url,
            "cancel_url"   => $cancel_url,
            "metadata"     => [
                "nom"        => $nom,
                "prenom"     => $prenom,
                "email"      => $email,
                "total"      => $final_total,
                "code_promo" => $promo_applied ? 'NDIGIT20' : ''
            ]
        ]);

        $paymentUrl = $transaction->generateToken()->url;

        // ----------------------------------------------------------------
        // SAUVEGARDE DU PANIER EN BASE pour survivre à la perte de session
        // ----------------------------------------------------------------
        // Supprimer un éventuel ancien enregistrement pour cet email
        $stmt = $database->prepare("DELETE FROM panier_temp WHERE user_email = ?");
        $stmt->execute([$email]);

        $stmt = $database->prepare(
            "INSERT INTO panier_temp (transaction_token, user_email, panier_data, total, created_at)
             VALUES (?, ?, ?, ?, NOW())"
        );
        $stmt->execute([
            $transactionToken,
            $email,
            json_encode($_SESSION['panier']),
            $final_total
        ]);

        // Stocker le token en session aussi (double sécurité)
        $_SESSION['last_transaction_token'] = $transactionToken;

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
                    <h2>Facturation</h2>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="index.html"><i class="fa-solid fa-house"></i></a>
                            </li>
                            <li class="breadcrumb-item active">Facturation</li>
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
                            $mdp   = htmlspecialchars($_POST['mdp']);
                            $email = htmlspecialchars($_POST['email']);

                            $resultats = $database->query('SELECT * FROM utilisateur');
                            $found = false;
                            while ($donnee = $resultats->fetch()) {
                                if ($donnee['email'] == $email && password_verify($mdp, $donnee['mdp'])) {
                                    $_SESSION['user_id'] = $donnee['id_uti'];
                                    $_SESSION['email']   = $email;
                                    echo '<meta http-equiv="refresh" content="0;URL=checkout">';
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
                            $nom    = htmlspecialchars($_POST['nom']);
                            $prenom = htmlspecialchars($_POST['prenom']);
                            $email  = htmlspecialchars($_POST['email']);
                            $mdp1   = htmlspecialchars($_POST['mdp1']);
                            $mdp2   = htmlspecialchars($_POST['mdp2']);

                            if ($mdp1 != $mdp2) {
                                echo '<p style="color:red;text-align:center">Les mots de passe ne correspondent pas</p>';
                            } else {
                                $stmt = $database->prepare("SELECT id_uti FROM utilisateur WHERE email = ?");
                                $stmt->execute([$email]);
                                if ($stmt->fetch()) {
                                    echo '<p style="color:red;text-align:center">Cette adresse e-mail est déjà utilisée.</p>';
                                } else {
                                    $hashedPassword = password_hash($mdp1, PASSWORD_DEFAULT);
                                    $stmt = $database->prepare(
                                        "INSERT INTO utilisateur (nom, prenom, email, type, statut, mdp) VALUES (?, ?, ?, '-', '-', ?)"
                                    );
                                    $stmt->execute([$nom, $prenom, $email, $hashedPassword]);
                                    $_SESSION['user_id'] = $database->lastInsertId();
                                    $_SESSION['email']   = $email;
                                    echo '<meta http-equiv="refresh" content="0;URL=checkout">';
                                }
                            }
                        }
                        ?>
                        <ul>
                            <li>
                                <div class="checkout-box">
                                    <div class="checkout-title">
                                        <h4>Connexion ou Inscription</h4>
                                    </div>

                                    <!-- Formulaire connexion -->
                                    <div class="checkout-detail" id="login-form">
                                        <form method="post">
                                            <div class="row g-4">
                                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                                    <div class="form-group">
                                                        <label for="email">Email</label>
                                                        <input type="email" id="email" name="email" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                                    <div class="form-group">
                                                        <label for="mdp">Mot de passe</label>
                                                        <input type="password" id="mdp" name="mdp" class="form-control" required>
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
                                                class="btn theme-bg-color text-light mt-3">S'inscrire</button>
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
                            <h3>Votre Panier</h3>
                        </div>

                        <ul class="summery-contain">
                            <?php if (empty($products)): ?>
                                <li><p>Votre panier est vide.</p></li>
                            <?php else: ?>
                                <?php foreach ($products as $product): ?>
                                    <?php
                                        $priceAfterDiscount = ($product['prix_reduction'] > 0) ? $product['prix_reduction'] : $product['prix'];
                                        $quantity = $_SESSION['panier'][$product['id']];
                                        $totalProductPrice = $priceAfterDiscount * $quantity;
                                    ?>
                                    <li>
                                        <img src="back-end/apps/<?php echo htmlspecialchars($product['image']); ?>"
                                            class="img-fluid blur-up lazyloaded checkout-image" alt="">
                                        <h4><?php echo htmlspecialchars($product['nom_article']); ?>
                                            <span>X <?php echo $quantity; ?></span>
                                        </h4>
                                        <h4 class="price"><?php echo number_format($priceAfterDiscount, 2); ?> CFA</h4>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>

                        <ul class="summery-total">
                            <li>
                                <h4>Sous-total</h4>
                                <h4 class="price"><?php echo number_format($total, 2); ?> CFA</h4>
                            </li>
                            <?php if ($promo_applied): ?>
                                <li>
                                    <h4>Réduction (NDIGIT20)</h4>
                                    <h4 class="price text-success">-<?php echo number_format($discount, 2); ?> CFA</h4>
                                </li>
                            <?php endif; ?>
                            <li class="list-total">
                                <h4>Total (CFA)</h4>
                                <h4 class="price"><?php echo number_format($final_total, 2); ?> CFA</h4>
                            </li>
                        </ul>
                    </div>

                    <!-- Code promo -->
                    <div class="code-promo-section checkout-offer">
                        <form method="post">
                            <label for="codePromo">Code Promo :</label>
                            <input type="text" id="codePromo" name="codePromo"
                                placeholder="Entrez votre code promo"
                                value="<?php echo isset($_POST['codePromo']) ? htmlspecialchars($_POST['codePromo']) : ''; ?>">
                            <button type="submit" class="btn btn-sm theme-bg-color text-light mt-2">Appliquer</button>
                        </form>
                        <?php if ($error_message): ?>
                            <p style="color:red;font-size:14px;margin-top:5px;">
                                <?php echo htmlspecialchars($error_message); ?>
                            </p>
                        <?php elseif ($promo_applied): ?>
                            <p style="color:green;font-size:14px;margin-top:5px;">Code promo appliqué !</p>
                        <?php endif; ?>
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
                            Passer la commande
                        </button>
                    <?php elseif ($is_client_connected && $fedapayError): ?>
                        <button class="btn pay-btn btn-danger btn-md w-100 mt-4 fw-bold" disabled>
                            Erreur — Réessayez
                        </button>
                    <?php else: ?>
                        <button class="btn pay-btn theme-bg-color text-white btn-md w-100 mt-4 fw-bold" disabled>
                            Passer la commande
                        </button>
                        <p style="color:red;text-align:center;margin-top:8px;">
                            Veuillez vous connecter pour passer la commande.
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
    var password        = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirm-password').value;
    var strengthBar     = document.getElementById('password-strength-bar');
    var requirementsText= document.getElementById('password-requirements');
    var matchMessage    = document.getElementById('password-match-message');
    var submitBtn       = document.getElementById('submit-btn');

    var strength = 0;
    if (password.length >= 8)    strength += 25;
    if (/[A-Z]/.test(password))  strength += 25;
    if (/[0-9]/.test(password))  strength += 25;
    if (/[\W]/.test(password))   strength += 25;

    strengthBar.style.width = strength + '%';
    strengthBar.style.backgroundColor = strength < 50 ? 'red' : strength < 75 ? 'orange' : 'green';

    var isValid = strength === 100;
    requirementsText.style.color = isValid ? 'green' : 'red';

    if (password !== confirmPassword || !isValid) {
        matchMessage.textContent  = 'Les mots de passe ne correspondent pas ou ne respectent pas les critères.';
        matchMessage.style.color  = 'red';
        if (submitBtn) submitBtn.disabled = true;
    } else {
        matchMessage.textContent  = 'Les mots de passe sont valides.';
        matchMessage.style.color  = 'green';
        if (submitBtn) submitBtn.disabled = false;
    }
}
</script>

<?php require('footer.php'); ?>