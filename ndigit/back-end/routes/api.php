<?php


session_start();

require_once __DIR__ . '/../config/database.php';

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
    case 'categories_edit':
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

    case 'categories_filter':
        require_once __DIR__ . '/../App/Controllers/Admin/CategorieController.php';
        $controller = new CategorieController($pdo);
        $controller->filter();
        break;

        
    case 'contenus':
        require_once __DIR__ . '/../App/Controllers/Admin/ContenuController.php';
        $controller = new ContenuController($pdo);
        $controller->index();
        break;

        // ============================================
    // BANNIÈRES
    // ============================================
    case 'bannieres':
        require_once __DIR__ . '/../App/Controllers/Admin/ContenuController.php';
        $controller = new ContenuController($pdo);
        $controller->index();
        break;

    case 'banniere_get':
        require_once __DIR__ . '/../App/Controllers/Admin/ContenuController.php';
        $controller = new ContenuController($pdo);
        $controller->getBanniere();
        break;

    case 'banniere_add':
        require_once __DIR__ . '/../App/Controllers/Admin/ContenuController.php';
        $controller = new ContenuController($pdo);
        $controller->addBanniere();
        break;

    case 'banniere_edit':
        require_once __DIR__ . '/../App/Controllers/Admin/ContenuController.php';
        $controller = new ContenuController($pdo);
        $controller->updateBanniere();
        break;

    case 'banniere_toggle':
        require_once __DIR__ . '/../App/Controllers/Admin/ContenuController.php';
        $controller = new ContenuController($pdo);
        $controller->toggleBanniere();
        break;

    case 'banniere_update_order':
        require_once __DIR__ . '/../App/Controllers/Admin/ContenuController.php';
        $controller = new ContenuController($pdo);
        $controller->updateBanniereOrder();
        break;

    case 'banniere_delete':
        require_once __DIR__ . '/../App/Controllers/Admin/ContenuController.php';
        $controller = new ContenuController($pdo);
        $controller->deleteBanniere();
        break;

    case 'banniere_stats':
        require_once __DIR__ . '/../App/Controllers/Admin/ContenuController.php';
        $controller = new ContenuController($pdo);
        $controller->getBanniereStats();
        break;

    // ============================================
    // CODES PROMO
    // ============================================
    case 'promo_generate':
        require_once __DIR__ . '/../App/Controllers/Admin/ContenuController.php';
        $controller = new ContenuController($pdo);
        $controller->generateCode();
        break;

    case 'promo_get':
        require_once __DIR__ . '/../App/Controllers/Admin/ContenuController.php';
        $controller = new ContenuController($pdo);
        $controller->getCodePromo();
        break;

    case 'promo_add':
        require_once __DIR__ . '/../App/Controllers/Admin/ContenuController.php';
        $controller = new ContenuController($pdo);
        $controller->addCodePromo();
        break;

    case 'promo_edit':
        require_once __DIR__ . '/../App/Controllers/Admin/ContenuController.php';
        $controller = new ContenuController($pdo);
        $controller->updateCodePromo();
        break;

    case 'promo_toggle':
        require_once __DIR__ . '/../App/Controllers/Admin/ContenuController.php';
        $controller = new ContenuController($pdo);
        $controller->toggleCodePromo();
        break;

    case 'promo_delete':
        require_once __DIR__ . '/../App/Controllers/Admin/ContenuController.php';
        $controller = new ContenuController($pdo);
        $controller->deleteCodePromo();
        break;

    case 'promo_history':
        require_once __DIR__ . '/../App/Controllers/Admin/ContenuController.php';
        $controller = new ContenuController($pdo);
        $controller->getPromoHistorique();
        break;
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
