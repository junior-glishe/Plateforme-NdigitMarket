<?php
/**
 * NDIGITMARKET - Admin trait : Withdrawals
 * Gestion des demandes de retrait vendeur.
 */

namespace App\Controllers\Admin\Traits;

trait WithdrawalsTrait
{
    public function withdrawals()
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        $where = [];
        $params = [];
        if (!empty($_GET['statut'])) {
            $where[] = 'r.statut = :statut';
            $params[':statut'] = $_GET['statut'];
        }
        if (!empty($_GET['search'])) {
            $where[] = '(u.nom LIKE :s OR u.prenom LIKE :s OR u.email LIKE :s)';
            $params[':s'] = '%' . $_GET['search'] . '%';
        }
        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $sql = "
            SELECT r.*, u.nom, u.prenom, u.email
            FROM retraits r
            LEFT JOIN utilisateur u ON u.id_uti = r.id_uti
            $whereClause
            ORDER BY r.date_demande DESC
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $withdrawals = $stmt->fetchAll();

        foreach ($withdrawals as &$w) {
            $w['full_name']         = trim(($w['prenom'] ?? '') . ' ' . ($w['nom'] ?? ''));
            $w['montant_formatted'] = $this->formatCurrency((float) $w['montant']);
            $w['date_formatted']    = $this->formatDate($w['date_demande'], 'd/m/Y H:i');
            $w['statut_label'] = match ($w['statut'] ?? '') {
                'en_attente' => 'En attente',
                'accepte'    => 'Accepté',
                'refuse'     => 'Refusé',
                default      => 'Inconnu',
            };
            $w['statut_class'] = match ($w['statut'] ?? '') {
                'en_attente' => 'warning',
                'accepte'    => 'success',
                'refuse'     => 'danger',
                default      => 'secondary',
            };
        }
        unset($w);

        $totalWithdrawals = count($withdrawals);
        $currentPage      = 'financieres-commission';
        require_once __DIR__ . '/../../../Views/admin/financieres-commission.php';
    }

    /**
     * BUG FIX : idem approveVendor — le prepare n'était pas exécuté avant fetch.
     */
    public function approveWithdrawal(int $id): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $db->beginTransaction();
        try {
            $stmt = $db->prepare("SELECT id_uti, montant FROM retraits WHERE id = ?");
            $stmt->execute([$id]);
            $retrait = $stmt->fetch();

            if (!$retrait) {
                throw new \Exception("Retrait introuvable");
            }

            $db->prepare("UPDATE retraits SET statut = 'accepte' WHERE id = ?")->execute([$id]);
            $db->prepare("UPDATE portefeuille_vendeur SET solde = GREATEST(0, solde - ?) WHERE id_uti = ?")
               ->execute([$retrait['montant'], $retrait['id_uti']]);

            $db->commit();
            $this->logAction('approve_withdrawal', $id,
                "Retrait approuvé montant={$retrait['montant']} id_uti={$retrait['id_uti']}");
            $this->jsonResponse(['success' => true, 'message' => 'Retrait approuvé']);
        } catch (\Exception $e) {
            $db->rollBack();
            $this->logAction('approve_withdrawal', $id, "Échec: " . $e->getMessage(), 'error');
            $this->jsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function rejectWithdrawal(int $id): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $commentaire = $_POST['commentaire'] ?? '';
        $db->prepare("UPDATE retraits SET statut = 'refuse', commentaire = ? WHERE id = ?")
           ->execute([$commentaire, $id]);
        $this->logAction('reject_withdrawal', $id, "Retrait refusé: $commentaire");
        $this->jsonResponse(['success' => true, 'message' => 'Retrait refusé']);
    }
}
