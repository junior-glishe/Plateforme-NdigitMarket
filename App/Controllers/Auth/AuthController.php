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
        if (!$account || !$this->verifyPassword($password, (string) $account['password'])) {
            $this->fail('Email ou mot de passe incorrect.');
        }

        if (isset($account['status']) && $account['status'] !== 'active') {
            $this->fail('Votre compte est désactivé.');
        }

        session_regenerate_id(true);

        // La colonne `role` en base peut contenir 'Admin', 'ADMIN', 'admin', etc.
        // On normalise pour que le switch ci-dessous fonctionne dans tous les cas.
        $role = strtolower(trim((string) $account['role']));

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

        switch ($role) {
            case 'admin':
                $_SESSION['admin'] = 'oui';
                $this->redirect('/index.php?route=admin/dashboard');
                break;
            case 'moderator':
                $this->redirect('/index.php?route=moderator/dashboard');
                break;
            case 'support':
                $this->redirect('/index.php?route=support/dashboard');
                break;
            default:
                session_destroy();
                $this->fail('Rôle inconnu.');
        }
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

    // -----------------------------------------------------------------
    // Helpers privés
    // -----------------------------------------------------------------

    private function verifyPassword(string $plain, string $stored): bool
    {
        if ($stored === '') return false;
        if (password_verify($plain, $stored)) return true;
        // Migration douce depuis mots de passe en clair
        return hash_equals($stored, $plain);
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
