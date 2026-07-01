<?php
require('include/connect.php');
if (!isset($_SESSION)) {
    session_start();
}
$response = ['success' => false, 'message' => '', 'cart_total' => 0];

// Handle cart item deletion
if (isset($_GET['del'])) {
    $id_del = intval($_GET['del']);
    unset($_SESSION["panier"][$id_del]);
    header("Location: cart.php");
    exit();
}

// Handle "Add to Cart"
if (isset($_POST['ajouter_panier'])) {
    $id = intval($_POST['id']);
    $nombre = intval($_POST['nombre']);
    $nom = htmlspecialchars($_POST['nom'], ENT_QUOTES, 'UTF-8');
   
    // Verify product existence and category
    $produit = $database->prepare('
        SELECT produits.*, categories.nom_categorie
        FROM produits
        INNER JOIN categories ON produits.categorie_id = categories.id
        WHERE produits.id = :id
    ');
    $produit->execute([':id' => $id]);
    $donnee = $produit->fetch(PDO::FETCH_ASSOC);
    if (empty($donnee)) {
        $response['message'] = "Ce produit n'existe pas.";
        echo json_encode($response);
        exit();
    }
   
    // Initialize cart if not exists
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = array();
    }
   
    // Check if product is already in cart
    if (isset($_SESSION['panier'][$id])) {
        $_SESSION['panier_message'] = "Ce produit est déjà dans le panier.";
        header("Location: product_detail.php?nom_article=" . urlencode($nom));
        exit();
    }
   
    // Add product to cart
    $_SESSION['panier'][$id] = $nombre;
    $_SESSION['panier_message'] = "Produit ajouté au panier avec succès.";
    $response['success'] = true;
    $response['cart_total'] = array_sum($_SESSION['panier']);
    header("Location: product_detail.php?nom_article=" . urlencode($nom));
    exit();
}

// Handle "Buy Now"
elseif (isset($_POST['telecharge'])) {
    $id = intval($_POST['id']);
    $nombre = intval($_POST['nombre']);
    $nom = htmlspecialchars($_POST['nom'], ENT_QUOTES, 'UTF-8');
   
    // Verify product existence and category
    $produit = $database->prepare('
        SELECT produits.*, categories.nom_categorie
        FROM produits
        INNER JOIN categories ON produits.categorie_id = categories.id
        WHERE produits.id = :id
    ');
    $produit->execute([':id' => $id]);
    $donnee = $produit->fetch(PDO::FETCH_ASSOC);
    if (empty($donnee)) {
        $response['message'] = "Ce produit n'existe pas.";
        echo json_encode($response);
        exit();
    }
   
    // Verify if the product is not free
    if ($donnee['prix'] == 0) {
        $response['message'] = "Ce produit est gratuit, utilisez l'option de téléchargement gratuit.";
        echo json_encode($response);
        exit();
    }
   
    // Initialize cart if not exists
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = array();
    }
   
    // Add product to cart
    $_SESSION['panier'][$id] = $nombre;
    $_SESSION['panier_message'] = "Produit ajouté au panier pour paiement.";
   
    // Check for active subscription if product is in 'PACKS' category
    if ($donnee['nom_categorie'] === 'PACKS' && isset($_SESSION['user_id']) && isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        $stmt = $database->prepare("SELECT id_uti, type FROM utilisateur WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && $user['type'] === 'pro') {
            $stmt_abonnement = $database->prepare("SELECT * FROM abonnement WHERE id_uti2 = :user_id AND date_fin > NOW()");
            $stmt_abonnement->bindParam(':user_id', $user['id_uti'], PDO::PARAM_INT);
            $stmt_abonnement->execute();
            $abonnement = $stmt_abonnement->fetch(PDO::FETCH_ASSOC);
            if ($abonnement) {
                // Show alert for users with active subscription
                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: 'Produit PACKS',
                            html: 'Les produits PACKS nécessitent un paiement unique et ne sont pas inclus dans votre abonnement, même s\'il est actif.',
                            icon: 'info',
                            confirmButtonText: 'Continuer vers le paiement',
                            customClass: {
                                popup: 'custom-swal-popup',
                                title: 'custom-swal-title',
                                htmlContainer: 'custom-swal-content',
                                confirmButton: 'custom-swal-confirm'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'checkout?nom_article=" . urlencode($nom) . "&pack_alert=1';
                            }
                        });
                    });
                </script>";
                exit();
            }
        }
    }
   
    // Redirect to checkout for non-PACKS or users without active subscription
    $redirect_url = "checkout?nom_article=" . urlencode($nom);
    if ($donnee['nom_categorie'] === 'PACKS') {
        $redirect_url .= "&pack_alert=1";
    }
    header("Location: $redirect_url");
    exit();
}

