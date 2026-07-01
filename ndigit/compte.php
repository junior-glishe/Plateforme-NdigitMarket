<?php
require('header.php');
?>

    <?php
    if (!isset($_SESSION['user_id'])) {
        echo '<meta http-equiv="refresh" content="0;URL=login">';
        exit;
    }

    $email = $_SESSION['email'];
    $verifie = $database->quote($email);
    $resultats1 = $database->query('SELECT * FROM utilisateur WHERE email=' . $verifie . '');
    $donnee1 = $resultats1->fetch();
    $date_aujourdhui = new DateTime();

    // --- TRAITEMENT DE LA DEMANDE DEVENIR VENDEUR ---
    if (isset($_POST['submit_become_seller'])) {
        $nom_boutique = trim($_POST['nom_boutique']);
        $description = trim($_POST['description']);
        $telephone = trim($_POST['telephone']);
        $id_uti = $donnee1['id_uti'];

        // Validation du téléphone
        $tel = preg_replace('/\s+/', '', $telephone);
        $tel = ltrim($tel, '+');
        if (preg_match('/^01[0-9]{8}$/', $tel)) {
            $tel = '229' . $tel;
        }
        if (!preg_match('/^22901[0-9]{8}$/', $tel)) {
            $message = "Numéro MTN invalide. Format attendu : 01XXXXXXXX ou 22901XXXXXXXX.";
            $icon = "warning";
        } elseif (!empty($nom_boutique) && !empty($description)) {
            $check = $database->prepare("SELECT id FROM demandes_vendeur WHERE id_uti = ? AND statut = 'en_attente'");
            $check->execute([$id_uti]);
            if ($check->fetch()) {
                $message = "Vous avez déjà une demande en cours d'examen.";
                $icon = "warning";
            } else {
                $insert = $database->prepare("INSERT INTO demandes_vendeur (id_uti, nom_boutique, description, categorie, telephone, adresse, statut, date_demande) VALUES (?, ?, ?, 'autre', ?, '', 'en_attente', NOW())");
                if ($insert->execute([$id_uti, $nom_boutique, $description, $tel])) {
                    $message = "Votre demande a bien été envoyée. Notre équipe va l'examiner dans les plus brefs délais.";
                    $icon = "success";
                } else {
                    $message = "Une erreur est survenue lors de l'envoi de votre demande.";
                    $icon = "error";
                }
            }
        } else {
            $message = "Tous les champs sont obligatoires.";
            $icon = "warning";
        }
        echo '
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: "'.$icon.'",
                title: "'.($icon == "success" ? "Succès !" : ($icon == "warning" ? "Attention" : "Erreur")).'",
                text: "'.addslashes($message).'",
                confirmButtonColor: "'.($icon == "success" ? "#087d67" : ($icon == "warning" ? "#f59e0b" : "#ef4444")).'"
            });
        </script>';
    }

    // Vérification du statut vendeur
    $is_seller = false;
    $seller_request = null;
    $check_seller = $database->prepare("SELECT * FROM demandes_vendeur WHERE id_uti = ? AND statut = 'acceptee'");
    $check_seller->execute([$donnee1['id_uti']]);
    if ($seller_request = $check_seller->fetch(PDO::FETCH_ASSOC)) {
        $is_seller = true;
        // Récupérer les infos du portefeuille
        $wallet = $database->prepare("SELECT * FROM portefeuille_vendeur WHERE id_uti = ?");
        $wallet->execute([$donnee1['id_uti']]);
        $wallet_data = $wallet->fetch(PDO::FETCH_ASSOC);
        if (!$wallet_data) {
            $database->prepare("INSERT INTO portefeuille_vendeur (id_uti, solde, total_gagne) VALUES (?, 0.00, 0.00)")->execute([$donnee1['id_uti']]);
            $wallet_data = ['solde' => 0.00, 'total_gagne' => 0.00];
        }

        // Récupérer le numéro de téléphone du vendeur depuis sa demande acceptée
        $seller_phone_stmt = $database->prepare("SELECT telephone FROM demandes_vendeur WHERE id_uti = ? AND statut = 'acceptee' ORDER BY date_demande DESC LIMIT 1");
        $seller_phone_stmt->execute([$donnee1['id_uti']]);
        $seller_phone_data = $seller_phone_stmt->fetch(PDO::FETCH_ASSOC);
        $seller_phone = $seller_phone_data ? $seller_phone_data['telephone'] : '';

        // Stats dashboard vendeur
        $pCount = $database->prepare("SELECT COUNT(*) FROM produits WHERE id_vendeur = ?");
        $pCount->execute([$donnee1['id_uti']]);
        $total_products = $pCount->fetchColumn();

        $sales = $database->prepare("SELECT SUM(c.prix) FROM commande c JOIN produits p ON c.id_article = p.id WHERE p.id_vendeur = ?");
        $sales->execute([$donnee1['id_uti']]);
        $total_sales = $sales->fetchColumn() ?? 0;

        $ordersCount = $database->prepare("SELECT COUNT(*) FROM commande c JOIN produits p ON c.id_article = p.id WHERE p.id_vendeur = ?");
        $ordersCount->execute([$donnee1['id_uti']]);
        $total_orders = $ordersCount->fetchColumn();

        $pendingWd = $database->prepare("SELECT COUNT(*) FROM retraits WHERE id_uti = ? AND statut = 'en_attente'");
        $pendingWd->execute([$donnee1['id_uti']]);
        $pending_withdrawals = $pendingWd->fetchColumn();
    }

    // Traitement de la modification du numéro de téléphone pour retrait
    if (isset($_POST['update_phone_number'])) {
        $new_phone = trim($_POST['new_phone_number']);
        $id_uti = $donnee1['id_uti'];
        
        $tel = preg_replace('/\s+/', '', $new_phone);
        $tel = ltrim($tel, '+');
        if (preg_match('/^01[0-9]{8}$/', $tel)) {
            $tel = '229' . $tel;
        }
        if (!preg_match('/^22901[0-9]{8}$/', $tel)) {
            echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    icon: "warning",
                    title: "Attention",
                    text: "Numéro MTN invalide. Format attendu : 01XXXXXXXX ou 22901XXXXXXXX.",
                    confirmButtonColor: "#f59e0b"
                });
            </script>';
        } else {
            $update_phone = $database->prepare("UPDATE demandes_vendeur SET telephone = ? WHERE id_uti = ? AND statut = 'acceptee'");
            if ($update_phone->execute([$tel, $id_uti])) {
                $seller_phone = $tel;
                echo '
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    Swal.fire({
                        icon: "success",
                        title: "Succès !",
                        text: "Votre numéro de téléphone a été mis à jour.",
                        confirmButtonColor: "#087d67",
                        timer: 2000
                    });
                </script>';
            }
        }
    }
    ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Mon Compte - NDIGITMARKET</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #087d67;
            --primary-dark: #065a4a;
            --primary-light: #e8f5f2;
            --accent: #f97316;
            --accent-dark: #e67e22;
            --accent-light: #fff7ed;
            --dark: #0f1923;
            --dark-2: #1a2634;
            --text: #374151;
            --text-light: #6b7280;
            --border: #e5e7eb;
            --bg: #f9fafb;
            --white: #ffffff;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --shadow: 0 10px 30px -10px rgba(0,0,0,0.1);
            --shadow-hover: 0 20px 40px -15px rgba(8,125,103,0.3);
            --radius: 20px;
            --radius-sm: 12px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
        }

        /* ===== BREADCRUMB MODERNE ===== */
        .breadcrumb-modern {
            background: linear-gradient(135deg, var(--dark) 0%, var(--dark-2) 100%);
            padding: 40px 0;
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
        }

        .breadcrumb-modern::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(8,125,103,0.2) 0%, transparent 70%);
            border-radius: 50%;
        }

        .breadcrumb-modern::after {
            content: '';
            position: absolute;
            bottom: -50px;
            left: -50px;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(249,115,22,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .breadcrumb-content {
            position: relative;
            z-index: 2;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .breadcrumb-content h1 {
            color: white;
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .breadcrumb-links {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255,255,255,0.6);
            flex-wrap: wrap;
        }

        .breadcrumb-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: color 0.3s;
            font-size: 14px;
        }

        .breadcrumb-links a:hover {
            color: white;
        }

        .breadcrumb-links i {
            font-size: 12px;
        }

        /* ===== DASHBOARD LAYOUT ===== */
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px 60px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 30px;
        }

        @media (max-width: 992px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ===== SIDEBAR GAUCHE ===== */
        .dashboard-sidebar {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            overflow: hidden;
            position: sticky;
            top: 100px;
            height: fit-content;
        }

        .profile-card {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 30px 20px;
            text-align: center;
            color: white;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 40px;
            color: white;
            border: 3px solid white;
        }

        .profile-name {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 5px;
            word-break: break-word;
        }

        .profile-email {
            font-size: 14px;
            opacity: 0.9;
            word-break: break-word;
        }

        .sidebar-menu {
            padding: 15px 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: var(--text);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
            cursor: pointer;
            width: 100%;
            border: none;
            background: transparent;
            font-size: 15px;
            font-weight: 500;
            text-align: left;
        }

        .menu-item i {
            width: 20px;
            color: var(--primary);
            font-size: 18px;
        }

        .menu-item:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .menu-item.active {
            background: var(--primary-light);
            color: var(--primary);
            border-left-color: var(--primary);
            font-weight: 600;
        }

        .menu-item.active i {
            color: var(--primary);
        }

        /* ===== CONTENU PRINCIPAL ===== */
        .dashboard-content {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            padding: 30px;
            overflow-x: auto;
        }

        .content-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .content-header h2 {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark);
        }

        .mobile-menu-btn {
            display: none;
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 14px;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            width: 100%;
            margin-bottom: 20px;
        }

        @media (max-width: 992px) {
            .mobile-menu-btn {
                display: flex;
            }
            .dashboard-sidebar {
                display: none;
            }
            .dashboard-sidebar.active {
                display: block;
                position: fixed;
                top: 0;
                left: 0;
                width: 85%;
                max-width: 350px;
                height: 100vh;
                z-index: 1000;
                border-radius: 0;
                overflow-y: auto;
            }
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 999;
                display: none;
            }
            .sidebar-overlay.active {
                display: block;
            }
            .close-sidebar {
                position: absolute;
                top: 15px;
                right: 15px;
                width: 40px;
                height: 40px;
                background: rgba(255,255,255,0.2);
                border: none;
                border-radius: 50%;
                color: white;
                font-size: 18px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
            }
        }

        /* ===== TABLEAU DE BORD ===== */
        .welcome-box {
            background: linear-gradient(135deg, var(--primary-light) 0%, white 100%);
            border-radius: var(--radius-sm);
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid var(--border);
        }

        .welcome-box h3 {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .welcome-box p {
            color: var(--text-light);
            line-height: 1.6;
        }

        /* ===== ABONNEMENT ===== */
        .subscription-card {
            background: linear-gradient(135deg, var(--accent-light) 0%, white 100%);
            border-radius: var(--radius-sm);
            padding: 25px;
            border: 1px solid var(--accent);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }

        .sub-info h4 {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .sub-badge {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
        }

        .sub-badge.simple {
            background: var(--primary-light);
            color: var(--primary);
        }

        .sub-badge.premium {
            background: var(--accent-light);
            color: var(--accent);
        }

        .sub-badge.expired {
            background: #fee2e2;
            color: var(--danger);
        }

        .sub-stats {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 800;
            color: var(--primary);
            line-height: 1.2;
        }

        .stat-label {
            font-size: 13px;
            color: var(--text-light);
        }

        .btn-upgrade {
            background: var(--accent);
            color: white;
            padding: 12px 25px;
            border-radius: 40px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-upgrade:hover {
            background: var(--accent-dark);
            transform: translateY(-2px);
            color: white;
        }

        /* ===== SECTION VENDEUR DASHBOARD ===== */
        .seller-quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: var(--radius-sm);
            padding: 20px;
            border: 1px solid var(--border);
            text-align: center;
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
            border-color: var(--primary);
        }

        .stat-card i {
            font-size: 32px;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .stat-card .stat-card-value {
            font-size: 24px;
            font-weight: 800;
            color: var(--dark);
            word-break: break-word;
        }

        .stat-card .stat-card-label {
            font-size: 13px;
            color: var(--text-light);
            margin-top: 5px;
        }

        .seller-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 30px;
        }

        .seller-actions .btn-action {
            padding: 10px 20px;
            border-radius: 40px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            border: 1px solid var(--border);
            background: white;
            color: var(--text);
        }

        .seller-actions .btn-action:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary);
        }

        /* ===== PROFIL ===== */
        .profile-detail {
            background: var(--bg);
            border-radius: var(--radius-sm);
            padding: 25px;
            overflow-x: auto;
        }

        .profile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .profile-header h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark);
        }

        .btn-edit {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s;
            border: none;
            background: transparent;
            cursor: pointer;
        }

        .btn-edit:hover {
            color: var(--primary-dark);
            gap: 8px;
        }

        .profile-name-display {
            font-size: 22px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 15px;
            word-break: break-word;
        }

        .profile-info {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 0;
            border-bottom: 1px solid var(--border);
            flex-wrap: wrap;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-item i {
            width: 20px;
            color: var(--primary);
            font-size: 18px;
        }

        .info-item span {
            color: var(--text);
            word-break: break-word;
        }

        .info-item strong {
            color: var(--dark);
            margin-right: 5px;
        }

        /* ===== TÉLÉCHARGEMENTS ===== */
        .download-table {
            background: white;
            border-radius: var(--radius-sm);
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            min-width: 500px;
        }

        .table thead th {
            background: var(--bg);
            padding: 15px;
            font-weight: 600;
            color: var(--dark);
            border-bottom: 2px solid var(--border);
        }

        .table tbody td {
            padding: 15px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: var(--bg);
        }

        .product-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
        }

        .btn-download {
            background: var(--primary-light);
            color: var(--primary);
            padding: 8px 15px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-download:hover {
            background: var(--primary);
            color: white;
        }

        .btn-download i {
            font-size: 14px;
        }

        .btn-cancel {
            background: #fee2e2;
            color: var(--danger);
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-cancel:hover {
            background: var(--danger);
            color: white;
        }

        /* ===== MODAL ===== */
        .modal-modern .modal-content {
            border-radius: 20px;
            border: none;
            padding: 20px;
        }

        .modal-modern .modal-header {
            border-bottom: 1px solid var(--border);
            padding: 0 0 20px 0;
        }

        .modal-modern .modal-title {
            font-weight: 700;
            color: var(--dark);
        }

        .modal-modern .modal-body {
            padding: 20px 0;
        }

        .modal-modern .btn-close {
            background: var(--bg);
            opacity: 1;
            padding: 8px;
            border-radius: 50%;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            font-size: 14px;
            font-weight: 500;
            color: var(--dark);
            margin-bottom: 5px;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(8,125,103,0.1);
        }

        .form-control[readonly] {
            background: var(--bg);
            cursor: not-allowed;
        }

        .btn-modal {
            padding: 12px 25px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-modal-primary {
            background: var(--primary);
            color: white;
        }

        .btn-modal-primary:hover {
            background: var(--primary-dark);
        }

        .btn-modal-secondary {
            background: var(--bg);
            color: var(--text);
            border: 1px solid var(--border);
        }

        .btn-modal-secondary:hover {
            background: var(--border);
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .tab-pane {
            animation: fadeIn 0.5s ease forwards;
        }

        /* Badge vendeur */
        .badge-vendeur {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            background: #fbbf24;
            color: #000;
            font-weight: 600;
            font-size: 12px;
            margin-top: 5px;
        }

        /* Motif refus */
        .motif-refus {
            font-size: 12px;
            color: #991b1b;
            margin-top: 4px;
            font-style: italic;
        }
        
        /* Responsive fixes */
        @media (max-width: 768px) {
            .dashboard-content {
                padding: 20px;
            }
            
            .content-header h2 {
                font-size: 20px;
            }
            
            .stat-card .stat-card-value {
                font-size: 18px;
            }
            
            .stat-card i {
                font-size: 24px;
            }
            
            .seller-quick-stats {
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
                gap: 12px;
            }
            
            .stat-card {
                padding: 15px;
            }
            
            .subscription-card {
                flex-direction: column;
                text-align: center;
            }
            
            .sub-stats {
                justify-content: center;
            }
            
            .seller-actions {
                justify-content: center;
            }
            
            .btn-action, .btn-upgrade {
                font-size: 13px;
                padding: 8px 16px;
            }
        }
        
        @media (max-width: 480px) {
            .dashboard-container {
                padding: 0 15px 40px;
            }
            
            .breadcrumb-content h1 {
                font-size: 28px;
            }
            
            .welcome-box h3 {
                font-size: 18px;
            }
            
            .welcome-box p {
                font-size: 14px;
            }
            
            .profile-name-display {
                font-size: 18px;
            }
            
            .info-item {
                gap: 8px;
            }
            
            .info-item i {
                width: 16px;
                font-size: 14px;
            }
            
            .info-item span {
                font-size: 14px;
            }
        }
        
        /* Amélioration du tableau responsive */
        @media (max-width: 768px) {
            .table thead th,
            .table tbody td {
                padding: 10px 8px;
            }
            
            .product-image {
                width: 40px;
                height: 40px;
            }
            
            .btn-download {
                padding: 6px 10px;
                font-size: 11px;
            }
        }
    </style>
</head>
<body>

    <!-- Breadcrumb moderne -->
    <section class="breadcrumb-modern">
        <div class="breadcrumb-content">
            <h1>Mon Compte</h1>
            <div class="breadcrumb-links">
                <a href="index.php"><i class="fa-solid fa-house"></i> Accueil</a>
                <i class="fa-solid fa-chevron-right"></i>
                <span>Tableau de bord</span>
            </div>
        </div>
    </section>

    <!-- Overlay mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="dashboard-container">
        <button class="mobile-menu-btn" id="mobileMenuBtn">
            <i class="fa-solid fa-bars"></i> Menu du compte
        </button>

        <div class="dashboard-grid">
            <!-- Sidebar gauche -->
            <div class="dashboard-sidebar" id="dashboardSidebar">
                <button class="close-sidebar d-lg-none" id="closeSidebar">
                    <i class="fa-solid fa-times"></i>
                </button>
                
                <div class="profile-card">
                    <div class="profile-avatar">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <div class="profile-name"><?php echo htmlspecialchars($donnee1['nom'] . ' ' . $donnee1['prenom']); ?></div>
                    <div class="profile-email"><?php echo htmlspecialchars($donnee1['email']); ?></div>
                    <?php if ($is_seller): ?>
                        <span class="badge-vendeur">🏪 Vendeur</span>
                    <?php endif; ?>
                </div>

                <div class="sidebar-menu" id="sidebarMenu">
                    <button class="menu-item active" onclick="showTab('dashboard')">
                        <i class="fa-regular fa-chart-bar"></i> Tableau de bord
                    </button>
                    <button class="menu-item" onclick="showTab('profile')">
                        <i class="fa-regular fa-user"></i> Mon profil
                    </button>
                    <button class="menu-item" onclick="showTab('downloads')">
                        <i class="fa-regular fa-circle-down"></i> Mes téléchargements
                    </button>
                    
                    <?php if ($is_seller): ?>
                        <a href="liste_produits.php" class="menu-item">
                            <i class="fa-solid fa-box"></i> Mes produits
                        </a>
                        <button class="menu-item" onclick="showTab('seller-wallet')">
                            <i class="fa-solid fa-wallet"></i> Mon portefeuille
                        </button>
                        <button class="menu-item" onclick="showTab('seller-withdrawals')">
                            <i class="fa-solid fa-money-bill-transfer"></i> Demandes de retrait
                        </button>
                    <?php else: ?>
                        <?php
                        $pending = $database->prepare("SELECT id FROM demandes_vendeur WHERE id_uti = ? AND statut = 'en_attente'");
                        $pending->execute([$donnee1['id_uti']]);
                        if ($pending->fetch()): ?>
                            <button class="menu-item" disabled style="opacity:0.7; cursor:not-allowed;">
                                <i class="fa-solid fa-clock"></i> Demande en attente
                            </button>
                        <?php else: ?>
                            <button class="menu-item" onclick="openBecomeSellerModal()">
                                <i class="fa-solid fa-store"></i> Devenir vendeur
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Contenu principal -->
            <div class="dashboard-content">
                <!-- Tableau de bord -->
                <div class="tab-pane active" id="tab-dashboard">
                    <div class="content-header">
                        <h2>Tableau de bord</h2>
                    </div>

                    <div class="welcome-box">
                        <h3>Bonjour, <?php echo htmlspecialchars($donnee1['prenom']); ?> !</h3>
                        <p>Bienvenue dans votre espace personnel. Gérez votre profil, consultez vos téléchargements et suivez votre activité.</p>
                    </div>

                    <!-- SECTION VENDEUR -->
                    <?php if ($is_seller): ?>
                    <h4 style="margin-bottom: 20px; color: var(--dark); font-weight: 600;">
                        <i class="fa-solid fa-store me-2" style="color: var(--primary);"></i>Espace vendeur
                    </h4>
                    <!-- Stats rapides -->
                    <div class="seller-quick-stats">
                        <div class="stat-card">
                            <i class="fa-solid fa-box"></i>
                            <div class="stat-card-value"><?php echo $total_products; ?></div>
                            <div class="stat-card-label">Produits en ligne</div>
                        </div>
                        <div class="stat-card">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <div class="stat-card-value"><?php echo number_format($total_sales, 0, ',', ' '); ?> CFA</div>
                            <div class="stat-card-label">Ventes totales</div>
                        </div>
                        <div class="stat-card">
                            <i class="fa-solid fa-receipt"></i>
                            <div class="stat-card-value"><?php echo $total_orders; ?></div>
                            <div class="stat-card-label">Commandes</div>
                        </div>
                        <div class="stat-card">
                            <i class="fa-solid fa-wallet"></i>
                            <div class="stat-card-value"><?php echo number_format($wallet_data['solde'], 0, ',', ' '); ?> CFA</div>
                            <div class="stat-card-label">Solde actuel</div>
                        </div>
                    </div>

                    <!-- Actions rapides -->
                    <div class="seller-actions">
                        <a href="ajouter_produit.php" class="btn-action">
                            <i class="fa-solid fa-plus"></i> Ajouter un produit
                        </a>
                        <a href="liste_produits.php" class="btn-action">
                            <i class="fa-solid fa-list-check"></i> Voir mes produits
                        </a>
                        <a href="javascript:showTab('seller-wallet')" class="btn-action">
                            <i class="fa-solid fa-coins"></i> Mon portefeuille
                        </a>
                        <a href="javascript:openWithdrawalModal()" class="btn-action">
                            <i class="fa-solid fa-paper-plane"></i> Demander un retrait
                        </a>
                    </div>
                    <?php endif; ?>

                    <!-- Abonnement -->
                    <?php if ($donnee1['type'] == "-"): ?>
                        <div class="subscription-card">
                            <div class="sub-info">
                                <h4>Compte Simple</h4>
                                <span class="sub-badge simple">Gratuit</span>
                                <p style="margin-top: 10px; color: var(--text-light);">Passez à un compte premium pour débloquer des téléchargements illimités !</p>
                            </div>
                            <a href="abonnement" class="btn-upgrade">
                                <i class="fa-solid fa-crown"></i> Passer à Premium
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if ($donnee1['type'] == "pro"): 
                        $stmt = $database->prepare("SELECT * FROM abonnement WHERE id_uti2 = ?");
                        $stmt->execute([$donnee1['id_uti']]);
                        $abonnement = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($abonnement):
                            $date_fin = new DateTime($abonnement['date_fin']);
                            $telechargements_restants = $abonnement['nombre_total'] - $abonnement['nombre_telecharge'];
                    ?>
                        <div class="subscription-card">
                            <div class="sub-info">
                                <h4>Compte Premium</h4>
                                <?php if ($date_aujourdhui < $date_fin): ?>
                                    <span class="sub-badge premium">Actif jusqu'au <?php echo $date_fin->format('d/m/Y'); ?></span>
                                <?php else: ?>
                                    <span class="sub-badge expired">Expiré</span>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($date_aujourdhui < $date_fin): ?>
                                <div class="sub-stats">
                                    <div class="stat-item">
                                        <div class="stat-value"><?php echo $abonnement['nombre_total']; ?></div>
                                        <div class="stat-label">Total</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value"><?php echo $abonnement['nombre_telecharge']; ?></div>
                                        <div class="stat-label">Utilisés</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value"><?php echo $telechargements_restants; ?></div>
                                        <div class="stat-label">Restants</div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <a href="abonnement" class="btn-upgrade">
                                    <i class="fa-solid fa-crown"></i> Renouveler
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php 
                        endif;
                    endif; 
                    ?>
                </div>

                <!-- Profil -->
                <div class="tab-pane" id="tab-profile" style="display: none;">
                    <div class="content-header">
                        <h2>Mon profil</h2>
                        <button class="btn-edit" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="fa-regular fa-pen-to-square"></i> Modifier
                        </button>
                    </div>
                    <div class="profile-detail">
                        <div class="profile-name-display">
                            <?php echo htmlspecialchars($donnee1['nom'] . ' ' . $donnee1['prenom']); ?>
                        </div>
                        <div class="profile-info">
                            <div class="info-item">
                                <i class="fa-regular fa-envelope"></i>
                                <span><strong>Email :</strong> <?php echo htmlspecialchars($donnee1['email']); ?></span>
                            </div>
                            <div class="info-item">
                                <i class="fa-regular fa-id-card"></i>
                                <span><strong>Type de compte :</strong> 
                                    <?php if ($donnee1['type'] == "-"): ?>
                                        <span class="sub-badge simple">Simple</span>
                                    <?php else: ?>
                                        <span class="sub-badge premium">Premium</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Téléchargements -->
                <div class="tab-pane" id="tab-downloads" style="display: none;">
                    <div class="content-header">
                        <h2>Mes téléchargements</h2>
                    </div>
                    <?php
                    $user_email = $_SESSION['email'];
                    $query = "
                        SELECT c.commande_id, c.id_article, c.image, c.prix, c.fichier, p.nom_article, p.image AS produit_image
                        FROM commande c
                        JOIN utilisateur u ON c.id_client = u.id_uti
                        JOIN produits p ON c.id_article = p.id
                        WHERE u.email = :email
                    ";
                    $stmt = $database->prepare($query);
                    $stmt->execute([':email' => $user_email]);
                    $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <div class="download-table">
                        <?php if (count($commandes) > 0): ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Image</th>
                                        <th>Nom du produit</th>
                                        <th>Téléchargement</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach ($commandes as $commande): ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td>
                                                <img src="back-end/apps/<?php echo htmlspecialchars($commande['image']); ?>" class="product-image" alt="">
                                            </td>
                                            <td><?php echo htmlspecialchars($commande['nom_article']); ?></td>
                                            <td>
                                                <a href="back-end/apps/<?php echo htmlspecialchars($commande['fichier']); ?>" class="btn-download" download>
                                                    <i class="fa-regular fa-circle-down"></i> Télécharger
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div style="text-align: center; padding: 60px 20px;">
                                <i class="fa-regular fa-circle-down" style="font-size: 60px; color: var(--border); margin-bottom: 20px;"></i>
                                <h3 style="color: var(--text-light); margin-bottom: 10px;">Aucun téléchargement</h3>
                                <p style="color: var(--text-light);">Vous n'avez pas encore téléchargé de produits.</p>
                                <a href="shop.php" class="btn-upgrade" style="margin-top: 20px; display: inline-flex;">
                                    <i class="fa-regular fa-store"></i> Découvrir la boutique
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- ===== TABS VENDEUR ===== -->
                <?php if ($is_seller): ?>
                <!-- Portefeuille -->
                <div class="tab-pane" id="tab-seller-wallet" style="display: none;">
                    <div class="content-header">
                        <h2>Mon portefeuille</h2>
                    </div>
                    <div class="profile-detail">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <i class="fa-solid fa-coins"></i>
                                    <span><strong>Solde actuel :</strong> <?php echo number_format($wallet_data['solde'], 2, ',', ' '); ?> CFA</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <i class="fa-solid fa-chart-line"></i>
                                    <span><strong>Total gagné :</strong> <?php echo number_format($wallet_data['total_gagne'], 2, ',', ' '); ?> CFA</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Demandes de retrait (vendeur) -->
                <div class="tab-pane" id="tab-seller-withdrawals" style="display: none;">
                    <div class="content-header">
                        <h2>Mes retraits</h2>
                        <?php 
                        $checkPending = $database->prepare("SELECT id FROM retraits WHERE id_uti = ? AND statut = 'en_attente'");
                        $checkPending->execute([$donnee1['id_uti']]);
                        $hasPending = $checkPending->fetch();
                        
                        if (!$hasPending): 
                        ?>
                            <button class="btn-upgrade" style="background: var(--accent);" onclick="openWithdrawalModal()">
                                <i class="fa-solid fa-paper-plane"></i> Demander un retrait
                            </button>
                        <?php else: ?>
                            <span style="color: var(--warning); font-weight: 600;">
                                <i class="fa-solid fa-clock"></i> Une demande de retrait est déjà en cours
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if ($hasPending): ?>
                        <div style="background: #fef3c7; border: 1px solid #f59e0b; border-radius: 12px; padding: 15px; margin-bottom: 20px; color: #92400e; display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                            <i class="fa-solid fa-circle-info" style="font-size: 20px; color: #f59e0b;"></i>
                            <span>Vous avez une demande de retrait en attente de validation. Vous ne pouvez pas faire une nouvelle demande tant que celle-ci n'est pas finalisée.</span>
                        </div>
                    <?php endif; ?>

                    <?php
                    $retraits = $database->prepare("SELECT * FROM retraits WHERE id_uti = ? ORDER BY date_demande DESC");
                    $retraits->execute([$donnee1['id_uti']]);
                    $retraits_list = $retraits->fetchAll(PDO::FETCH_ASSOC);
                    ?>

                    <div class="download-table">
                        <?php if (count($retraits_list) > 0): ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Numéro</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($retraits_list as $r): ?>
                                        <tr>
                                            <td><?php echo date('d/m/Y H:i', strtotime($r['date_demande'])); ?></td>
                                            <td><?php echo htmlspecialchars($r['telephone'] ?? 'N/A'); ?></td>
                                            <td><?php echo number_format($r['montant'], 2, ',', ' '); ?> CFA</td>
                                            <td>
                                                <?php
                                                if ($r['statut'] == 'en_attente') echo '<span class="sub-badge" style="background:#fef3c7; color:#92400e;">En attente</span>';
                                                elseif ($r['statut'] == 'accepte') echo '<span class="sub-badge" style="background:#d1fae5; color:#065f46;">Accepté</span>';
                                                else {
                                                    echo '<span class="sub-badge" style="background:#fee2e2; color:#991b1b;">Refusé</span>';
                                                    if (!empty($r['commentaire'])) {
                                                        echo '<div class="motif-refus">Motif : ' . htmlspecialchars($r['commentaire']) . '</div>';
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if ($r['statut'] == 'en_attente'): ?>
                                                    <button onclick="confirmCancel(<?php echo $r['id']; ?>)" class="btn-cancel">
                                                        <i class="fa-solid fa-xmark"></i> Annuler
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div style="text-align: center; padding: 60px 20px;">
                                <i class="fa-solid fa-receipt" style="font-size: 60px; color: var(--border); margin-bottom: 20px;"></i>
                                <h3 style="color: var(--text-light);">Aucune demande de retrait</h3>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal d'édition du profil -->
    <div class="modal fade modal-modern" id="editProfileModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier mon profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php
                    if (isset($_POST['envoyer'])) {
                        $nom = trim($_POST['nom']);
                        $prenom = trim($_POST['prenom']);
                        $email_verif = $_POST['email'];

                        if (!empty($nom) && !empty($prenom)) {
                            $stmt = $database->prepare("UPDATE utilisateur SET nom = ?, prenom = ? WHERE email = ?");
                            if ($stmt->execute([$nom, $prenom, $email_verif])) {
                                echo '
                                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                    <script>
                                        Swal.fire({
                                            icon: "success",
                                            title: "Succès !",
                                            text: "Vos informations ont été mises à jour.",
                                            confirmButtonColor: "#087d67",
                                            timer: 2000
                                        }).then(() => {
                                            window.location.href = "compte.php";
                                        });
                                    </script>';
                            } else {
                                echo '
                                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                    <script>
                                        Swal.fire({
                                            icon: "error",
                                            title: "Erreur",
                                            text: "Une erreur est survenue.",
                                            confirmButtonColor: "#ef4444"
                                        });
                                    </script>';
                            }
                        } else {
                            echo '
                                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                <script>
                                    Swal.fire({
                                        icon: "warning",
                                        title: "Attention",
                                        text: "Tous les champs doivent être remplis.",
                                        confirmButtonColor: "#f97316"
                                    });
                                </script>';
                        }
                    }
                    ?>
                    <form method="POST">
                        <div class="form-group">
                            <label class="form-label">Nom</label>
                            <input type="text" class="form-control" name="nom" value="<?php echo htmlspecialchars($donnee1['nom']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Prénom</label>
                            <input type="text" class="form-control" name="prenom" value="<?php echo htmlspecialchars($donnee1['prenom']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($donnee1['email']); ?>" readonly>
                        </div>
                        <div class="modal-footer" style="padding: 20px 0 0 0;">
                            <button type="button" class="btn-modal btn-modal-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" name="envoyer" class="btn-modal btn-modal-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal "Devenir vendeur" (simplifié) -->
    <div class="modal fade modal-modern" id="becomeSellerModal" tabindex="-1" aria-labelledby="becomeSellerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="becomeSellerModalLabel">Devenir vendeur sur NDIGITMARKET</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-4">Remplissez le formulaire ci-dessous pour soumettre votre demande. Notre équipe l'examinera et vous recontactera rapidement.</p>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label class="form-label">Nom de votre boutique <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nom_boutique" required placeholder="Ex: MonShop Digital">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Description des produits numériques que vous souhaitez vendre <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="description" rows="3" required placeholder="Décrivez les types de produits numériques que vous souhaitez vendre (applications, jeux, ebooks, formations, designs, etc.)..."></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Numéro MTN Bénin <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" style="background:#ffcc00; border-color:#ffcc00; color:#000;">
                                    <i class="fa-solid fa-mobile-screen-button"></i>
                                </span>
                                <input type="tel" class="form-control" name="telephone" required 
                                       placeholder="Ex: 01 97 12 34 56"
                                       pattern="^(229)?[0-9]{10,12}$"
                                       title="Numéro MTN Bénin (10 ou 12 chiffres, commence par 01)">
                            </div>
                            <small class="text-muted">Votre numéro MTN (format 01XXXXXXXX ou 22901XXXXXXXX)</small>
                        </div>
                        <div class="modal-footer" style="padding: 20px 0 0 0;">
                            <button type="button" class="btn-modal btn-modal-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" name="submit_become_seller" class="btn-modal btn-modal-primary">Envoyer ma demande</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal demande de retrait (vendeur) avec numéro modifiable -->
    <?php if ($is_seller): ?>
    <div class="modal fade modal-modern" id="withdrawalModal" tabindex="-1" aria-labelledby="withdrawalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Demander un retrait</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <p style="color: var(--text-light); font-size: 14px;">
                            <i class="fa-solid fa-circle-info" style="color: var(--primary);"></i> 
                            Solde disponible : <strong><?php echo number_format($wallet_data['solde'], 0, ',', ' '); ?> CFA</strong>
                        </p>
                        <p style="color: var(--text-light); font-size: 14px;">
                            <i class="fa-solid fa-circle-info" style="color: var(--primary);"></i> 
                            Montant minimum : <strong>2 000 CFA</strong>
                        </p>
                        <p style="color: var(--text-light); font-size: 14px;">
                            <i class="fa-solid fa-triangle-exclamation" style="color: var(--warning);"></i> 
                            <strong>Vérifiez bien votre numéro MTN Mobile Money avant de valider.</strong> Aucune modification ne sera possible après l'envoi.
                        </p>
                    </div>
                    <form method="POST" action="traitement_retrait.php" onsubmit="return validateWithdrawal(event)">
                        <div class="form-group">
                            <label class="form-label">Numéro MTN Mobile Money <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" style="background:#ffcc00; border-color:#ffcc00; color:#000;">
                                    <i class="fa-solid fa-mobile-screen-button"></i>
                                </span>
                                <input type="tel" class="form-control" name="telephone" id="telephoneRetrait" required 
                                       placeholder="Ex: 01 97 12 34 56"
                                       pattern="^(229)?01[0-9]{8}$"
                                       title="Numéro MTN Bénin commençant par 01 (10 ou 12 chiffres)"
                                       value="<?php echo htmlspecialchars($seller_phone); ?>">
                            </div>
                            <small class="text-muted">Format : 01XXXXXXXX ou 22901XXXXXXXX</small>
                            <div class="mt-2">
                                <button type="button" class="btn-cancel" style="font-size: 12px;" onclick="openUpdatePhoneModal()">
                                    <i class="fa-solid fa-pen"></i> Modifier mon numéro
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Montant (CFA) <span class="text-danger">*</span></label>
                            <input type="number" step="1" min="2000" max="<?php echo $wallet_data['solde']; ?>" 
                                   class="form-control" name="montant" id="montantRetrait" 
                                   required placeholder="Minimum 2 000 CFA">
                            <small class="text-muted">Vous pouvez retirer entre 2 000 CFA et votre solde actuel.</small>
                        </div>
                        <div class="modal-footer" style="padding: 20px 0 0 0;">
                            <button type="button" class="btn-modal btn-modal-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn-modal btn-modal-primary" id="btnValiderRetrait">
                                <i class="fa-solid fa-paper-plane"></i> Demander le retrait
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal modification du numéro de téléphone -->
    <div class="modal fade modal-modern" id="updatePhoneModal" tabindex="-1" aria-labelledby="updatePhoneModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier mon numéro de téléphone</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label class="form-label">Nouveau numéro MTN Mobile Money <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" style="background:#ffcc00; border-color:#ffcc00; color:#000;">
                                    <i class="fa-solid fa-mobile-screen-button"></i>
                                </span>
                                <input type="tel" class="form-control" name="new_phone_number" required 
                                       placeholder="Ex: 01 97 12 34 56"
                                       pattern="^(229)?01[0-9]{8}$"
                                       title="Numéro MTN Bénin commençant par 01 (10 ou 12 chiffres)"
                                       value="<?php echo htmlspecialchars($seller_phone); ?>">
                            </div>
                            <small class="text-muted">Format : 01XXXXXXXX ou 22901XXXXXXXX</small>
                        </div>
                        <div class="modal-footer" style="padding: 20px 0 0 0;">
                            <button type="button" class="btn-modal btn-modal-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" name="update_phone_number" class="btn-modal btn-modal-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function showTab(tabName) {
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.style.display = 'none';
            });
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active');
            });
            
            document.getElementById(`tab-${tabName}`).style.display = 'block';
            const buttons = document.querySelectorAll('.menu-item');
            buttons.forEach(btn => {
                if (btn.getAttribute('onclick') && btn.getAttribute('onclick').includes(`'${tabName}'`)) {
                    btn.classList.add('active');
                }
            });
            
            if (window.innerWidth <= 992) {
                document.getElementById('dashboardSidebar').classList.remove('active');
                document.getElementById('sidebarOverlay').classList.remove('active');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (window.location.hash === '#tab-seller-withdrawals') {
                showTab('seller-withdrawals');
            }
        });

        function openBecomeSellerModal() {
            $('#becomeSellerModal').modal('show');
            if (window.innerWidth <= 992) {
                document.getElementById('dashboardSidebar').classList.remove('active');
                document.getElementById('sidebarOverlay').classList.remove('active');
            }
        }

        function openWithdrawalModal() {
            $('#withdrawalModal').modal('show');
        }

        function openUpdatePhoneModal() {
            $('#updatePhoneModal').modal('show');
        }

        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            document.getElementById('dashboardSidebar').classList.add('active');
            document.getElementById('sidebarOverlay').classList.add('active');
        });

        document.getElementById('closeSidebar').addEventListener('click', function() {
            document.getElementById('dashboardSidebar').classList.remove('active');
            document.getElementById('sidebarOverlay').classList.remove('active');
        });

        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            document.getElementById('dashboardSidebar').classList.remove('active');
            this.classList.remove('active');
        });

        function validateWithdrawal(event) {
            const montant = parseFloat(document.getElementById('montantRetrait').value);
            const telephone = document.getElementById('telephoneRetrait').value.trim();
            const solde = <?php echo $wallet_data['solde'] ?? 0; ?>;
            
            const telClean = telephone.replace(/\s+/g, '');
            const pattern = /^(229)?01[0-9]{8}$/;
            
            if (!pattern.test(telClean)) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Numéro MTN invalide',
                    text: 'Le numéro doit commencer par 01 et contenir 10 chiffres (ex: 0197123456). Veuillez vérifier votre numéro.',
                    confirmButtonColor: '#ef4444'
                });
                return false;
            }
            
            if (montant < 2000) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Montant insuffisant',
                    text: 'Le montant minimum de retrait est de 2 000 CFA.',
                    confirmButtonColor: '#ef4444'
                });
                return false;
            }
            
            if (montant > solde) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Solde insuffisant',
                    text: 'Votre solde actuel est de ' + solde.toLocaleString('fr-FR') + ' CFA.',
                    confirmButtonColor: '#ef4444'
                });
                return false;
            }
            
            return true;
        }

        function confirmCancel(retraitId) {
            Swal.fire({
                title: 'Annuler la demande ?',
                text: 'Voulez-vous vraiment annuler cette demande de retrait ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Oui, annuler',
                cancelButtonText: 'Non'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'annuler_retrait.php?id=' + retraitId;
                }
            });
        }
    </script>

    <?php require('footer.php'); ?>
</body>
</html>