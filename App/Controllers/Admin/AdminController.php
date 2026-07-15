<?php


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
use App\Controllers\Admin\Traits\ProfileTrait;

class AdminController
{
    use AdminHelpersTrait;
    use ProfileTrait;
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
