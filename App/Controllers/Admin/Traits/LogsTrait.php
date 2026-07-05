<?php
/**
 * NDIGITMARKET - Admin trait : Logs & audit trail
 * Lit la table `admin_logs` (créée automatiquement par logAction()) avec filtres.
 */

namespace App\Controllers\Admin\Traits;

trait LogsTrait
{
    public function logs()
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        // S'assurer que la table existe
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
                INDEX idx_date (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        } catch (\Exception $e) { /* ignore */ }

        // Filtres
        $where = [];
        $params = [];
        if (!empty($_GET['action'])) {
            $where[] = 'action LIKE :action';
            $params[':action'] = '%' . $_GET['action'] . '%';
        }
        if (!empty($_GET['status'])) {
            $where[] = 'status = :status';
            $params[':status'] = $_GET['status'];
        }
        if (!empty($_GET['date_from'])) {
            $where[] = 'created_at >= :df';
            $params[':df'] = $_GET['date_from'] . ' 00:00:00';
        }
        if (!empty($_GET['date_to'])) {
            $where[] = 'created_at <= :dt';
            $params[':dt'] = $_GET['date_to'] . ' 23:59:59';
        }
        if (!empty($_GET['search'])) {
            $where[] = '(details LIKE :s OR ip_address LIKE :s)';
            $params[':s'] = '%' . $_GET['search'] . '%';
        }
        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $stmt = $db->prepare("
            SELECT l.*, a.nom AS admin_nom, a.email AS admin_email
            FROM admin_logs l
            LEFT JOIN admin a ON a.id_gestion = l.admin_id
            $whereClause
            ORDER BY l.created_at DESC
            LIMIT 500
        ");
        $stmt->execute($params);
        $logs = $stmt->fetchAll();

        foreach ($logs as &$log) {
            $log['date_formatted'] = $this->formatDate($log['created_at'] ?? null, 'd/m/Y H:i');
            $log['statut_label']   = $log['status'] === 'success' ? 'Succès' : 'Erreur';
            $log['statut_class']   = $log['status'] === 'success' ? 'success' : 'danger';
        }
        unset($log);

        $totalLogs   = count($logs);
        $currentPage = 'logs-audit';
        require_once __DIR__ . '/../../../Views/admin/logs-audit.php';
    }

    /**
     * Export CSV des logs (respect du CDC).
     */
    public function exportLogs(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $rows = $db->query("SELECT id, admin_id, action, target_user_id, details, status, ip_address, created_at
            FROM admin_logs ORDER BY created_at DESC LIMIT 5000")->fetchAll();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="admin_logs_' . date('Ymd_His') . '.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['id','admin_id','action','target_user_id','details','status','ip','date']);
        foreach ($rows as $r) fputcsv($out, $r);
        fclose($out);
        exit;
    }
}
