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
                    <p class="text-2xl font-bold text-[#0F172A]"><?= $statsBannieres['total'] ?? 0 ?></p>
                    <p class="text-xs text-gray-400 mt-1">Bannières créées</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">ACTIVES</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= $statsBannieres['actives'] ?? 0 ?></p>
                    <p class="text-xs text-gray-400 mt-1">Bannières actives</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                            <i class="fas fa-eye"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">VUES</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= number_format($statsBannieres['vues'] ?? 0, 0, ',', ' ') ?></p>
                    <p class="text-xs text-gray-400 mt-1">Affichages totaux</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600">
                            <i class="fas fa-mouse-pointer"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-purple-600 bg-purple-50 px-2 py-1 rounded-full">CLICS</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= number_format($statsBannieres['clics'] ?? 0, 0, ',', ' ') ?></p>
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
                <?php if (!empty($bannieres)): ?>
                    <?php foreach ($bannieres as $banniere): ?>
                        <div class="banner-card bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition overflow-hidden" 
                            draggable="true" 
                            data-id="<?= $banniere['id'] ?>">
                            <div class="flex items-stretch">
                                <div class="drag-handle w-12 bg-gray-50 hover:bg-gray-100 flex items-center justify-center cursor-grab active:cursor-grabbing border-r border-gray-100 transition">
                                    <i class="fas fa-grip-vertical text-gray-400"></i>
                                </div>
                                <div class="flex-1 p-4 flex flex-col md:flex-row items-start md:items-center gap-4">
                                    <!-- Preview bannière -->
                                    <div class="w-full md:w-64 h-28 rounded-xl flex items-center justify-center relative overflow-hidden flex-shrink-0"
                                        style="background: linear-gradient(135deg, <?= $banniere['couleur_1'] ?? '#6366f1' ?>, <?= $banniere['couleur_2'] ?? '#8b5cf6' ?>);">
                                        <div class="absolute inset-0 bg-black/20"></div>
                                        <?php if (!empty($banniere['image'])): ?>
                                            <img src="/uploads/bannieres/<?= $banniere['image'] ?>" class="absolute inset-0 w-full h-full object-cover">
                                        <?php endif; ?>
                                        <div class="relative text-center text-white px-4 z-10">
                                            <p class="text-xs font-bold uppercase tracking-wider"><?= htmlspecialchars($banniere['sous_titre'] ?? 'Promo') ?></p>
                                            <p class="text-lg font-extrabold"><?= htmlspecialchars($banniere['titre'] ?? 'Bannière') ?></p>
                                        </div>
                                        <span class="absolute top-2 right-2 text-[9px] font-semibold text-white bg-<?= $banniere['statut'] === 'active' ? 'emerald' : 'gray' ?>-500/80 px-2 py-0.5 rounded-full z-10">
                                            <i class="fas <?= $banniere['statut'] === 'active' ? 'fa-check' : 'fa-pause' ?> mr-1"></i>
                                            <?= $banniere['statut'] === 'active' ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-center gap-2 mb-1">
                                            <h5 class="text-sm font-bold text-[#0F172A]"><?= htmlspecialchars($banniere['titre'] ?? 'Sans titre') ?></h5>
                                            <span class="text-[10px] font-mono text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">
                                                Position <?= $banniere['ordre_affichage'] ?? 0 ?>
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-500 line-clamp-1 mb-2"><?= htmlspecialchars($banniere['sous_titre'] ?? '') ?></p>
                                        <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500 mb-2">
                                            <span class="flex items-center gap-1"><i class="fas fa-calendar text-gray-400"></i> Du <?= date('d/m/Y', strtotime($banniere['date_debut'] ?? 'now')) ?> au <?= date('d/m/Y', strtotime($banniere['date_fin'] ?? 'now')) ?></span>
                                            <span class="flex items-center gap-1"><i class="fas fa-link text-gray-400"></i> <span class="truncate max-w-[150px]"><?= htmlspecialchars($banniere['url_destination'] ?? '') ?></span></span>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="text-[10px] font-semibold text-blue-700 bg-blue-100 px-2 py-0.5 rounded-full">
                                                <i class="fas fa-eye mr-1"></i><?= number_format($banniere['vues'] ?? 0, 0, ',', ' ') ?> vues
                                            </span>
                                            <span class="text-[10px] font-semibold text-purple-700 bg-purple-100 px-2 py-0.5 rounded-full">
                                                <i class="fas fa-mouse-pointer mr-1"></i><?= number_format($banniere['clics'] ?? 0, 0, ',', ' ') ?> clics
                                            </span>
                                            <?php 
                                                $ctr = ($banniere['vues'] ?? 0) > 0 ? round(($banniere['clics'] ?? 0) / ($banniere['vues'] ?? 1) * 100, 1) : 0;
                                            ?>
                                            <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">
                                                CTR: <?= $ctr ?>%
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <button class="openBannerPreviewBtn w-9 h-9 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition" 
                                                title="Aperçu"
                                                data-id="<?= $banniere['id'] ?>">
                                            <i class="fas fa-eye text-xs"></i>
                                        </button>
                                        <button class="openBannerFormBtn w-9 h-9 rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-100 flex items-center justify-center transition" 
                                                title="Modifier"
                                                data-id="<?= $banniere['id'] ?>"
                                                data-titre="<?= htmlspecialchars($banniere['titre'] ?? '') ?>"
                                                data-sous-titre="<?= htmlspecialchars($banniere['sous_titre'] ?? '') ?>"
                                                data-image="<?= $banniere['image'] ?? '' ?>"
                                                data-texte-bouton="<?= htmlspecialchars($banniere['texte_bouton'] ?? 'Voir les offres') ?>"
                                                data-url="<?= htmlspecialchars($banniere['url_destination'] ?? '') ?>"
                                                data-debut="<?= $banniere['date_debut'] ?? '' ?>"
                                                data-fin="<?= $banniere['date_fin'] ?? '' ?>"
                                                data-statut="<?= $banniere['statut'] ?? 'active' ?>">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                        <button class="toggleBannerBtn w-9 h-9 rounded-lg <?= $banniere['statut'] === 'active' ? 'bg-yellow-50 text-yellow-600 hover:bg-yellow-100' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' ?> flex items-center justify-center transition" 
                                                title="<?= $banniere['statut'] === 'active' ? 'Désactiver' : 'Activer' ?>"
                                                data-id="<?= $banniere['id'] ?>"
                                                data-statut="<?= $banniere['statut'] ?? 'active' ?>"
                                                data-nom="<?= htmlspecialchars($banniere['titre'] ?? 'Bannière') ?>">
                                            <i class="fas <?= $banniere['statut'] === 'active' ? 'fa-toggle-on' : 'fa-toggle-off' ?> text-xs"></i>
                                        </button>
                                        
                                        <button class="openBannerStatsBtn w-9 h-9 rounded-lg bg-purple-50 text-purple-600 hover:bg-purple-100 flex items-center justify-center transition" 
                                                title="Statistiques"
                                                data-id="<?= $banniere['id'] ?>">
                                            <i class="fas fa-chart-line text-xs"></i>
                                        </button>
                                        <button class="openDeleteBannerBtn w-9 h-9 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition" 
                                                title="Supprimer"
                                                data-id="<?= $banniere['id'] ?>"
                                                data-nom="<?= htmlspecialchars($banniere['titre'] ?? 'Bannière') ?>">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-images text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-400">Aucune bannière créée</p>
                        <button id="openBannerFormBtn" class="mt-3 px-4 py-2 rounded-lg bg-[#0EA486] hover:bg-[#0c8f75] text-white text-xs font-semibold flex items-center gap-2 mx-auto">
                            <i class="fas fa-plus"></i> Créer la première bannière
                        </button>
                    </div>
                <?php endif; ?>
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
                <p class="text-2xl font-bold text-[#0F172A]"><?= $statsPromo['total'] ?? 0 ?></p>
                <p class="text-xs text-gray-400 mt-1">Codes créés</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">ACTIFS</span>
                </div>
                <p class="text-2xl font-bold text-[#0F172A]"><?= $statsPromo['actifs'] ?? 0 ?></p>
                <p class="text-xs text-gray-400 mt-1">Codes actifs</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">UTILISÉS</span>
                </div>
                <p class="text-2xl font-bold text-[#0F172A]"><?= number_format($statsPromo['utilisations'] ?? 0, 0, ',', ' ') ?></p>
                <p class="text-xs text-gray-400 mt-1">Utilisations totales</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600">
                        <i class="fas fa-percent"></i>
                    </div>
                    <span class="text-[10px] font-semibold text-orange-600 bg-orange-50 px-2 py-1 rounded-full">REMISES</span>
                </div>
                <p class="text-2xl font-bold text-[#0F172A]"><?= number_format($statsPromo['remises'] ?? 0, 0, ',', ' ') ?> FCFA</p>
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
                            <?php if (!empty($codesPromo)): ?>
                                <?php foreach ($codesPromo as $promo): ?>
                                    <?php
                                        // Calcul du pourcentage d'utilisation
                                        $utilisationsMax = $promo['utilisations_max'] ?? 0;
                                        $utilisationsActuelles = $promo['utilisations_actuelles'] ?? 0;
                                        $pourcentage = $utilisationsMax > 0 ? round(($utilisationsActuelles / $utilisationsMax) * 100) : 0;
                                        
                                        // Statut du code
                                        $statutClass = 'text-gray-500 bg-gray-100';
                                        $statutText = 'Inactif';
                                        if ($promo['statut'] === 'active') {
                                            // Vérifier si le code est expiré
                                            if (!empty($promo['date_expiration']) && strtotime($promo['date_expiration']) < time()) {
                                                $statutClass = 'text-red-700 bg-red-100';
                                                $statutText = 'Expiré';
                                            } else {
                                                $statutClass = 'text-emerald-700 bg-emerald-100';
                                                $statutText = 'Actif';
                                            }
                                        }
                                        
                                        // Type de remise
                                        $typeText = $promo['type'] === 'percentage' ? 'Pourcentage' : 'Montant fixe';
                                        $typeClass = $promo['type'] === 'percentage' ? 'text-purple-700 bg-purple-100' : 'text-blue-700 bg-blue-100';
                                        
                                        // Valeur formatée
                                        $valeurFormatee = $promo['type'] === 'percentage' ? $promo['valeur'] . '%' : number_format($promo['valeur'], 0, ',', ' ') . ' FCFA';
                                    ?>
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 bg-gradient-to-br from-purple-100 to-pink-100 rounded-lg flex items-center justify-center text-purple-600">
                                                    <i class="fas fa-ticket-alt text-xs"></i>
                                                </div>
                                                <span class="font-mono font-bold text-[#0F172A] text-sm"><?= htmlspecialchars($promo['code']) ?></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-[10px] font-semibold <?= $typeClass ?> px-2 py-1 rounded-full"><?= $typeText ?></span>
                                        </td>
                                        <td class="px-4 py-3 text-xs font-bold text-[#0EA486]"><?= $valeurFormatee ?></td>
                                        <td class="px-4 py-3">
                                            <div class="text-xs text-gray-500 space-y-0.5">
                                                <p>Min: <?= number_format($promo['montant_minimum'] ?? 0, 0, ',', ' ') ?> FCFA</p>
                                                <p>Max: <?= $utilisationsMax > 0 ? $utilisationsMax . ' utilisations' : 'Illimité' ?></p>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <div class="flex-1 bg-gray-100 rounded-full h-1.5 overflow-hidden w-16">
                                                    <div class="bg-[#0EA486] h-full" style="width: <?= min($pourcentage, 100) ?>%;"></div>
                                                </div>
                                                <span class="text-xs font-semibold text-[#0F172A]"><?= $utilisationsActuelles ?>/<?= $utilisationsMax > 0 ? $utilisationsMax : '∞' ?></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-500">
                                            <?= !empty($promo['date_expiration']) ? date('d/m/Y H:i', strtotime($promo['date_expiration'])) : '---' ?>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-[10px] font-semibold <?= $statutClass ?> px-2 py-1 rounded-full"><?= $statutText ?></span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-end gap-1">
                                                <button class="openPromoHistoryBtn w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center" 
                                                        title="Historique"
                                                        data-id="<?= $promo['id'] ?>">
                                                    <i class="fas fa-history text-xs"></i>
                                                </button>
                                                <button class="openPromoFormBtn w-8 h-8 rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-100 flex items-center justify-center" 
                                                        title="Modifier"
                                                        data-id="<?= $promo['id'] ?>"
                                                        data-code="<?= htmlspecialchars($promo['code']) ?>"
                                                        data-type="<?= $promo['type'] ?>"
                                                        data-valeur="<?= $promo['valeur'] ?>"
                                                        data-montant-minimum="<?= $promo['montant_minimum'] ?? 0 ?>"
                                                        data-utilisations-max="<?= $promo['utilisations_max'] ?? '' ?>"
                                                        data-date-expiration="<?= $promo['date_expiration'] ?? '' ?>"
                                                        data-categorie-id="<?= $promo['categorie_id'] ?? '' ?>"
                                                        data-produit-id="<?= $promo['produit_id'] ?? '' ?>"
                                                        data-utilisateur-id="<?= $promo['utilisateur_id'] ?? '' ?>"
                                                        data-statut="<?= $promo['statut'] ?? 'active' ?>">
                                                    <i class="fas fa-edit text-xs"></i>
                                                </button>
                                                <button class="togglePromoBtn w-8 h-8 rounded-lg <?= $promo['statut'] === 'active' ? 'bg-yellow-50 text-yellow-600 hover:bg-yellow-100' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' ?> flex items-center justify-center" 
                                                        title="<?= $promo['statut'] === 'active' ? 'Désactiver' : 'Activer' ?>"
                                                        data-id="<?= $promo['id'] ?>"
                                                        data-statut="<?= $promo['statut'] ?? 'active' ?>"
                                                        data-code="<?= htmlspecialchars($promo['code']) ?>">
                                                    <i class="fas <?= $promo['statut'] === 'active' ? 'fa-toggle-on' : 'fa-toggle-off' ?> text-xs"></i>
                                                </button>
                                                <button class="openDeletePromoBtn w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center" 
                                                        title="Supprimer"
                                                        data-id="<?= $promo['id'] ?>"
                                                        data-code="<?= htmlspecialchars($promo['code']) ?>">
                                                    <i class="fas fa-trash text-xs"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-400 text-sm">
                                        <i class="fas fa-ticket-alt text-3xl block mb-2"></i>
                                        Aucun code promo créé
                                    </td>
                                </tr>
                            <?php endif; ?>
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
                        <span><?= count($codesPromo) ?> codes</span>
                        <button class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-50">
                            <i class="fas fa-chevron-left text-[10px]"></i>
                        </button>
                        <button class="w-8 h-8 rounded-lg bg-[#0EA486] text-white flex items-center justify-center">1</button>
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
                <button type="button" class="closeBannerFormBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                    <!-- Formulaire -->
                    <form id="bannerForm" class="p-6 space-y-4 border-r border-gray-100" enctype="multipart/form-data">
                        <!-- ID caché pour l'édition -->
                        <input type="hidden" id="bannerId" name="id" value="">

                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Titre <span class="text-red-500">*</span></label>
                            <input type="text" id="bannerTitle" name="titre" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="Ex: Promo Black Friday">
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Sous-titre</label>
                            <input type="text" id="bannerSubtitle" name="sous_titre" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="Ex: -50% sur tous les templates">
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Image de fond <span class="text-red-500">*</span></label>
                            <div class="border-2 border-dashed border-gray-200 rounded-xl p-5 text-center hover:border-[#0EA486] transition cursor-pointer relative">
                                <input type="file" id="bannerImage" name="image" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                                <i class="fas fa-cloud-upload-alt text-2xl text-gray-300 mb-2"></i>
                                <p class="text-xs text-gray-500">Glissez-déposez ou <span class="text-[#0EA486] font-semibold">parcourir</span></p>
                                <p class="text-[10px] text-gray-400 mt-1">PNG, JPG · max 2 MB · 1200x400 recommandé</p>
                                <div id="bannerImagePreview" class="hidden mt-3">
                                    <img id="bannerImagePreviewImg" src="" class="max-h-32 rounded-lg mx-auto">
                                    <p class="text-xs text-emerald-600 mt-1">Image sélectionnée</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-semibold text-gray-600 mb-1 block">Texte du bouton</label>
                                <input type="text" id="bannerButtonText" name="texte_bouton" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="Ex: Voir les offres">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-600 mb-1 block">URL de destination <span class="text-red-500">*</span></label>
                                <input type="url" id="bannerUrl" name="url_destination" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="https://...">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-semibold text-gray-600 mb-1 block">Date de début <span class="text-red-500">*</span></label>
                                <input type="datetime-local" id="bannerDateDebut" name="date_debut" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-600 mb-1 block">Date de fin <span class="text-red-500">*</span></label>
                                <input type="datetime-local" id="bannerDateFin" name="date_fin" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                            </div>
                        </div>

                        <div>
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
                                        <p class="text-[10px] text-gray-500">Masquée</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                            <button type="button" class="closeBannerFormBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                                Annuler
                            </button>
                            <button type="submit" id="bannerSubmitBtn" class="px-5 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                                <i class="fas fa-save"></i> <span id="bannerSubmitText">Enregistrer</span>
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
                                <p id="previewSubtitle" class="text-sm font-bold uppercase tracking-wider mb-2">Sous-titre</p>
                                <p id="previewTitle" class="text-3xl font-extrabold mb-4">Titre de la bannière</p>
                                <button id="previewButton" class="px-6 py-2.5 bg-white text-[#0F172A] rounded-xl text-sm font-bold hover:scale-105 transition">
                                    Voir les offres
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

            <div class="overflow-y-auto p-6 space-y-5" id="bannerPreviewContent">
                <!-- En-tête de la bannière -->
                <div>
                    <h5 class="text-xs font-semibold text-gray-500 uppercase mb-2 flex items-center gap-2">
                        <i class="fas fa-desktop text-[#0EA486]"></i> Version desktop
                    </h5>
                    <div id="previewDesktop" class="rounded-2xl h-64 flex items-center justify-center relative overflow-hidden shadow-lg"
                        style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                        <div class="absolute inset-0 bg-black/30"></div>
                        <div class="relative text-center text-white px-6 z-10">
                            <p id="previewDesktopSubtitle" class="text-sm font-bold uppercase tracking-wider mb-2">Promo</p>
                            <p id="previewDesktopTitle" class="text-3xl font-extrabold mb-4">Titre de la bannière</p>
                            <button id="previewDesktopButton" class="px-6 py-2.5 bg-white text-[#0F172A] rounded-xl text-sm font-bold hover:scale-105 transition">
                                Voir les offres
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
                        <div id="previewMobile" class="rounded-2xl h-48 flex items-center justify-center relative overflow-hidden shadow-lg"
                            style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                            <div class="absolute inset-0 bg-black/30"></div>
                            <div class="relative text-center text-white px-4 z-10">
                                <p id="previewMobileSubtitle" class="text-xs font-bold uppercase tracking-wider mb-1">Promo</p>
                                <p id="previewMobileTitle" class="text-xl font-extrabold mb-3">Titre de la bannière</p>
                                <button id="previewMobileButton" class="px-4 py-2 bg-white text-[#0F172A] rounded-lg text-xs font-bold">
                                    Voir les offres
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
                            <span id="previewInfoTitle" class="font-medium text-[#0F172A]">---</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-gray-500">URL</span>
                            <span id="previewInfoUrl" class="font-mono text-[#0F172A] truncate max-w-[200px]">---</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-gray-500">Période</span>
                            <span id="previewInfoPeriod" class="font-medium text-[#0F172A]">---</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-gray-500">Statut</span>
                            <span id="previewInfoStatus" class="font-medium text-emerald-600">---</span>
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

        <div class="overflow-y-auto p-6 space-y-5" id="bannerStatsContent">
            <!-- Stats cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                    <p class="text-[10px] text-blue-600 font-semibold uppercase mb-1">Affichages</p>
                    <p id="statsVues" class="text-2xl font-bold text-[#0F172A]">0</p>
                    <p class="text-[10px] text-emerald-600 mt-1"><i class="fas fa-arrow-up text-[8px]"></i> <span id="statsVuesEvolution">0%</span></p>
                </div>
                <div class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                    <p class="text-[10px] text-purple-600 font-semibold uppercase mb-1">Clics</p>
                    <p id="statsClics" class="text-2xl font-bold text-[#0F172A]">0</p>
                    <p class="text-[10px] text-emerald-600 mt-1"><i class="fas fa-arrow-up text-[8px]"></i> <span id="statsClicsEvolution">0%</span></p>
                </div>
                <div class="p-4 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl border border-emerald-100">
                    <p class="text-[10px] text-emerald-600 font-semibold uppercase mb-1">CTR</p>
                    <p id="statsCtr" class="text-2xl font-bold text-[#0F172A]">0%</p>
                    <p class="text-[10px] text-gray-400 mt-1">Taux de clic</p>
                </div>
                <div class="p-4 bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl border border-amber-100">
                    <p class="text-[10px] text-amber-600 font-semibold uppercase mb-1">Jours actifs</p>
                    <p id="statsJoursActifs" class="text-2xl font-bold text-[#0F172A]">0</p>
                    <p class="text-[10px] text-gray-400 mt-1">Depuis activation</p>
                </div>
            </div>

            <!-- Graphique -->
            <div class="bg-white rounded-2xl p-5 border border-gray-100">
                <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                    <i class="fas fa-chart-bar text-[#0EA486]"></i> Évolution sur 7 jours
                </h5>
                <div id="statsChart" class="flex items-end justify-between gap-2 h-40">
                    <!-- Les barres seront générées par JS -->
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <div class="w-full bg-blue-200 rounded-t-lg" style="height: 10%;"></div>
                        <div class="w-full bg-purple-200 rounded-t-lg" style="height: 5%;"></div>
                        <span class="text-[10px] text-gray-400">Lun</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <div class="w-full bg-blue-200 rounded-t-lg" style="height: 10%;"></div>
                        <div class="w-full bg-purple-200 rounded-t-lg" style="height: 5%;"></div>
                        <span class="text-[10px] text-gray-400">Mar</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <div class="w-full bg-blue-200 rounded-t-lg" style="height: 10%;"></div>
                        <div class="w-full bg-purple-200 rounded-t-lg" style="height: 5%;"></div>
                        <span class="text-[10px] text-gray-400">Mer</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <div class="w-full bg-blue-200 rounded-t-lg" style="height: 10%;"></div>
                        <div class="w-full bg-purple-200 rounded-t-lg" style="height: 5%;"></div>
                        <span class="text-[10px] text-gray-400">Jeu</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <div class="w-full bg-blue-200 rounded-t-lg" style="height: 10%;"></div>
                        <div class="w-full bg-purple-200 rounded-t-lg" style="height: 5%;"></div>
                        <span class="text-[10px] text-gray-400">Ven</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <div class="w-full bg-blue-200 rounded-t-lg" style="height: 10%;"></div>
                        <div class="w-full bg-purple-200 rounded-t-lg" style="height: 5%;"></div>
                        <span class="text-[10px] text-gray-400">Sam</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <div class="w-full bg-[#0EA486] rounded-t-lg" style="height: 10%;"></div>
                        <div class="w-full bg-purple-800 rounded-t-lg" style="height: 5%;"></div>
                        <span class="text-[10px] text-gray-400 font-semibold">Dim</span>
                    </div>
                </div>
                <div class="flex items-center gap-4 mt-3 text-xs">
                    <span class="flex items-center gap-2"><span class="w-3 h-3 bg-blue-500 rounded"></span> Affichages</span>
                    <span class="flex items-center gap-2"><span class="w-3 h-3 bg-purple-500 rounded"></span> Clics</span>
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
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                <p class="text-xs text-gray-500 mb-1">Bannière concernée</p>
                <p id="toggleBannerName" class="text-sm font-bold text-[#0F172A] flex items-center gap-2">
                    <span class="text-xl"></span> 
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
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                <p class="text-xs text-gray-500 mb-1">Bannière à supprimer</p>
                <p id="deleteBannerName" class="text-sm font-bold text-[#0F172A] flex items-center gap-2">
                    <span class="text-xl"></span> 
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

