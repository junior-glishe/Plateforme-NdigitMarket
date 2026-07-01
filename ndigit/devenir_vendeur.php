<?php
require('header.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devenir Vendeur - NDIGITMARKET</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            --warning: #f59e0b;
            --shadow: 0 10px 30px -10px rgba(0,0,0,0.1);
            --shadow-hover: 0 20px 40px -15px rgba(8,125,103,0.2);
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
        }

        /* ===== HERO ===== */
        .hero-section {
            background: linear-gradient(135deg, var(--dark) 0%, var(--dark-2) 100%);
            padding: 80px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(8,125,103,0.2) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(249,115,22,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: 0 auto;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(249,115,22,0.15);
            border: 1px solid rgba(249,115,22,0.3);
            color: var(--accent);
            padding: 8px 20px;
            border-radius: 100px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 24px;
        }

        .hero-badge i {
            animation: pulse 2s infinite;
        }

        .hero-content h1 {
            color: white;
            font-size: clamp(32px, 5vw, 48px);
            font-weight: 800;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .hero-content h1 span {
            color: var(--accent);
            position: relative;
            display: inline-block;
        }

        .hero-content h1 span::after {
            content: '';
            position: absolute;
            bottom: 4px;
            left: 0;
            width: 100%;
            height: 8px;
            background: rgba(249,115,22,0.2);
            border-radius: 4px;
            z-index: -1;
        }

        .hero-content p {
            color: rgba(255,255,255,0.7);
            font-size: 18px;
            max-width: 600px;
            margin: 0 auto 36px;
            line-height: 1.7;
        }

        .btn-hero {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--accent);
            color: white;
            padding: 16px 32px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 16px;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 8px 25px rgba(249,115,22,0.3);
        }

        .btn-hero:hover {
            background: var(--accent-dark);
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(249,115,22,0.4);
            color: white;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.2); }
        }

        /* ===== SECTIONS ===== */
        .section {
            padding: 80px 20px;
        }

        .container-custom {
            max-width: 1100px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--primary);
            margin-bottom: 12px;
        }

        .section-label::before {
            content: '';
            width: 20px;
            height: 3px;
            background: var(--primary);
            border-radius: 2px;
        }

        .section-title {
            font-size: clamp(26px, 3vw, 32px);
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 15px;
        }

        .section-subtitle {
            color: var(--text-light);
            font-size: 16px;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* ===== CARTES PRODUITS ===== */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 20px;
            margin-bottom: 50px;
        }

        .product-type-card {
            background: white;
            border-radius: var(--radius-sm);
            padding: 25px 20px;
            text-align: center;
            border: 1px solid var(--border);
            transition: all 0.3s;
            cursor: default;
        }

        .product-type-card:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-hover);
            transform: translateY(-5px);
        }

        .product-type-icon {
            width: 60px;
            height: 60px;
            background: var(--primary-light);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 28px;
            color: var(--primary);
        }

        .product-type-card h4 {
            font-size: 15px;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
        }

        /* ===== AVANTAGES ===== */
        .advantages-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        @media (max-width: 768px) {
            .advantages-grid {
                grid-template-columns: 1fr;
            }
        }

        .advantage-card {
            background: white;
            border-radius: var(--radius);
            padding: 30px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .advantage-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
            border-color: var(--primary);
        }

        .advantage-icon {
            width: 55px;
            height: 55px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .advantage-card h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .advantage-card p {
            color: var(--text-light);
            font-size: 14px;
            line-height: 1.6;
        }

        /* ===== ÉTAPES ===== */
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
        }

        @media (max-width: 992px) {
            .steps-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .steps-grid {
                grid-template-columns: 1fr;
            }
        }

        .step-card {
            text-align: center;
            position: relative;
        }

        .step-number {
            width: 50px;
            height: 50px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 700;
            margin: 0 auto 20px;
        }

        .step-card h4 {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .step-card p {
            color: var(--text-light);
            font-size: 13px;
            line-height: 1.5;
        }

        .step-arrow {
            display: none;
        }

        @media (min-width: 992px) {
            .step-arrow {
                display: block;
                position: absolute;
                top: 20px;
                right: -20px;
                color: var(--primary);
                font-size: 20px;
            }
        }

        /* ===== COMMISSION ===== */
        .commission-box {
            background: linear-gradient(135deg, var(--primary-light) 0%, white 100%);
            border-radius: var(--radius);
            padding: 40px;
            text-align: center;
            border: 1px solid var(--primary);
            box-shadow: var(--shadow);
        }

        .commission-box h3 {
            font-size: 22px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 15px;
        }

        .commission-box .highlight {
            display: inline-block;
            background: var(--primary);
            color: white;
            padding: 6px 16px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .commission-box p {
            color: var(--text-light);
            font-size: 15px;
            line-height: 1.6;
            max-width: 600px;
            margin: 0 auto 20px;
        }

        .commission-details {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
            margin-top: 25px;
        }

        .commission-item {
            text-align: center;
        }

        .commission-value {
            font-size: 32px;
            font-weight: 800;
            color: var(--primary);
        }

        .commission-label {
            font-size: 13px;
            color: var(--text-light);
        }

        /* ===== CTA ===== */
        .cta-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 60px 20px;
            text-align: center;
            border-radius: var(--radius);
            margin-top: 40px;
            color: white;
        }

        .cta-section h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .cta-section p {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 30px;
        }

        .btn-cta {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: white;
            color: var(--primary);
            padding: 16px 32px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 16px;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        .btn-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.3);
            color: var(--primary-dark);
        }

        /* ===== FAQ ===== */
        .faq-section {
            margin-top: 60px;
        }

        .faq-item {
            background: white;
            border-radius: var(--radius-sm);
            padding: 20px 25px;
            margin-bottom: 15px;
            border: 1px solid var(--border);
            cursor: pointer;
            transition: all 0.3s;
        }

        .faq-item:hover {
            border-color: var(--primary);
        }

        .faq-question {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            color: var(--dark);
            font-size: 16px;
        }

        .faq-question i {
            color: var(--primary);
            transition: transform 0.3s;
        }

        .faq-item.active .faq-question i {
            transform: rotate(45deg);
        }

        .faq-answer {
            margin-top: 15px;
            color: var(--text-light);
            font-size: 14px;
            line-height: 1.6;
            display: none;
        }

        .faq-item.active .faq-answer {
            display: block;
        }
    </style>
