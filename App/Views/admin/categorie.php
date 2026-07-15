<?php 
$stats = $stats ?? [
    'total' => 0,
    'actives' => 0,
    'inactives' => 0,
    'produits' => 0
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NDIGITMARKET Admin · Gestion des Catégories</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../public/assets/CSS/app.css">
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
                    <p class="text-2xl font-bold text-[#0F172A]"><?= $stats['total'] ?></p>
                    <p class="text-xs text-gray-400 mt-1">Catégories totales</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">ACTIVES</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= $stats['actives'] ?></p>
                    <p class="text-xs text-gray-400 mt-1">Catégories actives</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-gray-500">
                            <i class="fas fa-pause-circle"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-full">INACTIVES</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= $stats['inactives'] ?></p>
                    <p class="text-xs text-gray-400 mt-1">Catégories désactivées</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600">
                            <i class="fas fa-box"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-purple-600 bg-purple-50 px-2 py-1 rounded-full">PRODUITS</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= $stats['produits'] ?></p>
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
            <div id="searchSection" class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm mb-4">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex-1 min-w-[220px] relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" id="searchInput" placeholder="Rechercher une catégorie..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white transition">
                    </div>
                    <select id="statusFilter" class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 focus:outline-none focus:border-[#0EA486]">
                        <option value="all">Tous les statuts</option>
                        <option value="active">Actives</option>
                        <option value="inactive">Inactives</option>
                    </select>
                    <select id="sortFilter" class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 focus:outline-none focus:border-[#0EA486]">
                        <option value="ordre">Trier par : Ordre d'affichage</option>
                        <option value="produits">Nombre de produits</option>
                        <option value="nom">Nom (A-Z)</option>
                        <option value="date">Date de création</option>
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
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <!-- Catégorie -->
                        <div class="category-card bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition overflow-hidden" 
                            draggable="true" 
                            data-id="<?= $category['id'] ?>">
                            <div class="flex items-stretch">
                                <!-- Drag handle -->
                                <div class="drag-handle w-12 bg-gray-50 hover:bg-gray-100 flex items-center justify-center cursor-grab active:cursor-grabbing border-r border-gray-100 transition">
                                    <i class="fas fa-grip-vertical text-gray-400"></i>
                                </div>
                                <!-- Contenu -->
                                <div class="flex-1 p-4 flex flex-col md:flex-row items-start md:items-center gap-4">
                                    <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i class="<?= $category['icone'] ?? 'fa-solid fa-globe' ?> text-3xl text-blue-600"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-center gap-2 mb-1">
                                            <h5 class="text-sm font-bold text-[#0F172A]"><?= htmlspecialchars($category['nom_categorie']) ?></h5>
                                            <span class="text-[10px] font-semibold <?= $category['statut'] === 'active' ? 'text-emerald-700 bg-emerald-100' : 'text-gray-500 bg-gray-100' ?> px-2 py-0.5 rounded-full">
                                                <i class="fas <?= $category['statut'] === 'active' ? 'fa-check' : 'fa-pause' ?> mr-1"></i>
                                                <?= $category['statut'] === 'active' ? 'Active' : 'Inactive' ?>
                                            </span>
                                            <span class="text-[10px] font-mono text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">
                                                /<?= htmlspecialchars($category['slug'] ?? '') ?>
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-500 line-clamp-1 mb-2"><?= htmlspecialchars($category['description'] ?? 'Aucune description') ?></p>
                                        <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                            <span class="flex items-center gap-1"><i class="fas fa-box text-[#0EA486]"></i> <strong class="text-[#0F172A]"><?= $category['nb_produits'] ?? 0 ?></strong> produits</span>
                                            <span class="flex items-center gap-1"><i class="fas fa-calendar text-gray-400"></i> <?= date('d/m/Y', strtotime($category['created_at'] ?? 'now')) ?></span>
                                            <span class="flex items-center gap-1"><i class="fas fa-sort-numeric-up text-gray-400"></i> Position <strong class="text-[#0F172A]"><?= $category['ordre_affichage'] ?? 0 ?></strong></span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <button class="openCategoryDetailBtn w-9 h-9 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition" 
                                                title="Voir détail"
                                                data-id="<?= $category['id'] ?>">
                                            <i class="fas fa-eye text-xs"></i>
                                        </button>
                                        <button class="openCategoryFormBtn w-9 h-9 rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-100 flex items-center justify-center transition" 
                                            title="Modifier"
                                            data-id="<?= $category['id'] ?>"
                                            data-name="<?= htmlspecialchars($category['nom_categorie']) ?>"
                                            data-slug="<?= htmlspecialchars($category['slug'] ?? '') ?>"
                                            data-emoji="<?= htmlspecialchars($category['icone'] ?? 'fa-solid fa-globe') ?>"
                                            data-couleur="<?= htmlspecialchars($category['couleur'] ?? 'blue') ?>"
                                            data-description="<?= htmlspecialchars($category['description'] ?? '') ?>"
                                            data-statut="<?= $category['statut'] ?? 'active' ?>"
                                            data-image="<?= htmlspecialchars($category['image_cat'] ?? '') ?>">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                        <button class="toggleCategoryBtn w-9 h-9 rounded-lg <?= $category['statut'] === 'active' ? 'bg-yellow-50 text-yellow-600 hover:bg-yellow-100' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' ?> flex items-center justify-center transition" 
                                                title="<?= $category['statut'] === 'active' ? 'Désactiver' : 'Activer' ?>"
                                                data-id="<?= $category['id'] ?>"
                                                 data-nom="<?= htmlspecialchars($category['nom_categorie']) ?>"> 
                                            <i class="fas <?= $category['statut'] === 'active' ? 'fa-toggle-on' : 'fa-toggle-off' ?> text-xs"></i>
                                        </button>
                                        <button class="openMergeBtn w-9 h-9 rounded-lg bg-purple-50 text-purple-600 hover:bg-purple-100 flex items-center justify-center transition" 
                                                title="Fusionner"
                                                data-id="<?= $category['id'] ?>"
                                                data-name="<?= htmlspecialchars($category['nom_categorie']) ?>">
                                            <i class="fas fa-object-group text-xs"></i>
                                        </button>
                                        <button class="openDeleteBtn w-9 h-9 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition" 
                                                title="Supprimer"
                                                data-id="<?= $category['id'] ?>">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-folder-open text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-400">Aucune catégorie trouvée</p>
                    </div>
             <?php endif; ?>
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
                    <i class="fas fa-tags text-[#0EA486]"></i> 
                    <span id="formModalTitle">Nouvelle catégorie</span>
                </h3>
                <p class="text-xs text-gray-400">Créer ou modifier une catégorie de produits</p>
            </div>
            <button class="closeCategoryFormBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form class="p-6 overflow-y-auto space-y-4">
            <!-- ID caché pour l'édition -->
            <input type="hidden" id="categoryId" name="id" value="">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nom -->
                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Nom de la catégorie <span class="text-red-500">*</span></label>
                    <input type="text" id="categoryName" name="nom_categorie" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="Ex: WordPress">
                </div>

                <!-- Slug -->
                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Slug URL <span class="text-red-500">*</span></label>
                    <div class="flex items-center">
                        <span class="px-3 py-2.5 bg-gray-100 border border-r-0 border-gray-100 rounded-l-xl text-xs text-gray-500">ndigitmarket.com/</span>
                        <input type="text" id="categorySlug" name="slug" class="flex-1 px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-r-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="wordpress">
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">Généré automatiquement depuis le nom, modifiable si nécessaire</p>
                </div>

                <!-- Icône -->
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">
                        Icône <span class="text-red-500">*</span>
                    </label>

                    <div class="relative">
                        <input type="hidden" id="categoryEmoji" name="icone" value="fa-solid fa-globe">
                        <div id="categoryIcon" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-center text-2xl focus-within:border-[#0EA486]">
                            <i class="fa-solid fa-globe"></i>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-1 mt-2">
                        <?php 
                        $icons = [
                            'fa-solid fa-globe',
                            'fa-solid fa-palette',
                            'fa-solid fa-gears',
                            'fa-brands fa-react',
                            'fa-solid fa-pen-ruler',
                            'fa-solid fa-plug',
                            'fa-solid fa-mobile-screen-button',
                            'fa-solid fa-laptop-code',
                            'fa-solid fa-cart-shopping',
                            'fa-solid fa-chart-line',
                            'fa-solid fa-code',
                            'fa-solid fa-database',
                            'fa-solid fa-cloud',
                            'fa-solid fa-shield-halved',
                            'fa-solid fa-rocket',
                            'fa-solid fa-wand-magic-sparkles'
                        ];
                        foreach ($icons as $icon): ?>
                            <button type="button" class="emoji-pick w-8 h-8 rounded-lg bg-gray-50 hover:bg-gray-100 flex items-center justify-center text-lg transition" data-emoji="<?= $icon ?>">
                                <i class="<?= $icon ?>"></i>
                            </button>

                            
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Couleur -->
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Couleur d'arrière-plan</label>
                    <select id="categoryColor" name="couleur" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                        <?php 
                        $colors = [
                            'blue' => 'Bleu / Indigo',
                            'orange' => 'Orange / Rouge',
                            'purple' => 'Violet / Indigo',
                            'cyan' => 'Cyan / Bleu',
                            'pink' => 'Rose / Rouge',
                            'amber' => 'Ambre / Jaune',
                            'emerald' => 'Émeraude / Teal',
                            'red' => 'Rouge',
                            'green' => 'Vert',
                            'gray' => 'Gris'
                        ];
                        foreach ($colors as $value => $label): ?>
                            <option value="<?= $value ?>"><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Description</label>
                    <textarea id="categoryDescription" name="description" rows="3" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white resize-none" placeholder="Description courte de la catégorie..."></textarea>
                </div>

                <!-- Statut -->
                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Statut</label>
                    <div class="flex gap-2">
                        <label class="flex-1 flex items-center gap-2 p-3 bg-emerald-50 border border-emerald-200 rounded-xl cursor-pointer">
                            <input type="radio" name="statut" value="active" checked class="w-4 h-4 text-[#0EA486]">
                            <div>
                                <p class="text-xs font-semibold text-emerald-700">Active</p>
                                <p class="text-[10px] text-emerald-600">Visible sur le site</p>
                            </div>
                        </label>
                        <label class="flex-1 flex items-center gap-2 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer">
                            <input type="radio" name="statut" value="inactive" class="w-4 h-4 text-gray-500">
                            <div>
                                <p class="text-xs font-semibold text-gray-600">Inactive</p>
                                <p class="text-[10px] text-gray-500">Masquée du site</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Image upload -->
                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Image de la catégorie</label>
                    <input type="file" id="categoryImage" name="image" accept="image/*" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                    <div id="currentImagePreview" class="hidden mt-2">
                        <img id="currentImage" src="" class="h-20 rounded-lg">
                        <p class="text-xs text-gray-400 mt-1">Image actuelle</p>
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

        <div class="overflow-y-auto p-6 space-y-5" id="categoryDetailContent">
            <?php if (!empty($categoryDetail) && is_array($categoryDetail)): ?>
                <?php $cat = $categoryDetail; ?>
                
                <!-- En-tête -->
                <div class="bg-gradient-to-br <?= isset($cat['couleur']) && $cat['couleur'] === 'blue' ? 'from-blue-100 to-indigo-100' : 'from-indigo-50 to-purple-50' ?> rounded-2xl p-5 border border-indigo-100">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-4xl shadow-sm">
                            <i class="<?= isset($cat['icone']) ? $cat['icone'] : 'fa-solid fa-globe' ?>"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <h4 class="text-lg font-bold text-[#0F172A]"><?= isset($cat['nom_categorie']) ? htmlspecialchars($cat['nom_categorie']) : 'Catégorie' ?></h4>
                                <span class="text-[10px] font-semibold <?= isset($cat['statut']) && $cat['statut'] === 'active' ? 'text-emerald-700 bg-emerald-100' : 'text-gray-500 bg-gray-100' ?> px-2 py-0.5 rounded-full">
                                    <i class="fas <?= isset($cat['statut']) && $cat['statut'] === 'active' ? 'fa-check' : 'fa-pause' ?> mr-1"></i>
                                    <?= isset($cat['statut']) && $cat['statut'] === 'active' ? 'Active' : 'Inactive' ?>
                                </span>
                            </div>
                            <p class="text-xs text-gray-500">
                                Slug: <span class="font-mono">/<?= isset($cat['slug']) ? htmlspecialchars($cat['slug']) : '' ?></span> · 
                                ID: <?= isset($cat['id']) ? $cat['id'] : '---' ?> · 
                                Position: <?= isset($cat['ordre_affichage']) ? $cat['ordre_affichage'] : 0 ?>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-[#0EA486]"><?= isset($cat['nb_produits']) ? $cat['nb_produits'] : 0 ?></p>
                            <p class="text-xs text-gray-500">Produits</p>
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
                                <span class="font-medium text-[#0F172A]"><?= isset($cat['nom_categorie']) ? htmlspecialchars($cat['nom_categorie']) : '---' ?></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Slug</span>
                                <span class="font-mono font-medium text-[#0F172A]">/<?= isset($cat['slug']) ? htmlspecialchars($cat['slug']) : '---' ?></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Icône</span>
                                <span class="font-medium text-[#0F172A]"><i class="<?= isset($cat['icone']) ? $cat['icone'] : 'fa-solid fa-globe' ?>"></i></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Couleur</span>
                                <span class="font-medium text-[#0F172A]"><?= isset($cat['couleur']) ? htmlspecialchars($cat['couleur']) : 'blue' ?></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Statut</span>
                                <span class="font-medium <?= isset($cat['statut']) && $cat['statut'] === 'active' ? 'text-emerald-600' : 'text-gray-500' ?>">
                                    <?= isset($cat['statut']) && $cat['statut'] === 'active' ? 'Active' : 'Inactive' ?>
                                </span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Position</span>
                                <span class="font-medium text-[#0F172A]"><?= isset($cat['ordre_affichage']) ? $cat['ordre_affichage'] : 0 ?></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Date de création</span>
                                <span class="font-medium text-[#0F172A]"><?= isset($cat['created_at']) ? date('d/m/Y H:i', strtotime($cat['created_at'])) : '---' ?></span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-gray-500">Dernière modification</span>
                                <span class="font-medium text-[#0F172A]"><?= isset($cat['updated_at']) ? date('d/m/Y H:i', strtotime($cat['updated_at'])) : '---' ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-4 border border-gray-100">
                        <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                            <i class="fas fa-chart-bar text-[#0EA486]"></i> Statistiques
                        </h5>
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Total produits</span>
                                <span class="font-semibold text-[#0F172A]"><?= isset($cat['nb_produits']) ? $cat['nb_produits'] : 0 ?></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Produits actifs</span>
                                <span class="font-semibold text-emerald-600"><?= isset($cat['produits_actifs']) ? $cat['produits_actifs'] : 0 ?></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Produits inactifs</span>
                                <span class="font-semibold text-gray-500"><?= isset($cat['produits_inactifs']) ? $cat['produits_inactifs'] : 0 ?></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Sous-catégories</span>
                                <span class="font-semibold text-[#0F172A]"><?= isset($cat['sous_categories']) ? $cat['sous_categories'] : 0 ?></span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-gray-500">Vendeurs utilisant</span>
                                <span class="font-semibold text-[#0F172A]"><?= isset($cat['vendeurs']) ? $cat['vendeurs'] : 0 ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-2 flex items-center gap-2">
                        <i class="fas fa-align-left text-[#0EA486]"></i> Description
                    </h5>
                    <p class="text-sm text-gray-600 leading-relaxed"><?= isset($cat['description']) && !empty($cat['description']) ? htmlspecialchars($cat['description']) : 'Aucune description disponible pour cette catégorie.' ?></p>
                </div>

                <!-- Image -->
                <?php if (isset($cat['image_cat']) && !empty($cat['image_cat'])): ?>
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-2 flex items-center gap-2">
                        <i class="fas fa-image text-[#0EA486]"></i> Image
                    </h5>
                    <img src="/uploads/<?= $cat['image_cat'] ?>" class="max-h-48 rounded-lg">
                </div>
                <?php endif; ?>

                <!-- Produits associés -->
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                        <h5 class="text-xs font-semibold text-gray-500 uppercase flex items-center gap-2">
                            <i class="fas fa-box text-[#0EA486]"></i> Produits associés
                        </h5>
                        <span class="text-[10px] text-gray-400"><?= isset($cat['nb_produits']) ? $cat['nb_produits'] : 0 ?> produits</span>
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
                                <?php if (isset($cat['produits']) && !empty($cat['produits'])): ?>
                                    <?php foreach (array_slice($cat['produits'], 0, 5) as $produit): ?>
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                        <i class="fas fa-image text-indigo-400 text-xs"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-[#0F172A] text-xs"><?= isset($produit['nom_produit']) ? htmlspecialchars($produit['nom_produit']) : 'Sans nom' ?></p>
                                                        <p class="text-[10px] text-gray-400"><?= isset($produit['slug']) ? htmlspecialchars($produit['slug']) : '' ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-xs text-gray-600"><?= isset($produit['vendeur']) ? htmlspecialchars($produit['vendeur']) : 'Admin' ?></td>
                                            <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]"><?= isset($produit['prix']) ? number_format($produit['prix'], 0, ',', ' ') . ' FCFA' : '---' ?></td>
                                            <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]"><?= isset($produit['ventes']) ? $produit['ventes'] : 0 ?></td>
                                            <td class="px-4 py-3">
                                                <span class="text-[10px] font-semibold <?= (isset($produit['statut']) && $produit['statut'] === 'active') ? 'text-emerald-700 bg-emerald-100' : 'text-gray-500 bg-gray-100' ?> px-2 py-1 rounded-full">
                                                    <?= (isset($produit['statut']) && $produit['statut'] === 'active') ? 'Publié' : 'Brouillon' ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-400 text-sm">
                                            <i class="fas fa-box-open text-2xl block mb-2"></i>
                                            Aucun produit dans cette catégorie
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php else: ?>
                <!-- Message quand aucune donnée -->
                <div class="text-center py-12 text-gray-400">
                    <i class="fas fa-info-circle text-4xl mb-3"></i>
                    <p class="text-lg font-semibold">Aucune information disponible</p>
                    <p class="text-sm">Veuillez sélectionner une catégorie pour voir les détails</p>
                </div>
            <?php endif; ?>
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
                <form id="mergeForm" class="p-6 space-y-4">
                    <div class="bg-purple-50 rounded-xl p-4 border border-purple-100">
                        <p class="text-sm text-purple-700">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Tous les produits de la catégorie source seront déplacés vers la catégorie cible. La catégorie source sera ensuite supprimée.
                        </p>
                    </div>

                    <!-- Catégorie source -->
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Catégorie source (à supprimer) <span class="text-red-500">*</span></label>
                        <select id="mergeSourceId" name="source_id" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-purple-500">
                            <option value="">— Sélectionner —</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>">
                                    <?= htmlspecialchars($cat['nom_categorie']) ?> (<?= $cat['nb_produits'] ?? 0 ?> produits)
                                </option>
                            <?php endforeach; ?>
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
                        <select id="mergeTargetId" name="target_id" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-purple-500">
                            <option value="">— Sélectionner —</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>">
                                    <?= htmlspecialchars($cat['nom_categorie']) ?> (<?= $cat['nb_produits'] ?? 0 ?> produits)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="flex items-start gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" id="mergeConfirm" class="w-4 h-4 rounded border-gray-300 text-purple-500 focus:ring-purple-500 mt-0.5" required>
                            <span>Je confirme vouloir fusionner ces catégories. Cette action est irréversible.</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" class="closeMergeBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                            Annuler
                        </button>
                        <button type="submit" id="confirmMergeBtn" class="px-5 py-2.5 rounded-xl bg-purple-500 hover:bg-purple-600 text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                            <i class="fas fa-object-group"></i> Confirmer la fusion
                        </button>
                    </div>
                </form>
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
                        <span class="text-xl"></span> 
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
                        <span class="text-xl"></span> <span id="toggleCategoryName">Sans nom</span>
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
    const form = modal.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');

    let currentCategoryId = null;

    function openModal(isEdit = false, data = null) {
        title.textContent = isEdit ? 'Modifier la catégorie' : 'Nouvelle catégorie';
        
        // Réinitialiser
        form.reset();
        document.getElementById('categoryIcon').innerHTML = `<i class="fas fa-tag"></i>`;
        document.getElementById('categoryEmoji').value = 'fa-solid fa-globe';
        
        const colorSelect = document.getElementById('categoryColor');
        if (colorSelect) colorSelect.value = 'blue';
        
        const preview = document.getElementById('currentImagePreview');
        if (preview) preview.classList.add('hidden');
        
        if (isEdit && data) {
            currentCategoryId = data.id;
            document.getElementById('categoryName').value = data.name || '';
            document.getElementById('categorySlug').value = data.slug || '';
            document.getElementById('categoryEmoji').value = data.emoji || 'fa-solid fa-globe';
            document.getElementById('categoryIcon').innerHTML = `<i class="${data.emoji || 'fa-solid fa-globe'}"></i>`;
            
            if (data.couleur && colorSelect) {
                colorSelect.value = data.couleur;
            }
            
            const descTextarea = document.getElementById('categoryDescription');
            if (descTextarea && data.description) {
                descTextarea.value = data.description;
            }
            
            if (data.statut) {
                const statusRadios = document.querySelectorAll('input[name="statut"]');
                statusRadios.forEach(radio => {
                    radio.checked = radio.value === data.statut;
                });
            }
            
            if (data.image) {
                const preview = document.getElementById('currentImagePreview');
                const img = document.getElementById('currentImage');
                if (preview && img) {
                    img.src = '/uploads/' + data.image;
                    preview.classList.remove('hidden');
                }
            }
        } else {
            currentCategoryId = null;
            const statusRadios = document.querySelectorAll('input[name="statut"]');
            statusRadios.forEach(radio => {
                radio.checked = radio.value === 'active';
            });
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
        currentCategoryId = null;
    }

    // Nouvelle catégorie
    document.querySelectorAll('#openCategoryFormBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            openModal(false);
        });
    });

    // Édition
    document.querySelectorAll('.openCategoryFormBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            const data = {
                id: this.dataset.id || '',
                name: this.dataset.name || '',
                slug: this.dataset.slug || '',
                emoji: this.dataset.emoji || 'fa-solid fa-globe',
                couleur: this.dataset.couleur || 'blue',
                description: this.dataset.description || '',
                statut: this.dataset.statut || 'active',
                image: this.dataset.image || ''
            };
            openModal(true, data);
        });
    });

    closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });

    // Sélection icône
    document.querySelectorAll('.emoji-pick').forEach(btn => {
        btn.addEventListener('click', function () {
            const icon = this.dataset.emoji;
            document.getElementById('categoryEmoji').value = icon;
            document.getElementById('categoryIcon').innerHTML = `<i class="${icon}"></i>`;
        });
    });

    // Auto-generate slug
    const nameInput = document.getElementById('categoryName');
    const slugInput = document.getElementById('categorySlug');
    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function() {
            slugInput.value = this.value.toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        });
    }

    // SUBMIT
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const nom = document.getElementById('categoryName').value.trim();
        if (!nom) {
            showToast('Erreur', 'Le nom de la catégorie est requis', 'error');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';

        const formData = new FormData(form);
        formData.append('id', currentCategoryId || '');
        formData.append('sous_categories', '0'); // 👈 IMPORTANT

        const action = currentCategoryId ? 'categories_edit' : 'categories_add';

        fetch('api.php?url=' + action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            // Voir la réponse brute pour debug
            return response.text().then(text => {
                console.log('📥 Réponse brute:', text);
                try {
                    return JSON.parse(text);
                } catch(e) {
                    throw new Error('Réponse non-JSON: ' + text.substring(0, 100));
                }
            });
        })
        .then(data => {
            if (data.success) {
                closeModal();
                showToast('Succès', data.message || 'Catégorie enregistrée', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('Erreur', data.error || 'Erreur lors de l\'enregistrement', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = currentCategoryId ? 'Modifier' : 'Ajouter';
            }
        })
        .catch(error => {
            console.error('❌ Erreur:', error);
            showToast('Erreur', error.message || 'Erreur serveur', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = currentCategoryId ? 'Modifier' : 'Ajouter';
        });
    });
})();

        // ============================================
