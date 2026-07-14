<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NDIGITMARKET Admin · Notifications & Emails</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/CSS/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
</head>
<body>
    <div id="overlay" class="overlay"></div>

    
    <?php include __DIR__ . '/../components/sidebar.php'; ?>

    <main class="md:ml-[280px] min-h-screen p-4 md:p-8 transition-all">

        <!-- HEADER -->
        <header class="flex items-center justify-between mb-6 bg-white/80 backdrop-blur-sm sticky top-0 z-30 py-4 px-4 md:px-6 rounded-2xl shadow-sm border border-gray-100/50">
            <div class="flex items-center gap-4">
                <button id="hamburgerBtn" class="md:hidden w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 transition flex items-center justify-center text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-[#0F172A]">Notifications & Emails</h2>
                    <p class="text-xs text-gray-400 hidden sm:block">Centre de notifications, templates et envois en masse</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button id="openNotifCenterBtn" class="relative w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 transition flex items-center justify-center">
                    <i class="fas fa-bell text-gray-600"></i>
                    <span class="absolute top-1 right-1 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full border-2 border-white flex items-center justify-center">...</span>
                </button>
                <div class="w-9 h-9 rounded-full bg-[#0EA486] text-white flex items-center justify-center font-semibold text-sm">
                    A
                </div>
            </div>
        </header>

        <!-- 4.10.1 CENTRE DE NOTIFICATIONS ADMIN -->
        <section class="mb-8">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-bell text-[#0EA486]"></i> · Centre de notifications admin
                </h3>
                <div class="flex gap-2">
                    <button id="markAllReadBtn" class="px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2">
                        <i class="fas fa-check-double"></i> Tout marquer comme lu
                    </button>
                    <button id="openNotifSettingsBtn" class="px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2">
                        <i class="fas fa-cog"></i> Paramètres
                    </button>
                </div>
            </div>

            <!-- Stats notifications -->
            <?php
            // Récupérer les stats depuis le contrôleur
            $totalNonLues = $stats['unread'] ?? 0;
            $demandesVendeur = $stats['by_type']['vendor_request'] ?? 0;
            $commandes = ($stats['by_type']['order_high'] ?? 0) + ($stats['by_type']['order_problem'] ?? 0);
            $alertesSecurite = $stats['by_type']['security_alert'] ?? 0;
            ?>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center text-red-600">
                            <i class="fas fa-bell"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-full">NON LUES</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= $totalNonLues ?></p>
                    <p class="text-xs text-gray-400 mt-1">Notifications non lues</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-yellow-50 rounded-xl flex items-center justify-center text-yellow-600">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-yellow-600 bg-yellow-50 px-2 py-1 rounded-full">VENDEURS</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= $demandesVendeur ?></p>
                    <p class="text-xs text-gray-400 mt-1">Demandes vendeur</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">COMMANDES</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= $commandes ?></p>
                    <p class="text-xs text-gray-400 mt-1">Commandes non traitées</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-purple-600 bg-purple-50 px-2 py-1 rounded-full">SÉCURITÉ</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= $alertesSecurite ?></p>
                    <p class="text-xs text-gray-400 mt-1">Alertes sécurité</p>
                </div>
            </div>

            <!-- Liste des notifications -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h4 class="text-sm font-semibold text-[#0F172A] flex items-center gap-2">
            <i class="fas fa-inbox text-[#0EA486]"></i> Notifications récentes
        </h4>
        <div class="flex gap-2">
            <select id="notifFilter" class="px-3 py-1.5 bg-gray-50 border border-gray-100 rounded-lg text-xs text-gray-600 focus:outline-none focus:border-[#0EA486]">
                <option value="all">Toutes</option>
                <option value="unread">Non lues</option>
                <option value="vendor_request">Vendeurs</option>
                <option value="product_moderation">Produits</option>
                <option value="order_high">Commandes</option>
                <option value="order_problem">Commandes problématiques</option>
                <option value="security_alert">Sécurité</option>
                <option value="support_message">Support</option>
            </select>
        </div>
    </div>

    <!-- Conteneur des notifications (VIDE - Rempli par JS) -->
    <div class="divide-y divide-gray-100" id="notificationsList">
        <!-- Les notifications seront chargées par JS -->
    </div>

    <!-- Pagination / Voir plus -->
    <div class="flex flex-wrap items-center justify-between gap-3 px-4 py-3 border-t border-gray-100 bg-gray-50/50">
        <div class="text-xs text-gray-500">
            <span id="notifCount">0 notification(s)</span>
        </div>
        <button id="loadMoreNotif" class="text-xs font-semibold text-[#0EA486] hover:underline flex items-center gap-1" style="display: none;">
            Voir plus <i class="fas fa-arrow-down text-[10px]"></i>
        </button>
    </div>
</div>
        </section>

        <!-- GESTION DES EMAILS TRANSACTIONNELS -->
<section class="mb-8">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
            <i class="fas fa-envelope-open-text text-[#0EA486]"></i> · Emails transactionnels
        </h3>
        <div class="flex gap-2">
            <button id="openEmailHistoryBtn" class="px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2">
                <i class="fas fa-history"></i> Historique d'envoi
            </button>
        </div>
    </div>

            <!-- Stats emails -->
        <?php
        // Les stats sont déjà passées directement par le contrôleur
        if (!isset($emailStats)) {
            $emailStats = [
                'envoyes' => 0,
                'taux_ouverture' => 0,
                'taux_clic' => 0,
                'templates' => 0
            ];
        }
        ?>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">ENVOYÉS</span>
            </div>
            <p class="text-2xl font-bold text-[#0F172A]"><?= number_format($emailStats['envoyes'] ?? 0, 0, ',', ' ') ?></p>
            <p class="text-xs text-gray-400 mt-1">Emails envoyés ce mois</p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                    <i class="fas fa-eye"></i>
                </div>
                <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">OUVERTS</span>
            </div>
            <p class="text-2xl font-bold text-[#0F172A]"><?= number_format($emailStats['taux_ouverture'] ?? 0, 1) ?>%</p>
            <p class="text-xs text-gray-400 mt-1">Taux d'ouverture moyen</p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600">
                    <i class="fas fa-mouse-pointer"></i>
                </div>
                <span class="text-[10px] font-semibold text-purple-600 bg-purple-50 px-2 py-1 rounded-full">CLICS</span>
            </div>
            <p class="text-2xl font-bold text-[#0F172A]"><?= number_format($emailStats['taux_clic'] ?? 0, 1) ?>%</p>
            <p class="text-xs text-gray-400 mt-1">Taux de clic moyen</p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600">
                    <i class="fas fa-file-alt"></i>
                </div>
                <span class="text-[10px] font-semibold text-orange-600 bg-orange-50 px-2 py-1 rounded-full">TEMPLATES</span>
            </div>
            <p class="text-2xl font-bold text-[#0F172A]"><?= $emailStats['templates'] ?? 0 ?></p>
            <p class="text-xs text-gray-400 mt-1">Templates configurés</p>
        </div>
    </div>

            <!-- Tableau templates -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-4 py-3">Template</th>
                    <th class="px-4 py-3">Déclencheur</th>
                    <th class="px-4 py-3">Destinataire</th>
                    <th class="px-4 py-3">Envoyés (30j)</th>
                    <th class="px-4 py-3">Taux ouverture</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (!empty($templates)): ?>
                    <?php foreach ($templates as $template): ?>
                        <tr class="hover:bg-gray-50/50 transition" data-id="<?= $template['id'] ?>">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 <?= $template['bg_color'] ?? 'bg-amber-100' ?> rounded-lg flex items-center justify-center <?= $template['text_color'] ?? 'text-amber-600' ?>">
                                        <i class="fas <?= $template['icon'] ?? 'fa-envelope-open-text' ?> text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-[#0F172A] text-sm"><?= htmlspecialchars($template['nom']) ?></p>
                                        <p class="text-[10px] text-gray-400 font-mono"><?= htmlspecialchars($template['slug']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-600"><?= htmlspecialchars($template['declencheur']) ?></td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    <?php foreach ($template['destinataires'] ?? [] as $dest): ?>
                                        <span class="text-[10px] font-semibold <?= $dest['class'] ?? 'text-blue-700 bg-blue-100' ?> px-2 py-1 rounded-full"><?= htmlspecialchars($dest['nom']) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]"><?= $template['envoyes'] ?? 0 ?></td>
                            <td class="px-4 py-3 text-xs font-semibold text-emerald-600"><?= $template['taux_ouverture'] ?? 0 ?>%</td>
                            <td class="px-4 py-3">
                                <?php $statut = $template['statut'] ?? 'active'; ?>
                                <span class="text-[10px] font-semibold <?= $statut === 'active' ? 'text-emerald-700 bg-emerald-100' : 'text-gray-500 bg-gray-100' ?> px-2 py-1 rounded-full">
                                    <i class="fas <?= $statut === 'active' ? 'fa-check' : 'fa-pause' ?> mr-1"></i>
                                    <?= $statut === 'active' ? 'Actif' : 'Inactif' ?>
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <button class="openEmailPreviewBtn w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center" 
                                            title="Aperçu"
                                            data-id="<?= $template['id'] ?>"
                                            data-nom="<?= htmlspecialchars($template['nom']) ?>"
                                            data-slug="<?= htmlspecialchars($template['slug']) ?>"
                                            data-objet="<?= htmlspecialchars($template['objet'] ?? '') ?>"
                                            data-contenu="<?= htmlspecialchars($template['contenu'] ?? '') ?>"
                                            data-bouton="<?= htmlspecialchars($template['bouton_texte'] ?? '') ?>"
                                            data-url="<?= htmlspecialchars($template['bouton_url'] ?? '') ?>">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                    <button class="openEmailEditorBtn w-8 h-8 rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-100 flex items-center justify-center" 
                                            title="Modifier"
                                            data-id="<?= $template['id'] ?>"
                                            data-nom="<?= htmlspecialchars($template['nom']) ?>"
                                            data-slug="<?= htmlspecialchars($template['slug']) ?>"
                                            data-objet="<?= htmlspecialchars($template['objet'] ?? '') ?>"
                                            data-contenu="<?= htmlspecialchars($template['contenu'] ?? '') ?>"
                                            data-bouton="<?= htmlspecialchars($template['bouton_texte'] ?? '') ?>"
                                            data-url="<?= htmlspecialchars($template['bouton_url'] ?? '') ?>"
                                            data-statut="<?= $template['statut'] ?? 'active' ?>">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <button class="toggleEmailBtn w-8 h-8 rounded-lg <?= $statut === 'active' ? 'bg-yellow-50 text-yellow-600 hover:bg-yellow-100' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' ?> flex items-center justify-center" 
                                            title="<?= $statut === 'active' ? 'Désactiver' : 'Activer' ?>"
                                            data-id="<?= $template['id'] ?>"
                                            data-statut="<?= $statut ?>"
                                            data-nom="<?= htmlspecialchars($template['nom']) ?>">
                                        <i class="fas <?= $statut === 'active' ? 'fa-toggle-on' : 'fa-toggle-off' ?> text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400 text-sm">
                            <i class="fas fa-file-alt text-3xl block mb-2"></i>
                            Aucun template configuré
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
        </section>

        <!-- ENVOI D'EMAILS EN MASSE -->
        <section class="mb-8">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-paper-plane text-[#0EA486]"></i> · Envoi d'emails en masse
                </h3>
                <div class="flex gap-2">
                    <button class="px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2">
                        <i class="fas fa-history"></i> Historique des envois
                    </button>
                    <button id="openComposeBtn" class="px-4 py-2 rounded-lg bg-[#0EA486] hover:bg-[#0c8f75] text-white text-xs font-semibold flex items-center gap-2 shadow-sm transition">
                        <i class="fas fa-pen"></i> Composer un email
                    </button>
                </div>
            </div>

            <!-- Stats envois masse -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">ENVOIS</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Campagnes envoyées</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                            <i class="fas fa-envelope-open"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">OUVERTS</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...%</p>
                    <p class="text-xs text-gray-400 mt-1">Taux d'ouverture</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600">
                            <i class="fas fa-mouse-pointer"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-purple-600 bg-purple-50 px-2 py-1 rounded-full">CLICS</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...%</p>
                    <p class="text-xs text-gray-400 mt-1">Taux de clic</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600">
                            <i class="fas fa-clock"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-orange-600 bg-orange-50 px-2 py-1 rounded-full">PLANIFIÉS</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Envois programmés</p>
                </div>
            </div>

            <!-- Historique des envois -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h4 class="text-sm font-semibold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-history text-[#0EA486]"></i> Campagnes récentes
                    </h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-3">Campagne</th>
                                <th class="px-4 py-3">Cible</th>
                                <th class="px-4 py-3">Envoyés</th>
                                <th class="px-4 py-3">Ouverts</th>
                                <th class="px-4 py-3">Cliqués</th>
                                <th class="px-4 py-3">Date envoi</th>
                                <th class="px-4 py-3">Statut</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-lg flex items-center justify-center text-indigo-600">
                                            <i class="fas fa-envelope-open-text text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-[#0F172A] text-sm">...</p>
                                            <p class="text-[10px] text-gray-400">...</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-[10px] font-semibold text-blue-700 bg-blue-100 px-2 py-1 rounded-full">...</span>
                                </td>
                                <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">...</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-semibold text-emerald-600">...%</span>
                                        <div class="flex-1 bg-gray-100 rounded-full h-1.5 w-12">
                                            <div class="bg-emerald-500 h-full rounded-full" style="width: 0%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-semibold text-purple-600">...%</span>
                                        <div class="flex-1 bg-gray-100 rounded-full h-1.5 w-12">
                                            <div class="bg-purple-500 h-full rounded-full" style="width: 0%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500">...</td>
                                <td class="px-4 py-3">
                                    <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-1 rounded-full">
                                        <i class="fas fa-check mr-1"></i>Envoyé
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="openCampaignStatsBtn w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center" title="Statistiques">
                                            <i class="fas fa-chart-line text-xs"></i>
                                        </button>
                                        <button class="w-8 h-8 rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-100 flex items-center justify-center" title="Dupliquer">
                                            <i class="fas fa-copy text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-lg flex items-center justify-center text-emerald-600">
                                            <i class="fas fa-envelope-open-text text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-[#0F172A] text-sm">...</p>
                                            <p class="text-[10px] text-gray-400">...</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-1 rounded-full">...</span>
                                </td>
                                <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">...</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-semibold text-emerald-600">...%</span>
                                        <div class="flex-1 bg-gray-100 rounded-full h-1.5 w-12">
                                            <div class="bg-emerald-500 h-full rounded-full" style="width: 0%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-semibold text-purple-600">...%</span>
                                        <div class="flex-1 bg-gray-100 rounded-full h-1.5 w-12">
                                            <div class="bg-purple-500 h-full rounded-full" style="width: 0%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500">...</td>
                                <td class="px-4 py-3">
                                    <span class="text-[10px] font-semibold text-orange-700 bg-orange-100 px-2 py-1 rounded-full">
                                        <i class="fas fa-clock mr-1"></i>Planifié
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center" title="Modifier">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                        <button class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center" title="Annuler">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <footer class="mt-8 text-center text-xs text-gray-400 border-t border-gray-100 pt-6">
            &copy; 2026 NDIGITMARKET · Administration
        </footer>
    </main>

    <!-- MODAL : PANNEAU NOTIFICATIONS (cloche) -->
    <div id="notifPanel" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" id="notifPanelOverlay"></div>
        <div class="absolute top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#0EA486]/5 to-transparent">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-bell text-[#0EA486]"></i> Notifications
                    </h3>
                    <p class="text-xs text-gray-400">Toutes vos notifications</p>
                </div>
                <button id="closeNotifPanelBtn" class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="border-b border-gray-100 px-4 py-2 flex gap-2">
                <button class="notif-filter-btn active px-3 py-1.5 text-xs font-semibold text-[#0EA486] bg-[#0EA486]/10 rounded-lg">Toutes</button>
                <button class="notif-filter-btn px-3 py-1.5 text-xs font-medium text-gray-500 hover:bg-gray-100 rounded-lg">Non lues</button>
                <button class="notif-filter-btn px-3 py-1.5 text-xs font-medium text-gray-500 hover:bg-gray-100 rounded-lg">Vendeurs</button>
                <button class="notif-filter-btn px-3 py-1.5 text-xs font-medium text-gray-500 hover:bg-gray-100 rounded-lg">Commandes</button>
                <button class="notif-filter-btn px-3 py-1.5 text-xs font-medium text-gray-500 hover:bg-gray-100 rounded-lg">Sécurité</button>
            </div>

            <div class="flex-1 overflow-y-auto divide-y divide-gray-100">
                <!-- Notification exemple -->
                <div class="notif-item p-4 hover:bg-gray-50/50 transition cursor-pointer bg-blue-50/30 border-l-4 border-blue-500">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center text-yellow-600 flex-shrink-0">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h5 class="text-sm font-semibold text-[#0F172A]">Nouvelle demande vendeur</h5>
                            <p class="text-xs text-gray-500 mt-0.5">...</p>
                            <span class="text-[10px] text-gray-400 mt-1 block">...</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                <button class="w-full px-4 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center justify-center gap-2 transition">
                    <i class="fas fa-check-double"></i> Tout marquer comme lu
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL : SUPPRIMER NOTIFICATION -->
<div id="deleteNotifModal" class="fixed inset-0 z-[70] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div>
                <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                    <i class="fas fa-trash text-red-500"></i> Supprimer la notification
                </h3>
                <p class="text-xs text-gray-400">Action irréversible</p>
            </div>
            <button class="closeDeleteNotifBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div class="bg-red-50 rounded-xl p-4 border border-red-100">
                <p class="text-sm text-red-700">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Attention :</strong> Cette action est irréversible. La notification sera définitivement supprimée.
                </p>
            </div>

            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                <p class="text-xs text-gray-500 mb-1">Notification à supprimer</p>
                <p id="deleteNotifTitle" class="text-sm font-bold text-[#0F172A]">---</p>
                <p id="deleteNotifId" class="text-[11px] text-gray-400 mt-1">ID: ---</p>
            </div>

            <div>
                <label class="text-xs font-semibold text-gray-600 mb-1 block">Tapez "SUPPRIMER" pour confirmer</label>
                <input type="text" id="deleteNotifConfirmInput" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:bg-white" placeholder="SUPPRIMER">
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
            <button class="closeDeleteNotifBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                Annuler
            </button>
            <button id="confirmDeleteNotifBtn" class="px-5 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition opacity-50 cursor-not-allowed" disabled>
                <i class="fas fa-trash"></i> Supprimer définitivement
            </button>
        </div>
    </div>
</div>

    <!-- MODAL : DÉTAIL NOTIFICATION -->
<div id="notifDetailModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[92vh] overflow-hidden flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#0EA486]/5 to-transparent">
            <div>
                <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                    <i class="fas fa-bell text-[#0EA486]"></i> Détail de la notification
                </h3>
                <p class="text-xs text-gray-400">Informations complètes</p>
            </div>
            <button class="closeNotifDetailBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="overflow-y-auto p-6 space-y-5">
            <!-- En-tête -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-5 border border-blue-100">
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 bg-yellow-100 rounded-2xl flex items-center justify-center text-yellow-600 flex-shrink-0">
                        <i class="fas fa-user-plus text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h4 id="detailNotifTitle" class="text-lg font-bold text-[#0F172A]">Nouvelle demande vendeur</h4>
                        <p id="detailNotifDate" class="text-xs text-gray-500 mt-1">Reçue le ...</p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <span id="detailNotifBadge" class="text-[10px] font-semibold text-yellow-700 bg-yellow-100 px-2 py-1 rounded-full">
                                <i class="fas fa-hourglass-half mr-1"></i>En attente
                            </span>
                            <span id="detailNotifId" class="text-[10px] font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-full">
                                ID: ...
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 🔥 INFORMATIONS COMPLÈTES -->
            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                    <i class="fas fa-info-circle text-[#0EA486]"></i> Informations
                </h5>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-500">Type</span>
                        <span id="detailNotifType" class="font-medium text-[#0F172A]">---</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-500">Source</span>
                        <span id="detailNotifSource" class="font-medium text-[#0F172A]">---</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-500">Priorité</span>
                        <span id="detailNotifPriorite" class="font-semibold text-yellow-600">---</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-500">Statut</span>
                        <span id="detailNotifStatut" class="font-medium text-blue-600">Non lue</span>
                    </div>
                </div>
            </div>

            <!-- Message -->
            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                <h5 class="text-xs font-semibold text-gray-400 uppercase mb-2 flex items-center gap-2">
                    <i class="fas fa-align-left text-[#0EA486]"></i> Message
                </h5>
                <p id="detailNotifMessage" class="text-sm text-gray-600 leading-relaxed">...</p>
            </div>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-wrap items-center justify-end gap-2">
            <button class="closeNotifDetailBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                Fermer
            </button>
            <button id="detailTraiterBtn" class="px-4 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                <i class="fas fa-arrow-right"></i> Traiter
            </button>
        </div>
    </div>
</div>

    <!-- MODAL : PARAMÈTRES NOTIFICATIONS -->
    <div id="notifSettingsModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-cog text-[#0EA486]"></i> Paramètres de notifications
                    </h3>
                    <p class="text-xs text-gray-400">Configurez vos préférences</p>
                </div>
                <button class="closeNotifSettingsBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="overflow-y-auto p-6 space-y-4">
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-bell text-[#0EA486]"></i> Notifications à recevoir
                    </h5>
                    <div class="space-y-2">
                        <label class="flex items-center justify-between p-3 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-user-plus text-yellow-600 w-5"></i>
                                <span class="text-sm text-gray-700">Nouvelles demandes vendeur</span>
                            </div>
                            <input type="checkbox" checked class="w-4 h-4 rounded border-gray-300 text-[#0EA486]">
                        </label>
                        <label class="flex items-center justify-between p-3 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-file-code text-indigo-600 w-5"></i>
                                <span class="text-sm text-gray-700">Produits soumis à modération</span>
                            </div>
                            <input type="checkbox" checked class="w-4 h-4 rounded border-gray-300 text-[#0EA486]">
                        </label>
                        <label class="flex items-center justify-between p-3 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-shopping-cart text-emerald-600 w-5"></i>
                                <span class="text-sm text-gray-700">Commandes au-dessus du seuil</span>
                            </div>
                            <input type="checkbox" checked class="w-4 h-4 rounded border-gray-300 text-[#0EA486]">
                        </label>
                        <label class="flex items-center justify-between p-3 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-exclamation-triangle text-red-600 w-5"></i>
                                <span class="text-sm text-gray-700">Commandes problématiques</span>
                            </div>
                            <input type="checkbox" checked class="w-4 h-4 rounded border-gray-300 text-[#0EA486]">
                        </label>
                        <label class="flex items-center justify-between p-3 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-envelope text-blue-600 w-5"></i>
                                <span class="text-sm text-gray-700">Messages support</span>
                            </div>
                            <input type="checkbox" checked class="w-4 h-4 rounded border-gray-300 text-[#0EA486]">
                        </label>
                        <label class="flex items-center justify-between p-3 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-shield-alt text-purple-600 w-5"></i>
                                <span class="text-sm text-gray-700">Tentatives de connexion suspectes</span>
                            </div>
                            <input type="checkbox" checked class="w-4 h-4 rounded border-gray-300 text-[#0EA486]">
                        </label>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-sliders-h text-[#0EA486]"></i> Seuils et configurations
                    </h5>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Seuil de commande importante (FCFA)</label>
                            <input type="number" value="100000" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                            <p class="text-[10px] text-gray-400 mt-1">Notification pour toute commande supérieure à ce montant</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Notifications par email</label>
                            <select class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                                <option>Toutes les notifications</option>
                                <option>Uniquement les urgentes</option>
                                <option>Résumé quotidien</option>
                                <option>Désactivé</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                <button class="closeNotifSettingsBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                    Annuler
                </button>
                <button class="saveNotifSettingsBtn px-5 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL : APERÇU EMAIL -->
    <div id="emailPreviewModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-eye text-[#0EA486]"></i> Aperçu de l'email
                    </h3>
                    <p class="text-xs text-gray-400">Visualisation du template</p>
                </div>
                <button class="closeEmailPreviewBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="overflow-y-auto p-6 space-y-4">
                <!-- Toggle desktop/mobile -->
                <div class="flex justify-center gap-2">
                    <button class="preview-device-btn active px-3 py-1.5 text-xs font-semibold text-[#0EA486] bg-[#0EA486]/10 rounded-lg flex items-center gap-1">
                        <i class="fas fa-desktop"></i> Desktop
                    </button>
                    <button class="preview-device-btn px-3 py-1.5 text-xs font-medium text-gray-500 hover:bg-gray-100 rounded-lg flex items-center gap-1">
                        <i class="fas fa-mobile-alt"></i> Mobile
                    </button>
                </div>

                <!-- Preview email -->
                <div id="emailPreviewContainer" class="bg-gray-100 rounded-2xl p-6 flex justify-center">
                    <div class="bg-white rounded-xl shadow-md max-w-xl w-full overflow-hidden">
                        <!-- Header email -->
                        <div class="bg-gradient-to-r from-[#0EA486] to-[#0c8f75] px-6 py-8 text-center text-white">
                            <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                <span class="text-2xl font-bold">N</span>
                            </div>
                            <h2 class="text-xl font-bold">NDIGITMARKET</h2>
                            <p class="text-xs text-white/80 mt-1">...</p>
                        </div>

                        <!-- Body -->
                        <div class="px-6 py-8">
                            <h3 class="text-lg font-bold text-[#0F172A] mb-3">...</h3>
                            <p class="text-sm text-gray-600 leading-relaxed mb-4">...</p>
                            <p class="text-sm text-gray-600 leading-relaxed mb-6">...</p>

                            <div class="text-center">
                                <a href="#" class="inline-block px-6 py-3 bg-[#0EA486] text-white rounded-xl text-sm font-bold hover:bg-[#0c8f75] transition">
                                    ...
                                </a>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="bg-gray-50 px-6 py-4 text-center border-t border-gray-100">
                            <p class="text-[10px] text-gray-400">© 2026 NDIGITMARKET · Tous droits réservés</p>
                            <p class="text-[10px] text-gray-400 mt-1">Cet email a été envoyé automatiquement</p>
                        </div>
                    </div>
                </div>

                <!-- Variables disponibles -->
                <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                    <h5 class="text-xs font-semibold text-blue-700 mb-2 flex items-center gap-2">
                        <i class="fas fa-code"></i> Variables disponibles
                    </h5>
                    <div class="flex flex-wrap gap-1.5">
                        <code class="text-[10px] bg-white px-2 py-1 rounded border border-blue-200 text-blue-700">{user_name}</code>
                        <code class="text-[10px] bg-white px-2 py-1 rounded border border-blue-200 text-blue-700">{user_email}</code>
                        <code class="text-[10px] bg-white px-2 py-1 rounded border border-blue-200 text-blue-700">{order_id}</code>
                        <code class="text-[10px] bg-white px-2 py-1 rounded border border-blue-200 text-blue-700">{product_name}</code>
                        <code class="text-[10px] bg-white px-2 py-1 rounded border border-blue-200 text-blue-700">{download_link}</code>
                        <code class="text-[10px] bg-white px-2 py-1 rounded border border-blue-200 text-blue-700">{shop_name}</code>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL : ÉDITEUR EMAIL -->
    <div id="emailEditorModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-5xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-edit text-[#0EA486]"></i> Éditeur de template email
                    </h3>
                    <p class="text-xs text-gray-400">Modifier le contenu de l'email</p>
                </div>
                <button class="closeEmailEditorBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                    <!-- Éditeur -->
                    <form class="p-6 space-y-4 border-r border-gray-100">
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Objet de l'email <span class="text-red-500">*</span></label>
                            <input type="text" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="Ex: Bienvenue sur NDIGITMARKET !">
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Pré-header (aperçu inbox)</label>
                            <input type="text" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="Texte court affiché dans l'aperçu...">
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Titre principal <span class="text-red-500">*</span></label>
                            <input type="text" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="Ex: Bienvenue !">
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Corps de l'email <span class="text-red-500">*</span></label>
                            <div class="border border-gray-100 rounded-xl overflow-hidden">
                                <div class="bg-gray-50 px-3 py-2 border-b border-gray-100 flex items-center gap-1 text-xs text-gray-500 flex-wrap">
                                    <button type="button" class="w-7 h-7 rounded hover:bg-gray-200 flex items-center justify-center"><i class="fas fa-bold"></i></button>
                                    <button type="button" class="w-7 h-7 rounded hover:bg-gray-200 flex items-center justify-center"><i class="fas fa-italic"></i></button>
                                    <button type="button" class="w-7 h-7 rounded hover:bg-gray-200 flex items-center justify-center"><i class="fas fa-underline"></i></button>
                                    <span class="w-px h-4 bg-gray-200 mx-1"></span>
                                    <button type="button" class="w-7 h-7 rounded hover:bg-gray-200 flex items-center justify-center"><i class="fas fa-list-ul"></i></button>
                                    <button type="button" class="w-7 h-7 rounded hover:bg-gray-200 flex items-center justify-center"><i class="fas fa-list-ol"></i></button>
                                    <span class="w-px h-4 bg-gray-200 mx-1"></span>
                                    <button type="button" class="w-7 h-7 rounded hover:bg-gray-200 flex items-center justify-center"><i class="fas fa-link"></i></button>
                                    <button type="button" class="w-7 h-7 rounded hover:bg-gray-200 flex items-center justify-center"><i class="fas fa-image"></i></button>
                                    <button type="button" class="w-7 h-7 rounded hover:bg-gray-200 flex items-center justify-center"><i class="fas fa-code"></i></button>
                                    <span class="w-px h-4 bg-gray-200 mx-1"></span>
                                    <button type="button" class="px-2 h-7 rounded hover:bg-gray-200 flex items-center justify-center text-[10px]">
                                        <i class="fas fa-plus mr-1"></i> Variable
                                    </button>
                                </div>
                                <textarea rows="10" class="w-full px-3 py-2.5 text-sm focus:outline-none resize-none" placeholder="Rédigez le contenu de votre email..."></textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-semibold text-gray-600 mb-1 block">Texte du bouton CTA</label>
                                <input type="text" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="Ex: Voir mon template">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-600 mb-1 block">URL du bouton</label>
                                <input type="url" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="https://...">
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Couleur du bouton</label>
                            <div class="flex gap-2">
                                <label class="flex-1 flex items-center gap-2 p-2 bg-[#0EA486]/10 border-2 border-[#0EA486] rounded-xl cursor-pointer">
                                    <input type="radio" name="btnColor" value="#0EA486" checked class="w-4 h-4 text-[#0EA486]">
                                    <div class="w-6 h-6 rounded" style="background-color: #0EA486;"></div>
                                </label>
                                <label class="flex-1 flex items-center gap-2 p-2 bg-gray-50 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-gray-300">
                                    <input type="radio" name="btnColor" value="#3B82F6" class="w-4 h-4 text-blue-500">
                                    <div class="w-6 h-6 rounded" style="background-color: #3B82F6;"></div>
                                </label>
                                <label class="flex-1 flex items-center gap-2 p-2 bg-gray-50 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-gray-300">
                                    <input type="radio" name="btnColor" value="#8B5CF6" class="w-4 h-4 text-purple-500">
                                    <div class="w-6 h-6 rounded" style="background-color: #8B5CF6;"></div>
                                </label>
                                <label class="flex-1 flex items-center gap-2 p-2 bg-gray-50 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-gray-300">
                                    <input type="radio" name="btnColor" value="#EF4444" class="w-4 h-4 text-red-500">
                                    <div class="w-6 h-6 rounded" style="background-color: #EF4444;"></div>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                            <button type="button" class="closeEmailEditorBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                                Annuler
                            </button>
                            <button type="button" class="previewEmailBtn px-4 py-2.5 rounded-xl bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-semibold flex items-center gap-2">
                                <i class="fas fa-eye"></i> Aperçu
                            </button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </div>
                    </form>

                    <!-- Aperçu live -->
                    <div class="p-6 bg-gray-50">
                        <h5 class="text-xs font-semibold text-gray-500 uppercase mb-3 flex items-center gap-2">
                            <i class="fas fa-eye text-[#0EA486]"></i> Aperçu en temps réel
                        </h5>
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                            <div class="bg-gradient-to-r from-[#0EA486] to-[#0c8f75] px-4 py-6 text-center text-white">
                                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-2">
                                    <span class="text-lg font-bold">N</span>
                                </div>
                                <h3 class="text-base font-bold">NDIGITMARKET</h3>
                            </div>
                            <div class="px-4 py-6">
                                <h4 class="text-base font-bold text-[#0F172A] mb-2">Titre de l'email</h4>
                                <p class="text-xs text-gray-600 leading-relaxed mb-4">Contenu de l'email qui sera affiché ici en temps réel pendant la rédaction...</p>
                                <div class="text-center">
                                    <a href="#" class="inline-block px-4 py-2 bg-[#0EA486] text-white rounded-lg text-xs font-bold">
                                        Bouton CTA
                                    </a>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 text-center border-t border-gray-100">
                                <p class="text-[9px] text-gray-400">© 2026 NDIGITMARKET</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL : COMPOSER EMAIL EN MASSE -->
    <div id="composeModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-5xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#0EA486]/5 to-transparent">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-pen text-[#0EA486]"></i> Composer un email en masse
                    </h3>
                    <p class="text-xs text-gray-400">Envoyer à une large audience</p>
                </div>
                <button class="closeComposeBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-0">
                    <!-- Panneau gauche : Configuration -->
                    <div class="lg:col-span-1 p-6 border-r border-gray-100 space-y-4 bg-gray-50/50">
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-2 block">Cible <span class="text-red-500">*</span></label>
                            <div class="space-y-2">
                                <label class="flex items-center gap-2 p-2.5 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-[#0EA486] transition">
                                    <input type="radio" name="target" value="all" checked class="w-4 h-4 text-[#0EA486]">
                                    <div class="flex-1">
                                        <p class="text-xs font-semibold text-[#0F172A]">Tous les utilisateurs</p>
                                        <p class="text-[10px] text-gray-400">... contacts</p>
                                    </div>
                                </label>
                                <label class="flex items-center gap-2 p-2.5 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-[#0EA486] transition">
                                    <input type="radio" name="target" value="vendors" class="w-4 h-4 text-[#0EA486]">
                                    <div class="flex-1">
                                        <p class="text-xs font-semibold text-[#0F172A]">Tous les vendeurs</p>
                                        <p class="text-[10px] text-gray-400">... contacts</p>
                                    </div>
                                </label>
                                <label class="flex items-center gap-2 p-2.5 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-[#0EA486] transition">
                                    <input type="radio" name="target" value="buyers" class="w-4 h-4 text-[#0EA486]">
                                    <div class="flex-1">
                                        <p class="text-xs font-semibold text-[#0F172A]">Acheteurs actifs</p>
                                        <p class="text-[10px] text-gray-400">... contacts</p>
                                    </div>
                                </label>
                                <label class="flex items-center gap-2 p-2.5 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-[#0EA486] transition">
                                    <input type="radio" name="target" value="custom" class="w-4 h-4 text-[#0EA486]">
                                    <div class="flex-1">
                                        <p class="text-xs font-semibold text-[#0F172A]">Liste personnalisée</p>
                                        <p class="text-[10px] text-gray-400">Sélection manuelle</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Planification</label>
                            <select class="w-full px-3 py-2.5 bg-white border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                                <option>Envoyer immédiatement</option>
                                <option>Planifier pour plus tard</option>
                            </select>
                        </div>

                        <div id="scheduleSection" class="hidden">
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Date et heure d'envoi</label>
                            <input type="datetime-local" class="w-full px-3 py-2.5 bg-white border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                        </div>

                        <div class="bg-blue-50 rounded-xl p-3 border border-blue-100">
                            <p class="text-[10px] text-blue-700">
                                <i class="fas fa-info-circle mr-1"></i>
                                <strong>Récapitulatif :</strong><br>
                                <span class="font-semibold">...</span> destinataires · <span class="font-semibold">...</span>
                            </p>
                        </div>
                    </div>

                    <!-- Panneau droit : Contenu -->
                    <div class="lg:col-span-2 p-6 space-y-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Nom de la campagne <span class="text-red-500">*</span></label>
                            <input type="text" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="Ex: Promo Black Friday 2026">
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Objet <span class="text-red-500">*</span></label>
                            <input type="text" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="Ex: Découvrez nos nouvelles offres !">
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Contenu <span class="text-red-500">*</span></label>
                            <div class="border border-gray-100 rounded-xl overflow-hidden">
                                <div class="bg-gray-50 px-3 py-2 border-b border-gray-100 flex items-center gap-1 text-xs text-gray-500 flex-wrap">
                                    <button type="button" class="w-7 h-7 rounded hover:bg-gray-200 flex items-center justify-center"><i class="fas fa-bold"></i></button>
                                    <button type="button" class="w-7 h-7 rounded hover:bg-gray-200 flex items-center justify-center"><i class="fas fa-italic"></i></button>
                                    <button type="button" class="w-7 h-7 rounded hover:bg-gray-200 flex items-center justify-center"><i class="fas fa-underline"></i></button>
                                    <span class="w-px h-4 bg-gray-200 mx-1"></span>
                                    <button type="button" class="w-7 h-7 rounded hover:bg-gray-200 flex items-center justify-center"><i class="fas fa-list-ul"></i></button>
                                    <button type="button" class="w-7 h-7 rounded hover:bg-gray-200 flex items-center justify-center"><i class="fas fa-link"></i></button>
                                    <button type="button" class="w-7 h-7 rounded hover:bg-gray-200 flex items-center justify-center"><i class="fas fa-image"></i></button>
                                    <span class="w-px h-4 bg-gray-200 mx-1"></span>
                                    <button type="button" class="px-2 h-7 rounded hover:bg-gray-200 flex items-center justify-center text-[10px]">
                                        <i class="fas fa-plus mr-1"></i> Variable
                                    </button>
                                </div>
                                <textarea rows="10" class="w-full px-3 py-2.5 text-sm focus:outline-none resize-none" placeholder="Rédigez le contenu de votre email..."></textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-semibold text-gray-600 mb-1 block">Texte du bouton CTA</label>
                                <input type="text" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="Ex: Découvrir">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-600 mb-1 block">URL du bouton</label>
                                <input type="url" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="https://...">
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                            <button type="button" class="closeComposeBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                                Annuler
                            </button>
                            <button type="button" class="previewComposeBtn px-4 py-2.5 rounded-xl bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-semibold flex items-center gap-2">
                                <i class="fas fa-eye"></i> Aperçu
                            </button>
                            <button type="button" class="testSendBtn px-4 py-2.5 rounded-xl bg-orange-100 hover:bg-orange-200 text-orange-700 text-sm font-semibold flex items-center gap-2">
                                <i class="fas fa-paper-plane"></i> Test
                            </button>
                            <button type="submit" class="sendMassEmailBtn px-5 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm">
                                <i class="fas fa-paper-plane"></i> Envoyer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL : CONFIRMATION ENVOI -->
    <div id="confirmSendModal" class="fixed inset-0 z-[70] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-paper-plane text-[#0EA486]"></i> Confirmer l'envoi
                    </h3>
                    <p class="text-xs text-gray-400">Vérifiez avant d'envoyer</p>
                </div>
                <button class="closeConfirmSendBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="bg-orange-50 rounded-xl p-4 border border-orange-100">
                    <p class="text-sm text-orange-700">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Vous êtes sur le point d'envoyer cet email à <strong>...</strong> destinataires. Cette action est irréversible.
                    </p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 space-y-2">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Campagne</span>
                        <span class="font-semibold text-[#0F172A]">...</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Destinataires</span>
                        <span class="font-semibold text-[#0F172A]">...</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Date d'envoi</span>
                        <span class="font-semibold text-[#0F172A]">Immédiat</span>
                    </div>
                </div>

                <label class="flex items-start gap-2 text-sm text-gray-700 cursor-pointer">
                    <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-[#0EA486] focus:ring-[#0EA486] mt-0.5" required>
                    <span>Je confirme vouloir envoyer cet email à tous les destinataires sélectionnés</span>
                </label>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                <button class="closeConfirmSendBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                    Annuler
                </button>
                <button class="finalSendBtn px-5 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                    <i class="fas fa-paper-plane"></i> Confirmer l'envoi
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL : STATS CAMPAGNE -->
    <div id="campaignStatsModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-chart-line text-purple-500"></i> Statistiques de la campagne
                    </h3>
                    <p class="text-xs text-gray-400">Performances détaillées</p>
                </div>
                <button class="closeCampaignStatsBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="overflow-y-auto p-6 space-y-5">
                <!-- En-tête -->
                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-5 border border-indigo-100">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div>
                            <h4 class="text-lg font-bold text-[#0F172A]">...</h4>
                            <p class="text-xs text-gray-500 mt-1">Envoyée le ...</p>
                        </div>
                        <div class="flex gap-2">
                            <button class="px-3 py-2 rounded-lg bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 text-xs font-medium flex items-center gap-2">
                                <i class="fas fa-file-csv"></i> Export CSV
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Stats cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                        <p class="text-[10px] text-blue-600 font-semibold uppercase mb-1">Envoyés</p>
                        <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl border border-emerald-100">
                        <p class="text-[10px] text-emerald-600 font-semibold uppercase mb-1">Ouverts</p>
                        <p class="text-2xl font-bold text-[#0F172A]">...</p>
                        <p class="text-[10px] text-emerald-600 mt-1">...%</p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                        <p class="text-[10px] text-purple-600 font-semibold uppercase mb-1">Cliqués</p>
                        <p class="text-2xl font-bold text-[#0F172A]">...</p>
                        <p class="text-[10px] text-purple-600 mt-1">...%</p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-red-50 to-orange-50 rounded-xl border border-red-100">
                        <p class="text-[10px] text-red-600 font-semibold uppercase mb-1">Désabonnés</p>
                        <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    </div>
                </div>

                <!-- Graphique -->
                <div class="bg-white rounded-2xl p-5 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-chart-bar text-[#0EA486]"></i> Évolution sur 7 jours
                    </h5>
                    <div class="flex items-end justify-between gap-2 h-40">
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <div class="w-full bg-emerald-200 rounded-t-lg" style="height: 40%;"></div>
                            <div class="w-full bg-purple-200 rounded-t-lg" style="height: 15%;"></div>
                            <span class="text-[10px] text-gray-400">J1</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <div class="w-full bg-emerald-300 rounded-t-lg" style="height: 60%;"></div>
                            <div class="w-full bg-purple-300 rounded-t-lg" style="height: 25%;"></div>
                            <span class="text-[10px] text-gray-400">J2</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <div class="w-full bg-emerald-400 rounded-t-lg" style="height: 75%;"></div>
                            <div class="w-full bg-purple-400 rounded-t-lg" style="height: 35%;"></div>
                            <span class="text-[10px] text-gray-400">J3</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <div class="w-full bg-emerald-500 rounded-t-lg" style="height: 85%;"></div>
                            <div class="w-full bg-purple-500 rounded-t-lg" style="height: 40%;"></div>
                            <span class="text-[10px] text-gray-400">J4</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <div class="w-full bg-emerald-600 rounded-t-lg" style="height: 90%;"></div>
                            <div class="w-full bg-purple-600 rounded-t-lg" style="height: 45%;"></div>
                            <span class="text-[10px] text-gray-400">J5</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <div class="w-full bg-emerald-700 rounded-t-lg" style="height: 95%;"></div>
                            <div class="w-full bg-purple-700 rounded-t-lg" style="height: 48%;"></div>
                            <span class="text-[10px] text-gray-400">J6</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <div class="w-full bg-[#0EA486] rounded-t-lg" style="height: 100%;"></div>
                            <div class="w-full bg-purple-800 rounded-t-lg" style="height: 50%;"></div>
                            <span class="text-[10px] text-gray-400 font-semibold">J7</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 mt-3 text-xs">
                        <span class="flex items-center gap-2"><span class="w-3 h-3 bg-emerald-500 rounded"></span> Ouvertures</span>
                        <span class="flex items-center gap-2"><span class="w-3 h-3 bg-purple-500 rounded"></span> Clics</span>
                    </div>
                </div>

                <!-- Top clicks -->
                <div class="bg-white rounded-2xl p-5 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-link text-[#0EA486]"></i> Liens les plus cliqués
                    </h5>
                    <div class="space-y-2">
                        <div class="flex items-center gap-3 p-2 bg-gray-50 rounded-lg">
                            <span class="text-xs font-mono text-gray-600 flex-1 truncate">...</span>
                            <span class="text-xs font-semibold text-[#0F172A]">... clics</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL : TOGGLE EMAIL TEMPLATE -->
    <div id="toggleEmailModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 id="toggleEmailTitle" class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-toggle-on text-yellow-500"></i> Changer le statut
                    </h3>
                    <p class="text-xs text-gray-400">Activer ou désactiver le template</p>
                </div>
                <button class="closeToggleEmailBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div id="toggleEmailInfo" class="bg-yellow-50 rounded-xl p-4 border border-yellow-100">
                    <p class="text-sm text-yellow-700">
                        <i class="fas fa-info-circle mr-2"></i>
                        <span id="toggleEmailInfoText">Les emails de ce type ne seront plus envoyés automatiquement.</span>
                    </p>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                <button class="closeToggleEmailBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                    Annuler
                </button>
                <button id="confirmToggleEmailBtn" class="px-5 py-2.5 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                    <i class="fas fa-check"></i> Confirmer
                </button>
            </div>
        </div>
    </div>

    <!-- TOAST NOTIFICATION -->
    <div id="toast" class="fixed bottom-6 right-6 z-[100] hidden">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4 flex items-center gap-3 min-w-[280px]">
            <div id="toastIcon" class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-emerald-100 text-emerald-600">
                <i class="fas fa-check"></i>
            </div>
            <div class="flex-1">
                <p id="toastTitle" class="text-sm font-semibold text-[#0F172A]">Succès</p>
                <p id="toastMessage" class="text-xs text-gray-500">Action effectuée</p>
            </div>
            <button class="closeToast text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
    </div>

    <script>
        // Sidebar mobile
        (function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const hamburger = document.getElementById('hamburgerBtn');

            function openSidebar() {
                sidebar.classList.add('open');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
            function closeSidebar() {
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }
            hamburger.addEventListener('click', function(e) {
                e.stopPropagation();
                sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
            });
            overlay.addEventListener('click', closeSidebar);
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('open')) closeSidebar();
            });
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768 && sidebar.classList.contains('open')) closeSidebar();
            });
        })();

        // Toast notification
        function showToast(title, message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastIcon = document.getElementById('toastIcon');
            const toastTitle = document.getElementById('toastTitle');
            const toastMessage = document.getElementById('toastMessage');

            toastTitle.textContent = title;
            toastMessage.textContent = message;

            const styles = {
                success: { bg: 'bg-emerald-100', color: 'text-emerald-600', icon: 'fa-check' },
                error: { bg: 'bg-red-100', color: 'text-red-600', icon: 'fa-times' },
                warning: { bg: 'bg-yellow-100', color: 'text-yellow-600', icon: 'fa-exclamation' },
                info: { bg: 'bg-blue-100', color: 'text-blue-600', icon: 'fa-info' }
            };

            const style = styles[type] || styles.success;
            toastIcon.className = `w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 ${style.bg} ${style.color}`;
            toastIcon.innerHTML = `<i class="fas ${style.icon}"></i>`;

            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3500);
        }

        document.querySelector('.closeToast').addEventListener('click', function() {
            document.getElementById('toast').classList.add('hidden');
        });

        // Helper pour modals
        function setupModal(modalId, openSelector, closeSelector) {
            const modal = document.getElementById(modalId);
            const openBtns = document.querySelectorAll(openSelector);
            const closeBtns = document.querySelectorAll(closeSelector);

            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }

            openBtns.forEach(btn => btn.addEventListener('click', openModal));
            closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });

            return { openModal, closeModal };
        }

        // Panneau notifications (cloche)
        (function() {
            const panel = document.getElementById('notifPanel');
            const overlay = document.getElementById('notifPanelOverlay');
            const openBtns = document.querySelectorAll('#openNotifCenterBtn');
            const closeBtn = document.getElementById('closeNotifPanelBtn');

            function openPanel() {
                panel.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
            function closePanel() {
                panel.classList.add('hidden');
                document.body.style.overflow = '';
            }

            openBtns.forEach(btn => btn.addEventListener('click', openPanel));
            closeBtn.addEventListener('click', closePanel);
            overlay.addEventListener('click', closePanel);
        })();

        // Filtres panneau notifications
        (function() {
            const filterBtns = document.querySelectorAll('.notif-filter-btn');
            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    filterBtns.forEach(b => {
                        b.classList.remove('active', 'text-[#0EA486]', 'bg-[#0EA486]/10', 'font-semibold');
                        b.classList.add('text-gray-500', 'font-medium');
                    });
                    this.classList.add('active', 'text-[#0EA486]', 'bg-[#0EA486]/10', 'font-semibold');
                    this.classList.remove('text-gray-500', 'font-medium');
                });
            });
        })();

        // Modal détail notification
        setupModal('notifDetailModal', '.openNotifDetailBtn', '.closeNotifDetailBtn');

        // Modal paramètres notifications
        (function() {
            const modal = document.getElementById('notifSettingsModal');
            const openBtn = document.getElementById('openNotifSettingsBtn');
            const closeBtns = document.querySelectorAll('.closeNotifSettingsBtn');
            const saveBtn = document.querySelector('.saveNotifSettingsBtn');

            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }

            openBtn.addEventListener('click', openModal);
            closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });

            saveBtn.addEventListener('click', function() {
                closeModal();
                showToast('Paramètres enregistrés', 'Vos préférences ont été sauvegardées', 'success');
            });
        })();

        // Marquer tout comme lu
        document.getElementById('markAllReadBtn').addEventListener('click', function() {
            document.querySelectorAll('.notif-item').forEach(item => {
                item.classList.remove('bg-blue-50/30', 'border-l-4', 'border-blue-500', 'bg-red-50/30', 'border-red-500');
            });
            showToast('Notifications marquées', 'Toutes les notifications ont été marquées comme lues', 'success');
        });

        // Modal aperçu email
        setupModal('emailPreviewModal', '.openEmailPreviewBtn, .previewEmailBtn, .previewComposeBtn', '.closeEmailPreviewBtn');

        // Modal éditeur email
        setupModal('emailEditorModal', '.openEmailEditorBtn', '.closeEmailEditorBtn');

        // Modal toggle email
        (function() {
            const modal = document.getElementById('toggleEmailModal');
            const openBtns = document.querySelectorAll('.toggleEmailBtn');
            const closeBtns = document.querySelectorAll('.closeToggleEmailBtn');
            const confirmBtn = document.getElementById('confirmToggleEmailBtn');
            const title = document.getElementById('toggleEmailTitle');
            const infoText = document.getElementById('toggleEmailInfoText');
            const info = document.getElementById('toggleEmailInfo');

            function openModal() {
                const btn = this;
                const isOn = btn.querySelector('.fa-toggle-on');
                
                if (isOn) {
                    title.innerHTML = '<i class="fas fa-toggle-off text-gray-500"></i> Désactiver le template';
                    infoText.textContent = 'Les emails de ce type ne seront plus envoyés automatiquement.';
                    info.className = 'bg-yellow-50 rounded-xl p-4 border border-yellow-100';
                    confirmBtn.className = 'px-5 py-2.5 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition';
                    confirmBtn.innerHTML = '<i class="fas fa-pause"></i> Désactiver';
                } else {
                    title.innerHTML = '<i class="fas fa-toggle-on text-emerald-500"></i> Activer le template';
                    infoText.textContent = 'Les emails de ce type seront à nouveau envoyés automatiquement.';
                    info.className = 'bg-emerald-50 rounded-xl p-4 border border-emerald-100';
                    confirmBtn.className = 'px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition';
                    confirmBtn.innerHTML = '<i class="fas fa-check"></i> Activer';
                }

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }

            openBtns.forEach(btn => btn.addEventListener('click', openModal));
            closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });

            confirmBtn.addEventListener('click', function() {
                closeModal();
                showToast('Statut modifié', 'Le statut du template a été mis à jour', 'success');
            });
        })();

        // Modal composer email en masse
        (function() {
            const modal = document.getElementById('composeModal');
            const openBtn = document.getElementById('openComposeBtn');
            const closeBtns = document.querySelectorAll('.closeComposeBtn');

            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }

            openBtn.addEventListener('click', openModal);
            closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });

            // Toggle planification
            const scheduleSelect = modal.querySelector('select');
            const scheduleSection = document.getElementById('scheduleSection');
            scheduleSelect.addEventListener('change', function() {
                if (this.value === 'Planifier pour plus tard') {
                    scheduleSection.classList.remove('hidden');
                } else {
                    scheduleSection.classList.add('hidden');
                }
            });
        })();

        // Modal confirmation envoi
        (function() {
            const modal = document.getElementById('confirmSendModal');
            const openBtn = document.querySelector('.sendMassEmailBtn');
            const closeBtns = document.querySelectorAll('.closeConfirmSendBtn');
            const finalBtn = document.querySelector('.finalSendBtn');

            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            openBtn.addEventListener('click', openModal);
            closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });

            finalBtn.addEventListener('click', function() {
                closeModal();
                document.getElementById('composeModal').classList.add('hidden');
                document.getElementById('composeModal').classList.remove('flex');
                document.body.style.overflow = '';
                showToast('Email envoyé', 'Votre campagne a été programmée avec succès', 'success');
            });
        })();

        // Test send
        document.querySelector('.testSendBtn').addEventListener('click', function() {
            showToast('Email de test envoyé', 'Un email de test a été envoyé à votre adresse', 'info');
        });

        // Modal stats campagne
        setupModal('campaignStatsModal', '.openCampaignStatsBtn', '.closeCampaignStatsBtn');

        // ESC pour fermer tous les modals
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('[id$="Modal"]:not(.hidden)').forEach(modal => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                });
                document.getElementById('notifPanel').classList.add('hidden');
                document.body.style.overflow = '';
            }
        });