</head>
<body>

<!-- HERO -->
<section class="hero-section">
    <div class="hero-content">
        <div class="hero-badge">
            <i class="fa-solid fa-store"></i> Programme Vendeur
        </div>
        <h1>Vendez vos <span>créations numériques</span> sur NDIGITMARKET</h1>
        <p>
            Rejoignez notre marketplace et proposez vos templates, scripts, designs et ressources numériques 
            à des milliers d'acheteurs. Gagnez de l'argent en partageant votre travail !
        </p>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="compte.php" class="btn-hero">
                <i class="fa-solid fa-rocket"></i> Commencer à vendre
            </a>
        <?php else: ?>
            <a href="login.php" class="btn-hero">
                <i class="fa-solid fa-right-to-bracket"></i> Connectez-vous pour commencer
            </a>
        <?php endif; ?>
    </div>
</section>

<!-- TYPES DE PRODUITS -->
<section class="section" style="padding-bottom: 40px;">
    <div class="container-custom">
        <div class="section-header">
            <div class="section-label">📦 Ce que vous pouvez vendre</div>
            <h2 class="section-title">Des produits numériques variés</h2>
            <p class="section-subtitle">
                NDIGITMARKET accepte une large gamme de produits numériques téléchargeables. 
                Voici les catégories principales :
            </p>
        </div>

        <div class="products-grid">
            <div class="product-type-card">
                <div class="product-type-icon">🟦</div>
                <h4>Templates WordPress</h4>
            </div>
            <div class="product-type-card">
                <div class="product-type-icon">🧡</div>
                <h4>Templates HTML</h4>
            </div>
            <div class="product-type-card">
                <div class="product-type-icon">💜</div>
                <h4>Scripts PHP</h4>
            </div>
            <div class="product-type-card">
                <div class="product-type-icon">⚛️</div>
                <h4>Templates React</h4>
            </div>
            <div class="product-type-card">
                <div class="product-type-icon">🎨</div>
                <h4>Fichiers PSD</h4>
            </div>
            <div class="product-type-card">
                <div class="product-type-icon">🤖</div>
                <h4>Intelligence Artificielle</h4>
            </div>
            <div class="product-type-card">
                <div class="product-type-icon">📚</div>
                <h4>eBooks & Formations</h4>
            </div>
            <div class="product-type-card">
                <div class="product-type-icon">🎵</div>
                <h4>Musique & Audio</h4>
            </div>
            <div class="product-type-card">
                <div class="product-type-icon">🎬</div>
                <h4>Vidéos & Motion</h4>
            </div>
            <div class="product-type-card">
                <div class="product-type-icon">🔌</div>
                <h4>Plugins & Extensions</h4>
            </div>
        </div>
    </div>
