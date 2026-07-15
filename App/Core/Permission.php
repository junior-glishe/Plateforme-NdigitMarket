<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Système de permissions RBAC (Role-Based Access Control)
 * 
 * Définit toutes les permissions disponibles dans le système
 * et fournit des méthodes pour vérifier les droits d'accès.
 */
class Permission
{
    /**
     * Liste de toutes les permissions disponibles
     */
    public const ALL = [
        // Dashboard
        'dashboard.view',

        // Gestion des administrateurs
        'admins.view',
        'admins.create',
        'admins.edit',
        'admins.delete',

        // Gestion des rôles et permissions
        'roles.view',
        'roles.create',
        'roles.edit',
        'roles.delete',
        'permissions.manage',

        // Gestion des utilisateurs
        'users.view',
        'users.create',
        'users.edit',
        'users.delete',
        'users.validate',

        // Gestion des vendeurs
        'vendors.view',
        'vendors.create',
        'vendors.edit',
        'vendors.delete',
        'vendors.validate',
        'vendors.approve',
        'vendors.reject',
        'vendors.suspend',

        // Gestion des produits
        'products.view',
        'products.create',
        'products.edit',
        'products.delete',
        'products.validate',
        'products.approve',
        'products.reject',

        // Gestion des catégories
        'categories.view',
        'categories.create',
        'categories.edit',
        'categories.delete',

        // Gestion des commandes
        'orders.view',
        'orders.create',
        'orders.edit',
        'orders.delete',
        'orders.validate',

        // Gestion des paiements
        'payments.view',
        'payments.create',
        'payments.edit',
        'payments.delete',
        'payments.validate',

        // Gestion financière
        'finance.view',
        'finance.manage',
        'commissions.view',
        'commissions.manage',
        'withdrawals.view',
        'withdrawals.manage',

        // Gestion des publicités
        'advertisements.view',
        'advertisements.create',
        'advertisements.edit',
        'advertisements.delete',

        // Gestion des bannières
        'banners.view',
        'banners.create',
        'banners.edit',
        'banners.delete',

        // Gestion des avis
        'reviews.view',
        'reviews.edit',
        'reviews.delete',
        'reviews.validate',

        // Gestion des signalements
        'reports.view',
        'reports.manage',

        // Paramètres système
        'settings.view',
        'settings.edit',
        'settings.critical',

        // Logs
        'logs.view',
        'logs.export',

        // Sauvegardes
        'backups.view',
        'backups.create',
        'backups.restore',
        'backups.delete',

        // Statistiques
        'statistics.view',
        'statistics.export',

        // Notifications
        'notifications.view',
        'notifications.send',
        'notifications.manage',

        // Profil
        'profile.view',
        'profile.edit',
    ];

