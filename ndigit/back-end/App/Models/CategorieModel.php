<?php

require_once __DIR__ . '/../models/CategorieModel.php';

class CategorieModel {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Récupérer toutes les catégories
     */
    public function getAllCategories() {
        $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY ordre_affichage ASC, id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupérer une catégorie par son ID
     */
    public function getCategoryById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupérer une catégorie par son slug
     */
    public function getCategoryBySlug($slug) {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Compter le nombre total de catégories
     */
    public function countCategories() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM categories");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    
    /**
     * Compter les catégories actives
     */
    public function countActiveCategories() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM categories WHERE statut = 'active'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    
    /**
     * Compter les catégories inactives
     */
    public function countInactiveCategories() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM categories WHERE statut = 'inactive'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    
    /**
     * Compter le nombre de produits par catégorie
     */
    public function countProductsByCategory($categoryId) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM produits WHERE categorie_id = ?");
        $stmt->execute([$categoryId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    
    /**
     * Ajouter une catégorie
     */
    public function addCategory($data) {
        $sql = "INSERT INTO categories (
                    nom_categorie, 
                    slug, 
                    description, 
                    icone, 
                    couleur, 
                    statut, 
                    ordre_affichage,
                    image_cat
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nom_categorie'],
            $data['slug'],
            $data['description'] ?? null,
            $data['icone'] ?? 'fa-solid fa-globe',
            $data['couleur'] ?? 'blue',
            $data['statut'] ?? 'active',
            $data['ordre_affichage'] ?? 0,
            $data['image_cat'] ?? null
        ]);
    }
    
    /**
     * Mettre à jour une catégorie
     */
    public function updateCategory($id, $data) {
        $sql = "UPDATE categories SET 
                    nom_categorie = ?,
                    slug = ?,
                    description = ?,
                    icone = ?,
                    couleur = ?,
                    statut = ?
                WHERE id = ?";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nom_categorie'],
            $data['slug'],
            $data['description'] ?? null,
            $data['icone'] ?? 'fa-solid fa-globe',
            $data['couleur'] ?? 'blue',
            $data['statut'] ?? 'active',
            $id
        ]);
    }
    
    /**
     * Mettre à jour l'image d'une catégorie
     */
    public function updateCategoryImage($id, $imageName) {
        $stmt = $this->pdo->prepare("UPDATE categories SET image_cat = ? WHERE id = ?");
        return $stmt->execute([$imageName, $id]);
    }
    
    /**
     * Mettre à jour l'ordre d'affichage
     */
    public function updateCategoryOrder($id, $ordre) {
        $stmt = $this->pdo->prepare("UPDATE categories SET ordre_affichage = ? WHERE id = ?");
        return $stmt->execute([$ordre, $id]);
    }
    
    /**
     * Mettre à jour le statut d'une catégorie
     */
    public function toggleCategoryStatus($id, $statut) {
        $stmt = $this->pdo->prepare("UPDATE categories SET statut = ? WHERE id = ?");
        return $stmt->execute([$statut, $id]);
    }
    
    /**
     * Supprimer une catégorie
     */
    public function deleteCategory($id) {
        // Récupérer d'abord l'image pour la supprimer du serveur
        $category = $this->getCategoryById($id);
        if ($category && $category['image_cat']) {
            $imagePath = __DIR__ . '/../../public/uploads/' . $category['image_cat'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Fusionner deux catégories
     */
    public function mergeCategories($sourceId, $targetId) {
        try {
            $this->pdo->beginTransaction();
            
            // Mettre à jour les produits de la source vers la cible
            $stmt = $this->pdo->prepare("UPDATE produits SET categorie_id = ? WHERE categorie_id = ?");
            $stmt->execute([$targetId, $sourceId]);
            
            // Supprimer la catégorie source
            $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->execute([$sourceId]);
            
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
    
    /**
     * Vérifier si un slug existe déjà
     */
    public function slugExists($slug, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM categories WHERE slug = ?";
        $params = [$slug];
        
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
     * Générer un slug unique
     */
    public function generateUniqueSlug($nom) {
        $baseSlug = $this->slugify($nom);
        $slug = $baseSlug;
        $counter = 1;
        
        while ($this->slugExists($slug)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * Convertir un texte en slug
     */
    private function slugify($text) {
        $text = mb_strtolower($text);
        $text = preg_replace('/[^a-z0-9-]/', '-', $text);
        $text = preg_replace('/-+/', '-', $text);
        return trim($text, '-');
    }
}