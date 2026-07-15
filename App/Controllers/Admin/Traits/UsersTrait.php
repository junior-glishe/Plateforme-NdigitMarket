<?php

/**
 * NDIGITMARKET - Admin trait: Users
 */

namespace App\Controllers\Admin\Traits;

trait UsersTrait
{
    private function ensureEmailTables(\PDO $db): void
    {
        $db->exec("CREATE TABLE IF NOT EXISTS email_campaigns (
            id INT AUTO_INCREMENT PRIMARY KEY,
            sujet VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            total_envoyes INT DEFAULT 0,
            total_erreurs INT DEFAULT 0,
            date_debut TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            date_fin TIMESTAMP NULL DEFAULT NULL,
            statut ENUM('en_cours','termine','pause') DEFAULT 'en_cours'
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

        $db->exec("CREATE TABLE IF NOT EXISTS email_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL,
            sujet VARCHAR(255) NOT NULL,
            statut ENUM('envoye','erreur') DEFAULT 'envoye',
            date_envoi TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    }

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
            $where[] = "u.type = ?";
            $params[] = $_GET['type'];
        }
        if (!empty($_GET['statut']) && $_GET['statut'] !== '') {
            $statutMap = [
                'actif'      => ['actif', '1', '-'],
                'bloque'     => ['bloque', '0'],
                'en_attente' => ['en_attente'],
            ];
            if (isset($statutMap[$_GET['statut']])) {
                $placeholders = [];
                foreach ($statutMap[$_GET['statut']] as $value) {
                    $placeholders[] = '?';
                    $params[] = $value;
                }
                $where[] = "u.statut IN (" . implode(',', $placeholders) . ")";
            }
        }
        if (!empty($_GET['search'])) {
            $where[] = "(u.nom LIKE ? OR u.prenom LIKE ? OR u.email LIKE ? OR CAST(u.id_uti AS CHAR) LIKE ?)";
            $searchTerm = '%' . $_GET['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
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

    public function exportUsers(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        $where = [];
        $params = [];

        if (!empty($_GET['type'])) {
            $where[] = "u.type = ?";
            $params[] = $_GET['type'];
        }

        if (!empty($_GET['statut'])) {
            $statutMap = [
                'actif'      => ['actif', '1', '-'],
                'bloque'     => ['bloque', '0'],
                'en_attente' => ['en_attente'],
            ];
            if (isset($statutMap[$_GET['statut']])) {
                $where[] = "u.statut IN (" . implode(',', array_fill(0, count($statutMap[$_GET['statut']]), '?')) . ")";
                array_push($params, ...$statutMap[$_GET['statut']]);
            }
        }

        if (!empty($_GET['search'])) {
            $where[] = "(u.nom LIKE ? OR u.prenom LIKE ? OR u.email LIKE ? OR CAST(u.id_uti AS CHAR) LIKE ?)";
            $searchTerm = '%' . $_GET['search'] . '%';
            array_push($params, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
        }

        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $sortMap = [
            'orders_count' => 'orders_count',
            'ca_generated' => 'ca_generated',
            'id_uti'       => 'u.id_uti',
            'nom'          => 'u.nom',
            'prenom'       => 'u.prenom',
            'email'        => 'u.email',
            'type'         => 'u.type',
            'statut'       => 'u.statut',
        ];
        $sort = $_GET['sort'] ?? 'id_uti';
        $sortColumn = $sortMap[$sort] ?? 'u.id_uti';
        $order = strtoupper($_GET['order'] ?? '') === 'ASC' ? 'ASC' : 'DESC';

        $stmt = $db->prepare("
            SELECT
                u.id_uti,
                u.prenom,
                u.nom,
                u.email,
                u.type,
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
            ORDER BY $sortColumn $order
        ");
        $stmt->execute($params);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $format = strtolower((string)($_GET['format'] ?? 'csv'));
        $filename = 'utilisateurs_' . date('Ymd_His');
        $headers = ['ID', 'Prénom', 'Nom', 'Email', 'Rôle', 'Statut', 'Commandes', 'CA généré'];

        if ($format === 'excel') {
            header('Content-Type: application/vnd.ms-excel; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '.xls"');
            echo "<table><thead><tr>";
            foreach ($headers as $header) {
                echo '<th>' . htmlspecialchars($header, ENT_QUOTES, 'UTF-8') . '</th>';
            }
            echo "</tr></thead><tbody>";
            foreach ($rows as $row) {
                echo '<tr>';
                echo '<td>' . (int)$row['id_uti'] . '</td>';
                echo '<td>' . htmlspecialchars((string)$row['prenom'], ENT_QUOTES, 'UTF-8') . '</td>';
                echo '<td>' . htmlspecialchars((string)$row['nom'], ENT_QUOTES, 'UTF-8') . '</td>';
                echo '<td>' . htmlspecialchars((string)$row['email'], ENT_QUOTES, 'UTF-8') . '</td>';
                echo '<td>' . htmlspecialchars((string)$row['type'], ENT_QUOTES, 'UTF-8') . '</td>';
                echo '<td>' . htmlspecialchars((string)$row['statut'], ENT_QUOTES, 'UTF-8') . '</td>';
                echo '<td>' . (int)$row['orders_count'] . '</td>';
                echo '<td>' . number_format((float)$row['ca_generated'], 0, ',', ' ') . '</td>';
                echo '</tr>';
            }
            echo "</tbody></table>";
            exit;
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, $headers);
        foreach ($rows as $row) {
            fputcsv($out, [
                $row['id_uti'],
                $row['prenom'],
                $row['nom'],
                $row['email'],
                $row['type'],
                $row['statut'],
                $row['orders_count'],
                $row['ca_generated'],
            ]);
        }
        fclose($out);
        exit;
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

    public function sendCustomEmail(): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $this->ensureEmailTables($db);

        $subject = trim((string)($_POST['subject'] ?? ''));
        $message = trim((string)($_POST['message'] ?? ''));
        $ids = $_POST['user_ids'] ?? [];

        if ($subject === '' || $message === '') {
            $this->jsonResponse(['success' => false, 'message' => 'Sujet et message requis'], 400);
            return;
        }

        if (!is_array($ids)) {
            $ids = [$ids];
        }
        $ids = array_values(array_unique(array_filter(array_map('intval', $ids))));
        if (!$ids) {
            $this->jsonResponse(['success' => false, 'message' => 'Sélectionnez au moins un utilisateur'], 400);
            return;
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $db->prepare("SELECT id_uti, nom, prenom, email FROM utilisateur WHERE id_uti IN ($placeholders)");
        $stmt->execute($ids);
        $recipients = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (!$recipients) {
            $this->jsonResponse(['success' => false, 'message' => 'Aucun destinataire valide'], 404);
            return;
        }

        $db->beginTransaction();
        try {
            $campaignStmt = $db->prepare("INSERT INTO email_campaigns (sujet, message, statut) VALUES (?, ?, 'en_cours')");
            $campaignStmt->execute([$subject, $message]);
            $campaignId = (int)$db->lastInsertId();

            $sent = 0;
            $errors = 0;
            $logStmt = $db->prepare("INSERT INTO email_logs (email, sujet, statut) VALUES (?, ?, ?)");

            foreach ($recipients as $recipient) {
                $email = trim((string)($recipient['email'] ?? ''));
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors++;
                    $logStmt->execute([$email ?: 'email-invalide', $subject, 'erreur']);
                    continue;
                }

                $fullName = trim(($recipient['prenom'] ?? '') . ' ' . ($recipient['nom'] ?? ''));
                $personalized = str_replace(
                    ['{{nom}}', '{{prenom}}', '{{email}}', '{{nom_complet}}'],
                    [$recipient['nom'] ?? '', $recipient['prenom'] ?? '', $email, $fullName],
                    $message
                );

                $headers = [
                    'MIME-Version: 1.0',
                    'Content-Type: text/html; charset=UTF-8',
                    'From: NDIGITMARKET <no-reply@ndigitmarket.local>',
                ];
                $ok = @mail($email, $subject, $personalized, implode("\r\n", $headers));
                $ok ? $sent++ : $errors++;
                $logStmt->execute([$email, $subject, $ok ? 'envoye' : 'erreur']);
            }

            $db->prepare("UPDATE email_campaigns SET total_envoyes = ?, total_erreurs = ?, statut = 'termine', date_fin = NOW() WHERE id = ?")
                ->execute([$sent, $errors, $campaignId]);

            $db->commit();
            $this->logAction('send_custom_email', 0, "Campagne #$campaignId : $sent envoyé(s), $errors erreur(s)");
            $this->jsonResponse([
                'success' => true,
                'message' => "Campagne terminée : $sent envoyé(s), $errors erreur(s)",
                'sent' => $sent,
                'errors' => $errors,
            ]);
        } catch (\Exception $e) {
            $db->rollBack();
            $this->jsonResponse(['success' => false, 'message' => 'Erreur campagne : ' . $e->getMessage()], 500);
        }
    }
}