    /**
     * Permissions par rôle
     */
    public const ROLE_PERMISSIONS = [
        'Super Admin' => [
            // Niveau 1 - Accès complet à tous les modules
            'dashboard.view',
            'admins.view',
            'admins.create',
            'admins.edit',
            'admins.delete',
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'permissions.manage',
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.validate',
            'vendors.view',
            'vendors.create',
            'vendors.edit',
            'vendors.delete',
            'vendors.validate',
            'vendors.approve',
            'vendors.reject',
            'vendors.suspend',
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',
            'products.validate',
            'products.approve',
            'products.reject',
            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',
            'orders.view',
            'orders.create',
            'orders.edit',
            'orders.delete',
            'orders.validate',
            'payments.view',
            'payments.create',
            'payments.edit',
            'payments.delete',
            'payments.validate',
            'finance.view',
            'finance.manage',
            'commissions.view',
            'commissions.manage',
            'withdrawals.view',
            'withdrawals.manage',
            'advertisements.view',
            'advertisements.create',
            'advertisements.edit',
            'advertisements.delete',
            'banners.view',
            'banners.create',
            'banners.edit',
            'banners.delete',
            'reviews.view',
            'reviews.edit',
            'reviews.delete',
            'reviews.validate',
            'reports.view',
            'reports.manage',
            'settings.view',
            'settings.edit',
            'settings.critical',
            'logs.view',
            'logs.export',
            'backups.view',
            'backups.create',
            'backups.restore',
            'backups.delete',
            'statistics.view',
            'statistics.export',
            'notifications.view',
            'notifications.send',
            'notifications.manage',
            'profile.view',
            'profile.edit',
        ],

        'Admin' => [
            // Niveau 2 - Accès étendu (sauf paramètres critiques et rôles)
            'dashboard.view',
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.validate',
            'vendors.view',
            'vendors.create',
            'vendors.edit',
            'vendors.delete',
            'vendors.validate',
            'vendors.approve',
            'vendors.reject',
            'vendors.suspend',
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',
            'products.validate',
            'products.approve',
            'products.reject',
            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',
            'orders.view',
            'orders.create',
            'orders.edit',
            'orders.delete',
            'orders.validate',
            'payments.view',
            'payments.create',
            'payments.edit',
            'payments.delete',
            'payments.validate',
            'finance.view',
            'finance.manage',
            'commissions.view',
            'commissions.manage',
            'withdrawals.view',
            'withdrawals.manage',
            'advertisements.view',
            'advertisements.create',
            'advertisements.edit',
            'advertisements.delete',
            'banners.view',
            'banners.create',
            'banners.edit',
            'banners.delete',
            'reviews.view',
            'reviews.edit',
            'reviews.delete',
            'reviews.validate',
            'reports.view',
            'reports.manage',
            'settings.view',
            'settings.edit',
            'statistics.view',
            'statistics.export',
            'logs.view',
            'notifications.view',
            'notifications.send',
            'profile.view',
            'profile.edit',
        ],

        'Modérateur' => [
            // Niveau 3 - Accès limité à la modération
            'dashboard.view',
            'users.view',
            'vendors.view',
            'vendors.validate',
            'vendors.approve',
            'vendors.reject',
            'products.view',
            'products.validate',
            'products.approve',
            'products.reject',
            'reviews.view',
            'reviews.edit',
            'reviews.delete',
            'reviews.validate',
            'reports.view',
            'reports.manage',
            'profile.view',
            'profile.edit',
        ],

        'Support' => [
            // Niveau 4 - Accès lecture uniquement
            'dashboard.view',
            'users.view',
            'vendors.view',
            'orders.view',
            'products.view',
            'profile.view',
        ],
    ];

    /**
     * Vérifie si une permission est accordée pour un rôle donné
     */
    public static function has(string $permission, string $role): bool
    {
        $permissions = self::ROLE_PERMISSIONS[$role] ?? [];
        return in_array($permission, $permissions, true);
    }

    /**
     * Récupère toutes les permissions d'un rôle
     */
    public static function getForRole(string $role): array
    {
        return self::ROLE_PERMISSIONS[$role] ?? [];
    }

    /**
     * Vérifie si un rôle a au moins une des permissions données
     */
    public static function hasAny(string $role, array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (self::has($permission, $role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Vérifie si un rôle a toutes les permissions données
     */
    public static function hasAll(string $role, array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!self::has($permission, $role)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Normalise un nom de rôle (gère les variations de casse)
     */
    public static function normalizeRole(string $role): string
    {
        $role = trim($role);
        $role = ucfirst(strtolower($role));

        // Mapping des variations courantes
        $map = [
            'super admin' => 'Super Admin',
            'superadmin' => 'Super Admin',
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'administrateur' => 'Admin',
            'administrator' => 'Admin',
            'moderator' => 'Modérateur',
            'moderateur' => 'Modérateur',
            'modération' => 'Modérateur',
            'support' => 'Support',
        ];

        return $map[strtolower($role)] ?? $role;
    }
}
