<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NDIGITMARKET Admin · Gestion des Commandes</title>

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
                    <h2 class="text-xl md:text-2xl font-bold text-[#0F172A]">Gestion des Commandes</h2>
                    <p class="text-xs text-gray-400 hidden sm:block">Suivi, remboursement et facturation des commandes</p>
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

        <!-- STATISTIQUES COMMANDES -->
        <section class="mb-6">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-chart-line text-[#0EA486]"></i> · Vue d'ensemble des commandes
                </h3>
            </div>

            <!-- Cartes de statistiques -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">TOTAL</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Commandes totales</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">PAYÉES</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Commandes payées</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-yellow-50 rounded-xl flex items-center justify-center text-yellow-600">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-yellow-600 bg-yellow-50 px-2 py-1 rounded-full">EN ATTENTE</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Paiements en attente</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center text-red-600">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-full">ÉCHOUÉES</span>
                    </div>
                    <p class="text-2xl font-bold text-[#0F172A]">...</p>
                    <p class="text-xs text-gray-400 mt-1">Paiements échoués</p>
                </div>
            </div>
        </section>

        <!-- LISTE DES COMMANDES -->
        <section class="mb-6">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-list text-[#0EA486]"></i> · Liste des commandes
                </h3>
                <div class="flex gap-2">
                    <button id="openAdvancedFiltersBtn" class="px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2">
                        <i class="fas fa-filter"></i> Filtres avancés
                    </button>
                    <button id="exportCsvBtn" class="px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2">
                        <i class="fas fa-file-csv"></i> Export CSV
                    </button>
                </div>
            </div>

            <!-- Barre de recherche + filtres -->
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm mb-4">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex-1 min-w-[220px] relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" placeholder="Rechercher par ID commande, acheteur, email ou produit..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white transition">
                    </div>
                    <select class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 focus:outline-none focus:border-[#0EA486]">
                        <option>Tous les statuts paiement</option>
                        <option>Payé</option>
                        <option>En attente</option>
                        <option>Échoué</option>
                        <option>Remboursé</option>
                    </select>
                    <select class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 focus:outline-none focus:border-[#0EA486]">
                        <option>Tous les statuts livraison</option>
                        <option>Livré</option>
                        <option>En cours</option>
                        <option>Problème</option>
                    </select>
                    <select class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 focus:outline-none focus:border-[#0EA486]">
                        <option>Tous les modes de paiement</option>
                        <option>FedaPay</option>
                        <option>Mobile Money</option>
                        <option>Autre</option>
                    </select>
                    <select class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 focus:outline-none focus:border-[#0EA486]">
                        <option>Tous les vendeurs</option>
                    </select>
                    <button class="px-3 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm flex items-center gap-2">
                        <i class="fas fa-calendar-alt"></i> Plage de dates
                    </button>
                </div>
            </div>

            <!-- Tableau -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-3 w-10"><input type="checkbox" class="w-4 h-4 rounded border-gray-300"></th>
                                <th class="px-4 py-3 cursor-pointer hover:text-[#0EA486]">ID Commande <i class="fas fa-sort text-[10px] ml-1"></i></th>
                                <th class="px-4 py-3 cursor-pointer hover:text-[#0EA486]">Date <i class="fas fa-sort text-[10px] ml-1"></i></th>
                                <th class="px-4 py-3">Acheteur</th>
                                <th class="px-4 py-3">Produit</th>
                                <th class="px-4 py-3">Vendeur</th>
                                <th class="px-4 py-3 cursor-pointer hover:text-[#0EA486]">Montant <i class="fas fa-sort text-[10px] ml-1"></i></th>
                                <th class="px-4 py-3">Commission plateforme</th>
                                <th class="px-4 py-3">Commission vendeur</th>
                                <th class="px-4 py-3">Mode paiement</th>
                                <th class="px-4 py-3">Statut paiement</th>
                                <th class="px-4 py-3">Statut livraison</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-4 py-3"><input type="checkbox" class="w-4 h-4 rounded border-gray-300"></td>
                                <td class="px-4 py-3">
                                    <span class="text-xs font-mono font-semibold text-[#0F172A]">...</span>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500">...</td>
                                <td class="px-4 py-3">
                                    <div>
                                        <p class="text-xs font-semibold text-[#0F172A]">...</p>
                                        <p class="text-[11px] text-gray-400">...</p>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="#" class="text-xs text-[#0EA486] hover:underline font-medium">...</a>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="#" class="text-xs text-[#0EA486] hover:underline font-medium">...</a>
                                </td>
                                <td class="px-4 py-3 text-xs font-semibold text-[#0F172A]">... FCFA</td>
                                <td class="px-4 py-3 text-xs font-semibold text-gray-600">... FCFA</td>
                                <td class="px-4 py-3 text-xs font-semibold text-[#0EA486]">... FCFA</td>
                                <td class="px-4 py-3">
                                    <span class="text-[10px] font-semibold text-blue-700 bg-blue-100 px-2 py-1 rounded-full">...</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-1 rounded-full">...</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-1 rounded-full">...</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="openOrderDetailBtn w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center" title="Voir détail">
                                            <i class="fas fa-eye text-xs"></i>
                                        </button>
                                        <button class="openRefundBtn w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center" title="Rembourser">
                                            <i class="fas fa-undo text-xs"></i>
                                        </button>
                                        <button class="openInvoiceBtn w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 flex items-center justify-center" title="Générer facture">
                                            <i class="fas fa-file-invoice text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
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
                        <span>1 - 10 sur 142</span>
                        <button class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-50">
                            <i class="fas fa-chevron-left text-[10px]"></i>
                        </button>
                        <button class="w-8 h-8 rounded-lg bg-[#0EA486] text-white flex items-center justify-center">1</button>
                        <button class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-50">2</button>
                        <button class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-50">3</button>
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

    <!-- MODAL 4.6.3 : DÉTAIL COMMANDE -->
    <div id="orderDetailModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-5xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#0EA486]/5 to-transparent">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-receipt text-[#0EA486]"></i> Détail de la commande
                    </h3>
                    <p class="text-xs text-gray-400">Informations complètes de la commande</p>
                </div>
                <button class="closeOrderDetailBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="overflow-y-auto p-6 space-y-5">
                <!-- En-tête commande -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-5 border border-blue-100">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div>
                            <h4 class="text-lg font-bold text-[#0F172A]">Commande #...</h4>
                            <p class="text-xs text-gray-500 mt-1">Passée le ...</p>
                            <div class="flex flex-wrap gap-2 mt-2">
                                <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-1 rounded-full">Payé</span>
                                <span class="text-[10px] font-semibold text-blue-700 bg-blue-100 px-2 py-1 rounded-full">Livré</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-[#0EA486]">... FCFA</p>
                            <p class="text-xs text-gray-500">Montant total</p>
                        </div>
                    </div>
                </div>

                <!-- Infos acheteur et produit -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white rounded-2xl p-4 border border-gray-100">
                        <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                            <i class="fas fa-user text-[#0EA486]"></i> Informations acheteur
                        </h5>
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Nom</span>
                                <span class="font-medium text-[#0F172A]">...</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Email</span>
                                <span class="font-medium text-[#0F172A]">...</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Téléphone</span>
                                <span class="font-medium text-[#0F172A]">...</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-4 border border-gray-100">
                        <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                            <i class="fas fa-box text-[#0EA486]"></i> Informations produit
                        </h5>
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Produit</span>
                                <span class="font-medium text-[#0F172A]">...</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Vendeur</span>
                                <span class="font-medium text-[#0F172A]">...</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500">Catégorie</span>
                                <span class="font-medium text-[#0F172A]">...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paiement et transaction -->
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-credit-card text-[#0EA486]"></i> Informations paiement
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="p-3 bg-gray-50 rounded-xl">
                            <p class="text-[10px] text-gray-400 mb-1">Mode de paiement</p>
                            <p class="text-sm font-semibold text-[#0F172A]">...</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-xl">
                            <p class="text-[10px] text-gray-400 mb-1">Référence transaction</p>
                            <p class="text-sm font-mono font-semibold text-[#0F172A]">...</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-xl">
                            <p class="text-[10px] text-gray-400 mb-1">Date paiement</p>
                            <p class="text-sm font-semibold text-[#0F172A]">...</p>
                        </div>
                    </div>
                </div>

                <!-- Répartition commissions -->
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-coins text-[#0EA486]"></i> Répartition des montants
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="p-3 bg-blue-50 rounded-xl border border-blue-100">
                            <p class="text-[10px] text-blue-600 mb-1">Montant total</p>
                            <p class="text-lg font-bold text-blue-700">... FCFA</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-200">
                            <p class="text-[10px] text-gray-600 mb-1">Commission plateforme (10%)</p>
                            <p class="text-lg font-bold text-gray-700">... FCFA</p>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                            <p class="text-[10px] text-emerald-600 mb-1">Commission vendeur (90%)</p>
                            <p class="text-lg font-bold text-emerald-700">... FCFA</p>
                        </div>
                    </div>
                </div>

                <!-- Lien de téléchargement -->
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-download text-[#0EA486]"></i> Lien de téléchargement
                    </h5>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600">
                            <i class="fas fa-link"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-mono text-gray-600 truncate">https://...</p>
                            <p class="text-[11px] text-gray-400">Généré le ... · Expire le ...</p>
                        </div>
                        <button class="regenerateLinkBtn px-3 py-2 rounded-lg bg-[#0EA486] hover:bg-[#0c8f75] text-white text-xs font-semibold flex items-center gap-1.5 transition">
                            <i class="fas fa-sync-alt"></i> Régénérer
                        </button>
                    </div>
                </div>

                <!-- Historique des statuts -->
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-history text-[#0EA486]"></i> Historique des statuts
                    </h5>
                    <div class="space-y-2">
                        <div class="flex items-start gap-3 p-2 rounded-lg hover:bg-gray-50">
                            <div class="w-7 h-7 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 flex-shrink-0 mt-0.5">
                                <i class="fas fa-shopping-cart text-[10px]"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-[#0F172A]">Commande créée</p>
                                <p class="text-[11px] text-gray-400">...</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-2 rounded-lg hover:bg-gray-50">
                            <div class="w-7 h-7 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 flex-shrink-0 mt-0.5">
                                <i class="fas fa-check text-[10px]"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-[#0F172A]">Paiement confirmé</p>
                                <p class="text-[11px] text-gray-400">...</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-2 rounded-lg hover:bg-gray-50">
                            <div class="w-7 h-7 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 flex-shrink-0 mt-0.5">
                                <i class="fas fa-truck text-[10px]"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-[#0F172A]">Produit livré</p>
                                <p class="text-[11px] text-gray-400">...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500">Actions rapides :</span>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <button class="openRefundBtn px-4 py-2.5 rounded-xl bg-red-100 hover:bg-red-200 text-red-700 text-sm font-semibold flex items-center gap-2 transition">
                        <i class="fas fa-undo"></i> Rembourser
                    </button>
                    <button class="openInvoiceBtn px-5 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                        <i class="fas fa-file-invoice"></i> Générer facture PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL : REMBOURSER COMMANDE -->
    <div id="refundModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-undo text-red-500"></i> Rembourser la commande
                    </h3>
                    <p class="text-xs text-gray-400">Motif obligatoire</p>
                </div>
                <button class="closeRefundBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="bg-red-50 rounded-xl p-4 border border-red-100">
                    <p class="text-sm text-red-700">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Le montant sera remboursé à l'acheteur via le même mode de paiement.
                    </p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Montant à rembourser (FCFA)</label>
                    <input type="number" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:bg-white" placeholder="..." readonly>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Motif du remboursement <span class="text-red-500">*</span></label>
                    <textarea rows="4" placeholder="Expliquez la raison du remboursement..." class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:bg-white resize-none" required></textarea>
                </div>
                <div>
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-red-500 focus:ring-red-500" required>
                        <span>Je confirme vouloir rembourser cette commande</span>
                    </label>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                <button class="closeRefundBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                    Annuler
                </button>
                <button class="px-5 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                    <i class="fas fa-undo"></i> Confirmer le remboursement
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL : GÉNÉRER FACTURE -->
    <div id="invoiceModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-file-invoice text-[#0EA486]"></i> Générer facture PDF
                    </h3>
                    <p class="text-xs text-gray-400">Télécharger la facture de la commande</p>
                </div>
                <button class="closeInvoiceBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100">
                    <p class="text-sm text-emerald-700">
                        <i class="fas fa-info-circle mr-2"></i>
                        La facture sera générée au format PDF et téléchargée automatiquement.
                    </p>
                </div>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-500">Commande</span>
                        <span class="font-mono font-semibold text-[#0F172A]">#...</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-500">Acheteur</span>
                        <span class="font-medium text-[#0F172A]">...</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-500">Montant</span>
                        <span class="font-semibold text-[#0EA486]">... FCFA</span>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                <button class="closeInvoiceBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                    Annuler
                </button>
                <button class="px-5 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                    <i class="fas fa-download"></i> Télécharger la facture
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL : RÉGÉNÉRER LIEN -->
    <div id="regenerateLinkModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-sync-alt text-[#0EA486]"></i> Régénérer le lien
                    </h3>
                    <p class="text-xs text-gray-400">Créer un nouveau lien de téléchargement</p>
                </div>
                <button class="closeRegenerateLinkBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                    <p class="text-sm text-blue-700">
                        <i class="fas fa-info-circle mr-2"></i>
                        L'ancien lien sera désactivé et un nouveau sera envoyé à l'acheteur par email.
                    </p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Durée de validité</label>
                    <select class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                        <option>24 heures</option>
                        <option>7 jours</option>
                        <option>30 jours</option>
                        <option>Illimitée</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Note (optionnel)</label>
                    <textarea rows="2" placeholder="Raison de la régénération..." class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white resize-none"></textarea>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                <button class="closeRegenerateLinkBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                    Annuler
                </button>
                <button class="px-5 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                    <i class="fas fa-sync-alt"></i> Régénérer le lien
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL : FILTRES AVANCÉS -->
    <div id="advancedFiltersModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl max-h-[92vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fas fa-filter text-[#0EA486]"></i> Filtres avancés
                    </h3>
                    <p class="text-xs text-gray-400">Affiner votre recherche</p>
                </div>
                <button class="closeAdvancedFiltersBtn w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="overflow-y-auto p-6 space-y-4">
                <!-- Plage de dates -->
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-[#0EA486]"></i> Plage de dates
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Date début</label>
                            <input type="date" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Date fin</label>
                            <input type="date" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                        </div>
                    </div>
                </div>

                <!-- Plage de montants -->
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-coins text-[#0EA486]"></i> Plage de montants
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Montant minimum (FCFA)</label>
                            <input type="number" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="0">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Montant maximum (FCFA)</label>
                            <input type="number" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="1000000">
                        </div>
                    </div>
                </div>

                <!-- Filtres additionnels -->
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase mb-3 flex items-center gap-2">
                        <i class="fas fa-sliders-h text-[#0EA486]"></i> Filtres additionnels
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Vendeur spécifique</label>
                            <select class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                                <option>Tous les vendeurs</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Produit spécifique</label>
                            <input type="text" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white" placeholder="Nom du produit...">
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                <button class="closeAdvancedFiltersBtn px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                    Annuler
                </button>
                <button class="px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-times"></i> Réinitialiser
                </button>
                <button class="px-5 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                    <i class="fas fa-check"></i> Appliquer les filtres
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

        // Modal détail commande
        (function() {
            const modal = document.getElementById('orderDetailModal');
            const openBtns = document.querySelectorAll('.openOrderDetailBtn');
            const closeBtns = document.querySelectorAll('.closeOrderDetailBtn');

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
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
            });
        })();

        // Modal rembourser
        (function() {
            const modal = document.getElementById('refundModal');
            const openBtns = document.querySelectorAll('.openRefundBtn');
            const closeBtns = document.querySelectorAll('.closeRefundBtn');

            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            openBtns.forEach(btn => btn.addEventListener('click', openModal));
            closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });
        })();

        // Modal générer facture
        (function() {
            const modal = document.getElementById('invoiceModal');
            const openBtns = document.querySelectorAll('.openInvoiceBtn');
            const closeBtns = document.querySelectorAll('.closeInvoiceBtn');

            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            openBtns.forEach(btn => btn.addEventListener('click', openModal));
            closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });
        })();

        // Modal régénérer lien
        (function() {
            const modal = document.getElementById('regenerateLinkModal');
            const openBtns = document.querySelectorAll('.regenerateLinkBtn');
            const closeBtns = document.querySelectorAll('.closeRegenerateLinkBtn');

            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            openBtns.forEach(btn => btn.addEventListener('click', openModal));
            closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });
        })();

        // Modal filtres avancés
        (function() {
            const modal = document.getElementById('advancedFiltersModal');
            const openBtn = document.getElementById('openAdvancedFiltersBtn');
            const closeBtns = document.querySelectorAll('.closeAdvancedFiltersBtn');

            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            openBtn.addEventListener('click', openModal);
            closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });
        })();

        // Export CSV
        (function() {
            const exportBtn = document.getElementById('exportCsvBtn');
            exportBtn.addEventListener('click', function() {
                alert('Export CSV en cours... (fonctionnalité à implémenter)');
            });
        })();
    </script>
</body>
</html>