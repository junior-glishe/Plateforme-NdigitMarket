<?php
namespace App\Middleware;

/**
 * Vérifie qu'un utilisateur est connecté.
 * Sinon, redirige vers la page de connexion.
 */
class AuthMiddleware
{
    public function handle(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (empty($_SESSION['user']) && empty($_SESSION['admin'])) {
            $base = defined('BASE_URL') ? BASE_URL : '';
            header('Location: ' . $base . '/');
            exit;
        }
    }
}
