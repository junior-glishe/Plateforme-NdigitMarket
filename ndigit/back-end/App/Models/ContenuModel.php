<?php

require_once __DIR__ . '/../../config/database.php';

class ContenuModel {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ============================================
    // BANNIÈRES
    // ============================================

    /**
     * Récupérer toutes les bannières
     */
    public function getAllBannieres() {
        $stmt = $this->pdo->query("SELECT * FROM bannieres ORDER BY ordre_affichage ASC, id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer une bannière par son ID
     */
    public function getBanniereById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM bannieres WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Compter le nombre total de bannières
     */
    public function countBannieres() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM bannieres");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    /**
     * Compter les bannières actives
     */
    public function countActiveBannieres() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM bannieres WHERE statut = 'active'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    /**
     * Compter les bannières inactives
     */
    public function countInactiveBannieres() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM bannieres WHERE statut = 'inactive'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    /**
     * Récupérer les statistiques totales des bannières
     */
    public function getBanniereStatsTotal() {
        $stmt = $this->pdo->query("SELECT SUM(vues) as total_vues, SUM(clics) as total_clics FROM bannieres");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Ajouter une bannière
     */
    public function addBanniere($data) {
        $sql = "INSERT INTO bannieres (
                    titre, 
                    sous_titre, 
                    image, 
                    texte_bouton, 
                    url_destination, 
                    date_debut, 
                    date_fin, 
                    statut, 
                    ordre_affichage
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['titre'],
            $data['sous_titre'] ?? null,
            $data['image'],
            $data['texte_bouton'] ?? 'Voir les offres',
            $data['url_destination'],
            $data['date_debut'],
            $data['date_fin'],
            $data['statut'] ?? 'active',
            $data['ordre_affichage'] ?? 0
        ]);
    }

    /**
     * Mettre à jour une bannière
     */
    public function updateBanniere($id, $data) {
        $sql = "UPDATE bannieres SET 
                    titre = ?,
                    sous_titre = ?,
                    image = ?,
                    texte_bouton = ?,
                    url_destination = ?,
                    date_debut = ?,
                    date_fin = ?,
                    statut = ?,
                    ordre_affichage = ?
                WHERE id = ?";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['titre'],
            $data['sous_titre'] ?? null,
            $data['image'],
            $data['texte_bouton'] ?? 'Voir les offres',
            $data['url_destination'],
            $data['date_debut'],
            $data['date_fin'],
            $data['statut'] ?? 'active',
            $data['ordre_affichage'] ?? 0,
            $id
        ]);
    }

    /**
     * Mettre à jour l'ordre d'affichage des bannières
     */
    public function updateBanniereOrder($id, $ordre) {
        $stmt = $this->pdo->prepare("UPDATE bannieres SET ordre_affichage = ? WHERE id = ?");
        return $stmt->execute([$ordre, $id]);
    }

    /**
     * Changer le statut d'une bannière
     */
    public function toggleBanniereStatus($id, $statut) {
        $stmt = $this->pdo->prepare("UPDATE bannieres SET statut = ? WHERE id = ?");
        return $stmt->execute([$statut, $id]);
    }

    /**
     * Incrémenter les vues d'une bannière
     */
    public function incrementBanniereVues($id) {
        $stmt = $this->pdo->prepare("UPDATE bannieres SET vues = vues + 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Incrémenter les clics d'une bannière
     */
    public function incrementBanniereClics($id) {
        $stmt = $this->pdo->prepare("UPDATE bannieres SET clics = clics + 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Supprimer une bannière
     */
    public function deleteBanniere($id) {
        $banniere = $this->getBanniereById($id);
        if ($banniere && $banniere['image']) {
            $imagePath = __DIR__ . '/../../public/uploads/bannieres/' . $banniere['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        $stmt = $this->pdo->prepare("DELETE FROM bannieres WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Récupérer les stats d'une bannière sur 7 jours
     */
    public function getBanniereStats7Days($id) {
        $stmt = $this->pdo->prepare("
            SELECT 
                DATE(date) as jour,
                SUM(vues) as vues,
                SUM(clics) as clics
            FROM banniere_stats
            WHERE banniere_id = ? AND date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY DATE(date)  
            ORDER BY DATE(date) ASC
        ");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ============================================
    // CODES PROMOTIONNELS
    // ============================================

    /**
     * Récupérer tous les codes promo
     */
    public function getAllCodesPromo() {
        $stmt = $this->pdo->query("
            SELECT cp.*, 
                   c.nom_categorie as categorie_nom,
                   (SELECT COUNT(*) FROM promo_utilisations WHERE code_promo_id = cp.id) as utilisations
            FROM codes_promo cp
            LEFT JOIN categories c ON cp.categorie_id = c.id
            ORDER BY cp.id DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer un code promo par son ID
     */
    public function getCodePromoById($id) {
        $stmt = $this->pdo->prepare("
            SELECT cp.*, 
                   c.nom_categorie as categorie_nom,
                   (SELECT COUNT(*) FROM promo_utilisations WHERE code_promo_id = cp.id) as utilisations
            FROM codes_promo cp
            LEFT JOIN categories c ON cp.categorie_id = c.id
            WHERE cp.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer un code promo par son code
     */
    public function getCodePromoByCode($code) {
        $stmt = $this->pdo->prepare("SELECT * FROM codes_promo WHERE code = ?");
        $stmt->execute([$code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Vérifier si un code existe déjà
     */
    public function codeExists($code, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM codes_promo WHERE code = ?";
        $params = [$code];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    /**
     * Compter le nombre total de codes promo
     */
    public function countCodesPromo() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM codes_promo");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    /**
     * Compter les codes promo actifs
     */
    public function countActiveCodesPromo() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM codes_promo WHERE statut = 'active'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    /**
     * Compter les codes promo inactifs
     */
    public function countInactiveCodesPromo() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM codes_promo WHERE statut = 'inactive'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    /**
     * Récupérer le total des remises accordées
     */
    public function getTotalRemises() {
        $stmt = $this->pdo->query("SELECT SUM(remise) as total FROM promo_utilisations");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Ajouter un code promo
     */
    public function addCodePromo($data) {
        $sql = "INSERT INTO codes_promo (
                    code, 
                    type, 
                    valeur, 
                    montant_minimum, 
                    utilisations_max, 
                    date_expiration, 
                    categorie_id, 
                    produit_id, 
                    utilisateur_id, 
                    statut
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['code'],
            $data['type'] ?? 'percentage',
            $data['valeur'],
            $data['montant_minimum'] ?? 0,
            $data['utilisations_max'] ?? null,
            $data['date_expiration'],
            $data['categorie_id'] ?? null,
            $data['produit_id'] ?? null,
            $data['utilisateur_id'] ?? null,
            $data['statut'] ?? 'active'
        ]);
    }

    /**
     * Mettre à jour un code promo
     */
    public function updateCodePromo($id, $data) {
        $sql = "UPDATE codes_promo SET 
                    code = ?,
                    type = ?,
                    valeur = ?,
                    montant_minimum = ?,
                    utilisations_max = ?,
                    date_expiration = ?,
                    categorie_id = ?,
                    produit_id = ?,
                    utilisateur_id = ?,
                    statut = ?
                WHERE id = ?";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['code'],
            $data['type'] ?? 'percentage',
            $data['valeur'],
            $data['montant_minimum'] ?? 0,
            $data['utilisations_max'] ?? null,
            $data['date_expiration'],
            $data['categorie_id'] ?? null,
            $data['produit_id'] ?? null,
            $data['utilisateur_id'] ?? null,
            $data['statut'] ?? 'active',
            $id
        ]);
    }

    /**
     * Changer le statut d'un code promo
     */
    public function toggleCodePromoStatus($id, $statut) {
        $stmt = $this->pdo->prepare("UPDATE codes_promo SET statut = ? WHERE id = ?");
        return $stmt->execute([$statut, $id]);
    }

    /**
     * Supprimer un code promo
     */
    public function deleteCodePromo($id) {
        $stmt = $this->pdo->prepare("DELETE FROM codes_promo WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Enregistrer une utilisation de code promo
     */
    public function addPromoUtilisation($data) {
        $sql = "INSERT INTO promo_utilisations (
                    code_promo_id, 
                    commande_id, 
                    utilisateur_id, 
                    montant_initial, 
                    remise, 
                    montant_final
                ) VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            $data['code_promo_id'],
            $data['commande_id'],
            $data['utilisateur_id'],
            $data['montant_initial'],
            $data['remise'],
            $data['montant_final']
        ]);
        
        if ($result) {
            // Incrémenter les utilisations actuelles
            $stmt = $this->pdo->prepare("UPDATE codes_promo SET utilisations_actuelles = utilisations_actuelles + 1 WHERE id = ?");
            $stmt->execute([$data['code_promo_id']]);
        }
        
        return $result;
    }

    /**
     * Récupérer l'historique d'utilisation d'un code promo
     */
    public function getPromoHistorique($codePromoId) {
        $stmt = $this->pdo->prepare("
            SELECT pu.*, 
                   u.nom as utilisateur_nom,
                   u.email as utilisateur_email
            FROM promo_utilisations pu
            LEFT JOIN users u ON pu.utilisateur_id = u.id
            WHERE pu.code_promo_id = ?
            ORDER BY pu.date_utilisation DESC
            LIMIT 50
        ");
        $stmt->execute([$codePromoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les codes promo expirés et les mettre à jour
     */
    public function updateExpiredCodes() {
        $stmt = $this->pdo->query("
            UPDATE codes_promo 
            SET statut = 'expire' 
            WHERE date_expiration < NOW() AND statut = 'active'
        ");
        return $stmt->rowCount();
    }

    /**
     * Générer un code promo aléatoire
     */
    public function generateRandomCode($prefix = 'PROMO-', $length = 8) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = $prefix;
        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $code;
    }

    /**
 * Compter le nombre total d'utilisations de codes promo
 */
public function countTotalUtilisations() {
    $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM promo_utilisations");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] ?? 0;
}


public function getCodesPromoFiltered($search = '', $status = '', $type = '') {
    $sql = "SELECT cp.*, 
                   c.nom_categorie as categorie_nom,
                   (SELECT COUNT(*) FROM promo_utilisations WHERE code_promo_id = cp.id) as utilisations
            FROM codes_promo cp
            LEFT JOIN categories c ON cp.categorie_id = c.id
            WHERE 1=1";
    
    $params = [];
    
    if (!empty($search)) {
        $sql .= " AND cp.code LIKE ?";
        $params[] = '%' . $search . '%';
    }
    
    if (!empty($status)) {
        $sql .= " AND cp.statut = ?";
        $params[] = $status;
    }
    
    if (!empty($type)) {
        $sql .= " AND cp.type = ?";
        $params[] = $type;
    }
    
    $sql .= " ORDER BY cp.id DESC";
    
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}