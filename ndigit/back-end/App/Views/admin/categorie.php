<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NDIGITMARKET Admin · Gestion des Catégories</title>

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
                    <h2 class="text-xl md:text-2xl font-bold text-[#0F172A]">Gestion des Catégories</h2>
                    <p class="text-xs text-gray-400 hidden sm:block">Organiser, créer et gérer les catégories de produits</p>
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

        <!-- STATISTIQUES -->
        <section class="mb-6">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-chart-pie text-[#0EA486]"></i> · Vue d'ensemble des catégories
                </h3>
            </div>

            <!-- Cartes de statistiques -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                            <i class="fas fa-tags"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-full">TOTAL</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Catégories totales</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">ACTIVES</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Catégories actives</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-gray-500">
                            <i class="fas fa-pause-circle"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-full">INACTIVES</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Catégories désactivées</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600">
                            <i class="fas fa-box"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-purple-600 bg-purple-50 px-2 py-1 rounded-full">PRODUITS</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Produits associés</p>
                </div>
            </div>
        </section>

        <!-- 4.8.1 LISTE DES CATÉGORIES -->
        <section class="mb-6">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-list text-[#0EA486]"></i> · Liste des catégories
                </h3>
                <div class="flex gap-2">
                    <button id="saveOrderBtn" class="px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2 transition">
                        <i class="fas fa-save"></i> Enregistrer l'ordre
                    </button>
                    <button id="openCategoryFormBtn" class="px-4 py-2 rounded-lg bg-[#0EA486] hover:bg-[#0c8f75] text-white text-xs font-semibold flex items-center gap-2 shadow-sm transition">
                        <i class="fas fa-plus"></i> Nouvelle catégorie
                    </button>
                </div>
            </div>

            <!-- Barre de recherche + filtres -->
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm mb-4">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex-1 min-w-[220px] relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" placeholder="Rechercher une catégorie..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white transition">
                    </div>
                    <select class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 focus:outline-none focus:border-[#0EA486]">
                        <option>Tous les statuts</option>
                        <option>Actives</option>
                        <option>Inactives</option>
                    </select>
                    <select class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 focus:outline-none focus:border-[#0EA486]">
                        <option>Trier par : Ordre d'affichage</option>
                        <option>Nombre de produits</option>
                        <option>Nom (A-Z)</option>
                        <option>Date de création</option>
                    </select>
                </div>
            </div>

            <!-- Info drag & drop -->
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 mb-4 flex items-start gap-3">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 flex-shrink-0">
                    <i class="fas fa-info-circle text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-xs text-blue-700 font-semibold">Réorganisez les catégories par glisser-déposer</p>
                    <p class="text-[11px] text-blue-600 mt-0.5">Utilisez la poignée <i class="fas fa-grip-vertical"></i> à gauche de chaque carte pour réorganiser l'ordre d'affichage sur le site.</p>
                </div>
            </div>

            <!-- Liste des catégories -->
            <div id="categoriesList" class="space-y-3">
                <!-- Catégorie 1 : WordPress -->
                <div class="category-card bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition overflow-hidden" draggable="true" data-id="1">
                    <div class="flex items-stretch">
                        <!-- Drag handle -->
                        <div class="drag-handle w-12 bg-gray-50 hover:bg-gray-100 flex items-center justify-center cursor-grab active:cursor-grabbing border-r border-gray-100 transition">
                            <i class="fas fa-grip-vertical text-gray-400"></i>
                        </div>
                        <!-- Contenu -->
                        <div class="flex-1 p-4 flex flex-col md:flex-row items-start md:items-center gap-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-globe text-3xl text-blue-600"></i>
                        </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <h5 class="text-sm font-bold text-[#0F172A]">WordPress</h5>
                                    <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">
                                        <i class="fas fa-check mr-1"></i>Active
                                    </span>
                                    <span class="text-[10px] font-mono text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">
                                        /wordpress
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 line-clamp-1 mb-2">Thèmes et templates WordPress pour sites professionnels</p>
                                <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                    <span class="flex items-center gap-1"><i class="fas fa-box text-[#0EA486]"></i> <strong class="text-[#0F172A]">...</strong> produits</span>
                                    <span class="flex items-center gap-1"><i class="fas fa-calendar text-gray-400"></i> Créé le ...</span>
                                    <span class="flex items-center gap-1"><i class="fas fa-sort-numeric-up text-gray-400"></i> Position <strong class="text-[#0F172A]">1</strong></span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <button class="openCategoryDetailBtn w-9 h-9 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition" title="Voir détail">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                                <button class="openCategoryFormBtn w-9 h-9 rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-100 flex items-center justify-center transition" title="Modifier">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <button class="toggleCategoryBtn w-9 h-9 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100 flex items-center justify-center transition" title="Désactiver">
                                    <i class="fas fa-toggle-on text-xs"></i>
                                </button>
                                <button class="openMergeBtn w-9 h-9 rounded-lg bg-purple-50 text-purple-600 hover:bg-purple-100 flex items-center justify-center transition" title="Fusionner">
                                    <i class="fas fa-object-group text-xs"></i>
                                </button>
                                <button class="openDeleteBtn w-9 h-9 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition" title="Supprimer">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                

                

                
            </div>
        </section>

        <footer class="mt-8 text-center text-xs text-gray-400 border-t border-gray-100 pt-6">
            &copy; 2026 NDIGITMARKET · Administration
        </footer>
    </main>

    <!-- MODAL : CRÉER / MODIFIER CATÉGORIE -->
    <div id="categoryFormModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#0EA486]/5 to-transparent">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-tags text-[#0EA486]"></i> <span id="formModalTitle">Nouvelle catégorie</span>
                    </h3>
                    <p class="text-xs text-gray-400">Créer ou modifier une catégorie de produits</p>
                </div>
                <button class="closeCategoryFormBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form class="p-6 overflow-y-auto space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nom -->
                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Nom de la catégorie <span class="text-red-500">*</span></label>
                        <input type="text" id="categoryName" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="Ex: WordPress">
                    </div>

                    <!-- Slug -->
                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Slug URL <span class="text-red-500">*</span></label>
                        <div class="flex items-center">
                            <span class="px-3 py-2.5 bg-gray-100 border border-r-0 border-gray-100 rounded-l-xl text-xs text-gray-500">ndigitmarket.com/</span>
                            <input type="text" id="categorySlug" class="flex-1 px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-r-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="wordpress">
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1">Généré automatiquement depuis le nom, modifiable si nécessaire</p>
                    </div>

                    <!-- Icône emoji -->
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">
                            Icône <span class="text-red-500">*</span>
                        </label>

                        <div class="relative">
                            <!-- Valeur envoyée au serveur -->
                            <input type="hidden" id="categoryEmoji" value="fa-solid fa-globe">

                            <!-- Aperçu de l'icône -->
                            <div id="categoryIcon"
                                class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-center text-2xl focus-within:border-[#0EA486]">
                                <i class="fa-solid fa-globe"></i>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-1 mt-2">
                            <button type="button" class="emoji-pick w-8 h-8 rounded-lg bg-gray-50 hover:bg-gray-100 flex items-center justify-center text-lg transition" data-emoji="fa-solid fa-globe">
                                <i class="fa-solid fa-globe"></i>
                            </button>

                            <button type="button" class="emoji-pick w-8 h-8 rounded-lg bg-gray-50 hover:bg-gray-100 flex items-center justify-center text-lg transition" data-emoji="fa-solid fa-palette">
                                <i class="fa-solid fa-palette"></i>
                            </button>

                            <button type="button" class="emoji-pick w-8 h-8 rounded-lg bg-gray-50 hover:bg-gray-100 flex items-center justify-center text-lg transition" data-emoji="fa-solid fa-gears">
                                <i class="fa-solid fa-gears"></i>
                            </button>

                            <button type="button" class="emoji-pick w-8 h-8 rounded-lg bg-gray-50 hover:bg-gray-100 flex items-center justify-center text-lg transition" data-emoji="fa-brands fa-react">
                                <i class="fa-brands fa-react"></i>
                            </button>

                            <button type="button" class="emoji-pick w-8 h-8 rounded-lg bg-gray-50 hover:bg-gray-100 flex items-center justify-center text-lg transition" data-emoji="fa-solid fa-pen-ruler">
                                <i class="fa-solid fa-pen-ruler"></i>
                            </button>

                            <button type="button" class="emoji-pick w-8 h-8 rounded-lg bg-gray-50 hover:bg-gray-100 flex items-center justify-center text-lg transition" data-emoji="fa-solid fa-plug">
                                <i class="fa-solid fa-plug"></i>
                            </button>

                            <button type="button" class="emoji-pick w-8 h-8 rounded-lg bg-gray-50 hover:bg-gray-100 flex items-center justify-center text-lg transition" data-emoji="fa-solid fa-mobile-screen-button">
                                <i class="fa-solid fa-mobile-screen-button"></i>
                            </button>

                            <button type="button" class="emoji-pick w-8 h-8 rounded-lg bg-gray-50 hover:bg-gray-100 flex items-center justify-center text-lg transition" data-emoji="fa-solid fa-laptop-code">
                                <i class="fa-solid fa-laptop-code"></i>
                            </button>

                            <button type="button" class="emoji-pick w-8 h-8 rounded-lg bg-gray-50 hover:bg-gray-100 flex items-center justify-center text-lg transition" data-emoji="fa-solid fa-cart-shopping">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </button>

                            <button type="button" class="emoji-pick w-8 h-8 rounded-lg bg-gray-50 hover:bg-gray-100 flex items-center justify-center text-lg transition" data-emoji="fa-solid fa-chart-line">
                                <i class="fa-solid fa-chart-line"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Couleur -->
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Couleur d'arrière-plan</label>
                        <select class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                            <option>Bleu / Indigo</option>
                            <option>Orange / Rouge</option>
                            <option>Violet / Indigo</option>
                            <option>Cyan / Bleu</option>
                            <option>Rose / Rouge</option>
                            <option>Ambre / Jaune</option>
                            <option>Émeraude / Teal</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Description</label>
                        <textarea rows="3" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white resize-none" placeholder="Description courte de la catégorie..."></textarea>
                    </div>

                    <!-- Statut -->
                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Statut</label>
                        <div class="flex gap-2">
                            <label class="flex-1 flex items-center gap-2 p-3 bg-emerald-50 border border-emerald-200 rounded-xl cursor-pointer">
                                <input type="radio" name="status" value="active" checked class="w-4 h-4 text-[#0EA486]">
                                <div>
                                    <p class="text-xs font-semibold text-emerald-700">Active</p>
                                    <p class="text-[10px] text-emerald-600">Visible sur le site</p>
                                </div>
                            </label>
                            <label class="flex-1 flex items-center gap-2 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer">
                                <input type="radio" name="status" value="inactive" class="w-4 h-4 text-gray-500">
                                <div>
                                    <p class="text-xs font-semibold text-gray-600">Inactive</p>
                                    <p class="text-[10px] text-gray-500">Masquée du site</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                    <button type="button" class="closeCategoryFormBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                        Annuler
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL : DÉTAIL CATÉGORIE -->
    <div id="categoryDetailModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#0EA486]/5 to-transparent">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-info-circle text-[#0EA486]"></i> Détail de la catégorie
                    </h3>
                    <p class="text-xs text-gray-400">Informations et produits associés</p>
                </div>
                <button class="closeCategoryDetailBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="overflow-y-auto p-6 space-y-5">
                <!-- En-tête -->
                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-5 border border-indigo-100">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-4xl shadow-sm">
                            🌐
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <h4 class="text-lg font-bold text-[#0F172A]">WordPress</h4>
                                <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">
                                    <i class="fas fa-check mr-1"></i>Active
                                </span>
                            </div>
                            <p class="text-xs text-gray-500">Slug: <span class="font-mono">/wordpress</span> · ID: ...</p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-[#0EA486]">...</p>
                            <p class="text-xs text-gray-500">Produits actifs</p>
                        </div>
                    </div>
                </div>

                <!-- Infos -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white rounded-2xl p-4 border border-gray-100">
                        <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                            <i class="fas fa-info-circle text-[#0EA486]"></i> Informations
                        </h5>
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Nom</span>
                                <span class="font-medium text-[#0F172A]">...</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Slug</span>
                                <span class="font-mono font-medium text-[#0F172A]">...</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Statut</span>
                                <span class="font-medium text-emerald-600">...</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Date de création</span>
                                <span class="font-medium text-[#0F172A]">...</span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-gray-500">Dernière modification</span>
                                <span class="font-medium text-[#0F172A]">...</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-4 border border-gray-100">
                        <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                            <i class="fas fa-chart-bar text-[#0EA486]"></i> Statistiques
                        </h5>
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Produits actifs</span>
                                <span class="font-semibold text-[#0F172A]">...</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Produits en attente</span>
                                <span class="font-semibold text-yellow-600">...</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Total ventes</span>
                                <span class="font-semibold text-[#0EA486]">...</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">CA généré</span>
                                <span class="font-semibold text-[#0EA486]">... FCFA</span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-gray-500">Vendeurs utilisant</span>
                                <span class="font-semibold text-[#0F172A]">...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-2 flex items-center gap-2">
                        <i class="fas fa-align-left text-[#0EA486]"></i> Description
                    </h5>
                    <p class="text-sm text-gray-600 leading-relaxed">...</p>
                </div>

                <!-- Produits associés -->
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                        <h5 class="text-xs font-semibold text-gray-500 uppercase flex items-center gap-2">
                            <i class="fas fa-box text-[#0EA486]"></i> Produits associés (aperçu)
                        </h5>
                        <span class="text-[10px] text-gray-400">Top 5</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th class="px-4 py-3">Produit</th>
                                    <th class="px-4 py-3">Vendeur</th>
                                    <th class="px-4 py-3">Prix</th>
                                    <th class="px-4 py-3">Ventes</th>
                                    <th class="px-4 py-3">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-image text-indigo-400 text-xs"></i>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-[#0F172A] text-xs">...</p>
                                                <p class="text-[10px] text-gray-400">...</p>
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
        </div>
    </div>

    <!-- MODAL : FUSIONNER CATÉGORIES -->
    <div id="mergeModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-object-group text-purple-500"></i> Fusionner les catégories
                    </h3>
                    <p class="text-xs text-gray-400">Déplacer les produits d'une catégorie vers une autre</p>
                </div>
                <button class="closeMergeBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="bg-purple-50 rounded-xl p-4 border border-purple-100">
                    <p class="text-sm text-purple-700">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Tous les produits de la catégorie source seront déplacés vers la catégorie cible. La catégorie source sera ensuite supprimée.
                    </p>
                </div>

                <!-- Catégorie source -->
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Catégorie source (à supprimer) <span class="text-red-500">*</span></label>
                    <select class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-purple-500">
                        <option>— Sélectionner —</option>
                        <option> WordPress (... produits)</option>
                        <option> HTML (... produits)</option>
                        <option> PHP (... produits)</option>
                        <option> React (... produits)</option>
                        <option> PSD (... produits)</option>
                        <option> Plugin (... produits)</option>
                    </select>
                </div>

                <div class="flex items-center justify-center">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center text-purple-600">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                </div>

                <!-- Catégorie cible -->
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Catégorie cible (recevoir les produits) <span class="text-red-500">*</span></label>
                    <select class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-purple-500">
                        <option>— Sélectionner —</option>
                        <option> WordPress (... produits)</option>
                        <option> HTML (... produits)</option>
                        <option> PHP (... produits)</option>
                        <option> React (... produits)</option>
                        <option> PSD (... produits)</option>
                        <option> Plugin (... produits)</option>
                    </select>
                </div>

                <div>
                    <label class="flex items-start gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-purple-500 focus:ring-purple-500 mt-0.5" required>
                        <span>Je confirme vouloir fusionner ces catégories. Cette action est irréversible.</span>
                    </label>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                <button class="closeMergeBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                    Annuler
                </button>
                <button class="px-5 py-2.5 rounded-xl bg-purple-500 hover:bg-purple-600 text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                    <i class="fas fa-object-group"></i> Confirmer la fusion
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL : SUPPRIMER CATÉGORIE -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-trash text-red-500"></i> Supprimer la catégorie
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
                        <strong>Attention :</strong> Cette action est irréversible. Assurez-vous qu'aucun produit actif n'est rattaché à cette catégorie.
                    </p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                    <p class="text-xs text-gray-500 mb-1">Catégorie à supprimer</p>
                    <p class="text-sm font-bold text-[#0F172A] flex items-center gap-2">
                        <span class="text-xl"></span> WordPress
                    </p>
                    <p class="text-[11px] text-gray-400 mt-1">Produits associés: <strong class="text-red-600">...</strong></p>
                </div>

                <div id="deleteWarning" class="bg-yellow-50 rounded-xl p-3 border border-yellow-200 flex items-start gap-2">
                    <i class="fas fa-exclamation-circle text-yellow-600 mt-0.5"></i>
                    <p class="text-xs text-yellow-700">Cette catégorie contient encore des produits. Veuillez d'abord les déplacer ou les supprimer.</p>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Tapez "SUPPRIMER" pour confirmer</label>
                    <input type="text" id="deleteConfirmInput" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:bg-white" placeholder="SUPPRIMER">
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                <button class="closeDeleteBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                    Annuler
                </button>
                <button id="confirmDeleteBtn" class="px-5 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition opacity-50 cursor-not-allowed" disabled>
                    <i class="fas fa-trash"></i> Supprimer définitivement
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL : TOGGLE STATUT -->
    <div id="toggleModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 id="toggleTitle" class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-toggle-on text-yellow-500"></i> Changer le statut
                    </h3>
                    <p class="text-xs text-gray-400">Activer ou désactiver la catégorie</p>
                </div>
                <button class="closeToggleBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div id="toggleInfo" class="bg-yellow-50 rounded-xl p-4 border border-yellow-100">
                    <p class="text-sm text-yellow-700">
                        <i class="fas fa-info-circle mr-2"></i>
                        <span id="toggleInfoText">La catégorie sera masquée du site, mais les produits associés resteront disponibles.</span>
                    </p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                    <p class="text-xs text-gray-500 mb-1">Catégorie concernée</p>
                    <p class="text-sm font-bold text-[#0F172A] flex items-center gap-2">
                        <span class="text-xl"></span> WordPress
                    </p>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                <button class="closeToggleBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                    Annuler
                </button>
                <button id="confirmToggleBtn" class="px-5 py-2.5 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
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
        function setupModal(modalId, openSelector, closeSelector, options = {}) {
            const modal = document.getElementById(modalId);
            const openBtns = document.querySelectorAll(openSelector);
            const closeBtns = document.querySelectorAll(closeSelector);

            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                if (options.lockBody !== false) document.body.style.overflow = 'hidden';
            }
            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                if (options.lockBody !== false) document.body.style.overflow = '';
            }

            openBtns.forEach(btn => btn.addEventListener('click', openModal));
            closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });

            return { openModal, closeModal };
        }

        // Modal formulaire catégorie
        (function() {
            const modal = document.getElementById('categoryFormModal');
            const openBtns = document.querySelectorAll('.openCategoryFormBtn, #openCategoryFormBtn');
            const closeBtns = document.querySelectorAll('.closeCategoryFormBtn');
            const title = document.getElementById('formModalTitle');

            function openModal(isEdit = false) {
                title.textContent = isEdit ? 'Modifier la catégorie' : 'Nouvelle catégorie';
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
                const isEdit = this.classList.contains('openCategoryFormBtn');
                openModal(isEdit);
            }));
            closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });

            // Sélection d'une icône
document.querySelectorAll('.emoji-pick').forEach(btn => {
    btn.addEventListener('click', function () {
        const icon = this.dataset.emoji;

        // Stocke la classe Font Awesome
        document.getElementById('categoryEmoji').value = icon;

        // Met à jour l'aperçu
        document.getElementById('categoryIcon').innerHTML = `<i class="${icon}"></i>`;
    });
});

            // Auto-generate slug
            const nameInput = document.getElementById('categoryName');
            const slugInput = document.getElementById('categorySlug');
            nameInput.addEventListener('input', function() {
                slugInput.value = this.value.toLowerCase()
                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            });

            // Submit
            modal.querySelector('form').addEventListener('submit', function(e) {
                e.preventDefault();
                closeModal();
                showToast('Succès', 'Catégorie enregistrée avec succès', 'success');
            });
        })();

        // Modal détail
        setupModal('categoryDetailModal', '.openCategoryDetailBtn', '.closeCategoryDetailBtn');

        // Modal fusion
        (function() {
            const modal = document.getElementById('mergeModal');
            const openBtns = document.querySelectorAll('.openMergeBtn');
            const closeBtns = document.querySelectorAll('.closeMergeBtn');

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

            modal.querySelector('button.bg-purple-500').addEventListener('click', function() {
                closeModal();
                showToast('Fusion réussie', 'Les catégories ont été fusionnées', 'success');
            });
        })();

        // Modal suppression
        (function() {
            const modal = document.getElementById('deleteModal');
            const openBtns = document.querySelectorAll('.openDeleteBtn');
            const closeBtns = document.querySelectorAll('.closeDeleteBtn');
            const input = document.getElementById('deleteConfirmInput');
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            const warning = document.getElementById('deleteWarning');
            
            // Variable pour stocker l'ID de la catégorie à supprimer
            let currentCategoryId = null;

            function openModal() {
                // Récupérer l'ID depuis la carte
                const card = this.closest('.category-card');
                if (card) {
                    currentCategoryId = card.getAttribute('data-id');
                    
                    // Afficher le nom de la catégorie dans le modal
                    const name = card.querySelector('h5')?.textContent || 'Catégorie';
                    const nameElement = document.querySelector('#deleteModal .text-sm.font-bold');
                    if (nameElement) {
                        nameElement.innerHTML = `<span class="text-xl"></span> ${name}`;
                    }
                }
                
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
                // Réinitialiser l'ID quand on ferme
                currentCategoryId = null;
            }

            // Modifier l'ouverture pour récupérer le contexte
            openBtns.forEach(btn => btn.addEventListener('click', function() {
                openModal.call(this);
            }));
            
            closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
            
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });

            // Vérifier si l'utilisateur a tapé "SUPPRIMER"
            input.addEventListener('input', function() {
                if (this.value === 'SUPPRIMER') {
                    confirmBtn.disabled = false;
                    confirmBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    confirmBtn.disabled = true;
                    confirmBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            });

            // Action de suppression
            confirmBtn.addEventListener('click', function() {
                if (!this.disabled) {
                    // Vérifier qu'on a un ID
                    if (!currentCategoryId) {
                        showToast('Erreur', 'ID de catégorie manquant', 'error');
                        return;
                    }
                    
                    // Envoyer la requête de suppression
                    fetch('index.php?url=categories_delete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'id=' + currentCategoryId
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erreur réseau');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            closeModal();
                            showToast('Succès', data.message || 'Catégorie supprimée avec succès', 'success');
                            // Recharger la page après 1.5 secondes
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showToast('Erreur', data.error || 'Impossible de supprimer la catégorie', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        showToast('Erreur', 'Impossible de supprimer la catégorie', 'error');
                    });
                }
            });
        })();

        // Modal toggle statut
        (function() {
            const modal = document.getElementById('toggleModal');
            const openBtns = document.querySelectorAll('.toggleCategoryBtn');
            const closeBtns = document.querySelectorAll('.closeToggleBtn');
            const confirmBtn = document.getElementById('confirmToggleBtn');
            const title = document.getElementById('toggleTitle');
            const infoText = document.getElementById('toggleInfoText');
            const info = document.getElementById('toggleInfo');

            function openModal() {
                const btn = this;
                const isOn = btn.querySelector('.fa-toggle-on');
                
                if (isOn) {
                    title.innerHTML = '<i class="fas fa-toggle-off text-gray-500"></i> Désactiver la catégorie';
                    infoText.textContent = 'La catégorie sera masquée du site, mais les produits associés resteront disponibles.';
                    info.className = 'bg-yellow-50 rounded-xl p-4 border border-yellow-100';
                    confirmBtn.className = 'px-5 py-2.5 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition';
                    confirmBtn.innerHTML = '<i class="fas fa-pause"></i> Désactiver';
                } else {
                    title.innerHTML = '<i class="fas fa-toggle-on text-emerald-500"></i> Activer la catégorie';
                    infoText.textContent = 'La catégorie sera à nouveau visible sur le site et ses produits seront affichés.';
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
                showToast('Statut modifié', 'Le statut de la catégorie a été mis à jour', 'success');
            });
        })();

        // Drag & Drop réorganisation
        (function() {
            const list = document.getElementById('categoriesList');
            let draggedItem = null;

            list.querySelectorAll('.category-card').forEach(card => {
                card.addEventListener('dragstart', function(e) {
                    draggedItem = this;
                    setTimeout(() => this.classList.add('opacity-50', 'scale-95'), 0);
                });

                card.addEventListener('dragend', function() {
                    this.classList.remove('opacity-50', 'scale-95');
                    list.querySelectorAll('.category-card').forEach(c => {
                        c.classList.remove('border-t-2', 'border-[#0EA486]');
                    });
                    draggedItem = null;
                    updatePositions();
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

            function updatePositions() {
                list.querySelectorAll('.category-card').forEach((card, index) => {
                    const posEl = card.querySelector('.fa-sort-numeric-up')?.parentElement;
                    if (posEl) {
                        const strong = posEl.querySelector('strong');
                        if (strong) strong.textContent = index + 1;
                    }
                });
            }

            // Bouton enregistrer l'ordre
            document.getElementById('saveOrderBtn').addEventListener('click', function() {
                const order = [];
                list.querySelectorAll('.category-card').forEach(card => {
                    order.push(card.getAttribute('data-id'));
                });
                console.log('Nouvel ordre:', order);
                showToast('Ordre enregistré', 'L\'ordre d\'affichage a été sauvegardé', 'success');
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