<?php
namespace App\Controllers\Admin\Traits;

trait OrdersTrait
{
    private function generateOrderCode(\PDO $db): string
    {
        do {
            $code = 'CMD_' . strtoupper(bin2hex(random_bytes(6)));
            $stmt = $db->prepare("SELECT COUNT(*) FROM commande WHERE commande_id = ?");
            $stmt->execute([$code]);
        } while ((int)$stmt->fetchColumn() > 0);

        return $code;
    }

    /**
     * READ - Liste des commandes avec filtres, recherche, tri
     */
    public function orders()
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        $where = [];
        $params = [];

        // Recherche
        if (isset($_GET['search']) && trim((string) $_GET['search']) !== '') {
            $searchTerm = '%' . trim((string) $_GET['search']) . '%';
            $where[] = "(c.a LIKE :search_id OR c.commande_id LIKE :search_code OR c.email LIKE :search_email OR p.nom_article LIKE :search_product OR u.nom LIKE :search_nom OR u.prenom LIKE :search_prenom)";
            $params[':search_id'] = $searchTerm;
            $params[':search_code'] = $searchTerm;
            $params[':search_email'] = $searchTerm;
            $params[':search_product'] = $searchTerm;
            $params[':search_nom'] = $searchTerm;
            $params[':search_prenom'] = $searchTerm;
        }

        // Filtre par vendeur
        if (isset($_GET['vendeur']) && $_GET['vendeur'] !== '') {
            $where[] = "p.id_vendeur = :vendeur";
            $params[':vendeur'] = (int) $_GET['vendeur'];
        }

        // Filtre par date
        if (isset($_GET['date_from']) && $_GET['date_from'] !== '') {
            $where[] = "c.date_commande >= :date_from";
            $params[':date_from'] = $_GET['date_from'] . ' 00:00:00';
        }
        if (isset($_GET['date_to']) && $_GET['date_to'] !== '') {
            $where[] = "c.date_commande <= :date_to";
            $params[':date_to'] = $_GET['date_to'] . ' 23:59:59';
        }

        // Filtre par prix min/max
        if (isset($_GET['prix_min']) && $_GET['prix_min'] !== '') {
            $where[] = "CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2)) >= :prix_min";
            $params[':prix_min'] = (float)$_GET['prix_min'];
        }
        if (isset($_GET['prix_max']) && $_GET['prix_max'] !== '') {
            $where[] = "CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2)) <= :prix_max";
            $params[':prix_max'] = (float)$_GET['prix_max'];
        }

        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        // Tri
        $allowedSort = [
            'date_commande' => 'c.date_commande',
            'prix'          => "CAST(REPLACE(c.prix, ' ', '') AS DECIMAL(14,2))",
            'id'            => 'c.a',
        ];
        $sortCol = $_GET['sort'] ?? 'date_commande';
        $sortDir = strtoupper($_GET['order'] ?? 'DESC');
        if (!in_array($sortDir, ['ASC', 'DESC'])) $sortDir = 'DESC';
        $orderByCol = $allowedSort[$sortCol] ?? 'c.date_commande';
        $orderBy = "$orderByCol $sortDir";

