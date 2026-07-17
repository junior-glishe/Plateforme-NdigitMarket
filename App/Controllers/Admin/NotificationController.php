<?php

require_once __DIR__ . '/../../Models/NotificationModel.php';

class NotificationController {
    private $model;
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new NotificationModel($pdo);
    }

    // ============================================
    // PAGE PRINCIPALE
    // ============================================

    public function index() {
        // Récupérer les notifications
        $notifications = $this->model->getAllNotifications(50);
        $stats = $this->model->getNotificationStats();
        $settings = $this->model->getSettings();

           // Stats emails
    $emailStats = [
        'envoyes' => $this->model->countEmailsEnvoyes(),
        'taux_ouverture' => $this->model->getTauxOuverture(),
        'taux_clic' => $this->model->getTauxClic(),
        'templates' => $this->model->countEmailTemplates()
    ];

    // Stats envois en masse
$massStats = [
    'campagnes' => $this->model->countMassCampaigns(),
    'taux_ouverture' => $this->model->getMassTauxOuverture(),
    'taux_clic' => $this->model->getMassTauxClic(),
    'planifies' => $this->model->countMassPlanifies()
];
    
    // Récupérer les templates
    $templates = $this->model->getAllEmailTemplates();

    // Récupérer les campagnes récentes
$campagnes = $this->model->getRecentMassCampaigns(10);
    
        
        // Compter par type pour les stats
        $stats['vendor_requests'] = $this->model->countByType('vendor_request');
        $stats['product_moderation'] = $this->model->countByType('product_moderation');
        $stats['order_high'] = $this->model->countByType('order_high');
        $stats['order_problem'] = $this->model->countByType('order_problem');
        $stats['support_message'] = $this->model->countByType('support_message');
        $stats['security_alert'] = $this->model->countByType('security_alert');
        
        $this->render('admin/notifications', [
            'notifications' => $notifications,
            'stats' => $stats,
            'settings' => $settings,
             'emailStats' => $emailStats,  
            'templates' => $templates,
            'massStats' => $massStats, 
            'campagnes' => $campagnes,
            'currentPage' => 'notifications' 
        ]);
    }

    // ============================================
    // API NOTIFICATIONS
    // ============================================

    /**
     * Récupérer les notifications (API)
     */
    public function getNotifications() {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
    $offset = ($page - 1) * $limit;
    
    // Récupérer les notifications selon le filtre
    if ($filter === 'unread') {
        $notifications = $this->model->getUnreadNotifications($limit);
        $total = $this->model->countUnreadNotifications();
    } else {
        $notifications = $this->model->getAllNotifications($limit, $offset);
        $total = $this->model->countAllNotifications();
    }
    
    // Stats
    $stats = $this->model->getNotificationStats();
    
    $this->jsonResponse([
        'success' => true,
        'data' => $notifications,
        'total' => $total,
        'page' => $page,
        'limit' => $limit,
        'stats' => $stats
    ]);
}

    /**
     * Marquer une notification comme lue
     */
    public function markRead() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }
        
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            $this->jsonResponse(['error' => 'ID manquant'], 400);
            return;
        }
        
        if ($this->model->markAsRead($id)) {
            $this->jsonResponse(['success' => true, 'message' => 'Notification marquée comme lue']);
        } else {
            $this->jsonResponse(['error' => 'Erreur lors de la mise à jour'], 500);
        }
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllRead() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }
        
        $count = $this->model->markAllAsRead();
        $this->jsonResponse(['success' => true, 'message' => "{$count} notifications marquées comme lues"]);
    }

    /**
     * Supprimer une notification
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
        
        if ($this->model->deleteNotification($id)) {
            $this->jsonResponse(['success' => true, 'message' => 'Notification supprimée']);
        } else {
            $this->jsonResponse(['error' => 'Erreur lors de la suppression'], 500);
        }
    }


    /**
 * Mettre à jour les paramètres des notifications
 */
public function updateSettings() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
        return;
    }

    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    
    // Logique de sauvegarde
    $this->jsonResponse([
        'success' => true,
        'message' => 'Paramètres mis à jour'
    ]);
}

    // ============================================
    // FONCTIONS UTILITAIRES
    // ============================================

    private function render($view, $data = []) {
        extract($data);
        $viewPath = __DIR__ . '/../../Views/' . $view . '.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        }
    }

    private function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    // ============================================
// EMAILS TRANSACTIONNELS - API
// ============================================

/**
 * Récupérer tous les templates (API)
 */
public function getTemplates() {
    $templates = $this->model->getAllEmailTemplates();
    $this->jsonResponse([
        'success' => true,
        'data' => $templates
    ]);
}

/**
 * Changer le statut d'un template (AVEC MODAL)
 */
