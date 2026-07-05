<?php
/**
 * NDIGITMARKET - Front controller
 *
 * Point d'entrée unique. Charge la configuration, initialise l'autoloader
 * PSR-4, puis délègue le dispatch à App\Core\Router.
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/App/Core/Autoloader.php';

App\Core\Autoloader::register(__DIR__);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$route = (string) ($_GET['route'] ?? '');
(new App\Core\Router())->dispatch($route);
