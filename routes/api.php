<?php


session_start();

require_once __DIR__ . '/../config/database.php';

// Récupérer la connexion PDO
$pdo = Database::getConnection();


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

    case 'promo_list':
        require_once __DIR__ . '/../App/Controllers/Admin/ContenuController.php';
        $controller = new ContenuController($pdo);
        $controller->listCodesPromo();
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


        // ============================================
    // NOTIFICATIONS
    // ============================================
    case 'notifications':
        require_once __DIR__ . '/../App/Controllers/Admin/NotificationController.php';
        $controller = new NotificationController($pdo);
        $controller->index();
        break;

    case 'notifications_list':
        require_once __DIR__ . '/../App/Controllers/Admin/NotificationController.php';
        $controller = new NotificationController($pdo);
        $controller->getNotifications();
        break;

    case 'notifications_mark_read':
        require_once __DIR__ . '/../App/Controllers/Admin/NotificationController.php';
        $controller = new NotificationController($pdo);
        $controller->markRead();
        break;

    case 'notifications_mark_all_read':
        require_once __DIR__ . '/../App/Controllers/Admin/NotificationController.php';
        $controller = new NotificationController($pdo);
        $controller->markAllRead();
        break;

    case 'notifications_delete':
        require_once __DIR__ . '/../App/Controllers/Admin/NotificationController.php';
        $controller = new NotificationController($pdo);
        $controller->delete();
        break;

    case 'notifications_settings':
        require_once __DIR__ . '/../App/Controllers/Admin/NotificationController.php';
        $controller = new NotificationController($pdo);
        $controller->updateSettings();
        break;


        // ============================================
    // EMAILS TRANSACTIONNELS
    // ============================================
    case 'email_templates_list':
        require_once __DIR__ . '/../App/Controllers/Admin/NotificationController.php';
        $controller = new NotificationController($pdo);        $controller->getTemplates();
        break;

    case 'email_template_toggle':
        require_once __DIR__ . '/../App/Controllers/Admin/NotificationController.php';
        $controller = new NotificationController($pdo);        $controller->toggleTemplate();
        break;

    case 'email_template_update':
        require_once __DIR__ . '/../App/Controllers/Admin/NotificationController.php';
        $controller = new NotificationController($pdo);        $controller->updateTemplate();
        break;
        
    // ============================================
    // CAMPAGNES EMAILS EN MASSE
    // ============================================
    case 'campaign_stats':
        require_once __DIR__ . '/../App/Controllers/Admin/NotificationController.php';
        $controller = new NotificationController($pdo);
        $controller->getCampaignStats();
        break;

    case 'campaign_duplicate':
        require_once __DIR__ . '/../App/Controllers/Admin/NotificationController.php';
        $controller = new NotificationController($pdo);
        $controller->duplicateCampaign();
        break;

    case 'campaign_cancel':
        require_once __DIR__ . '/../App/Controllers/Admin/NotificationController.php';
        $controller = new NotificationController($pdo);
        $controller->cancelCampaign();
        break;

    case 'campaign_export_csv':
        require_once __DIR__ . '/../App/Controllers/Admin/NotificationController.php';
        $controller = new NotificationController($pdo);
        $controller->exportCampaignCSV();
        break;

    // ============================================
    // CAMPAGNES EMAILS EN MASSE - COMPOSER
    // ============================================
    case 'campaign_send':
        require_once __DIR__ . '/../App/Controllers/Admin/NotificationController.php';
        $controller = new NotificationController($pdo);
        $controller->sendCampaign();
        break;

    case 'campaign_test':
        require_once __DIR__ . '/../App/Controllers/Admin/NotificationController.php';
        $controller = new NotificationController($pdo);
        $controller->testCampaign();
        break;


    //    // ============================================
    // STATISTIQUES ET RAPPORTS
    // ============================================

    case 'rapport-stat':
        require_once __DIR__ . '/../App/Controllers/Admin/StatsRappportsController.php';
        $controller = new StatsRappportsController($pdo);
        $controller->index();
        break;
    // Statistiques générales
    case 'stats_general':
        require_once __DIR__ . '/../App/Controllers/Admin/StatsRappportsController.php';
        $controller = new StatsRappportsController($pdo);
        $controller->getGeneralStats();
        break;

    // Top produits
    case 'stats_top_products':
        require_once __DIR__ . '/../App/Controllers/Admin/StatsRappportsController.php';
        $controller = new StatsRappportsController($pdo);
        $controller->getTopProducts();
        break;

    // Top produits vus
    case 'stats_top_viewed':
        require_once __DIR__ . '/../App/Controllers/Admin/StatsRappportsController.php';
        $controller = new StatsRappportsController($pdo);
        $controller->getTopViewedProducts();
        break;

    // Top catégories
    case 'stats_top_categories':
        require_once __DIR__ . '/../App/Controllers/Admin/StatsRappportsController.php';
        $controller = new StatsRappportsController($pdo);
        $controller->getTopCategories();
        break;

    // Graphiques - données dynamiques
    case 'stats_chart_data':
        require_once __DIR__ . '/../App/Controllers/Admin/StatsRappportsController.php';
        $controller = new StatsRappportsController($pdo);
        $controller->getChartDataAPI();
        break;

    // Répartition géographique
    case 'stats_geo_distribution':
        require_once __DIR__ . '/../App/Controllers/Admin/StatsRappportsController.php';
        $controller = new StatsRappportsController($pdo);
        $controller->getGeoDistribution();
        break;

        // Rapports exportables
    case 'report_sales_generate':
        require_once __DIR__ . '/../App/Controllers/Admin/StatsRappportsController.php';
        $controller = new StatsRappportsController($pdo);
        $controller->generateSalesReport();
        break;

    case 'report_financial_data':
        require_once __DIR__ . '/../App/Controllers/Admin/StatsRappportsController.php';
        $controller = new StatsRappportsController($pdo);
        $controller->getFinancialReportData();
        break;

    case 'report_users_data':
        require_once __DIR__ . '/../App/Controllers/Admin/StatsRappportsController.php';
        $controller = new StatsRappportsController($pdo);
        $controller->getUsersReportData();
        break;

    case 'report_vendors_data':
        require_once __DIR__ . '/../App/Controllers/Admin/StatsRappportsController.php';
        $controller = new StatsRappportsController($pdo);
        $controller->getVendorsReportData();
        break;

    case 'export_report':
        require_once __DIR__ . '/../App/Controllers/Admin/StatsRappportsController.php';
        $controller = new StatsRappportsController($pdo);
        $controller->exportReport();
        break;
        
            
    // ============================================
    // PARAMÈTRES SYSTÈME
    // ============================================

    // Page des paramètres
    case 'parametres-systeme':
        require_once __DIR__ . '/../App/Controllers/Admin/SettingController.php';
        $controller = new SettingController($pdo);
        $controller->index();
        break;

    // API - Paramètres généraux
    case 'settings_general_update':
        require_once __DIR__ . '/../App/Controllers/Admin/SettingController.php';
        $controller = new SettingController($pdo);
        $controller->updateGeneralSettings();
        break;

    // API - Configuration paiements
    case 'settings_payment_update':
        require_once __DIR__ . '/../App/Controllers/Admin/SettingController.php';
        $controller = new SettingController($pdo);
        $controller->updatePaymentSettings();
        break;

    // API - Configuration SMTP
    case 'settings_smtp_update':
        require_once __DIR__ . '/../App/Controllers/Admin/SettingController.php';
        $controller = new SettingController($pdo);
        $controller->updateSmtpSettings();
        break;

    // API - Test SMTP
    case 'settings_smtp_test':
        require_once __DIR__ . '/../App/Controllers/Admin/SettingController.php';
        $controller = new SettingController($pdo);
        $controller->testSmtp();
        break;

    // API - Créer administrateur
    case 'settings_admin_create':
        require_once __DIR__ . '/../App/Controllers/Admin/SettingController.php';
        $controller = new SettingController($pdo);
        $controller->createAdmin();
        break;

    // API - Mettre à jour administrateur
    case 'settings_admin_update':
        require_once __DIR__ . '/../App/Controllers/Admin/SettingController.php';
        $controller = new SettingController($pdo);
        $controller->updateAdmin();
        break;

    // API - Désactiver administrateur
    case 'settings_admin_disable':
        require_once __DIR__ . '/../App/Controllers/Admin/SettingController.php';
        $controller = new SettingController($pdo);
        $controller->disableAdmin();
        break;

    // API - Réactiver administrateur
    case 'settings_admin_enable':
        require_once __DIR__ . '/../App/Controllers/Admin/SettingController.php';
        $controller = new SettingController($pdo);
        $controller->enableAdmin();
        break;

    // API - Réinitialiser mot de passe admin
    case 'settings_admin_reset_password':
        require_once __DIR__ . '/../App/Controllers/Admin/SettingController.php';
        $controller = new SettingController($pdo);
        $controller->resetAdminPassword();
        break;

        // ============================================
    // LOGS & AUDIT
    // ============================================

    // Page principale
    case 'logs-audit':
        require_once __DIR__ . '/../App/Controllers/Admin/LogsAuditController.php';
        $controller = new LogsAuditController($pdo);
        $controller->index();
        break;

    // API - Statistiques
    case 'logs_stats':
        require_once __DIR__ . '/../App/Controllers/Admin/LogsAuditController.php';
        $controller = new LogsAuditController($pdo);
        $controller->getStats();
        break;

    // API - Liste des logs
    case 'logs_list':
        require_once __DIR__ . '/../App/Controllers/Admin/LogsAuditController.php';
        $controller = new LogsAuditController($pdo);
        $controller->getLogs();
        break;

    // API - Détail d'un log
    case 'logs_detail':
        require_once __DIR__ . '/../App/Controllers/Admin/LogsAuditController.php';
        $controller = new LogsAuditController($pdo);
        $controller->getLogDetail();
        break;

    // API - Graphique
    case 'logs_chart':
        require_once __DIR__ . '/../App/Controllers/Admin/LogsAuditController.php';
        $controller = new LogsAuditController($pdo);
        $controller->getChartData();
        break;

    // API - Export CSV
    case 'logs_export':
        require_once __DIR__ . '/../App/Controllers/Admin/LogsAuditController.php';
        $controller = new LogsAuditController($pdo);
        $controller->exportCSV();
        break;

    // API - Rétention
    case 'logs_retention':
        require_once __DIR__ . '/../App/Controllers/Admin/LogsAuditController.php';
        $controller = new LogsAuditController($pdo);
        $controller->updateRetention();
        break;

    // API - Bloquer IP
    case 'logs_block_ip':
        require_once __DIR__ . '/../App/Controllers/Admin/LogsAuditController.php';
        $controller = new LogsAuditController($pdo);
        $controller->blockIP();
        break;
        


    default:
        echo "404 - Page introuvable";
}