public function toggleTemplate() {
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
    
    if ($this->model->toggleEmailTemplateStatus($id, $statut)) {
        $this->jsonResponse(['success' => true, 'message' => 'Statut du template mis à jour']);
    } else {
        $this->jsonResponse(['error' => 'Erreur lors de la mise à jour'], 500);
    }
}

/**
 * Mettre à jour un template
 */
public function updateTemplate() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
        return;
    }
    
    $id = (int)($_POST['id'] ?? 0);
    if (!$id) {
        $this->jsonResponse(['error' => 'ID manquant'], 400);
        return;
    }
    
    $nom = trim($_POST['nom'] ?? '');
    if (empty($nom)) {
        $this->jsonResponse(['error' => 'Le nom est requis'], 400);
        return;
    }
    
    $data = [
        'nom' => $nom,
        'slug' => $this->generateSlug($nom),
        'objet' => $_POST['objet'] ?? '',
        'contenu' => $_POST['contenu'] ?? '',
        'bouton_texte' => $_POST['bouton_texte'] ?? '',
        'bouton_url' => $_POST['bouton_url'] ?? '#',
        'bg_color' => $_POST['bg_color'] ?? 'bg-amber-100',
        'text_color' => $_POST['text_color'] ?? 'text-amber-600',
        'icon' => $_POST['icon'] ?? 'fa-envelope-open-text',
        'statut' => $_POST['statut'] ?? 'active'
    ];
    
    if ($this->model->updateEmailTemplate($id, $data)) {
        $this->jsonResponse(['success' => true, 'message' => 'Template mis à jour avec succès']);
    } else {
        $this->jsonResponse(['error' => 'Erreur lors de la mise à jour'], 500);
    }
}

/**
 * Générer un slug à partir d'un nom
 */
private function generateSlug($text) {
    $text = mb_strtolower($text);
    $text = preg_replace('/[^a-z0-9-]/', '-', $text);
    $text = preg_replace('/-+/', '-', $text);
    return trim($text, '-');
}

// ============================================
// CAMPAGNES EMAILS EN MASSE
// ============================================

public function getCampaignStats() {
    if (!isset($_GET['id'])) {
        $this->jsonResponse(['error' => 'ID manquant'], 400);
        return;
    }
    
    $id = (int)$_GET['id'];
    $stats = $this->model->getCampaignStats($id);
    $this->jsonResponse(['success' => true, 'data' => $stats]);
}

public function duplicateCampaign() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
        return;
    }
    
    $id = (int)($_POST['id'] ?? 0);
    if (!$id) {
        $this->jsonResponse(['error' => 'ID manquant'], 400);
        return;
    }
    
    if ($this->model->duplicateCampaign($id)) {
        $this->jsonResponse(['success' => true, 'message' => 'Campagne dupliquée avec succès']);
    } else {
        $this->jsonResponse(['error' => 'Erreur lors de la duplication'], 500);
    }
}

public function cancelCampaign() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
        return;
    }
    
    $id = (int)($_POST['id'] ?? 0);
    if (!$id) {
        $this->jsonResponse(['error' => 'ID manquant'], 400);
        return;
    }
    
    if ($this->model->cancelCampaign($id)) {
        $this->jsonResponse(['success' => true, 'message' => 'Campagne annulée']);
    } else {
        $this->jsonResponse(['error' => 'Erreur lors de l\'annulation'], 500);
    }
}


public function exportCampaignCSV() {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $data = $this->model->getCampaignExportData($id);
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=campagne_' . $id . '_' . date('Y-m-d') . '.csv');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Email', 'Ouvert', 'Clic', 'Date']);
    
    foreach ($data as $row) {
        fputcsv($output, [
            $row['email'],
            $row['ouvert'] ? 'Oui' : 'Non',
            $row['clic'] ? 'Oui' : 'Non',
            $row['date_envoi']
        ]);
    }
    
    fclose($output);
    exit();
}

/**
 * Composer un email
 */
