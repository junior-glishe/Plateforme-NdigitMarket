<?php
/**
 * NDIGITMARKET - Admin trait: Reviews
 *
 * Fait partie du refactor du monolithique AdminController.
 * Chaque trait regroupe les méthodes d'un même domaine metier.
 * Le comportement des méthodes est identique à la version d'origine.
 */

namespace App\Controllers\Admin\Traits;

trait ReviewsTrait
{
    public function reviews()
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        try {
            $reviews = $db->query("
                SELECT 
                    a.*,
                    u.nom AS client_nom,
                    u.prenom AS client_prenom,
                    p.nom_article,
                    p.id AS produit_id
                FROM avis a
                LEFT JOIN utilisateur u ON u.id_uti = a.id_uti
                LEFT JOIN produits p ON p.id = a.produit_id
                ORDER BY a.date_creation DESC
            ")->fetchAll();

            foreach ($reviews as &$review) {
                $review['client_full_name'] = trim(($review['client_prenom'] ?? '') . ' ' . ($review['client_nom'] ?? ''));
                $review['date_formatted'] = $this->formatDate($review['date_creation'] ?? null, 'd/m/Y H:i');
                $review['statut_label'] = match ($review['statut'] ?? '') {
                    'approuve'   => 'Approuvé',
                    'en_attente' => 'En attente',
                    'refuse'     => 'Rejeté',
                    default      => 'Inconnu',
                };
                $review['statut_class'] = match ($review['statut'] ?? '') {
                    'approuve'   => 'success',
                    'en_attente' => 'warning',
                    'refuse'     => 'danger',
                    default      => 'secondary',
                };
            }
            unset($review);
        } catch (\Exception $e) {
            $reviews = [];
        }

        $totalReviews = count($reviews);
        $currentPage = 'avis-commentaires';
        require_once __DIR__ . '/../../../Views/admin/avis-commentaires.php';
    }

    public function approveReview(int $id): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $db->prepare("UPDATE avis SET statut = 'approuve' WHERE id = ?")->execute([$id]);
        $this->logAction('approve_review', $id, "Avis approuvé");
        $this->jsonResponse(['success' => true, 'message' => 'Avis approuvé']);
    }

    public function rejectReview(int $id): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $db->prepare("UPDATE avis SET statut = 'refuse' WHERE id = ?")->execute([$id]);
        $this->logAction('reject_review', $id, "Avis rejeté");
        $this->jsonResponse(['success' => true, 'message' => 'Avis rejeté']);
    }
}
