<?php
require('../../include/connect.php');

if (isset($_GET['id'])) {
    $commande_id = base64_decode($_GET['id']);

    $query = "
        SELECT c.commande_id, c.id_article, c.image, c.prix, c.fichier, c.date_commande, 
               u.nom, u.prenom, u.email, p.nom_article, p.image AS produit_image
        FROM commande c
        JOIN utilisateur u ON c.id_client = u.id_uti
        JOIN produits p ON c.id_article = p.id
        WHERE c.commande_id = :commande_id
    ";

    $stmt = $database->prepare($query);
    $stmt->bindValue(':commande_id', $commande_id, PDO::PARAM_STR);
    $stmt->execute();
    $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($commandes)) {
        echo "<p>Commande introuvable.</p>";
        exit();
    }
} else {
    echo "<p>ID de commande manquant.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la commande #<?php echo htmlspecialchars($commande_id); ?> - NDIGITMARKET</title>
</head>
<body>
    <?php 
    $pageTitle = 'Détails de la commande';
    require('header.php'); 
    ?>

    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div>
                                    <h5>Commande #<?php echo htmlspecialchars($commande_id); ?></h5>
                                    <p class="text-muted">Détails complets de la commande</p>
                                </div>
                                <a href="commandes" class="btn btn-outline-secondary">
                                    <i class="fa-regular fa-arrow-left"></i> Retour aux commandes
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Résumé de la commande -->
                            <div class="order-summary">
                                <div class="row g-4">
                                    <div class="col-md-3">
                                        <div class="summary-item">
                                            <span class="summary-label">Date de commande</span>
                                            <span class="summary-value"><?php echo date('d/m/Y H:i', strtotime($commandes[0]['date_commande'])); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="summary-item">
                                            <span class="summary-label">Nombre d'articles</span>
                                            <span class="summary-value"><?php echo count($commandes); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="summary-item">
                                            <span class="summary-label">Total commande</span>
                                            <span class="summary-value total"><?php echo number_format(array_sum(array_column($commandes, 'prix')), 0, ',', ' '); ?> CFA</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="summary-item">
                                            <span class="summary-label">Statut</span>
                                            <span class="badge badge-success">Succès</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informations client -->
                            <div class="client-section">
                                <h5>Informations client</h5>
                                <div class="client-card">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="client-info-item">
                                                <i class="fa-regular fa-user"></i>
                                                <div>
                                                    <span class="info-label">Nom complet</span>
                                                    <span class="info-value"><?php echo htmlspecialchars($commandes[0]['prenom'] . ' ' . $commandes[0]['nom']); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="client-info-item">
                                                <i class="fa-regular fa-envelope"></i>
                                                <div>
                                                    <span class="info-label">Email</span>
                                                    <span class="info-value"><?php echo htmlspecialchars($commandes[0]['email']); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Détails des articles -->
                            <div class="items-section">
                                <h5>Articles commandés</h5>
                                <div class="table-responsive">
                                    <table class="table items-table">
                                        <thead>
                                            <tr>
                                                <th>Produit</th>
                                                <th>Image</th>
                                                <th>Prix unitaire</th>
                                                <th>Quantité</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($commandes as $commande): ?>
                                                <tr>
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($commande['nom_article']); ?></strong>
                                                    </td>
                                                    <td>
                                                        <div class="product-image">
                                                            <img src="uploads/<?php echo basename($commande['produit_image']); ?>" 
                                                                 alt="<?php echo htmlspecialchars($commande['nom_article']); ?>"
                                                                 onerror="this.src='assets/images/placeholder.jpg'">
                                                        </div>
                                                    </td>
                                                    <td><?php echo number_format($commande['prix'], 0, ',', ' '); ?> CFA</td>
                                                    <td>1</td>
                                                    <td><strong><?php echo number_format($commande['prix'], 0, ',', ' '); ?> CFA</strong></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" class="text-end"><strong>Sous-total</strong></td>
                                                <td><strong><?php echo number_format(array_sum(array_column($commandes, 'prix')), 0, ',', ' '); ?> CFA</strong></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="text-end"><strong>Frais de service</strong></td>
                                                <td>2 500 CFA</td>
                                            </tr>
                                            <tr class="total-row">
                                                <td colspan="4" class="text-end"><strong>Total TTC</strong></td>
                                                <td class="total-price">
                                                    <strong><?php echo number_format(array_sum(array_column($commandes, 'prix')) + 2500, 0, ',', ' '); ?> CFA</strong>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Styles pour la page de détail */
        .order-summary {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .summary-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .summary-label {
            font-size: 13px;
            color: #6b7280;
        }
        
        .summary-value {
            font-size: 20px;
            font-weight: 700;
            color: #1a2634;
        }
        
        .summary-value.total {
            color: #087d67;
        }
        
        .client-section {
            margin-bottom: 30px;
        }
        
        .client-section h5 {
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .client-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
        }
        
        .client-info-item {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .client-info-item i {
            width: 40px;
            height: 40px;
            background: #e8f5f2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #087d67;
            font-size: 18px;
        }
        
        .info-label {
            display: block;
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 3px;
        }
        
        .info-value {
            font-weight: 600;
            color: #1a2634;
        }
        
        .items-section h5 {
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .items-table th {
            background: #f8f9fa;
            padding: 15px;
            font-weight: 600;
            color: #1a2634;
        }
        
        .items-table td {
            padding: 15px;
            vertical-align: middle;
        }
        
        .product-image {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }
        
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .items-table tfoot tr td {
            background: #f8f9fa;
            font-weight: 500;
        }
        
        .total-row td {
            background: #e8f5f2 !important;
        }
        
        .total-price {
            color: #087d67;
            font-size: 18px;
        }
        
        .badge-success {
            background: #d1fae5;
            color: #065f46;
            padding: 8px 15px;
            border-radius: 30px;
            font-size: 14px;
        }
        
        .btn-outline-secondary {
            border-radius: 30px;
            padding: 8px 20px;
            border: 1px solid #e5e7eb;
            color: #6b7280;
        }
        
        .btn-outline-secondary:hover {
            background: #f8f9fa;
            border-color: #d1d5db;
        }
    </style>

    <?php require('footer.php'); ?>
</body>
</html>