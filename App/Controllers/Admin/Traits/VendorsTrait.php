<?php

/**
 * NDIGITMARKET - Admin trait : Vendors
 * Gestion des vendeurs : liste, validation, refus.
 */

namespace App\Controllers\Admin\Traits;

trait VendorsTrait
{
    public function vendors()
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        // Filtres
        $where = [];
        $params = [];
        if (!empty($_GET['statut'])) {
            $where[] = 'dv.statut = :statut';
            $params[':statut'] = $_GET['statut'];
        }
        if (!empty($_GET['search'])) {
            $where[] = '(dv.nom_boutique LIKE :s1 OR u.nom LIKE :s2 OR u.prenom LIKE :s3 OR u.email LIKE :s4)';
            $searchTerm = '%' . $_GET['search'] . '%';
            $params[':s1'] = $searchTerm;
            $params[':s2'] = $searchTerm;
            $params[':s3'] = $searchTerm;
            $params[':s4'] = $searchTerm;
        }
        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $sortMap = [
            'id_uti' => 'dv.id_uti',
            'nom_boutique' => 'dv.nom_boutique',
            'vendeur' => 'u.nom',
            'date_demande' => 'dv.date_demande',
            'nb_produits' => 'nb_produits',
            'total_gagne' => 'total_gagne',
            'solde_portefeuille' => 'solde_portefeuille',
            'statut' => 'dv.statut',
        ];
        $sort = (string)($_GET['sort'] ?? 'date_demande');
        $sortColumn = $sortMap[$sort] ?? 'dv.date_demande';
        $order = strtoupper((string)($_GET['order'] ?? 'DESC')) === 'ASC' ? 'ASC' : 'DESC';

        $sql = "
            SELECT
                dv.id, dv.id_uti, dv.nom_boutique, dv.description, dv.categorie,
                dv.telephone, dv.image_boutique, dv.adresse, dv.pays, dv.statut, dv.date_demande,
                u.nom, u.prenom, u.email,
                COALESCE(pv.solde, 0)        AS solde_portefeuille,
                COALESCE(pv.total_gagne, 0)  AS total_gagne,
                COUNT(DISTINCT p.id)         AS nb_produits,
                COUNT(DISTINCT c.a)          AS nb_ventes
            FROM demandes_vendeur dv
            LEFT JOIN utilisateur u           ON u.id_uti  = dv.id_uti
            LEFT JOIN portefeuille_vendeur pv ON pv.id_uti = dv.id_uti
            LEFT JOIN produits p              ON p.id_vendeur = dv.id_uti
            LEFT JOIN commande c              ON c.id_article = p.id
            $whereClause
            GROUP BY dv.id
            ORDER BY $sortColumn $order
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $vendors = $stmt->fetchAll();

        foreach ($vendors as &$vendor) {
            $vendor['full_name']            = trim(($vendor['prenom'] ?? '') . ' ' . ($vendor['nom'] ?? ''));
            $vendor['solde_formatted']      = $this->formatCurrency((float) $vendor['solde_portefeuille']);
            $vendor['total_gagne_formatted'] = $this->formatCurrency((float) $vendor['total_gagne']);
            $vendor['date_formatted']       = $this->formatDate($vendor['date_demande'], 'd/m/Y H:i');
            $vendor['statut_label'] = match ($vendor['statut'] ?? '') {
                'en_attente' => 'En attente',
                'acceptee'   => 'Acceptée',
                'refusee'    => 'Refusée',
                default      => 'N/A',
            };
            $vendor['statut_class'] = match ($vendor['statut'] ?? '') {
                'en_attente' => 'warning',
                'acceptee'   => 'success',
                'refusee'    => 'danger',
                default      => 'secondary',
            };
        }
        unset($vendor);

