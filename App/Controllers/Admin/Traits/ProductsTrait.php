<?php

/**
 * NDIGITMARKET - Admin trait: Products
 */

namespace App\Controllers\Admin\Traits;

trait ProductsTrait
{
    private function productFilters(): array
    {
        $where = [];
        $params = [];

        if (isset($_GET['statut']) && $_GET['statut'] !== '') {
            $where[] = "p.statut = :statut";
            $params[':statut'] = (string) $_GET['statut'];
        }
        if (isset($_GET['categorie']) && $_GET['categorie'] !== '') {
            $where[] = "p.categorie_id = :categorie";
            $params[':categorie'] = (string) $_GET['categorie'];
        }
        if (isset($_GET['search']) && trim((string) $_GET['search']) !== '') {
            $where[] = "(p.nom_article LIKE :search1 OR p.description LIKE :search2)";
            $searchTerm = '%' . trim((string) $_GET['search']) . '%';
            $params[':search1'] = $searchTerm;
            $params[':search2'] = $searchTerm;
        }

        return [$where ? 'WHERE ' . implode(' AND ', $where) : '', $params];
    }

    public function products()
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        [$whereClause, $params] = $this->productFilters();

        // === TRI DYNAMIQUE ===
        $allowedSort = [
            'nom_article' => 'p.nom_article',
            'prix'        => "CAST(REPLACE(p.prix, ' ', '') AS DECIMAL(14,2))",
            'nb_ventes'   => 'nb_ventes',
            'date_ajout'  => 'p.date_ajout',
        ];
        $sortCol = $_GET['sort'] ?? 'date_ajout';
        $sortDir = strtoupper($_GET['order'] ?? 'DESC');
        if (!in_array($sortDir, ['ASC', 'DESC'])) $sortDir = 'DESC';
        $orderByCol = $allowedSort[$sortCol] ?? 'p.date_ajout';
        $orderBy = "$orderByCol $sortDir";

