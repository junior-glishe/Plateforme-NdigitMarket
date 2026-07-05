<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NDIGITMARKET Admin · Avis & Commentaires</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/assets/CSS/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
</head>
<body>
    <?php $currentPage = 'avis-commentaires'; require_once __DIR__ . '/../components/sidebar.php'; ?>

    <main class="md:ml-[280px] min-h-screen p-4 md:p-8 transition-all">

        <!-- HEADER -->
        <header class="flex items-center justify-between mb-6 bg-white/80 backdrop-blur-sm sticky top-0 z-30 py-4 px-4 md:px-6 rounded-2xl shadow-sm border border-gray-100/50">
            <div class="flex items-center gap-4">
                <button id="hamburgerBtn" class="md:hidden w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 transition flex items-center justify-center text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-[#0F172A]">Avis & Commentaires</h2>
                    <p class="text-xs text-gray-400 hidden sm:block">Modération des avis clients sur les produits</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-[#0EA486] text-white flex items-center justify-center font-semibold text-sm">A</div>
            </div>
        </header>

        <!-- STATS -->
        <?php
            $countBy = fn($status) => count(array_filter($reviews ?? [], fn($r) => ($r['statut'] ?? '') === $status));
            $nbApprouve  = $countBy('approuve');
            $nbAttente   = $countBy('en_attente');
            $nbRefuse    = $countBy('refuse');
        ?>
        <section class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600"><i class="fas fa-comments"></i></div>
                    <span class="text-[10px] font-semibold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-full">TOTAL</span>
                </div>
                <p class="text-2xl font-bold text-[#0F172A]"><?= (int) ($totalReviews ?? 0) ?></p>
                <p class="text-xs text-gray-400 mt-1">Avis reçus</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600"><i class="fas fa-check-circle"></i></div>
                    <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">OK</span>
                </div>
                <p class="text-2xl font-bold text-[#0F172A]"><?= $nbApprouve ?></p>
                <p class="text-xs text-gray-400 mt-1">Approuvés</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600"><i class="fas fa-hourglass-half"></i></div>
                    <span class="text-[10px] font-semibold text-amber-600 bg-amber-50 px-2 py-1 rounded-full">ATTENTE</span>
                </div>
                <p class="text-2xl font-bold text-[#0F172A]"><?= $nbAttente ?></p>
                <p class="text-xs text-gray-400 mt-1">En attente</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center text-red-600"><i class="fas fa-times-circle"></i></div>
                    <span class="text-[10px] font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-full">REJET</span>
                </div>
                <p class="text-2xl font-bold text-[#0F172A]"><?= $nbRefuse ?></p>
                <p class="text-xs text-gray-400 mt-1">Rejetés</p>
            </div>
        </section>

        <!-- TABLE -->
        <section class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-[#0F172A]">Liste des avis</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3 text-left">Client</th>
                            <th class="px-6 py-3 text-left">Produit</th>
                            <th class="px-6 py-3 text-left">Note</th>
                            <th class="px-6 py-3 text-left">Commentaire</th>
                            <th class="px-6 py-3 text-left">Date</th>
                            <th class="px-6 py-3 text-left">Statut</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($reviews)): ?>
                            <tr><td colspan="7" class="px-6 py-10 text-center text-gray-400">Aucun avis pour l'instant.</td></tr>
                        <?php else: foreach ($reviews as $r): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 font-medium text-[#0F172A]"><?= htmlspecialchars($r['client_full_name'] ?? '—') ?></td>
                                <td class="px-6 py-3"><?= htmlspecialchars($r['nom_article'] ?? '—') ?></td>
                                <td class="px-6 py-3">
                                    <?php $n = (int) ($r['note'] ?? 0); for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?= $i <= $n ? 'text-amber-400' : 'text-gray-200' ?>"></i>
                                    <?php endfor; ?>
                                </td>
                                <td class="px-6 py-3 max-w-xs truncate text-gray-600"><?= htmlspecialchars($r['commentaire'] ?? '') ?></td>
                                <td class="px-6 py-3 text-gray-500"><?= htmlspecialchars($r['date_formatted'] ?? '') ?></td>
                                <td class="px-6 py-3">
                                    <span class="text-xs font-semibold px-2 py-1 rounded-full bg-<?= $r['statut_class'] ?>-100 text-<?= $r['statut_class'] ?>-700">
                                        <?= htmlspecialchars($r['statut_label']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <a href="/admin/action/approveReview/<?= (int) $r['id_avis'] ?>" class="text-emerald-600 hover:text-emerald-800 mr-2" title="Approuver"><i class="fas fa-check"></i></a>
                                    <a href="/admin/action/rejectReview/<?= (int) $r['id_avis'] ?>" class="text-red-600 hover:text-red-800" title="Rejeter"><i class="fas fa-times"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
