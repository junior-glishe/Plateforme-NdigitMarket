<?php
$role = $_SESSION['user_role'];
$currentPage = $_GET['url'] ?? 'dashboard';
$baseUrl = "/NdigitMarket/public/index.php?url=";

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NDIGITMARKET</title>

    <!-- TAILWIND -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .sidebar {
            width: 280px;
            height: 100vh;
            background: white;
            border-right: 1px solid #e5e7eb;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            transition: transform 0.3s ease;
            z-index: 50;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            border-radius: 10px;
            color: #64748b;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .sidebar-link i {
            width: 20px;
            font-size: 16px;
            color: #94a3b8;
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            background: #f1f5f9;
            color: #0F172A;
            transform: translateX(4px);
        }

        .sidebar-link:hover i {
            color: #0EA486;
        }

        .sidebar-link.active {
            background: #0F172A;
            color: white;
        }

        .sidebar-link.active i {
            color: #0EA486;
        }

        /* ===== SIDEBAR MOBILE ===== */
        #sidebar {
            transform: translateX(-100%);
        }

        #sidebar.is-open {
            transform: translateX(0);
        }

        /* ===== DESKTOP ===== */
        @media (min-width: 1024px) {
            #sidebar {
                transform: translateX(0) !important;
            }
        }

        /* ===== OVERLAY ===== */
        #sidebar-overlay {
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        #sidebar-overlay.is-open {
            pointer-events: auto;
            opacity: 1;
        }

        /* Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }
    </style>
</head>

<body class="bg-slate-50">

    <!-- BOUTON HAMBURGER -->
    <button id="sidebar-toggle" type="button"
        class="fixed top-4 left-4 z-[60] lg:hidden w-11 h-11 rounded-xl bg-[#0F172A] text-white shadow-xl flex items-center justify-center">
        <i class="fa-solid fa-bars"></i>
    </button>

    <div id="sidebar-overlay" class="fixed inset-0 z-40 bg-slate-900/50 lg:hidden"></div>

    <aside id="sidebar" class="sidebar">

        <div class="p-6">
            <!-- Logo -->
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 bg-[#0EA486] rounded-xl flex items-center justify-center text-white font-bold text-xl">
                    N
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-[#0F172A] tracking-tight">NDIGITMARKET</h1>
                    <p class="text-[10px] uppercase tracking-widest text-gray-400">Administration</p>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="space-y-0.5">

                <!-- ADMIN -->
                <?php if($role === 'admin'): ?>

                <a href="<?= $baseUrl ?>dashboard" 
                   class="sidebar-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-th-large"></i> Tableau de bord
                </a>

                <h4 class="mt-5 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Gestion</h4>

                <a href="<?= $baseUrl ?>users" 
                   class="sidebar-link <?= $currentPage === 'users' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i> Gestion utilisateur
                </a>

                <a href="<?= $baseUrl ?>vendeurs" 
                   class="sidebar-link <?= $currentPage === 'vendeurs' ? 'active' : '' ?>">
                    <i class="fas fa-store"></i> Gestion vendeur
                </a>

                <a href="<?= $baseUrl ?>produits" 
                   class="sidebar-link <?= $currentPage === 'produits' ? 'active' : '' ?>">
                    <i class="fas fa-file-code"></i> Produits & Templates
                </a>

                <a href="<?= $baseUrl ?>commandes" 
                   class="sidebar-link <?= $currentPage === 'commandes' ? 'active' : '' ?>">
                    <i class="fas fa-shopping-cart"></i> Gestion commande
                </a>

                <h4 class="mt-5 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Finances & Contenu</h4>

                <a href="<?= $baseUrl ?>finances" 
                   class="sidebar-link <?= $currentPage === 'finances' ? 'active' : '' ?>">
                    <i class="fas fa-coins"></i> Financières & commission
                </a>

                <a href="<?= $baseUrl ?>categories" 
                   class="sidebar-link <?= $currentPage === 'categories' ? 'active' : '' ?>">
                    <i class="fas fa-tags"></i> Catégorie
                </a>

                <a href="<?= $baseUrl ?>contenus" 
                   class="sidebar-link <?= $currentPage === 'contenus' ? 'active' : '' ?>">
                    <i class="fas fa-newspaper"></i> Contenus
                </a>

                <a href="<?= $baseUrl ?>notifications" 
                   class="sidebar-link <?= $currentPage === 'notifications' ? 'active' : '' ?>">
                    <i class="fas fa-bell"></i> Notifications
                </a>

                <h4 class="mt-5 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Analyse & Système</h4>

                <a href="<?= $baseUrl ?>statistiques" 
                   class="sidebar-link <?= $currentPage === 'statistiques' ? 'active' : '' ?>">
                    <i class="fas fa-chart-pie"></i> Statistique & rapport
                </a>

                <a href="<?= $baseUrl ?>avis" 
                   class="sidebar-link <?= $currentPage === 'avis' ? 'active' : '' ?>">
                    <i class="fas fa-comment-dots"></i> Avis / Commentaire
                </a>

                <a href="<?= $baseUrl ?>parametres" 
                   class="sidebar-link <?= $currentPage === 'parametres' ? 'active' : '' ?>">
                    <i class="fas fa-cog"></i> Paramètre système
                </a>

                <a href="<?= $baseUrl ?>logs" 
                   class="sidebar-link <?= $currentPage === 'logs' ? 'active' : '' ?>">
                    <i class="fas fa-history"></i> Logs & audit trail
                </a>

                <?php endif; ?>

                <!-- SUPPORT -->
                <?php if($role === 'support'): ?>
                <a href="<?= $baseUrl ?>faq" 
                   class="sidebar-link <?= $currentPage === 'faq' ? 'active' : '' ?>">
                    <i class="fas fa-question-circle"></i> FAQ & base de connaissances
                </a>

                <h4 class="mt-5 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Rapports</h4>

                <a href="<?= $baseUrl ?>rapports_support" 
                   class="sidebar-link <?= $currentPage === 'rapports_support' ? 'active' : '' ?>">
                    <i class="fas fa-chart-bar"></i> Rapports support
                </a>

                <a href="<?= $baseUrl ?>satisfaction" 
                   class="sidebar-link <?= $currentPage === 'satisfaction' ? 'active' : '' ?>">
                    <i class="fas fa-star"></i> Satisfaction client
                </a>

                <?php endif; ?>

                <!-- MODERATEUR -->
                <?php if($role === 'moderateur'): ?>
                <a href="<?= $baseUrl ?>sanctions" 
                   class="sidebar-link <?= $currentPage === 'sanctions' ? 'active' : '' ?>">
                    <i class="fas fa-ban"></i> Sanctions
                </a>

                <a href="<?= $baseUrl ?>rapports_moderation" 
                   class="sidebar-link <?= $currentPage === 'rapports_moderation' ? 'active' : '' ?>">
                    <i class="fas fa-chart-pie"></i> Rapports modération
                </a>

                <?php endif; ?>

            </nav>

            <!-- Profil en bas -->
            <div class="mt-8 pt-6 border-t border-gray-100">
                <div class="bg-[#0F172A] rounded-2xl p-4 text-white">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-[#0EA486] rounded-full flex items-center justify-center text-lg font-semibold">
                            <?= strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)) ?>
                        </div>
                        <div>
                            <p class="text-sm font-semibold"><?= $_SESSION['user_name'] ?? 'Administrateur' ?></p>
                            <p class="text-[11px] text-gray-400"><?= ucfirst($role) ?></p>
                        </div>
                    </div>
                    <a href="<?= $baseUrl ?>logout" 
                       class="text-xs text-gray-300 hover:text-white transition flex items-center gap-2">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </a>
                </div>
            </div>
        </div>

    </aside>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const toggleBtn = document.getElementById('sidebar-toggle');

        function openSidebar() {
            sidebar.classList.add('is-open');
            overlay.classList.add('is-open');
            document.body.classList.add('overflow-hidden');
            toggleBtn.style.display = 'none';
        }

        function closeSidebar() {
            sidebar.classList.remove('is-open');
            overlay.classList.remove('is-open');
            document.body.classList.remove('overflow-hidden');
            toggleBtn.style.display = 'flex';
        }

        toggleBtn.addEventListener('click', () => {
            if (sidebar.classList.contains('is-open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });

        overlay.addEventListener('click', closeSidebar);

        document.querySelectorAll('#sidebar a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) {
                    closeSidebar();
                }
            });
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('is-open');
                overlay.classList.remove('is-open');
                document.body.classList.remove('overflow-hidden');
                toggleBtn.style.display = 'none';
            } else {
                toggleBtn.style.display = 'flex';
            }
        });

        // Active link highlighting
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', function() {
                document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>

</body>
</html>
