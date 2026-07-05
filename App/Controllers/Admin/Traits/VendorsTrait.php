<?php
/**
 * NDIGITMARKET - Admin trait : Vendors
 * Gestion des vendeurs : liste, validation, refus.
 */

namespace App\Controllers\Admin\Traits;

trait VendorsTrait
{
    public function vendors()
    {
        $this->checkAuth();
        $db = \Database::getConnection();

        // Filtres
        $where = [];
        $params = [];
        if (!empty($_GET['statut'])) {
            $where[] = 'dv.statut = :statut';
            $params[':statut'] = $_GET['statut'];
        }
        if (!empty($_GET['search'])) {
            $where[] = '(dv.nom_boutique LIKE :s OR u.nom LIKE :s OR u.prenom LIKE :s OR u.email LIKE :s)';
            $params[':s'] = '%' . $_GET['search'] . '%';
        }
        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $sql = "
            SELECT
                dv.id, dv.id_uti, dv.nom_boutique, dv.description, dv.categorie,
                dv.telephone, dv.image_boutique, dv.adresse, dv.pays, dv.statut, dv.date_demande,
                u.nom, u.prenom, u.email,
                COALESCE(pv.solde, 0)        AS solde_portefeuille,
                COALESCE(pv.total_gagne, 0)  AS total_gagne,
                COUNT(DISTINCT p.id)         AS nb_produits,
                COUNT(DISTINCT c.a)          AS nb_ventes
            FROM demandes_vendeur dv
            LEFT JOIN utilisateur u           ON u.id_uti  = dv.id_uti
            LEFT JOIN portefeuille_vendeur pv ON pv.id_uti = dv.id_uti
            LEFT JOIN produits p              ON p.id_vendeur = dv.id_uti
            LEFT JOIN commande c              ON c.id_article = p.id
            $whereClause
            GROUP BY dv.id
            ORDER BY dv.date_demande DESC
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $vendors = $stmt->fetchAll();

        foreach ($vendors as &$vendor) {
            $vendor['full_name']            = trim(($vendor['prenom'] ?? '') . ' ' . ($vendor['nom'] ?? ''));
            $vendor['solde_formatted']      = $this->formatCurrency((float) $vendor['solde_portefeuille']);
            $vendor['total_gagne_formatted']= $this->formatCurrency((float) $vendor['total_gagne']);
            $vendor['date_formatted']       = $this->formatDate($vendor['date_demande'], 'd/m/Y H:i');
            $vendor['statut_label'] = match ($vendor['statut'] ?? '') {
                'en_attente' => 'En attente',
                'acceptee'   => 'Acceptée',
                'refusee'    => 'Refusée',
                default      => 'N/A',
            };
            $vendor['statut_class'] = match ($vendor['statut'] ?? '') {
                'en_attente' => 'warning',
                'acceptee'   => 'success',
                'refusee'    => 'danger',
                default      => 'secondary',
            };
        }
        unset($vendor);

        $totalVendors = count($vendors);
        $currentPage  = 'gestion-vendeur';
        require_once __DIR__ . '/../../../Views/admin/gestion-vendeur.php';
    }

