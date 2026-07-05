<?php
/**
 * NDIGITMARKET - Admin trait: Contents
 *
 * Fait partie du refactor du monolithique AdminController.
 * Chaque trait regroupe les méthodes d'un même domaine metier.
 * Le comportement des méthodes est identique à la version d'origine.
 */

namespace App\Controllers\Admin\Traits;

trait ContentsTrait
{
    public function contents()
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        try {
            $campaigns = $db->query("
                SELECT * FROM email_campaigns 
                ORDER BY id DESC
            ")->fetchAll();
        } catch (\Exception $e) {
            $campaigns = [];
        }

        $totalCampaigns = count($campaigns);
        $currentPage = 'contenus';
        require_once __DIR__ . '/../../../Views/admin/contenus.php';
    }
}
