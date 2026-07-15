<?php
/**
 * NDIGITMARKET - SupportController
 *
 * Squelette minimal : ce contrôleur n'existait pas du tout, ce qui causait
 * un 404 systématique après la connexion d'un compte "support" (redirigé
 * par AuthController vers route=support/dashboard).
 *
 * À compléter avec les vraies fonctionnalités support
 * (tickets, réclamations, etc.).
 */

declare(strict_types=1);

namespace App\Controllers\Support;

require_once __DIR__ . '/../../../config/database.php';

class SupportController
{
    public function dashboard(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userName = $_SESSION['user_name'] ?? 'Support';
        echo '<h1>Tableau de bord Support</h1>';
        echo '<p>Bienvenue, ' . htmlspecialchars($userName) . '.</p>';
        echo '<p>Cette section est en construction.</p>';
    }
}
