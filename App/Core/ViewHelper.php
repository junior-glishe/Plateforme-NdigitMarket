<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Helper pour les vues - Gestion des permissions dans les templates
 * 
 * Ce fichier fournit des fonctions helper pour afficher/masquer
 * des éléments en fonction des permissions de l'utilisateur connecté.
 * 
 * Usage dans les vues:
 * <?php if (can('users.edit')): ?>
 *     <button>Modifier</button>
 * <?php endif; ?>
 */

// Démarrer la session si ce n'est pas déjà fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Vérifie si l'utilisateur a une permission
 * 
 * @param string $permission Le nom de la permission
 * @return bool
 */
function can(string $permission): bool
{
    return Auth::hasPermission($permission);
}

/**
 * Vérifie si l'utilisateur a au moins une des permissions
 * 
 * @param array $permissions Liste des permissions
 * @return bool
 */
function canAny(array $permissions): bool
{
    return Auth::hasAnyPermission($permissions);
}

/**
 * Vérifie si l'utilisateur a toutes les permissions
 * 
 * @param array $permissions Liste des permissions
 * @return bool
 */
function canAll(array $permissions): bool
{
    return Auth::hasAllPermissions($permissions);
}

/**
 * Vérifie si l'utilisateur est Super Admin
 * 
 * @return bool
 */
function isSuperAdmin(): bool
{
    return Auth::isSuperAdmin();
}

/**
 * Vérifie si l'utilisateur est Admin
 * 
 * @return bool
 */
function isAdmin(): bool
{
    return Auth::isAdmin();
}

/**
 * Vérifie si l'utilisateur est Modérateur
 * 
 * @return bool
 */
function isModerator(): bool
{
    return Auth::isModerator();
}

/**
 * Vérifie si l'utilisateur est Support
 * 
 * @return bool
 */
function isSupport(): bool
{
    return Auth::isSupport();
}

/**
 * Récupère le rôle de l'utilisateur connecté
 * 
 * @return string|null
 */
function currentRole(): ?string
{
    return Auth::role();
}

/**
 * Récupère l'utilisateur connecté
 * 
 * @return array|null
 */
function currentUser(): ?array
{
    return Auth::user();
}

/**
 * Affiche un élément seulement si l'utilisateur a la permission
 * 
 * @param string $permission La permission requise
 * @param callable $callback Fonction à exécuter si autorisé
 */
function renderIf(string $permission, callable $callback): void
{
    if (can($permission)) {
        $callback();
    }
}

/**
 * Génère un attribut disabled si l'utilisateur n'a pas la permission
 * 
 * @param string $permission La permission requise
 * @return string
 */
function disabledIf(string $permission): string
{
    return can($permission) ? '' : 'disabled';
}

/**
 * Génère un attribut style="display:none" si l'utilisateur n'a pas la permission
 * 
 * @param string $permission La permission requise
 * @return string
 */
function hideIf(string $permission): string
{
    return can($permission) ? '' : 'style="display:none;"';
}

/**
 * Génère une classe CSS pour griser un élément si pas de permission
 * 
 * @param string $permission La permission requise
 * @param string $disabledClass Classe CSS à ajouter (défaut: 'disabled')
 * @return string
 */
function disabledClass(string $permission, string $disabledClass = 'disabled'): string
{
    return can($permission) ? '' : $disabledClass;
}

/**
 * Retourne la liste des permissions de l'utilisateur connecté
 * 
 * @return array
 */
function userPermissions(): array
{
    return Auth::permissions();
}
