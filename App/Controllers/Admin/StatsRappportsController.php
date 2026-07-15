<?php
// App/Controllers/Admin/StatsRappportsController.php

require_once __DIR__ . '/../../Models/StatsRappportsModel.php';

class StatsRappportsController {
    private $model;
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new StatsRappportsModel($pdo);
    }

    // ============================================
    // PAGE PRINCIPALE
    // ============================================
   public function index() {
    $period = $_GET['period'] ?? 'month';
    $chartPeriod = $_GET['chart_period'] ?? 'week';
    
    // Récupérer les stats générales
    $stats = $this->model->getGeneralStats($period);
    $topProducts = $this->model->getTopProducts(5);
    $topViewedProducts = $this->model->getTopViewedProducts(5);
    $topCategories = $this->model->getTopCategories();
    $geoDistribution = $this->model->getGeoDistribution();
    $chartData = $this->model->getChartData($chartPeriod);
    
    // Récupérer les données des rapports
    $salesData = $this->model->getSalesReportData();
    $salesSummary = $this->model->getSalesReportSummary();
    $financialData = $this->model->getFinancialReportData();
    $financialSummary = $this->model->getFinancialReportSummary();
    $usersData = $this->model->getUsersReportData();
    $usersSummary = $this->model->getUsersReportSummary();
    $vendorsData = $this->model->getVendorsReportData();
    $vendorsSummary = $this->model->getVendorsReportSummary();
    $categoriesList = $this->model->getCategoriesList();
    $vendorsList = $this->model->getVendorsList();
    
    $this->render('admin/rapport-stat', [
        'stats' => $stats,
        'topProducts' => $topProducts,
        'topViewedProducts' => $topViewedProducts,
        'topCategories' => $topCategories,
        'geoDistribution' => $geoDistribution,
        'chartData' => $chartData,
        'chartPeriod' => $chartPeriod,
        'salesData' => $salesData,
        'salesSummary' => $salesSummary,
        'financialData' => $financialData,
        'financialSummary' => $financialSummary,
        'usersData' => $usersData,
        'usersSummary' => $usersSummary,
        'vendorsData' => $vendorsData,
        'vendorsSummary' => $vendorsSummary,
        'categoriesList' => $categoriesList,
        'vendorsList' => $vendorsList,
        'currentPage' => 'rapport-stat'
    ]);
}

    // ============================================
    // API STATISTIQUES
    // ============================================

    public function getGeneralStats() {
        $period = $_GET['period'] ?? 'month';
        $stats = $this->model->getGeneralStats($period);
        $this->jsonResponse(['success' => true, 'data' => $stats]);
    }

    public function getTopProducts() {
        $limit = (int)($_GET['limit'] ?? 5);
        $data = $this->model->getTopProducts($limit);
        $this->jsonResponse(['success' => true, 'data' => $data]);
    }

    public function getTopViewedProducts() {
        $limit = (int)($_GET['limit'] ?? 5);
        $data = $this->model->getTopViewedProducts($limit);
        $this->jsonResponse(['success' => true, 'data' => $data]);
    }

    public function getTopCategories() {
        $data = $this->model->getTopCategories();
        $this->jsonResponse(['success' => true, 'data' => $data]);
    }

    public function getGeoDistribution() {
        $data = $this->model->getGeoDistribution();
        $this->jsonResponse(['success' => true, 'data' => $data]);
    }

    // ============================================
    // API GRAPHIQUES - MISE À JOUR DYNAMIQUE
    // ============================================

    public function getChartDataAPI() {
        $period = $_GET['period'] ?? 'week';
        $data = $this->model->getChartData($period);
        $this->jsonResponse(['success' => true, 'data' => $data]);
    }

    // ============================================
    // API RAPPORTS
    // ============================================

    public function generateSalesReport() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }

        $startDate = $_POST['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $_POST['end_date'] ?? date('Y-m-d');
        $format = $_POST['format'] ?? 'json';

        $data = $this->model->getSalesReport($startDate, $endDate);

        if ($format === 'csv') {
            $this->model->exportToCSV($data, 'rapport_ventes_' . date('Y-m-d'));
            return;
        }

        $totalVentes = count($data);
        $caTotal = array_sum(array_column($data, 'prix'));
        $panierMoyen = $totalVentes > 0 ? round($caTotal / $totalVentes, 2) : 0;
        
        $jours = [];
        foreach ($data as $row) {
            $jours[$row['date']] = ($jours[$row['date']] ?? 0) + $row['prix'];
        }
        arsort($jours);
        $meilleurJour = key($jours);

        $this->jsonResponse([
            'success' => true,
            'data' => $data,
            'summary' => [
                'total_ventes' => $totalVentes,
                'ca_total' => $caTotal,
                'panier_moyen' => $panierMoyen,
                'meilleur_jour' => $meilleurJour
            ]
        ]);
    }

    public function generateFinancialReport() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }

        $startDate = $_POST['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $_POST['end_date'] ?? date('Y-m-d');
        $format = $_POST['format'] ?? 'json';

        $data = $this->model->getFinancialReport($startDate, $endDate);

        if ($format === 'csv') {
            $this->model->exportToCSV($data, 'rapport_financier_' . date('Y-m-d'));
            return;
        }

        $caTotal = array_sum(array_column($data, 'ca'));
        $commissionPlateforme = array_sum(array_column($data, 'commission_plateforme'));
        $commissionVendeurs = array_sum(array_column($data, 'commission_vendeurs'));
        $versements = array_sum(array_column($data, 'versements'));
        $soldeDu = array_sum(array_column($data, 'solde_du'));

        $this->jsonResponse([
            'success' => true,
            'data' => $data,
            'summary' => [
                'ca_total' => $caTotal,
                'commission_plateforme' => $commissionPlateforme,
                'commission_vendeurs' => $commissionVendeurs,
                'versements' => $versements,
                'solde_du' => $soldeDu
            ]
        ]);
    }

    public function generateUsersReport() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }

        $startDate = $_POST['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $_POST['end_date'] ?? date('Y-m-d');
        $format = $_POST['format'] ?? 'json';

        $data = $this->model->getUsersReport($startDate, $endDate);

        if ($format === 'csv') {
            $this->model->exportToCSV($data, 'rapport_utilisateurs_' . date('Y-m-d'));
            return;
        }

        $totalInscriptions = array_sum(array_column($data, 'inscriptions'));
        $totalConnexions = array_sum(array_column($data, 'connexions'));
        $totalAcheteurs = array_sum(array_column($data, 'acheteurs_actifs'));
        $totalDesabonnements = array_sum(array_column($data, 'desabonnements'));
        $tauxRetention = $totalInscriptions > 0 ? round((($totalInscriptions - $totalDesabonnements) / $totalInscriptions) * 100, 1) : 0;

        $this->jsonResponse([
            'success' => true,
            'data' => $data,
            'summary' => [
                'total_inscriptions' => $totalInscriptions,
                'total_connexions' => $totalConnexions,
                'total_acheteurs' => $totalAcheteurs,
                'total_desabonnements' => $totalDesabonnements,
                'taux_retention' => $tauxRetention
            ]
        ]);
    }

    public function generateVendorsReport() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }

        $startDate = $_POST['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $_POST['end_date'] ?? date('Y-m-d');
        $format = $_POST['format'] ?? 'json';

        $data = $this->model->getVendorsReport($startDate, $endDate);

        if ($format === 'csv') {
            $this->model->exportToCSV($data, 'rapport_vendeurs_' . date('Y-m-d'));
            return;
        }

        $totalVendeurs = count($data);
        $totalProduits = array_sum(array_column($data, 'produits'));
        $totalVentes = array_sum(array_column($data, 'ventes'));
        $totalCA = array_sum(array_column($data, 'ca'));
        $revenuMoyen = $totalVendeurs > 0 ? round($totalCA / $totalVendeurs, 2) : 0;

        $this->jsonResponse([
            'success' => true,
            'data' => $data,
            'summary' => [
                'total_vendeurs' => $totalVendeurs,
                'total_produits' => $totalProduits,
                'total_ventes' => $totalVentes,
                'total_ca' => $totalCA,
                'revenu_moyen' => $revenuMoyen
            ]
        ]);
    }

    // ============================================
    // EXPORT
    // ============================================

    public function exportReport() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }

        $type = $_POST['type'] ?? 'sales';
        $format = $_POST['format'] ?? 'csv';
        $startDate = $_POST['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $_POST['end_date'] ?? date('Y-m-d');

        $data = [];
        $filename = '';

        switch($type) {
            case 'sales':
                $data = $this->model->getSalesReport($startDate, $endDate);
                $filename = 'rapport_ventes_' . date('Y-m-d');
                break;
            case 'financial':
                $data = $this->model->getFinancialReport($startDate, $endDate);
                $filename = 'rapport_financier_' . date('Y-m-d');
                break;
            case 'users':
                $data = $this->model->getUsersReport($startDate, $endDate);
                $filename = 'rapport_utilisateurs_' . date('Y-m-d');
                break;
            case 'vendors':
                $data = $this->model->getVendorsReport($startDate, $endDate);
                $filename = 'rapport_vendeurs_' . date('Y-m-d');
                break;
            default:
                $this->jsonResponse(['error' => 'Type de rapport inconnu'], 400);
                return;
        }

        if ($format === 'csv') {
            $this->model->exportToCSV($data, $filename);
            return;
        }

        $this->jsonResponse([
            'success' => true,
            'message' => 'Rapport exporté avec succès',
            'data' => $data,
            'format' => $format
        ]);
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




    // ============================================
// RAPPORTS EXPORTABLES
// ============================================

/**
 * Récupérer les données pour le rapport financier
 */
public function getFinancialReportData() {
    $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
    $endDate = $_GET['end_date'] ?? date('Y-m-d');
    
    $data = $this->model->getFinancialReport($startDate, $endDate);
    
    $summary = [
        'ca_total' => array_sum(array_column($data, 'ca')),
        'commission_plateforme' => array_sum(array_column($data, 'commission_plateforme')),
        'commission_vendeurs' => array_sum(array_column($data, 'commission_vendeurs')),
        'versements' => array_sum(array_column($data, 'versements')),
        'solde_du' => array_sum(array_column($data, 'solde_du'))
    ];
    
    $this->jsonResponse([
        'success' => true,
        'data' => $data,
        'summary' => $summary
    ]);
}

/**
 * Récupérer les données pour le rapport utilisateurs
 */
public function getUsersReportData() {
    $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
    $endDate = $_GET['end_date'] ?? date('Y-m-d');
    
    $data = $this->model->getUsersReport($startDate, $endDate);
    
    $summary = [
        'total_inscriptions' => array_sum(array_column($data, 'inscriptions')),
        'total_connexions' => array_sum(array_column($data, 'connexions')),
        'total_acheteurs' => array_sum(array_column($data, 'acheteurs_actifs')),
        'total_desabonnements' => array_sum(array_column($data, 'desabonnements')),
        'taux_retention' => 0
    ];
    
    $totalInscriptions = $summary['total_inscriptions'];
    $totalDesabonnements = $summary['total_desabonnements'];
    $summary['taux_retention'] = $totalInscriptions > 0 ? round((($totalInscriptions - $totalDesabonnements) / $totalInscriptions) * 100, 1) : 0;
    
    $this->jsonResponse([
        'success' => true,
        'data' => $data,
        'summary' => $summary
    ]);
}

/**
 * Récupérer les données pour le rapport vendeurs
 */
public function getVendorsReportData() {
    $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
    $endDate = $_GET['end_date'] ?? date('Y-m-d');
    
    $data = $this->model->getVendorsReport($startDate, $endDate);
    
    $summary = [
        'total_vendeurs' => count($data),
        'total_produits' => array_sum(array_column($data, 'produits')),
        'total_ventes' => array_sum(array_column($data, 'ventes')),
        'total_ca' => array_sum(array_column($data, 'ca')),
        'revenu_moyen' => 0
    ];
    
    $totalVendeurs = $summary['total_vendeurs'];
    $summary['revenu_moyen'] = $totalVendeurs > 0 ? round($summary['total_ca'] / $totalVendeurs, 2) : 0;
    
    $this->jsonResponse([
        'success' => true,
        'data' => $data,
        'summary' => $summary
    ]);
}
}