<?php
namespace App\Controllers\Admin\Traits;

trait CommissionsTrait
{
    private function ensureRefundsTable(\PDO $db): void
    {
        $db->exec("CREATE TABLE IF NOT EXISTS remboursements (
            id INT AUTO_INCREMENT PRIMARY KEY,
            commande_id INT NOT NULL,
            id_client INT NULL,
            id_vendeur INT NULL,
            montant DECIMAL(14,2) NOT NULL,
            commission_recuperee DECIMAL(14,2) DEFAULT 0,
            motif TEXT NOT NULL,
            statut ENUM('effectue','en_cours','annule') NOT NULL DEFAULT 'en_cours',
            admin_id INT NULL,
            notifier_acheteur TINYINT(1) DEFAULT 1,
            notifier_vendeur TINYINT(1) DEFAULT 1,
            date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_commande (commande_id),
            INDEX idx_statut (statut),
            INDEX idx_date (date_creation)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    }

    private function normalizeRefundStatus(string $status): string
    {
        return in_array($status, ['effectue', 'en_cours', 'annule'], true) ? $status : 'en_cours';
    }

    private function financialDateCondition(string $alias, string $column): string
    {
        $period = $_GET['periode'] ?? 'all';
        $start = match ($period) {
            'month' => date('Y-m-01 00:00:00'),
            'quarter' => date('Y-m-d 00:00:00', strtotime('-3 months')),
            'year' => date('Y-01-01 00:00:00'),
            default => null,
        };

        return $start ? sprintf(" AND %s.%s >= '%s'", $alias, $column, $start) : '';
    }

    public function commissions()
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $orderDateFilter = $this->financialDateCondition('c', 'date_commande');
        $refundDateFilter = $this->financialDateCondition('r', 'date_creation');

        // Statistiques de base (sans dépendances)
        try {
            $totalRevenue = (float) $db->query("
                SELECT COALESCE(SUM(CAST(REPLACE(prix, ' ', '') AS DECIMAL(14,2))), 0) 
                FROM commande c
                WHERE 1=1 {$orderDateFilter}
            ")->fetchColumn();
        } catch (\Exception $e) {
            $totalRevenue = 0;
        }

        $commissionTotal = $totalRevenue * 0.10;
        $totalOrders = (int) $db->query("SELECT COUNT(*) FROM commande c WHERE 1=1 {$orderDateFilter}")->fetchColumn();

        // Commissions par vendeur (avec fallback si tables manquantes)
        $commissionsByVendor = [];
        try {
            $commissionsByVendor = $db->query("
                SELECT
                    u.id_uti, u.nom, u.prenom, u.email,
                    COUNT(c.a) AS nb_ventes,
                    COALESCE(SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))), 0) AS ca_vendeur,
                    COALESCE(SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))), 0) * 0.90 AS net_vendeur,
                    COALESCE(SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))), 0) * 0.10 AS commission_plateforme,
                    0 AS solde_disponible
                FROM utilisateur u
                INNER JOIN produits p ON p.id_vendeur = u.id_uti
                INNER JOIN commande c ON c.id_article = p.id
                WHERE 1=1 {$orderDateFilter}
                GROUP BY u.id_uti
                HAVING nb_ventes > 0
                ORDER BY ca_vendeur DESC
            ")->fetchAll();
        } catch (\Exception $e) {
            // Ignore l'erreur
        }

        foreach ($commissionsByVendor as &$vendor) {
            $vendor['full_name'] = trim(($vendor['prenom'] ?? '') . ' ' . ($vendor['nom'] ?? ''));
            $vendor['ca_formatted'] = $this->formatCurrency((float)$vendor['ca_vendeur']);
            $vendor['net_formatted'] = $this->formatCurrency((float)$vendor['net_vendeur']);
            $vendor['commission_formatted'] = $this->formatCurrency((float)$vendor['commission_plateforme']);
            $vendor['solde_formatted'] = $this->formatCurrency((float)$vendor['solde_disponible']);
        }
        unset($vendor);

        // Commissions par catégorie
        $commissionsByCategory = [];
        try {
            $commissionsByCategory = $db->query("
                SELECT
                    cat.id, cat.nom_categorie,
                    COUNT(c.a) AS nb_ventes,
                    COALESCE(SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))), 0) AS ca_categorie,
                    COALESCE(SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))), 0) * 0.10 AS commission_plateforme
                FROM categories cat
                INNER JOIN produits p ON p.categorie_id = cat.id
                INNER JOIN commande c ON c.id_article = p.id
                WHERE 1=1 {$orderDateFilter}
                GROUP BY cat.id
                HAVING nb_ventes > 0
                ORDER BY ca_categorie DESC
            ")->fetchAll();
        } catch (\Exception $e) {}

        foreach ($commissionsByCategory as &$cat) {
            $cat['ca_formatted'] = $this->formatCurrency((float)$cat['ca_categorie']);
            $cat['commission_formatted'] = $this->formatCurrency((float)$cat['commission_plateforme']);
            $cat['part_ca'] = $totalRevenue > 0 ? round(($cat['ca_categorie'] / $totalRevenue) * 100, 1) : 0;
        }
        unset($cat);

        // Remboursements (optionnel)
        $refunds = [];
        $totalRefunds = 0;
        $refundsDone = 0;
        $refundsPending = 0;
        $commissionRecovered = 0;
        try {
            $this->ensureRefundsTable($db);
            $totalRefunds = (int) $db->query("SELECT COUNT(*) FROM remboursements r WHERE 1=1 {$refundDateFilter}")->fetchColumn();
            $refundsDone = (int) $db->query("SELECT COUNT(*) FROM remboursements r WHERE statut = 'effectue' {$refundDateFilter}")->fetchColumn();
            $refundsPending = (int) $db->query("SELECT COUNT(*) FROM remboursements r WHERE statut = 'en_cours' {$refundDateFilter}")->fetchColumn();
            $commissionRecovered = (float) $db->query("SELECT COALESCE(SUM(commission_recuperee), 0) FROM remboursements r WHERE statut = 'effectue' {$refundDateFilter}")->fetchColumn();
            
            $refunds = $db->query("
                SELECT r.*, c.email AS client_email, p.nom_article,
                       v.nom AS vendeur_nom, v.prenom AS vendeur_prenom,
                       u.nom AS client_nom, u.prenom AS client_prenom
                FROM remboursements r
                LEFT JOIN commande c ON c.a = r.commande_id
                LEFT JOIN produits p ON p.id = c.id_article
                LEFT JOIN utilisateur v ON v.id_uti = p.id_vendeur
                LEFT JOIN utilisateur u ON u.id_uti = r.id_client
                WHERE 1=1 {$refundDateFilter}
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
                    'effectue' => 'Effectué', 'en_cours' => 'En cours', 'annule' => 'Annulé', default => 'Inconnu'
                };
                $refund['statut_class'] = match ($refund['statut']) {
                    'effectue' => 'success', 'en_cours' => 'warning', 'annule' => 'danger', default => 'secondary'
                };
            }
            unset($refund);
        } catch (\Exception $e) {
            // Table remboursements n'existe pas
        }

        // Vendeurs pour le filtre
        $vendeurs = [];
        try {
            $vendeurs = $db->query("
                SELECT DISTINCT u.id_uti, u.nom, u.prenom 
                FROM utilisateur u
                INNER JOIN produits p ON p.id_vendeur = u.id_uti
                ORDER BY u.nom ASC
            ")->fetchAll();
        } catch (\Exception $e) {}

        $totalRevenueFormatted = $this->formatCurrency($totalRevenue);
        $commissionTotalFormatted = $this->formatCurrency($commissionTotal);
        $netPlatform = $commissionTotal - $commissionRecovered;
        $netPlatformFormatted = $this->formatCurrency($netPlatform);
        $soldeAVerser = array_sum(array_column($commissionsByVendor, 'solde_disponible'));
        $soldeAVerserFormatted = $this->formatCurrency((float)$soldeAVerser);

        $currentPage = 'financieres-commission';
        require_once __DIR__ . '/../../../Views/admin/financieres-commission.php';
    }

    public function createRefund(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $this->ensureRefundsTable($db);

        $commandeInput = trim((string)($_POST['commande_id'] ?? ''));
        $montant = $this->normalizeFloat($_POST['montant'] ?? 0);
        $motif = trim($_POST['motif'] ?? '');

        if ($commandeInput === '' || $montant <= 0 || !$motif) {
            $this->jsonResponse(['success' => false, 'message' => 'Données invalides'], 400);
            return;
        }

        try {
            $stmt = $db->prepare("
                SELECT a, prix, id_client
                FROM commande
                WHERE a = ? OR commande_id = ?
                LIMIT 1
            ");
            $stmt->execute([(int)$commandeInput, $commandeInput]);
            $commande = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!$commande) {
                $this->jsonResponse(['success' => false, 'message' => 'Commande introuvable'], 404);
                return;
            }

            $commandeId = (int)$commande['a'];
            $prixCommande = $this->normalizeFloat($commande['prix'] ?? 0);
            $commissionRecuperee = !empty($_POST['recuperer_commission'])
                ? round($prixCommande * 0.10, 2)
                : $this->normalizeFloat($_POST['commission_recuperee'] ?? 0);
            $statut = $this->normalizeRefundStatus((string)($_POST['statut'] ?? 'en_cours'));

            $stmtProd = $db->prepare("SELECT id_vendeur FROM produits WHERE id = (SELECT id_article FROM commande WHERE a = ?)");
            $stmtProd->execute([$commandeId]);
            $idVendeur = $stmtProd->fetchColumn();

            $stmt = $db->prepare("
                INSERT INTO remboursements 
                (commande_id, id_client, id_vendeur, montant, commission_recuperee, motif, statut, date_creation)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $commandeId,
                $commande['id_client'] ?: null,
                $idVendeur ?: null,
                $montant,
                $commissionRecuperee,
                $motif,
                $statut
            ]);

            $this->logAction('create_refund', $db->lastInsertId(), "Remboursement créé");
            $this->jsonResponse(['success' => true, 'message' => 'Remboursement initié']);
        } catch (\Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()], 500);
        }
    }

    public function getRefundDetails(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $this->ensureRefundsTable($db);
        $id = (int)($_GET['id'] ?? 0);

        if (!$id) {
            $this->jsonResponse(['success' => false, 'message' => 'ID manquant'], 400);
            return;
        }

        try {
            $stmt = $db->prepare("
                SELECT r.*, c.prix AS prix_commande, c.date_commande, c.email AS client_email,
                       p.nom_article, v.nom AS vendeur_nom, v.prenom AS vendeur_prenom,
                       u.nom AS client_nom, u.prenom AS client_prenom
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
                $this->jsonResponse(['success' => false, 'message' => 'Introuvable'], 404);
                return;
            }

            $refund['montant_formatted'] = $this->formatCurrency((float)$refund['montant']);
            $refund['commission_recuperee_formatted'] = $this->formatCurrency((float)$refund['commission_recuperee']);
            $refund['date_formatted'] = $this->formatDate($refund['date_creation']);
            $refund['vendeur_full_name'] = trim(($refund['vendeur_prenom'] ?? '') . ' ' . ($refund['vendeur_nom'] ?? ''));
            $refund['client_full_name'] = trim(($refund['client_prenom'] ?? '') . ' ' . ($refund['client_nom'] ?? ''));
            if (empty($refund['client_full_name']) || $refund['client_full_name'] === ' ') {
                $refund['client_full_name'] = $refund['client_email'] ?? 'N/A';
            }
            $refund['statut_label'] = match ($refund['statut']) {
                'effectue' => 'Effectué', 'en_cours' => 'En cours', 'annule' => 'Annulé', default => 'Inconnu'
            };

            $this->jsonResponse(['success' => true, 'refund' => $refund]);
        } catch (\Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateRefund(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $this->ensureRefundsTable($db);
        $id = (int)($_POST['id'] ?? 0);
        $montant = $this->normalizeFloat($_POST['montant'] ?? 0);
        $commissionRecuperee = $this->normalizeFloat($_POST['commission_recuperee'] ?? 0);
        $motif = trim($_POST['motif'] ?? '');
        $statut = $this->normalizeRefundStatus((string)($_POST['statut'] ?? 'en_cours'));

        if (!$id || $montant <= 0 || !$motif) {
            $this->jsonResponse(['success' => false, 'message' => 'Données invalides'], 400);
            return;
        }

        if ($commissionRecuperee < 0) {
            $this->jsonResponse(['success' => false, 'message' => 'La commission récupérée ne peut pas être négative'], 400);
            return;
        }

        try {
            $stmt = $db->prepare("SELECT id FROM remboursements WHERE id = ?");
            $stmt->execute([$id]);
            if (!$stmt->fetchColumn()) {
                $this->jsonResponse(['success' => false, 'message' => 'Remboursement introuvable'], 404);
                return;
            }

            $stmt = $db->prepare("
                UPDATE remboursements
                SET montant = ?, commission_recuperee = ?, motif = ?, statut = ?
                WHERE id = ?
            ");
            $stmt->execute([$montant, $commissionRecuperee, $motif, $statut, $id]);

            $this->logAction('update_refund', $id, "Remboursement modifié");
            $this->jsonResponse(['success' => true, 'message' => 'Remboursement mis à jour']);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Impossible de modifier le remboursement : ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteRefund(int $id = 0): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $this->ensureRefundsTable($db);
        $id = $id ?: (int)($_POST['id'] ?? 0);
        if (!$id) {
            $this->jsonResponse(['success' => false, 'message' => 'ID manquant'], 400);
            return;
        }
        $db->prepare("DELETE FROM remboursements WHERE id = ?")->execute([$id]);
        $this->logAction('delete_refund', $id, "Remboursement supprimé");
        $this->jsonResponse(['success' => true, 'message' => 'Remboursement supprimé']);
    }

    public function bulkDeleteRefunds(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $this->ensureRefundsTable($db);
        $ids = $_POST['ids'] ?? [];
        if (empty($ids) || !is_array($ids)) {
            $this->jsonResponse(['success' => false, 'message' => 'Aucune sélection'], 400);
            return;
        }
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $intIds = array_map('intval', $ids);
        $db->prepare("DELETE FROM remboursements WHERE id IN ($placeholders)")->execute($intIds);
        $this->jsonResponse(['success' => true, 'message' => count($intIds) . ' supprimé(s)']);
    }

    public function getMonthlyReport(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $mois = $_GET['mois'] ?? date('Y-m');
        $dateFrom = $mois . '-01';
        $dateTo = date('Y-m-t', strtotime($dateFrom));

        $stmt = $db->prepare("
            SELECT COUNT(c.a) AS nb_commandes, COUNT(DISTINCT c.id_article) AS nb_produits,
                   COALESCE(SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))), 0) AS ca_total,
                   COUNT(DISTINCT p.id_vendeur) AS nb_vendeurs
            FROM commande c LEFT JOIN produits p ON p.id = c.id_article
            WHERE c.date_commande BETWEEN ? AND ?
        ");
        $stmt->execute([$dateFrom, $dateTo . ' 23:59:59']);
        $stats = $stmt->fetch(\PDO::FETCH_ASSOC);

        $topProductsStmt = $db->prepare("
            SELECT p.nom_article, COUNT(c.a) AS nb_ventes,
                   COALESCE(SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))), 0) AS ca
            FROM commande c
            INNER JOIN produits p ON p.id = c.id_article
            WHERE c.date_commande BETWEEN ? AND ?
            GROUP BY p.id
            ORDER BY nb_ventes DESC, ca DESC
            LIMIT 5
        ");
        $topProductsStmt->execute([$dateFrom, $dateTo . ' 23:59:59']);

        $topVendorsStmt = $db->prepare("
            SELECT u.nom, u.prenom,
                   COALESCE(SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))), 0) AS ca
            FROM commande c
            INNER JOIN produits p ON p.id = c.id_article
            INNER JOIN utilisateur u ON u.id_uti = p.id_vendeur
            WHERE c.date_commande BETWEEN ? AND ?
            GROUP BY u.id_uti
            ORDER BY ca DESC
            LIMIT 5
        ");
        $topVendorsStmt->execute([$dateFrom, $dateTo . ' 23:59:59']);

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
                'top_products' => $topProductsStmt->fetchAll(\PDO::FETCH_ASSOC),
                'top_vendors' => $topVendorsStmt->fetchAll(\PDO::FETCH_ASSOC),
            ]
        ]);
    }

    public function getCategoryReport(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $orderDateFilter = $this->financialDateCondition('c', 'date_commande');
        $categorieId = (int)($_GET['categorie_id'] ?? 0);
        if (!$categorieId) {
            $this->jsonResponse(['success' => false, 'message' => 'Catégorie requise'], 400);
            return;
        }

        $stmt = $db->prepare("
            SELECT cat.nom_categorie, COUNT(c.a) AS nb_ventes,
                   COALESCE(SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))), 0) AS ca_total,
                   COALESCE(SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))), 0) * 0.10 AS commission
            FROM categories cat
            LEFT JOIN produits p ON p.categorie_id = cat.id
            LEFT JOIN commande c ON c.id_article = p.id
            WHERE cat.id = ? {$orderDateFilter}
            GROUP BY cat.id
        ");
        $stmt->execute([$categorieId]);
        $cat = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$cat) {
            $this->jsonResponse(['success' => false, 'message' => 'Catégorie introuvable'], 404);
            return;
        }

        $productsStmt = $db->prepare("
            SELECT p.nom_article,
                   v.nom AS vendeur_nom,
                   v.prenom AS vendeur_prenom,
                   COUNT(c.a) AS nb_ventes,
                   COALESCE(SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))), 0) AS ca
            FROM produits p
            LEFT JOIN commande c ON c.id_article = p.id
            LEFT JOIN utilisateur v ON v.id_uti = p.id_vendeur
            WHERE p.categorie_id = ? {$orderDateFilter}
            GROUP BY p.id
            ORDER BY nb_ventes DESC, ca DESC, p.nom_article ASC
            LIMIT 20
        ");
        $productsStmt->execute([$categorieId]);

        $this->jsonResponse([
            'success' => true,
            'category' => $cat,
            'products' => $productsStmt->fetchAll(\PDO::FETCH_ASSOC),
            'ca_formatted' => $this->formatCurrency((float)($cat['ca_total'] ?? 0)),
            'commission_formatted' => $this->formatCurrency((float)($cat['commission'] ?? 0)),
        ]);
    }

    public function getVendorReport(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $orderDateFilter = $this->financialDateCondition('c', 'date_commande');
        $vendeurId = (int)($_GET['vendeur_id'] ?? 0);
        if (!$vendeurId) {
            $this->jsonResponse(['success' => false, 'message' => 'Vendeur requis'], 400);
            return;
        }

        $stmt = $db->prepare("
            SELECT u.id_uti, u.nom, u.prenom, u.email,
                   COUNT(c.a) AS nb_ventes,
                   COALESCE(SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))), 0) AS ca_total,
                   COALESCE(SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))), 0) * 0.90 AS net_vendeur,
                   0 AS solde_disponible
            FROM utilisateur u
            INNER JOIN produits p ON p.id_vendeur = u.id_uti
            INNER JOIN commande c ON c.id_article = p.id
            WHERE u.id_uti = ? {$orderDateFilter}
            GROUP BY u.id_uti
        ");
        $stmt->execute([$vendeurId]);
        $vendor = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$vendor) {
            $this->jsonResponse(['success' => false, 'message' => 'Vendeur introuvable'], 404);
            return;
        }

        $productsStmt = $db->prepare("
            SELECT p.nom_article,
                   p.prix,
                   p.statut,
                   COUNT(c.a) AS nb_ventes,
                   COALESCE(SUM(CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))), 0) AS ca
            FROM produits p
            LEFT JOIN commande c ON c.id_article = p.id
            WHERE p.id_vendeur = ? {$orderDateFilter}
            GROUP BY p.id
            ORDER BY nb_ventes DESC, ca DESC, p.nom_article ASC
            LIMIT 20
        ");
        $productsStmt->execute([$vendeurId]);

        $this->jsonResponse([
            'success' => true,
            'vendor' => $vendor,
            'products' => $productsStmt->fetchAll(\PDO::FETCH_ASSOC),
            'ca_formatted' => $this->formatCurrency((float)($vendor['ca_total'] ?? 0)),
            'net_formatted' => $this->formatCurrency((float)($vendor['net_vendeur'] ?? 0)),
            'solde_formatted' => $this->formatCurrency(0),
        ]);
    }

    public function exportFinancialReport(): void
    {
        $this->checkAuth();
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="rapport_' . date('Y-m-d') . '.csv"');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Export en cours...']);
        fclose($output);
        exit;
    }
}
