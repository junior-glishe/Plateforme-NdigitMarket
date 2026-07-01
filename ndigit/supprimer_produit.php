<?php
require('header.php');

// Vérifier connexion et statut vendeur
if (!isset($_SESSION['user_id'])) {
    echo '<meta http-equiv="refresh" content="0;URL=login">';
    exit;
}

$stmtUser = $database->prepare("SELECT id_uti FROM utilisateur WHERE email = ?");
$stmtUser->execute([$_SESSION['email']]);
$user = $stmtUser->fetch();
if (!$user) { echo 'Utilisateur introuvable'; exit; }

$checkVendeur = $database->prepare("SELECT id FROM demandes_vendeur WHERE id_uti = ? AND statut = 'acceptee'");
$checkVendeur->execute([$user['id_uti']]);
if (!$checkVendeur->fetch()) {
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<script>Swal.fire("Accès refusé", "", "error").then(() => { window.location.href="compte.php"; });</script>';
    exit;
}

// Récupérer l'ID du produit
$id_produit = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Récupérer le produit pour vérifier qu'il appartient bien au vendeur
$stmtProd = $database->prepare("SELECT * FROM produits WHERE id = ? AND id_vendeur = ?");
$stmtProd->execute([$id_produit, $user['id_uti']]);
$produit = $stmtProd->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<script>Swal.fire("Produit introuvable", "", "error").then(() => { window.location.href="liste_produits.php"; });</script>';
    exit;
}

// 1. Supprimer les enregistrements liés dans telechargements
$deleteTelechargements = $database->prepare("DELETE FROM telechargements WHERE id_produit = ?");
$deleteTelechargements->execute([$id_produit]);

// 2. Supprimer les enregistrements liés dans commande (si applicable)
$deleteCommandes = $database->prepare("DELETE FROM commande WHERE id_article = ?");
$deleteCommandes->execute([$id_produit]);

// 3. Supprimer les fichiers associés
$targetDir = 'back-end/apps/';
if (!empty($produit['image']) && file_exists($targetDir . $produit['image'])) {
    unlink($targetDir . $produit['image']);
}
if (!empty($produit['fichier']) && file_exists($targetDir . $produit['fichier'])) {
    unlink($targetDir . $produit['fichier']);
}

// 4. Supprimer le produit de la base de données
$delete = $database->prepare("DELETE FROM produits WHERE id = ? AND id_vendeur = ?");
$delete->execute([$id_produit, $user['id_uti']]);

// Redirection avec message de succès
echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
echo '<script>
    Swal.fire({
        icon: "success",
        title: "Produit supprimé",
        text: "Le produit et tous les fichiers associés ont été supprimés avec succès.",
        confirmButtonColor: "#087d67"
    }).then(() => {
        window.location.href = "liste_produits.php";
    });
</script>';