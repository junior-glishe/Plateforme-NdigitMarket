<?php
/**
 * NDIGITMARKET - Admin trait: Settings
 *
 * Fait partie du refactor du monolithique AdminController.
 * Chaque trait regroupe les méthodes d'un même domaine metier.
 * Le comportement des méthodes est identique à la version d'origine.
 */

namespace App\Controllers\Admin\Traits;

trait SettingsTrait
{
    public function settings()
    {
        $this->checkAuth();
        $currentPage = 'parametres-systeme';
        require_once __DIR__ . '/../../../Views/admin/parametres-systeme.php';
    }
}
