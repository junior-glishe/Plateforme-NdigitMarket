<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NDIGITMARKET Admin · Dashboard</title>
    <link rel="icon" type="image/png" href="/back-end/public/assets/images/favi.png">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/back-end/public/assets/CSS/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />


</head>

<body>
    <?php $currentPage = 'dashboard';
    require_once __DIR__ . '/../components/sidebar.php'; ?>



    <main class="md:ml-[280px] min-h-screen p-4 md:p-8 transition-all">


        <header class="flex items-center justify-between mb-6 bg-white/80 backdrop-blur-sm sticky top-0 z-30 py-4 px-4 md:px-6 rounded-2xl shadow-sm border border-gray-100/50">
            <div class="flex items-center gap-4">
                <button id="hamburgerBtn" class="md:hidden w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 transition flex items-center justify-center text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-[#0F172A]">Tableau de bord</h2>
                    <p class="text-xs text-gray-400 hidden sm:block">Vue d'ensemble de la plateforme</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                
                
                <div class="w-9 h-9 rounded-full bg-[#0EA486] text-white flex items-center justify-center font-semibold text-sm">
                    A
                </div>
            </div>
        </header>


        <section class="mb-6">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-chart-line text-[#0EA486]"></i> Métriques en temps réel
                </h3>
                <div class="flex gap-1 text-xs">
                    <button class="px-3 py-1 rounded-lg bg-[#0EA486] text-white font-medium">Aujourd'hui</button>
                    <button class="px-3 py-1 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200">7j</button>
                    <button class="px-3 py-1 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200">30j</button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="stat-card bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="flex justify-between items-start">
                        <span class="text-sm text-gray-500">CA total</span>
                        <span class="w-8 h-8 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600"><i class="fas fa-euro-sign"></i></span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A] mt-1"><?= $totalRevenueFormatted ?></p>
                    <span class="text-xs text-gray-400"><?= $totalOrders > 0 ? $totalOrders . ' commande' . ($totalOrders > 1 ? 's' : '') : 'Aucune commande' ?></span>
                </div>
                <div class="stat-card bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="flex justify-between items-start">
                        <span class="text-sm text-gray-500">Commandes</span>
                        <span class="w-8 h-8 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600"><i class="fas fa-shopping-bag"></i></span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A] mt-1"><?= $totalOrders ?></p>
                    <span class="text-xs text-gray-400"><?= $totalOrders > 0 ? 'Total des commandes' : 'Aucune commande' ?></span>
                </div>
                <div class="stat-card bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="flex justify-between items-start">
                        <span class="text-sm text-gray-500">Nouveaux users</span>
                        <span class="w-8 h-8 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600"><i class="fas fa-user-plus"></i></span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A] mt-1"><?= $newUsers ?></p>
                    <span class="text-xs text-gray-400"><?= $newUsers > 0 ? 'Inscrits 30 derniers jours' : 'Aucune inscription récente' ?></span>
                </div>
                <div class="stat-card bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="flex justify-between items-start">
                        <span class="text-sm text-gray-500">Commissions (10%)</span>
                        <span class="w-8 h-8 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600"><i class="fas fa-percent"></i></span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A] mt-1"><?= $commissionTotalFormatted ?></p>
                    <span class="text-xs text-gray-400"><?= $totalRevenue > 0 ? '10% du CA total' : 'Aucune commission' ?></span>
                </div>
            </div>

            <!-- Deuxième ligne -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-xs text-gray-500">Nouveaux vendeurs</p>
                    <p class="text-lg font-bold text-[#0F172A]"><?= $vendorRequests ?></p>
                    <span class="text-xs text-gray-400"><?= $vendorRequests > 0 ? 'Demandes en attente' : 'Aucune demande' ?></span>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-xs text-gray-500">Produits en attente</p>
                    <p class="text-lg font-bold text-[#0F172A]"><?= $productsPending ?></p>
                    <span class="text-xs text-gray-400"><?= $productsPending > 0 ? 'Bloqués pour validation' : 'Aucun produit en attente' ?></span>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-xs text-gray-500">Panier moyen</p>
                    <p class="text-lg font-bold text-[#0F172A] mt-1"><?= $averageBasketFormatted ?></p>
                    <span class="text-xs text-gray-400"><?= $averageBasket > 0 ? 'Valeur moyenne panier' : 'Aucune donnée' ?></span>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-xs text-gray-500">Templates actifs</p>
                    <p class="text-lg font-bold text-[#0F172A]"><?= $activeTemplates ?></p>
                    <span class="text-xs text-gray-400"><?= $activeTemplates > 0 ? 'Templates approuvés' : 'Aucun template actif' ?></span>
                </div>
            </div>
        </section>


        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h4 class="text-sm font-semibold text-gray-700 mb-2"><i class="fas fa-chart-line mr-2 text-[#0EA486]"></i> Évolution du CA (30j)</h4>
                <?php if (empty($monthlyRevenue)): ?>
                    <div class="flex flex-col items-center justify-center py-12">
                        <i class="fas fa-chart-line empty-icon"></i>
                        <p class="text-sm text-gray-400 mt-3">Aucune donnée disponible</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-3 mt-4">
                        <?php foreach (array_slice($monthlyRevenue, -6) as $month): ?>
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-xs text-gray-500"><?= htmlspecialchars($month['mois_label']) ?></span>
                                <span class="text-sm font-semibold text-[#0F172A]"><?= htmlspecialchars($month['ca_formatted']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h4 class="text-sm font-semibold text-gray-700 mb-2"><i class="fas fa-chart-bar mr-2 text-[#0EA486]"></i> Commandes / 7 jours</h4>
                <?php if (empty($ordersLast7Days)): ?>
                    <div class="flex flex-col items-center justify-center py-12">
                        <i class="fas fa-chart-bar empty-icon"></i>
                        <p class="text-sm text-gray-400 mt-3">Aucune donnée disponible</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-3 mt-4">
                        <?php foreach ($ordersLast7Days as $day): ?>
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-xs text-gray-500"><?= htmlspecialchars($day['jour_label']) ?></span>
                                <span class="text-sm font-semibold text-[#0F172A]"><?= (int)$day['nb_commandes'] ?> commande<?= (int)$day['nb_commandes'] > 1 ? 's' : '' ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>


        <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h4 class="text-sm font-semibold text-gray-700 mb-2"><i class="fas fa-chart-pie mr-2 text-[#0EA486]"></i> Ventes par catégorie</h4>
                <div class="space-y-3 mt-4">
                    <?php if (empty($categorySales)): ?>
                        <p class="text-sm text-gray-400 text-center py-8">Aucune donnée</p>
                    <?php else: foreach ($categorySales as $cat): ?>
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-xs text-gray-500 truncate"><?= htmlspecialchars($cat['nom_categorie'] ?? 'N/A') ?></span>
                            <span class="text-sm font-semibold text-[#0F172A]"><?= (int)$cat['nb_ventes'] ?></span>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h4 class="text-sm font-semibold text-gray-700 mb-2"><i class="fas fa-trophy mr-2 text-[#0EA486]"></i> Top 5 vendeurs</h4>
                <div class="space-y-3 mt-4">
                    <?php if (empty($topVendors)): ?>
                        <p class="text-sm text-gray-400 text-center py-8">Aucun vendeur</p>
                    <?php else: foreach ($topVendors as $vendor): ?>
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-xs text-gray-500 truncate"><?= htmlspecialchars($vendor['full_name'] ?: 'Vendeur #' . $vendor['id_uti']) ?></span>
                            <span class="text-sm font-semibold text-[#0F172A]"><?= htmlspecialchars($vendor['ca_formatted']) ?></span>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h4 class="text-sm font-semibold text-gray-700 mb-2"><i class="fas fa-star mr-2 text-[#0EA486]"></i> Top 10 templates</h4>
                <div class="space-y-3 mt-4">
                    <?php if (empty($topProducts)): ?>
                        <p class="text-sm text-gray-400 text-center py-8">Aucun template</p>
                    <?php else: foreach ($topProducts as $product): ?>
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-xs text-gray-500 truncate"><?= htmlspecialchars($product['nom_article'] ?? 'N/A') ?></span>
                            <span class="text-sm font-semibold text-[#0F172A]"><?= (int)$product['nb_ventes'] ?> vente<?= (int)$product['nb_ventes'] > 1 ? 's' : '' ?></span>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </section>


        <section class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-8">
            <div class="bg-red-50 border border-red-200 rounded-xl p-3 opacity-60">
                <div class="flex items-center gap-3">
                    <i class="fas fa-clock text-red-400 text-lg"></i>
                    <div>
                        <p class="text-sm font-semibold text-red-400"><?= $productsPending ?> produit<?= $productsPending > 1 ? 's' : '' ?></p>
                        <p class="text-xs text-red-300">en attente</p>
                    </div>
                </div>
            </div>
            <div class="bg-orange-50 border border-orange-200 rounded-xl p-3 opacity-60">
                <div class="flex items-center gap-3">
                    <i class="fas fa-store text-orange-400 text-lg"></i>
                    <div>
                        <p class="text-sm font-semibold text-orange-400"><?= $vendorRequests ?> vendeur<?= $vendorRequests > 1 ? 's' : '' ?></p>
                        <p class="text-xs text-orange-300">en attente</p>
                    </div>
                </div>
            </div>
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 opacity-60">
                <div class="flex items-center gap-3">
                    <i class="fas fa-exclamation-triangle text-yellow-400 text-lg"></i>
                    <div>
                        <p class="text-sm font-semibold text-yellow-400">0 litige</p>
                        <p class="text-xs text-yellow-300">commandes</p>
                    </div>
                </div>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 opacity-60">
                <div class="flex items-center gap-3">
                    <i class="fas fa-envelope text-blue-400 text-lg"></i>
                    <div>
                        <p class="text-sm font-semibold text-blue-400">0 message</p>
                        <p class="text-xs text-blue-300">support</p>
                    </div>
                </div>
            </div>
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3 opacity-60">
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle text-emerald-400 text-lg"></i>
                    <div>
                        <p class="text-sm font-semibold text-emerald-400"><?= count($recentOrders) ?> commande<?= count($recentOrders) > 1 ? 's' : '' ?></p>
                        <p class="text-xs text-emerald-300">récente</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-sm font-semibold text-gray-700"><i class="fas fa-clock mr-2 text-[#0EA486]"></i> Activité récente</h4>
                <span class="text-xs text-gray-400"><?= count($recentActivity) ?> activité<?= count($recentActivity) > 1 ? 's' : '' ?></span>
            </div>
            <?php if (empty($recentActivity)): ?>
                <div class="flex flex-col items-center justify-center py-8">
                    <i class="fas fa-inbox empty-icon"></i>
                    <p class="text-sm text-gray-400 mt-3">Aucune activité récente</p>
                </div>
            <?php else: ?>
                <div class="space-y-2 mt-4">
                    <?php foreach ($recentActivity as $activity): ?>
                        <div class="flex items-center justify-between gap-3 p-3 border-b border-gray-50 last:border-0">
                            <div>
                                <p class="text-sm font-semibold text-[#0F172A]"><?= htmlspecialchars($activity['details'] ?: $activity['action']) ?></p>
                                <p class="text-[11px] text-gray-400"><?= htmlspecialchars($activity['action']) ?></p>
                            </div>
                            <span class="text-[11px] text-gray-500"><?= htmlspecialchars($activity['date_formatted']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <footer class="mt-8 text-center text-xs text-gray-400 border-t border-gray-100 pt-6">
            &copy; 2026 NDIGITMARKET · Administration
        </footer>

    </main>
</body>

</html>
