<?php
/**
 * NDIGITMARKET - Admin trait : Helpers
 *
 * Utilitaires transverses utilisés par tous les traits Admin :
 *  - checkAuth      : verrou d'accès (redirige vers la page de login)
 *  - jsonResponse   : réponse JSON standardisée (AJAX)
 *  - formatCurrency : formatage FCFA
 *  - formatDate     : formatage date FR
 *  - normalizeFloat : nettoyage des prix stockés en varchar
 *  - logAction      : écriture dans `admin_logs`
 *  - sortParams     : mapping colonne + ordre sécurisé
 */

namespace App\Controllers\Admin\Traits;

trait AdminHelpersTrait
{
    private function formatCurrency(float $amount): string
    {
        return number_format($amount, 0, ',', ' ') . ' FCFA';
    }

    private function normalizeFloat($value): float
    {
        if ($value === null) return 0.0;
        if (is_numeric($value)) return (float) $value;
        $clean = str_replace([' ', "\u{00A0}", ',', '€'], ['', '', '.', ''], (string) $value);
        return (float) preg_replace('/[^0-9.\-]/', '', $clean);
    }

    /**
     * Vérifie que l'admin est connecté.
     * BUG FIX : la version précédente redirigeait vers /admin/dashboard, ce qui
     * créait une boucle infinie avec AdminMiddleware. On renvoie maintenant
     * l'utilisateur vers la page de connexion (BASE_URL . '/').
     */
    private function checkAuth(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $isAdmin = !empty($_SESSION['admin'])
            || (($_SESSION['user_role'] ?? '') === 'admin')
            || (($_SESSION['user']['role'] ?? '') === 'admin');

        if (!$isAdmin) {
            // Requête AJAX -> réponse JSON 401, sinon redirection
            if ($this->isAjax()) {
                $this->jsonResponse(['success' => false, 'message' => 'Session expirée'], 401);
            }
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }

    private function isAjax(): bool
    {
        return (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest')
            || (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false);
    }

    private function jsonResponse(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    private function formatDate(?string $date, string $format = 'd/m/Y'): string
    {
        if (!$date) return 'N/A';
        $ts = strtotime($date);
        return $ts ? date($format, $ts) : 'N/A';
    }

    /**
     * Récupère un couple (colonne SQL, sens ASC/DESC) sécurisé depuis $_GET.
     *
     * @param array<string,string> $map  Ex: ['nom' => 'u.nom', 'email' => 'u.email']
     * @param string $default            Clé par défaut (doit exister dans $map)
     * @return array{0:string,1:string}
     */
    private function sortParams(array $map, string $default): array
    {
        $key = $_GET['sort'] ?? $default;
        if (!isset($map[$key])) $key = $default;
        $order = strtoupper((string) ($_GET['order'] ?? 'DESC')) === 'ASC' ? 'ASC' : 'DESC';
        return [$map[$key], $order];
    }

    /**
     * Journalisation dans admin_logs. La table est créée automatiquement
     * si elle n'existe pas (idempotent, sans supprimer les données).
     */
    private function logAction(string $action, int $userId, string $details = '', string $status = 'success'): void
    {
        try {
            $db = \Database::getConnection();
            $adminId = $_SESSION['admin_id'] ?? $_SESSION['user_id'] ?? null;

            static $tableChecked = false;
            if (!$tableChecked) {
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
                    INDEX idx_admin (admin_id),
                    INDEX idx_action (action),
                    INDEX idx_target (target_user_id),
                    INDEX idx_date (created_at)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
                $tableChecked = true;
            }

            $stmt = $db->prepare("INSERT INTO admin_logs
                (admin_id, action, target_user_id, details, status, ip_address, user_agent)
                VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $adminId,
                $action,
                $userId ?: null,
                $details,
                $status,
                $_SERVER['REMOTE_ADDR'] ?? null,
                $_SERVER['HTTP_USER_AGENT'] ?? null,
            ]);
        } catch (\Exception $e) {
            error_log("[admin_logs] " . $e->getMessage());
        }
    }
}