// Handle "Free Download"
elseif (isset($_POST['telecharge_gratuit'])) {
    $id = intval($_POST['id']);
    $nombre = intval($_POST['nombre']);
    $nom = htmlspecialchars($_POST['nom'], ENT_QUOTES, 'UTF-8');
   
    // Verify product existence and category
    $produit = $database->prepare('
        SELECT produits.*, categories.nom_categorie
        FROM produits
        INNER JOIN categories ON produits.categorie_id = categories.id
        WHERE produits.id = :id
    ');
    $produit->execute([':id' => $id]);
    $donnee = $produit->fetch(PDO::FETCH_ASSOC);
    if (empty($donnee)) {
        $response['message'] = "Ce produit n'existe pas.";
        echo json_encode($response);
        exit();
    }
   
    // Verify if the product is free
    if ($donnee['prix'] != 0) {
        $response['message'] = "Ce produit n'est pas gratuit.";
        echo json_encode($response);
        exit();
    }
   
    // Check if user is logged in
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
        $response['message'] = "Vous devez être connecté pour télécharger gratuitement.";
        echo json_encode($response);
        exit();
    }
   
    // Get user ID
    $email = $_SESSION['email'];
    $stmt = $database->prepare("SELECT id_uti FROM utilisateur WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        $response['message'] = "Utilisateur non trouvé.";
        echo json_encode($response);
        exit();
    }
   
    // Insert into telechargements table
    $stmt = $database->prepare("INSERT INTO telechargements (id_produit, id_user, type, date_telechargement) VALUES (:id_produit, :id_user, 'gratuit', NOW())");
    $stmt->execute([':id_produit' => $id, ':id_user' => $user['id_uti']]);
   
    // Handle file download
    $fichier = $donnee['fichier'];
    if (!empty($fichier)) {
        $filePath = __DIR__ . '/back-end/apps/' . $fichier;
        $fileUrl = 'https://www.ndigitmarket.com/back-end/apps/' . $fichier;
        if (file_exists($filePath)) {
            // Redirect for large files (> 200MB)
            if (filesize($filePath) > 200 * 1024 * 1024) {
                header("Location: $fileUrl");
                exit();
            }
            // Direct download for smaller files
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Content-Length: ' . filesize($filePath));
            flush();
            readfile($filePath);
            exit();
        } else {
            $response['message'] = "Le fichier n'est pas disponible pour le téléchargement.";
            echo json_encode($response);
            exit();
        }
    } else {
        $response['message'] = "Aucun fichier de téléchargement n'est associé à ce produit.";
        echo json_encode($response);
        exit();
    }
}