</section>

<!-- AVANTAGES -->
<section class="section" style="background: white;">
    <div class="container-custom">
        <div class="section-header">
            <div class="section-label">⭐ Pourquoi nous rejoindre</div>
            <h2 class="section-title">Les avantages vendeur</h2>
            <p class="section-subtitle">
                Découvrez pourquoi des centaines de créateurs nous font confiance pour vendre leurs produits.
            </p>
        </div>

        <div class="advantages-grid">
            <div class="advantage-card">
                <div class="advantage-icon" style="background:#e8f5f2; color:#087d67;">
                    <i class="fa-solid fa-globe"></i>
                </div>
                <h3>Visibilité internationale</h3>
                <p>Vos produits sont visibles par des milliers d'acheteurs à travers le monde, principalement en Afrique francophone.</p>
            </div>

            <div class="advantage-card">
                <div class="advantage-icon" style="background:#fff7ed; color:#f97316;">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3>Paiement sécurisé</h3>
                <p>Toutes les transactions sont gérées par FedaPay. Vous recevez vos gains directement sur votre portefeuille.</p>
            </div>

            <div class="advantage-card">
                <div class="advantage-icon" style="background:#ede9fe; color:#7c3aed;">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <h3>Suivi en temps réel</h3>
                <p>Tableau de bord complet : ventes, téléchargements, revenus. Suivez vos performances en un clin d'œil.</p>
            </div>

            <div class="advantage-card">
                <div class="advantage-icon" style="background:#fce7f3; color:#db2777;">
                    <i class="fa-solid fa-coins"></i>
                </div>
                <h3>Retraits rapides</h3>
                <p>Demandez un retrait à partir de 2 000 CFA. Paiement direct via MTN Mobile Money.</p>
            </div>

            <div class="advantage-card">
                <div class="advantage-icon" style="background:#d1fae5; color:#10b981;">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <h3>Support dédié</h3>
                <p>Une équipe à votre écoute pour vous aider à optimiser vos ventes et résoudre vos problèmes.</p>
            </div>

            <div class="advantage-card">
                <div class="advantage-icon" style="background:#e0f2fe; color:#0284c7;">
                    <i class="fa-solid fa-gift"></i>
                </div>
                <h3>Commission compétitive</h3>
                <p>Seulement 10% de commission sur chaque vente. Vous gardez 90% de vos revenus.</p>
            </div>
        </div>
    </div>
</section>

<!-- ÉTAPES -->
<section class="section">
    <div class="container-custom">
        <div class="section-header">
            <div class="section-label">🚀 Comment ça marche</div>
            <h2 class="section-title">4 étapes simples</h2>
        </div>

        <div class="steps-grid">
            <div class="step-card">
                <div class="step-number">1</div>
                <h4>Créez votre compte</h4>
                <p>Inscrivez-vous gratuitement sur NDIGITMARKET.</p>
                <span class="step-arrow">→</span>
            </div>

            <div class="step-card">
                <div class="step-number">2</div>
                <h4>Faites une demande</h4>
                <p>Remplissez le formulaire "Devenir vendeur" depuis votre compte.</p>
                <span class="step-arrow">→</span>
            </div>

            <div class="step-card">
                <div class="step-number">3</div>
                <h4>Validation</h4>
                <p>Notre équipe examine votre demande sous 24-48h.</p>
                <span class="step-arrow">→</span>
            </div>

            <div class="step-card">
                <div class="step-number">4</div>
                <h4>Publiez & gagnez</h4>
                <p>Ajoutez vos produits et commencez à vendre !</p>
            </div>
        </div>
    </div>
