<?php
class SettingModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // ============================================
    // PARAMÈTRES GÉNÉRAUX
    // ============================================

    /**
     * Récupérer tous les paramètres
     */
    public function getAllSettings()
    {
        try {
            $stmt = $this->pdo->query("SELECT setting_key, setting_value FROM settings");
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $settings = [];
            foreach ($results as $row) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }

            return $settings;
        } catch (PDOException $e) {
            error_log("Erreur getAllSettings: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupérer le dernier administrateur connecté
     */
    public function getLastConnection()
    {
        try {
            $stmt = $this->pdo->query("
                SELECT nom, derniere_connexion 
                FROM admin 
                WHERE derniere_connexion IS NOT NULL 
                ORDER BY derniere_connexion DESC 
                LIMIT 1
            ");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['nom'] . ' - ' . $result['derniere_connexion'] : '-';
        } catch (PDOException $e) {
            error_log("Erreur getLastConnection: " . $e->getMessage());
            return '-';
        }
    }

    /**
     * Mettre à jour les paramètres généraux avec gestion des fichiers
     */
    public function updateGeneralSettings($data, $files = [])
    {
        error_log("===  START updateGeneralSettings (MODEL) ===");
        error_log(" DATA reçues: " . print_r($data, true));
        error_log(" FILES reçus: " . print_r($files, true));

        try {
            $success = true;

            //  DEBUG: Vérifier la connexion à la BDD
            if (!$this->pdo) {
                error_log(" CONNEXION PDO NULL !");
                return false;
            }
            error_log(" CONNEXION PDO OK");

            // Gérer l'upload du logo
            if (isset($files['site_logo']) && $files['site_logo']['error'] === UPLOAD_ERR_OK) {
                error_log(" Upload du logo en cours...");
                $logoPath = $this->uploadFile($files['site_logo'], 'logo');
                if ($logoPath) {
                    $data['site_logo'] = $logoPath;
                    error_log(" Logo uploadé: " . $logoPath);
                } else {
                    error_log(" Erreur upload logo");
                    $success = false;
                }
            } else {
                error_log(" Pas de logo à uploader");
            }

            // Gérer l'upload du favicon
            if (isset($files['site_favicon']) && $files['site_favicon']['error'] === UPLOAD_ERR_OK) {
                error_log(" Upload du favicon en cours...");
                $faviconPath = $this->uploadFile($files['site_favicon'], 'favicon');
                if ($faviconPath) {
                    $data['site_favicon'] = $faviconPath;
                    error_log(" Favicon uploadé: " . $faviconPath);
                } else {
                    error_log(" Erreur upload favicon");
                    $success = false;
                }
            } else {
                error_log(" Pas de favicon à uploader");
            }

            // Mettre à jour les paramètres
            error_log(" Mise à jour des paramètres dans la BDD...");

            foreach ($data as $key => $value) {
                error_log("=== UPDATE KEY: $key = " . substr($value, 0, 100) . (strlen($value) > 100 ? '...' : '') . " ===");

                try {
                    $result = $this->updateSetting($key, $value);
                    if (!$result) {
                        error_log(" ERREUR pour la clé: $key");
                        $success = false;
                    } else {
                        error_log(" OK pour la clé: $key");
                    }
                } catch (Exception $e) {
                    error_log(" EXCEPTION pour la clé $key: " . $e->getMessage());
                    $success = false;
                }
            }

            error_log("=== FINAL RESULT: " . ($success ? ' SUCCESS' : ' FAILED') . " ===");
            return $success;
        } catch (Exception $e) {
            error_log(" EXCEPTION GLOBALE: " . $e->getMessage());
            error_log(" TRACE: " . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Mettre à jour un paramètre
     */
    public function updateSetting($key, $value)
    {
        error_log(" updateSetting: $key = " . substr($value, 0, 50) . (strlen($value) > 50 ? '...' : ''));

        try {
            // Vérifier la connexion PDO
            if (!$this->pdo) {
                error_log(" PDO NULL dans updateSetting");
                return false;
            }

            //  Vérifier si la table existe, la créer automatiquement sinon
            try {
                $checkTable = $this->pdo->query("SHOW TABLES LIKE 'settings'");
                if ($checkTable->rowCount() == 0) {
                    error_log(" La table 'settings' n'existe pas, création automatique...");
                    $this->ensureSettingsTable();
                }
            } catch (Exception $e) {
                error_log(" Erreur vérification/création table: " . $e->getMessage());
                return false;
            }

            $stmt = $this->pdo->prepare("
            INSERT INTO settings (setting_key, setting_value) 
            VALUES (:key, :value) 
            ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)
        ");

            $result = $stmt->execute([
                ':key' => $key,
                ':value' => $value
            ]);

            if ($result) {
                error_log(" updateSetting OK pour: $key");
            } else {
                error_log(" updateSetting FAILED pour: $key");
                error_log(" Erreur PDO: " . print_r($stmt->errorInfo(), true));
            }

            return $result;
        } catch (PDOException $e) {
            error_log(" PDOException updateSetting: " . $e->getMessage());
            error_log(" Code: " . $e->getCode());
            return false;
        } catch (Exception $e) {
            error_log(" Exception updateSetting: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Upload d'un fichier avec debug
     */
    private function uploadFile($file, $type = 'logo')
    {
        error_log(" uploadFile: type=$type, name=" . $file['name']);

        try {
            // Vérifier les erreurs
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $errors = [
                    UPLOAD_ERR_INI_SIZE => 'Fichier trop volumineux (ini)',
                    UPLOAD_ERR_FORM_SIZE => 'Fichier trop volumineux (form)',
                    UPLOAD_ERR_PARTIAL => 'Upload partiel',
                    UPLOAD_ERR_NO_FILE => 'Aucun fichier',
                    UPLOAD_ERR_NO_TMP_DIR => 'Dossier tmp manquant',
                    UPLOAD_ERR_CANT_WRITE => 'Impossible d\'écrire',
                    UPLOAD_ERR_EXTENSION => 'Extension bloquée'
                ];
                $errorMsg = $errors[$file['error']] ?? 'Erreur inconnue: ' . $file['error'];
                error_log(" Erreur upload: " . $errorMsg);
                return false;
            }

            //  CORRECTION: Utiliser le bon dossier
            $uploadDir = __DIR__ . '/../../public/assets/images/';
            error_log(" Dossier upload: " . $uploadDir);

            // Créer le dossier s'il n'existe pas
            if (!is_dir($uploadDir)) {
                error_log(" Création du dossier: " . $uploadDir);
                if (!mkdir($uploadDir, 0777, true)) {
                    error_log(" Impossible de créer le dossier: " . $uploadDir);
                    return false;
                }
            }

            // Vérifier les droits
            if (!is_writable($uploadDir)) {
                error_log(" Dossier non accessible en écriture: " . $uploadDir);
                return false;
            }
            error_log(" Dossier accessible en écriture");

            // Vérifier le type de fichier
            $allowedTypes = ['image/jpeg', 'image/png', 'image/svg+xml', 'image/x-icon', 'image/vnd.microsoft.icon'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            error_log(" Type MIME: " . $mimeType);

            if (!in_array($mimeType, $allowedTypes)) {
                error_log(" Type de fichier non autorisé: " . $mimeType);
                return false;
            }

            // Vérifier la taille (max 2MB)
            if ($file['size'] > 2 * 1024 * 1024) {
                error_log(" Fichier trop volumineux: " . $file['size'] . " bytes");
                return false;
            }
            error_log(" Taille OK: " . $file['size'] . " bytes");

            // Générer un nom unique
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = $type . '_' . time() . '_' . uniqid() . '.' . $extension;
            $destination = $uploadDir . $filename;

            error_log(" Destination: " . $destination);

            // Supprimer l'ancien fichier si existe
            if ($type === 'logo') {
                $oldLogo = $this->getSetting('site_logo');
                if ($oldLogo && file_exists($uploadDir . $oldLogo)) {
                    error_log(" Suppression ancien logo: " . $oldLogo);
                    unlink($uploadDir . $oldLogo);
                }
            } elseif ($type === 'favicon') {
                $oldFavicon = $this->getSetting('site_favicon');
                if ($oldFavicon && file_exists($uploadDir . $oldFavicon)) {
                    error_log(" Suppression ancien favicon: " . $oldFavicon);
                    unlink($uploadDir . $oldFavicon);
                }
            }

            // Déplacer le fichier
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                error_log(" Fichier uploadé avec succès: " . $filename);
                return $filename;
            } else {
                error_log(" Erreur move_uploaded_file");
                return false;
            }
        } catch (Exception $e) {
            error_log(" EXCEPTION uploadFile: " . $e->getMessage());
            return false;
        }
    }
    /**
     * Mettre à jour plusieurs paramètres
     */
    public function updateSettings($data)
    {
        try {
            $success = true;
            foreach ($data as $key => $value) {
                if (!$this->updateSetting($key, $value)) {
                    $success = false;
                }
            }
            return $success;
        } catch (Exception $e) {
            error_log("Erreur updateSettings: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer un paramètre spécifique
     */
    public function getSetting($key)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = :key");
            $stmt->execute([':key' => $key]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['setting_value'] : null;
        } catch (PDOException $e) {
            error_log("Erreur getSetting: " . $e->getMessage());
            return null;
        }
    }
    
    // ============================================
    // ADMINISTRATEURS
    // ============================================

    /**
     * Récupérer tous les administrateurs
     */
    public function getAdmins()
    {
        try {
            $stmt = $this->pdo->query("
                SELECT 
                    id_gestion as id,
                    nom,
                    email,
                    telephone,
                    image_auteur,
                    role,
                    statut,
                    derniere_connexion
                FROM admin
                ORDER BY id_gestion ASC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur get admins: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupérer un administrateur par ID
     */
    public function getAdminById($id)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    id_gestion as id,
                    nom,
                    email,
                    telephone,
                    image_auteur,
                    role,
                    statut
                FROM admin
                WHERE id_gestion = ?
            ");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getAdminById: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Compter les administrateurs
     */
    public function countAdmins()
    {
        try {
            $stmt = $this->pdo->query("
                  SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN statut = 'actif' THEN 1 ELSE 0 END) as actifs,
                    SUM(CASE WHEN role = 'Super Admin' THEN 1 ELSE 0 END) as super_admins
                FROM admin
            ");
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur count admins: " . $e->getMessage());
            return ['total' => 0, 'actifs' => 0, 'super_admins' => 0];
        }
    }

    /**
     * Créer un administrateur
     */
     public function createAdmin($data) {
    try {
        $hashedPassword = password_hash($data['mdp'], PASSWORD_DEFAULT);
        
        $stmt = $this->pdo->prepare("
            INSERT INTO admin (nom, email, telephone, mdp, image_auteur, role, statut) 
            VALUES (?, ?, ?, ?, ?, ?, 'actif')
        ");
        return $stmt->execute([
            $data['nom'],
            $data['email'],
            $data['telephone'] ?? '',
            $hashedPassword,
            $data['image_auteur'] ?? 'default.png',
            $data['role'] ?? 'Admin'
        ]);
    } catch (PDOException $e) {
        error_log("Erreur createAdmin: " . $e->getMessage());
        return false;
    }
}

    /**
     * Mettre à jour un administrateur
     */
     public function updateAdmin($id, $data) {
    try {
        $sql = "UPDATE admin SET nom = ?, email = ?, telephone = ?, role = ? WHERE id_gestion = ?";
        $params = [$data['nom'], $data['email'], $data['telephone'] ?? '', $data['role'] ?? 'Admin', $id];
        
        if (!empty($data['mdp'])) {
            $sql = "UPDATE admin SET nom = ?, email = ?, telephone = ?, mdp = ?, role = ? WHERE id_gestion = ?";
            $params = [$data['nom'], $data['email'], $data['telephone'] ?? '', password_hash($data['mdp'], PASSWORD_DEFAULT), $data['role'] ?? 'Admin', $id];
        }
        
        if (!empty($data['image_auteur'])) {
            $sql = "UPDATE admin SET nom = ?, email = ?, telephone = ?, image_auteur = ?, role = ? WHERE id_gestion = ?";
            $params = [$data['nom'], $data['email'], $data['telephone'] ?? '', $data['image_auteur'], $data['role'] ?? 'Admin', $id];
        }
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    } catch (PDOException $e) {
        error_log("Erreur updateAdmin: " . $e->getMessage());
        return false;
    }
}

    /**
     * Désactiver un administrateur
     */
    public function disableAdmin($id)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE admin SET statut = 'inactif' WHERE id_gestion = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erreur disableAdmin: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Réactiver un administrateur
     */
    public function enableAdmin($id)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE admin SET statut = 'actif' WHERE id_gestion = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erreur enableAdmin: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Réinitialiser le mot de passe
     */
    public function resetAdminPassword($id, $newPassword)
    {
        try {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("UPDATE admin SET mdp = ? WHERE id_gestion = ?");
            return $stmt->execute([$hashedPassword, $id]);
        } catch (PDOException $e) {
            error_log("Erreur resetAdminPassword: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifier si un email existe déjà
     */
    public function emailExists($email, $excludeId = null)
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM admin WHERE email = ?";
            $params = [$email];

            if ($excludeId) {
                $sql .= " AND id_gestion != ?";
                $params[] = $excludeId;
            }

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] > 0;
        } catch (PDOException $e) {
            error_log("Erreur emailExists: " . $e->getMessage());
            return false;
        }
    }
    // ============================================
    // TABLE DES PARAMÈTRES
    // ============================================


    /**
     * Récupérer les paramètres SMTP depuis la BDD
     */
    public function getSmtpSettings()
    {
        try {
            $stmt = $this->pdo->query("
            SELECT setting_key, setting_value 
            FROM settings 
            WHERE setting_key LIKE 'smtp_%'
        ");
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $settings = [];
            foreach ($results as $row) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }

            return $settings;
        } catch (PDOException $e) {
            error_log("Erreur getSmtpSettings: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Mettre à jour les paramètres SMTP
     */
    public function updateSmtpSettings($data)
    {
        try {
            $this->ensureSettingsTable();

            foreach ($data as $key => $value) {
                // Nettoyer la valeur
                $value = trim($value);

                // Insérer la clé si elle n'existe pas encore, sinon la mettre à jour.
                $stmt = $this->pdo->prepare("
                    INSERT INTO settings (setting_key, setting_value, created_at, updated_at)
                    VALUES (:key, :value, NOW(), NOW())
                    ON DUPLICATE KEY UPDATE
                        setting_value = VALUES(setting_value),
                        updated_at = NOW()
                ");
                $stmt->execute([
                    ':key' => $key,
                    ':value' => $value
                ]);
            }
            return true;
        } catch (PDOException $e) {
            error_log("Erreur updateSmtpSettings: " . $e->getMessage());
            return false;
        }
    }

    private function ensureSettingsTable()
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS settings (
                id INT(11) NOT NULL AUTO_INCREMENT,
                setting_key VARCHAR(100) NOT NULL,
                setting_value TEXT DEFAULT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                UNIQUE KEY setting_key (setting_key)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");

        try {
            $schema = (string)$this->pdo->query('SELECT DATABASE()')->fetchColumn();
            $stmt = $this->pdo->prepare("
                SELECT EXTRA
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_SCHEMA = ?
                  AND TABLE_NAME = 'settings'
                  AND COLUMN_NAME = 'id'
                LIMIT 1
            ");
            $stmt->execute([$schema]);
            $extra = strtolower((string)$stmt->fetchColumn());

            if (!str_contains($extra, 'auto_increment')) {
                $this->pdo->exec("ALTER TABLE settings MODIFY id INT(11) NOT NULL AUTO_INCREMENT");
            }
        } catch (Exception $e) {
            error_log("Erreur correction settings.id: " . $e->getMessage());
        }

        try {
            $this->pdo->exec("ALTER TABLE settings ADD UNIQUE KEY setting_key (setting_key)");
        } catch (Exception $e) {
            // La clé existe déjà dans les installations normales.
        }
    }

    /**
     * Tester la configuration SMTP
     */
    public function testSmtp($toEmail, $message = '')
    {
        try {
            // Récupérer les paramètres SMTP depuis la BDD
            $smtp = $this->getSmtpSettings();

            // Vérifier que la configuration est complète
            if (empty($smtp['smtp_host']) || empty($smtp['smtp_username']) || empty($smtp['smtp_password'])) {
                return ['success' => false, 'error' => 'Configuration SMTP incomplète'];
            }

            $fromEmail = $smtp['smtp_from_email'] ?? $smtp['smtp_username'];

            // Corps du message
            $body = "Bonjour,\n\n";
            $body .= "Ceci est un email de test envoyé depuis la plateforme NDIGITMARKET.\n\n";
            $body .= "La configuration SMTP fonctionne correctement.\n\n";
            if (!empty($message)) {
                $body .= "Message personnel :\n" . $message . "\n\n";
            }
            $body .= "---\n";
            $body .= "Serveur : " . $smtp['smtp_host'] . "\n";
            $body .= "Port : " . $smtp['smtp_port'] . "\n";
            $body .= "Sécurité : " . $smtp['smtp_encryption'] . "\n";
            $body .= "Expéditeur : " . $fromEmail . "\n\n";
            $body .= "© " . date('Y') . " NDIGITMARKET";

            $this->sendSmtpMail($smtp, $toEmail, 'Test SMTP - NDIGITMARKET', nl2br($body));

            return ['success' => true, 'message' => 'Email envoyé avec succès'];
        } catch (Exception $e) {
            error_log("Erreur SMTP test: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function smtpRead($socket, array $expectedCodes)
    {
        $response = '';
        while (($line = fgets($socket, 515)) !== false) {
            $response .= $line;
            if (isset($line[3]) && $line[3] === ' ') {
                break;
            }
        }

        $code = (int)substr($response, 0, 3);
        if (!in_array($code, $expectedCodes, true)) {
            throw new Exception(trim($response) ?: 'Réponse SMTP inattendue');
        }

        return $response;
    }

    private function smtpCommand($socket, $command, array $expectedCodes)
    {
        fwrite($socket, $command . "\r\n");
        return $this->smtpRead($socket, $expectedCodes);
    }

    private function sendSmtpMail(array $smtp, $toEmail, $subject, $htmlBody)
    {
        $host = trim((string)$smtp['smtp_host']);
        $port = (int)($smtp['smtp_port'] ?? 587);
        $encryption = strtoupper((string)($smtp['smtp_encryption'] ?? 'TLS'));
        $remote = ($encryption === 'SSL' ? 'ssl://' : '') . $host;
        $socket = @stream_socket_client($remote . ':' . $port, $errno, $errstr, 20);

        if (!$socket) {
            throw new Exception("Connexion SMTP impossible : $errstr");
        }

        stream_set_timeout($socket, 20);

        try {
            $this->smtpRead($socket, [220]);
            $serverName = $_SERVER['SERVER_NAME'] ?? 'localhost';
            $this->smtpCommand($socket, 'EHLO ' . $serverName, [250]);

            if ($encryption === 'TLS') {
                $this->smtpCommand($socket, 'STARTTLS', [220]);
                if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                    throw new Exception('Activation TLS impossible');
                }
                $this->smtpCommand($socket, 'EHLO ' . $serverName, [250]);
            }

            $this->smtpCommand($socket, 'AUTH LOGIN', [334]);
            $this->smtpCommand($socket, base64_encode((string)$smtp['smtp_username']), [334]);
            $this->smtpCommand($socket, base64_encode((string)$smtp['smtp_password']), [235]);

            $fromEmail = trim((string)($smtp['smtp_from_email'] ?? $smtp['smtp_username']));
            $fromName = trim((string)($smtp['smtp_from_name'] ?? 'NDIGITMARKET'));
            $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
            $encodedFromName = '=?UTF-8?B?' . base64_encode($fromName) . '?=';

            $this->smtpCommand($socket, 'MAIL FROM:<' . $fromEmail . '>', [250]);
            $this->smtpCommand($socket, 'RCPT TO:<' . $toEmail . '>', [250, 251]);
            $this->smtpCommand($socket, 'DATA', [354]);

            $body = str_replace(["\r\n", "\r"], "\n", (string)$htmlBody);
            $body = str_replace("\n.", "\n..", $body);
            $data = [
                'Date: ' . date('r'),
                'From: ' . $encodedFromName . ' <' . $fromEmail . '>',
                'Reply-To: ' . $fromEmail,
                'To: <' . $toEmail . '>',
                'Subject: ' . $encodedSubject,
                'MIME-Version: 1.0',
                'Content-Type: text/html; charset=UTF-8',
                'Content-Transfer-Encoding: 8bit',
                '',
                $body,
                '.',
            ];
            fwrite($socket, implode("\r\n", $data) . "\r\n");
            $this->smtpRead($socket, [250]);
            $this->smtpCommand($socket, 'QUIT', [221]);
            fclose($socket);
            return true;
        } catch (Exception $e) {
            @fwrite($socket, "QUIT\r\n");
            @fclose($socket);
            throw $e;
        }
    }
}