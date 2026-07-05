<?php
/**
 * NDIGITMARKET - Admin trait: PageDispatch
 *
 * Fait partie du refactor du monolithique AdminController.
 * Chaque trait regroupe les méthodes d'un même domaine metier.
 * Le comportement des méthodes est identique à la version d'origine.
 */

namespace App\Controllers\Admin\Traits;

trait PageDispatchTrait
{
    public function page(string $pageName)
    {
        $this->checkAuth();

        $allowed = [
            'dashboard'               => ['view' => 'dashboard.php',                'method' => 'dashboard'],
            'gestion-utilisateurs'    => ['view' => 'gestion-utilisateurs.php',     'method' => 'users'],
            'gestion-vendeur'         => ['view' => 'gestion-vendeur.php',          'method' => 'vendors'],
            'gestion-produits'        => ['view' => 'gestion-produits.php',         'method' => 'products'],
            'gestion-commande'        => ['view' => 'gestion-commande.php',         'method' => 'orders'],
            'financieres-commission'  => ['view' => 'financieres-commission.php',   'method' => 'commissions'],
            'categorie'               => ['view' => 'categorie.php',                'method' => 'categories'],
            'contenus'                => ['view' => 'contenus.php',                 'method' => 'contents'],
            'notifications'           => ['view' => 'notifications.php',            'method' => 'notifications'],
            'rapport-stat'            => ['view' => 'rapport-stat.php',             'method' => 'reports'],
            'avis-commentaires'       => ['view' => 'avis-commentaires.php',        'method' => 'reviews'],
            'parametres-systeme'      => ['view' => 'parametres-systeme.php',       'method' => 'settings'],
            'logs-audit'              => ['view' => 'logs-audit.php',               'method' => 'logs'],
        ];

        if (!isset($allowed[$pageName])) {
            http_response_code(404);
            echo 'Page introuvable';
            exit;
        }

        $config = $allowed[$pageName];

        if (method_exists($this, $config['method'])) {
            $this->{$config['method']}();
            return;
        }

        $currentPage = $pageName;
        require_once __DIR__ . '/../../../Views/admin/' . $config['view'];
    }
}
