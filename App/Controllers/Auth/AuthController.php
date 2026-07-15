<?php

/**
 * NDIGITMARKET - Controller d'authentification
 *
 * Gère la connexion et la déconnexion de tous les back-office
 * (admin, moderator, support). Les redirections utilisent BASE_URL
 * et passent par le routeur (/index.php?route=...).
 */

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Models\User;
use PDO;

class AuthController
{
    private PDO $db;
    private array $currentAccount = [];

    public function __construct(PDO $db)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->db = $db;
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
        }

        $email    = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? $_POST['mdp'] ?? '');

        if ($email === '' || $password === '') {
            $this->fail('Tous les champs sont obligatoires.');
        }

        $account = (new User($this->db))->findByEmail($email);

        if (!$account) {
            $this->fail('Email ou mot de passe incorrect.');
        }

        // Sauvegarder le compte AVANT la vérification du mot de passe
        $this->currentAccount = $account;

        if (!$this->verifyPassword($password, (string) $account['password'])) {
            $this->fail('Email ou mot de passe incorrect.');
        }

        if (isset($account['status']) && $account['status'] !== 'active') {
            $this->fail('Votre compte est désactivé.');
        }

        session_regenerate_id(true);

        // Normaliser le rôle pour gérer les variations de casse
        $role = \App\Core\Permission::normalizeRole((string) $account['role']);

        $_SESSION['user'] = [
            'id'    => $account['id'],
            'name'  => $account['name'],
            'email' => $account['email'],
            'role'  => $role,
        ];
        $_SESSION['user_id']   = $account['id'];
        $_SESSION['user_name'] = $account['name'];
        $_SESSION['user_role'] = $role;
        $_SESSION['email']     = $account['email'];

        // Redirection selon le rôle (tous vers le dashboard admin unifié)
        $this->redirect('/index.php?route=admin/dashboard');
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        $this->redirect('/');
    }

    // Helpers privés

    private function verifyPassword(string $plain, string $stored): bool
    {
        if ($stored === '') return false;

        $accountId = isset($this->currentAccount['id']) ? (int) $this->currentAccount['id'] : 0;

        // Vérifier avec password_verify (pour les hashs modernes)
        if (password_verify($plain, $stored)) {
            // Si le mot de passe était en clair, le re-hacher automatiquement
            if (password_get_info($stored)['algo'] === null && $accountId > 0) {
                $hashed = password_hash($plain, PASSWORD_DEFAULT);
                $this->updatePasswordHash($accountId, $hashed);
            }
            return true;
        }

        // Migration douce depuis mots de passe en clair (ancien système)
        if (hash_equals($stored, $plain)) {
            // Migrer vers un hash sécurisé
            if ($accountId > 0) {
                $hashed = password_hash($plain, PASSWORD_DEFAULT);
                $this->updatePasswordHash($accountId, $hashed);
            }
            return true;
        }

        return false;
    }

    private function updatePasswordHash(int $id, string $hashedPassword): void
    {
        try {
            $adminModel = new \App\Models\AdminModel($this->db);
            $adminModel->updatePassword($id, $hashedPassword);
        } catch (\Exception $e) {
            // Log l'erreur mais ne bloque pas la connexion
            error_log("Erreur migration mot de passe admin #$id: " . $e->getMessage());
        }
    }

    private function fail(string $message): void
    {
        $_SESSION['error'] = $message;
        $this->redirect('/');
    }

    private function redirect(string $path): void
    {
        $base = defined('BASE_URL') ? BASE_URL : '';
        header('Location: ' . $base . $path);
        exit;
    }
}
