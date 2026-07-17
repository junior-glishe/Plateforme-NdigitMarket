<?php

require_once __DIR__ . '/../../Models/LogsAuditModel.php';

  use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Style\Alignment;
    use PhpOffice\PhpSpreadsheet\Style\Fill;
    use PhpOffice\PhpSpreadsheet\Style\Border;
    use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

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
    //  Récupérer les données pour les filtres
    $actionTypes = $this->model->getActionTypes();
    $admins = $this->model->getAdmins();

      //  DEBUG - Vérifier que les admins sont récupérés
    error_log("Nombre d'admins: " . count($admins));
    error_log("Admins: " . print_r($admins, true));
    
    //  Récupérer les filtres depuis l'URL
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
    
    //  Passer toutes les données à la vue
    $this->render('admin/logs-audit', [
        'stats' => $stats,
        'logs' => $logs,
        'total' => $total,
        'page' => $page,
        'limit' => $limit,
        'filters' => $filters,
        'chartData' => $chartData,
        'actionTypes' => $actionTypes,    //  Pour les types d'actions
        'admins' => $admins,              //  Pour les administrateurs
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
 * API - Liste des logs avec filtres
 */
public function getLogs() {
    $filters = [];
    if (!empty($_GET['search'])) $filters['search'] = $_GET['search'];
    if (!empty($_GET['action'])) $filters['action'] = $_GET['action'];
    if (!empty($_GET['admin_id'])) $filters['admin_id'] = (int)$_GET['admin_id'];
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
 * API - Bloquer IP (CORRIGÉ)
 */
public function blockIP() {
    // Accepter les deux méthodes POST et GET pour le test
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->jsonResponse(['success' => false, 'error' => 'Méthode non autorisée. Utilisez POST.'], 405);
        return;
    }
    
    // Récupérer l'IP depuis POST (FormData)
    $ip = $_POST['ip'] ?? '';
    
    // Si pas d'IP, essayer de récupérer depuis le body JSON
    if (empty($ip)) {
        $input = json_decode(file_get_contents('php://input'), true);
        $ip = $input['ip'] ?? '';
    }
    
    // Nettoyer l'IP
    $ip = trim($ip);
    
    if (empty($ip)) {
        $this->jsonResponse(['success' => false, 'error' => 'Adresse IP requise'], 400);
        return;
    }
    
    // Valider le format IP
    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        $this->jsonResponse(['success' => false, 'error' => 'Adresse IP invalide'], 400);
        return;
    }
    
    // Logger l'action
    $this->model->addLog([
        'action_type' => 'block_ip',
        'action_description' => 'Blocage IP: ' . $ip . ' - Raison: ' . ($_POST['reason'] ?? 'Non spécifiée'),
        'level' => 'critical',
        'metadata' => ['ip' => $ip, 'reason' => $_POST['reason'] ?? null]
    ]);
    
    $this->jsonResponse([
        'success' => true,
        'message' => 'IP ' . $ip . ' bloquée avec succès'
    ]);
}

     // ============================================
    //  AJOUTE CETTE MÉTHODE render()
    // ============================================
    private function render($view, $data = []) {
        extract($data);
        $viewPath = __DIR__ . '/../../Views/' . $view . '.php';
        
        // Debug - Vérifier si le fichier existe
        if (!file_exists($viewPath)) {
            echo " Vue non trouvée: " . $viewPath;
            exit();
        }
        
        include $viewPath;
    }

/**
 * API - Exporter les logs (CSV, Excel, JSON)
 */
public function exportLogs() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->jsonResponse(['success' => false, 'error' => 'Méthode non autorisée'], 405);
        return;
    }
    
    $format = $_POST['format'] ?? 'csv';
    $period = $_POST['period'] ?? 'all';
    $columnsRaw = $_POST['columns'] ?? '';
    
    //  CORRECTION : Décoder le JSON correctement
    if (is_string($columnsRaw) && !empty($columnsRaw)) {
        $columns = json_decode($columnsRaw, true);
        // Si le JSON est invalide, utiliser un tableau vide
        if (!is_array($columns)) {
            $columns = [];
        }
    } else {
        $columns = [];
    }
    
    // Récupérer les filtres
    $filters = [];
    if ($period !== 'all') {
        switch($period) {
            case 'today':
                $filters['date_from'] = date('Y-m-d');
                $filters['date_to'] = date('Y-m-d');
                break;
            case '7days':
                $filters['date_from'] = date('Y-m-d', strtotime('-7 days'));
                $filters['date_to'] = date('Y-m-d');
                break;
            case '30days':
                $filters['date_from'] = date('Y-m-d', strtotime('-30 days'));
                $filters['date_to'] = date('Y-m-d');
                break;
            case 'month':
                $filters['date_from'] = date('Y-m-01');
                $filters['date_to'] = date('Y-m-d');
                break;
        }
    }
    
    // Récupérer les logs
    $logs = $this->model->getLogs($filters, 10000, 0);
    
    //  Si aucune colonne sélectionnée, utiliser toutes
    if (empty($columns)) {
        $columns = ['Date', 'Admin', 'Email', 'Action', 'Description', 'IP', 'Niveau', 'Statut'];
    }
    
    // Construire les données pour l'export
    $allColumns = [
        'Date' => 'created_at',
        'Admin' => 'admin_name',
        'Email' => 'admin_email',
        'Action' => 'action',
        'Description' => 'action_description',
        'Entité' => 'entity_type',
        'ID Entité' => 'entity_id',
        'IP' => 'ip_address',
        'Niveau' => 'level',
        'Statut' => 'status'
    ];
    
    // Filtrer les colonnes
    $selectedColumns = [];
    foreach ($columns as $col) {
        if (isset($allColumns[$col])) {
            $selectedColumns[$col] = $allColumns[$col];
        }
    }
    
    // Si aucune colonne valide, utiliser toutes
    if (empty($selectedColumns)) {
        $selectedColumns = $allColumns;
    }
    
    $data = [];
    foreach ($logs as $log) {
        $row = [];
        foreach ($selectedColumns as $header => $field) {
            $value = $log[$field] ?? '-';
            // Formater la date
            if ($field === 'created_at' && $value) {
                $value = date('d/m/Y H:i', strtotime($value));
            }
            $row[$header] = $value;
        }
        $data[] = $row;
    }
    
    $filename = 'logs_audit_' . date('Y-m-d');
    
    switch($format) {
        case 'csv':
            $this->exportLogsCSV($data, $filename);
            break;
        case 'excel':
            $this->exportLogsExcel($data, $filename);
            break;
        case 'json':
            $this->exportLogsJSON($data, $filename);
            break;
        default:
            $this->jsonResponse(['success' => false, 'error' => 'Format non supporté'], 400);
    }
}

