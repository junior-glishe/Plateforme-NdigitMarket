<?php
// App/Controllers/Admin/NotificationController.php

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
            'settings' => $settings
        ]);
    }

    // ============================================
    // API NOTIFICATIONS
    // ============================================

    /**
     * Récupérer les notifications (API)
     */
    public function getNotifications() {
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
        $unreadOnly = isset($_GET['unread']) && $_GET['unread'] === 'true';
        
        if ($unreadOnly) {
            $notifications = $this->model->getUnreadNotifications($limit);
        } else {
            $notifications = $this->model->getAllNotifications($limit);
        }
        
        $stats = $this->model->getNotificationStats();
        
        $this->jsonResponse([
            'success' => true,
            'data' => $notifications,
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
     * Mettre à jour les paramètres
     */
    public function updateSettings() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }
        
        $settings = $_POST['settings'] ?? [];
        $success = true;
        
        foreach ($settings as $type => $data) {
            if (!$this->model->updateSetting($type, $data)) {
                $success = false;
            }
        }
        
        if ($success) {
            $this->jsonResponse(['success' => true, 'message' => 'Paramètres mis à jour']);
        } else {
            $this->jsonResponse(['error' => 'Erreur lors de la mise à jour'], 500);
        }
    }

    // ============================================
    // FONCTIONS UTILITAIRES
    // ============================================

    private function render($view, $data = []) {
        extract($data);
        $viewPath = __DIR__ . '/../Views/' . $view . '.php';
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
}