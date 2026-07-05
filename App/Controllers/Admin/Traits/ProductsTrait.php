<?php
namespace App\Controllers\Admin\Traits;

trait ProductsTrait
{
    public function products()
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        $where = [];
        $params = [];

        if (!empty($_GET['statut']) && $_GET['statut'] !== '') {
            $where[] = "p.statut = :statut";
            $params[':statut'] = $_GET['statut'];
        }
        if (!empty($_GET['categorie']) && $_GET['categorie'] !== '') {
            $where[] = "p.categorie_id = :categorie";
            $params[':categorie'] = $_GET['categorie'];
        }
        if (!empty($_GET['search'])) {
            $where[] = "(p.nom_article LIKE :search OR p.description LIKE :search)";
            $params[':search'] = '%' . $_GET['search'] . '%';
        }

        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        // === TRI DYNAMIQUE ===
        $allowedSort = [
            'nom_article' => 'p.nom_article',
            'prix'        => 'p.prix',
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
        $totalProducts = count($products);
        $currentPage = 'gestion-produits';
        require_once __DIR__ . '/../../../Views/admin/gestion-produits.php';
    }

    public function approveProduct(int $id): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $db->prepare("UPDATE produits SET statut = 'approuve' WHERE id = ?")->execute([$id]);
        $this->logAction('approve_product', $id, "Produit approuvé");
        $this->jsonResponse(['success' => true, 'message' => 'Produit approuvé']);
    }

    public function rejectProduct(int $id): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $commentaire = $_POST['commentaire'] ?? '';
        $db->prepare("UPDATE produits SET statut = 'refuse', commentaire = ? WHERE id = ?")
            ->execute([$commentaire, $id]);
        $this->logAction('reject_product', $id, "Produit refusé: $commentaire");
        $this->jsonResponse(['success' => true, 'message' => 'Produit refusé']);
    }

    public function deleteProduct(int $id): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $db->prepare("DELETE FROM produits WHERE id = ?")->execute([$id]);
        $this->logAction('delete_product', $id, "Produit supprimé");
        $this->jsonResponse(['success' => true, 'message' => 'Produit supprimé']);
    }

    // === NOUVEAU : Charger les détails d'un produit en AJAX ===
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