<?php

require_once __DIR__ . '/../../Models/CategorieModel.php';

class CategorieController {
    private $model;
    
    public function __construct($pdo) {
        $this->model = new CategorieModel($pdo);
    }
    
    /**
     * Afficher la page de gestion des catégories
     */
    public function index() {
    $categories = $this->model->getAllCategories();
    
    // Statistiques
    $stats = [
        'total' => $this->model->countCategories(),
        'actives' => $this->model->countActiveCategories(),
        'inactives' => $this->model->countInactiveCategories(),
        'produits' => $this->model->countAllProducts()
    ];

    // Pour chaque catégorie, compter les produits associés
    foreach ($categories as &$category) {
        $category['nb_produits'] = $this->model->countProductsByCategory($category['id']);
    }

    // Récupérer les listes d'icônes et couleurs
    $icons = $this->model->getAvailableIcons();
    $colors = $this->model->getAvailableColors();

    // Initialiser categoryDetail à null (pas de données par défaut)
    $categoryDetail = null;

    $this->render('admin/categorie', [
        'categories' => $categories,
        'stats' => $stats,
        'icons' => $icons,
        'colors' => $colors,
        'categoryDetail' => $categoryDetail  // 👈 AJOUTER CETTE LIGNE
    ]);
}
        private function render($view, $data = [])
        {
            extract($data);
            require __DIR__ . '/../../Views/admin/categorie.php';
        }   
    /**
     * Ajouter une catégorie (AJAX ou POST)
     */
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }
        
        $nom_categorie = trim($_POST['nom_categorie'] ?? '');
        if (empty($nom_categorie)) {
            $this->jsonResponse(['error' => 'Le nom de la catégorie est requis'], 400);
            return;
        }
        
        // Générer le slug
        $slug = trim($_POST['slug'] ?? '');
        if (empty($slug)) {
            $slug = $this->model->generateUniqueSlug($nom_categorie);
        } else {
            // Vérifier si le slug existe déjà
            if ($this->model->slugExists($slug)) {
                $this->jsonResponse(['error' => 'Ce slug existe déjà'], 400);
                return;
            }
        }
        
        // Traitement de l'image
        $imageName = null;
        if (!empty($_FILES['image']['name'])) {
            $imageName = $this->uploadImage($_FILES['image']);
            if (!$imageName) {
                $this->jsonResponse(['error' => 'Erreur lors du téléchargement de l\'image'], 400);
                return;
            }
        }
        
        $data = [
            'nom_categorie' => $nom_categorie,
            'slug' => $slug,
            'description' => $_POST['description'] ?? null,
            'icone' => $_POST['icone'] ?? 'fa-solid fa-globe',
            'couleur' => $_POST['couleur'] ?? 'blue',
            'statut' => $_POST['statut'] ?? 'active',
            'ordre_affichage' => (int)($this->model->countCategories() + 1),
            'image_cat' => $imageName
        ];
        
        if ($this->model->addCategory($data)) {
            $this->jsonResponse(['success' => true, 'message' => 'Catégorie ajoutée avec succès']);
        } else {
            $this->jsonResponse(['error' => 'Erreur lors de l\'ajout de la catégorie'], 500);
        }
    }
    
    /**
     * Récupérer une catégorie (AJAX)
     */
    public function getCategory() {
    // Vérifier si l'ID est présent
    if (!isset($_GET['id'])) {
        $this->jsonResponse(['error' => 'ID manquant'], 400);
        return;
    }
    
    $id = (int)$_GET['id'];
    
    // Récupérer la catégorie
    $category = $this->model->getCategoryById($id);
    
    if (!$category) {
        $this->jsonResponse(['error' => 'Catégorie non trouvée'], 404);
        return;
    }
    
    // Compter les produits
    $category['nb_produits'] = $this->model->countProductsByCategory($id);
    
    // Récupérer les produits de la catégorie
    $category['produits'] = $this->model->getProductsByCategory($id);
    
    // Statistiques supplémentaires
    $category['produits_actifs'] = $this->model->countActiveProductsByCategory($id);
    $category['produits_inactifs'] = $this->model->countInactiveProductsByCategory($id);
    $category['vendeurs'] = $this->model->countVendeursByCategory($id);
    $category['sous_categories'] = 0; // à adapter selon ta BD
    
    $this->jsonResponse([
        'success' => true,
        'data' => $category  // 👈 ATTENTION: utilise 'data' pas 'category'
    ]);
}
    
    /**
     * Mettre à jour une catégorie
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }
        
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            $this->jsonResponse(['error' => 'ID manquant'], 400);
            return;
        }
        
        $category = $this->model->getCategoryById($id);
        if (!$category) {
            $this->jsonResponse(['error' => 'Catégorie non trouvée'], 404);
            return;
        }
        
        $nom_categorie = trim($_POST['nom_categorie'] ?? '');
        if (empty($nom_categorie)) {
            $this->jsonResponse(['error' => 'Le nom de la catégorie est requis'], 400);
            return;
        }
        
        // Vérifier le slug
        $slug = trim($_POST['slug'] ?? '');
        if (empty($slug)) {
            $slug = $this->model->slugify($nom_categorie);
        }
        
        if ($this->model->slugExists($slug, $id)) {
            $this->jsonResponse(['error' => 'Ce slug existe déjà'], 400);
            return;
        }
        
        $data = [
            'nom_categorie' => $nom_categorie,
            'slug' => $slug,
            'description' => $_POST['description'] ?? null,
            'icone' => $_POST['icone'] ?? 'fa-solid fa-globe',
            'couleur' => $_POST['couleur'] ?? 'blue',
            'statut' => $_POST['statut'] ?? 'active'
        ];
        
        // Traitement de l'image si présente
        if (!empty($_FILES['image']['name'])) {
            $imageName = $this->uploadImage($_FILES['image']);
            if ($imageName) {
                $this->model->updateCategoryImage($id, $imageName);
            }
        }
        
        if ($this->model->updateCategory($id, $data)) {
            $this->jsonResponse(['success' => true, 'message' => 'Catégorie mise à jour avec succès']);
        } else {
            $this->jsonResponse(['error' => 'Erreur lors de la mise à jour'], 500);
        }
    }
    
    /**
     * Mettre à jour l'ordre d'affichage
     */
    public function updateOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }
        
        $order = json_decode($_POST['order'] ?? '[]', true);
        if (empty($order)) {
            $this->jsonResponse(['error' => 'Ordre invalide'], 400);
            return;
        }
        
        $success = true;
        foreach ($order as $index => $id) {
            if (!$this->model->updateCategoryOrder((int)$id, $index + 1)) {
                $success = false;
            }
        }
        
        if ($success) {
            $this->jsonResponse(['success' => true, 'message' => 'Ordre mis à jour avec succès']);
        } else {
            $this->jsonResponse(['error' => 'Erreur lors de la mise à jour de l\'ordre'], 500);
        }
    }
    
    /**
     * Changer le statut d'une catégorie
     */
    public function toggleStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }
        
        $id = (int)($_POST['id'] ?? 0);
        $statut = $_POST['statut'] ?? 'active';
        
        if (!$id || !in_array($statut, ['active', 'inactive'])) {
            $this->jsonResponse(['error' => 'Données invalides'], 400);
            return;
        }
        
        if ($this->model->toggleCategoryStatus($id, $statut)) {
            $this->jsonResponse(['success' => true, 'message' => 'Statut mis à jour avec succès']);
        } else {
            $this->jsonResponse(['error' => 'Erreur lors de la mise à jour du statut'], 500);
        }
    }
    
    /**
     * Supprimer une catégorie
     */
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }
        
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            $this->jsonResponse(['error' => 'ID manquant'], 400);
            return;
        }
        
        // Vérifier si la catégorie a des produits
        $nbProduits = $this->model->countProductsByCategory($id);
        if ($nbProduits > 0) {
            $this->jsonResponse(['error' => 'Cette catégorie contient des produits. Veuillez d\'abord les déplacer ou les supprimer.'], 400);
            return;
        }
        
        if ($this->model->deleteCategory($id)) {
            $this->jsonResponse(['success' => true, 'message' => 'Catégorie supprimée avec succès']);
        } else {
            $this->jsonResponse(['error' => 'Erreur lors de la suppression'], 500);
        }
    }
    
    /**
     * Fusionner deux catégories
     */
    public function merge() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }
        
        $sourceId = (int)($_POST['source_id'] ?? 0);
        $targetId = (int)($_POST['target_id'] ?? 0);
        
        if (!$sourceId || !$targetId || $sourceId === $targetId) {
            $this->jsonResponse(['error' => 'Données invalides'], 400);
            return;
        }
        
        if ($this->model->mergeCategories($sourceId, $targetId)) {
            $this->jsonResponse(['success' => true, 'message' => 'Catégories fusionnées avec succès']);
        } else {
            $this->jsonResponse(['error' => 'Erreur lors de la fusion'], 500);
        }
    }

        /**
         * Filtrer les catégories (AJAX)
         * Retourne les données en JSON
         */
        public function filter() {
            // Récupérer les paramètres
            $search = $_GET['search'] ?? null;
            $statut = $_GET['statut'] ?? null;
            $tri = $_GET['tri'] ?? null;
            
            // Filtrer les catégories
            $categories = $this->model->filterCategories($search, $statut, $tri);
            
            // Compter les produits pour chaque catégorie
            foreach ($categories as &$category) {
                $category['nb_produits'] = $this->model->countProductsByCategory($category['id']);
            }
            
            $this->jsonResponse([
                'success' => true,
                'categories' => $categories,
                'count' => count($categories)
            ]);
        }
            
    /**
     * Upload d'image
     */
    private function uploadImage($file) {
        $imageName = time() . '_' . basename($file['name']);
        $imagePath = __DIR__ . '/../../public/uploads/' . $imageName;
        $imageSize = $file['size'];
        $imageTmp = $file['tmp_name'];
        $imageType = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        
        $formats_autorises = ["jpg", "jpeg", "png", "gif", "webp", "svg"];
        if (!in_array($imageType, $formats_autorises)) {
            return false;
        }
        
        if ($imageSize > 2 * 1024 * 1024) { // 2MB
            return false;
        }
        
        if (!is_dir(__DIR__ . '/../../public/uploads')) {
            mkdir(__DIR__ . '/../../public/uploads', 0777, true);
        }
        
        if (move_uploaded_file($imageTmp, $imagePath)) {
            return $imageName;
        }
        
        return false;
    }
    
    /**
     * Réponse JSON
     */
    private function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}