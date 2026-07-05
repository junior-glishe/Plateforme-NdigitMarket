<?php
/**
 * NDIGITMARKET - Admin trait: Users
 *
 * Fait partie du refactor du monolithique AdminController.
 * Chaque trait regroupe les méthodes d'un même domaine metier.
 * Le comportement des méthodes est identique à la version d'origine.
 */

namespace App\Controllers\Admin\Traits;

trait UsersTrait
{
    public function users()
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        // Colonnes autorisées pour le tri
        $allowedSorts = ['id_uti', 'nom', 'prenom', 'email', 'type', 'statut', 'orders_count', 'ca_generated'];
        $sort = in_array($_GET['sort'] ?? '', $allowedSorts) ? $_GET['sort'] : 'id_uti';
        $order = strtoupper($_GET['order'] ?? '') === 'ASC' ? 'ASC' : 'DESC';

        // Filtres
        $where = [];
        $params = [];

        if (!empty($_GET['type']) && $_GET['type'] !== '') {
            $where[] = "u.type = :type";
            $params[':type'] = $_GET['type'];
        }
        if (!empty($_GET['statut']) && $_GET['statut'] !== '') {
            $statutMap = [
                'actif'      => 'actif,1,-',
                'bloque'     => 'bloque,0',
                'en_attente' => 'en_attente',
            ];
            if (isset($statutMap[$_GET['statut']])) {
                $where[] = "FIND_IN_SET(u.statut, :statut_list)";
                $params[':statut_list'] = $statutMap[$_GET['statut']];
            }
        }
        if (!empty($_GET['search'])) {
            $where[] = "(u.nom LIKE :search OR u.prenom LIKE :search OR u.email LIKE :search OR CAST(u.id_uti AS CHAR) LIKE :search)";
            $params[':search'] = '%' . $_GET['search'] . '%';
        }

        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        // Mapping des colonnes triables
        $sortMap = [
            'orders_count' => 'o.orders_count',
            'ca_generated' => 'o.total_ca',
            'id_uti'       => 'u.id_uti',
            'nom'          => 'u.nom',
            'prenom'       => 'u.prenom',
            'email'        => 'u.email',
            'type'         => 'u.type',
            'statut'       => 'u.statut',
        ];
        $sortColumn = $sortMap[$sort] ?? 'u.id_uti';

        $sql = "SELECT 
                u.id_uti AS id,
                u.id_uti,
                u.nom,
                u.prenom,
                u.email,
                u.type AS role,
                u.statut,
                COALESCE(o.orders_count, 0) AS orders_count,
                COALESCE(o.total_ca, 0) AS ca_generated
            FROM utilisateur u
            LEFT JOIN (
                SELECT email,
                    COUNT(*) AS orders_count,
                    SUM(CAST(REPLACE(prix, ' ', '') AS DECIMAL(14,2))) AS total_ca
                FROM commande
                GROUP BY email
            ) o ON o.email = u.email
            $whereClause
            ORDER BY $sortColumn $order";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $users = $stmt->fetchAll();

