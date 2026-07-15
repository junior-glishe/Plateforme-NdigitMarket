<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NDIGITMARKET Admin · Gestion utilisateur</title>
    <link rel="icon" type="image/png" href="/ndigitmarket/assets/images/favi.png">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/CSS/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
</head>

<body>
    <?php $currentPage = 'gestion-utilisateurs';
    require_once __DIR__ . '/../components/sidebar.php'; ?>


    <main class="md:ml-[280px] min-h-screen p-4 md:p-8 transition-all">

        <!-- HEADER -->
        <header class="flex items-center justify-between mb-6 bg-white/80 backdrop-blur-sm sticky top-0 z-30 py-4 px-4 md:px-6 rounded-2xl shadow-sm border border-gray-100/50">
            <div class="flex items-center gap-4">
                <button id="hamburgerBtn" class="md:hidden w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 transition flex items-center justify-center text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-[#0F172A]">Gestion utilisateur</h2>
                    <p class="text-xs text-gray-400 hidden sm:block">Liste, fiches et actions sur les utilisateurs</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                
                <div class="w-9 h-9 rounded-full bg-[#0EA486] text-white flex items-center justify-center font-semibold text-sm">
                    A
                </div>
            </div>
        </header>

        <!--  LISTE DES UTILISATEURS -->
        <section class="mb-6">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-list-ul text-[#0EA486]"></i> · Liste des utilisateurs
                </h3>
                <?php
                $exportParams = $_GET;
                unset($exportParams['route']);
                $csvUrl = (defined('BASE_URL') ? BASE_URL : '/ndigitmarket') . '/index.php?route=admin/exportUsers&format=csv';
                $excelUrl = (defined('BASE_URL') ? BASE_URL : '/ndigitmarket') . '/index.php?route=admin/exportUsers&format=excel';
                if ($exportParams) {
                    $csvUrl .= '&' . http_build_query($exportParams);
                    $excelUrl .= '&' . http_build_query($exportParams);
                }
                ?>
                <div class="flex gap-2">
                    <a href="<?= htmlspecialchars($csvUrl) ?>" class="px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2">
                        <i class="fas fa-file-csv"></i> Export CSV
                    </a>
                    <a href="<?= htmlspecialchars($excelUrl) ?>" class="px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                    <button type="button" id="openFormBtn" data-open-modal="formModal" class="px-4 py-2 rounded-lg bg-[#0EA486] hover:bg-[#0c8f75] text-white text-xs font-semibold flex items-center gap-2 shadow-sm transition">
                        <i class="fas fa-plus"></i> Nouvel utilisateur
                    </button>
                </div>
            </div>

            <!-- Barre de recherche + filtres -->
            <form method="GET" data-filter class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm mb-4">
                <input type="hidden" name="route" value="admin/gestion-utilisateurs">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex-1 min-w-[220px] relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" name="search" data-search-input="search"
                            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                            placeholder="Rechercher par nom, email ou ID..."
                            class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white transition">
                    </div>
                    <select name="type" class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 focus:outline-none focus:border-[#0EA486]">
                        <option value="">Tous les rôles</option>
                        <option value="-" <?= ($_GET['type'] ?? '') === '-'     ? 'selected' : '' ?>>Acheteur</option>
                        <option value="pro" <?= ($_GET['type'] ?? '') === 'pro'   ? 'selected' : '' ?>>Vendeur Pro</option>
                        <option value="admin" <?= ($_GET['type'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                    <select name="statut" class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 focus:outline-none focus:border-[#0EA486]">
                        <option value="">Tous les statuts</option>
                        <option value="actif" <?= ($_GET['statut'] ?? '') === 'actif'      ? 'selected' : '' ?>>Actif</option>
                        <option value="bloque" <?= ($_GET['statut'] ?? '') === 'bloque'     ? 'selected' : '' ?>>Bloqué</option>
                        <option value="en_attente" <?= ($_GET['statut'] ?? '') === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                    </select>
                    <a href="?route=admin/gestion-utilisateurs" class="px-3 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm flex items-center gap-2">
                        <i class="fas fa-times"></i> Réinitialiser
                    </a>
                </div>

                <div data-bulk-bar class="hidden flex-wrap items-center gap-2 mt-3 pt-3 border-t border-gray-100">
                    <span class="text-xs text-gray-500"><b data-bulk-count>0</b> sélectionné(s)</span>
                    <span class="text-xs text-gray-400">·</span>
                    <button type="button" data-bulk-action="admin/bulkBlock" data-confirm="Bloquer les utilisateurs sélectionnés ?" class="px-3 py-1.5 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100 text-xs font-medium flex items-center gap-1.5"><i class="fas fa-pause-circle"></i> Suspendre</button>
                    <button type="button" data-bulk-action="admin/bulkUnblock" data-confirm="Réactiver les utilisateurs sélectionnés ?" class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 text-xs font-medium flex items-center gap-1.5"><i class="fas fa-play-circle"></i> Réactiver</button>
                    <button type="button" data-bulk-action="admin/bulkPromote" data-confirm="Promouvoir les utilisateurs sélectionnés en vendeurs Pro ?" class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 text-xs font-medium flex items-center gap-1.5"><i class="fas fa-user-plus"></i> Promouvoir</button>
                    <button type="button" data-bulk-action="admin/bulkDelete" data-confirm="Supprimer définitivement les utilisateurs sélectionnés ?" class="px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 text-xs font-medium flex items-center gap-1.5"><i class="fas fa-trash"></i> Supprimer</button>
                </div>
            </form>

            <!-- Tableau -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" data-paginate data-page-size="25">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-3 w-10"><input type="checkbox" data-bulk-master class="w-4 h-4 rounded border-gray-300"></th>
                                <th class="px-4 py-3" data-sort-col="id_uti">ID <i class="fas fa-sort text-[10px] ml-1"></i></th>
                                <th class="px-4 py-3" data-sort-col="nom">Nom complet <i class="fas fa-sort text-[10px] ml-1"></i></th>
                                <th class="px-4 py-3" data-sort-col="email">Email <i class="fas fa-sort text-[10px] ml-1"></i></th>
                                <th class="px-4 py-3" data-sort-col="type">Rôle <i class="fas fa-sort text-[10px] ml-1"></i></th>
                                <th class="px-4 py-3" data-sort-col="statut">Statut <i class="fas fa-sort text-[10px] ml-1"></i></th>
                                <th class="px-4 py-3" data-sort-col="orders_count">Nb commandes <i class="fas fa-sort text-[10px] ml-1"></i></th>
                                <th class="px-4 py-3" data-sort-col="ca_generated">CA généré <i class="fas fa-sort text-[10px] ml-1"></i></th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)) : ?>
                                <?php foreach ($users as $user) : ?>
                                    <?php $uid = (int) $user['id_uti']; ?>
                                    <tr class="border-b border-gray-100 hover:bg-gray-50/60">
                                        <td class="px-4 py-4"><input type="checkbox" data-bulk-checkbox value="<?= $uid ?>" class="w-4 h-4 rounded border-gray-300"></td>
                                        <td class="px-4 py-4 text-gray-700"><?= $uid ?></td>
                                        <td class="px-4 py-4 text-gray-700 font-medium"><?= htmlspecialchars($user['full_name'] ?: ($user['nom'] ?? '')) ?></td>
                                        <td class="px-4 py-4 text-gray-700"><?= htmlspecialchars($user['email'] ?? '') ?></td>
                                        <td class="px-4 py-4 text-gray-700"><?= htmlspecialchars($user['role_label']) ?></td>
                                        <td class="px-4 py-4">
                                            <span class="px-2 py-1 rounded-lg text-xs font-medium <?= $user['statut'] === 'actif' ? 'bg-emerald-50 text-emerald-600' : ($user['statut'] === 'bloque' ? 'bg-red-50 text-red-600' : 'bg-yellow-50 text-yellow-600') ?>">
                                                <?= htmlspecialchars($user['status_label']) ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-gray-700"><?= (int) $user['orders_count'] ?></td>
                                        <td class="px-4 py-4 text-gray-700"><?= htmlspecialchars($user['ca_generated']) ?> FCFA</td>
                                        <td class="px-4 py-4 text-right">
                                            <div class="inline-flex items-center gap-1">
                                                <button type="button" data-open-modal="formModal"
                                                    data-edit='<?= htmlspecialchars(json_encode(["id" => $uid, "prenom" => $user["prenom"] ?? "", "nom" => $user["nom"] ?? "", "email" => $user["email"] ?? "", "type" => $user["role"] ?? "-", "statut" => $user["statut"] ?? "actif"]), ENT_QUOTES) ?>'
                                                    class="px-2 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs" title="Modifier">
                                                    <i class="fas fa-pen"></i>
                                                </button>
                                                <?php if ($user['statut'] === 'bloque') : ?>
                                                    <button type="button" data-action="admin/unblockUser" data-id="<?= $uid ?>" data-confirm="Réactiver cet utilisateur ?" data-success="Utilisateur réactivé" class="px-2 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 text-xs" title="Débloquer"><i class="fas fa-play-circle"></i></button>
                                                <?php else : ?>
                                                    <button type="button" data-action="admin/blockUser" data-id="<?= $uid ?>" data-confirm="Bloquer cet utilisateur ?" data-success="Utilisateur bloqué" class="px-2 py-1.5 rounded-lg bg-yellow-50 hover:bg-yellow-100 text-yellow-600 text-xs" title="Bloquer"><i class="fas fa-pause-circle"></i></button>
                                                <?php endif; ?>
                                                <?php if (($user['role'] ?? '') !== 'pro') : ?>
                                                    <button type="button" data-action="admin/promoteUser" data-id="<?= $uid ?>" data-confirm="Promouvoir en vendeur Pro ?" data-success="Utilisateur promu" class="px-2 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 text-xs" title="Promouvoir"><i class="fas fa-user-plus"></i></button>
                                                <?php endif; ?>
                                                <button type="button" data-action="admin/deleteUser" data-id="<?= $uid ?>" data-confirm="Supprimer définitivement cet utilisateur ?" data-success="Utilisateur supprimé" class="px-2 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 text-xs" title="Supprimer"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="9">
                                        <div class="flex flex-col items-center justify-center py-16">
                                            <i class="fas fa-users empty-icon text-5xl text-gray-300"></i>
                                            <p class="text-sm text-gray-400 mt-4 font-medium">Aucun utilisateur trouvé</p>
                                            <button type="button" data-open-modal="formModal" id="openFormBtn2" class="mt-5 px-4 py-2 rounded-lg bg-[#0EA486] hover:bg-[#0c8f75] text-white text-xs font-semibold flex items-center gap-2 transition">
                                                <i class="fas fa-plus"></i> Créer le premier utilisateur
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between px-4 py-3 border-t border-gray-100 bg-gray-50/50 text-xs text-gray-500">
                    <span><?= (int) ($totalUsers ?? count($users)) ?> utilisateur(s) au total</span>
                </div>
            </div>
        </section>



        <!-- ACTIONS SUR UN UTILISATEUR -->
        <section class="mb-8">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-tools text-[#0EA486]"></i> · Actions sur un utilisateur
                </h3>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">


                <button type="button" data-open-modal="emailModal" class="text-left bg-white rounded-2xl p-4 border border-gray-100 shadow-sm hover:border-[#0EA486] transition group md:col-span-2">
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 mb-3 group-hover:bg-[#0EA486] group-hover:text-white transition">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <p class="text-sm font-semibold text-[#0F172A]">Envoyer un email personnalisé</p>
                    <p class="text-xs text-gray-400 mt-1">Communication directe depuis l'interface</p>
                </button>
            </div>
        </section>

        <footer class="mt-8 text-center text-xs text-gray-400 border-t border-gray-100 pt-6">
            &copy; 2026 NDIGITMARKET · Administration
        </footer>
    </main>

    <!-- MODAL FORMULAIRE -->
    <div id="formModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A]">Nouvel utilisateur</h3>
                    <p class="text-xs text-gray-400">Remplissez les informations pour créer un compte</p>
                </div>
                <button id="closeFormBtn" data-close-modal="formModal" class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form data-ajax data-action="admin/createUser" data-success="Utilisateur enregistré" class="p-6 overflow-y-auto space-y-4">
                <input type="hidden" name="id" value="">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Prénom <span style="color: red;">*</span></label>
                        <input type="text" name="prenom" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Nom complet <span style="color: red;">*</span></label>
                        <input type="text" name="nom" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Email <span style="color: red;">*</span></label>
                        <input type="email" name="email" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Téléphone</label>
                        <input type="tel" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Rôle <span style="color: red;">*</span></label>
                        <select name="type" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                            <option value="-">Acheteur</option>
                            <option value="pro">Vendeur Pro</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Date d'inscription <span style="color: red;">*</span></label>
                        <input type="date" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Nombre de commandes <span style="color: red;">*</span></label>
                        <input type="number" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                    </div>


                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Chiffre d'affaires <span style="color: red;">*</span></label>
                        <input type="number" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                    </div>


                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Status <span style="color: red;">*</span></label>
                        <select name="statut" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                            <option value="actif">Actif</option>
                            <option value="bloque">Bloqué</option>
                            <option value="en_attente">En attente</option>
                        </select>
                    </div>



                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Pays <span style="color: red;">*</span></label>
                        <select class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                            <option>Sénégal</option>
                            <option>Côte d'Ivoire</option>
                            <option>Cameroun</option>
                            <option>France</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Mot de passe <span class="text-xs text-gray-400">(laisser vide pour ne pas changer)</span></label>
                        <input type="password" name="mdp" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Notes internes (admin) <span style="color: red;">*</span></label>
                        <textarea rows="3" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white resize-none"></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                    <button type="button" id="cancelFormBtn" data-close-modal="formModal" class="px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                        Annuler
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EMAIL PERSONNALISÉ -->
    <div id="emailModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-6xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A]">Envoyer un email personnalisé</h3>
                    <p class="text-xs text-gray-400">Sélectionnez les destinataires et rédigez votre message</p>
                </div>
                <button type="button" data-close-modal="emailModal" class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form data-ajax data-action="admin/sendCustomEmail" data-success="Email envoyé" class="overflow-hidden flex flex-col">
                <div class="grid grid-cols-1 lg:grid-cols-[380px_1fr] min-h-0">
                    <section class="border-r border-gray-100 p-5 min-h-0">
                        <div class="flex items-center justify-between gap-3 mb-3">
                            <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Utilisateurs</h4>
                            <label class="text-xs text-gray-500 flex items-center gap-2">
                                <input type="checkbox" data-email-select-all class="w-4 h-4 rounded border-gray-300">
                                Tous
                            </label>
                        </div>
                        <div class="relative mb-3">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" data-email-search placeholder="Rechercher un utilisateur..."
                                class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                        </div>
                        <div class="max-h-[54vh] overflow-y-auto border border-gray-100 rounded-xl divide-y divide-gray-100">
                            <?php foreach (($users ?? []) as $emailUser): ?>
                                <?php
                                $emailUid = (int)($emailUser['id_uti'] ?? 0);
                                $emailName = trim(($emailUser['prenom'] ?? '') . ' ' . ($emailUser['nom'] ?? ''));
                                $emailAddress = (string)($emailUser['email'] ?? '');
                                ?>
                                <label data-email-user="<?= htmlspecialchars(mb_strtolower($emailName . ' ' . $emailAddress . ' ' . $emailUid)) ?>" class="flex items-center gap-3 px-3 py-3 hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" name="user_ids[]" value="<?= $emailUid ?>" class="w-4 h-4 rounded border-gray-300">
                                    <span class="min-w-0">
                                        <span class="block text-sm font-medium text-[#0F172A] truncate"><?= htmlspecialchars($emailName ?: 'Utilisateur #' . $emailUid) ?></span>
                                        <span class="block text-xs text-gray-400 truncate"><?= htmlspecialchars($emailAddress) ?></span>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </section>

                    <section class="p-5 overflow-y-auto min-h-0 space-y-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Sujet <span class="text-red-500">*</span></label>
                            <input type="text" name="subject" required placeholder="Ex: Mise à jour importante sur votre compte"
                                class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Message <span class="text-red-500">*</span></label>
                            <textarea id="customEmailMessage" name="message" rows="14" required
                                class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white"></textarea>
                            <p class="text-[11px] text-gray-400 mt-2">Variables disponibles : {{prenom}}, {{nom}}, {{nom_complet}}, {{email}}</p>
                        </div>
                    </section>
                </div>

                <div class="flex items-center justify-between gap-2 px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    <span class="text-xs text-gray-500"><b data-email-selected-count>0</b> destinataire(s) sélectionné(s)</span>
                    <div class="flex items-center gap-2">
                        <button type="button" data-close-modal="emailModal" class="px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">Annuler</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm">
                            <i class="fas fa-paper-plane"></i> Envoyer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="<?= (defined('BASE_URL') ? BASE_URL : '/ndigitmarket') ?>/assets/js/tinymce/tinymce.min.js"></script>
    <script>
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

        // Gestion du modal formulaire
        (function() {
            const modal = document.getElementById('formModal');
            const openBtns = [document.getElementById('openFormBtn'), document.getElementById('openFormBtn2')];
            const closeBtn = document.getElementById('closeFormBtn');
            const cancelBtn = document.getElementById('cancelFormBtn');

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

            openBtns.forEach(btn => btn && btn.addEventListener('click', openModal));
            closeBtn && closeBtn.addEventListener('click', closeModal);
            cancelBtn && cancelBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
            });
        })();

        // Email personnalisé : recherche, sélection et éditeur riche
        (function() {
            const modal = document.getElementById('emailModal');
            if (!modal) return;

            const search = modal.querySelector('[data-email-search]');
            const selectAll = modal.querySelector('[data-email-select-all]');
            const rows = Array.from(modal.querySelectorAll('[data-email-user]'));
            const count = modal.querySelector('[data-email-selected-count]');
            const checkboxes = () => Array.from(modal.querySelectorAll('input[name="user_ids[]"]'));

            function refreshCount() {
                const checked = checkboxes().filter(c => c.checked).length;
                if (count) count.textContent = checked;
                if (selectAll) {
                    const visible = rows.filter(row => !row.classList.contains('hidden'));
                    const visibleChecked = visible.filter(row => row.querySelector('input')?.checked).length;
                    selectAll.checked = visible.length > 0 && visible.length === visibleChecked;
                }
            }

            search && search.addEventListener('input', function() {
                const term = this.value.trim().toLowerCase();
                rows.forEach(row => row.classList.toggle('hidden', term && !row.dataset.emailUser.includes(term)));
                refreshCount();
            });

            selectAll && selectAll.addEventListener('change', function() {
                rows.forEach(row => {
                    if (!row.classList.contains('hidden')) {
                        const input = row.querySelector('input');
                        if (input) input.checked = selectAll.checked;
                    }
                });
                refreshCount();
            });

            modal.addEventListener('change', function(e) {
                if (e.target.matches('input[name="user_ids[]"]')) refreshCount();
            });

            modal.querySelector('form')?.addEventListener('submit', function() {
                if (window.tinymce) tinymce.triggerSave();
            });

            if (window.tinymce) {
                tinymce.init({
                    selector: '#customEmailMessage',
                    menubar: false,
                    branding: false,
                    height: 320,
                    plugins: 'lists link table autoresize',
                    toolbar: 'undo redo | bold italic underline | bullist numlist | link table | removeformat',
                    content_style: 'body{font-family:Inter,Arial,sans-serif;font-size:14px;line-height:1.6}'
                });
            }
        })();
    </script>

    <script>
        // Si le modal est ouvert avec un id (édition), on switch vers updateUser/{id}
        document.querySelectorAll('[data-open-modal="formModal"]').forEach(btn => {
            btn.addEventListener('click', () => {
                const form = document.querySelector('#formModal form[data-ajax]');
                if (!form) return;
                let payload = {};
                try {
                    payload = JSON.parse(btn.getAttribute('data-edit') || '{}');
                } catch (_) {}
                const id = payload.id;
                const title = document.querySelector('#formModal h3');
                if (id) {
                    form.setAttribute('data-action', 'admin/updateUser/' + id);
                    if (title) title.textContent = 'Modifier l\'utilisateur #' + id;
                } else {
                    form.setAttribute('data-action', 'admin/createUser');
                    if (title) title.textContent = 'Nouvel utilisateur';
                    form.reset();
                }
            });
        });
    </script>

</body>

</html>
