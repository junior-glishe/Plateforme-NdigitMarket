<?php require('header.php'); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="lbUYNaEqrCLdufs5HEios_Z_L9b5Wud72zVdI3ctwhA" />
    <link rel="icon" href="assets/images/favi.png" type="image/x-icon">
    <title>NDIGITMARKET - Templates & Ressources Web Premium</title>
    <meta name="description" content="Découvrez des milliers de templates WordPress, HTML, PHP, React et PSD professionnels à prix abordables. Livraison instantanée.">
    <meta name="keywords" content="NDIGITMARKET, templates WordPress, modèles HTML, scripts PHP, React templates, PSD, ressources web">
    <meta property="og:title" content="NDIGITMARKET - Templates & Ressources Web Premium">
    <meta property="og:description" content="Votre bibliothèque de ressources web : templates vérifiés, installation facile, support rapide inclus.">
    <meta property="og:url" content="https://www.ndigitmarket.com">

    <!-- Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

    <style>
        :root {
            --primary: #087d67;
            --primary-dark: #065a4a;
            --primary-light: #e8f5f2;
            --accent: #f97316;
            --dark: #0f1923;
            --dark-2: #1a2634;
            --text: #374151;
            --text-light: #6b7280;
            --border: #e5e7eb;
            --bg: #f9fafb;
            --white: #ffffff;
            --badge-new: #10b981;
            --badge-hot: #ef4444;
            --badge-sale: #f97316;
            --badge-gold: #FFD700;
            --badge-silver: #C0C0C0;
            --badge-bronze: #CD7F32;
            --radius: 12px;
            --shadow: 0 4px 24px rgba(0,0,0,0.08);
            --shadow-hover: 0 12px 40px rgba(8,125,103,0.18);
        }

        body {
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
        }

        /* ===== HERO ===== */
        .hero {
            background: linear-gradient(135deg, var(--dark) 0%, var(--dark-2) 60%, #0d3329 100%);
            min-height: 580px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23087d67' fill-opacity='0.07'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .hero::after {
            content: '';
            position: absolute;
            right: -100px;
            top: -100px;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(8,125,103,0.25) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 1200px;
            margin: 0 auto;
            padding: 80px 24px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(8,125,103,0.2);
            border: 1px solid rgba(8,125,103,0.4);
            color: #4ade80;
            padding: 6px 16px;
            border-radius: 100px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 24px;
            animation: fadeInDown 0.6s ease;
        }

        .hero-badge span { background: #4ade80; width: 8px; height: 8px; border-radius: 50%; animation: pulse 2s infinite; }

        .hero h1 {
            font-size: clamp(32px, 5vw, 58px);
            font-weight: 800;
            color: var(--white);
            line-height: 1.15;
            max-width: 700px;
            margin-bottom: 20px;
            animation: fadeInUp 0.7s ease;
        }

        .hero h1 span { color: #4ade80; }

        .hero p {
            color: rgba(255,255,255,0.72);
            font-size: 18px;
            max-width: 560px;
            line-height: 1.7;
            margin-bottom: 36px;
            animation: fadeInUp 0.8s ease;
        }

        .hero-cta {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            animation: fadeInUp 0.9s ease;
        }

        .btn-hero-primary {
            background: var(--primary);
            color: white;
            padding: 16px 32px;
            border-radius: var(--radius);
            font-weight: 700;
            font-size: 16px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-hero-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(8,125,103,0.4);
        }

        .btn-hero-outline {
            background: transparent;
            color: white;
            padding: 16px 32px;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 16px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            border: 1px solid rgba(255,255,255,0.25);
            cursor: pointer;
        }

        .btn-hero-outline:hover {
            background: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.5);
        }

        /* ===== SECTION COMMONS ===== */
        .section { padding: 64px 24px; }
        .section-bg { background: white; }
        .section-dark { background: var(--dark); }

        .container { max-width: 1200px; margin: 0 auto; }

        .section-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 36px;
            gap: 16px;
            flex-wrap: wrap;
        }

        .section-title-group {}

        .section-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .section-label::before {
            content: '';
            width: 20px;
            height: 3px;
            background: var(--primary);
            border-radius: 2px;
        }

        .section-title {
            font-size: clamp(22px, 3vw, 30px);
            font-weight: 800;
            color: var(--dark);
            line-height: 1.2;
        }

        .section-subtitle {
            color: var(--text-light);
            font-size: 15px;
            margin-top: 8px;
        }

        .section-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--primary);
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            white-space: nowrap;
            transition: gap 0.2s;
        }

        .section-link:hover { gap: 10px; }

        /* ===== PRODUCT CARD ===== */
        .product-grid {
            display: grid;
            gap: 20px;
        }

        .grid-5 { grid-template-columns: repeat(5, 1fr); }
        .grid-4 { grid-template-columns: repeat(4, 1fr); }
        .grid-3 { grid-template-columns: repeat(3, 1fr); }

        @media (max-width: 1100px) {
            .grid-5 { grid-template-columns: repeat(4, 1fr); }
            .grid-4 { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 800px) {
            .grid-5, .grid-4 { grid-template-columns: repeat(2, 1fr); }
            .grid-3 { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 480px) {
            .grid-5, .grid-4, .grid-3 { grid-template-columns: repeat(2, 1fr); }
        }

        .product-card {
            background: white;
            border-radius: var(--radius);
            overflow: hidden;
            border: 1.5px solid var(--border);
            transition: all 0.3s;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-hover);
            transform: translateY(-4px);
        }

        .product-card-img {
            position: relative;
            overflow: hidden;
            aspect-ratio: 16/10;
            background: var(--bg);
        }

        .product-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
        }

        .product-card:hover .product-card-img img {
            transform: scale(1.05);
        }

        .product-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            z-index: 2;
        }

        .badge-new { background: var(--badge-new); color: white; }
        .badge-hot { background: var(--badge-hot); color: white; }
        .badge-sale { background: var(--badge-sale); color: white; }
        .badge-free { background: #6366f1; color: white; }
        .badge-gold { background: var(--badge-gold); color: #000; }
        .badge-silver { background: var(--badge-silver); color: #000; }
        .badge-bronze { background: var(--badge-bronze); color: #fff; }
        .badge-popular { background: #ff6b6b; color: white; }

        .discount-tag {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--badge-hot);
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            z-index: 2;
        }

        .product-overlay {
            position: absolute;
            inset: 0;
            background: rgba(15,25,35,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 3;
        }

        .product-card:hover .product-overlay { opacity: 1; }

        .btn-preview {
            background: white;
            color: var(--dark);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-preview:hover { background: var(--primary); color: white; }

        .product-card-body {
            padding: 14px 16px 16px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .product-category {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--primary);
        }

        .product-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--dark);
            line-height: 1.4;
            text-decoration: none;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-name:hover { color: var(--primary); }

        .product-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
        }

        .product-price {
            display: flex;
            flex-direction: column;
        }

        .price-old {
            font-size: 11px;
            color: var(--text-light);
            text-decoration: line-through;
        }

        .price-new {
            font-size: 16px;
            font-weight: 800;
            color: var(--primary);
        }

        .price-free {
            font-size: 16px;
            font-weight: 800;
            color: #6366f1;
        }

        .price-premium {
            font-size: 13px;
            font-weight: 700;
            color: var(--accent);
            background: #fff7ed;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .btn-card {
            background: var(--primary-light);
            color: var(--primary);
            border: none;
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .btn-card:hover {
            background: var(--primary);
            color: white;
        }

        .sales-count {
            font-size: 11px;
            color: var(--text-light);
            margin-top: 2px;
        }

        .sales-count i {
            color: var(--accent);
            margin-right: 2px;
        }

        .seller-name {
            font-size: 11px;
            color: var(--text-light);
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 4px;
        }

        .seller-name i {
            color: var(--primary);
            font-size: 12px;
        }

        /* ===== FEATURED (VEDETTE) ===== */
        .featured-card {
            border-radius: 16px;
            overflow: hidden;
            background: white;
            border: 1.5px solid var(--border);
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }

        .featured-card:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-5px);
            border-color: var(--primary);
        }

        .featured-img {
            position: relative;
            aspect-ratio: 16/9;
            overflow: hidden;
        }

        .featured-img img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
        }

        .featured-card:hover .featured-img img { transform: scale(1.05); }

        .featured-badge {
            position: absolute;
            top: 14px;
            left: 14px;
            background: var(--accent);
            color: white;
            font-weight: 700;
            font-size: 12px;
            padding: 5px 12px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
            z-index: 2;
        }

        .featured-body {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .featured-cat {
            font-size: 12px;
            font-weight: 700;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .featured-name {
            font-size: 17px;
            font-weight: 800;
            color: var(--dark);
            text-decoration: none;
            line-height: 1.3;
        }

        .featured-name:hover { color: var(--primary); }

        .featured-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
        }

        .featured-sales {
            font-size: 12px;
            color: var(--text-light);
        }

        .featured-sales i {
            color: var(--accent);
        }

        .featured-seller {
            font-size: 11px;
            color: var(--text-light);
            margin-top: 5px;
        }

        /* ===== TABS ===== */
        .tabs {
            display: flex;
            gap: 4px;
            background: var(--bg);
            border-radius: 10px;
            padding: 4px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            background: transparent;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-light);
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .tab-btn.active, .tab-btn:hover {
            background: white;
            color: var(--primary);
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .tab-content { display: none; }
        .tab-content.active { display: block; }

        /* ===== SWIPER ===== */
        .swiper-main { padding-bottom: 40px !important; }

        .swiper-main .swiper-pagination-bullet { background: var(--primary); }

        .swiper-main .swiper-button-prev,
        .swiper-main .swiper-button-next {
            color: var(--primary);
            background: white;
            border-radius: 50%;
            width: 44px;
            height: 44px;
            box-shadow: var(--shadow);
        }

        .swiper-main .swiper-button-prev::after,
        .swiper-main .swiper-button-next::after { font-size: 18px; }

        /* ===== VIEW ALL BTN ===== */
        .view-all-wrap { text-align: center; margin-top: 36px; }

        .btn-view-all {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
            padding: 14px 32px;
            border-radius: var(--radius);
            font-weight: 700;
            font-size: 15px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-view-all:hover {
            background: var(--primary);
            color: white;
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 600px) {
            .section { padding: 48px 16px; }
            .hero-stats { gap: 24px; }
            .promo-banner { padding: 28px; }
        }

        /* ===== FREE GRID (5 colonnes) ===== */
        .free-grid.grid-5 {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
        }

        @media (max-width: 1100px) {
            .free-grid.grid-5 {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 800px) {
            .free-grid.grid-5 {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
        }

        @media (max-width: 480px) {
            .free-grid.grid-5 {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }
        }

        /* ===== TESTIMONIAL SECTION ===== */
        .testimonial-swiper {
            padding: 20px 10px 60px 10px;
        }

        .testimonial-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            transition: all 0.3s;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
            border-color: var(--primary);
        }

        .testimonial-rating {
            margin-bottom: 20px;
            color: #ffb800;
        }

        .testimonial-rating i {
            margin-right: 3px;
            font-size: 16px;
        }

        .testimonial-text {
            font-size: 15px;
            line-height: 1.7;
            color: var(--text);
            margin-bottom: 25px;
            flex: 1;
            font-style: italic;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 15px;
            border-top: 1px solid var(--border);
            padding-top: 20px;
        }

        .testimonial-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-light);
        }

        .testimonial-author-info h4 {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .testimonial-author-info p {
            font-size: 13px;
            color: var(--text-light);
        }

        .testimonial-pagination {
            bottom: 0 !important;
        }

        .testimonial-pagination .swiper-pagination-bullet {
            background: var(--primary);
            opacity: 0.3;
        }

        .testimonial-pagination .swiper-pagination-bullet-active {
            opacity: 1;
        }

        /* ===== TRUST STATS ===== */
        .trust-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            margin-top: 50px;
            text-align: center;
        }

        @media (max-width: 800px) {
            .trust-stats {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }
        }

        .trust-stat-item {
            padding: 20px;
        }

        .trust-stat-number {
            font-size: 32px;
            font-weight: 800;
            color: var(--primary);
            line-height: 1.2;
            margin-bottom: 5px;
        }

        .trust-stat-label {
            font-size: 14px;
            color: var(--text-light);
            font-weight: 500;
        }

        /* ===== WHY CHOOSE SECTION ===== */
        .why-choose-section {
            background: linear-gradient(135deg, var(--primary-light) 0%, white 100%);
        }

        .why-choose-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        @media (max-width: 900px) {
            .why-choose-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .why-choose-grid {
                grid-template-columns: 1fr;
            }
        }

        .why-choose-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            border: 1px solid var(--border);
            transition: all 0.3s;
        }

        .why-choose-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow);
            border-color: var(--primary);
        }

        .why-choose-icon {
            width: 70px;
            height: 70px;
            background: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 30px;
            color: var(--primary);
            transition: all 0.3s;
        }

        .why-choose-card:hover .why-choose-icon {
            background: var(--primary);
            color: white;
        }

        .why-choose-card h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 15px;
        }

        .why-choose-card p {
            font-size: 14px;
            color: var(--text-light);
            line-height: 1.6;
        }

        /* =============================================
           CORRECTIONS À COPIER DANS index2.php
           Remplace les sections CSS et JS correspondantes
        ============================================= */

        /* === FIX 1 : Swiper "Templates les plus vendus" === */

        /* Supprimer l'espace excessif sous les cartes */
        .swiper-main {
            padding-bottom: 48px !important;
            position: relative;
        }

        /* Boutons ← → centrés verticalement sur les cartes, pas décalés en bas */
        .swiper-main .swiper-button-prev,
        .swiper-main .swiper-button-next {
            color: var(--primary);
            background: white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
            /* Remonter de 24px pour sortir de la zone pagination */
            top: calc(50% - 24px);
            transform: translateY(-50%);
        }

        .swiper-main .swiper-button-prev::after,
        .swiper-main .swiper-button-next::after {
            font-size: 15px;
            font-weight: 800;
        }

        /* Pagination ancrée tout en bas du swiper */
        .swiper-main .swiper-pagination {
            bottom: 10px !important;
        }
        .swiper-main .swiper-pagination-bullet {
            background: var(--primary);
            opacity: 0.35;
        }
        .swiper-main .swiper-pagination-bullet-active {
            opacity: 1;
            width: 20px;
            border-radius: 4px;
        }

        /* === FIX 2 : Section Témoignages === */
        .testimonial-swiper {
            padding-bottom: 48px !important;
            padding-top: 8px;
        }

        /* Toutes les slides à égale hauteur — la clé du fix */
        .testimonial-swiper .swiper-wrapper {
            align-items: stretch;
        }
        .testimonial-swiper .swiper-slide {
            height: auto;       /* laisse la hauteur se calculer */
            display: flex;      /* stretch interne */
        }

        .testimonial-card {
            background: white;
            border-radius: 18px;
            padding: 26px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
            border: 1px solid var(--border);
            transition: all 0.3s;
            width: 100%;
            display: flex;
            flex-direction: column;
            /* flex:1 pour que toutes les cartes remplissent la hauteur de la slide */
            flex: 1;
        }

        .testimonial-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(8,125,103,0.15);
            border-color: var(--primary);
        }

        .testimonial-rating {
            margin-bottom: 16px;
            color: #ffb800;
        }
        .testimonial-rating i { font-size: 14px; margin-right: 2px; }

        .testimonial-text {
            font-size: 14px;
            line-height: 1.75;
            color: var(--text);
            /* flex:1 pousse l'auteur tout en bas */
            flex: 1;
            font-style: italic;
            margin-bottom: 20px;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 12px;
            border-top: 1px solid var(--border);
            padding-top: 16px;
            margin-top: auto;
        }
        .testimonial-avatar {
            width: 48px; height: 48px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-light);
            flex-shrink: 0;
        }
        .testimonial-author-info h4 { font-size: 14px; font-weight: 700; color: var(--dark); margin-bottom: 3px; }
        .testimonial-author-info p  { font-size: 12px; color: var(--text-light); }

        .testimonial-pagination {
            bottom: 10px !important;
        }
        .testimonial-pagination .swiper-pagination-bullet {
            background: var(--primary);
            opacity: 0.3;
        }
        .testimonial-pagination .swiper-pagination-bullet-active {
            opacity: 1;
            width: 20px;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<!-- =========================================================
     HERO
========================================================== -->
<section class="hero">
    <div class="hero-content">
        <div class="hero-badge">
            <span></span>
            +300 ressources disponibles maintenant
        </div>
        <h1>Des templates <span>professionnels</span><br>prêts pour vos projets</h1>
        <p>WordPress, HTML, PHP, React, PSD — trouvez le template parfait, téléchargez instantanément et lancez votre site dès aujourd'hui.</p>
        <div class="hero-cta">
            <a href="shop.php" class="btn-hero-primary">
                <i class="fa-solid fa-rocket"></i> Explorer tous les templates
            </a>
            <a href="gratuit" class="btn-hero-outline">
                <i class="fa-solid fa-gift"></i> Templates gratuits
            </a>
        </div>
    </div>
</section>

<!-- =========================================================
     SECTION : EN VEDETTE (TOP VENTES)
========================================================== -->
<section class="section section-bg">
    <div class="container">
        <div class="section-header">
            <div class="section-title-group">
                <div class="section-label">⭐ Meilleures ventes</div>
                <h2 class="section-title">Templates les plus vendus</h2>
                <p class="section-subtitle">Les templates préférés de notre communauté</p>
            </div>
            <a href="shop.php?sort=best-sellers" class="section-link">Voir tout <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        <div class="swiper swiper-main" id="featuredSwiper">
            <div class="swiper-wrapper">
                <?php
                $resultats = $database->query('
                    SELECT p.*, c.nom_categorie, dv.nom_boutique AS vendeur_boutique,
                           COUNT(co.id_article) AS nombre_commandes
                    FROM produits p
                    INNER JOIN categories c ON p.categorie_id = c.id
                    LEFT JOIN demandes_vendeur dv ON p.id_vendeur = dv.id_uti AND dv.statut = "acceptee"
                    LEFT JOIN commande co ON p.id = co.id_article
                    WHERE p.statut = "approuve"
                    GROUP BY p.id
                    ORDER BY nombre_commandes DESC, p.date_ajout DESC
                    LIMIT 16
                ');
                $featuredProducts = $resultats->fetchAll(PDO::FETCH_ASSOC);

                if (empty($featuredProducts) || $featuredProducts[0]['nombre_commandes'] == 0) {
                    $resultats = $database->query('
                        SELECT p.*, c.nom_categorie, dv.nom_boutique AS vendeur_boutique,
                               0 AS nombre_commandes
                        FROM produits p
                        INNER JOIN categories c ON p.categorie_id = c.id
                        LEFT JOIN demandes_vendeur dv ON p.id_vendeur = dv.id_uti AND dv.statut = "acceptee"
                        WHERE p.statut = "approuve"
                        ORDER BY p.date_ajout DESC
                        LIMIT 16
                    ');
                    $featuredProducts = $resultats->fetchAll(PDO::FETCH_ASSOC);
                }

                $featuredSlides = array_chunk($featuredProducts, 4);
                foreach ($featuredSlides as $slide):
                ?>
                <div class="swiper-slide">
                    <div class="product-grid grid-4">
                        <?php foreach ($slide as $i => $produit):
                            $imagePath = 'back-end/apps/' . htmlspecialchars($produit['image']);
                            if (!file_exists($imagePath)) $imagePath = 'back-end/apps/Blue.jpg';
                            $hasSale = $produit['prix_reduction'] > 0;
                            $isFree = $produit['prix'] == 0;
                            $disc = $hasSale ? round((($produit['prix'] - $produit['prix_reduction']) / $produit['prix']) * 100) : 0;
                            // Utilise le nom de la boutique
                            $sellerName = $produit['vendeur_boutique'] ?? '';

                            $rank = $i + 1;
                            $badgeClass = '';
                            $badgeText = '';
                            if ($produit['nombre_commandes'] > 0) {
                                if ($rank == 1) {$badgeClass = 'badge-gold'; $badgeText = '🥇 #1 des ventes';}
                                elseif ($rank == 2) {$badgeClass = 'badge-silver'; $badgeText = '🥈 #2 des ventes';}
                                elseif ($rank == 3) {$badgeClass = 'badge-bronze'; $badgeText = '🥉 #3 des ventes';}
                                else {$badgeClass = 'badge-popular'; $badgeText = '🔥 Populaire';}
                            }
                        ?>
                        <div class="featured-card">
                            <div class="featured-img">
                                <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($produit['nom_article']); ?>" loading="lazy">
                                <?php if (!empty($badgeText)): ?>
                                <div class="featured-badge <?php echo $badgeClass; ?>" style="background: <?php 
                                    echo $badgeClass == 'badge-gold' ? '#FFD700' : 
                                        ($badgeClass == 'badge-silver' ? '#C0C0C0' : 
                                        ($badgeClass == 'badge-bronze' ? '#CD7F32' : '#ff6b6b')); 
                                ?>; color: <?php echo $badgeClass == 'badge-gold' || $badgeClass == 'badge-silver' ? '#000' : '#fff'; ?>;">
                                    <?php echo $badgeText; ?>
                                </div>
                                <?php endif; ?>
                                <?php if ($hasSale): ?><div class="discount-tag">-<?php echo $disc; ?>%</div><?php endif; ?>
                                <div class="product-overlay">
                                    <a class="btn-preview" href="product_detail.php?nom_article=<?php echo urlencode($produit['nom_article']); ?>">
                                        <i class="fa-solid fa-eye"></i> Voir le détail
                                    </a>
                                </div>
                            </div>
                            <div class="featured-body">
                                <span class="featured-cat"><?php echo htmlspecialchars($produit['nom_categorie']); ?></span>
                                <a href="product_detail.php?nom_article=<?php echo urlencode($produit['nom_article']); ?>" class="product-name">
                                    <?php echo htmlspecialchars(mb_strimwidth($produit['nom_article'], 0, 20, '...')); ?>
                                </a>
                                <?php if (!empty($sellerName)): ?>
                                    <div class="featured-seller">🏪 <?php echo htmlspecialchars($sellerName); ?></div>
                                <?php endif; ?>
                                <div class="featured-footer">
                                    <div class="product-price">
                                        <?php if ($isFree): ?>
                                            <span class="price-free">Gratuit</span>
                                        <?php elseif ($hasSale): ?>
                                            <span class="price-old"><?php echo number_format($produit['prix'], 0); ?> <?php echo $cfa; ?></span>
                                            <span class="price-new"><?php echo number_format($produit['prix_reduction'], 0); ?> <?php echo $cfa; ?></span>
                                        <?php else: ?>
                                            <span class="price-new"><?php echo number_format($produit['prix'], 0); ?> <?php echo $cfa; ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($produit['nombre_commandes'] > 0): ?>
                                    <div class="featured-sales">
                                        <i class="fa-solid fa-cart-shopping"></i> <?php echo $produit['nombre_commandes']; ?> ventes
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

<!-- =========================================================
     SECTION : PLUS VENDUS + TOP PAR CATÉGORIE
========================================================== -->
<!-- =========================================================
     SECTION : PLUS VENDUS + TOP PAR CATÉGORIE
========================================================== -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-title-group">
                <div class="section-label">🔥 Les plus populaires</div>
                <h2 class="section-title">Plus Vendus & Tendances</h2>
                <p class="section-subtitle">Les templates préférés de notre communauté par catégorie</p>
            </div>
        </div>

        <?php
        $catIcons = ['WordPress'=>'🟦','HTML'=>'🧡','PHP'=>'💜','React'=>'⚛️','PSD'=>'🎨','Plugin'=>'🔌','JavaScript'=>'💛'];
        $tabCats = ['WordPress','HTML','PHP','React','Plugin','PSD'];
        ?>
        <div class="tabs" id="catTabs">
            <?php
            $first = true;
            foreach ($tabCats as $tc):
            ?>
            <button class="tab-btn <?php echo $first ? 'active' : ''; ?>" data-tab="tab-<?php echo strtolower($tc); ?>">
                <?php echo $catIcons[$tc] ?? '📁'; ?> <?php echo $tc; ?>
            </button>
            <?php $first = false; endforeach; ?>
        </div>

        <?php
        $firstTab = true;
        foreach ($tabCats as $tc):
            $tabRes = $database->prepare('
                SELECT p.*, c.nom_categorie, dv.nom_boutique AS vendeur_boutique,
                       COUNT(co.id_article) AS nombre_commandes
                FROM produits p
                INNER JOIN categories c ON p.categorie_id = c.id
                LEFT JOIN demandes_vendeur dv ON p.id_vendeur = dv.id_uti AND dv.statut = "acceptee"
                LEFT JOIN commande co ON p.id = co.id_article
                WHERE c.nom_categorie = :categorie AND p.statut = "approuve"
                GROUP BY p.id
                ORDER BY nombre_commandes DESC, p.date_ajout DESC
                LIMIT 10
            ');
            $tabRes->execute([':categorie' => $tc]);
            $tabProds = $tabRes->fetchAll(PDO::FETCH_ASSOC);

            if (empty($tabProds)) {
                $tabRes = $database->prepare('
                    SELECT p.*, c.nom_categorie, dv.nom_boutique AS vendeur_boutique,
                           0 AS nombre_commandes
                    FROM produits p
                    INNER JOIN categories c ON p.categorie_id = c.id
                    LEFT JOIN demandes_vendeur dv ON p.id_vendeur = dv.id_uti AND dv.statut = "acceptee"
                    WHERE c.nom_categorie = :categorie AND p.statut = "approuve"
                    ORDER BY p.date_ajout DESC
                    LIMIT 10
                ');
                $tabRes->execute([':categorie' => $tc]);
                $tabProds = $tabRes->fetchAll(PDO::FETCH_ASSOC);
            }
        ?>
        <div class="tab-content <?php echo $firstTab ? 'active' : ''; ?>" id="tab-<?php echo strtolower($tc); ?>">
            <div class="product-grid grid-5">
                <?php foreach ($tabProds as $index => $produit):
                    // MÊME LOGIQUE D'IMAGE QUE LA SECTION "NOUVELLES ARRIVÉES"
                    $imagePath = 'back-end/apps/' . htmlspecialchars($produit['image']);
                    if (!file_exists($imagePath)) $imagePath = 'back-end/apps/Blue.jpg';
                    
                    $hasSale = $produit['prix_reduction'] > 0;
                    $isFree = $produit['prix'] == 0;
                    $disc = $hasSale ? round((($produit['prix'] - $produit['prix_reduction']) / $produit['prix']) * 100) : 0;
                    $sellerName = $produit['vendeur_boutique'] ?? '';

                    $rank = $index + 1;
                    $badgeText = '';
                    $badgeClass = '';
                    if ($produit['nombre_commandes'] > 0) {
                        if ($rank == 1) {$badgeText = '🔥 #1'; $badgeClass = 'badge-hot';}
                        elseif ($rank == 2) {$badgeText = '⚡ #2'; $badgeClass = 'badge-new';}
                        elseif ($rank == 3) {$badgeText = '💫 #3'; $badgeClass = 'badge-sale';}
                        else {$badgeText = '📈 Tendance'; $badgeClass = 'badge-popular';}
                    }
                ?>
                <div class="product-card">
                    <div class="product-card-img">
                        <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($produit['nom_article']); ?>" loading="lazy">
                        <?php if ($badgeText): ?><span class="product-badge <?php echo $badgeClass; ?>"><?php echo $badgeText; ?></span><?php endif; ?>
                        <?php if ($hasSale): ?><span class="discount-tag">-<?php echo $disc; ?>%</span><?php endif; ?>
                        <?php if ($isFree): ?><span class="product-badge badge-free">Gratuit</span><?php endif; ?>
                        <div class="product-overlay">
                            <a class="btn-preview" href="product_detail.php?nom_article=<?php echo urlencode($produit['nom_article']); ?>">
                                <i class="fa-solid fa-eye"></i> Aperçu
                            </a>
                        </div>
                    </div>
                    <div class="product-card-body">
                        <span class="product-category"><?php echo htmlspecialchars($produit['nom_categorie']); ?></span>
                        <a href="product_detail.php?nom_article=<?php echo urlencode($produit['nom_article']); ?>" class="product-name">
                            <?php echo htmlspecialchars($produit['nom_article']); ?>
                        </a>
                        <?php if (!empty($sellerName)): ?>
                            <div class="seller-name"><i class="fa-solid fa-store"></i> <?php echo htmlspecialchars($sellerName); ?></div>
                        <?php endif; ?>
                        <div class="product-footer">
                            <div class="product-price">
                                <?php if ($isFree): ?>
                                    <span class="price-free">Gratuit</span>
                                <?php elseif ($hasSale): ?>
                                    <span class="price-old"><?php echo number_format($produit['prix'], 0); ?> <?php echo $cfa; ?></span>
                                    <span class="price-new"><?php echo number_format($produit['prix_reduction'], 0); ?> <?php echo $cfa; ?></span>
                                <?php else: ?>
                                    <span class="price-new"><?php echo number_format($produit['prix'], 0); ?> <?php echo $cfa; ?></span>
                                <?php endif; ?>
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <?php if ($produit['nombre_commandes'] > 0): ?>
                                <span class="sales-count"><i class="fa-solid fa-fire"></i> <?php echo $produit['nombre_commandes']; ?></span>
                                <?php endif; ?>
                                <a class="btn-card" href="product_detail.php?nom_article=<?php echo urlencode($produit['nom_article']); ?>">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="view-all-wrap">
                <a href="categorie?category_name=<?php echo urlencode($tc); ?>" class="btn-view-all">
                    Voir tous les templates <?php echo $tc; ?> <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
        <?php $firstTab = false; endforeach; ?>
    </div>
</section>

<!-- =========================================================
     PROMO BANNER
========================================================== -->
<?php require('banniere.php'); ?>

<!-- =========================================================
     SECTION : NOUVEAU (NEW ARRIVALS)
========================================================== -->
<!-- =========================================================
     SECTION : NOUVEAU (NEW ARRIVALS)
========================================================== -->
<section class="section section-bg">
    <div class="container">
        <div class="section-header">
            <div class="section-title-group">
                <div class="section-label">🆕 Dernières ajouts</div>
                <h2 class="section-title">Nouvelles Arrivées</h2>
                <p class="section-subtitle">Les templates les plus récents sur la plateforme</p>
            </div>
            <a href="shop.php?sort=new" class="section-link">Tout voir <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        <?php
        $newRes = $database->query('
            SELECT p.*, c.nom_categorie, dv.nom_boutique AS vendeur_boutique
            FROM produits p
            INNER JOIN categories c ON p.categorie_id = c.id
            LEFT JOIN demandes_vendeur dv ON p.id_vendeur = dv.id_uti AND dv.statut = "acceptee"
            WHERE p.statut = "approuve"
            ORDER BY p.date_ajout DESC LIMIT 10
        ');
        $newProds = $newRes->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="product-grid grid-5">
            <?php foreach ($newProds as $produit):
                $imagePath = 'back-end/apps/' . htmlspecialchars($produit['image']);
                if (!file_exists($imagePath)) $imagePath = 'back-end/apps/Blue.jpg';
                $hasSale = $produit['prix_reduction'] > 0;
                $isFree = $produit['prix'] == 0;
                $disc = $hasSale ? round((($produit['prix'] - $produit['prix_reduction']) / $produit['prix']) * 100) : 0;
                $sellerName = $produit['vendeur_boutique'] ?? '';
            ?>
            <div class="product-card">
                <div class="product-card-img">
                    <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($produit['nom_article']); ?>" loading="lazy">
                    <span class="product-badge badge-new">Nouveau</span>
                    <?php if ($hasSale): ?><span class="discount-tag">-<?php echo $disc; ?>%</span><?php endif; ?>
                    <div class="product-overlay">
                        <a class="btn-preview" href="product_detail.php?nom_article=<?php echo urlencode($produit['nom_article']); ?>">
                            <i class="fa-solid fa-eye"></i> Aperçu
                        </a>
                    </div>
                </div>
                <div class="product-card-body">
                    <span class="product-category"><?php echo htmlspecialchars($produit['nom_categorie']); ?></span>
                    <a href="product_detail.php?nom_article=<?php echo urlencode($produit['nom_article']); ?>" class="product-name">
                        <?php echo htmlspecialchars($produit['nom_article']); ?>
                    </a>
                    <?php if (!empty($sellerName)): ?>
                        <div class="seller-name"><i class="fa-solid fa-store"></i> <?php echo htmlspecialchars($sellerName); ?></div>
                    <?php endif; ?>
                    <div class="product-footer">
                        <div class="product-price">
                            <?php if ($isFree): ?>
                                <span class="price-free">Gratuit</span>
                            <?php elseif ($hasSale): ?>
                                <span class="price-old"><?php echo number_format($produit['prix'], 0); ?> <?php echo $cfa; ?></span>
                                <span class="price-new"><?php echo number_format($produit['prix_reduction'], 0); ?> <?php echo $cfa; ?></span>
                            <?php else: ?>
                                <span class="price-new"><?php echo number_format($produit['prix'], 0); ?> <?php echo $cfa; ?></span>
                            <?php endif; ?>
                        </div>
                        <a class="btn-card" href="product_detail.php?nom_article=<?php echo urlencode($produit['nom_article']); ?>">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- =========================================================
     SECTION : GRATUITS
========================================================== -->
<!-- =========================================================
     SECTION : GRATUITS (5 par ligne desktop, 2 mobile)
========================================================== -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-title-group">
                <div class="section-label">🎁 100% gratuit</div>
                <h2 class="section-title">Templates Gratuits</h2>
                <p class="section-subtitle">Téléchargez sans débourser un centime</p>
            </div>
            <a href="gratuit" class="section-link">Voir tous les gratuits <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        <?php
        $freeRes = $database->query('
            SELECT p.*, c.nom_categorie, dv.nom_boutique AS vendeur_boutique
            FROM produits p
            INNER JOIN categories c ON p.categorie_id = c.id
            LEFT JOIN demandes_vendeur dv ON p.id_vendeur = dv.id_uti AND dv.statut = "acceptee"
            WHERE p.prix = 0 AND p.statut = "approuve"
            ORDER BY p.date_ajout DESC LIMIT 10
        ');
        $freeProds = $freeRes->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <div class="free-grid grid-5">
            <?php foreach ($freeProds as $produit):
                $imagePath = 'back-end/apps/' . htmlspecialchars($produit['image']);
                if (!file_exists($imagePath)) $imagePath = 'back-end/apps/Blue.jpg';
                $sellerName = $produit['vendeur_boutique'] ?? '';
            ?>
            <div class="product-card">
                <div class="product-card-img">
                    <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($produit['nom_article']); ?>" loading="lazy">
                    <span class="product-badge badge-free">Gratuit</span>
                    <div class="product-overlay">
                        <a class="btn-preview" href="product_detail.php?nom_article=<?php echo urlencode($produit['nom_article']); ?>">
                            <i class="fa-solid fa-download"></i> Télécharger
                        </a>
                    </div>
                </div>
                <div class="product-card-body">
                    <span class="product-category"><?php echo htmlspecialchars($produit['nom_categorie']); ?></span>
                    <a href="product_detail.php?nom_article=<?php echo urlencode($produit['nom_article']); ?>" class="product-name">
                        <?php echo htmlspecialchars($produit['nom_article']); ?>
                    </a>
                    <?php if (!empty($sellerName)): ?>
                        <div class="seller-name"><i class="fa-solid fa-store"></i> <?php echo htmlspecialchars($sellerName); ?></div>
                    <?php endif; ?>
                    <div class="product-footer">
                        <span class="price-free">Gratuit</span>
                        <a class="btn-card" href="product_detail.php?nom_article=<?php echo urlencode($produit['nom_article']); ?>">
                            <i class="fa-solid fa-download"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="view-all-wrap">
            <a href="gratuit" class="btn-view-all">
                Voir tous les templates gratuits <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>





<!-- =========================================================

     SECTION : TÉMOIGNAGES CLIENTS

========================================================== -->

<section class="section section-bg">

    <div class="container">

        <div class="section-header" style="justify-content:center; text-align:center; display:block; margin-bottom: 40px;">

            <div class="section-label" style="justify-content:center;">💬 Ils nous ont fait confiance</div>

            <h2 class="section-title">Ce que disent nos clients</h2>

            <p class="section-subtitle">Rejoignez les milliers de développeurs et designers satisfaits</p>

        </div>



        <div class="swiper testimonial-swiper">

            <div class="swiper-wrapper">

                <!-- Témoignage 1 -->

                <div class="swiper-slide">

                    <div class="testimonial-card">

                        <div class="testimonial-rating">

                            <i class="fa-solid fa-star"></i>

                            <i class="fa-solid fa-star"></i>

                            <i class="fa-solid fa-star"></i>

                            <i class="fa-solid fa-star"></i>

                            <i class="fa-solid fa-star"></i>

                        </div>

                        <p class="testimonial-text">

                            "J'ai acheté plusieurs templates WordPress sur NDIGITMARKET, la qualité est exceptionnelle. Le support est réactif et les fichiers sont toujours propres et bien documentés. Je recommande vivement !"

                        </p>

                        <div class="testimonial-author">

                            <img src="assets/images/testimonials/avatar1.jpg" alt="Jean Dupont" class="testimonial-avatar" onerror="this.src='https://ui-avatars.com/api/?name=Jean+Dupont&background=087d67&color=fff&size=60'">

                            <div class="testimonial-author-info">

                                <h4>Jean Dupont</h4>

                                <p>Développeur Web, France</p>

                            </div>

                        </div>

                    </div>

                </div>

                

                <!-- Témoignage 2 -->

                <div class="swiper-slide">

                    <div class="testimonial-card">

                        <div class="testimonial-rating">

                            <i class="fa-solid fa-star"></i>

                            <i class="fa-solid fa-star"></i>

                            <i class="fa-solid fa-star"></i>

                            <i class="fa-solid fa-star"></i>

                            <i class="fa-solid fa-star"></i>

                        </div>

                        <p class="testimonial-text">

                            "Les templates React sont modernes et faciles à intégrer. J'ai gagné des heures de développement sur mes projets. Le rapport qualité-prix est imbattable !"

                        </p>

                        <div class="testimonial-author">

                            <img src="assets/images/testimonials/avatar2.jpg" alt="Marie Koné" class="testimonial-avatar" onerror="this.src='https://ui-avatars.com/api/?name=Marie+Koné&background=087d67&color=fff&size=60'">

                            <div class="testimonial-author-info">

                                <h4>Marie Koné</h4>

                                <p>Designer UI/UX, Côte d'Ivoire</p>

                            </div>

                        </div>

                    </div>

                </div>

                

                <!-- Témoignage 3 -->

                <div class="swiper-slide">

                    <div class="testimonial-card">

                        <div class="testimonial-rating">

                            <i class="fa-solid fa-star"></i>

                            <i class="fa-solid fa-star"></i>

                            <i class="fa-solid fa-star"></i>

                            <i class="fa-solid fa-star"></i>

                            <i class="fa-solid fa-star"></i>

                        </div>

                        <p class="testimonial-text">

                            "La livraison est instantanée et les fichiers sont toujours de qualité. J'utilise NDIGITMARKET pour tous mes projets clients depuis 2 ans, jamais déçu !"

                        </p>

                        <div class="testimonial-author">

                            <img src="assets/images/testimonials/avatar3.jpg" alt="Amadou Diallo" class="testimonial-avatar" onerror="this.src='https://ui-avatars.com/api/?name=Amadou+Diallo&background=087d67&color=fff&size=60'">

                            <div class="testimonial-author-info">

                                <h4>Amadou Diallo</h4>

                                <p>Freelance, Sénégal</p>

                            </div>

                        </div>

                    </div>

                </div>

                

                <!-- Témoignage 4 -->

                <div class="swiper-slide">

                    <div class="testimonial-card">

                        <div class="testimonial-rating">

                            <i class="fa-solid fa-star"></i>

                            <i class="fa-solid fa-star"></i>

                            <i class="fa-solid fa-star"></i>

                            <i class="fa-solid fa-star"></i>

                            <i class="fa-regular fa-star"></i>

                        </div>

                        <p class="testimonial-text">

                            "Très bonne plateforme, les templates PSD sont bien organisés et faciles à modifier. Le support m'a aidé rapidement quand j'avais une question."

                        </p>

                        <div class="testimonial-author">

                            <img src="assets/images/testimonials/avatar4.jpg" alt="Sophie Martin" class="testimonial-avatar" onerror="this.src='https://ui-avatars.com/api/?name=Sophie+Martin&background=087d67&color=fff&size=60'">

                            <div class="testimonial-author-info">

                                <h4>Sophie Martin</h4>

                                <p>Graphiste, Belgique</p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            

            <!-- Navigation et pagination -->

            <div class="swiper-pagination testimonial-pagination"></div>

        </div>

        

   

    </div>

</section>





<?php require('popup.php'); ?>
<?php require('footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
// Featured Swiper
new Swiper('#featuredSwiper', {
    loop: true,
    navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
    pagination: { el: '.swiper-pagination', clickable: true },
    autoplay: { delay: 7000, disableOnInteraction: false },
});

// Tabs
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById(btn.dataset.tab).classList.add('active');
    });
});

// Testimonials Swiper
new Swiper('.testimonial-swiper', {
    loop: true,
    slidesPerView: 1,
    spaceBetween: 20,
    pagination: { el: '.testimonial-pagination', clickable: true },
    autoplay: { delay: 5000, disableOnInteraction: false },
    breakpoints: {
        640: { slidesPerView: 1, spaceBetween: 20 },
        768: { slidesPerView: 2, spaceBetween: 30 },
        1024: { slidesPerView: 3, spaceBetween: 30 },
    },
});
</script>
</body>
</html>