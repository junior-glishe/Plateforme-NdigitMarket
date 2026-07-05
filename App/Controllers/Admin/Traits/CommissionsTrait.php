<?php
namespace App\Controllers\Admin\Traits;

trait CommissionsTrait
{
    /**
     * PAGE PRINCIPALE - Vue d'ensemble financière
     */
    public function commissions()
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        // Période filtrable
        $periode = $_GET['periode'] ?? 'all';
        $dateFilter = $this->getDateFilter($periode);

        // === STATISTIQUES GLOBALES ===
        $totalRevenue = $this->normalizeFloat(
            $db->query("
                SELECT COALESCE(SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))), 0) 
                FROM commande c 
                $dateFilter
            ")->fetchColumn()
        );
        $commissionTotal = $totalRevenue * 0.10;
        $totalOrders = (int) $db->query("SELECT COUNT(*) FROM commande c $dateFilter")->fetchColumn();
        $totalProductsSold = (int) $db->query("SELECT COUNT(DISTINCT c.id_article) FROM commande c $dateFilter")->fetchColumn();

        // Remboursements
        $totalRefunds = (int) $db->query("SELECT COUNT(*) FROM remboursements")->fetchColumn();
        $refundsDone = (int) $db->query("SELECT COUNT(*) FROM remboursements WHERE statut = 'effectue'")->fetchColumn();
        $refundsPending = (int) $db->query("SELECT COUNT(*) FROM remboursements WHERE statut = 'en_cours'")->fetchColumn();
        $commissionRecovered = $this->normalizeFloat(
            $db->query("SELECT COALESCE(SUM(commission_recuperee), 0) FROM remboursements WHERE statut = 'effectue'")->fetchColumn()
        );

        // === COMMISSIONS PAR VENDEUR ===
        try {
            $commissionsByVendor = $db->query("
                SELECT
                    u.id_uti,
                    u.nom,
                    u.prenom,
                    u.email,
                    COUNT(c.a) AS nb_ventes,
                    COALESCE(SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))), 0) AS ca_vendeur,
                    COALESCE(SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))), 0) * 0.90 AS net_vendeur,
                    COALESCE(SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))), 0) * 0.10 AS commission_plateforme,
                    COALESCE(pv.solde, 0) AS solde_disponible
                FROM utilisateur u
                LEFT JOIN produits p ON p.id_vendeur = u.id_uti
                LEFT JOIN commande c ON c.id_article = p.id
                LEFT JOIN portefeuille_vendeur pv ON pv.id_uti = u.id_uti
                GROUP BY u.id_uti
                HAVING nb_ventes > 0
                ORDER BY ca_vendeur DESC
            ")->fetchAll();
        } catch (\Exception $e) {
            $commissionsByVendor = [];
        }

        foreach ($commissionsByVendor as &$vendor) {
            $vendor['full_name'] = trim(($vendor['prenom'] ?? '') . ' ' . ($vendor['nom'] ?? ''));
            $vendor['ca_formatted'] = $this->formatCurrency((float)$vendor['ca_vendeur']);
            $vendor['net_formatted'] = $this->formatCurrency((float)$vendor['net_vendeur']);
            $vendor['commission_formatted'] = $this->formatCurrency((float)$vendor['commission_plateforme']);
            $vendor['solde_formatted'] = $this->formatCurrency((float)$vendor['solde_disponible']);
        }
        unset($vendor);

        // === COMMISSIONS PAR CATÉGORIE ===
        try {
            $commissionsByCategory = $db->query("
                SELECT
                    cat.id,
                    cat.nom_categorie,
                    COUNT(c.a) AS nb_ventes,
                    COALESCE(SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))), 0) AS ca_categorie,
                    COALESCE(SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))), 0) * 0.10 AS commission_plateforme
                FROM categories cat
                LEFT JOIN produits p ON p.categorie_id = cat.id
                LEFT JOIN commande c ON c.id_article = p.id
                GROUP BY cat.id
                HAVING nb_ventes > 0
                ORDER BY ca_categorie DESC
            ")->fetchAll();
        } catch (\Exception $e) {
            $commissionsByCategory = [];
        }

        foreach ($commissionsByCategory as &$cat) {
            $cat['ca_formatted'] = $this->formatCurrency((float)$cat['ca_categorie']);
            $cat['commission_formatted'] = $this->formatCurrency((float)$cat['commission_plateforme']);
            $cat['part_ca'] = $totalRevenue > 0 
                ? round(($cat['ca_categorie'] / $totalRevenue) * 100, 1) 
                : 0;
        }
        unset($cat);

        // === LISTE DES REMBOURSEMENTS ===
        $refunds = $db->query("
            SELECT 
                r.*,
                c.email AS client_email,
                p.nom_article,
                p.id_vendeur,
                v.nom AS vendeur_nom,
                v.prenom AS vendeur_prenom,
                u.nom AS client_nom,
                u.prenom AS client_prenom
            FROM remboursements r
            LEFT JOIN commande c ON c.a = r.commande_id
            LEFT JOIN produits p ON p.id = c.id_article
            LEFT JOIN utilisateur v ON v.id_uti = p.id_vendeur
            LEFT JOIN utilisateur u ON u.id_uti = r.id_client
            ORDER BY r.date_creation DESC
            LIMIT 100
        ")->fetchAll();

        foreach ($refunds as &$refund) {
            $refund['montant_formatted'] = $this->formatCurrency((float)$refund['montant']);
            $refund['commission_recuperee_formatted'] = $this->formatCurrency((float)$refund['commission_recuperee']);
            $refund['date_formatted'] = $this->formatDate($refund['date_creation']);
            $refund['vendeur_full_name'] = trim(($refund['vendeur_prenom'] ?? '') . ' ' . ($refund['vendeur_nom'] ?? ''));
            $refund['client_full_name'] = trim(($refund['client_prenom'] ?? '') . ' ' . ($refund['client_nom'] ?? ''));
            if (empty($refund['client_full_name']) || $refund['client_full_name'] === ' ') {
                $refund['client_full_name'] = $refund['client_email'] ?? 'N/A';
            }
            $refund['statut_label'] = match ($refund['statut']) {
                'effectue' => 'Effectué',
                'en_cours' => 'En cours',
                'annule'   => 'Annulé',
                default    => 'Inconnu',
            };
            $refund['statut_class'] = match ($refund['statut']) {
                'effectue' => 'success',
                'en_cours' => 'warning',
                'annule'   => 'danger',
                default    => 'secondary',
            };
        }
        unset($refund);

        // Vendeurs pour le select
        try {
            $vendeurs = $db->query("
                SELECT DISTINCT u.id_uti, u.nom, u.prenom 
                FROM utilisateur u
                INNER JOIN produits p ON p.id_vendeur = u.id_uti
                ORDER BY u.nom ASC
            ")->fetchAll();
        } catch (\Exception $e) {
            $vendeurs = [];
        }

        $totalRevenueFormatted = $this->formatCurrency($totalRevenue);
        $commissionTotalFormatted = $this->formatCurrency($commissionTotal);
        $netPlatform = $commissionTotal - $commissionRecovered;
        $netPlatformFormatted = $this->formatCurrency($netPlatform);
        $totalVendeursActifs = count($commissionsByVendor);
        $soldeAVerser = array_sum(array_column($commissionsByVendor, 'solde_disponible'));
        $soldeAVerserFormatted = $this->formatCurrency((float)$soldeAVerser);

        $currentPage = 'financieres-commission';
        require_once __DIR__ . '/../../../Views/admin/financieres-commission.php';
    }

    /**
     * RAPPORT MENSUEL
     */
    public function getMonthlyReport(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $mois = $_GET['mois'] ?? date('Y-m');

        $dateFrom = $mois . '-01';
        $dateTo = date('Y-m-t', strtotime($dateFrom));

        $stmt = $db->prepare("
            SELECT 
                COUNT(c.a) AS nb_commandes,
                COUNT(DISTINCT c.id_article) AS nb_produits,
                COALESCE(SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))), 0) AS ca_total,
                COUNT(DISTINCT p.id_vendeur) AS nb_vendeurs
            FROM commande c
            LEFT JOIN produits p ON p.id = c.id_article
            WHERE c.date_commande BETWEEN ? AND ?
        ");
        $stmt->execute([$dateFrom, $dateTo . ' 23:59:59']);
        $stats = $stmt->fetch(\PDO::FETCH_ASSOC);

        // Top 5 produits
        $topProducts = $db->prepare("
            SELECT p.id, p.nom_article, p.prix,
                   COUNT(c.a) AS nb_ventes,
                   SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))) AS ca
            FROM produits p
            INNER JOIN commande c ON c.id_article = p.id
            WHERE c.date_commande BETWEEN ? AND ?
            GROUP BY p.id
            ORDER BY nb_ventes DESC
            LIMIT 5
        ");
        $topProducts->execute([$dateFrom, $dateTo . ' 23:59:59']);
        $topProducts = $topProducts->fetchAll(\PDO::FETCH_ASSOC);

        // Top 5 vendeurs
        $topVendors = $db->prepare("
            SELECT u.id_uti, u.nom, u.prenom,
                   COUNT(c.a) AS nb_ventes,
                   SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))) AS ca
            FROM utilisateur u
            INNER JOIN produits p ON p.id_vendeur = u.id_uti
            INNER JOIN commande c ON c.id_article = p.id
            WHERE c.date_commande BETWEEN ? AND ?
            GROUP BY u.id_uti
            ORDER BY ca DESC
            LIMIT 5
        ");
        $topVendors->execute([$dateFrom, $dateTo . ' 23:59:59']);
        $topVendors = $topVendors->fetchAll(\PDO::FETCH_ASSOC);

        $this->jsonResponse([
            'success' => true,
            'report' => [
                'mois' => $mois,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'stats' => $stats,
                'ca_formatted' => $this->formatCurrency((float)($stats['ca_total'] ?? 0)),
                'commission_plateforme' => $this->formatCurrency((float)($stats['ca_total'] ?? 0) * 0.10),
                'commission_vendeurs' => $this->formatCurrency((float)($stats['ca_total'] ?? 0) * 0.90),
                'top_products' => $topProducts,
                'top_vendors' => $topVendors,
            ]
        ]);
    }

    /**
     * RAPPORT PAR CATÉGORIE
     */
    public function getCategoryReport(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $categorieId = (int)($_GET['categorie_id'] ?? 0);

        if (!$categorieId) {
            $this->jsonResponse(['success' => false, 'message' => 'Catégorie requise'], 400);
            return;
        }

        $stmt = $db->prepare("
            SELECT 
                cat.nom_categorie,
                COUNT(c.a) AS nb_ventes,
                COALESCE(SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))), 0) AS ca_total,
                COALESCE(SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))), 0) * 0.10 AS commission
            FROM categories cat
            LEFT JOIN produits p ON p.categorie_id = cat.id
            LEFT JOIN commande c ON c.id_article = p.id
            WHERE cat.id = ?
            GROUP BY cat.id
        ");
        $stmt->execute([$categorieId]);
        $cat = $stmt->fetch(\PDO::FETCH_ASSOC);

        // Produits de la catégorie
        $stmtP = $db->prepare("
            SELECT p.id, p.nom_article, p.prix,
                   u.nom AS vendeur_nom, u.prenom AS vendeur_prenom,
                   COUNT(c.a) AS nb_ventes,
                   COALESCE(SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))), 0) AS ca
            FROM produits p
            LEFT JOIN utilisateur u ON u.id_uti = p.id_vendeur
            LEFT JOIN commande c ON c.id_article = p.id
            WHERE p.categorie_id = ?
            GROUP BY p.id
            ORDER BY nb_ventes DESC
            LIMIT 20
        ");
        $stmtP->execute([$categorieId]);
        $products = $stmtP->fetchAll(\PDO::FETCH_ASSOC);

        $this->jsonResponse([
            'success' => true,
            'category' => $cat,
            'products' => $products,
            'ca_formatted' => $this->formatCurrency((float)($cat['ca_total'] ?? 0)),
            'commission_formatted' => $this->formatCurrency((float)($cat['commission'] ?? 0)),
        ]);
    }

    /**
     * RAPPORT PAR VENDEUR
     */
    public function getVendorReport(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $vendeurId = (int)($_GET['vendeur_id'] ?? 0);

        if (!$vendeurId) {
            $this->jsonResponse(['success' => false, 'message' => 'Vendeur requis'], 400);
            return;
        }

        $stmt = $db->prepare("
            SELECT 
                u.id_uti, u.nom, u.prenom, u.email,
                COUNT(c.a) AS nb_ventes,
                COALESCE(SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))), 0) AS ca_total,
                COALESCE(SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))), 0) * 0.90 AS net_vendeur,
                COALESCE(pv.solde, 0) AS solde_disponible
            FROM utilisateur u
            LEFT JOIN produits p ON p.id_vendeur = u.id_uti
            LEFT JOIN commande c ON c.id_article = p.id
            LEFT JOIN portefeuille_vendeur pv ON pv.id_uti = u.id_uti
            WHERE u.id_uti = ?
            GROUP BY u.id_uti
        ");
        $stmt->execute([$vendeurId]);
        $vendor = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$vendor) {
            $this->jsonResponse(['success' => false, 'message' => 'Vendeur introuvable'], 404);
            return;
        }

        // Produits du vendeur
        $stmtP = $db->prepare("
            SELECT p.id, p.nom_article, p.prix, p.statut,
                   COUNT(c.a) AS nb_ventes,
                   COALESCE(SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))), 0) AS ca
            FROM produits p
            LEFT JOIN commande c ON c.id_article = p.id
            WHERE p.id_vendeur = ?
            GROUP BY p.id
            ORDER BY nb_ventes DESC
            LIMIT 20
        ");
        $stmtP->execute([$vendeurId]);
        $products = $stmtP->fetchAll(\PDO::FETCH_ASSOC);

        $this->jsonResponse([
            'success' => true,
            'vendor' => $vendor,
            'products' => $products,
            'ca_formatted' => $this->formatCurrency((float)($vendor['ca_total'] ?? 0)),
            'net_formatted' => $this->formatCurrency((float)($vendor['net_vendeur'] ?? 0)),
            'solde_formatted' => $this->formatCurrency((float)($vendor['solde_disponible'] ?? 0)),
        ]);
    }

    /**
     * CRÉER UN REMBOURSEMENT
     */
    public function createRefund(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        $commandeId = (int)($_POST['commande_id'] ?? 0);
        $montant = (float)($_POST['montant'] ?? 0);
        $motif = trim($_POST['motif'] ?? '');
        $recupererCommission = isset($_POST['recuperer_commission']) ? 1 : 0;
        $notifierAcheteur = isset($_POST['notifier_acheteur']) ? 1 : 0;
        $notifierVendeur = isset($_POST['notifier_vendeur']) ? 1 : 0;

        if (!$commandeId || $montant <= 0 || !$motif) {
            $this->jsonResponse(['success' => false, 'message' => 'Données invalides'], 400);
            return;
        }

        // Vérifier que la commande existe
        $stmt = $db->prepare("SELECT prix, id_client FROM commande WHERE a = ?");
        $stmt->execute([$commandeId]);
        $commande = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$commande) {
            $this->jsonResponse(['success' => false, 'message' => 'Commande introuvable'], 404);
            return;
        }

        $prixCommande = (float) str_replace(' ', '', $commande['prix']);
        if ($montant > $prixCommande) {
            $this->jsonResponse(['success' => false, 'message' => 'Montant supérieur au prix de la commande'], 400);
            return;
        }

        // Calculer commission récupérée
        $commissionRecuperee = $recupererCommission ? ($montant * 0.10) : 0;

        // Récupérer id_vendeur via le produit
        $stmtProd = $db->prepare("SELECT id_vendeur FROM produits WHERE id = (SELECT id_article FROM commande WHERE a = ?)");
        $stmtProd->execute([$commandeId]);
        $idVendeur = $stmtProd->fetchColumn();

        $stmt = $db->prepare("
            INSERT INTO remboursements 
            (commande_id, id_client, id_vendeur, montant, commission_recuperee, motif, statut, admin_id, notifier_acheteur, notifier_vendeur)
            VALUES (?, ?, ?, ?, ?, 'en_cours', ?, ?, ?)
        ");
        $stmt->execute([
            $commandeId,
            $commande['id_client'] ?: null,
            $idVendeur ?: null,
            $montant,
            $commissionRecuperee,
            $_SESSION['admin_id'] ?? null,
            $notifierAcheteur,
            $notifierVendeur
        ]);

        $newId = $db->lastInsertId();
        $this->logAction('create_refund', $newId, "Remboursement créé: {$montant} FCFA pour commande #$commandeId");
        $this->jsonResponse(['success' => true, 'message' => 'Remboursement initié avec succès', 'id' => $newId]);
    }

    /**
     * DÉTAILS D'UN REMBOURSEMENT
     */
    public function getRefundDetails(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $id = (int)($_GET['id'] ?? 0);

        if (!$id) {
            $this->jsonResponse(['success' => false, 'message' => 'ID manquant'], 400);
            return;
        }

        $stmt = $db->prepare("
            SELECT 
                r.*,
                c.prix AS prix_commande,
                c.date_commande,
                c.email AS client_email,
                p.nom_article,
                v.nom AS vendeur_nom,
                v.prenom AS vendeur_prenom,
                u.nom AS client_nom,
                u.prenom AS client_prenom
            FROM remboursements r
            LEFT JOIN commande c ON c.a = r.commande_id
            LEFT JOIN produits p ON p.id = c.id_article
            LEFT JOIN utilisateur v ON v.id_uti = r.id_vendeur
            LEFT JOIN utilisateur u ON u.id_uti = r.id_client
            WHERE r.id = ?
        ");
        $stmt->execute([$id]);
        $refund = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$refund) {
            $this->jsonResponse(['success' => false, 'message' => 'Remboursement introuvable'], 404);
            return;
        }

        $refund['montant_formatted'] = $this->formatCurrency((float)$refund['montant']);
        $refund['commission_recuperee_formatted'] = $this->formatCurrency((float)$refund['commission_recuperee']);
        $refund['prix_commande_formatted'] = $this->formatCurrency((float) str_replace(' ', '', $refund['prix_commande'] ?? 0));
        $refund['date_formatted'] = $this->formatDate($refund['date_creation']);
        $refund['date_commande_formatted'] = $this->formatDate($refund['date_commande']);
        $refund['vendeur_full_name'] = trim(($refund['vendeur_prenom'] ?? '') . ' ' . ($refund['vendeur_nom'] ?? ''));
        $refund['client_full_name'] = trim(($refund['client_prenom'] ?? '') . ' ' . ($refund['client_nom'] ?? ''));
        if (empty($refund['client_full_name']) || $refund['client_full_name'] === ' ') {
            $refund['client_full_name'] = $refund['client_email'] ?? 'N/A';
        }

        $this->jsonResponse(['success' => true, 'refund' => $refund]);
    }

    /**
     * METTRE À JOUR LE STATUT D'UN REMBOURSEMENT
     */
    public function updateRefundStatus(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        $id = (int)($_POST['id'] ?? 0);
        $statut = $_POST['statut'] ?? '';

        if (!$id || !in_array($statut, ['effectue', 'en_cours', 'annule'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Données invalides'], 400);
            return;
        }

        $stmt = $db->prepare("UPDATE remboursements SET statut = ? WHERE id = ?");
        $stmt->execute([$statut, $id]);

        $this->logAction('update_refund_status', $id, "Statut remboursement: $statut");
        $this->jsonResponse(['success' => true, 'message' => 'Statut mis à jour']);
    }

    /**
     * EXPORT CSV - Rapport financier
     */
    public function exportFinancialReport(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $type = $_GET['type'] ?? 'vendors'; // vendors | categories | refunds

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="rapport_' . $type . '_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

        if ($type === 'vendors') {
            fputcsv($output, ['ID', 'Vendeur', 'Email', 'Nb ventes', 'CA Total', 'Commission vendeur', 'Commission plateforme', 'Solde']);
            $rows = $db->query("
                SELECT u.id_uti, u.nom, u.prenom, u.email,
                       COUNT(c.a) AS nb_ventes,
                       SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))) AS ca,
                       SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))) * 0.90 AS net,
                       SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))) * 0.10 AS comm,
                       COALESCE(pv.solde, 0) AS solde
                FROM utilisateur u
                LEFT JOIN produits p ON p.id_vendeur = u.id_uti
                LEFT JOIN commande c ON c.id_article = p.id
                LEFT JOIN portefeuille_vendeur pv ON pv.id_uti = u.id_uti
                GROUP BY u.id_uti
                HAVING nb_ventes > 0
                ORDER BY ca DESC
            ")->fetchAll();
            foreach ($rows as $r) {
                fputcsv($output, [
                    $r['id_uti'],
                    trim($r['prenom'] . ' ' . $r['nom']),
                    $r['email'],
                    $r['nb_ventes'],
                    $r['ca'],
                    $r['net'],
                    $r['comm'],
                    $r['solde']
                ]);
            }
        } elseif ($type === 'categories') {
            fputcsv($output, ['ID', 'Catégorie', 'Nb ventes', 'CA Total', 'Commission plateforme', 'Part du CA (%)']);
            $rows = $db->query("
                SELECT cat.id, cat.nom_categorie,
                       COUNT(c.a) AS nb_ventes,
                       SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))) AS ca,
                       SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))) * 0.10 AS comm
                FROM categories cat
                LEFT JOIN produits p ON p.categorie_id = cat.id
                LEFT JOIN commande c ON c.id_article = p.id
                GROUP BY cat.id
                HAVING nb_ventes > 0
                ORDER BY ca DESC
            ")->fetchAll();
            $totalCa = array_sum(array_column($rows, 'ca'));
            foreach ($rows as $r) {
                $part = $totalCa > 0 ? round(($r['ca'] / $totalCa) * 100, 1) : 0;
                fputcsv($output, [$r['id'], $r['nom_categorie'], $r['nb_ventes'], $r['ca'], $r['comm'], $part]);
            }
        } elseif ($type === 'refunds') {
            fputcsv($output, ['ID', 'Commande', 'Client', 'Vendeur', 'Montant', 'Commission récupérée', 'Motif', 'Date', 'Statut']);
            $rows = $db->query("
                SELECT r.*, c.email, p.nom_article,
                       v.nom AS v_nom, v.prenom AS v_prenom,
                       u.nom AS c_nom, u.prenom AS c_prenom
                FROM remboursements r
                LEFT JOIN commande c ON c.a = r.commande_id
                LEFT JOIN produits p ON p.id = c.id_article
                LEFT JOIN utilisateur v ON v.id_uti = r.id_vendeur
                LEFT JOIN utilisateur u ON u.id_uti = r.id_client
                ORDER BY r.date_creation DESC
            ")->fetchAll();
            foreach ($rows as $r) {
                fputcsv($output, [
                    $r['id'],
                    $r['commande_id'],
                    trim($r['c_prenom'] ?? '' . ' ' . ($r['c_nom'] ?? '')),
                    trim($r['v_prenom'] ?? '' . ' ' . ($r['v_nom'] ?? '')),
                    $r['montant'],
                    $r['commission_recuperee'],
                    $r['motif'],
                    $r['date_creation'],
                    $r['statut']
                ]);
            }
        }

        fclose($output);
        exit;
    }

    /**
     * Utilitaire : filtre de date
     */
    private function getDateFilter(string $periode): string
    {
        return match ($periode) {
            'month' => "WHERE c.date_commande >= DATE_FORMAT(NOW(), '%Y-%m-01')",
            'quarter' => "WHERE c.date_commande >= DATE_SUB(NOW(), INTERVAL 3 MONTH)",
            'year' => "WHERE c.date_commande >= DATE_FORMAT(NOW(), '%Y-01-01')",
            default => '',
        };
    }
}