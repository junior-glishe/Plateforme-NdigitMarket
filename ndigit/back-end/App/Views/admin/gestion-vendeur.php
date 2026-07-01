<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NDIGITMARKET Admin · Gestion des Vendeurs</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/CSS/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
</head>
<body>
    <div id="overlay" class="overlay"></div>

    <aside id="sidebar" class="sidebar sidebar-mobile md:sidebar-mobile md:translate-x-0">
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
                <a href="../dashboard.html" class="sidebar-link">
                    <i class="fas fa-th-large"></i> Tableau de bord
                </a>
                <a href="./gestion-utilisateur.html" class="sidebar-link">
                    <i class="fas fa-users"></i> Gestion utilisateur
                </a>
                <a href="./gestion-vendeur.html" class="sidebar-link active">
                    <i class="fas fa-store"></i> Gestion vendeur
                </a>
                <a href="./gestion-produits.html" class="sidebar-link">
                    <i class="fas fa-file-code"></i> Produits & Templates
                </a>
                <a href="./gestion-commande.html" class="sidebar-link">
                    <i class="fas fa-shopping-cart"></i> Gestion commande
                </a>
                <a href="./financieres-commission.html" class="sidebar-link">
                    <i class="fas fa-coins"></i> Financières & commission
                </a>
                <a href="./categorie.html" class="sidebar-link">
                    <i class="fas fa-tags"></i> Catégorie
                </a>
                <a href="./layout/contenus.html" class="sidebar-link">
                    <i class="fas fa-newspaper"></i> Contenus
                </a>
                <a href="./notifications.html" class="sidebar-link">
                    <i class="fas fa-bell"></i> Notifications
                </a>
                <a href="./rapport-stat.html" class="sidebar-link">
                    <i class="fas fa-chart-pie"></i> Statistique & rapport
                </a>
                <a href="./avis-commentaires.html" class="sidebar-link">
                    <i class="fas fa-comment-dots"></i> Avis / Commentaire
                </a>
                <a href="./parametres-systeme.html" class="sidebar-link">
                    <i class="fas fa-cog"></i> Paramètre système
                </a>
                <a href="./logs-audit.html" class="sidebar-link">
                    <i class="fas fa-history"></i> Logs & audit trail
                </a>
            </nav>

            <!-- Profil en bas -->
            <div class="mt-8 pt-6 border-t border-gray-100">
                <div class="bg-[#0F172A] rounded-2xl p-4 text-white">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-[#0EA486] rounded-full flex items-center justify-center text-lg font-semibold">
                            A
                        </div>
                        <div>
                            <p class="text-sm font-semibold">....</p>
                            <p class="text-[11px] text-gray-400">....</p>
                        </div>
                    </div>
                    <button class="text-xs text-gray-300 hover:text-white transition flex items-center gap-2">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </button>
                </div>
            </div>
        </div>
    </aside>

    <main class="md:ml-[280px] min-h-screen p-4 md:p-8 transition-all">

        <!-- HEADER -->
        <header class="flex items-center justify-between mb-6 bg-white/80 backdrop-blur-sm sticky top-0 z-30 py-4 px-4 md:px-6 rounded-2xl shadow-sm border border-gray-100/50">
            <div class="flex items-center gap-4">
                <button id="hamburgerBtn" class="md:hidden w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 transition flex items-center justify-center text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-[#0F172A]">Gestion des Vendeurs</h2>
                    <p class="text-xs text-gray-400 hidden sm:block">Demandes, vendeurs actifs, commissions et versements</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button class="relative w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 transition flex items-center justify-center">
                    <i class="fas fa-bell text-gray-600"></i>
                    <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                </button>
                <div class="w-9 h-9 rounded-full bg-[#0EA486] text-white flex items-center justify-center font-semibold text-sm">
                    A
                </div>
            </div>
        </header>

        <!--  DEMANDES DE DEVENIR VENDEUR -->
        <section class="mb-6">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-user-plus text-[#0EA486]"></i> · Demandes de devenir vendeur
                </h3>
                <div class="flex gap-2">
                    <button class="px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2">
                        <i class="fas fa-history"></i> Historique des décisions
                    </button>
                </div>
            </div>

            <!-- Cartes de statistiques -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-yellow-50 rounded-xl flex items-center justify-center text-yellow-600">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-yellow-600 bg-yellow-50 px-2 py-1 rounded-full">EN ATTENTE</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Demandes en attente</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">APPROUVÉES</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Approuvées ce mois</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center text-red-600">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-full">REFUSÉES</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Refusées ce mois</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">INFO</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Infos supplémentaires demandées</p>
                </div>
            </div>

            <!-- Liste des demandes en attente -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h4 class="text-sm font-semibold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-inbox text-[#0EA486]"></i> Demandes en attente de revue
                    </h4>
                    <span class="text-xs text-gray-400">... demandes</span>
                </div>

                <div class="divide-y divide-gray-100">
                    <!-- Item 1 -->
                    <div class="p-4 hover:bg-gray-50/50 transition">
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="w-full md:w-16 h-16 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-store text-2xl text-emerald-400"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-start justify-between gap-2 mb-1">
                                    <div>
                                        <h5 class="text-sm font-semibold text-[#0F172A]">...</h5>
                                        <p class="text-xs text-gray-400 mt-0.5">ID ... · Soumis il y a ...</p>
                                    </div>
                                    <span class="text-[10px] font-semibold text-yellow-700 bg-yellow-100 px-2 py-1 rounded-full">
                                        <i class="fas fa-hourglass-half mr-1"></i>En attente
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 line-clamp-2 mb-2">... , ... , ...</p>
                                <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                    <span class="flex items-center gap-1"><i class="fas fa-user text-[#0EA486]"></i>...</span>
                                    <span class="flex items-center gap-1"><i class="fas fa-envelope text-[#0EA486]"></i>...</span>
                                    <span class="flex items-center gap-1"><i class="fas fa-phone text-[#0EA486]"></i>...</span>
                                </div>
                            </div>
                            <div class="flex md:flex-col gap-2 md:justify-center">
                                <button class="openReviewBtn px-3 py-2 rounded-lg bg-[#0EA486] hover:bg-[#0c8f75] text-white text-xs font-semibold flex items-center gap-1.5 transition">
                                    <i class="fas fa-eye"></i> Revoir
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!--  LISTE DES VENDEURS ACTIFS -->
        <section class="mb-6">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-list text-[#0EA486]"></i>· Liste des vendeurs actifs
                </h3>
                <div class="flex gap-2">
                    <button class="px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2">
                        <i class="fas fa-file-csv"></i> Export CSV
                    </button>
                </div>
            </div>

            <!-- Barre de recherche + filtres -->
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm mb-4">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex-1 min-w-[220px] relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" placeholder="Rechercher par nom de boutique ou ID..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white transition">
                    </div>
                    <select class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 focus:outline-none focus:border-[#0EA486]">
                        <option>Tous les statuts</option>
                        <option>Actif</option>
                        <option>Suspendu</option>
                        <option>Banni</option>
                    </select>
                    <select class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 focus:outline-none focus:border-[#0EA486]">
                        <option>Trier par : Date d'activation</option>
                        <option>CA total</option>
                        <option>Nombre de produits</option>
                        <option>Nom (A-Z)</option>
                    </select>
                    <button class="px-3 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm flex items-center gap-2">
                        <i class="fas fa-filter"></i> Plus de filtres
                    </button>
                </div>
            </div>

            <!-- Tableau -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-3 w-10"><input type="checkbox" class="w-4 h-4 rounded border-gray-300"></th>
                                <th class="px-4 py-3 cursor-pointer hover:text-[#0EA486]">ID Vendeur <i class="fas fa-sort text-[10px] ml-1"></i></th>
                                <th class="px-4 py-3 cursor-pointer hover:text-[#0EA486]">Nom de boutique <i class="fas fa-sort text-[10px] ml-1"></i></th>
                                <th class="px-4 py-3">Vendeur (utilisateur)</th>
                                <th class="px-4 py-3 cursor-pointer hover:text-[#0EA486]">Date d'activation <i class="fas fa-sort text-[10px] ml-1"></i></th>
                                <th class="px-4 py-3">Nb produits</th>
                                <th class="px-4 py-3 cursor-pointer hover:text-[#0EA486]">CA total <i class="fas fa-sort text-[10px] ml-1"></i></th>
                                <th class="px-4 py-3">Commissions dues</th>
                                <th class="px-4 py-3">Statut</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-4 py-3"><input type="checkbox" class="w-4 h-4 rounded border-gray-300"></td>
                                <td class="px-4 py-3 text-xs text-gray-500">...</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-store text-emerald-400"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-[#0F172A] text-sm">...</p>
                                            <p class="text-[11px] text-gray-400">...</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="#" class="text-xs text-[#0EA486] hover:underline font-medium">...</a>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500">...</td>
                                <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">...</td>
                                <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">... FCFA</td>
                                <td class="px-4 py-3 text-xs font-semibold text-[#0EA486]">... FCFA</td>
                                <td class="px-4 py-3">
                                    <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-1 rounded-full">...</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="openVendorProfileBtn w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center" title="Voir">
                                            <i class="fas fa-eye text-xs"></i>
                                        </button>
                                        <button class="openVendorEditBtn w-8 h-8 rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-100 flex items-center justify-center" title="Modifier">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                        <button class="openSuspendBtn w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100 flex items-center justify-center" title="Suspendre">
                                            <i class="fas fa-pause text-xs"></i>
                                        </button>
                                        <button class="openDeleteBtn w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center" title="Supprimer">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex flex-wrap items-center justify-between gap-3 px-4 py-3 border-t border-gray-100 bg-gray-50/50">
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <span>Afficher</span>
                        <select class="px-2 py-1 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-[#0EA486]">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                            <option>100</option>
                        </select>
                        <span>résultats par page</span>
                    </div>
                    <div class="flex items-center gap-1 text-xs text-gray-500">
                        <span>1 - 4 sur 142</span>
                        <button class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-50">
                            <i class="fas fa-chevron-left text-[10px]"></i>
                        </button>
                        <button class="w-8 h-8 rounded-lg bg-[#0EA486] text-white flex items-center justify-center">1</button>
                        <button class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-50">2</button>
                        <button class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-50">3</button>
                        <button class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-50">
                            <i class="fas fa-chevron-right text-[10px]"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- GESTION DES COMMISSIONS ET VERSEMENTS -->
        <section class="mb-8">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-coins text-[#0EA486]"></i> · Gestion des commissions et versements
                </h3>
                <div class="flex gap-2">
                    <button class="px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2">
                        <i class="fas fa-file-csv"></i> Export versements CSV
                    </button>
                </div>
            </div>

            <!-- Vue globale -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">TOTAL</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">... FCFA</p>
                    <p class="text-xs text-gray-400 mt-1">Commissions dues à tous les vendeurs</p>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-orange-600 bg-orange-50 px-2 py-1 rounded-full">ALERTE</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Vendeurs avec solde > 30 jours</p>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">CE MOIS</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">... FCFA</p>
                    <p class="text-xs text-gray-400 mt-1">Versements effectués ce mois</p>
                </div>
            </div>

            <!-- Tableau des versements par vendeur -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h4 class="text-sm font-semibold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-money-bill-wave text-[#0EA486]"></i> Versements par vendeur
                    </h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-3">Vendeur</th>
                                <th class="px-4 py-3">Solde à verser</th>
                                <th class="px-4 py-3">Dernier versement</th>
                                <th class="px-4 py-3">Statut</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-store text-emerald-400"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-[#0F172A] text-sm">...</p>
                                            <p class="text-[11px] text-gray-400">...</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-xs font-semibold text-[#0EA486]">... FCFA</td>
                                <td class="px-4 py-3 text-xs text-gray-500">...</td>
                                <td class="px-4 py-3">
                                    <span class="text-[10px] font-semibold text-orange-700 bg-orange-100 px-2 py-1 rounded-full">...</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="openPaymentBtn w-8 h-8 rounded-lg bg-[#0EA486]/10 text-[#0EA486] hover:bg-[#0EA486] hover:text-white flex items-center justify-center transition" title="Marquer versement effectué">
                                            <i class="fas fa-check text-xs"></i>
                                        </button>
                                        <button class="openPaymentHistoryBtn w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center" title="Historique">
                                            <i class="fas fa-history text-xs"></i>
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

    <!-- MODAL : INTERFACE DE REVUE DEMANDE -->
    <div id="reviewModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-5xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#0EA486]/5 to-transparent">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-user-plus text-[#0EA486]"></i> Revue de demande vendeur
                    </h3>
                    <p class="text-xs text-gray-400">Détail de la demande avant décision</p>
                </div>
                <button class="closeReviewBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="overflow-y-auto p-6 space-y-5">
                <!-- Infos demandeur -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl p-5 border border-gray-100">
                            <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                                <i class="fas fa-user text-[#0EA486]"></i> Informations du demandeur
                            </h5>
                            <div class="space-y-3">
                                <div class="flex justify-between py-2 border-b border-gray-50">
                                    <span class="text-xs text-gray-500">Nom complet</span>
                                    <span class="text-sm font-medium text-[#0F172A]">...</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-50">
                                    <span class="text-xs text-gray-500">Email</span>
                                    <span class="text-sm font-medium text-[#0F172A]">...</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-50">
                                    <span class="text-xs text-gray-500">Téléphone</span>
                                    <span class="text-sm font-medium text-[#0F172A]">...</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-50">
                                    <span class="text-xs text-gray-500">Date de soumission</span>
                                    <span class="text-sm font-medium text-[#0F172A]">...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="bg-white rounded-2xl p-4 border border-gray-100">
                            <h5 class="text-xs font-semibold text-gray-400 uppercase mb-2">Boutique proposée</h5>
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-12 h-12 bg-[#0EA486] rounded-xl flex items-center justify-center text-white font-semibold text-lg">
                                    ...
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-[#0F172A]">...</p>
                                    <p class="text-[11px] text-gray-400">...</p>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 leading-relaxed">...</p>
                        </div>

                        <div class="bg-white rounded-2xl p-4 border border-gray-100">
                            <h5 class="text-xs font-semibold text-gray-400 uppercase mb-2">Exemples de produits</h5>
                            <div class="space-y-2">
                                <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg">
                                    <i class="fas fa-link text-[#0EA486] text-xs"></i>
                                    <span class="text-xs text-gray-600 truncate">...</span>
                                </div>
                                <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg">
                                    <i class="fas fa-link text-[#0EA486] text-xs"></i>
                                    <span class="text-xs text-gray-600 truncate">...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description complète -->
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-2 flex items-center gap-2">
                        <i class="fas fa-align-left text-[#0EA486]"></i> Description de la boutique
                    </h5>
                    <p class="text-sm text-gray-600 leading-relaxed">...</p>
                </div>

                <!-- Historique de modération -->
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-history text-[#0EA486]"></i> Historique des décisions
                    </h5>
                    <div class="space-y-2">
                        <div class="flex items-start gap-3 p-2 rounded-lg hover:bg-gray-50">
                            <div class="w-7 h-7 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-600 flex-shrink-0 mt-0.5">
                                <i class="fas fa-hourglass-half text-[10px]"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-[#0F172A]">Demande soumise</p>
                                <p class="text-[11px] text-gray-400">...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Commentaires internes -->
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-lock text-[#0EA486]"></i> Commentaires internes (non visibles par le demandeur)
                    </h5>
                    <textarea rows="3" placeholder="Ajoutez une note interne pour les autres administrateurs..." class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white resize-none"></textarea>
                </div>
            </div>

            <!-- Actions de modération -->
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500">Décision :</span>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <button class="openInfoRequestBtn px-4 py-2.5 rounded-xl bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-semibold flex items-center gap-2 transition">
                        <i class="fas fa-question-circle"></i> Demander infos
                    </button>
                    <button class="openRejectBtn px-4 py-2.5 rounded-xl bg-red-100 hover:bg-red-200 text-red-700 text-sm font-semibold flex items-center gap-2 transition">
                        <i class="fas fa-times-circle"></i> Refuser
                    </button>
                    <button class="openApproveBtn px-5 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                        <i class="fas fa-check-circle"></i> Approuver
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL  : FICHE VENDEUR DÉTAILLÉE -->
    <div id="vendorProfileModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-5xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#0EA486]/5 to-transparent">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-store text-[#0EA486]"></i> Fiche vendeur détaillée
                    </h3>
                    <p class="text-xs text-gray-400">Informations complètes du vendeur</p>
                </div>
                <button class="closeVendorProfileBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="overflow-y-auto p-6 space-y-5">
                <!-- En-tête vendeur -->
                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl p-5 border border-emerald-100">
                    <div class="flex flex-col md:flex-row items-start md:items-center gap-4">
                        <div class="w-16 h-16 bg-[#0EA486] rounded-2xl flex items-center justify-center text-white font-bold text-2xl">
                            ...
                        </div>
                        <div class="flex-1">
                            <h4 class="text-lg font-bold text-[#0F172A]">...</h4>
                            <p class="text-xs text-gray-500 mt-1">ID ... · Activé le ...</p>
                            <div class="flex flex-wrap gap-2 mt-2">
                                <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-1 rounded-full">Actif</span>
                                <span class="text-[10px] font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-full">... produits</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-[#0EA486]">... FCFA</p>
                            <p class="text-xs text-gray-500">CA total</p>
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="border-b border-gray-100">
                    <nav class="flex gap-4">
                        <button class="vendor-tab-btn active px-4 py-2 text-sm font-semibold text-[#0EA486] border-b-2 border-[#0EA486]" data-tab="info">Informations</button>
                        <button class="vendor-tab-btn px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700" data-tab="products">Produits</button>
                        <button class="vendor-tab-btn px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700" data-tab="sales">Ventes</button>
                        <button class="vendor-tab-btn px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700" data-tab="payments">Versements</button>
                        <button class="vendor-tab-btn px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700" data-tab="notes">Notes</button>
                    </nav>
                </div>

                <!-- Tab: Informations -->
                <div class="vendor-tab-content" data-tab="info">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white rounded-2xl p-4 border border-gray-100">
                            <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                                <i class="fas fa-store text-[#0EA486]"></i> Informations boutique
                            </h5>
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between py-2 border-b border-gray-50">
                                    <span class="text-gray-500">Nom</span>
                                    <span class="font-medium text-[#0F172A]">...</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-50">
                                    <span class="text-gray-500">Description</span>
                                    <span class="font-medium text-[#0F172A] text-right max-w-[200px] truncate">...</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-50">
                                    <span class="text-gray-500">Logo</span>
                                    <span class="font-medium text-[#0F172A]">...</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-50">
                                    <span class="text-gray-500">Réseaux sociaux</span>
                                    <span class="font-medium text-[#0F172A]">...</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl p-4 border border-gray-100">
                            <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                                <i class="fas fa-mobile-alt text-[#0EA486]"></i> Coordonnées de paiement
                            </h5>
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between py-2 border-b border-gray-50">
                                    <span class="text-gray-500">Opérateur</span>
                                    <span class="font-medium text-[#0F172A]">...</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-50">
                                    <span class="text-gray-500">Numéro</span>
                                    <span class="font-medium text-[#0F172A]">...</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-50">
                                    <span class="text-gray-500">Solde actuel</span>
                                    <span class="font-semibold text-[#0EA486]">... FCFA</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab: Produits -->
                <div class="vendor-tab-content hidden" data-tab="products">
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 border-b border-gray-100">
                                    <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        <th class="px-4 py-3">Produit</th>
                                        <th class="px-4 py-3">Catégorie</th>
                                        <th class="px-4 py-3">Prix</th>
                                        <th class="px-4 py-3">Ventes</th>
                                        <th class="px-4 py-3">Statut</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <i class="fas fa-image text-indigo-400"></i>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-[#0F172A] text-sm">...</p>
                                                    <p class="text-[11px] text-gray-400">...</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-600">...</td>
                                        <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">... FCFA</td>
                                        <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">...</td>
                                        <td class="px-4 py-3">
                                            <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-1 rounded-full">...</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tab: Ventes -->
                <div class="vendor-tab-content hidden" data-tab="sales">
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 border-b border-gray-100">
                                    <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        <th class="px-4 py-3">Produit</th>
                                        <th class="px-4 py-3">Date</th>
                                        <th class="px-4 py-3">Montant</th>
                                        <th class="px-4 py-3">Commission</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">...</td>
                                        <td class="px-4 py-3 text-xs text-gray-500">...</td>
                                        <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">... FCFA</td>
                                        <td class="px-4 py-3 text-xs font-semibold text-[#0EA486]">... FCFA</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tab: Versements -->
                <div class="vendor-tab-content hidden" data-tab="payments">
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 border-b border-gray-100">
                                    <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        <th class="px-4 py-3">Date</th>
                                        <th class="px-4 py-3">Montant</th>
                                        <th class="px-4 py-3">Référence</th>
                                        <th class="px-4 py-3">Statut</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-4 py-3 text-xs text-gray-500">...</td>
                                        <td class="px-4 py-3 text-xs font-semibold text-[#0EA486]">... FCFA</td>
                                        <td class="px-4 py-3 text-xs text-gray-600">...</td>
                                        <td class="px-4 py-3">
                                            <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-1 rounded-full">...</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tab: Notes -->
                <div class="vendor-tab-content hidden" data-tab="notes">
                    <div class="bg-white rounded-2xl p-4 border border-gray-100">
                        <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                            <i class="fas fa-sticky-note text-[#0EA486]"></i> Notes internes admin
                        </h5>
                        <textarea rows="5" placeholder="Ajoutez une note interne sur ce vendeur..." class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white resize-none">...</textarea>
                        <button class="mt-3 px-4 py-2 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-xs font-semibold flex items-center gap-2 transition">
                            <i class="fas fa-save"></i> Enregistrer la note
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL : APPROUVER DEMANDE -->
    <div id="approveModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-check-circle text-[#0EA486]"></i> Approuver la demande
                    </h3>
                    <p class="text-xs text-gray-400">Confirmer l'activation de la boutique</p>
                </div>
                <button class="closeApproveBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100">
                    <p class="text-sm text-emerald-700">
                        <i class="fas fa-info-circle mr-2"></i>
                        Un email de confirmation sera envoyé au demandeur avec les instructions pour activer sa boutique.
                    </p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Message additionnel (optionnel)</label>
                    <textarea rows="3" placeholder="Ajoutez un message personnalisé..." class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white resize-none"></textarea>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                <button class="closeApproveBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                    Annuler
                </button>
                <button class="px-5 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                    <i class="fas fa-check"></i> Confirmer l'approbation
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL : REFUSER DEMANDE -->
    <div id="rejectModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-times-circle text-red-500"></i> Refuser la demande
                    </h3>
                    <p class="text-xs text-gray-400">Motif obligatoire</p>
                </div>
                <button class="closeRejectBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="bg-red-50 rounded-xl p-4 border border-red-100">
                    <p class="text-sm text-red-700">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Un email de refus avec explication sera envoyé au demandeur.
                    </p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Motif du refus <span class="text-red-500">*</span></label>
                    <textarea rows="4" placeholder="Expliquez la raison du refus..." class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:bg-white resize-none" required></textarea>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                <button class="closeRejectBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                    Annuler
                </button>
                <button class="px-5 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                    <i class="fas fa-times"></i> Confirmer le refus
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL : DEMANDER INFOS -->
    <div id="infoRequestModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-question-circle text-blue-500"></i> Demander des informations
                    </h3>
                    <p class="text-xs text-gray-400">Envoyer une question au demandeur</p>
                </div>
                <button class="closeInfoRequestBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                    <p class="text-sm text-blue-700">
                        <i class="fas fa-info-circle mr-2"></i>
                        Un email sera envoyé au demandeur avec votre question.
                    </p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Votre question <span class="text-red-500">*</span></label>
                    <textarea rows="4" placeholder="Posez votre question..." class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:bg-white resize-none" required></textarea>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                <button class="closeInfoRequestBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                    Annuler
                </button>
                <button class="px-5 py-2.5 rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                    <i class="fas fa-paper-plane"></i> Envoyer la demande
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL : MARQUER VERSEMENT -->
    <div id="paymentModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-check-circle text-[#0EA486]"></i> Marquer versement effectué
                    </h3>
                    <p class="text-xs text-gray-400">Confirmer le versement au vendeur</p>
                </div>
                <button class="closePaymentBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100">
                    <p class="text-sm text-emerald-700">
                        <i class="fas fa-info-circle mr-2"></i>
                        Le vendeur sera notifié du versement effectué.
                    </p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Montant versé (FCFA) <span class="text-red-500">*</span></label>
                    <input type="number" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="...">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Référence transaction <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="Ex: TXN-123456">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Note (optionnel)</label>
                    <textarea rows="2" placeholder="Ajoutez une note..." class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white resize-none"></textarea>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                <button class="closePaymentBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                    Annuler
                </button>
                <button class="px-5 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                    <i class="fas fa-check"></i> Confirmer le versement
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL : HISTORIQUE VERSEMENTS -->
    <div id="paymentHistoryModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-history text-blue-500"></i> Historique des versements
                    </h3>
                    <p class="text-xs text-gray-400">Tous les versements effectués à ce vendeur</p>
                </div>
                <button class="closePaymentHistoryBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="overflow-y-auto p-6">
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">Montant</th>
                                    <th class="px-4 py-3">Référence</th>
                                    <th class="px-4 py-3">Note</th>
                                    <th class="px-4 py-3">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-4 py-3 text-xs text-gray-500">...</td>
                                    <td class="px-4 py-3 text-xs font-semibold text-[#0EA486]">... FCFA</td>
                                    <td class="px-4 py-3 text-xs text-gray-600">...</td>
                                    <td class="px-4 py-3 text-xs text-gray-500">...</td>
                                    <td class="px-4 py-3">
                                        <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-1 rounded-full">...</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL : SUSPENDRE VENDEUR -->
    <div id="suspendModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-pause text-yellow-500"></i> Suspendre le vendeur
                    </h3>
                    <p class="text-xs text-gray-400">Motif de suspension</p>
                </div>
                <button class="closeSuspendBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="bg-yellow-50 rounded-xl p-4 border border-yellow-100">
                    <p class="text-sm text-yellow-700">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Le vendeur ne pourra plus publier de produits ni recevoir de commandes pendant la suspension.
                    </p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Motif de suspension <span class="text-red-500">*</span></label>
                    <textarea rows="4" placeholder="Expliquez la raison de la suspension..." class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-yellow-500 focus:bg-white resize-none" required></textarea>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Durée (optionnel)</label>
                    <select class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                        <option>Indéterminée</option>
                        <option>7 jours</option>
                        <option>30 jours</option>
                        <option>90 jours</option>
                    </select>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                <button class="closeSuspendBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                    Annuler
                </button>
                <button class="px-5 py-2.5 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                    <i class="fas fa-pause"></i> Confirmer la suspension
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL : SUPPRIMER VENDEUR -->
    <div id="deleteModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-trash text-red-500"></i> Supprimer le vendeur
                    </h3>
                    <p class="text-xs text-gray-400">Action irréversible</p>
                </div>
                <button class="closeDeleteBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="bg-red-50 rounded-xl p-4 border border-red-100">
                    <p class="text-sm text-red-700">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Attention :</strong> Cette action est irréversible. Toutes les données du vendeur seront définitivement supprimées.
                    </p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Tapez "SUPPRIMER" pour confirmer</label>
                    <input type="text" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:bg-white" placeholder="SUPPRIMER">
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                <button class="closeDeleteBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                    Annuler
                </button>
                <button class="px-5 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                    <i class="fas fa-trash"></i> Supprimer définitivement
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL : MODIFIER VENDEUR -->
    <div id="vendorEditModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A]">Modifier le vendeur</h3>
                    <p class="text-xs text-gray-400">Modifier les informations de la boutique</p>
                </div>
                <button class="closeVendorEditBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form class="p-6 overflow-y-auto space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Nom de la boutique <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Description</label>
                        <textarea rows="3" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white resize-none"></textarea>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Opérateur Mobile Money</label>
                        <select class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                            <option>— Sélectionner —</option>
                            <option>Orange Money</option>
                            <option>MTN Money</option>
                            <option>Moov Money</option>
                            <option>Wave</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Numéro Mobile Money</label>
                        <input type="text" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Statut</label>
                        <select class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                            <option>Actif</option>
                            <option>Suspendu</option>
                            <option>Banni</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Logo de la boutique</label>
                        <div class="border-2 border-dashed border-gray-200 rounded-xl p-5 text-center hover:border-[#0EA486] transition cursor-pointer">
                            <i class="fas fa-cloud-upload-alt text-2xl text-gray-300 mb-2"></i>
                            <p class="text-xs text-gray-500">Glissez-déposez ou <span class="text-[#0EA486] font-semibold">parcourir</span></p>
                            <p class="text-[10px] text-gray-400 mt-1">PNG, JPG · max 2 MB</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                    <button type="button" class="closeVendorEditBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                        Annuler
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
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

        // Modal de revue demande (4.4.1)
        (function() {
            const modal = document.getElementById('reviewModal');
            const openBtns = document.querySelectorAll('.openReviewBtn');
            const closeBtns = document.querySelectorAll('.closeReviewBtn');

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
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
            });
        })();

        // Modal fiche vendeur (4.4.3)
        (function() {
            const modal = document.getElementById('vendorProfileModal');
            const openBtns = document.querySelectorAll('.openVendorProfileBtn');
            const closeBtns = document.querySelectorAll('.closeVendorProfileBtn');

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
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
            });

            // Tabs
            const tabBtns = document.querySelectorAll('.vendor-tab-btn');
            const tabContents = document.querySelectorAll('.vendor-tab-content');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const tabName = this.getAttribute('data-tab');
                    
                    tabBtns.forEach(b => {
                        b.classList.remove('active', 'text-[#0EA486]', 'border-b-2', 'border-[#0EA486]', 'font-semibold');
                        b.classList.add('text-gray-500', 'font-medium');
                    });
                    this.classList.add('active', 'text-[#0EA486]', 'border-b-2', 'border-[#0EA486]', 'font-semibold');
                    this.classList.remove('text-gray-500', 'font-medium');

                    tabContents.forEach(content => {
                        if (content.getAttribute('data-tab') === tabName) {
                            content.classList.remove('hidden');
                        } else {
                            content.classList.add('hidden');
                        }
                    });
                });
            });
        })();

        // Modal approuver
        (function() {
            const modal = document.getElementById('approveModal');
            const openBtns = document.querySelectorAll('.openApproveBtn');
            const closeBtns = document.querySelectorAll('.closeApproveBtn');

            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            openBtns.forEach(btn => btn.addEventListener('click', openModal));
            closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });
        })();

        // Modal refuser
        (function() {
            const modal = document.getElementById('rejectModal');
            const openBtns = document.querySelectorAll('.openRejectBtn');
            const closeBtns = document.querySelectorAll('.closeRejectBtn');

            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            openBtns.forEach(btn => btn.addEventListener('click', openModal));
            closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });
        })();

        // Modal demander infos
        (function() {
            const modal = document.getElementById('infoRequestModal');
            const openBtns = document.querySelectorAll('.openInfoRequestBtn');
            const closeBtns = document.querySelectorAll('.closeInfoRequestBtn');

            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            openBtns.forEach(btn => btn.addEventListener('click', openModal));
            closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });
        })();

        // Modal versement
        (function() {
            const modal = document.getElementById('paymentModal');
            const openBtns = document.querySelectorAll('.openPaymentBtn');
            const closeBtns = document.querySelectorAll('.closePaymentBtn');

            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            openBtns.forEach(btn => btn.addEventListener('click', openModal));
            closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });
        })();

        // Modal historique versements
        (function() {
            const modal = document.getElementById('paymentHistoryModal');
            const openBtns = document.querySelectorAll('.openPaymentHistoryBtn');
            const closeBtns = document.querySelectorAll('.closePaymentHistoryBtn');

            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            openBtns.forEach(btn => btn.addEventListener('click', openModal));
            closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });
        })();

        // Modal suspendre
        (function() {
            const modal = document.getElementById('suspendModal');
            const openBtns = document.querySelectorAll('.openSuspendBtn');
            const closeBtns = document.querySelectorAll('.closeSuspendBtn');

            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            openBtns.forEach(btn => btn.addEventListener('click', openModal));
            closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });
        })();

        // Modal supprimer
        (function() {
            const modal = document.getElementById('deleteModal');
            const openBtns = document.querySelectorAll('.openDeleteBtn');
            const closeBtns = document.querySelectorAll('.closeDeleteBtn');

            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            openBtns.forEach(btn => btn.addEventListener('click', openModal));
            closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });
        })();

        // Modal modifier vendeur
        (function() {
            const modal = document.getElementById('vendorEditModal');
            const openBtns = document.querySelectorAll('.openVendorEditBtn');
            const closeBtns = document.querySelectorAll('.closeVendorEditBtn');

            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            openBtns.forEach(btn => btn.addEventListener('click', openModal));
            closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });
        })();
    </script>
</body>
</html>