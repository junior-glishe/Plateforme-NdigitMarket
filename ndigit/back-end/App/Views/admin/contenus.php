<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NDIGITMARKET Admin · Contenus Éditoriaux</title>

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
                    <h2 class="text-xl md:text-2xl font-bold text-[#0F172A]">Contenus Éditoriaux</h2>
                    <p class="text-xs text-gray-400 hidden sm:block">Bannières publicitaires et codes promotionnels</p>
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

        <!-- BANNIÈRES PUBLICITAIRES -->
        <section class="mb-8">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-image text-[#0EA486]"></i> · Bannières publicitaires
                </h3>
                <div class="flex gap-2">
                    <button id="saveBannerOrderBtn" class="px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2">
                        <i class="fas fa-save"></i> Enregistrer l'ordre
                    </button>
                    <button id="openBannerFormBtn" class="px-4 py-2 rounded-lg bg-[#0EA486] hover:bg-[#0c8f75] text-white text-xs font-semibold flex items-center gap-2 shadow-sm transition">
                        <i class="fas fa-plus"></i> Nouvelle bannière
                    </button>
                </div>
            </div>

            <!-- Stats bannières -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                            <i class="fas fa-images"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-full">TOTAL</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Bannières créées</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">ACTIVES</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Bannières actives</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                            <i class="fas fa-eye"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">VUES</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Affichages totaux</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600">
                            <i class="fas fa-mouse-pointer"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-purple-600 bg-purple-50 px-2 py-1 rounded-full">CLICS</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Clics totaux</p>
                </div>
            </div>

            <!-- Info drag & drop -->
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 mb-4 flex items-start gap-3">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 flex-shrink-0">
                    <i class="fas fa-info-circle text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-xs text-blue-700 font-semibold">Réorganisez les bannières par glisser-déposer</p>
                    <p class="text-[11px] text-blue-600 mt-0.5">L'ordre détermine la rotation des bannières sur le front-end.</p>
                </div>
            </div>

            <!-- Liste des bannières -->
            <div id="bannersList" class="space-y-3">
                <!-- Bannière 1 -->
                <div class="banner-card bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition overflow-hidden" draggable="true" data-id="1">
                    <div class="flex items-stretch">
                        <div class="drag-handle w-12 bg-gray-50 hover:bg-gray-100 flex items-center justify-center cursor-grab active:cursor-grabbing border-r border-gray-100 transition">
                            <i class="fas fa-grip-vertical text-gray-400"></i>
                        </div>
                        <div class="flex-1 p-4 flex flex-col md:flex-row items-start md:items-center gap-4">
                            <!-- Preview bannière -->
                            <div class="w-full md:w-64 h-28 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-xl flex items-center justify-center relative overflow-hidden flex-shrink-0">
                                <div class="absolute inset-0 bg-black/20"></div>
                                <div class="relative text-center text-white px-4">
                                    <p class="text-xs font-bold uppercase tracking-wider">Promo</p>
                                    <p class="text-lg font-extrabold">-50% sur tout</p>
                                </div>
                                <span class="absolute top-2 right-2 text-[9px] font-semibold text-white bg-black/40 px-2 py-0.5 rounded-full">
                                    <i class="fas fa-check mr-1"></i>Active
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <h5 class="text-sm font-bold text-[#0F172A]">Promo Black Friday</h5>
                                    <span class="text-[10px] font-mono text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">
                                        Position 1
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 line-clamp-1 mb-2">...</p>
                                <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500 mb-2">
                                    <span class="flex items-center gap-1"><i class="fas fa-calendar text-gray-400"></i> Du ... au ...</span>
                                    <span class="flex items-center gap-1"><i class="fas fa-link text-gray-400"></i> <span class="truncate max-w-[150px]">...</span></span>
                                </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-[10px] font-semibold text-blue-700 bg-blue-100 px-2 py-0.5 rounded-full">
                                        <i class="fas fa-eye mr-1"></i>... vues
                                    </span>
                                    <span class="text-[10px] font-semibold text-purple-700 bg-purple-100 px-2 py-0.5 rounded-full">
                                        <i class="fas fa-mouse-pointer mr-1"></i>... clics
                                    </span>
                                    <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">
                                        CTR: ...%
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <button class="openBannerPreviewBtn w-9 h-9 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition" title="Aperçu">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                                <button class="openBannerFormBtn w-9 h-9 rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-100 flex items-center justify-center transition" title="Modifier">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <button class="toggleBannerBtn w-9 h-9 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100 flex items-center justify-center transition" title="Désactiver">
                                    <i class="fas fa-toggle-on text-xs"></i>
                                </button>
                                <button class="openBannerStatsBtn w-9 h-9 rounded-lg bg-purple-50 text-purple-600 hover:bg-purple-100 flex items-center justify-center transition" title="Statistiques">
                                    <i class="fas fa-chart-line text-xs"></i>
                                </button>
                                <button class="openDeleteBannerBtn w-9 h-9 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition" title="Supprimer">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                
            </div>
        </section>

        <!-- CODES PROMOTIONNELS -->
        <section class="mb-8">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-ticket-alt text-[#0EA486]"></i>· Codes promotionnels
                </h3>
                <div class="flex gap-2">
                    <button id="openPromoFormBtn" class="px-4 py-2 rounded-lg bg-[#0EA486] hover:bg-[#0c8f75] text-white text-xs font-semibold flex items-center gap-2 shadow-sm transition">
                        <i class="fas fa-plus"></i> Nouveau code promo
                    </button>
                </div>
            </div>

            <!-- Stats codes promo -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-full">TOTAL</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Codes créés</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">ACTIFS</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Codes actifs</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">UTILISÉS</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Utilisations totales</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600">
                            <i class="fas fa-percent"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-orange-600 bg-orange-50 px-2 py-1 rounded-full">REMISES</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">... FCFA</p>
                    <p class="text-xs text-gray-400 mt-1">Total remises accordées</p>
                </div>
            </div>

            <!-- Filtres -->
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm mb-4">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex-1 min-w-[220px] relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" placeholder="Rechercher un code promo..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white transition">
                    </div>
                    <select class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 focus:outline-none focus:border-[#0EA486]">
                        <option>Tous les statuts</option>
                        <option>Actifs</option>
                        <option>Inactifs</option>
                        <option>Expirés</option>
                    </select>
                    <select class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 focus:outline-none focus:border-[#0EA486]">
                        <option>Tous les types</option>
                        <option>Pourcentage</option>
                        <option>Montant fixe</option>
                    </select>
                </div>
            </div>

            <!-- Tableau codes promo -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-3">Code</th>
                                <th class="px-4 py-3">Type</th>
                                <th class="px-4 py-3">Valeur</th>
                                <th class="px-4 py-3">Conditions</th>
                                <th class="px-4 py-3">Utilisations</th>
                                <th class="px-4 py-3">Expiration</th>
                                <th class="px-4 py-3">Statut</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-gradient-to-br from-purple-100 to-pink-100 rounded-lg flex items-center justify-center text-purple-600">
                                            <i class="fas fa-ticket-alt text-xs"></i>
                                        </div>
                                        <span class="font-mono font-bold text-[#0F172A] text-sm">...</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-[10px] font-semibold text-purple-700 bg-purple-100 px-2 py-1 rounded-full">...</span>
                                </td>
                                <td class="px-4 py-3 text-xs font-bold text-[#0EA486]">...</td>
                                <td class="px-4 py-3">
                                    <div class="text-xs text-gray-500 space-y-0.5">
                                        <p>Min: ... FCFA</p>
                                        <p>Max: ... utilisations</p>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-gray-100 rounded-full h-1.5 overflow-hidden w-16">
                                            <div class="bg-[#0EA486] h-full" style="width: 45%;"></div>
                                        </div>
                                        <span class="text-xs font-semibold text-[#0F172A]">.../...</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500">...</td>
                                <td class="px-4 py-3">
                                    <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-1 rounded-full">...</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="openPromoHistoryBtn w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center" title="Historique">
                                            <i class="fas fa-history text-xs"></i>
                                        </button>
                                        <button class="openPromoFormBtn w-8 h-8 rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-100 flex items-center justify-center" title="Modifier">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                        <button class="togglePromoBtn w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100 flex items-center justify-center" title="Désactiver">
                                            <i class="fas fa-toggle-on text-xs"></i>
                                        </button>
                                        <button class="openDeletePromoBtn w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center" title="Supprimer">
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
                        <span>1 - 5 sur 24</span>
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

    <!-- MODAL : CRÉER / MODIFIER BANNIÈRE -->
    <div id="bannerFormModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-5xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#0EA486]/5 to-transparent">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-image text-[#0EA486]"></i> <span id="bannerFormTitle">Nouvelle bannière</span>
                    </h3>
                    <p class="text-xs text-gray-400">Créer ou modifier une bannière publicitaire</p>
                </div>
                <button class="closeBannerFormBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                    <!-- Formulaire -->
                    <form class="p-6 space-y-4 border-r border-gray-100">
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Titre <span class="text-red-500">*</span></label>
                            <input type="text" id="bannerTitle" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="Ex: Promo Black Friday">
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Sous-titre</label>
                            <input type="text" id="bannerSubtitle" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="Ex: -50% sur tous les templates">
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Image de fond <span class="text-red-500">*</span></label>
                            <div class="border-2 border-dashed border-gray-200 rounded-xl p-5 text-center hover:border-[#0EA486] transition cursor-pointer">
                                <i class="fas fa-cloud-upload-alt text-2xl text-gray-300 mb-2"></i>
                                <p class="text-xs text-gray-500">Glissez-déposez ou <span class="text-[#0EA486] font-semibold">parcourir</span></p>
                                <p class="text-[10px] text-gray-400 mt-1">PNG, JPG · max 2 MB · 1200x400 recommandé</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-semibold text-gray-600 mb-1 block">Texte du bouton</label>
                                <input type="text" id="bannerButtonText" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="Ex: Voir les offres">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-600 mb-1 block">URL de destination <span class="text-red-500">*</span></label>
                                <input type="url" id="bannerUrl" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="https://...">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-semibold text-gray-600 mb-1 block">Date de début <span class="text-red-500">*</span></label>
                                <input type="datetime-local" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-600 mb-1 block">Date de fin <span class="text-red-500">*</span></label>
                                <input type="datetime-local" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Statut</label>
                            <div class="flex gap-2">
                                <label class="flex-1 flex items-center gap-2 p-3 bg-emerald-50 border border-emerald-200 rounded-xl cursor-pointer">
                                    <input type="radio" name="bannerStatus" value="active" checked class="w-4 h-4 text-[#0EA486]">
                                    <div>
                                        <p class="text-xs font-semibold text-emerald-700">Active</p>
                                        <p class="text-[10px] text-emerald-600">Visible sur le site</p>
                                    </div>
                                </label>
                                <label class="flex-1 flex items-center gap-2 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer">
                                    <input type="radio" name="bannerStatus" value="inactive" class="w-4 h-4 text-gray-500">
                                    <div>
                                        <p class="text-xs font-semibold text-gray-600">Inactive</p>
                                        <p class="text-[10px] text-gray-500">Masquée</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                            <button type="button" class="closeBannerFormBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                                Annuler
                            </button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </div>
                    </form>

                    <!-- Aperçu en temps réel -->
                    <div class="p-6 bg-gray-50">
                        <h5 class="text-xs font-semibold text-gray-500 uppercase mb-3 flex items-center gap-2">
                            <i class="fas fa-eye text-[#0EA486]"></i> Aperçu en temps réel
                        </h5>
                        <div id="bannerPreview" class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-2xl h-64 flex items-center justify-center relative overflow-hidden shadow-lg">
                            <div class="absolute inset-0 bg-black/30"></div>
                            <div class="relative text-center text-white px-6">
                                <p id="previewSubtitle" class="text-sm font-bold uppercase tracking-wider mb-2">...</p>
                                <p id="previewTitle" class="text-3xl font-extrabold mb-4">...</p>
                                <button id="previewButton" class="px-6 py-2.5 bg-white text-[#0F172A] rounded-xl text-sm font-bold hover:scale-105 transition">
                                    ...
                                </button>
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-2 text-center">
                            <i class="fas fa-info-circle mr-1"></i> L'aperçu se met à jour en temps réel
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL : APERÇU BANNIÈRE -->
    <div id="bannerPreviewModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-eye text-[#0EA486]"></i> Aperçu de la bannière
                    </h3>
                    <p class="text-xs text-gray-400">Visualisation complète</p>
                </div>
                <button class="closeBannerPreviewBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="overflow-y-auto p-6 space-y-5">
                <!-- Preview desktop -->
                <div>
                    <h5 class="text-xs font-semibold text-gray-500 uppercase mb-2 flex items-center gap-2">
                        <i class="fas fa-desktop text-[#0EA486]"></i> Version desktop
                    </h5>
                    <div class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-2xl h-64 flex items-center justify-center relative overflow-hidden shadow-lg">
                        <div class="absolute inset-0 bg-black/30"></div>
                        <div class="relative text-center text-white px-6">
                            <p class="text-sm font-bold uppercase tracking-wider mb-2">...</p>
                            <p class="text-3xl font-extrabold mb-4">...</p>
                            <button class="px-6 py-2.5 bg-white text-[#0F172A] rounded-xl text-sm font-bold">
                                ...
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Preview mobile -->
                <div>
                    <h5 class="text-xs font-semibold text-gray-500 uppercase mb-2 flex items-center gap-2">
                        <i class="fas fa-mobile-alt text-[#0EA486]"></i> Version mobile
                    </h5>
                    <div class="max-w-sm mx-auto">
                        <div class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-2xl h-48 flex items-center justify-center relative overflow-hidden shadow-lg">
                            <div class="absolute inset-0 bg-black/30"></div>
                            <div class="relative text-center text-white px-4">
                                <p class="text-xs font-bold uppercase tracking-wider mb-1">...</p>
                                <p class="text-xl font-extrabold mb-3">...</p>
                                <button class="px-4 py-2 bg-white text-[#0F172A] rounded-lg text-xs font-bold">
                                    ...
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Infos -->
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-info-circle text-[#0EA486]"></i> Informations
                    </h5>
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-gray-500">Titre</span>
                            <span class="font-medium text-[#0F172A]">...</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-gray-500">URL</span>
                            <span class="font-mono text-[#0F172A] truncate max-w-[200px]">...</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-gray-500">Période</span>
                            <span class="font-medium text-[#0F172A]">Du ... au ...</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-gray-500">Statut</span>
                            <span class="font-medium text-emerald-600">Active</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL : STATS BANNIÈRE -->
    <div id="bannerStatsModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-chart-line text-purple-500"></i> Statistiques de la bannière
                    </h3>
                    <p class="text-xs text-gray-400">Performances détaillées</p>
                </div>
                <button class="closeBannerStatsBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="overflow-y-auto p-6 space-y-5">
                <!-- Stats cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                        <p class="text-[10px] text-blue-600 font-semibold uppercase mb-1">Affichages</p>
                        <p class="text-2xl font-bold text-[#0F172A]">...</p>
                        <p class="text-[10px] text-emerald-600 mt-1"><i class="fas fa-arrow-up text-[8px]"></i> ...%</p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                        <p class="text-[10px] text-purple-600 font-semibold uppercase mb-1">Clics</p>
                        <p class="text-2xl font-bold text-[#0F172A]">...</p>
                        <p class="text-[10px] text-emerald-600 mt-1"><i class="fas fa-arrow-up text-[8px]"></i> ...%</p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl border border-emerald-100">
                        <p class="text-[10px] text-emerald-600 font-semibold uppercase mb-1">CTR</p>
                        <p class="text-2xl font-bold text-[#0F172A]">...%</p>
                        <p class="text-[10px] text-gray-400 mt-1">Taux de clic</p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl border border-amber-100">
                        <p class="text-[10px] text-amber-600 font-semibold uppercase mb-1">Jours actifs</p>
                        <p class="text-2xl font-bold text-[#0F172A]">...</p>
                        <p class="text-[10px] text-gray-400 mt-1">Depuis activation</p>
                    </div>
                </div>

                <!-- Graphique -->
                <div class="bg-white rounded-2xl p-5 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-chart-bar text-[#0EA486]"></i> Évolution sur 7 jours
                    </h5>
                    <div class="flex items-end justify-between gap-2 h-40">
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <div class="w-full bg-blue-200 rounded-t-lg" style="height: 30%;"></div>
                            <div class="w-full bg-purple-200 rounded-t-lg" style="height: 15%;"></div>
                            <span class="text-[10px] text-gray-400">Lun</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <div class="w-full bg-blue-300 rounded-t-lg" style="height: 45%;"></div>
                            <div class="w-full bg-purple-300 rounded-t-lg" style="height: 20%;"></div>
                            <span class="text-[10px] text-gray-400">Mar</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <div class="w-full bg-blue-400 rounded-t-lg" style="height: 55%;"></div>
                            <div class="w-full bg-purple-400 rounded-t-lg" style="height: 25%;"></div>
                            <span class="text-[10px] text-gray-400">Mer</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <div class="w-full bg-blue-500 rounded-t-lg" style="height: 70%;"></div>
                            <div class="w-full bg-purple-500 rounded-t-lg" style="height: 35%;"></div>
                            <span class="text-[10px] text-gray-400">Jeu</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <div class="w-full bg-blue-600 rounded-t-lg" style="height: 85%;"></div>
                            <div class="w-full bg-purple-600 rounded-t-lg" style="height: 45%;"></div>
                            <span class="text-[10px] text-gray-400">Ven</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <div class="w-full bg-blue-700 rounded-t-lg" style="height: 95%;"></div>
                            <div class="w-full bg-purple-700 rounded-t-lg" style="height: 55%;"></div>
                            <span class="text-[10px] text-gray-400">Sam</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <div class="w-full bg-[#0EA486] rounded-t-lg" style="height: 100%;"></div>
                            <div class="w-full bg-purple-800 rounded-t-lg" style="height: 60%;"></div>
                            <span class="text-[10px] text-gray-400 font-semibold">Dim</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 mt-3 text-xs">
                        <span class="flex items-center gap-2"><span class="w-3 h-3 bg-blue-500 rounded"></span> Affichages</span>
                        <span class="flex items-center gap-2"><span class="w-3 h-3 bg-purple-500 rounded"></span> Clics</span>
                    </div>
                </div>

                <!-- Top referrers -->
                <div class="bg-white rounded-2xl p-5 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-globe text-[#0EA486]"></i> Pages de destination les plus cliquées
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

    <!-- MODAL : TOGGLE BANNIÈRE -->
    <div id="toggleBannerModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 id="toggleBannerTitle" class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-toggle-on text-yellow-500"></i> Changer le statut
                    </h3>
                    <p class="text-xs text-gray-400">Activer ou désactiver la bannière</p>
                </div>
                <button class="closeToggleBannerBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div id="toggleBannerInfo" class="bg-yellow-50 rounded-xl p-4 border border-yellow-100">
                    <p class="text-sm text-yellow-700">
                        <i class="fas fa-info-circle mr-2"></i>
                        <span id="toggleBannerInfoText">La bannière sera masquée du site.</span>
                    </p>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                <button class="closeToggleBannerBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                    Annuler
                </button>
                <button id="confirmToggleBannerBtn" class="px-5 py-2.5 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                    <i class="fas fa-check"></i> Confirmer
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL : SUPPRIMER BANNIÈRE -->
    <div id="deleteBannerModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-trash text-red-500"></i> Supprimer la bannière
                    </h3>
                    <p class="text-xs text-gray-400">Action irréversible</p>
                </div>
                <button class="closeDeleteBannerBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="bg-red-50 rounded-xl p-4 border border-red-100">
                    <p class="text-sm text-red-700">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Attention :</strong> Cette action est irréversible. Toutes les statistiques associées seront perdues.
                    </p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Tapez "SUPPRIMER" pour confirmer</label>
                    <input type="text" id="deleteBannerConfirmInput" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:bg-white" placeholder="SUPPRIMER">
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                <button class="closeDeleteBannerBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                    Annuler
                </button>
                <button id="confirmDeleteBannerBtn" class="px-5 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition opacity-50 cursor-not-allowed" disabled>
                    <i class="fas fa-trash"></i> Supprimer définitivement
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL : CRÉER / MODIFIER CODE PROMO -->
    <div id="promoFormModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#0EA486]/5 to-transparent">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-ticket-alt text-[#0EA486]"></i> <span id="promoFormTitle">Nouveau code promo</span>
                    </h3>
                    <p class="text-xs text-gray-400">Créer ou modifier un code promotionnel</p>
                </div>
                <button class="closePromoFormBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form class="p-6 overflow-y-auto space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Code -->
                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Code promotionnel <span class="text-red-500">*</span></label>
                        <div class="flex gap-2">
                            <input type="text" id="promoCode" class="flex-1 px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-mono font-bold uppercase focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="Ex: BLACKFRIDAY50">
                            <button type="button" id="generatePromoCodeBtn" class="px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium flex items-center gap-2">
                                <i class="fas fa-magic"></i> Générer
                            </button>
                        </div>
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Type de remise <span class="text-red-500">*</span></label>
                        <select id="promoType" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                            <option value="percentage">Pourcentage (%)</option>
                            <option value="fixed">Montant fixe (FCFA)</option>
                        </select>
                    </div>

                    <!-- Valeur -->
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Valeur <span class="text-red-500">*</span></label>
                        <input type="number" id="promoValue" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="Ex: 50">
                    </div>

                    <!-- Montant minimum -->
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Montant minimum d'achat (FCFA)</label>
                        <input type="number" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="Ex: 10000">
                    </div>

                    <!-- Utilisations max -->
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Nombre d'utilisations max</label>
                        <input type="number" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="Ex: 100">
                    </div>

                    <!-- Date expiration -->
                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Date d'expiration <span class="text-red-500">*</span></label>
                        <input type="datetime-local" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                    </div>

                    <!-- Restriction catégorie -->
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Restreindre à une catégorie</label>
                        <select class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                            <option>Toutes les catégories</option>
                            <option>WordPress</option>
                            <option>HTML</option>
                            <option>PHP</option>
                            <option>React</option>
                            <option>PSD</option>
                            <option>Plugin</option>
                        </select>
                    </div>

                    <!-- Restriction produit -->
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Restreindre à un produit</label>
                        <input type="text" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="ID ou nom du produit...">
                    </div>

                    <!-- Restriction utilisateur -->
                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Restreindre à un utilisateur spécifique</label>
                        <input type="text" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="Email ou ID de l'utilisateur...">
                        <p class="text-[10px] text-gray-400 mt-1">Laissez vide pour que le code soit utilisable par tous</p>
                    </div>

                    <!-- Statut -->
                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Statut</label>
                        <div class="flex gap-2">
                            <label class="flex-1 flex items-center gap-2 p-3 bg-emerald-50 border border-emerald-200 rounded-xl cursor-pointer">
                                <input type="radio" name="promoStatus" value="active" checked class="w-4 h-4 text-[#0EA486]">
                                <div>
                                    <p class="text-xs font-semibold text-emerald-700">Actif</p>
                                    <p class="text-[10px] text-emerald-600">Utilisable</p>
                                </div>
                            </label>
                            <label class="flex-1 flex items-center gap-2 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer">
                                <input type="radio" name="promoStatus" value="inactive" class="w-4 h-4 text-gray-500">
                                <div>
                                    <p class="text-xs font-semibold text-gray-600">Inactif</p>
                                    <p class="text-[10px] text-gray-500">Désactivé</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                    <button type="button" class="closePromoFormBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                        Annuler
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL : HISTORIQUE CODE PROMO -->
    <div id="promoHistoryModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-history text-blue-500"></i> Historique d'utilisation
                    </h3>
                    <p class="text-xs text-gray-400">Toutes les utilisations du code promo</p>
                </div>
                <button class="closePromoHistoryBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="overflow-y-auto p-6 space-y-5">
                <!-- En-tête code -->
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-5 border border-purple-100">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center text-white">
                            <i class="fas fa-ticket-alt text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-lg font-bold font-mono text-[#0F172A]">...</h4>
                            <p class="text-xs text-gray-500 mt-1">Type: ... · Valeur: ...</p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-[#0EA486]">...</p>
                            <p class="text-xs text-gray-500">Utilisations</p>
                        </div>
                    </div>
                </div>

                <!-- Tableau historique -->
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">Utilisateur</th>
                                    <th class="px-4 py-3">Commande</th>
                                    <th class="px-4 py-3">Montant initial</th>
                                    <th class="px-4 py-3">Remise</th>
                                    <th class="px-4 py-3">Montant final</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-4 py-3 text-xs text-gray-500">...</td>
                                    <td class="px-4 py-3">
                                        <div>
                                            <p class="text-xs font-semibold text-[#0F172A]">...</p>
                                            <p class="text-[11px] text-gray-400">...</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-xs font-mono text-gray-600">...</span>
                                    </td>
                                    <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">... FCFA</td>
                                    <td class="px-4 py-3 text-xs font-bold text-red-600">-... FCFA</td>
                                    <td class="px-4 py-3 text-xs font-bold text-[#0EA486]">... FCFA</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL : TOGGLE CODE PROMO -->
    <div id="togglePromoModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 id="togglePromoTitle" class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-toggle-on text-yellow-500"></i> Changer le statut
                    </h3>
                    <p class="text-xs text-gray-400">Activer ou désactiver le code promo</p>
                </div>
                <button class="closeTogglePromoBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div id="togglePromoInfo" class="bg-yellow-50 rounded-xl p-4 border border-yellow-100">
                    <p class="text-sm text-yellow-700">
                        <i class="fas fa-info-circle mr-2"></i>
                        <span id="togglePromoInfoText">Le code ne sera plus utilisable.</span>
                    </p>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                <button class="closeTogglePromoBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                    Annuler
                </button>
                <button id="confirmTogglePromoBtn" class="px-5 py-2.5 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                    <i class="fas fa-check"></i> Confirmer
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL : SUPPRIMER CODE PROMO -->
    <div id="deletePromoModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-trash text-red-500"></i> Supprimer le code promo
                    </h3>
                    <p class="text-xs text-gray-400">Action irréversible</p>
                </div>
                <button class="closeDeletePromoBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="bg-red-50 rounded-xl p-4 border border-red-100">
                    <p class="text-sm text-red-700">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Attention :</strong> Cette action est irréversible. L'historique d'utilisation sera conservé mais le code ne sera plus disponible.
                    </p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Tapez "SUPPRIMER" pour confirmer</label>
                    <input type="text" id="deletePromoConfirmInput" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:bg-white" placeholder="SUPPRIMER">
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                <button class="closeDeletePromoBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                    Annuler
                </button>
                <button id="confirmDeletePromoBtn" class="px-5 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition opacity-50 cursor-not-allowed" disabled>
                    <i class="fas fa-trash"></i> Supprimer définitivement
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

        // Modal formulaire bannière
        (function() {
            const modal = document.getElementById('bannerFormModal');
            const openBtns = document.querySelectorAll('.openBannerFormBtn, #openBannerFormBtn');
            const closeBtns = document.querySelectorAll('.closeBannerFormBtn');
            const title = document.getElementById('bannerFormTitle');

            function openModal(isEdit = false) {
                title.textContent = isEdit ? 'Modifier la bannière' : 'Nouvelle bannière';
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }

            openBtns.forEach(btn => btn.addEventListener('click', function() {
                const isEdit = this.classList.contains('openBannerFormBtn');
                openModal(isEdit);
            }));
            closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });

            // Aperçu en temps réel
            const titleInput = document.getElementById('bannerTitle');
            const subtitleInput = document.getElementById('bannerSubtitle');
            const buttonText = document.getElementById('bannerButtonText');
            const previewTitle = document.getElementById('previewTitle');
            const previewSubtitle = document.getElementById('previewSubtitle');
            const previewButton = document.getElementById('previewButton');

            function updatePreview() {
                previewTitle.textContent = titleInput.value || 'Titre de la bannière';
                previewSubtitle.textContent = subtitleInput.value || 'Sous-titre';
                previewButton.textContent = buttonText.value || 'Cliquez ici';
            }

            titleInput.addEventListener('input', updatePreview);
            subtitleInput.addEventListener('input', updatePreview);
            buttonText.addEventListener('input', updatePreview);

            // Submit
            modal.querySelector('form').addEventListener('submit', function(e) {
                e.preventDefault();
                closeModal();
                showToast('Succès', 'Bannière enregistrée avec succès', 'success');
            });
        })();

        // Modal aperçu bannière
        setupModal('bannerPreviewModal', '.openBannerPreviewBtn', '.closeBannerPreviewBtn');

        // Modal stats bannière
        setupModal('bannerStatsModal', '.openBannerStatsBtn', '.closeBannerStatsBtn');

        // Modal toggle bannière
        (function() {
            const modal = document.getElementById('toggleBannerModal');
            const openBtns = document.querySelectorAll('.toggleBannerBtn');
            const closeBtns = document.querySelectorAll('.closeToggleBannerBtn');
            const confirmBtn = document.getElementById('confirmToggleBannerBtn');
            const title = document.getElementById('toggleBannerTitle');
            const infoText = document.getElementById('toggleBannerInfoText');
            const info = document.getElementById('toggleBannerInfo');

            function openModal() {
                const btn = this;
                const isOn = btn.querySelector('.fa-toggle-on');
                
                if (isOn) {
                    title.innerHTML = '<i class="fas fa-toggle-off text-gray-500"></i> Désactiver la bannière';
                    infoText.textContent = 'La bannière sera masquée du site.';
                    info.className = 'bg-yellow-50 rounded-xl p-4 border border-yellow-100';
                    confirmBtn.className = 'px-5 py-2.5 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition';
                    confirmBtn.innerHTML = '<i class="fas fa-pause"></i> Désactiver';
                } else {
                    title.innerHTML = '<i class="fas fa-toggle-on text-emerald-500"></i> Activer la bannière';
                    infoText.textContent = 'La bannière sera à nouveau visible sur le site.';
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
                showToast('Statut modifié', 'Le statut de la bannière a été mis à jour', 'success');
            });
        })();

        // Modal suppression bannière
        (function() {
            const modal = document.getElementById('deleteBannerModal');
            const openBtns = document.querySelectorAll('.openDeleteBannerBtn');
            const closeBtns = document.querySelectorAll('.closeDeleteBannerBtn');
            const input = document.getElementById('deleteBannerConfirmInput');
            const confirmBtn = document.getElementById('confirmDeleteBannerBtn');

            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
                input.value = '';
                confirmBtn.disabled = true;
                confirmBtn.classList.add('opacity-50', 'cursor-not-allowed');
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

            input.addEventListener('input', function() {
                if (this.value === 'SUPPRIMER') {
                    confirmBtn.disabled = false;
                    confirmBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    confirmBtn.disabled = true;
                    confirmBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            });

            confirmBtn.addEventListener('click', function() {
                if (!this.disabled) {
                    closeModal();
                    showToast('Supprimé', 'Bannière supprimée avec succès', 'success');
                }
            });
        })();

        // Drag & Drop bannières
        (function() {
            const list = document.getElementById('bannersList');
            let draggedItem = null;

            list.querySelectorAll('.banner-card').forEach(card => {
                card.addEventListener('dragstart', function(e) {
                    draggedItem = this;
                    setTimeout(() => this.classList.add('opacity-50', 'scale-95'), 0);
                });

                card.addEventListener('dragend', function() {
                    this.classList.remove('opacity-50', 'scale-95');
                    list.querySelectorAll('.banner-card').forEach(c => {
                        c.classList.remove('border-t-2', 'border-[#0EA486]');
                    });
                    draggedItem = null;
                });

                card.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    if (draggedItem !== this) {
                        const rect = this.getBoundingClientRect();
                        const midY = rect.top + rect.height / 2;
                        if (e.clientY < midY) {
                            this.classList.add('border-t-2', 'border-[#0EA486]');
                        } else {
                            this.classList.remove('border-t-2', 'border-[#0EA486]');
                        }
                    }
                });

                card.addEventListener('dragleave', function() {
                    this.classList.remove('border-t-2', 'border-[#0EA486]');
                });

                card.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.classList.remove('border-t-2', 'border-[#0EA486]');
                    
                    if (draggedItem !== this) {
                        const rect = this.getBoundingClientRect();
                        const midY = rect.top + rect.height / 2;
                        if (e.clientY < midY) {
                            list.insertBefore(draggedItem, this);
                        } else {
                            list.insertBefore(draggedItem, this.nextSibling);
                        }
                    }
                });
            });

            document.getElementById('saveBannerOrderBtn').addEventListener('click', function() {
                showToast('Ordre enregistré', 'L\'ordre des bannières a été sauvegardé', 'success');
            });
        })();

        // Modal formulaire code promo
        (function() {
            const modal = document.getElementById('promoFormModal');
            const openBtns = document.querySelectorAll('.openPromoFormBtn, #openPromoFormBtn');
            const closeBtns = document.querySelectorAll('.closePromoFormBtn');
            const title = document.getElementById('promoFormTitle');

            function openModal(isEdit = false) {
                title.textContent = isEdit ? 'Modifier le code promo' : 'Nouveau code promo';
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }

            openBtns.forEach(btn => btn.addEventListener('click', function() {
                const isEdit = this.classList.contains('openPromoFormBtn');
                openModal(isEdit);
            }));
            closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });

            // Générer code aléatoire
            document.getElementById('generatePromoCodeBtn').addEventListener('click', function() {
                const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                let code = 'PROMO-';
                for (let i = 0; i < 8; i++) {
                    code += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                document.getElementById('promoCode').value = code;
            });

            // Submit
            modal.querySelector('form').addEventListener('submit', function(e) {
                e.preventDefault();
                closeModal();
                showToast('Succès', 'Code promo enregistré avec succès', 'success');
            });
        })();

        // Modal historique code promo
        setupModal('promoHistoryModal', '.openPromoHistoryBtn', '.closePromoHistoryBtn');

        // Modal toggle code promo
        (function() {
            const modal = document.getElementById('togglePromoModal');
            const openBtns = document.querySelectorAll('.togglePromoBtn');
            const closeBtns = document.querySelectorAll('.closeTogglePromoBtn');
            const confirmBtn = document.getElementById('confirmTogglePromoBtn');
            const title = document.getElementById('togglePromoTitle');
            const infoText = document.getElementById('togglePromoInfoText');
            const info = document.getElementById('togglePromoInfo');

            function openModal() {
                const btn = this;
                const isOn = btn.querySelector('.fa-toggle-on');
                
                if (isOn) {
                    title.innerHTML = '<i class="fas fa-toggle-off text-gray-500"></i> Désactiver le code';
                    infoText.textContent = 'Le code ne sera plus utilisable par les clients.';
                    info.className = 'bg-yellow-50 rounded-xl p-4 border border-yellow-100';
                    confirmBtn.className = 'px-5 py-2.5 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition';
                    confirmBtn.innerHTML = '<i class="fas fa-pause"></i> Désactiver';
                } else {
                    title.innerHTML = '<i class="fas fa-toggle-on text-emerald-500"></i> Activer le code';
                    infoText.textContent = 'Le code sera à nouveau utilisable par les clients.';
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
                showToast('Statut modifié', 'Le statut du code promo a été mis à jour', 'success');
            });
        })();

        // Modal suppression code promo
        (function() {
            const modal = document.getElementById('deletePromoModal');
            const openBtns = document.querySelectorAll('.openDeletePromoBtn');
            const closeBtns = document.querySelectorAll('.closeDeletePromoBtn');
            const input = document.getElementById('deletePromoConfirmInput');
            const confirmBtn = document.getElementById('confirmDeletePromoBtn');

            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
                input.value = '';
                confirmBtn.disabled = true;
                confirmBtn.classList.add('opacity-50', 'cursor-not-allowed');
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

            input.addEventListener('input', function() {
                if (this.value === 'SUPPRIMER') {
                    confirmBtn.disabled = false;
                    confirmBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    confirmBtn.disabled = true;
                    confirmBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            });

            confirmBtn.addEventListener('click', function() {
                if (!this.disabled) {
                    closeModal();
                    showToast('Supprimé', 'Code promo supprimé avec succès', 'success');
                }
            });
        })();

        // ESC pour fermer tous les modals
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('[id$="Modal"]:not(.hidden)').forEach(modal => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                });
                document.body.style.overflow = '';
            }
        });
    </script>
</body>
</html>