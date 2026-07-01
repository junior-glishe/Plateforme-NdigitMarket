<?php

session_start();

require_once __DIR__ . '/../app/config/database.php';

$url = $_GET['url'] ?? 'dashboard';

switch ($url) {

    case 'dashboard_admin':
        require_once __DIR__ . '/../app/controllers/DashboardSuperAdminController.php';
        $controller = new DashboardSuperAdminController($pdo);
        $controller->index();
        break;

    case 'gestion_utilisateurs':
        require_once __DIR__ . '/../app/controllers/DashboardAdminController.php';
        $controller = new DashboardAdminController($pdo);
        $controller->index();
        break;
    
    case 'gestion_vendeurs':
        require_once __DIR__ . '/../app/controllers/DashboardAdminController.php';
        $controller = new DashboardAdminController($pdo);
        $controller->adminSettings();
        break;

    case 'gestion_produits':
        require_once __DIR__ . '/../app/controllers/DashboardAgentController.php';
        $controller = new DashboardAgentController($pdo);
        $controller->index();
        break;

    case 'gestion_commandes':
        require_once __DIR__ . '/../app/controllers/SetupSchoolController.php';
        $controller = new SetupSchoolController($pdo);
        $controller->index();
        break;

    case 'financieres_commissions':
        require_once __DIR__ . '/../app/controllers/SchoolController.php';
        $controller = new SchoolController($pdo);
        $controller->identity();
        break;


    case 'categories':
        require_once __DIR__ . '/../app/controllers/ManageAgentController.php';
        $controller = new ManageAgentController($pdo);
        $controller->index();
        break;
        
    case 'contenus':
        require_once __DIR__ . '/../app/controllers/StudentsController.php';
        $controller = new StudentsController($pdo);
        $controller->index();
        break;

    case 'notifications':
        require_once __DIR__ . '/../app/controllers/LoginController.php';
        $controller = new LoginController($pdo);
        $controller->index();
        break;
        
    case 'rapport_stats':
        require_once __DIR__ . '/../app/controllers/LoginController.php';
        $controller = new LoginController($pdo);
        $controller->index();
        break;
        
    case 'avis_commentaires':
        require_once __DIR__ . '/../app/controllers/LoginController.php';
        $controller = new LoginController($pdo);
        $controller->index();
        break;
        
   case 'parametres_systemes':
        require_once __DIR__ . '/../app/controllers/LoginController.php';
        $controller = new LoginController($pdo);
        $controller->index();
        break;
        
   case 'logs_audits':
        require_once __DIR__ . '/../app/controllers/LoginController.php';
        $controller = new LoginController($pdo);
        $controller->index();
        break;
        
   case 'login':
        require_once __DIR__ . '/../app/controllers/LoginController.php';
        $controller = new LoginController($pdo);
        $controller->index();
        break;
	
    
    case 'logout':
        require_once __DIR__ . '/../app/controllers/LogoutController.php';
        $controller = new LogoutController();
        $controller->index();
        break;

    default:
        echo "404 - Page introuvable";
}
