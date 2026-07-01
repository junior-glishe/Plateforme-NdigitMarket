<?php
// session_start();
require('header.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Récupérer l'email de l'utilisateur connecté
$user_email = $_SESSION['email'];

// Récupérer le commande_id depuis l'URL
if (isset($_GET['commande_id'])) {
    $commande_id = $_GET['commande_id'];
} else {
    echo "<p>Commande non spécifiée.</p>";
    exit();
}

// Préparer la requête pour récupérer les informations de la commande
$query = "
    SELECT c.commande_id, c.id_article, c.image, c.prix, c.fichier, c.date_commande, 
           u.nom, u.prenom, p.nom_article, p.image AS produit_image, u.email
    FROM commande c
    JOIN utilisateur u ON c.id_client = u.id_uti
    JOIN produits p ON c.id_article = p.id
    WHERE c.commande_id = :commande_id
";

$stmt = $database->prepare($query);
$stmt->execute([':commande_id' => $commande_id]);
$commande_info = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$commande_info || $commande_info['email'] !== $user_email) {
    echo '<meta http-equiv="refresh" content="0;URL=index">';
    exit();
}

// Récupérer les produits de la commande
$products = [];
$query = "SELECT c.id_article, c.image, c.prix, c.fichier, p.nom_article, p.image AS produit_image 
          FROM commande c
          JOIN produits p ON c.id_article = p.id
          WHERE c.commande_id = :commande_id";
