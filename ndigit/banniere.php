<?php
$is_client_connected = isset($_SESSION['user_id']);
$abonnement_actif = false;
$abonnement_expire = false;
$abonnement_atteint_limite = false;

if ($is_client_connected) {
    $stmt = $database->prepare("SELECT * FROM utilisateur WHERE email = ?");
    $stmt->execute([$_SESSION['email']]);
    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user_info) {
        $user_id = $user_info['id_uti'];

        if ($user_info['type'] == 'pro') {
            $stmt_abonnement = $database->prepare("SELECT * FROM abonnement WHERE id_uti2 = ?");
            $stmt_abonnement->execute([$user_id]);
            $abonnement = $stmt_abonnement->fetch(PDO::FETCH_ASSOC);

            if ($abonnement) {
                $date_fin = new DateTime($abonnement['date_fin']);
                $date_aujourdhui = new DateTime();

                if ($date_aujourdhui < $date_fin) {
                    if ($abonnement['nombre_telecharge'] >= $abonnement['nombre_total']) {
                        $abonnement_atteint_limite = true;
                    } else {
                        $abonnement_actif = true;
                    }
                } else {
                    $abonnement_expire = true;
                }
            }
        } else {
            $abonnement_expire = true;
        }
    }
}

// Déterminer le contenu selon l'état
$icon = '🚀';
$title = '';
$subtitle = '';
$desc = '';
$btn_text = '';
$btn_href = 'abonnement';
$variant = 'upgrade'; // upgrade | expired | limit | active

if (!$is_client_connected) {
    $icon = '🚀';
    $title = 'Passez au Pro';
    $subtitle = 'Accès illimité à toutes nos ressources';
    $desc = 'Rejoignez notre programme Pro et téléchargez tous nos templates, plugins et scripts sans restriction.';
    $btn_text = 'Voir les abonnements';
    $variant = 'upgrade';

} elseif ($user_info['type'] != 'pro') {
    $icon = '⭐';
    $title = 'Débloquez le contenu Premium';
    $subtitle = 'Accédez à toutes nos ressources sans limites';
    $desc = 'Prenez un abonnement Pro pour accéder à notre bibliothèque complète de templates et ressources web premium.';
    $btn_text = 'Souscrire à l\'abonnement';
    $variant = 'upgrade';

} elseif ($user_info['type'] == 'pro' && $abonnement_expire) {
    $icon = '⏰';
    $title = 'Abonnement expiré';
    $subtitle = 'Renouvelez pour retrouver votre accès';
    $desc = 'Votre abonnement Pro a expiré. Renouvelez maintenant pour continuer à télécharger tous nos templates et ressources premium.';
    $btn_text = 'Renouveler l\'abonnement';
    $variant = 'expired';

} elseif ($user_info['type'] == 'pro' && $abonnement_atteint_limite) {
    $icon = '📦';
    $title = 'Limite atteinte';
    $subtitle = 'Passez à un plan supérieur';
    $desc = 'Vous avez atteint votre limite de téléchargements. Passez à un plan supérieur pour continuer sans interruption.';
    $btn_text = 'Passer à un plan supérieur';
    $variant = 'limit';

} elseif ($user_info['type'] == 'pro' && $abonnement_actif) {
    $icon = '✅';
    $title = 'Abonnement Actif';
    $subtitle = 'Profitez de votre accès illimité';
    $desc = 'Vous avez accès à toute notre bibliothèque de ressources premium. Téléchargez autant que vous voulez !';
    $btn_text = '';
    $variant = 'active';
}
?>

<style>
.ndm-banner-wrap {
    padding: 0 24px;
    max-width: 1200px;
    margin: 0 auto 64px;
}

.ndm-banner {
    border-radius: 20px;
    overflow: hidden;
    position: relative;
    display: flex;
    align-items: center;
    min-height: 220px;
}