    /**
     * BUG FIX : la version précédente faisait
     *   $demande = $db->prepare(...)->execute([$id]) ? $db->prepare(...)->fetch() : null;
     * Le second prepare() n'était jamais exécuté -> $demande valait toujours false.
     */
    public function approveVendor(int $id): void
    {
        $this->checkAuth();
        $db = \Database::getConnection();
        $db->beginTransaction();
        try {
            $stmt = $db->prepare("SELECT id_uti FROM demandes_vendeur WHERE id = ?");
            $stmt->execute([$id]);
            $demande = $stmt->fetch();

            if (!$demande) {
                throw new \Exception("Demande introuvable");
            }

            $db->prepare("UPDATE demandes_vendeur SET statut = 'acceptee' WHERE id = ?")->execute([$id]);
            $db->prepare("UPDATE utilisateur SET type = 'pro' WHERE id_uti = ?")->execute([$demande['id_uti']]);

            // Créer le portefeuille s'il n'existe pas
            $check = $db->prepare("SELECT id FROM portefeuille_vendeur WHERE id_uti = ?");
            $check->execute([$demande['id_uti']]);
            if (!$check->fetch()) {
                $db->prepare("INSERT INTO portefeuille_vendeur (id_uti, solde, total_gagne) VALUES (?, 0, 0)")
                   ->execute([$demande['id_uti']]);
            }

            $db->commit();
            $this->logAction('approve_vendor', $id, "Vendeur approuvé (id_uti={$demande['id_uti']})");
            $this->jsonResponse(['success' => true, 'message' => 'Vendeur approuvé']);
        } catch (\Exception $e) {
            $db->rollBack();
            $this->logAction('approve_vendor', $id, "Échec: " . $e->getMessage(), 'error');
            $this->jsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

        // --- NOUVELLES MÉTHODES POUR LES ACTIONS AJAX ---

    public function rejectVendor() {
        $id = $_POST['id'] ?? null;
        $motif = $_POST['motif'] ?? '';
        if (!$id) return $this->json(['success' => false, 'message' => 'ID manquant'], 400);

        $db = $this->db;
        $stmt = $db->prepare("UPDATE vendeurs SET statut = 'refusee', motif_refus = ? WHERE id = ?");
        $stmt->execute([$motif, $id]);
        
        $this->logAction('rejectVendor', $id, 'success');
        return $this->json(['success' => true, 'message' => 'Demande refusée avec succès']);
    }

    public function updateVendor() {
        $id = $_POST['id'] ?? null;
        $nom = $_POST['nom_boutique'] ?? '';
        $desc = $_POST['description'] ?? '';
        $statut = $_POST['statut'] ?? 'actif';
        if (!$id) return $this->json(['success' => false, 'message' => 'ID manquant'], 400);

        $db = $this->db;
        $stmt = $db->prepare("UPDATE vendeurs SET nom_boutique = ?, description = ?, statut = ? WHERE id = ?");
        $stmt->execute([$nom, $desc, $statut, $id]);
        
        $this->logAction('updateVendor', $id, 'success');
        return $this->json(['success' => true, 'message' => 'Vendeur mis à jour']);
    }

    public function suspendVendor() {
        $id = $_POST['id'] ?? null;
        $motif = $_POST['motif'] ?? '';
        $duree = $_POST['duree'] ?? 'indefinie';
        if (!$id) return $this->json(['success' => false, 'message' => 'ID manquant'], 400);

        $db = $this->db;
        $date_fin = ($duree === 'indefinie') ? null : date('Y-m-d H:i:s', strtotime("+$duree days"));
        $stmt = $db->prepare("UPDATE vendeurs SET statut = 'suspendu', motif_suspension = ?, date_fin_suspension = ? WHERE id = ?");
        $stmt->execute([$motif, $date_fin, $id]);
        
        $this->logAction('suspendVendor', $id, 'success');
        return $this->json(['success' => true, 'message' => 'Vendeur suspendu']);
    }

    public function deleteVendor() {
        $id = $_POST['id'] ?? null;
        $confirmation = $_POST['confirmation'] ?? '';
        if ($confirmation !== 'SUPPRIMER') return $this->json(['success' => false, 'message' => 'Confirmation incorrecte'], 400);
        if (!$id) return $this->json(['success' => false, 'message' => 'ID manquant'], 400);

        $db = $this->db;
        // Sécurité : vérifier s'il reste des produits
        $check = $db->prepare("SELECT COUNT(*) FROM produits WHERE id_vendeur = ?");
        $check->execute([$id]);
        if ($check->fetchColumn() > 0) {
            return $this->json(['success' => false, 'message' => 'Impossible : ce vendeur a encore des produits. Supprimez-les d\'abord.'], 400);
        }

        $stmt = $db->prepare("DELETE FROM vendeurs WHERE id = ?");
        $stmt->execute([$id]);
        
        $this->logAction('deleteVendor', $id, 'success');
        return $this->json(['success' => true, 'message' => 'Vendeur supprimé']);
    }

    public function markPayment() {
        $id = $_POST['id'] ?? null;
        $montant = (float)($_POST['montant'] ?? 0);
        $reference = $_POST['reference'] ?? '';
        if (!$id || $montant <= 0) return $this->json(['success' => false, 'message' => 'Données invalides'], 400);

        $db = $this->db;
        // Décrémenter le solde (protection GREATEST pour éviter les négatifs)
        $stmt = $db->prepare("UPDATE vendeurs SET solde_portefeuille = GREATEST(0, solde_portefeuille - ?) WHERE id = ?");
        $stmt->execute([$montant, $id]);
        
        $this->logAction('markPayment', $id, 'success');
        return $this->json(['success' => true, 'message' => 'Versement de ' . $montant . ' FCFA marqué comme effectué']);
    }

    // NOUVELLE MÉTHODE : Récupérer les détails (Produits + Ventes) pour la modale
    public function getVendorDetails() {
        $id = $_GET['id'] ?? null;
        if (!$id) return $this->json(['success' => false], 400);

        $db = $this->db;
        
        // Récupérer les produits
        $stmtP = $db->prepare("SELECT id, nom_produit, prix, stock, statut FROM produits WHERE id_vendeur = ? ORDER BY date_ajout DESC LIMIT 10");
        $stmtP->execute([$id]);
        $produits = $stmtP->fetchAll();

        // Récupérer les ventes (commandes)
        $stmtV = $db->prepare("SELECT c.id, c.date_commande, c.montant_total, c.statut 
                               FROM commandes c 
                               JOIN produits p ON c.id_produit = p.id 
                               WHERE p.id_vendeur = ? 
                               ORDER BY c.date_commande DESC LIMIT 10");
        $stmtV->execute([$id]);
        $ventes = $stmtV->fetchAll();

        return $this->json([
            'success' => true, 
            'produits' => $produits, 
            'ventes' => $ventes
        ]);
    }
}

