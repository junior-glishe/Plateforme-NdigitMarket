<?php
namespace App\Middleware;

/**
 * Vérifie que l'utilisateur possède l'un des rôles autorisés.
 */
class RoleMiddleware
{
    /** @param string[] $roles */
    public function handle(array $roles = []): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $role = $_SESSION['user_role'] ?? ($_SESSION['user']['role'] ?? null);

        if ($role === null || ($roles && !in_array($role, $roles, true))) {
            http_response_code(403);
            echo 'Accès refusé.';
            exit;
        }
    }
}
