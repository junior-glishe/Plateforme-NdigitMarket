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
if (!isset($_SESSION)) {
    session_start();
}

// Forcer le fuseau horaire à WAT
date_default_timezone_set('Africa/Lagos');

// Tableau pour stocker les emails envoyés
$_SESSION['emails_envoyes'] = $_SESSION['emails_envoyes'] ?? [];

// Vérifier si le formulaire d'envoi d'email est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_email'])) {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $sujet = htmlspecialchars($_POST['sujet']);
    $message = $_POST['message'];
    $abonnement_status = htmlspecialchars($_POST['abonnement_status']);
    $date_fin = htmlspecialchars($_POST['date_fin']);
    $nombre_restant = intval($_POST['nombre_restant']);
    $nombre_total = intval($_POST['nombre_total']);

    if (!$email || empty($message)) {
        $messageError = "Erreur : Email invalide ou message vide.";
    } else {
        try {
            $mail = new PHPMailer(true);
            
            // Configuration SMTP
            $mail->isSMTP();
            $mail->Host = 'mail03.lwspanel.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'contact@ndigitmarket.com';
            $mail->Password = 'Nawane@2023';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';
            $mail->SMTPDebug = 0;
            
            // Expéditeur
            $mail->setFrom('contact@ndigitmarket.com', 'NDIGIT MARKET');
            $mail->addAddress($email, "$prenom $nom");
            
            // Contenu
            $mail->isHTML(true);
            $mail->Subject = $sujet;
            
            // Template d'email moderne
            $messagePersonnalise = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <style>
                    @import url("https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap");
                    body {
                        font-family: "Inter", sans-serif;
                        margin: 0;
                        padding: 0;
                        background-color: #f5f7fb;
                    }
                    .email-container {
                        max-width: 600px;
                        margin: 0 auto;
                        background-color: #ffffff;
                        border-radius: 20px;
                        overflow: hidden;
                        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                    }
                    .header {
                        background: linear-gradient(135deg, #087d67 0%, #065a4a 100%);
                        padding: 40px 30px;
                        text-align: center;
                    }
                    .header img {
                        max-width: 180px;
                        margin-bottom: 20px;
                    }
                    .header h1 {
                        color: white;
                        font-size: 28px;
                        font-weight: 700;
                        margin: 0;
                        line-height: 1.3;
                    }
                    .header p {
                        color: rgba(255,255,255,0.9);
                        font-size: 16px;
                        margin: 10px 0 0;
                    }
                    .content {
                        padding: 40px 30px;
                    }
                    .greeting {
                        font-size: 18px;
                        font-weight: 600;
                        color: #1a2634;
                        margin-bottom: 20px;
                    }
                    .message {
                        color: #374151;
                        line-height: 1.8;
                        font-size: 15px;
                        margin-bottom: 30px;
                    }
                    .info-box {
                        background: linear-gradient(135deg, #e8f5f2 0%, #ffffff 100%);
                        border: 1px solid #087d67;
                        border-radius: 12px;
                        padding: 25px;
                        margin: 30px 0;
                        text-align: center;
                    }
                    .info-box h3 {
                        color: #087d67;
                        font-size: 20px;
                        font-weight: 700;
                        margin: 0 0 10px;
                    }
                    .info-box p {
                        color: #374151;
                        margin: 5px 0;
                    }
                    .date-highlight {
                        font-size: 24px;
                        font-weight: 800;
                        color: #f97316;
                        margin: 15px 0;
                    }
                    .stats-box {
                        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
                        border: 2px dashed #087d67;
                        border-radius: 12px;
                        padding: 20px;
                        margin: 30px 0;
                        text-align: center;
                    }
                    .stats-box h2 {
                        color: #087d67;
                        font-size: 36px;
                        font-weight: 800;
                        margin: 10px 0;
                    }
                    .stats-box p {
                        color: #374151;
                        font-size: 16px;
                    }
                    .btn-container {
                        text-align: center;
                        margin: 35px 0 20px;
                    }
                    .btn {
                        display: inline-block;
                        background: linear-gradient(135deg, #f97316 0%, #e67e22 100%);
                        color: white;
                        text-decoration: none;
                        padding: 15px 35px;
                        border-radius: 50px;
                        font-weight: 600;
                        font-size: 16px;
                        box-shadow: 0 5px 15px rgba(249,115,22,0.3);
                        transition: all 0.3s;
                    }
                    .btn:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 8px 25px rgba(249,115,22,0.4);
                    }
                    .btn-primary {
                        background: linear-gradient(135deg, #087d67 0%, #065a4a 100%);
                        box-shadow: 0 5px 15px rgba(8,125,103,0.3);
                    }
                    .footer {
                        background: #f8f9fa;
                        padding: 30px;
                        text-align: center;
                        border-top: 1px solid #e5e7eb;
                    }
                    .footer p {
                        color: #6b7280;
                        font-size: 14px;
                        margin: 5px 0;
                    }
                    .footer a {
                        color: #087d67;
                        text-decoration: none;
                    }
                    .footer a:hover {
                        text-decoration: underline;
                    }
                </style>
            </head>
            <body>
                <div class="email-container">
                    <div class="header">
                        <img src="https://www.ndigitmarket.com/assets/images/loo.png" alt="NDIGIT MARKET">
                        <h1>NDIGITMARKET</h1>
                        <p>Votre partenaire pour des ressources web premium</p>
                    </div>
                    
                    <div class="content">
                        <div class="greeting">
                            Bonjour ' . htmlspecialchars($prenom) . ' ' . htmlspecialchars($nom) . ',
                        </div>
                        
                        <div class="message">
                            ' . $message . '
                        </div>';
                        
            // Ajouter une box d'information selon le statut
            if ($abonnement_status === 'En cours') {
                $messagePersonnalise .= '
                        <div class="stats-box">
                            <h3>📊 Votre abonnement est actif !</h3>
                            <p>Vous avez actuellement</p>
                            <h2>' . $nombre_restant . ' / ' . $nombre_total . '</h2>
                            <p><strong>téléchargements restants</strong></p>
                            <p>Profitez pleinement de votre abonnement pour télécharger tous les templates dont vous avez besoin.</p>
                        </div>';
            } elseif ($abonnement_status === 'Presque fini') {
                $messagePersonnalise .= '
                        <div class="info-box">
                            <h3>⏰ Votre abonnement expire bientôt !</h3>
                            <p>Date d\'expiration :</p>
                            <div class="date-highlight">' . date('d/m/Y', strtotime($date_fin)) . '</div>
                            <p>Il vous reste <strong>' . $nombre_restant . ' téléchargements</strong>.</p>
                            <p>Renouvelez dès maintenant pour continuer à profiter de tous nos avantages.</p>
                        </div>';
            } elseif ($abonnement_status === 'Expiré') {
                $messagePersonnalise .= '
                        <div class="info-box">
                            <h3>⚠️ Votre abonnement a expiré</h3>
                            <p>Date d\'expiration :</p>
                            <div class="date-highlight">' . date('d/m/Y', strtotime($date_fin)) . '</div>
                            <p>Réactivez votre accès dès maintenant pour retrouver vos ' . $nombre_restant . ' téléchargements restants.</p>
                        </div>';
            } elseif ($nombre_restant <= 0) {
                $messagePersonnalise .= '
                        <div class="info-box">
                            <h3>📊 Limite de téléchargements atteinte</h3>
                            <p>Vous avez utilisé tous vos téléchargements.</p>
                            <p>Passez à un plan supérieur pour continuer à profiter de nos ressources.</p>
                        </div>';
            }
            
            $messagePersonnalise .= '
                        <div class="btn-container">
                            <a href="https://www.ndigitmarket.com/abonnement" class="btn btn-primary">
                                <span style="margin-right: 8px;">🔗</span> Gérer mon abonnement
                            </a>
                        </div>
                        <div class="btn-container">
                            <a href="https://www.ndigitmarket.com/shop" class="btn">
                                <span style="margin-right: 8px;">🛒</span> Découvrir la boutique
                            </a>
                        </div>
                    </div>
                    
                    <div class="footer">
                        <p>NDIGITMARKET - Des templates professionnels pour vos projets</p>
                        <p>📧 contact@ndigitmarket.com | 🌐 www.ndigitmarket.com</p>
                        <p style="margin-top: 15px; font-size: 12px;">
                            Cet email a été envoyé depuis votre espace client NDIGITMARKET.<br>
                            &copy; ' . date('Y') . ' NDIGITMARKET. Tous droits réservés.
                        </p>
                    </div>
                </div>
            </body>
            </html>';
            
            $mail->Body = $messagePersonnalise;
            $mail->AltBody = strip_tags(str_replace(['<br>', '</div>', '</p>'], ["\n", "\n", "\n"], $messagePersonnalise));

            if (!$mail->send()) {
                throw new Exception($mail->ErrorInfo);
            }

            // Marquer l'email comme envoyé
            $_SESSION['emails_envoyes'][$email] = true;

            // Retourner une réponse JSON pour AJAX
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode(['success' => true, 'message' => 'Email envoyé avec succès']);
                exit;
            } else {
                // Redirection avec message de succès
                echo '
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    Swal.fire({
                        icon: "success",
                        title: "Succès !",
                        text: "Mail envoyé à ' . htmlspecialchars($email) . ' avec succès",
                        confirmButtonColor: "#087d67"
                    }).then(() => {
                        window.location.href = "abonnements_list.php";
                    });
                </script>';
                exit;
            }
            
        } catch (Exception $e) {
            $errorMessage = "Erreur lors de l'envoi : " . $e->getMessage();
            
            // Retourner une réponse JSON pour AJAX
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode(['success' => false, 'message' => $errorMessage]);
                exit;
            } else {
                echo '
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    Swal.fire({
                        icon: "error",
                        title: "Erreur !",
                        text: ' . json_encode($errorMessage) . ',
                        confirmButtonColor: "#ef4444"
                    });
                </script>';
                exit;
            }
        }
    }
}