// ============================================
// NOTIFICATIONS - LISTE & ACTIONS (COMPLET)
// ============================================
(function() {
    'use strict';

    let currentPage = 1;
    const limit = 10;
    let currentFilter = 'all';
    let isLoading = false;
    let hasMore = true;
    let totalNotifs = 0;
    let deleteNotifId = null;

    const container = document.getElementById('notificationsList');
    const filterSelect = document.getElementById('notifFilter');
    const notifCount = document.getElementById('notifCount');
    const loadMoreBtn = document.getElementById('loadMoreNotif');

    // ============================================
    // CHARGEMENT DES NOTIFICATIONS
    // ============================================
    function loadNotifications(reset = true) {
        if (isLoading) return;
        if (!reset && !hasMore) {
            loadMoreBtn.style.display = 'none';
            return;
        }

        isLoading = true;
        if (reset) {
            currentPage = 1;
            hasMore = true;
            container.innerHTML = `
                <div class="text-center py-12">
                    <i class="fas fa-spinner fa-spin text-2xl text-[#0EA486] mb-2"></i>
                    <p class="text-gray-400 text-sm">Chargement...</p>
                </div>
            `;
        }

        const params = new URLSearchParams({
            page: currentPage,
            limit: limit,
            filter: currentFilter
        });

        fetch('api.php?url=notifications_list&' + params.toString())
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const notifications = data.data || [];
                    totalNotifs = data.total || 0;
                    
                    if (reset) {
                        renderNotifications(notifications);
                    } else {
                        appendNotifications(notifications);
                    }
                    
                    const loadedCount = container.querySelectorAll('.notif-item').length;
                    hasMore = loadedCount < totalNotifs;
                    
                    updateCount(loadedCount, totalNotifs);
                    
                    loadMoreBtn.style.display = hasMore ? 'inline-flex' : 'none';
                    if (hasMore) {
                        loadMoreBtn.innerHTML = `Voir plus (${totalNotifs - loadedCount} restantes) <i class="fas fa-arrow-down text-[10px]"></i>`;
                    }
                    
                    if (data.stats) {
                        updateStats(data.stats);
                    }
                } else {
                    showError(data.error || 'Erreur de chargement');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showError('Erreur de connexion au serveur');
            })
            .finally(() => {
                isLoading = false;
            });
    }

    // ============================================
    // RENDU DES NOTIFICATIONS
    // ============================================
    function renderNotifications(notifications) {
        if (!notifications || notifications.length === 0) {
            container.innerHTML = `
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-400 text-sm">Aucune notification</p>
                </div>
            `;
            return;
        }

        container.innerHTML = notifications.map(notif => createNotifHTML(notif)).join('');
        attachEvents();
        updateCounters();
    }

    function appendNotifications(notifications) {
        if (!notifications || notifications.length === 0) return;
        const html = notifications.map(notif => createNotifHTML(notif)).join('');
        container.insertAdjacentHTML('beforeend', html);
        attachEvents();
        updateCounters();
    }

    function createNotifHTML(notif) {
        const config = getTypeConfig(notif.type);
        const isRead = notif.est_lu == 1;
        const readClass = isRead ? '' : 'bg-blue-50/30 border-l-4 ' + config.border;
        const dateFormatted = formatDate(notif.created_at);

        return `
            <div class="notif-item p-4 hover:bg-gray-50/50 transition cursor-pointer ${readClass}" 
                 data-id="${notif.id}"
                 data-type="${notif.type}"
                 data-lu="${notif.est_lu}"
                 data-priorite="${notif.priorite || 'medium'}">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 ${config.bg} rounded-xl flex items-center justify-center ${config.text} flex-shrink-0">
                        <i class="fas ${config.icon}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-start justify-between gap-2 mb-1">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <h5 class="text-sm font-semibold text-[#0F172A]">${escapeHtml(notif.title)}</h5>
                                    ${notif.priorite === 'high' ? '<span class="text-[8px] font-bold text-red-600 bg-red-100 px-1.5 py-0.5 rounded-full">URGENT</span>' : ''}
                                </div>
                                <p class="text-xs text-gray-500 mt-0.5">${escapeHtml(notif.message)}</p>
                            </div>
                            <span class="text-[10px] text-gray-400 whitespace-nowrap flex-shrink-0">${dateFormatted}</span>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 mt-2">
                            <span class="text-[10px] font-semibold ${config.badge_class} px-2 py-0.5 rounded-full">
                                <i class="fas fa-circle text-[6px] mr-1"></i>${config.badge}
                            </span>
                            ${!isRead ? '<span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full"><i class="fas fa-circle text-[6px] mr-1"></i>Nouveau</span>' : ''}
                            <button class="openNotifDetailBtn text-[10px] font-semibold text-[#0EA486] hover:underline flex items-center gap-1" 
                                    data-id="${notif.id}">
                                <i class="fas fa-eye"></i> Voir détail
                            </button>
                            ${!isRead ? `<button class="markReadBtn text-[10px] font-semibold text-gray-500 hover:text-gray-700 flex items-center gap-1" 
                                    data-id="${notif.id}">
                                <i class="fas fa-check"></i> Marquer lu
                            </button>` : ''}
                            <button class="deleteNotifBtn text-[10px] font-semibold text-red-500 hover:text-red-700 flex items-center gap-1" 
                                    data-id="${notif.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    function getTypeConfig(type) {
        const configs = {
            'vendor_request': {
                bg: 'bg-yellow-100', text: 'text-yellow-600', icon: 'fa-user-plus',
                border: 'border-yellow-500', badge: 'En attente', badge_class: 'text-yellow-700 bg-yellow-100'
            },
            'product_moderation': {
                bg: 'bg-indigo-100', text: 'text-indigo-600', icon: 'fa-file-code',
                border: 'border-indigo-500', badge: 'À modérer', badge_class: 'text-indigo-700 bg-indigo-100'
            },
            'order_high': {
                bg: 'bg-emerald-100', text: 'text-emerald-600', icon: 'fa-shopping-cart',
                border: 'border-emerald-500', badge: 'Commande importante', badge_class: 'text-emerald-700 bg-emerald-100'
            },
            'order_problem': {
                bg: 'bg-red-100', text: 'text-red-600', icon: 'fa-exclamation-triangle',
                border: 'border-red-500', badge: 'Problème', badge_class: 'text-red-700 bg-red-100'
            },
            'support_message': {
                bg: 'bg-blue-100', text: 'text-blue-600', icon: 'fa-envelope',
                border: 'border-blue-500', badge: 'Support', badge_class: 'text-blue-700 bg-blue-100'
            },
            'security_alert': {
                bg: 'bg-purple-100', text: 'text-purple-600', icon: 'fa-shield-alt',
                border: 'border-purple-500', badge: 'Alerte sécurité', badge_class: 'text-purple-700 bg-purple-100'
            }
        };
        return configs[type] || {
            bg: 'bg-gray-100', text: 'text-gray-600', icon: 'fa-bell',
            border: 'border-gray-500', badge: 'Notification', badge_class: 'text-gray-700 bg-gray-100'
        };
    }

    // ============================================
    // COMPTEURS ET STATS
    // ============================================
    function updateCount(loaded, total) {
        if (notifCount) {
            notifCount.textContent = loaded + ' / ' + total + ' notification(s)';
        }
    }

    function updateStats(stats) {
        const totalNonLues = document.querySelector('.bg-red-50 + .text-2xl');
        if (totalNonLues) totalNonLues.textContent = stats.unread || 0;
        
        const demandesVendeur = document.querySelectorAll('.bg-yellow-50 + .text-2xl');
        if (demandesVendeur.length > 0) demandesVendeur[0].textContent = stats.vendor_request || 0;
        
        const commandes = document.querySelectorAll('.bg-blue-50 + .text-2xl');
        if (commandes.length > 0) commandes[0].textContent = (stats.order_high || 0) + (stats.order_problem || 0);
        
        const alertesSecurite = document.querySelectorAll('.bg-purple-50 + .text-2xl');
        if (alertesSecurite.length > 0) alertesSecurite[0].textContent = stats.security_alert || 0;
    }

    function updateCounters() {
        const totalItems = container.querySelectorAll('.notif-item').length;
        const unreadItems = container.querySelectorAll('.notif-item[data-lu="0"]').length;
        
        if (notifCount) {
            notifCount.textContent = totalItems + ' notification(s)';
        }
        
        const bellBadge = document.querySelector('#openNotifCenterBtn .absolute');
        if (bellBadge) {
            bellBadge.textContent = unreadItems > 0 ? unreadItems : '';
            bellBadge.style.display = unreadItems > 0 ? 'flex' : 'none';
        }
        
        const unreadStat = document.querySelector('.bg-red-50 + .text-2xl');
        if (unreadStat) unreadStat.textContent = unreadItems;
    }

    // ============================================
    // FORMATAGE
    // ============================================
    function formatDate(dateStr) {
        if (!dateStr) return '---';
        const date = new Date(dateStr);
        const now = new Date();
        const diff = Math.floor((now - date) / 1000);

        if (diff < 60) return 'À l\'instant';
        if (diff < 3600) return 'Il y a ' + Math.floor(diff / 60) + ' min';
        if (diff < 86400) return 'Il y a ' + Math.floor(diff / 3600) + ' h';
        if (diff < 604800) return 'Il y a ' + Math.floor(diff / 86400) + ' j';
        return date.toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    }

    function escapeHtml(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function showError(message) {
        container.innerHTML = `
            <div class="text-center py-12 text-red-500">
                <i class="fas fa-exclamation-circle text-4xl mb-3"></i>
                <p class="text-sm">${message}</p>
                <button onclick="location.reload()" class="mt-4 px-4 py-2 bg-[#0EA486] text-white rounded-lg text-sm">
                    <i class="fas fa-redo mr-2"></i>Réessayer
                </button>
            </div>
        `;
    }

    // ============================================
    // ACTION 1 : MARQUER COMME LU
    // ============================================
    function markAsRead(id) {
        const notifItem = document.querySelector(`.notif-item[data-id="${id}"]`);
        const btn = document.querySelector(`.markReadBtn[data-id="${id}"]`);
        
        if (btn) {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;
        }

        fetch('api.php?url=notifications_mark_read', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + id
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (notifItem) {
                    notifItem.classList.remove('bg-blue-50/30', 'border-l-4');
                    notifItem.dataset.lu = '1';
                    notifItem.style.opacity = '0.7';
                    
                    const newBadge = notifItem.querySelector('.text-blue-600.bg-blue-50');
                    if (newBadge) newBadge.remove();
                    if (btn) btn.remove();
                }
                updateCounters();
                showToast('Succès', 'Notification marquée comme lue', 'success');
            } else {
                showToast('Erreur', data.error || 'Erreur', 'error');
                if (btn) {
                    btn.innerHTML = '<i class="fas fa-check"></i> Marquer lu';
                    btn.disabled = false;
                }
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showToast('Erreur', 'Erreur de connexion', 'error');
            if (btn) {
                btn.innerHTML = '<i class="fas fa-check"></i> Marquer lu';
                btn.disabled = false;
            }
        });
    }

    // ============================================
    // ACTION 2 : SUPPRIMER (AVEC MODAL)
    // ============================================
    function openDeleteNotifModal(id) {
        const notifItem = document.querySelector(`.notif-item[data-id="${id}"]`);
        if (!notifItem) {
            showToast('Erreur', 'Notification non trouvée', 'error');
            return;
        }

        const title = notifItem.querySelector('h5')?.textContent || 'Notification';
        deleteNotifId = id;

        const titleEl = document.getElementById('deleteNotifTitle');
        const idEl = document.getElementById('deleteNotifId');
        if (titleEl) titleEl.textContent = title;
        if (idEl) idEl.textContent = 'ID: #' + id;

        const input = document.getElementById('deleteNotifConfirmInput');
        const confirmBtn = document.getElementById('confirmDeleteNotifBtn');
        
        if (input) input.value = '';
        if (confirmBtn) {
            confirmBtn.disabled = true;
            confirmBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }

        const modal = document.getElementById('deleteNotifModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteNotifModal() {
        const modal = document.getElementById('deleteNotifModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        document.body.style.overflow = '';
        deleteNotifId = null;
    }

    function confirmDeleteNotification(id) {
        const notifItem = document.querySelector(`.notif-item[data-id="${id}"]`);
        
        if (notifItem) {
            notifItem.style.opacity = '0.5';
            notifItem.style.pointerEvents = 'none';
        }

        fetch('api.php?url=notifications_delete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + id
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeDeleteNotifModal();
                if (notifItem) {
                    notifItem.style.transition = 'all 0.3s ease';
                    notifItem.style.transform = 'translateX(100%)';
                    setTimeout(() => {
                        notifItem.remove();
                        updateCounters();
                        
                        const remaining = container.querySelectorAll('.notif-item').length;
                        if (remaining === 0) {
                            container.innerHTML = `
                                <div class="text-center py-12">
                                    <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                                    <p class="text-gray-400 text-sm">Aucune notification</p>
                                </div>
                            `;
                        }
                    }, 300);
                }
                showToast('Succès', 'Notification supprimée', 'success');
            } else {
                showToast('Erreur', data.error || 'Erreur', 'error');
                if (notifItem) {
                    notifItem.style.opacity = '1';
                    notifItem.style.pointerEvents = 'auto';
                }
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showToast('Erreur', 'Erreur de connexion', 'error');
            if (notifItem) {
                notifItem.style.opacity = '1';
                notifItem.style.pointerEvents = 'auto';
            }
        });
    }

    function deleteNotification(id) {
        openDeleteNotifModal(id);
    }

    // ============================================
    // ACTION 3 : VOIR DÉTAIL (CORRIGÉ)
    // ============================================
    function viewNotificationDetail(id) {
        const notifItem = document.querySelector(`.notif-item[data-id="${id}"]`);
        if (!notifItem) {
            showToast('Erreur', 'Notification non trouvée', 'error');
            return;
        }

        // Extraire les données
        const title = notifItem.querySelector('h5')?.textContent || 'Notification';
        const message = notifItem.querySelector('.text-gray-500')?.textContent || '';
        const type = notifItem.dataset.type || 'general';
        const date = notifItem.querySelector('.text-gray-400')?.textContent || '';
        const isRead = notifItem.dataset.lu === '1';
        const priorite = notifItem.dataset.priorite || 'medium';
        
        // Configurations par type
        const configs = {
            'vendor_request': { 
                icon: 'fa-user-plus', 
                color: 'text-yellow-600', 
                bg: 'bg-yellow-100', 
                badge: 'Demande vendeur',
                source: 'Utilisateur',
                label: 'Demande vendeur'
            },
            'product_moderation': { 
                icon: 'fa-file-code', 
                color: 'text-indigo-600', 
                bg: 'bg-indigo-100', 
                badge: 'Modération produit',
                source: 'Produit',
                label: 'Produit à modérer'
            },
            'order_high': { 
                icon: 'fa-shopping-cart', 
                color: 'text-emerald-600', 
                bg: 'bg-emerald-100', 
                badge: 'Commande importante',
                source: 'Commande',
                label: 'Commande'
            },
            'order_problem': { 
                icon: 'fa-exclamation-triangle', 
                color: 'text-red-600', 
                bg: 'bg-red-100', 
                badge: 'Commande problématique',
                source: 'Commande',
                label: 'Problème commande'
            },
            'support_message': { 
                icon: 'fa-envelope', 
                color: 'text-blue-600', 
                bg: 'bg-blue-100', 
                badge: 'Support',
                source: 'Ticket support',
                label: 'Message support'
            },
            'security_alert': { 
                icon: 'fa-shield-alt', 
                color: 'text-purple-600', 
                bg: 'bg-purple-100', 
                badge: 'Alerte sécurité',
                source: 'Système',
                label: 'Alerte sécurité'
            }
        };
        const config = configs[type] || { 
            icon: 'fa-bell', 
            color: 'text-gray-600', 
            bg: 'bg-gray-100', 
            badge: 'Notification',
            source: 'Système',
            label: 'Notification'
        };

        const modal = document.getElementById('notifDetailModal');
        if (!modal) {
            showToast('Info', 'Détail de la notification #' + id, 'info');
            return;
        }

        // Remplir le modal avec TOUTES les infos
        const titleEl = document.getElementById('detailNotifTitle');
        if (titleEl) titleEl.textContent = title;
        
        const dateEl = document.getElementById('detailNotifDate');
        if (dateEl) dateEl.textContent = date || 'Reçue récemment';
        
        const idEl = document.getElementById('detailNotifId');
        if (idEl) idEl.textContent = 'ID: #' + id;
        
        const badgeEl = document.getElementById('detailNotifBadge');
        if (badgeEl) {
            badgeEl.innerHTML = `<i class="fas fa-circle text-[6px] mr-1"></i>${config.badge}`;
            badgeEl.className = `text-[10px] font-semibold ${config.color.replace('text-', 'bg-').replace('text-yellow-600', 'bg-yellow-100 text-yellow-700').replace('text-indigo-600', 'bg-indigo-100 text-indigo-700').replace('text-emerald-600', 'bg-emerald-100 text-emerald-700').replace('text-red-600', 'bg-red-100 text-red-700').replace('text-blue-600', 'bg-blue-100 text-blue-700').replace('text-purple-600', 'bg-purple-100 text-purple-700').replace('text-gray-600', 'bg-gray-100 text-gray-700')} px-2 py-1 rounded-full`;
        }
        
        // Icône
        const iconContainer = modal.querySelector('.bg-gradient-to-br .w-14.h-14');
        if (iconContainer) {
            iconContainer.className = `w-14 h-14 ${config.bg} rounded-2xl flex items-center justify-center ${config.color} flex-shrink-0`;
            iconContainer.innerHTML = `<i class="fas ${config.icon} text-xl"></i>`;
        }
        
        // Informations détaillées
        const typeInfoEl = document.getElementById('detailNotifType');
        if (typeInfoEl) typeInfoEl.textContent = config.label;
        
        const sourceEl = document.getElementById('detailNotifSource');
        if (sourceEl) sourceEl.textContent = config.source;
        
        const prioriteLabels = { 'high': 'Haute', 'medium': 'Moyenne', 'low': 'Basse' };
        const prioriteColors = { 'high': 'text-red-600', 'medium': 'text-yellow-600', 'low': 'text-gray-500' };
        const prioriteEl = document.getElementById('detailNotifPriorite');
        if (prioriteEl) {
            prioriteEl.textContent = prioriteLabels[priorite] || 'Moyenne';
            prioriteEl.className = `font-semibold ${prioriteColors[priorite] || 'text-yellow-600'}`;
        }
        
        const statutEl = document.getElementById('detailNotifStatut');
        if (statutEl) {
            statutEl.textContent = isRead ? 'Lue' : 'Non lue';
            statutEl.className = `font-medium ${isRead ? 'text-gray-500' : 'text-blue-600'}`;
        }
        
        // Message
        const messageEl = document.getElementById('detailNotifMessage');
        if (messageEl) messageEl.textContent = message;

        // Bouton Traiter
        const traiterBtn = document.getElementById('detailTraiterBtn');
        if (traiterBtn) {
            traiterBtn.dataset.id = id;
            traiterBtn.onclick = function(e) {
                const notifId = this.dataset.id;
                showToast('Info', 'Traitement de la notification #' + notifId, 'info');
                // TODO: Rediriger vers la page de traitement appropriée
                closeModal('notifDetailModal');
            };
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        
        // Marquer automatiquement comme lu si non lue
        if (!isRead) {
            markAsRead(id);
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
    }

    // ============================================
    // ACTION 4 : MARQUER TOUTES COMME LUES
    // ============================================
    function markAllAsRead() {
        fetch('api.php?url=notifications_mark_all_read', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                container.querySelectorAll('.notif-item[data-lu="0"]').forEach(item => {
                    item.classList.remove('bg-blue-50/30', 'border-l-4');
                    item.dataset.lu = '1';
                    item.style.opacity = '0.7';
                    
                    const badge = item.querySelector('.text-blue-600.bg-blue-50');
                    if (badge) badge.remove();
                    
                    const btn = item.querySelector('.markReadBtn');
                    if (btn) btn.remove();
                });
                updateCounters();
                showToast('Succès', data.message || 'Toutes marquées comme lues', 'success');
            } else {
                showToast('Erreur', data.error || 'Erreur', 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showToast('Erreur', 'Erreur de connexion', 'error');
        });
    }

    // ============================================
    // ATTACHER LES ÉVÉNEMENTS
    // ============================================
    function attachEvents() {
        document.querySelectorAll('.markReadBtn').forEach(btn => {
            btn.removeEventListener('click', handleMarkRead);
            btn.addEventListener('click', handleMarkRead);
        });

        document.querySelectorAll('.deleteNotifBtn').forEach(btn => {
            btn.removeEventListener('click', handleDelete);
            btn.addEventListener('click', handleDelete);
        });

        document.querySelectorAll('.openNotifDetailBtn').forEach(btn => {
            btn.removeEventListener('click', handleDetail);
            btn.addEventListener('click', handleDetail);
        });
    }

    function handleMarkRead(e) {
        e.stopPropagation();
        const id = this.dataset.id;
        if (id) markAsRead(id);
    }

    function handleDelete(e) {
        e.stopPropagation();
        const id = this.dataset.id;
        if (id) deleteNotification(id);
    }

    function handleDetail(e) {
        e.stopPropagation();
        const id = this.dataset.id;
        if (id) viewNotificationDetail(id);
    }

    // ============================================
    // ÉVÉNEMENTS DES MODALS
    // ============================================
    document.getElementById('deleteNotifConfirmInput')?.addEventListener('input', function() {
        const confirmBtn = document.getElementById('confirmDeleteNotifBtn');
        if (this.value === 'SUPPRIMER') {
            if (confirmBtn) {
                confirmBtn.disabled = false;
                confirmBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        } else {
            if (confirmBtn) {
                confirmBtn.disabled = true;
                confirmBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }
    });

    document.getElementById('confirmDeleteNotifBtn')?.addEventListener('click', function() {
        if (!this.disabled && deleteNotifId) {
            confirmDeleteNotification(deleteNotifId);
        }
    });

    document.querySelectorAll('.closeDeleteNotifBtn').forEach(btn => {
        btn.addEventListener('click', closeDeleteNotifModal);
    });

    document.getElementById('deleteNotifModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeDeleteNotifModal();
    });

    // ============================================
    // FILTRE (UNIQUE VERSION)
    // ============================================
    filterSelect.addEventListener('change', function() {
        const filterValue = this.value;
        console.log('🔵 Filtre sélectionné:', filterValue);
        
        if (filterValue === 'all' || filterValue === 'unread') {
            // Recharger via API
            currentFilter = filterValue;
            currentPage = 1;
            loadNotifications(true);
        } else {
            // Filtre côté client
            const items = container.querySelectorAll('.notif-item');
            let visibleCount = 0;
            
            items.forEach(item => {
                const type = item.dataset.type;
                if (filterValue === type) {
                    item.style.display = '';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Mettre à jour le compteur
            if (notifCount) {
                notifCount.textContent = visibleCount + ' / ' + items.length + ' notification(s)';
            }
            
            // Afficher un message si aucun résultat
            const existingMsg = container.querySelector('.filter-no-results');
            if (visibleCount === 0 && items.length > 0) {
                if (!existingMsg) {
                    const msg = document.createElement('div');
                    msg.className = 'filter-no-results text-center py-12';
                    msg.innerHTML = `
                        <i class="fas fa-search text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-400 text-sm">Aucune notification de ce type</p>
                    `;
                    container.appendChild(msg);
                }
            } else {
                if (existingMsg) existingMsg.remove();
            }
        }
    });

    // ============================================
    // VOIR PLUS
    // ============================================
    loadMoreBtn.addEventListener('click', function() {
        if (!isLoading && hasMore) {
            currentPage++;
            loadNotifications(false);
        }
    });

    // ============================================
    // BOUTON "TOUT MARQUER COMME LU"
    // ============================================
    document.getElementById('markAllReadBtn')?.addEventListener('click', markAllAsRead);

    // ============================================
    // TOAST
    // ============================================
    function showToast(title, message, type = 'success') {
        if (typeof window.showToast === 'function') {
            window.showToast(title, message, type);
        } else {
            alert(title + ': ' + message);
        }
    }

    // ============================================
    // INITIALISATION
    // ============================================
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            loadNotifications(true);
        });
    } else {
        loadNotifications(true);
    }

    console.log('✅ Notifications prêtes');

})();

// ============================================
// EMAILS TRANSACTIONNELS - ACTIONS
// ============================================
(function() {
    'use strict';

    // ============================================
    // 1. APERÇU DU TEMPLATE
    // ============================================
    document.querySelectorAll('.openEmailPreviewBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const nom = this.dataset.nom || 'Template';
            const objet = this.dataset.objet || '';
            const contenu = this.dataset.contenu || '';
            const bouton = this.dataset.bouton || 'Voir les offres';
            const url = this.dataset.url || '#';
            
            // Remplir le modal d'aperçu
            const modal = document.getElementById('emailPreviewModal');
            if (modal) {
                // Titre de l'email
                const titleEl = modal.querySelector('.bg-gradient-to-r .text-xl.font-bold');
                if (titleEl) titleEl.textContent = 'NDIGITMARKET';
                
                // Objet
                const subjectEl = modal.querySelector('.bg-gradient-to-r .text-xs.text-white\\/80');
                if (subjectEl) subjectEl.textContent = objet;
                
                // Titre principal
                const mainTitle = modal.querySelector('.px-6.py-8 .text-lg.font-bold');
                if (mainTitle) mainTitle.textContent = nom;
                
                // Contenu
                const contentEl = modal.querySelector('.px-6.py-8 .text-sm.text-gray-600.leading-relaxed');
                if (contentEl) contentEl.textContent = contenu;
                
                // Bouton CTA
                const ctaBtn = modal.querySelector('.px-6.py-8 .inline-block');
                if (ctaBtn) {
                    ctaBtn.textContent = bouton;
                    ctaBtn.href = url;
                }
                
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        });
    });

    // ============================================
    // 2. MODIFIER LE TEMPLATE
    // ============================================
    document.querySelectorAll('.openEmailEditorBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const nom = this.dataset.nom || '';
            const objet = this.dataset.objet || '';
            const contenu = this.dataset.contenu || '';
            const bouton = this.dataset.bouton || '';
            const url = this.dataset.url || '';
            const statut = this.dataset.statut || 'active';
            
            const modal = document.getElementById('emailEditorModal');
            if (modal) {
                // Remplir les champs
                const inputs = modal.querySelectorAll('input');
                if (inputs.length >= 4) {
                    // Objet
                    inputs[0].value = objet;
                    // Titre principal
                    inputs[1].value = nom;
                    // Texte bouton
                    inputs[2].value = bouton;
                    // URL bouton
                    inputs[3].value = url;
                }
                
                // Contenu
                const textarea = modal.querySelector('textarea');
                if (textarea) textarea.value = contenu;
                
                // Statut du bouton
                const statusRadios = modal.querySelectorAll('input[name="btnColor"]');
                // ... selon ta logique
                
                // Stocker l'ID pour la sauvegarde
                const submitBtn = modal.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.dataset.id = id;
                    submitBtn.dataset.statut = statut;
                }
                
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        });
    });

    // ============================================
    // 3. TOGGLE STATUT DU TEMPLATE
    // ============================================
    document.querySelectorAll('.toggleEmailBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const statut = this.dataset.statut;
            const nom = this.dataset.nom || 'Template';
            const newStatut = statut === 'active' ? 'inactive' : 'active';
            const action = newStatut === 'active' ? 'Activer' : 'Désactiver';

            if (!confirm(`${action} le template "${nom}" ?`)) return;

            const originalHtml = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i>';
            this.disabled = true;

            fetch('api.php?url=email_template_toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + id + '&statut=' + newStatut
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (typeof showToast === 'function') {
                        showToast('Succès', data.message || 'Statut mis à jour', 'success');
                    }
                    // Recharger la page pour voir les changements
                    setTimeout(() => location.reload(), 1000);
                } else {
                    if (typeof showToast === 'function') {
                        showToast('Erreur', data.error || 'Erreur', 'error');
                    }
                    this.innerHTML = originalHtml;
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                if (typeof showToast === 'function') {
                    showToast('Erreur', 'Erreur de connexion', 'error');
                }
                this.innerHTML = originalHtml;
                this.disabled = false;
            });
        });
    });

    // ============================================
    // 4. SAUVEGARDER LE TEMPLATE (Éditeur)
    // ============================================
    document.querySelector('#emailEditorModal button[type="submit"]')?.addEventListener('click', function(e) {
        e.preventDefault();
        const id = this.dataset.id;
        const modal = document.getElementById('emailEditorModal');
        const inputs = modal.querySelectorAll('input');
        const textarea = modal.querySelector('textarea');
        
        const data = {
            id: id,
            objet: inputs[0]?.value || '',
            nom: inputs[1]?.value || '',
            bouton_texte: inputs[2]?.value || '',
            bouton_url: inputs[3]?.value || '',
            contenu: textarea?.value || ''
        };

        const originalHtml = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';
        this.disabled = true;

        const formData = new FormData();
        formData.append('id', data.id);
        formData.append('objet', data.objet);
        formData.append('nom', data.nom);
        formData.append('bouton_texte', data.bouton_texte);
        formData.append('bouton_url', data.bouton_url);
        formData.append('contenu', data.contenu);

        fetch('api.php?url=email_template_update', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (typeof showToast === 'function') {
                    showToast('Succès', data.message || 'Template mis à jour', 'success');
                }
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
                setTimeout(() => location.reload(), 1000);
            } else {
                if (typeof showToast === 'function') {
                    showToast('Erreur', data.error || 'Erreur', 'error');
                }
                this.innerHTML = originalHtml;
                this.disabled = false;
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            if (typeof showToast === 'function') {
                showToast('Erreur', 'Erreur de connexion', 'error');
            }
            this.innerHTML = originalHtml;
            this.disabled = false;
        });
    });

    // ============================================
    // 5. FERMER LES MODALS
    // ============================================
    document.querySelectorAll('.closeEmailPreviewBtn, .closeEmailEditorBtn, .closeComposeBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('[id$="Modal"]');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }
        });
    });

    // Fermer les modals en cliquant sur l'overlay
    document.querySelectorAll('[id$="Modal"]').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
                this.classList.remove('flex');
                document.body.style.overflow = '';
            }
        });
    });

    console.log('✅ Emails transactionnels - Actions prêtes');

})();
    </script>
</body>
</html>