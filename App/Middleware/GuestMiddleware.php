<?php
namespace App\Middleware;

/**
 * Redirige les utilisateurs déjà connectés vers leur dashboard.
 */
class GuestMiddleware
{
    public function handle(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!empty($_SESSION['admin']) || (($_SESSION['user']['role'] ?? '') === 'admin')) {
            $base = defined('BASE_URL') ? BASE_URL : '';
            header('Location: ' . $base . '/admin/dashboard');
            exit;
        }
    }
}
