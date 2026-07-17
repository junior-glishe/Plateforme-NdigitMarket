<?php
// App/Models/LogsAuditModel.php

class LogsAuditModel {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Ajouter une entrée de log
     */
    public function addLog($data) {
        try {
            $sql = "
                INSERT INTO admin_logs (
                    admin_id, admin_email, admin_name,
                    action, action_description,
                    target_user_id, entity_type, entity_id,
                    ip_address, user_agent,
                    level, status, metadata
                ) VALUES (
                    :admin_id, :admin_email, :admin_name,
                    :action, :action_description,
                    :target_user_id, :entity_type, :entity_id,
                    :ip_address, :user_agent,
                    :level, :status, :metadata
                )
            ";
            
            $stmt = $this->pdo->prepare($sql);
            
            // Récupérer les infos de l'admin connecté
            $adminId = $_SESSION['admin_id'] ?? null;
            $adminEmail = $_SESSION['admin_email'] ?? null;
            $adminName = $_SESSION['admin_name'] ?? null;
            
            $stmt->execute([
                ':admin_id' => $data['admin_id'] ?? $adminId,
                ':admin_email' => $data['admin_email'] ?? $adminEmail,
                ':admin_name' => $data['admin_name'] ?? $adminName,
                ':action' => $data['action_type'],
                ':action_description' => $data['action_description'] ?? $data['details'] ?? null,
                ':target_user_id' => $data['target_user_id'] ?? null,
                ':entity_type' => $data['entity_type'] ?? null,
                ':entity_id' => $data['entity_id'] ?? null,
                ':ip_address' => $data['ip_address'] ?? $_SERVER['REMOTE_ADDR'] ?? null,
                ':user_agent' => $data['user_agent'] ?? $_SERVER['HTTP_USER_AGENT'] ?? null,
                ':level' => $data['level'] ?? 'info',
                ':status' => $data['status'] ?? 'success',
                ':metadata' => isset($data['metadata']) ? json_encode($data['metadata']) : null
            ]);
            
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur addLog: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer les statistiques globales
     */
    public function getStats() {
        try {
            $stats = [];
            
            // Total des logs
            $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM admin_logs");
            $stats['total'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Connexions
            $stmt = $this->pdo->query("
                SELECT COUNT(*) as total 
                FROM admin_logs 
                WHERE action LIKE '%connexion%' OR action LIKE '%login%'
            ");
            $stats['connexions'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Modifications
            $stmt = $this->pdo->query("
                SELECT COUNT(*) as total 
                FROM admin_logs 
                WHERE action LIKE '%modification%' OR action LIKE '%update%' OR action LIKE '%edit%'
            ");
            $stats['modifications'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Critiques
            $stmt = $this->pdo->query("
                SELECT COUNT(*) as total 
                FROM admin_logs 
                WHERE level = 'critical' OR status = 'failed'
            ");
            $stats['critiques'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            return $stats;
        } catch (PDOException $e) {
            error_log("Erreur getStats: " . $e->getMessage());
            return ['total' => 0, 'connexions' => 0, 'modifications' => 0, 'critiques' => 0];
        }
    }

/**
 * Récupérer les données pour le graphique d'activité
 */
public function getActivityChart($days = 7) {
    try {
        $stmt = $this->pdo->prepare("
            SELECT 
                DATE(created_at) as date,
                COUNT(*) as total,
                SUM(CASE WHEN level = 'critical' THEN 1 ELSE 0 END) as critical,
                SUM(CASE WHEN level = 'warning' THEN 1 ELSE 0 END) as warning,
                SUM(CASE WHEN level = 'info' THEN 1 ELSE 0 END) as info
            FROM admin_logs
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
            GROUP BY DATE(created_at)
            ORDER BY date ASC
        ");
        $stmt->execute([$days]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 🔥 Retourner directement les résultats, même s'ils sont vides
        return $results;
        
    } catch (PDOException $e) {
        error_log("Erreur getActivityChart: " . $e->getMessage());
        return [];
    }
}


/**
 * Générer des données de test pour le graphique
 */
private function generateTestChartData($days = 7) {
    $data = [];
    $today = new DateTime();
    
    for ($i = $days - 1; $i >= 0; $i--) {
        $date = new DateTime();
        $date->modify("-$i days");
        $data[] = [
            'date' => $date->format('Y-m-d'),
            'total' => rand(2, 15),
            'critical' => rand(0, 4)
        ];
    }
    
    return $data;
}
    /**
     * Récupérer un log par son ID
     */
    public function getLogById($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM admin_logs WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getLogById: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Supprimer les anciens logs
     */
    public function deleteOldLogs($days) {
        try {
            $stmt = $this->pdo->prepare("
                DELETE FROM admin_logs 
                WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)
                AND (level IS NULL OR level != 'critical')
            ");
            $stmt->execute([$days]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("Erreur deleteOldLogs: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Exporter en CSV
     */
    public function exportCSV($filters = []) {
        $logs = $this->getLogs($filters, 10000, 0);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=logs_audit_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, [
            'ID', 'Date', 'Admin', 'Email', 'Action', 'Description', 
            'Target ID', 'Entité', 'ID Entité', 'IP', 'Niveau', 'Statut'
        ]);
        
        foreach ($logs as $log) {
            fputcsv($output, [
                $log['id'],
                $log['created_at'],
                $log['admin_name'] ?? '-',
                $log['admin_email'] ?? '-',
                $log['action'],
                $log['action_description'] ?? $log['details'] ?? '-',
                $log['target_user_id'] ?? '-',
                $log['entity_type'] ?? '-',
                $log['entity_id'] ?? '-',
                $log['ip_address'] ?? '-',
                $log['level'] ?? 'info',
                $log['status'] ?? 'success'
            ]);
        }
        
        fclose($output);
        exit();
    }

/**
 * Récupérer les types d'actions distincts
 */
public function getActionTypes() {
    try {
        $stmt = $this->pdo->query("
            SELECT DISTINCT action 
            FROM admin_logs 
            WHERE action IS NOT NULL AND action != ''
            ORDER BY action ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        error_log("Erreur getActionTypes: " . $e->getMessage());
        return [];
    }
}


/**
 * Récupérer les administrateurs depuis la table admin
 */
public function getAdmins() {
    try {
        $stmt = $this->pdo->query("
            SELECT id_gestion, nom, email, role 
            FROM admin 
            ORDER BY nom ASC
        ");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 🔥 DEBUG - Vérifier les résultats
        error_log("getAdmins résultats: " . print_r($results, true));
        
        return $results;
    } catch (PDOException $e) {
        error_log("Erreur getAdmins: " . $e->getMessage());
        return [];
    }
}


/**
 * Récupérer les logs avec filtres
 */
public function getLogs($filters = [], $limit = 25, $offset = 0) {
    try {
        $sql = "SELECT * FROM admin_logs WHERE 1=1";
        $params = [];
        
        // RECHERCHE TEXTUELLE
        if (!empty($filters['search'])) {
            $sql .= " AND (action LIKE :search 
                          OR action_description LIKE :search 
                          OR admin_email LIKE :search 
                          OR admin_name LIKE :search 
                          OR ip_address LIKE :search
                          OR details LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        // TYPE D'ACTION
        if (!empty($filters['action'])) {
            $sql .= " AND action = :action";
            $params[':action'] = $filters['action'];
        }
        
        // 🔥 ADMINISTRATEUR - CORRIGÉ
        if (!empty($filters['admin_id']) && is_numeric($filters['admin_id'])) {
            $sql .= " AND admin_id = :admin_id";
            $params[':admin_id'] = (int)$filters['admin_id'];
        }
        
        // NIVEAU
        if (!empty($filters['level'])) {
            $sql .= " AND level = :level";
            $params[':level'] = $filters['level'];
        }
        
        // PÉRIODE
        if (!empty($filters['period'])) {
            switch($filters['period']) {
                case 'today':
                    $sql .= " AND DATE(created_at) = CURDATE()";
                    break;
                case '7days':
                    $sql .= " AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
                    break;
                case '30days':
                    $sql .= " AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
                    break;
                case 'month':
                    $sql .= " AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
                    break;
            }
        }
        
        // DATES PERSONNALISÉES
        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(created_at) >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(created_at) <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $params[':limit'] = (int)$limit;
        $params[':offset'] = (int)$offset;
        
        $stmt = $this->pdo->prepare($sql);
        
        foreach ($params as $key => $value) {
            if ($key === ':limit' || $key === ':offset') {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value);
            }
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        error_log("Erreur getLogs: " . $e->getMessage());
        return [];
    }
}
/**
 * Compter les logs avec filtres - Version avec jointure
 */
public function countLogs($filters = []) {
    try {
        $sql = "SELECT COUNT(*) as total FROM admin_logs al WHERE 1=1";
        $params = [];
        
        // RECHERCHE TEXTUELLE
        if (!empty($filters['search'])) {
            $sql .= " AND (al.action LIKE :search 
                          OR al.action_description LIKE :search 
                          OR al.admin_email LIKE :search 
                          OR al.admin_name LIKE :search 
                          OR al.ip_address LIKE :search
                          OR al.details LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        // TYPE D'ACTION
        if (!empty($filters['action'])) {
            $sql .= " AND al.action = :action";
            $params[':action'] = $filters['action'];
        }
        
        // ADMINISTRATEUR
        if (!empty($filters['admin_id'])) {
            $sql .= " AND al.admin_id = :admin_id";
            $params[':admin_id'] = (int)$filters['admin_id'];
        }
        
        // NIVEAU
        if (!empty($filters['level'])) {
            $sql .= " AND al.level = :level";
            $params[':level'] = $filters['level'];
        }
        
        // STATUT
        if (!empty($filters['status'])) {
            $sql .= " AND al.status = :status";
            $params[':status'] = $filters['status'];
        }
        
        // PÉRIODE
        if (!empty($filters['period'])) {
            switch($filters['period']) {
                case 'today':
                    $sql .= " AND DATE(al.created_at) = CURDATE()";
                    break;
                case '7days':
                    $sql .= " AND al.created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
                    break;
                case '30days':
                    $sql .= " AND al.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
                    break;
                case 'month':
                    $sql .= " AND MONTH(al.created_at) = MONTH(CURDATE()) AND YEAR(al.created_at) = YEAR(CURDATE())";
                    break;
            }
        }
        
        // DATES PERSONNALISÉES
        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(al.created_at) >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(al.created_at) <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }
        
        $stmt = $this->pdo->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
        
    } catch (PDOException $e) {
        error_log("Erreur countLogs: " . $e->getMessage());
        return 0;
    }
}

}