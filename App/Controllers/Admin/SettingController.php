<?php
// App/Controllers/Admin/SettingController.php

require_once __DIR__ . '/../../Models/SettingModel.php';

class SettingController {
    private $model;
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new SettingModel($pdo);
    }

    // ============================================
    // PAGE PRINCIPALE
    // ============================================

    public function index() {        
        // Récupérer tous les paramètres
        $settings = $this->model->getAllSettings();

         $smtpSettings = $this->model->getSmtpSettings();
        
        // Récupérer les administrateurs
        $admins = $this->model->getAdmins();
        $adminStats = $this->model->countAdmins();
        
        // Récupérer la dernière connexion
        $lastConnection = '';
        if (!empty($admins)) {
            $lastAdmin = $admins[0];
            $lastConnection = $lastAdmin['derniere_connexion'] ?? '-';
        }
        
        $this->render('admin/parametres-systeme', [
            'settings' => $settings,
            'admins' => $admins,
            'adminStats' => $adminStats,
            'lastConnection' => $lastConnection,
            'smtpSettings' => $smtpSettings, 
            'currentPage' => 'parametres-systeme'
        ]);
    }

    // ============================================
    // API PARAMÈTRES GÉNÉRAUX
    // ============================================
/**
 * Mettre à jour les paramètres généraux
 */
