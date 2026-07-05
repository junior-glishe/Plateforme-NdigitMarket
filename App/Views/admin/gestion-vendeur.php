<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NDIGITMARKET Admin · Gestion des Vendeurs</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/CSS/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
</head>
<body>
    <?php
    /*
     * =====================================================================
     * ÉTAT DE L'INTÉGRATION AJAX (basé sur VendorsTrait.php et admin.js réels)
     * =====================================================================
     * BRANCHÉ ET FONCTIONNEL :
     *   - admin/approveVendor   (bouton data-action, id en route param -> int $id)
     *   - admin/rejectVendor    (form data-ajax, $_POST['id'], $_POST['motif'])
     *   - admin/suspendVendor   (form data-ajax, $_POST['id'/'motif'/'duree'])
     *   - admin/deleteVendor    (form data-ajax, $_POST['id'/'confirmation'])
     *   - admin/updateVendor    (form data-ajax, $_POST['id'/'nom_boutique'/'description'/'statut'])
     *   - admin/markPayment     (form data-ajax, $_POST['id'/'montant'/'reference'])
     *   - admin/getVendorDetails (fetch GET ?id=<id_uti> -> produits + ventes)
     *
     * ⚠️ BUGS CÔTÉ CONTRÔLEUR À VÉRIFIER (non corrigés ici, hors périmètre de la vue) :
     *   - vendors() lit la table demandes_vendeur, alors que rejectVendor/updateVendor/
     *     suspendVendor/deleteVendor/markPayment ciblent une table `vendeurs` distincte
     *     via `id`. L'id envoyé par cette vue (dv.id) n'a aucune raison de correspondre
     *     à une ligne de `vendeurs`. Il manque soit une jointure vers `vendeurs` dans
     *     vendors(), soit approveVendor() doit créer la ligne `vendeurs` correspondante.
     *   - rejectVendor() ne met pas à jour demandes_vendeur.statut (contrairement à
     *     approveVendor) : une demande refusée ne sera jamais marquée 'refusee'.
     *   - getVendorDetails() utilise la table `commandes` (pluriel) + `id_produit`,
     *     alors que vendors() utilise `commande` (singulier) + `id_article` pour la
     *     même relation -> vérifier le nom réel de la table.
     *   - La liste "vendeurs actifs" affiche actuellement TOUTES les demandes
     *     (en_attente + acceptee + refusee), pas seulement les acceptées.
     *
     * NON BRANCHÉ (aucune méthode correspondante dans VendorsTrait.php) : les boutons
     * "Demander infos", "Notes internes", "Historique versements" et les actions
     * groupées sont désactivés (grisés) plutôt que câblés sur une route inexistante.
     * =====================================================================
     */
    $currentPage = 'gestion-vendeur';
    require_once __DIR__ . '/../components/sidebar.php';
    ?>

    <main class="md:ml-[280px] min-h-screen p-4 md:p-8 transition-all">

        <!-- HEADER -->
        <header class="flex items-center justify-between mb-6 bg-white/80 backdrop-blur-sm sticky top-0 z-30 py-4 px-4 md:px-6 rounded-2xl shadow-sm border border-gray-100/50">
            <div class="flex items-center gap-4">
                <button id="hamburgerBtn" class="md:hidden w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 transition flex items-center justify-center text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-[#0F172A]">Gestion des Vendeurs</h2>
                    <p class="text-xs text-gray-400 hidden sm:block">Demandes, vendeurs actifs, commissions et versements</p>
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

        <!--  DEMANDES DE DEVENIR VENDEUR -->
        <section class="mb-6">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-user-plus text-[#0EA486]"></i> · Demandes de devenir vendeur
                </h3>
                <div class="flex gap-2">
                    <button disabled title="Non implémenté côté serveur"
                            class="px-3 py-2 rounded-lg bg-gray-100 text-gray-400 text-xs font-medium flex items-center gap-2 cursor-not-allowed">
                        <i class="fas fa-history"></i> Historique des décisions
                    </button>
                </div>
            </div>

            <?php
                $pendingVendors  = array_filter($vendors, fn($v) => ($v['statut'] ?? '') === 'en_attente');
                $acceptedVendors = array_filter($vendors, fn($v) => ($v['statut'] ?? '') === 'acceptee');
                $refusedVendors  = array_filter($vendors, fn($v) => ($v['statut'] ?? '') === 'refusee');
            ?>
            <!-- Cartes de statistiques -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-yellow-50 rounded-xl flex items-center justify-center text-yellow-600">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-yellow-600 bg-yellow-50 px-2 py-1 rounded-full">EN ATTENTE</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= count($pendingVendors) ?></p>
                    <p class="text-xs text-gray-400 mt-1">Demandes en attente</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">APPROUVÉES</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= count($acceptedVendors) ?></p>
                    <p class="text-xs text-gray-400 mt-1">Approuvées au total</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center text-red-600">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-full">REFUSÉES</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= count($refusedVendors) ?></p>
                    <p class="text-xs text-gray-400 mt-1">Refusées au total</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                            <i class="fas fa-store"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">TOTAL</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= $totalVendors ?></p>
                    <p class="text-xs text-gray-400 mt-1">Vendeurs enregistrés</p>
                </div>
            </div>

            <!-- Liste des demandes en attente -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h4 class="text-sm font-semibold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-inbox text-[#0EA486]"></i> Demandes en attente de revue
                    </h4>
                    <span class="text-xs text-gray-400"><?= count($pendingVendors) ?> demande<?= count($pendingVendors) > 1 ? 's' : '' ?></span>
                </div>

                <div class="divide-y divide-gray-100">
                    <?php if (empty($pendingVendors)): ?>
                        <div class="p-6 text-center text-sm text-gray-400">Aucune demande en attente.</div>
                    <?php else: foreach ($pendingVendors as $v): ?>
                    <?php
                        // Toutes les données du demandeur sont déjà chargées par vendors() :
                        // pas besoin d'un aller-retour AJAX pour remplir reviewModal, on les
                        // embarque directement dans data-vendor (lu en JS au clic).
                        $requestPayload = [
                            'id'            => (int) $v['id'],
                            'full_name'     => $v['full_name'],
                            'email'         => $v['email'] ?? '',
                            'telephone'     => $v['telephone'] ?? '',
                            'date_formatted'=> $v['date_formatted'],
                            'nom_boutique'  => $v['nom_boutique'] ?? 'N/A',
                            'categorie'     => $v['categorie'] ?? '',
                            'description'   => $v['description'] ?? '',
                            'initiale'      => mb_strtoupper(mb_substr($v['nom_boutique'] ?? 'N', 0, 1)),
                        ];
                    ?>
                    <div class="p-4 hover:bg-gray-50/50 transition">
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="w-full md:w-16 h-16 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-store text-2xl text-emerald-400"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-start justify-between gap-2 mb-1">
                                    <div>
                                        <h5 class="text-sm font-semibold text-[#0F172A]"><?= htmlspecialchars($v['nom_boutique'] ?? 'N/A') ?></h5>
                                        <p class="text-xs text-gray-400 mt-0.5">ID <?= (int) $v['id'] ?> · Soumis le <?= htmlspecialchars($v['date_formatted']) ?></p>
                                    </div>
                                    <span class="text-[10px] font-semibold text-yellow-700 bg-yellow-100 px-2 py-1 rounded-full">
                                        <i class="fas fa-hourglass-half mr-1"></i>En attente
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 line-clamp-2 mb-2"><?= htmlspecialchars($v['description'] ?? '') ?></p>
                                <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                    <span class="flex items-center gap-1"><i class="fas fa-user text-[#0EA486]"></i><?= htmlspecialchars($v['full_name']) ?></span>
                                    <span class="flex items-center gap-1"><i class="fas fa-envelope text-[#0EA486]"></i><?= htmlspecialchars($v['email'] ?? '') ?></span>
                                    <span class="flex items-center gap-1"><i class="fas fa-phone text-[#0EA486]"></i><?= htmlspecialchars($v['telephone'] ?? '') ?></span>
                                </div>
                            </div>
                            <div class="flex md:flex-col gap-2 md:justify-center">
                                <button data-open-modal="reviewModal" data-id="<?= (int) $v['id'] ?>"
                                        data-vendor='<?= htmlspecialchars(json_encode($requestPayload), ENT_QUOTES) ?>'
                                        class="js-review-btn px-3 py-2 rounded-lg bg-[#0EA486] hover:bg-[#0c8f75] text-white text-xs font-semibold flex items-center gap-1.5 transition">
                                    <i class="fas fa-eye"></i> Revoir
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </section>

        <!--  LISTE DES VENDEURS ACTIFS -->
        <section class="mb-6">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-list text-[#0EA486]"></i>· Liste des vendeurs actifs
                </h3>
                <div class="flex gap-2">
                    <!-- TODO: pas de route d'export vendeurs dans VendorsTrait.php (contrairement à admin/exportLogs) -->
                    <button disabled title="Non implémenté côté serveur"
                            class="px-3 py-2 rounded-lg bg-gray-100 text-gray-400 text-xs font-medium flex items-center gap-2 cursor-not-allowed">
                        <i class="fas fa-file-csv"></i> Export CSV
                    </button>
                </div>
            </div>

            <!-- Barre de recherche + filtres : 'search' et 'statut' sont déjà lus par vendors() -->
            <form data-filter method="get" class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm mb-4">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex-1 min-w-[220px] relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" name="search" data-search-input="search"
                               value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                               placeholder="Rechercher par nom de boutique ou ID..."
                               class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white transition">
                    </div>
                    <?php $statutFilter = $_GET['statut'] ?? ''; ?>
                    <select name="statut" class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 focus:outline-none focus:border-[#0EA486]">
                        <option value="" <?= $statutFilter === '' ? 'selected' : '' ?>>Tous les statuts</option>
                        <option value="en_attente" <?= $statutFilter === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                        <option value="acceptee" <?= $statutFilter === 'acceptee' ? 'selected' : '' ?>>Acceptée</option>
                        <option value="refusee" <?= $statutFilter === 'refusee' ? 'selected' : '' ?>>Refusée</option>
                    </select>
                </div>
            </form>

            <!-- Sélection multiple : câblée pour plus tard, aucune action groupée n'existe encore côté serveur -->
            <div class="hidden items-center gap-2 bg-white rounded-2xl p-3 border border-gray-100 shadow-sm mb-4" data-bulk-bar>
                <span class="text-xs text-gray-500"><span data-bulk-count>0</span> sélectionné(s) — actions groupées à venir</span>
            </div>

            <!-- Tableau -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" data-paginate data-page-size="25">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-3 w-10"><input type="checkbox" data-bulk-master class="w-4 h-4 rounded border-gray-300"></th>
                                <th class="px-4 py-3">ID Vendeur</th>
                                <th class="px-4 py-3">Nom de boutique</th>
                                <th class="px-4 py-3">Vendeur (utilisateur)</th>
                                <th class="px-4 py-3">Date de la demande</th>
                                <th class="px-4 py-3">Nb produits</th>
                                <th class="px-4 py-3">CA total</th>
                                <th class="px-4 py-3">Commissions dues</th>
                                <th class="px-4 py-3">Statut</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if (empty($vendors)): ?>
                            <tr><td colspan="10" class="px-4 py-6 text-center text-sm text-gray-400">Aucun vendeur enregistré.</td></tr>
                            <?php else: foreach ($vendors as $v): ?>
                            <?php
                                // Payload utilisé pour remplir instantanément l'en-tête et l'onglet
                                // "Informations" de vendorProfileModal (getVendorDetails ne renvoie
                                // que produits+ventes, pas les infos de la boutique elle-même).
                                $profilePayload = [
                                    'id'                    => (int) $v['id'],
                                    'id_uti'                => (int) $v['id_uti'],
                                    'nom_boutique'          => $v['nom_boutique'] ?? 'N/A',
                                    'description'           => $v['description'] ?? '',
                                    'categorie'             => $v['categorie'] ?? '',
                                    'telephone'             => $v['telephone'] ?? '',
                                    'date_formatted'        => $v['date_formatted'],
                                    'statut_label'          => $v['statut_label'],
                                    'nb_produits'           => (int) $v['nb_produits'],
                                    'total_gagne_formatted' => $v['total_gagne_formatted'],
                                    'solde_formatted'       => $v['solde_formatted'],
                                    'initiale'              => mb_strtoupper(mb_substr($v['nom_boutique'] ?? 'N', 0, 1)),
                                ];
                            ?>
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-4 py-3"><input type="checkbox" data-bulk-checkbox class="w-4 h-4 rounded border-gray-300" value="<?= (int) $v['id'] ?>"></td>
                                <td class="px-4 py-3 text-xs text-gray-500">#<?= (int) $v['id_uti'] ?></td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-store text-emerald-400"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-[#0F172A] text-sm"><?= htmlspecialchars($v['nom_boutique'] ?? 'N/A') ?></p>
                                            <p class="text-[11px] text-gray-400"><?= htmlspecialchars($v['categorie'] ?? '') ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="mailto:<?= htmlspecialchars($v['email'] ?? '') ?>" class="text-xs text-[#0EA486] hover:underline font-medium"><?= htmlspecialchars($v['full_name']) ?></a>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500"><?= htmlspecialchars($v['date_formatted']) ?></td>
                                <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]"><?= (int) $v['nb_produits'] ?></td>
                                <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]"><?= htmlspecialchars($v['total_gagne_formatted']) ?></td>
                                <td class="px-4 py-3 text-xs font-semibold text-[#0EA486]"><?= htmlspecialchars($v['solde_formatted']) ?></td>
                                <td class="px-4 py-3">
                                    <?php $vColor = ['success' => 'emerald', 'warning' => 'yellow', 'danger' => 'red', 'secondary' => 'gray'][$v['statut_class']] ?? 'gray'; ?>
                                    <span class="text-[10px] font-semibold text-<?= $vColor ?>-700 bg-<?= $vColor ?>-100 px-2 py-1 rounded-full"><?= htmlspecialchars($v['statut_label']) ?></span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <!-- data-id = id_uti : getVendorDetails filtre produits.id_vendeur = id_uti -->
                                        <button data-open-modal="vendorProfileModal" data-id="<?= (int) $v['id_uti'] ?>"
                                                data-vendor='<?= htmlspecialchars(json_encode($profilePayload), ENT_QUOTES) ?>'
                                                class="js-profile-btn w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center" title="Voir">
                                            <i class="fas fa-eye text-xs"></i>
                                        </button>
                                        <!-- ⚠️ id ci-dessous = dv.id (demande) : à corriger si updateVendor doit cibler `vendeurs` -->
                                        <button data-open-modal="vendorEditModal" data-id="<?= (int) $v['id'] ?>"
                                                data-edit='<?= htmlspecialchars(json_encode([
                                                    'nom_boutique' => $v['nom_boutique'] ?? '',
                                                    'description'  => $v['description'] ?? '',
                                                ]), ENT_QUOTES) ?>'
                                                class="w-8 h-8 rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-100 flex items-center justify-center" title="Modifier">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                        <button data-open-modal="suspendModal" data-id="<?= (int) $v['id'] ?>"
                                                class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100 flex items-center justify-center" title="Suspendre">
                                            <i class="fas fa-pause text-xs"></i>
                                        </button>
                                        <button data-open-modal="deleteModal" data-id="<?= (int) $v['id'] ?>"
                                                class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center" title="Supprimer">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="flex items-center justify-between gap-3 px-4 py-3 border-t border-gray-100 bg-gray-50/50">
                    <span class="text-xs text-gray-500"><?= $totalVendors ?> résultat<?= $totalVendors > 1 ? 's' : '' ?> au total</span>
                </div>
                <!-- La navigation de pagination est injectée automatiquement ici par admin.js (data-paginate) -->
            </div>
        </section>

        <!-- GESTION DES COMMISSIONS ET VERSEMENTS -->
        <section class="mb-8">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-coins text-[#0EA486]"></i> · Gestion des commissions et versements
                </h3>
                <div class="flex gap-2">
                    <button disabled title="Non implémenté côté serveur"
                            class="px-3 py-2 rounded-lg bg-gray-100 text-gray-400 text-xs font-medium flex items-center gap-2 cursor-not-allowed">
                        <i class="fas fa-file-csv"></i> Export versements CSV
                    </button>
                </div>
            </div>

            <?php
                $vendorsWithBalance = array_filter($vendors, fn($v) => (float) $v['solde_portefeuille'] > 0);
                $totalDue = array_sum(array_column($vendors, 'solde_portefeuille'));
            ?>
            <!-- Vue globale -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">TOTAL</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= $this->formatCurrency((float) $totalDue) ?></p>
                    <p class="text-xs text-gray-400 mt-1">Soldes dus à tous les vendeurs</p>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-orange-600 bg-orange-50 px-2 py-1 rounded-full">ALERTE</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= count($vendorsWithBalance) ?></p>
                    <p class="text-xs text-gray-400 mt-1">Vendeurs avec un solde à verser</p>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">TOTAL GAGNÉ</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]"><?= $this->formatCurrency((float) array_sum(array_column($vendors, 'total_gagne'))) ?></p>
                    <p class="text-xs text-gray-400 mt-1">Cumul gagné par les vendeurs</p>
                </div>
            </div>

            <!-- Tableau des versements par vendeur -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h4 class="text-sm font-semibold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-money-bill-wave text-[#0EA486]"></i> Versements par vendeur
                    </h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-3">Vendeur</th>
                                <th class="px-4 py-3">Solde à verser</th>
                                <th class="px-4 py-3">Statut</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if (empty($vendorsWithBalance)): ?>
                            <tr><td colspan="4" class="px-4 py-6 text-center text-sm text-gray-400">Aucun versement en attente.</td></tr>
                            <?php else: foreach ($vendorsWithBalance as $v): ?>
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-store text-emerald-400"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-[#0F172A] text-sm"><?= htmlspecialchars($v['nom_boutique'] ?? 'N/A') ?></p>
                                            <p class="text-[11px] text-gray-400"><?= htmlspecialchars($v['full_name']) ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-xs font-semibold text-[#0EA486]"><?= htmlspecialchars($v['solde_formatted']) ?></td>
                                <td class="px-4 py-3">
                                    <span class="text-[10px] font-semibold text-orange-700 bg-orange-100 px-2 py-1 rounded-full">En attente</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <button data-open-modal="paymentModal" data-id="<?= (int) $v['id'] ?>"
                                                class="w-8 h-8 rounded-lg bg-[#0EA486]/10 text-[#0EA486] hover:bg-[#0EA486] hover:text-white flex items-center justify-center transition" title="Marquer versement effectué">
                                            <i class="fas fa-check text-xs"></i>
                                        </button>
                                        <!-- TODO: aucune méthode d'historique de versements dans VendorsTrait.php -->
                                        <button disabled title="Non implémenté côté serveur"
                                                class="w-8 h-8 rounded-lg bg-gray-50 text-gray-300 flex items-center justify-center cursor-not-allowed" title="Historique">
                                            <i class="fas fa-history text-xs"></i>
                                        </button>
                                    </div>
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

    <!-- MODAL : INTERFACE DE REVUE DEMANDE -->
    <div id="reviewModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-5xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#0EA486]/5 to-transparent">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-user-plus text-[#0EA486]"></i> Revue de demande vendeur
                    </h3>
                    <p class="text-xs text-gray-400">Détail de la demande avant décision</p>
                </div>
                <button data-close-modal class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="overflow-y-auto p-6 space-y-5">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl p-5 border border-gray-100">
                            <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                                <i class="fas fa-user text-[#0EA486]"></i> Informations du demandeur
                            </h5>
                            <div class="space-y-3">
                                <div class="flex justify-between py-2 border-b border-gray-50">
                                    <span class="text-xs text-gray-500">Nom complet</span>
                                    <span class="text-sm font-medium text-[#0F172A]" data-field="full_name">—</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-50">
                                    <span class="text-xs text-gray-500">Email</span>
                                    <span class="text-sm font-medium text-[#0F172A]" data-field="email">—</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-50">
                                    <span class="text-xs text-gray-500">Téléphone</span>
                                    <span class="text-sm font-medium text-[#0F172A]" data-field="telephone">—</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-50">
                                    <span class="text-xs text-gray-500">Date de soumission</span>
                                    <span class="text-sm font-medium text-[#0F172A]" data-field="date_formatted">—</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="bg-white rounded-2xl p-4 border border-gray-100">
                            <h5 class="text-xs font-semibold text-gray-400 uppercase mb-2">Boutique proposée</h5>
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-12 h-12 bg-[#0EA486] rounded-xl flex items-center justify-center text-white font-semibold text-lg" data-field="initiale">—</div>
                                <div>
                                    <p class="text-sm font-semibold text-[#0F172A]" data-field="nom_boutique">—</p>
                                    <p class="text-[11px] text-gray-400" data-field="categorie">—</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description complète -->
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-2 flex items-center gap-2">
                        <i class="fas fa-align-left text-[#0EA486]"></i> Description de la boutique
                    </h5>
                    <p class="text-sm text-gray-600 leading-relaxed" data-field="description">—</p>
                </div>

                <!-- Commentaires internes : désactivé, aucune méthode addVendorNote côté serveur -->
                <div class="bg-white rounded-2xl p-4 border border-gray-100 opacity-60">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-lock text-[#0EA486]"></i> Commentaires internes
                        <span class="text-[10px] font-normal normal-case text-gray-400">(bientôt disponible)</span>
                    </h5>
                    <textarea rows="3" disabled placeholder="Fonctionnalité pas encore implémentée côté serveur..." class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm resize-none cursor-not-allowed"></textarea>
                </div>
            </div>

            <!-- Actions de modération -->
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-wrap items-center justify-between gap-3">
                <span class="text-xs text-gray-500">Décision :</span>
                <div class="flex flex-wrap items-center gap-2">
                    <!-- TODO: aucune méthode requestVendorInfo côté serveur -->
                    <button disabled title="Non implémenté côté serveur"
                            class="px-4 py-2.5 rounded-xl bg-gray-100 text-gray-400 text-sm font-semibold flex items-center gap-2 cursor-not-allowed">
                        <i class="fas fa-question-circle"></i> Demander infos
                    </button>
                    <button data-open-modal="rejectModal" class="px-4 py-2.5 rounded-xl bg-red-100 hover:bg-red-200 text-red-700 text-sm font-semibold flex items-center gap-2 transition">
                        <i class="fas fa-times-circle"></i> Refuser
                    </button>
                    <!-- approveVendor attend l'id en route param -> bouton data-action, pas un formulaire -->
                    <button data-action="admin/approveVendor" data-confirm="Approuver cette demande vendeur ?"
                            data-success="Vendeur approuvé" class="js-approve-btn px-5 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                        <i class="fas fa-check-circle"></i> Approuver
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL  : FICHE VENDEUR DÉTAILLÉE -->
    <div id="vendorProfileModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-5xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#0EA486]/5 to-transparent">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-store text-[#0EA486]"></i> Fiche vendeur détaillée
                    </h3>
                    <p class="text-xs text-gray-400">Informations complètes du vendeur</p>
                </div>
                <button data-close-modal class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="overflow-y-auto p-6 space-y-5">
                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl p-5 border border-emerald-100">
                    <div class="flex flex-col md:flex-row items-start md:items-center gap-4">
                        <div class="w-16 h-16 bg-[#0EA486] rounded-2xl flex items-center justify-center text-white font-bold text-2xl" data-field="initiale">—</div>
                        <div class="flex-1">
                            <h4 class="text-lg font-bold text-[#0F172A]" data-field="nom_boutique">—</h4>
                            <p class="text-xs text-gray-500 mt-1">ID utilisateur <span data-field="id_uti">—</span> · Demande du <span data-field="date_formatted">—</span></p>
                            <div class="flex flex-wrap gap-2 mt-2">
                                <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-1 rounded-full" data-field="statut_label">—</span>
                                <span class="text-[10px] font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-full"><span data-field="nb_produits">—</span> produits</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-[#0EA486]" data-field="total_gagne_formatted">—</p>
                            <p class="text-xs text-gray-500">CA total</p>
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-100">
                    <nav class="flex gap-4">
                        <button class="vendor-tab-btn active px-4 py-2 text-sm font-semibold text-[#0EA486] border-b-2 border-[#0EA486]" data-tab="info">Informations</button>
                        <button class="vendor-tab-btn px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700" data-tab="products">Produits</button>
                        <button class="vendor-tab-btn px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700" data-tab="sales">Ventes</button>
                        <button class="vendor-tab-btn px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700" data-tab="notes">Notes</button>
                    </nav>
                </div>

                <div class="vendor-tab-content" data-tab="info">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white rounded-2xl p-4 border border-gray-100">
                            <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                                <i class="fas fa-store text-[#0EA486]"></i> Informations boutique
                            </h5>
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between py-2 border-b border-gray-50">
                                    <span class="text-gray-500">Nom</span>
                                    <span class="font-medium text-[#0F172A]" data-field="nom_boutique">—</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-50">
                                    <span class="text-gray-500">Description</span>
                                    <span class="font-medium text-[#0F172A] text-right max-w-[200px] truncate" data-field="description">—</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-50">
                                    <span class="text-gray-500">Catégorie</span>
                                    <span class="font-medium text-[#0F172A]" data-field="categorie">—</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-50">
                                    <span class="text-gray-500">Téléphone</span>
                                    <span class="font-medium text-[#0F172A]" data-field="telephone">—</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl p-4 border border-gray-100">
                            <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                                <i class="fas fa-wallet text-[#0EA486]"></i> Portefeuille
                            </h5>
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between py-2 border-b border-gray-50">
                                    <span class="text-gray-500">Solde actuel</span>
                                    <span class="font-semibold text-[#0EA486]" data-field="solde_formatted">—</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-50">
                                    <span class="text-gray-500">Total gagné</span>
                                    <span class="font-semibold text-[#0F172A]" data-field="total_gagne_formatted">—</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab: Produits (rempli en AJAX via admin/getVendorDetails?id=<id_uti>) -->
                <div class="vendor-tab-content hidden" data-tab="products">
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                        <div class="overflow-x-auto" data-products-table>
                            <div class="p-4 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>Chargement...</div>
                        </div>
                    </div>
                </div>

                <!-- Tab: Ventes (rempli en AJAX) -->
                <div class="vendor-tab-content hidden" data-tab="sales">
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                        <div class="overflow-x-auto" data-sales-table>
                            <div class="p-4 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>Chargement...</div>
                        </div>
                    </div>
                </div>

                <!-- Tab: Notes : désactivé, aucune méthode addVendorNote côté serveur -->
                <div class="vendor-tab-content hidden" data-tab="notes">
                    <div class="bg-white rounded-2xl p-4 border border-gray-100 opacity-60">
                        <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                            <i class="fas fa-sticky-note text-[#0EA486]"></i> Notes internes admin
                            <span class="text-[10px] font-normal normal-case text-gray-400">(bientôt disponible)</span>
                        </h5>
                        <textarea rows="5" disabled placeholder="Fonctionnalité pas encore implémentée côté serveur..." class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm resize-none cursor-not-allowed"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL : REFUSER DEMANDE -->
    <div id="rejectModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <form data-ajax data-action="admin/rejectVendor" data-success="Demande refusée">
                <input type="hidden" name="id" value="">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                            <i class="fas fa-times-circle text-red-500"></i> Refuser la demande
                        </h3>
                        <p class="text-xs text-gray-400">Motif obligatoire</p>
                    </div>
                    <button type="button" data-close-modal class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Motif du refus <span class="text-red-500">*</span></label>
                        <textarea name="motif" rows="4" placeholder="Expliquez la raison du refus..." class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:bg-white resize-none" required></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                    <button type="button" data-close-modal class="px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">Annuler</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                        <i class="fas fa-times"></i> Confirmer le refus
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL : MARQUER VERSEMENT -->
    <div id="paymentModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <form data-ajax data-action="admin/markPayment" data-success="Versement enregistré">
                <input type="hidden" name="id" value="">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                            <i class="fas fa-check-circle text-[#0EA486]"></i> Marquer versement effectué
                        </h3>
                        <p class="text-xs text-gray-400">Confirmer le versement au vendeur</p>
                    </div>
                    <button type="button" data-close-modal class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Montant versé (FCFA) <span class="text-red-500">*</span></label>
                        <input type="number" name="montant" min="1" step="0.01" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="Ex: 50000">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Référence transaction <span class="text-red-500">*</span></label>
                        <input type="text" name="reference" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="Ex: TXN-123456">
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                    <button type="button" data-close-modal class="px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">Annuler</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                        <i class="fas fa-check"></i> Confirmer le versement
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL : SUSPENDRE VENDEUR -->
    <div id="suspendModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <form data-ajax data-action="admin/suspendVendor" data-success="Vendeur suspendu">
                <input type="hidden" name="id" value="">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                            <i class="fas fa-pause text-yellow-500"></i> Suspendre le vendeur
                        </h3>
                        <p class="text-xs text-gray-400">Motif de suspension</p>
                    </div>
                    <button type="button" data-close-modal class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div class="bg-yellow-50 rounded-xl p-4 border border-yellow-100">
                        <p class="text-sm text-yellow-700">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Le vendeur ne pourra plus publier de produits ni recevoir de commandes pendant la suspension.
                        </p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Motif de suspension <span class="text-red-500">*</span></label>
                        <textarea name="motif" rows="4" placeholder="Expliquez la raison de la suspension..." class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-yellow-500 focus:bg-white resize-none" required></textarea>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Durée</label>
                        <!-- valeurs alignées sur suspendVendor() : 'indefinie' ou un nombre de jours -->
                        <select name="duree" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                            <option value="indefinie">Indéterminée</option>
                            <option value="7">7 jours</option>
                            <option value="30">30 jours</option>
                            <option value="90">90 jours</option>
                        </select>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                    <button type="button" data-close-modal class="px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">Annuler</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                        <i class="fas fa-pause"></i> Confirmer la suspension
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL : SUPPRIMER VENDEUR -->
    <div id="deleteModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <form data-ajax data-action="admin/deleteVendor" data-success="Vendeur supprimé">
                <input type="hidden" name="id" value="">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                            <i class="fas fa-trash text-red-500"></i> Supprimer le vendeur
                        </h3>
                        <p class="text-xs text-gray-400">Action irréversible</p>
                    </div>
                    <button type="button" data-close-modal class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div class="bg-red-50 rounded-xl p-4 border border-red-100">
                        <p class="text-sm text-red-700">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Attention :</strong> le serveur refuse la suppression si le vendeur a encore des produits actifs.
                        </p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Tapez "SUPPRIMER" pour confirmer</label>
                        <input type="text" name="confirmation" pattern="SUPPRIMER" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:bg-white" placeholder="SUPPRIMER">
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                    <button type="button" data-close-modal class="px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">Annuler</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                        <i class="fas fa-trash"></i> Supprimer définitivement
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL : MODIFIER VENDEUR -->
    <div id="vendorEditModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A]">Modifier le vendeur</h3>
                    <p class="text-xs text-gray-400">Modifier les informations de la boutique</p>
                </div>
                <button type="button" data-close-modal class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- updateVendor() ne lit que nom_boutique / description / statut : les autres champs
                 du formulaire d'origine (opérateur, numéro mobile money, logo) ne sont PAS
                 encore persistés côté serveur. Retirés ici pour ne pas présenter une UI trompeuse ;
                 à réintroduire quand updateVendor() les gérera. -->
            <form data-ajax data-action="admin/updateVendor" data-success="Vendeur mis à jour" class="p-6 overflow-y-auto space-y-4">
                <input type="hidden" name="id" value="">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Nom de la boutique <span class="text-red-500">*</span></label>
                        <input type="text" name="nom_boutique" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Description</label>
                        <textarea name="description" rows="3" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white resize-none"></textarea>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Statut</label>
                        <select name="statut" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                            <option value="actif">Actif</option>
                            <option value="suspendu">Suspendu</option>
                            <option value="banni">Banni</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                    <button type="button" data-close-modal class="px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">Annuler</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Sidebar mobile (indépendant du système de modales de admin.js)
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
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768 && sidebar.classList.contains('open')) closeSidebar();
            });
        })();

        // NB : ouverture/fermeture des modales (data-open-modal/data-close-modal), actions
        // AJAX (data-action / data-ajax / data-confirm), recherche (data-search-input),
        // filtres (data-filter), bulk (data-bulk-*) et pagination (data-paginate) sont
        // déjà gérés globalement par assets/JS/admin.js. Il ne reste ici que la logique
        // propre à cette page.

        // 1. Propagation de l'id (et du payload data-vendor) vers les modales imbriquées.
        //    Ex: "Revoir" ouvre reviewModal avec l'id X -> les boutons "Approuver / Refuser"
        //    à l'intérieur, et les formulaires data-ajax de la modale, héritent de cet id.
        (function() {
            document.body.addEventListener('click', function(e) {
                const trigger = e.target.closest('[data-open-modal]');
                if (!trigger) return;

                const id = trigger.getAttribute('data-id');
                const modalId = trigger.getAttribute('data-open-modal');
                const modal = document.getElementById(modalId);
                if (!modal || !id) return;

                modal.dataset.currentId = id;
                modal.querySelectorAll('input[name="id"]').forEach(input => input.value = id);
                modal.querySelectorAll('[data-open-modal]').forEach(nested => nested.setAttribute('data-id', id));
                // Le bouton "Approuver" (data-action, pas data-open-modal) doit aussi hériter de l'id
                modal.querySelectorAll('[data-action]').forEach(actionBtn => actionBtn.setAttribute('data-id', id));
            });
        })();

        // 2. Remplissage synchrone des champs [data-field] à partir de data-vendor (JSON déjà
        //    présent côté serveur, pas besoin d'un aller-retour AJAX pour ces infos statiques).
        (function() {
            document.body.addEventListener('click', function(e) {
                const trigger = e.target.closest('[data-vendor]');
                if (!trigger) return;
                const modalId = trigger.getAttribute('data-open-modal');
                const modal = document.getElementById(modalId);
                if (!modal) return;

                let payload;
                try { payload = JSON.parse(trigger.getAttribute('data-vendor')); } catch (_) { return; }

                modal.querySelectorAll('[data-field]').forEach(el => {
                    const field = el.getAttribute('data-field');
                    if (payload[field] !== undefined && payload[field] !== null && payload[field] !== '') {
                        el.textContent = payload[field];
                    }
                });
            });
        })();

        // 3. Tabs de la fiche vendeur (vendorProfileModal)
        (function() {
            const modal = document.getElementById('vendorProfileModal');
            if (!modal) return;

            const tabBtns = modal.querySelectorAll('.vendor-tab-btn');
            const tabContents = modal.querySelectorAll('.vendor-tab-content');

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
                        content.classList.toggle('hidden', content.getAttribute('data-tab') !== tabName);
                    });
                });
            });
        })();

        // 4. Chargement AJAX des produits/ventes (admin/getVendorDetails?id=<id_uti>)
        (function() {
            const modal = document.getElementById('vendorProfileModal');
            if (!modal) return;

            document.body.addEventListener('click', function(e) {
                const btn = e.target.closest('.js-profile-btn');
                if (!btn) return;
                const idUti = btn.getAttribute('data-id'); // id_uti, cf. commentaire sur le bouton
                if (!idUti) return;

                const productsBox = modal.querySelector('[data-products-table]');
                const salesBox = modal.querySelector('[data-sales-table]');
                productsBox.innerHTML = '<div class="p-4 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>Chargement...</div>';
                salesBox.innerHTML = '<div class="p-4 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>Chargement...</div>';

                fetch(window.NDIGIT_BASE_URL + '/index.php?route=admin/getVendorDetails&id=' + idUti)
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success) {
                            productsBox.innerHTML = '<div class="p-4 text-center text-red-500">Erreur de chargement</div>';
                            salesBox.innerHTML = '<div class="p-4 text-center text-red-500">Erreur de chargement</div>';
                            return;
                        }

                        if (!data.produits.length) {
                            productsBox.innerHTML = '<div class="p-4 text-center text-sm text-gray-400">Aucun produit.</div>';
                        } else {
                            let html = '<table class="w-full text-sm"><tbody class="divide-y divide-gray-100">';
                            data.produits.forEach(p => {
                                html += `<tr>
                                    <td class="px-4 py-3 text-sm font-medium text-[#0F172A]">${p.nom_produit}</td>
                                    <td class="px-4 py-3 text-xs text-gray-500">${p.stock ?? ''}</td>
                                    <td class="px-4 py-3 text-xs font-semibold text-[#0EA486] text-right">${p.prix} FCFA</td>
                                </tr>`;
                            });
                            html += '</tbody></table>';
                            productsBox.innerHTML = html;
                        }

                        if (!data.ventes.length) {
                            salesBox.innerHTML = '<div class="p-4 text-center text-sm text-gray-400">Aucune vente.</div>';
                        } else {
                            let html = '<table class="w-full text-sm"><tbody class="divide-y divide-gray-100">';
                            data.ventes.forEach(v => {
                                html += `<tr>
                                    <td class="px-4 py-3">
                                        <p class="text-sm font-medium text-[#0F172A]">Commande #${v.id}</p>
                                        <p class="text-[11px] text-gray-400">${v.date_commande}</p>
                                    </td>
                                    <td class="px-4 py-3 text-xs font-semibold text-[#0F172A] text-right">${v.montant_total} FCFA</td>
                                </tr>`;
                            });
                            html += '</tbody></table>';
                            salesBox.innerHTML = html;
                        }
                    })
                    .catch(() => {
                        productsBox.innerHTML = '<div class="p-4 text-center text-red-500">Erreur de chargement</div>';
                        salesBox.innerHTML = '<div class="p-4 text-center text-red-500">Erreur de chargement</div>';
                    });
            });
        })();

                // 5. Gestion de la fermeture des modales (data-close-modal)
        (function() {
            document.body.addEventListener('click', function(e) {
                const closeBtn = e.target.closest('[data-close-modal]');
                if (!closeBtn) return;

                // Trouver la modale parente
                const modal = closeBtn.closest('.fixed.inset-0');
                if (!modal) return;

                // Fermer la modale
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';

                // Empêcher la soumission du formulaire si le bouton est dans un form
                e.preventDefault();
                e.stopPropagation();
            });

            // Fermer aussi en cliquant sur le fond (backdrop)
            document.body.addEventListener('click', function(e) {
                if (e.target.classList.contains('fixed') && 
                    e.target.classList.contains('inset-0') && 
                    e.target.classList.contains('items-center') &&
                    e.target.classList.contains('justify-center')) {
                    
                    e.target.classList.add('hidden');
                    e.target.classList.remove('flex');
                    document.body.style.overflow = '';
                }
            });

            // Fermer avec la touche Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const openModals = document.querySelectorAll('.fixed.inset-0:not(.hidden)');
                    openModals.forEach(modal => {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    });
                    document.body.style.overflow = '';
                }
            });
        })();
    </script>
</body>
</html>