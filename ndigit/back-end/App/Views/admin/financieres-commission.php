<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NDIGITMARKET Admin · Gestion Financière</title>

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
                <a href="./gestion-vendeur.html" class="sidebar-link">
                    <i class="fas fa-store"></i> Gestion vendeur
                </a>
                <a href="./gestion-produits.html" class="sidebar-link">
                    <i class="fas fa-file-code"></i> Produits & Templates
                </a>
                <a href="./gestion-commande.html" class="sidebar-link">
                    <i class="fas fa-shopping-cart"></i> Gestion commande
                </a>
                <a href="../layout/financieres-commission.html" class="sidebar-link active">
                    <i class="fas fa-coins"></i> Financières & commission
                </a>
                <a href="./categorie.html" class="sidebar-link">
                    <i class="fas fa-tags"></i> Catégorie
                </a>
                <a href="./contenus.html" class="sidebar-link">
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
                    <h2 class="text-xl md:text-2xl font-bold text-[#0F172A]">Gestion Financière</h2>
                    <p class="text-xs text-gray-400 hidden sm:block">Tableau financier, rapports et remboursements</p>
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

        <!-- VUE D'ENSEMBLE FINANCIÈRE -->
        <section class="mb-6">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-chart-line text-[#0EA486]"></i> · Vue d'ensemble financière
                </h3>
                <div class="flex gap-2">
                    <select class="px-3 py-2 rounded-lg bg-white border border-gray-100 text-xs font-medium text-gray-600 focus:outline-none focus:border-[#0EA486]">
                        <option>Toutes les périodes</option>
                        <option>Ce mois</option>
                        <option>Ce trimestre</option>
                        <option>Cette année</option>
                        <option>Personnalisé</option>
                    </select>
                    <button class="px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2">
                        <i class="fas fa-sync-alt"></i> Actualiser
                    </button>
                </div>
            </div>

            <!-- Cartes principales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                <!-- CA total -->
                <div class="bg-gradient-to-br from-[#0EA486] to-[#0c8f75] rounded-2xl p-5 text-white shadow-lg">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-wallet text-xl"></i>
                        </div>
                        <span class="text-[10px] font-semibold bg-white/20 px-2 py-1 rounded-full">TOTAL</span>
                    </div>
                    <p class="text-3xl font-bold mb-1">... FCFA</p>
                    <p class="text-xs text-white/80">Chiffre d'affaires total</p>
                    <div class="flex items-center gap-2 mt-3 text-xs">
                        <span class="flex items-center gap-1 bg-white/20 px-2 py-1 rounded-full">
                            <i class="fas fa-arrow-up text-[10px]"></i> ...%
                        </span>
                        <span class="text-white/70">vs mois précédent</span>
                    </div>
                </div>

                <!-- CA mois en cours -->
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                            <i class="fas fa-calendar-check text-xl"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">CE MOIS</span>
                    </div>
                    <p class="text-3xl font-bold text-[#0F172A] mb-1">... FCFA</p>
                    <p class="text-xs text-gray-400">CA du mois en cours</p>
                    <div class="flex items-center gap-2 mt-3 text-xs">
                        <span class="flex items-center gap-1 text-emerald-600 font-semibold">
                            <i class="fas fa-arrow-up text-[10px]"></i> ...%
                        </span>
                        <span class="text-gray-400">vs mois précédent</span>
                    </div>
                </div>

                <!-- Revenus nets plateforme -->
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600">
                            <i class="fas fa-piggy-bank text-xl"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-purple-600 bg-purple-50 px-2 py-1 rounded-full">NET</span>
                    </div>
                    <p class="text-3xl font-bold text-[#0F172A] mb-1">... FCFA</p>
                    <p class="text-xs text-gray-400">Revenus nets plateforme</p>
                    <div class="flex items-center gap-2 mt-3 text-xs">
                        <span class="text-gray-400">Après versements vendeurs</span>
                    </div>
                </div>
            </div>

            <!-- Cartes commissions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Commissions plateforme -->
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                            <i class="fas fa-building"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">10%</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">... FCFA</p>
                    <p class="text-xs text-gray-400 mt-1">Commissions plateforme perçues</p>
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Ce mois</span>
                            <span class="font-semibold text-[#0F172A]">... FCFA</span>
                        </div>
                    </div>
                </div>

                <!-- Commissions vendeurs versées -->
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">VERSÉ</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">... FCFA</p>
                    <p class="text-xs text-gray-400 mt-1">Commissions vendeurs versées</p>
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">90% du CA</span>
                            <span class="font-semibold text-[#0F172A]">...</span>
                        </div>
                    </div>
                </div>

                <!-- Commissions non versées -->
                <div class="bg-white rounded-2xl p-5 border border-orange-100 shadow-sm bg-orange-50/30">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center text-orange-600">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-orange-600 bg-orange-100 px-2 py-1 rounded-full">À PAYER</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">... FCFA</p>
                    <p class="text-xs text-gray-400 mt-1">Solde à verser aux vendeurs</p>
                    <div class="mt-3 pt-3 border-t border-orange-100">
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Vendeurs en attente</span>
                            <span class="font-semibold text-orange-600">...</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- RAPPORTS FINANCIERS -->
        <section class="mb-6">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-file-invoice-dollar text-[#0EA486]"></i>  · Rapports financiers
                </h3>
                <div class="flex gap-2">
                    <button class="exportReportBtn px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2" data-type="csv">
                        <i class="fas fa-file-csv"></i> Export CSV
                    </button>
                    <button class="exportReportBtn px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2" data-type="pdf">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </button>
                </div>
            </div>

            <!-- Tabs rapports -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="border-b border-gray-100 px-4">
                    <nav class="flex gap-1 overflow-x-auto">
                        <button class="report-tab-btn active px-4 py-3 text-sm font-semibold text-[#0EA486] border-b-2 border-[#0EA486] whitespace-nowrap" data-tab="monthly">
                            <i class="fas fa-calendar-alt mr-2"></i>Rapport mensuel
                        </button>
                        <button class="report-tab-btn px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap" data-tab="category">
                            <i class="fas fa-tags mr-2"></i>Par catégorie
                        </button>
                        <button class="report-tab-btn px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap" data-tab="vendor">
                            <i class="fas fa-store mr-2"></i>Par vendeur
                        </button>
                    </nav>
                </div>

                <!-- Tab: Rapport mensuel -->
                <div class="report-tab-content p-5" data-tab="monthly">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <div class="flex items-center gap-2">
                            <input type="month" class="px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                            <button class="viewMonthlyReportBtn px-3 py-2 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-xs font-semibold flex items-center gap-2">
                                <i class="fas fa-eye"></i> Voir le rapport
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                            <p class="text-[10px] text-blue-600 font-semibold uppercase mb-1">CA total</p>
                            <p class="text-xl font-bold text-[#0F172A]">... FCFA</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl border border-emerald-100">
                            <p class="text-[10px] text-emerald-600 font-semibold uppercase mb-1">Commandes</p>
                            <p class="text-xl font-bold text-[#0F172A]">...</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                            <p class="text-[10px] text-purple-600 font-semibold uppercase mb-1">Produits vendus</p>
                            <p class="text-xl font-bold text-[#0F172A]">...</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl border border-amber-100">
                            <p class="text-[10px] text-amber-600 font-semibold uppercase mb-1">Vendeurs actifs</p>
                            <p class="text-xl font-bold text-[#0F172A]">...</p>
                        </div>
                    </div>

                    <div class="mt-4 p-4 bg-gray-50 rounded-xl">
                        <h5 class="text-xs font-semibold text-gray-500 uppercase mb-3 flex items-center gap-2">
                            <i class="fas fa-chart-bar text-[#0EA486]"></i> Évolution sur 6 mois
                        </h5>
                        <div class="flex items-end justify-between gap-2 h-32">
                            <div class="flex-1 flex flex-col items-center gap-1">
                                <div class="w-full bg-[#0EA486]/30 rounded-t-lg" style="height: 40%;"></div>
                                <span class="text-[10px] text-gray-400">Jan</span>
                            </div>
                            <div class="flex-1 flex flex-col items-center gap-1">
                                <div class="w-full bg-[#0EA486]/40 rounded-t-lg" style="height: 55%;"></div>
                                <span class="text-[10px] text-gray-400">Fév</span>
                            </div>
                            <div class="flex-1 flex flex-col items-center gap-1">
                                <div class="w-full bg-[#0EA486]/50 rounded-t-lg" style="height: 45%;"></div>
                                <span class="text-[10px] text-gray-400">Mar</span>
                            </div>
                            <div class="flex-1 flex flex-col items-center gap-1">
                                <div class="w-full bg-[#0EA486]/60 rounded-t-lg" style="height: 70%;"></div>
                                <span class="text-[10px] text-gray-400">Avr</span>
                            </div>
                            <div class="flex-1 flex flex-col items-center gap-1">
                                <div class="w-full bg-[#0EA486]/70 rounded-t-lg" style="height: 60%;"></div>
                                <span class="text-[10px] text-gray-400">Mai</span>
                            </div>
                            <div class="flex-1 flex flex-col items-center gap-1">
                                <div class="w-full bg-[#0EA486] rounded-t-lg" style="height: 85%;"></div>
                                <span class="text-[10px] text-gray-400 font-semibold">Juin</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab: Par catégorie -->
                <div class="report-tab-content hidden p-5" data-tab="category">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th class="px-4 py-3">Catégorie</th>
                                    <th class="px-4 py-3">Produits vendus</th>
                                    <th class="px-4 py-3">CA généré</th>
                                    <th class="px-4 py-3">Commission plateforme</th>
                                    <th class="px-4 py-3">Part du CA</th>
                                    <th class="px-4 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-500">
                                                <i class="fas fa-code text-xs"></i>
                                            </div>
                                            <span class="font-semibold text-[#0F172A] text-sm">...</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">...</td>
                                    <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">... FCFA</td>
                                    <td class="px-4 py-3 text-xs font-semibold text-emerald-600">... FCFA</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <div class="flex-1 bg-gray-100 rounded-full h-2 overflow-hidden">
                                                <div class="bg-[#0EA486] h-full" style="width: 45%;"></div>
                                            </div>
                                            <span class="text-xs font-semibold text-gray-600">...%</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <button class="viewCategoryReportBtn w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center" title="Voir détail">
                                            <i class="fas fa-eye text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab: Par vendeur -->
                <div class="report-tab-content hidden p-5" data-tab="vendor">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th class="px-4 py-3">Vendeur</th>
                                    <th class="px-4 py-3">CA total</th>
                                    <th class="px-4 py-3">Commission vendeur</th>
                                    <th class="px-4 py-3">Versé</th>
                                    <th class="px-4 py-3">Solde dû</th>
                                    <th class="px-4 py-3">Nb ventes</th>
                                    <th class="px-4 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-lg flex items-center justify-center text-emerald-500">
                                                <i class="fas fa-store text-xs"></i>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-[#0F172A] text-sm">...</p>
                                                <p class="text-[11px] text-gray-400">...</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">... FCFA</td>
                                    <td class="px-4 py-3 text-xs font-semibold text-[#0EA486]">... FCFA</td>
                                    <td class="px-4 py-3 text-xs font-semibold text-blue-600">... FCFA</td>
                                    <td class="px-4 py-3 text-xs font-semibold text-orange-600">... FCFA</td>
                                    <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">...</td>
                                    <td class="px-4 py-3 text-right">
                                        <button class="viewVendorReportBtn w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center" title="Voir détail">
                                            <i class="fas fa-eye text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- GESTION DES REMBOURSEMENTS -->
        <section class="mb-8">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-undo text-[#0EA486]"></i> · Gestion des remboursements
                </h3>
                <div class="flex gap-2">
                    <button id="openNewRefundBtn" class="px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white text-xs font-semibold flex items-center gap-2 shadow-sm transition">
                        <i class="fas fa-plus"></i> Initier un remboursement
                    </button>
                </div>
            </div>

            <!-- Stats remboursements -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center text-red-600">
                            <i class="fas fa-undo"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-full">TOTAL</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Remboursements totaux</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">EFFECTUÉS</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Remboursements effectués</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-yellow-50 rounded-xl flex items-center justify-center text-yellow-600">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-yellow-600 bg-yellow-50 px-2 py-1 rounded-full">EN COURS</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Remboursements en cours</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600">
                            <i class="fas fa-coins"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-orange-600 bg-orange-50 px-2 py-1 rounded-full">RÉCUPÉRÉ</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">... FCFA</p>
                    <p class="text-xs text-gray-400 mt-1">Commission récupérée</p>
                </div>
            </div>

            <!-- Filtres remboursements -->
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm mb-4">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex-1 min-w-[220px] relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" placeholder="Rechercher par ID commande, acheteur ou vendeur..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white transition">
                    </div>
                    <select class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 focus:outline-none focus:border-[#0EA486]">
                        <option>Tous les statuts</option>
                        <option>Effectué</option>
                        <option>En cours</option>
                        <option>Annulé</option>
                    </select>
                    <select class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 focus:outline-none focus:border-[#0EA486]">
                        <option>Toutes périodes</option>
                        <option>Ce mois</option>
                        <option>Ce trimestre</option>
                        <option>Cette année</option>
                    </select>
                </div>
            </div>

            <!-- Tableau remboursements -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-3">ID Remboursement</th>
                                <th class="px-4 py-3">Commande</th>
                                <th class="px-4 py-3">Acheteur</th>
                                <th class="px-4 py-3">Vendeur</th>
                                <th class="px-4 py-3">Montant</th>
                                <th class="px-4 py-3">Commission récupérée</th>
                                <th class="px-4 py-3">Motif</th>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Statut</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-4 py-3">
                                    <span class="text-xs font-mono font-semibold text-[#0F172A]">...</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-xs font-mono text-gray-600">...</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div>
                                        <p class="text-xs font-semibold text-[#0F172A]">...</p>
                                        <p class="text-[11px] text-gray-400">...</p>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-xs text-[#0EA486] font-medium">...</span>
                                </td>
                                <td class="px-4 py-3 text-xs font-semibold text-red-600">... FCFA</td>
                                <td class="px-4 py-3 text-xs font-semibold text-orange-600">... FCFA</td>
                                <td class="px-4 py-3">
                                    <span class="text-xs text-gray-600 truncate max-w-[150px] block">...</span>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500">...</td>
                                <td class="px-4 py-3">
                                    <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-1 rounded-full">...</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="viewRefundDetailBtn w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center" title="Voir détail">
                                            <i class="fas fa-eye text-xs"></i>
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
                        <span>1 - 5 sur 42</span>
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

        <footer class="mt-8 text-center text-xs text-gray-400 border-t border-gray-100 pt-6">
            &copy; 2026 NDIGITMARKET · Administration
        </footer>
    </main>

    <!-- MODAL : RAPPORT MENSUEL DÉTAILLÉ -->
    <div id="monthlyReportModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#0EA486]/5 to-transparent">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-[#0EA486]"></i> Rapport mensuel détaillé
                    </h3>
                    <p class="text-xs text-gray-400">Analyse complète du mois sélectionné</p>
                </div>
                <button class="closeMonthlyReportBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="overflow-y-auto p-6 space-y-5">
                <!-- En-tête rapport -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-5 border border-blue-100">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div>
                            <h4 class="text-lg font-bold text-[#0F172A]">Rapport de ...</h4>
                            <p class="text-xs text-gray-500 mt-1">Période du ... au ...</p>
                        </div>
                        <div class="flex gap-2">
                            <button class="exportMonthlyReportBtn px-3 py-2 rounded-lg bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 text-xs font-medium flex items-center gap-2">
                                <i class="fas fa-file-csv"></i> CSV
                            </button>
                            <button class="exportMonthlyReportBtn px-3 py-2 rounded-lg bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 text-xs font-medium flex items-center gap-2">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Stats principales -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="p-4 bg-white rounded-xl border border-gray-100">
                        <p class="text-[10px] text-gray-400 uppercase font-semibold mb-1">CA total</p>
                        <p class="text-xl font-bold text-[#0F172A]">... FCFA</p>
                        <p class="text-[10px] text-emerald-600 mt-1"><i class="fas fa-arrow-up text-[8px]"></i> ...% vs mois précédent</p>
                    </div>
                    <div class="p-4 bg-white rounded-xl border border-gray-100">
                        <p class="text-[10px] text-gray-400 uppercase font-semibold mb-1">Commandes</p>
                        <p class="text-xl font-bold text-[#0F172A]">...</p>
                        <p class="text-[10px] text-emerald-600 mt-1"><i class="fas fa-arrow-up text-[8px]"></i> ...% vs mois précédent</p>
                    </div>
                    <div class="p-4 bg-white rounded-xl border border-gray-100">
                        <p class="text-[10px] text-gray-400 uppercase font-semibold mb-1">Produits vendus</p>
                        <p class="text-xl font-bold text-[#0F172A]">...</p>
                        <p class="text-[10px] text-gray-400 mt-1">... uniques</p>
                    </div>
                    <div class="p-4 bg-white rounded-xl border border-gray-100">
                        <p class="text-[10px] text-gray-400 uppercase font-semibold mb-1">Vendeurs actifs</p>
                        <p class="text-xl font-bold text-[#0F172A]">...</p>
                        <p class="text-[10px] text-emerald-600 mt-1"><i class="fas fa-arrow-up text-[8px]"></i> ... nouveaux</p>
                    </div>
                </div>

                <!-- Répartition financière -->
                <div class="bg-white rounded-2xl p-5 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-coins text-[#0EA486]"></i> Répartition financière
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                            <p class="text-[10px] text-blue-600 font-semibold uppercase mb-1">CA total</p>
                            <p class="text-2xl font-bold text-blue-700">... FCFA</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl border border-emerald-100">
                            <p class="text-[10px] text-emerald-600 font-semibold uppercase mb-1">Commission plateforme (10%)</p>
                            <p class="text-2xl font-bold text-emerald-700">... FCFA</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                            <p class="text-[10px] text-purple-600 font-semibold uppercase mb-1">Commission vendeurs (90%)</p>
                            <p class="text-2xl font-bold text-purple-700">... FCFA</p>
                        </div>
                    </div>
                </div>

                <!-- Top produits -->
                <div class="bg-white rounded-2xl p-5 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-trophy text-amber-500"></i> Top 5 produits vendus
                    </h5>
                    <div class="space-y-2">
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                            <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600 font-bold text-sm">1</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-[#0F172A] truncate">...</p>
                                <p class="text-[11px] text-gray-400">...</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-[#0EA486]">... FCFA</p>
                                <p class="text-[10px] text-gray-400">... ventes</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top vendeurs -->
                <div class="bg-white rounded-2xl p-5 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-store text-[#0EA486]"></i> Top 5 vendeurs
                    </h5>
                    <div class="space-y-2">
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                            <div class="w-8 h-8 bg-[#0EA486]/10 rounded-lg flex items-center justify-center text-[#0EA486] font-bold text-sm">1</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-[#0F172A] truncate">...</p>
                                <p class="text-[11px] text-gray-400">...</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-[#0EA486]">... FCFA</p>
                                <p class="text-[10px] text-gray-400">... ventes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL : RAPPORT PAR CATÉGORIE -->
    <div id="categoryReportModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#0EA486]/5 to-transparent">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-tags text-[#0EA486]"></i> Rapport par catégorie
                    </h3>
                    <p class="text-xs text-gray-400">Détail des ventes et revenus</p>
                </div>
                <button class="closeCategoryReportBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="overflow-y-auto p-6 space-y-5">
                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-5 border border-indigo-100">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600">
                            <i class="fas fa-code text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-[#0F172A]">...</h4>
                            <p class="text-xs text-gray-500 mt-1">Catégorie de produits</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="p-4 bg-white rounded-xl border border-gray-100">
                        <p class="text-[10px] text-gray-400 uppercase font-semibold mb-1">Produits vendus</p>
                        <p class="text-xl font-bold text-[#0F172A]">...</p>
                    </div>
                    <div class="p-4 bg-white rounded-xl border border-gray-100">
                        <p class="text-[10px] text-gray-400 uppercase font-semibold mb-1">CA généré</p>
                        <p class="text-xl font-bold text-[#0F172A]">... FCFA</p>
                    </div>
                    <div class="p-4 bg-white rounded-xl border border-gray-100">
                        <p class="text-[10px] text-gray-400 uppercase font-semibold mb-1">Commission plateforme</p>
                        <p class="text-xl font-bold text-emerald-600">... FCFA</p>
                    </div>
                    <div class="p-4 bg-white rounded-xl border border-gray-100">
                        <p class="text-[10px] text-gray-400 uppercase font-semibold mb-1">Part du CA total</p>
                        <p class="text-xl font-bold text-blue-600">...%</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-list text-[#0EA486]"></i> Produits les plus vendus
                    </h5>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th class="px-4 py-3">Produit</th>
                                    <th class="px-4 py-3">Vendeur</th>
                                    <th class="px-4 py-3">Ventes</th>
                                    <th class="px-4 py-3">CA</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">...</td>
                                    <td class="px-4 py-3 text-xs text-gray-600">...</td>
                                    <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">...</td>
                                    <td class="px-4 py-3 text-xs font-semibold text-[#0EA486]">... FCFA</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL : RAPPORT PAR VENDEUR -->
    <div id="vendorReportModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#0EA486]/5 to-transparent">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-store text-[#0EA486]"></i> Rapport par vendeur
                    </h3>
                    <p class="text-xs text-gray-400">Performance et commissions</p>
                </div>
                <button class="closeVendorReportBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="overflow-y-auto p-6 space-y-5">
                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl p-5 border border-emerald-100">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-[#0EA486] rounded-2xl flex items-center justify-center text-white font-bold text-xl">
                            ...
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-[#0F172A]">...</h4>
                            <p class="text-xs text-gray-500 mt-1">ID ... · Actif depuis ...</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="p-4 bg-white rounded-xl border border-gray-100">
                        <p class="text-[10px] text-gray-400 uppercase font-semibold mb-1">CA total</p>
                        <p class="text-xl font-bold text-[#0F172A]">... FCFA</p>
                    </div>
                    <div class="p-4 bg-white rounded-xl border border-gray-100">
                        <p class="text-[10px] text-gray-400 uppercase font-semibold mb-1">Commission vendeur</p>
                        <p class="text-xl font-bold text-[#0EA486]">... FCFA</p>
                    </div>
                    <div class="p-4 bg-white rounded-xl border border-gray-100">
                        <p class="text-[10px] text-gray-400 uppercase font-semibold mb-1">Versé</p>
                        <p class="text-xl font-bold text-blue-600">... FCFA</p>
                    </div>
                    <div class="p-4 bg-white rounded-xl border border-gray-100">
                        <p class="text-[10px] text-gray-400 uppercase font-semibold mb-1">Solde dû</p>
                        <p class="text-xl font-bold text-orange-600">... FCFA</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-history text-[#0EA486]"></i> Historique des versements
                    </h5>
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
                                    <td class="px-4 py-3 text-xs font-mono text-gray-600">...</td>
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

    <!-- MODAL : INITIER REMBOURSEMENT -->
    <div id="newRefundModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-undo text-red-500"></i> Initier un remboursement
                    </h3>
                    <p class="text-xs text-gray-400">Rembourser une commande existante</p>
                </div>
                <button class="closeNewRefundBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form class="p-6 overflow-y-auto space-y-4">
                <div class="bg-red-50 rounded-xl p-4 border border-red-100">
                    <p class="text-sm text-red-700">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Le remboursement impactera les commissions vendeur et déclenchera des notifications automatiques.
                    </p>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">ID Commande <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:bg-white" placeholder="Ex: CMD-123456" required>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Montant à rembourser (FCFA) <span class="text-red-500">*</span></label>
                    <input type="number" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:bg-white" placeholder="..." required>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Motif du remboursement <span class="text-red-500">*</span></label>
                    <textarea rows="4" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:bg-white resize-none" placeholder="Expliquez la raison du remboursement..." required></textarea>
                </div>

                <div class="space-y-2 p-3 bg-gray-50 rounded-xl">
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-red-500 focus:ring-red-500" required>
                        <span>Récupérer la commission plateforme (10%)</span>
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-red-500 focus:ring-red-500" required>
                        <span>Ajuster le solde du vendeur</span>
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-red-500 focus:ring-red-500" checked>
                        <span>Notifier l'acheteur par email</span>
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-red-500 focus:ring-red-500" checked>
                        <span>Notifier le vendeur par email</span>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                    <button type="button" class="closeNewRefundBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                        Annuler
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-semibold flex items-center gap-2 shadow-sm">
                        <i class="fas fa-undo"></i> Confirmer le remboursement
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL : DÉTAIL REMBOURSEMENT -->
    <div id="refundDetailModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-red-50 to-transparent">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-undo text-red-500"></i> Détail du remboursement
                    </h3>
                    <p class="text-xs text-gray-400">Informations complètes</p>
                </div>
                <button class="closeRefundDetailBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="overflow-y-auto p-6 space-y-5">
                <div class="bg-gradient-to-br from-red-50 to-orange-50 rounded-2xl p-5 border border-red-100">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div>
                            <h4 class="text-lg font-bold text-[#0F172A]">Remboursement #...</h4>
                            <p class="text-xs text-gray-500 mt-1">Effectué le ...</p>
                            <div class="flex flex-wrap gap-2 mt-2">
                                <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-1 rounded-full">Effectué</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-red-600">... FCFA</p>
                            <p class="text-xs text-gray-500">Montant remboursé</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white rounded-2xl p-4 border border-gray-100">
                        <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                            <i class="fas fa-receipt text-[#0EA486]"></i> Commande associée
                        </h5>
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">ID Commande</span>
                                <span class="font-mono font-medium text-[#0F172A]">...</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Date originale</span>
                                <span class="font-medium text-[#0F172A]">...</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Montant initial</span>
                                <span class="font-semibold text-[#0F172A]">... FCFA</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-4 border border-gray-100">
                        <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                            <i class="fas fa-users text-[#0EA486]"></i> Parties impliquées
                        </h5>
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Acheteur</span>
                                <span class="font-medium text-[#0F172A]">...</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Vendeur</span>
                                <span class="font-medium text-[#0F172A]">...</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Commission récupérée</span>
                                <span class="font-semibold text-orange-600">... FCFA</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-comment-dots text-[#0EA486]"></i> Motif du remboursement
                    </h5>
                    <p class="text-sm text-gray-600 leading-relaxed">...</p>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-bell text-[#0EA486]"></i> Notifications envoyées
                    </h5>
                    <div class="space-y-2">
                        <div class="flex items-center gap-3 p-2 bg-gray-50 rounded-lg">
                            <div class="w-7 h-7 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 flex-shrink-0">
                                <i class="fas fa-check text-[10px]"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-[#0F172A]">Email envoyé à l'acheteur</p>
                                <p class="text-[11px] text-gray-400">...</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-2 bg-gray-50 rounded-lg">
                            <div class="w-7 h-7 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 flex-shrink-0">
                                <i class="fas fa-check text-[10px]"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-[#0F172A]">Email envoyé au vendeur</p>
                                <p class="text-[11px] text-gray-400">...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL : EXPORT RAPPORT -->
    <div id="exportReportModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-download text-[#0EA486]"></i> Exporter le rapport
                    </h3>
                    <p class="text-xs text-gray-400">Choisir le format d'export</p>
                </div>
                <button class="closeExportReportBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                    <p class="text-sm text-blue-700">
                        <i class="fas fa-info-circle mr-2"></i>
                        Le rapport sera généré et téléchargé automatiquement.
                    </p>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Période</label>
                    <select class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                        <option>Ce mois</option>
                        <option>Ce trimestre</option>
                        <option>Cette année</option>
                        <option>Personnalisé</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <button class="p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-[#0EA486] transition text-center group">
                        <i class="fas fa-file-csv text-2xl text-emerald-500 mb-2 group-hover:scale-110 transition"></i>
                        <p class="text-sm font-semibold text-[#0F172A]">CSV</p>
                        <p class="text-[10px] text-gray-400 mt-1">Excel compatible</p>
                    </button>
                    <button class="p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-[#0EA486] transition text-center group">
                        <i class="fas fa-file-pdf text-2xl text-red-500 mb-2 group-hover:scale-110 transition"></i>
                        <p class="text-sm font-semibold text-[#0F172A]">PDF</p>
                        <p class="text-[10px] text-gray-400 mt-1">Rapport complet</p>
                    </button>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                <button class="closeExportReportBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                    Annuler
                </button>
            </div>
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

        // Tabs rapports
        (function() {
            const tabBtns = document.querySelectorAll('.report-tab-btn');
            const tabContents = document.querySelectorAll('.report-tab-content');

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

        // Modal rapport mensuel
        (function() {
            const modal = document.getElementById('monthlyReportModal');
            const openBtns = document.querySelectorAll('.viewMonthlyReportBtn');
            const closeBtns = document.querySelectorAll('.closeMonthlyReportBtn');

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
        })();

        // Modal rapport catégorie
        (function() {
            const modal = document.getElementById('categoryReportModal');
            const openBtns = document.querySelectorAll('.viewCategoryReportBtn');
            const closeBtns = document.querySelectorAll('.closeCategoryReportBtn');

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
        })();

        // Modal rapport vendeur
        (function() {
            const modal = document.getElementById('vendorReportModal');
            const openBtns = document.querySelectorAll('.viewVendorReportBtn');
            const closeBtns = document.querySelectorAll('.closeVendorReportBtn');

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
        })();

        // Modal nouveau remboursement
        (function() {
            const modal = document.getElementById('newRefundModal');
            const openBtn = document.getElementById('openNewRefundBtn');
            const closeBtns = document.querySelectorAll('.closeNewRefundBtn');

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
        })();

        // Modal détail remboursement
        (function() {
            const modal = document.getElementById('refundDetailModal');
            const openBtns = document.querySelectorAll('.viewRefundDetailBtn');
            const closeBtns = document.querySelectorAll('.closeRefundDetailBtn');

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
        })();

        // Modal export rapport
        (function() {
            const modal = document.getElementById('exportReportModal');
            const openBtns = document.querySelectorAll('.exportReportBtn');
            const closeBtns = document.querySelectorAll('.closeExportReportBtn');

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

        // Export depuis modal rapport mensuel
        (function() {
            const exportBtns = document.querySelectorAll('.exportMonthlyReportBtn');
            exportBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    alert('Export en cours... (fonctionnalité à implémenter)');
                });
            });
        })();
    </script>
</body>
</html>