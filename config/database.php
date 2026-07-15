<?php

class Database
{
    private static ?PDO $instance = null;

    private string $host = "localhost";
    private string $dbname = "ndigi2561261";
    private string $username = "root";
    private string $password = "";

    // Mets à true UNIQUEMENT en développement pour voir les erreurs détaillées.
    // Mets à false en production pour ne pas exposer d'infos sensibles.
    private static bool $debug = true;

    private function __construct() {}

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {

            $config = new self();

            if (self::$debug) {
                error_reporting(E_ALL);
                ini_set('display_errors', '1');
            }

            try {
                self::$instance = new PDO(
                    "mysql:host={$config->host};dbname={$config->dbname};charset=utf8mb4",
                    $config->username,
                    $config->password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (PDOException $e) {

                if (self::$debug) {
                    // Message détaillé pour comprendre précisément le problème :
                    // - "Access denied for user" => mauvais username/password
                    // - "Unknown database"       => le nom de la base est faux ou elle n'existe pas
                    // - "SQLSTATE[HY000] [2002]" => MySQL ne répond pas sur ce host (service arrêté / mauvais host)
                    die("Erreur de connexion PDO : " . $e->getMessage());
                } else {
                    // En production, on ne montre jamais le détail technique à l'utilisateur
                    error_log("Erreur de connexion PDO : " . $e->getMessage());
                    die("Une erreur est survenue. Veuillez réessayer plus tard.");
                }
            }
        }

        return self::$instance;
    }
}
