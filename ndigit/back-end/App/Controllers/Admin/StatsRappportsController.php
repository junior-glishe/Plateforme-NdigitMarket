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
    
    // Récupérer les stats depuis le modèle
    $stats = $this->model->getGeneralStats($period);
    $topProducts = $this->model->getTopProducts(5);
    $topCategories = $this->model->getTopCategories();
    $geoDistribution = $this->model->getGeoDistribution();
    
    // Récupérer les données d'évolution
    $evolutionData = $this->model->getEvolutionData();
    
    $this->render('admin/rapport-stat', [
        'stats' => $stats,
        'topProducts' => $topProducts,
        'topCategories' => $topCategories,
        'geoDistribution' => $geoDistribution,
        'evolutionData' => $evolutionData
    ]);
}


    // ============================================
    // API STATISTIQUES
    // ============================================

    /**
     * Récupérer les statistiques générales
     */
    public function getGeneralStats() {
        $period = $_GET['period'] ?? 'month';
        $stats = $this->model->getGeneralStats($period);
        $this->jsonResponse(['success' => true, 'data' => $stats]);
    }

    /**
     * Récupérer les top produits
     */
    public function getTopProducts() {
        $limit = (int)($_GET['limit'] ?? 5);
        $data = $this->model->getTopProducts($limit);
        $this->jsonResponse(['success' => true, 'data' => $data]);
    }

    /**
     * Récupérer les top produits vus
     */
    public function getTopViewedProducts() {
        $limit = (int)($_GET['limit'] ?? 5);
        $data = $this->model->getTopViewedProducts($limit);
        $this->jsonResponse(['success' => true, 'data' => $data]);
    }

    /**
     * Récupérer les catégories performantes
     */
    public function getTopCategories() {
        $data = $this->model->getTopCategories();
        $this->jsonResponse(['success' => true, 'data' => $data]);
    }

    /**
     * Récupérer la répartition géographique
     */
    public function getGeoDistribution() {
        $data = $this->model->getGeoDistribution();
        $this->jsonResponse(['success' => true, 'data' => $data]);
    }

    // ============================================
    // API RAPPORTS
    // ============================================

    /**
     * Générer un rapport de ventes
     */
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

        // Résumé
        $totalVentes = count($data);
        $caTotal = array_sum(array_column($data, 'prix'));
        $panierMoyen = $totalVentes > 0 ? round($caTotal / $totalVentes, 2) : 0;
        
        // Meilleur jour
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

    /**
     * Générer un rapport financier
     */
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

        // Résumé
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

    /**
     * Générer un rapport utilisateurs
     */
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

        // Résumé
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

    /**
     * Générer un rapport vendeurs
     */
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

        // Résumé
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

    /**
     * Exporter un rapport
     */
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

    // ==========================================   ==
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