<?php

require_once __DIR__ . '/../../Models/LogsAuditModel.php';

class LogsAuditController {
    private $model;
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new LogsAuditModel($pdo);
    }

    /**
     * Page principale
     */

public function index() {
    // 🔥 Récupérer les données pour les filtres
    $actionTypes = $this->model->getActionTypes();
    $admins = $this->model->getAdmins();
    
    // 🔥 Récupérer les filtres depuis l'URL
    $filters = [];
    if (!empty($_GET['search'])) $filters['search'] = $_GET['search'];
    if (!empty($_GET['action'])) $filters['action'] = $_GET['action'];
    if (!empty($_GET['admin_id'])) $filters['admin_id'] = $_GET['admin_id'];
    if (!empty($_GET['level'])) $filters['level'] = $_GET['level'];
    if (!empty($_GET['period'])) $filters['period'] = $_GET['period'];
    if (!empty($_GET['date_from'])) $filters['date_from'] = $_GET['date_from'];
    if (!empty($_GET['date_to'])) $filters['date_to'] = $_GET['date_to'];
    
    // Pagination
    $page = (int)($_GET['page'] ?? 1);
    $limit = (int)($_GET['limit'] ?? 25);
    $offset = ($page - 1) * $limit;
    
    // Données
    $stats = $this->model->getStats();
    $logs = $this->model->getLogs($filters, $limit, $offset);
    $total = $this->model->countLogs($filters);
    $chartData = $this->model->getActivityChart(7);
    
    // 🔥 Passer toutes les données à la vue
    $this->render('admin/logs-audit', [
        'stats' => $stats,
        'logs' => $logs,
        'total' => $total,
        'page' => $page,
        'limit' => $limit,
        'filters' => $filters,
        'chartData' => $chartData,
        'actionTypes' => $actionTypes,    // 🔥 Pour les types d'actions
        'admins' => $admins,              // 🔥 Pour les administrateurs
        'currentPage' => 'logs-audit'
    ]);
}

    /**
     * API - Statistiques
     */
    public function getStats() {
        $stats = $this->model->getStats();
        $this->jsonResponse(['success' => true, 'data' => $stats]);
    }

    /**
     * API - Liste des logs
     */
    public function getLogs() {
    $filters = [];
    if (!empty($_GET['search'])) $filters['search'] = $_GET['search'];
    if (!empty($_GET['action'])) $filters['action'] = $_GET['action'];
    if (!empty($_GET['admin_id'])) $filters['admin_id'] = $_GET['admin_id'];
    if (!empty($_GET['level'])) $filters['level'] = $_GET['level'];
    if (!empty($_GET['period'])) $filters['period'] = $_GET['period'];
    if (!empty($_GET['date_from'])) $filters['date_from'] = $_GET['date_from'];
    if (!empty($_GET['date_to'])) $filters['date_to'] = $_GET['date_to'];
    
    $page = (int)($_GET['page'] ?? 1);
    $limit = (int)($_GET['limit'] ?? 25);
    $offset = ($page - 1) * $limit;
    
    $logs = $this->model->getLogs($filters, $limit, $offset);
    $total = $this->model->countLogs($filters);
    
    $this->jsonResponse([
        'success' => true,
        'data' => $logs,
        'pagination' => [
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'pages' => ceil($total / $limit)
        ]
    ]);
}

    /**
     * API - Graphique
     */
    public function getChartData() {
        $days = (int)($_GET['days'] ?? 7);
        $data = $this->model->getActivityChart($days);
        $this->jsonResponse(['success' => true, 'data' => $data]);
    }

    /**
     * API - Détail d'un log
     */
    public function getLogDetail() {
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) {
            $this->jsonResponse(['success' => false, 'error' => 'ID requis'], 400);
            return;
        }
        
        $log = $this->model->getLogById($id);
        if (!$log) {
            $this->jsonResponse(['success' => false, 'error' => 'Log non trouvé'], 404);
            return;
        }
        
        if ($log['metadata']) {
            $log['metadata'] = json_decode($log['metadata'], true);
        }
        
        $this->jsonResponse(['success' => true, 'data' => $log]);
    }

    /**
     * API - Export CSV
     */
    public function exportCSV() {
        $filters = $_GET['filters'] ?? [];
        $this->model->exportCSV($filters);
    }

    /**
     * API - Rétention
     */
    public function updateRetention() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'error' => 'Méthode non autorisée'], 405);
            return;
        }
        
        $retention = (int)($_POST['retention_days'] ?? 365);
        $deleted = $this->model->deleteOldLogs($retention);
        
        $this->model->addLog([
            'action_type' => 'configuration_retention',
            'action_description' => 'Mise à jour de la rétention: ' . $retention . ' jours',
            'level' => 'info',
            'metadata' => ['retention_days' => $retention, 'logs_deleted' => $deleted]
        ]);
        
        $this->jsonResponse([
            'success' => true,
            'message' => 'Rétention configurée',
            'data' => ['retention_days' => $retention, 'logs_deleted' => $deleted]
        ]);
    }

    /**
     * API - Bloquer IP
     */
    public function blockIP() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'error' => 'Méthode non autorisée'], 405);
            return;
        }
        
        $ip = $_POST['ip'] ?? '';
        if (empty($ip)) {
            $this->jsonResponse(['success' => false, 'error' => 'IP requise'], 400);
            return;
        }
        
        $this->model->addLog([
            'action_type' => 'block_ip',
            'action_description' => 'Blocage IP: ' . $ip,
            'level' => 'critical',
            'metadata' => ['ip' => $ip]
        ]);
        
        $this->jsonResponse(['success' => true, 'message' => 'IP bloquée']);
    }

     // ============================================
    // 🔥 AJOUTE CETTE MÉTHODE render()
    // ============================================
    private function render($view, $data = []) {
        extract($data);
        $viewPath = __DIR__ . '/../../Views/' . $view . '.php';
        
        // Debug - Vérifier si le fichier existe
        if (!file_exists($viewPath)) {
            echo "❌ Vue non trouvée: " . $viewPath;
            exit();
        }
        
        include $viewPath;
    }



    private function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}