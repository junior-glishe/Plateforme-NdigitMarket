<?php

use CategorieController;

session_start();

require_once __DIR__ . '/../app/config/database.php';

$url = $_GET['url'] ?? 'dashboard';

switch ($url) {

    // ===== GESTION DES CATÉGORIES =====
    case 'categories':
        require_once __DIR__ . '/../App/Controllers/Admin/CategorieController.php';
        $controller = new CategorieController($pdo);
        $controller->index();
        break;
    
    // Ajouter une catégorie
    case 'categories_add':
        require_once __DIR__ . '/../App/Controllers/Admin/CategorieController.php';
        $controller = new CategorieController($pdo);
        $controller->add();
        break;
    
    // Récupérer une catégorie (AJAX)
    case 'categories_get':
        require_once __DIR__ . '/../App/Controllers/Admin/CategorieController.php';
        $controller = new CategorieController($pdo);
        $controller->getCategory();
        break;
    
    // Mettre à jour une catégorie
    case 'categories_update':
        require_once __DIR__ . '/../App/Controllers/Admin/CategorieController.php';
        $controller = new CategorieController($pdo);
        $controller->update();
        break;
    
    // Mettre à jour l'ordre
    case 'categories_update_order':
        require_once __DIR__ . '/../App/Controllers/Admin/CategorieController.php';
        $controller = new CategorieController($pdo);
        $controller->updateOrder();
        break;
    
    // Changer le statut
    case 'categories_toggle':
        require_once __DIR__ . '/../App/Controllers/Admin/CategorieController.php';
        $controller = new CategorieController($pdo);
        $controller->toggleStatus();
        break;
    
    // Supprimer une catégorie
    case 'categories_delete':
        require_once __DIR__ . '/../App/Controllers/Admin/CategorieController.php';
        $controller = new CategorieController($pdo);
        $controller->delete();
        break;
    
    // Fusionner des catégories
    case 'categories_merge':
        require_once __DIR__ . '/../App/Controllers/Admin/CategorieController.php';
        $controller = new CategorieController($pdo);
        $controller->merge();
        break;

        
//     case 'contenus':
//         require_once __DIR__ . '/../app/controllers/StudentsController.php';
//         $controller = new StudentsController($pdo);
//         $controller->index();
//         break;

//     case 'notifications':
//         require_once __DIR__ . '/../app/controllers/LoginController.php';
//         $controller = new LoginController($pdo);
//         $controller->index();
//         break;
        
//     case 'rapport_stats':
//         require_once __DIR__ . '/../app/controllers/LoginController.php';
//         $controller = new LoginController($pdo);
//         $controller->index();
//         break;
        
//     case 'avis_commentaires':
//         require_once __DIR__ . '/../app/controllers/LoginController.php';
//         $controller = new LoginController($pdo);
//         $controller->index();
//         break;
        
//    case 'parametres_systemes':
//         require_once __DIR__ . '/../app/controllers/LoginController.php';
//         $controller = new LoginController($pdo);
//         $controller->index();
//         break;
        
//    case 'logs_audits':
//         require_once __DIR__ . '/../app/controllers/LoginController.php';
//         $controller = new LoginController($pdo);
//         $controller->index();
//         break;
        
//    case 'login':
//         require_once __DIR__ . '/../app/controllers/LoginController.php';
//         $controller = new LoginController($pdo);
//         $controller->index();
//         break;
	
    
//     case 'logout':
//         require_once __DIR__ . '/../app/controllers/LogoutController.php';
//         $controller = new LogoutController();
//         $controller->index();
//         break;

    default:
        echo "404 - Page introuvable";
}