public function compose() {
    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        $this->jsonResponse(['error' => 'Non authentifié'], 401);
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
        return;
    }

    // Récupérer les données
    $nom = trim($_POST['nom'] ?? '');
    $sujet = trim($_POST['sujet'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    $cible = $_POST['cible'] ?? 'all';
    $bouton_texte = trim($_POST['bouton_texte'] ?? '');
    $bouton_url = trim($_POST['bouton_url'] ?? '#');
    $date_planification = $_POST['date_planification'] ?? null;

    // Validation
    if (empty($nom)) {
        $this->jsonResponse(['error' => 'Le nom de la campagne est requis'], 400);
        return;
    }

    if (empty($sujet)) {
        $this->jsonResponse(['error' => 'Le sujet est requis'], 400);
        return;
    }

    if (empty($contenu)) {
        $this->jsonResponse(['error' => 'Le contenu est requis'], 400);
        return;
    }

    // Déterminer le label et la classe de la cible
    $cibleLabels = [
        'all' => ['label' => 'Tous les utilisateurs', 'class' => 'text-blue-700 bg-blue-100'],
        'vendors' => ['label' => 'Tous les vendeurs', 'class' => 'text-purple-700 bg-purple-100'],
        'buyers' => ['label' => 'Acheteurs actifs', 'class' => 'text-emerald-700 bg-emerald-100'],
        'custom' => ['label' => 'Liste personnalisée', 'class' => 'text-orange-700 bg-orange-100']
    ];

    $cibleInfo = $cibleLabels[$cible] ?? $cibleLabels['all'];

    // Créer la campagne
    $data = [
        'nom' => $nom,
        'sujet' => $sujet,
        'contenu' => $contenu,
        'cible' => $cible,
        'cible_label' => $cibleInfo['label'],
        'cible_class' => $cibleInfo['class'],
        'bouton_texte' => $bouton_texte,
        'bouton_url' => $bouton_url,
        'statut' => $date_planification ? 'planifie' : 'envoye',
        'date_planification' => $date_planification
    ];

    $result = $this->model->createMassCampaign($data);

    if ($result) {
        $this->jsonResponse([
            'success' => true,
            'message' => 'Campagne créée avec succès',
            'data' => [
                'nom' => $nom,
                'sujet' => $sujet,
                'cible' => $cibleInfo['label'],
                'statut' => $data['statut']
            ]
        ]);
    } else {
        $this->jsonResponse(['error' => 'Erreur lors de la création de la campagne'], 500);
    }
}


/**
 * Obtenir le label de la cible
 */
private function getCibleLabel($cible) {
    $labels = [
        'all' => 'Tous les utilisateurs',
        'vendors' => 'Tous les vendeurs',
        'buyers' => 'Acheteurs actifs',
        'custom' => 'Liste personnalisée'
    ];
    return $labels[$cible] ?? 'Tous les utilisateurs';
}

/**
 * Obtenir la classe CSS de la cible
 */
private function getCibleClass($cible) {
    $classes = [
        'all' => 'text-blue-700 bg-blue-100',
        'vendors' => 'text-purple-700 bg-purple-100',
        'buyers' => 'text-emerald-700 bg-emerald-100',
        'custom' => 'text-orange-700 bg-orange-100'
    ];
    return $classes[$cible] ?? 'text-blue-700 bg-blue-100';
}

/**
 * Envoyer une campagne (créer et envoyer)
 */
public function sendCampaign() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
        return;
    }

    $nom = trim($_POST['nom'] ?? '');
    $sujet = trim($_POST['sujet'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    $cible = $_POST['cible'] ?? 'all';
    $bouton_texte = trim($_POST['bouton_texte'] ?? '');
    $bouton_url = trim($_POST['bouton_url'] ?? '#');
    $date_planification = $_POST['date_planification'] ?? null;

    // Validation
    if (empty($nom) || empty($sujet) || empty($contenu)) {
        $this->jsonResponse(['error' => 'Tous les champs obligatoires doivent être remplis'], 400);
        return;
    }

    // Labels pour la cible
    $cibleLabels = [
        'all' => 'Tous les utilisateurs',
        'vendors' => 'Tous les vendeurs',
        'buyers' => 'Acheteurs actifs',
        'custom' => 'Liste personnalisée'
    ];
    $cibleClasses = [
        'all' => 'text-blue-700 bg-blue-100',
        'vendors' => 'text-purple-700 bg-purple-100',
        'buyers' => 'text-emerald-700 bg-emerald-100',
        'custom' => 'text-orange-700 bg-orange-100'
    ];

    // Créer la campagne
    $data = [
        'nom' => $nom,
        'sujet' => $sujet,
        'contenu' => $contenu,
        'cible' => $cible,
        'cible_label' => $cibleLabels[$cible] ?? 'Tous les utilisateurs',
        'cible_class' => $cibleClasses[$cible] ?? 'text-blue-700 bg-blue-100',
        'bouton_texte' => $bouton_texte,
        'bouton_url' => $bouton_url,
        'statut' => $date_planification ? 'planifie' : 'envoye',
        'date_planification' => $date_planification
    ];

    $result = $this->model->createMassCampaign($data);

    if ($result) {
        $this->jsonResponse([
            'success' => true,
            'message' => $date_planification ? 'Campagne planifiée avec succès' : 'Campagne envoyée avec succès'
        ]);
    } else {
        $this->jsonResponse(['error' => 'Erreur lors de la création de la campagne'], 500);
    }
}

/**
 * Envoyer un email de test
 */
public function testCampaign() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
        return;
    }

    $sujet = trim($_POST['sujet'] ?? 'Email de test');
    $contenu = trim($_POST['contenu'] ?? '');

    // Ici vous pouvez implémenter l'envoi réel d'email
    // Pour l'instant, on simule
    $this->jsonResponse([
        'success' => true,
        'message' => 'Email de test envoyé avec succès'
    ]);
}
}