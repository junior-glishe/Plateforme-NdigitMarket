<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NDIGITMARKET Admin · Gestion utilisateur</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/CSS/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
</head>
<body>
    <div id="overlay" class="overlay"></div>

    <aside id="sidebar" class="sidebar sidebar-mobile md:sidebar-mobile md:translate-x-0">
        <div class="p-6">
            <!-- Logo -->
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 bg-[#0EA486] rounded-xl flex items-center justify-center text-white font-bold text-xl">
                    N
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-[#0F172A] tracking-tight">NDIGITMARKET</h1>
                    <p class="text-[10px] uppercase tracking-widest text-gray-400">Administration</p>
                </div>
            </div>

           <!-- Navigation -->
            <nav class="space-y-0.5">
                <a href="../dashboard.html" class="sidebar-link">
                    <i class="fas fa-th-large"></i> Tableau de bord
                </a>
                <a href="./gestion-utilisateur.html" class="sidebar-link active">
                    <i class="fas fa-users"></i> Gestion utilisateur
                </a>
                <a href="./gestion-vendeur.html" class="sidebar-link">
                    <i class="fas fa-store"></i> Gestion vendeur
                </a>
                <a href="./gestion-produits.html" class="sidebar-link">
                    <i class="fas fa-file-code"></i> Produits & Templates
                </a>
                <a href="./gestion-commande.html" class="sidebar-link ">
                    <i class="fas fa-shopping-cart"></i> Gestion commande
                </a>
                <a href="./financieres-commission.html" class="sidebar-link">
                    <i class="fas fa-coins"></i> Financières & commission
                </a>
                <a href="./categorie.html" class="sidebar-link">
                    <i class="fas fa-tags"></i> Catégorie
                </a>
                <a href="./contenus.html" class="sidebar-link">
                    <i class="fas fa-newspaper"></i> Contenus
                </a>
                <a href="./notifications.html" class="sidebar-link">
                    <i class="fas fa-bell"></i> Notifications
                </a>
                <a href="./rapport-stat.html" class="sidebar-link">
                    <i class="fas fa-chart-pie"></i> Statistique & rapport
                </a>
                <a href="./avis-commentaires.html" class="sidebar-link">
                    <i class="fas fa-comment-dots"></i> Avis / Commentaire
                </a>
                <a href="./parametres-systeme.html" class="sidebar-link">
                    <i class="fas fa-cog"></i> Paramètre système
                </a>
                <a href="./logs-audit.html" class="sidebar-link">
                    <i class="fas fa-history"></i> Logs & audit trail
                </a>
            </nav>

            <!-- Profil en bas -->
            <div class="mt-8 pt-6 border-t border-gray-100">
                <div class="bg-[#0F172A] rounded-2xl p-4 text-white">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-[#0EA486] rounded-full flex items-center justify-center text-lg font-semibold">
                            A
                        </div>
                        <div>
                            <p class="text-sm font-semibold">....</p>
                            <p class="text-[11px] text-gray-400">....</p>
                        </div>
                    </div>
                    <button class="text-xs text-gray-300 hover:text-white transition flex items-center gap-2">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </button>
                </div>
            </div>
        </div>
    </aside>

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
                <button class="relative w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 transition flex items-center justify-center">
                    <i class="fas fa-bell text-gray-600"></i>
                    <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                </button>
                <div class="w-9 h-9 rounded-full bg-[#0EA486] text-white flex items-center justify-center font-semibold text-sm">
                    A
                </div>
            </div>
        </header>

        <!--  LISTE DES UTILISATEURS -->
        <section class="mb-6">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-list-ul text-[#0EA486]"></i>  · Liste des utilisateurs
                </h3>
                <div class="flex gap-2">
                    <button class="px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2">
                        <i class="fas fa-file-csv"></i> Export CSV
                    </button>
                    <button class="px-3 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-medium flex items-center gap-2">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                    <button id="openFormBtn" class="px-4 py-2 rounded-lg bg-[#0EA486] hover:bg-[#0c8f75] text-white text-xs font-semibold flex items-center gap-2 shadow-sm transition">
                        <i class="fas fa-plus"></i> Nouvel utilisateur
                    </button>
                </div>
            </div>

            <!-- Barre de recherche + filtres -->
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm mb-4">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex-1 min-w-[220px] relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" placeholder="Rechercher par nom, email ou ID..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white transition">
                    </div>
                    <select class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 focus:outline-none focus:border-[#0EA486]">
                        <option>Tous les rôles</option>
                        <option>Acheteur</option>
                        <option>Vendeur</option>
                        <option>Admin</option>
                    </select>
                    <select class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 focus:outline-none focus:border-[#0EA486]">
                        <option>Tous les statuts</option>
                        <option>Actif</option>
                        <option>Suspendu</option>
                        <option>Banni</option>
                    </select>
                    <select class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 focus:outline-none focus:border-[#0EA486]">
                        <option>Tous les pays</option>
                    </select>
                    <input type="date" class="px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 focus:outline-none focus:border-[#0EA486]">
                    <button class="px-3 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm flex items-center gap-2">
                        <i class="fas fa-filter"></i> Plus de filtres
                    </button>
                </div>

                <!-- Actions groupées -->
                <div class="flex flex-wrap items-center gap-2 mt-3 pt-3 border-t border-gray-100">
                    <label class="flex items-center gap-2 text-xs text-gray-500">
                        <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-[#0EA486] focus:ring-[#0EA486]">
                        Tout sélectionner
                    </label>
                    <span class="text-xs text-gray-400">·</span>
                    <button class="px-3 py-1.5 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100 text-xs font-medium flex items-center gap-1.5">
                        <i class="fas fa-pause-circle"></i> Suspendre
                    </button>
                    <button class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 text-xs font-medium flex items-center gap-1.5">
                        <i class="fas fa-play-circle"></i> Réactiver
                    </button>
                    <button class="px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 text-xs font-medium flex items-center gap-1.5">
                        <i class="fas fa-trash"></i> Supprimer
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
                                <th class="px-4 py-3 cursor-pointer hover:text-[#0EA486]">ID <i class="fas fa-sort text-[10px] ml-1"></i></th>
                                <th class="px-4 py-3 cursor-pointer hover:text-[#0EA486]">Nom complet <i class="fas fa-sort text-[10px] ml-1"></i></th>
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3">Rôle</th>
                                <th class="px-4 py-3 cursor-pointer hover:text-[#0EA486]">Date d'inscription <i class="fas fa-sort text-[10px] ml-1"></i></th>
                                <th class="px-4 py-3">Statut</th>
                                <th class="px-4 py-3 cursor-pointer hover:text-[#0EA486]">Nb commandes <i class="fas fa-sort text-[10px] ml-1"></i></th>
                                <th class="px-4 py-3 cursor-pointer hover:text-[#0EA486]">CA généré <i class="fas fa-sort text-[10px] ml-1"></i></th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="10">
                                    <div class="flex flex-col items-center justify-center py-16">
                                        <i class="fas fa-users empty-icon text-5xl"></i>
                                        <p class="text-sm text-gray-400 mt-4 font-medium">Aucun utilisateur pour le moment</p>
                                        <p class="text-xs text-gray-300 mt-1">Les utilisateurs inscrits apparaîtront ici</p>
                                        <button id="openFormBtn2" class="mt-5 px-4 py-2 rounded-lg bg-[#0EA486] hover:bg-[#0c8f75] text-white text-xs font-semibold flex items-center gap-2 transition">
                                            <i class="fas fa-plus"></i> Créer le premier utilisateur
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
                        <span>0 - 0 sur 0</span>
                        <button class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-50" disabled>
                            <i class="fas fa-chevron-left text-[10px]"></i>
                        </button>
                        <button class="w-8 h-8 rounded-lg bg-[#0EA486] text-white flex items-center justify-center">1</button>
                        <button class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-50" disabled>
                            <i class="fas fa-chevron-right text-[10px]"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!--  FICHE UTILISATEUR DÉTAILLÉE -->
        <section class="mb-6">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-id-card text-[#0EA486]"></i> · Fiche utilisateur détaillée
                </h3>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Infos personnelles -->
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <i class="fas fa-user text-[#0EA486]"></i> Informations personnelles
                    </h4>
                    <div class="flex flex-col items-center justify-center py-8">
                        <i class="fas fa-user-circle empty-icon text-4xl"></i>
                        <p class="text-xs text-gray-400 mt-3 text-center">Sélectionnez un utilisateur pour voir ses informations</p>
                    </div>
                    <ul class="text-xs text-gray-400 space-y-1.5 mt-2">
                        <li><i class="fas fa-check text-[#0EA486] mr-2"></i>Nom, email, téléphone</li>
                        <li><i class="fas fa-check text-[#0EA486] mr-2"></i>Pays, date d'inscription</li>
                    </ul>
                </div>

                <!-- Historique commandes / téléchargements / connexions -->
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <i class="fas fa-history text-[#0EA486]"></i> Historiques
                    </h4>
                    <div class="flex flex-col items-center justify-center py-8">
                        <i class="fas fa-clock empty-icon text-4xl"></i>
                        <p class="text-xs text-gray-400 mt-3 text-center">Aucun historique disponible</p>
                    </div>
                    <ul class="text-xs text-gray-400 space-y-1.5 mt-2">
                        <li><i class="fas fa-check text-[#0EA486] mr-2"></i>Commandes (statut + montants)</li>
                        <li><i class="fas fa-check text-[#0EA486] mr-2"></i>Téléchargements</li>
                        <li><i class="fas fa-check text-[#0EA486] mr-2"></i>Connexions (10 dernières : IP + date)</li>
                    </ul>
                </div>

                <!-- Statut vendeur + notes admin -->
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <i class="fas fa-sticky-note text-[#0EA486]"></i> Statut vendeur & Notes admin
                    </h4>
                    <div class="flex flex-col items-center justify-center py-8">
                        <i class="fas fa-store empty-icon text-4xl"></i>
                        <p class="text-xs text-gray-400 mt-3 text-center">Aucune information</p>
                    </div>
                    <ul class="text-xs text-gray-400 space-y-1.5 mt-2">
                        <li><i class="fas fa-check text-[#0EA486] mr-2"></i>Statut vendeur + lien boutique</li>
                        <li><i class="fas fa-check text-[#0EA486] mr-2"></i>Notes internes admin (privées)</li>
                    </ul>
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
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm hover:border-[#0EA486] transition group">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 mb-3 group-hover:bg-[#0EA486] group-hover:text-white transition">
                        <i class="fas fa-edit"></i>
                    </div>
                    <p class="text-sm font-semibold text-[#0F172A]">Modifier le profil</p>
                    <p class="text-xs text-gray-400 mt-1">Éditer les informations</p>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm hover:border-[#0EA486] transition group">
                    <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 mb-3 group-hover:bg-[#0EA486] group-hover:text-white transition">
                        <i class="fas fa-key"></i>
                    </div>
                    <p class="text-sm font-semibold text-[#0F172A]">Réinitialiser mot de passe</p>
                    <p class="text-xs text-gray-400 mt-1">Envoi d'email automatique</p>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm hover:border-[#0EA486] transition group">
                    <div class="w-10 h-10 bg-yellow-50 rounded-xl flex items-center justify-center text-yellow-600 mb-3 group-hover:bg-[#0EA486] group-hover:text-white transition">
                        <i class="fas fa-pause-circle"></i>
                    </div>
                    <p class="text-sm font-semibold text-[#0F172A]">Suspendre temporairement</p>
                    <p class="text-xs text-gray-400 mt-1">Motif + durée</p>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm hover:border-[#0EA486] transition group">
                    <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center text-red-600 mb-3 group-hover:bg-[#0EA486] group-hover:text-white transition">
                        <i class="fas fa-ban"></i>
                    </div>
                    <p class="text-sm font-semibold text-[#0F172A]">Bannir définitivement</p>
                    <p class="text-xs text-gray-400 mt-1">Motif obligatoire</p>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm hover:border-[#0EA486] transition group">
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 mb-3 group-hover:bg-[#0EA486] group-hover:text-white transition">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <p class="text-sm font-semibold text-[#0F172A]">Réactiver le compte</p>
                    <p class="text-xs text-gray-400 mt-1">Suspendu ou banni</p>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm hover:border-[#0EA486] transition group">
                    <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600 mb-3 group-hover:bg-[#0EA486] group-hover:text-white transition">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                    <p class="text-sm font-semibold text-[#0F172A]">Supprimer le compte</p>
                    <p class="text-xs text-gray-400 mt-1">Soft delete (confirmation)</p>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm hover:border-[#0EA486] transition group md:col-span-2">
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 mb-3 group-hover:bg-[#0EA486] group-hover:text-white transition">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <p class="text-sm font-semibold text-[#0F172A]">Envoyer un email personnalisé</p>
                    <p class="text-xs text-gray-400 mt-1">Communication directe depuis l'interface</p>
                </div>
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
                <button id="closeFormBtn" class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form class="p-6 overflow-y-auto space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Prénom <span style="color: red;">*</span></label>
                        <input type="text" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Nom complet <span style="color: red;">*</span></label>
                        <input type="text" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Email <span style="color: red;">*</span></label>
                        <input type="email" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Téléphone</label>
                        <input type="tel" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Rôle <span style="color: red;">*</span></label>
                        <select class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                            <option>Acheteur</option>
                            <option>Vendeur</option>
                            <option>Admin</option>
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
                        <select class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486]">
                            <option>Actif</option>
                            <option>Suspendu</option>
                             <option>Banni</option>
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
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Mot de passe <span style="color: red;">*</span></label>
                        <input type="password" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Notes internes (admin) <span style="color: red;">*</span></label>
                        <textarea rows="3" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#0EA486] focus:bg-white resize-none"></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                    <button type="button" id="cancelFormBtn" class="px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                        Annuler
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0EA486] hover:bg-[#0c8f75] text-white text-sm font-semibold flex items-center gap-2 shadow-sm">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

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
    </script>
</body>
</html>