<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;

/**
 * Vérifie que l'utilisateur a les permissions de modérateur
 * Accepte: Modérateur, Admin, Super Admin
 */
class ModeratorMiddleware
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

        // Vérifier que l'utilisateur a au moins une permission de modérateur
        $moderatorPermissions = [
            'vendors.view',
            'vendors.validate',
            'vendors.approve',
            'vendors.reject',
            'products.view',
            'products.validate',
            'products.approve',
            'products.reject',
            'reviews.view',
            'reports.view'
        ];

        if (!Auth::hasAnyPermission($moderatorPermissions)) {
            $base = defined('BASE_URL') ? BASE_URL : '';
            header('Location: ' . $base . '/');
            exit;
        }
    }
}
