<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NDIGITMARKET Admin · Statistiques & Rapports</title>
    <link rel="icon" type="image/png" href="/back-end/public/assets/images/favi.png">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../public/assets/CSS/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    
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
                    <h2 class="text-xl md:text-2xl font-bold text-[#0F172A]">Statistiques & Rapports</h2>
                    <p class="text-xs text-gray-400 hidden sm:block">Analytics, performances et exports de données</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <!-- <button class="relative w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 transition flex items-center justify-center">
                    <i class="fas fa-bell text-gray-600"></i>
                    <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                </button> -->
                <div class="w-9 h-9 rounded-full bg-[#0EA486] text-white flex items-center justify-center font-semibold text-sm">
                    A
                </div>
            </div>
        </header>

        <!--  STATISTIQUES PLATEFORME -->
        <section class="mb-8">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-chart-line text-[#0EA486]"></i> · Statistiques plateforme
                </h3>
                <div class="flex gap-2">
                    <select id="periodSelect" class="px-3 py-2 rounded-lg bg-white border border-gray-100 text-xs font-medium text-gray-600 focus:outline-none focus:border-[#0EA486]">
                        <option value="week">Cette semaine</option>
                        <option value="month" selected>Ce mois</option>
                        <option value="quarter">Ce trimestre</option>
                        <option value="year">Cette année</option>
                    </select>
                    <button class="px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2">
                        <i class="fas fa-sync-alt"></i> Actualiser
                    </button>
                </div>
            </div>

           <!-- KPI Cards avec données PHP -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
    <!-- Inscriptions -->
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                <i class="fas fa-users"></i>
            </div>
            <span class="text-[10px] font-semibold <?= ($stats['evolution_inscriptions'] ?? 0) >= 0 ? 'text-emerald-600 bg-emerald-50' : 'text-red-600 bg-red-50' ?> px-2 py-1 rounded-full">
                <i class="fas fa-<?= ($stats['evolution_inscriptions'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' ?> text-[8px]"></i> 
                <?= abs($stats['evolution_inscriptions'] ?? 0) ?>%
            </span>
        </div>
        <p class="text-2xl font-bold text-[#0F172A]">
            <?= number_format($stats['inscriptions'] ?? 0, 0, ',', ' ') ?>
        </p>
        <p class="text-xs text-gray-400 mt-1">Inscriptions totales</p>
    </div>

    <!-- Ventes -->
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <span class="text-[10px] font-semibold <?= ($stats['evolution_ventes'] ?? 0) >= 0 ? 'text-emerald-600 bg-emerald-50' : 'text-red-600 bg-red-50' ?> px-2 py-1 rounded-full">
                <i class="fas fa-<?= ($stats['evolution_ventes'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' ?> text-[8px]"></i> 
                <?= abs($stats['evolution_ventes'] ?? 0) ?>%
            </span>
        </div>
        <p class="text-2xl font-bold text-[#0F172A]">
            <?= number_format($stats['ventes'] ?? 0, 0, ',', ' ') ?>
        </p>
        <p class="text-xs text-gray-400 mt-1">Ventes totales</p>
    </div>

    <!-- Chiffre d'affaires -->
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600">
                <i class="fas fa-wallet"></i>
            </div>
            <span class="text-[10px] font-semibold <?= ($stats['evolution_ca'] ?? 0) >= 0 ? 'text-emerald-600 bg-emerald-50' : 'text-red-600 bg-red-50' ?> px-2 py-1 rounded-full">
                <i class="fas fa-<?= ($stats['evolution_ca'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' ?> text-[8px]"></i> 
                <?= abs($stats['evolution_ca'] ?? 0) ?>%
            </span>
        </div>
        <p class="text-2xl font-bold text-[#0F172A]">
            <?= number_format($stats['ca'] ?? 0, 0, ',', ' ') ?> FCFA
        </p>
        <p class="text-xs text-gray-400 mt-1">Chiffre d'affaires</p>
    </div>

    <!-- Taux de conversion -->
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600">
                <i class="fas fa-percentage"></i>
            </div>
            <span class="text-[10px] font-semibold <?= ($stats['taux_conversion'] ?? 0) >= 50 ? 'text-emerald-600 bg-emerald-50' : 'text-red-600 bg-red-50' ?> px-2 py-1 rounded-full">
                <i class="fas fa-<?= ($stats['taux_conversion'] ?? 0) >= 50 ? 'arrow-up' : 'arrow-down' ?> text-[8px]"></i> 
                <?= number_format($stats['taux_conversion'] ?? 0, 1) ?>%
            </span>
        </div>
        <p class="text-2xl font-bold text-[#0F172A]">
            <?= number_format($stats['taux_conversion'] ?? 0, 1) ?>%
        </p>
        <p class="text-xs text-gray-400 mt-1">Taux de conversion</p>
    </div>
</div>
            <!-- Graphiques principaux -->
           <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
    <!-- Évolution des inscriptions -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h4 class="text-sm font-bold text-[#0F172A] flex items-center gap-2">
                    <i class="fas fa-user-plus text-blue-500"></i> Évolution des inscriptions
                </h4>
                <p class="text-[11px] text-gray-400 mt-0.5">Nouveaux utilisateurs par période</p>
            </div>
                        <!-- Évolution des inscriptions -->
            <div class="flex gap-1">
                <a href="#" class="chart-period-link px-2 py-1 text-[10px] font-semibold <?= $chartPeriod === 'week' ? 'text-[#0EA486] bg-[#0EA486]/10' : 'text-gray-500' ?> rounded" data-period="week">
                    Sem
                </a>
                <a href="#" class="chart-period-link px-2 py-1 text-[10px] font-medium <?= $chartPeriod === 'month' ? 'text-[#0EA486] bg-[#0EA486]/10' : 'text-gray-500' ?> hover:bg-gray-100 rounded" data-period="month">
                    Mois
                </a>
            </div>
        </div>
        <div class="h-64">
            <canvas id="inscriptionsChart"></canvas>
        </div>
    </div>

    <!-- Évolution des ventes -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h4 class="text-sm font-bold text-[#0F172A] flex items-center gap-2">
                    <i class="fas fa-chart-area text-emerald-500"></i> Évolution des ventes
                </h4>
                <p class="text-[11px] text-gray-400 mt-0.5">Nombre de ventes et CA</p>
            </div>
                        <!-- Évolution des ventes -->
            <div class="flex gap-1">
                <a href="#" class="chart-period-link px-2 py-1 text-[10px] <?= $chartPeriod === 'week' ? 'font-semibold text-[#0EA486] bg-[#0EA486]/10' : 'font-medium text-gray-500' ?> rounded" data-period="week">
                    Sem
                </a>
                <a href="#" class="chart-period-link px-2 py-1 text-[10px] <?= $chartPeriod === 'month' ? 'font-semibold text-[#0EA486] bg-[#0EA486]/10' : 'font-medium text-gray-500' ?> hover:bg-gray-100 rounded" data-period="month">
                    Mois
                </a>
            </div>
        </div>
        <div class="h-64">
            <canvas id="ventesChart"></canvas>
        </div>
        <div class="flex items-center gap-4 mt-3 text-xs">
            <span class="flex items-center gap-2"><span class="w-3 h-3 bg-[#0EA486] rounded"></span> CA (FCFA)</span>
            <span class="flex items-center gap-2"><span class="w-3 h-3 bg-blue-500 rounded"></span> Ventes</span>
        </div>
    </div>
</div>

            <!-- Top produits et catégories -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
    <!-- Produits les plus vendus -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-sm font-bold text-[#0F172A] flex items-center gap-2">
                <i class="fas fa-trophy text-amber-500"></i> Top produits vendus
            </h4>
            <!-- <button class="text-xs text-[#0EA486] hover:underline font-medium">Voir tout</button> -->
        </div>
        <div class="space-y-3">
            <?php if (!empty($topProducts)): ?>
                <?php foreach ($topProducts as $index => $product): ?>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 <?= $index === 0 ? 'bg-amber-100 text-amber-600' : ($index === 1 ? 'bg-gray-100 text-gray-600' : ($index === 2 ? 'bg-orange-100 text-orange-600' : 'bg-gray-50 text-gray-500')) ?> rounded-lg flex items-center justify-center font-bold text-xs">
                            <?= $index + 1 ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-[#0F172A] truncate"><?= htmlspecialchars($product['nom'] ?? '-') ?></p>
                            <p class="text-[10px] text-gray-400"><?= htmlspecialchars($product['vendeur'] ?? '-') ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-[#0EA486]"><?= $product['ventes'] ?? 0 ?></p>
                            <p class="text-[10px] text-gray-400">ventes</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-4 text-gray-400">
                    <i class="fas fa-box-open text-2xl opacity-30"></i>
                    <p class="text-xs mt-1">Aucun produit vendu</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Produits les plus vus -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-sm font-bold text-[#0F172A] flex items-center gap-2">
                <i class="fas fa-eye text-blue-500"></i> Top produits vus
            </h4>
            <!-- <button class="text-xs text-[#0EA486] hover:underline font-medium">Voir tout</button> -->
        </div>
        <div class="space-y-3">
            <?php if (!empty($topViewedProducts)): ?>
                <?php foreach ($topViewedProducts as $index => $product): ?>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 <?= $index === 0 ? 'bg-blue-100 text-blue-600' : ($index === 1 ? 'bg-blue-50 text-blue-500' : ($index === 2 ? 'bg-blue-50 text-blue-500' : 'bg-gray-50 text-gray-500')) ?> rounded-lg flex items-center justify-center font-bold text-xs">
                            <?= $index + 1 ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-[#0F172A] truncate"><?= htmlspecialchars($product['nom'] ?? '-') ?></p>
                            <p class="text-[10px] text-gray-400"><?= htmlspecialchars($product['vendeur'] ?? '-') ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-blue-600"><?= $product['vues'] ?? 0 ?></p>
                            <p class="text-[10px] text-gray-400">vues</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-4 text-gray-400">
                    <i class="fas fa-eye-slash text-2xl opacity-30"></i>
                    <p class="text-xs mt-1">Aucune vue de produit</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Catégories performantes -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-sm font-bold text-[#0F172A] flex items-center gap-2">
                <i class="fas fa-tags text-purple-500"></i> Top catégories
            </h4>
            <!-- <button class="text-xs text-[#0EA486] hover:underline font-medium">Voir tout</button> -->
        </div>
        <div class="space-y-3">
            <?php if (!empty($topCategories)): ?>
                <?php 
                $maxVentes = max(array_column($topCategories, 'ventes'));
                $maxVentes = $maxVentes > 0 ? $maxVentes : 1;
                ?>
                <?php foreach ($topCategories as $index => $category): ?>
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-[#0F172A]"><?= htmlspecialchars($category['nom'] ?? '-') ?></span>
                            <span class="text-xs font-bold text-[#0EA486]"><?= $category['ventes'] ?? 0 ?> ventes</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                            <div class="bg-purple-500 h-full rounded-full transition-all duration-500" 
                                 style="width: <?= max(5, ($category['ventes'] ?? 0) / $maxVentes * 100) ?>%;"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-4 text-gray-400">
                    <i class="fas fa-tags text-2xl opacity-30"></i>
                    <p class="text-xs mt-1">Aucune catégorie</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

            <!-- Répartition géographique -->
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="text-sm font-bold text-[#0F172A] flex items-center gap-2">
                            <i class="fas fa-globe-africa text-[#0EA486]"></i> Répartition géographique des acheteurs
                        </h4>
                        <p class="text-[11px] text-gray-400 mt-0.5">Distribution par pays</p>
                    </div>
                    <!-- <button class="text-xs text-[#0EA486] hover:underline font-medium">Voir détails</button> -->
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div class="h-64">
                        <canvas id="geoChart"></canvas>
                    </div>
                            <div class="space-y-2">
            <?php 
            $emojiMap = [
                'Sénégal' => '🇸🇳',
                'Côte d\'Ivoire' => '🇨🇮',
                'Cameroun' => '🇨🇲',
                'Mali' => '🇲🇱',
                'Burkina Faso' => '🇧🇫',
                'Guinée' => '🇬🇳',
                'Bénin' => '🇧🇯',
                'Togo' => '🇹🇬',
                'Niger' => '🇳🇪',
                'RDC' => '🇨🇩',
                'Gabon' => '🇬🇦',
                'Congo' => '🇨🇬',
                'Autres' => '🌍'
            ];
            
            // Vérifier si les données existent avec les bonnes clés
            $labels = $geoDistribution['labels'] ?? [];
            $values = $geoDistribution['values'] ?? [];
            $pourcentages = $geoDistribution['pourcentages'] ?? [];
            
            // Si les données sont vides, afficher un message
            if (empty($labels)): ?>
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-globe-africa text-4xl mb-3 block"></i>
                    <p class="text-sm font-medium">Aucune donnée géographique disponible</p>
                    <p class="text-xs mt-1">Les utilisateurs n'ont pas encore renseigné leur pays</p>
                </div>
            <?php else: 
                $totalAcheteurs = array_sum($values);
                $totalAcheteurs = $totalAcheteurs > 0 ? $totalAcheteurs : 1;
                
                foreach ($labels as $index => $nom): 
                    $total = $values[$index] ?? 0;
                    $pourcentage = $pourcentages[$index] ?? 0;
                    $emoji = $emojiMap[$nom] ?? '🌍';
            ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="text-xl"><?= $emoji ?></span>
                        <div>
                            <p class="text-xs font-semibold text-[#0F172A]"><?= htmlspecialchars($nom) ?></p>
                            <p class="text-[10px] text-gray-400"><?= number_format($total, 0, ',', ' ') ?> acheteurs</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-[#0EA486]"><?= number_format($pourcentage, 1) ?>%</p>
                        <div class="w-20 bg-gray-200 rounded-full h-1.5 mt-1">
                            <div class="bg-[#0EA486] h-full rounded-full transition-all duration-500" style="width: <?= min($pourcentage, 100) ?>%;"></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; endif; ?>
        </div>
                </div>
            </div>
        </section>

        

                <!--  RAPPORTS EXPORTABLES -->
        <section class="mb-8">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-file-export text-[#0EA486]"></i> · Rapports exportables
                </h3>
            </div>

            <!-- Tabs rapports -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="border-b border-gray-100 px-4">
                    <nav class="flex gap-1 overflow-x-auto">
                        <button class="report-tab-btn active px-4 py-3 text-sm font-semibold text-[#0EA486] border-b-2 border-[#0EA486] whitespace-nowrap" data-tab="sales">
                            <i class="fas fa-shopping-bag mr-2"></i>Rapport de ventes
                        </button>
                        <button class="report-tab-btn px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap" data-tab="financial">
                            <i class="fas fa-coins mr-2"></i>Rapport financier
                        </button>
                        <button class="report-tab-btn px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap" data-tab="users">
                            <i class="fas fa-users mr-2"></i>Rapport utilisateurs
                        </button>
                        <button class="report-tab-btn px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap" data-tab="vendors">
                            <i class="fas fa-store mr-2"></i>Rapport vendeurs
                        </button>
                    </nav>
                </div>

                <!-- Tab: Rapport de ventes -->
                <div class="report-tab-content p-5" data-tab="sales">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <input type="date" class="px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]" value="<?= date('Y-m-d', strtotime('-30 days')) ?>">
                            <span class="text-xs text-gray-400">à</span>
                            <input type="date" class="px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]" value="<?= date('Y-m-d') ?>">
                            <select class="px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                                <option value="all">Toutes les catégories</option>
                                <?php foreach ($categoriesList as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom_categorie']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select class="px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                                <option value="all">Tous les vendeurs</option>
                                <?php foreach ($vendorsList as $v): ?>
                                    <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button class="generateReportBtn px-3 py-2 rounded-lg bg-[#0EA486] hover:bg-[#0c8f75] text-white text-xs font-semibold flex items-center gap-2">
                                <i class="fas fa-sync-alt"></i> Générer
                            </button>
                            <button class="exportReportBtn px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2" data-format="csv">
                                <i class="fas fa-file-csv"></i> CSV
                            </button>
                            <button class="exportReportBtn px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2" data-format="excel">
                                <i class="fas fa-file-excel"></i> Excel
                            </button>
                            <button class="exportReportBtn px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2" data-format="pdf">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                        </div>
                    </div>

                    <!-- Résumé -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                        <div class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                            <p class="text-[10px] text-blue-600 font-semibold uppercase mb-1">Total ventes</p>
                            <p class="text-xl font-bold text-[#0F172A]"><?= number_format($salesSummary['total_ventes'] ?? 0) ?></p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl border border-emerald-100">
                            <p class="text-[10px] text-emerald-600 font-semibold uppercase mb-1">CA total</p>
                            <p class="text-xl font-bold text-[#0F172A]"><?= number_format($salesSummary['ca_total'] ?? 0) ?> FCFA</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                            <p class="text-[10px] text-purple-600 font-semibold uppercase mb-1">Panier moyen</p>
                            <p class="text-xl font-bold text-[#0F172A]"><?= number_format($salesSummary['panier_moyen'] ?? 0) ?> FCFA</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl border border-amber-100">
                            <p class="text-[10px] text-amber-600 font-semibold uppercase mb-1">Meilleur jour</p>
                            <p class="text-xl font-bold text-[#0F172A]"><?= $salesSummary['meilleur_jour'] ?? '-' ?></p>
                        </div>
                    </div>

                    <!-- Tableau -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">Produit</th>
                                    <th class="px-4 py-3">Vendeur</th>
                                    <th class="px-4 py-3">Catégorie</th>
                                    <th class="px-4 py-3">Prix</th>
                                    <th class="px-4 py-3">Commission</th>
                                    <th class="px-4 py-3">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php if (!empty($salesData)): ?>
                                    <?php foreach ($salesData as $row): ?>
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="px-4 py-3 text-xs text-gray-500"><?= $row['date'] ?? '-' ?></td>
                                            <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]"><?= htmlspecialchars($row['produit'] ?? '-') ?></td>
                                            <td class="px-4 py-3 text-xs text-gray-600"><?= htmlspecialchars($row['vendeur'] ?? '-') ?></td>
                                            <td class="px-4 py-3">
                                                <span class="text-[10px] font-semibold text-indigo-700 bg-indigo-100 px-2 py-1 rounded-full"><?= htmlspecialchars($row['categorie'] ?? '-') ?></span>
                                            </td>
                                            <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]"><?= number_format($row['prix'] ?? 0) ?> FCFA</td>
                                            <td class="px-4 py-3 text-xs font-semibold text-[#0EA486]"><?= number_format($row['commission'] ?? 0) ?> FCFA</td>
                                            <td class="px-4 py-3">
                                                <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-1 rounded-full"><?= $row['statut'] ?? '-' ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="px-4 py-8 text-center text-gray-400 text-sm">
                                            <i class="fas fa-inbox text-3xl block mb-2"></i>
                                            Aucune donnée de vente
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab: Rapport financier -->
                <div class="report-tab-content hidden p-5" data-tab="financial">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <input type="date" class="px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]" value="<?= date('Y-m-d', strtotime('-30 days')) ?>">
                            <span class="text-xs text-gray-400">à</span>
                            <input type="date" class="px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="flex gap-2">
                            <button class="generateReportBtn px-3 py-2 rounded-lg bg-[#0EA486] hover:bg-[#0c8f75] text-white text-xs font-semibold flex items-center gap-2">
                                <i class="fas fa-sync-alt"></i> Générer
                            </button>
                            <button class="exportReportBtn px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2" data-format="csv">
                                <i class="fas fa-file-csv"></i> CSV
                            </button>
                            <button class="exportReportBtn px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2" data-format="excel">
                                <i class="fas fa-file-excel"></i> Excel
                            </button>
                            <button class="exportReportBtn px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2" data-format="pdf">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                        <div class="p-4 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl border border-emerald-100">
                            <p class="text-[10px] text-emerald-600 font-semibold uppercase mb-1">CA total</p>
                            <p class="text-xl font-bold text-[#0F172A]"><?= number_format($financialSummary['ca_total'] ?? 0) ?> FCFA</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                            <p class="text-[10px] text-blue-600 font-semibold uppercase mb-1">Commission plateforme</p>
                            <p class="text-xl font-bold text-[#0F172A]"><?= number_format($financialSummary['commission_plateforme'] ?? 0) ?> FCFA</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                            <p class="text-[10px] text-purple-600 font-semibold uppercase mb-1">Commission vendeurs</p>
                            <p class="text-xl font-bold text-[#0F172A]"><?= number_format($financialSummary['commission_vendeurs'] ?? 0) ?> FCFA</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl border border-orange-100">
                            <p class="text-[10px] text-orange-600 font-semibold uppercase mb-1">Versements effectués</p>
                            <p class="text-xl font-bold text-[#0F172A]"><?= number_format($financialSummary['versements'] ?? 0) ?> FCFA</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th class="px-4 py-3">Mois</th>
                                    <th class="px-4 py-3">CA</th>
                                    <th class="px-4 py-3">Commission plateforme</th>
                                    <th class="px-4 py-3">Commission vendeurs</th>
                                    <th class="px-4 py-3">Versements</th>
                                    <th class="px-4 py-3">Solde dû</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php if (!empty($financialData)): ?>
                                    <?php foreach ($financialData as $row): ?>
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]"><?= $row['mois'] ?? '-' ?></td>
                                            <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]"><?= number_format($row['ca'] ?? 0) ?> FCFA</td>
                                            <td class="px-4 py-3 text-xs font-semibold text-[#0EA486]"><?= number_format($row['commission_plateforme'] ?? 0) ?> FCFA</td>
                                            <td class="px-4 py-3 text-xs font-semibold text-purple-600"><?= number_format($row['commission_vendeurs'] ?? 0) ?> FCFA</td>
                                            <td class="px-4 py-3 text-xs font-semibold text-blue-600"><?= number_format($row['versements'] ?? 0) ?> FCFA</td>
                                            <td class="px-4 py-3 text-xs font-semibold text-orange-600"><?= number_format($row['solde_du'] ?? 0) ?> FCFA</td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-400 text-sm">
                                            <i class="fas fa-inbox text-3xl block mb-2"></i>
                                            Aucune donnée financière
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab: Rapport utilisateurs -->
                <div class="report-tab-content hidden p-5" data-tab="users">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <input type="date" class="px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]" value="<?= date('Y-m-d', strtotime('-30 days')) ?>">
                            <span class="text-xs text-gray-400">à</span>
                            <input type="date" class="px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="flex gap-2">
                            <button class="generateReportBtn px-3 py-2 rounded-lg bg-[#0EA486] hover:bg-[#0c8f75] text-white text-xs font-semibold flex items-center gap-2">
                                <i class="fas fa-sync-alt"></i> Générer
                            </button>
                            <button class="exportReportBtn px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2" data-format="csv">
                                <i class="fas fa-file-csv"></i> CSV
                            </button>
                            <button class="exportReportBtn px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2" data-format="excel">
                                <i class="fas fa-file-excel"></i> Excel
                            </button>
                            <button class="exportReportBtn px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2" data-format="pdf">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                        <div class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                            <p class="text-[10px] text-blue-600 font-semibold uppercase mb-1">Nouvelles inscriptions</p>
                            <p class="text-xl font-bold text-[#0F172A]"><?= number_format($usersSummary['total_inscriptions'] ?? 0) ?></p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl border border-emerald-100">
                            <p class="text-[10px] text-emerald-600 font-semibold uppercase mb-1">Utilisateurs actifs</p>
                            <p class="text-xl font-bold text-[#0F172A]"><?= number_format($usersSummary['total_connexions'] ?? 0) ?></p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                            <p class="text-[10px] text-purple-600 font-semibold uppercase mb-1">Acheteurs</p>
                            <p class="text-xl font-bold text-[#0F172A]"><?= number_format($usersSummary['total_acheteurs'] ?? 0) ?></p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl border border-amber-100">
                            <p class="text-[10px] text-amber-600 font-semibold uppercase mb-1">Taux rétention</p>
                            <p class="text-xl font-bold text-[#0F172A]"><?= number_format($usersSummary['taux_retention'] ?? 0, 1) ?>%</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">Inscriptions</th>
                                    <th class="px-4 py-3">Connexions</th>
                                    <th class="px-4 py-3">Acheteurs actifs</th>
                                    <th class="px-4 py-3">Désabonnements</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php if (!empty($usersData)): ?>
                                    <?php foreach ($usersData as $row): ?>
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]"><?= $row['date'] ?? '-' ?></td>
                                            <td class="px-4 py-3 text-xs font-semibold text-blue-600"><?= $row['inscriptions'] ?? 0 ?></td>
                                            <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]"><?= $row['connexions'] ?? 0 ?></td>
                                            <td class="px-4 py-3 text-xs font-semibold text-emerald-600"><?= $row['acheteurs_actifs'] ?? 0 ?></td>
                                            <td class="px-4 py-3 text-xs font-semibold text-red-600"><?= $row['desabonnements'] ?? 0 ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-400 text-sm">
                                            <i class="fas fa-inbox text-3xl block mb-2"></i>
                                            Aucune donnée utilisateur
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab: Rapport vendeurs -->
                <div class="report-tab-content hidden p-5" data-tab="vendors">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <input type="date" class="px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]" value="<?= date('Y-m-d', strtotime('-30 days')) ?>">
                            <span class="text-xs text-gray-400">à</span>
                            <input type="date" class="px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="flex gap-2">
                            <button class="generateReportBtn px-3 py-2 rounded-lg bg-[#0EA486] hover:bg-[#0c8f75] text-white text-xs font-semibold flex items-center gap-2">
                                <i class="fas fa-sync-alt"></i> Générer
                            </button>
                            <button class="exportReportBtn px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2" data-format="csv">
                                <i class="fas fa-file-csv"></i> CSV
                            </button>
                            <button class="exportReportBtn px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2" data-format="excel">
                                <i class="fas fa-file-excel"></i> Excel
                            </button>
                            <button class="exportReportBtn px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2" data-format="pdf">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                        <div class="p-4 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl border border-emerald-100">
                            <p class="text-[10px] text-emerald-600 font-semibold uppercase mb-1">Vendeurs actifs</p>
                            <p class="text-xl font-bold text-[#0F172A]"><?= number_format($vendorsSummary['total_vendeurs'] ?? 0) ?></p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                            <p class="text-[10px] text-blue-600 font-semibold uppercase mb-1">Nouveaux vendeurs</p>
                            <p class="text-xl font-bold text-[#0F172A]"><?= number_format($vendorsSummary['total_produits'] ?? 0) ?></p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                            <p class="text-[10px] text-purple-600 font-semibold uppercase mb-1">Produits publiés</p>
                            <p class="text-xl font-bold text-[#0F172A]"><?= number_format($vendorsSummary['total_ventes'] ?? 0) ?></p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl border border-amber-100">
                            <p class="text-[10px] text-amber-600 font-semibold uppercase mb-1">Revenu moyen</p>
                            <p class="text-xl font-bold text-[#0F172A]"><?= number_format($vendorsSummary['revenu_moyen'] ?? 0) ?> FCFA</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th class="px-4 py-3">Vendeur</th>
                                    <th class="px-4 py-3">Produits</th>
                                    <th class="px-4 py-3">Ventes</th>
                                    <th class="px-4 py-3">CA</th>
                                    <th class="px-4 py-3">Commission</th>
                                    <th class="px-4 py-3">Note moyenne</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php if (!empty($vendorsData)): ?>
                                    <?php foreach ($vendorsData as $row): ?>
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-lg flex items-center justify-center text-emerald-500">
                                                        <i class="fas fa-store text-xs"></i>
                                                    </div>
                                                    <span class="text-xs font-semibold text-[#0F172A]"><?= htmlspecialchars($row['nom'] ?? '-') ?></span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]"><?= $row['produits'] ?? 0 ?></td>
                                            <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]"><?= $row['ventes'] ?? 0 ?></td>
                                            <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]"><?= number_format($row['ca'] ?? 0) ?> FCFA</td>
                                            <td class="px-4 py-3 text-xs font-semibold text-[#0EA486]"><?= number_format($row['commission'] ?? 0) ?> FCFA</td>
                                            <td class="px-4 py-3">
                                                <span class="text-xs font-semibold text-amber-600">
                                                    <i class="fas fa-star text-[10px]"></i> <?= $row['note_moyenne'] ? number_format($row['note_moyenne'], 1) : '-' ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-400 text-sm">
                                            <i class="fas fa-inbox text-3xl block mb-2"></i>
                                            Aucun vendeur
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <footer class="mt-8 text-center text-xs text-gray-400 border-t border-gray-100 pt-6">
            &copy; 2026 NDIGITMARKET · Administration
        </footer>
    </main>

    <!-- MODAL : EXPORT RAPPORT -->
    <div id="exportModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-download text-[#0EA486]"></i> Exporter le rapport
                    </h3>
                    <p class="text-xs text-gray-400">Choisir le format d'export</p>
                </div>
                <button class="closeExportBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
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
                    <div class="flex gap-2">
                        <input type="date" class="flex-1 px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                        <input type="date" class="flex-1 px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-2 block">Format d'export</label>
                    <div class="grid grid-cols-3 gap-3">
                        <button class="export-format-btn p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-[#0EA486] transition text-center group" data-format="csv">
                            <i class="fas fa-file-csv text-2xl text-emerald-500 mb-2 group-hover:scale-110 transition"></i>
                            <p class="text-sm font-semibold text-[#0F172A]">CSV</p>
                            <p class="text-[10px] text-gray-400 mt-1">Excel compatible</p>
                        </button>
                        <button class="export-format-btn p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-[#0EA486] transition text-center group" data-format="excel">
                            <i class="fas fa-file-excel text-2xl text-green-500 mb-2 group-hover:scale-110 transition"></i>
                            <p class="text-sm font-semibold text-[#0F172A]">Excel</p>
                            <p class="text-[10px] text-gray-400 mt-1">.xlsx</p>
                        </button>
                        <button class="export-format-btn p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-[#0EA486] transition text-center group" data-format="pdf">
                            <i class="fas fa-file-pdf text-2xl text-red-500 mb-2 group-hover:scale-110 transition"></i>
                            <p class="text-sm font-semibold text-[#0F172A]">PDF</p>
                            <p class="text-[10px] text-gray-400 mt-1">Rapport complet</p>
                        </button>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" checked class="w-4 h-4 rounded border-gray-300 text-[#0EA486]">
                        <span>Inclure les graphiques</span>
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" checked class="w-4 h-4 rounded border-gray-300 text-[#0EA486]">
                        <span>Inclure le résumé exécutif</span>
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-[#0EA486]">
                        <span>Envoyer par email</span>
                    </label>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                <button class="closeExportBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                    Annuler
                </button>
                <button class="confirmExportBtn px-5 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                    <i class="fas fa-download"></i> Télécharger
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL : DÉTAIL RAPPORT -->
    <div id="reportDetailModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-5xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#0EA486]/5 to-transparent">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-chart-bar text-[#0EA486]"></i> Détail du rapport
                    </h3>
                    <p class="text-xs text-gray-400">Analyse détaillée</p>
                </div>
                <button class="closeReportDetailBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="overflow-y-auto p-6 space-y-5">
                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-5 border border-indigo-100">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div>
                            <h4 class="text-lg font-bold text-[#0F172A]">Rapport de ventes</h4>
                            <p class="text-xs text-gray-500 mt-1">Période du ... au ...</p>
                        </div>
                        <div class="flex gap-2">
                            <button class="px-3 py-2 rounded-lg bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 text-xs font-medium flex items-center gap-2">
                                <i class="fas fa-file-csv"></i> CSV
                            </button>
                            <button class="px-3 py-2 rounded-lg bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 text-xs font-medium flex items-center gap-2">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="p-4 bg-white rounded-xl border border-gray-100">
                        <p class="text-[10px] text-gray-400 uppercase font-semibold mb-1">Total ventes</p>
                        <p class="text-xl font-bold text-[#0F172A]">-</p>
                    </div>
                    <div class="p-4 bg-white rounded-xl border border-gray-100">
                        <p class="text-[10px] text-gray-400 uppercase font-semibold mb-1">CA total</p>
                        <p class="text-xl font-bold text-[#0F172A]">- FCFA</p>
                    </div>
                    <div class="p-4 bg-white rounded-xl border border-gray-100">
                        <p class="text-[10px] text-gray-400 uppercase font-semibold mb-1">Panier moyen</p>
                        <p class="text-xl font-bold text-[#0F172A]">- FCFA</p>
                    </div>
                    <div class="p-4 bg-white rounded-xl border border-gray-100">
                        <p class="text-[10px] text-gray-400 uppercase font-semibold mb-1">Croissance</p>
                        <p class="text-xl font-bold text-emerald-600">+-%</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-chart-line text-[#0EA486]"></i> Évolution des ventes
                    </h5>
                    <div class="h-64">
                        <canvas id="detailChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-list text-[#0EA486]"></i> Détail par produit
                    </h5>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th class="px-4 py-3">Produit</th>
                                    <th class="px-4 py-3">Vendeur</th>
                                    <th class="px-4 py-3">Ventes</th>
                                    <th class="px-4 py-3">CA</th>
                                    <th class="px-4 py-3">Part</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">-</td>
                                    <td class="px-4 py-3 text-xs text-gray-600">-</td>
                                    <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">-</td>
                                    <td class="px-4 py-3 text-xs font-semibold text-[#0EA486]">- FCFA</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <div class="flex-1 bg-gray-100 rounded-full h-1.5 w-16">
                                                <div class="bg-[#0EA486] h-full rounded-full" style="width: 0%;"></div>
                                            </div>
                                            <span class="text-xs font-semibold text-gray-600">-%</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
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

        
        

        

        // Chart toggle buttons
        (function() {
            const toggleBtns = document.querySelectorAll('.chart-toggle-btn');
            toggleBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const chart = this.getAttribute('data-chart');
                    const period = this.getAttribute('data-period');
                    
                    // Update active state
                    document.querySelectorAll(`.chart-toggle-btn[data-chart="${chart}"]`).forEach(b => {
                        b.classList.remove('active', 'text-[#0EA486]', 'bg-[#0EA486]/10', 'font-semibold');
                        b.classList.add('text-gray-500', 'font-medium');
                    });
                    this.classList.add('active', 'text-[#0EA486]', 'bg-[#0EA486]/10', 'font-semibold');
                    this.classList.remove('text-gray-500', 'font-medium');
                    
                    showToast('Graphique mis à jour', `Affichage par ${period === 'week' ? 'semaine' : 'mois'}`, 'info');
                });
            });
        })();

        // Modal export
        (function() {
            const modal = document.getElementById('exportModal');
            const openBtns = document.querySelectorAll('.exportReportBtn');
            const closeBtns = document.querySelectorAll('.closeExportBtn');
            const confirmBtn = document.querySelector('.confirmExportBtn');
            const formatBtns = document.querySelectorAll('.export-format-btn');

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

            formatBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    formatBtns.forEach(b => b.classList.remove('border-[#0EA486]', 'bg-[#0EA486]/5'));
                    this.classList.add('border-[#0EA486]', 'bg-[#0EA486]/5');
                });
            });

            confirmBtn.addEventListener('click', function() {
                closeModal();
                showToast('Export en cours', 'Votre rapport est en cours de génération', 'success');
            });
        })();

        // Modal détail rapport
        (function() {
            const modal = document.getElementById('reportDetailModal');
            const openBtns = document.querySelectorAll('.generateReportBtn');
            const closeBtns = document.querySelectorAll('.closeReportDetailBtn');

            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';

                // Initialize detail chart (vide)
                setTimeout(() => {
                    const ctx = document.getElementById('detailChart');
                    if (ctx && !ctx.chartInitialized) {
                        new Chart(ctx.getContext('2d'), {
                            type: 'bar',
                            data: {
                                labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'],
                                datasets: [{
                                    label: 'Ventes',
                                    data: [0, 0, 0, 0],
                                    backgroundColor: '#0EA486',
                                    borderRadius: 8
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                                    x: { grid: { display: false } }
                                }
                            }
                        });
                        ctx.chartInitialized = true;
                    }
                }, 100);
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

        // Period select
        document.getElementById('periodSelect').addEventListener('change', function() {
            showToast('Période mise à jour', `Affichage des données pour : ${this.options[this.selectedIndex].text}`, 'info');
        });

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




// ============================================
// GRAPHIQUES LINEAIRES - VERSION CORRIGÉE
// ============================================

// Données initiales PHP
let chartData = <?= json_encode($chartData) ?>;

// Variables pour les instances
let inscriptionsChart = null;
let ventesChart = null;
let currentPeriod = 'month';
let isLoading = false;

// ============================================
// 1. CRÉATION DES GRAPHIQUES
// ============================================

function createInscriptionsChart(data) {
    const canvas = document.getElementById('inscriptionsChart');
    if (!canvas) return;
    
    if (inscriptionsChart) {
        inscriptionsChart.destroy();
        inscriptionsChart = null;
    }
    
    const ctx = canvas.getContext('2d');
    
    // Vérifier que les données existent
    const labels = data?.labels || ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
    const inscriptions = data?.inscriptions || [0, 0, 0, 0, 0, 0, 0];
    
    // Gradient pour le remplissage
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.15)');
    gradient.addColorStop(0.6, 'rgba(59, 130, 246, 0.05)');
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');
    
    inscriptionsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Inscriptions',
                data: inscriptions,
                borderColor: '#3B82F6',
                backgroundColor: gradient,
                borderWidth: 2.5,
                fill: true,
                tension: 0,
                pointRadius: 3,
                pointHoverRadius: 6,
                pointBackgroundColor: '#3B82F6',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 1.5,
                pointHoverBackgroundColor: '#1D4ED8',
                pointHoverBorderColor: '#ffffff',
                pointHoverBorderWidth: 2,
                spanGaps: true,
                stepped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(255,255,255,0.95)',
                    titleColor: '#0F172A',
                    bodyColor: '#3B82F6',
                    borderColor: '#E5E7EB',
                    borderWidth: 1,
                    cornerRadius: 10,
                    padding: 12,
                    titleFont: { size: 13, weight: '600' },
                    bodyFont: { size: 14, weight: '500' },
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return `Inscriptions: ${context.parsed.y}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { 
                        color: 'rgba(0,0,0,0.05)',
                        drawBorder: false,
                        drawTicks: false
                    },
                    ticks: { 
                        font: { size: 12, family: 'Inter' },
                        color: '#6B7280',
                        padding: 10,
                        stepSize: 1
                    },
                    border: { display: false }
                },
                x: {
                    grid: { display: false },
                    ticks: { 
                        font: { size: 12, family: 'Inter' },
                        color: '#6B7280',
                        padding: 10
                    },
                    border: { display: false }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
}

function createVentesChart(data) {
    const canvas = document.getElementById('ventesChart');
    if (!canvas) return;
    
    if (ventesChart) {
        ventesChart.destroy();
        ventesChart = null;
    }
    
    const ctx = canvas.getContext('2d');
    
    const labels = data?.labels || ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
    const ca = data?.ca || [0, 0, 0, 0, 0, 0, 0];
    const ventes = data?.ventes || [0, 0, 0, 0, 0, 0, 0];
    
    // Gradient pour CA
    const gradientCA = ctx.createLinearGradient(0, 0, 0, 300);
    gradientCA.addColorStop(0, 'rgba(14, 164, 134, 0.15)');
    gradientCA.addColorStop(0.6, 'rgba(14, 164, 134, 0.05)');
    gradientCA.addColorStop(1, 'rgba(14, 164, 134, 0.0)');
    
    ventesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'CA (FCFA)',
                    data: ca,
                    borderColor: '#0EA486',
                    backgroundColor: gradientCA,
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#0EA486',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 1.5,
                    pointHoverBackgroundColor: '#047857',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 2,
                    yAxisID: 'y',
                    spanGaps: true,
                    stepped: false
                },
                {
                    label: 'Ventes',
                    data: ventes,
                    borderColor: '#3B82F6',
                    borderWidth: 2.5,
                    fill: false,
                    tension: 0,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#3B82F6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 1.5,
                    pointHoverBackgroundColor: '#1D4ED8',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 2,
                    yAxisID: 'y1',
                    spanGaps: true,
                    stepped: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    display: true,
                    position: 'top',
                    labels: {
                        font: { size: 13, weight: '600', family: 'Inter' },
                        color: '#0F172A',
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        boxWidth: 8,
                        boxHeight: 8
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(255,255,255,0.95)',
                    titleColor: '#0F172A',
                    borderColor: '#E5E7EB',
                    borderWidth: 1,
                    cornerRadius: 10,
                    padding: 12,
                    titleFont: { size: 13, weight: '600' },
                    bodyFont: { size: 14, weight: '500' },
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            if (context.dataset.label === 'CA (FCFA)') {
                                return 'CA: ' + new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' FCFA';
                            }
                            return 'Ventes: ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { 
                        color: 'rgba(0,0,0,0.05)',
                        drawBorder: false,
                        drawTicks: false
                    },
                    ticks: { 
                        font: { size: 12, family: 'Inter' },
                        color: '#0EA486',
                        padding: 10,
                        callback: function(value) {
                            if (value >= 1000000) return (value / 1000000) + 'M';
                            if (value >= 1000) return (value / 1000) + 'k';
                            return value;
                        }
                    },
                    border: { display: false },
                    position: 'left'
                },
                y1: {
                    beginAtZero: true,
                    grid: { display: false },
                    ticks: { 
                        font: { size: 12, family: 'Inter' },
                        color: '#3B82F6',
                        padding: 10,
                        stepSize: 1
                    },
                    border: { display: false },
                    position: 'right'
                },
                x: {
                    grid: { display: false },
                    ticks: { 
                        font: { size: 12, family: 'Inter' },
                        color: '#6B7280',
                        padding: 10
                    },
                    border: { display: false }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
}

// ============================================
// 2. CHARGEMENT DES DONNÉES
// ============================================

async function loadChartData(period) {
    if (isLoading) return;
    isLoading = true;
    
    currentPeriod = period;
    console.log('🔄 Chargement des données pour:', period);
    
    // Mettre à jour le select
    const periodSelect = document.getElementById('periodSelect');
    if (periodSelect) {
        periodSelect.value = period;
    }
    
    // Mettre à jour les liens
    document.querySelectorAll('.chart-period-link').forEach(link => {
        const isActive = link.dataset.period === period;
        link.classList.toggle('text-[#0EA486]', isActive);
        link.classList.toggle('bg-[#0EA486]/10', isActive);
        link.classList.toggle('font-semibold', isActive);
        link.classList.toggle('text-gray-500', !isActive);
        link.classList.toggle('font-medium', !isActive);
    });
    
    // Afficher le chargement
    showLoadingState(true);
    
    try {
        // Construire l'URL API
        const apiUrl = `/back-end/routes/api.php?url=stats_chart_data&period=${period}`;
        console.log('📡 Appel API:', apiUrl);
        
        const response = await fetch(apiUrl);
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const result = await response.json();
        console.log('📊 Données reçues:', result);
        
        if (result.success && result.data) {
            // Vérifier que les données sont valides
            const data = result.data;
            
            // S'assurer que les tableaux existent
            data.labels = data.labels || [];
            data.inscriptions = data.inscriptions || [];
            data.ventes = data.ventes || [];
            data.ca = data.ca || [];
            
            // Mettre à jour les graphiques
            createInscriptionsChart(data);
            createVentesChart(data);
            
            showToast('Succès', 'Graphiques mis à jour ', 'success');
        } else {
            throw new Error(result.message || 'Données invalides');
        }
    } catch (error) {
        console.error(' Erreur:', error);
        showToast('Erreur', error.message || 'Impossible de charger les données', 'error');
        
        // Données de secours
        const fallbackData = getFallbackData(period);
        createInscriptionsChart(fallbackData);
        createVentesChart(fallbackData);
    } finally {
        showLoadingState(false);
        isLoading = false;
    }
}

// ============================================
// 3. DONNÉES DE SECOURS
// ============================================

function getFallbackData(period) {
    const labels = {
        'week': ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
        'month': Array.from({length: 30}, (_, i) => `${i+1}/` + new Date().getMonth() + 1),
        'quarter': ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
        'year': ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc']
    };
    
    const count = labels[period]?.length || 7;
    
    return {
        labels: labels[period] || labels.week,
        inscriptions: Array(count).fill(0),
        ventes: Array(count).fill(0),
        ca: Array(count).fill(0)
    };
}

// ============================================
// 4. GESTION DES ÉTATS
// ============================================

function showLoadingState(isLoading) {
    const canvases = document.querySelectorAll('canvas');
    canvases.forEach(canvas => {
        canvas.style.opacity = isLoading ? '0.5' : '1';
        canvas.style.transition = 'opacity 0.3s';
    });
    
    document.querySelectorAll('.chart-period-link, #periodSelect, button').forEach(el => {
        el.style.pointerEvents = isLoading ? 'none' : 'auto';
        el.style.opacity = isLoading ? '0.6' : '1';
    });
}

// ============================================
// 5. TOAST NOTIFICATIONS
// ============================================

function showToast(title, message, type = 'info') {
    let toast = document.getElementById('chartToast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'chartToast';
        toast.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: white;
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            border-left: 4px solid #0EA486;
            z-index: 9999;
            max-width: 350px;
            font-family: Inter, sans-serif;
            transform: translateX(100%);
            transition: all 0.3s ease;
            opacity: 0;
        `;
        document.body.appendChild(toast);
    }
    
    const colors = {
        success: '#0EA486',
        error: '#EF4444',
        info: '#3B82F6',
        warning: '#F59E0B'
    };
    
    toast.style.borderLeftColor = colors[type] || colors.info;
    toast.innerHTML = `
        <div style="display:flex; align-items:center; gap:10px;">
            <div style="font-size:20px;">${type === 'success' ? '' : type === 'error' ? '' : 'ℹ️'}</div>
            <div>
                <div style="font-weight:600; font-size:14px; color:#0F172A;">${title}</div>
                <div style="font-size:12px; color:#6B7280; margin-top:2px;">${message}</div>
            </div>
        </div>
    `;
    
    toast.style.transform = 'translateX(0)';
    toast.style.opacity = '1';
    
    clearTimeout(toast._hideTimeout);
    toast._hideTimeout = setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        toast.style.opacity = '0';
    }, 3000);
}

