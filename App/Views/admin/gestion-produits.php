<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NDIGITMARKET Admin · Produits & Templates</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="/ndigitmarket/assets/images/favi.png">

    <link rel="stylesheet" href="../assets/CSS/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
</head>

<body>
    <?php $currentPage = 'gestion-produits';
    require_once __DIR__ . '/../components/sidebar.php';
    $plainProductText = static function (?string $html): string {
        $decoded = html_entity_decode((string) $html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $decoded = preg_replace('/<(br|\/p|\/h[1-6]|\/li)\b[^>]*>/i', "\n", $decoded);
        $text = trim(strip_tags((string) $decoded));
        return preg_replace("/[ \t]+/", ' ', preg_replace("/\n{3,}/", "\n\n", $text));
    };
    ?>

    <main class="md:ml-[280px] min-h-screen p-4 md:p-8 transition-all">

        <header class="flex items-center justify-between mb-6 bg-white/80 backdrop-blur-sm sticky top-0 z-30 py-4 px-4 md:px-6 rounded-2xl shadow-sm border border-gray-100/50">
            <div class="flex items-center gap-4">
                <button id="hamburgerBtn" class="md:hidden w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 transition flex items-center justify-center text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-[#0F172A]">Produits & Templates</h2>
                    <p class="text-xs text-gray-400 hidden sm:block">Modération, catalogue et gestion des produits</p>
                </div>
            </div>
        </header>

        <!-- FILE DE MODÉRATION -->
        <section class="mb-6">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2 mb-4">
                <i class="fas fa-clipboard-check text-[#0EA486]"></i>· File de modération
            </h3>

            <?php
            $pendingProducts = array_filter($products, fn($p) => ($p['statut'] ?? '') === 'en_attente');
            $refusedProducts = array_filter($products, fn($p) => ($p['statut'] ?? '') === 'refuse');
            $approvedToday   = array_filter($products, fn($p) => ($p['statut'] ?? '') === 'approuve' && date('Y-m-d', strtotime($p['date_ajout'])) === date('Y-m-d'));
            ?>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-yellow-50 rounded-xl flex items-center justify-center text-yellow-600"><i class="fas fa-hourglass-half"></i></div>
                        <span class="text-[10px] font-semibold text-yellow-600 bg-yellow-50 px-2 py-1 rounded-full">EN ATTENTE</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= count($pendingProducts) ?></p>
                    <p class="text-xs text-gray-400 mt-1">Produits à modérer</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600"><i class="fas fa-box"></i></div>
                        <span class="text-[10px] font-semibold text-orange-600 bg-orange-50 px-2 py-1 rounded-full">TOTAL</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= $totalProducts ?></p>
                    <p class="text-xs text-gray-400 mt-1">Produits enregistrés</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600"><i class="fas fa-check-circle"></i></div>
                        <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">AUJOURD'HUI</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= count($approvedToday) ?></p>
                    <p class="text-xs text-gray-400 mt-1">Approuvés aujourd'hui</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center text-red-600"><i class="fas fa-times-circle"></i></div>
                        <span class="text-[10px] font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-full">REFUSÉS</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= count($refusedProducts) ?></p>
                    <p class="text-xs text-gray-400 mt-1">Refusés au total</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h4 class="text-sm font-semibold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-inbox text-[#0EA486]"></i> Produits en attente de revue
                    </h4>
                    <span class="text-xs text-gray-400"><?= count($pendingProducts) ?> produit<?= count($pendingProducts) > 1 ? 's' : '' ?></span>
                </div>
                <div class="divide-y divide-gray-100">
                    <?php if (empty($pendingProducts)): ?>
                        <div class="p-6 text-center text-sm text-gray-400">Aucun produit en attente.</div>
                        <?php else: foreach ($pendingProducts as $p): ?>
                            <?php
                            $payload = json_encode([
                                'id' => (int)$p['id'],
                                'nom_article' => $p['nom_article'] ?? 'N/A',
                                'description' => $plainProductText($p['description'] ?? ''),
                                'prix_formatted' => $p['prix_formatted'],
                                'prix_reduction_formatted' => $p['prix_reduction_formatted'],
                                'nom_categorie' => $p['nom_categorie'] ?? 'N/A',
                                'vendeur_full_name' => $p['vendeur_full_name'],
                                'vendeur_email' => $p['vendeur_email'] ?? '',
                                'date_formatted' => $p['date_formatted'],
                                'image' => $p['image'] ?? '',
                            ], JSON_HEX_APOS | JSON_HEX_QUOT);
                            ?>
                            <div class="p-4 hover:bg-gray-50/50 transition">
                                <div class="flex flex-col md:flex-row gap-4">
                                    <div class="w-full md:w-32 h-24 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden">
                                        <?php if (!empty($p['image'])): ?>
                                            <img src="<?= htmlspecialchars('../' . $p['image']) ?>" class="w-full h-full object-cover" alt="">
                                        <?php else: ?>
                                            <i class="fas fa-image text-3xl text-indigo-300"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-start justify-between gap-2 mb-1">
                                            <div>
                                                <h5 class="text-sm font-semibold text-[#0F172A]"><?= htmlspecialchars($p['nom_article'] ?? 'N/A') ?></h5>
                                                <p class="text-xs text-gray-400 mt-0.5">ID <?= (int)$p['id'] ?> · Soumis le <?= htmlspecialchars($p['date_formatted']) ?></p>
                                            </div>
                                            <span class="text-[10px] font-semibold text-yellow-700 bg-yellow-100 px-2 py-1 rounded-full">
                                                <i class="fas fa-hourglass-half mr-1"></i>En attente
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-500 line-clamp-2 mb-2"><?= htmlspecialchars($plainProductText($p['description'] ?? '')) ?></p>
                                        <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                            <span class="flex items-center gap-1"><i class="fas fa-tag text-[#0EA486]"></i><?= htmlspecialchars($p['nom_categorie'] ?? 'N/A') ?></span>
                                            <span class="flex items-center gap-1"><i class="fas fa-coins text-[#0EA486]"></i><?= htmlspecialchars($p['prix_formatted']) ?></span>
                                            <span class="flex items-center gap-1"><i class="fas fa-user text-[#0EA486]"></i><?= htmlspecialchars($p['vendeur_full_name']) ?></span>
                                        </div>
                                    </div>
                                    <div class="flex md:flex-col gap-2 md:justify-center">
                                        <button data-open-modal="reviewModal" data-id="<?= (int)$p['id'] ?>"
                                            data-product='<?= $payload ?>'
                                            class="px-3 py-2 rounded-lg bg-[#0EA486] hover:bg-[#0c8f75] text-white text-xs font-semibold flex items-center gap-1.5 transition">
                                            <i class="fas fa-eye"></i> Revoir
                                        </button>
                                    </div>
                                </div>
                            </div>
                    <?php endforeach;
                    endif; ?>
                </div>
            </div>
        </section>

        <!-- LISTE COMPLÈTE -->
        <section class="mb-6">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-list text-[#0EA486]"></i>· Liste complète des produits
                </h3>
                <div class="flex flex-wrap items-center gap-2">
                    <?php
                    $exportParams = $_GET;
                    $exportParams['route'] = 'admin/exportProducts';
                    $exportUrl = (defined('BASE_URL') ? BASE_URL : '/ndigitmarket') . '/index.php?' . http_build_query($exportParams);
                    ?>
                    <button type="button" data-open-modal="productHistoryModal"
                        class="px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2">
                        <i class="fas fa-history"></i> Historique
                    </button>
                    <a href="<?= htmlspecialchars($exportUrl) ?>"
                        class="px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2">
                        <i class="fas fa-file-csv"></i> Export CSV
                    </a>
                    <button data-open-modal="productFormModal" data-mode="create"
                        class="px-4 py-2 rounded-lg bg-[#0EA486] hover:bg-[#0c8f75] text-white text-xs font-semibold flex items-center gap-2 shadow-sm transition">
                        <i class="fas fa-plus"></i> Nouveau produit
                    </button>
                </div>
            </div>

            <!-- Filtres -->
            <form data-filter method="get" class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm mb-4">
                <input type="hidden" name="route" value="admin/gestion-produits">
                <div class="flex flex-wrap items-center gap-3">

                    <div class="flex-1 min-w-[220px] relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" name="search" data-search-input="search"
                            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                            placeholder="Rechercher par nom..."
                            class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                    </div>
                    <?php $catFilter = $_GET['categorie'] ?? ''; ?>
                    <select name="categorie" class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600">
                        <option value="">Toutes les catégories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $catFilter == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nom_categorie']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php $statutFilter = $_GET['statut'] ?? ''; ?>
                    <select name="statut" class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600">
                        <option value="">Tous les statuts</option>
                        <option value="approuve" <?= $statutFilter === 'approuve'   ? 'selected' : '' ?>>Approuvé</option>
                        <option value="en_attente" <?= $statutFilter === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                        <option value="refuse" <?= $statutFilter === 'refuse'     ? 'selected' : '' ?>>Refusé</option>
                        <option value="suspendu" <?= $statutFilter === 'suspendu'   ? 'selected' : '' ?>>Suspendu</option>
                    </select>
                </div>
            </form>

            <!-- Bulk bar -->
            <div class="hidden items-center gap-2 bg-white rounded-2xl p-3 border border-gray-100 shadow-sm mb-4" data-bulk-bar>
                <span class="text-xs text-gray-500"><span data-bulk-count>0</span> sélectionné(s)</span>
                <button data-bulk-action="admin/bulkDeleteProducts" data-confirm="Supprimer les produits sélectionnés ?"
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
                                <th data-sort-col="nom_article" class="px-4 py-3 cursor-pointer hover:text-[#0EA486]">Produit <i class="fas fa-sort text-[10px] ml-1"></i></th>
                                <th class="px-4 py-3">Catégorie</th>
                                <th class="px-4 py-3">Vendeur</th>
                                <th data-sort-col="prix" class="px-4 py-3 cursor-pointer hover:text-[#0EA486]">Prix <i class="fas fa-sort text-[10px] ml-1"></i></th>
                                <th class="px-4 py-3">Statut</th>
                                <th data-sort-col="nb_ventes" class="px-4 py-3 cursor-pointer hover:text-[#0EA486]">Ventes <i class="fas fa-sort text-[10px] ml-1"></i></th>
                                <th data-sort-col="date_ajout" class="px-4 py-3 cursor-pointer hover:text-[#0EA486]">Date <i class="fas fa-sort text-[10px] ml-1"></i></th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if (empty($products)): ?>
                                <tr>
                                    <td colspan="9" class="px-4 py-6 text-center text-sm text-gray-400">Aucun produit trouvé.</td>
                                </tr>
                                <?php else: foreach ($products as $p): ?>
                                    <?php
                                    $pColor  = ['success' => 'emerald', 'warning' => 'yellow', 'danger' => 'red', 'secondary' => 'gray'][$p['statut_class']] ?? 'gray';
                                    $editPayload = json_encode([
                                        'id' => (int)$p['id'],
                                        'nom_article' => $p['nom_article'] ?? '',
                                        'description' => $plainProductText($p['description'] ?? ''),
                                        'prix' => $p['prix'] ?? 0,
                                        'prix_reduction' => $p['prix_reduction'] ?? 0,
                                        'categorie_id' => $p['categorie_id'] ?? '',
                                        'id_vendeur' => $p['id_vendeur'] ?? '',
                                        'statut' => $p['statut'] ?? '',
                                        'demo_url' => $p['demo_url'] ?? '',
                                        'tags' => $p['tags'] ?? '',
                                    ], JSON_HEX_APOS | JSON_HEX_QUOT);
                                    $apercuPayload = json_encode([
                                        'id' => (int)$p['id'],
                                        'nom_article' => $p['nom_article'] ?? 'N/A',
                                        'description' => $plainProductText($p['description'] ?? ''),
                                        'prix' => $p['prix'] ?? 0,
                                        'prix_reduction' => $p['prix_reduction'] ?? 0,
                                        'prix_formatted' => $p['prix_formatted'],
                                        'prix_reduction_formatted' => $p['prix_reduction_formatted'],
                                        'nom_categorie' => $p['nom_categorie'] ?? 'N/A',
                                        'vendeur_full_name' => $p['vendeur_full_name'],
                                        'vendeur_email' => $p['vendeur_email'] ?? '',
                                        'date_formatted' => $p['date_formatted'],
                                        'image' => $p['image'] ?? '',
                                        'fichier' => $p['fichier'] ?? '',
                                        'demo_url' => $p['demo_url'] ?? $p['apercue'] ?? '',
                                        'statut' => $p['statut'] ?? '',
                                        'statut_label' => $p['statut_label'],
                                        'tags' => $p['tags'] ?? $p['sous_categorie'] ?? '',
                                    ], JSON_HEX_APOS | JSON_HEX_QUOT);
                                    $reviewPayload = json_encode([
                                        'id' => (int)$p['id'],
                                        'nom_article' => $p['nom_article'] ?? 'N/A',
                                        'description' => $plainProductText($p['description'] ?? ''),
                                        'prix_formatted' => $p['prix_formatted'],
                                        'prix_reduction_formatted' => $p['prix_reduction_formatted'],
                                        'nom_categorie' => $p['nom_categorie'] ?? 'N/A',
                                        'vendeur_full_name' => $p['vendeur_full_name'],
                                        'vendeur_email' => $p['vendeur_email'] ?? '',
                                        'date_formatted' => $p['date_formatted'],
                                        'image' => $p['image'] ?? '',
                                    ], JSON_HEX_APOS | JSON_HEX_QUOT);
                                    ?>
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-4 py-3"><input type="checkbox" data-bulk-checkbox class="w-4 h-4 rounded border-gray-300" value="<?= (int)$p['id'] ?>"></td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                                                    <?php if (!empty($p['image'])): ?>
                                                        <img src="<?= htmlspecialchars('../' . $p['image']) ?>" class="w-full h-full object-cover" alt="">
                                                    <?php else: ?>
                                                        <i class="fas fa-image text-indigo-400"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-[#0F172A] text-sm"><?= htmlspecialchars($p['nom_article'] ?? 'N/A') ?></p>
                                                    <p class="text-[11px] text-gray-400">ID <?= (int)$p['id'] ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-600"><?= htmlspecialchars($p['nom_categorie'] ?? 'N/A') ?></td>
                                        <td class="px-4 py-3"><span class="text-xs text-[#0EA486] font-medium"><?= htmlspecialchars($p['vendeur_full_name']) ?></span></td>
                                        <td class="px-4 py-3"><span class="text-xs font-semibold text-[#0F172A]"><?= htmlspecialchars($p['prix_formatted']) ?></span></td>
                                        <td class="px-4 py-3">
                                            <span class="text-[10px] font-semibold text-<?= $pColor ?>-700 bg-<?= $pColor ?>-100 px-2 py-1 rounded-full"><?= htmlspecialchars($p['statut_label']) ?></span>
                                        </td>
                                        <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]"><?= (int)$p['nb_ventes'] ?></td>
                                        <td class="px-4 py-3 text-xs text-gray-500"><?= htmlspecialchars($p['date_formatted']) ?></td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-end gap-1">
                                                <button data-open-modal="apercuModal" data-id="<?= (int)$p['id'] ?>"
                                                    data-apercu='<?= $apercuPayload ?>'
                                                    class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 flex items-center justify-center" title="Aperçu complet">
                                                    <i class="fas fa-eye text-xs"></i>
                                                </button>
                                                <button data-open-modal="productFormModal" data-id="<?= (int)$p['id'] ?>"
                                                    data-mode="edit" data-edit='<?= $editPayload ?>'
                                                    class="w-8 h-8 rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-100 flex items-center justify-center" title="Modifier">
                                                    <i class="fas fa-edit text-xs"></i>
                                                </button>
                                                <button data-open-modal="deleteProductModal" data-id="<?= (int)$p['id'] ?>"
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
                    <span class="text-xs text-gray-500"><?= $totalProducts ?> résultat<?= $totalProducts > 1 ? 's' : '' ?> au total</span>
                </div>
            </div>
        </section>

        <footer class="mt-8 text-center text-xs text-gray-400 border-t border-gray-100 pt-6">
            &copy; 2026 NDIGITMARKET · Administration
        </footer>
    </main>

    <!-- ============ MODAL : FORMULAIRE PRODUIT (CREATE + EDIT) ============ -->
    <div id="productHistoryModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2"><i class="fas fa-history text-[#0EA486]"></i> Historique produits/templates</h3>
                    <p class="text-xs text-gray-400">Dernières actions administrateur</p>
                </div>
                <button data-close-modal class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto">
                <div data-product-history-list class="space-y-2">
                    <div class="p-4 text-center text-sm text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>Chargement...</div>
                </div>
            </div>
        </div>
    </div>

    <div id="productFormModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A]" id="productFormTitle">Nouveau produit</h3>
                    <p class="text-xs text-gray-400">Créer ou modifier un produit</p>
                </div>
                <button data-close-modal class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="productForm" data-ajax data-action="admin/createProduct" data-success="Produit enregistré" class="p-6 overflow-y-auto space-y-4" enctype="multipart/form-data">
                <input type="hidden" name="id" value="">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Nom du produit <span class="text-red-500">*</span></label>
                        <input type="text" name="nom_article" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Description <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="4" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] resize-none"></textarea>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Catégorie <span class="text-red-500">*</span></label>
                        <select name="categorie_id" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm">
                            <option value="">— Sélectionner —</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom_categorie']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Vendeur <span class="text-red-500">*</span></label>
                        <div class="relative" id="vendorSearchContainer">
                            <input type="text" id="vendorSearchInput"
                                placeholder="Rechercher un vendeur par nom..."
                                autocomplete="off"
                                class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                            <input type="hidden" name="id_vendeur" id="vendorIdHidden" required>
                            <div id="vendorResults"
                                class="absolute z-50 top-full left-0 right-0 mt-1 bg-white border border-gray-100 rounded-xl shadow-lg max-h-48 overflow-y-auto hidden">
                            </div>
                            <div id="vendorSelected"
                                class="mt-2 px-3 py-2 bg-emerald-50 border border-emerald-100 rounded-lg text-sm text-emerald-700 hidden flex items-center justify-between">
                                <span id="vendorSelectedName"></span>
                                <button type="button" onclick="clearVendor()" class="text-emerald-500 hover:text-emerald-700">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Prix (FCFA) <span class="text-red-500">*</span></label>
                        <input type="number" name="prix" min="0" step="1" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm">
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Prix réduit (FCFA)</label>
                        <input type="number" name="prix_reduction" min="0" step="1" value="0" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">URL de démonstration</label>
                        <input type="url" name="demo_url" placeholder="https://..." class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Tags / Mots-clés</label>
                        <input type="text" name="tags" placeholder="dashboard, admin, react..." class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm">
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Image principale</label>
                        <input type="file" name="image" accept="image/*" class="w-full px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-xs">
                        <p class="text-[10px] text-gray-400 mt-1">PNG/JPG · max 2 MB</p>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Fichier template</label>
                        <input type="file" name="fichier_template" accept=".zip,.rar" class="w-full px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-xs">
                        <p class="text-[10px] text-gray-400 mt-1">ZIP/RAR · max 100 MB</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Statut</label>
                        <select name="statut" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm">
                            <option value="en_attente">En attente</option>
                            <option value="approuve">Approuvé</option>
                            <option value="refuse">Refusé</option>
                            <option value="suspendu">Suspendu</option>
                        </select>
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

    <!-- ============ MODAL : REVUE PRODUIT ============ -->
    <div id="reviewModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-5xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-clipboard-check text-[#0EA486]"></i> Interface de modération
                    </h3>
                    <p class="text-xs text-gray-400">Revue détaillée du produit</p>
                </div>
                <button data-close-modal class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="overflow-y-auto p-6 space-y-5">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div class="lg:col-span-2">
                        <div class="bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100 rounded-2xl h-64 flex items-center justify-center relative overflow-hidden">
                            <img data-field="image" class="w-full h-full object-cover hidden" alt="">
                            <i data-field="image_placeholder" class="fas fa-image text-6xl text-indigo-300"></i>
                            <span class="absolute top-3 left-3 text-[10px] font-semibold text-white bg-black/50 px-2 py-1 rounded-full">Image principale</span>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="bg-white rounded-2xl p-4 border border-gray-100">
                            <h5 class="text-xs font-semibold text-gray-400 uppercase mb-2">Produit</h5>
                            <h4 class="text-base font-bold text-[#0F172A] mb-1" data-field="nom_article">—</h4>
                            <p class="text-[11px] text-gray-400 mb-3">ID <span data-field="id">—</span></p>
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between"><span class="text-gray-500">Catégorie</span><span class="font-medium text-[#0F172A]" data-field="nom_categorie">—</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Prix</span><span class="font-semibold text-[#0EA486]" data-field="prix_formatted">—</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Prix réduit</span><span class="font-medium text-[#0F172A]" data-field="prix_reduction_formatted">—</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Soumis le</span><span class="font-medium text-[#0F172A]" data-field="date_formatted">—</span></div>
                            </div>
                        </div>
                        <div class="bg-white rounded-2xl p-4 border border-gray-100">
                            <h5 class="text-xs font-semibold text-gray-400 uppercase mb-2">Vendeur</h5>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#0EA486] rounded-full flex items-center justify-center text-white font-semibold"><i class="fas fa-user"></i></div>
                                <div>
                                    <p class="text-sm font-semibold text-[#0F172A]" data-field="vendeur_full_name">—</p>
                                    <p class="text-[11px] text-gray-400" data-field="vendeur_email">—</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-2 flex items-center gap-2">
                        <i class="fas fa-align-left text-[#0EA486]"></i> Description complète
                    </h5>
                    <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-wrap" data-field="description">—</p>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-star text-[#0EA486]"></i> Avis clients
                    </h5>
                    <div data-avis-container class="space-y-2">
                        <div class="p-4 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>Chargement...</div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-wrap items-center justify-between gap-3">
                <span class="text-xs text-gray-500">Décision :</span>
                <div class="flex flex-wrap items-center gap-2">
                    <button data-open-modal="rejectProductModal" class="px-4 py-2.5 rounded-xl bg-red-100 hover:bg-red-200 text-red-700 text-sm font-semibold flex items-center gap-2">
                        <i class="fas fa-times-circle"></i> Refuser
                    </button>
                    <button data-action="admin/approveProduct" data-confirm="Approuver et publier ce produit ?"
                        data-success="Produit approuvé"
                        class="px-5 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm">
                        <i class="fas fa-check-circle"></i> Approuver et publier
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ MODAL : REFUSER PRODUIT ============ -->
    <div id="rejectProductModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <form data-ajax data-action="admin/rejectProduct" data-success="Produit refusé">
                <input type="hidden" name="id" value="">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2"><i class="fas fa-times-circle text-red-500"></i> Refuser le produit</h3>
                    <button type="button" data-close-modal class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600"><i class="fas fa-times"></i></button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Commentaire <span class="text-red-500">*</span></label>
                        <textarea name="commentaire" rows="4" placeholder="Expliquez la raison du refus..." class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm" required></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                    <button type="button" data-close-modal class="px-4 py-2.5 rounded-xl bg-gray-100 text-gray-700 text-sm">Annuler</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-semibold"><i class="fas fa-times"></i> Confirmer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============ MODAL : SUPPRIMER PRODUIT ============ -->
    <div id="deleteProductModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <form data-ajax data-action="admin/deleteProduct" data-success="Produit supprimé">
                <input type="hidden" name="id" value="">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2"><i class="fas fa-trash text-red-500"></i> Supprimer le produit</h3>
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

    <!-- ============ MODAL : APERÇU COMPLET DU PRODUIT ============ -->
    <div id="apercuModal" class="fixed inset-0 z-[70] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-5xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-eye text-[#0EA486]"></i> Aperçu complet
                    </h3>
                    <p class="text-xs text-gray-400">Tous les détails du produit</p>
                </div>
                <button data-close-modal class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="overflow-y-auto p-6">
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

                    <!-- COLONNE GAUCHE : Image + téléchargement -->
                    <div class="lg:col-span-2 space-y-4">
                        <div class="bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100 rounded-2xl h-72 flex items-center justify-center relative overflow-hidden border border-gray-100">
                            <img id="apercuImage" class="w-full h-full object-cover hidden" alt="Image du produit">
                            <i id="apercuImagePlaceholder" class="fas fa-image text-6xl text-indigo-300"></i>
                        </div>

                        <!-- Bouton télécharger ZIP -->
                        <div id="apercuDownloadSection" class="hidden bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <p class="text-xs font-semibold text-gray-600 mb-2 flex items-center gap-2">
                                <i class="fas fa-download text-[#0EA486]"></i> Fichier template
                            </p>
                            <a id="apercuDownloadLink" href="#" download
                                class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold transition">
                                <i class="fas fa-file-archive"></i> Télécharger le ZIP
                            </a>
                        </div>

                        <!-- Démo URL (bouton) -->
                        <div id="apercuDemoSection" class="hidden">
                            <a id="apercuDemoLink" href="#" target="_blank" rel="noopener"
                                class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-sm font-semibold transition border border-indigo-100">
                                <i class="fas fa-external-link-alt"></i> <span id="apercuDemoText">Voir la démo</span>
                            </a>
                        </div>
                    </div>

                    <!-- COLONNE DROITE : Infos produit -->
                    <div class="lg:col-span-3 space-y-4">
                        <!-- Titre + statut -->
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h2 id="apercuNom" class="text-2xl font-bold text-[#0F172A]">—</h2>
                                <p class="text-xs text-gray-400 mt-1">ID <span id="apercuId">—</span></p>
                            </div>
                            <span id="apercuStatutBadge" class="text-[10px] font-semibold px-2 py-1 rounded-full whitespace-nowrap">—</span>
                        </div>

                        <!-- Grille infos -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Catégorie</p>
                                <p id="apercuCategorie" class="text-sm font-semibold text-[#0F172A]">—</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Prix</p>
                                <p id="apercuPrix" class="text-sm font-semibold text-[#0EA486]">—</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Prix réduit</p>
                                <p id="apercuPrixReduit" class="text-sm font-semibold text-[#0F172A]">—</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Date</p>
                                <p id="apercuDate" class="text-sm font-semibold text-[#0F172A]">—</p>
                            </div>
                        </div>

                        <!-- Vendeur -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2">Vendeur</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#0EA486] rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <p id="apercuVendeur" class="text-sm font-semibold text-[#0F172A]">—</p>
                                    <p id="apercuVendeurEmail" class="text-xs text-gray-400">—</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tags -->
                        <div id="apercuTagsSection" class="hidden">
                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2">Tags / Mots-clés</p>
                            <div id="apercuTags" class="flex flex-wrap gap-2"></div>
                        </div>

                        <!-- Description -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2">Description</p>
                            <p id="apercuDescription" class="text-sm text-gray-600 leading-relaxed whitespace-pre-wrap">—</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                <button data-close-modal class="px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                    <i class="fas fa-times"></i> Fermer
                </button>
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
        })();

        // Propagation de l'ID entre modales + gestion du mode (create/edit)
        (function() {
            document.body.addEventListener('click', function(e) {
                const trigger = e.target.closest('[data-open-modal]');
                if (!trigger) return;
                const id = trigger.getAttribute('data-id');
                const modalId = trigger.getAttribute('data-open-modal');
                const modal = document.getElementById(modalId);
                if (!modal) return;

                // Gestion du mode create/edit pour productFormModal
                if (modalId === 'productFormModal') {
                    const mode = trigger.getAttribute('data-mode') || 'create';
                    const form = modal.querySelector('#productForm');
                    const title = modal.querySelector('#productFormTitle');
                    if (form) {
                        if (mode === 'edit') {
                            form.setAttribute('data-action', 'admin/updateProduct');
                            form.setAttribute('data-success', 'Produit mis à jour');
                            if (title) title.textContent = 'Modifier le produit';
                        } else {
                            form.setAttribute('data-action', 'admin/createProduct');
                            form.setAttribute('data-success', 'Produit créé');
                            if (title) title.textContent = 'Nouveau produit';
                            form.reset();
                        }
                    }
                }

                if (id) {
                    modal.dataset.currentId = id;
                    modal.querySelectorAll('input[name="id"]').forEach(input => input.value = id);
                    modal.querySelectorAll('[data-open-modal]').forEach(nested => nested.setAttribute('data-id', id));
                    modal.querySelectorAll('[data-action]').forEach(btn => btn.setAttribute('data-id', id));
                }
            });
        })();

        // Remplissage [data-field] depuis data-product + chargement avis
        (function() {
            document.body.addEventListener('click', function(e) {
                const trigger = e.target.closest('[data-product]');
                if (!trigger) return;
                const modalId = trigger.getAttribute('data-open-modal');
                const modal = document.getElementById(modalId);
                if (!modal) return;
                let payload;
                try {
                    payload = JSON.parse(trigger.getAttribute('data-product'));
                } catch (_) {
                    return;
                }
                modal.querySelectorAll('[data-field]').forEach(el => {
                    const field = el.getAttribute('data-field');
                    if (payload[field] !== undefined && payload[field] !== null && payload[field] !== '') {
                        if (field === 'image') {
                            el.src = '../' + payload.image;
                            el.classList.remove('hidden');
                            const ph = modal.querySelector('[data-field="image_placeholder"]');
                            if (ph) ph.classList.add('hidden');
                        } else {
                            el.textContent = payload[field];
                        }
                    }
                });

                if (modalId === 'reviewModal') {
                    const avisBox = modal.querySelector('[data-avis-container]');
                    if (avisBox) {
                        avisBox.innerHTML = '<div class="p-4 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>Chargement...</div>';
                        fetch(window.NDIGIT_BASE_URL + '/index.php?route=admin/getProductDetails&id=' + payload.id)
                            .then(r => r.json())
                            .then(data => {
                                if (!data.success || !data.avis || data.avis.length === 0) {
                                    avisBox.innerHTML = '<div class="p-4 text-center text-sm text-gray-400">Aucun avis pour ce produit.</div>';
                                    return;
                                }
                                let html = '';
                                data.avis.forEach(a => {
                                    const stars = '★'.repeat(parseInt(a.note) || 0) + '☆'.repeat(5 - (parseInt(a.note) || 0));
                                    html += `
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-sm font-semibold text-[#0F172A]">${(a.prenom || '') + ' ' + (a.nom || '')}</span>
                                            <span class="text-amber-500 text-sm">${stars}</span>
                                        </div>
                                        <p class="text-xs text-gray-600">${a.commentaire || ''}</p>
                                        <p class="text-[10px] text-gray-400 mt-1">${a.date_avis || ''}</p>
                                    </div>`;
                                });
                                avisBox.innerHTML = html;
                            })
                            .catch(() => {
                                avisBox.innerHTML = '<div class="p-4 text-center text-sm text-red-500">Erreur de chargement.</div>';
                            });
                    }
                }
            });
        })();

        // Historique produits/templates
        (function() {
            const modal = document.getElementById('productHistoryModal');
            const list = modal ? modal.querySelector('[data-product-history-list]') : null;
            if (!modal || !list) return;
            const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, char => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            } [char]));

            document.body.addEventListener('click', function(e) {
                const trigger = e.target.closest('[data-open-modal="productHistoryModal"]');
                if (!trigger) return;

                list.innerHTML = '<div class="p-4 text-center text-sm text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>Chargement...</div>';
                fetch(window.NDIGIT_BASE_URL + '/index.php?route=admin/productHistory', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (!data.success || !data.history || data.history.length === 0) {
                            list.innerHTML = '<div class="p-4 text-center text-sm text-gray-400">Aucun historique disponible.</div>';
                            return;
                        }

                        list.innerHTML = data.history.map(item => `
                            <div class="p-3 rounded-xl border border-gray-100 bg-gray-50">
                                <div class="flex flex-wrap items-start justify-between gap-2">
                                    <div>
                                        <p class="text-sm font-semibold text-[#0F172A]">${escapeHtml(item.details || item.action || 'Action produit')}</p>
                                        <p class="text-[11px] text-gray-400">Action : ${escapeHtml(item.action || '-')}${item.target_user_id ? ' · ID ' + escapeHtml(item.target_user_id) : ''}</p>
                                    </div>
                                    <span class="text-[11px] font-medium text-gray-500">${escapeHtml(item.date_formatted || item.created_at || '')}</span>
                                </div>
                            </div>
                        `).join('');
                    })
                    .catch(() => {
                        list.innerHTML = '<div class="p-4 text-center text-sm text-red-500">Erreur de chargement.</div>';
                    });
            });
        })();

        // =====================================================
        // 0. MODALE APERÇU PRODUIT (data-apercu)
        // =====================================================
        (function() {
            document.body.addEventListener('click', function(e) {
                const trigger = e.target.closest('[data-apercu]');
                if (!trigger) return;
                let payload;
                try {
                    payload = JSON.parse(trigger.getAttribute('data-apercu'));
                } catch (_) {
                    return;
                }

                const baseUrl = window.NDIGIT_BASE_URL || '/ndigitmarket';

                // Nom + ID
                document.getElementById('apercuNom').textContent = payload.nom_article || '—';
                document.getElementById('apercuId').textContent = payload.id || '—';

                // Statut badge
                const badge = document.getElementById('apercuStatutBadge');
                const statusColors = {
                    'approuve': 'bg-emerald-100 text-emerald-700',
                    'en_attente': 'bg-yellow-100 text-yellow-700',
                    'refuse': 'bg-red-100 text-red-700',
                    'suspendu': 'bg-gray-100 text-gray-700'
                };
                const label = payload.statut_label || payload.statut || 'Inconnu';
                badge.textContent = label;
                badge.className = 'text-[10px] font-semibold px-2 py-1 rounded-full whitespace-nowrap ' +
                    (statusColors[payload.statut] || 'bg-gray-100 text-gray-700');

                // Catégorie
                document.getElementById('apercuCategorie').textContent = payload.nom_categorie || '—';
                // Prix
                document.getElementById('apercuPrix').textContent = payload.prix_formatted || '—';
                document.getElementById('apercuPrixReduit').textContent = payload.prix_reduction_formatted || '—';
                // Date
                document.getElementById('apercuDate').textContent = payload.date_formatted || '—';
                // Vendeur
                document.getElementById('apercuVendeur').textContent = payload.vendeur_full_name || '—';
                document.getElementById('apercuVendeurEmail').textContent = payload.vendeur_email || '—';
                // Description
                document.getElementById('apercuDescription').textContent = payload.description || '—';

                // Image
                const img = document.getElementById('apercuImage');
                const placeholder = document.getElementById('apercuImagePlaceholder');
                if (payload.image) {
                    img.src = baseUrl + '/' + payload.image;
                    img.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                } else {
                    img.classList.add('hidden');
                    placeholder.classList.remove('hidden');
                }

                // Section téléchargement ZIP
                const dlSection = document.getElementById('apercuDownloadSection');
                const dlLink = document.getElementById('apercuDownloadLink');
                if (payload.fichier) {
                    dlSection.classList.remove('hidden');
                    dlLink.href = baseUrl + '/' + payload.fichier;
                } else {
                    dlSection.classList.add('hidden');
                }

                // Section démo URL
                const demoSec = document.getElementById('apercuDemoSection');
                const demoLink = document.getElementById('apercuDemoLink');
                const demoText = document.getElementById('apercuDemoText');
                if (payload.demo_url) {
                    demoSec.classList.remove('hidden');
                    demoLink.href = payload.demo_url;
                    demoText.textContent = payload.demo_url.length > 50 ?
                        payload.demo_url.substring(0, 50) + '...' :
                        payload.demo_url;
                } else {
                    demoSec.classList.add('hidden');
                }

                // Tags
                const tagsSection = document.getElementById('apercuTagsSection');
                const tagsContainer = document.getElementById('apercuTags');
                if (payload.tags) {
                    const tagList = payload.tags.split(',').map(t => t.trim()).filter(t => t);
                    if (tagList.length > 0) {
                        tagsSection.classList.remove('hidden');
                        tagsContainer.innerHTML = tagList.map(t =>
                            '<span class="px-2.5 py-1 bg-gray-100 rounded-lg text-xs text-gray-600 font-medium">' +
                            t + '</span>'
                        ).join('');
                    } else {
                        tagsSection.classList.add('hidden');
                    }
                } else {
                    tagsSection.classList.add('hidden');
                }
            });
        })();

        // =====================================================
        // 1. RECHERCHE VENDEUR AUTOCOMPLETE (AJAX)
        // =====================================================
        (function() {
            const input = document.getElementById('vendorSearchInput');
            const results = document.getElementById('vendorResults');
            const hidden = document.getElementById('vendorIdHidden');
            const selected = document.getElementById('vendorSelected');
            const selectedName = document.getElementById('vendorSelectedName');
            let debounceTimer;
            let selectedId = null;
            let items = [];

            function hideResults() {
                results.classList.add('hidden');
            }

            window.clearVendor = function() {
                selectedId = null;
                hidden.value = '';
                input.value = '';
                input.classList.remove('hidden');
                selected.classList.add('hidden');
                input.focus();
                hideResults();
            };

            input.addEventListener('input', function() {
                const q = this.value.trim();
                if (selectedId) {
                    selectedId = null;
                    hidden.value = '';
                    selected.classList.add('hidden');
                    input.classList.remove('hidden');
                }
                if (q.length < 2) {
                    hideResults();
                    return;
                }
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    const url = (window.NDIGIT_BASE_URL || '/ndigitmarket') +
                        '/index.php?route=admin/searchVendors&q=' + encodeURIComponent(q);
                    fetch(url, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(r => r.json())
                        .then(data => {
                            items = data.vendors || [];
                            if (items.length === 0) {
                                results.innerHTML =
                                    '<div class="p-3 text-sm text-gray-400 text-center">Aucun vendeur trouvé</div>';
                                results.classList.remove('hidden');
                                return;
                            }
                            results.innerHTML = items.map(v =>
                                '<div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-50 text-sm flex items-center gap-2" data-id="' +
                                v.id + '" data-name="' + v.full_name.replace(/'/g, "&#39;") + '">' +
                                '<div class="w-8 h-8 rounded-full bg-[#0EA486]/10 text-[#0EA486] flex items-center justify-center text-xs font-bold">' +
                                v.full_name.charAt(0).toUpperCase() +
                                '</div>' +
                                '<div><p class="font-medium text-[#0F172A]">' + v.full_name + '</p>' +
                                '<p class="text-xs text-gray-400">' + v.email + '</p></div>' +
                                '</div>'
                            ).join('');
                            results.classList.remove('hidden');
                        })
                        .catch(() => {
                            results.innerHTML = '<div class="p-3 text-sm text-red-400 text-center">Erreur</div>';
                            results.classList.remove('hidden');
                        });
                }, 300);
            });

            results.addEventListener('click', function(e) {
                const item = e.target.closest('[data-id]');
                if (!item) return;
                const id = item.getAttribute('data-id');
                const name = item.getAttribute('data-name');
                selectedId = id;
                hidden.value = id;
                selectedName.textContent = name;
                selected.classList.remove('hidden');
                input.classList.add('hidden');
                hideResults();
            });

            // Cacher les résultats au clic ailleurs
            document.addEventListener('click', function(e) {
                if (!e.target.closest('#vendorSearchContainer')) {
                    hideResults();
                }
            });

            // Navigation clavier
            input.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    const first = results.querySelector('[data-id]');
                    if (first) first.focus();
                }
                if (e.key === 'Escape') hideResults();
            });

            results.addEventListener('keydown', function(e) {
                const items = results.querySelectorAll('[data-id]');
                const current = document.activeElement;
                const idx = Array.from(items).indexOf(current);
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (idx < items.length - 1) items[idx + 1].focus();
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (idx > 0) items[idx - 1].focus();
                    else input.focus();
                } else if (e.key === 'Escape') {
                    hideResults();
                    input.focus();
                }
            });

            // Gérer le remplissage du vendeur en mode édition (depuis data-edit)
            document.body.addEventListener('click', function(e) {
                const trigger = e.target.closest('[data-open-modal="productFormModal"][data-edit]');
                if (!trigger) return;
                let payload;
                try {
                    payload = JSON.parse(trigger.getAttribute('data-edit'));
                } catch (_) {
                    return;
                }
                if (payload.id_vendeur && (payload.vendeur_full_name || payload.nom_vendeur)) {
                    const name = payload.vendeur_full_name || payload.nom_vendeur || '';
                    clearVendor();
                    hidden.value = payload.id_vendeur;
                    selectedId = payload.id_vendeur;
                    selectedName.textContent = name;
                    selected.classList.remove('hidden');
                    input.classList.add('hidden');
                }
            });
        })();
    </script>
</body>

</html>