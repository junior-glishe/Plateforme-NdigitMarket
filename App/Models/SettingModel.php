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
     * Mettre à jour un paramètre
     */
    public function updateSetting($key, $value) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO settings (setting_key, setting_value) 
                VALUES (:key, :value) 
                ON DUPLICATE KEY UPDATE setting_value = :value
            ");
            return $stmt->execute([
                ':key' => $key,
                ':value' => $value
            ]);
        } catch (PDOException $e) {
            error_log("Erreur updateSetting: " . $e->getMessage());
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
                    id_uti as id,
                    nom,
                    prenom,
                    email,
                    type,
                    statut,
                    created_at as date_creation,
                    derniere_connexion
                FROM utilisateur 
                WHERE type = 'admin' OR type = 'super_admin'
                ORDER BY created_at DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur get admins: " . $e->getMessage());
            return [];
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
                    SUM(CASE WHEN type = 'super_admin' THEN 1 ELSE 0 END) as super_admins
                FROM utilisateur 
                WHERE type = 'admin' OR type = 'super_admin'
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
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            
            $stmt = $this->pdo->prepare("
                INSERT INTO utilisateur (nom, prenom, email, type, statut, mdp, created_at) 
                VALUES (?, ?, ?, ?, 'actif', ?, NOW())
            ");
            return $stmt->execute([
                $data['nom'],
                $data['prenom'],
                $data['email'],
                $data['role'] ?? 'admin',
                $hashedPassword
            ]);
        } catch (PDOException $e) {
            error_log("Erreur create admin: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Mettre à jour un administrateur
     */
    public function updateAdmin($id, $data) {
        try {
            $sql = "UPDATE utilisateur SET nom = ?, prenom = ?, email = ?, type = ? WHERE id_uti = ?";
            $params = [$data['nom'], $data['prenom'], $data['email'], $data['role'], $id];
            
            if (!empty($data['password'])) {
                $sql = "UPDATE utilisateur SET nom = ?, prenom = ?, email = ?, type = ?, mdp = ? WHERE id_uti = ?";
                $params = [$data['nom'], $data['prenom'], $data['email'], $data['role'], password_hash($data['password'], PASSWORD_DEFAULT), $id];
            }
            
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Erreur update admin: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Désactiver un administrateur
     */
    public function disableAdmin($id) {
        try {
            $stmt = $this->pdo->prepare("UPDATE utilisateur SET statut = 'desactive' WHERE id_uti = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erreur disable admin: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Réactiver un administrateur
     */
    public function enableAdmin($id) {
        try {
            $stmt = $this->pdo->prepare("UPDATE utilisateur SET statut = 'actif' WHERE id_uti = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erreur enable admin: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Réinitialiser le mot de passe d'un administrateur
     */
    public function resetAdminPassword($id, $newPassword) {
        try {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("UPDATE utilisateur SET mdp = ? WHERE id_uti = ?");
            return $stmt->execute([$hashedPassword, $id]);
        } catch (PDOException $e) {
            error_log("Erreur reset password: " . $e->getMessage());
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