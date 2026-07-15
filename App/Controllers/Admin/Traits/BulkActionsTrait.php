<?php


namespace App\Controllers\Admin\Traits;

trait BulkActionsTrait
{
    public function bulkBlock(): void
    {
        $this->checkAuth();
        $this->bulkAction('block');
    }

    public function bulkUnblock(): void
    {
        $this->checkAuth();
        $this->bulkAction('unblock');
    }

    public function bulkPromote(): void
    {
        $this->checkAuth();
        $this->bulkAction('promote');
    }

    public function bulkDelete(): void
    {
        $this->checkAuth();
        $this->bulkAction('delete');
    }

    private function bulkAction(string $action): void
    {
        $db = \Database::getConnection();
        $ids = $_POST['ids'] ?? [];

        if (empty($ids) || !is_array($ids)) {
            $this->jsonResponse(['success' => false, 'message' => 'Aucun utilisateur sélectionné'], 400);
            return;
        }

        $ids = array_filter(array_map('intval', $ids));
        if (empty($ids)) {
            $this->jsonResponse(['success' => false, 'message' => 'IDs invalides'], 400);
            return;
        }

        // Empêcher l'action sur soi-même
        $adminId = $_SESSION['admin_id'] ?? $_SESSION['user_id'] ?? null;
        $ids = array_filter($ids, fn($id) => $id != $adminId);

        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        try {
            switch ($action) {
                case 'block':
                    $db->prepare("UPDATE utilisateur SET statut = 'bloque' WHERE id_uti IN ($placeholders)")->execute($ids);
                    $message = count($ids) . ' utilisateur(s) bloqué(s)';
                    break;
                case 'unblock':
                    $db->prepare("UPDATE utilisateur SET statut = 'actif' WHERE id_uti IN ($placeholders)")->execute($ids);
                    $message = count($ids) . ' utilisateur(s) réactivé(s)';
                    break;
                case 'promote':
                    $db->prepare("UPDATE utilisateur SET type = 'pro' WHERE id_uti IN ($placeholders)")->execute($ids);
                    $message = count($ids) . ' utilisateur(s) promu(s) vendeur';
                    break;
                case 'delete':
                    $db->prepare("DELETE FROM utilisateur WHERE id_uti IN ($placeholders)")->execute($ids);
                    $message = count($ids) . ' utilisateur(s) supprimé(s)';
                    break;
                default:
                    $this->jsonResponse(['success' => false, 'message' => 'Action invalide'], 400);
                    return;
            }

            $this->logAction("bulk_{$action}", 0, "Action groupée sur IDs: " . implode(',', $ids));
            $this->jsonResponse(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            $this->logAction("bulk_{$action}", 0, "Échec: " . $e->getMessage(), 'error');
            $this->jsonResponse(['success' => false, 'message' => 'Erreur lors de l\'action groupée'], 500);
        }
    }
}
