<?php

// ── Configuration session AVANT session_start() ──────────────────
session_set_cookie_params([
    'lifetime' => 86400,
    'path'     => '/',
    'domain'   => '.ndigitmarket.com',
    'secure'   => true,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();
// ─────────────────────────────────────────────────────────────────

require('include/connect.php');
$cfa = "CFA";

if (!isset($_SESSION['panier'])) $_SESSION['panier'] = array();
$total_articles = array_sum($_SESSION['panier']);

// Récupérer UNIQUEMENT les catégories qui ont au moins un produit approuvé
// Récupérer UNIQUEMENT les catégories qui ont au moins un produit approuvé
// Triées par nombre de produits décroissant
$categories_nav = $database->query("
    SELECT c.*, COUNT(p.id) AS nb_produits
    FROM categories c
    INNER JOIN produits p ON c.id = p.categorie_id AND p.statut = 'approuve'
    GROUP BY c.id
    ORDER BY nb_produits DESC, c.nom_categorie ASC
")->fetchAll(PDO::FETCH_ASSOC);

// Vérifier si l'utilisateur connecté est un vendeur accepté
$is_vendeur = false;
if (isset($_SESSION['user_id'], $_SESSION['email'])) {
    $stmtVendeur = $database->prepare("SELECT id FROM demandes_vendeur WHERE id_uti = (SELECT id_uti FROM utilisateur WHERE email = ?) AND statut = 'acceptee'");
    $stmtVendeur->execute([$_SESSION['email']]);
    if ($stmtVendeur->fetch()) {
        $is_vendeur = true;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/images/favi.png" type="image/x-icon">
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#087d67">
    <title>NDIGITMARKET - Templates & Ressources Web Premium</title>
    <meta name="description" content="Découvrez des milliers de templates WordPress, HTML, PHP, React et PSD à prix abordables.">
    <meta name="keywords" content="NDIGITMARKET, templates WordPress, HTML, PHP, React, PSD">
    <meta property="og:title" content="NDIGITMARKET - Templates & Ressources Web Premium">
    <meta property="og:url" content="https://www.ndigitmarket.com">
    <meta name="google-site-verification" content="lbUYNaEqrCLdufs5HEios_Z_L9b5Wud72zVdI3ctwhA"/>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link id="rtl-link"   rel="stylesheet" href="assets/css/vendors/bootstrap.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/bulk-style.css">
    <link rel="stylesheet" href="assets/css/vendors/animate.css">
    <link id="color-link" rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
:root {
    --hp: #087d67;
    --hd: #0f1923;
    --ha: #f97316;
    --hb: #e5e7eb;
    --ht: #374151;
    --hl: #f9fafb;
}

/* ── TOP BAR ─────────────────────────────── */
.ndm-topbar {
    background: var(--hd);
    padding: 7px 16px;
    text-align: center;
    font-size: 13px;
    color: rgba(255,255,255,.72);
    gap: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
}
.ndm-topbar-code {
    background: var(--ha);
    color: #fff;
    font-weight: 800;
    font-size: 12px;
    padding: 2px 8px;
    border-radius: 5px;
}

/* ── MAIN HEADER ─────────────────────────── */
.ndm-header {
    background: #fff;
    border-bottom: 1px solid var(--hb);
    position: sticky;
    top: 0;
    z-index: 500;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
}
.ndm-header-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 16px;
    height: 60px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.ndm-burger {
    display: none;
    flex-shrink: 0;
    background: none;
    border: none;
    color: var(--ht);
    font-size: 22px;
    cursor: pointer;
    padding: 7px;
    border-radius: 8px;
    line-height: 1;
}

.ndm-logo { flex-shrink: 0; text-decoration: none; }
.ndm-logo img { height: 34px; width: auto; display: block; }

.ndm-search { flex: 1; min-width: 0; }
.ndm-search form {
    display: flex;
    align-items: center;
    background: var(--hl);
    border: 1.5px solid var(--hb);
    border-radius: 10px;
    overflow: hidden;
    transition: border-color .2s;
}
.ndm-search form:focus-within { border-color: var(--hp); background: #fff; }
.ndm-search input {
    flex: 1; min-width: 0;
    border: none; background: transparent;
    padding: 10px 13px;
    font-size: 14px; outline: none; color: var(--ht);
}
.ndm-search input::placeholder { color: #9ca3af; }
.ndm-search button {
    background: var(--hp);
    border: none;
    padding: 10px 15px;
    color: #fff;
    cursor: pointer;
    flex-shrink: 0;
    transition: background .2s;
    display: flex; align-items: center;
}
.ndm-search button:hover { background: #065a4a; }

.ndm-header-actions {
    display: flex;
    align-items: center;
    gap: 4px;
    flex-shrink: 0;
}

.ndm-flash-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg,#f97316,#ef4444);
    color: #fff;
    font-weight: 700;
    font-size: 13px;
    padding: 8px 13px;
    border-radius: 9px;
    border: none;
    cursor: pointer;
    white-space: nowrap;
    transition: all .2s;
}
.ndm-flash-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(239,68,68,.35); }

.ndm-icon-btn {
    position: relative;
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 10px;
    border-radius: 9px;
    text-decoration: none;
    color: var(--ht);
    font-size: 13px;
    font-weight: 600;
    border: 1.5px solid transparent;
    cursor: pointer;
    background: transparent;
    transition: all .2s;
    white-space: nowrap;
    flex-shrink: 0;
}
.ndm-icon-btn:hover { background: var(--hl); border-color: var(--hb); color: var(--hp); }
.ndm-icon-btn > i { font-size: 19px; }
.ndm-icon-btn .lbl { display: flex; flex-direction: column; line-height: 1.2; }
.ndm-icon-btn .lbl small  { font-size: 10px; color: #9ca3af; font-weight: 400; }
.ndm-icon-btn .lbl strong { font-size: 12px; font-weight: 700; color: var(--hd); }

.ndm-cart-badge {
    position: absolute;
    top: 2px; right: 2px;
    width: 17px; height: 17px;
    background: var(--ha);
    color: #fff;
    font-size: 10px; font-weight: 800;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
}

.ndm-user-wrap { position: relative; display: inline-block; }
.ndm-user-wrap .ndm-icon-btn { cursor: pointer; }
.ndm-user-dropdown {
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    background: #fff;
    border: 1.5px solid var(--hb);
    border-radius: 12px;
    box-shadow: 0 8px 30px rgba(0,0,0,.12);
    min-width: 200px;
    padding: 6px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-6px);
    transition: all 0.2s ease;
    z-index: 1000;
    pointer-events: none;
}
.ndm-user-dropdown.active {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
    pointer-events: auto;
}
.ndm-user-dropdown a {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 14px; border-radius: 8px;
    color: var(--ht); text-decoration: none;
    font-size: 14px; font-weight: 500;
    transition: all .15s;
}
.ndm-user-dropdown a:hover { background: #f0fdf4; color: var(--hp); }
.ndm-user-dropdown a i { font-size: 16px; color: var(--hp); width: 20px; }

/* ── NAVBAR (desktop) ─────────────────────── */
.ndm-navbar {
    background: var(--hd);
    border-bottom: 1px solid rgba(255,255,255,.06);
}
.ndm-navbar-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 16px;
    display: flex;
    align-items: center;
}

.ndm-cat-wrap { position: relative; flex-shrink: 0; }
.ndm-cat-btn {
    display: flex; align-items: center; gap: 8px;
    padding: 12px 18px;
    background: var(--hp);
    color: #fff;
    font-size: 13px; font-weight: 700;
    border: none; cursor: pointer; white-space: nowrap;
    transition: background .2s;
}
.ndm-cat-btn:hover { background: #065a4a; }

.ndm-cat-panel {
    position: absolute; top: 100%; left: 0;
    width: 260px;
    background: #fff;
    border: 1.5px solid var(--hb);
    border-top: none;
    border-radius: 0 0 14px 14px;
    box-shadow: 0 12px 40px rgba(0,0,0,.14);
    z-index: 700;
    display: none;
    max-height: 450px;
    overflow-y: auto;
    padding: 6px 0;
}
.ndm-cat-panel.open { display: block; }
.ndm-cat-panel::-webkit-scrollbar { width: 4px; }
.ndm-cat-panel::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }

.ndm-cat-item { position: relative; }
.ndm-cat-item-link {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 16px;
    text-decoration: none; color: var(--ht);
    font-size: 13px; font-weight: 600;
    transition: all .15s;
    border-left: 3px solid transparent;
}
.ndm-cat-item-link:hover { background: #f0fdf4; color: var(--hp); border-left-color: var(--hp); }
.ndm-cat-item-icon {
    width: 26px; height: 26px;
    border-radius: 7px;
    background: var(--hl);
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; flex-shrink: 0; overflow: hidden;
}
.ndm-cat-item-icon img { width: 100%; height: 100%; object-fit: cover; border-radius: 7px; }
.ndm-cat-item-name { flex: 1; display: flex; align-items: center; justify-content: space-between; }
.ndm-cat-item-badge {
    font-size: 10px; background: var(--hp); color: #fff;
    padding: 2px 7px; border-radius: 10px; font-weight: 600;
}
.ndm-cat-arrow { font-size: 10px; color: #9ca3af; }
.ndm-cat-item:hover .ndm-cat-arrow { color: var(--hp); }

.ndm-cat-sub {
    position: absolute; left: 100%; top: 0;
    width: 200px;
    background: #fff;
    border: 1.5px solid var(--hb);
    border-radius: 12px;
    box-shadow: 0 8px 30px rgba(0,0,0,.12);
    padding: 8px;
    display: none; z-index: 701;
}
.ndm-cat-item:hover .ndm-cat-sub { display: block; }
.ndm-cat-sub-title {
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 1px;
    color: var(--hp);
    padding: 4px 8px 8px;
    border-bottom: 1px solid var(--hb);
    margin-bottom: 4px;
}
.ndm-cat-sub-link {
    display: flex; align-items: center; gap: 7px;
    padding: 8px 10px; border-radius: 8px;
    color: var(--ht); text-decoration: none;
    font-size: 13px; transition: all .15s;
}
.ndm-cat-sub-link i { color: var(--hp); font-size: 11px; }
.ndm-cat-sub-link:hover { background: #f0fdf4; color: var(--hp); }

/* Nav links */
.ndm-nav-links { display: flex; align-items: center; list-style: none; margin: 0; padding: 0; }
.ndm-nav-links > li { position: relative; }
.ndm-nav-links > li > a {
    display: flex; align-items: center; gap: 4px;
    padding: 13px 13px;
    color: rgba(255,255,255,.8);
    text-decoration: none;
    font-size: 13px; font-weight: 600;
    transition: all .2s;
    border-bottom: 3px solid transparent;
    white-space: nowrap;
}
.ndm-nav-links > li > a:hover,
.ndm-nav-links > li:hover > a { color: #fff; border-bottom-color: var(--hp); }
.ndm-nav-links > li > a .chv { font-size: 9px; opacity: .6; transition: transform .2s; }
.ndm-nav-links > li:hover > a .chv { transform: rotate(180deg); }

.ndm-dropdown {
    position: absolute; top: 100%; left: 0;
    background: #fff;
    border: 1.5px solid var(--hb);
    border-radius: 12px;
    box-shadow: 0 8px 30px rgba(0,0,0,.14);
    min-width: 200px; padding: 8px;
    opacity: 0; pointer-events: none;
    transform: translateY(-6px);
    transition: all .2s; z-index: 600;
}
.ndm-nav-links > li:hover .ndm-dropdown { opacity: 1; pointer-events: all; transform: translateY(0); }
.ndm-dropdown a {
    display: flex; align-items: center; gap: 8px;
    padding: 9px 12px; border-radius: 8px;
    color: var(--ht); text-decoration: none;
    font-size: 13px; font-weight: 500; transition: all .15s;
}
.ndm-dropdown a::before { content: '›'; color: var(--hp); font-size: 16px; line-height: 1; }
.ndm-dropdown a:hover { background: #f0fdf4; color: var(--hp); }

.ndm-nav-free    { color: #4ade80 !important; font-weight: 700 !important; }
.ndm-nav-seller  { color: #fbbf24 !important; font-weight: 600 !important; }
.ndm-nav-seller:hover { color: #fbbf24 !important; border-bottom-color: #fbbf24 !important; }
.ndm-nav-contact { color: rgba(255,255,255,.5) !important; }

/* ── OFFCANVAS (mobile) ───────────────────── */
.ndm-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.55); z-index: 8999; display: none; }
.ndm-overlay.open { display: block; }

.ndm-offcanvas {
    position: fixed; top: 0; left: -100%;
    width: 285px; height: 100%;
    background: var(--hd);
    z-index: 9000;
    transition: left .3s ease;
    overflow-y: auto;
    padding: 20px;
}
.ndm-offcanvas.open { left: 0; }

.ndm-oc-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 18px; padding-bottom: 14px;
    border-bottom: 1px solid rgba(255,255,255,.1);
}
.ndm-oc-header img { height: 28px; }
.ndm-oc-close {
    background: rgba(255,255,255,.1); border: none;
    color: #fff; width: 30px; height: 30px;
    border-radius: 8px; display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 15px;
}

.ndm-oc-search {
    display: flex; gap: 8px; margin-bottom: 16px;
}
.ndm-oc-search input {
    flex: 1; padding: 10px 12px; border-radius: 8px;
    border: 1px solid rgba(255,255,255,.15);
    background: rgba(255,255,255,.08);
    color: #fff; font-size: 14px; outline: none;
}
.ndm-oc-search input::placeholder { color: #64748b; }
.ndm-oc-search button {
    background: var(--hp); border: none;
    border-radius: 8px; padding: 0 12px;
    color: #fff; cursor: pointer;
}

.ndm-oc-nav { list-style: none; margin: 0; padding: 0; }
.ndm-oc-nav li a {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 0;
    color: rgba(255,255,255,.82);
    text-decoration: none;
    font-size: 14px; font-weight: 600;
    border-bottom: 1px solid rgba(255,255,255,.07);
    transition: color .2s;
}
.ndm-oc-nav li a:hover { color: #4ade80; }
.ndm-oc-free  { color: #4ade80 !important; }
.ndm-oc-seller { color: #fbbf24 !important; }
.ndm-oc-sep   { margin-top: 12px; padding-top: 12px; border-top: 1px solid rgba(255,255,255,.1); }

@media (max-width: 1024px) { .ndm-navbar { display: none !important; } .ndm-burger { display: flex !important; } .ndm-icon-btn .lbl { display: none !important; } }
@media (max-width: 768px) { .ndm-header-actions .ndm-icon-btn[href="cart"] { display: none !important; } }
@media (max-width: 600px) { .ndm-search { display: none !important; } .ndm-flash-btn { display: none !important; } .ndm-topbar { display: none !important; } }
</style>

</head>
<body>

<div class="ndm-topbar d-none d-lg-flex">
    🎉 Offre spéciale: -25% avec le code 
    <span class="ndm-topbar-code">NDIGIT20</span> 
    — À utiliser lors du paiement !  
    <strong>Valable jusqu'au 30/04/2026</strong>
</div>

<header class="ndm-header">
    <div class="ndm-header-inner">
        <button class="ndm-burger" id="ndmBurger" aria-label="Menu"><i class="fa-solid fa-bars"></i></button>
        <a href="index" class="ndm-logo"><img src="assets/images/loo.png" alt="NDIGITMARKET"></a>

        <div class="ndm-search">
            <form action="recherche" method="GET">
                <input type="text" name="search_query" placeholder="Rechercher un template, plugin..."
                    value="<?php echo isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : ''; ?>">
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>

        <div class="ndm-header-actions">
            <button class="ndm-flash-btn d-none d-sm-flex" data-bs-toggle="modal" data-bs-target="#deal-box">
                <i class="fa-solid fa-bolt"></i><span class="d-none d-lg-inline">Vente flash</span>
            </button>
            <a href="cart" class="ndm-icon-btn cart-desktop">
                <i class="fa-solid fa-bag-shopping"></i>
                <div class="lbl d-none d-lg-flex"><small>Mon</small><strong>Panier</strong></div>
                <span class="ndm-cart-badge"><?php echo $total_articles; ?></span>
            </a>
            <div class="ndm-user-wrap" id="userMenu">
                <div class="ndm-icon-btn" id="userMenuBtn">
                    <i class="fa-solid fa-circle-user"></i>
                    <div class="lbl d-none d-lg-flex"><small>Bonjour,</small><strong><?php echo isset($_SESSION['user_id']) ? 'Mon compte' : 'Connexion'; ?></strong></div>
                </div>
                <div class="ndm-user-dropdown" id="userDropdown">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="compte"><i class="fa-solid fa-user"></i> Mon Compte</a>
                        <a href="liste_produits.php"><i class="fa-solid fa-box"></i> Mes produits</a>
                        <?php if ($is_vendeur): ?>
                            <a href="liste_produits.php" style="color:#fbbf24;font-weight:600;"><i class="fa-solid fa-store"></i> 🏪 Vous êtes vendeur</a>
                        <?php else: ?>
                            <a href="devenir_vendeur"><i class="fa-solid fa-store"></i> Devenir vendeur</a>
                        <?php endif; ?>
                        <a href="deconne"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a>
                    <?php else: ?>
                        <a href="login"><i class="fa-solid fa-right-to-bracket"></i> Se connecter</a>
                        <a href="inscrire"><i class="fa-solid fa-user-plus"></i> S'inscrire</a>
                        <a href="devenir_vendeur"><i class="fa-solid fa-store"></i> Devenir vendeur</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- ===== NAVBAR (desktop) GÉNÉRÉE AUTOMATIQUEMENT ===== -->
<nav class="ndm-navbar d-none d-xl-block">
    <div class="ndm-navbar-inner">

        <div class="ndm-cat-wrap">
            <button class="ndm-cat-btn" id="ndmCatToggle">
                <i class="fa-solid fa-grip"></i> Toutes les catégories
                <i class="fa-solid fa-chevron-down" id="ndmCatChv" style="font-size:10px;margin-left:4px;transition:transform .2s;"></i>
            </button>
            <div class="ndm-cat-panel" id="ndmCatPanel">
                <?php
                // Icônes par défaut
                $catIcons = [
                    'WordPress'=>'🟦','HTML'=>'🧡','PHP'=>'💜','React'=>'⚛️','PSD'=>'🎨',
                    'Plugin'=>'🔌','JavaScript'=>'💛','Laravel'=>'🔴','Vue'=>'💚','Angular'=>'🅰️',
                    'Design Graphique'=>'🎨','Vidéo & Motion'=>'🎬','3D & Modélisation'=>'🧊',
                    'eBooks & Formations'=>'📚','Intelligence Artificielle'=>'🤖','Mobile Apps'=>'📱',
                    'Musique & Audio'=>'🎵','Jeux Vidéo'=>'🎮','Scripts & Code'=>'💻',
                    'Plugins & Extensions'=>'🧩','Automatisation & No-Code'=>'⚡',
                    'Shopify'=>'🛍️','PowerPoint'=>'📊','Word'=>'📝','AI,EPS'=>'✒️','PACKS'=>'📦'
                ];
                
                foreach ($categories_nav as $cat):
                    $icon = $catIcons[$cat['nom_categorie']] ?? '📁';
                    $subs = !empty($cat['sous_categories']) ? array_map('trim', explode(',', $cat['sous_categories'])) : [];
                ?>
                <div class="ndm-cat-item">
                    <a href="categorie?category_name=<?php echo urlencode($cat['nom_categorie']); ?>" class="ndm-cat-item-link">
                        <span class="ndm-cat-item-icon">
                            <?php if (!empty($cat['image_cat'])): ?>
                                <img src="back-end/apps/uploads/<?php echo htmlspecialchars($cat['image_cat']); ?>" alt="" onerror="this.style.display='none'">
                            <?php else: ?><?php echo $icon; ?><?php endif; ?>
                        </span>
                        <span class="ndm-cat-item-name">
                            <?php echo htmlspecialchars($cat['nom_categorie']); ?>
                            <span class="ndm-cat-item-badge"><?php echo $cat['nb_produits']; ?></span>
                        </span>
                        <?php if (!empty($subs)): ?><i class="fa-solid fa-chevron-right ndm-cat-arrow"></i><?php endif; ?>
                    </a>
                    <?php if (!empty($subs)): ?>
                    <div class="ndm-cat-sub">
                        <div class="ndm-cat-sub-title"><?php echo htmlspecialchars($cat['nom_categorie']); ?></div>
                        <?php foreach ($subs as $sub): ?>
                        <a href="categorie.php?category_name=<?php echo urlencode($cat['nom_categorie']); ?>&sous_category_name=<?php echo urlencode(trim($sub)); ?>" class="ndm-cat-sub-link">
                            <i class="fa-solid fa-angle-right"></i><?php echo htmlspecialchars(trim($sub)); ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <ul class="ndm-nav-links">
            <li><a href="shop">Tout</a></li>
            
            <?php
            // Afficher les 6 premières catégories (ou moins) dans la navbar principale
            $navCats = array_slice($categories_nav, 0, 6);
            foreach ($navCats as $catNav):
                $subs = !empty($catNav['sous_categories']) ? array_map('trim', explode(',', $catNav['sous_categories'])) : [];
            ?>
            <li>
                <a href="categorie?category_name=<?php echo urlencode($catNav['nom_categorie']); ?>">
                    <?php echo htmlspecialchars($catNav['nom_categorie']); ?>
                    <?php if (!empty($subs)): ?><i class="fa-solid fa-chevron-down chv"></i><?php endif; ?>
                </a>
                <?php if (!empty($subs)): ?>
                <div class="ndm-dropdown">
                    <?php foreach (array_slice($subs, 0, 8) as $sub): ?>
                    <a href="categorie.php?category_name=<?php echo urlencode($catNav['nom_categorie']); ?>&sous_category_name=<?php echo urlencode(trim($sub)); ?>">
                        <?php echo htmlspecialchars(trim($sub)); ?>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </li>
            <?php endforeach; ?>
            
            <li><a href="gratuit" class="ndm-nav-free">🎁 Gratuit</a></li>
            
            <?php if ($is_vendeur): ?>
                <li><a href="liste_produits.php" class="ndm-nav-seller">🏪 Vous êtes vendeur</a></li>
            <?php else: ?>
                <li><a href="devenir_vendeur" style="color:#fbbf24 !important;">🏪 Devenir vendeur</a></li>
            <?php endif; ?>
            
            <li><a href="vendeurs">👥 Nos vendeurs</a></li>
            <li><a href="contact" class="ndm-nav-contact">Contact</a></li>
        </ul>

    </div>
</nav>

<!-- ===== OFFCANVAS (GÉNÉRÉ AUTOMATIQUEMENT) ===== -->
<div class="ndm-overlay" id="ndmOverlay"></div>
<div class="ndm-offcanvas" id="ndmOffcanvas">
    <div class="ndm-oc-header">
        <img src="assets/images/loo.png" alt="NDIGITMARKET">
        <button class="ndm-oc-close" id="ndmOcClose"><i class="fa-solid fa-xmark"></i></button>
    </div>

    <form action="recherche" method="GET" class="ndm-oc-search">
        <input type="text" name="search_query" placeholder="Rechercher..."
            value="<?php echo isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : ''; ?>">
        <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>

    <ul class="ndm-oc-nav">
        <li><a href="shop">🗂 Tous les templates</a></li>
        <?php foreach ($categories_nav as $cat): ?>
            <li><a href="categorie?category_name=<?php echo urlencode($cat['nom_categorie']); ?>">
                <?php 
                $icon = $catIcons[$cat['nom_categorie']] ?? '📁';
                echo $icon . ' ' . htmlspecialchars($cat['nom_categorie']); 
                ?> (<?php echo $cat['nb_produits']; ?>)
            </a></li>
        <?php endforeach; ?>
        <li><a href="gratuit" class="ndm-oc-free">🎁 Gratuit</a></li>
        
        <?php if ($is_vendeur): ?>
            <li><a href="liste_produits.php" class="ndm-oc-seller">🏪 Vous êtes vendeur</a></li>
        <?php else: ?>
            <li><a href="devenir_vendeur" style="color:#fbbf24;">🏪 Devenir vendeur</a></li>
        <?php endif; ?>
        
        <li><a href="vendeurs">👥 Nos vendeurs</a></li>
        <li><a href="contact">📞 Contact</a></li>
        
        <li class="ndm-oc-sep">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="compte">👤 Mon Compte</a>
            <?php else: ?>
                <a href="login">🔐 Se connecter</a>
            <?php endif; ?>
        </li>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <li><a href="inscrire">✍️ S'inscrire</a></li>
        <?php endif; ?>
    </ul>
</div>

<script>
const burger=document.getElementById('ndmBurger'),offcanvas=document.getElementById('ndmOffcanvas'),overlay=document.getElementById('ndmOverlay'),ocClose=document.getElementById('ndmOcClose');
function ndmOpen(){offcanvas.classList.add('open');overlay.classList.add('open');document.body.style.overflow='hidden';}
function ndmClose(){offcanvas.classList.remove('open');overlay.classList.remove('open');document.body.style.overflow='';}
if(burger)burger.addEventListener('click',ndmOpen);
if(ocClose)ocClose.addEventListener('click',ndmClose);
if(overlay)overlay.addEventListener('click',ndmClose);

const userMenuBtn=document.getElementById('userMenuBtn'),userDropdown=document.getElementById('userDropdown');
if(userMenuBtn&&userDropdown){userMenuBtn.addEventListener('click',function(e){e.stopPropagation();userDropdown.classList.toggle('active');});document.addEventListener('click',function(e){if(!userMenuBtn.contains(e.target)&&!userDropdown.contains(e.target))userDropdown.classList.remove('active');});userDropdown.addEventListener('click',function(e){e.stopPropagation();});}

const catToggle=document.getElementById('ndmCatToggle'),catPanel=document.getElementById('ndmCatPanel'),catChv=document.getElementById('ndmCatChv');
if(catToggle&&catPanel){catToggle.addEventListener('click',function(e){e.stopPropagation();const open=catPanel.classList.toggle('open');if(catChv)catChv.style.transform=open?'rotate(180deg)':'rotate(0deg)';});document.addEventListener('click',function(e){if(!catToggle.contains(e.target)&&!catPanel.contains(e.target)){catPanel.classList.remove('open');if(catChv)catChv.style.transform='rotate(0deg)';}});}
</script>

<!-- Meta Pixel -->
<script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init','24107163828923491');fbq('track','PageView');</script>
<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=24107163828923491&ev=PageView&noscript=1"/></noscript>

<script>if('serviceWorker' in navigator){navigator.serviceWorker.register('sw.js');}let deferredPrompt;window.addEventListener('beforeinstallprompt',(e)=>{e.preventDefault();deferredPrompt=e;const b=document.createElement('div');b.innerHTML=`<div style="position:fixed;bottom:20px;right:20px;background:#087d67;color:white;padding:12px 18px;border-radius:10px;font-size:14px;cursor:pointer;z-index:9999;box-shadow:0 2px 8px rgba(0,0,0,.3)">📲 Installer NDIGIT MARKET</div>`;b.onclick=()=>{deferredPrompt.prompt();deferredPrompt.userChoice.then(()=>b.remove());};document.body.appendChild(b);});</script>