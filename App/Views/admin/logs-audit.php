<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NDIGITMARKET Admin · Logs & Audit Trail</title>
    <link rel="icon" type="image/png" href="/back-end/public/assets/images/favi.png">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../public/assets/CSS/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

     
</head>
<style>
    /* Dans ton fichier CSS ou dans une balise style */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.95) translateY(-10px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.animate-fadeIn {
    animation: fadeIn 0.25s ease-out;
}
</style>
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
                    <h2 class="text-xl md:text-2xl font-bold text-[#0F172A]">Logs & Audit Trail</h2>
                    <p class="text-xs text-gray-400 hidden sm:block">Journal d'audit et traçabilité des actions</p>
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

        <!-- STATISTIQUES AUDIT -->
        <section class="mb-6">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-chart-bar text-[#0EA486]"></i> · Vue d'ensemble de l'activité
                </h3>
                <div class="flex gap-2">
                    <button id="openRetentionBtn" class="px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2">
                        <i class="fas fa-database"></i> Rétention
                    </button>
                    <button id="exportLogsBtn" class="px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2">
                        <i class="fas fa-file-csv"></i> Export CSV
                    </button>
                </div>
            </div>

            <!-- Cartes statistiques -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                <!-- Total actions -->
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                            <i class="fas fa-history"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-full">TOTAL</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= number_format($stats['total'] ?? 0, 0, ',', ' ') ?></p>
                    <p class="text-xs text-gray-400 mt-1">Actions enregistrées</p>
                </div>

                <!-- Connexions -->
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">CONNEXIONS</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= number_format($stats['connexions'] ?? 0, 0, ',', ' ') ?></p>
                    <p class="text-xs text-gray-400 mt-1">Connexions admin</p>
                </div>

                <!-- Modifications -->
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                            <i class="fas fa-edit"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">MODIFS</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= number_format($stats['modifications'] ?? 0, 0, ',', ' ') ?></p>
                    <p class="text-xs text-gray-400 mt-1">Modifications</p>
                </div>

                <!-- Critiques / Alertes -->
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center text-red-600">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-full">ALERTES</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= number_format($stats['critiques'] ?? 0, 0, ',', ' ') ?></p>
                    <p class="text-xs text-gray-400 mt-1">Actions critiques</p>
                </div>
            </div>

             <!-- Graphique activité -->        
                            <!-- Graphique activité -->        
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-sm font-bold text-[#0F172A] flex items-center gap-2">
                                <i class="fas fa-chart-bar text-[#0EA486]"></i> Activité des 7 derniers jours
                            </h4>
                            <p class="text-[11px] text-gray-400 mt-0.5">Nombre d'actions par jour</p>
                        </div>
                        <div class="flex items-center gap-3 text-xs">
                            <span class="flex items-center gap-2">
                                <span class="w-3 h-3 bg-red-500 rounded"></span> Critique
                            </span>
                            <span class="flex items-center gap-2">
                                <span class="w-3 h-3 bg-amber-500 rounded"></span> Avertissement
                            </span>
                            <span class="flex items-center gap-2">
                                <span class="w-3 h-3 bg-emerald-500 rounded"></span> Info
                            </span>
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>
        </section>

        <!-- 4.13 JOURNAL D'AUDIT -->
        <section class="mb-8">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-list-alt text-[#0EA486]"></i> · Journal d'audit
                </h3>
            </div>

            <!-- Filtres -->
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm mb-4">
                <div class="flex flex-wrap items-center gap-3">
                    <!-- Recherche -->
                    <div class="flex-1 min-w-[220px] relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" id="searchLogs" 
                            placeholder="Rechercher dans les logs (admin, action, IP...)" 
                            class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white transition">
                    </div>
                    
                    <!-- Type d'action -->
                    <!-- Administrateur -->
                    <select id="filterAdmin" class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 focus:outline-none focus:border-[#0EA486]">
                        <option value="">Tous les administrateurs</option>
                        <?php foreach ($admins as $admin): ?>
                            <option value="<?= $admin['id_gestion'] ?>" <?= (isset($filters['admin_id']) && $filters['admin_id'] == $admin['id_gestion']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($admin['nom']) ?> (<?= htmlspecialchars($admin['email']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Type d'action -->
                    <select id="filterAction" class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 focus:outline-none focus:border-[#0EA486]">
                        <option value="">Tous les types d'actions</option>
                        <?php foreach ($actionTypes as $action): ?>
                            <option value="<?= htmlspecialchars($action) ?>" <?= (isset($filters['action']) && $filters['action'] == $action) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($action) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Niveau -->
                    <select id="filterLevel" class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 focus:outline-none focus:border-[#0EA486]">
                        <option value="">Tous les niveaux</option>
                        <option value="info" <?= (isset($filters['level']) && $filters['level'] == 'info') ? 'selected' : '' ?>>Info</option>
                        <option value="warning" <?= (isset($filters['level']) && $filters['level'] == 'warning') ? 'selected' : '' ?>>Warning</option>
                        <option value="critical" <?= (isset($filters['level']) && $filters['level'] == 'critical') ? 'selected' : '' ?>>Critical</option>
                    </select>

                    <!-- Période -->
                    <select id="filterPeriod" class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 focus:outline-none focus:border-[#0EA486]">
                        <option value="">Toutes les périodes</option>
                        <option value="today" <?= (isset($filters['period']) && $filters['period'] == 'today') ? 'selected' : '' ?>>Aujourd'hui</option>
                        <option value="7days" <?= (isset($filters['period']) && $filters['period'] == '7days') ? 'selected' : '' ?>>7 derniers jours</option>
                        <option value="30days" <?= (isset($filters['period']) && $filters['period'] == '30days') ? 'selected' : '' ?>>30 derniers jours</option>
                        <option value="month" <?= (isset($filters['period']) && $filters['period'] == 'month') ? 'selected' : '' ?>>Ce mois</option>
                        <option value="custom">Personnalisé</option>
                    </select>
                    
                    <!-- Boutons -->
                    <button id="applyFilters" class="px-3 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-medium flex items-center gap-2 transition">
                        <i class="fas fa-search"></i> Filtrer
                    </button>
                    <button id="resetFilters" class="px-3 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm flex items-center gap-2 transition">
                        <i class="fas fa-redo"></i> Réinitialiser
                    </button>
                </div>
            </div>

            <!-- Tableau des logs -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-3">Date & Heure</th>
                                <th class="px-4 py-3">Administrateur</th>
                                <th class="px-4 py-3">Type d'action</th>
                                <th class="px-4 py-3">Description</th>
                                <th class="px-4 py-3">IP</th>
                                <th class="px-4 py-3">Niveau</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if (!empty($logs)): ?>
                                <?php foreach ($logs as $log): ?>
                                    <?php 
                                    // Couleurs selon le niveau
                                    $levelColors = [
                                        'critical' => 'bg-red-100 text-red-700',
                                        'warning' => 'bg-yellow-100 text-yellow-700',
                                        'info' => 'bg-blue-100 text-blue-700'
                                    ];
                                    $levelColor = $levelColors[$log['level'] ?? 'info'] ?? 'bg-gray-100 text-gray-700';
                                    
                                    // Couleurs de statut
                                    $statusClass = ($log['status'] ?? 'success') === 'failed' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700';
                                    $statusIcon = ($log['status'] ?? 'success') === 'failed' ? 'fa-times' : 'fa-check';
                                    ?>
                                    <tr class="hover:bg-gray-50/50 transition <?= ($log['level'] ?? '') === 'critical' ? 'bg-red-50/20' : '' ?>">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <p class="text-xs font-semibold text-[#0F172A]">
                                                <?= date('d/m/Y', strtotime($log['created_at'])) ?>
                                            </p>
                                            <p class="text-[10px] text-gray-400">
                                                <?= date('H:i:s', strtotime($log['created_at'])) ?>
                                            </p>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <div class="w-7 h-7 bg-[#0EA486] rounded-full flex items-center justify-center text-white text-[10px] font-semibold">
                                                    <?= strtoupper(substr($log['admin_name'] ?? '?', 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-medium text-[#0F172A]">
                                                        <?= htmlspecialchars($log['admin_name'] ?? 'Inconnu') ?>
                                                    </p>
                                                    <p class="text-[10px] text-gray-400">
                                                        <?= htmlspecialchars($log['admin_email'] ?? '-') ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-[10px] font-semibold <?= $levelColor ?> px-2 py-1 rounded-full inline-flex items-center gap-1">
                                                <?= htmlspecialchars(str_replace('_', ' ', $log['action'] ?? 'Action')) ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <p class="text-xs text-gray-600 max-w-xs truncate">
                                                <?= htmlspecialchars($log['action_description'] ?? $log['details'] ?? '-') ?>
                                            </p>
                                            <?php if (!empty($log['entity_id'])): ?>
                                                <p class="text-[10px] text-gray-400">ID: <?= htmlspecialchars($log['entity_id']) ?></p>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-xs font-mono text-gray-600">
                                                <?= htmlspecialchars($log['ip_address'] ?? '-') ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-[10px] font-semibold <?= $levelColor ?> px-2 py-1 rounded-full capitalize">
                                                <?= $log['level'] ?? 'info' ?>
                                            </span>
                                            <span class="text-[10px] font-semibold <?= $statusClass ?> px-2 py-1 rounded-full ml-1 inline-flex items-center gap-0.5">
                                                <i class="fas <?= $statusIcon ?> text-[8px]"></i>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-end gap-1">
                                                <button class="openLogDetailBtn w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition" 
                                                        data-id="<?= $log['id'] ?>"
                                                        title="Voir détail">
                                                    <i class="fas fa-eye text-xs"></i>
                                                </button>
                                                <?php if (!empty($log['ip_address'])): ?>
                                                    <button class="blockIpBtn w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition" 
                                                            data-ip="<?= htmlspecialchars($log['ip_address']) ?>"
                                                            title="Bloquer IP">
                                                        <i class="fas fa-ban text-xs"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                                        <i class="fas fa-inbox text-3xl block mb-2"></i>
                                        <p class="text-sm">Aucun log trouvé</p>
                                        <p class="text-xs mt-1">Essayez de modifier vos filtres</p>
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
                        <select id="limitPerPage" class="px-2 py-1 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-[#0EA486]">
                            <option value="25" <?= $limit == 25 ? 'selected' : '' ?>>25</option>
                            <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                            <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100</option>
                            <option value="500" <?= $limit == 500 ? 'selected' : '' ?>>500</option>
                        </select>
                        <span>résultats par page</span>
                    </div>
                    <div class="flex items-center gap-1 text-xs text-gray-500">
                        <?php 
                        $totalPages = ceil($total / $limit);
                        $from = ($page - 1) * $limit + 1;
                        $to = min($page * $limit, $total);
                        ?>
                        <span><?= $from ?> - <?= $to ?> sur <?= $total ?></span>
                        
                        <?php if ($page > 1): ?>
                            <a href="?url=logs-audit&page=<?= $page - 1 ?><?= !empty($filters) ? '&' . http_build_query($filters) : '' ?>" 
                            class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition">
                                <i class="fas fa-chevron-left text-[10px]"></i>
                            </a>
                        <?php else: ?>
                            <button class="w-8 h-8 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center cursor-not-allowed opacity-50">
                                <i class="fas fa-chevron-left text-[10px]"></i>
                            </button>
                        <?php endif; ?>
                        
                        <?php 
                        $startPage = max(1, $page - 2);
                        $endPage = min($totalPages, $page + 2);
                        
                        if ($startPage > 1): ?>
                            <a href="?url=logs-audit&page=1<?= !empty($filters) ? '&' . http_build_query($filters) : '' ?>" 
                            class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition">1</a>
                            <?php if ($startPage > 2): ?>
                                <span class="px-2 text-gray-400">...</span>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <?php if ($i == $page): ?>
                                <span class="w-8 h-8 rounded-lg bg-[#0EA486] text-white flex items-center justify-center"><?= $i ?></span>
                            <?php else: ?>
                                <a href="?url=logs-audit&page=<?= $i ?><?= !empty($filters) ? '&' . http_build_query($filters) : '' ?>" 
                                class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition"><?= $i ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        <?php if ($endPage < $totalPages): ?>
                            <?php if ($endPage < $totalPages - 1): ?>
                                <span class="px-2 text-gray-400">...</span>
                            <?php endif; ?>
                            <a href="?url=logs-audit&page=<?= $totalPages ?><?= !empty($filters) ? '&' . http_build_query($filters) : '' ?>" 
                            class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition"><?= $totalPages ?></a>
                        <?php endif; ?>
                        
                        <?php if ($page < $totalPages): ?>
                            <a href="?url=logs-audit&page=<?= $page + 1 ?><?= !empty($filters) ? '&' . http_build_query($filters) : '' ?>" 
                            class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition">
                                <i class="fas fa-chevron-right text-[10px]"></i>
                            </a>
                        <?php else: ?>
                            <button class="w-8 h-8 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center cursor-not-allowed opacity-50">
                                <i class="fas fa-chevron-right text-[10px]"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <footer class="mt-8 text-center text-xs text-gray-400 border-t border-gray-100 pt-6">
            &copy; 2026 NDIGITMARKET · Administration
        </footer>
    </main>


   <!-- MODAL : CONFIRMATION BLOCAGE IP -->
<div id="blockIpModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-red-50 to-transparent">
            <div>
                <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                    <i class="fas fa-shield-alt text-red-600"></i> Confirmation de blocage
                </h3>
                <p class="text-xs text-gray-400">Blocage d'une adresse IP</p>
            </div>
            <button class="closeBlockIpBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div class="bg-red-50 rounded-xl p-4 border border-red-200 flex items-start gap-3">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl mt-0.5"></i>
                <div>
                    <p class="text-sm font-semibold text-red-800"> Action irréversible</p>
                    <p class="text-xs text-red-600 mt-1">Le blocage d'une IP empêchera tout accès depuis cette adresse.</p>
                </div>
            </div>

            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-gray-500">Adresse IP ciblée</span>
                    <span class="text-sm font-mono font-bold text-[#0F172A] bg-white px-3 py-1 rounded-lg border border-gray-200" id="blockIpAddress">-</span>
                </div>
            </div>

            <div>
                <label class="text-xs font-semibold text-gray-600 mb-1 block">Raison du blocage <span class="text-gray-400">(optionnel)</span></label>
                <textarea id="blockIpReason" rows="2" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-200 transition resize-none" placeholder="Ex: Tentatives de connexion frauduleuses..."></textarea>
            </div>

            <div class="bg-yellow-50 rounded-xl p-3 border border-yellow-200 flex items-start gap-2">
                <i class="fas fa-info-circle text-yellow-600 mt-0.5 text-sm"></i>
                <p class="text-xs text-yellow-700">
                    L'utilisateur concerné recevra un message d'accès refusé.<br>
                    Vous pourrez débloquer cette IP depuis les paramètres de sécurité.
                </p>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
            <button class="closeBlockIpBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium transition">
                Annuler
            </button>
            <button id="confirmBlockIpBtn" class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                <i class="fas fa-ban"></i> Bloquer définitivement
            </button>
        </div>
    </div>
</div>


<!-- MODAL : DÉTAIL D'UN LOG -->
<div id="logDetailModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl max-h-[92vh] overflow-hidden flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#0EA486]/5 to-transparent">
            <div>
                <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                    <i class="fas fa-info-circle text-[#0EA486]"></i> Détail de l'action
                </h3>
                <p class="text-xs text-gray-400">Informations complètes de l'événement</p>
            </div>
            <button class="closeLogDetailBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="overflow-y-auto p-6 space-y-5">
            <!-- En-tête -->
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-5 border border-indigo-100">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 bg-[#0EA486] rounded-2xl flex items-center justify-center text-white">
                            <i class="fas fa-history text-xl"></i>
                        </div>
                        <div>
                            <h4 class="log-title text-lg font-bold text-[#0F172A]">...</h4>
                            <p class="text-xs text-gray-500 mt-1">ID Log: <span class="log-id font-mono">...</span></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="log-level-badge text-[10px] font-semibold px-2 py-1 rounded-full">Info</span>
                        <p class="log-date-badge text-xs text-gray-500 mt-1">...</p>
                    </div>
                </div>
            </div>

            <!-- Infos principales -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Administrateur -->
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-user-shield text-[#0EA486]"></i> Administrateur
                    </h5>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-gray-500">Nom</span>
                            <span class="log-admin-name font-medium text-[#0F172A]">...</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-gray-500">Email</span>
                            <span class="log-admin-email font-medium text-[#0F172A]">...</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-gray-500">Rôle</span>
                            <span class="log-admin-role font-medium text-[#0F172A]">...</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-gray-500">ID</span>
                            <span class="log-admin-id font-mono font-medium text-[#0F172A]">...</span>
                        </div>
                    </div>
                </div>

                <!-- Temps -->
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-clock text-[#0EA486]"></i> Informations temporelles
                    </h5>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-gray-500">Date</span>
                            <span class="log-date font-medium text-[#0F172A]">...</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-gray-500">Heure</span>
                            <span class="log-time font-medium text-[#0F172A]">...</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-gray-500">Fuseau</span>
                            <span class="log-timezone font-medium text-[#0F172A]">...</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-gray-500">Il y a</span>
                            <span class="log-ago font-medium text-[#0F172A]">...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails de l'action -->
            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                    <i class="fas fa-bolt text-[#0EA486]"></i> Détails de l'action
                </h5>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-500">Type d'action</span>
                        <span class="log-action-type font-semibold text-[#0F172A]">...</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-500">Description</span>
                        <span class="log-action-desc font-medium text-[#0F172A] text-right max-w-[300px]">...</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-500">Entité concernée</span>
                        <span class="log-entity-type font-mono font-medium text-[#0F172A]">...</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-500">ID Entité</span>
                        <span class="log-entity-id font-mono font-medium text-[#0F172A]">...</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-500">Résultat</span>
                        <span class="log-result font-semibold">...</span>
                    </div>
                </div>
            </div>

            <!-- Informations réseau -->
            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                    <i class="fas fa-network-wired text-[#0EA486]"></i> Informations réseau
                </h5>
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <p class="text-[10px] text-gray-400 mb-1">Adresse IP</p>
                        <p class="log-ip text-sm font-mono font-semibold text-[#0F172A]">...</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <p class="text-[10px] text-gray-400 mb-1">Localisation</p>
                        <p class="log-location text-sm font-semibold text-[#0F172A]">...</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <p class="text-[10px] text-gray-400 mb-1">Navigateur</p>
                        <p class="log-browser text-sm font-semibold text-[#0F172A]">...</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <p class="text-[10px] text-gray-400 mb-1">Système</p>
                        <p class="log-os text-sm font-semibold text-[#0F172A]">...</p>
                    </div>
                </div>
            </div>

            <!-- Métadonnées -->
            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                    <i class="fas fa-code text-[#0EA486]"></i> Métadonnées JSON
                </h5>
                <pre class="log-metadata text-xs text-gray-600 font-mono bg-gray-50 rounded-xl p-3 overflow-x-auto whitespace-pre-wrap">...</pre>
            </div>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
            <button class="closeLogDetailBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                Fermer
            </button>
            <button class="log-export-btn px-4 py-2.5 rounded-xl bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-semibold flex items-center gap-2">
                <i class="fas fa-download"></i> Exporter
            </button>
        </div>
    </div>
</div>


    <!-- MODAL : CONFIGURATION RÉTENTION -->
    <div id="retentionModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-database text-[#0EA486]"></i> Configuration de la rétention
                    </h3>
                    <p class="text-xs text-gray-400">Durée de conservation des logs</p>
                </div>
                <button class="closeRetentionBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                    <p class="text-sm text-blue-700">
                        <i class="fas fa-info-circle mr-2"></i>
                        Les logs plus anciens que la durée définie seront automatiquement supprimés pour économiser de l'espace.
                    </p>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Durée de rétention <span class="text-red-500">*</span></label>
                    <select class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                        <option>3 mois</option>
                        <option>6 mois</option>
                        <option selected>12 mois (par défaut)</option>
                        <option>18 mois</option>
                        <option>24 mois</option>
                        <option>36 mois</option>
                        <option>Illimitée</option>
                    </select>
                    <p class="text-[10px] text-gray-400 mt-1">Recommandation légale : minimum 12 mois</p>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Actions critiques</label>
                    <select class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                        <option>Même durée que les autres logs</option>
                        <option selected>Conservation illimitée</option>
                        <option>Durée personnalisée</option>
                    </select>
                    <p class="text-[10px] text-gray-400 mt-1">Les actions critiques (suppressions, modifications paramètres) peuvent être conservées plus longtemps</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-200">
                        <p class="text-[10px] text-gray-400 mb-1">Logs actuels</p>
                        <p class="text-lg font-bold text-[#0F172A]">...</p>
                        <p class="text-[10px] text-gray-500">entrées</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-200">
                        <p class="text-[10px] text-gray-400 mb-1">Espace utilisé</p>
                        <p class="text-lg font-bold text-[#0F172A]">... MB</p>
                        <p class="text-[10px] text-gray-500">base de données</p>
                    </div>
                </div>

                <div class="bg-yellow-50 rounded-xl p-3 border border-yellow-200 flex items-start gap-2">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5"></i>
                    <p class="text-xs text-yellow-700">La réduction de la durée de rétention supprimera définitivement les logs plus anciens.</p>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                <button class="closeRetentionBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                    Annuler
                </button>
                <button id="saveRetentionBtn" class="px-5 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL : EXPORT CSV -->
    <div id="exportModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-file-csv text-[#0EA486]"></i> Exporter les logs
                    </h3>
                    <p class="text-xs text-gray-400">Choisir les paramètres d'export</p>
                </div>
                <button class="closeExportBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                    <p class="text-sm text-blue-700">
                        <i class="fas fa-info-circle mr-2"></i>
                        Les logs seront exportés selon les filtres actuellement appliqués.
                    </p>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Période</label>
                    <select class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                        <option>Tous les logs</option>
                        <option>Aujourd'hui</option>
                        <option>7 derniers jours</option>
                        <option>30 derniers jours</option>
                        <option>Ce mois</option>
                        <option>Personnalisé</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Format</label>
                    <div class="grid grid-cols-3 gap-2">
                        <button class="export-format-btn p-3 bg-white border-2 border-[#0EA486] bg-[#0EA486]/5 rounded-xl text-center" data-format="csv">
                            <i class="fas fa-file-csv text-xl text-emerald-500 mb-1"></i>
                            <p class="text-xs font-semibold text-[#0F172A]">CSV</p>
                        </button>
                        <button class="export-format-btn p-3 bg-white border-2 border-gray-200 rounded-xl text-center" data-format="excel">
                            <i class="fas fa-file-excel text-xl text-green-500 mb-1"></i>
                            <p class="text-xs font-semibold text-[#0F172A]">Excel</p>
                        </button>
                        <button class="export-format-btn p-3 bg-white border-2 border-gray-200 rounded-xl text-center" data-format="json">
                            <i class="fas fa-file-code text-xl text-blue-500 mb-1"></i>
                            <p class="text-xs font-semibold text-[#0F172A]">JSON</p>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-2 block">Colonnes à inclure</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center gap-2 text-xs text-gray-700 cursor-pointer">
                            <input type="checkbox" checked class="w-4 h-4 rounded border-gray-300 text-[#0EA486]">
                            <span>Date & Heure</span>
                        </label>
                        <label class="flex items-center gap-2 text-xs text-gray-700 cursor-pointer">
                            <input type="checkbox" checked class="w-4 h-4 rounded border-gray-300 text-[#0EA486]">
                            <span>Administrateur</span>
                        </label>
                        <label class="flex items-center gap-2 text-xs text-gray-700 cursor-pointer">
                            <input type="checkbox" checked class="w-4 h-4 rounded border-gray-300 text-[#0EA486]">
                            <span>Type d'action</span>
                        </label>
                        <label class="flex items-center gap-2 text-xs text-gray-700 cursor-pointer">
                            <input type="checkbox" checked class="w-4 h-4 rounded border-gray-300 text-[#0EA486]">
                            <span>Description</span>
                        </label>
                        <label class="flex items-center gap-2 text-xs text-gray-700 cursor-pointer">
                            <input type="checkbox" checked class="w-4 h-4 rounded border-gray-300 text-[#0EA486]">
                            <span>Adresse IP</span>
                        </label>
                        <label class="flex items-center gap-2 text-xs text-gray-700 cursor-pointer">
                            <input type="checkbox" checked class="w-4 h-4 rounded border-gray-300 text-[#0EA486]">
                            <span>Métadonnées</span>
                        </label>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-3 border border-gray-200">
                    <p class="text-[10px] text-gray-500">
                        <i class="fas fa-database mr-1"></i>
                        <strong>Estimation :</strong> ... entrées · environ ... MB
                    </p>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                <button class="closeExportBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                    Annuler
                </button>
                <button id="confirmExportBtn" class="px-5 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                    <i class="fas fa-download"></i> Télécharger
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

        // Modal rétention
        (function() {
            const modal = document.getElementById('retentionModal');
            const openBtn = document.getElementById('openRetentionBtn');
            const closeBtns = document.querySelectorAll('.closeRetentionBtn');
            const saveBtn = document.getElementById('saveRetentionBtn');

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
                showToast('Rétention configurée', 'La durée de conservation des logs a été mise à jour', 'success');
            });
        })();

        // Modal export
        (function() {
            const modal = document.getElementById('exportModal');
            const openBtn = document.getElementById('exportLogsBtn');
            const closeBtns = document.querySelectorAll('.closeExportBtn');
            const confirmBtn = document.getElementById('confirmExportBtn');
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

            openBtn.addEventListener('click', openModal);
            closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });

            formatBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    formatBtns.forEach(b => {
                        b.classList.remove('border-[#0EA486]', 'bg-[#0EA486]/5');
                        b.classList.add('border-gray-200');
                    });
                    this.classList.add('border-[#0EA486]', 'bg-[#0EA486]/5');
                    this.classList.remove('border-gray-200');
                });
            });

            confirmBtn.addEventListener('click', function() {
                closeModal();
                showToast('Export en cours', 'Le fichier CSV est en cours de génération', 'success');
            });
        })();

        // Auto-refresh simulation
        (function() {
            setInterval(() => {
                // Simulation d'activité en temps réel
            }, 30000);
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


// ============================================
// GRAPHIQUE D'ACTIVITÉ - DONNÉES DEPUIS PHP
// ============================================

//  Récupérer les données PHP
const chartData = <?= json_encode($chartData ?? []) ?>;

console.log(' Données chartData:', chartData);
console.log(' Nombre de jours:', chartData.length);

let activityChart = null;

function createActivityChart(data) {
    const canvas = document.getElementById('activityChart');
    if (!canvas) {
        console.error(' Canvas non trouvé');
        return;
    }
    
    if (activityChart) {
        activityChart.destroy();
        activityChart = null;
    }
    
    if (!data || data.length === 0) {
        console.warn(' Aucune donnée pour le graphique');
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = '#9CA3AF';
        ctx.font = '14px Inter, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('Aucune donnée d\'activité', canvas.width/2, canvas.height/2);
        return;
    }
    
    const ctx = canvas.getContext('2d');
    
    const labels = data.map(item => {
        const date = new Date(item.date);
        return date.toLocaleDateString('fr-FR', { weekday: 'short' });
    });
    
    const criticalData = data.map(item => item.critical || 0);
    const warningData = data.map(item => item.warning || 0);
    const infoData = data.map(item => item.info || 0);
    
    console.log(' Labels:', labels);
    console.log(' Critical:', criticalData);
    console.log(' Warning:', warningData);
    console.log(' Info:', infoData);
    
    activityChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Critique',
                    data: criticalData,
                    backgroundColor: '#EF4444',
                    borderRadius: 4,
                    barPercentage: 0.3,
                    categoryPercentage: 0.8,
                    order: 1
                },
                {
                    label: 'Avertissement',
                    data: warningData,
                    backgroundColor: '#F59E0B',
                    borderRadius: 4,
                    barPercentage: 0.3,
                    categoryPercentage: 0.8,
                    order: 2
                },
                {
                    label: 'Info',
                    data: infoData,
                    backgroundColor: '#0EA486',
                    borderRadius: 4,
                    barPercentage: 0.3,
                    categoryPercentage: 0.8,
                    order: 3
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
                        font: { size: 11, weight: '600' },
                        color: '#0F172A',
                        padding: 15,
                        usePointStyle: true,
                        pointStyle: 'rectRounded',
                        boxWidth: 12,
                        boxHeight: 12
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(255,255,255,0.95)',
                    titleColor: '#0F172A',
                    bodyColor: '#6B7280',
                    borderColor: '#E5E7EB',
                    borderWidth: 1,
                    cornerRadius: 10,
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            const label = context.dataset.label || '';
                            const value = context.parsed.y;
                            return `${label}: ${value}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                    ticks: { font: { size: 11 }, color: '#6B7280', stepSize: 1 },
                    border: { display: false }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 }, color: '#6B7280' },
                    border: { display: false }
                }
            }
        }
    });
    
    console.log(' Graphique d\'activité créé avec 3 niveaux');
}

//  Initialisation
document.addEventListener('DOMContentLoaded', function() {
    console.log(' Initialisation graphique activité');
    createActivityChart(chartData);
});



// ============================================
// GESTION DES FILTRES - LOGS (CORRIGÉ)
// ============================================

(function() {
    'use strict';

    // Éléments du DOM
    const searchInput = document.getElementById('searchLogs');
    const filterAction = document.getElementById('filterAction');
    const filterAdmin = document.getElementById('filterAdmin');
    const filterPeriod = document.getElementById('filterPeriod');
    const filterLevel = document.getElementById('filterLevel');
    const applyBtn = document.getElementById('applyFilters');
    const resetBtn = document.getElementById('resetFilters');

    /**
     * Récupérer les valeurs des filtres
     */
    function getFilters() {
        const filters = {};
        
        if (searchInput.value.trim()) filters.search = searchInput.value.trim();
        if (filterAction.value) filters.action = filterAction.value;
        if (filterAdmin.value) filters.admin_id = filterAdmin.value;
        if (filterPeriod.value) filters.period = filterPeriod.value;
        if (filterLevel.value) filters.level = filterLevel.value;
        
        return filters;
    }

    /**
     * Appliquer les filtres
     */
    function applyFilters() {
        const filters = getFilters();
        let url = window.location.pathname + '?url=logs-audit';
        
        for (const [key, value] of Object.entries(filters)) {
            if (value) {
                url += '&' + key + '=' + encodeURIComponent(value);
            }
        }
        
        url += '&page=1&limit=25';
        window.location.href = url;
    }

    /**
     * Réinitialiser tous les filtres
     */
    function resetFilters() {
        searchInput.value = '';
        filterAction.value = '';
        filterAdmin.value = '';
        filterPeriod.value = '';
        filterLevel.value = '';
        window.location.href = window.location.pathname + '?url=logs-audit';
    }

    // ============================================
    // ÉVÉNEMENTS
    // ============================================

    if (applyBtn) {
        applyBtn.addEventListener('click', function(e) {
            e.preventDefault();
            applyFilters();
        });
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', function(e) {
            e.preventDefault();
            resetFilters();
        });
    }

    // Filtres au changement
    if (filterAction) filterAction.addEventListener('change', applyFilters);
    if (filterAdmin) filterAdmin.addEventListener('change', applyFilters);
    if (filterPeriod) filterPeriod.addEventListener('change', applyFilters);
    if (filterLevel) filterLevel.addEventListener('change', applyFilters);

    // Recherche en temps réel (debounce)
    let searchTimeout = null;
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(applyFilters, 500);
        });
        
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchTimeout);
                applyFilters();
            }
        });
    }

    console.log(' Filtres initialisés');

})();

// ============================================
// GESTION DE LA PAGINATION
// ============================================

(function() {
    'use strict';

    const limitSelect = document.getElementById('limitPerPage');
    
    if (limitSelect) {
        limitSelect.addEventListener('change', function() {
            const limit = this.value;
            const url = new URL(window.location.href);
            url.searchParams.set('limit', limit);
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        });
    }

})();
























































// ============================================
// GESTION DU DÉTAIL DU LOG ET BLOCAGE IP AVEC MODAL
// ============================================

(function() {
    'use strict';

    // ============================================
    // 1. OUVERTURE DU MODAL DE DÉTAIL
    // ============================================
    document.querySelectorAll('.openLogDetailBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            const logId = this.dataset.id;
            if (!logId) return;
            
            const modal = document.getElementById('logDetailModal');
            if (!modal) return;
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            
            showLoadingDetail();
            
            fetch(`/back-end/routes/api.php?url=logs_detail&id=${logId}`)
                .then(response => response.json())
                .then(result => {
                    if (result.success && result.data) {
                        populateDetailModal(result.data);
                    } else {
                        showToast('Erreur', 'Impossible de charger les détails', 'error');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showToast('Erreur', 'Erreur de connexion', 'error');
                });
        });
    });

    // ============================================
    // 2. FERMETURE DU MODAL DE DÉTAIL
    // ============================================
    document.querySelectorAll('.closeLogDetailBtn').forEach(btn => {
        btn.addEventListener('click', closeDetailModal);
    });

    document.getElementById('logDetailModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeDetailModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('logDetailModal');
            if (modal && !modal.classList.contains('hidden')) {
                closeDetailModal();
            }
            const blockModal = document.getElementById('blockIpModal');
            if (blockModal && !blockModal.classList.contains('hidden')) {
                closeBlockIpModal();
            }
        }
    });

    function closeDetailModal() {
        const modal = document.getElementById('logDetailModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
    }

    function showLoadingDetail() {
        const modal = document.getElementById('logDetailModal');
        if (!modal) return;
        
        modal.querySelectorAll('.log-field').forEach(el => {
            el.textContent = '...';
        });
        
        const title = modal.querySelector('.log-title');
        if (title) title.textContent = 'Chargement...';
        
        const idSpan = modal.querySelector('.log-id');
        if (idSpan) idSpan.textContent = '...';
    }

    // ============================================
    // 3. REMPLIR LE MODAL DE DÉTAIL (CORRIGÉ)
    // ============================================
    function populateDetailModal(log) {
        const modal = document.getElementById('logDetailModal');
        if (!modal) {
            console.error('❌ Modal non trouvé');
            return;
        }
        
        console.log('📊 Données du log:', log);
        
        // Niveau - Couleurs
        const levelColors = {
            'critical': 'bg-red-100 text-red-700',
            'warning': 'bg-yellow-100 text-yellow-700',
            'info': 'bg-blue-100 text-blue-700'
        };
        const levelColor = levelColors[log.level] || 'bg-gray-100 text-gray-700';
        
        // Statut
        const statusClass = log.status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700';
        const statusIcon = log.status === 'failed' ? 'fa-times' : 'fa-check';
        const statusText = log.status === 'failed' ? 'Échec' : 'Succès';
        
        // Date
        const createdAt = new Date(log.created_at);
        const dateStr = createdAt.toLocaleDateString('fr-FR', { 
            day: '2-digit', month: '2-digit', year: 'numeric' 
        });
        const timeStr = createdAt.toLocaleTimeString('fr-FR', { 
            hour: '2-digit', minute: '2-digit', second: '2-digit' 
        });
        const timeAgo = getTimeAgo(createdAt);
        
        // ==================== EN-TÊTE ====================
        const titleEl = modal.querySelector('.log-title');
        if (titleEl) titleEl.textContent = log.action_description || log.action || 'Action';
        
        const idEl = modal.querySelector('.log-id');
        if (idEl) idEl.textContent = '#' + log.id;
        
        const levelBadge = modal.querySelector('.log-level-badge');
        if (levelBadge) {
            levelBadge.className = `log-level-badge text-[10px] font-semibold ${levelColor} px-2 py-1 rounded-full`;
            levelBadge.textContent = log.level || 'info';
        }
        
        const dateBadge = modal.querySelector('.log-date-badge');
        if (dateBadge) dateBadge.textContent = dateStr + ' ' + timeStr;
        
        // ==================== ADMINISTRATEUR ====================
        const adminName = modal.querySelector('.log-admin-name');
        if (adminName) adminName.textContent = log.admin_name || 'Inconnu';
        
        const adminEmail = modal.querySelector('.log-admin-email');
        if (adminEmail) adminEmail.textContent = log.admin_email || '-';
        
        const adminRole = modal.querySelector('.log-admin-role');
        if (adminRole) adminRole.textContent = log.role || 'Admin';
        
        const adminId = modal.querySelector('.log-admin-id');
        if (adminId) adminId.textContent = log.admin_id || '-';
        
        // ==================== TEMPS ====================
        const dateField = modal.querySelector('.log-date');
        if (dateField) dateField.textContent = dateStr;
        
        const timeField = modal.querySelector('.log-time');
        if (timeField) timeField.textContent = timeStr;
        
        const timezoneField = modal.querySelector('.log-timezone');
        if (timezoneField) timezoneField.textContent = 'UTC+1';
        
        const agoField = modal.querySelector('.log-ago');
        if (agoField) agoField.textContent = timeAgo;
        
        // ==================== DÉTAILS ACTION ====================
        const actionType = modal.querySelector('.log-action-type');
        if (actionType) actionType.textContent = log.action || '-';
        
        const actionDesc = modal.querySelector('.log-action-desc');
        if (actionDesc) actionDesc.textContent = log.action_description || log.details || '-';
        
        const entityType = modal.querySelector('.log-entity-type');
        if (entityType) entityType.textContent = log.entity_type || '-';
        
        const entityId = modal.querySelector('.log-entity-id');
        if (entityId) entityId.textContent = log.entity_id || '-';
        
        const resultField = modal.querySelector('.log-result');
        if (resultField) {
            resultField.className = `log-result font-semibold ${statusClass}`;
            resultField.innerHTML = `<i class="fas ${statusIcon}"></i> ${statusText}`;
        }
        
        // ==================== RÉSEAU ====================
        const ipField = modal.querySelector('.log-ip');
        if (ipField) ipField.textContent = log.ip_address || '-';
        
        // LOCALISATION (simulée)
        const locationField = modal.querySelector('.log-location');
        if (locationField) {
            const locations = ['Paris, France', 'Dakar, Sénégal', 'Abidjan, Côte d\'Ivoire', 'Douala, Cameroun', 'Bamako, Mali'];
            const randomLocation = locations[Math.floor(Math.random() * locations.length)];
            locationField.textContent = log.ip_address ? randomLocation : '-';
        }
        
        // NAVIGATEUR (extrait du user agent)
        const browserField = modal.querySelector('.log-browser');
        if (browserField) {
            const ua = log.user_agent || '';
            let browser = 'Inconnu';
            if (ua.includes('Chrome')) browser = 'Chrome';
            else if (ua.includes('Firefox')) browser = 'Firefox';
            else if (ua.includes('Safari')) browser = 'Safari';
            else if (ua.includes('Edge')) browser = 'Edge';
            else if (ua.includes('Opera')) browser = 'Opera';
            browserField.textContent = browser;
        }
        
        // SYSTÈME D'EXPLOITATION (extrait du user agent)
        const osField = modal.querySelector('.log-os');
        if (osField) {
            const ua = log.user_agent || '';
            let os = 'Inconnu';
            if (ua.includes('Windows')) os = 'Windows';
            else if (ua.includes('Mac')) os = 'macOS';
            else if (ua.includes('Linux')) os = 'Linux';
            else if (ua.includes('Android')) os = 'Android';
            else if (ua.includes('iOS') || ua.includes('iPhone') || ua.includes('iPad')) os = 'iOS';
            osField.textContent = os;
        }
        
        // USER AGENT complet (dans une section séparée)
        const userAgentField = modal.querySelector('.log-user-agent');
        if (userAgentField) {
            const ua = log.user_agent || '';
            userAgentField.textContent = ua.length > 60 ? ua.substring(0, 60) + '...' : ua || '-';
        }
        
                // ==================== MÉTADONNÉES ====================
        const metadataPre = modal.querySelector('.log-metadata');
        if (metadataPre) {
            // 🔥 Construire un objet avec les données disponibles
            const metaData = {
                "ID du log": log.id || '-',
                "Action": log.action || '-',
                "Description": log.action_description || log.details || '-',
                "Type d'entité": log.entity_type || '-',
                "ID entité": log.entity_id || '-',
                "Admin ID": log.admin_id || '-',
                "Email": log.admin_email || '-',
                "IP": log.ip_address || '-',
                "Niveau": log.level || 'info',
                "Statut": log.status || 'success',
                "Date": log.created_at || '-'
            };
            
            // 🔥 Ajouter target_user_id si présent
            if (log.target_user_id) {
                metaData["ID utilisateur cible"] = log.target_user_id;
            }
            
            // 🔥 Afficher en JSON formaté
            metadataPre.textContent = JSON.stringify(metaData, null, 2);
            
            console.log('📊 Métadonnées affichées:', metaData);
        }
        // ==================== BOUTON EXPORT ====================
        const exportBtn = modal.querySelector('.log-export-btn');
        if (exportBtn) {
            exportBtn.dataset.id = log.id;
        }
        
        console.log('✅ Modal rempli avec succès');
    }

    // ============================================
    // 4. TEMPS RELATIF
    // ============================================
    function getTimeAgo(date) {
        const now = new Date();
        const diff = now - date;
        const seconds = Math.floor(diff / 1000);
        const minutes = Math.floor(seconds / 60);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);
        
        if (days > 0) return `Il y a ${days} jour${days > 1 ? 's' : ''}`;
        if (hours > 0) return `Il y a ${hours} heure${hours > 1 ? 's' : ''}`;
        if (minutes > 0) return `Il y a ${minutes} minute${minutes > 1 ? 's' : ''}`;
        return 'À l\'instant';
    }

    // ============================================
    // 5. BLOCAGE IP AVEC MODAL DE CONFIRMATION
    // ============================================
    let currentBlockIp = '';

    // OUVERTURE DU MODAL DE BLOCAGE
    document.querySelectorAll('.blockIpBtn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const ip = this.dataset.ip;
            if (!ip) {
                showToast('Erreur', 'IP non trouvée', 'error');
                return;
            }
            
            currentBlockIp = ip;
            
            // Mettre à jour l'IP dans le modal
            const ipDisplay = document.getElementById('blockIpAddress');
            if (ipDisplay) {
                ipDisplay.textContent = ip;
            }
            
            // Ouvrir le modal
            const modal = document.getElementById('blockIpModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        });
    });

    // FERMETURE DU MODAL DE BLOCAGE
    function closeBlockIpModal() {
        const modal = document.getElementById('blockIpModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
        const reasonField = document.getElementById('blockIpReason');
        if (reasonField) {
            reasonField.value = '';
        }
        currentBlockIp = '';
    }

    document.querySelectorAll('.closeBlockIpBtn').forEach(btn => {
        btn.addEventListener('click', closeBlockIpModal);
    });

    document.getElementById('blockIpModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeBlockIpModal();
        }
    });

    // CONFIRMATION ET BLOCAGE
    document.getElementById('confirmBlockIpBtn')?.addEventListener('click', function() {
        const ip = currentBlockIp;
        if (!ip) {
            showToast('Erreur', 'Aucune IP à bloquer', 'error');
            return;
        }
        
        const reason = document.getElementById('blockIpReason')?.value.trim() || 'Tentatives suspectes';
        
        // Désactiver le bouton
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Blocage en cours...';
        
        const formData = new FormData();
        formData.append('ip', ip);
        formData.append('reason', reason);
        
        fetch('/back-end/routes/api.php?url=logs_block_ip', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            closeBlockIpModal();
            
            if (data.success) {
                showToast('Succès', `✅ IP ${ip} bloquée avec succès`, 'success');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showToast('Erreur', data.error || data.message || 'Impossible de bloquer l\'IP', 'error');
            }
        })
        .catch(error => {
            closeBlockIpModal();
            console.error('Erreur:', error);
            showToast('Erreur', 'Erreur de connexion au serveur', 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-ban"></i> Bloquer définitivement';
        });
    });

    // ============================================
    // 6. EXPORT LOG
    // ============================================
    // ============================================
// EXPORT D'UN LOG INDIVIDUEL
// ============================================

document.querySelector('.log-export-btn')?.addEventListener('click', function() {
    const logId = this.dataset.id;
    if (!logId) return;
    
    // Récupérer les détails du log
    fetch(`/back-end/routes/api.php?url=logs_detail&id=${logId}`)
        .then(response => response.json())
        .then(result => {
            if (result.success && result.data) {
                // Exporter en JSON
                const log = result.data;
                const filename = 'log_' + logId + '_' + new Date().toISOString().slice(0,10);
                
                const jsonData = {
                    exported_at: new Date().toISOString(),
                    log: log
                };
                
                const blob = new Blob([JSON.stringify(jsonData, null, 2)], { type: 'application/json' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = filename + '.json';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
                
                showToast('Succès', `Log #${logId} exporté ✅`, 'success');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showToast('Erreur', 'Impossible d\'exporter le log', 'error');
        });
});

})();

    </script>
</body>
</html>