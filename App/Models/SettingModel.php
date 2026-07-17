<?php
// App/Models/SettingModel.php

class SettingModel {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ============================================
    // PARAMÈTRES GÉNÉRAUX
    // ============================================

    /**
     * Récupérer tous les paramètres
     */
    public function getAllSettings() {
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
    public function getLastConnection() {
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
public function updateGeneralSettings($data, $files = []) {
    error_log("=== 🚀 START updateGeneralSettings (MODEL) ===");
    error_log("📊 DATA reçues: " . print_r($data, true));
    error_log("📁 FILES reçus: " . print_r($files, true));
    
    try {
        $success = true;
        
        // 🔥 DEBUG: Vérifier la connexion à la BDD
        if (!$this->pdo) {
            error_log("❌ CONNEXION PDO NULL !");
            return false;
        }
        error_log("✅ CONNEXION PDO OK");
        
        // Gérer l'upload du logo
        if (isset($files['site_logo']) && $files['site_logo']['error'] === UPLOAD_ERR_OK) {
            error_log("🔄 Upload du logo en cours...");
            $logoPath = $this->uploadFile($files['site_logo'], 'logo');
            if ($logoPath) {
                $data['site_logo'] = $logoPath;
                error_log("✅ Logo uploadé: " . $logoPath);
            } else {
                error_log("❌ Erreur upload logo");
                $success = false;
            }
        } else {
            error_log("ℹ️ Pas de logo à uploader");
        }
        
        // Gérer l'upload du favicon
        if (isset($files['site_favicon']) && $files['site_favicon']['error'] === UPLOAD_ERR_OK) {
            error_log("🔄 Upload du favicon en cours...");
            $faviconPath = $this->uploadFile($files['site_favicon'], 'favicon');
            if ($faviconPath) {
                $data['site_favicon'] = $faviconPath;
                error_log("✅ Favicon uploadé: " . $faviconPath);
            } else {
                error_log("❌ Erreur upload favicon");
                $success = false;
            }
        } else {
            error_log("ℹ️ Pas de favicon à uploader");
        }
        
        // Mettre à jour les paramètres
        error_log("🔄 Mise à jour des paramètres dans la BDD...");
        
        foreach ($data as $key => $value) {
            error_log("=== UPDATE KEY: $key = " . substr($value, 0, 100) . (strlen($value) > 100 ? '...' : '') . " ===");
            
            try {
                $result = $this->updateSetting($key, $value);
                if (!$result) {
                    error_log("❌ ERREUR pour la clé: $key");
                    $success = false;
                } else {
                    error_log("✅ OK pour la clé: $key");
                }
            } catch (Exception $e) {
                error_log("❌ EXCEPTION pour la clé $key: " . $e->getMessage());
                $success = false;
            }
        }
        
        error_log("=== FINAL RESULT: " . ($success ? '✅ SUCCESS' : '❌ FAILED') . " ===");
        return $success;
        
    } catch (Exception $e) {
        error_log("❌ EXCEPTION GLOBALE: " . $e->getMessage());
        error_log("❌ TRACE: " . $e->getTraceAsString());
        return false;
    }
}

/**
 * Mettre à jour un paramètre
 */
public function updateSetting($key, $value) {
    error_log("🔧 updateSetting: $key = " . substr($value, 0, 50) . (strlen($value) > 50 ? '...' : ''));
    
    try {
        // Vérifier la connexion PDO
        if (!$this->pdo) {
            error_log("❌ PDO NULL dans updateSetting");
            return false;
        }
        
        // 🔥 DEBUG: Vérifier si la table existe
        try {
            $checkTable = $this->pdo->query("SHOW TABLES LIKE 'settings'");
            if ($checkTable->rowCount() == 0) {
                error_log("❌ La table 'settings' n'existe pas !");
                return false;
            }
        } catch (Exception $e) {
            error_log("❌ Erreur vérification table: " . $e->getMessage());
            return false;
        }
        
        $stmt = $this->pdo->prepare("
            INSERT INTO settings (setting_key, setting_value) 
            VALUES (:key, :value) 
            ON DUPLICATE KEY UPDATE setting_value = :value
        ");
        
        $result = $stmt->execute([
            ':key' => $key,
            ':value' => $value
        ]);
        
        if ($result) {
            error_log("✅ updateSetting OK pour: $key");
        } else {
            error_log("❌ updateSetting FAILED pour: $key");
            error_log("❌ Erreur PDO: " . print_r($stmt->errorInfo(), true));
        }
        
        return $result;
        
    } catch (PDOException $e) {
        error_log("❌ PDOException updateSetting: " . $e->getMessage());
        error_log("❌ Code: " . $e->getCode());
        return false;
    } catch (Exception $e) {
        error_log("❌ Exception updateSetting: " . $e->getMessage());
        return false;
    }
}

/**
 * Upload d'un fichier avec debug
 */
private function uploadFile($file, $type = 'logo') {
    error_log("🔄 uploadFile: type=$type, name=" . $file['name']);
    
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
            error_log("❌ Erreur upload: " . $errorMsg);
            return false;
        }
        
        // 🔥 CORRECTION: Utiliser le bon dossier
        $uploadDir = __DIR__ . '/../../public/assets/images/';
        error_log("📁 Dossier upload: " . $uploadDir);
        
        // Créer le dossier s'il n'existe pas
        if (!is_dir($uploadDir)) {
            error_log("📁 Création du dossier: " . $uploadDir);
            if (!mkdir($uploadDir, 0777, true)) {
                error_log("❌ Impossible de créer le dossier: " . $uploadDir);
                return false;
            }
        }
        
        // Vérifier les droits
        if (!is_writable($uploadDir)) {
            error_log("❌ Dossier non accessible en écriture: " . $uploadDir);
            return false;
        }
        error_log("✅ Dossier accessible en écriture");
        
        // Vérifier le type de fichier
        $allowedTypes = ['image/jpeg', 'image/png', 'image/svg+xml', 'image/x-icon', 'image/vnd.microsoft.icon'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        error_log("📋 Type MIME: " . $mimeType);
        
        if (!in_array($mimeType, $allowedTypes)) {
            error_log("❌ Type de fichier non autorisé: " . $mimeType);
            return false;
        }
        
        // Vérifier la taille (max 2MB)
        if ($file['size'] > 2 * 1024 * 1024) {
            error_log("❌ Fichier trop volumineux: " . $file['size'] . " bytes");
            return false;
        }
        error_log("✅ Taille OK: " . $file['size'] . " bytes");
        
        // Générer un nom unique
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = $type . '_' . time() . '_' . uniqid() . '.' . $extension;
        $destination = $uploadDir . $filename;
        
        error_log("📁 Destination: " . $destination);
        
        // Supprimer l'ancien fichier si existe
        if ($type === 'logo') {
            $oldLogo = $this->getSetting('site_logo');
            if ($oldLogo && file_exists($uploadDir . $oldLogo)) {
                error_log("🗑️ Suppression ancien logo: " . $oldLogo);
                unlink($uploadDir . $oldLogo);
            }
        } elseif ($type === 'favicon') {
            $oldFavicon = $this->getSetting('site_favicon');
            if ($oldFavicon && file_exists($uploadDir . $oldFavicon)) {
                error_log("🗑️ Suppression ancien favicon: " . $oldFavicon);
                unlink($uploadDir . $oldFavicon);
            }
        }
        
        // Déplacer le fichier
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            error_log("✅ Fichier uploadé avec succès: " . $filename);
            return $filename;
        } else {
            error_log("❌ Erreur move_uploaded_file");
            return false;
        }
        
    } catch (Exception $e) {
        error_log("❌ EXCEPTION uploadFile: " . $e->getMessage());
        return false;
    }
}
    /**
     * Mettre à jour plusieurs paramètres
     */
    public function updateSettings($data) {
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
    public function getSetting($key) {
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
    public function getAdmins() {
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
    public function getAdminById($id) {
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
    public function countAdmins() {
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
                $data['image_auteur'] ?? 'uploads/default.png',
                $data['role']
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
            $params = [$data['nom'], $data['email'], $data['telephone'] ?? '', $data['role'], $id];
            
            // Si mot de passe fourni
            if (!empty($data['mdp'])) {
                $sql = "UPDATE admin SET nom = ?, email = ?, telephone = ?, mdp = ?, role = ? WHERE id_gestion = ?";
                $params = [$data['nom'], $data['email'], $data['telephone'] ?? '', password_hash($data['mdp'], PASSWORD_DEFAULT), $data['role'], $id];
            }
            
            // Si image fournie
            if (!empty($data['image_auteur'])) {
                $sql = "UPDATE admin SET nom = ?, email = ?, telephone = ?, image_auteur = ?, role = ? WHERE id_gestion = ?";
                $params = [$data['nom'], $data['email'], $data['telephone'] ?? '', $data['image_auteur'], $data['role'], $id];
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
    public function disableAdmin($id) {
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
    public function enableAdmin($id) {
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
    public function resetAdminPassword($id, $newPassword) {
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
    public function emailExists($email, $excludeId = null) {
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

    // App/Models/SettingModel.php

/**
 * Récupérer les paramètres SMTP depuis la BDD
 */
public function getSmtpSettings() {
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
public function updateSmtpSettings($data) {
    try {
        foreach ($data as $key => $value) {
            // Nettoyer la valeur
            $value = trim($value);
            
            // Mettre à jour avec UPDATE direct
            $stmt = $this->pdo->prepare("
                UPDATE settings 
                SET setting_value = :value, updated_at = NOW() 
                WHERE setting_key = :key
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







// App/Models/SettingModel.php

/**
 * Tester la configuration SMTP
 */
public function testSmtp($toEmail, $message = '') {
    try {
        // Récupérer les paramètres SMTP depuis la BDD
        $smtp = $this->getSmtpSettings();
        
        // Vérifier que la configuration est complète
        if (empty($smtp['smtp_host']) || empty($smtp['smtp_username']) || empty($smtp['smtp_password'])) {
            return ['success' => false, 'error' => 'Configuration SMTP incomplète'];
        }
        
        // Charger PHPMailer
        require_once __DIR__ . '/../../vendor/autoload.php';
        
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        
        // Configuration SMTP
        $mail->isSMTP();
        $mail->Host = $smtp['smtp_host'];
        $mail->SMTPAuth = true;
        $mail->Username = $smtp['smtp_username'];
        $mail->Password = $smtp['smtp_password'];
        $mail->SMTPSecure = $smtp['smtp_encryption'] === 'SSL' ? 'ssl' : ($smtp['smtp_encryption'] === 'TLS' ? 'tls' : '');
        $mail->Port = (int)($smtp['smtp_port'] ?? 587);
        $mail->CharSet = 'UTF-8';
        
        // Expéditeur
        $fromEmail = $smtp['smtp_from_email'] ?? $smtp['smtp_username'];
        $fromName = $smtp['smtp_from_name'] ?? 'NDIGITMARKET';
        $mail->setFrom($fromEmail, $fromName);
        $mail->addReplyTo($fromEmail, $fromName);
        
        // Destinataire
        $mail->addAddress($toEmail);
        
        // Contenu
        $mail->isHTML(true);
        $mail->Subject = 'Test SMTP - NDIGITMARKET';
        
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
        
        $mail->Body = nl2br($body);
        $mail->AltBody = $body;
        
        // Envoyer
        $mail->send();
        
        return ['success' => true, 'message' => 'Email envoyé avec succès'];
        
    } catch (\PHPMailer\PHPMailer\Exception $e) {
        error_log("Erreur SMTP test: " . $e->getMessage());
        return ['success' => false, 'error' => $e->getMessage()];
    } catch (Exception $e) {
        error_log("Erreur SMTP test: " . $e->getMessage());
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
}