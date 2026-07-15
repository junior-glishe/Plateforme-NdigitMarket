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
            $stmt = $this->pdo->query("SELECT * FROM settings");
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Transformer en tableau clé-valeur
            $settings = [];
            foreach ($result as $row) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
            return $settings;
        } catch (PDOException $e) {
            error_log("Erreur get all settings: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupérer un paramètre par sa clé
     */
    public function getSetting($key) {
        try {
            $stmt = $this->pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
            $stmt->execute([$key]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['setting_value'] ?? null;
        } catch (PDOException $e) {
            error_log("Erreur get setting: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Mettre à jour un paramètre
     */
    public function updateSetting($key, $value) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO settings (setting_key, setting_value, updated_at) 
                VALUES (?, ?, NOW()) 
                ON DUPLICATE KEY UPDATE setting_value = ?, updated_at = NOW()
            ");
            return $stmt->execute([$key, $value, $value]);
        } catch (PDOException $e) {
            error_log("Erreur update setting: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Mettre à jour plusieurs paramètres
     */
    public function updateSettings($data) {
        try {
            $this->pdo->beginTransaction();
            
            foreach ($data as $key => $value) {
                $stmt = $this->pdo->prepare("
                    INSERT INTO settings (setting_key, setting_value, updated_at) 
                    VALUES (?, ?, NOW()) 
                    ON DUPLICATE KEY UPDATE setting_value = ?, updated_at = NOW()
                ");
                $stmt->execute([$key, $value, $value]);
            }
            
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Erreur update settings: " . $e->getMessage());
            return false;
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

    /**
     * Créer la table settings si elle n'existe pas
     */
    public function createSettingsTable() {
        $sql = "
            CREATE TABLE IF NOT EXISTS settings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                setting_key VARCHAR(100) UNIQUE NOT NULL,
                setting_value TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ";
        try {
            $this->pdo->exec($sql);
            return true;
        } catch (PDOException $e) {
            error_log("Erreur create settings table: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Initialiser les paramètres par défaut
     */
    public function initDefaultSettings() {
        $defaults = [
            'site_name' => 'NDIGITMARKET',
            'site_tagline' => 'Le marketplace digital de référence en Afrique',
            'site_email' => 'contact@ndigitmarket.com',
            'site_currency' => 'FCFA',
            'commission_rate' => '10',
            'min_withdrawal' => '5000',
            'min_order_amount' => '500',
            'max_order_amount' => '5000000',
            'refund_days' => '7',
            'vendor_payout_days' => '30',
            'maintenance_mode' => '0',
            'maintenance_message' => 'Nous effectuons une maintenance. Le site sera bientôt de retour...',
            'facebook_url' => '',
            'twitter_url' => '',
            'instagram_url' => '',
            'linkedin_url' => '',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => '587',
            'smtp_username' => 'noreply@ndigitmarket.com',
            'smtp_password' => '',
            'smtp_encryption' => 'tls',
            'smtp_from_email' => 'noreply@ndigitmarket.com',
            'smtp_from_name' => 'NDIGITMARKET',
            'fedapay_public_key' => 'pub_test_xxxxxxxxxx',
            'fedapay_secret_key' => 'sec_test_xxxxxxxxxx',
            'fedapay_mode' => 'test',
            'payment_mobile_money' => '1',
            'payment_card' => '1',
            'payment_bank_transfer' => '0',
            'payment_paypal' => '0'
        ];

        foreach ($defaults as $key => $value) {
            $this->updateSetting($key, $value);
        }
        return true;
    }
}