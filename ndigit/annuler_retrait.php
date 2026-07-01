<?php
require('header.php');

// Vérifier connexion
if (!isset($_SESSION['user_id'])) {
    echo '<meta http-equiv="refresh" content="0;URL=login">';
    exit;
}

// Récupérer l'ID du retrait
$id_retrait = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Vérifier que le retrait appartient bien à l'utilisateur et est en attente
$stmt = $database->prepare("SELECT r.* FROM retraits r JOIN utilisateur u ON r.id_uti = u.id_uti WHERE r.id = ? AND u.email = ? AND r.statut = 'en_attente'");
$stmt->execute([$id_retrait, $_SESSION['email']]);
$retrait = $stmt->fetch();

if (!$retrait) {
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<script>
        Swal.fire({
            icon: "error",
            title: "Action impossible",
            text: "Cette demande ne peut pas être annulée.",
            confirmButtonColor: "#ef4444"
        }).then(() => {
            window.location.href = "compte.php#tab-seller-withdrawals";
        });
    </script>';
    exit;
}

// Supprimer la demande
$delete = $database->prepare("DELETE FROM retraits WHERE id = ?");
$delete->execute([$id_retrait]);

// Message de succès
echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
echo '<script>
    Swal.fire({
        icon: "success",
        title: "Demande annulée",
        text: "Votre demande de retrait a été annulée avec succès.",
        confirmButtonColor: "#087d67"
    }).then(() => {
        window.location.href = "compte.php#tab-seller-withdrawals";
    });
</script>';
?>