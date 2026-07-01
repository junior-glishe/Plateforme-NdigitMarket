<?php
// upload.php — Fichier à placer à la racine du site

// ── Configuration de session qui s'adapte à l'environnement ──
if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
    session_set_cookie_params([
        'lifetime' => 86400,
        'path'     => '/',
        'secure'   => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
} else {
    session_set_cookie_params([
        'lifetime' => 86400,
        'path'     => '/',
        'domain'   => '.ndigitmarket.com',
        'secure'   => true,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}
session_start();

// Connexion à la base de données
require('include/connect.php');

// Désactiver l'affichage des erreurs dans la réponse
error_reporting(0);
ini_set('display_errors', 0);

// Nettoyer tout buffer de sortie éventuel
if (ob_get_level()) ob_clean();

header('Content-Type: application/json; charset=utf-8');

// ── Vérification de la session ──
if (!isset($_SESSION['user_id'], $_SESSION['email'])) {
    echo json_encode(["status" => "error", "message" => "Vous devez être connecté."]);
    exit;
}

// Récupération de l'utilisateur
$stmt = $database->prepare("SELECT id_uti, nom, prenom FROM utilisateur WHERE email = ?");
$stmt->execute([$_SESSION['email']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    echo json_encode(["status" => "error", "message" => "Utilisateur introuvable."]);
    exit;
}

// ── Vérification du statut vendeur accepté ──
$check = $database->prepare("SELECT statut FROM demandes_vendeur WHERE id_uti = ?");
$check->execute([$user['id_uti']]);
$demande = $check->fetch(PDO::FETCH_ASSOC);
if (!$demande || strtolower($demande['statut']) !== 'acceptee') {
    echo json_encode(["status" => "error", "message" => "Vous n'êtes pas autorisé à ajouter des produits."]);
    exit;
}

// ── Traitement de la requête POST ──
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Aucune donnée reçue."]);
    exit;
}

// Vérification des fichiers obligatoires
if (!isset($_FILES['imageProduit'], $_FILES['fichierUpload']) ||
    $_FILES['imageProduit']['error'] !== UPLOAD_ERR_OK ||
    $_FILES['fichierUpload']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(["status" => "error", "message" => "Fichiers manquants ou erreur de téléversement."]);
    exit;
}

// Récupération et nettoyage des champs
$nom_article   = trim($_POST['nom_article'] ?? '');
$description   = $_POST['description'] ?? '';
$prix          = $_POST['prix'] ?? 0;
$prix_reduction = $_POST['prix_reduction'] ?? 0;
$categorie_id  = $_POST['categorie'] ?? '';
$sous_cats     = is_array($_POST['sous_categorie'] ?? null) ? implode(',', $_POST['sous_categorie']) : '';
$apercu        = !empty($_POST['apercu']) ? $_POST['apercu'] : '';

// Validation minimale
if (empty($nom_article) || empty($categorie_id)) {
    echo json_encode(["status" => "error", "message" => "Nom du produit et catégorie sont obligatoires."]);
    exit;
}

// ── Gestion des fichiers ──
// Dossier physique où seront stockés les fichiers
$targetDir = 'back-end/apps/uploads/';
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}

// Image produit
$imgExt = strtolower(pathinfo($_FILES['imageProduit']['name'], PATHINFO_EXTENSION));
$validImgExts = ['jpg', 'jpeg', 'png', 'gif'];
if (!in_array($imgExt, $validImgExts)) {
    echo json_encode(["status" => "error", "message" => "Format image non valide (JPG, PNG, GIF)."]);
    exit;
}
// Générer un nom unique pour l'image
$imgName = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($_FILES['imageProduit']['name']));
$imgPath = $targetDir . $imgName; // Chemin physique complet
if (!move_uploaded_file($_FILES['imageProduit']['tmp_name'], $imgPath)) {
    echo json_encode(["status" => "error", "message" => "Échec de l'enregistrement de l'image."]);
    exit;
}
// Chemin relatif à stocker en base (uploads/nom_fichier)
$imgDbPath = 'uploads/' . $imgName;

// Fichier ZIP
$zipName = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($_FILES['fichierUpload']['name']));
$zipPath = $targetDir . $zipName; // Chemin physique complet
if (!move_uploaded_file($_FILES['fichierUpload']['tmp_name'], $zipPath)) {
    // Si le ZIP échoue, on supprime l'image déjà uploadée
    unlink($imgPath);
    echo json_encode(["status" => "error", "message" => "Échec de l'enregistrement du fichier ZIP."]);
    exit;
}
// Chemin relatif à stocker en base (uploads/nom_fichier)
$zipDbPath = 'uploads/' . $zipName;

// ── Insertion en base de données ──
try {
    $stmt = $database->prepare("INSERT INTO produits 
        (nom_article, prix, prix_reduction, image, fichier, categorie_id, sous_categorie, date_ajout, auteur, description, apercue, id_vendeur, statut)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, 'en_attente')");

    $stmt->execute([
        $nom_article,
        $prix,
        $prix_reduction,
        $imgDbPath,       // ex: uploads/69f27c75bafc6_image.jpg
        $zipDbPath,       // ex: uploads/69f27c75bafc6_tekz-wp.zip
        $categorie_id,
        $sous_cats,
        $user['nom'] . ' ' . $user['prenom'],
        $description,
        $apercu,
        $user['id_uti']
    ]);

    echo json_encode(["status" => "success", "message" => "Produit soumis avec succès ! Il sera visible après validation."]);
} catch (PDOException $e) {
    // En cas d'erreur, supprimer les fichiers uploadés
    unlink($imgPath);
    unlink($zipPath);
    echo json_encode(["status" => "error", "message" => "Erreur base de données : " . $e->getMessage()]);
}