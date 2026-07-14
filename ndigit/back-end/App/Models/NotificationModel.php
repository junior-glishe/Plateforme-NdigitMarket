<?php
// App/Models/NotificationModel.php

class NotificationModel {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ============================================
    // CRUD NOTIFICATIONS
    // ============================================

    /**
     * Récupérer toutes les notifications
     */
    public function getAllNotifications($limit = 50, $offset = 0) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM notifications 
            ORDER BY created_at DESC 
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les notifications non lues
     */
    public function getUnreadNotifications($limit = 20) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM notifications 
            WHERE est_lu = 0 
            ORDER BY created_at DESC 
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Compter les notifications non lues
     */
    public function countUnreadNotifications() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM notifications WHERE est_lu = 0");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Compter par type
     */
    public function countByType($type) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM notifications WHERE type = ? AND est_lu = 0");
        $stmt->execute([$type]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Récupérer les stats des notifications
     */
    public function getNotificationStats() {
        $stats = [
            'total' => 0,
            'unread' => 0,
            'by_type' => []
        ];

        // Total non lues
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM notifications WHERE est_lu = 0");
        $stats['unread'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        // Total
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM notifications");
        $stats['total'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        // Par type
        $types = ['vendor_request', 'product_moderation', 'order_high', 'order_problem', 'support_message', 'security_alert'];
        foreach ($types as $type) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM notifications WHERE type = ? AND est_lu = 0");
            $stmt->execute([$type]);
            $stats['by_type'][$type] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        }

        return $stats;
    }

    /**
     * Créer une notification
     */
    public function createNotification($data) {
        $sql = "INSERT INTO notifications (
                    type, title, message, lien, icone, couleur, 
                    priorite, source_type, source_id
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['type'],
            $data['title'],
            $data['message'],
            $data['lien'] ?? null,
            $data['icone'] ?? 'fa-bell',
            $data['couleur'] ?? 'blue',
            $data['priorite'] ?? 'medium',
            $data['source_type'] ?? null,
            $data['source_id'] ?? null
        ]);
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead($id) {
        $stmt = $this->pdo->prepare("UPDATE notifications SET est_lu = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead() {
        $stmt = $this->pdo->query("UPDATE notifications SET est_lu = 1");
        return $stmt->rowCount();
    }

    /**
     * Supprimer une notification
     */
    public function deleteNotification($id) {
        $stmt = $this->pdo->prepare("DELETE FROM notifications WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Récupérer une notification par ID
     */
    public function getNotificationById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM notifications WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ============================================
    // PARAMÈTRES DES NOTIFICATIONS
    // ============================================

    /**
     * Récupérer les paramètres
     */
    public function getSettings() {
        $stmt = $this->pdo->query("SELECT * FROM notification_settings");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mettre à jour un paramètre
     */
    public function updateSetting($type, $data) {
        $sql = "UPDATE notification_settings SET 
                    activer = ?,
                    email_notification = ?,
                    seuil_commande = ?
                WHERE type = ?";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['activer'] ?? 1,
            $data['email_notification'] ?? 1,
            $data['seuil_commande'] ?? null,
            $type
        ]);
    }

    // ============================================
    // FONCTIONS DE CRÉATION AUTOMATIQUE
    // ============================================

    /**
     * Notification demande vendeur
     */
    public function notifyVendorRequest($userId, $shopName) {
        return $this->createNotification([
            'type' => 'vendor_request',
            'title' => 'Nouvelle demande vendeur',
            'message' => "L'utilisateur a soumis une demande pour ouvrir la boutique \"{$shopName}\"",
            'icone' => 'fa-user-plus',
            'couleur' => 'yellow',
            'priorite' => 'high',
            'source_type' => 'user',
            'source_id' => $userId
        ]);
    }

    /**
     * Notification produit à modérer
     */
    public function notifyProductModeration($productId, $productName, $sellerName) {
        return $this->createNotification([
            'type' => 'product_moderation',
            'title' => 'Nouveau produit à modérer',
            'message' => "Le produit \"{$productName}\" a été soumis par le vendeur {$sellerName}",
            'icone' => 'fa-file-code',
            'couleur' => 'indigo',
            'priorite' => 'high',
            'source_type' => 'product',
            'source_id' => $productId
        ]);
    }

    /**
     * Notification commande importante
     */
    public function notifyHighOrder($orderId, $amount, $customerName) {
        return $this->createNotification([
            'type' => 'order_high',
            'title' => 'Commande importante reçue',
            'message' => "Une commande de {$amount} FCFA a été passée par {$customerName}",
            'icone' => 'fa-shopping-cart',
            'couleur' => 'emerald',
            'priorite' => 'medium',
            'source_type' => 'order',
            'source_id' => $orderId
        ]);
    }

    /**
     * Notification commande problématique
     */
    public function notifyOrderProblem($orderId, $problem) {
        return $this->createNotification([
            'type' => 'order_problem',
            'title' => 'Commande signalée comme problématique',
            'message' => "La commande #{$orderId} a été signalée : {$problem}",
            'icone' => 'fa-exclamation-triangle',
            'couleur' => 'red',
            'priorite' => 'high',
            'source_type' => 'order',
            'source_id' => $orderId
        ]);
    }

    /**
     * Notification message support
     */
    public function notifySupportMessage($ticketId, $subject, $userName) {
        return $this->createNotification([
            'type' => 'support_message',
            'title' => 'Nouveau message support',
            'message' => "{$userName} a envoyé un message concernant \"{$subject}\"",
            'icone' => 'fa-envelope',
            'couleur' => 'blue',
            'priorite' => 'medium',
            'source_type' => 'ticket',
            'source_id' => $ticketId
        ]);
    }

        /**
     * Compter le nombre total de notifications
     */
    public function countAllNotifications() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM notifications");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
    /**
     * Compter le nombre total de notifications (alias)
     */
    public function countNotifications() {
        return $this->countAllNotifications();
    }

    /**
     * Notification sécurité - tentative suspecte
     */
    public function notifySecurityAlert($ip, $location) {
        return $this->createNotification([
            'type' => 'security_alert',
            'title' => 'Tentative de connexion suspecte',
            'message' => "Une tentative de connexion admin a été détectée depuis l'IP {$ip} ({$location})",
            'icone' => 'fa-shield-alt',
            'couleur' => 'purple',
            'priorite' => 'high',
            'source_type' => 'system',
            'source_id' => null
        ]);
    }

    // ============================================
// STATISTIQUES EMAILS
// ============================================
/**
 * Compter le nombre d'emails envoyés ce mois
 */
public function countEmailsEnvoyes() {
    $stmt = $this->pdo->query("
        SELECT COUNT(*) as total 
        FROM email_logs 
        WHERE DATE(date_envoi) >= DATE_FORMAT(NOW(), '%Y-%m-01')
        AND statut = 'envoye'
    ");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] ?? 0;
}

/**
 * Calculer le taux d'ouverture moyen
 */
public function getTauxOuverture() {
    $stmt = $this->pdo->query("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN ouvert = 1 THEN 1 ELSE 0 END) as ouverts
        FROM email_logs 
        WHERE DATE(date_envoi) >= DATE_FORMAT(NOW(), '%Y-%m-01')
        AND statut = 'envoye'
    ");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $total = $result['total'] ?? 0;
    $ouverts = $result['ouverts'] ?? 0;
    
    return $total > 0 ? round(($ouverts / $total) * 100, 1) : 0;
}

/**
 * Calculer le taux de clic moyen
 */
public function getTauxClic() {
    $stmt = $this->pdo->query("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN clic = 1 THEN 1 ELSE 0 END) as clics
        FROM email_logs 
        WHERE DATE(date_envoi) >= DATE_FORMAT(NOW(), '%Y-%m-01')
        AND statut = 'envoye'
    ");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $total = $result['total'] ?? 0;
    $clics = $result['clics'] ?? 0;
    
    return $total > 0 ? round(($clics / $total) * 100, 1) : 0;
}

/**
 * Compter le nombre de templates d'emails
 */
public function countEmailTemplates() {
    $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM email_templates WHERE statut = 'active'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] ?? 0;
}

/**
 * Récupérer tous les templates d'emails
 */
public function getAllEmailTemplates() {
    $stmt = $this->pdo->query("
        SELECT * FROM email_templates 
        ORDER BY nom ASC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupérer un template par son ID
 */
public function getEmailTemplateById($id) {
    $stmt = $this->pdo->prepare("SELECT * FROM email_templates WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Mettre à jour un template
 */
public function updateEmailTemplate($id, $data) {
    $sql = "UPDATE email_templates SET 
                nom = ?,
                slug = ?,
                objet = ?,
                contenu = ?,
                bouton_texte = ?,
                bouton_url = ?,
                bg_color = ?,
                text_color = ?,
                icon = ?,
                statut = ?
            WHERE id = ?";
    
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        $data['nom'],
        $data['slug'],
        $data['objet'] ?? null,
        $data['contenu'] ?? null,
        $data['bouton_texte'] ?? null,
        $data['bouton_url'] ?? null,
        $data['bg_color'] ?? 'bg-amber-100',
        $data['text_color'] ?? 'text-amber-600',
        $data['icon'] ?? 'fa-envelope-open-text',
        $data['statut'] ?? 'active',
        $id
    ]);
}

/**
 * Changer le statut d'un template
 */
public function toggleEmailTemplateStatus($id, $statut) {
    $stmt = $this->pdo->prepare("UPDATE email_templates SET statut = ? WHERE id = ?");
    return $stmt->execute([$statut, $id]);
}

/**
 * Récupérer les logs d'emails
 */
public function getEmailLogs($limit = 50) {
    $stmt = $this->pdo->prepare("
        SELECT * FROM email_logs 
        ORDER BY date_envoi DESC 
        LIMIT ?
    ");
    $stmt->execute([$limit]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Enregistrer un email envoyé
 */
public function logEmail($data) {
    $sql = "INSERT INTO email_logs (
                email, 
                destinataire, 
                sujet, 
                statut, 
                ouvert, 
                clic, 
                template_id
            ) VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        $data['email'],
        $data['destinataire'] ?? $data['email'],
        $data['sujet'],
        $data['statut'] ?? 'envoye',
        $data['ouvert'] ?? 0,
        $data['clic'] ?? 0,
        $data['template_id'] ?? null
    ]);
}
}