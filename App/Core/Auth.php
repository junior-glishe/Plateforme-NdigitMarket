<?php

declare(strict_types=1);

namespace App\Core;
require_once __DIR__ . '/Permission.php';
class Auth
{
    public static function check(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['user_id']) ||
            isset($_SESSION['user']['id']) ||
            isset($_SESSION['admin']);
    }

    public static function user(): ?array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }

        if (isset($_SESSION['user_id'])) {
            return [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'] ?? '',
                'email' => $_SESSION['email'] ?? '',
                'role' => $_SESSION['user_role'] ?? '',
            ];
        }

        return null;
    }

    public static function role(): ?string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $role = $_SESSION['user_role'] ?? ($_SESSION['user']['role'] ?? null);

        return $role ? Permission::normalizeRole($role) : null;
    }

    public static function hasPermission(string $permission): bool
    {
        if (!self::check()) {
            return false;
        }

        $role = self::role();

        if (!$role) {
            return false;
        }

        return Permission::has($permission, $role);
    }

    public static function can(string $permission): bool
    {
        return self::hasPermission($permission);
    }

    public static function hasAnyPermission(array $permissions): bool
    {
        if (!self::check()) {
            return false;
        }

        $role = self::role();

        if (!$role) {
            return false;
        }

        return Permission::hasAny($role, $permissions);
    }

    public static function hasAllPermissions(array $permissions): bool
    {
        if (!self::check()) {
            return false;
        }

        $role = self::role();

        if (!$role) {
            return false;
        }

        return Permission::hasAll($role, $permissions);
    }

    public static function isSuperAdmin(): bool
    {
        return self::role() === 'Super Admin';
    }

    public static function isAdmin(): bool
    {
        return self::role() === 'Admin';
    }

    public static function isModerator(): bool
    {
        return self::role() === 'Modérateur';
    }

    public static function isSupport(): bool
    {
        return self::role() === 'Support';
    }

    public static function permissions(): array
    {
        if (!self::check()) {
            return [];
        }

        $role = self::role();

        if (!$role) {
            return [];
        }

        return Permission::getForRole($role);
    }

    public static function authorize(string $permission, int $httpCode = 403): void
    {
        if (!self::hasPermission($permission)) {
            http_response_code($httpCode);

            if (self::isAjax()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Accès refusé. Vous n\'avez pas les permissions nécessaires.',
                    'permission' => $permission
                ]);
                exit;
            }

            require_once __DIR__ . '/../Views/errors/403.php';
            exit;
        }
    }

    private static function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    public static function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }
}
