<?php

/**
 * NDIGITMARKET - Admin trait: Settings
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
