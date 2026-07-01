<?php
require 'vendor/autoload.php';
require('header.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';

// ============================================================
// VÉRIFICATION DES PARAMÈTRES
// ============================================================
if (!isset($_GET['status']) || !isset($_GET['transaction_id'])) {
    echo "<p>Paramètres manquants.</p>";
    require('footer.php');
    exit;
}

$status          = $_GET['status'];
$transactionToken = $_GET['transaction_id'];

// ============================================================
// PAIEMENT APPROUVÉ
// ============================================================
if ($status === 'approved') {

    // --------------------------------------------------------
    // 1. RÉCUPÉRER LES DONNÉES (session OU base de données)
    // --------------------------------------------------------
    $user_email = null;
    $panier     = [];

    if (!empty($_SESSION['email']) && !empty($_SESSION['panier'])) {
        // Session intacte — cas normal
        $user_email = $_SESSION['email'];
        $panier     = $_SESSION['panier'];
    } else {
        // Session perdue → récupérer depuis panier_temp
        $stmt = $database->prepare("SELECT * FROM panier_temp WHERE transaction_token = ?");
        $stmt->execute([$transactionToken]);
        $temp = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$temp) {
            echo "<p style='color:red;text-align:center;'>
                Erreur : session expirée et aucune donnée de secours trouvée.<br>
                Contactez-nous à <a href='mailto:contact@ndigitmarket.com'>contact@ndigitmarket.com</a>
                en indiquant votre ID de transaction : <strong>" . htmlspecialchars($transactionToken) . "</strong>
            </p>";
            require('footer.php');
            exit;
        }

        $user_email = $temp['user_email'];
        $panier     = json_decode($temp['panier_data'], true);

        // Restaurer la session pour la suite
        $_SESSION['email']  = $user_email;
        $_SESSION['panier'] = $panier;
    }

    // --------------------------------------------------------
    // 2. RÉCUPÉRER L'UTILISATEUR EN BASE
    // --------------------------------------------------------
    $stmt = $database->prepare("SELECT * FROM utilisateur WHERE email = ?");
    $stmt->execute([$user_email]);
    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user_info) {
        echo "<p style='color:red;text-align:center;'>Erreur : utilisateur introuvable.</p>";
        require('footer.php');
        exit;
    }

    // --------------------------------------------------------
    // 3. RÉCUPÉRER LES PRODUITS
    // --------------------------------------------------------
    $ids = array_keys($panier);

    if (empty($ids)) {
        echo "<p style='color:red;text-align:center;'>Erreur : panier vide.</p>";
        require('footer.php');
        exit;
    }

    $query = "SELECT id, nom_article, image, prix, prix_reduction, fichier
              FROM produits
              WHERE id IN (" . implode(',', array_map('intval', $ids)) . ")";
    $stmt = $database->prepare($query);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($products)) {
        echo "<p style='color:red;text-align:center;'>Erreur : produits introuvables.</p>";
        require('footer.php');
        exit;
    }

    // --------------------------------------------------------
    // 4. ENREGISTREMENT DE LA COMMANDE EN BASE
    // --------------------------------------------------------
    try {
        $database->beginTransaction();

        $commande_id   = uniqid('CMD_');
        $date_commande = date('Y-m-d H:i:s');
        $total         = 0;

        foreach ($products as $product) {
            $productId   = $product['id'];
            $quantity    = $panier[$productId];
            $price       = ($product['prix_reduction'] > 0) ? $product['prix_reduction'] : $product['prix'];
            $total      += $price * $quantity;

            $stmt = $database->prepare(
                "INSERT INTO commande
                    (commande_id, id_client, email, id_article, image, prix, fichier, date_commande)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $commande_id,
                $user_info['id_uti'],
                $user_email,
                $product['id'],
                $product['image'],
                $price,
                $product['fichier'],
                $date_commande
            ]);
        }

        // Supprimer le panier temporaire (plus besoin)
        $stmt = $database->prepare("DELETE FROM panier_temp WHERE transaction_token = ?");
        $stmt->execute([$transactionToken]);

        $database->commit();

        // Vider le panier SESSION après le commit (ordre correct)
        $_SESSION['id_commande'] = $commande_id;
        unset($_SESSION['panier']);

    } catch (Exception $e) {
        $database->rollBack();
        echo "<p style='color:red;text-align:center;'>Erreur base de données : " . htmlspecialchars($e->getMessage()) . "</p>";
        require('footer.php');
        exit;
    }

    // --------------------------------------------------------
    // 5. ENVOI DES EMAILS
    // --------------------------------------------------------

    // --- Template commun ---
    $tableRows = '';
    foreach ($products as $product) {
        $price        = ($product['prix_reduction'] > 0) ? $product['prix_reduction'] : $product['prix'];
        $quantity     = $panier[$product['id']];
        $productTotal = $price * $quantity;
        $tableRows   .= '
        <tr>
            <td>' . htmlspecialchars($product['nom_article']) . '</td>
            <td>' . number_format($price, 2) . ' CFA</td>
            <td>' . $quantity . '</td>
            <td>' . number_format($productTotal, 2) . ' CFA</td>
        </tr>';
    }

    $cssCommun = '
        body { font-family: Arial, sans-serif; color: #333; background-color: #f4f4f4; padding: 20px; }
        .container { width:100%; max-width:600px; margin:0 auto; background:#fff; padding:30px; border-radius:8px; box-shadow:0 4px 6px rgba(0,0,0,0.1); }
        .header { text-align:center; padding-bottom:20px; }
        .header img { width:150px; }
        .title { color:#0ea487; font-size:24px; font-weight:bold; margin-bottom:20px; }
        table { width:100%; border-collapse:collapse; margin-bottom:20px; }
        th, td { padding:10px; text-align:left; border-bottom:1px solid #ddd; }
        th { background-color:#0ea487; color:white; }
        .total { font-size:18px; font-weight:bold; color:#0ea487; text-align:right; }
        .footer { text-align:center; margin-top:20px; font-size:14px; color:#777; }';

    // --- Mail client ---
    $mailClient = '
    <html><head><style>' . $cssCommun . '</style></head><body>
    <div class="container">
        <div class="header"><img src="https://www.ndigitmarket.com/assets/images/loo.png" alt="NDIGIT MARKET"></div>
        <div class="title">Merci pour votre commande, '
            . htmlspecialchars($user_info['prenom']) . ' ' . htmlspecialchars($user_info['nom']) . ' !</div>
        <p>Voici les détails de votre commande :</p>
        <table>
            <tr><th>Produit</th><th>Prix</th><th>Quantité</th><th>Total</th></tr>
            ' . $tableRows . '
        </table>
        <p class="total">Total : ' . number_format($total, 2) . ' CFA</p>
        <div class="footer">
            <p>Merci de faire confiance à NDIGIT MARKET !</p>
            <p>Contact : <a href="mailto:contact@ndigitmarket.com">contact@ndigitmarket.com</a></p>
        </div>
    </div></body></html>';

    // --- Mail admin ---
    $mailAdmin = '
    <html><head><style>' . $cssCommun . '</style></head><body>
    <div class="container">
        <div class="header"><img src="https://www.ndigitmarket.com/assets/images/loo.png" alt="NDIGIT MARKET"></div>
        <div class="title">Nouvelle commande de '
            . htmlspecialchars($user_info['prenom']) . ' ' . htmlspecialchars($user_info['nom']) . '</div>
        <p>Email client : ' . htmlspecialchars($user_email) . '</p>
        <p>ID commande : ' . htmlspecialchars($commande_id) . '</p>
        <table>
            <tr><th>Produit</th><th>Prix</th><th>Quantité</th><th>Total</th></tr>
            ' . $tableRows . '
        </table>
        <p class="total">Total : ' . number_format($total, 2) . ' CFA</p>
        <div class="footer"><p>Merci de traiter cette commande rapidement.</p></div>
    </div></body></html>';

    // --- Envoi ---
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'mail03.lwspanel.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'contact@ndigitmarket.com';
        $mail->Password   = 'Nawane@2023';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->isHTML(true);
        $mail->setFrom('contact@ndigitmarket.com', 'NDIGIT MARKET');
        $mail->CharSet = 'UTF-8';

        // Mail au client
        $mail->addAddress($user_email);
        $mail->Subject = 'Confirmation de votre commande';
        $mail->Body    = $mailClient;
        $mail->send();

        // Mail à l'admin
        $mail->clearAddresses();
        $mail->addAddress('contact@ndigitmarket.com');
        $mail->Subject = 'Nouvelle commande reçue – ' . $commande_id;
        $mail->Body    = $mailAdmin;
        $mail->send();

    } catch (Exception $e) {
        // On log l'erreur mais on ne bloque pas la redirection
        error_log("Erreur envoi mail commande {$commande_id} : " . $mail->ErrorInfo);
    }

    // --------------------------------------------------------
    // 6. REDIRECTION VERS LA PAGE DE VALIDATION
    // --------------------------------------------------------
    echo '<meta http-equiv="refresh" content="0;URL=validation?commande_id=' . urlencode($commande_id) . '">';
    exit;
}

// ============================================================
// TRANSACTION ANNULÉE
// ============================================================
elseif ($status === 'cancelled') {
    ?>
    <div style="text-align:center; padding:40px;">
        <div id="cancel-alert" style="
            display:inline-block;
            padding:15px 30px;
            background-color:#f44336;
            color:white;
            font-size:16px;
            border-radius:8px;
            margin-bottom:20px;">
            ❌ La transaction a été annulée.
        </div>
        <p>Vous allez être redirigé vers le panier…</p>
    </div>
    <script>
        setTimeout(function() {
            window.location.href = 'checkout';
        }, 2500);
    </script>
    <?php
    require('footer.php');
    exit;
}

// ============================================================
// TRANSACTION EN ATTENTE
// ============================================================
elseif ($status === 'pending') {
    ?>
    <div style="text-align:center; padding:40px;">
        <div style="
            display:inline-block;
            padding:15px 30px;
            background-color:#ff9800;
            color:white;
            font-size:16px;
            border-radius:8px;
            margin-bottom:20px;">
            ⏳ Votre paiement est en attente de confirmation.
        </div>
        <p>Vous serez notifié par email dès que le paiement sera validé.<br>
        Vous allez être redirigé vers le panier…</p>
    </div>
    <script>
        setTimeout(function() {
            window.location.href = 'checkout';
        }, 3000);
    </script>
    <?php
    require('footer.php');
    exit;
}

// ============================================================
// STATUT INCONNU
// ============================================================
else {
    echo "<p style='text-align:center;'>Statut de transaction inconnu : " . htmlspecialchars($status) . "</p>";
}

require('footer.php');
?>