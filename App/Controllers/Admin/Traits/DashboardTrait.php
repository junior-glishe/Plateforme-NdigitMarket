<?php
/**
 * NDIGITMARKET - Admin trait: Dashboard
 *
 * Fait partie du refactor du monolithique AdminController.
 * Chaque trait regroupe les méthodes d'un même domaine metier.
 * Le comportement des méthodes est identique à la version d'origine.
 */

namespace App\Controllers\Admin\Traits;

trait DashboardTrait
{
    public function dashboard()
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        // Stats commandes
        $orderStats = $db->query("
            SELECT COUNT(*) AS total_orders,
                SUM(CAST(REPLACE(prix, ' ', '') AS DECIMAL(14,2))) AS total_revenue,
                AVG(CAST(REPLACE(prix, ' ', '') AS DECIMAL(14,2))) AS average_basket
            FROM commande
        ")->fetch();

        $totalOrders = (int) ($orderStats['total_orders'] ?? 0);
        $totalRevenue = $this->normalizeFloat($orderStats['total_revenue'] ?? 0);
        $averageBasket = $this->normalizeFloat($orderStats['average_basket'] ?? 0);
        $commissionTotal = $totalRevenue * 0.10;

        // Stats utilisateurs
        $totalUsers = (int) $db->query("SELECT COUNT(*) FROM utilisateur")->fetchColumn();
        $newUsers = (int) $db->query("
            SELECT COUNT(*) FROM utilisateur 
            WHERE id_uti >= (SELECT MAX(id_uti) - 30 FROM utilisateur)
        ")->fetchColumn();

        // Stats vendeurs / produits
        $vendorRequests = (int) $db->query("SELECT COUNT(*) FROM demandes_vendeur WHERE statut = 'en_attente'")->fetchColumn();
        $productsPending = (int) $db->query("SELECT COUNT(*) FROM produits WHERE statut = 'en_attente'")->fetchColumn();
        $activeTemplates = (int) $db->query("SELECT COUNT(*) FROM produits WHERE statut = 'approuve'")->fetchColumn();
        $totalVendors = (int) $db->query("SELECT COUNT(*) FROM utilisateur WHERE type = 'pro'")->fetchColumn();
        $totalProducts = (int) $db->query("SELECT COUNT(*) FROM produits")->fetchColumn();
        $activeSubscriptions = (int) $db->query("SELECT COUNT(*) FROM abonnement WHERE date_fin > NOW()")->fetchColumn();

        // Commandes récentes
        $recentOrders = $db->query("
            SELECT commande_id, email, prix, date_commande 
            FROM commande 
            ORDER BY date_commande DESC LIMIT 5
        ")->fetchAll();

        foreach ($recentOrders as &$recentOrder) {
            $recentOrder['prix_formatted'] = $this->formatCurrency($this->normalizeFloat($recentOrder['prix'] ?? 0));
            $recentOrder['commande_id'] = $recentOrder['commande_id'] ?: 'N/A';
            $recentOrder['date_formatted'] = $this->formatDate($recentOrder['date_commande']);
        }
        unset($recentOrder);

        // CA par mois (12 derniers mois)
        $monthlyRevenue = $db->query("
            SELECT 
                DATE_FORMAT(date_commande, '%Y-%m') AS mois,
                COUNT(*) AS nb_commandes,
                SUM(CAST(REPLACE(prix, ' ', '') AS DECIMAL(14,2))) AS ca
            FROM commande
            WHERE date_commande >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(date_commande, '%Y-%m')
            ORDER BY mois ASC
        ")->fetchAll();

        foreach ($monthlyRevenue as &$month) {
            $month['ca_formatted'] = $this->formatCurrency((float) $month['ca']);
            $month['mois_label'] = date('M Y', strtotime($month['mois'] . '-01'));
        }
        unset($month);

        // Top produits
        $topProducts = $db->query("
            SELECT p.id, p.nom_article, p.prix, p.image,
                COUNT(c.a) AS nb_ventes,
                SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))) AS ca
            FROM produits p
            LEFT JOIN commande c ON c.id_article = p.id
            GROUP BY p.id
            ORDER BY nb_ventes DESC
            LIMIT 5
        ")->fetchAll();

        foreach ($topProducts as &$product) {
            $product['ca_formatted'] = $this->formatCurrency((float) $product['ca']);
            $product['prix_formatted'] = $this->formatCurrency((float) $product['prix']);
        }
        unset($product);

        // Formatage final
        $totalRevenueFormatted = $this->formatCurrency($totalRevenue);
        $commissionTotalFormatted = $this->formatCurrency($commissionTotal);
        $averageBasketFormatted = $this->formatCurrency($averageBasket);

        $currentPage = 'dashboard';
        require_once __DIR__ . '/../../../Views/admin/dashboard.php';
    }
}
