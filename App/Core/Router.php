<?php
declare(strict_types=1);

namespace App\Core;

use App\Controllers\Admin\AdminController;
use App\Controllers\Auth\AuthController;
use App\Controllers\Moderator\ModeratorController;
use App\Controllers\Support\SupportController;
use App\Middleware\AdminMiddleware;
use App\Middleware\GuestMiddleware;
use App\Middleware\ModeratorMiddleware;
use App\Middleware\SupportMiddleware;

class Router
{
    /**
     * Actions admin qui reçoivent un id numérique en 3ème segment
     * (ex: /admin/updateUser/42).
     */
// Dans App/Core/Router.php
const ADMIN_ACTIONS_WITH_ID = [
    // ... autres actions ...
    'getVendorDetails',
    'getVendorRequestDetails',
    'approveVendor',
    'rejectVendor',
    'suspendVendor',
    'deleteVendor',
    'updateVendor',
    'markVendorPayment'
    

];

    public function dispatch(string $route): void
    {
        $segments = $route === '' ? [] : explode('/', trim($route, '/'));
        $first = $segments[0] ?? '';

        try {
            switch ($first) {
                case '':
                    (new GuestMiddleware())->handle();
                    require dirname(__DIR__, 2) . '/public/index.php';
                    return;

                case 'login':
                    (new AuthController(\Database::getConnection()))->login();
                    return;

                case 'logout':
                    (new AuthController(\Database::getConnection()))->logout();
                    return;

                case 'admin':
                    (new AdminMiddleware())->handle();
                    $this->dispatchAdmin(new AdminController(), $segments);
                    return;

                case 'moderator':
                    (new ModeratorMiddleware())->handle();
                    $this->dispatchSimple(new ModeratorController(), $segments);
                    return;

                case 'support':
                    (new SupportMiddleware())->handle();
                    $this->dispatchSimple(new SupportController(), $segments);
                    return;

                default:
                    http_response_code(404);
                    echo '<h1>404 - Page introuvable</h1>';
                    return;
            }
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    private function dispatchAdmin(AdminController $controller, array $segments): void
    {
        $action = $segments[1] ?? 'dashboard';
        $id     = isset($segments[2]) ? (int) $segments[2] : 0;

        // 1) Méthode directe du contrôleur (dashboard, users, createUser, updateUser/{id}, ...)
        if (method_exists($controller, $action)) {
            if (in_array($action, self::ADMIN_ACTIONS_WITH_ID, true)) {
                $controller->$action($id);
            } else {
                $controller->$action();
            }
            return;
        }

        // 2) Dispatcher de page (slugs gestion-utilisateurs, categorie, ...)
        if (method_exists($controller, 'page')) {
            $controller->page($action);
            return;
        }

        http_response_code(404);
        echo '<h1>404 - Action admin introuvable</h1>';
    }

    private function dispatchSimple(object $controller, array $segments): void
    {
        $action = $segments[1] ?? 'dashboard';
        if (!method_exists($controller, $action)) {
            http_response_code(404);
            echo '<h1>404 - Action introuvable</h1>';
            return;
        }
        $controller->$action();
    }

    private function handleError(\Throwable $e): void
    {
        if (!empty($GLOBALS['APP_DEBUG'])) {
            echo '<pre>' . htmlspecialchars($e->getMessage() . "\n" . $e->getTraceAsString()) . '</pre>';
            return;
        }
        error_log('[ROUTER] ' . $e->getMessage());
        http_response_code(500);
        echo 'Erreur interne du serveur.';
    }
}


