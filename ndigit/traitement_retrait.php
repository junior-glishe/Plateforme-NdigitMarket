<?php
require('header.php');

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo '<meta http-equiv="refresh" content="0;URL=login">';
    exit;
}

// Récupérer les infos utilisateur
$stmt = $database->prepare("SELECT id_uti FROM utilisateur WHERE email = ?");
$stmt->execute([$_SESSION['email']]);
$user = $stmt->fetch();

if (!$user) {
    echo '<script>alert("Utilisateur introuvable."); window.location.href="compte.php";</script>';
    exit;
}

$id_uti = $user['id_uti'];

// Vérifier que l'utilisateur est un vendeur accepté
$checkVendeur = $database->prepare("SELECT id FROM demandes_vendeur WHERE id_uti = ? AND statut = 'acceptee'");
$checkVendeur->execute([$id_uti]);

if (!$checkVendeur->fetch()) {
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<script>
        Swal.fire({
            icon: "error",
            title: "Action non autorisée",
            text: "Vous devez être un vendeur accepté pour effectuer un retrait."
        }).then(() => {
            window.location.href = "compte.php";
        });
    </script>';
    exit;
}

// Vérifier si une demande est déjà en cours
$checkPending = $database->prepare("SELECT id FROM retraits WHERE id_uti = ? AND statut = 'en_attente'");
$checkPending->execute([$id_uti]);

if ($checkPending->fetch()) {
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<script>
        Swal.fire({
            icon: "warning",
            title: "Demande en cours",
            text: "Vous avez déjà une demande de retrait en attente. Veuillez patienter qu\'elle soit finalisée avant d\'en faire une nouvelle.",
            confirmButtonColor: "#f59e0b"
        }).then(() => {
            window.location.href = "compte.php#tab-seller-withdrawals";
        });
    </script>';
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['montant'])) {
    $montant = floatval($_POST['montant']);
    $telephone = trim($_POST['telephone'] ?? '');

    // Nettoyage et validation du numéro MTN Bénin
    $tel = preg_replace('/\s+/', '', $telephone);
    $tel = ltrim($tel, '+');
    
    // Si l'utilisateur a saisi 10 chiffres commençant par 01, on ajoute 229
    if (preg_match('/^01[0-9]{8}$/', $tel)) {
        $tel = '229' . $tel;
    }
    
    // Vérification finale : doit être 22901XXXXXXXX (12 chiffres)
    if (!preg_match('/^22901[0-9]{8}$/', $tel)) {
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '<script>
            Swal.fire({
                icon: "error",
                title: "Numéro MTN invalide",
                text: "Le numéro doit être au format 01XXXXXXXX ou 22901XXXXXXXX.",
                confirmButtonColor: "#ef4444"
            }).then(() => {
                window.location.href = "compte.php";
            });
        </script>';
        exit;
    }

    // Vérifier le solde
    $stmtWallet = $database->prepare("SELECT solde FROM portefeuille_vendeur WHERE id_uti = ?");
    $stmtWallet->execute([$id_uti]);
    $wallet = $stmtWallet->fetch();

    $solde = $wallet ? $wallet['solde'] : 0;

    // Validation
    $errors = [];
    if ($montant < 2000) {
        $errors[] = "Le montant minimum de retrait est de 2 000 CFA.";
    }
    if ($montant > $solde) {
        $errors[] = "Votre solde est insuffisant (Solde actuel : " . number_format($solde, 0, ',', ' ') . " CFA).";
    }

    if (!empty($errors)) {
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '<script>
            Swal.fire({
                icon: "error",
                title: "Erreur de retrait",
                html: "' . implode('<br>', $errors) . '",
                confirmButtonColor: "#ef4444"
            }).then(() => {
                window.location.href = "compte.php";
            });
        </script>';
        exit;
    }

    // Insérer la demande de retrait (sans toucher au solde)
    try {
        $insert = $database->prepare("INSERT INTO retraits (id_uti, montant, telephone, statut, date_demande) VALUES (?, ?, ?, 'en_attente', NOW())");
        $insert->execute([$id_uti, $montant, $tel]);

        // Message de succès – redirection vers l'onglet retraits
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '<script>
            Swal.fire({
                icon: "success",
                title: "Demande envoyée !",
                text: "Votre demande de retrait de ' . number_format($montant, 0, ',', ' ') . ' CFA a été enregistrée sur le numéro ' . $tel . ' et est en attente de validation.",
                confirmButtonColor: "#087d67"
            }).then(() => {
                window.location.href = "compte.php#tab-seller-withdrawals";
            });
        </script>';

    } catch (Exception $e) {
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '<script>
            Swal.fire({
                icon: "error",
                title: "Erreur",
                text: "Une erreur est survenue lors du traitement de votre demande. Veuillez réessayer.",
                confirmButtonColor: "#ef4444"
            }).then(() => {
                window.location.href = "compte.php";
            });
        </script>';
    }
} else {
    header('Location: compte.php');
    exit;
}
?>