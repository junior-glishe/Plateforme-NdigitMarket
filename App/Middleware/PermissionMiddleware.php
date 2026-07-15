<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;

/**
 * Middleware de vérification des permissions RBAC
 * 
 * Vérifie que l'utilisateur connecté possède la permission requise
 * pour accéder à une ressource.
 */
class PermissionMiddleware
{
    /**
     * Vérifie une permission spécifique
     * 
     * @param string $permission La permission requise (ex: 'users.edit')
     */
    public function handle(string $permission): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!Auth::check()) {
            http_response_code(401);
            $this->showError('Vous devez être connecté pour accéder à cette page.');
            exit;
        }

        if (!Auth::hasPermission($permission)) {
            http_response_code(403);
            $this->showError('Accès refusé. Vous n\'avez pas les permissions nécessaires.');
            exit;
        }
    }

    /**
     * Vérifie plusieurs permissions (au moins une requise)
     * 
     * @param array $permissions Liste des permissions
     */
    public function handleAny(array $permissions): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!Auth::check()) {
            http_response_code(401);
            $this->showError('Vous devez être connecté pour accéder à cette page.');
            exit;
        }

        if (!Auth::hasAnyPermission($permissions)) {
            http_response_code(403);
            $this->showError('Accès refusé. Vous n\'avez pas les permissions nécessaires.');
            exit;
        }
    }

    /**
     * Vérifie plusieurs permissions (toutes requises)
     * 
     * @param array $permissions Liste des permissions
     */
    public function handleAll(array $permissions): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!Auth::check()) {
            http_response_code(401);
            $this->showError('Vous devez être connecté pour accéder à cette page.');
            exit;
        }

        if (!Auth::hasAllPermissions($permissions)) {
            http_response_code(403);
            $this->showError('Accès refusé. Vous n\'avez pas les permissions nécessaires.');
            exit;
        }
    }

    /**
     * Affiche la page d'erreur 403
     */
    private function showError(string $message): void
    {
        // Si c'est une requête AJAX, retourner du JSON
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $message
            ]);
            exit;
        }

        // Sinon, afficher la page d'erreur HTML
        require_once __DIR__ . '/../Views/errors/403.php';
        exit;
    }
}