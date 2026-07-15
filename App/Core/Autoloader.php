<?php
/**
 * NDIGITMARKET - Autoloader PSR-4 simplifié pour le namespace App\.
 *
 * Résout App\Foo\Bar vers App/Foo/Bar.php à la racine du projet.
 * Utilisé par le front controller (index.php) et par les tests éventuels.
 */

declare(strict_types=1);

namespace App\Core;

final class Autoloader
{
    public static function register(string $baseDir): void
    {
        spl_autoload_register(static function (string $class) use ($baseDir): void {
            $prefix = 'App\\';
            if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
                return;
            }
            $relative = substr($class, strlen($prefix));
            $file     = $baseDir . '/App/' . str_replace('\\', '/', $relative) . '.php';
            if (is_file($file)) {
                require_once $file;
            }
        });
    }
}