// Handle "Pro Download"
elseif (isset($_POST['telecharge_pro'])) {
    // Forcer le fuseau horaire à WAT
    date_default_timezone_set('Africa/Lagos');
    
    $id = intval($_POST['id']);
    $nombre = intval($_POST['nombre']);
    $nom = htmlspecialchars($_POST['nom'], ENT_QUOTES, 'UTF-8');
   
    // Verify product existence and category
    $produit = $database->prepare('
        SELECT produits.*, categories.nom_categorie
        FROM produits
        INNER JOIN categories ON produits.categorie_id = categories.id
        WHERE produits.id = :id
    ');
    $produit->execute([':id' => $id]);
    $donnee = $produit->fetch(PDO::FETCH_ASSOC);
    if (empty($donnee)) {
        $response['message'] = "Ce produit n'existe pas.";
        echo json_encode($response);
        exit();
    }
   
    // Check if user is logged in
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
        $response['message'] = "Vous devez être connecté pour télécharger.";
        echo json_encode($response);
        exit();
    }
   
    // Get user ID and type
    $email = $_SESSION['email'];
    $stmt = $database->prepare("SELECT id_uti, type FROM utilisateur WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        $response['message'] = "Utilisateur non trouvé.";
        echo json_encode($response);
        exit();
    }
   
    // === CAS 1 : PRODUIT EST UN PACKS → Toujours paiement, même avec abonnement actif ===
    if ($donnee['nom_categorie'] === 'PACKS') {
        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = array();
        }
        $_SESSION['panier'][$id] = $nombre;
        $_SESSION['panier_message'] = "Produit ajouté au panier pour paiement.";

        // Vérifie si abonnement actif → affiche alerte
        if ($user['type'] === 'pro') {
            $stmt_abonnement = $database->prepare("SELECT * FROM abonnement WHERE id_uti2 = :user_id AND date_fin > NOW()");
            $stmt_abonnement->bindParam(':user_id', $user['id_uti'], PDO::PARAM_INT);
            $stmt_abonnement->execute();
            $abonnement = $stmt_abonnement->fetch(PDO::FETCH_ASSOC);

            if ($abonnement) {
                // Alerte pour abonnés actifs
                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: 'Produit PACKS',
                            html: 'Les produits <strong>PACKS</strong> nécessitent un paiement unique.<br>Ils ne sont pas inclus dans votre abonnement, même s\'il est actif.',
                            icon: 'info',
                            confirmButtonText: 'Continuer vers le paiement',
                            customClass: {
                                popup: 'custom-swal-popup',
                                title: 'custom-swal-title',
                                htmlContainer: 'custom-swal-content',
                                confirmButton: 'custom-swal-confirm'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'checkout?nom_article=" . urlencode($nom) . "&pack_alert=1';
                            }
                        });
                    });
                </script>";
                exit();
            }
        }

        // Sinon → redirection directe sans alerte
        header("Location: checkout?nom_article=" . urlencode($nom) . "&pack_alert=1");
        exit();
    }
   
    // === CAS 2 : PRODUIT NORMAL (pas PACKS) → Vérification abonnement + compteur ===
    if ($user['type'] !== 'pro') {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Abonnement requis',
                    html: 'Vous devez avoir un abonnement premium pour télécharger ce produit.',
                    icon: 'warning',
                    confirmButtonText: 'Voir les abonnements',
                    customClass: {
                        popup: 'custom-swal-popup',
                        title: 'custom-swal-title',
                        htmlContainer: 'custom-swal-content',
                        confirmButton: 'custom-swal-confirm'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'abonnement';
                    }
                });
            });
        </script>";
        exit();
    }
   
    // Vérifie si abonnement actif et téléchargements restants
    $stmt_abonnement = $database->prepare("
        SELECT id_abonnement, id_uti2, choix, date_debut, date_fin, nombre_telecharge, nombre_total 
        FROM abonnement 
        WHERE id_uti2 = :user_id 
        AND date_fin > NOW()
    ");
    $stmt_abonnement->bindParam(':user_id', $user['id_uti'], PDO::PARAM_INT);
    $stmt_abonnement->execute();
    $abonnement = $stmt_abonnement->fetch(PDO::FETCH_ASSOC);

    // Si pas d'abonnement actif OU limite atteinte → affiche alerte avec options
    if (!$abonnement || ($abonnement['nombre_telecharge'] >= $abonnement['nombre_total'])) {
        $error_message = $abonnement 
            ? ($abonnement['nombre_telecharge'] >= $abonnement['nombre_total'] 
                ? "Vous avez atteint la limite de téléchargements de votre abonnement."
                : "Votre abonnement est expiré.")
            : "Aucun abonnement actif trouvé.";
        
        // Ajouter le produit au panier pour le bouton "Ajouter au panier"
        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = array();
        }
        $_SESSION['panier'][$id] = $nombre;
        $_SESSION['panier_message'] = "Produit ajouté au panier.";

        // Débogage pour identifier la cause
        $debug_message = $abonnement 
            ? "Détails : id_uti2={$abonnement['id_uti2']}, date_fin={$abonnement['date_fin']}, nombre_telecharge={$abonnement['nombre_telecharge']}, nombre_total={$abonnement['nombre_total']}, date_actuelle=" . date('Y-m-d H:i:s') . ""
            : "Aucun abonnement actif trouvé pour id_uti2={$user['id_uti']}";

        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<style>
            .custom-swal-popup {
                background-color: #fff;
                border: 2px solid #0da487;
                border-radius: 12px;
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
                padding: 25px;
                max-width: 500px;
                width: 90%;
                font-family: 'Arial', sans-serif;
            }
            .custom-swal-title {
                color: #0da487;
                font-size: 1.5rem;
                font-weight: 700;
                margin-bottom: 15px;
            }
            .custom-swal-content {
                color: #333;
                font-size: 1rem;
                line-height: 1.6;
            }
            .custom-swal-confirm, .custom-swal-cart, .custom-swal-checkout {
                background-color: #ce5809;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 8px;
                font-size: 1rem;
                font-weight: 600;
                margin: 5px;
                transition: background-color 0.3s, transform 0.2s;
            }
            .custom-swal-confirm:hover, .custom-swal-cart:hover, .custom-swal-checkout:hover {
                background-color: #a64606;
                transform: scale(1.03);
            }
            @media (max-width: 600px) {
                .custom-swal-popup {
                    padding: 15px;
                    width: 95%;
                }
                .custom-swal-title {
                    font-size: 1.3rem;
                }
                .custom-swal-content {
                    font-size: 0.9rem;
                }
                .custom-swal-confirm, .custom-swal-cart, .custom-swal-checkout {
                    padding: 8px 15px;
                    font-size: 0.9rem;
                }
            }
        </style>";
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Limite atteinte ou abonnement expiré',
                    html: '$error_message<br><br><small></small>',
                    icon: 'warning',
                    showConfirmButton: true,
                    confirmButtonText: 'Renouveler l\\'abonnement',
                    showCancelButton: true,
                    cancelButtonText: 'Ajouter au panier',
                    showDenyButton: true,
                    denyButtonText: 'Acheter maintenant',
                    customClass: {
                        popup: 'custom-swal-popup',
                        title: 'custom-swal-title',
                        htmlContainer: 'custom-swal-content',
                        confirmButton: 'custom-swal-confirm',
                        cancelButton: 'custom-swal-cart',
                        denyButton: 'custom-swal-checkout'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'abonnement';
                    } else if (result.isDismissed && result.dismiss === Swal.DismissReason.cancel) {
                        window.location.href = 'cart';
                    } else if (result.isDenied) {
                        window.location.href = 'checkout?nom_article=" . urlencode($nom) . "&pack_alert=1';
                    }
                });
            });
        </script>";
        exit();
    }
   
    // === Téléchargement autorisé ===
    $stmt = $database->prepare("
        INSERT INTO telechargements (id_produit, id_user, type, date_telechargement) 
        VALUES (:id_produit, :id_user, 'abonnement', NOW())
    ");
    $stmt->execute([':id_produit' => $id, ':id_user' => $user['id_uti']]);
   
    // Incrémente le compteur
    $stmt_update = $database->prepare("
        UPDATE abonnement 
        SET nombre_telecharge = nombre_telecharge + 1 
        WHERE id_uti2 = :user_id
    ");
    $stmt_update->execute([':user_id' => $user['id_uti']]);
   
    // Téléchargement du fichier
    $fichier = $donnee['fichier'];
    if (!empty($fichier)) {
        $filePath = __DIR__ . '/back-end/apps/' . $fichier;
        $fileUrl = 'https://www.ndigitmarket.com/back-end/apps/' . $fichier;
        if (file_exists($filePath)) {
            if (filesize($filePath) > 200 * 1024 * 1024) {
                header("Location: $fileUrl");
                exit();
            }
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Content-Length: ' . filesize($filePath));
            flush();
            readfile($filePath);
            exit();
        } else {
            $response['message'] = "Le fichier n'est pas disponible.";
            echo json_encode($response);
            exit();
        }
    } else {
        $response['message'] = "Aucun fichier associé.";
        echo json_encode($response);
        exit();
    }
}

// Default case: redirect to index
else {
    header("Location: index");
    exit();
}
?>


 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="NTECH DIGIT">
    <meta name="keywords" content="NTECH DIGIT">
    <meta name="author" content="NTECH DIGIT">
    <link rel="icon" href="assets/images/favi.png" type="image/x-icon">
    <title>NDIGITMARKET - Votre marché en ligne pour des produits de qualité à prix abordables</title>