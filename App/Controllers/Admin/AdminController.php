<?php
/**
 * NDIGITMARKET - AdminController (façade)
 *
 * Le controller admin d'origine (1300+ lignes) a été découpé par domaine
 * en traits situés dans App\Controllers\Admin\Traits.
 *
 * L'API publique (noms de méthodes) est strictement identique — le routeur
 * de public/index.php continue de dispatcher via method_exists() sans
 * aucune modification. Ce fichier ne fait que composer les responsabilités.
 *
 * Domaines :
 *   - AdminHelpers  : utilitaires internes (auth, JSON, formats, logs)
 *   - Dashboard     : tableau de bord principal
 *   - Users         : gestion des utilisateurs
 *   - Vendors       : gestion des vendeurs
 *   - Products      : gestion des produits
 *   - Orders        : gestion des commandes
 *   - Commissions   : commissions et finances
 *   - Categories    : gestion des catégories
 *   - Reviews       : modération des avis
 *   - Notifications : notifications système
 *   - Reports       : rapports et statistiques
 *   - Settings      : paramètres système
 *   - Logs          : logs d'audit
 *   - Contents      : gestion des contenus
 *   - Withdrawals   : demandes de retrait
 *   - BulkActions   : actions groupées
 *   - PageDispatch  : dispatcher générique page($pageName)
 */

namespace App\Controllers\Admin;

require_once __DIR__ . '/../../../config/database.php';

use App\Controllers\Admin\Traits\AdminHelpersTrait;
use App\Controllers\Admin\Traits\DashboardTrait;
use App\Controllers\Admin\Traits\UsersTrait;
use App\Controllers\Admin\Traits\VendorsTrait;
use App\Controllers\Admin\Traits\ProductsTrait;
use App\Controllers\Admin\Traits\OrdersTrait;
use App\Controllers\Admin\Traits\CommissionsTrait;
use App\Controllers\Admin\Traits\CategoriesTrait;
use App\Controllers\Admin\Traits\ReviewsTrait;
use App\Controllers\Admin\Traits\NotificationsTrait;
use App\Controllers\Admin\Traits\ReportsTrait;
use App\Controllers\Admin\Traits\SettingsTrait;
use App\Controllers\Admin\Traits\LogsTrait;
use App\Controllers\Admin\Traits\ContentsTrait;
use App\Controllers\Admin\Traits\WithdrawalsTrait;
use App\Controllers\Admin\Traits\BulkActionsTrait;
use App\Controllers\Admin\Traits\PageDispatchTrait;

class AdminController
{
    use AdminHelpersTrait;
    use DashboardTrait;
    use UsersTrait;
    use VendorsTrait;
    use ProductsTrait;
    use OrdersTrait;
    use CommissionsTrait;
    use CategoriesTrait;
    use ReviewsTrait;
    use NotificationsTrait;
    use ReportsTrait;
    use SettingsTrait;
    use LogsTrait;
    use ContentsTrait;
    use WithdrawalsTrait;
    use BulkActionsTrait;
    use PageDispatchTrait;
}