function formatDateForInput(dateStr) {
    if (!dateStr) return '';
    // MySQL: "2025-11-20 14:30:00" → HTML: "2025-11-20T14:30"
    return dateStr.replace(' ', 'T').slice(0, 16);
}

function formatDateForMySQL(datetime) {
    if (!datetime) return null;
    // HTML: "2025-11-20T14:30" → MySQL: "2025-11-20 14:30:00"
    return datetime.replace('T', ' ') + ':00';
}

// ============================================
// MODAL FORMULAIRE BANNIÈRE
// ============================================
(function() {
    const modal = document.getElementById('bannerFormModal');
    const openBtns = document.querySelectorAll('.openBannerFormBtn, #openBannerFormBtn');
    const closeBtns = document.querySelectorAll('.closeBannerFormBtn');
    const form = document.getElementById('bannerForm');
    const title = document.getElementById('bannerFormTitle');
    const submitBtn = document.getElementById('bannerSubmitBtn');
    const submitText = document.getElementById('bannerSubmitText');

    let currentBannerId = null;

    const titleInput = document.getElementById('bannerTitle');
    const subtitleInput = document.getElementById('bannerSubtitle');
    const buttonTextInput = document.getElementById('bannerButtonText');
    const previewTitle = document.getElementById('previewTitle');
    const previewSubtitle = document.getElementById('previewSubtitle');
    const previewButton = document.getElementById('previewButton');

    function updatePreview() {
        previewTitle.textContent = titleInput.value || 'Titre de la bannière';
        previewSubtitle.textContent = subtitleInput.value || 'Sous-titre';
        previewButton.textContent = buttonTextInput.value || 'Voir les offres';
    }

    titleInput.addEventListener('input', updatePreview);
    subtitleInput.addEventListener('input', updatePreview);
    buttonTextInput.addEventListener('input', updatePreview);

    const imageInput = document.getElementById('bannerImage');
    const imagePreview = document.getElementById('bannerImagePreview');
    const imagePreviewImg = document.getElementById('bannerImagePreviewImg');

    imageInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreviewImg.src = e.target.result;
                imagePreview.classList.remove('hidden');
                document.getElementById('bannerPreview').style.backgroundImage = `url(${e.target.result})`;
                document.getElementById('bannerPreview').style.backgroundSize = 'cover';
                document.getElementById('bannerPreview').style.backgroundPosition = 'center';
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    function openBannerFormModal(isEdit = false, data = null) {
        title.textContent = isEdit ? 'Modifier la bannière' : 'Nouvelle bannière';
        submitText.textContent = isEdit ? 'Modifier' : 'Enregistrer';
        
        form.reset();
        document.getElementById('bannerId').value = '';
        imagePreview.classList.add('hidden');
        imagePreviewImg.src = '';
        document.getElementById('bannerPreview').style.backgroundImage = '';
        document.getElementById('bannerPreview').style.background = 'linear-gradient(135deg, #6366f1, #8b5cf6)';
        
        if (isEdit && data) {
            currentBannerId = data.id;
            document.getElementById('bannerId').value = data.id;
            document.getElementById('bannerTitle').value = data.titre || '';
            document.getElementById('bannerSubtitle').value = data.sousTitre || '';
            document.getElementById('bannerButtonText').value = data.texteBouton || 'Voir les offres';
            document.getElementById('bannerUrl').value = data.url || '';
            
            // 🔥 ICI : CONVERSION MySQL → HTML pour les dates
            document.getElementById('bannerDateDebut').value = formatDateForInput(data.dateDebut || '');
            document.getElementById('bannerDateFin').value = formatDateForInput(data.dateFin || '');
            
            const statusRadios = document.querySelectorAll('input[name="statut"]');
            statusRadios.forEach(radio => {
                radio.checked = radio.value === (data.statut || 'active');
            });
            
            if (data.image) {
                imagePreviewImg.src = '/uploads/bannieres/' + data.image;
                imagePreview.classList.remove('hidden');
            }
            updatePreview();
        } else {
            currentBannerId = null;
            const statusRadios = document.querySelectorAll('input[name="statut"]');
            statusRadios.forEach(radio => {
                radio.checked = radio.value === 'active';
            });
            updatePreview();
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeBannerFormModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
        currentBannerId = null;
        document.getElementById('bannerPreview').style.background = 'linear-gradient(135deg, #6366f1, #8b5cf6)';
    }

    document.querySelectorAll('#openBannerFormBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            openBannerFormModal(false);
        });
    });

    document.querySelectorAll('.openBannerFormBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            const data = {
                id: this.dataset.id || '',
                titre: this.dataset.titre || '',
                sousTitre: this.dataset.sousTitre || '',
                image: this.dataset.image || '',
                texteBouton: this.dataset.texteBouton || 'Voir les offres',
                url: this.dataset.url || '',
                dateDebut: this.dataset.debut || '',
                dateFin: this.dataset.fin || '',
                statut: this.dataset.statut || 'active'
            };
            openBannerFormModal(true, data);
        });
    });

    closeBtns.forEach(btn => btn.addEventListener('click', closeBannerFormModal));
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeBannerFormModal();
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const titre = document.getElementById('bannerTitle').value.trim();
        const url = document.getElementById('bannerUrl').value.trim();
        const dateDebut = document.getElementById('bannerDateDebut').value;
        const dateFin = document.getElementById('bannerDateFin').value;

        if (!titre) {
            showToast('Erreur', 'Le titre est requis', 'error');
            return;
        }
        if (!url) {
            showToast('Erreur', 'L\'URL de destination est requise', 'error');
            return;
        }
        if (!dateDebut) {
            showToast('Erreur', 'La date de début est requise', 'error');
            return;
        }
        if (!dateFin) {
            showToast('Erreur', 'La date de fin est requise', 'error');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';

        const formData = new FormData(form);
        formData.append('id', currentBannerId || '');


        // 🔥 ICI : CONVERSION HTML → MySQL pour les dates
        formData.set('date_debut', formatDateForMySQL(dateDebut));
        formData.set('date_fin', formatDateForMySQL(dateFin));

        const action = currentBannerId ? 'banniere_edit' : 'banniere_add';

        fetch('api.php?url=' + action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                closeBannerFormModal();
                showToast('Succès', data.message || 'Bannière enregistrée avec succès', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('Erreur', data.error || 'Erreur lors de l\'enregistrement', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save"></i> ' + (currentBannerId ? 'Modifier' : 'Enregistrer');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showToast('Erreur', error.message || 'Erreur serveur', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> ' + (currentBannerId ? 'Modifier' : 'Enregistrer');
        });
    });
})();

