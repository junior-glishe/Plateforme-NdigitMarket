<?php

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

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {

            $_SESSION['error'] = "Tous les champs sont obligatoires.";

            header("Location: /index.php");
            exit;
        }

        $user = new User($this->db);

        $account = $user->findByEmail($email);

        if (!$account) {

            $_SESSION['error'] = "Email ou mot de passe incorrect.";

            header("Location: /index.php");
            exit;
        }

        if (!password_verify($password, $account['password'])) {

            $_SESSION['error'] = "Email ou mot de passe incorrect.";

            header("Location: /index.php");
            exit;
        }

        if (isset($account['status']) && $account['status'] != 'active') {

            $_SESSION['error'] = "Votre compte est désactivé.";

            header("Location: /index.php");
            exit;
        }

        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id'    => $account['id'],
            'name'  => $account['name'],
            'email' => $account['email'],
            'role'  => $account['role']
        ];

        switch ($account['role']) {

            case 'admin':
                header("Location: /app/Views/admin/dashboard.php");
                break;

            case 'moderator':
                header("Location: /app/Views/moderator/dashboard.php");
                break;

            case 'support':
                header("Location: /app/Views/support/dashboard.php");
                break;

            default:
                session_destroy();

                $_SESSION['error'] = "Rôle inconnu.";

                header("Location: /index.php");
                break;
        }

        exit;
    }

    public function logout()
    {
        session_start();

        session_destroy();

        header("Location: /index.php");

        exit;
    }
}