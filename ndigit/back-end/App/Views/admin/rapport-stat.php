<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NDIGITMARKET Admin · Statistiques & Rapports</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/CSS/app.css">
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
                <button class="relative w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 transition flex items-center justify-center">
                    <i class="fas fa-bell text-gray-600"></i>
                    <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                </button>
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
                        <div class="flex gap-1">
                            <button class="chart-toggle-btn active px-2 py-1 text-[10px] font-semibold text-[#0EA486] bg-[#0EA486]/10 rounded" data-chart="inscriptions" data-period="week">Sem</button>
                            <button class="chart-toggle-btn px-2 py-1 text-[10px] font-medium text-gray-500 hover:bg-gray-100 rounded" data-chart="inscriptions" data-period="month">Mois</button>
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
                        <div class="flex gap-1">
                            <button class="chart-toggle-btn active px-2 py-1 text-[10px] font-semibold text-[#0EA486] bg-[#0EA486]/10 rounded" data-chart="ventes" data-period="week">Sem</button>
                            <button class="chart-toggle-btn px-2 py-1 text-[10px] font-medium text-gray-500 hover:bg-gray-100 rounded" data-chart="ventes" data-period="month">Mois</button>
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
                        <button class="text-xs text-[#0EA486] hover:underline font-medium">Voir tout</button>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600 font-bold text-xs">1</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-[#0F172A] truncate">-</p>
                                <p class="text-[10px] text-gray-400">-</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-[#0EA486]">-</p>
                                <p class="text-[10px] text-gray-400">ventes</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center text-gray-600 font-bold text-xs">2</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-[#0F172A] truncate">-</p>
                                <p class="text-[10px] text-gray-400">-</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-[#0EA486]">-</p>
                                <p class="text-[10px] text-gray-400">ventes</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center text-orange-600 font-bold text-xs">3</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-[#0F172A] truncate">-</p>
                                <p class="text-[10px] text-gray-400">-</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-[#0EA486]">-</p>
                                <p class="text-[10px] text-gray-400">ventes</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center text-gray-500 font-bold text-xs">4</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-[#0F172A] truncate">-</p>
                                <p class="text-[10px] text-gray-400">-</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-[#0EA486]">-</p>
                                <p class="text-[10px] text-gray-400">ventes</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center text-gray-500 font-bold text-xs">5</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-[#0F172A] truncate">-</p>
                                <p class="text-[10px] text-gray-400">-</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-[#0EA486]">-</p>
                                <p class="text-[10px] text-gray-400">ventes</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Produits les plus vus -->
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-bold text-[#0F172A] flex items-center gap-2">
                            <i class="fas fa-eye text-blue-500"></i> Top produits vus
                        </h4>
                        <button class="text-xs text-[#0EA486] hover:underline font-medium">Voir tout</button>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 font-bold text-xs">1</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-[#0F172A] truncate">-</p>
                                <p class="text-[10px] text-gray-400">-</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-blue-600">-</p>
                                <p class="text-[10px] text-gray-400">vues</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center text-blue-500 font-bold text-xs">2</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-[#0F172A] truncate">-</p>
                                <p class="text-[10px] text-gray-400">-</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-blue-600">-</p>
                                <p class="text-[10px] text-gray-400">vues</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center text-blue-500 font-bold text-xs">3</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-[#0F172A] truncate">-</p>
                                <p class="text-[10px] text-gray-400">-</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-blue-600">-</p>
                                <p class="text-[10px] text-gray-400">vues</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center text-gray-500 font-bold text-xs">4</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-[#0F172A] truncate">-</p>
                                <p class="text-[10px] text-gray-400">-</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-blue-600">-</p>
                                <p class="text-[10px] text-gray-400">vues</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center text-gray-500 font-bold text-xs">5</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-[#0F172A] truncate">-</p>
                                <p class="text-[10px] text-gray-400">-</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-blue-600">-</p>
                                <p class="text-[10px] text-gray-400">vues</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Catégories performantes -->
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-bold text-[#0F172A] flex items-center gap-2">
                            <i class="fas fa-tags text-purple-500"></i> Top catégories
                        </h4>
                        <button class="text-xs text-[#0EA486] hover:underline font-medium">Voir tout</button>
                    </div>
                    <div class="h-48">
                        <canvas id="categoriesChart"></canvas>
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
                    <button class="text-xs text-[#0EA486] hover:underline font-medium">Voir détails</button>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div class="h-64">
                        <canvas id="geoChart"></canvas>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <div class="flex items-center gap-3">
                                <span class="text-xl">🇸🇳</span>
                                <div>
                                    <p class="text-xs font-semibold text-[#0F172A]">Sénégal</p>
                                    <p class="text-[10px] text-gray-400">- acheteurs</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-[#0EA486]">-%</p>
                                <div class="w-20 bg-gray-200 rounded-full h-1.5 mt-1">
                                    <div class="bg-[#0EA486] h-full rounded-full" style="width: 0%;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <div class="flex items-center gap-3">
                                <span class="text-xl">🇨🇮</span>
                                <div>
                                    <p class="text-xs font-semibold text-[#0F172A]">Côte d'Ivoire</p>
                                    <p class="text-[10px] text-gray-400">- acheteurs</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-[#0EA486]">-%</p>
                                <div class="w-20 bg-gray-200 rounded-full h-1.5 mt-1">
                                    <div class="bg-[#0EA486] h-full rounded-full" style="width: 0%;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <div class="flex items-center gap-3">
                                <span class="text-xl">🇨🇲</span>
                                <div>
                                    <p class="text-xs font-semibold text-[#0F172A]">Cameroun</p>
                                    <p class="text-[10px] text-gray-400">- acheteurs</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-[#0EA486]">-%</p>
                                <div class="w-20 bg-gray-200 rounded-full h-1.5 mt-1">
                                    <div class="bg-[#0EA486] h-full rounded-full" style="width: 0%;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <div class="flex items-center gap-3">
                                <span class="text-xl">🇲🇱</span>
                                <div>
                                    <p class="text-xs font-semibold text-[#0F172A]">Mali</p>
                                    <p class="text-[10px] text-gray-400">- acheteurs</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-[#0EA486]">-%</p>
                                <div class="w-20 bg-gray-200 rounded-full h-1.5 mt-1">
                                    <div class="bg-[#0EA486] h-full rounded-full" style="width: 0%;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <div class="flex items-center gap-3">
                                <span class="text-xl">🌍</span>
                                <div>
                                    <p class="text-xs font-semibold text-[#0F172A]">Autres pays</p>
                                    <p class="text-[10px] text-gray-400">- acheteurs</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-[#0EA486]">-%</p>
                                <div class="w-20 bg-gray-200 rounded-full h-1.5 mt-1">
                                    <div class="bg-[#0EA486] h-full rounded-full" style="width: 0%;"></div>
                                </div>
                            </div>
                        </div>
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
                            <input type="date" class="px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                            <span class="text-xs text-gray-400">à</span>
                            <input type="date" class="px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                            <select class="px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                                <option>Toutes les catégories</option>
                                <option>WordPress</option>
                                <option>HTML</option>
                                <option>PHP</option>
                                <option>React</option>
                            </select>
                            <select class="px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                                <option>Tous les vendeurs</option>
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
                            <p class="text-xl font-bold text-[#0F172A]">-</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl border border-emerald-100">
                            <p class="text-[10px] text-emerald-600 font-semibold uppercase mb-1">CA total</p>
                            <p class="text-xl font-bold text-[#0F172A]">- FCFA</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                            <p class="text-[10px] text-purple-600 font-semibold uppercase mb-1">Panier moyen</p>
                            <p class="text-xl font-bold text-[#0F172A]">- FCFA</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl border border-amber-100">
                            <p class="text-[10px] text-amber-600 font-semibold uppercase mb-1">Meilleur jour</p>
                            <p class="text-xl font-bold text-[#0F172A]">-</p>
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
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-4 py-3 text-xs text-gray-500">-</td>
                                    <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">-</td>
                                    <td class="px-4 py-3 text-xs text-gray-600">-</td>
                                    <td class="px-4 py-3">
                                        <span class="text-[10px] font-semibold text-indigo-700 bg-indigo-100 px-2 py-1 rounded-full">-</span>
                                    </td>
                                    <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">- FCFA</td>
                                    <td class="px-4 py-3 text-xs font-semibold text-[#0EA486]">- FCFA</td>
                                    <td class="px-4 py-3">
                                        <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-1 rounded-full">-</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab: Rapport financier -->
                <div class="report-tab-content hidden p-5" data-tab="financial">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <input type="date" class="px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                            <span class="text-xs text-gray-400">à</span>
                            <input type="date" class="px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
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
                            <p class="text-xl font-bold text-[#0F172A]">- FCFA</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                            <p class="text-[10px] text-blue-600 font-semibold uppercase mb-1">Commission plateforme</p>
                            <p class="text-xl font-bold text-[#0F172A]">- FCFA</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                            <p class="text-[10px] text-purple-600 font-semibold uppercase mb-1">Commission vendeurs</p>
                            <p class="text-xl font-bold text-[#0F172A]">- FCFA</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl border border-orange-100">
                            <p class="text-[10px] text-orange-600 font-semibold uppercase mb-1">Versements effectués</p>
                            <p class="text-xl font-bold text-[#0F172A]">- FCFA</p>
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
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">-</td>
                                    <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">- FCFA</td>
                                    <td class="px-4 py-3 text-xs font-semibold text-[#0EA486]">- FCFA</td>
                                    <td class="px-4 py-3 text-xs font-semibold text-purple-600">- FCFA</td>
                                    <td class="px-4 py-3 text-xs font-semibold text-blue-600">- FCFA</td>
                                    <td class="px-4 py-3 text-xs font-semibold text-orange-600">- FCFA</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab: Rapport utilisateurs -->
                <div class="report-tab-content hidden p-5" data-tab="users">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <input type="date" class="px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                            <span class="text-xs text-gray-400">à</span>
                            <input type="date" class="px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
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
                            <p class="text-xl font-bold text-[#0F172A]">-</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl border border-emerald-100">
                            <p class="text-[10px] text-emerald-600 font-semibold uppercase mb-1">Utilisateurs actifs</p>
                            <p class="text-xl font-bold text-[#0F172A]">-</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                            <p class="text-[10px] text-purple-600 font-semibold uppercase mb-1">Acheteurs</p>
                            <p class="text-xl font-bold text-[#0F172A]">-</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl border border-amber-100">
                            <p class="text-[10px] text-amber-600 font-semibold uppercase mb-1">Taux rétention</p>
                            <p class="text-xl font-bold text-[#0F172A]">-%</p>
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
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">-</td>
                                    <td class="px-4 py-3 text-xs font-semibold text-blue-600">-</td>
                                    <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">-</td>
                                    <td class="px-4 py-3 text-xs font-semibold text-emerald-600">-</td>
                                    <td class="px-4 py-3 text-xs font-semibold text-red-600">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab: Rapport vendeurs -->
                <div class="report-tab-content hidden p-5" data-tab="vendors">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <input type="date" class="px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                            <span class="text-xs text-gray-400">à</span>
                            <input type="date" class="px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
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
                            <p class="text-xl font-bold text-[#0F172A]">-</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                            <p class="text-[10px] text-blue-600 font-semibold uppercase mb-1">Nouveaux vendeurs</p>
                            <p class="text-xl font-bold text-[#0F172A]">-</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                            <p class="text-[10px] text-purple-600 font-semibold uppercase mb-1">Produits publiés</p>
                            <p class="text-xl font-bold text-[#0F172A]">-</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl border border-amber-100">
                            <p class="text-[10px] text-amber-600 font-semibold uppercase mb-1">Revenu moyen</p>
                            <p class="text-xl font-bold text-[#0F172A]">- FCFA</p>
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
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-lg flex items-center justify-center text-emerald-500">
                                                <i class="fas fa-store text-xs"></i>
                                            </div>
                                            <span class="text-xs font-semibold text-[#0F172A]">-</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">-</td>
                                    <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">-</td>
                                    <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">- FCFA</td>
                                    <td class="px-4 py-3 text-xs font-semibold text-[#0EA486]">- FCFA</td>
                                    <td class="px-4 py-3">
                                        <span class="text-xs font-semibold text-amber-600">
                                            <i class="fas fa-star text-[10px]"></i> -
                                        </span>
                                    </td>
                                </tr>
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

        // Chart.js - Graphique inscriptions (vide)
        (function() {
            const ctx = document.getElementById('inscriptionsChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
                    datasets: [{
                        label: 'Inscriptions',
                        data: [0, 0, 0, 0, 0, 0, 0],
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#3B82F6',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: { font: { size: 10 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });
        })();

        // Chart.js - Graphique ventes (vide)
        (function() {
            const ctx = document.getElementById('ventesChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
                    datasets: [
                        {
                            label: 'CA (FCFA)',
                            data: [0, 0, 0, 0, 0, 0, 0],
                            borderColor: '#0EA486',
                            backgroundColor: 'rgba(14, 164, 134, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#0EA486',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Ventes',
                            data: [0, 0, 0, 0, 0, 0, 0],
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            fill: false,
                            tension: 0.4,
                            pointBackgroundColor: '#3B82F6',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: { font: { size: 10 } },
                            position: 'left'
                        },
                        y1: {
                            beginAtZero: true,
                            grid: { display: false },
                            ticks: { font: { size: 10 } },
                            position: 'right'
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });
        })();

        // Chart.js - Graphique catégories (vide)
        (function() {
            const ctx = document.getElementById('categoriesChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['WordPress', 'HTML', 'PHP', 'React', 'PSD', 'Plugin'],
                    datasets: [{
                        data: [0, 0, 0, 0, 0, 0],
                        backgroundColor: [
                            '#3B82F6',
                            '#F97316',
                            '#8B5CF6',
                            '#06B6D4',
                            '#EC4899',
                            '#F59E0B'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                font: { size: 10 },
                                padding: 10
                            }
                        }
                    }
                }
            });
        })();

        // Chart.js - Graphique géographique (vide)
        (function() {
            const ctx = document.getElementById('geoChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Sénégal', 'Côte d\'Ivoire', 'Cameroun', 'Mali', 'Autres'],
                    datasets: [{
                        label: 'Acheteurs (%)',
                        data: [0, 0, 0, 0, 0],
                        backgroundColor: [
                            '#0EA486',
                            '#0EA486',
                            '#0EA486',
                            '#0EA486',
                            '#0EA486'
                        ],
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: { font: { size: 10 } }
                        },
                        y: {
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
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


    </script>
</body>
</html>