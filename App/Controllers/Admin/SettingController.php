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
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    error_log("=== updateGeneralSettings START ===");
    error_log("POST: " . print_r($_POST, true));
    error_log("FILES: " . print_r($_FILES, true));
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'error' => 'Methode non autorisee']);
        exit();
    }
    
    try {
        // Récupérer les données POST
        $data = [];
        foreach ($_POST as $key => $value) {
            $data[$key] = trim($value);
        }
        
        // Récupérer les fichiers
        $files = [];
        if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] === UPLOAD_ERR_OK) {
            $files['site_logo'] = $_FILES['site_logo'];
        }
        if (isset($_FILES['site_favicon']) && $_FILES['site_favicon']['error'] === UPLOAD_ERR_OK) {
            $files['site_favicon'] = $_FILES['site_favicon'];
        }
        
        $result = $this->model->updateGeneralSettings($data, $files);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Parametres mis a jour']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Erreur lors de la mise a jour']);
        }
        
    } catch (Exception $e) {
        error_log("EXCEPTION: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
    exit();
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
        echo json_encode(['success' => false, 'error' => 'Methode non autorisee']);
        exit();
    }

    $data = [
        'payment_mobile_money' => isset($_POST['payment_mobile_money']) ? '1' : '0',
        'payment_card' => isset($_POST['payment_card']) ? '1' : '0',
        'payment_bank_transfer' => isset($_POST['payment_bank_transfer']) ? '1' : '0',
        'payment_paypal' => isset($_POST['payment_paypal']) ? '1' : '0',
        'fedapay_mode' => trim($_POST['fedapay_mode'] ?? 'test'),
        'fedapay_public_key' => trim($_POST['fedapay_public_key'] ?? ''),
        'fedapay_secret_key' => trim($_POST['fedapay_secret_key'] ?? ''),
        'min_order_amount' => trim($_POST['min_order_amount'] ?? '500'),
        'max_order_amount' => trim($_POST['max_order_amount'] ?? '5000000'),
        'refund_days' => trim($_POST['refund_days'] ?? '7'),
        'vendor_payout_days' => trim($_POST['vendor_payout_days'] ?? '30')
    ];

    $result = $this->model->updateSettings($data);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Configuration paiements mise a jour']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Erreur lors de la mise a jour']);
    }
    exit();
}

    // ============================================
    // API CONFIGURATION SMTP
    // ============================================

       /**
 * Mettre à jour les paramètres SMTP
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
    /**
 * Creer un administrateur
 */
public function createAdmin() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'error' => 'Methode non autorisee']);
        exit();
    }

    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mdp = $_POST['mdp'] ?? ''; // CHANGÉ: password → mdp
    $role = $_POST['role'] ?? 'Admin';
    $telephone = trim($_POST['telephone'] ?? '');

    if (empty($nom) || empty($email) || empty($mdp)) {
        echo json_encode(['success' => false, 'error' => 'Tous les champs sont requis']);
        exit();
    }

    if (strlen($mdp) < 8) {
        echo json_encode(['success' => false, 'error' => 'Le mot de passe doit contenir au moins 8 caracteres']);
        exit();
    }

    // Verifier si l'email existe deja dans admin
    $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM admin WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch(PDO::FETCH_ASSOC)['total'] > 0) {
        echo json_encode(['success' => false, 'error' => 'Cet email est deja utilise']);
        exit();
    }

    $data = [
        'nom' => $nom,
        'email' => $email,
        'mdp' => $mdp, // CHANGÉ: password → mdp
        'role' => $role,
        'telephone' => $telephone
    ];

    if ($this->model->createAdmin($data)) {
        echo json_encode(['success' => true, 'message' => 'Administrateur cree avec succes']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Erreur lors de la creation']);
    }
    exit();
}

    /**
 * Mettre à jour un administrateur
 */
public function updateAdmin() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'error' => 'Methode non autorisee']);
        exit();
    }

    $id = (int)($_POST['id'] ?? 0);
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? 'Admin';
    $mdp = $_POST['mdp'] ?? ''; // CHANGÉ: password → mdp
    $telephone = trim($_POST['telephone'] ?? '');

    if (!$id || empty($nom) || empty($email)) {
        echo json_encode(['success' => false, 'error' => 'Tous les champs sont requis']);
        exit();
    }

    $data = [
        'nom' => $nom,
        'email' => $email,
        'role' => $role,
        'telephone' => $telephone
    ];

    if (!empty($mdp)) {
        if (strlen($mdp) < 8) {
            echo json_encode(['success' => false, 'error' => 'Le mot de passe doit contenir au moins 8 caracteres']);
            exit();
        }
        $data['mdp'] = $mdp; // CHANGÉ: password → mdp
    }

    if ($this->model->updateAdmin($id, $data)) {
        echo json_encode(['success' => true, 'message' => 'Administrateur mis a jour']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Erreur lors de la mise a jour']);
    }
    exit();
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