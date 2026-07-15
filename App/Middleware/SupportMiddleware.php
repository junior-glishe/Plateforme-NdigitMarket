<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;

/**
 * Vérifie que l'utilisateur a les permissions de support
 * Accepte: Support, Admin, Super Admin
 */
class SupportMiddleware
{
    public function handle(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!Auth::check()) {
            $base = defined('BASE_URL') ? BASE_URL : '';
            header('Location: ' . $base . '/');
            exit;
        }

        // Vérifier que l'utilisateur a au moins une permission de support
        $supportPermissions = [
            'users.view',
            'vendors.view',
            'orders.view',
            'products.view'
        ];

        if (!Auth::hasAnyPermission($supportPermissions)) {
            $base = defined('BASE_URL') ? BASE_URL : '';
            header('Location: ' . $base . '/');
            exit;
        }
    }
}
