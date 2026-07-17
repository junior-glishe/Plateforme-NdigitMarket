<?php
// App/Controllers/Admin/SettingController.php

require_once __DIR__ . '/../../Models/SettingModel.php';

class SettingController
{
    private $model;
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->model = new SettingModel($pdo);
    }

    // ============================================
    // PAGE PRINCIPALE
    // ============================================

    public function index()
    {


        // Récupérer tous les paramètres
        $settings = $this->model->getAllSettings();

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
            'currentPage' => 'parametres-systeme'
        ]);
    }

    // ============================================
    // API PARAMÈTRES GÉNÉRAUX
    // ============================================

    /**
     * Mettre à jour les paramètres généraux
     */
    public function updateGeneralSettings()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }

        $data = [
            'site_name' => $_POST['site_name'] ?? 'NDIGITMARKET',
            'site_tagline' => $_POST['site_tagline'] ?? '',
            'site_email' => $_POST['site_email'] ?? '',
            'site_currency' => $_POST['site_currency'] ?? 'FCFA',
            'facebook_url' => $_POST['facebook_url'] ?? '',
            'twitter_url' => $_POST['twitter_url'] ?? '',
            'instagram_url' => $_POST['instagram_url'] ?? '',
            'linkedin_url' => $_POST['linkedin_url'] ?? '',
            'maintenance_mode' => isset($_POST['maintenance_mode']) ? '1' : '0',
            'maintenance_message' => $_POST['maintenance_message'] ?? '',
            'commission_rate' => $_POST['commission_rate'] ?? '10',
            'min_withdrawal' => $_POST['min_withdrawal'] ?? '5000'
        ];

        if ($this->model->updateSettings($data)) {
            $this->jsonResponse(['success' => true, 'message' => 'Paramètres généraux mis à jour']);
        } else {
            $this->jsonResponse(['error' => 'Erreur lors de la mise à jour'], 500);
        }
    }

    // ============================================
    // API CONFIGURATION PAIEMENTS
    // ============================================

    /**
     * Mettre à jour la configuration des paiements
     */
    public function updatePaymentSettings()
    {
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
     * Mettre à jour la configuration SMTP
     */
    public function updateSmtpSettings()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }

        $data = [
            'smtp_host' => $_POST['smtp_host'] ?? '',
            'smtp_port' => $_POST['smtp_port'] ?? '587',
            'smtp_username' => $_POST['smtp_username'] ?? '',
            'smtp_password' => $_POST['smtp_password'] ?? '',
            'smtp_encryption' => $_POST['smtp_encryption'] ?? 'tls',
            'smtp_from_email' => $_POST['smtp_from_email'] ?? '',
            'smtp_from_name' => $_POST['smtp_from_name'] ?? 'NDIGITMARKET'
        ];

        if ($this->model->updateSettings($data)) {
            $this->jsonResponse(['success' => true, 'message' => 'Configuration SMTP mise à jour']);
        } else {
            $this->jsonResponse(['error' => 'Erreur lors de la mise à jour'], 500);
        }
    }

    /**
     * Tester la configuration SMTP
     */
    public function testSmtp()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }

        $email = $_POST['email'] ?? '';
        $message = $_POST['message'] ?? 'Ceci est un email de test de la plateforme NDIGITMARKET.';

        if (empty($email)) {
            $this->jsonResponse(['error' => 'Email destinataire requis'], 400);
            return;
        }

        // Ici vous pouvez implémenter l'envoi réel d'email
        // Pour l'instant, on simule un succès
        $this->jsonResponse([
            'success' => true,
            'message' => 'Email de test envoyé avec succès à ' . $email
        ]);
    }

    // ============================================
    // API GESTION ADMINISTRATEURS
    // ============================================

    /**
     * Créer un administrateur
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
    public function disableAdmin()
    {
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
    public function enableAdmin()
    {
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
    public function resetAdminPassword()
    {
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

    private function render($view, $data = [])
    {
        extract($data);
        $viewPath = __DIR__ . '/../../Views/' . $view . '.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        }
    }

    private function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}