        $totalVendors = count($vendors);
        $currentPage  = 'gestion-vendeur';
        require_once __DIR__ . '/../../../Views/admin/gestion-vendeur.php';
    }

    public function exportVendors(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        $where = [];
        $params = [];
        if (!empty($_GET['statut'])) {
            $where[] = 'dv.statut = :statut';
            $params[':statut'] = $_GET['statut'];
        }
        if (!empty($_GET['search'])) {
            $where[] = '(dv.nom_boutique LIKE :s1 OR u.nom LIKE :s2 OR u.prenom LIKE :s3 OR u.email LIKE :s4)';
            $searchTerm = '%' . $_GET['search'] . '%';
            $params[':s1'] = $searchTerm;
            $params[':s2'] = $searchTerm;
            $params[':s3'] = $searchTerm;
            $params[':s4'] = $searchTerm;
        }
        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $stmt = $db->prepare("
            SELECT
                dv.id, dv.id_uti, dv.nom_boutique, dv.categorie, dv.telephone,
                dv.pays, dv.statut, dv.date_demande,
                u.nom, u.prenom, u.email,
                COALESCE(pv.solde, 0) AS solde,
                COALESCE(pv.total_gagne, 0) AS total_gagne,
                COUNT(DISTINCT p.id) AS nb_produits,
                COUNT(DISTINCT c.a) AS nb_ventes
            FROM demandes_vendeur dv
            LEFT JOIN utilisateur u ON u.id_uti = dv.id_uti
            LEFT JOIN portefeuille_vendeur pv ON pv.id_uti = dv.id_uti
            LEFT JOIN produits p ON p.id_vendeur = dv.id_uti
            LEFT JOIN commande c ON c.id_article = p.id
            $whereClause
            GROUP BY dv.id
            ORDER BY dv.date_demande DESC
        ");
        $stmt->execute($params);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="vendeurs_' . date('Ymd_His') . '.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['ID demande', 'ID utilisateur', 'Boutique', 'Vendeur', 'Email', 'Téléphone', 'Catégorie', 'Pays', 'Statut', 'Date demande', 'Produits', 'Ventes', 'Solde', 'Total gagné']);
        foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            fputcsv($out, [
                $row['id'],
                $row['id_uti'],
                $row['nom_boutique'],
                trim(($row['prenom'] ?? '') . ' ' . ($row['nom'] ?? '')),
                $row['email'],
                $row['telephone'],
                $row['categorie'],
                $row['pays'],
                $row['statut'],
                $row['date_demande'],
                $row['nb_produits'],
                $row['nb_ventes'],
                $row['solde'],
                $row['total_gagne'],
            ]);
        }
        fclose($out);
        exit;
    }

    public function exportVendorPayments(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        $rows = $db->query("
            SELECT dv.id, dv.id_uti, dv.nom_boutique, u.nom, u.prenom, u.email,
                   COALESCE(pv.solde, 0) AS solde, COALESCE(pv.total_gagne, 0) AS total_gagne
            FROM demandes_vendeur dv
            LEFT JOIN utilisateur u ON u.id_uti = dv.id_uti
            LEFT JOIN portefeuille_vendeur pv ON pv.id_uti = dv.id_uti
            WHERE COALESCE(pv.solde, 0) > 0
            ORDER BY pv.solde DESC
        ")->fetchAll(\PDO::FETCH_ASSOC);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="versements_vendeurs_' . date('Ymd_His') . '.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['ID demande', 'ID utilisateur', 'Boutique', 'Vendeur', 'Email', 'Solde à verser', 'Total gagné']);
        foreach ($rows as $row) {
            fputcsv($out, [
                $row['id'],
                $row['id_uti'],
                $row['nom_boutique'],
                trim(($row['prenom'] ?? '') . ' ' . ($row['nom'] ?? '')),
                $row['email'],
                $row['solde'],
                $row['total_gagne'],
            ]);
        }
        fclose($out);
        exit;
    }

    public function getVendorDecisionHistory(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $id = (int)($_GET['id'] ?? 0);

        $db->exec("CREATE TABLE IF NOT EXISTS admin_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            admin_id INT NULL,
            action VARCHAR(100) NOT NULL,
            target_user_id INT NULL,
            details TEXT,
            status VARCHAR(20) DEFAULT 'success',
            ip_address VARCHAR(45),
            user_agent TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_action (action),
            INDEX idx_target (target_user_id),
            INDEX idx_date (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $actions = ['approve_vendor', 'reject_vendor', 'update_vendor', 'suspend_vendor', 'delete_vendor', 'mark_payment'];
        $placeholders = implode(',', array_fill(0, count($actions), '?'));
        $params = $actions;
        $whereTarget = '';
        if ($id > 0) {
            $whereTarget = ' AND target_user_id = ?';
            $params[] = $id;
        }

        $stmt = $db->prepare("
            SELECT action, details, status, created_at, ip_address
            FROM admin_logs
            WHERE action IN ($placeholders) $whereTarget
            ORDER BY created_at DESC
            LIMIT 100
        ");
        $stmt->execute($params);
        $history = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($history as &$item) {
            $item['date_formatted'] = $this->formatDate($item['created_at'] ?? null, 'd/m/Y H:i');
            $item['action_label'] = match ($item['action'] ?? '') {
                'approve_vendor' => 'Acceptation',
                'reject_vendor' => 'Refus',
                'update_vendor' => 'Modification',
                'suspend_vendor' => 'Suspension',
                'delete_vendor' => 'Suppression',
                'mark_payment' => 'Versement',
                default => $item['action'] ?? 'Action',
            };
        }
        unset($item);

        $this->jsonResponse(['success' => true, 'history' => $history]);
    }

    /**
     * BUG FIX : la version précédente faisait
     *   $demande = $db->prepare(...)->execute([$id]) ? $db->prepare(...)->fetch() : null;
     * Le second prepare() n'était jamais exécuté -> $demande valait toujours false.
     */
    public function approveVendor(int $id): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $db->beginTransaction();
        try {
            $stmt = $db->prepare("SELECT id_uti FROM demandes_vendeur WHERE id = ?");
            $stmt->execute([$id]);
            $demande = $stmt->fetch();

            if (!$demande) {
                throw new \Exception("Demande introuvable");
            }

            $db->prepare("UPDATE demandes_vendeur SET statut = 'acceptee' WHERE id = ?")->execute([$id]);
            $db->prepare("UPDATE utilisateur SET type = 'pro' WHERE id_uti = ?")->execute([$demande['id_uti']]);

            // Créer le portefeuille s'il n'existe pas
            $check = $db->prepare("SELECT id FROM portefeuille_vendeur WHERE id_uti = ?");
            $check->execute([$demande['id_uti']]);
            if (!$check->fetch()) {
                $db->prepare("INSERT INTO portefeuille_vendeur (id_uti, solde, total_gagne) VALUES (?, 0, 0)")
                    ->execute([$demande['id_uti']]);
            }

            $db->commit();
            $this->logAction('approve_vendor', $id, "Vendeur approuvé (id_uti={$demande['id_uti']})");
            $this->jsonResponse(['success' => true, 'message' => 'Vendeur approuvé']);
        } catch (\Exception $e) {
            $db->rollBack();
            $this->logAction('approve_vendor', $id, "Échec: " . $e->getMessage(), 'error');
            $this->jsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // --- NOUVELLES MÉTHODES POUR LES ACTIONS AJAX ---

    public function rejectVendor(int $routeId = 0): void
    {
        $this->checkAuth();
        $id = $routeId ?: ($_POST['id'] ?? null);
        $motif = $_POST['motif'] ?? '';
        if (!$id) {
            $this->jsonResponse(['success' => false, 'message' => 'ID manquant'], 400);
            return;
        }

        $db = \Database::getConnection();
        $stmt = $db->prepare("UPDATE demandes_vendeur SET statut = 'refusee' WHERE id = ?");
        $stmt->execute([(int)$id]);

        $this->logAction('reject_vendor', (int)$id, "Demande refusée: $motif");
        $this->jsonResponse(['success' => true, 'message' => 'Demande refusée avec succès']);
    }

    public function updateVendor(int $routeId = 0): void
    {
        $this->checkAuth();
        $id = $routeId ?: ($_POST['id'] ?? null);
        $nom = $_POST['nom_boutique'] ?? '';
        $desc = $_POST['description'] ?? '';
        $statut = $_POST['statut'] ?? 'acceptee';
        if (!$id) {
            $this->jsonResponse(['success' => false, 'message' => 'ID manquant'], 400);
            return;
        }

        $statutMap = [
            'actif' => 'acceptee',
            'suspendu' => 'refusee',
            'banni' => 'refusee',
            'en_attente' => 'en_attente',
            'acceptee' => 'acceptee',
            'refusee' => 'refusee',
        ];
        $statut = $statutMap[$statut] ?? 'acceptee';

        $db = \Database::getConnection();
        $stmt = $db->prepare("UPDATE demandes_vendeur SET nom_boutique = ?, description = ?, statut = ? WHERE id = ?");
        $stmt->execute([$nom, $desc, $statut, $id]);

        $this->logAction('update_vendor', (int)$id, 'Vendeur mis à jour');
        $this->jsonResponse(['success' => true, 'message' => 'Vendeur mis à jour']);
    }

    public function suspendVendor(int $routeId = 0): void
    {
        $this->checkAuth();
        $id = $routeId ?: ($_POST['id'] ?? null);
        $motif = $_POST['motif'] ?? '';
        if (!$id) {
            $this->jsonResponse(['success' => false, 'message' => 'ID manquant'], 400);
            return;
        }

        $db = \Database::getConnection();
        $stmt = $db->prepare("SELECT id_uti FROM demandes_vendeur WHERE id = ?");
        $stmt->execute([(int)$id]);
        $idUti = (int)$stmt->fetchColumn();
        if (!$idUti) {
            $this->jsonResponse(['success' => false, 'message' => 'Vendeur introuvable'], 404);
            return;
        }

        $db->beginTransaction();
        try {
            $db->prepare("UPDATE utilisateur SET statut = 'bloque' WHERE id_uti = ?")->execute([$idUti]);
            $db->prepare("UPDATE produits SET statut = 'refuse', commentaire = ? WHERE id_vendeur = ? AND statut = 'en_attente'")
                ->execute([$motif, $idUti]);
            $db->commit();
        } catch (\Exception $e) {
            $db->rollBack();
            $this->jsonResponse(['success' => false, 'message' => 'Suspension impossible : ' . $e->getMessage()], 500);
            return;
        }

        $this->logAction('suspend_vendor', (int)$id, "Vendeur suspendu: $motif");
        $this->jsonResponse(['success' => true, 'message' => 'Vendeur suspendu']);
    }

    public function deleteVendor(int $routeId = 0): void
    {
        $this->checkAuth();
        $id = $routeId ?: ($_POST['id'] ?? null);
        $confirmation = $_POST['confirmation'] ?? '';
        if ($confirmation !== 'SUPPRIMER') {
            $this->jsonResponse(['success' => false, 'message' => 'Confirmation incorrecte'], 400);
            return;
        }
        if (!$id) {
            $this->jsonResponse(['success' => false, 'message' => 'ID manquant'], 400);
            return;
        }

        $db = \Database::getConnection();
        $stmt = $db->prepare("SELECT id_uti FROM demandes_vendeur WHERE id = ?");
        $stmt->execute([(int)$id]);
        $idUti = (int)$stmt->fetchColumn();
        if (!$idUti) {
            $this->jsonResponse(['success' => false, 'message' => 'Vendeur introuvable'], 404);
            return;
        }

        $check = $db->prepare("SELECT COUNT(*) FROM produits WHERE id_vendeur = ?");
        $check->execute([$idUti]);
        if ($check->fetchColumn() > 0) {
            $this->jsonResponse(['success' => false, 'message' => 'Impossible : ce vendeur a encore des produits. Supprimez-les d\'abord.'], 400);
            return;
        }

        $db->beginTransaction();
        try {
            $db->prepare("DELETE FROM demandes_vendeur WHERE id = ?")->execute([(int)$id]);
            $db->prepare("DELETE FROM portefeuille_vendeur WHERE id_uti = ?")->execute([$idUti]);
            $db->prepare("UPDATE utilisateur SET type = '-' WHERE id_uti = ?")->execute([$idUti]);
            $db->commit();
        } catch (\Exception $e) {
            $db->rollBack();
            $this->jsonResponse(['success' => false, 'message' => 'Suppression impossible : ' . $e->getMessage()], 500);
            return;
        }

        $this->logAction('delete_vendor', (int)$id, 'Vendeur supprimé');
        $this->jsonResponse(['success' => true, 'message' => 'Vendeur supprimé']);
    }

    public function markPayment(int $routeId = 0): void
    {
        $this->checkAuth();
        $id = $routeId ?: ($_POST['id'] ?? null);
        $montant = (float)($_POST['montant'] ?? 0);
        $reference = $_POST['reference'] ?? '';
        if (!$id || $montant <= 0 || $reference === '') {
            $this->jsonResponse(['success' => false, 'message' => 'Données invalides'], 400);
            return;
        }

        $db = \Database::getConnection();
        $stmt = $db->prepare("SELECT id_uti FROM demandes_vendeur WHERE id = ?");
        $stmt->execute([(int)$id]);
        $idUti = (int)$stmt->fetchColumn();
        if (!$idUti) {
            $this->jsonResponse(['success' => false, 'message' => 'Vendeur introuvable'], 404);
            return;
        }

        $stmt = $db->prepare("UPDATE portefeuille_vendeur SET solde = GREATEST(0, solde - ?) WHERE id_uti = ?");
        $stmt->execute([$montant, $idUti]);

        $this->logAction('mark_payment', (int)$id, "Versement $montant FCFA, ref $reference");
        $this->jsonResponse(['success' => true, 'message' => 'Versement de ' . $montant . ' FCFA marqué comme effectué']);
    }

    // NOUVELLE MÉTHODE : Récupérer les détails (Produits + Ventes) pour la modale
    public function getVendorDetails(int $routeId = 0): void
    {
        $this->checkAuth();
        $id = $routeId ?: ($_GET['id'] ?? null);
        if (!$id) {
            $this->jsonResponse(['success' => false, 'message' => 'ID manquant'], 400);
            return;
        }

        $db = \Database::getConnection();

        $stmtP = $db->prepare("SELECT id, nom_article AS nom_produit, prix, statut, date_ajout FROM produits WHERE id_vendeur = ? ORDER BY date_ajout DESC LIMIT 10");
        $stmtP->execute([$id]);
        $produits = $stmtP->fetchAll();

        $stmtV = $db->prepare("SELECT c.a AS id, c.date_commande, c.prix AS montant_total
                               FROM commande c
                               JOIN produits p ON c.id_article = p.id
                               WHERE p.id_vendeur = ? 
                               ORDER BY c.date_commande DESC LIMIT 10");
        $stmtV->execute([$id]);
        $ventes = $stmtV->fetchAll();

        $this->jsonResponse([
            'success' => true,
            'produits' => $produits,
            'ventes' => $ventes
        ]);
    }
}
