<?php
$baseUrl = '/back-end';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Charger le helper de vues pour les permissions
require_once __DIR__ . '/../../Core/ViewHelper.php';
require_once __DIR__ . '/../../Core/Auth.php';

// Importer les fonctions helper
use function App\Core\can;
use function App\Core\canAny;

$role = \App\Core\Auth::role();
$currentPage = $currentPage ?? '';
?>
<?php
// Récupérer les permissions de l'utilisateur pour éviter les appels répétés
$userPermissions = \App\Core\Auth::permissions();
?>

<style>
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

    .sidebar-link.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
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

<div id="sidebar-overlay" class="fixed inset-0 z-40 bg-slate-900/50 lg:hidden"></div>

<aside id="sidebar" class="sidebar sidebar-mobile md:sidebar-mobile md:translate-x-0">
    <div class="p-6">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 bg-[#0EA486]/10 rounded-xl flex items-center justify-center overflow-hidden">
                <img src="<?= $baseUrl ?>/public/assets/images/favi.png" alt="NDIGITMARKET" class="w-8 h-8 object-contain">
            </div>
            <div>
                <h1 class="text-xl font-extrabold text-[#0F172A] tracking-tight">NDIGITMARKET</h1>
                <p class="text-[10px] uppercase tracking-widest text-gray-400">Administration</p>
            </div>
        </div>

        <nav class="space-y-0.5">
            <?php if (\App\Core\Auth::hasPermission('dashboard.view')): ?>
                <a href="<?= $baseUrl ?>/index.php?route=admin/dashboard"
                    class="sidebar-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-th-large"></i> Tableau de bord
                </a>
            <?php endif; ?>

            <?php if (\App\Core\Auth::hasAnyPermission(['users.view', 'vendors.view', 'products.view', 'orders.view'])): ?>
                <h4 class="mt-5 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Gestion</h4>

                <?php if (\App\Core\Auth::hasPermission('users.view')): ?>
                    <a href="<?= $baseUrl ?>/index.php?route=admin/gestion-utilisateurs"
                        class="sidebar-link <?= $currentPage === 'gestion-utilisateurs' ? 'active' : '' ?>">
                        <i class="fas fa-users"></i> Gestion utilisateur
                    </a>
                <?php endif; ?>

                <?php if (\App\Core\Auth::hasPermission('vendors.view')): ?>
                    <a href="<?= $baseUrl ?>/index.php?route=admin/gestion-vendeur"
                        class="sidebar-link <?= $currentPage === 'gestion-vendeur' ? 'active' : '' ?>">
                        <i class="fas fa-store"></i> Gestion vendeur
                    </a>
                <?php endif; ?>

                <?php if (\App\Core\Auth::hasPermission('products.view')): ?>
                    <a href="<?= $baseUrl ?>/index.php?route=admin/gestion-produits"
                        class="sidebar-link <?= $currentPage === 'gestion-produits' ? 'active' : '' ?>">
                        <i class="fas fa-file-code"></i> Produits & Templates
                    </a>
                <?php endif; ?>

                <?php if (\App\Core\Auth::hasPermission('orders.view')): ?>
                    <a href="<?= $baseUrl ?>/index.php?route=admin/gestion-commande"
                        class="sidebar-link <?= $currentPage === 'gestion-commande' ? 'active' : '' ?>">
                        <i class="fas fa-shopping-cart"></i> Gestion commande
                    </a>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (\App\Core\Auth::hasAnyPermission(['finance.view', 'categories.view', 'advertisements.view', 'banners.view', 'notifications.view'])): ?>
                <h4 class="mt-5 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Finances & Contenu
                </h4>

                <?php if (\App\Core\Auth::hasPermission('finance.view')): ?>
                    <a href="<?= $baseUrl ?>/index.php?route=admin/financieres-commission"
                        class="sidebar-link <?= $currentPage === 'financieres-commission' ? 'active' : '' ?>">
                        <i class="fas fa-coins"></i> Financières & commission
                    </a>
                <?php endif; ?>

                <?php if (\App\Core\Auth::hasPermission('categories.view')): ?>
                    <a href="<?= $baseUrl ?>/routes/api.php?url=categories"
                        class="sidebar-link <?= $currentPage === 'categories' ? 'active' : '' ?>">
                        <i class="fas fa-tags"></i> Catégorie
                    </a>
                <?php endif; ?>

                <?php if (\App\Core\Auth::hasPermission('advertisements.view')): ?>
                    <a href="<?= $baseUrl ?>/routes/api.php?url=contenus"
                        class="sidebar-link <?= $currentPage === 'contenus' ? 'active' : '' ?>">
                        <i class="fas fa-newspaper"></i> Contenus
                    </a>
                <?php endif; ?>

                <?php if (\App\Core\Auth::hasPermission('notifications.view')): ?>
                    <a href="<?= $baseUrl ?>/routes/api.php?url=notifications"
                        class="sidebar-link <?= $currentPage === 'notifications' ? 'active' : '' ?>">
                        <i class="fas fa-bell"></i> Notifications
                    </a>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (\App\Core\Auth::hasAnyPermission(['statistics.view', 'reviews.view', 'settings.view', 'logs.view', 'admins.view'])): ?>
                <h4 class="mt-5 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Analyse & Système
                </h4>

                <?php if (\App\Core\Auth::hasPermission('statistics.view')): ?>
                    <a href="<?= $baseUrl ?>/routes/api.php?url=rapport-stat"
                        class="sidebar-link <?= $currentPage === 'rapport-stat' ? 'active' : '' ?>">
                        <i class="fas fa-chart-pie"></i> Statistique & rapport
                    </a>
                <?php endif; ?>

                <?php if (\App\Core\Auth::hasPermission('reviews.view')): ?>
                    <a href="<?= $baseUrl ?>/routes/api.php?url=categories"
                        class="sidebar-link <?= $currentPage === 'avis-commentaires' ? 'active' : '' ?>">
                        <i class="fas fa-comment-dots"></i> Avis / Commentaire
                    </a>
                <?php endif; ?>

                <?php if (\App\Core\Auth::hasPermission('settings.view')): ?>
                    <a href="<?= $baseUrl ?>/routes/api.php?url=parametres-systeme"
                        class="sidebar-link <?= $currentPage === 'parametres-systeme' ? 'active' : '' ?>">
                        <i class="fas fa-cog"></i> Paramètre système
                    </a>
                <?php endif; ?>

                <?php if (\App\Core\Auth::hasPermission('logs.view')): ?>
                    <a href="<?= $baseUrl ?>/routes/api.php?url=categories"
                        class="sidebar-link <?= $currentPage === 'logs-audit' ? 'active' : '' ?>">
                        <i class="fas fa-history"></i> Logs & audit trail
                    </a>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (\App\Core\Auth::hasPermission('profile.view')): ?>
                <h4 class="mt-5 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Mon Compte
                </h4>

                <a href="<?= $baseUrl ?>/index.php?route=admin/profil"
                    class="sidebar-link <?= $currentPage === 'profil' ? 'active' : '' ?>">
                    <i class="fas fa-user-circle"></i> Profil
                </a>
            <?php endif; ?>
        </nav>
        <div class="mt-8 pt-6 border-t border-gray-100">
            <div class="bg-[#0F172A] rounded-2xl p-4 text-white">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-[#0EA486] rounded-full flex items-center justify-center text-lg font-semibold">
                        <?= strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)) ?>
                    </div>
                    <div>
                        <p class="text-sm font-semibold"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Administrateur') ?></p>
                        <p class="text-[11px] text-gray-400"><?= htmlspecialchars($role ?? 'Invité') ?></p>
                    </div>
                </div>
                <a href="<?= $baseUrl ?>/index.php?route=logout"
                    class="text-xs text-gray-300 hover:text-white transition flex items-center gap-2">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </div>
        </div>
    </div>
