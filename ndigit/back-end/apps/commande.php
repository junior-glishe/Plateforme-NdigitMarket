<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion des commandes - NDIGITMARKET</title>
</head>
<body>
    <?php 
    $pageTitle = 'Gestion des commandes';
    require('header.php'); 
    ?>

    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-header">
                            <h5>Liste des commandes</h5>
                            <p class="text-muted">
                                <?php
                                require('../../include/connect.php');
                                $queryCount = $database->query("SELECT COUNT(DISTINCT commande_id) FROM commande");
                                $totalCommandes = $queryCount->fetchColumn();
                                echo $totalCommandes . ' commande(s) au total';
                                ?>
                            </p>
                        </div>
                        <div class="card-body">
                            <!-- Barre de recherche et filtres -->
                            <div class="search-section">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <form method="GET" action="" class="search-form">
                                            <div class="input-group">
                                                <input type="text" name="search" class="form-control" 
                                                       placeholder="Rechercher par ID, client..." 
                                                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                                <button class="btn btn-primary" type="submit">
                                                    <i class="fa-regular fa-magnifying-glass"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <div class="filter-options">
                                            <select class="form-select" id="statusFilter" onchange="filterByStatus(this.value)">
                                                <option value="">Tous les statuts</option>
                                                <option value="Succès">Succès</option>
                                                <option value="En attente">En attente</option>
                                                <option value="Annulé">Annulé</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Table des commandes -->
                            <div class="table-responsive">
                                <?php
                                $searchQuery = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "%";
                                $commandesParPage = 10;
                                $pageActuelle = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                $offset = ($pageActuelle - 1) * $commandesParPage;

                                $query = "
                                    SELECT c.commande_id, u.nom, u.prenom, c.date_commande, 
                                           SUM(c.prix) AS total_commande, COUNT(c.id_article) AS item_count
                                    FROM commande c
                                    JOIN utilisateur u ON c.id_client = u.id_uti
                                    WHERE c.commande_id LIKE :search OR u.nom LIKE :search OR u.prenom LIKE :search
                                    GROUP BY c.commande_id, u.nom, u.prenom, c.date_commande
                                    ORDER BY c.date_commande DESC
                                    LIMIT :limit OFFSET :offset
                                ";

                                $stmt = $database->prepare($query);
                                $stmt->bindValue(':search', $searchQuery, PDO::PARAM_STR);
                                $stmt->bindValue(':limit', $commandesParPage, PDO::PARAM_INT);
                                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
                                $stmt->execute();
                                $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                $queryCount = "
                                    SELECT COUNT(DISTINCT c.commande_id) 
                                    FROM commande c
                                    JOIN utilisateur u ON c.id_client = u.id_uti
                                    WHERE c.commande_id LIKE :search OR u.nom LIKE :search OR u.prenom LIKE :search
                                ";
                                $stmtCount = $database->prepare($queryCount);
                                $stmtCount->bindValue(':search', $searchQuery, PDO::PARAM_STR);
                                $stmtCount->execute();
                                $totalCommandes = $stmtCount->fetchColumn();
                                $totalPages = ceil($totalCommandes / $commandesParPage);
                                ?>

                                <table class="table order-table">
                                    <thead>
                                        <tr>
                                            <th>ID Commande</th>
                                            <th>Client</th>
                                            <th>Articles</th>
                                            <th>Total</th>
                                            <th>Date</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($commandes)): ?>
                                            <tr>
                                                <td colspan="7" class="text-center py-5">
                                                    <i class="fa-regular fa-cart-shopping" style="font-size: 40px; color: #ddd;"></i>
                                                    <p class="mt-2">Aucune commande trouvée</p>
                                                </td>
                                            </tr>
                                        <?php endif; ?>

                                        <?php foreach ($commandes as $commande): 
                                            // Simuler un statut (à remplacer par votre logique réelle)
                                            $statuses = ['Succès', 'En attente', 'Annulé'];
                                            $randomStatus = $statuses[array_rand($statuses)];
                                        ?>
                                            <tr>
                                                <td>
                                                    <span class="order-id">#<?php echo htmlspecialchars($commande['commande_id']); ?></span>
                                                </td>
                                                <td>
                                                    <div class="client-info">
                                                        <strong><?php echo htmlspecialchars($commande['prenom'] . ' ' . $commande['nom']); ?></strong>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-item"><?php echo $commande['item_count']; ?> article(s)</span>
                                                </td>
                                                <td>
                                                    <span class="order-total"><?php echo number_format($commande['total_commande'], 0, ',', ' '); ?> CFA</span>
                                                </td>
                                                <td>
                                                    <?php echo date('d/m/Y H:i', strtotime($commande['date_commande'])); ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $statusClass = '';
                                                    if ($randomStatus == 'Succès') $statusClass = 'badge-success';
                                                    elseif ($randomStatus == 'En attente') $statusClass = 'badge-warning';
                                                    elseif ($randomStatus == 'Annulé') $statusClass = 'badge-danger';
                                                    ?>
                                                    <span class="badge <?php echo $statusClass; ?>"><?php echo $randomStatus; ?></span>
                                                </td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <a href="order-detail.php?id=<?php echo base64_encode($commande['commande_id']); ?>" 
                                                           class="btn-view" title="Voir détails">
                                                            <i class="fa-regular fa-eye"></i>
                                                        </a>
                                                        <button class="btn-delete" onclick="confirmDelete('<?php echo $commande['commande_id']; ?>')" title="Supprimer">
                                                            <i class="fa-regular fa-trash-can"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <?php if ($totalPages > 1): ?>
                                <div class="pagination-section">
                                    <nav>
                                        <ul class="pagination">
                                            <?php if ($pageActuelle > 1): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=<?php echo $pageActuelle - 1; ?>&search=<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                                        <i class="fa-regular fa-chevron-left"></i>
                                                    </a>
                                                </li>
                                            <?php endif; ?>

                                            <?php
                                            $startPage = max(1, $pageActuelle - 2);
                                            $endPage = min($totalPages, $pageActuelle + 2);

                                            if ($startPage > 1): ?>
                                                <li class="page-item"><a class="page-link" href="?page=1&search=<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">1</a></li>
                                                <?php if ($startPage > 2): ?>
                                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                                <li class="page-item <?php echo ($i == $pageActuelle) ? 'active' : ''; ?>">
                                                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                                        <?php echo $i; ?>
                                                    </a>
                                                </li>
                                            <?php endfor; ?>

                                            <?php if ($endPage < $totalPages): ?>
                                                <?php if ($endPage < $totalPages - 1): ?>
                                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                                <?php endif; ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=<?php echo $totalPages; ?>&search=<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                                        <?php echo $totalPages; ?>
                                                    </a>
                                                </li>
                                            <?php endif; ?>

                                            <?php if ($pageActuelle < $totalPages): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=<?php echo $pageActuelle + 1; ?>&search=<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                                        <i class="fa-regular fa-chevron-right"></i>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Styles spécifiques aux commandes */
        .order-table th {
            background: #f8f9fa;
            padding: 15px;
            font-weight: 600;
            color: #1a2634;
        }
        
        .order-table td {
            padding: 15px;
            vertical-align: middle;
        }
        
        .order-id {
            font-weight: 600;
            color: #087d67;
        }
        
        .badge-item {
            background: #e8f5f2;
            color: #087d67;
            padding: 5px 12px;
            border-radius: 30px;
            font-size: 12px;
        }
        
        .order-total {
            font-weight: 600;
            color: #1a2634;
        }
        
        .badge-success {
            background: #d1fae5;
            color: #065f46;
            padding: 5px 12px;
            border-radius: 30px;
            font-size: 12px;
        }
        
        .badge-warning {
            background: #fef3c7;
            color: #92400e;
            padding: 5px 12px;
            border-radius: 30px;
            font-size: 12px;
        }
        
        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
            padding: 5px 12px;
            border-radius: 30px;
            font-size: 12px;
        }
        
        .search-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
        }
        
        .filter-options .form-select {
            width: 200px;
            border-radius: 30px;
            padding: 10px 30px 10px 15px;
            border-color: #e5e7eb;
        }
    </style>

    <script>
        function filterByStatus(status) {
            // Implémenter le filtrage par statut
            console.log('Filtre par statut:', status);
        }

        function confirmDelete(commandeId) {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Cette action est irréversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "delete_commande.php?id=" + commandeId;
                }
            });
        }
    </script>

    <?php require('footer.php'); ?>
</body>
</html>