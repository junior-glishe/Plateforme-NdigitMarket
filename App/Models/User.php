<?php
namespace App\Models;

use PDO;

class User extends BaseModel
{
    protected string $table = 'utilisateur';
    protected string $primaryKey = 'id_uti';

    /**
     * Compatibilité avec AuthController : recherche par email
     * dans la table `admin` (compte administrateur historique).
     */
    public function findByEmail(string $email)
    {
        $stmt = $this->db->prepare("SELECT * FROM admin WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        // Normalisation vers un format commun
        return [
            'id'       => $row['id_gestion'] ?? $row['id'] ?? null,
            'name'     => trim(($row['prenom'] ?? '') . ' ' . ($row['nom'] ?? '')),
            'email'    => $row['email'],
            'password' => $row['mdp'] ?? '',
            'role'     => $row['role'] ?? 'admin',
            'status'   => $row['statut'] ?? 'active',
        ];
    }

    public function search(string $q): array
    {
        $like = '%' . $q . '%';
        $sql = "SELECT * FROM utilisateur
                WHERE nom LIKE :q OR prenom LIKE :q OR email LIKE :q
                ORDER BY id_uti DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':q' => $like]);
        return $stmt->fetchAll();
    }
}
