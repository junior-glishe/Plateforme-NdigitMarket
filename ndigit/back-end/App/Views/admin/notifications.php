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
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center text-red-600">
                            <i class="fas fa-bell"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-full">NON LUES</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Notifications non lues</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-yellow-50 rounded-xl flex items-center justify-center text-yellow-600">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-yellow-600 bg-yellow-50 px-2 py-1 rounded-full">VENDEURS</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Demandes vendeur</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">COMMANDES</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Nouvelles commandes</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-purple-600 bg-purple-50 px-2 py-1 rounded-full">SÉCURITÉ</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
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
                        <select class="px-3 py-1.5 bg-gray-50 border border-gray-100 rounded-lg text-xs text-gray-600 focus:outline-none focus:border-[#0EA486]">
                            <option>Toutes</option>
                            <option>Non lues</option>
                            <option>Vendeurs</option>
                            <option>Produits</option>
                            <option>Commandes</option>
                            <option>Sécurité</option>
                        </select>
                    </div>
                </div>

                <div class="divide-y divide-gray-100">
                    <!-- Notification 1 : Demande vendeur -->
                    <div class="notif-item p-4 hover:bg-gray-50/50 transition cursor-pointer bg-blue-50/30 border-l-4 border-blue-500" data-type="vendor">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center text-yellow-600 flex-shrink-0">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-start justify-between gap-2 mb-1">
                                    <div>
                                        <h5 class="text-sm font-semibold text-[#0F172A]">Nouvelle demande vendeur</h5>
                                        <p class="text-xs text-gray-500 mt-0.5"><strong class="text-[#0F172A]">...</strong> a soumis une demande pour ouvrir la boutique "<strong class="text-[#0F172A]">...</strong>"</p>
                                    </div>
                                    <span class="text-[10px] text-gray-400 whitespace-nowrap">...</span>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 mt-2">
                                    <span class="text-[10px] font-semibold text-yellow-700 bg-yellow-100 px-2 py-0.5 rounded-full">
                                        <i class="fas fa-hourglass-half mr-1"></i>En attente
                                    </span>
                                    <button class="openNotifDetailBtn text-[10px] font-semibold text-[#0EA486] hover:underline flex items-center gap-1">
                                        <i class="fas fa-eye"></i> Voir détail
                                    </button>
                                    <button class="text-[10px] font-semibold text-gray-500 hover:text-gray-700 flex items-center gap-1">
                                        <i class="fas fa-check"></i> Marquer lu
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    

                <!-- Pagination -->
                <div class="flex flex-wrap items-center justify-between gap-3 px-4 py-3 border-t border-gray-100 bg-gray-50/50">
                    <div class="text-xs text-gray-500">
                        <span>Affichage de 6 notifications récentes</span>
                    </div>
                    <button class="text-xs font-semibold text-[#0EA486] hover:underline flex items-center gap-1">
                        Voir toutes les notifications <i class="fas fa-arrow-right text-[10px]"></i>
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
                    <button class="px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2">
                        <i class="fas fa-history"></i> Historique d'envoi
                    </button>
                </div>
            </div>

            <!-- Stats emails -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">ENVOYÉS</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Emails envoyés ce mois</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                            <i class="fas fa-eye"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">OUVERTS</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...%</p>
                    <p class="text-xs text-gray-400 mt-1">Taux d'ouverture moyen</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600">
                            <i class="fas fa-mouse-pointer"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-purple-600 bg-purple-50 px-2 py-1 rounded-full">CLICS</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...%</p>
                    <p class="text-xs text-gray-400 mt-1">Taux de clic moyen</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-orange-600 bg-orange-50 px-2 py-1 rounded-full">TEMPLATES</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
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
                            

                           

                            

                            <!-- Réinitialisation mot de passe -->
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600">
                                            <i class="fas fa-key text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-[#0F172A] text-sm">Réinitialisation mot de passe</p>
                                            <p class="text-[10px] text-gray-400 font-mono">password_reset</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-600">Demande reset password</td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-1">
                                        <span class="text-[10px] font-semibold text-blue-700 bg-blue-100 px-2 py-1 rounded-full">Utilisateur</span>
                                        <span class="text-[10px] font-semibold text-gray-700 bg-gray-100 px-2 py-1 rounded-full">Admin</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">...</td>
                                <td class="px-4 py-3 text-xs font-semibold text-emerald-600">...%</td>
                                <td class="px-4 py-3">
                                    <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-1 rounded-full">
                                        <i class="fas fa-check mr-1"></i>Actif
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="openEmailPreviewBtn w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center" title="Aperçu">
                                            <i class="fas fa-eye text-xs"></i>
                                        </button>
                                        <button class="openEmailEditorBtn w-8 h-8 rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-100 flex items-center justify-center" title="Modifier">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                        <button class="toggleEmailBtn w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100 flex items-center justify-center" title="Désactiver">
                                            <i class="fas fa-toggle-on text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
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
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-5 border border-blue-100">
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 bg-yellow-100 rounded-2xl flex items-center justify-center text-yellow-600 flex-shrink-0">
                            <i class="fas fa-user-plus text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-lg font-bold text-[#0F172A]">Nouvelle demande vendeur</h4>
                            <p class="text-xs text-gray-500 mt-1">Reçue le ...</p>
                            <div class="flex flex-wrap gap-2 mt-2">
                                <span class="text-[10px] font-semibold text-yellow-700 bg-yellow-100 px-2 py-1 rounded-full">
                                    <i class="fas fa-hourglass-half mr-1"></i>En attente
                                </span>
                                <span class="text-[10px] font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-full">
                                    ID: ...
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-info-circle text-[#0EA486]"></i> Informations
                    </h5>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-gray-500">Type</span>
                            <span class="font-medium text-[#0F172A]">...</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-gray-500">Source</span>
                            <span class="font-medium text-[#0F172A]">...</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-gray-500">Priorité</span>
                            <span class="font-semibold text-yellow-600">...</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-gray-500">Statut</span>
                            <span class="font-medium text-blue-600">Non lue</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-2 flex items-center gap-2">
                        <i class="fas fa-align-left text-[#0EA486]"></i> Message
                    </h5>
                    <p class="text-sm text-gray-600 leading-relaxed">...</p>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-wrap items-center justify-end gap-2">
                <button class="closeNotifDetailBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                    Fermer
                </button>
                <button class="px-4 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
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
    </script>
</body>
</html>