</section>

<!-- COMMISSION -->
<section class="section" style="background: white;">
    <div class="container-custom">
        <div class="commission-box">
            <span class="highlight">💰 Commission transparente</span>
            <h3>Vous gardez 90% de vos ventes</h3>
            <p>
                NDIGITMARKET prélève une commission de <strong>10%</strong> sur chaque vente pour assurer 
                l'hébergement, la sécurité des transactions et la promotion de la plateforme.
            </p>
            <div class="commission-details">
                <div class="commission-item">
                    <div class="commission-value" style="color:#087d67;">90%</div>
                    <div class="commission-label">Pour le vendeur</div>
                </div>
                <div class="commission-item">
                    <div class="commission-value" style="color:#f97316;">10%</div>
                    <div class="commission-label">Commission plateforme</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="section">
    <div class="container-custom">
        <div class="cta-section">
            <h2>Prêt à commencer ?</h2>
            <p>Rejoignez NDIGITMARKET dès aujourd'hui et commencez à vendre vos créations numériques.</p>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="compte.php" class="btn-cta">
                    <i class="fa-solid fa-store"></i> Devenir vendeur maintenant
                </a>
            <?php else: ?>
                <a href="inscrire.php" class="btn-cta">
                    <i class="fa-solid fa-user-plus"></i> Créer un compte gratuit
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="section" style="padding-top: 20px;">
    <div class="container-custom">
        <div class="section-header">
            <div class="section-label">❓ Questions fréquentes</div>
            <h2 class="section-title">Tout ce que vous devez savoir</h2>
        </div>

        <div class="faq-section">
            <div class="faq-item">
                <div class="faq-question">
                    <span>Qui peut devenir vendeur sur NDIGITMARKET ?</span>
                    <i class="fa-solid fa-plus"></i>
                </div>
                <div class="faq-answer">
                    Toute personne majeure possédant des produits numériques originaux (templates, scripts, designs, etc.) peut faire une demande. Votre compte sera examiné par notre équipe sous 24-48h.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <span>Comment sont payés les vendeurs ?</span>
                    <i class="fa-solid fa-plus"></i>
                </div>
                <div class="faq-answer">
                    Les gains sont crédités dans votre portefeuille NDIGITMARKET. Vous pouvez demander un retrait à partir de 2 000 CFA directement sur votre compte MTN Mobile Money.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <span>Quels types de fichiers puis-je vendre ?</span>
                    <i class="fa-solid fa-plus"></i>
                </div>
                <div class="faq-answer">
                    Vous pouvez vendre des fichiers ZIP contenant des templates WordPress, HTML, PHP, React, PSD, des eBooks, des assets graphiques, des plugins, et tout autre produit numérique téléchargeable.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <span>Y a-t-il des frais pour devenir vendeur ?</span>
                    <i class="fa-solid fa-plus"></i>
                </div>
                <div class="faq-answer">
                    Non, l'inscription en tant que vendeur est <strong>totalement gratuite</strong>. Nous prélevons uniquement une commission de 10% sur chaque vente réalisée.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <span>Comment fonctionne la validation des produits ?</span>
                    <i class="fa-solid fa-plus"></i>
                </div>
                <div class="faq-answer">
                    Chaque produit que vous soumettez est examiné par notre équipe pour vérifier sa qualité et sa conformité avant d'être publié sur la plateforme.
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // FAQ accordéon
    document.querySelectorAll('.faq-item').forEach(item => {
        item.addEventListener('click', () => {
            // Fermer les autres
            document.querySelectorAll('.faq-item').forEach(other => {
                if (other !== item) other.classList.remove('active');
            });
            // Ouvrir/fermer celui-ci
            item.classList.toggle('active');
        });
    });
</script>

<?php require('footer.php'); ?>
</body>
</html>