/* Variants */
.ndm-banner.upgrade {
    background: linear-gradient(135deg, #0f1923 0%, #0d3329 50%, #087d67 100%);
}
.ndm-banner.expired {
    background: linear-gradient(135deg, #1a0a0a 0%, #4a1010 50%, #ef4444 100%);
}
.ndm-banner.limit {
    background: linear-gradient(135deg, #1a1200 0%, #4a3000 50%, #f97316 100%);
}
.ndm-banner.active {
    background: linear-gradient(135deg, #071a10 0%, #0a4a2a 50%, #10b981 100%);
}

/* Background decoration */
.ndm-banner::before {
    content: '';
    position: absolute;
    right: -80px; top: -80px;
    width: 360px; height: 360px;
    background: rgba(255,255,255,0.04);
    border-radius: 50%;
}
.ndm-banner::after {
    content: '';
    position: absolute;
    left: 40%; bottom: -120px;
    width: 280px; height: 280px;
    background: rgba(255,255,255,0.03);
    border-radius: 50%;
}

.ndm-banner-icon-bg {
    position: absolute;
    right: 60px;
    font-size: 120px;
    opacity: 0.07;
    line-height: 1;
    user-select: none;
    pointer-events: none;
}

.ndm-banner-content {
    position: relative;
    z-index: 2;
    padding: 40px 48px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    gap: 32px;
    flex-wrap: wrap;
}

.ndm-banner-left {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    flex: 1;
    min-width: 260px;
}

.ndm-banner-emoji {
    font-size: 48px;
    line-height: 1;
    flex-shrink: 0;
    filter: drop-shadow(0 4px 12px rgba(0,0,0,0.3));
}

.ndm-banner-text {}

.ndm-banner-label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: rgba(255,255,255,0.55);
    margin-bottom: 6px;
}

.ndm-banner-title {
    font-size: clamp(20px, 3vw, 28px);
    font-weight: 800;
    color: #fff;
    line-height: 1.2;
    margin-bottom: 6px;
}

.ndm-banner-subtitle {
    font-size: 14px;
    color: rgba(255,255,255,0.65);
    line-height: 1.6;
    max-width: 520px;
}

.ndm-banner-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 12px;
    flex-shrink: 0;
}

.ndm-banner-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: #fff;
    color: #0f1923;
    padding: 15px 30px;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.3s;
    box-shadow: 0 4px 20px rgba(0,0,0,0.25);
    border: none;
    cursor: pointer;
}

.ndm-banner-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.35);
    color: #0f1923;
}

.ndm-banner-btn.upgrade-btn:hover { background: #f0fdf4; }
.ndm-banner-btn.expired-btn { background: #fff0f0; }
.ndm-banner-btn.expired-btn:hover { background: #ffe0e0; }
.ndm-banner-btn.limit-btn { background: #fff8f0; }
.ndm-banner-btn.limit-btn:hover { background: #ffeed6; }

.ndm-banner-note {
    font-size: 12px;
    color: rgba(255,255,255,0.4);
    text-align: right;
}

/* Active state — no button, show badges */
.ndm-banner-badges {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.ndm-banner-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.15);
    color: rgba(255,255,255,0.85);
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
}

@media (max-width: 700px) {
    .ndm-banner-content { padding: 28px 24px; }
    .ndm-banner-right { align-items: flex-start; }
    .ndm-banner-icon-bg { display: none; }
    .ndm-banner-emoji { font-size: 36px; }
}
</style>

<div class="ndm-banner-wrap">
    <div class="ndm-banner <?php echo $variant; ?>">

        <div class="ndm-banner-icon-bg"><?php echo $icon; ?></div>

        <div class="ndm-banner-content">
            <div class="ndm-banner-left">
                <div class="ndm-banner-emoji"><?php echo $icon; ?></div>
                <div class="ndm-banner-text">
                    <div class="ndm-banner-label">
                        <?php if ($variant === 'active'): ?>✦ Statut abonnement
                        <?php elseif ($variant === 'expired'): ?>⚠ Action requise
                        <?php elseif ($variant === 'limit'): ?>⚠ Limite atteinte
                        <?php else: ?>✦ Offre Premium
                        <?php endif; ?>
                    </div>
                    <div class="ndm-banner-title"><?php echo $title; ?></div>
                    <div class="ndm-banner-subtitle"><?php echo $desc; ?></div>
                </div>
            </div>

            <div class="ndm-banner-right">
                <?php if ($variant === 'active'): ?>
                    <div class="ndm-banner-badges">
                        <div class="ndm-banner-badge">✅ Actif</div>
                        <div class="ndm-banner-badge">⚡ Téléchargements illimités</div>
                        <?php if (isset($abonnement['date_fin'])): ?>
                        <div class="ndm-banner-badge">📅 Expire le <?php echo (new DateTime($abonnement['date_fin']))->format('d/m/Y'); ?></div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <a href="<?php echo $btn_href; ?>" class="ndm-banner-btn <?php echo $variant; ?>-btn">
                        <?php if ($variant === 'upgrade'): ?>🚀<?php elseif ($variant === 'expired'): ?>🔄<?php elseif ($variant === 'limit'): ?>⬆<?php endif; ?>
                        <?php echo $btn_text; ?>
                    </a>
                    <span class="ndm-banner-note">✔ Installation facile &nbsp;·&nbsp; ✔ Accès immédiat</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>