// ============================================
// 6. INITIALISATION
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Initialisation des graphiques...');
    
    // Déterminer la période initiale
    const periodSelect = document.getElementById('periodSelect');
    const initialPeriod = periodSelect ? periodSelect.value : 'month';
    currentPeriod = initialPeriod;
    
    // Initialiser les graphiques avec les données PHP
    if (chartData && chartData.labels) {
        console.log('📊 Données initiales PHP:', chartData);
        createInscriptionsChart(chartData);
        createVentesChart(chartData);
    } else {
        // Utiliser des données de secours
        const fallback = getFallbackData(initialPeriod);
        createInscriptionsChart(fallback);
        createVentesChart(fallback);
    }
    
    // Mettre à jour l'état des liens
    document.querySelectorAll('.chart-period-link').forEach(link => {
        const isActive = link.dataset.period === initialPeriod;
        link.classList.toggle('text-[#0EA486]', isActive);
        link.classList.toggle('bg-[#0EA486]/10', isActive);
        link.classList.toggle('font-semibold', isActive);
        link.classList.toggle('text-gray-500', !isActive);
        link.classList.toggle('font-medium', !isActive);
    });
    
    // Événement sur le select
    if (periodSelect) {
        periodSelect.addEventListener('change', function() {
            const period = this.value;
            if (period !== currentPeriod) {
                loadChartData(period);
            }
        });
    }
    
    // Événement sur les liens
    document.querySelectorAll('.chart-period-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const period = this.dataset.period || 'week';
            if (period !== currentPeriod) {
                loadChartData(period);
            }
        });
    });
    
    // Événement sur le bouton d'actualisation
    const refreshBtn = document.querySelector('button .fa-sync-alt')?.closest('button');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            loadChartData(currentPeriod);
        });
    }
    
    console.log(' Graphiques prêts - Période:', initialPeriod);
});

