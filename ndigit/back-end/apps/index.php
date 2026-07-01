<?php
$pageTitle = 'Tableau de bord';
require('header.php');

// Statistiques
try {
    // Nombre total de produits
    $stmt = $database->query("SELECT COUNT(*) as total FROM produits");
    $totalProduits = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Nombre total de commandes
    $stmt = $database->query("SELECT COUNT(*) as total FROM commande");
    $totalCommandes = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Nombre total d'utilisateurs
    $stmt = $database->query("SELECT COUNT(*) as total FROM utilisateur");
    $totalUtilisateurs = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Nombre total d'abonnements actifs
    $stmt = $database->query("
        SELECT COUNT(*) as total FROM abonnement 
        WHERE date_fin > NOW()
    ");
    $totalAbonnementsActifs = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Dernières commandes
    $stmt = $database->query("
        SELECT c.*, u.nom, u.prenom, p.nom_article 
        FROM commande c
        JOIN utilisateur u ON c.id_client = u.id_uti
        JOIN produits p ON c.id_article = p.id
        ORDER BY c.commande_id DESC
        LIMIT 5
    ");
    $dernieresCommandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Derniers avis
    $stmt = $database->query("
        SELECT a.*, p.nom_article 
        FROM avis a
        LEFT JOIN produits p ON a.produit_id = p.id
        ORDER BY a.date_creation DESC
        LIMIT 5
    ");
    $derniersAvis = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    // Gérer l'erreur silencieusement
}
?>

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 25px;
        margin-bottom: 40px;
    }

    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }

    .stat-card {
        background: white;
        border-radius: var(--radius);
        padding: 25px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
    }

    .stat-info h3 {
        font-size: 14px;
        font-weight: 500;
        color: var(--text-light);
        margin-bottom: 8px;
    }

    .stat-info h2 {
        font-size: 32px;
        font-weight: 700;
        color: var(--dark);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        background: var(--primary-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 28px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: var(--primary);
    }

    .table-card {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead th {
        background: var(--bg);
        padding: 15px 20px;
        font-weight: 600;
        color: var(--dark);
        border-bottom: 2px solid var(--border);
        text-align: left;
    }

    .table tbody td {
        padding: 15px 20px;
        border-bottom: 1px solid var(--border);
    }

    .table tbody tr:hover {
        background: var(--bg);
    }

    .badge {
        padding: 5px 10px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-warning {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-view {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: gap 0.3s;
    }

    .btn-view:hover {
        gap: 8px;
        color: var(--primary-dark);
    }

    .rating {
        display: flex;
        gap: 2px;
        color: #ffb800;
    }
</style>

<div class="stats-grid">
    <!-- Produits -->
    <div class="stat-card">
        <div class="stat-info">
            <h3>Produits</h3>
            <h2><?php echo number_format($totalProduits ?? 0, 0, ',', ' '); ?></h2>
        </div>
        <div class="stat-icon">
            <i class="fa-regular fa-box"></i>
        </div>
    </div>

    <!-- Commandes -->
    <div class="stat-card">
        <div class="stat-info">
            <h3>Commandes</h3>
            <h2><?php echo number_format($totalCommandes ?? 0, 0, ',', ' '); ?></h2>
        </div>
        <div class="stat-icon">
            <i class="fa-regular fa-cart-shopping"></i>
        </div>
    </div>

    <!-- Utilisateurs -->
    <div class="stat-card">
        <div class="stat-info">
            <h3>Utilisateurs</h3>
            <h2><?php echo number_format($totalUtilisateurs ?? 0, 0, ',', ' '); ?></h2>
        </div>
        <div class="stat-icon">
            <i class="fa-regular fa-users"></i>
        </div>
    </div>

    <!-- Abonnements actifs -->
    <div class="stat-card">
        <div class="stat-info">
            <h3>Abonnements actifs</h3>
            <h2><?php echo number_format($totalAbonnementsActifs ?? 0, 0, ',', ' '); ?></h2>
        </div>
        <div class="stat-icon">
            <i class="fa-regular fa-crown"></i>
        </div>
    </div>
</div>

<div class="row">
    <!-- Dernières commandes -->
    <div class="col-xxl-6">
        <div class="section-title">
            <i class="fa-regular fa-clock"></i>
            Dernières commandes
        </div>
        
        <div class="table-card">
            <?php if (!empty($dernieresCommandes)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Produit</th>
                        <th>Montant</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dernieresCommandes as $commande): ?>
                    <tr>
                        <td>#<?php echo $commande['commande_id']; ?></td>
                        <td><?php echo htmlspecialchars($commande['prenom'] . ' ' . $commande['nom']); ?></td>
                        <td><?php echo htmlspecialchars(mb_strimwidth($commande['nom_article'], 0, 30, '...')); ?></td>
                        <td><?php echo number_format($commande['prix'], 0, ',', ' '); ?> CFA</td>
                        <td><?php echo date('d/m/Y', strtotime($commande['date_creation'] ?? 'now')); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div style="padding: 40px; text-align: center; color: var(--text-light);">
                Aucune commande récente
            </div>
            <?php endif; ?>
        </div>
        
        <a href="commandes" class="btn-view">
            Voir toutes les commandes <i class="fa-regular fa-arrow-right"></i>
        </a>
    </div>

    <!-- Derniers avis -->
    <div class="col-xxl-6">
        <div class="section-title">
            <i class="fa-regular fa-star"></i>
            Derniers avis clients
        </div>
        
        <div class="table-card">
            <?php if (!empty($derniersAvis)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Produit</th>
                        <th>Note</th>
                        <th>Statut</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($derniersAvis as $avis): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($avis['nom']); ?></td>
                        <td><?php echo htmlspecialchars($avis['nom_article'] ?? 'Général'); ?></td>
                        <td>
                            <div class="rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fa-<?php echo $i <= $avis['note'] ? 'solid' : 'regular'; ?> fa-star"></i>
                                <?php endfor; ?>
                            </div>
                        </td>
                        <td>
                            <?php if ($avis['statut'] == 'approuve'): ?>
                                <span class="badge badge-success">Approuvé</span>
                            <?php elseif ($avis['statut'] == 'en_attente'): ?>
                                <span class="badge badge-warning">En attente</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Rejeté</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($avis['date_creation'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div style="padding: 40px; text-align: center; color: var(--text-light);">
                Aucun avis récent
            </div>
            <?php endif; ?>
        </div>
        
        <a href="avis" class="btn-view">
            Voir tous les avis <i class="fa-regular fa-arrow-right"></i>
        </a>
    </div>
</div>

<?php require('footer.php'); ?>