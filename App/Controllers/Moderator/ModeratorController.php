<?php
/**
 * NDIGITMARKET - ModeratorController
 *
 * Squelette minimal : ce contrôleur n'existait pas du tout, ce qui causait
 * un 404 systématique après la connexion d'un compte "moderator" (redirigé
 * par AuthController vers route=moderator/dashboard).
 *
 * À compléter avec les vraies fonctionnalités de modération
 * (produits en attente, avis à valider, etc.), sur le même modèle
 * que App\Controllers\Admin\Traits.
 */

declare(strict_types=1);

namespace App\Controllers\Moderator;

require_once __DIR__ . '/../../../config/database.php';

class ModeratorController
{
    public function dashboard(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userName = $_SESSION['user_name'] ?? 'Modérateur';
        echo '<h1>Tableau de bord Modérateur</h1>';
        echo '<p>Bienvenue, ' . htmlspecialchars($userName) . '.</p>';
        echo '<p>Cette section est en construction.</p>';
    }
}
