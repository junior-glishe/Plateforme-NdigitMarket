<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != "oui") {
    header('location: ../');
    exit();
}

// Récupérer les informations de l'admin connecté
require_once('../../include/connect.php');
$email = $_SESSION['email'];
$stmt = $database->prepare("SELECT * FROM admin WHERE email = ?");
$stmt->execute([$email]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - NDIGITMARKET</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../assets/css/vendors/bootstrap.css">
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
            --dark: #0f1923;
            --dark-2: #1a2634;
            --text: #374151;
            --text-light: #6b7280;
            --border: #e5e7eb;
            --bg: #f9fafb;
            --white: #ffffff;
            --danger: #ef4444;
            --success: #10b981;
            --warning: #f59e0b;
            --sidebar-width: 280px;
            --header-height: 70px;
            --shadow: 0 10px 30px -10px rgba(0,0,0,0.1);
            --radius: 16px;
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

        /* ===== LAYOUT ===== */
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            border-right: 1px solid var(--border);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid var(--border);
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        }

        .sidebar-header .logo {
            text-align: center;
        }

        .sidebar-header .logo img {
            max-width: 150px;
            height: auto;
        }

        .sidebar-header .logo h3 {
            color: white;
            font-size: 20px;
            font-weight: 700;
            margin-top: 10px;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-title {
            padding: 10px 20px;
            color: var(--text-light);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: var(--text);
            text-decoration: none;
            transition: all 0.3s;
            margin: 2px 10px;
            border-radius: 10px;
        }

        .menu-item i {
            width: 22px;
            color: var(--primary);
            font-size: 18px;
        }

        .menu-item:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .menu-item.active {
            background: var(--primary);
            color: white;
        }

        .menu-item.active i {
            color: white;
        }

        /* ===== CONTENU PRINCIPAL ===== */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background: var(--bg);
        }

        /* ===== HEADER ===== */
        .top-header {
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            position: sticky;
            top: 0;
            z-index: 99;
            box-shadow: var(--shadow);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .menu-toggle {
            display: none;
            background: transparent;
            border: none;
            font-size: 24px;
            color: var(--text);
            cursor: pointer;
        }

        .page-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 40px;
            transition: background 0.3s;
        }

        .admin-profile:hover {
            background: var(--bg);
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            background: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 20px;
        }

        .admin-info {
            display: none;
        }

        .admin-info h6 {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark);
        }

        .admin-info p {
            font-size: 12px;
            color: var(--text-light);
        }

        @media (min-width: 768px) {
            .admin-info {
                display: block;
            }
        }

        .dropdown-menu {
            position: absolute;
            top: 60px;
            right: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            min-width: 200px;
            display: none;
            z-index: 1000;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-item {
            padding: 12px 20px;
            color: var(--text);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background 0.3s;
        }

        .dropdown-item:hover {
            background: var(--bg);
        }

        .dropdown-item i {
            color: var(--primary);
            width: 20px;
        }

        .dropdown-divider {
            height: 1px;
            background: var(--border);
            margin: 8px 0;
        }

        /* ===== CONTENU ===== */
        .content {
            padding: 30px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 992px) {
            .sidebar {
                left: -100%;
            }
            
            .sidebar.active {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .menu-toggle {
                display: block;
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
        }

        /* ===== LOADER ===== */
        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(3px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 99999;
        }

        .loader-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid var(--border);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

<!-- Loader -->
<div class="loader-overlay" id="loader">
    <div class="loader-spinner"></div>
</div>

<!-- Overlay mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="admin-wrapper">
    
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <img src="../assets/images/lo.png" alt="NDIGITMARKET" onerror="this.style.display='none'">
                <h3>NDIGITMARKET</h3>
            </div>
        </div>

        <div class="sidebar-menu">
            <div class="menu-title">Navigation</div>
            
            <a href="index" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                <i class="fa-regular fa-chart-pie"></i>
                Tableau de bord
            </a>
            
            <a href="produits" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'produits.php' ? 'active' : ''; ?>">
                <i class="fa-regular fa-box"></i>
                Produits
            </a>

             <a href="gestion_paiements" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'gestion_paiements.php' ? 'active' : ''; ?>">
                <i class="fa-regular fa-box"></i>
                Paiemants
            </a>
            
            <a href="categorie" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'categorie.php' ? 'active' : ''; ?>">
                <i class="fa-regular fa-tags"></i>
                Catégories
            </a>
            
            <a href="commande" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'commande.php' ? 'active' : ''; ?>">
                <i class="fa-regular fa-cart-shopping"></i>
                Commandes
            </a>
            
            <a href="client" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'client.php' ? 'active' : ''; ?>">
                <i class="fa-regular fa-users"></i>
                Clients
            </a>
            
            <a href="avis" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'avis.php' ? 'active' : ''; ?>">
                <i class="fa-regular fa-star"></i>
                Avis clients
            </a>
            
            <a href="abonnements_list" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'abonnements_list.php' ? 'active' : ''; ?>">
                <i class="fa-regular fa-crown"></i>
                Abonnements
            </a>

            <div class="menu-title">Configuration</div>
            
            <a href="parametres" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'parametres.php' ? 'active' : ''; ?>">
                <i class="fa-regular fa-gear"></i>
                Paramètres
            </a>
            
            <a href="profil" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'profil.php' ? 'active' : ''; ?>">
                <i class="fa-regular fa-user"></i>
                Mon profil
            </a>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="main-content">
        
        <!-- Header -->
        <div class="top-header">
            <div class="header-left">
                <button class="menu-toggle" id="menuToggle">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h2 class="page-title"><?php echo $pageTitle ?? 'Tableau de bord'; ?></h2>
            </div>

            <div class="header-right">
                <div class="admin-profile" id="adminProfile">
                    <div class="admin-avatar">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <div class="admin-info">
                        <h6><?php echo htmlspecialchars($admin['nom'] ?? 'Admin'); ?></h6>
                        <p><?php echo htmlspecialchars($admin['email']); ?></p>
                    </div>
                    <i class="fa-solid fa-chevron-down" style="color: var(--text-light); font-size: 12px;"></i>
                </div>

                <div class="dropdown-menu" id="dropdownMenu">
                    <a href="profil" class="dropdown-item">
                        <i class="fa-regular fa-user"></i>
                        Mon profil
                    </a>
                    <a href="parametres" class="dropdown-item">
                        <i class="fa-regular fa-gear"></i>
                        Paramètres
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="deconnexion" class="dropdown-item" style="color: var(--danger);">
                        <i class="fa-regular fa-arrow-right-from-bracket"></i>
                        Déconnexion
                    </a>
                </div>
            </div>
        </div>

        <!-- Contenu -->
        <div class="content">