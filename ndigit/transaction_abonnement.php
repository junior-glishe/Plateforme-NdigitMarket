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

$status           = $_GET['status'];
$transactionToken = $_GET['transaction_id'];

// ============================================================
// PAIEMENT APPROUVÉ
// ============================================================
if ($status === 'approved') {

    // --------------------------------------------------------
    // 1. RÉCUPÉRER LES DONNÉES (session OU base de données)
    // --------------------------------------------------------
    $user_email     = null;
    $choix          = null;
    $telechargement = null;

    if (!empty($_SESSION['email']) && !empty($_SESSION['choix'])) {
        // Session intacte — cas normal
        $user_email     = $_SESSION['email'];
        $choix          = $_SESSION['choix'];
        $telechargement = $_SESSION['telechargement'];
    } else {
        // Session perdue → récupérer depuis abonnement_temp
        $stmt = $database->prepare("SELECT * FROM abonnement_temp WHERE transaction_token = ?");
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

        $user_email     = $temp['user_email'];
        $choix          = $temp['choix'];
        $telechargement = (int) $temp['telechargement'];

        // Restaurer la session
        $_SESSION['email']          = $user_email;
        $_SESSION['choix']          = $choix;
        $_SESSION['telechargement'] = $telechargement;
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

    $user_id = $user_info['id_uti'];

    // --------------------------------------------------------
    // 3. ENREGISTREMENT / MISE À JOUR DE L'ABONNEMENT
    // --------------------------------------------------------
    try {
        $database->beginTransaction();

        $date_debut        = date('Y-m-d');
        $date_fin          = date('Y-m-d', strtotime('+30 days'));
        $nombre_telecharge = 0;
        $nombre_total      = $telechargement;

        // Vérifier si un abonnement existe déjà
        $stmt = $database->prepare("SELECT * FROM abonnement WHERE id_uti2 = ?");
        $stmt->execute([$user_id]);
        $abonnement_existant = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($abonnement_existant) {
            // Mettre à jour l'abonnement existant
            $stmt = $database->prepare(
                "UPDATE abonnement
                 SET date_debut = ?, date_fin = ?, nombre_telecharge = ?, nombre_total = ?, choix = ?
                 WHERE id_uti2 = ?"
            );
            $stmt->execute([$date_debut, $date_fin, $nombre_telecharge, $nombre_total, $choix, $user_id]);
        } else {
            // Créer un nouvel abonnement
            $stmt = $database->prepare(
                "INSERT INTO abonnement (id_uti2, choix, date_debut, date_fin, nombre_telecharge, nombre_total)
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([$user_id, $choix, $date_debut, $date_fin, $nombre_telecharge, $nombre_total]);

            // Passer l'utilisateur en type "pro"
            $stmt = $database->prepare("UPDATE utilisateur SET type = 'pro' WHERE id_uti = ?");
            $stmt->execute([$user_id]);
        }

        // Supprimer le token temporaire (plus besoin)
        $stmt = $database->prepare("DELETE FROM abonnement_temp WHERE transaction_token = ?");
        $stmt->execute([$transactionToken]);

        $database->commit();

        // Nettoyer la session
        unset($_SESSION['choix'], $_SESSION['telechargement'], $_SESSION['last_abonnement_token']);

    } catch (Exception $e) {
        $database->rollBack();
        echo "<p style='color:red;text-align:center;'>Erreur base de données : " . htmlspecialchars($e->getMessage()) . "</p>";
        require('footer.php');
        exit;
    }

    // --------------------------------------------------------
    // 4. ENVOI DES EMAILS
    // --------------------------------------------------------
    $css = '
        body { font-family: Arial, sans-serif; color: #333; background: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,.1); }
        .title { color: #0ea487; font-size: 22px; font-weight: bold; margin-bottom: 16px; }
        .footer { text-align: center; margin-top: 20px; font-size: 13px; color: #777; }';

    $mailClient = '
    <html><head><style>' . $css . '</style></head><body>
    <div class="container">
        <img src="https://www.ndigitmarket.com/assets/images/loo.png" alt="NDIGIT MARKET" style="height:40px;margin-bottom:16px;">
        <div class="title">Merci pour votre abonnement, '
            . htmlspecialchars($user_info['prenom']) . ' ' . htmlspecialchars($user_info['nom']) . ' !</div>
        <p>Votre abonnement <strong>' . htmlspecialchars($choix) . '</strong> est actif.</p>
        <p>Vous bénéficiez de <strong>' . $telechargement . ' téléchargements</strong> jusqu\'au <strong>' . $date_fin . '</strong>.</p>
        <div class="footer">
            <p>Des questions ? <a href="mailto:contact@ndigitmarket.com">contact@ndigitmarket.com</a></p>
        </div>
    </div></body></html>';

    $mailAdmin = '
    <html><head><style>' . $css . '</style></head><body>
    <div class="container">
        <div class="title">Nouvel abonnement — '
            . htmlspecialchars($user_info['prenom']) . ' ' . htmlspecialchars($user_info['nom']) . '</div>
        <p>Email : ' . htmlspecialchars($user_email) . '</p>
        <p>Plan : <strong>' . htmlspecialchars($choix) . '</strong></p>
        <p>Téléchargements : <strong>' . $telechargement . '</strong></p>
        <p>Validité : ' . $date_debut . ' → ' . $date_fin . '</p>
    </div></body></html>';

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
        $mail->CharSet    = 'UTF-8';
        $mail->setFrom('contact@ndigitmarket.com', 'NDIGIT MARKET');

        // Mail client
        $mail->addAddress($user_email);
        $mail->Subject = 'Confirmation de votre abonnement ' . $choix;
        $mail->Body    = $mailClient;
        $mail->send();

        // Mail admin
        $mail->clearAddresses();
        $mail->addAddress('contact@ndigitmarket.com');
        $mail->Subject = 'Nouvel abonnement — ' . $choix . ' — ' . $user_email;
        $mail->Body    = $mailAdmin;
        $mail->send();

    } catch (Exception $e) {
        error_log("Erreur mail abonnement {$user_email} : " . $mail->ErrorInfo);
    }

    // --------------------------------------------------------
    // 5. REDIRECTION VERS LE COMPTE
    // --------------------------------------------------------
    echo '<meta http-equiv="refresh" content="0;URL=compte">';
    exit;
}

// ============================================================
// TRANSACTION ANNULÉE
// ============================================================
elseif ($status === 'cancelled') {
    ?>
    <div style="text-align:center; padding:40px;">
        <div style="display:inline-block;padding:15px 30px;background:#f44336;color:white;font-size:16px;border-radius:8px;margin-bottom:20px;">
            ❌ La transaction a été annulée.
        </div>
        <p>Vous allez être redirigé vers la page d'abonnement…</p>
    </div>
    <script>setTimeout(function() { window.location.href = 'abonnement'; }, 2500);</script>
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
        <div style="display:inline-block;padding:15px 30px;background:#ff9800;color:white;font-size:16px;border-radius:8px;margin-bottom:20px;">
            ⏳ Votre paiement est en attente de confirmation.
        </div>
        <p>Vous serez notifié par email dès que le paiement sera validé.<br>
        Vous allez être redirigé…</p>
    </div>
    <script>setTimeout(function() { window.location.href = 'abonnement'; }, 3000);</script>
    <?php
    require('footer.php');
    exit;
}

// ============================================================
// STATUT INCONNU
// ============================================================
else {
    echo "<p style='text-align:center;'>Statut inconnu : " . htmlspecialchars($status) . "</p>";
}

require('footer.php');
?>