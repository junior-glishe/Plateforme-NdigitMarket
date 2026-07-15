<?php

/**
 * NDIGITMARKET - Admin trait: Profile
 *
 * Gère le CRUD du profil administrateur (table `admin`).
 * Un admin ne peut modifier que son propre profil.
 */

namespace App\Controllers\Admin\Traits;

trait ProfileTrait
{
    /**
     * Affiche la page profil avec les infos de l'admin connecté.
     */
    public function profil()
    {
        $this->checkAuth();

        $db = \Database::getConnection();
        $adminEmail = $_SESSION['email'] ?? $_SESSION['user']['email'] ?? '';

        $stmt = $db->prepare("SELECT * FROM admin WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $adminEmail]);
        $admin = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$admin) {
            $admin = [
                'id_gestion'   => 'N/A',
                'nom'          => $_SESSION['user_name'] ?? $_SESSION['user']['name'] ?? 'Administrateur',
                'email'        => $adminEmail ?: 'N/A',
                'mdp'          => '********',
                'image_auteur' => '',
                'role'         => $_SESSION['user_role'] ?? $_SESSION['user']['role'] ?? 'admin',
            ];
        }

        $currentPage = 'profil';
        require_once __DIR__ . '/../../../Views/admin/profil.php';
    }

    /**
     * AJAX - Met à jour les informations du profil (nom, email, image).
     */
    public function updateProfile()
    {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Méthode non autorisée'], 405);
        }

        $db = \Database::getConnection();
        $adminEmail = $_SESSION['email'] ?? $_SESSION['user']['email'] ?? '';

        // Récupérer l'admin actuel
        $stmt = $db->prepare("SELECT * FROM admin WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $adminEmail]);
        $admin = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$admin) {
            $this->jsonResponse(['success' => false, 'message' => 'Administrateur introuvable'], 404);
        }

        $id = (int) $admin['id_gestion'];
        $nom = trim((string) ($_POST['nom'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $imagePath = $admin['image_auteur'];

        if ($nom === '') {
            $this->jsonResponse(['success' => false, 'message' => 'Le nom est obligatoire'], 400);
        }
        if ($email === '' || !filter_var($email, \FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse(['success' => false, 'message' => 'Email invalide'], 400);
        }

        // Vérifier si l'email est déjà pris par un autre admin
        if ($email !== $admin['email']) {
            $check = $db->prepare("SELECT id_gestion FROM admin WHERE email = :email AND id_gestion != :id LIMIT 1");
            $check->execute([':email' => $email, ':id' => $id]);
            if ($check->fetch()) {
                $this->jsonResponse(['success' => false, 'message' => 'Cet email est déjà utilisé'], 400);
            }
        }

        // Gestion de l'upload d'image
        if (!empty($_FILES['image_auteur']) && $_FILES['image_auteur']['error'] === UPLOAD_ERR_OK) {
            $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            $extension = strtolower(pathinfo($_FILES['image_auteur']['name'], \PATHINFO_EXTENSION));
            if (!in_array($extension, $allowedExts)) {
                $this->jsonResponse(['success' => false, 'message' => 'Extension non autorisée (jpg, jpeg, png, gif, webp)'], 400);
            }

            $maxSize = 2 * 1024 * 1024;
            if ($_FILES['image_auteur']['size'] > $maxSize) {
                $this->jsonResponse(['success' => false, 'message' => 'L\'image ne doit pas dépasser 2 Mo'], 400);
            }

            // Détermine le chemin absolu du dossier racine du projet
            // ProfileTrait.php se trouve dans App/Controllers/Admin/Traits/
            // dirname(__DIR__, 4) remonte jusqu'à la racine du projet
            $rootDir = dirname(__DIR__, 4);
            $uploadDir = $rootDir . '/assets/images/profile/';

            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }

            $filename = 'admin_' . $id . '_' . time() . '.' . $extension;
            $destination = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['image_auteur']['tmp_name'], $destination)) {
                // Supprimer l'ancienne image
                if (!empty($admin['image_auteur'])) {
                    $oldPath = $rootDir . '/' . $admin['image_auteur'];
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }
                $imagePath = 'assets/images/profile/' . $filename;
            }
        }

        // Mise à jour en base
        $update = $db->prepare("UPDATE admin SET nom = :nom, email = :email, image_auteur = :image WHERE id_gestion = :id");
        $success = $update->execute([
            ':nom'   => $nom,
            ':email' => $email,
            ':image' => $imagePath,
            ':id'    => $id,
        ]);

        if ($success) {
            // Mettre à jour la session
            $_SESSION['user_name'] = $nom;
            $_SESSION['email'] = $email;
            if (isset($_SESSION['user'])) {
                $_SESSION['user']['name'] = $nom;
                $_SESSION['user']['email'] = $email;
            }

            $this->logAction('update_profile', $id, 'Mise à jour du profil administrateur');
            $this->jsonResponse([
                'success' => true,
                'message' => 'Profil mis à jour avec succès',
                'data'    => [
                    'nom'          => $nom,
                    'email'        => $email,
                    'image_auteur' => $imagePath,
                ]
            ]);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Erreur lors de la mise à jour'], 500);
        }
    }

    /**
     * AJAX - Change le mot de passe de l'admin connecté.
     */
    public function changePassword()
    {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Méthode non autorisée'], 405);
        }

        $db = \Database::getConnection();
        $adminEmail = $_SESSION['email'] ?? $_SESSION['user']['email'] ?? '';

        $stmt = $db->prepare("SELECT * FROM admin WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $adminEmail]);
        $admin = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$admin) {
            $this->jsonResponse(['success' => false, 'message' => 'Administrateur introuvable'], 404);
        }

        $currentPassword = (string) ($_POST['current_password'] ?? '');
        $newPassword = (string) ($_POST['new_password'] ?? '');
        $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

        if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
            $this->jsonResponse(['success' => false, 'message' => 'Tous les champs sont obligatoires'], 400);
        }

        // Vérifier l'ancien mot de passe
        if (!password_verify($currentPassword, $admin['mdp']) && $admin['mdp'] !== $currentPassword) {
            $this->jsonResponse(['success' => false, 'message' => 'Mot de passe actuel incorrect'], 400);
        }

        if ($newPassword !== $confirmPassword) {
            $this->jsonResponse(['success' => false, 'message' => 'Les nouveaux mots de passe ne correspondent pas'], 400);
        }

        if (strlen($newPassword) < 6) {
            $this->jsonResponse(['success' => false, 'message' => 'Le mot de passe doit contenir au moins 6 caractères'], 400);
        }

        $hashedPassword = password_hash($newPassword, \PASSWORD_BCRYPT);
        $update = $db->prepare("UPDATE admin SET mdp = :mdp WHERE id_gestion = :id");
        $success = $update->execute([
            ':mdp' => $hashedPassword,
            ':id'  => (int) $admin['id_gestion'],
        ]);

        if ($success) {
            $this->logAction('change_password', (int) $admin['id_gestion'], 'Changement de mot de passe');
            $this->jsonResponse(['success' => true, 'message' => 'Mot de passe modifié avec succès']);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Erreur lors du changement de mot de passe'], 500);
        }
    }
}