        foreach ($users as &$user) {
            $user['role_label'] = match ($user['role'] ?? '') {
                'pro'     => 'Vendeur Pro',
                'admin'   => 'Administrateur',
                default   => 'Utilisateur',
            };

            $user['status_label'] = match ($user['statut'] ?? '') {
                'actif', '1', '-' => 'Actif',
                'bloque', '0'     => 'Bloqué',
                'en_attente'      => 'En attente',
                default           => 'Actif',
            };

            $user['full_name'] = trim(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? ''));
            $user['orders_count'] = (int) $user['orders_count'];
            $user['ca_generated'] = number_format((float) $user['ca_generated'], 0, ',', ' ');
            $user['created_at_formatted'] = 'N/A';
        }
        unset($user);

        $totalUsers = count($users);
        $currentPage = 'gestion-utilisateurs';
        require_once __DIR__ . '/../../../Views/admin/gestion-utilisateurs.php';
    }

    public function createUser(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        $prenom = trim($_POST['prenom'] ?? '');
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $type = $_POST['type'] ?? '-';
        $statut = $_POST['statut'] ?? 'actif';
        $mdp = $_POST['mdp'] ?? '';

        // Validation
        if (empty($prenom) || empty($nom) || empty($email) || empty($mdp)) {
            $this->jsonResponse(['success' => false, 'message' => 'Tous les champs obligatoires doivent être remplis'], 400);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse(['success' => false, 'message' => 'Email invalide'], 400);
            return;
        }

        // Vérifier si l'email existe déjà
        $existing = $db->prepare("SELECT id_uti FROM utilisateur WHERE email = ?");
        $existing->execute([$email]);
        if ($existing->fetch()) {
            $this->jsonResponse(['success' => false, 'message' => 'Cet email est déjà utilisé'], 400);
            return;
        }

        if (strlen($mdp) < 6) {
            $this->jsonResponse(['success' => false, 'message' => 'Le mot de passe doit contenir au moins 6 caractères'], 400);
            return;
        }

        try {
            $hashedPassword = password_hash($mdp, PASSWORD_BCRYPT);
            $stmt = $db->prepare("INSERT INTO utilisateur (prenom, nom, email, type, statut, mdp) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$prenom, $nom, $email, $type, $statut, $hashedPassword]);
            $newId = $db->lastInsertId();

            $this->logAction('create_user', (int)$newId, "Création de l'utilisateur $prenom $nom ($email)");

            $this->jsonResponse([
                'success' => true,
                'message' => 'Utilisateur créé avec succès',
                'user_id' => $newId
            ]);
        } catch (\Exception $e) {
            $this->logAction('create_user', 0, "Échec: " . $e->getMessage(), 'error');
            $this->jsonResponse(['success' => false, 'message' => 'Erreur lors de la création'], 500);
        }
    }

    public function getUser(int $id): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        $stmt = $db->prepare("SELECT id_uti, nom, prenom, email, type, statut FROM utilisateur WHERE id_uti = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();

        if (!$user) {
            $this->jsonResponse(['success' => false, 'message' => 'Utilisateur introuvable'], 404);
            return;
        }

        $this->jsonResponse(['success' => true, 'user' => $user]);
    }

    public function updateUser(int $id): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        // Vérifier existence
        $stmt = $db->prepare("SELECT * FROM utilisateur WHERE id_uti = ?");
        $stmt->execute([$id]);
        $existing = $stmt->fetch();
        if (!$existing) {
            $this->jsonResponse(['success' => false, 'message' => 'Utilisateur introuvable'], 404);
            return;
        }

        $prenom = trim($_POST['prenom'] ?? $existing['prenom']);
        $nom = trim($_POST['nom'] ?? $existing['nom']);
        $email = trim($_POST['email'] ?? $existing['email']);
        $type = $_POST['type'] ?? $existing['type'];
        $statut = $_POST['statut'] ?? $existing['statut'];
        $mdp = $_POST['mdp'] ?? '';

        // Vérifier email unique (sauf si c'est le même)
        if ($email !== $existing['email']) {
            $check = $db->prepare("SELECT id_uti FROM utilisateur WHERE email = ? AND id_uti != ?");
            $check->execute([$email, $id]);
            if ($check->fetch()) {
                $this->jsonResponse(['success' => false, 'message' => 'Cet email est déjà utilisé'], 400);
                return;
            }
        }

        try {
            $changes = [];
            $sql = "UPDATE utilisateur SET prenom = ?, nom = ?, email = ?, type = ?, statut = ?";
            $params = [$prenom, $nom, $email, $type, $statut];

            // Mettre à jour le mot de passe seulement si fourni
            if (!empty($mdp)) {
                if (strlen($mdp) < 6) {
                    $this->jsonResponse(['success' => false, 'message' => 'Le mot de passe doit contenir au moins 6 caractères'], 400);
                    return;
                }
                $sql .= ", mdp = ?";
                $params[] = password_hash($mdp, PASSWORD_BCRYPT);
                $changes[] = 'mot de passe modifié';
            }

            // Détecter les changements
            if ($prenom !== $existing['prenom']) $changes[] = "prénom: {$existing['prenom']} → $prenom";
            if ($nom !== $existing['nom']) $changes[] = "nom: {$existing['nom']} → $nom";
            if ($email !== $existing['email']) $changes[] = "email: {$existing['email']} → $email";
            if ($type !== $existing['type']) $changes[] = "type: {$existing['type']} → $type";
            if ($statut !== $existing['statut']) $changes[] = "statut: {$existing['statut']} → $statut";

            $params[] = $id;
            $db->prepare($sql)->execute($params);

            $this->logAction('update_user', $id, "Modification: " . implode(', ', $changes));

            $this->jsonResponse(['success' => true, 'message' => 'Utilisateur mis à jour avec succès']);
        } catch (\Exception $e) {
            $this->logAction('update_user', $id, "Échec: " . $e->getMessage(), 'error');
            $this->jsonResponse(['success' => false, 'message' => 'Erreur lors de la mise à jour'], 500);
        }
    }

    public function promoteUser(int $id): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        $stmt = $db->prepare("UPDATE utilisateur SET type = 'pro' WHERE id_uti = ?");
        $stmt->execute([$id]);

        $this->logAction('promote_user', $id, "Promu vendeur Pro");
        $this->jsonResponse(['success' => true, 'message' => 'Utilisateur promu vendeur avec succès']);
    }

    public function blockUser(int $id): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $db->prepare("UPDATE utilisateur SET statut = 'bloque' WHERE id_uti = ?")->execute([$id]);
        $this->logAction('block_user', $id, "Utilisateur bloqué");
        $this->jsonResponse(['success' => true, 'message' => 'Utilisateur bloqué']);
    }

    public function unblockUser(int $id): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $db->prepare("UPDATE utilisateur SET statut = 'actif' WHERE id_uti = ?")->execute([$id]);
        $this->logAction('unblock_user', $id, "Utilisateur réactivé");
        $this->jsonResponse(['success' => true, 'message' => 'Utilisateur débloqué']);
    }

    public function deleteUser(int $id): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        // Récupérer les infos avant suppression
        $stmt = $db->prepare("SELECT nom, prenom, email FROM utilisateur WHERE id_uti = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();

        if (!$user) {
            $this->jsonResponse(['success' => false, 'message' => 'Utilisateur introuvable'], 404);
            return;
        }

        // Empêcher la suppression de soi-même
        $adminId = $_SESSION['admin_id'] ?? $_SESSION['user_id'] ?? null;
        if ($adminId == $id) {
            $this->jsonResponse(['success' => false, 'message' => 'Vous ne pouvez pas supprimer votre propre compte'], 400);
            return;
        }

        try {
            $db->prepare("DELETE FROM utilisateur WHERE id_uti = ?")->execute([$id]);
            $this->logAction('delete_user', $id, "Suppression de {$user['prenom']} {$user['nom']} ({$user['email']})");
            $this->jsonResponse(['success' => true, 'message' => 'Utilisateur supprimé']);
        } catch (\Exception $e) {
            $this->logAction('delete_user', $id, "Échec: " . $e->getMessage(), 'error');
            $this->jsonResponse(['success' => false, 'message' => 'Erreur lors de la suppression'], 500);
        }
    }
}