        $stmt = $db->prepare("
            SELECT 
                c.*,
                p.nom_article,
                p.image AS produit_image,
                p.id_vendeur,
                v.nom AS vendeur_nom,
                v.prenom AS vendeur_prenom,
                v.email AS vendeur_email,
                u.nom AS client_nom,
                u.prenom AS client_prenom,
                cat.nom_categorie
            FROM commande c
            LEFT JOIN produits p ON p.id = c.id_article
            LEFT JOIN utilisateur v ON v.id_uti = p.id_vendeur
            LEFT JOIN utilisateur u ON u.id_uti = c.id_client
            LEFT JOIN categories cat ON cat.id = p.categorie_id
            $whereClause
            ORDER BY $orderBy
        ");
        $stmt->execute($params);
        $orders = $stmt->fetchAll();

        // Formatage
        foreach ($orders as &$order) {
            $orderAmount = $this->normalizeFloat($order['prix'] ?? 0);
            $order['prix_formatted'] = $this->formatCurrency($orderAmount);
            $order['commission_plateforme'] = round($orderAmount * 0.10, 2);
            $order['commission_vendeur'] = round($orderAmount * 0.90, 2);
            $order['commission_plateforme_formatted'] = $this->formatCurrency($order['commission_plateforme']);
            $order['commission_vendeur_formatted'] = $this->formatCurrency($order['commission_vendeur']);
            $order['date_formatted'] = $this->formatDate($order['date_commande']);
            $order['vendeur_full_name'] = trim(($order['vendeur_prenom'] ?? '') . ' ' . ($order['vendeur_nom'] ?? ''));
            $order['client_full_name'] = trim(($order['client_prenom'] ?? '') . ' ' . ($order['client_nom'] ?? ''));
            if (empty($order['client_full_name']) || $order['client_full_name'] === ' ') {
                $order['client_full_name'] = $order['email'] ?? 'N/A';
            }
        }
        unset($order);

        // Statistiques
        $totalOrders = count($orders);
        $totalRevenue = array_sum(array_map(fn($o) => $this->normalizeFloat($o['prix'] ?? 0), $orders));
        $totalCommission = array_sum(array_map(fn($o) => $o['commission_plateforme'], $orders));

        // Vendeurs pour le filtre
        try {
            $vendeurs = $db->query("
                SELECT DISTINCT u.id_uti, u.nom, u.prenom 
                FROM utilisateur u
                INNER JOIN produits p ON p.id_vendeur = u.id_uti
                ORDER BY u.nom ASC
            ")->fetchAll();
        } catch (\Exception $e) {
            $vendeurs = $db->query("SELECT id_uti, nom, prenom FROM utilisateur ORDER BY nom ASC")->fetchAll();
        }

        $currentPage = 'gestion-commande';
        require_once __DIR__ . '/../../../Views/admin/gestion-commande.php';
    }

    /**
     * READ - Détails d'une commande (pour modale)
     */
    public function getOrderDetails(): void
    {
        $this->checkAuth();
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) {
            $this->jsonResponse(['success' => false, 'message' => 'ID manquant'], 400);
            return;
        }

        $db = \Database::getConnection();
        $stmt = $db->prepare("
            SELECT 
                c.*,
                p.nom_article,
                p.prix AS prix_produit,
                p.image AS produit_image,
                p.fichier AS produit_fichier,
                p.id_vendeur,
                v.nom AS vendeur_nom,
                v.prenom AS vendeur_prenom,
                v.email AS vendeur_email,
                u.nom AS client_nom,
                u.prenom AS client_prenom,
                u.email AS client_email,
                cat.nom_categorie
            FROM commande c
            LEFT JOIN produits p ON p.id = c.id_article
            LEFT JOIN utilisateur v ON v.id_uti = p.id_vendeur
            LEFT JOIN utilisateur u ON u.id_uti = c.id_client
            LEFT JOIN categories cat ON cat.id = p.categorie_id
            WHERE c.a = ?
        ");
        $stmt->execute([$id]);
        $order = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$order) {
            $this->jsonResponse(['success' => false, 'message' => 'Commande introuvable'], 404);
            return;
        }

        $orderAmount = $this->normalizeFloat($order['prix'] ?? 0);
        $order['prix_formatted'] = $this->formatCurrency($orderAmount);
        $order['commission_plateforme'] = round($orderAmount * 0.10, 2);
        $order['commission_vendeur'] = round($orderAmount * 0.90, 2);
        $order['commission_plateforme_formatted'] = $this->formatCurrency($order['commission_plateforme']);
        $order['commission_vendeur_formatted'] = $this->formatCurrency($order['commission_vendeur']);
        $order['date_formatted'] = $this->formatDate($order['date_commande']);
        $order['vendeur_full_name'] = trim(($order['vendeur_prenom'] ?? '') . ' ' . ($order['vendeur_nom'] ?? ''));
        $order['client_full_name'] = trim(($order['client_prenom'] ?? '') . ' ' . ($order['client_nom'] ?? ''));
        if (empty($order['client_full_name']) || $order['client_full_name'] === ' ') {
            $order['client_full_name'] = $order['email'] ?? 'N/A';
        }

        $this->jsonResponse(['success' => true, 'order' => $order]);
    }

