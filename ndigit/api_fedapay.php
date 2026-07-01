<?php
ob_start(); // Démarre la mise en tampon de la sortie

require_once('fedapay/init.php');
use FedaPay\FedaPay;
use FedaPay\Transaction;

FedaPay::setEnvironment('live'); // Mode sandbox pour les tests
FedaPay::setApiKey('sk_live_4ZBrSu1MUy-TPU2kNv8GI-IO');  // Remplacez par votre clé API

// Informations utilisateur (exemple)
$nom = $user_info['nom'] ?? 'Inconnu';
$prenom = $user_info['prenom'] ?? 'Inconnu';
$email = $user_info['email'] ?? 'inconnu@example.com';

// URL de redirection après succès ou annulation
$callback_url = "http://localhost/ndigitmarket/transaction_status.php?status=success&transaction_id=".$transaction."";  
$cancel_url = "http://localhost/ndigitmarket/transaction_status.php?status=cancelled&transaction_id=".$transaction."";  

// Assurez-vous que les classes nécessaires sont incluses pour gérer la transaction (par exemple, avec l'API FedaPay)

$transaction = null; // Variable pour la transaction (à ajuster en fonction de l'API FedaPay que vous utilisez)

// Créer une transaction via l'API FedaPay (modifie cette partie selon l'API que tu utilises)
$transaction = Transaction::create([
    "description" => "Achat sur ton site",
    "amount" => $total,  // Montant total du panier
    "currency" => ["iso" => "XOF"],
    "callback_url" => "http://localhost/ndigitmarket/transaction_status.php?status=success&transaction_id=" . urlencode($transaction->id),
    "cancel_url" => "http://localhost/ndigitmarket/transaction_status.php?status=cancelled&transaction_id=" . urlencode($transaction->id),
    "metadata" => [
        "nom" => $nom,
        "prenom" => $prenom,
        "email" => $email,
        "total" => $total,
        "phone_number" => $phone_number
    ]
]);

// Vérifier si la transaction a été créée avec succès
if ($transaction) {
    // Récupérer les URLs de redirection
    $callback_url = "http://localhost/ndigitmarket/transaction_status.php?status=success&transaction_id=" . urlencode($transaction->id);
    $cancel_url = "http://localhost/ndigitmarket/transaction_status.php?status=cancelled&transaction_id=" . urlencode($transaction->id);

    // Vérifier si la transaction est en attente, rediriger vers l'URL appropriée
    if ($transaction->status === 'pending') {
        // Redirige vers l'URL de statut en attente
        header("Location: $callback_url");
        exit();
    } else {
        // Redirige immédiatement vers la page de succès ou d'annulation selon l'état
        if ($transaction->status === 'success') {
            header("Location: $callback_url");
        } else {
            header("Location: $cancel_url");
        }
        exit();
    }
} else {
    // En cas d'échec de la création de la transaction
    echo "Erreur lors de la création de la transaction.";
}



ob_end_flush();  // Finalise la sortie PHP
?>

<!-- Code HTML du bouton -->
<button class="btn pay-btn theme-bg-color text-white btn-md w-100 mt-4 fw-bold" 
    <?php if (!$is_client_connected): ?> 
        disabled 
    <?php endif; ?>
    onclick="startPayment('<?php echo $transactionId; ?>');">
    Passer la commande
</button>

<?php if (!$is_client_connected): ?>
    <p style="color: red; text-align: center;">Veuillez vous connecter pour passer la commande.</p>
<?php endif; ?>

<script src="https://cdn.fedapay.com/checkout.js?v=1.1.7"></script>
<script type="text/javascript">
    function startPayment(transactionId) {
        var transactionDetails = {
            public_key: 'pk_live_ZWZcZt1Lvwup1NY0j4zArx9B',  // Ta clé publique FedaPay
            transaction_id: transactionId
        };

        // Initialisation du widget FedaPay
        var widget = FedaPay.init('.pay-btn', {
            public_key: transactionDetails.public_key,
            transaction: {
                id: transactionDetails.transaction_id
            }
        });

        // Ouvre le widget sur la même page (dans un modal)
        widget.open();

        // Ajouter un écouteur pour l'événement de succès
      widget.on('transaction.completed', function(event) {
    console.log('Transaction réussie !', event);
    // Créer l'URL de redirection
    var successUrl = "<?php echo $callback_url; ?>" + event.transaction.id;
    console.log("Redirection vers : " + successUrl);  // Afficher l'URL avant redirection
    // Rediriger vers la page de succès après paiement réussi
    window.location.href = successUrl;
});

// Ajouter un écouteur pour l'événement d'annulation
widget.on('transaction.cancelled', function() {
    console.log('Transaction annulée.');
    // Créer l'URL d'annulation
    var cancelUrl = "<?php echo $cancel_url; ?>" + event.transaction.id;
    console.log("Redirection vers : " + cancelUrl);  // Afficher l'URL avant redirection
    // Rediriger vers la page d'annulation
    window.location.href = cancelUrl;
});

// Ajouter un écouteur pour l'événement d'erreur
widget.on('transaction.error', function(error) {
    console.log('Erreur dans la transaction : ', error);
    // Créer l'URL d'annulation en cas d'erreur
    var errorUrl = "<?php echo $cancel_url; ?>" + event.transaction.id;
    console.log("Redirection vers : " + errorUrl);  // Afficher l'URL avant redirection
    // Rediriger vers la page d'annulation
    window.location.href = errorUrl;
});

    }
</script>