</aside>

<script>
    function initSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const toggleBtn = document.getElementById('hamburgerBtn');

        if (!sidebar || !overlay || !toggleBtn) return;

        function openSidebar() {
            sidebar.classList.add('is-open');
            overlay.classList.add('is-open');
            document.body.classList.add('overflow-hidden');
            toggleBtn.setAttribute('aria-expanded', 'true');
            toggleBtn.style.display = 'none';
        }

        function closeSidebar() {
            sidebar.classList.remove('is-open');
            overlay.classList.remove('is-open');
            document.body.classList.remove('overflow-hidden');
            toggleBtn.setAttribute('aria-expanded', 'false');
            toggleBtn.style.display = 'flex';
        }

        toggleBtn.addEventListener('click', (event) => {
            event.preventDefault();
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
                toggleBtn.setAttribute('aria-expanded', 'false');
            } else {
                toggleBtn.style.display = 'flex';
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSidebar);
    } else {
        initSidebar();
    }
</script>
<?php // Assets globaux admin (chargés une seule fois par page) 
?>
<?php $__base = defined('$baseUrl') ? $baseUrl : ''; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script>
    window.NDIGIT_$baseUrl = <?= json_encode($__base) ?>;
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= $__base ?>/public/assets/JS/admin.js" defer></script>