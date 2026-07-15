<?php
/**
 * NDIGITMARKET - Modèle Admin
 *
 * Accès à la table `admin` (comptes back-office). Fournit la recherche
 * par email et la mise à jour du mot de passe hashé.
 */

declare(strict_types=1);

namespace App\Models;

use PDO;

class AdminModel extends BaseModel
{
    protected string $table = 'admin';
    protected string $primaryKey = 'id_gestion';

    public function findByEmail(string $email): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM admin WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePassword(int $id, string $hashedPassword): bool
    {
        $stmt = $this->db->prepare('UPDATE admin SET mdp = :mdp WHERE id_gestion = :id');
        return $stmt->execute([':mdp' => $hashedPassword, ':id' => $id]);
    }
}

// Alias global pour rétro-compatibilité (public/index.php référence \AdminModel).
if (!class_exists('AdminModel', false)) {
    class_alias(AdminModel::class, 'AdminModel');
}