// Exposer pour débogage
window.chartManager = {
    loadChartData,
    currentPeriod: () => currentPeriod,
    setPeriod: (period) => loadChartData(period),
    refresh: () => loadChartData(currentPeriod)
};

console.log('💡 Utilise window.chartManager.setPeriod("month") pour changer la période');





(function() {
    'use strict';

    // ============================================
    // 1. RAPPORT DE VENTES
    // ============================================
    function loadSalesReport() {
        const container = document.querySelector('.report-tab-content[data-tab="sales"]');
        if (!container) return;
        
        const startDate = container.querySelector('input[type="date"]:first-child')?.value || '';
        const endDate = container.querySelector('input[type="date"]:nth-child(3)')?.value || '';
        const categorie = container.querySelector('select:first-child')?.value || '';
        const vendeur = container.querySelector('select:last-child')?.value || '';
        
        const formData = new FormData();
        formData.append('start_date', startDate);
        formData.append('end_date', endDate);
        formData.append('categorie', categorie);
        formData.append('vendeur', vendeur);
        
        fetch('api.php?url=report_sales_generate', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateSalesReport(data.data, data.summary);
            }
        })
        .catch(error => console.error('Erreur:', error));
    }

    function updateSalesReport(data, summary) {
        // Mettre à jour les KPI cards
        const container = document.querySelector('.report-tab-content[data-tab="sales"]');
        if (!container) return;
        
        const kpis = container.querySelectorAll('.grid-cols-2.md\\:grid-cols-4 .p-4 .text-xl');
        if (kpis.length >= 4) {
            kpis[0].textContent = summary?.total_ventes || 0;
            kpis[1].textContent = (summary?.ca_total || 0).toLocaleString('fr-FR') + ' FCFA';
            kpis[2].textContent = (summary?.panier_moyen || 0).toLocaleString('fr-FR') + ' FCFA';
            kpis[3].textContent = summary?.meilleur_jour || '-';
        }
        
        // Mettre à jour le tableau
        const tbody = container.querySelector('tbody');
        if (!tbody || !data || data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-400 text-sm">
                        <i class="fas fa-inbox text-3xl block mb-2"></i>
                        Aucune donnée pour cette période
                    </td>
                </tr>
            `;
            return;
        }
        
        tbody.innerHTML = data.map(row => `
            <tr class="hover:bg-gray-50/50 transition">
                <td class="px-4 py-3 text-xs text-gray-500">${row.date || '-'}</td>
                <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">${escapeHtml(row.produit || '-')}</td>
                <td class="px-4 py-3 text-xs text-gray-600">${escapeHtml(row.vendeur || '-')}</td>
                <td class="px-4 py-3">
                    <span class="text-[10px] font-semibold text-indigo-700 bg-indigo-100 px-2 py-1 rounded-full">${escapeHtml(row.categorie || '-')}</span>
                </td>
                <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">${(row.prix || 0).toLocaleString('fr-FR')} FCFA</td>
                <td class="px-4 py-3 text-xs font-semibold text-[#0EA486]">${(row.commission || 0).toLocaleString('fr-FR')} FCFA</td>
                <td class="px-4 py-3">
                    <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-1 rounded-full">${row.statut || '-'}</span>
                </td>
            </tr>
        `).join('');
    }

    // ============================================
    // 2. RAPPORT FINANCIER
    // ============================================
    function loadFinancialReport() {
        const container = document.querySelector('.report-tab-content[data-tab="financial"]');
        if (!container) return;
        
        const startDate = container.querySelector('input[type="date"]:first-child')?.value || '';
        const endDate = container.querySelector('input[type="date"]:nth-child(3)')?.value || '';
        
        fetch(`api.php?url=report_financial_data&start_date=${startDate}&end_date=${endDate}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateFinancialReport(data.data, data.summary);
                }
            })
            .catch(error => console.error('Erreur:', error));
    }

    function updateFinancialReport(data, summary) {
        const container = document.querySelector('.report-tab-content[data-tab="financial"]');
        if (!container) return;
        
        // Mettre à jour les KPI cards
        const kpis = container.querySelectorAll('.grid-cols-2.md\\:grid-cols-4 .p-4 .text-xl');
        if (kpis.length >= 4) {
            kpis[0].textContent = (summary?.ca_total || 0).toLocaleString('fr-FR') + ' FCFA';
            kpis[1].textContent = (summary?.commission_plateforme || 0).toLocaleString('fr-FR') + ' FCFA';
            kpis[2].textContent = (summary?.commission_vendeurs || 0).toLocaleString('fr-FR') + ' FCFA';
            kpis[3].textContent = (summary?.versements || 0).toLocaleString('fr-FR') + ' FCFA';
        }
        
        // Mettre à jour le tableau
        const tbody = container.querySelector('tbody');
        if (!tbody || !data || data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400 text-sm">
                        <i class="fas fa-inbox text-3xl block mb-2"></i>
                        Aucune donnée financière
                    </td>
                </tr>
            `;
            return;
        }
        
        tbody.innerHTML = data.map(row => `
            <tr class="hover:bg-gray-50/50 transition">
                <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">${row.mois || '-'}</td>
                <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">${(row.ca || 0).toLocaleString('fr-FR')} FCFA</td>
                <td class="px-4 py-3 text-xs font-semibold text-[#0EA486]">${(row.commission_plateforme || 0).toLocaleString('fr-FR')} FCFA</td>
                <td class="px-4 py-3 text-xs font-semibold text-purple-600">${(row.commission_vendeurs || 0).toLocaleString('fr-FR')} FCFA</td>
                <td class="px-4 py-3 text-xs font-semibold text-blue-600">${(row.versements || 0).toLocaleString('fr-FR')} FCFA</td>
                <td class="px-4 py-3 text-xs font-semibold text-orange-600">${(row.solde_du || 0).toLocaleString('fr-FR')} FCFA</td>
            </tr>
        `).join('');
    }

    // ============================================
    // 3. RAPPORT UTILISATEURS
    // ============================================
    function loadUsersReport() {
        const container = document.querySelector('.report-tab-content[data-tab="users"]');
        if (!container) return;
        
        const startDate = container.querySelector('input[type="date"]:first-child')?.value || '';
        const endDate = container.querySelector('input[type="date"]:nth-child(3)')?.value || '';
        
        fetch(`api.php?url=report_users_data&start_date=${startDate}&end_date=${endDate}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateUsersReport(data.data, data.summary);
                }
            })
            .catch(error => console.error('Erreur:', error));
    }

    function updateUsersReport(data, summary) {
        const container = document.querySelector('.report-tab-content[data-tab="users"]');
        if (!container) return;
        
        const kpis = container.querySelectorAll('.grid-cols-2.md\\:grid-cols-4 .p-4 .text-xl');
        if (kpis.length >= 4) {
            kpis[0].textContent = summary?.total_inscriptions || 0;
            kpis[1].textContent = summary?.total_connexions || 0;
            kpis[2].textContent = summary?.total_acheteurs || 0;
            kpis[3].textContent = (summary?.taux_retention || 0) + '%';
        }
        
        const tbody = container.querySelector('tbody');
        if (!tbody || !data || data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-400 text-sm">
                        <i class="fas fa-inbox text-3xl block mb-2"></i>
                        Aucune donnée utilisateur
                    </td>
                </tr>
            `;
            return;
        }
        
        tbody.innerHTML = data.map(row => `
            <tr class="hover:bg-gray-50/50 transition">
                <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">${row.date || '-'}</td>
                <td class="px-4 py-3 text-xs font-semibold text-blue-600">${row.inscriptions || 0}</td>
                <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">${row.connexions || 0}</td>
                <td class="px-4 py-3 text-xs font-semibold text-emerald-600">${row.acheteurs_actifs || 0}</td>
                <td class="px-4 py-3 text-xs font-semibold text-red-600">${row.desabonnements || 0}</td>
            </tr>
        `).join('');
    }

    // ============================================
    // 4. RAPPORT VENDEURS
    // ============================================
    function loadVendorsReport() {
        const container = document.querySelector('.report-tab-content[data-tab="vendors"]');
        if (!container) return;
        
        const startDate = container.querySelector('input[type="date"]:first-child')?.value || '';
        const endDate = container.querySelector('input[type="date"]:nth-child(3)')?.value || '';
        
        fetch(`api.php?url=report_vendors_data&start_date=${startDate}&end_date=${endDate}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateVendorsReport(data.data, data.summary);
                }
            })
            .catch(error => console.error('Erreur:', error));
    }

    function updateVendorsReport(data, summary) {
        const container = document.querySelector('.report-tab-content[data-tab="vendors"]');
        if (!container) return;
        
        const kpis = container.querySelectorAll('.grid-cols-2.md\\:grid-cols-4 .p-4 .text-xl');
        if (kpis.length >= 4) {
            kpis[0].textContent = summary?.total_vendeurs || 0;
            kpis[1].textContent = summary?.total_produits || 0;
            kpis[2].textContent = summary?.total_ventes || 0;
            kpis[3].textContent = (summary?.revenu_moyen || 0).toLocaleString('fr-FR') + ' FCFA';
        }
        
        const tbody = container.querySelector('tbody');
        if (!tbody || !data || data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400 text-sm">
                        <i class="fas fa-inbox text-3xl block mb-2"></i>
                        Aucun vendeur
                    </td>
                </tr>
            `;
            return;
        }
        
        tbody.innerHTML = data.map(row => `
            <tr class="hover:bg-gray-50/50 transition">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-lg flex items-center justify-center text-emerald-500">
                            <i class="fas fa-store text-xs"></i>
                        </div>
                        <span class="text-xs font-semibold text-[#0F172A]">${escapeHtml(row.nom || '-')}</span>
                    </div>
                </td>
                <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">${row.produits || 0}</td>
                <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">${row.ventes || 0}</td>
                <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">${(row.ca || 0).toLocaleString('fr-FR')} FCFA</td>
                <td class="px-4 py-3 text-xs font-semibold text-[#0EA486]">${(row.commission || 0).toLocaleString('fr-FR')} FCFA</td>
                <td class="px-4 py-3">
                    <span class="text-xs font-semibold text-amber-600">
                        <i class="fas fa-star text-[10px]"></i> ${row.note_moyenne ? Number(row.note_moyenne).toFixed(1) : '-'}
                    </span>
                </td>
            </tr>
        `).join('');
    }

    // ============================================
    // 5. UTILITAIRES
    // ============================================
    function escapeHtml(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    // ============================================
// GÉNÉRATION DES RAPPORTS
// ============================================

document.querySelectorAll('.generateReportBtn').forEach(btn => {
    btn.addEventListener('click', function() {
        const tab = this.closest('.report-tab-content');
        if (!tab) return;
        
        const tabName = tab.dataset.tab;
        const startInputs = tab.querySelectorAll('input[type="date"]');
        const startDate = startInputs[0]?.value || '';
        const endDate = startInputs[1]?.value || '';
        
        // Récupérer les filtres pour le rapport de ventes
        let categorie = '';
        let vendeur = '';
        if (tabName === 'sales') {
            const selects = tab.querySelectorAll('select');
            if (selects.length >= 2) {
                categorie = selects[0].value || '';
                vendeur = selects[1].value || '';
            }
        }
        
        // Construire l'URL
        let url = `/back-end/routes/api.php?url=report_${tabName}_data&start_date=${startDate}&end_date=${endDate}`;
        if (categorie) url += `&categorie=${categorie}`;
        if (vendeur) url += `&vendeur=${vendeur}`;
        
        showToast('Génération', 'Chargement des données...', 'info');
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateReportData(tabName, data.data, data.summary);
                    showToast('Succès', 'Rapport mis à jour ✅', 'success');
                } else {
                    throw new Error(data.message || 'Erreur');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Erreur', 'Impossible de générer le rapport', 'error');
            });
    });
});

// Fonction pour mettre à jour les données du rapport
function updateReportData(tabName, data, summary) {
    const container = document.querySelector(`.report-tab-content[data-tab="${tabName}"]`);
    if (!container) return;
    
    // Mettre à jour les KPI
    const kpis = container.querySelectorAll('.grid-cols-2.md\\:grid-cols-4 .p-4 .text-xl');
    
    switch(tabName) {
        case 'sales':
            if (kpis.length >= 4) {
                kpis[0].textContent = summary?.total_ventes || 0;
                kpis[1].textContent = (summary?.ca_total || 0).toLocaleString('fr-FR') + ' FCFA';
                kpis[2].textContent = (summary?.panier_moyen || 0).toLocaleString('fr-FR') + ' FCFA';
                kpis[3].textContent = summary?.meilleur_jour || '-';
            }
            break;
        case 'financial':
            if (kpis.length >= 4) {
                kpis[0].textContent = (summary?.ca_total || 0).toLocaleString('fr-FR') + ' FCFA';
                kpis[1].textContent = (summary?.commission_plateforme || 0).toLocaleString('fr-FR') + ' FCFA';
                kpis[2].textContent = (summary?.commission_vendeurs || 0).toLocaleString('fr-FR') + ' FCFA';
                kpis[3].textContent = (summary?.versements || 0).toLocaleString('fr-FR') + ' FCFA';
            }
            break;
        case 'users':
            if (kpis.length >= 4) {
                kpis[0].textContent = summary?.total_inscriptions || 0;
                kpis[1].textContent = summary?.total_connexions || 0;
                kpis[2].textContent = summary?.total_acheteurs || 0;
                kpis[3].textContent = (summary?.taux_retention || 0) + '%';
            }
            break;
        case 'vendors':
            if (kpis.length >= 4) {
                kpis[0].textContent = summary?.total_vendeurs || 0;
                kpis[1].textContent = summary?.total_produits || 0;
                kpis[2].textContent = summary?.total_ventes || 0;
                kpis[3].textContent = (summary?.revenu_moyen || 0).toLocaleString('fr-FR') + ' FCFA';
            }
            break;
    }
    
    // Mettre à jour le tableau
    updateReportTable(container, data, tabName);
}
    
    // ============================================
    // 7. EXPORT DES RAPPORTS
    // ============================================
    document.querySelectorAll('.exportReportBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            const format = this.dataset.format || 'csv';
            const tab = this.closest('.report-tab-content');
            if (!tab) return;
            
            const tabName = tab.dataset.tab;
            const startDate = tab.querySelector('input[type="date"]:first-child')?.value || '';
            const endDate = tab.querySelector('input[type="date"]:nth-child(3)')?.value || '';
            
            // Ouvrir le modal d'export
            const modal = document.getElementById('exportModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        });
    });

    // ============================================
    // 8. INITIALISATION - Charger les données par défaut
    // ============================================
    // Charger le rapport de ventes par défaut
    setTimeout(() => {
        loadSalesReport();
    }, 500);

})();


// ============================================
// GRAPHIQUE GÉOGRAPHIQUE - DYNAMIQUE
// ============================================

let geoChart = null;
let currentGeoPeriod = 'all';

/**
 * Créer ou mettre à jour le graphique géographique
 */
function createGeoChart(data) {
    const canvas = document.getElementById('geoChart');
    if (!canvas) return;
    
    // Détruire l'ancien graphique s'il existe
    if (geoChart) {
        geoChart.destroy();
        geoChart = null;
    }
    
    const ctx = canvas.getContext('2d');
    
    // Vérifier les données
    const labels = data?.labels || ['Sénégal', 'Côte d\'Ivoire', 'Cameroun', 'Mali', 'Autres'];
    const values = data?.values || [0, 0, 0, 0, 0];
    const pourcentages = data?.pourcentages || [0, 0, 0, 0, 0];
    
    // Couleurs pour les barres
    const colors = [
        '#0EA486',
        '#0EA486',
        '#0EA486',
        '#0EA486',
        '#0EA486'
    ];
    
    // Créer le graphique
    geoChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Acheteurs (%)',
                data: values,
                backgroundColor: colors,
                borderColor: colors.map(c => c),
                borderWidth: 0,
                borderRadius: 8,
                barThickness: 30
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: {
                legend: { 
                    display: false 
                },
                tooltip: {
                    backgroundColor: 'rgba(255,255,255,0.95)',
                    titleColor: '#0F172A',
                    bodyColor: '#0EA486',
                    borderColor: '#E5E7EB',
                    borderWidth: 1,
                    cornerRadius: 10,
                    padding: 12,
                    titleFont: { size: 13, weight: '600' },
                    bodyFont: { size: 14, weight: '500' },
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            const index = context.dataIndex;
                            const pourcentage = pourcentages[index] || 0;
                            const valeur = context.parsed.x;
                            return `${valeur} utilisateurs (${pourcentage}%)`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: { 
                        color: 'rgba(0,0,0,0.05)',
                        drawBorder: false
                    },
                    ticks: { 
                        font: { size: 11, family: 'Inter' },
                        color: '#6B7280',
                        padding: 10
                    },
                    border: { display: false }
                },
                y: {
                    grid: { display: false },
                    ticks: { 
                        font: { size: 12, family: 'Inter', weight: '500' },
                        color: '#0F172A',
                        padding: 10
                    },
                    border: { display: false }
                }
            },
            interaction: {
                intersect: true,
                mode: 'index'
            }
        }
    });
    
    console.log(' Graphique géographique créé');
}

/**
 * Charger les données géographiques depuis l'API
 */
async function loadGeoData() {
    try {
        console.log('🔄 Chargement des données géographiques...');
        
        const apiUrl = `/back-end/routes/api.php?url=stats_geo_distribution`;
        console.log('📡 Appel API:', apiUrl);
        
        const response = await fetch(apiUrl);
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const result = await response.json();
        console.log('📊 Données géo reçues:', result);
        
        if (result.success && result.data) {
            // Mettre à jour le graphique
            createGeoChart(result.data);
            
            // Mettre à jour le texte récapitulatif (optionnel)
            updateGeoSummary(result.data);
            
            showToast('Succès', 'Données géographiques mises à jour ', 'success');
        } else {
            throw new Error(result.message || 'Données invalides');
        }
    } catch (error) {
        console.error(' Erreur chargement géo:', error);
        showToast('Erreur', 'Impossible de charger les données géographiques', 'error');
        
        // Données de secours
        const fallbackData = {
            labels: ['Sénégal', 'Côte d\'Ivoire', 'Cameroun', 'Mali', 'Autres'],
            values: [0, 0, 0, 0, 0],
            pourcentages: [0, 0, 0, 0, 0]
        };
        createGeoChart(fallbackData);
    }
}

/**
 * Mettre à jour le résumé géographique
 */
function updateGeoSummary(data) {
    const summaryEl = document.getElementById('geoSummary');
    if (!summaryEl) return;
    
    if (!data || !data.labels || data.labels.length === 0) {
        summaryEl.innerHTML = 'Aucune donnée géographique disponible';
        return;
    }
    
    // Trouver le pays avec le plus d'utilisateurs
    const maxIndex = data.values.indexOf(Math.max(...data.values));
    const topPays = data.labels[maxIndex] || 'Inconnu';
    const topValue = data.values[maxIndex] || 0;
    const topPercent = data.pourcentages[maxIndex] || 0;
    
    // Calculer le total
    const total = data.values.reduce((a, b) => a + b, 0);
    
    summaryEl.innerHTML = `
        <div class="flex items-center gap-4 text-xs">
            <span class="flex items-center gap-1">
                <span class="w-2 h-2 bg-[#0EA486] rounded-full"></span>
                <span class="font-medium">Total:</span>
                <span class="text-gray-600">${total} utilisateurs</span>
            </span>
            <span class="flex items-center gap-1">
                <span class="w-2 h-2 bg-[#0EA486] rounded-full"></span>
                <span class="font-medium">Top:</span>
                <span class="text-gray-600">${topPays} (${topValue} - ${topPercent}%)</span>
            </span>
        </div>
    `;
}

/**
 * Initialiser le graphique géographique
 */
function initGeoChart() {
    console.log('🚀 Initialisation du graphique géographique...');
    
    // Vérifier si les données PHP sont disponibles
    if (typeof geoData !== 'undefined' && geoData && geoData.labels) {
        console.log('📊 Données PHP géo disponibles');
        createGeoChart(geoData);
        updateGeoSummary(geoData);
    } else {
        // Charger depuis l'API
        loadGeoData();
    }
}

// Exposer les fonctions
window.geoChartManager = {
    loadGeoData,
    refresh: loadGeoData,
    createGeoChart
};

// Initialiser au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Attendre un peu pour s'assurer que tout est chargé
    setTimeout(initGeoChart, 500);
});






// ============================================
// GESTION DES EXPORTS AVEC MODAL
// ============================================

(function() {
    'use strict';

    let selectedFormat = 'csv';
    let selectedTab = 'sales';
    let selectedStartDate = '';
    let selectedEndDate = '';
    let selectedCategorie = '';
    let selectedVendeur = '';

    // ============================================
    // 1. OUVERTURE DU MODAL D'EXPORT
    // ============================================
    document.querySelectorAll('.exportReportBtn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const format = this.dataset.format || 'csv';
            const tab = this.closest('.report-tab-content');
            if (!tab) return;
            
            // Récupérer les informations
            selectedTab = tab.dataset.tab || 'sales';
            selectedFormat = format;
            
            const startInputs = tab.querySelectorAll('input[type="date"]');
            selectedStartDate = startInputs[0]?.value || '';
            selectedEndDate = startInputs[1]?.value || '';
            
            if (selectedTab === 'sales') {
                const selects = tab.querySelectorAll('select');
                if (selects.length >= 2) {
                    selectedCategorie = selects[0].value || '';
                    selectedVendeur = selects[1].value || '';
                }
            }
            
            // Mettre à jour le modal avec le format sélectionné
            const modal = document.getElementById('exportModal');
            if (modal) {
                // Mettre en surbrillance le format sélectionné
                document.querySelectorAll('.export-format-btn').forEach(b => {
                    b.classList.remove('border-[#0EA486]', 'bg-[#0EA486]/5');
                    if (b.dataset.format === format) {
                        b.classList.add('border-[#0EA486]', 'bg-[#0EA486]/5');
                    }
                });
                
                // Mettre à jour les dates dans le modal
                const dateInputs = modal.querySelectorAll('input[type="date"]');
                if (dateInputs.length >= 2) {
                    dateInputs[0].value = selectedStartDate;
                    dateInputs[1].value = selectedEndDate;
                }
                
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        });
    });

    // ============================================
    // 2. SÉLECTION DU FORMAT DANS LE MODAL
    // ============================================
    document.querySelectorAll('.export-format-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.export-format-btn').forEach(b => {
                b.classList.remove('border-[#0EA486]', 'bg-[#0EA486]/5');
            });
            this.classList.add('border-[#0EA486]', 'bg-[#0EA486]/5');
            selectedFormat = this.dataset.format || 'csv';
        });
    });

    // ============================================
    // 3. FERMETURE DU MODAL
    // ============================================
    function closeExportModal() {
        const modal = document.getElementById('exportModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
    }

    document.querySelectorAll('.closeExportBtn').forEach(btn => {
        btn.addEventListener('click', closeExportModal);
    });

    document.getElementById('exportModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeExportModal();
    });

    // ============================================
    // 4. CONFIRMATION ET TÉLÉCHARGEMENT
    // ============================================
    document.querySelector('.confirmExportBtn')?.addEventListener('click', function() {
        // Récupérer les dates du modal
        const modal = document.getElementById('exportModal');
        const dateInputs = modal?.querySelectorAll('input[type="date"]');
        const startDate = dateInputs?.[0]?.value || selectedStartDate;
        const endDate = dateInputs?.[1]?.value || selectedEndDate;
        
        // Récupérer les options
        const includeCharts = modal?.querySelector('input[type="checkbox"]:first-child')?.checked || false;
        const includeSummary = modal?.querySelectorAll('input[type="checkbox"]')[1]?.checked || false;
        const sendEmail = modal?.querySelectorAll('input[type="checkbox"]')[2]?.checked || false;
        
        // Fermer le modal
        closeExportModal();
        
        // Afficher le toast de chargement
        showToast('Export en cours', 'Génération du fichier ' + selectedFormat.toUpperCase() + '...', 'info');
        
        // Construire les données
        const formData = new FormData();
        formData.append('type', selectedTab);
        formData.append('format', selectedFormat);
        formData.append('start_date', startDate);
        formData.append('end_date', endDate);
        formData.append('categorie', selectedCategorie);
        formData.append('vendeur', selectedVendeur);
        formData.append('include_charts', includeCharts ? '1' : '0');
        formData.append('include_summary', includeSummary ? '1' : '0');
        formData.append('send_email', sendEmail ? '1' : '0');
        
        // Envoyer la requête
        fetch('/back-end/routes/api.php?url=export_report', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur HTTP: ' + response.status);
            }
            
            // Extraire le nom du fichier
            const contentDisposition = response.headers.get('Content-Disposition');
            let filename = 'rapport.' + selectedFormat;
            
            if (contentDisposition) {
                const match = contentDisposition.match(/filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/);
                if (match && match[1]) {
                    filename = match[1].replace(/['"]/g, '');
                }
            }
            
            return response.blob().then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
                
                showToast('Export réussi', 'Fichier ' + selectedFormat.toUpperCase() + ' téléchargé ✅', 'success');
            });
        })
        .catch(error => {
            console.error('❌ Erreur export:', error);
            showToast('Erreur', 'Impossible d\'exporter le rapport', 'error');
        });
    });

    // ============================================
    // 5. RACCOURCI CLAVIER (ESC pour fermer)
    // ============================================
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('exportModal');
            if (modal && !modal.classList.contains('hidden')) {
                closeExportModal();
            }
        }
    });

})();
    </script>
</body>
</html>