<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NDIGITMARKET Admin · Gestion des Commandes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="/ndigitmarket/assets/images/favi.png">

    <link rel="stylesheet" href="../assets/CSS/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
</head>

<body>
    <?php $currentPage = 'gestion-commande';
    require_once __DIR__ . '/../components/sidebar.php'; ?>

    <main class="md:ml-[280px] min-h-screen p-4 md:p-8 transition-all">

        <header class="flex items-center justify-between mb-6 bg-white/80 backdrop-blur-sm sticky top-0 z-30 py-4 px-4 md:px-6 rounded-2xl shadow-sm border border-gray-100/50">
            <div class="flex items-center gap-4">
                <button id="hamburgerBtn" class="md:hidden w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 transition flex items-center justify-center text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-[#0F172A]">Gestion des Commandes</h2>
                    <p class="text-xs text-gray-400 hidden sm:block">Suivi et gestion des commandes</p>
                </div>
            </div>
        </header>

        <!-- STATS -->
        <section class="mb-6">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2 mb-4">
                <i class="fas fa-chart-line text-[#0EA486]"></i> · Vue d'ensemble
            </h3>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600"><i class="fas fa-shopping-cart"></i></div>
                        <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">TOTAL</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= $totalOrders ?></p>
                    <p class="text-xs text-gray-400 mt-1">Commandes totales</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600"><i class="fas fa-coins"></i></div>
                        <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">REVENUS</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= $this->formatCurrency((float)$totalRevenue) ?></p>
                    <p class="text-xs text-gray-400 mt-1">Chiffre d'affaires total</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600"><i class="fas fa-percentage"></i></div>
                        <span class="text-[10px] font-semibold text-purple-600 bg-purple-50 px-2 py-1 rounded-full">COMMISSION</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= $this->formatCurrency((float)$totalCommission) ?></p>
                    <p class="text-xs text-gray-400 mt-1">Commission plateforme (10%)</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600"><i class="fas fa-wallet"></i></div>
                        <span class="text-[10px] font-semibold text-orange-600 bg-orange-50 px-2 py-1 rounded-full">VENDEURS</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= $this->formatCurrency((float)($totalRevenue - $totalCommission)) ?></p>
                    <p class="text-xs text-gray-400 mt-1">Revenu des vendeurs (90%)</p>
                </div>
            </div>
        </section>

        <!-- LISTE DES COMMANDES -->
        <section class="mb-6">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-list text-[#0EA486]"></i>· Liste des commandes
                </h3>
                <button data-open-modal="orderFormModal" data-mode="create"
                    class="px-4 py-2 rounded-lg bg-[#0EA486] hover:bg-[#0c8f75] text-white text-xs font-semibold flex items-center gap-2 shadow-sm transition">
                    <i class="fas fa-plus"></i> Nouvelle commande
                </button>
            </div>

            <!-- Filtres -->
            <form data-filter method="get" class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm mb-4">
                <input type="hidden" name="route" value="admin/gestion-commande">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex-1 min-w-[220px] relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" name="search" data-search-input="search"
                            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                            placeholder="Rechercher par ID, client, produit..."
                            class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm">
                    </div>
                    <?php $vendeurFilter = $_GET['vendeur'] ?? ''; ?>
                    <select name="vendeur" class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600">
                        <option value="">Tous les vendeurs</option>
                        <?php foreach ($vendeurs as $v): ?>
                            <option value="<?= $v['id_uti'] ?>" <?= $vendeurFilter == $v['id_uti'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars(trim($v['prenom'] . ' ' . $v['nom'])) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="date" name="date_from" value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>"
                        class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600" placeholder="Date début">
                    <input type="date" name="date_to" value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>"
                        class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600" placeholder="Date fin">
                    <input type="number" name="prix_min" value="<?= htmlspecialchars($_GET['prix_min'] ?? '') ?>"
                        class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 w-32" placeholder="Prix min">
                    <input type="number" name="prix_max" value="<?= htmlspecialchars($_GET['prix_max'] ?? '') ?>"
                        class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 w-32" placeholder="Prix max">
                </div>
            </form>

            <!-- Bulk bar -->
            <div class="hidden items-center gap-2 bg-white rounded-2xl p-3 border border-gray-100 shadow-sm mb-4" data-bulk-bar>
                <span class="text-xs text-gray-500"><span data-bulk-count>0</span> sélectionné(s)</span>
                <button data-bulk-action="admin/bulkDeleteOrders" data-confirm="Supprimer les commandes sélectionnées ?"
                    class="px-3 py-1.5 rounded-lg bg-red-50 text-red-700 hover:bg-red-100 text-xs font-medium">
                    <i class="fas fa-trash mr-1"></i> Supprimer
                </button>
            </div>

            <!-- Tableau -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" data-paginate data-page-size="25">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-3 w-10"><input type="checkbox" data-bulk-master class="w-4 h-4 rounded border-gray-300"></th>
                                <th data-sort-col="id" class="px-4 py-3 cursor-pointer hover:text-[#0EA486]">ID <i class="fas fa-sort text-[10px] ml-1"></i></th>
                                <th data-sort-col="date_commande" class="px-4 py-3 cursor-pointer hover:text-[#0EA486]">Date <i class="fas fa-sort text-[10px] ml-1"></i></th>
                                <th class="px-4 py-3">Client</th>
                                <th class="px-4 py-3">Produit</th>
                                <th class="px-4 py-3">Vendeur</th>
                                <th data-sort-col="prix" class="px-4 py-3 cursor-pointer hover:text-[#0EA486]">Prix <i class="fas fa-sort text-[10px] ml-1"></i></th>
                                <th class="px-4 py-3">Commission plateforme</th>
                                <th class="px-4 py-3">Commission vendeur</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if (empty($orders)): ?>
                                <tr>
                                    <td colspan="10" class="px-4 py-6 text-center text-sm text-gray-400">Aucune commande trouvée.</td>
                                </tr>
                                <?php else: foreach ($orders as $o): ?>
                                    <?php
                                    $editPayload = json_encode([
                                        'a' => (int)$o['a'],
                                        'id_client' => $o['id_client'] ?? '',
                                        'email' => $o['email'] ?? '',
                                        'id_article' => $o['id_article'] ?? '',
                                        'prix' => $o['prix'] ?? 0,
                                    ], JSON_HEX_APOS | JSON_HEX_QUOT);
                                    $detailPayload = json_encode([
                                        'a' => (int)$o['a'],
                                        'date_formatted' => $o['date_formatted'],
                                        'prix_formatted' => $o['prix_formatted'],
                                        'commission_plateforme_formatted' => $o['commission_plateforme_formatted'],
                                        'commission_vendeur_formatted' => $o['commission_vendeur_formatted'],
                                        'client_full_name' => $o['client_full_name'],
                                        'email' => $o['email'] ?? '',
                                        'nom_article' => $o['nom_article'] ?? 'N/A',
                                        'vendeur_full_name' => $o['vendeur_full_name'],
                                        'nom_categorie' => $o['nom_categorie'] ?? 'N/A',
                                        'image' => $o['produit_image'] ?? '',
                                        'fichier' => $o['fichier'] ?? '',
                                    ], JSON_HEX_APOS | JSON_HEX_QUOT);
                                    ?>
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-4 py-3"><input type="checkbox" data-bulk-checkbox class="w-4 h-4 rounded border-gray-300" value="<?= (int)$o['a'] ?>"></td>
                                        <td class="px-4 py-3 text-xs text-gray-500">#<?= (int)$o['a'] ?></td>
                                        <td class="px-4 py-3 text-xs text-gray-500"><?= htmlspecialchars($o['date_formatted']) ?></td>
                                        <td class="px-4 py-3">
                                            <div>
                                                <span class="text-xs text-[#0EA486] font-medium"><?= htmlspecialchars($o['client_full_name']) ?></span>
                                                <?php if (!empty($o['email'])): ?>
                                                    <p class="text-[10px] text-gray-400"><?= htmlspecialchars($o['email']) ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-600"><?= htmlspecialchars($o['nom_article'] ?? 'N/A') ?></td>
                                        <td class="px-4 py-3 text-xs text-gray-600"><?= htmlspecialchars($o['vendeur_full_name']) ?></td>
                                        <td class="px-4 py-3"><span class="text-xs font-semibold text-[#0F172A]"><?= htmlspecialchars($o['prix_formatted']) ?></span></td>
                                        <td class="px-4 py-3"><span class="text-xs font-semibold text-[#0EA486]"><?= htmlspecialchars($o['commission_plateforme_formatted']) ?></span></td>
                                        <td class="px-4 py-3"><span class="text-xs font-semibold text-gray-600"><?= htmlspecialchars($o['commission_vendeur_formatted']) ?></span></td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-end gap-1">
                                                <button data-open-modal="orderDetailModal" data-id="<?= (int)$o['a'] ?>"
                                                    data-order='<?= $detailPayload ?>'
                                                    class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center" title="Voir">
                                                    <i class="fas fa-eye text-xs"></i>
                                                </button>
                                                <button data-open-modal="orderFormModal" data-id="<?= (int)$o['a'] ?>"
                                                    data-mode="edit" data-edit='<?= $editPayload ?>'
                                                    class="w-8 h-8 rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-100 flex items-center justify-center" title="Modifier">
                                                    <i class="fas fa-edit text-xs"></i>
                                                </button>
                                                <button data-open-modal="deleteOrderModal" data-id="<?= (int)$o['a'] ?>"
                                                    class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center" title="Supprimer">
                                                    <i class="fas fa-trash text-xs"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                            <?php endforeach;
                            endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="flex flex-wrap items-center justify-between gap-3 px-4 py-3 border-t border-gray-100 bg-gray-50/50">
                    <span class="text-xs text-gray-500"><?= $totalOrders ?> résultat<?= $totalOrders > 1 ? 's' : '' ?> au total</span>
                </div>
            </div>
        </section>

        <footer class="mt-8 text-center text-xs text-gray-400 border-t border-gray-100 pt-6">
            &copy; 2026 NDIGITMARKET · Administration
        </footer>
    </main>

    <!-- ============ MODAL : FORMULAIRE COMMANDE (CREATE + EDIT) ============ -->
    <div id="orderFormModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A]" id="orderFormTitle">Nouvelle commande</h3>
                    <p class="text-xs text-gray-400">Créer ou modifier une commande</p>
                </div>
                <button data-close-modal class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="orderForm" data-ajax data-action="admin/createOrder" data-success="Commande enregistrée" class="p-6 overflow-y-auto space-y-4">
                <input type="hidden" name="id" value="">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Produit <span class="text-red-500">*</span></label>
                        <select name="id_article" required id="productSelect" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm">
                            <option value="">— Sélectionner un produit —</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Client (optionnel)</label>
                        <select name="id_client" id="clientSelect" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm">
                            <option value="">— Client invité —</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Email client <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Prix (FCFA) <span class="text-red-500">*</span></label>
                        <input type="number" name="prix" min="0" step="0.01" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                    <button type="button" data-close-modal class="px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">Annuler</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============ MODAL : DÉTAIL COMMANDE ============ -->
    <div id="orderDetailModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-5xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-receipt text-[#0EA486]"></i> Détail de la commande
                    </h3>
                    <p class="text-xs text-gray-400">Commande #<span data-field="a">...</span></p>
                </div>
                <button data-close-modal class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="overflow-y-auto p-6 space-y-5">
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-5 border border-blue-100">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div>
                            <h4 class="text-lg font-bold text-[#0F172A]">Commande #<span data-field="a">...</span></h4>
                            <p class="text-xs text-gray-500 mt-1">Passée le <span data-field="date_formatted">...</span></p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-[#0EA486]" data-field="prix_formatted">...</p>
                            <p class="text-xs text-gray-500">Montant total</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white rounded-2xl p-4 border border-gray-100">
                        <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3">Informations client</h5>
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between"><span class="text-gray-500">Nom</span><span class="font-medium" data-field="client_full_name">...</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Email</span><span class="font-medium" data-field="email">...</span></div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-4 border border-gray-100">
                        <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3">Informations produit</h5>
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between"><span class="text-gray-500">Produit</span><span class="font-medium" data-field="nom_article">...</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Vendeur</span><span class="font-medium" data-field="vendeur_full_name">...</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Catégorie</span><span class="font-medium" data-field="nom_categorie">...</span></div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3">Répartition des montants</h5>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between"><span class="text-gray-500">Montant total</span><span class="font-semibold" data-field="prix_formatted">...</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Commission plateforme (10%)</span><span class="font-semibold text-[#0EA486]" data-field="commission_plateforme_formatted">...</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Commission vendeur (90%)</span><span class="font-semibold" data-field="commission_vendeur_formatted">...</span></div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3">Fichier acheté</h5>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600">
                            <i class="fas fa-file-archive"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-[#0F172A] truncate" data-field="fichier">...</p>
                            <p class="text-[11px] text-gray-400">Fichier téléchargeable par le client</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                <button data-close-modal class="px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">Fermer</button>
            </div>
        </div>
    </div>

    <!-- ============ MODAL : SUPPRIMER COMMANDE ============ -->
    <div id="deleteOrderModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <form data-ajax data-action="admin/deleteOrder" data-success="Commande supprimée">
                <input type="hidden" name="id" value="">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2"><i class="fas fa-trash text-red-500"></i> Supprimer la commande</h3>
                    <button type="button" data-close-modal class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600"><i class="fas fa-times"></i></button>
                </div>
                <div class="p-6 space-y-4">
                    <div class="bg-red-50 rounded-xl p-4 border border-red-100">
                        <p class="text-sm text-red-700"><i class="fas fa-exclamation-triangle mr-2"></i><strong>Attention :</strong> Action irréversible.</p>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                    <button type="button" data-close-modal class="px-4 py-2.5 rounded-xl bg-gray-100 text-gray-700 text-sm">Annuler</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-semibold"><i class="fas fa-trash"></i> Supprimer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Sidebar mobile
        (function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const hamburger = document.getElementById('hamburgerBtn');
            if (!sidebar || !overlay || !hamburger) return;

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
        })();

        // Charger les produits et clients dans les selects
        (function() {
            const productSelect = document.getElementById('productSelect');
            const clientSelect = document.getElementById('clientSelect');

            // Charger les produits
            fetch(window.NDIGIT_BASE_URL + '/index.php?route=admin/getProductsList')
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.products) {
                        data.products.forEach(p => {
                            const opt = document.createElement('option');
                            opt.value = p.id;
                            opt.textContent = `${p.nom_article} - ${p.prix} FCFA`;
                            opt.dataset.prix = p.prix;
                            productSelect.appendChild(opt);
                        });
                    }
                })
                .catch(() => {});

            // Charger les clients
            fetch(window.NDIGIT_BASE_URL + '/index.php?route=admin/getClientsList')
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.clients) {
                        data.clients.forEach(c => {
                            const opt = document.createElement('option');
                            opt.value = c.id_uti;
                            opt.textContent = `${c.prenom || ''} ${c.nom || ''} (${c.email || ''})`;
                            opt.dataset.email = c.email || '';
                            clientSelect.appendChild(opt);
                        });
                    }
                })
                .catch(() => {});

            // Auto-remplir le prix quand on sélectionne un produit
            productSelect.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                const prix = selected.dataset.prix;
                if (prix) {
                    const prixInput = document.querySelector('#orderForm input[name="prix"]');
                    if (prixInput) prixInput.value = prix;
                }
            });

            // Auto-remplir l'email quand on sélectionne un client
            clientSelect.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                const email = selected.dataset.email;
                if (email) {
                    const emailInput = document.querySelector('#orderForm input[name="email"]');
                    if (emailInput) emailInput.value = email;
                }
            });
        })();

        // Propagation ID + mode create/edit
        (function() {
            document.body.addEventListener('click', function(e) {
                const trigger = e.target.closest('[data-open-modal]');
                if (!trigger) return;
                const id = trigger.getAttribute('data-id');
                const modalId = trigger.getAttribute('data-open-modal');
                const modal = document.getElementById(modalId);
                if (!modal) return;

                if (modalId === 'orderFormModal') {
                    const mode = trigger.getAttribute('data-mode') || 'create';
                    const form = modal.querySelector('#orderForm');
                    const title = modal.querySelector('#orderFormTitle');
                    if (form) {
                        if (mode === 'edit') {
                            form.setAttribute('data-action', 'admin/updateOrder');
                            form.setAttribute('data-success', 'Commande mise à jour');
                            if (title) title.textContent = 'Modifier la commande';
                        } else {
                            form.setAttribute('data-action', 'admin/createOrder');
                            form.setAttribute('data-success', 'Commande créée');
                            if (title) title.textContent = 'Nouvelle commande';
                            form.reset();
                        }
                    }
                }

                if (id) {
                    modal.querySelectorAll('input[name="id"]').forEach(input => input.value = id);
                    modal.querySelectorAll('[data-open-modal]').forEach(nested => nested.setAttribute('data-id', id));
                    modal.querySelectorAll('[data-action]').forEach(btn => btn.setAttribute('data-id', id));
                }
            });
        })();

        // Remplissage data-field depuis data-order
        (function() {
            document.body.addEventListener('click', function(e) {
                const trigger = e.target.closest('[data-order]');
                if (!trigger) return;
                const modalId = trigger.getAttribute('data-open-modal');
                const modal = document.getElementById(modalId);
                if (!modal) return;
                let payload;
                try {
                    payload = JSON.parse(trigger.getAttribute('data-order'));
                } catch (_) {
                    return;
                }
                modal.querySelectorAll('[data-field]').forEach(el => {
                    const field = el.getAttribute('data-field');
                    if (payload[field] !== undefined && payload[field] !== null && payload[field] !== '') {
                        el.textContent = payload[field];
                    }
                });
            });
        })();
    </script>
</body>

</html>
