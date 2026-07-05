<?php
namespace App\Middleware;

/**
 * Vérifie que l'utilisateur connecté est un administrateur.
 */
class AdminMiddleware
{
    public function handle(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $isAdmin = !empty($_SESSION['admin'])
            || (($_SESSION['user_role'] ?? '') === 'admin')
            || (($_SESSION['user']['role'] ?? '') === 'admin');

        if (!$isAdmin) {
            $base = defined('BASE_URL') ? BASE_URL : '';
            header('Location: ' . $base . '/');
            exit;
        }
    }
}
