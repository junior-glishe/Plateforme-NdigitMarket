<?php
// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('../../include/connect.php');
require '../../vendor/autoload.php';
// Inclure PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../vendor/PHPMailer/src/PHPMailer.php';
require '../../vendor/PHPMailer/src/SMTP.php';

// Vérifier si le fichier autoload existe
$autoloadPath = '../../vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    die("Erreur : Le fichier vendor/autoload.php est introuvable. Assurez-vous d'avoir installé PHPMailer via Composer.");
}
require $autoloadPath;

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_email'])) {
    $sujet = htmlspecialchars($_POST['sujet']);
    $message = trim($_POST['message']); // Récupérer le HTML de TinyMCE

    // Vérifier que le message n'est pas vide
    if (empty($message)) {
        $messageError = "Erreur : Le message ne peut pas être vide.";
    } else {
        // Initialiser PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configuration SMTP
            $mail->isSMTP();
            $mail->Host = 'mail03.lwspanel.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'contact@ndigitmarket.com';
            $mail->Password = 'Nawane@2023';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Forcer l'encodage UTF-8
            $mail->CharSet = 'UTF-8';

            // Activer le débogage SMTP pour voir les erreurs
            $mail->SMTPDebug = 2;
            $mail->Debugoutput = 'html';

            // Paramètres de l'expéditeur
            $mail->setFrom('contact@ndigitmarket.com', 'NDIGIT MARKET');

            // Récupérer tous les clients avec nom, prénom et email
            $query = "SELECT id_uti, nom, prenom, email FROM utilisateur";
            $stmt = $database->prepare($query);
            $stmt->execute();
            $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($clients)) {
                throw new Exception("Aucun client actif trouvé dans la base de données.");
            }

            $successCount = 0;
            $errorCount = 0;

            // Envoyer un email personnalisé à chaque client
            foreach ($clients as $client) {
                $email = $client['email'];
                $nom = htmlspecialchars($client['nom']);
                $prenom = htmlspecialchars($client['prenom']);
                $messagePersonnalise = str_replace('[NOM]', $nom, str_replace('[PRENOM]', $prenom, $message));
                $messagePersonnalise = "Salut $prenom $nom, <br>" . $messagePersonnalise;

                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $mail->addAddress($email);
                    $mail->isHTML(true);
                    $mail->Subject = $sujet;
                    $mail->Body = $messagePersonnalise;
                    $mail->AltBody = strip_tags($messagePersonnalise);

                    $mail->send();
                    $successCount++;
                    $mail->clearAddresses();
                } else {
                    $errorCount++;
                }
            }

            // Envoyer une copie à ton email avec le rapport
            $mail->clearAddresses();
            $mail->addAddress('contact@ndigitmarket.com');
            $mail->Subject = 'Rapport d\'envoi d\'emails - ' . $sujet;
            $rapport = "Envoi d'emails effectué le " . date('d/m/Y H:i') . ":<br>"
                     . "Sujet : $sujet<br>"
                     . "Modèle de message : <br>" . $message . "<br>"
                     . "Emails envoyés avec succès : $successCount<br>"
                     . "Emails en erreur : $errorCount";
            $mail->Body = $rapport;
            $mail->AltBody = strip_tags($rapport);
            $mail->send();

            $messageSuccess = "Emails envoyés avec succès : $successCount. Erreurs : $errorCount. Une copie a été envoyée à contact@ndigitmarket.com.";
               echo '
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script>
                        Swal.fire({
                            icon: "success",
                            title: "Succès !",
                            text: "Mail envoyé avec succès",
                            confirmButtonColor: "#0a4e83"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "envoie_mail.php";
                            }
                        });
                    </script>';
        } catch (Exception $e) {
            $messageError = "Erreur lors de l'envoi : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Envoyer un email à tous les clients</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Inclure TinyMCE -->
    <script src="assets/js/tinymce/tinymce.min.js"></script>
    <style>
        body {
            background-color: #f5f7fb;
            font-family: 'Poppins', sans-serif;
            margin: 0;
        }
        .page-body {
            padding: 40px 20px;
        }
        .container-fluid {
            max-width: 900px;
            margin: 0 auto;
        }
        .card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            padding: 30px;
        }
        .title-header {
            margin-bottom: 30px;
            text-align: center;
        }
        .title-header h5 {
            font-size: 24px;
            font-weight: 600;
            color: #1c242e;
            margin: 0;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: 500;
            display: block;
            margin-bottom: 8px;
            color: #1c242e;
            font-size: 16px;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        .form-group input:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.2);
        }
        .tox-tinymce {
            border: 1px solid #ddd;
            border-radius: 8px;
            min-height: 250px;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 14px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: background-color 0.3s ease, transform 0.1s ease;
        }
        .btn:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        .btn:active {
            transform: translateY(0);
        }
        .message-success, .message-error {
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
            font-size: 14px;
            text-align: center;
        }
        .message-success {
            background-color: #e6f4ea;
            color: #2e7d32;
        }
        .message-error {
            background-color: #fdeded;
            color: #d32f2f;
        }
        @media screen and (max-width: 480px) {
            .page-body {
                padding: 20px 15px;
            }
            .card {
                padding: 20px;
            }
            .title-header h5 {
                font-size: 20px;
            }
            .form-group label {
                font-size: 14px;
            }
            .form-group input {
                padding: 10px;
                font-size: 13px;
            }
            .btn {
                padding: 12px;
                font-size: 14px;
            }
            .message-success, .message-error {
                font-size: 13px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
<?php require('header.php'); ?>

<div class="page-body">
    <div class="container-fluid">
        <div class="card">
            <div class="title-header">
                <h5>Envoyer un email à tous les clients</h5>
            </div>
            <?php if (isset($messageSuccess)): ?>
                <div class="message-success"><?php echo htmlspecialchars($messageSuccess); ?></div>
            <?php elseif (isset($messageError)): ?>
                <div class="message-error"><?php echo htmlspecialchars($messageError); ?></div>
            <?php endif; ?>
            <form method="POST" action="" id="emailForm">
                <div class="form-group">
                    <label for="sujet">Sujet de l'email :</label>
                    <input type="text" name="sujet" id="sujet" required placeholder="Entrez le sujet de l'email">
                </div>
                <div class="form-group">
                    <label for="message">Message personnalisé (utilisez [PRENOM] et [NOM] pour personnalisation) :</label>
                    <textarea name="message" id="message"></textarea>
                </div>
                <button type="submit" name="send_email" class="btn">Envoyer les emails</button>
            </form>
        </div>
    </div>
</div>

<?php require('footer.php') ?>

<script>
    // Initialiser TinyMCE sur le textarea avec les options pour URLs absolues
    tinymce.init({
        selector: '#message',
        plugins: 'link lists image code',
        toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent | link image | code',
        menubar: false,
        height: 250,
        relative_urls: false, // Désactiver les URLs relatives
        remove_script_host: false, // Conserver l'hôte dans les URLs
        convert_urls: false, // Empêcher la conversion des URLs
        images_upload_url: 'postAcceptor.php', // Optionnel : pour uploader des images (à configurer séparément)
        setup: function (editor) {
            editor.on('init', function () {
                console.log('TinyMCE initialisé');
            });
            // Personnaliser le gestionnaire de liens pour forcer les URLs absolues
            editor.ui.registry.addButton('link', {
                tooltip: 'Insert/edit link',
                onAction: function () {
                    editor.windowManager.open({
                        title: 'Insert link',
                        body: [
                            { type: 'textbox', name: 'href', label: 'URL', value: 'https://' }
                        ],
                        onsubmit: function (e) {
                            editor.execCommand('mceInsertLink', false, { href: e.data.href });
                        }
                    });
                }
            });
        }
    });

    // Ajouter un écouteur pour gérer la soumission du formulaire
    document.getElementById('emailForm').addEventListener('submit', function (event) {
        const sujet = document.getElementById('sujet').value;
        const message = tinymce.get('message').getContent(); // Récupérer le contenu de TinyMCE
        if (!message || message.trim() === '') {
            event.preventDefault(); // Empêcher la soumission si le champ est vide
            alert('Veuillez entrer un message.');
            return;
        }
        console.log('Formulaire soumis');
        console.log('Sujet : ', sujet);
        console.log('Message : ', message);
        // Mettre à jour la valeur du textarea avec le contenu de TinyMCE
        document.querySelector('#message').value = message;
    });
</script>
</body>
</html>