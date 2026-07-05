-- =========================================================================
-- NDIGITMARKET — Migrations complémentaires (à exécuter APRÈS ndigitmarket.sql)
-- Ne supprime aucune donnée. Toutes les instructions sont idempotentes.
-- =========================================================================

-- Table de journalisation admin (utilisée par logAction() et le module logs-audit)
CREATE TABLE IF NOT EXISTS `admin_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `admin_id` INT NULL,
  `action` VARCHAR(100) NOT NULL,
  `target_user_id` INT NULL,
  `details` TEXT,
  `status` VARCHAR(20) DEFAULT 'success',
  `ip_address` VARCHAR(45),
  `user_agent` TEXT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_admin` (`admin_id`),
  INDEX `idx_action` (`action`),
  INDEX `idx_target` (`target_user_id`),
  INDEX `idx_date` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table des avis produits (module 4.10 / avis-commentaires)
CREATE TABLE IF NOT EXISTS `avis` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `produit_id` INT NOT NULL,
  `id_uti` INT NOT NULL,
  `note` TINYINT NOT NULL DEFAULT 5,
  `commentaire` TEXT,
  `statut` ENUM('en_attente','approuve','refuse') NOT NULL DEFAULT 'en_attente',
  `date_creation` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_produit` (`produit_id`),
  INDEX `idx_user` (`id_uti`),
  INDEX `idx_statut` (`statut`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Notes internes admin sur utilisateurs / vendeurs (CDC 6.1)
CREATE TABLE IF NOT EXISTS `notes_internes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `admin_id` INT NOT NULL,
  `target_type` ENUM('user','vendor','order') NOT NULL,
  `target_id` INT NOT NULL,
  `note` TEXT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_target` (`target_type`,`target_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Notifications admin (CDC module 10)
CREATE TABLE IF NOT EXISTS `admin_notifications` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `type` VARCHAR(50) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `message` TEXT,
  `link` VARCHAR(255) DEFAULT NULL,
  `is_read` TINYINT(1) DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_read` (`is_read`),
  INDEX `idx_date` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




-- Table des remboursements (gestion financière)
CREATE TABLE IF NOT EXISTS `remboursements` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `commande_id` INT NOT NULL,
  `id_client` INT NULL,
  `id_vendeur` INT NULL,
  `montant` DECIMAL(14,2) NOT NULL,
  `commission_recuperee` DECIMAL(14,2) DEFAULT 0,
  `motif` TEXT NOT NULL,
  `statut` ENUM('effectue','en_cours','annule') NOT NULL DEFAULT 'en_cours',
  `admin_id` INT NULL,
  `notifier_acheteur` TINYINT(1) DEFAULT 1,
  `notifier_vendeur` TINYINT(1) DEFAULT 1,
  `date_creation` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_commande` (`commande_id`),
  INDEX `idx_statut` (`statut`),
  INDEX `idx_date` (`date_creation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;






-- ---------------------------------------------------------------------------
-- Clés primaires manquantes (les tables du dump n'ont pas d'AUTO_INCREMENT)
-- Ces ALTER sont volontairement enveloppés dans un bloc "safe" : si la clé
-- existe déjà, MariaDB renverra une erreur ignorable. Exécute-les à la main
-- uniquement si nécessaire.
-- ---------------------------------------------------------------------------
-- ALTER TABLE `admin`               ADD PRIMARY KEY (`id_gestion`), MODIFY `id_gestion` INT NOT NULL AUTO_INCREMENT;
-- ALTER TABLE `categories`          ADD PRIMARY KEY (`id`),         MODIFY `id` INT NOT NULL AUTO_INCREMENT;
-- ALTER TABLE `commande`            ADD PRIMARY KEY (`a`),          MODIFY `a` INT NOT NULL AUTO_INCREMENT;
-- ALTER TABLE `demandes_vendeur`    ADD PRIMARY KEY (`id`),         MODIFY `id` INT NOT NULL AUTO_INCREMENT;
-- ALTER TABLE `produits`            ADD PRIMARY KEY (`id`),         MODIFY `id` INT NOT NULL AUTO_INCREMENT;
-- ALTER TABLE `retraits`            ADD PRIMARY KEY (`id`),         MODIFY `id` INT NOT NULL AUTO_INCREMENT;
-- ALTER TABLE `utilisateur`         ADD PRIMARY KEY (`id_uti`),     MODIFY `id_uti` INT NOT NULL AUTO_INCREMENT;
-- ALTER TABLE `portefeuille_vendeur` ADD PRIMARY KEY (`id`),        MODIFY `id` INT NOT NULL AUTO_INCREMENT;
