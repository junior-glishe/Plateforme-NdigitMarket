<?php


namespace App\Controllers\Admin\Traits;

trait CategoriesTrait
{
    public function categories()
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        $search = trim((string) ($_GET['search'] ?? ''));
        $where  = '';
        $params = [];
        if ($search !== '') {
            $where = 'WHERE c.nom_categorie LIKE :s OR c.sous_categories LIKE :s';
            $params[':s'] = '%' . $search . '%';
        }

        $stmt = $db->prepare("
            SELECT c.*, COUNT(p.id) AS nb_produits
            FROM categories c
            LEFT JOIN produits p ON p.categorie_id = c.id
            $where
            GROUP BY c.id
            ORDER BY c.nom_categorie ASC
        ");
        $stmt->execute($params);
        $categories = $stmt->fetchAll();

        $totalCategories = count($categories);
        $currentPage     = 'categorie';
        require_once __DIR__ . '/../../../Views/admin/categorie.php';
    }

    public function getCategory(int $id): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        $cat = $stmt->fetch();
        if (!$cat) $this->jsonResponse(['success' => false, 'message' => 'Introuvable'], 404);
        $this->jsonResponse(['success' => true, 'category' => $cat]);
    }

    public function addCategory(): void
    {
        $this->checkAuth();
        $db  = \Database::getConnection();
        $nom = trim((string) ($_POST['nom_categorie'] ?? ''));
        $sc  = trim((string) ($_POST['sous_categories'] ?? ''));

        if ($nom === '') {
            $this->jsonResponse(['success' => false, 'message' => 'Le nom est obligatoire'], 400);
        }

        // Upload image (facultatif)
        $imageName = '';
        if (!empty($_FILES['image_cat']['name'])) {
            $imageName = $this->handleUpload($_FILES['image_cat'], 'uploads/categories/');
        }

        // BUG FIX : la version précédente utilisait la colonne `image`
        // alors que la BDD a `image_cat`.
        $db->prepare("INSERT INTO categories (nom_categorie, sous_categories, image_cat) VALUES (?, ?, ?)")
            ->execute([$nom, $sc, $imageName]);

        $this->logAction('add_category', (int)$db->lastInsertId(), "Catégorie ajoutée : $nom");
        $this->jsonResponse(['success' => true, 'message' => 'Catégorie ajoutée']);
    }

    public function updateCategory(int $id): void
    {
        $this->checkAuth();
        $db  = \Database::getConnection();

        $stmt = $db->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        $existing = $stmt->fetch();
        if (!$existing) $this->jsonResponse(['success' => false, 'message' => 'Introuvable'], 404);

        $nom = trim((string) ($_POST['nom_categorie'] ?? $existing['nom_categorie']));
        $sc  = trim((string) ($_POST['sous_categories'] ?? $existing['sous_categories']));
        $img = $existing['image_cat'];
        if (!empty($_FILES['image_cat']['name'])) {
            $img = $this->handleUpload($_FILES['image_cat'], 'uploads/categories/');
        }

        $db->prepare("UPDATE categories SET nom_categorie = ?, sous_categories = ?, image_cat = ? WHERE id = ?")
            ->execute([$nom, $sc, $img, $id]);

        $this->logAction('update_category', $id, "Catégorie mise à jour : $nom");
        $this->jsonResponse(['success' => true, 'message' => 'Catégorie mise à jour']);
    }

    public function deleteCategory(int $id): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        // Refuse la suppression si des produits y sont rattachés (CDC 4.8)
        $check = $db->prepare("SELECT COUNT(*) FROM produits WHERE categorie_id = ?");
        $check->execute([$id]);
        if ((int) $check->fetchColumn() > 0) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Impossible : des produits sont rattachés à cette catégorie.'
            ], 400);
        }

        $db->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
        $this->logAction('delete_category', $id, "Catégorie supprimée");
        $this->jsonResponse(['success' => true, 'message' => 'Catégorie supprimée']);
    }


    private function handleUpload(array $file, string $subdir): string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) return '';
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'svg', 'gif'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed, true)) return '';

        $baseDir = dirname(__DIR__, 4) . '/' . $subdir;
        if (!is_dir($baseDir)) @mkdir($baseDir, 0775, true);

        $name = time() . '_' . preg_replace('/[^a-z0-9\.\-_]/i', '_', $file['name']);
        $target = $baseDir . $name;
        return move_uploaded_file($file['tmp_name'], $target)
            ? $subdir . $name
            : '';
    }
}
