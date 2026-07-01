<?php
require('header.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abonnements Premium - NDIGITMARKET</title>
    <meta name="description" content="Découvrez nos offres d'abonnement exclusives sur NDIGITMARKET pour un accès illimité à des ressources premium. Choisissez le plan qui vous convient et commencez à télécharger dès aujourd'hui !">
    <meta name="keywords" content="NDIGITMARKET, abonnement, téléchargements, ressources premium, Mobile Money, accès illimité">
    
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
            --accent-light: #fff7ed;
            --dark: #0f1923;
            --dark-2: #1a2634;
            --text: #374151;
            --text-light: #6b7280;
            --border: #e5e7eb;
            --bg: #f9fafb;
            --white: #ffffff;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
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
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
        }

        /* ===== BREADCRUMB ===== */
        .breadcrumb-modern {
            background: linear-gradient(135deg, var(--dark) 0%, var(--dark-2) 100%);
            padding: 40px 0;
            margin-bottom: 50px;
            position: relative;
            overflow: hidden;
        }

        .breadcrumb-modern::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(8,125,103,0.2) 0%, transparent 70%);
            border-radius: 50%;
        }

        .breadcrumb-modern::after {
            content: '';
            position: absolute;
            bottom: -50px;
            left: -50px;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(249,115,22,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .breadcrumb-content {
            position: relative;
            z-index: 2;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .breadcrumb-content h1 {
            color: white;
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .breadcrumb-links {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255,255,255,0.6);
            flex-wrap: wrap;
        }

        .breadcrumb-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: color 0.3s;
            font-size: 14px;
        }

        .breadcrumb-links a:hover {
            color: white;
        }

        .breadcrumb-links i {
            font-size: 12px;
        }

        /* ===== HEADER TEXTE ===== */
        .pricing-header {
            text-align: center;
            max-width: 700px;
            margin: 0 auto 50px;
            padding: 0 20px;
        }

        .pricing-header h1 {
            font-size: 36px;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 15px;
        }

        .pricing-header h1 span {
            color: var(--primary);
            position: relative;
            display: inline-block;
        }

        .pricing-header h1 span::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 0;
            width: 100%;
            height: 8px;
            background: rgba(8,125,103,0.2);
            z-index: -1;
            border-radius: 4px;
        }

        .pricing-header h4 {
            font-size: 18px;
            color: var(--text-light);
            font-weight: 400;
            line-height: 1.6;
        }

        /* ===== CONTAINER ===== */
        .container-custom {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px 60px;
        }

        /* ===== GRILLE DES OFFRES ===== */
        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-bottom: 60px;
        }

        @media (max-width: 992px) {
            .pricing-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .pricing-grid {
                grid-template-columns: 1fr;
                max-width: 400px;
                margin-left: auto;
                margin-right: auto;
            }
        }

        /* ===== CARTE D'ABONNEMENT ===== */
        .pricing-card {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            padding: 30px 25px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
            opacity: 0;
            animation: fadeInUp 0.6s ease forwards;
        }

        .pricing-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-hover);
            border-color: var(--primary);
        }

        .pricing-card.highlight {
            border: 2px solid var(--accent);
            box-shadow: 0 15px 35px -10px rgba(249,115,22,0.3);
            transform: scale(1.02);
        }

        .pricing-card.highlight:hover {
            transform: scale(1.02) translateY(-8px);
        }

        .popular-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
            color: white;
            padding: 6px 15px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 10px rgba(249,115,22,0.3);
            z-index: 2;
        }

        .pricing-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary-light) 0%, white 100%);
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            color: var(--primary);
            font-size: 30px;
            border: 1px solid rgba(8,125,103,0.2);
        }

        .pricing-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .price-box {
            margin-bottom: 20px;
        }

        .price {
            font-size: 32px;
            font-weight: 800;
            color: var(--primary);
            line-height: 1.2;
        }

        .price span {
            font-size: 16px;
            font-weight: 400;
            color: var(--text-light);
        }

        .features-list {
            list-style: none;
            padding: 0;
            margin: 0 0 25px 0;
            flex: 1;
        }

        .features-list li {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px dashed var(--border);
            font-size: 14px;
            color: var(--text);
        }

        .features-list li:last-child {
            border-bottom: none;
        }

        .features-list li i {
            color: var(--success);
            font-size: 16px;
            flex-shrink: 0;
        }

        .features-list li i.fa-times-circle {
            color: var(--danger);
        }

        .btn-subscribe {
            display: block;
            padding: 15px 20px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            margin-top: auto;
            box-shadow: 0 5px 15px rgba(8,125,103,0.2);
        }

        .btn-subscribe:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(8,125,103,0.3);
        }

        .btn-subscribe i {
            margin-right: 8px;
        }

        .btn-subscribe.highlight {
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
            box-shadow: 0 5px 15px rgba(249,115,22,0.2);
        }

        .btn-subscribe.highlight:hover {
            box-shadow: 0 10px 25px rgba(249,115,22,0.3);
        }

        /* ===== MESSAGE DE CONFIRMATION ===== */
        .message-box {
            background: linear-gradient(135deg, var(--primary-light) 0%, white 100%);
            border: 1px solid var(--primary);
            border-radius: var(--radius);
            padding: 30px;
            text-align: center;
            max-width: 700px;
            margin: 30px auto;
            box-shadow: var(--shadow);
        }

        .message-box h2 {
            color: var(--primary);
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .message-box p {
            color: var(--text);
            margin-bottom: 20px;
            font-size: 16px;
            line-height: 1.6;
        }

        .message-box .btn-return {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 30px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 40px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .message-box .btn-return:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(8,125,103,0.3);
        }

        /* ===== SECTION FAQ ===== */
        .faq-section {
            background: white;
            border-radius: var(--radius);
            padding: 40px;
            margin-top: 40px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }

        .faq-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 30px;
            text-align: center;
        }

        .faq-title span {
            color: var(--primary);
        }

        .faq-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        @media (max-width: 768px) {
            .faq-grid {
                grid-template-columns: 1fr;
            }
        }

        .faq-item {
            background: var(--bg);
            border-radius: var(--radius-sm);
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid var(--border);
        }

        .faq-item:hover {
            border-color: var(--primary);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .faq-question {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 600;
            color: var(--dark);
            font-size: 16px;
        }

        .faq-question i {
            color: var(--primary);
            transition: transform 0.3s ease;
        }

        .faq-item.active .faq-question i {
            transform: rotate(45deg);
        }

        .faq-answer {
            margin-top: 15px;
            font-size: 14px;
            color: var(--text-light);
            line-height: 1.6;
            display: none;
        }

        .faq-item.active .faq-answer {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .pricing-card:nth-child(1) { animation-delay: 0.1s; }
        .pricing-card:nth-child(2) { animation-delay: 0.2s; }
        .pricing-card:nth-child(3) { animation-delay: 0.3s; }

        /* ===== UTILITAIRES ===== */
        .text-center {
            text-align: center;
        }

        .mt-4 { margin-top: 30px; }
        .mb-4 { margin-bottom: 30px; }

        /* ===== BANDEAU INFORMATIF ===== */
        .info-banner {
            background: linear-gradient(135deg, #e8f5f2 0%, #ffffff 100%);
            border: 1px solid var(--primary);
            border-radius: 16px;
            padding: 24px;
            margin: 0 auto 30px;
            max-width: 900px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(8,125,103,0.1);
        }

        .info-banner h3 {
            color: var(--primary);
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .info-banner p {
            color: var(--text);
            font-size: 15px;
            line-height: 1.6;
        }
    </style>
</head>
<body>

    <!-- Breadcrumb moderne -->
    <section class="breadcrumb-modern">
        <div class="breadcrumb-content">
            <h1>Abonnements Premium</h1>
            <div class="breadcrumb-links">
                <a href="index.php"><i class="fa-solid fa-house"></i> Accueil</a>
                <i class="fa-solid fa-chevron-right"></i>
                <span>Abonnements</span>
            </div>
        </div>
    </section>

    <!-- Bandeau informatif (affiché pour tous) -->
    <div class="container-custom">
        <div class="info-banner">
            <h3><i class="fa-solid fa-circle-info"></i> Abonnement réservé aux templates officiels NDIGITMARKET</h3>
            <p>
                Votre abonnement vous donne un accès illimité à l'ensemble de notre collection de <strong>plus de 500 templates premium</strong> créés par la boutique NDIGITMARKET. 
                Les produits vendus par d'autres créateurs ne sont pas inclus dans l'abonnement.
            </p>
        </div>
    </div>

    <div class="container-custom">
        <?php
        if (!isset($_SESSION['user_id'])) {
            echo '
            <div class="pricing-header">
                <h1>Débloquez un <span>Monde de Ressources</span> Premium !</h1>
                <h4>Rejoignez NDIGITMARKET et accédez instantanément à des milliers de ressources pour booster vos projets.</h4>
            </div>
            
            <div class="pricing-grid">
                <!-- Plan Essentiel -->
                <div class="pricing-card">
                    <div class="pricing-icon">
                        <i class="fa-solid fa-rocket"></i>
                    </div>
                    <h2 class="pricing-title">Essentiel</h2>
                    <div class="price-box">
                        <span class="price">6 000 <span>CFA / mois</span></span>
                    </div>
                    <ul class="features-list">
                        <li><i class="fa-solid fa-check-circle"></i> 95 téléchargements / 30 jours</li>
                        <li><i class="fa-solid fa-check-circle"></i> Accès à tous les templates NDIGITMARKET (+500 modèles)</li>
                        <li><i class="fa-solid fa-check-circle"></i> Téléchargement direct (collection officielle)</li>
                        <li><i class="fa-solid fa-check-circle"></i> 100 % sécurisé</li>
                        <li><i class="fa-solid fa-times-circle"></i> Templates de créateurs tiers exclus</li>
                    </ul>
                    <a href="checkout_abonnement?choix=Essentiel" class="btn-subscribe">
                        <i class="fa-solid fa-crown"></i> Choisir ce plan
                    </a>
                </div>
                
                <!-- Plan Avancé -->
                <div class="pricing-card highlight">
                    <div class="popular-badge">Populaire</div>
                    <div class="pricing-icon" style="color: var(--accent);">
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <h2 class="pricing-title">Avancé</h2>
                    <div class="price-box">
                        <span class="price">10 000 <span>CFA / mois</span></span>
                    </div>
                    <ul class="features-list">
                        <li><i class="fa-solid fa-check-circle"></i> 150 téléchargements / 30 jours</li>
                        <li><i class="fa-solid fa-check-circle"></i> Accès à tous les templates NDIGITMARKET (+500 modèles)</li>
                        <li><i class="fa-solid fa-check-circle"></i> Téléchargement direct (collection officielle)</li>
                        <li><i class="fa-solid fa-check-circle"></i> 100 % sécurisé</li>
                        <li><i class="fa-solid fa-times-circle"></i> Templates de créateurs tiers exclus</li>
                    </ul>
                    <a href="checkout_abonnement?choix=Avancé" class="btn-subscribe highlight">
                        <i class="fa-solid fa-crown"></i> Choisir ce plan
                    </a>
                </div>

                <!-- Plan Élite -->
                <div class="pricing-card">
                    <div class="pricing-icon" style="color: var(--accent);">
                        <i class="fa-solid fa-crown"></i>
                    </div>
                    <h2 class="pricing-title">Élite</h2>
                    <div class="price-box">
                        <span class="price">20 000 <span>CFA / mois</span></span>
                    </div>
                    <ul class="features-list">
                        <li><i class="fa-solid fa-check-circle"></i> 300 téléchargements / 30 jours</li>
                        <li><i class="fa-solid fa-check-circle"></i> Accès à tous les templates NDIGITMARKET (+500 modèles)</li>
                        <li><i class="fa-solid fa-check-circle"></i> Téléchargement direct (collection officielle)</li>
                        <li><i class="fa-solid fa-check-circle"></i> 100 % sécurisé</li>
                        <li><i class="fa-solid fa-times-circle"></i> Templates de créateurs tiers exclus</li>
                    </ul>
                    <a href="checkout_abonnement?choix=Élite" class="btn-subscribe">
                        <i class="fa-solid fa-crown"></i> Choisir ce plan
                    </a>
                </div>
            </div>

            <!-- Section FAQ -->
            <div class="faq-section">
                <h2 class="faq-title">Questions <span>Fréquentes</span></h2>
                <div class="faq-grid">
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>Que comprend l\'abonnement premium ?</span>
                            <i class="fa-solid fa-plus"></i>
                        </div>
                        <div class="faq-answer">
                            Profitez d\'un accès illimité à tous les templates officiels NDIGITMARKET (plus de 500 modèles), téléchargements directs, support prioritaire. Les créateurs tiers sont exclus.
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>Est-ce que l\'abonnement est renouvelé automatiquement ?</span>
                            <i class="fa-solid fa-plus"></i>
                        </div>
                        <div class="faq-answer">
                            Non, pas de surprises ! Nos abonnements sont sans renouvellement automatique. Renouvelez manuellement quand vous le souhaitez.
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>Comment puis-je annuler mon abonnement ?</span>
                            <i class="fa-solid fa-plus"></i>
                        </div>
                        <div class="faq-answer">
                            Annulez à tout moment en nous contactant par e-mail. Simple, rapide, sans frais cachés.
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>Quels sont les modes de paiement acceptés ?</span>
                            <i class="fa-solid fa-plus"></i>
                        </div>
                        <div class="faq-answer">
                            Payez via Mobile Money (MTN, Moov, Celtiis, BMO, Coris Money, TogoCom, Wave, Orange, Free Sénégal, Airtel, etc.) ou carte bancaire (Visa, MasterCard). Sécurisé et rapide !
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>Puis-je passer à un plan supérieur ?</span>
                            <i class="fa-solid fa-plus"></i>
                        </div>
                        <div class="faq-answer">
                            Oui, passez à un plan supérieur à tout moment depuis votre compte. Vos téléchargements restants seront ajustés.
                        </div>
                    </div>
                </div>
            </div>
            ';

        } else {
            $user_email = $_SESSION['email'];
            $stmt = $database->prepare("SELECT * FROM utilisateur WHERE email = ?");
            $stmt->execute([$user_email]);
            $user_info = $stmt->fetch(PDO::FETCH_ASSOC);
            $date_aujourdhui = new DateTime();

            if ($user_info['type'] == "-") {
                echo '
                <div class="pricing-header">
                    <h1>Débloquez un <span>Monde de Ressources</span> Premium !</h1>
                    <h4>Rejoignez NDIGITMARKET et accédez instantanément à des milliers de ressources pour booster vos projets.</h4>
                </div>
                
                <div class="pricing-grid">
                    <!-- Plan Essentiel -->
                    <div class="pricing-card">
                        <div class="pricing-icon">
                            <i class="fa-solid fa-rocket"></i>
                        </div>
                        <h2 class="pricing-title">Essentiel</h2>
                        <div class="price-box">
                            <span class="price">6 000 <span>CFA / mois</span></span>
                        </div>
                        <ul class="features-list">
                            <li><i class="fa-solid fa-check-circle"></i> 95 téléchargements / 30 jours</li>
                            <li><i class="fa-solid fa-check-circle"></i> Accès à tous les templates NDIGITMARKET (+500 modèles)</li>
                            <li><i class="fa-solid fa-check-circle"></i> Téléchargement direct (collection officielle)</li>
                            <li><i class="fa-solid fa-check-circle"></i> 100 % sécurisé</li>
                            <li><i class="fa-solid fa-times-circle"></i> Templates de créateurs tiers exclus</li>
                        </ul>
                        <a href="checkout_abonnement?choix=Essentiel" class="btn-subscribe">
                            <i class="fa-solid fa-crown"></i> Choisir ce plan
                        </a>
                    </div>
                    
                    <!-- Plan Avancé -->
                    <div class="pricing-card highlight">
                        <div class="popular-badge">Populaire</div>
                        <div class="pricing-icon" style="color: var(--accent);">
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <h2 class="pricing-title">Avancé</h2>
                        <div class="price-box">
                            <span class="price">10 000 <span>CFA / mois</span></span>
                        </div>
                        <ul class="features-list">
                            <li><i class="fa-solid fa-check-circle"></i> 150 téléchargements / 30 jours</li>
                            <li><i class="fa-solid fa-check-circle"></i> Accès à tous les templates NDIGITMARKET (+500 modèles)</li>
                            <li><i class="fa-solid fa-check-circle"></i> Téléchargement direct (collection officielle)</li>
                            <li><i class="fa-solid fa-check-circle"></i> 100 % sécurisé</li>
                            <li><i class="fa-solid fa-times-circle"></i> Templates de créateurs tiers exclus</li>
                        </ul>
                        <a href="checkout_abonnement?choix=Avancé" class="btn-subscribe highlight">
                            <i class="fa-solid fa-crown"></i> Choisir ce plan
                        </a>
                    </div>

                    <!-- Plan Élite -->
                    <div class="pricing-card">
                        <div class="pricing-icon" style="color: var(--accent);">
                            <i class="fa-solid fa-crown"></i>
                        </div>
                        <h2 class="pricing-title">Élite</h2>
                        <div class="price-box">
                            <span class="price">20 000 <span>CFA / mois</span></span>
                        </div>
                        <ul class="features-list">
                            <li><i class="fa-solid fa-check-circle"></i> 300 téléchargements / 30 jours</li>
                            <li><i class="fa-solid fa-check-circle"></i> Accès à tous les templates NDIGITMARKET (+500 modèles)</li>
                            <li><i class="fa-solid fa-check-circle"></i> Téléchargement direct (collection officielle)</li>
                            <li><i class="fa-solid fa-check-circle"></i> 100 % sécurisé</li>
                            <li><i class="fa-solid fa-times-circle"></i> Templates de créateurs tiers exclus</li>
                        </ul>
                        <a href="checkout_abonnement?choix=Élite" class="btn-subscribe">
                            <i class="fa-solid fa-crown"></i> Choisir ce plan
                        </a>
                    </div>
                </div>

                <!-- Section FAQ -->
                <div class="faq-section">
                    <h2 class="faq-title">Questions <span>Fréquentes</span></h2>
                    <div class="faq-grid">
                        <div class="faq-item">
                            <div class="faq-question">
                                <span>Que comprend l\'abonnement premium ?</span>
                                <i class="fa-solid fa-plus"></i>
                            </div>
                            <div class="faq-answer">
                                Profitez d\'un accès illimité à tous les templates officiels NDIGITMARKET (plus de 500 modèles), téléchargements directs, support prioritaire. Les créateurs tiers sont exclus.
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-question">
                                <span>Est-ce que l\'abonnement est renouvelé automatiquement ?</span>
                                <i class="fa-solid fa-plus"></i>
                            </div>
                            <div class="faq-answer">
                                Non, pas de surprises ! Nos abonnements sont sans renouvellement automatique. Renouvelez manuellement quand vous le souhaitez.
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-question">
                                <span>Comment puis-je annuler mon abonnement ?</span>
                                <i class="fa-solid fa-plus"></i>
                            </div>
                            <div class="faq-answer">
                                Annulez à tout moment en nous contactant par e-mail. Simple, rapide, sans frais cachés.
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-question">
                                <span>Quels sont les modes de paiement acceptés ?</span>
                                <i class="fa-solid fa-plus"></i>
                            </div>
                            <div class="faq-answer">
                                Payez via Mobile Money (MTN, Moov, Celtiis, BMO, Coris Money, TogoCom, Wave, Orange, Free Sénégal, Airtel, etc.) ou carte bancaire (Visa, MasterCard). Sécurisé et rapide !
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-question">
                                <span>Puis-je passer à un plan supérieur ?</span>
                                <i class="fa-solid fa-plus"></i>
                            </div>
                            <div class="faq-answer">
                                Oui, passez à un plan supérieur à tout moment depuis votre compte. Vos téléchargements restants seront ajustés.
                            </div>
                        </div>
                    </div>
                </div>
                ';

            }

            if ($user_info['type'] == "pro") {
                $stmt = $database->prepare("SELECT * FROM abonnement WHERE id_uti2 = ?");
                $stmt->execute([$user_info['id_uti']]);
                $abonnement = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($abonnement) {
                    $date_fin = new DateTime($abonnement['date_fin']);
                    $date_aujourdhui = new DateTime();

                    if ($date_aujourdhui < $date_fin) {
                        if ($abonnement['nombre_telecharge'] >= $abonnement['nombre_total']) {
                            echo '
                            <div class="message-box">
                                <h2>Limite de téléchargements atteinte</h2>
                                <p>Vous avez atteint votre quota de téléchargements. Passez à un plan supérieur pour continuer à profiter de nos ressources premium !</p>
                            </div>
                            
                            <div class="pricing-header">
                                <h1>Débloquez un <span>Monde de Ressources</span> Premium !</h1>
                                <h4>Rejoignez NDIGITMARKET et accédez instantanément à des milliers de ressources pour booster vos projets.</h4>
                            </div>
                            
                            <div class="pricing-grid">
                                <div class="pricing-card">
                                    <div class="pricing-icon"><i class="fa-solid fa-rocket"></i></div>
                                    <h2 class="pricing-title">Essentiel</h2>
                                    <div class="price-box"><span class="price">6 000 <span>CFA / mois</span></span></div>
                                    <ul class="features-list">
                                        <li><i class="fa-solid fa-check-circle"></i> 95 téléchargements / 30 jours</li>
                                        <li><i class="fa-solid fa-check-circle"></i> Accès à tous les templates NDIGITMARKET (+500 modèles)</li>
                                        <li><i class="fa-solid fa-check-circle"></i> Téléchargement direct (collection officielle)</li>
                                        <li><i class="fa-solid fa-check-circle"></i> 100 % sécurisé</li>
                                        <li><i class="fa-solid fa-times-circle"></i> Templates de créateurs tiers exclus</li>
                                    </ul>
                                    <a href="checkout_abonnement?choix=Essentiel" class="btn-subscribe"><i class="fa-solid fa-crown"></i> Choisir ce plan</a>
                                </div>
                                
                                <div class="pricing-card highlight">
                                    <div class="popular-badge">Populaire</div>
                                    <div class="pricing-icon" style="color: var(--accent);"><i class="fa-solid fa-star"></i></div>
                                    <h2 class="pricing-title">Avancé</h2>
                                    <div class="price-box"><span class="price">10 000 <span>CFA / mois</span></span></div>
                                    <ul class="features-list">
                                        <li><i class="fa-solid fa-check-circle"></i> 150 téléchargements / 30 jours</li>
                                        <li><i class="fa-solid fa-check-circle"></i> Accès à tous les templates NDIGITMARKET (+500 modèles)</li>
                                        <li><i class="fa-solid fa-check-circle"></i> Téléchargement direct (collection officielle)</li>
                                        <li><i class="fa-solid fa-check-circle"></i> 100 % sécurisé</li>
                                        <li><i class="fa-solid fa-times-circle"></i> Templates de créateurs tiers exclus</li>
                                    </ul>
                                    <a href="checkout_abonnement?choix=Avancé" class="btn-subscribe highlight"><i class="fa-solid fa-crown"></i> Choisir ce plan</a>
                                </div>

                                <div class="pricing-card">
                                    <div class="pricing-icon" style="color: var(--accent);"><i class="fa-solid fa-crown"></i></div>
                                    <h2 class="pricing-title">Élite</h2>
                                    <div class="price-box"><span class="price">20 000 <span>CFA / mois</span></span></div>
                                    <ul class="features-list">
                                        <li><i class="fa-solid fa-check-circle"></i> 300 téléchargements / 30 jours</li>
                                        <li><i class="fa-solid fa-check-circle"></i> Accès à tous les templates NDIGITMARKET (+500 modèles)</li>
                                        <li><i class="fa-solid fa-check-circle"></i> Téléchargement direct (collection officielle)</li>
                                        <li><i class="fa-solid fa-check-circle"></i> 100 % sécurisé</li>
                                        <li><i class="fa-solid fa-times-circle"></i> Templates de créateurs tiers exclus</li>
                                    </ul>
                                    <a href="checkout_abonnement?choix=Élite" class="btn-subscribe"><i class="fa-solid fa-crown"></i> Choisir ce plan</a>
                                </div>
                            </div>
                            
                            <div class="faq-section">...</div>';
                        } else {
                            echo '
                            <div class="message-box">
                                <h2>Votre abonnement est actif !</h2>
                                <p>Profitez de vos <strong>' . ($abonnement['nombre_total'] - $abonnement['nombre_telecharge']) . ' téléchargements restants</strong>. Explorez nos ressources premium !</p>
                                <a href="shop" class="btn-return"><i class="fa-solid fa-arrow-left"></i> Retourner au shopping</a>
                            </div>';
                        }
                    } else {
                        echo '
                        <div class="message-box">
                            <h2>Votre abonnement a expiré</h2>
                            <p>Renouvelez dès maintenant pour continuer à accéder à nos ressources premium !</p>
                        </div>
                        
                        <div class="pricing-header"><h1>Débloquez un <span>Monde de Ressources</span> Premium !</h1><h4>Rejoignez NDIGITMARKET et accédez instantanément à des milliers de ressources pour booster vos projets.</h4></div>
                        <div class="pricing-grid">
                            <div class="pricing-card">
                                <div class="pricing-icon"><i class="fa-solid fa-rocket"></i></div>
                                <h2 class="pricing-title">Essentiel</h2>
                                <div class="price-box"><span class="price">6 000 <span>CFA / mois</span></span></div>
                                <ul class="features-list">
                                    <li><i class="fa-solid fa-check-circle"></i> 95 téléchargements / 30 jours</li>
                                    <li><i class="fa-solid fa-check-circle"></i> Accès à tous les templates NDIGITMARKET (+500 modèles)</li>
                                    <li><i class="fa-solid fa-check-circle"></i> Téléchargement direct (collection officielle)</li>
                                    <li><i class="fa-solid fa-check-circle"></i> 100 % sécurisé</li>
                                    <li><i class="fa-solid fa-times-circle"></i> Templates de créateurs tiers exclus</li>
                                </ul>
                                <a href="checkout_abonnement?choix=Essentiel" class="btn-subscribe"><i class="fa-solid fa-crown"></i> Choisir ce plan</a>
                            </div>
                            <div class="pricing-card highlight">
                                <div class="popular-badge">Populaire</div>
                                <div class="pricing-icon" style="color: var(--accent);"><i class="fa-solid fa-star"></i></div>
                                <h2 class="pricing-title">Avancé</h2>
                                <div class="price-box"><span class="price">10 000 <span>CFA / mois</span></span></div>
                                <ul class="features-list">
                                    <li><i class="fa-solid fa-check-circle"></i> 150 téléchargements / 30 jours</li>
                                    <li><i class="fa-solid fa-check-circle"></i> Accès à tous les templates NDIGITMARKET (+500 modèles)</li>
                                    <li><i class="fa-solid fa-check-circle"></i> Téléchargement direct (collection officielle)</li>
                                    <li><i class="fa-solid fa-check-circle"></i> 100 % sécurisé</li>
                                    <li><i class="fa-solid fa-times-circle"></i> Templates de créateurs tiers exclus</li>
                                </ul>
                                <a href="checkout_abonnement?choix=Avancé" class="btn-subscribe highlight"><i class="fa-solid fa-crown"></i> Choisir ce plan</a>
                            </div>
                            <div class="pricing-card">
                                <div class="pricing-icon" style="color: var(--accent);"><i class="fa-solid fa-crown"></i></div>
                                <h2 class="pricing-title">Élite</h2>
                                <div class="price-box"><span class="price">20 000 <span>CFA / mois</span></span></div>
                                <ul class="features-list">
                                    <li><i class="fa-solid fa-check-circle"></i> 300 téléchargements / 30 jours</li>
                                    <li><i class="fa-solid fa-check-circle"></i> Accès à tous les templates NDIGITMARKET (+500 modèles)</li>
                                    <li><i class="fa-solid fa-check-circle"></i> Téléchargement direct (collection officielle)</li>
                                    <li><i class="fa-solid fa-check-circle"></i> 100 % sécurisé</li>
                                    <li><i class="fa-solid fa-times-circle"></i> Templates de créateurs tiers exclus</li>
                                </ul>
                                <a href="checkout_abonnement?choix=Élite" class="btn-subscribe"><i class="fa-solid fa-crown"></i> Choisir ce plan</a>
                            </div>
                        </div>
                        <div class="faq-section">...</div>';
                    }
                } else {
                    echo '<p style="text-align: center; padding: 20px;">Vous n\'avez pas d\'abonnement actif pour le moment.</p>';
                }
            }
        }
        ?>
    </div>

    <script>
        document.querySelectorAll('.faq-item').forEach(item => {
            item.addEventListener('click', () => {
                item.classList.toggle('active');
            });
        });
    </script>

    <?php require('footer.php'); ?>
</body>
</html>