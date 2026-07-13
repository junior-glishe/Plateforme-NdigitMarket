<?php
require_once __DIR__ . '/../../Models/ContenuModel.php';
require_once __DIR__ . '/../../Models/CategorieModel.php';

class ContenuController {
    private $model;
    private $pdo;  // 🔥 AJOUTE CETTE PROPRIÉTÉ
    
    public function __construct($pdo) {
        $this->pdo = $pdo;  // 🔥 STOCKE LE PDO
        $this->model = new ContenuModel($pdo);
    }

    // ============================================
    // PAGE PRINCIPALE
    // ============================================

    public function index() {
        // 🔥 Récupérer les catégories - Utilise $this->pdo
        $categorieModel = new CategorieModel($this->pdo);
        $categories = $categorieModel->getAllCategories();
        
        // Bannières
        $bannieres = $this->model->getAllBannieres();
        
        // Ajouter les couleurs aux bannières
        $colors = [
            ['#6366f1', '#8b5cf6'],
            ['#f59e0b', '#ef4444'],
            ['#10b981', '#06b6d4'],
            ['#8b5cf6', '#ec4899'],
            ['#f472b6', '#fb923c'],
            ['#14b8a6', '#3b82f6'],
            ['#a855f7', '#d946ef'],
            ['#f97316', '#ef4444'],
            ['#3b82f6', '#8b5cf6'],
            ['#ec4899', '#f59e0b']
        ];
        
        foreach ($bannieres as &$banniere) {
            $color = $colors[array_rand($colors)];
            $banniere['couleur_1'] = $color[0];
            $banniere['couleur_2'] = $color[1];
        }
        
        // Codes promo
        $codesPromo = $this->model->getAllCodesPromo();
        
        // Statistiques bannières
        $statsBannieres = [
            'total' => $this->model->countBannieres(),
            'actives' => $this->model->countActiveBannieres(),
            'inactives' => $this->model->countInactiveBannieres()
        ];
        $statsVues = $this->model->getBanniereStatsTotal();
        $statsBannieres['vues'] = $statsVues['total_vues'] ?? 0;
        $statsBannieres['clics'] = $statsVues['total_clics'] ?? 0;
        
        // Statistiques codes promo
        $statsPromo = [
            'total' => $this->model->countCodesPromo(),
            'actifs' => $this->model->countActiveCodesPromo(),
            'inactifs' => $this->model->countInactiveCodesPromo(),
            'utilisations' => $this->model->countTotalUtilisations(),
            'remises' => $this->model->getTotalRemises()
        ];
        
        // Passer TOUTES les données à la vue
        $this->render('admin/contenus', [
            'bannieres' => $bannieres,
            'codesPromo' => $codesPromo,
            'statsBannieres' => $statsBannieres,
            'statsPromo' => $statsPromo,
            'categories' => $categories
        ]);
    }


    // ============================================
    // BANNIÈRES - CRUD
    // ============================================

    public function getBanniere() {
        if (!isset($_GET['id'])) {
            $this->jsonResponse(['error' => 'ID manquant'], 400);
            return;
        }
        
        $banniere = $this->model->getBanniereById((int)$_GET['id']);
        if (!$banniere) {
            $this->jsonResponse(['error' => 'Bannière non trouvée'], 404);
            return;
        }
        
        $this->jsonResponse(['success' => true, 'data' => $banniere]);
    }