// MODAL DÉTAIL - Version unique
// ============================================
(function() {
    const modal = document.getElementById('categoryDetailModal');
    const openBtns = document.querySelectorAll('.openCategoryDetailBtn');
    const closeBtns = document.querySelectorAll('.closeCategoryDetailBtn');
    const content = document.getElementById('categoryDetailContent');

    // Helpers
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function formatPrice(price) {
        if (!price) return '---';
        return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' FCFA';
    }

    function formatDate(dateStr) {
        if (!dateStr) return '---';
        try {
            const date = new Date(dateStr);
            if (isNaN(date.getTime())) return '---';
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            return `${day}/${month}/${year} ${hours}:${minutes}`;
        } catch (e) {
            return '---';
        }
    }

    function openModal() {
        const btn = this;
        const id = btn.getAttribute('data-id');
        
        if (!id) {
            showToast('Erreur', 'ID manquant', 'error');
            return;
        }

        content.innerHTML = `
            <div class="flex justify-center py-12">
                <div class="flex flex-col items-center gap-3">
                    <i class="fas fa-spinner fa-spin text-3xl text-[#0EA486]"></i>
                    <p class="text-gray-400 text-sm">Chargement...</p>
                </div>
            </div>
        `;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        fetch('api.php?url=categories_get&id=' + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderDetail(data.data);
                } else {
                    content.innerHTML = `
                        <div class="text-center py-12 text-red-500">
                            <i class="fas fa-exclamation-circle text-4xl mb-3"></i>
                            <p class="text-lg font-semibold">Erreur</p>
                            <p class="text-sm">${data.error || 'Erreur de chargement'}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                content.innerHTML = `
                    <div class="text-center py-12 text-red-500">
                        <i class="fas fa-exclamation-circle text-4xl mb-3"></i>
                        <p class="text-lg font-semibold">Erreur serveur</p>
                        <p class="text-sm">Veuillez réessayer</p>
                    </div>
                `;
            });
    }

    function renderDetail(cat) {
        const statutClass = cat.statut === 'active' 
            ? 'text-emerald-700 bg-emerald-100' 
            : 'text-gray-500 bg-gray-100';
        const statutIcon = cat.statut === 'active' ? 'fa-check' : 'fa-pause';
        const statutText = cat.statut === 'active' ? 'Active' : 'Inactive';

        let produitsHtml = '';
        if (cat.produits && cat.produits.length > 0) {
            cat.produits.slice(0, 5).forEach(p => {
                let pStatutClass = 'text-gray-500 bg-gray-100';
                let pStatutText = 'Inconnu';
                
                if (p.statut === 'approuve') {
                    pStatutClass = 'text-emerald-700 bg-emerald-100';
                    pStatutText = 'Approuvé';
                } else if (p.statut === 'en_attente') {
                    pStatutClass = 'text-yellow-700 bg-yellow-100';
                    pStatutText = 'En attente';
                } else if (p.statut === 'refuse') {
                    pStatutClass = 'text-red-700 bg-red-100';
                    pStatutText = 'Refusé';
                }
                
                const imagePath = p.image ? p.image : '';
                const imageHtml = imagePath 
                    ? `<img src="/${imagePath}" class="w-8 h-8 rounded-lg object-cover">` 
                    : `<i class="fas fa-image text-indigo-400 text-xs"></i>`;
                
                produitsHtml += `
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    ${imageHtml}
                                </div>
                                <div>
                                    <p class="font-semibold text-[#0F172A] text-xs">${escapeHtml(p.nom_article || 'Sans nom')}</p>
                                    <p class="text-[10px] text-gray-400">${escapeHtml(p.auteur || '')}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-600">${escapeHtml(p.vendeur || 'Admin')}</td>
                        <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">${formatPrice(p.prix)}</td>
                        <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">0</td>
                        <td class="px-4 py-3">
                            <span class="text-[10px] font-semibold ${pStatutClass} px-2 py-1 rounded-full">${pStatutText}</span>
                        </td>
                    </tr>
                `;
            });
        } else {
            produitsHtml = `
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-400 text-sm">
                        <i class="fas fa-box-open text-2xl block mb-2"></i>
                        Aucun produit dans cette catégorie
                    </td>
                </tr>
            `;
        }

        content.innerHTML = `
            <div class="bg-gradient-to-br from-blue-100 to-indigo-100 rounded-2xl p-5 border border-indigo-100">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-4xl shadow-sm">
                        <i class="${cat.icone || 'fa-solid fa-globe'}"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <h4 class="text-lg font-bold text-[#0F172A]">${escapeHtml(cat.nom_categorie)}</h4>
                            <span class="text-[10px] font-semibold ${statutClass} px-2 py-0.5 rounded-full">
                                <i class="fas ${statutIcon} mr-1"></i>${statutText}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500">
                            Slug: <span class="font-mono">/${escapeHtml(cat.slug || '')}</span> · 
                            ID: ${cat.id} · 
                            Position: ${cat.ordre_affichage || 0}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-[#0EA486]">${cat.nb_produits || 0}</p>
                        <p class="text-xs text-gray-500">Produits</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-info-circle text-[#0EA486]"></i> Informations
                    </h5>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-gray-500">Nom</span>
                            <span class="font-medium text-[#0F172A]">${escapeHtml(cat.nom_categorie)}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-gray-500">Slug</span>
                            <span class="font-mono font-medium text-[#0F172A]">/${escapeHtml(cat.slug || '')}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-gray-500">Icône</span>
                            <span class="font-medium text-[#0F172A]"><i class="${cat.icone || 'fa-solid fa-globe'}"></i></span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-gray-500">Couleur</span>
                            <span class="font-medium text-[#0F172A]">${escapeHtml(cat.couleur || 'blue')}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-gray-500">Statut</span>
                            <span class="font-medium ${cat.statut === 'active' ? 'text-emerald-600' : 'text-gray-500'}">${statutText}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-gray-500">Position</span>
                            <span class="font-medium text-[#0F172A]">${cat.ordre_affichage || 0}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-gray-500"> Dates de création</span>
                            <span class="font-medium text-[#0F172A]">${formatDate(cat.created_at)}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-gray-500">Dernière modification</span>
                            <span class="font-medium text-[#0F172A]">${formatDate(cat.updated_at)}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-chart-bar text-[#0EA486]"></i> Statistiques
                    </h5>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-gray-500">Total produits</span>
                            <span class="font-semibold text-[#0F172A]">${cat.nb_produits || 0}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-gray-500">Produits approuvés</span>
                            <span class="font-semibold text-emerald-600">${cat.produits_actifs || 0}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-gray-500">Produits en attente/refusés</span>
                            <span class="font-semibold text-yellow-600">${cat.produits_inactifs || 0}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-gray-500">Sous-catégories</span>
                            <span class="font-semibold text-[#0F172A]">${cat.sous_categories || 0}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-gray-500">Vendeurs utilisant</span>
                            <span class="font-semibold text-[#0F172A]">${cat.vendeurs || 0}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                <h5 class="text-xs font-semibold text-gray-400 uppercase mb-2 flex items-center gap-2">
                    <i class="fas fa-align-left text-[#0EA486]"></i> Description
                </h5>
                <p class="text-sm text-gray-600 leading-relaxed">${escapeHtml(cat.description || 'Aucune description disponible pour cette catégorie.')}</p>
            </div>

            ${cat.image_cat ? `
            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                <h5 class="text-xs font-semibold text-gray-400 uppercase mb-2 flex items-center gap-2">
                    <i class="fas fa-image text-[#0EA486]"></i> Image
                </h5>
                <img src="/uploads/${cat.image_cat}" class="max-h-48 rounded-lg" onerror="this.style.display='none'">
            </div>
            ` : ''}

            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                    <h5 class="text-xs font-semibold text-gray-500 uppercase flex items-center gap-2">
                        <i class="fas fa-box text-[#0EA486]"></i> Produits associés (5 derniers)
                    </h5>
                    <span class="text-[10px] text-gray-400">${cat.nb_produits || 0} produits</span>
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
                            ${produitsHtml}
                        </tbody>
                    </table>
                </div>
            </div>
        `;
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
        content.innerHTML = `
            <div class="text-center py-12 text-gray-400">
                <i class="fas fa-info-circle text-4xl mb-3"></i>
                <p class="text-lg font-semibold">Aucune information disponible</p>
                <p class="text-sm">Veuillez sélectionner une catégorie pour voir les détails</p>
            </div>
        `;
    }

    openBtns.forEach(btn => {
        btn.removeEventListener('click', openModal);
        btn.addEventListener('click', openModal);
    });

    closeBtns.forEach(btn => {
        btn.removeEventListener('click', closeModal);
        btn.addEventListener('click', closeModal);
    });

    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });
})();

        // Modal fusion
        // Modal fusion
(function() {
    const modal = document.getElementById('mergeModal');
    const openBtns = document.querySelectorAll('.openMergeBtn');
    const closeBtns = document.querySelectorAll('.closeMergeBtn');
    const form = document.getElementById('mergeForm');
    const submitBtn = document.getElementById('confirmMergeBtn');
    const confirmCheckbox = document.getElementById('mergeConfirm');

    let currentSourceId = null;

    function openModal() {
        const btn = this;
        currentSourceId = btn.getAttribute('data-id');
        const nom = btn.getAttribute('data-name') || 'Catégorie';

        // 🔥 Pré-sélectionner la source
        const sourceSelect = document.getElementById('mergeSourceId');
        if (sourceSelect && currentSourceId) {
            sourceSelect.value = currentSourceId;
            // Désactiver la source pour éviter de la sélectionner comme cible
            const targetSelect = document.getElementById('mergeTargetId');
            if (targetSelect) {
                // Retirer la source de la liste des cibles
                Array.from(targetSelect.options).forEach(opt => {
                    if (opt.value == currentSourceId) {
                        opt.disabled = true;
                    }
                });
            }
        }

        // Réinitialiser le formulaire
        form.reset();
        confirmCheckbox.checked = false;
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
        currentSourceId = null;
        
        // Réactiver les options désactivées
        const targetSelect = document.getElementById('mergeTargetId');
        if (targetSelect) {
            Array.from(targetSelect.options).forEach(opt => {
                opt.disabled = false;
            });
        }
    }

    // Vérifier que la confirmation est cochée
    confirmCheckbox.addEventListener('change', function() {
        if (this.checked) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    });

    openBtns.forEach(btn => btn.addEventListener('click', openModal));
    closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });

    // 🔥 SOUMISSION AVEC FETCH
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const sourceId = document.getElementById('mergeSourceId').value;
        const targetId = document.getElementById('mergeTargetId').value;

        if (!sourceId || !targetId) {
            showToast('Erreur', 'Veuillez sélectionner les deux catégories', 'error');
            return;
        }

        if (sourceId === targetId) {
            showToast('Erreur', 'Les catégories source et cible doivent être différentes', 'error');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Fusion en cours...';

        const formData = new FormData(form);

        fetch('api.php?url=categories_merge', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeModal();
                showToast('Succès', data.message || 'Catégories fusionnées avec succès', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast('Erreur', data.error || 'Erreur lors de la fusion', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-object-group"></i> Confirmer la fusion';
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showToast('Erreur', 'Erreur serveur', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-object-group"></i> Confirmer la fusion';
        });
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
                    fetch('api.php?url=categories_delete', {
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
        // Modal toggle statut
(function() {
    const modal = document.getElementById('toggleModal');
    const openBtns = document.querySelectorAll('.toggleCategoryBtn');
    const closeBtns = document.querySelectorAll('.closeToggleBtn');
    const confirmBtn = document.getElementById('confirmToggleBtn');
    const title = document.getElementById('toggleTitle');
    const infoText = document.getElementById('toggleInfoText');
    const info = document.getElementById('toggleInfo');
    const categoryNameSpan = document.getElementById('toggleCategoryName'); // 👈 AJOUTE ÇA

    let currentCategoryId = null;
    let currentAction = null; // "activate" ou "deactivate"

    function openModal() {
        const btn = this;
        currentCategoryId = btn.getAttribute('data-id');
        const categoryName = btn.getAttribute('data-nom') || 'Catégorie'; // 👈 RÉCUPÈRE LE NOM

        // Afficher le nom dans le modal
        if (categoryNameSpan) {
            categoryNameSpan.textContent = categoryName;
        }

        const isOn = btn.querySelector('.fa-toggle-on');

        if (isOn) {
            currentAction = 'deactivate';

            title.innerHTML = '<i class="fas fa-toggle-off text-gray-500"></i> Désactiver la catégorie';
            infoText.textContent = 'La catégorie sera masquée du site, mais les produits associés resteront disponibles.';
            info.className = 'bg-yellow-50 rounded-xl p-4 border border-yellow-100';
            confirmBtn.className = 'px-5 py-2.5 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition';
            confirmBtn.innerHTML = '<i class="fas fa-pause"></i> Désactiver';

        } else {
            currentAction = 'activate';

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
        currentCategoryId = null;
        currentAction = null;
    }

    openBtns.forEach(btn => btn.addEventListener('click', openModal));
    closeBtns.forEach(btn => btn.addEventListener('click', closeModal));

    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });

    // CONFIRM ACTION + FETCH BACKEND
    confirmBtn.addEventListener('click', function() {

        if (!currentCategoryId) {
            showToast('Erreur', 'ID manquant', 'error');
            return;
        }

        fetch('api.php?url=categories_toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + currentCategoryId + '&statut=' + (currentAction === 'activate' ? 'active' : 'inactive')
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeModal();
                showToast('Succès', data.message || 'Statut mis à jour', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('Erreur', data.error || 'Action impossible', 'error');
            }
        })
        .catch(error => {
            console.error(error);
            showToast('Erreur', 'Erreur serveur', 'error');
        });
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

        // ============================================
// FILTRAGE CATEGORIES
// ============================================
(function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const sortFilter = document.getElementById('sortFilter');
    let debounceTimer = null;

    // Fonction pour construire le HTML d'une catégorie
    function renderCategory(category) {
        const statutClass = category.statut === 'active' 
            ? 'text-emerald-700 bg-emerald-100' 
            : 'text-gray-500 bg-gray-100';
        const statutIcon = category.statut === 'active' ? 'fa-check' : 'fa-pause';
        const statutText = category.statut === 'active' ? 'Active' : 'Inactive';
        const toggleClass = category.statut === 'active' 
            ? 'bg-yellow-50 text-yellow-600 hover:bg-yellow-100' 
            : 'bg-gray-50 text-gray-400 hover:bg-gray-100';
        const toggleIcon = category.statut === 'active' ? 'fa-toggle-on' : 'fa-toggle-off';
        const toggleTitle = category.statut === 'active' ? 'Désactiver' : 'Activer';

        return `
            <div class="category-card bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition overflow-hidden" 
                 draggable="true" 
                 data-id="${category.id}">
                <div class="flex items-stretch">
                    <div class="drag-handle w-12 bg-gray-50 hover:bg-gray-100 flex items-center justify-center cursor-grab active:cursor-grabbing border-r border-gray-100 transition">
                        <i class="fas fa-grip-vertical text-gray-400"></i>
                    </div>
                    <div class="flex-1 p-4 flex flex-col md:flex-row items-start md:items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="${category.icone || 'fa-solid fa-globe'} text-3xl text-blue-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <h5 class="text-sm font-bold text-[#0F172A]">${escapeHtml(category.nom_categorie)}</h5>
                                <span class="text-[10px] font-semibold ${statutClass} px-2 py-0.5 rounded-full">
                                    <i class="fas ${statutIcon} mr-1"></i>
                                    ${statutText}
                                </span>
                                <span class="text-[10px] font-mono text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">
                                    /${escapeHtml(category.slug || '')}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 line-clamp-1 mb-2">${escapeHtml(category.description || 'Aucune description')}</p>
                            <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                <span class="flex items-center gap-1"><i class="fas fa-box text-[#0EA486]"></i> <strong class="text-[#0F172A]">${category.nb_produits || 0}</strong> produits</span>
                                <span class="flex items-center gap-1"><i class="fas fa-calendar text-gray-400"></i> ${formatDate(category.created_at)}</span>
                                <span class="flex items-center gap-1"><i class="fas fa-sort-numeric-up text-gray-400"></i> Position <strong class="text-[#0F172A]">${category.ordre_affichage || 0}</strong></span>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <button class="openCategoryDetailBtn w-9 h-9 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition" 
                                    title="Voir détail"
                                    data-id="${category.id}">
                                <i class="fas fa-eye text-xs"></i>
                            </button>
                            <button class="openCategoryFormBtn w-9 h-9 rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-100 flex items-center justify-center transition" 
                                    title="Modifier"
                                    data-id="${category.id}"
                                    data-name="${escapeHtml(category.nom_categorie)}"
                                    data-slug="${escapeHtml(category.slug || '')}"
                                    data-emoji="${escapeHtml(category.icone || 'fa-solid fa-globe')}">
                                <i class="fas fa-edit text-xs"></i>
                            </button>
                            <button class="toggleCategoryBtn w-9 h-9 rounded-lg ${toggleClass} flex items-center justify-center transition" 
                                    title="${toggleTitle}"
                                    data-id="${category.id}">
                                <i class="fas ${toggleIcon} text-xs"></i>
                            </button>
                            <button class="openMergeBtn w-9 h-9 rounded-lg bg-purple-50 text-purple-600 hover:bg-purple-100 flex items-center justify-center transition" 
                                    title="Fusionner"
                                    data-id="${category.id}"
                                    data-name="${escapeHtml(category.nom_categorie)}">
                                <i class="fas fa-object-group text-xs"></i>
                            </button>
                            <button class="openDeleteBtn w-9 h-9 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition" 
                                    title="Supprimer"
                                    data-id="${category.id}">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    // Helper pour échapper le HTML
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Helper pour formater la date
    function formatDate(dateStr) {
        if (!dateStr) return '---';
        const date = new Date(dateStr);
        return date.toLocaleDateString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }

    // ============================================
// FILTRAGE CATEGORIES - VERSION CORRIGÉE
// ============================================
(function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const sortFilter = document.getElementById('sortFilter');
    let debounceTimer = null;

    // Helper pour échapper le HTML
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Helper pour formater la date
    function formatDate(dateStr) {
        if (!dateStr) return '---';
        const date = new Date(dateStr);
        return date.toLocaleDateString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }

    // Fonction pour construire le HTML d'une catégorie
    function renderCategory(category) {
        const statutClass = category.statut === 'active' 
            ? 'text-emerald-700 bg-emerald-100' 
            : 'text-gray-500 bg-gray-100';
        const statutIcon = category.statut === 'active' ? 'fa-check' : 'fa-pause';
        const statutText = category.statut === 'active' ? 'Active' : 'Inactive';
        const toggleClass = category.statut === 'active' 
            ? 'bg-yellow-50 text-yellow-600 hover:bg-yellow-100' 
            : 'bg-gray-50 text-gray-400 hover:bg-gray-100';
        const toggleIcon = category.statut === 'active' ? 'fa-toggle-on' : 'fa-toggle-off';
        const toggleTitle = category.statut === 'active' ? 'Désactiver' : 'Activer';

        return `
            <div class="category-card bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition overflow-hidden" 
                 draggable="true" 
                 data-id="${category.id}">
                <div class="flex items-stretch">
                    <div class="drag-handle w-12 bg-gray-50 hover:bg-gray-100 flex items-center justify-center cursor-grab active:cursor-grabbing border-r border-gray-100 transition">
                        <i class="fas fa-grip-vertical text-gray-400"></i>
                    </div>
                    <div class="flex-1 p-4 flex flex-col md:flex-row items-start md:items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="${category.icone || 'fa-solid fa-globe'} text-3xl text-blue-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <h5 class="text-sm font-bold text-[#0F172A]">${escapeHtml(category.nom_categorie)}</h5>
                                <span class="text-[10px] font-semibold ${statutClass} px-2 py-0.5 rounded-full">
                                    <i class="fas ${statutIcon} mr-1"></i>
                                    ${statutText}
                                </span>
                                <span class="text-[10px] font-mono text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">
                                    /${escapeHtml(category.slug || '')}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 line-clamp-1 mb-2">${escapeHtml(category.description || 'Aucune description')}</p>
                            <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                <span class="flex items-center gap-1"><i class="fas fa-box text-[#0EA486]"></i> <strong class="text-[#0F172A]">${category.nb_produits || 0}</strong> produits</span>
                                <span class="flex items-center gap-1"><i class="fas fa-calendar text-gray-400"></i> ${formatDate(category.created_at)}</span>
                                <span class="flex items-center gap-1"><i class="fas fa-sort-numeric-up text-gray-400"></i> Position <strong class="text-[#0F172A]">${category.ordre_affichage || 0}</strong></span>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <button class="openCategoryDetailBtn w-9 h-9 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition" 
                                    title="Voir détail"
                                    data-id="${category.id}">
                                <i class="fas fa-eye text-xs"></i>
                            </button>
                            <button class="openCategoryFormBtn w-9 h-9 rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-100 flex items-center justify-center transition" 
                                    title="Modifier"
                                    data-id="${category.id}"
                                    data-name="${escapeHtml(category.nom_categorie)}"
                                    data-slug="${escapeHtml(category.slug || '')}"
                                    data-emoji="${escapeHtml(category.icone || 'fa-solid fa-globe')}"
                                    data-couleur="${escapeHtml(category.couleur || 'blue')}"
                                    data-description="${escapeHtml(category.description || '')}"
                                    data-statut="${category.statut || 'active'}"
                                    data-image="${escapeHtml(category.image_cat || '')}">
                                <i class="fas fa-edit text-xs"></i>
                            </button>
                            <button class="toggleCategoryBtn w-9 h-9 rounded-lg ${toggleClass} flex items-center justify-center transition" 
                                    title="${toggleTitle}"
                                    data-id="${category.id}"
                                    data-nom="${escapeHtml(category.nom_categorie)}">
                                <i class="fas ${toggleIcon} text-xs"></i>
                            </button>
                            <button class="openMergeBtn w-9 h-9 rounded-lg bg-purple-50 text-purple-600 hover:bg-purple-100 flex items-center justify-center transition" 
                                    title="Fusionner"
                                    data-id="${category.id}"
                                    data-name="${escapeHtml(category.nom_categorie)}">
                                <i class="fas fa-object-group text-xs"></i>
                            </button>
                            <button class="openDeleteBtn w-9 h-9 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition" 
                                    title="Supprimer"
                                    data-id="${category.id}">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    function filterCategories() {
        const search = searchInput ? searchInput.value.trim() : '';
        const statut = statusFilter ? statusFilter.value : 'all';
        const tri = sortFilter ? sortFilter.value : 'ordre';

        //  Construction des paramètres
        let params = '';
        if (search) params += (params ? '&' : '') + 'search=' + encodeURIComponent(search);
        if (statut !== 'all') params += (params ? '&' : '') + 'statut=' + encodeURIComponent(statut);
        if (tri !== 'ordre') params += (params ? '&' : '') + 'tri=' + encodeURIComponent(tri);

        // Si pas de filtres, recharger la page
        if (!params) {
            location.reload();
            return;
        }

        // Afficher loading
        const container = document.getElementById('categoriesList');
        container.innerHTML = `
            <div class="col-span-full flex justify-center py-12">
                <div class="flex flex-col items-center gap-3">
                    <i class="fas fa-spinner fa-spin text-3xl text-[#0EA486]"></i>
                    <p class="text-gray-400 text-sm">Chargement...</p>
                </div>
            </div>
        `;

        // 🔥 Fetch vers backend
        fetch('api.php?url=categories_filter&' + params)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur HTTP: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('📥 Réponse filtre:', data);
                if (data.success) {
                    if (data.categories && data.categories.length > 0) {
                        let html = '';
                        data.categories.forEach(cat => {
                            html += renderCategory(cat);
                        });
                        container.innerHTML = html;
                        // Les événements sont déjà gérés par les closures existantes
                    } else {
                        container.innerHTML = `
                            <div class="col-span-full text-center py-12">
                                <i class="fas fa-search text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-400">Aucune catégorie trouvée</p>
                            </div>
                        `;
                    }
                } else {
                    showToast('Erreur', data.error || 'Erreur de chargement', 'error');
                }
            })
            .catch(error => {
                console.error('❌ Erreur:', error);
                showToast('Erreur', 'Erreur serveur', 'error');
            });
    }

    // Debounce pour la recherche
    function debounceFilter() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(filterCategories, 500);
    }

    // Écouteurs d'événements
    if (searchInput) searchInput.addEventListener('input', debounceFilter);
    if (statusFilter) statusFilter.addEventListener('change', filterCategories);
    if (sortFilter) sortFilter.addEventListener('change', filterCategories);
})();
    // Réattacher les événements après filtrage
    function reattachFilterEvents() {
        // Détail
        document.querySelectorAll('.openCategoryDetailBtn').forEach(btn => {
            btn.removeEventListener('click', window.openDetailModal);
            btn.addEventListener('click', window.openDetailModal);
        });
        
        // Édition
        document.querySelectorAll('.openCategoryFormBtn').forEach(btn => {
            btn.removeEventListener('click', window.openEditModal);
            btn.addEventListener('click', window.openEditModal);
        });
        
        // Toggle
        document.querySelectorAll('.toggleCategoryBtn').forEach(btn => {
            btn.removeEventListener('click', window.openToggleModal);
            btn.addEventListener('click', window.openToggleModal);
        });
        
        // Suppression
        document.querySelectorAll('.openDeleteBtn').forEach(btn => {
            btn.removeEventListener('click', window.openDeleteModal);
            btn.addEventListener('click', window.openDeleteModal);
        });
        
        // Fusion
        document.querySelectorAll('.openMergeBtn').forEach(btn => {
            btn.removeEventListener('click', window.openMergeModal);
            btn.addEventListener('click', window.openMergeModal);
        });
        
        // Drag & Drop
        if (typeof initDragDrop === 'function') {
            initDragDrop();
        }
    }

    // Debounce pour la recherche
    function debounceFilter() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(filterCategories, 500);
    }

    // Écouteurs d'événements
    if (searchInput) searchInput.addEventListener('input', debounceFilter);
    if (statusFilter) statusFilter.addEventListener('change', filterCategories);
    if (sortFilter) sortFilter.addEventListener('change', filterCategories);

    // Exposer les fonctions pour réattachement
    window.reattachFilterEvents = reattachFilterEvents;
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