        $stmt = $db->prepare("
    SELECT
        p.*,
        c.nom_categorie,
        u.nom AS vendeur_nom,
        u.prenom AS vendeur_prenom,
        u.email AS vendeur_email,
        COUNT(DISTINCT co.a) AS nb_ventes
    FROM produits p
    LEFT JOIN categories c ON c.id = p.categorie_id
    LEFT JOIN utilisateur u ON u.id_uti = p.id_vendeur
    LEFT JOIN commande co ON co.id_article = p.id
    $whereClause
    GROUP BY p.id
    ORDER BY $orderBy
");
        $stmt->execute($params);
        $products = $stmt->fetchAll();

        foreach ($products as &$product) {
            $product['prix_formatted'] = $this->formatCurrency((float) $product['prix']);
            $product['prix_reduction_formatted'] = $this->formatCurrency((float) ($product['prix_reduction'] ?? 0));
            $product['vendeur_full_name'] = trim(($product['vendeur_prenom'] ?? '') . ' ' . ($product['vendeur_nom'] ?? ''));
            $product['vendeur_email'] = $product['vendeur_email'] ?? '';
            $product['date_formatted'] = $this->formatDate($product['date_ajout']);
            $product['statut_label'] = match ($product['statut'] ?? '') {
                'en_attente' => 'En attente',
                'approuve'   => 'Approuvé',
                'refuse'     => 'Refusé',
                default      => 'Inconnu',
            };
            $product['statut_class'] = match ($product['statut'] ?? '') {
                'en_attente' => 'warning',
                'approuve'   => 'success',
                'refuse'     => 'danger',
                default      => 'secondary',
            };
            $product['reduction_percent'] = ($product['prix'] > 0 && !empty($product['prix_reduction']))
                ? round((($product['prix'] - $product['prix_reduction']) / $product['prix']) * 100)
                : 0;
        }
        unset($product);

        $categories = $db->query("SELECT id, nom_categorie FROM categories ORDER BY nom_categorie ASC")->fetchAll();
        $vendeurs = $db->query("
            SELECT id_uti, nom, prenom
            FROM utilisateur
            WHERE type = 'pro' OR type = 'admin'
            ORDER BY nom ASC, prenom ASC
        ")->fetchAll();
        $totalProducts = count($products);
        $currentPage = 'gestion-produits';
        require_once __DIR__ . '/../../../Views/admin/gestion-produits.php';
    }

    public function exportProducts(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        [$whereClause, $params] = $this->productFilters();

        $stmt = $db->prepare("
            SELECT
                p.id,
                p.nom_article,
                c.nom_categorie,
                CONCAT(COALESCE(u.prenom, ''), ' ', COALESCE(u.nom, '')) AS vendeur,
                u.email AS vendeur_email,
                p.prix,
                p.prix_reduction,
                p.statut,
                COUNT(DISTINCT co.a) AS nb_ventes,
                p.date_ajout
            FROM produits p
            LEFT JOIN categories c ON c.id = p.categorie_id
            LEFT JOIN utilisateur u ON u.id_uti = p.id_vendeur
            LEFT JOIN commande co ON co.id_article = p.id
            $whereClause
            GROUP BY p.id
            ORDER BY p.date_ajout DESC
        ");
        $stmt->execute($params);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="produits_templates_' . date('Ymd_His') . '.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['ID', 'Produit', 'Catégorie', 'Vendeur', 'Email vendeur', 'Prix', 'Prix réduit', 'Statut', 'Ventes', 'Date ajout']);
        foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $row['vendeur'] = trim((string) $row['vendeur']);
            fputcsv($out, $row);
        }
        fclose($out);
        exit;
    }

    public function productHistory(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        try {
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

            $stmt = $db->prepare("
                SELECT id, action, target_user_id, details, status, created_at
                FROM admin_logs
                WHERE action IN ('create_product', 'update_product', 'approve_product', 'reject_product', 'delete_product', 'bulk_delete_products')
                ORDER BY created_at DESC
                LIMIT 50
            ");
            $stmt->execute();
            $history = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($history as &$item) {
                $item['date_formatted'] = $this->formatDate($item['created_at'] ?? null, 'd/m/Y H:i');
            }
            unset($item);

            $this->jsonResponse(['success' => true, 'history' => $history]);
        } catch (\Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Historique indisponible'], 500);
        }
    }

    private function storeProductUpload(string $field, array $allowedExtensions, int $maxBytes): ?string
    {
        if (empty($_FILES[$field]) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK || $_FILES[$field]['size'] > $maxBytes) {
            throw new \RuntimeException("Fichier invalide pour $field");
        }

        $extension = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedExtensions, true)) {
            throw new \RuntimeException("Extension non autorisée pour $field");
        }

        $uploadDir = dirname(__DIR__, 4) . '/uploads';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $safeName = bin2hex(random_bytes(8)) . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES[$field]['name']));
        $target = $uploadDir . '/' . $safeName;
        if (!move_uploaded_file($_FILES[$field]['tmp_name'], $target)) {
            throw new \RuntimeException("Impossible d'enregistrer $field");
        }

        return 'uploads/' . $safeName;
    }

    public function createProduct(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        $nom = trim((string)($_POST['nom_article'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));
        $categorieId = trim((string)($_POST['categorie_id'] ?? ''));
        $idVendeur = (int)($_POST['id_vendeur'] ?? 0);
        $prix = (int)($_POST['prix'] ?? 0);
        $prixReduction = (int)($_POST['prix_reduction'] ?? 0);
        $statut = (string)($_POST['statut'] ?? 'en_attente');
        $apercue = trim((string)($_POST['demo_url'] ?? ''));
        $tags = trim((string)($_POST['tags'] ?? ''));

        if ($nom === '' || $categorieId === '' || !$idVendeur || $prix <= 0) {
            $this->jsonResponse(['success' => false, 'message' => 'Nom, catégorie, vendeur et prix requis'], 400);
            return;
        }
        if (!in_array($statut, ['en_attente', 'approuve', 'refuse'], true)) {
            $statut = 'en_attente';
        }

        try {
            $image = $this->storeProductUpload('image', ['jpg', 'jpeg', 'png', 'webp'], 2 * 1024 * 1024) ?? '';
            $fichier = $this->storeProductUpload('fichier_template', ['zip', 'rar'], 100 * 1024 * 1024) ?? '';

            $stmt = $db->prepare("
                INSERT INTO produits
                (nom_article, prix, prix_reduction, image, fichier, categorie_id, sous_categorie, auteur, description, apercue, id_vendeur, statut, commentaire)
                VALUES (?, ?, ?, ?, ?, ?, ?, '', ?, ?, ?, ?, NULL)
            ");
            $stmt->execute([$nom, $prix, $prixReduction, $image, $fichier, $categorieId, $tags, $description, $apercue, $idVendeur, $statut]);
            $newId = (int)$db->lastInsertId();

            $this->logAction('create_product', $newId, "Produit créé: $nom");
            $this->jsonResponse(['success' => true, 'message' => 'Produit créé', 'id' => $newId]);
        } catch (\Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Erreur création produit : ' . $e->getMessage()], 500);
        }
    }

    public function updateProduct(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $id = (int)($_POST['id'] ?? 0);

        if (!$id) {
            $this->jsonResponse(['success' => false, 'message' => 'ID manquant'], 400);
            return;
        }

        $stmt = $db->prepare("SELECT * FROM produits WHERE id = ?");
        $stmt->execute([$id]);
        $existing = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$existing) {
            $this->jsonResponse(['success' => false, 'message' => 'Produit introuvable'], 404);
            return;
        }

        $statut = (string)($_POST['statut'] ?? $existing['statut']);
        if (!in_array($statut, ['en_attente', 'approuve', 'refuse'], true)) {
            $statut = $existing['statut'];
        }

        try {
            $image = $this->storeProductUpload('image', ['jpg', 'jpeg', 'png', 'webp'], 2 * 1024 * 1024) ?? $existing['image'];
            $fichier = $this->storeProductUpload('fichier_template', ['zip', 'rar'], 100 * 1024 * 1024) ?? $existing['fichier'];

            $stmt = $db->prepare("
                UPDATE produits
                SET nom_article = ?, description = ?, categorie_id = ?, id_vendeur = ?, prix = ?,
                    prix_reduction = ?, apercue = ?, sous_categorie = ?, image = ?, fichier = ?, statut = ?
                WHERE id = ?
            ");
            $stmt->execute([
                trim((string)($_POST['nom_article'] ?? $existing['nom_article'])),
                trim((string)($_POST['description'] ?? $existing['description'])),
                trim((string)($_POST['categorie_id'] ?? $existing['categorie_id'])),
                (int)($_POST['id_vendeur'] ?? $existing['id_vendeur']),
                (int)($_POST['prix'] ?? $existing['prix']),
                (int)($_POST['prix_reduction'] ?? $existing['prix_reduction']),
                trim((string)($_POST['demo_url'] ?? $existing['apercue'])),
                trim((string)($_POST['tags'] ?? $existing['sous_categorie'])),
                $image,
                $fichier,
                $statut,
                $id,
            ]);

            $this->logAction('update_product', $id, "Produit modifié");
            $this->jsonResponse(['success' => true, 'message' => 'Produit mis à jour']);
        } catch (\Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Erreur modification produit : ' . $e->getMessage()], 500);
        }
    }

    public function approveProduct(int $id = 0): void
    {
        $this->checkAuth();
        $id = $id ?: (int)($_POST['id'] ?? 0);
        if (!$id) {
            $this->jsonResponse(['success' => false, 'message' => 'ID manquant'], 400);
            return;
        }
        $db = \Database::getConnection();
        $db->prepare("UPDATE produits SET statut = 'approuve' WHERE id = ?")->execute([$id]);
        $this->logAction('approve_product', $id, "Produit approuvé");
        $this->jsonResponse(['success' => true, 'message' => 'Produit approuvé']);
    }

    public function rejectProduct(int $id = 0): void
    {
        $this->checkAuth();
        $id = $id ?: (int)($_POST['id'] ?? 0);
        if (!$id) {
            $this->jsonResponse(['success' => false, 'message' => 'ID manquant'], 400);
            return;
        }
        $db = \Database::getConnection();
        $commentaire = $_POST['commentaire'] ?? '';
        $db->prepare("UPDATE produits SET statut = 'refuse', commentaire = ? WHERE id = ?")
            ->execute([$commentaire, $id]);
        $this->logAction('reject_product', $id, "Produit refusé: $commentaire");
        $this->jsonResponse(['success' => true, 'message' => 'Produit refusé']);
    }

    public function deleteProduct(int $id = 0): void
    {
        $this->checkAuth();
        $id = $id ?: (int)($_POST['id'] ?? 0);
        if (!$id) {
            $this->jsonResponse(['success' => false, 'message' => 'ID manquant'], 400);
            return;
        }
        $db = \Database::getConnection();
        $db->prepare("DELETE FROM produits WHERE id = ?")->execute([$id]);
        $this->logAction('delete_product', $id, "Produit supprimé");
        $this->jsonResponse(['success' => true, 'message' => 'Produit supprimé']);
    }

    public function bulkDeleteProducts(): void
    {
        $this->checkAuth();
        $ids = $_POST['ids'] ?? [];
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        $ids = array_values(array_unique(array_filter(array_map('intval', $ids))));
        if (!$ids) {
            $this->jsonResponse(['success' => false, 'message' => 'Aucun produit sélectionné'], 400);
            return;
        }

        $db = \Database::getConnection();
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $db->prepare("DELETE FROM produits WHERE id IN ($placeholders)")->execute($ids);
        $this->logAction('bulk_delete_products', 0, count($ids) . " produits supprimés");
        $this->jsonResponse(['success' => true, 'message' => count($ids) . ' produit(s) supprimé(s)']);
    }

    // === NOUVEAU : Charger les détails d'un produit en AJAX ===
    /**
     * AJAX - Recherche de vendeurs pour l'autocomplétion.
     */
    public function searchVendors(): void
    {
        $this->checkAuth();

        $q = trim((string) ($_GET['q'] ?? ''));
        if (strlen($q) < 2) {
            $this->jsonResponse(['success' => true, 'vendors' => []]);
            return;
        }

        $db = \Database::getConnection();
        $searchTerm = '%' . $q . '%';
        $stmt = $db->prepare("
            SELECT id_uti AS id, nom, prenom, email,
                   CONCAT(COALESCE(prenom, ''), ' ', COALESCE(nom, '')) AS full_name
            FROM utilisateur
            WHERE (type = 'pro' OR type = 'admin')
              AND (nom LIKE ? OR prenom LIKE ? OR CONCAT(COALESCE(prenom, ''), ' ', COALESCE(nom, '')) LIKE ?)
            LIMIT 15
        ");
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        $vendors = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->jsonResponse(['success' => true, 'vendors' => $vendors]);
    }

    public function getProductDetails(): void
    {
        $this->checkAuth();
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) {
            $this->jsonResponse(['success' => false, 'message' => 'ID manquant'], 400);
            return;
        }

        $db = \Database::getConnection();

        // Produit
        $stmt = $db->prepare("
            SELECT p.*, c.nom_categorie,
                   u.nom AS vendeur_nom, u.prenom AS vendeur_prenom, u.email AS vendeur_email
            FROM produits p
            LEFT JOIN categories c ON c.id = p.categorie_id
            LEFT JOIN utilisateur u ON u.id_uti = p.id_vendeur
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        $product = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$product) {
            $this->jsonResponse(['success' => false, 'message' => 'Produit introuvable'], 404);
            return;
        }

        $product['vendeur_full_name'] = trim(($product['vendeur_prenom'] ?? '') . ' ' . ($product['vendeur_nom'] ?? ''));
        $product['prix_formatted'] = $this->formatCurrency((float) $product['prix']);
        $product['date_formatted'] = $this->formatDate($product['date_ajout']);

        // Avis sur ce produit
        $stmtAvis = $db->prepare("
            SELECT a.note, a.commentaire, a.date_avis, u.nom, u.prenom
            FROM avis a
            LEFT JOIN utilisateur u ON u.id_uti = a.id_utilisateur
            WHERE a.id_produit = ?
            ORDER BY a.date_avis DESC
            LIMIT 10
        ");
        $stmtAvis->execute([$id]);
        $avis = $stmtAvis->fetchAll(\PDO::FETCH_ASSOC);

        $this->jsonResponse([
            'success' => true,
            'product' => $product,
            'avis'    => $avis
        ]);
    }
}