    public function addBanniere() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }
        
        $titre = trim($_POST['titre'] ?? '');
        if (empty($titre)) {
            $this->jsonResponse(['error' => 'Le titre est requis'], 400);
            return;
        }
        
        $imageName = null;
        if (!empty($_FILES['image']['name'])) {
            $imageName = $this->uploadImage($_FILES['image'], 'bannieres');
            if (!$imageName) {
                $this->jsonResponse(['error' => 'Erreur lors du téléchargement de l\'image'], 400);
                return;
            }
        } else {
            $this->jsonResponse(['error' => 'L\'image est requise'], 400);
            return;
        }
        
        $data = [
            'titre' => $titre,
            'sous_titre' => $_POST['sous_titre'] ?? null,
            'image' => $imageName,
            'texte_bouton' => $_POST['texte_bouton'] ?? 'Voir les offres',
            'url_destination' => $_POST['url_destination'] ?? '',
            'date_debut' => $_POST['date_debut'] ?? null,
            'date_fin' => $_POST['date_fin'] ?? null,
            'statut' => $_POST['statut'] ?? 'active',
            'ordre_affichage' => (int)($this->model->countBannieres() + 1)
        ];
        
        if ($this->model->addBanniere($data)) {
            $this->jsonResponse(['success' => true, 'message' => 'Bannière ajoutée avec succès']);
        } else {
            $this->jsonResponse(['error' => 'Erreur lors de l\'ajout'], 500);
        }
    }

    public function updateBanniere() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }
        
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            $this->jsonResponse(['error' => 'ID manquant'], 400);
            return;
        }
        
        $banniere = $this->model->getBanniereById($id);
        if (!$banniere) {
            $this->jsonResponse(['error' => 'Bannière non trouvée'], 404);
            return;
        }
        
        $titre = trim($_POST['titre'] ?? '');
        if (empty($titre)) {
            $this->jsonResponse(['error' => 'Le titre est requis'], 400);
            return;
        }
        
        $data = [
            'titre' => $titre,
            'sous_titre' => $_POST['sous_titre'] ?? null,
            'image' => $banniere['image'],
            'texte_bouton' => $_POST['texte_bouton'] ?? 'Voir les offres',
            'url_destination' => $_POST['url_destination'] ?? '',
            'date_debut' => $_POST['date_debut'] ?? null,
            'date_fin' => $_POST['date_fin'] ?? null,
            'statut' => $_POST['statut'] ?? 'active',
            'ordre_affichage' => $_POST['ordre_affichage'] ?? 0
        ];
        
        // Nouvelle image ?
        if (!empty($_FILES['image']['name'])) {
            $imageName = $this->uploadImage($_FILES['image'], 'bannieres');
            if ($imageName) {
                $data['image'] = $imageName;
                // Supprimer l'ancienne image
                if ($banniere['image'] && file_exists(__DIR__ . '/../../public/uploads/' . $banniere['image'])) {
                    unlink(__DIR__ . '/../../public/uploads/' . $banniere['image']);
                }
            }
        }
        
        if ($this->model->updateBanniere($id, $data)) {
            $this->jsonResponse(['success' => true, 'message' => 'Bannière mise à jour avec succès']);
        } else {
            $this->jsonResponse(['error' => 'Erreur lors de la mise à jour'], 500);
        }
    }

    public function toggleBanniere() {
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
        
        if ($this->model->toggleBanniereStatus($id, $statut)) {
            $this->jsonResponse(['success' => true, 'message' => 'Statut mis à jour avec succès']);
        } else {
            $this->jsonResponse(['error' => 'Erreur lors de la mise à jour'], 500);
        }
    }

    public function updateBanniereOrder() {
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
            if (!$this->model->updateBanniereOrder((int)$id, $index + 1)) {
                $success = false;
            }
        }
        
        if ($success) {
            $this->jsonResponse(['success' => true, 'message' => 'Ordre mis à jour avec succès']);
        } else {
            $this->jsonResponse(['error' => 'Erreur lors de la mise à jour de l\'ordre'], 500);
        }
    }

    public function deleteBanniere() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }
        
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            $this->jsonResponse(['error' => 'ID manquant'], 400);
            return;
        }
        
        if ($this->model->deleteBanniere($id)) {
            $this->jsonResponse(['success' => true, 'message' => 'Bannière supprimée avec succès']);
        } else {
            $this->jsonResponse(['error' => 'Erreur lors de la suppression'], 500);
        }
    }

    public function getBanniereStats() {
        if (!isset($_GET['id'])) {
            $this->jsonResponse(['error' => 'ID manquant'], 400);
            return;
        }
        
        $id = (int)$_GET['id'];
        $banniere = $this->model->getBanniereById($id);
        if (!$banniere) {
            $this->jsonResponse(['error' => 'Bannière non trouvée'], 404);
            return;
        }
        
        $stats7Days = $this->model->getBanniereStats7Days($id);
        $statsTotal = [
            'vues' => $banniere['vues'] ?? 0,
            'clics' => $banniere['clics'] ?? 0
        ];
        $statsTotal['ctr'] = $statsTotal['vues'] > 0 ? round(($statsTotal['clics'] / $statsTotal['vues']) * 100, 2) : 0;
        
        $this->jsonResponse([
            'success' => true,
            'data' => [
                'total' => $statsTotal,
                '7days' => $stats7Days
            ]
        ]);
    }

    // ============================================
    // CODES PROMO - CRUD
    // ============================================

    public function getCodePromo() {
        if (!isset($_GET['id'])) {
            $this->jsonResponse(['error' => 'ID manquant'], 400);
            return;
        }
        
        $code = $this->model->getCodePromoById((int)$_GET['id']);
        if (!$code) {
            $this->jsonResponse(['error' => 'Code promo non trouvé'], 404);
            return;
        }
        
        $this->jsonResponse(['success' => true, 'data' => $code]);
    }

    public function addCodePromo() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }
        
        $code = trim($_POST['code'] ?? '');
        if (empty($code)) {
            $this->jsonResponse(['error' => 'Le code est requis'], 400);
            return;
        }
        
        if ($this->model->codeExists($code)) {
            $this->jsonResponse(['error' => 'Ce code existe déjà'], 400);
            return;
        }
        
        $valeur = (float)($_POST['valeur'] ?? 0);
        if ($valeur <= 0) {
            $this->jsonResponse(['error' => 'La valeur doit être supérieure à 0'], 400);
            return;
        }
        
        $data = [
            'code' => strtoupper($code),
            'type' => $_POST['type'] ?? 'percentage',
            'valeur' => $valeur,
            'montant_minimum' => (float)($_POST['montant_minimum'] ?? 0),
            'utilisations_max' => $_POST['utilisations_max'] ? (int)$_POST['utilisations_max'] : null,
            'date_expiration' => $_POST['date_expiration'] ?? null,
            'categorie_id' => $_POST['categorie_id'] ? (int)$_POST['categorie_id'] : null,
            'produit_id' => $_POST['produit_id'] ? (int)$_POST['produit_id'] : null,
            'utilisateur_id' => $_POST['utilisateur_id'] ? (int)$_POST['utilisateur_id'] : null,
            'statut' => $_POST['statut'] ?? 'active'
        ];
        
        if ($this->model->addCodePromo($data)) {
            $this->jsonResponse(['success' => true, 'message' => 'Code promo ajouté avec succès']);
        } else {
            $this->jsonResponse(['error' => 'Erreur lors de l\'ajout'], 500);
        }
    }

    public function updateCodePromo() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }
        
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            $this->jsonResponse(['error' => 'ID manquant'], 400);
            return;
        }
        
        $code = trim($_POST['code'] ?? '');
        if (empty($code)) {
            $this->jsonResponse(['error' => 'Le code est requis'], 400);
            return;
        }
        
        if ($this->model->codeExists($code, $id)) {
            $this->jsonResponse(['error' => 'Ce code existe déjà'], 400);
            return;
        }
        
        $valeur = (float)($_POST['valeur'] ?? 0);
        if ($valeur <= 0) {
            $this->jsonResponse(['error' => 'La valeur doit être supérieure à 0'], 400);
            return;
        }
        
        $data = [
            'code' => strtoupper($code),
            'type' => $_POST['type'] ?? 'percentage',
            'valeur' => $valeur,
            'montant_minimum' => (float)($_POST['montant_minimum'] ?? 0),
            'utilisations_max' => $_POST['utilisations_max'] ? (int)$_POST['utilisations_max'] : null,
            'date_expiration' => $_POST['date_expiration'] ?? null,
            'categorie_id' => $_POST['categorie_id'] ? (int)$_POST['categorie_id'] : null,
            'produit_id' => $_POST['produit_id'] ? (int)$_POST['produit_id'] : null,
            'utilisateur_id' => $_POST['utilisateur_id'] ? (int)$_POST['utilisateur_id'] : null,
            'statut' => $_POST['statut'] ?? 'active'
        ];
        
        if ($this->model->updateCodePromo($id, $data)) {
            $this->jsonResponse(['success' => true, 'message' => 'Code promo mis à jour avec succès']);
        } else {
            $this->jsonResponse(['error' => 'Erreur lors de la mise à jour'], 500);
        }
    }

    public function toggleCodePromo() {
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
        
        if ($this->model->toggleCodePromoStatus($id, $statut)) {
            $this->jsonResponse(['success' => true, 'message' => 'Statut mis à jour avec succès']);
        } else {
            $this->jsonResponse(['error' => 'Erreur lors de la mise à jour'], 500);
        }
    }

    public function deleteCodePromo() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }
        
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            $this->jsonResponse(['error' => 'ID manquant'], 400);
            return;
        }
        
        if ($this->model->deleteCodePromo($id)) {
            $this->jsonResponse(['success' => true, 'message' => 'Code promo supprimé avec succès']);
        } else {
            $this->jsonResponse(['error' => 'Erreur lors de la suppression'], 500);
        }
    }

    public function getPromoHistorique() {
        if (!isset($_GET['id'])) {
            $this->jsonResponse(['error' => 'ID manquant'], 400);
            return;
        }
        
        $historique = $this->model->getPromoHistorique((int)$_GET['id']);
        $this->jsonResponse(['success' => true, 'data' => $historique]);
    }

    public function generateCode() {
        $this->jsonResponse([
            'success' => true,
            'code' => $this->model->generateRandomCode()
        ]);
    }

    // ============================================
    // UTILITAIRES
    // ============================================

    private function render($view, $data = []) {
        extract($data);
        require __DIR__ . '/../../Views/' . $view . '.php';
    }

    private function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    private function uploadImage($file, $subfolder = '') {
        $uploadDir = __DIR__ . '/../../public/uploads/';
        if ($subfolder) {
            $uploadDir .= $subfolder . '/';
        }
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $imageName = time() . '_' . basename($file['name']);
        $imagePath = $uploadDir . $imageName;
        
        $imageType = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        $formats_autorises = ["jpg", "jpeg", "png", "gif", "webp", "svg"];
        if (!in_array($imageType, $formats_autorises)) {
            return false;
        }
        
        if ($file['size'] > 2 * 1024 * 1024) {
            return false;
        }
        
        if (move_uploaded_file($file['tmp_name'], $imagePath)) {
            return $imageName;
        }
        
        return false;
    }

    public function listCodesPromo() {
    // Récupérer les filtres
    $search = $_GET['search'] ?? '';
    $status = $_GET['status'] ?? '';
    $type = $_GET['type'] ?? '';
    
    // Récupérer les codes avec filtres
    $codes = $this->model->getCodesPromoFiltered($search, $status, $type);
    
    // Statistiques
    $stats = [
        'total' => $this->model->countCodesPromo(),
        'active' => $this->model->countActiveCodesPromo(),
        'inactive' => $this->model->countInactiveCodesPromo(),
        'total_remises' => $this->model->getTotalRemises()
    ];
    
    $this->jsonResponse([
        'success' => true,
        'data' => $codes,
        'stats' => $stats
    ]);
}
}