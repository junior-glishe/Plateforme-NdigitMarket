<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;

/**
 * Vérifie que l'utilisateur connecté est un administrateur.
 * Accepte tous les rôles: Super Admin, Admin, Modérateur, Support
 */
class AdminMiddleware
{
    public function handle(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!Auth::check()) {
            $base = defined('BASE_URL') ? BASE_URL : '';
            header('Location: ' . $base . '/');
            exit;
        }

        // Vérifier que l'utilisateur a au moins la permission de base
        if (!Auth::hasPermission('dashboard.view')) {
            $base = defined('BASE_URL') ? BASE_URL : '';
            header('Location: ' . $base . '/');
            exit;
        }
    }
}