// ============================================
// MODAL APERÇU BANNIÈRE
// ============================================
(function() {
    const modal = document.getElementById('bannerPreviewModal');
    const openBtns = document.querySelectorAll('.openBannerPreviewBtn');
    const closeBtns = document.querySelectorAll('.closeBannerPreviewBtn');
    
    function openBannerPreviewModal() {
        const btn = this;
        const id = btn.getAttribute('data-id');
        
        if (!id) {
            showToast('Erreur', 'ID manquant', 'error');
            return;
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        fetch('api.php?url=banniere_get&id=' + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderPreview(data.data);
                } else {
                    showToast('Erreur', data.error || 'Erreur de chargement', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Erreur', 'Erreur serveur', 'error');
            });
    }

    function renderPreview(banner) {
        const colors = [
            ['#6366f1', '#8b5cf6'],
            ['#f59e0b', '#ef4444'],
            ['#10b981', '#06b6d4'],
            ['#8b5cf6', '#ec4899'],
            ['#f472b6', '#fb923c'],
            ['#14b8a6', '#3b82f6'],
            ['#a855f7', '#d946ef'],
            ['#f97316', '#ef4444']
        ];
        const color = colors[Math.floor(Math.random() * colors.length)];
        const gradient = `linear-gradient(135deg, ${color[0]}, ${color[1]})`;
        
        const desktopDiv = document.getElementById('previewDesktop');
        if (desktopDiv) {
            desktopDiv.style.background = gradient;
            if (banner.image) {
                desktopDiv.style.backgroundImage = `url('/uploads/bannieres/${banner.image}')`;
                desktopDiv.style.backgroundSize = 'cover';
                desktopDiv.style.backgroundPosition = 'center';
            }
        }
        document.getElementById('previewDesktopSubtitle').textContent = banner.sous_titre || 'Promo';
        document.getElementById('previewDesktopTitle').textContent = banner.titre || 'Titre de la bannière';
        document.getElementById('previewDesktopButton').textContent = banner.texte_bouton || 'Voir les offres';
        
        const mobileDiv = document.getElementById('previewMobile');
        if (mobileDiv) {
            mobileDiv.style.background = gradient;
            if (banner.image) {
                mobileDiv.style.backgroundImage = `url('/uploads/bannieres/${banner.image}')`;
                mobileDiv.style.backgroundSize = 'cover';
                mobileDiv.style.backgroundPosition = 'center';
            }
        }
        document.getElementById('previewMobileSubtitle').textContent = banner.sous_titre || 'Promo';
        document.getElementById('previewMobileTitle').textContent = banner.titre || 'Titre de la bannière';
        document.getElementById('previewMobileButton').textContent = banner.texte_bouton || 'Voir les offres';
        
        document.getElementById('previewInfoTitle').textContent = banner.titre || '---';
        document.getElementById('previewInfoUrl').textContent = banner.url_destination || '---';
        
        const dateDebut = banner.date_debut ? new Date(banner.date_debut).toLocaleDateString('fr-FR') : '---';
        const dateFin = banner.date_fin ? new Date(banner.date_fin).toLocaleDateString('fr-FR') : '---';
        document.getElementById('previewInfoPeriod').textContent = `Du ${dateDebut} au ${dateFin}`;
        
        const statusText = banner.statut === 'active' ? 'Active' : 'Inactive';
        const statusClass = banner.statut === 'active' ? 'text-emerald-600' : 'text-gray-500';
        const statusEl = document.getElementById('previewInfoStatus');
        statusEl.textContent = statusText;
        statusEl.className = `font-medium ${statusClass}`;
    }

    function closeBannerPreviewModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    openBtns.forEach(btn => btn.addEventListener('click', openBannerPreviewModal));
    closeBtns.forEach(btn => btn.addEventListener('click', closeBannerPreviewModal));
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeBannerPreviewModal();
    });
})();