public function updateGeneralSettings() {
     //  FORCER L'AFFICHAGE DES ERREURS
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors', 1);
    
    //  LOG
    error_log("===  updateGeneralSettings START ===");
    error_log(" POST: " . print_r($_POST, true));
    error_log(" FILES: " . print_r($_FILES, true));
    
    try {
        // ... le reste du code
    } catch (Exception $e) {
        //  AFFICHER L'ERREUR DANS LA RÉPONSE
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        exit();
    }
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->jsonResponse(['success' => false, 'error' => 'Méthode non autorisée'], 405);
        return;
    }
    
    try {
        // 1. Récupérer TOUTES les données POST
        $data = [];
        foreach ($_POST as $key => $value) {
            $data[$key] = trim($value);
        }
        
        // 2. Récupérer les fichiers
        $files = [];
        if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] === UPLOAD_ERR_OK) {
            $files['site_logo'] = $_FILES['site_logo'];
        }
        if (isset($_FILES['site_favicon']) && $_FILES['site_favicon']['error'] === UPLOAD_ERR_OK) {
            $files['site_favicon'] = $_FILES['site_favicon'];
        }
        
        // 3. Appeler le modèle
        $result = $this->model->updateGeneralSettings($data, $files);
        
        if ($result) {
            $this->jsonResponse(['success' => true, 'message' => 'Paramètres mis à jour avec succès']);
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Erreur lors de la mise à jour'], 500);
        }
        
    } catch (Exception $e) {
        error_log(" Erreur: " . $e->getMessage());
        $this->jsonResponse(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

/**
 * API - Mettre à jour tous les paramètres (généraux + SMTP + paiement)
 */
public function updateAllSettings() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
        return;
    }
    
    // Récupérer toutes les données POST
    $data = [];
    foreach ($_POST as $key => $value) {
        $data[$key] = trim($value);
    }
    
    $result = $this->model->updateSettings($data);
    
    if ($result) {
        $this->jsonResponse(['success' => true, 'message' => 'Tous les paramètres ont été mis à jour']);
    } else {
        $this->jsonResponse(['success' => false, 'error' => 'Erreur lors de la mise à jour des paramètres'], 500);
    }
}

    // ============================================
    // API CONFIGURATION PAIEMENTS
    // ============================================

    /**
     * Mettre à jour la configuration des paiements
     */
    public function updatePaymentSettings() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }

        $data = [
            'fedapay_public_key' => $_POST['fedapay_public_key'] ?? '',
            'fedapay_secret_key' => $_POST['fedapay_secret_key'] ?? '',
            'fedapay_mode' => $_POST['fedapay_mode'] ?? 'test',
            'payment_mobile_money' => isset($_POST['payment_mobile_money']) ? '1' : '0',
            'payment_card' => isset($_POST['payment_card']) ? '1' : '0',
            'payment_bank_transfer' => isset($_POST['payment_bank_transfer']) ? '1' : '0',
            'payment_paypal' => isset($_POST['payment_paypal']) ? '1' : '0',
            'min_order_amount' => $_POST['min_order_amount'] ?? '500',
            'max_order_amount' => $_POST['max_order_amount'] ?? '5000000',
            'refund_days' => $_POST['refund_days'] ?? '7',
            'vendor_payout_days' => $_POST['vendor_payout_days'] ?? '30'
        ];

        if ($this->model->updateSettings($data)) {
            $this->jsonResponse(['success' => true, 'message' => 'Configuration paiements mise à jour']);
        } else {
            $this->jsonResponse(['error' => 'Erreur lors de la mise à jour'], 500);
        }
    }

    // ============================================
    // API CONFIGURATION SMTP
    // ============================================

        /**
         * API - Mettre à jour les paramètres SMTP
         */

        public function updateSmtpSettings() {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
                return;
            }
            
            // DEBUG - Voir les données POST reçues
            error_log("=== SMTP POST DATA ===");
            error_log(print_r($_POST, true));
            
            $data = [
                'smtp_host' => trim($_POST['smtp_host'] ?? ''),
                'smtp_port' => trim($_POST['smtp_port'] ?? ''),
                'smtp_username' => trim($_POST['smtp_username'] ?? ''),
                'smtp_password' => trim($_POST['smtp_password'] ?? ''),
                'smtp_encryption' => trim($_POST['smtp_encryption'] ?? 'TLS'),
                'smtp_from_email' => trim($_POST['smtp_from_email'] ?? ''),
                'smtp_from_name' => trim($_POST['smtp_from_name'] ?? '')
            ];
            
            $result = $this->model->updateSmtpSettings($data);
            
            if ($result) {
                $this->jsonResponse(['success' => true, 'message' => 'Paramètres SMTP mis à jour']);
            } else {
                $this->jsonResponse(['success' => false, 'error' => 'Erreur lors de la mise à jour des paramètres'], 500);
            }
        }
        /**
         * API - Tester SMTP
         */
        public function testSmtp() {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
                return;
            }
            
            $toEmail = trim($_POST['email'] ?? '');
            $message = trim($_POST['message'] ?? '');
            
            if (empty($toEmail)) {
                $this->jsonResponse(['success' => false, 'error' => 'Email destinataire requis'], 400);
                return;
            }
            
            if (!filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
                $this->jsonResponse(['success' => false, 'error' => 'Email invalide'], 400);
                return;
            }
            
            $result = $this->model->testSmtp($toEmail, $message);
            
            if ($result['success']) {
                $this->jsonResponse(['success' => true, 'message' => $result['message']]);
            } else {
                $this->jsonResponse(['success' => false, 'error' => $result['error'] ?? 'Erreur lors de l\'envoi'], 500);
            }
        }
                    

    // ============================================
    // API GESTION ADMINISTRATEURS
    // ============================================

    /**
     * Créer un administrateur
     */
    public function createAdmin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }

        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'admin';

        if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
            $this->jsonResponse(['error' => 'Tous les champs sont requis'], 400);
            return;
        }

        if (strlen($password) < 8) {
            $this->jsonResponse(['error' => 'Le mot de passe doit contenir au moins 8 caractères'], 400);
            return;
        }

        // Vérifier si l'email existe déjà
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch(PDO::FETCH_ASSOC)['total'] > 0) {
            $this->jsonResponse(['error' => 'Cet email est déjà utilisé'], 400);
            return;
        }

        $data = [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'password' => $password,
            'role' => $role
        ];

        if ($this->model->createAdmin($data)) {
            $this->jsonResponse(['success' => true, 'message' => 'Administrateur créé avec succès']);
        } else {
            $this->jsonResponse(['error' => 'Erreur lors de la création'], 500);
        }
    }

    /**
     * Mettre à jour un administrateur
     */
    public function updateAdmin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role'] ?? 'admin';
        $password = $_POST['password'] ?? '';

        if (!$id || empty($nom) || empty($prenom) || empty($email)) {
            $this->jsonResponse(['error' => 'Tous les champs sont requis'], 400);
            return;
        }

        $data = [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'role' => $role
        ];

        if (!empty($password)) {
            if (strlen($password) < 8) {
                $this->jsonResponse(['error' => 'Le mot de passe doit contenir au moins 8 caractères'], 400);
                return;
            }
            $data['password'] = $password;
        }

        if ($this->model->updateAdmin($id, $data)) {
            $this->jsonResponse(['success' => true, 'message' => 'Administrateur mis à jour']);
        } else {
            $this->jsonResponse(['error' => 'Erreur lors de la mise à jour'], 500);
        }
    }

    /**
     * Désactiver un administrateur
     */
    public function disableAdmin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            $this->jsonResponse(['error' => 'ID manquant'], 400);
            return;
        }

        if ($this->model->disableAdmin($id)) {
            $this->jsonResponse(['success' => true, 'message' => 'Administrateur désactivé']);
        } else {
            $this->jsonResponse(['error' => 'Erreur lors de la désactivation'], 500);
        }
    }

    /**
     * Réactiver un administrateur
     */
    public function enableAdmin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            $this->jsonResponse(['error' => 'ID manquant'], 400);
            return;
        }

        if ($this->model->enableAdmin($id)) {
            $this->jsonResponse(['success' => true, 'message' => 'Administrateur réactivé']);
        } else {
            $this->jsonResponse(['error' => 'Erreur lors de la réactivation'], 500);
        }
    }

    /**
     * Réinitialiser le mot de passe d'un administrateur
     */
    public function resetAdminPassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        $newPassword = $_POST['new_password'] ?? '';

        if (!$id || empty($newPassword)) {
            $this->jsonResponse(['error' => 'ID et nouveau mot de passe requis'], 400);
            return;
        }

        if (strlen($newPassword) < 8) {
            $this->jsonResponse(['error' => 'Le mot de passe doit contenir au moins 8 caractères'], 400);
            return;
        }

        if ($this->model->resetAdminPassword($id, $newPassword)) {
            $this->jsonResponse(['success' => true, 'message' => 'Mot de passe réinitialisé avec succès']);
        } else {
            $this->jsonResponse(['error' => 'Erreur lors de la réinitialisation'], 500);
        }
    }

    // ============================================
    // FONCTIONS UTILITAIRES
    // ============================================

    private function render($view, $data = []) {
        extract($data);
        $viewPath = __DIR__ . '/../../Views/' . $view . '.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        }
    }

    private function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}