/**
 * Export CSV pour les logs
 */
private function exportLogsCSV($data, $filename) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename . '.csv');
    header('Cache-Control: max-age=0');
    
    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8
    
    if (!empty($data)) {
        // Entêtes
        fputcsv($output, array_keys($data[0]));
        
        // Données
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
    }
    
    fclose($output);
    exit();
}

/**
 * Export Excel pour les logs
 */
private function exportLogsExcel($data, $filename) {
    require_once __DIR__ . '/../../../vendor/autoload.php';
    
  
    
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Style des entêtes
    $headerStyle = [
        'font' => [
            'bold' => true,
            'color' => ['rgb' => 'FFFFFF'],
            'size' => 11
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => '0EA486']
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER
        ]
    ];
    
    // Entêtes
    $col = 0;
    if (!empty($data)) {
        foreach (array_keys($data[0]) as $header) {
            $column = Coordinate::stringFromColumnIndex($col + 1);
            $sheet->setCellValue($column . '1', $header);
            $sheet->getColumnDimension($column)->setAutoSize(true);
            $col++;
        }
    }
    
    $lastColumn = Coordinate::stringFromColumnIndex($col);
    $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray($headerStyle);
    $sheet->getRowDimension(1)->setRowHeight(25);
    
    // Données
    $rowNum = 2;
    foreach ($data as $row) {
        $col = 0;
        foreach ($row as $value) {
            $column = Coordinate::stringFromColumnIndex($col + 1);
            $sheet->setCellValue($column . $rowNum, $value);
            $col++;
        }
        $rowNum++;
    }
    
    // Bordures
    $styleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => 'E5E7EB']
            ]
        ]
    ];
    $sheet->getStyle('A1:' . $lastColumn . ($rowNum - 1))->applyFromArray($styleArray);
    
    //  Forcer l'extension .xlsx
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '.xlsx"');
    header('Cache-Control: max-age=0');
    
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
}

/**
 * Export JSON pour les logs
 */
private function exportLogsJSON($data, $filename) {
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename . '.json');
    header('Cache-Control: max-age=0');
    
    echo json_encode([
        'exported_at' => date('Y-m-d H:i:s'),
        'total' => count($data),
        'data' => $data
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit();
}



// App/Controllers/Admin/LogsAuditController.php

public function updateRetention() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->jsonResponse(['success' => false, 'error' => 'Méthode non autorisée'], 405);
        return;
    }
    
    $retention = (int)($_POST['retention_days'] ?? 365);
    $criticalRetention = $_POST['critical_retention'] ?? 'unlimited';
    
    // Supprimer les anciens logs (sauf critiques si conservation illimitée)
    $deleted = 0;
    if ($retention > 0) {
        $deleted = $this->model->deleteOldLogs($retention);
    }
    
    // Journaliser l'action
    $this->model->addLog([
        'action_type' => 'configuration_retention',
        'action_description' => 'Mise à jour de la rétention: ' . $retention . ' jours',
        'level' => 'info',
        'metadata' => [
            'retention_days' => $retention,
            'critical_retention' => $criticalRetention,
            'logs_deleted' => $deleted
        ]
    ]);
    
    $this->jsonResponse([
        'success' => true,
        'message' => 'Rétention configurée avec succès',
        'data' => [
            'retention_days' => $retention,
            'logs_deleted' => $deleted
        ]
    ]);
}




    private function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}