// ============================================
// MODAL STATS BANNIÈRE
// ============================================
(function() {
    const modal = document.getElementById('bannerStatsModal');
    const openBtns = document.querySelectorAll('.openBannerStatsBtn');
    const closeBtns = document.querySelectorAll('.closeBannerStatsBtn');
    const content = document.getElementById('bannerStatsContent');

    function openBannerStatsModal() {
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
                    <p class="text-gray-400 text-sm">Chargement des statistiques...</p>
                </div>
            </div>
        `;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        fetch('api.php?url=banniere_stats&id=' + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderStats(data.data);
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

    function renderStats(data) {
        const total = data.total || {};
        const days = data['7days'] || [];

        document.getElementById('statsVues').textContent = total.vues || 0;
        document.getElementById('statsClics').textContent = total.clics || 0;
        document.getElementById('statsCtr').textContent = (total.ctr || 0) + '%';
        document.getElementById('statsJoursActifs').textContent = '0';

        const chartContainer = document.getElementById('statsChart');
        const jours = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
        
        let maxVues = 1;
        let maxClics = 1;
        days.forEach(d => {
            if (d.vues > maxVues) maxVues = d.vues;
            if (d.clics > maxClics) maxClics = d.clics;
        });

        let html = '';
        jours.forEach((jour, index) => {
            const dayData = days.find(d => {
                const d2 = new Date(d.jour);
                return d2.getDay() === (index + 1);
            }) || { vues: 0, clics: 0 };
            
            const hauteurVues = Math.max(5, (dayData.vues / maxVues) * 100);
            const hauteurClics = Math.max(5, (dayData.clics / maxClics) * 100);
            
            html += `
                <div class="flex-1 flex flex-col items-center gap-1">
                    <div class="w-full bg-blue-200 rounded-t-lg" style="height: ${hauteurVues}%;"></div>
                    <div class="w-full bg-purple-200 rounded-t-lg" style="height: ${hauteurClics}%;"></div>
                    <span class="text-[10px] text-gray-400">${jour}</span>
                </div>
            `;
        });
        chartContainer.innerHTML = html;
    }

    function closeBannerStatsModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
        content.innerHTML = `
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                    <p class="text-[10px] text-blue-600 font-semibold uppercase mb-1">Affichages</p>
                    <p id="statsVues" class="text-2xl font-bold text-[#0F172A]">0</p>
                    <p class="text-[10px] text-emerald-600 mt-1"><i class="fas fa-arrow-up text-[8px]"></i> <span id="statsVuesEvolution">0%</span></p>
                </div>
                <div class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                    <p class="text-[10px] text-purple-600 font-semibold uppercase mb-1">Clics</p>
                    <p id="statsClics" class="text-2xl font-bold text-[#0F172A]">0</p>
                    <p class="text-[10px] text-emerald-600 mt-1"><i class="fas fa-arrow-up text-[8px]"></i> <span id="statsClicsEvolution">0%</span></p>
                </div>
                <div class="p-4 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl border border-emerald-100">
                    <p class="text-[10px] text-emerald-600 font-semibold uppercase mb-1">CTR</p>
                    <p id="statsCtr" class="text-2xl font-bold text-[#0F172A]">0%</p>
                    <p class="text-[10px] text-gray-400 mt-1">Taux de clic</p>
                </div>
                <div class="p-4 bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl border border-amber-100">
                    <p class="text-[10px] text-amber-600 font-semibold uppercase mb-1">Jours actifs</p>
                    <p id="statsJoursActifs" class="text-2xl font-bold text-[#0F172A]">0</p>
                    <p class="text-[10px] text-gray-400 mt-1">Depuis activation</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100">
                <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                    <i class="fas fa-chart-bar text-[#0EA486]"></i> Évolution sur 7 jours
                </h5>
                <div id="statsChart" class="flex items-end justify-between gap-2 h-40">
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <div class="w-full bg-blue-200 rounded-t-lg" style="height: 10%;"></div>
                        <div class="w-full bg-purple-200 rounded-t-lg" style="height: 5%;"></div>
                        <span class="text-[10px] text-gray-400">Lun</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <div class="w-full bg-blue-200 rounded-t-lg" style="height: 10%;"></div>
                        <div class="w-full bg-purple-200 rounded-t-lg" style="height: 5%;"></div>
                        <span class="text-[10px] text-gray-400">Mar</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <div class="w-full bg-blue-200 rounded-t-lg" style="height: 10%;"></div>
                        <div class="w-full bg-purple-200 rounded-t-lg" style="height: 5%;"></div>
                        <span class="text-[10px] text-gray-400">Mer</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <div class="w-full bg-blue-200 rounded-t-lg" style="height: 10%;"></div>
                        <div class="w-full bg-purple-200 rounded-t-lg" style="height: 5%;"></div>
                        <span class="text-[10px] text-gray-400">Jeu</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <div class="w-full bg-blue-200 rounded-t-lg" style="height: 10%;"></div>
                        <div class="w-full bg-purple-200 rounded-t-lg" style="height: 5%;"></div>
                        <span class="text-[10px] text-gray-400">Ven</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <div class="w-full bg-blue-200 rounded-t-lg" style="height: 10%;"></div>
                        <div class="w-full bg-purple-200 rounded-t-lg" style="height: 5%;"></div>
                        <span class="text-[10px] text-gray-400">Sam</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <div class="w-full bg-[#0EA486] rounded-t-lg" style="height: 10%;"></div>
                        <div class="w-full bg-purple-800 rounded-t-lg" style="height: 5%;"></div>
                        <span class="text-[10px] text-gray-400 font-semibold">Dim</span>
                    </div>
                </div>
                <div class="flex items-center gap-4 mt-3 text-xs">
                    <span class="flex items-center gap-2"><span class="w-3 h-3 bg-blue-500 rounded"></span> Affichages</span>
                    <span class="flex items-center gap-2"><span class="w-3 h-3 bg-purple-500 rounded"></span> Clics</span>
                </div>
            </div>
        `;
    }

    openBtns.forEach(btn => btn.addEventListener('click', openBannerStatsModal));
    closeBtns.forEach(btn => btn.addEventListener('click', closeBannerStatsModal));
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeBannerStatsModal();
    });
})();

// ============================================
// MODAL TOGGLE BANNIÈRE
// ============================================
(function() {
    const modal = document.getElementById('toggleBannerModal');
    const openBtns = document.querySelectorAll('.toggleBannerBtn');
    const closeBtns = document.querySelectorAll('.closeToggleBannerBtn');
    const confirmBtn = document.getElementById('confirmToggleBannerBtn');
    const title = document.getElementById('toggleBannerTitle');
    const infoText = document.getElementById('toggleBannerInfoText');
    const info = document.getElementById('toggleBannerInfo');
    const nameSpan = document.getElementById('toggleBannerName');

    let currentBannerId = null;
    let currentAction = null;

    function openToggleBannerModal() {
        const btn = this;
        currentBannerId = btn.getAttribute('data-id');
        const currentStatut = btn.getAttribute('data-statut');
        const nom = btn.getAttribute('data-nom') || 'Bannière';

        nameSpan.textContent = nom;

        if (currentStatut === 'active') {
            currentAction = 'deactivate';
            title.innerHTML = '<i class="fas fa-toggle-off text-gray-500"></i> Désactiver la bannière';
            infoText.textContent = 'La bannière sera masquée du site.';
            info.className = 'bg-yellow-50 rounded-xl p-4 border border-yellow-100';
            confirmBtn.className = 'px-5 py-2.5 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition';
            confirmBtn.innerHTML = '<i class="fas fa-pause"></i> Désactiver';
        } else {
            currentAction = 'activate';
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

    function closeToggleBannerModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
        currentBannerId = null;
        currentAction = null;
    }

    openBtns.forEach(btn => btn.addEventListener('click', openToggleBannerModal));
    closeBtns.forEach(btn => btn.addEventListener('click', closeToggleBannerModal));
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeToggleBannerModal();
    });

    confirmBtn.addEventListener('click', function() {
        if (!currentBannerId) {
            showToast('Erreur', 'ID manquant', 'error');
            return;
        }

        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Chargement...';

        fetch('api.php?url=banniere_toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + currentBannerId + '&statut=' + (currentAction === 'activate' ? 'active' : 'inactive')
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeToggleBannerModal();
                showToast('Succès', data.message || 'Statut mis à jour', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('Erreur', data.error || 'Action impossible', 'error');
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = currentAction === 'activate' ? 'Activer' : 'Désactiver';
            }
        })
        .catch(error => {
            console.error(error);
            showToast('Erreur', 'Erreur serveur', 'error');
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = currentAction === 'activate' ? 'Activer' : 'Désactiver';
        });
    });
})();

// ============================================
// MODAL SUPPRESSION BANNIÈRE
// ============================================
(function() {
    const modal = document.getElementById('deleteBannerModal');
    const openBtns = document.querySelectorAll('.openDeleteBannerBtn');
    const closeBtns = document.querySelectorAll('.closeDeleteBannerBtn');
    const input = document.getElementById('deleteBannerConfirmInput');
    const confirmBtn = document.getElementById('confirmDeleteBannerBtn');
    const nameSpan = document.getElementById('deleteBannerName');

    let currentBannerId = null;

    function openDeleteBannerModal() {
        const btn = this;
        currentBannerId = btn.getAttribute('data-id');
        const nom = btn.getAttribute('data-nom') || 'Bannière';

        nameSpan.textContent = nom;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        input.value = '';
        confirmBtn.disabled = true;
        confirmBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }

    function closeDeleteBannerModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
        currentBannerId = null;
    }

    openBtns.forEach(btn => btn.addEventListener('click', openDeleteBannerModal));
    closeBtns.forEach(btn => btn.addEventListener('click', closeDeleteBannerModal));
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeDeleteBannerModal();
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
            if (!currentBannerId) {
                showToast('Erreur', 'ID de bannière manquant', 'error');
                return;
            }

            confirmBtn.disabled = true;
            confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Suppression...';

            fetch('api.php?url=banniere_delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + currentBannerId
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    closeDeleteBannerModal();
                    showToast('Succès', data.message || 'Bannière supprimée avec succès', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast('Erreur', data.error || 'Impossible de supprimer la bannière', 'error');
                    confirmBtn.disabled = false;
                    confirmBtn.innerHTML = '<i class="fas fa-trash"></i> Supprimer définitivement';
                    confirmBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Erreur', 'Impossible de supprimer la bannière', 'error');
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = '<i class="fas fa-trash"></i> Supprimer définitivement';
                confirmBtn.classList.add('opacity-50', 'cursor-not-allowed');
            });
        }
    });
})();

// ============================================
// DRAG & DROP BANNIÈRES
// ============================================
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

// ============================================
// MODAL FORMULAIRE CODE PROMO
// ============================================
(function() {
    const modal = document.getElementById('promoFormModal');
    const openBtns = document.querySelectorAll('.openPromoFormBtn, #openPromoFormBtn');
    const closeBtns = document.querySelectorAll('.closePromoFormBtn');
    const title = document.getElementById('promoFormTitle');

    function openPromoFormModal(isEdit = false) {
        title.textContent = isEdit ? 'Modifier le code promo' : 'Nouveau code promo';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    function closePromoFormModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    openBtns.forEach(btn => btn.addEventListener('click', function() {
        const isEdit = this.classList.contains('openPromoFormBtn');
        openPromoFormModal(isEdit);
    }));
    closeBtns.forEach(btn => btn.addEventListener('click', closePromoFormModal));
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closePromoFormModal();
    });

    document.getElementById('generatePromoCodeBtn').addEventListener('click', function() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let code = 'PROMO-';
        for (let i = 0; i < 8; i++) {
            code += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('promoCode').value = code;
    });

    modal.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        closePromoFormModal();
        showToast('Succès', 'Code promo enregistré avec succès', 'success');
    });
})();

// ============================================
// MODAL HISTORIQUE CODE PROMO
// ============================================
setupModal('promoHistoryModal', '.openPromoHistoryBtn', '.closePromoHistoryBtn');

// ============================================
// MODAL TOGGLE CODE PROMO
// ============================================
(function() {
    const modal = document.getElementById('togglePromoModal');
    const openBtns = document.querySelectorAll('.togglePromoBtn');
    const closeBtns = document.querySelectorAll('.closeTogglePromoBtn');
    const confirmBtn = document.getElementById('confirmTogglePromoBtn');
    const title = document.getElementById('togglePromoTitle');
    const infoText = document.getElementById('togglePromoInfoText');
    const info = document.getElementById('togglePromoInfo');

    function openTogglePromoModal() {
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
    function closeTogglePromoModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    openBtns.forEach(btn => btn.addEventListener('click', openTogglePromoModal));
    closeBtns.forEach(btn => btn.addEventListener('click', closeTogglePromoModal));
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeTogglePromoModal();
    });

    confirmBtn.addEventListener('click', function() {
        closeTogglePromoModal();
        showToast('Statut modifié', 'Le statut du code promo a été mis à jour', 'success');
    });
})();

// ============================================
// MODAL SUPPRESSION CODE PROMO
// ============================================
(function() {
    const modal = document.getElementById('deletePromoModal');
    const openBtns = document.querySelectorAll('.openDeletePromoBtn');
    const closeBtns = document.querySelectorAll('.closeDeletePromoBtn');
    const input = document.getElementById('deletePromoConfirmInput');
    const confirmBtn = document.getElementById('confirmDeletePromoBtn');

    function openDeletePromoModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        input.value = '';
        confirmBtn.disabled = true;
        confirmBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }
    function closeDeletePromoModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    openBtns.forEach(btn => btn.addEventListener('click', openDeletePromoModal));
    closeBtns.forEach(btn => btn.addEventListener('click', closeDeletePromoModal));
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeDeletePromoModal();
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
            closeDeletePromoModal();
            showToast('Supprimé', 'Code promo supprimé avec succès', 'success');
        }
    });
})();

// ============================================
// ESC POUR FERMER TOUS LES MODALS
// ============================================
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