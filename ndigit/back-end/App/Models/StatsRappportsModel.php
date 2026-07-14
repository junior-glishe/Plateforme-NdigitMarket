<?php
// App/Models/StatsRappportsModel.php

class StatsRappportsModel {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ============================================
    // STATISTIQUES GÉNÉRALES
    // ============================================

    public function getGeneralStats($period = 'month') {
        $stats = [
            'inscriptions' => 0,
            'ventes' => 0,
            'ca' => 0,
            'taux_conversion' => 0,
            'evolution_inscriptions' => 0,
            'evolution_ventes' => 0,
            'evolution_ca' => 0,
            'evolution' => [
                'inscriptions' => [],
                'ventes' => []
            ]
        ];

        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM utilisateur WHERE type != 'admin'");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['inscriptions'] = (int)($result['total'] ?? 0);
        } catch (PDOException $e) {
            error_log("Erreur inscriptions: " . $e->getMessage());
        }

        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM commande");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['ventes'] = (int)($result['total'] ?? 0);
        } catch (PDOException $e) {
            error_log("Erreur ventes: " . $e->getMessage());
        }

        try {
            $stmt = $this->pdo->query("SELECT SUM(CAST(prix AS DECIMAL(10,2))) as total FROM commande");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['ca'] = (float)($result['total'] ?? 0);
        } catch (PDOException $e) {
            error_log("Erreur CA: " . $e->getMessage());
        }

        $stats['taux_conversion'] = $stats['inscriptions'] > 0 ? round(($stats['ventes'] / $stats['inscriptions']) * 100, 1) : 0;

        // Calcul des évolutions
        try {
            $stmt = $this->pdo->query("
                SELECT COUNT(*) as total 
                FROM utilisateur 
                WHERE type != 'admin' 
                AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            ");
            $moisActuel = (int)($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
            
            $stmt = $this->pdo->query("
                SELECT COUNT(*) as total 
                FROM utilisateur 
                WHERE type != 'admin' 
                AND created_at BETWEEN DATE_SUB(CURDATE(), INTERVAL 60 DAY) AND DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            ");
            $moisPrecedent = (int)($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
            
            if ($moisPrecedent > 0) {
                $stats['evolution_inscriptions'] = round((($moisActuel - $moisPrecedent) / $moisPrecedent) * 100, 1);
            }
        } catch (PDOException $e) {
            error_log("Erreur evolution inscriptions: " . $e->getMessage());
        }

        try {
            $stmt = $this->pdo->query("
                SELECT COUNT(*) as total 
                FROM commande 
                WHERE date_commande >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            ");
            $moisActuel = (int)($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
            
            $stmt = $this->pdo->query("
                SELECT COUNT(*) as total 
                FROM commande 
                WHERE date_commande BETWEEN DATE_SUB(CURDATE(), INTERVAL 60 DAY) AND DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            ");
            $moisPrecedent = (int)($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
            
            if ($moisPrecedent > 0) {
                $stats['evolution_ventes'] = round((($moisActuel - $moisPrecedent) / $moisPrecedent) * 100, 1);
            }
        } catch (PDOException $e) {
            error_log("Erreur evolution ventes: " . $e->getMessage());
        }

        try {
            $stmt = $this->pdo->query("
                SELECT SUM(CAST(prix AS DECIMAL(10,2))) as total 
                FROM commande 
                WHERE date_commande >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            ");
            $moisActuel = (float)($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
            
            $stmt = $this->pdo->query("
                SELECT SUM(CAST(prix AS DECIMAL(10,2))) as total 
                FROM commande 
                WHERE date_commande BETWEEN DATE_SUB(CURDATE(), INTERVAL 60 DAY) AND DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            ");
            $moisPrecedent = (float)($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
            
            if ($moisPrecedent > 0) {
                $stats['evolution_ca'] = round((($moisActuel - $moisPrecedent) / $moisPrecedent) * 100, 1);
            }
        } catch (PDOException $e) {
            error_log("Erreur evolution CA: " . $e->getMessage());
        }

        return $stats;
    }

    // ============================================
    // TOP PRODUITS
    // ============================================

    public function getTopProducts($limit = 5) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    p.id,
                    p.nom_article as nom,
                    p.prix,
                    p.image,
                    CONCAT(COALESCE(u.prenom, ''), ' ', COALESCE(u.nom, '')) as vendeur,
                    COUNT(c.a) as ventes,
                    SUM(CAST(c.prix AS DECIMAL(10,2))) as ca
                FROM produits p
                LEFT JOIN commande c ON c.id_article = p.id
                LEFT JOIN utilisateur u ON u.id_uti = p.id_vendeur
                GROUP BY p.id
                ORDER BY ventes DESC
                LIMIT ?
            ");
            $stmt->execute([$limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur top produits: " . $e->getMessage());
            return [];
        }
    }

    // ============================================
    // CATÉGORIES PERFORMANTES
    // ============================================

    public function getTopCategories() {
        try {
            $stmt = $this->pdo->query("
                SELECT 
                    c.nom_categorie as nom,
                    COUNT(co.a) as ventes,
                    SUM(CAST(co.prix AS DECIMAL(10,2))) as ca
                FROM categories c
                LEFT JOIN produits p ON p.categorie_id = c.id
                LEFT JOIN commande co ON co.id_article = p.id
                GROUP BY c.id
                ORDER BY ventes DESC
                LIMIT 6
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur top categories: " . $e->getMessage());
            return [];
        }
    }

    // ============================================
    // RÉPARTITION GÉOGRAPHIQUE
    // ============================================

    public function getGeoDistribution() {
        try {
            $stmt = $this->pdo->query("SHOW COLUMNS FROM utilisateur LIKE 'pays'");
            if ($stmt->rowCount() > 0) {
                $stmt = $this->pdo->query("
                    SELECT 
                        pays,
                        COUNT(*) as total,
                        ROUND((COUNT(*) / (SELECT COUNT(*) FROM utilisateur WHERE type != 'admin')) * 100, 1) as pourcentage
                    FROM utilisateur
                    WHERE type != 'admin'
                    GROUP BY pays
                    ORDER BY total DESC
                    LIMIT 5
                ");
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($result)) {
                    return $result;
                }
            }
        } catch (PDOException $e) {
            error_log("Erreur geo: " . $e->getMessage());
        }
        
        return [
            ['pays' => 'Sénégal', 'total' => 0, 'pourcentage' => 0],
            ['pays' => 'Côte d\'Ivoire', 'total' => 0, 'pourcentage' => 0],
            ['pays' => 'Cameroun', 'total' => 0, 'pourcentage' => 0],
            ['pays' => 'Mali', 'total' => 0, 'pourcentage' => 0],
            ['pays' => 'Autres', 'total' => 0, 'pourcentage' => 0]
        ];
    }

    // ============================================
    // RAPPORTS
    // ============================================

    public function getSalesReport($startDate, $endDate) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    DATE(c.date_commande) as date,
                    p.nom_article as produit,
                    CONCAT(COALESCE(u.prenom, ''), ' ', COALESCE(u.nom, '')) as vendeur,
                    cat.nom_categorie as categorie,
                    c.prix,
                    '0' as commission,
                    'livree' as statut
                FROM commande c
                JOIN produits p ON p.id = c.id_article
                JOIN utilisateur u ON u.id_uti = p.id_vendeur
                JOIN categories cat ON cat.id = p.categorie_id
                WHERE c.date_commande BETWEEN ? AND ?
                ORDER BY c.date_commande DESC
                LIMIT 100
            ");
            $stmt->execute([$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur rapport ventes: " . $e->getMessage());
            return [];
        }
    }

    public function getFinancialReport($startDate, $endDate) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    DATE_FORMAT(c.date_commande, '%Y-%m') as mois,
                    SUM(CAST(c.prix AS DECIMAL(10,2))) as ca,
                    SUM(CAST(c.prix AS DECIMAL(10,2)) * 0.1) as commission_plateforme,
                    SUM(CAST(c.prix AS DECIMAL(10,2)) * 0.85) as commission_vendeurs,
                    SUM(CAST(c.prix AS DECIMAL(10,2)) * 0.05) as versements,
                    SUM(CAST(c.prix AS DECIMAL(10,2)) * 0) as solde_du
                FROM commande c
                WHERE c.date_commande BETWEEN ? AND ?
                GROUP BY DATE_FORMAT(c.date_commande, '%Y-%m')
                ORDER BY mois DESC
            ");
            $stmt->execute([$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur rapport financier: " . $e->getMessage());
            return [];
        }
    }

    public function getUsersReport($startDate, $endDate) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    type,
                    COUNT(*) as total,
                    SUM(CASE WHEN statut = 'actif' THEN 1 ELSE 0 END) as actifs
                FROM utilisateur
                GROUP BY type
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur rapport utilisateurs: " . $e->getMessage());
            return [];
        }
    }

    public function getVendorsReport($startDate, $endDate) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    u.id_uti as id,
                    CONCAT(COALESCE(u.prenom, ''), ' ', COALESCE(u.nom, '')) as nom,
                    u.email,
                    COUNT(DISTINCT p.id) as produits,
                    COUNT(c.a) as ventes,
                    SUM(CAST(c.prix AS DECIMAL(10,2))) as ca,
                    SUM(CAST(c.prix AS DECIMAL(10,2)) * 0.85) as commission
                FROM utilisateur u
                LEFT JOIN produits p ON p.id_vendeur = u.id_uti
                LEFT JOIN commande c ON c.id_article = p.id
                WHERE u.type = 'vendor'
                GROUP BY u.id_uti
                ORDER BY ca DESC
                LIMIT 20
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur rapport vendeurs: " . $e->getMessage());
            return [];
        }
    }

    // ============================================
    // EXPORTS
    // ============================================

    public function exportToCSV($data, $filename) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename . '.csv');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        if (!empty($data)) {
            fputcsv($output, array_keys($data[0]));
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
        }
        
        fclose($output);
        exit();
    }

    // ============================================
    // GRAPHIQUES - DONNÉES D'ÉVOLUTION
    // ============================================

    public function getChartData($period = 'week') {
        $data = [
            'labels' => [],
            'inscriptions' => [],
            'ventes' => [],
            'ca' => []
        ];

        $days = $period === 'week' ? 7 : 30;
        
        // Récupérer les commandes par jour
        $commandesParJour = [];
        try {
            $stmt = $this->pdo->query("
                SELECT 
                    DATE(date_commande) as jour,
                    COUNT(*) as total_ventes,
                    SUM(CAST(prix AS DECIMAL(10,2))) as total_ca
                FROM commande 
                WHERE date_commande >= DATE_SUB(CURDATE(), INTERVAL $days DAY)
                GROUP BY DATE(date_commande)
            ");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $commandesParJour[$row['jour']] = [
                    'ventes' => (int)($row['total_ventes'] ?? 0),
                    'ca' => (float)($row['total_ca'] ?? 0)
                ];
            }
        } catch (PDOException $e) {
            error_log("Erreur commandes: " . $e->getMessage());
        }
        
        // Récupérer les inscriptions par jour (si possible)
        $inscriptionsParJour = [];
        try {
            $stmt = $this->pdo->query("SHOW COLUMNS FROM utilisateur LIKE 'created_at'");
            if ($stmt->rowCount() > 0) {
                $stmt = $this->pdo->query("
                    SELECT 
                        DATE(created_at) as jour,
                        COUNT(*) as total
                    FROM utilisateur 
                    WHERE type != 'admin'
                    AND created_at >= DATE_SUB(CURDATE(), INTERVAL $days DAY)
                    GROUP BY DATE(created_at)
                ");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $inscriptionsParJour[$row['jour']] = (int)$row['total'];
                }
            }
        } catch (PDOException $e) {
            error_log("Erreur inscriptions: " . $e->getMessage());
        }
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            
            $data['labels'][] = $period === 'week' ? date('D', strtotime("-$i days")) : date('d/m', strtotime("-$i days"));
            $data['inscriptions'][] = $inscriptionsParJour[$date] ?? 0;
            $data['ventes'][] = $commandesParJour[$date]['ventes'] ?? 0;
            $data['ca'][] = $commandesParJour[$date]['ca'] ?? 0;
        }

        return $data;
    }


    /**
 * Récupérer les top produits vus
 */
public function getTopViewedProducts($limit = 5) {
    try {
        // Si la table produit_views existe
        $stmt = $this->pdo->prepare("
            SELECT 
                p.id,
                p.nom_article as nom,
                p.prix,
                p.image,
                CONCAT(COALESCE(u.prenom, ''), ' ', COALESCE(u.nom, '')) as vendeur,
                COUNT(pv.id) as vues
            FROM produits p
            LEFT JOIN produit_views pv ON pv.produit_id = p.id
            LEFT JOIN utilisateur u ON u.id_uti = p.id_vendeur
            GROUP BY p.id
            ORDER BY vues DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Si la table n'existe pas, retourner les produits sans les vues
        error_log("Erreur top viewed: " . $e->getMessage());
        return [];
    }
}
}