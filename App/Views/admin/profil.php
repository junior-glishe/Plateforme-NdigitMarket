<?php

/**
 * NDIGITMARKET - Page Profil Administrateur (vue complète avec CRUD)
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base = defined('BASE_URL') ? BASE_URL : '/back-end';
$currentPage = 'profil';

$adminEmail = $_SESSION['email'] ?? $_SESSION['user']['email'] ?? '';

try {
    $db = \Database::getConnection();
    $stmt = $db->prepare("SELECT * FROM admin WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $adminEmail]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (\Exception $e) {
    $admin = null;
}

if (!$admin) {
    $admin = [
        'id_gestion'   => 'N/A',
        'nom'          => $_SESSION['user_name'] ?? $_SESSION['user']['name'] ?? 'Administrateur',
        'email'        => $adminEmail ?: 'N/A',
        'mdp'          => '********',
        'image_auteur' => '',
        'role'         => $_SESSION['user_role'] ?? $_SESSION['user']['role'] ?? 'admin',
    ];
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NDIGITMARKET Admin · Profil</title>
    <link rel="icon" type="image/png" href="<?= $base ?>/public/assets/images/favi.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= $base ?>/public/assets/CSS/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .card-profile {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .card-profile-header {
            background: linear-gradient(135deg, #0F172A 0%, #1e293b 100%);
            padding: 50px 40px 40px;
            text-align: center;
            position: relative;
        }

        .avatar-wrapper {
            position: relative;
            display: inline-block;
        }

        .avatar-wrapper img,
        .avatar-placeholder {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            border: 4px solid #0EA486;
            object-fit: cover;
            display: block;
        }

        .avatar-placeholder {
            background: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 52px;
            font-weight: 700;
            color: #0EA486;
            margin: 0 auto;
        }

        .avatar-badge {
            position: absolute;
            bottom: 4px;
            right: 4px;
            width: 36px;
            height: 36px;
            background: #0EA486;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 14px;
            border: 3px solid #0F172A;
            cursor: pointer;
            transition: all 0.2s;
        }

        .avatar-badge:hover {
            background: #0c8f75;
            transform: scale(1.05);
        }

        .profile-name-display {
            font-size: 26px;
            font-weight: 800;
            color: #fff;
            margin-top: 16px;
            letter-spacing: -0.3px;
        }

        .profile-role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(14, 164, 134, 0.2);
            color: #0EA486;
            padding: 5px 18px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.3px;
            margin-top: 6px;
        }

        .stat-box {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            padding: 20px;
            transition: all 0.2s;
        }

        .stat-box:hover {
            border-color: #0EA486;
            box-shadow: 0 4px 16px rgba(14, 164, 134, 0.08);
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .info-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #94a3b8;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 15px;
            font-weight: 600;
            color: #0F172A;
            word-break: break-word;
        }

        .modal-box {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 560px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .form-input {
            width: 100%;
            padding: 10px 14px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            outline: none;
            transition: all 0.2s;
        }

        .form-input:focus {
            border-color: #0EA486;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(14, 164, 134, 0.1);
        }

        .form-input::file-selector-button {
            padding: 6px 14px;
            border-radius: 8px;
            border: none;
            background: #0F172A;
            color: #fff;
            font-size: 12px;
            cursor: pointer;
            margin-right: 10px;
        }

        .btn-primary {
            background: #0F172A;
            color: #fff;
            padding: 10px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            background: #1e293b;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-outline {
            background: transparent;
            color: #64748b;
            padding: 10px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-outline:hover {
            background: #f1f5f9;
            color: #0F172A;
            border-color: #cbd5e1;
        }

        .btn-green {
            background: #0EA486;
            color: #fff;
            padding: 10px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-green:hover {
            background: #0c8f75;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(14, 164, 134, 0.2);
        }

        .btn-green:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-danger {
            background: #dc2626;
            color: #fff;
            padding: 10px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-danger:hover {
            background: #b91c1c;
            transform: translateY(-1px);
        }

        .tab-btn {
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            background: transparent;
            color: #94a3b8;
        }

        .tab-btn.active {
            background: #0F172A;
            color: #fff;
        }

        .tab-btn:not(.active):hover {
            background: #f1f5f9;
            color: #0F172A;
        }

        /* Notification toast custom */
        .toast-notif {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            padding: 16px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
            display: none;
            animation: slideIn 0.3s ease;
            max-width: 400px;
        }

        .toast-notif.success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            display: block;
        }

        .toast-notif.error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            display: block;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <?php require_once __DIR__ . '/../components/sidebar.php'; ?>

    <main class="md:ml-[280px] min-h-screen p-4 md:p-8 transition-all bg-[#f8fafc]">

        <!-- HEADER -->
        <header class="flex items-center justify-between mb-6 bg-white/80 backdrop-blur-sm sticky top-0 z-30 py-4 px-4 md:px-6 rounded-2xl shadow-sm border border-gray-100/50">
            <div class="flex items-center gap-4">
                <button id="hamburgerBtn" class="md:hidden w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 transition flex items-center justify-center text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-[#0F172A]">Mon Profil</h2>
                    <p class="text-xs text-gray-400 hidden sm:block">Gérez vos informations personnelles et votre mot de passe</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button class="relative w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 transition flex items-center justify-center">
                    <i class="fas fa-bell text-gray-600"></i>
                    <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                </button>
                <div class="w-9 h-9 rounded-full bg-[#0EA486] text-white flex items-center justify-center font-semibold text-sm">
                    <?= strtoupper(substr($admin['nom'] ?? 'A', 0, 1)) ?>
                </div>
            </div>
        </header>

        <!-- TOAST NOTIFICATION -->
        <div id="toast" class="toast-notif"></div>

        <!-- STATS RAPIDES -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="stat-box flex items-center gap-4">
                <div class="stat-icon bg-emerald-50 text-emerald-600">
                    <i class="fas fa-hashtag"></i>
                </div>
                <div>
                    <p class="info-label">ID Gestion</p>
                    <p class="info-value">#<?= htmlspecialchars($admin['id_gestion']) ?></p>
                </div>
            </div>
            <div class="stat-box flex items-center gap-4">
                <div class="stat-icon bg-blue-50 text-blue-600">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div>
                    <p class="info-label">Rôle</p>
                    <p class="info-value"><?= htmlspecialchars(ucfirst($admin['role'])) ?></p>
                </div>
            </div>
            <div class="stat-box flex items-center gap-4">
                <div class="stat-icon bg-purple-50 text-purple-600">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <p class="info-label">Statut</p>
                    <p class="info-value text-emerald-600">● Actif</p>
                </div>
            </div>
        </div>

        <!-- PROFILE CARD -->
        <div class="card-profile mb-6">
            <div class="card-profile-header">
                <div class="avatar-wrapper">
                    <div id="avatarDisplayContainer">
                        <?php if (!empty($admin['image_auteur'])): ?>
                            <img id="profileAvatarDisplay" src="<?= $base ?>/<?= htmlspecialchars($admin['image_auteur']) ?>" alt="Avatar" class="w-[130px] h-[130px] rounded-full border-4 border-[#0EA486] object-cover block mx-auto">
                        <?php else: ?>
                            <div id="profileAvatarDisplay" class="avatar-placeholder">
                                <?= strtoupper(substr($admin['nom'] ?? 'A', 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button class="avatar-badge" onclick="document.getElementById('editProfileBtn').click()" title="Modifier la photo">
                        <i class="fas fa-camera"></i>
                    </button>
                </div>
                <div class="profile-name-display" id="profileNameDisplay"><?= htmlspecialchars($admin['nom']) ?></div>
                <span class="profile-role-badge">
                    <i class="fas fa-shield-alt"></i> <?= htmlspecialchars(ucfirst($admin['role'])) ?>
                </span>
            </div>

            <!-- TABS -->
            <div class="px-6 pt-6 pb-2 border-b border-gray-100 flex gap-2">
                <button class="tab-btn active" data-tab="info">
                    <i class="fas fa-user-circle"></i> Informations
                </button>
                <button class="tab-btn" data-tab="password">
                    <i class="fas fa-lock"></i> Mot de passe
                </button>
            </div>

            <!-- TAB CONTENT: INFOS -->
            <div id="tab-info" class="tab-content p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <p class="info-label"><i class="fas fa-envelope text-gray-400 mr-1"></i> Adresse email</p>
                        <p class="info-value" id="profileEmailDisplay"><?= htmlspecialchars($admin['email']) ?></p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <p class="info-label"><i class="fas fa-lock text-gray-400 mr-1"></i> Mot de passe</p>
                        <p class="info-value text-gray-400">••••••••</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <p class="info-label"><i class="fas fa-image text-gray-400 mr-1"></i> Photo de profil</p>
                        <p class="info-value" id="profilePhotoStatus">
                            <?php if (!empty($admin['image_auteur'])): ?>
                                <span class="text-emerald-600"><i class="fas fa-check-circle"></i> Photo disponible</span>
                            <?php else: ?>
                                <span class="text-gray-400">Aucune photo</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <p class="info-label"><i class="fas fa-user-tag text-gray-400 mr-1"></i> Nom complet</p>
                        <p class="info-value"><?= htmlspecialchars($admin['nom']) ?></p>
                    </div>
                </div>
                <button id="editProfileBtn" class="btn-primary" onclick="openEditModal()">
                    <i class="fas fa-pen"></i> Modifier le profil
                </button>
            </div>

            <!-- TAB CONTENT: PASSWORD -->
            <div id="tab-password" class="tab-content p-6 hidden">
                <form id="passwordForm" onsubmit="return handlePasswordChange(event)">
                    <div class="grid grid-cols-1 gap-4 max-w-lg">
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1.5 block">Mot de passe actuel <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="password" name="current_password" required
                                    class="form-input pl-10" placeholder="Entrez votre mot de passe actuel">
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1.5 block">Nouveau mot de passe <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <i class="fas fa-key absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="password" name="new_password" required minlength="6"
                                    class="form-input pl-10" placeholder="Minimum 6 caractères">
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1.5 block">Confirmer le mot de passe <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <i class="fas fa-check-circle absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="password" name="confirm_password" required
                                    class="form-input pl-10" placeholder="Confirmez le nouveau mot de passe">
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3">
                        <button type="submit" class="btn-green" id="passwordSubmitBtn">
                            <i class="fas fa-save"></i> Changer le mot de passe
                        </button>
                        <button type="reset" class="btn-outline">
                            <i class="fas fa-times"></i> Effacer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- BOUTONS DE NAVIGATION -->
        <div class="flex flex-wrap gap-3">
            <a href="<?= $base ?>/index.php?route=admin/dashboard"
                class="btn-outline text-sm">
                <i class="fas fa-th-large"></i> Tableau de bord
            </a>
            <a href="<?= $base ?>/index.php?route=admin/parametres-systeme"
                class="btn-outline text-sm">
                <i class="fas fa-cog"></i> Paramètres
            </a>
        </div>

        <footer class="mt-8 text-center text-xs text-gray-400 border-t border-gray-100 pt-6">
            &copy; 2026 NDIGITMARKET · Administration
        </footer>
    </main>

    <!-- MODAL ÉDITION PROFIL -->
    <div id="editProfileModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="modal-box flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A]">Modifier le profil</h3>
                    <p class="text-xs text-gray-400">Mettez à jour vos informations personnelles</p>
                </div>
                <button onclick="closeEditModal()" class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editProfileForm" onsubmit="return handleUpdateProfile(event)">
                <div class="p-6 space-y-4">
                    <div class="flex flex-col items-center mb-4">
                        <div class="relative mb-3">
                            <img id="modalAvatarPreview"
                                src="<?= !empty($admin['image_auteur']) ? $base . '/' . $admin['image_auteur'] : '' ?>"
                                class="w-24 h-24 rounded-full object-cover border-4 border-gray-100"
                                alt="Avatar"
                                style="<?= empty($admin['image_auteur']) ? 'display:none' : '' ?>">
                            <div id="modalAvatarPlaceholder"
                                class="w-24 h-24 rounded-full border-4 border-gray-100 bg-gray-100 flex items-center justify-center text-3xl font-bold text-gray-400"
                                style="<?= !empty($admin['image_auteur']) ? 'display:none' : '' ?>">
                                <?= strtoupper(substr($admin['nom'] ?? 'A', 0, 1)) ?>
                            </div>
                        </div>
                        <label class="text-xs text-[#0EA486] font-medium cursor-pointer hover:underline">
                            <i class="fas fa-camera"></i> Changer la photo
                            <input type="file" name="image_auteur" accept="image/*" class="hidden" onchange="previewModalAvatar(event)">
                        </label>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1.5 block">Nom complet <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" name="nom" required
                                value="<?= htmlspecialchars($admin['nom']) ?>"
                                class="form-input pl-10" placeholder="Votre nom">
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1.5 block">Adresse email <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="email" name="email" required
                                value="<?= htmlspecialchars($admin['email']) ?>"
                                class="form-input pl-10" placeholder="votre@email.com">
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit" class="btn-green flex-1 justify-center" id="profileSubmitBtn">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                        <button type="button" onclick="closeEditModal()" class="btn-outline flex-1 justify-center">
                            <i class="fas fa-times"></i> Annuler
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- SWEETALERT2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ===== CONFIG =====
        const BASE = window.NDIGIT_BASE_URL || '/back-end';

        // ===== TOAST =====
        function showToast(message, type) {
            const toast = document.getElementById('toast');
            toast.className = 'toast-notif ' + type;
            toast.innerHTML = '<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + ' mr-2"></i> ' + message;
            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }

        // ===== TABS =====
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
                document.getElementById('tab-' + this.dataset.tab).classList.remove('hidden');
            });
        });

        // ===== MODAL =====
        function openEditModal() {
            document.getElementById('editProfileModal').classList.remove('hidden');
            document.getElementById('editProfileModal').classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editProfileModal').classList.add('hidden');
            document.getElementById('editProfileModal').classList.remove('flex');
        }

        document.getElementById('editProfileModal').addEventListener('click', function(e) {
            if (e.target === this) closeEditModal();
        });

        // ===== PREVIEW AVATAR =====
        function previewModalAvatar(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('modalAvatarPreview');
                const placeholder = document.getElementById('modalAvatarPlaceholder');
                img.src = e.target.result;
                img.style.display = 'block';
                placeholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }

        // ===== HANDLE UPDATE PROFILE (AJAX avec upload d'image) =====
        async function handleUpdateProfile(event) {
            event.preventDefault();
            const form = event.target;
            const submitBtn = document.getElementById('profileSubmitBtn');
            const origHtml = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';

            try {
                const formData = new FormData(form);
                const url = BASE + '/index.php?route=admin/updateProfile';

                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const text = await res.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (_) {
                    data = {
                        success: false,
                        message: 'Réponse invalide: ' + text.substring(0, 200)
                    };
                }

                if (data.success) {
                    // Mettre à jour les affichages
                    document.getElementById('profileNameDisplay').textContent = data.data.nom;
                    document.getElementById('profileEmailDisplay').textContent = data.data.email;
                    document.getElementById('profilePhotoStatus').innerHTML = data.data.image_auteur ?
                        '<span class="text-emerald-600"><i class="fas fa-check-circle"></i> Photo disponible</span>' :
                        '<span class="text-gray-400">Aucune photo</span>';

                    // Mettre à jour l'avatar
                    const container = document.getElementById('avatarDisplayContainer');
                    if (data.data.image_auteur) {
                        container.innerHTML =
                            '<img id="profileAvatarDisplay" src="' + BASE + '/' + data.data.image_auteur +
                            '" alt="Avatar" class="w-[130px] h-[130px] rounded-full border-4 border-[#0EA486] object-cover block mx-auto">';
                    } else {
                        container.innerHTML =
                            '<div id="profileAvatarDisplay" class="avatar-placeholder">' +
                            data.data.nom.charAt(0).toUpperCase() +
                            '</div>';
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Succès',
                        text: data.message || 'Profil mis à jour avec succès',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2500
                    });
                    closeEditModal();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: data.message || 'Une erreur est survenue'
                    });
                }
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur réseau',
                    text: err.message || 'Impossible de contacter le serveur'
                });
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = origHtml;
            }

            return false;
        }

        // ===== HANDLE CHANGE PASSWORD =====
        async function handlePasswordChange(event) {
            event.preventDefault();
            const form = event.target;
            const submitBtn = document.getElementById('passwordSubmitBtn');
            const origHtml = submitBtn.innerHTML;

            // Validation côté client
            const newPass = form.querySelector('[name="new_password"]').value;
            const confirmPass = form.querySelector('[name="confirm_password"]').value;

            if (newPass !== confirmPass) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Les nouveaux mots de passe ne correspondent pas'
                });
                return false;
            }

            if (newPass.length < 6) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Le mot de passe doit contenir au moins 6 caractères'
                });
                return false;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Modification...';

            try {
                const formData = new URLSearchParams(new FormData(form));
                const url = BASE + '/index.php?route=admin/changePassword';

                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: formData.toString()
                });

                const text = await res.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (_) {
                    data = {
                        success: false,
                        message: 'Réponse invalide: ' + text.substring(0, 200)
                    };
                }

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès',
                        text: data.message || 'Mot de passe modifié avec succès',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2500
                    });
                    form.reset();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: data.message || 'Une erreur est survenue'
                    });
                }
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur réseau',
                    text: err.message || 'Impossible de contacter le serveur'
                });
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = origHtml;
            }

            return false;
        }
    </script>
</body>

</html>