// Si on arrive ici sans POST, continuer l'affichage normal
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liste des abonnements</title>
    <?php require('header.php'); ?>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --primary: #087d67;
            --primary-dark: #065a4a;
            --primary-light: #e8f5f2;
            --accent: #f97316;
            --dark: #0f1923;
            --text: #374151;
            --text-light: #6b7280;
            --border: #e5e7eb;
            --bg: #f9fafb;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            margin: 0;
        }
        
        .page-body {
            padding: 40px 20px;
        }
        
        .container-fluid {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            padding: 30px;
            border: 1px solid var(--border);
        }
        
        .title-header {
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .title-header h5 {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
        }
        
        .title-header .btn {
            padding: 10px 20px;
            border-radius: 30px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .btn-success {
            background: var(--success);
            color: white;
            border: none;
        }
        
        .btn-success:hover {
            background: #0e9f6e;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16,185,129,0.3);
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .theme-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        
        .theme-table th {
            background: linear-gradient(135deg, var(--bg) 0%, #ffffff 100%);
            padding: 15px;
            font-weight: 600;
            color: var(--dark);
            border-bottom: 2px solid var(--border);
        }
        
        .theme-table td {
            padding: 15px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }
        
        .theme-table tbody tr:hover {
            background: var(--bg);
        }
        
        .status-actif {
            background: #d1fae5;
            color: var(--success);
            padding: 5px 12px;
            border-radius: 30px;
            font-weight: 500;
            display: inline-block;
        }
        
        .status-presque-fini {
            background: #fef3c7;
            color: var(--warning);
            padding: 5px 12px;
            border-radius: 30px;
            font-weight: 500;
            display: inline-block;
        }
        
        .status-expire {
            background: #fee2e2;
            color: var(--danger);
            padding: 5px 12px;
            border-radius: 30px;
            font-weight: 500;
            display: inline-block;
        }
        
        .btn-mail {
            padding: 8px 16px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #fff;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s;
            white-space: nowrap;
        }
        
        .btn-mail:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(8,125,103,0.3);
        }
        
        .btn-mail-sent {
            padding: 8px 16px;
            background: #6c757d;
            color: #fff;
            border: none;
            border-radius: 30px;
            font-size: 13px;
            cursor: not-allowed;
            opacity: 0.7;
            white-space: nowrap;
        }
        
        .search-bar {
            margin-bottom: 25px;
        }
        
        .search-bar form {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
        
        .search-bar input {
            padding: 12px 16px;
            width: 300px;
            border: 1px solid var(--border);
            border-radius: 30px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .search-bar input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(8,125,103,0.1);
        }
        
        .search-bar button {
            padding: 12px 24px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #fff;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .search-bar button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(8,125,103,0.3);
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 30px;
        }
        
        .pagination a {
            padding: 8px 16px;
            background: #fff;
            border: 1px solid var(--border);
            text-decoration: none;
            color: var(--text);
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .pagination a:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary);
        }
        
        .pagination a.active {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }
        
        .message-error {
            margin: 20px 0;
            padding: 15px;
            background: #fee2e2;
            color: var(--danger);
            border-radius: 8px;
            text-align: center;
        }
        
        .badge-info {
            background: var(--primary-light);
            color: var(--primary);
            padding: 5px 12px;
            border-radius: 30px;
            font-weight: 500;
            font-size: 13px;
        }
        
        @media (max-width: 768px) {
            .search-bar form {
                flex-direction: column;
            }
            
            .search-bar input {
                width: 100%;
            }
            
            .title-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

<div class="page-body">
    <div class="container-fluid">
        <div class="card">
            <div class="title-header">
                <h5>Liste des abonnements</h5>
                <a href="envoie_mail.php" class="btn btn-success">
                    <i class="fa-regular fa-envelope"></i> Envoyer un email à tous
                </a>
            </div>
            
            <?php if (isset($messageError)): ?>
                <div class="message-error"><?php echo htmlspecialchars($messageError); ?></div>
            <?php endif; ?>
            
            <div>
                <?php
                // Nombre d'abonnements par page
                $abonnementsParPage = 10;
                $pageActuelle = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $offset = ($pageActuelle - 1) * $abonnementsParPage;

                // Recherche
                $searchQuery = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "%";
                $query = "
                    SELECT a.*, u.nom, u.prenom, u.email
                    FROM abonnement a
                    JOIN utilisateur u ON a.id_uti2 = u.id_uti
                    WHERE u.nom LIKE :search OR u.prenom LIKE :search OR u.email LIKE :search
                    ORDER BY a.date_fin DESC
                    LIMIT :limit OFFSET :offset
                ";
                $stmt = $database->prepare($query);
                $stmt->bindValue(':search', $searchQuery, PDO::PARAM_STR);
                $stmt->bindValue(':limit', $abonnementsParPage, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
                $stmt->execute();
                $abonnements = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Compter le total
                $queryCount = "SELECT COUNT(*) FROM abonnement a JOIN utilisateur u ON a.id_uti2 = u.id_uti";
                $stmtCount = $database->prepare($queryCount);
                $stmtCount->execute();
                $totalAbonnements = $stmtCount->fetchColumn();
                $totalPages = ceil($totalAbonnements / $abonnementsParPage);
                ?>
                
                <div class="search-bar">
                    <form method="GET" action="">
                        <input type="text" name="search" placeholder="Rechercher un utilisateur..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button type="submit"><i class="fa-solid fa-magnifying-glass"></i> Rechercher</button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table theme-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Plan</th>
                                <th>Statut</th>
                                <th>Téléchargements</th>
                                <th>Date début</th>
                                <th>Date fin</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($abonnements as $abonnement):
                                $date_fin = new DateTime($abonnement['date_fin']);
                                $date_aujourdhui = new DateTime();
                                $interval = $date_aujourdhui->diff($date_fin);
                                $jours_restants = $interval->days * ($interval->invert ? -1 : 1);
                                $nombre_restant = $abonnement['nombre_total'] - $abonnement['nombre_telecharge'];

                                if ($date_aujourdhui > $date_fin) {
                                    $statut = 'Expiré';
                                    $statut_class = 'status-expire';
                                } elseif ($jours_restants <= 7) {
                                    $statut = 'Presque fini';
                                    $statut_class = 'status-presque-fini';
                                } else {
                                    $statut = 'En cours';
                                    $statut_class = 'status-actif';
                                }

                                $email_sent = isset($_SESSION['emails_envoyes'][$abonnement['email']]);
                            ?>
                                <tr>
                                    <td><strong>#<?php echo $abonnement['id_uti2']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($abonnement['nom']); ?></td>
                                    <td><?php echo htmlspecialchars($abonnement['prenom']); ?></td>
                                    <td><?php echo htmlspecialchars($abonnement['email']); ?></td>
                                    <td><?php echo htmlspecialchars($abonnement['choix']); ?></td>
                                    <td><span class="<?php echo $statut_class; ?>"><?php echo $statut; ?></span></td>
                                    <td>
                                        <span style="font-weight: 600; color: var(--primary);"><?php echo $nombre_restant; ?></span> / <?php echo $abonnement['nombre_total']; ?>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($abonnement['date_debut'])); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($abonnement['date_fin'])); ?></td>
                                    <td>
                                        <button class="<?php echo $email_sent ? 'btn-mail-sent' : 'btn-mail'; ?>" 
                                                <?php echo $email_sent ? 'disabled' : ''; ?>
                                                onclick="showEmailForm(
                                                    '<?php echo htmlspecialchars($abonnement['email'], ENT_QUOTES); ?>',
                                                    '<?php echo htmlspecialchars($abonnement['nom'], ENT_QUOTES); ?>',
                                                    '<?php echo htmlspecialchars($abonnement['prenom'], ENT_QUOTES); ?>',
                                                    '<?php echo htmlspecialchars($abonnement['date_fin'], ENT_QUOTES); ?>',
                                                    '<?php echo $statut; ?>',
                                                    <?php echo $nombre_restant; ?>,
                                                    <?php echo $abonnement['nombre_total']; ?>
                                                )">
                                            <i class="fa-regular fa-envelope"></i>
                                            <?php echo $email_sent ? 'Déjà envoyé' : 'Envoyer'; ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($pageActuelle > 1): ?>
                            <a href="?page=<?php echo $pageActuelle - 1; ?>&search=<?php echo isset($_GET['search']) ? urlencode($_GET['search']) : ''; ?>">&laquo;</a>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="?page=<?php echo $i; ?>&search=<?php echo isset($_GET['search']) ? urlencode($_GET['search']) : ''; ?>" class="<?php echo ($i == $pageActuelle) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                        <?php endfor; ?>
                        
                        <?php if ($pageActuelle < $totalPages): ?>
                            <a href="?page=<?php echo $pageActuelle + 1; ?>&search=<?php echo isset($_GET['search']) ? urlencode($_GET['search']) : ''; ?>">&raquo;</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function showEmailForm(email, nom, prenom, date_fin, statut, nombre_restant, nombre_total) {
    let defaultSubject, defaultMessage;

    if (statut === 'En cours') {
        defaultSubject = 'Votre abonnement est actif - NDIGITMARKET';
        defaultMessage = `
            <p>Nous sommes heureux de vous compter parmi nos abonnés premium !</p>
            <p>Votre abonnement est actuellement <strong>en cours de validité</strong> et vous avez <strong>${nombre_restant} téléchargements restants</strong> sur votre quota total de ${nombre_total}.</p>
            <p>Profitez pleinement de votre abonnement pour télécharger tous les templates et ressources dont vous avez besoin pour vos projets.</p>
            <p>N'hésitez pas à visiter régulièrement notre boutique pour découvrir les nouveaux templates ajoutés chaque semaine.</p>
        `;
    } else if (statut === 'Presque fini') {
        defaultSubject = 'Votre abonnement expire bientôt - NDIGITMARKET';
        defaultMessage = `
            <p>Votre abonnement NDIGITMARKET arrive bientôt à expiration (le ${date_fin}).</p>
            <p>Il vous reste encore <strong>${nombre_restant} téléchargements</strong> sur votre quota total de ${nombre_total}.</p>
            <p>Pour éviter toute interruption de service, nous vous recommandons de renouveler dès maintenant.</p>
            <p>Profitez de vos téléchargements restants avant l'expiration !</p>
        `;
    } else if (statut === 'Expiré') {
        defaultSubject = 'Votre abonnement a expiré - NDIGITMARKET';
        defaultMessage = `
            <p>Votre abonnement NDIGITMARKET a expiré le ${date_fin}.</p>
            <p>Il vous restait <strong>${nombre_restant} téléchargements non utilisés</strong>.</p>
            <p>Pour retrouver l'accès à tous nos templates et ressources premium, renouvelez votre abonnement dès aujourd'hui et retrouvez vos téléchargements restants.</p>
        `;
    } else if (nombre_restant <= 0) {
        defaultSubject = 'Limite de téléchargements atteinte - NDIGITMARKET';
        defaultMessage = `
            <p>Vous avez atteint votre limite de téléchargements mensuels.</p>
            <p>Pour continuer à profiter de nos ressources premium sans interruption, nous vous invitons à passer à un plan supérieur.</p>
        `;
    }

    Swal.fire({
        title: `📧 Envoyer un email à ${prenom}`,
        html: `
            <div style="text-align: left; padding: 10px;">
                <div style="background: #e8f5f2; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                    <p style="margin: 0; color: #087d67;">
                        <i class="fa-regular fa-user"></i> ${prenom} ${nom}<br>
                        <i class="fa-regular fa-envelope"></i> ${email}<br>
                        <i class="fa-regular fa-chart-bar"></i> ${nombre_restant}/${nombre_total} téléchargements
                    </p>
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="font-weight: 600; color: #087d67; display: block; margin-bottom: 5px;">Sujet</label>
                    <input type="text" id="sujet" class="swal2-input" value="${defaultSubject}" style="width: 100%; border-radius: 8px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="font-weight: 600; color: #087d67; display: block; margin-bottom: 5px;">Message</label>
                    <textarea id="message" class="swal2-textarea" style="width: 100%; height: 200px; border-radius: 8px;">${defaultMessage}</textarea>
                </div>
                <input type="hidden" id="email" value="${email}">
                <input type="hidden" id="nom" value="${nom}">
                <input type="hidden" id="prenom" value="${prenom}">
                <input type="hidden" id="date_fin" value="${date_fin}">
                <input type="hidden" id="abonnement_status" value="${statut}">
                <input type="hidden" id="nombre_restant" value="${nombre_restant}">
                <input type="hidden" id="nombre_total" value="${nombre_total}">
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Envoyer',
        cancelButtonText: 'Annuler',
        confirmButtonColor: '#087d67',
        cancelButtonColor: '#6b7280',
        width: '650px',
        preConfirm: () => {
            const sujet = document.getElementById('sujet').value;
            const message = document.getElementById('message').value;
            
            if (!sujet || !message) {
                Swal.showValidationMessage('Veuillez remplir tous les champs');
                return false;
            }

            // Créer un formulaire et soumettre
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = window.location.href;

            const fields = {
                send_email: 'true',
                email: document.getElementById('email').value,
                nom: document.getElementById('nom').value,
                prenom: document.getElementById('prenom').value,
                sujet: sujet,
                message: message,
                date_fin: document.getElementById('date_fin').value,
                abonnement_status: document.getElementById('abonnement_status').value,
                nombre_restant: document.getElementById('nombre_restant').value,
                nombre_total: document.getElementById('nombre_total').value
            };

            for (const [key, value] of Object.entries(fields)) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            }

            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

<?php require('footer.php'); ?>
</body>
</html>