<?php


require_once __DIR__ . '/../../Models/StatsRappportsModel.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class StatsRappportsController
{
    private $model;
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->model = new StatsRappportsModel($pdo);
    }

    // ============================================
    // PAGE PRINCIPALE
    // ============================================
    public function index()
    {
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

    public function getGeneralStats()
    {
        $period = $_GET['period'] ?? 'month';
        $stats = $this->model->getGeneralStats($period);
        $this->jsonResponse(['success' => true, 'data' => $stats]);
    }

    public function getTopProducts()
    {
        $limit = (int)($_GET['limit'] ?? 5);
        $data = $this->model->getTopProducts($limit);
        $this->jsonResponse(['success' => true, 'data' => $data]);
    }

    public function getTopViewedProducts()
    {
        $limit = (int)($_GET['limit'] ?? 5);
        $data = $this->model->getTopViewedProducts($limit);
        $this->jsonResponse(['success' => true, 'data' => $data]);
    }

    public function getTopCategories()
    {
        $data = $this->model->getTopCategories();
        $this->jsonResponse(['success' => true, 'data' => $data]);
    }

    public function getGeoDistribution()
    {
        $data = $this->model->getGeoDistribution();
        $this->jsonResponse(['success' => true, 'data' => $data]);
    }



    // ============================================
    // API GRAPHIQUES - MISE À JOUR DYNAMIQUE
    // ============================================

    public function getChartDataAPI()
    {
        $period = $_GET['period'] ?? 'week';

        // Valider la période
        $allowed = ['week', 'month', 'quarter', 'year'];
        if (!in_array($period, $allowed)) {
            $period = 'week';
        }

        $data = $this->model->getChartData($period);

        $this->jsonResponse([
            'success' => true,
            'data' => $data
        ]);
    }

    // ============================================
    // API RAPPORTS
    // ============================================

    public function generateSalesReport()
    {
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

    public function generateFinancialReport()
    {
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

    public function generateUsersReport()
    {
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

    public function generateVendorsReport()
    {
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


    public function exportReport()
    {
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
        $headers = [];

        switch ($type) {
            case 'sales':
                $data = $this->model->getSalesReport($startDate, $endDate);
                $filename = 'rapport_ventes_' . date('Y-m-d');
                $headers = ['Date', 'Produit', 'Vendeur', 'Catégorie', 'Prix (FCFA)', 'Commission (FCFA)', 'Statut'];
                break;
            case 'financial':
                $data = $this->model->getFinancialReport($startDate, $endDate);
                $filename = 'rapport_financier_' . date('Y-m-d');
                $headers = ['Mois', 'CA (FCFA)', 'Commission Plateforme', 'Commission Vendeurs', 'Versements', 'Solde Dû'];
                break;
            case 'users':
                $data = $this->model->getUsersReport($startDate, $endDate);
                $filename = 'rapport_utilisateurs_' . date('Y-m-d');
                $headers = ['Date', 'Inscriptions', 'Connexions', 'Acheteurs Actifs', 'Désabonnements'];
                break;
            case 'vendors':
                $data = $this->model->getVendorsReport($startDate, $endDate);
                $filename = 'rapport_vendeurs_' . date('Y-m-d');
                $headers = ['Vendeur', 'Email', 'Produits', 'Ventes', 'CA (FCFA)', 'Commission (FCFA)', 'Note Moyenne'];
                break;
            default:
                $this->jsonResponse(['error' => 'Type de rapport inconnu'], 400);
                return;
        }

        // Export selon le format
        switch ($format) {
            case 'csv':
                $this->exportCSV($data, $filename, $headers);
                break;
            case 'excel':
                $this->exportExcel($data, $filename, $headers);
                break;
            case 'pdf':
                $this->exportPDF($data, $filename, $headers, $type);
                break;
            default:
                $this->jsonResponse(['error' => 'Format non supporté'], 400);
        }
    }

    // ============================================
    // EXPORT CSV
    // ============================================
    private function exportCSV($data, $filename, $headers = [])
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename . '.csv');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM pour UTF-8

        // Entêtes
        if (!empty($headers)) {
            fputcsv($output, $headers);
        } elseif (!empty($data)) {
            fputcsv($output, array_keys($data[0]));
        }

        // Données
        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit();
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




    // ============================================
// RAPPORTS EXPORTABLES
// ============================================

    /**
     * Récupérer les données pour le rapport financier
     */
    public function getFinancialReportData()
    {
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
    public function getUsersReportData()
    {
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
    public function getVendorsReportData()
    {
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

    /// ============================================
    // EXPORT PDF - VERSION SIMPLE ET QUI MARCHE
    // ============================================

    private function exportPDF($data, $filename, $headers = [], $type = 'sales')
    {
        require_once __DIR__ . '/../../../vendor/autoload.php';

        try {
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'L',
                'margin_top' => 20,
                'margin_bottom' => 20,
                'margin_left' => 15,
                'margin_right' => 15,
                'default_font_size' => 10,
                'default_font' => 'dejavusans'
            ]);

            // Titre
            $title = '';
            switch ($type) {
                case 'sales':
                    $title = 'Rapport de ventes';
                    break;
                case 'financial':
                    $title = 'Rapport financier';
                    break;
                case 'users':
                    $title = 'Rapport utilisateurs';
                    break;
                case 'vendors':
                    $title = 'Rapport vendeurs';
                    break;
                default:
                    $title = 'Rapport';
            }

            // Générer le HTML
            $html = $this->generateSimplePDFHTML($data, $headers, $title);

            $mpdf->WriteHTML($html);
            $mpdf->Output($filename . '.pdf', 'D');
            exit();
        } catch (Exception $e) {
            die(" Erreur PDF: " . $e->getMessage());
        }
    }

    /**
     * Génération HTML simplifiée
     */
    private function generateSimplePDFHTML($data, $headers, $title)
    {
        $html = '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>' . $title . '</title>
        <style>
            body { font-family: DejaVu Sans, sans-serif; font-size: 10pt; margin: 20px; }
            h1 { color: #0EA486; text-align: center; border-bottom: 2px solid #0EA486; padding-bottom: 10px; }
            .subtitle { text-align: center; color: #666; margin-bottom: 20px; }
            table { width: 100%; border-collapse: collapse; margin-top: 10px; }
            th { background: #0EA486; color: #fff; padding: 8px; text-align: left; }
            td { padding: 6px 8px; border-bottom: 1px solid #ddd; }
            tr:nth-child(even) { background: #f9f9f9; }
            .footer { text-align: center; color: #999; margin-top: 20px; border-top: 1px solid #ddd; padding-top: 10px; font-size: 8pt; }
            .badge-success { background: #d4edda; color: #155724; padding: 2px 8px; border-radius: 4px; display: inline-block; }
            .badge-warning { background: #fff3cd; color: #856404; padding: 2px 8px; border-radius: 4px; display: inline-block; }
            .badge-info { background: #d1ecf1; color: #0c5460; padding: 2px 8px; border-radius: 4px; display: inline-block; }
            .no-data { text-align: center; color: #999; padding: 40px; }
        </style>
    </head>
    <body>
        <h1>' . htmlspecialchars($title) . '</h1>
        <div class="subtitle">Généré le ' . date('d/m/Y à H:i') . '</div>';

        if (!empty($data)) {
            $html .= '<table><thead><tr>';
            foreach ($headers as $header) {
                $html .= '<th>' . htmlspecialchars($header) . '</th>';
            }
            $html .= '</tr></thead><tbody>';

            foreach ($data as $row) {
                $html .= '<tr>';
                foreach ($row as $key => $value) {
                    if (is_numeric($value)) {
                        $html .= '<td>' . number_format((float)$value, 0, ',', ' ') . '</td>';
                    } elseif ($key === 'statut') {
                        $class = $value === 'livree' ? 'badge-success' : ($value === 'en_attente' ? 'badge-warning' : 'badge-info');
                        $html .= '<td><span class="' . $class . '">' . htmlspecialchars($value) . '</span></td>';
                    } else {
                        $html .= '<td>' . htmlspecialchars($value) . '</td>';
                    }
                }
                $html .= '</tr>';
            }
            $html .= '</tbody></table>';
        } else {
            $html .= '<div class="no-data">Aucune donnée disponible</div>';
        }

        $html .= '<div class="footer">&copy; ' . date('Y') . ' NDIGITMARKET</div>
    </body>
    </html>';

        return $html;
    }



    // ============================================
    // EXPORT EXCEL (PhpSpreadsheet)
    // ============================================// 

    private function exportExcel($data, $filename, $headers = [])
    {
        require_once __DIR__ . '/../../../vendor/autoload.php';



        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Style des entêtes
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0EA486']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];

        // Entêtes
        $col = 0; // Commencer à 0 pour utiliser la méthode columnIndexFromString
        if (!empty($headers)) {
            foreach ($headers as $header) {
                $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
                $sheet->setCellValue($column . '1', $header);
                $sheet->getColumnDimension($column)->setAutoSize(true);
                $col++;
            }
        } elseif (!empty($data)) {
            foreach (array_keys($data[0]) as $header) {
                $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
                $sheet->setCellValue($column . '1', $header);
                $sheet->getColumnDimension($column)->setAutoSize(true);
                $col++;
            }
        }

        // Appliquer le style aux entêtes
        $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray($headerStyle);
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Données
        $rowNum = 2;
        foreach ($data as $row) {
            $col = 0;
            foreach ($row as $value) {
                $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
                if (is_numeric($value) && strpos((string)$value, '.') !== false) {
                    $sheet->setCellValue($column . $rowNum, (float)$value);
                    $sheet->getStyle($column . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00');
                } elseif (is_numeric($value)) {
                    $sheet->setCellValue($column . $rowNum, (int)$value);
                } else {
                    $sheet->setCellValue($column . $rowNum, $value);
                }
                $col++;
            }
            $rowNum++;
        }

        // Bordures pour toutes les cellules
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E5E7EB']
                ]
            ]
        ];
        $sheet->getStyle('A1:' . $lastColumn . ($rowNum - 1))->applyFromArray($styleArray);

        // En-tête HTTP
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
}
