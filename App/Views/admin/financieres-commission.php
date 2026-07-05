<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NDIGITMARKET Admin · Gestion Financière</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/CSS/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
</head>
<body>
<?php $currentPage = 'financieres-commission'; require_once __DIR__ . '/../components/sidebar.php'; ?>

<main class="md:ml-[280px] min-h-screen p-4 md:p-8 transition-all">

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
            <form data-filter method="get" class="flex gap-2">
                <?php $periode = $_GET['periode'] ?? 'all'; ?>
                <select name="periode" class="px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm">
                    <option value="all" <?= $periode === 'all' ? 'selected' : '' ?>>Toutes les périodes</option>
                    <option value="month" <?= $periode === 'month' ? 'selected' : '' ?>>Ce mois</option>
                    <option value="quarter" <?= $periode === 'quarter' ? 'selected' : '' ?>>Ce trimestre</option>
                    <option value="year" <?= $periode === 'year' ? 'selected' : '' ?>>Cette année</option>
                </select>
            </form>
        </div>
    </header>

    <!-- VUE D'ENSEMBLE -->
    <section class="mb-6">
        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2 mb-4">
            <i class="fas fa-chart-line text-[#0EA486]"></i> · Vue d'ensemble financière
        </h3>

        <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-4">
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600"><i class="fas fa-coins"></i></div>
                    <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">TOTAL</span>
                </div>
                <p class="text-xl font-bold text-[#0F172A]"><?= $totalRevenueFormatted ?></p>
                <p class="text-xs text-gray-400 mt-1">Chiffre d'affaires</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600"><i class="fas fa-wallet"></i></div>
                    <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">NET</span>
                </div>
                <p class="text-xl font-bold text-[#0F172A]"><?= $netPlatformFormatted ?></p>
                <p class="text-xs text-gray-400 mt-1">Revenus nets plateforme</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600"><i class="fas fa-percentage"></i></div>
                    <span class="text-[10px] font-semibold text-purple-600 bg-purple-50 px-2 py-1 rounded-full">10%</span>
                </div>
                <p class="text-xl font-bold text-[#0F172A]"><?= $commissionTotalFormatted ?></p>
                <p class="text-xs text-gray-400 mt-1">Commissions perçues</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600"><i class="fas fa-hand-holding-usd"></i></div>
                    <span class="text-[10px] font-semibold text-orange-600 bg-orange-50 px-2 py-1 rounded-full">VERSÉ</span>
                </div>
                <p class="text-xl font-bold text-[#0F172A]"><?= $this->formatCurrency($commissionRecovered) ?></p>
                <p class="text-xs text-gray-400 mt-1">Commission récupérée</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center text-red-600"><i class="fas fa-clock"></i></div>
                    <span class="text-[10px] font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-full">À PAYER</span>
                </div>
                <p class="text-xl font-bold text-[#0F172A]"><?= $soldeAVerserFormatted ?></p>
                <p class="text-xs text-gray-400 mt-1">Solde à verser</p>
            </div>
        </div>
    </section>

    <!-- RAPPORTS -->
    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                <i class="fas fa-file-alt text-[#0EA486]"></i> · Rapports financiers
            </h3>
            <div class="flex gap-2">
                <button data-open-modal="monthlyReportModal" class="px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2">
                    <i class="fas fa-calendar"></i> Rapport mensuel
                </button>
                <div class="relative">
                    <button data-open-modal="exportModal" class="px-3 py-2 rounded-lg bg-[#0EA486] text-white hover:bg-[#0c8f75] text-xs font-medium flex items-center gap-2">
                        <i class="fas fa-download"></i> Exporter
                    </button>
                </div>
            </div>
        </div>

        <!-- Par catégorie -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-4">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h4 class="text-sm font-semibold text-[#0F172A] flex items-center gap-2">
                    <i class="fas fa-tags text-[#0EA486]"></i> Par catégorie
                </h4>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr class="text-left text-xs font-semibold text-gray-500 uppercase">
                            <th class="px-4 py-3">Catégorie</th>
                            <th class="px-4 py-3">Produits vendus</th>
                            <th class="px-4 py-3">CA généré</th>
                            <th class="px-4 py-3">Commission plateforme</th>
                            <th class="px-4 py-3">Part du CA</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($commissionsByCategory)): ?>
                            <tr><td colspan="6" class="px-4 py-6 text-center text-sm text-gray-400">Aucune donnée</td></tr>
                        <?php else: foreach ($commissionsByCategory as $cat): ?>
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-4 py-3 text-sm font-medium text-[#0F172A]"><?= htmlspecialchars($cat['nom_categorie']) ?></td>
                                <td class="px-4 py-3 text-xs font-semibold"><?= (int)$cat['nb_ventes'] ?></td>
                                <td class="px-4 py-3 text-xs font-semibold"><?= htmlspecialchars($cat['ca_formatted']) ?></td>
                                <td class="px-4 py-3 text-xs font-semibold text-[#0EA486]"><?= htmlspecialchars($cat['commission_formatted']) ?></td>
                                <td class="px-4 py-3 text-xs"><?= htmlspecialchars($cat['part_ca']) ?>%</td>
                                <td class="px-4 py-3 text-right">
                                    <button data-open-modal="categoryReportModal" data-id="<?= (int)$cat['id'] ?>"
                                            class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center" title="Voir détails">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Par vendeur -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h4 class="text-sm font-semibold text-[#0F172A] flex items-center gap-2">
                    <i class="fas fa-store text-[#0EA486]"></i> Par vendeur
                </h4>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr class="text-left text-xs font-semibold text-gray-500 uppercase">
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
                        <?php if (empty($commissionsByVendor)): ?>
                            <tr><td colspan="7" class="px-4 py-6 text-center text-sm text-gray-400">Aucun vendeur avec des ventes</td></tr>
                        <?php else: foreach ($commissionsByVendor as $v): ?>
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-4 py-3">
                                    <span class="text-sm font-medium text-[#0F172A]"><?= htmlspecialchars($v['full_name']) ?></span>
                                    <p class="text-[10px] text-gray-400"><?= htmlspecialchars($v['email'] ?? '') ?></p>
                                </td>
                                <td class="px-4 py-3 text-xs font-semibold"><?= htmlspecialchars($v['ca_formatted']) ?></td>
                                <td class="px-4 py-3 text-xs font-semibold text-gray-600"><?= htmlspecialchars($v['net_formatted']) ?></td>
                                <td class="px-4 py-3 text-xs font-semibold text-emerald-600">0 FCFA</td>
                                <td class="px-4 py-3 text-xs font-semibold text-orange-600"><?= htmlspecialchars($v['solde_formatted']) ?></td>
                                <td class="px-4 py-3 text-xs font-semibold"><?= (int)$v['nb_ventes'] ?></td>
                                <td class="px-4 py-3 text-right">
                                    <button data-open-modal="vendorReportModal" data-id="<?= (int)$v['id_uti'] ?>"
                                            class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center" title="Voir détails">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- REMBOURSEMENTS -->
    <section class="mb-8">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                <i class="fas fa-undo text-[#0EA486]"></i> · Gestion des remboursements
            </h3>
            <div class="flex gap-2">
                <button data-open-modal="refundFormModal" class="px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white text-xs font-semibold flex items-center gap-2">
                    <i class="fas fa-plus"></i> Initier un remboursement
                </button>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <p class="text-2xl font-bold text-[#0F172A]"><?= $totalRefunds ?></p>
                <p class="text-xs text-gray-400 mt-1">Total</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <p class="text-2xl font-bold text-emerald-600"><?= $refundsDone ?></p>
                <p class="text-xs text-gray-400 mt-1">Effectués</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <p class="text-2xl font-bold text-yellow-600"><?= $refundsPending ?></p>
                <p class="text-xs text-gray-400 mt-1">En cours</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <p class="text-2xl font-bold text-[#0EA486]"><?= $this->formatCurrency($commissionRecovered) ?></p>
                <p class="text-xs text-gray-400 mt-1">Commission récupérée</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr class="text-left text-xs font-semibold text-gray-500 uppercase">
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Commande</th>
                            <th class="px-4 py-3">Client</th>
                            <th class="px-4 py-3">Vendeur</th>
                            <th class="px-4 py-3">Montant</th>
                            <th class="px-4 py-3">Commission récupérée</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Statut</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($refunds)): ?>
                            <tr><td colspan="9" class="px-4 py-6 text-center text-sm text-gray-400">Aucun remboursement</td></tr>
                        <?php else: foreach ($refunds as $r): ?>
                            <?php $rColor = ['success' => 'emerald', 'warning' => 'yellow', 'danger' => 'red', 'secondary' => 'gray'][$r['statut_class']] ?? 'gray'; ?>
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-4 py-3 text-xs text-gray-500">#<?= (int)$r['id'] ?></td>
                                <td class="px-4 py-3 text-xs text-gray-500">#<?= (int)$r['commande_id'] ?></td>
                                <td class="px-4 py-3 text-xs text-gray-600"><?= htmlspecialchars($r['client_full_name']) ?></td>
                                <td class="px-4 py-3 text-xs text-gray-600"><?= htmlspecialchars($r['vendeur_full_name']) ?></td>
                                <td class="px-4 py-3 text-xs font-semibold"><?= htmlspecialchars($r['montant_formatted']) ?></td>
                                <td class="px-4 py-3 text-xs font-semibold text-[#0EA486]"><?= htmlspecialchars($r['commission_recuperee_formatted']) ?></td>
                                <td class="px-4 py-3 text-xs text-gray-500"><?= htmlspecialchars($r['date_formatted']) ?></td>
                                <td class="px-4 py-3">
                                    <span class="text-[10px] font-semibold text-<?= $rColor ?>-700 bg-<?= $rColor ?>-100 px-2 py-1 rounded-full"><?= htmlspecialchars($r['statut_label']) ?></span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <button data-open-modal="refundDetailModal" data-id="<?= (int)$r['id'] ?>"
                                            class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center" title="Voir">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <footer class="mt-8 text-center text-xs text-gray-400 border-t border-gray-100 pt-6">
        &copy; 2026 NDIGITMARKET · Administration
    </footer>
</main>

<!-- ============ MODAL : RAPPORT MENSUEL ============ -->
<div id="monthlyReportModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[92vh] overflow-hidden flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div>
                <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2"><i class="fas fa-calendar text-[#0EA486]"></i> Rapport mensuel</h3>
                <p class="text-xs text-gray-400">Analyse du mois sélectionné</p>
            </div>
            <button data-close-modal class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <div class="p-6 overflow-y-auto space-y-4">
            <div class="flex gap-3">
                <input type="month" id="reportMonth" value="<?= date('Y-m') ?>" class="px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm">
                <button id="loadMonthlyReport" class="px-4 py-2 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold">Voir le rapport</button>
            </div>
            <div id="monthlyReportContent" class="space-y-4">
                <div class="p-6 text-center text-gray-400">Sélectionnez un mois et cliquez sur "Voir le rapport"</div>
            </div>
        </div>
    </div>
</div>

<!-- ============ MODAL : RAPPORT PAR CATÉGORIE ============ -->
<div id="categoryReportModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[92vh] overflow-hidden flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2"><i class="fas fa-tags text-[#0EA486]"></i> Rapport par catégorie</h3>
            <button data-close-modal class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <div class="p-6 overflow-y-auto">
            <div id="categoryReportContent" class="space-y-4">
                <div class="p-6 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>Chargement...</div>
            </div>
        </div>
    </div>
</div>

<!-- ============ MODAL : RAPPORT PAR VENDEUR ============ -->
<div id="vendorReportModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[92vh] overflow-hidden flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2"><i class="fas fa-store text-[#0EA486]"></i> Rapport par vendeur</h3>
            <button data-close-modal class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <div class="p-6 overflow-y-auto">
            <div id="vendorReportContent" class="space-y-4">
                <div class="p-6 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>Chargement...</div>
            </div>
        </div>
    </div>
</div>

<!-- ============ MODAL : INITIER REMBOURSEMENT ============ -->
<div id="refundFormModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <form data-ajax data-action="admin/createRefund" data-success="Remboursement initié">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2"><i class="fas fa-undo text-red-500"></i> Initier un remboursement</h3>
                <button type="button" data-close-modal class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600"><i class="fas fa-times"></i></button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">ID Commande <span class="text-red-500">*</span></label>
                    <input type="number" name="commande_id" required min="1" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Montant (FCFA) <span class="text-red-500">*</span></label>
                    <input type="number" name="montant" required min="1" step="0.01" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Motif <span class="text-red-500">*</span></label>
                    <textarea name="motif" rows="3" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm resize-none"></textarea>
                </div>
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="recuperer_commission" value="1" class="w-4 h-4 rounded">
                        Récupérer la commission plateforme (10%)
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="notifier_acheteur" value="1" checked class="w-4 h-4 rounded">
                        Notifier l'acheteur par email
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="notifier_vendeur" value="1" checked class="w-4 h-4 rounded">
                        Notifier le vendeur par email
                    </label>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                <button type="button" data-close-modal class="px-4 py-2.5 rounded-xl bg-gray-100 text-gray-700 text-sm">Annuler</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-semibold"><i class="fas fa-check"></i> Confirmer</button>
            </div>
        </form>
    </div>
</div>

<!-- ============ MODAL : DÉTAIL REMBOURSEMENT ============ -->
<div id="refundDetailModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl max-h-[92vh] overflow-hidden flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2"><i class="fas fa-receipt text-[#0EA486]"></i> Détail du remboursement #<span data-field="id">...</span></h3>
            <button data-close-modal class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <div class="p-6 overflow-y-auto space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3">Informations</h5>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between"><span class="text-gray-500">Montant</span><span class="font-semibold" data-field="montant_formatted">...</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Commission récupérée</span><span class="font-semibold text-[#0EA486]" data-field="commission_recuperee_formatted">...</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Date</span><span class="font-medium" data-field="date_formatted">...</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Statut</span><span class="font-medium" data-field="statut_label">...</span></div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3">Parties</h5>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between"><span class="text-gray-500">Client</span><span class="font-medium" data-field="client_full_name">...</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Vendeur</span><span class="font-medium" data-field="vendeur_full_name">...</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Commande</span><span class="font-medium">#<span data-field="commande_id">...</span></span></div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                <h5 class="text-xs font-semibold text-gray-400 uppercase mb-2">Motif</h5>
                <p class="text-sm text-gray-600" data-field="motif">...</p>
            </div>
        </div>
    </div>
</div>

<!-- ============ MODAL : EXPORT ============ -->
<div id="exportModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2"><i class="fas fa-download text-[#0EA486]"></i> Exporter le rapport</h3>
            <button data-close-modal class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <div class="p-6 space-y-3">
            <a href="<?= window.NDIGIT_BASE_URL ?? '' ?>/index.php?route=admin/exportFinancialReport&type=vendors" class="block p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600"><i class="fas fa-store"></i></div>
                    <div>
                        <p class="text-sm font-semibold text-[#0F172A]">Rapport par vendeur</p>
                        <p class="text-[11px] text-gray-400">CA, commissions, soldes</p>
                    </div>
                </div>
            </a>
            <a href="<?= window.NDIGIT_BASE_URL ?? '' ?>/index.php?route=admin/exportFinancialReport&type=categories" class="block p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600"><i class="fas fa-tags"></i></div>
                    <div>
                        <p class="text-sm font-semibold text-[#0F172A]">Rapport par catégorie</p>
                        <p class="text-[11px] text-gray-400">Ventes et revenus par catégorie</p>
                    </div>
                </div>
            </a>
            <a href="<?= window.NDIGIT_BASE_URL ?? '' ?>/index.php?route=admin/exportFinancialReport&type=refunds" class="block p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600"><i class="fas fa-undo"></i></div>
                    <div>
                        <p class="text-sm font-semibold text-[#0F172A]">Rapport des remboursements</p>
                        <p class="text-[11px] text-gray-400">Historique complet</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<script>
    // Sidebar mobile
    (function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const hamburger = document.getElementById('hamburgerBtn');
        if (!sidebar || !overlay || !hamburger) return;
        function openSidebar() { sidebar.classList.add('open'); overlay.classList.add('active'); document.body.style.overflow = 'hidden'; }
        function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('active'); document.body.style.overflow = ''; }
        hamburger.addEventListener('click', function(e) { e.stopPropagation(); sidebar.classList.contains('open') ? closeSidebar() : openSidebar(); });
        overlay.addEventListener('click', closeSidebar);
    })();

    // Propagation ID + data-field
    (function() {
        document.body.addEventListener('click', function(e) {
            const trigger = e.target.closest('[data-open-modal]');
            if (!trigger) return;
            const id = trigger.getAttribute('data-id');
            const modalId = trigger.getAttribute('data-open-modal');
            const modal = document.getElementById(modalId);
            if (!modal) return;

            if (id) {
                modal.querySelectorAll('input[name="id"]').forEach(input => input.value = id);
            }

            // Charger rapport catégorie
            if (modalId === 'categoryReportModal' && id) {
                const box = modal.querySelector('#categoryReportContent');
                box.innerHTML = '<div class="p-6 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>Chargement...</div>';
                fetch(window.NDIGIT_BASE_URL + '/index.php?route=admin/getCategoryReport&categorie_id=' + id)
                    .then(r => r.json())
                    .then(data => {
                        if (!data.success) { box.innerHTML = '<div class="text-red-500">Erreur</div>'; return; }
                        let html = `
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-5 border border-blue-100">
                                <h4 class="text-lg font-bold">${data.category.nom_categorie}</h4>
                                <div class="grid grid-cols-3 gap-4 mt-3">
                                    <div><p class="text-xs text-gray-500">CA total</p><p class="text-xl font-bold">${data.ca_formatted}</p></div>
                                    <div><p class="text-xs text-gray-500">Commission</p><p class="text-xl font-bold text-[#0EA486]">${data.commission_formatted}</p></div>
                                    <div><p class="text-xs text-gray-500">Ventes</p><p class="text-xl font-bold">${data.category.nb_ventes}</p></div>
                                </div>
                            </div>
                            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                                <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3">Produits les plus vendus</h5>
                                <table class="w-full text-sm">
                                    <thead><tr class="text-left text-xs text-gray-500"><th class="py-2">Produit</th><th>Vendeur</th><th>Ventes</th><th>CA</th></tr></thead>
                                    <tbody>`;
                        data.products.forEach(p => {
                            html += `<tr class="border-t border-gray-100">
                                <td class="py-2 text-sm font-medium">${p.nom_article}</td>
                                <td class="text-xs text-gray-500">${p.vendeur_prenom || ''} ${p.vendeur_nom || ''}</td>
                                <td class="text-xs font-semibold">${p.nb_ventes}</td>
                                <td class="text-xs font-semibold">${parseFloat(p.ca).toLocaleString()} FCFA</td>
                            </tr>`;
                        });
                        html += '</tbody></table></div>';
                        box.innerHTML = html;
                    })
                    .catch(() => { box.innerHTML = '<div class="text-red-500">Erreur de chargement</div>'; });
            }

            // Charger rapport vendeur
            if (modalId === 'vendorReportModal' && id) {
                const box = modal.querySelector('#vendorReportContent');
                box.innerHTML = '<div class="p-6 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>Chargement...</div>';
                fetch(window.NDIGIT_BASE_URL + '/index.php?route=admin/getVendorReport&vendeur_id=' + id)
                    .then(r => r.json())
                    .then(data => {
                        if (!data.success) { box.innerHTML = '<div class="text-red-500">Erreur</div>'; return; }
                        const v = data.vendor;
                        let html = `
                            <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl p-5 border border-emerald-100">
                                <h4 class="text-lg font-bold">${v.prenom || ''} ${v.nom || ''}</h4>
                                <p class="text-xs text-gray-500">${v.email || ''}</p>
                                <div class="grid grid-cols-4 gap-4 mt-3">
                                    <div><p class="text-xs text-gray-500">CA total</p><p class="text-xl font-bold">${data.ca_formatted}</p></div>
                                    <div><p class="text-xs text-gray-500">Net vendeur</p><p class="text-xl font-bold text-[#0EA486]">${data.net_formatted}</p></div>
                                    <div><p class="text-xs text-gray-500">Solde dû</p><p class="text-xl font-bold text-orange-600">${data.solde_formatted}</p></div>
                                    <div><p class="text-xs text-gray-500">Ventes</p><p class="text-xl font-bold">${v.nb_ventes}</p></div>
                                </div>
                            </div>
                            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                                <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3">Produits du vendeur</h5>
                                <table class="w-full text-sm">
                                    <thead><tr class="text-left text-xs text-gray-500"><th class="py-2">Produit</th><th>Prix</th><th>Statut</th><th>Ventes</th><th>CA</th></tr></thead>
                                    <tbody>`;
                        data.products.forEach(p => {
                            html += `<tr class="border-t border-gray-100">
                                <td class="py-2 text-sm font-medium">${p.nom_article}</td>
                                <td class="text-xs">${parseFloat(p.prix).toLocaleString()} FCFA</td>
                                <td class="text-xs">${p.statut}</td>
                                <td class="text-xs font-semibold">${p.nb_ventes}</td>
                                <td class="text-xs font-semibold">${parseFloat(p.ca).toLocaleString()} FCFA</td>
                            </tr>`;
                        });
                        html += '</tbody></table></div>';
                        box.innerHTML = html;
                    })
                    .catch(() => { box.innerHTML = '<div class="text-red-500">Erreur de chargement</div>'; });
            }

            // Charger détail remboursement
            if (modalId === 'refundDetailModal' && id) {
                fetch(window.NDIGIT_BASE_URL + '/index.php?route=admin/getRefundDetails&id=' + id)
                    .then(r => r.json())
                    .then(data => {
                        if (!data.success) return;
                        modal.querySelectorAll('[data-field]').forEach(el => {
                            const field = el.getAttribute('data-field');
                            if (data.refund[field] !== undefined) el.textContent = data.refund[field];
                        });
                    });
            }
        });
    })();

    // Rapport mensuel
    (function() {
        const btn = document.getElementById('loadMonthlyReport');
        if (!btn) return;
        btn.addEventListener('click', function() {
            const mois = document.getElementById('reportMonth').value;
            const box = document.getElementById('monthlyReportContent');
            box.innerHTML = '<div class="p-6 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>Chargement...</div>';
            fetch(window.NDIGIT_BASE_URL + '/index.php?route=admin/getMonthlyReport&mois=' + mois)
                .then(r => r.json())
                .then(data => {
                    if (!data.success) { box.innerHTML = '<div class="text-red-500">Erreur</div>'; return; }
                    const r = data.report;
                    let html = `
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-5 border border-blue-100">
                            <h4 class="text-lg font-bold">Rapport de ${r.mois}</h4>
                            <p class="text-xs text-gray-500">Période du ${r.date_from} au ${r.date_to}</p>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-3">
                                <div><p class="text-xs text-gray-500">CA total</p><p class="text-xl font-bold">${r.ca_formatted}</p></div>
                                <div><p class="text-xs text-gray-500">Commission plateforme</p><p class="text-xl font-bold text-[#0EA486]">${r.commission_plateforme}</p></div>
                                <div><p class="text-xs text-gray-500">Commandes</p><p class="text-xl font-bold">${r.stats.nb_commandes}</p></div>
                                <div><p class="text-xs text-gray-500">Vendeurs actifs</p><p class="text-xl font-bold">${r.stats.nb_vendeurs}</p></div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                                <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3">Top 5 produits</h5>
                                <div class="space-y-2">`;
                    r.top_products.forEach((p, i) => {
                        html += `<div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-[#0EA486] text-white text-xs flex items-center justify-center font-bold">${i+1}</span>
                                <span class="text-sm font-medium">${p.nom_article}</span>
                            </div>
                            <span class="text-xs font-semibold">${p.nb_ventes} ventes</span>
                        </div>`;
                    });
                    html += `</div></div>
                            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                                <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3">Top 5 vendeurs</h5>
                                <div class="space-y-2">`;
                    r.top_vendors.forEach((v, i) => {
                        html += `<div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-[#0EA486] text-white text-xs flex items-center justify-center font-bold">${i+1}</span>
                                <span class="text-sm font-medium">${v.prenom || ''} ${v.nom || ''}</span>
                            </div>
                            <span class="text-xs font-semibold">${parseFloat(v.ca).toLocaleString()} FCFA</span>
                        </div>`;
                    });
                    html += '</div></div></div>';
                    box.innerHTML = html;
                })
                .catch(() => { box.innerHTML = '<div class="text-red-500">Erreur de chargement</div>'; });
        });
    })();
</script>
</body>
</html>