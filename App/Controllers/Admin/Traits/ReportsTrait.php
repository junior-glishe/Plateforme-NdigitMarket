<?php

/**
 * NDIGITMARKET - Admin trait: Reports
 */

namespace App\Controllers\Admin\Traits;

trait ReportsTrait
{
    public function reports()
    {
        $this->checkAuth();
        $db = \Database::getConnection();

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

        $topProducts = $db->query("
            SELECT 
                p.id,
                p.nom_article,
                p.prix,
                p.image,
                COUNT(c.a) AS nb_ventes,
                SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))) AS ca
            FROM produits p
            LEFT JOIN commande c ON c.id_article = p.id
            GROUP BY p.id
            ORDER BY nb_ventes DESC
            LIMIT 10
        ")->fetchAll();

        foreach ($topProducts as &$product) {
            $product['ca_formatted'] = $this->formatCurrency((float) $product['ca']);
            $product['prix_formatted'] = $this->formatCurrency((float) $product['prix']);
        }
        unset($product);

        $topVendors = $db->query("
            SELECT 
                u.id_uti,
                u.nom,
                u.prenom,
                u.email,
                COUNT(c.a) AS nb_ventes,
                SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))) AS ca
            FROM utilisateur u
            JOIN produits p ON p.id_vendeur = u.id_uti
            LEFT JOIN commande c ON c.id_article = p.id
            WHERE u.type = 'pro'
            GROUP BY u.id_uti
            ORDER BY ca DESC
            LIMIT 10
        ")->fetchAll();

        foreach ($topVendors as &$vendor) {
            $vendor['full_name'] = trim($vendor['prenom'] . ' ' . $vendor['nom']);
            $vendor['ca_formatted'] = $this->formatCurrency((float) $vendor['ca']);
        }
        unset($vendor);

        $totalUsers = (int) $db->query("SELECT COUNT(*) FROM utilisateur")->fetchColumn();
        $totalVendors = (int) $db->query("SELECT COUNT(*) FROM utilisateur WHERE type = 'pro'")->fetchColumn();
        $totalProducts = (int) $db->query("SELECT COUNT(*) FROM produits")->fetchColumn();
        $totalOrders = (int) $db->query("SELECT COUNT(*) FROM commande")->fetchColumn();
        $totalRevenue = $this->normalizeFloat(
            $db->query("SELECT COALESCE(SUM(CAST(REPLACE(prix, ' ', '') AS DECIMAL(14,2))), 0) FROM commande")->fetchColumn()
        );
        $totalRevenueFormatted = $this->formatCurrency($totalRevenue);

        $currentPage = 'rapport-stat';
        require_once __DIR__ . '/../../../Views/admin/rapport-stat.php';
    }
}
