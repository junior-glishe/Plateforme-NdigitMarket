<?php
/**
 * NDIGITMARKET - Admin trait: Notifications
 *
 * Fait partie du refactor du monolithique AdminController.
 * Chaque trait regroupe les méthodes d'un même domaine metier.
 * Le comportement des méthodes est identique à la version d'origine.
 */

namespace App\Controllers\Admin\Traits;

trait NotificationsTrait
{
    public function notifications()
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        $notifications = $db->query("
            SELECT * FROM email_logs 
            ORDER BY date_envoi DESC 
            LIMIT 100
        ")->fetchAll();

        foreach ($notifications as &$notif) {
            $notif['date_formatted'] = $this->formatDate($notif['date_envoi'] ?? null, 'd/m/Y H:i');
            $notif['statut_label'] = $notif['statut'] === 'envoye' ? 'Envoyé' : 'Erreur';
            $notif['statut_class'] = $notif['statut'] === 'envoye' ? 'success' : 'danger';
        }
        unset($notif);

        $totalNotifications = count($notifications);
        $currentPage = 'notifications';
        require_once __DIR__ . '/../../../Views/admin/notifications.php';
    }
}