    /**
     * CREATE - Créer une commande manuellement (admin)
     */
    public function createOrder(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        $idClient   = (int)($_POST['id_client'] ?? 0);
        $email      = trim($_POST['email'] ?? '');
        $idArticle  = (int)($_POST['id_article'] ?? 0);
        $prix       = $this->normalizeFloat($_POST['prix'] ?? 0);

        if (!$idArticle || $prix <= 0 || $email === '') {
            $this->jsonResponse(['success' => false, 'message' => 'Produit, email et prix requis'], 400);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse(['success' => false, 'message' => 'Email client invalide'], 400);
            return;
        }

        try {
            // Récupérer les infos du produit
            $stmtProd = $db->prepare("SELECT image, fichier FROM produits WHERE id = ?");
            $stmtProd->execute([$idArticle]);
            $prod = $stmtProd->fetch(\PDO::FETCH_ASSOC);

            if (!$prod) {
                $this->jsonResponse(['success' => false, 'message' => 'Produit introuvable'], 404);
                return;
            }

            $stmt = $db->prepare("
                INSERT INTO commande (commande_id, id_client, email, id_article, image, prix, fichier, date_commande)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $this->generateOrderCode($db),
                $idClient ?: '',
                $email,
                (string)$idArticle,
                $prod['image'] ?? '',
                (string)$prix,
                $prod['fichier'] ?? ''
            ]);

            $newId = (int)$db->lastInsertId();
            $this->logAction('create_order', $newId, "Commande créée manuellement");
            $this->jsonResponse(['success' => true, 'message' => 'Commande créée avec succès', 'id' => $newId]);
        } catch (\Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Impossible de créer la commande : ' . $e->getMessage()], 500);
        }
    }

    /**
     * UPDATE - Modifier une commande
     */
    public function updateOrder(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            $this->jsonResponse(['success' => false, 'message' => 'ID manquant'], 400);
            return;
        }

        $check = $db->prepare("SELECT * FROM commande WHERE a = ?");
        $check->execute([$id]);
        $existing = $check->fetch(\PDO::FETCH_ASSOC);
        if (!$existing) {
            $this->jsonResponse(['success' => false, 'message' => 'Commande introuvable'], 404);
            return;
        }

        $idClient  = (int)($_POST['id_client'] ?? 0);
        $email     = trim($_POST['email'] ?? '');
        $idArticle = (int)($_POST['id_article'] ?? 0);
        $prix      = $this->normalizeFloat($_POST['prix'] ?? 0);

        if (!$idArticle || $prix <= 0 || $email === '') {
            $this->jsonResponse(['success' => false, 'message' => 'Produit, email et prix requis'], 400);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse(['success' => false, 'message' => 'Email client invalide'], 400);
            return;
        }

        // Récupérer les infos du nouveau produit
        $stmtProd = $db->prepare("SELECT image, fichier FROM produits WHERE id = ?");
        $stmtProd->execute([$idArticle]);
        $prod = $stmtProd->fetch(\PDO::FETCH_ASSOC);

        if (!$prod) {
            $this->jsonResponse(['success' => false, 'message' => 'Produit introuvable'], 404);
            return;
        }

        $stmt = $db->prepare("
            UPDATE commande 
            SET id_client = ?, email = ?, id_article = ?, image = ?, prix = ?, fichier = ?
            WHERE a = ?
        ");
        $stmt->execute([
            $idClient ?: '',
            $email,
            (string)$idArticle,
            $prod['image'] ?? '',
            (string)$prix,
            $prod['fichier'] ?? '',
            $id
        ]);

        $this->logAction('update_order', $id, "Commande modifiée");
        $this->jsonResponse(['success' => true, 'message' => 'Commande mise à jour']);
    }

    /**
     * DELETE - Supprimer une commande
     */
    public function deleteOrder(int $id = 0): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $id = $id ?: (int)($_POST['id'] ?? 0);
        if (!$id) {
            $this->jsonResponse(['success' => false, 'message' => 'ID manquant'], 400);
            return;
        }

        $stmt = $db->prepare("SELECT a FROM commande WHERE a = ?");
        $stmt->execute([$id]);
        if (!$stmt->fetchColumn()) {
            $this->jsonResponse(['success' => false, 'message' => 'Commande introuvable'], 404);
            return;
        }

        $db->prepare("DELETE FROM commande WHERE a = ?")->execute([$id]);
        $this->logAction('delete_order', $id, "Commande supprimée");
        $this->jsonResponse(['success' => true, 'message' => 'Commande supprimée']);
    }

    /**
     * BULK DELETE - Suppression groupée
     */
    public function bulkDeleteOrders(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $ids = $_POST['ids'] ?? [];
        if (empty($ids) || !is_array($ids)) {
            $this->jsonResponse(['success' => false, 'message' => 'Aucune commande sélectionnée'], 400);
            return;
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $intIds = array_map('intval', $ids);

        $db->prepare("DELETE FROM commande WHERE a IN ($placeholders)")->execute($intIds);
        $this->logAction('bulk_delete_orders', 0, count($intIds) . " commandes supprimées");
        $this->jsonResponse([
            'success' => true,
            'message' => count($intIds) . ' commande(s) supprimée(s)'
        ]);
    }

    /**
     * READ - Récupérer les produits pour le select du formulaire
     */
    public function getProductsList(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        
        $stmt = $db->query("
            SELECT id, nom_article, prix, statut
            FROM produits 
            ORDER BY nom_article ASC
            LIMIT 500
        ");
        $products = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $this->jsonResponse(['success' => true, 'products' => $products]);
    }

    /**
     * READ - Récupérer les clients pour le select du formulaire
     */
    public function getClientsList(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        
        $stmt = $db->query("
            SELECT id_uti, nom, prenom, email 
            FROM utilisateur 
            ORDER BY nom ASC
            LIMIT 100
        ");
        $clients = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $this->jsonResponse(['success' => true, 'clients' => $clients]);
    }
}