$stmt = $database->prepare($query);
$stmt->execute([':commande_id' => $commande_id]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation de commande - NDIGITMARKET</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        :root {
            --primary: #087d67;
            --primary-dark: #065a4a;
            --primary-light: #e8f5f2;
            --accent: #f97316;
            --accent-dark: #e67e22;
            --dark: #0f1923;
            --dark-2: #1a2634;
            --text: #374151;
            --text-light: #6b7280;
            --border: #e5e7eb;
            --bg: #f9fafb;
            --white: #ffffff;
            --success: #10b981;
            --shadow: 0 10px 30px -10px rgba(0,0,0,0.1);
            --shadow-hover: 0 20px 40px -15px rgba(8,125,103,0.3);
            --radius: 20px;
            --radius-sm: 12px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--bg) 0%, #ffffff 100%);
            color: var(--text);
            min-height: 100vh;
        }

        /* ===== CONTAINER PRINCIPAL ===== */
        .validation-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px 60px;
        }

        /* ===== SECTION SUCCÈS ===== */
        .success-section {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            padding: 50px 30px;
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
            animation: slideInDown 0.6s ease;
        }

        .success-section::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(8,125,103,0.05) 0%, transparent 70%);
            border-radius: 50%;
        }

        .success-section::after {
            content: '';
            position: absolute;
            bottom: -100px;
            left: -100px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(249,115,22,0.05) 0%, transparent 70%);
            border-radius: 50%;
        }

        /* Checkmark animé */
        .checkmark-wrapper {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            z-index: 2;
        }

        .checkmark-circle {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, var(--success) 0%, #0e9f6e 100%);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            box-shadow: 0 15px 35px rgba(16,185,129,0.3);
            animation: pulse 2s infinite;
        }

        .checkmark-circle i {
            font-size: 60px;
            color: white;
            animation: checkmark 0.8s ease;
        }

        .success-title {
            font-size: 32px;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 10px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--success) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .success-subtitle {
            font-size: 18px;
            color: var(--text-light);
            margin-bottom: 25px;
        }

        .order-info-card {
            background: linear-gradient(135deg, var(--bg) 0%, white 100%);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 25px;
            max-width: 500px;
            margin: 0 auto;
        }

        .order-info-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px dashed var(--border);
        }

        .order-info-item:last-child {
            border-bottom: none;
        }

        .order-info-label {
            color: var(--text-light);
            font-weight: 500;
        }

        .order-info-value {
            font-weight: 700;
            color: var(--dark);
        }

        .order-info-value.id {
            color: var(--primary);
            font-family: monospace;
            font-size: 16px;
        }

        /* ===== PRODUITS ===== */
        .products-section {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            padding: 30px;
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: var(--primary);
            font-size: 28px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .product-card {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 20px;
            display: flex;
            gap: 15px;
            transition: all 0.3s;
        }

        .product-card:hover {
            transform: translateY(-3px);
            border-color: var(--primary);
            box-shadow: var(--shadow-hover);
        }

        .product-image {
            width: 100px;
            height: 100px;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid var(--border);
            flex-shrink: 0;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-info {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-name {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .product-price {
            font-size: 18px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .btn-download {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 12px 20px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            margin-top: auto;
            width: fit-content;
        }

        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(8,125,103,0.3);
        }

        .btn-download i {
            font-size: 16px;
        }

        /* ===== RÉCAPITULATIF ===== */
        .summary-section {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            padding: 30px;
        }

        .summary-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--border);
        }

        .summary-header h3 {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark);
        }

        .summary-total {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .total-label {
            font-size: 18px;
            font-weight: 600;
            color: var(--text);
        }

        .total-price {
            font-size: 28px;
            font-weight: 800;
            color: var(--primary);
        }

        .btn-continue {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            background: var(--bg);
            color: var(--text);
            padding: 14px 20px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 16px;
            text-decoration: none;
            border: 1px solid var(--border);
            transition: all 0.3s;
        }

        .btn-continue:hover {
            background: var(--border);
            transform: translateY(-2px);
        }

        /* ===== ANIMATIONS ===== */
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        @keyframes checkmark {
            0% {
                transform: scale(0);
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
            }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .success-title {
                font-size: 24px;
            }
            
            .success-subtitle {
                font-size: 16px;
            }
            
            .products-grid {
                grid-template-columns: 1fr;
            }
            
            .product-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            
            .product-image {
                width: 120px;
                height: 120px;
            }
            
            .btn-download {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .checkmark-circle {
                width: 100px;
                height: 100px;
            }
            
            .checkmark-circle i {
                font-size: 50px;
            }
            
            .order-info-card {
                padding: 15px;
            }
            
            .total-price {
                font-size: 22px;
            }
        }

        /* Confetti animation */
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background: var(--accent);
            opacity: 0.6;
            animation: confetti 5s ease-in-out infinite;
        }

        @keyframes confetti {
            0% {
                transform: translateY(-100%) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(720deg);
                opacity: 0;
            }
        }
    </style>
</head>
<body>


<div class="validation-container">
    
    <!-- SECTION SUCCÈS -->
    <div class="success-section">
        <!-- Confetti animé (optionnel) -->
        <div class="confetti" style="left: 10%; animation-delay: 0s;"></div>
        <div class="confetti" style="left: 30%; background: var(--primary); animation-delay: 0.5s;"></div>
        <div class="confetti" style="left: 50%; background: var(--success); animation-delay: 1s;"></div>
        <div class="confetti" style="left: 70%; background: var(--accent); animation-delay: 1.5s;"></div>
        <div class="confetti" style="left: 90%; background: var(--primary); animation-delay: 2s;"></div>
        
        <div class="checkmark-wrapper">
            <div class="checkmark-circle">
                <i class="fa-regular fa-check"></i>
            </div>
            <h1 class="success-title">Paiement réussi !</h1>
            <p class="success-subtitle">Merci pour votre confiance, <?php echo htmlspecialchars($commande_info['prenom']); ?> 🎉</p>
        </div>
        
        <div class="order-info-card">
            <div class="order-info-item">
                <span class="order-info-label">Numéro de commande</span>
                <span class="order-info-value id">#<?php echo htmlspecialchars($commande_info['commande_id']); ?></span>
            </div>
            <div class="order-info-item">
                <span class="order-info-label">Date</span>
                <span class="order-info-value"><?php echo date('d/m/Y H:i', strtotime($commande_info['date_commande'])); ?></span>
            </div>
            <div class="order-info-item">
                <span class="order-info-label">Client</span>
                <span class="order-info-value"><?php echo htmlspecialchars($commande_info['prenom'] . ' ' . $commande_info['nom']); ?></span>
            </div>
        </div>
    </div>

    <!-- SECTION PRODUITS -->
    <div class="products-section">
        <h2 class="section-title">
            <i class="fa-regular fa-circle-down"></i>
            Vos fichiers à télécharger
        </h2>
        
        <div class="products-grid">
            <?php foreach ($products as $product): 
                $total += $product['prix'];
            ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="back-end/apps/<?php echo htmlspecialchars($product['produit_image']); ?>" 
                             alt="<?php echo htmlspecialchars($product['nom_article']); ?>">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name"><?php echo htmlspecialchars($product['nom_article']); ?></h3>
                        <div class="product-price"><?php echo number_format($product['prix'], 0, ',', ' '); ?> CFA</div>
                        <a href="back-end/apps/<?php echo htmlspecialchars($product['fichier']); ?>" 
                           class="btn-download" 
                           download>
                            <i class="fa-regular fa-circle-down"></i>
                            Télécharger
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- SECTION RÉCAPITULATIF -->
    <div class="summary-section">
        <div class="summary-header">
            <h3>Récapitulatif de la commande</h3>
            <span class="badge" style="background: var(--primary-light); color: var(--primary); padding: 5px 10px; border-radius: 30px;">
                <?php echo count($products); ?> article(s)
            </span>
        </div>
        
        <div class="summary-total">
            <span class="total-label">Total payé</span>
            <span class="total-price"><?php echo number_format($total, 0, ',', ' '); ?> CFA</span>
        </div>
        
        <div style="background: var(--bg); border-radius: 10px; padding: 15px; margin-bottom: 20px;">
            <p style="color: var(--text-light); margin-bottom: 5px;">
                <i class="fa-regular fa-envelope"></i>
                Une confirmation a été envoyée à :
            </p>
            <p style="font-weight: 600; color: var(--dark);"><?php echo htmlspecialchars($commande_info['email']); ?></p>
        </div>
        
        <a href="shop.php" class="btn-continue">
            <i class="fa-regular fa-store"></i>
            Continuer mes achats
        </a>
    </div>
</div>

<script>
// Animation supplémentaire au chargement
document.addEventListener('DOMContentLoaded', function() {
    // Faire briller le checkmark
    const checkmark = document.querySelector('.checkmark-circle');
    if (checkmark) {
        checkmark.style.animation = 'pulse 2s infinite, checkmark 0.8s ease';
    }
});
</script>

<?php require('footer.php'); ?>
</body>
</html>