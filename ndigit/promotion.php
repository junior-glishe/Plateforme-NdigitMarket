<?php
// Dans votre index.php - À placer avant le </body>
?>

<!-- POPUP PROMOTIONNELLE NdigitMarket -->
<style>
/* Reset et base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Overlay (fond sombre) */
.promo-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(5, 15, 20, 0.85);
    backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 999999;
    visibility: hidden;
    opacity: 0;
    transition: opacity 0.4s ease, visibility 0.4s;
    padding: 0.8rem;
    font-family: 'Inter', sans-serif;
}

.promo-overlay.active {
    visibility: visible;
    opacity: 1;
}

/* Carte popup */
.promo-card {
    background: #ffffff;
    max-width: 600px;
    width: 100%;
    border-radius: 28px;
    box-shadow: 0 40px 80px -20px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(8, 125, 103, 0.3);
    overflow: hidden;
    position: relative;
    transform: scale(0.9) translateY(20px);
    transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.promo-overlay.active .promo-card {
    transform: scale(1) translateY(0);
}

/* Bande décorative */
.promo-strip {
    height: 6px;
    background: linear-gradient(90deg, #087d67 0%, #f97316 70%, #fbbf24 100%);
}

/* Bouton fermer */
.close-promo {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 36px;
    height: 36px;
    background: #f0fdf9;
    border: none;
    border-radius: 50%;
    color: #087d67;
    font-size: 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    z-index: 10;
    border: 1px solid rgba(8, 125, 103, 0.2);
}

.close-promo:hover {
    background: #f97316;
    color: white;
    transform: rotate(90deg);
}

/* Contenu */
.promo-content {
    padding: 1.5rem 1.5rem 2rem;
    text-align: center;
}

/* Badge FLASH */
.flash-badge {
    background: linear-gradient(135deg, #0f1923, #1e2a3a);
    color: #fbbf24;
    padding: 0.4rem 1.2rem;
    border-radius: 40px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-weight: 700;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    margin-bottom: 1.2rem;
    border: 1px solid #f97316;
}

.flash-badge i {
    color: #f97316;
    font-size: 1rem;
}

/* Titre */
.promo-title {
    font-size: 2.5rem;
    font-weight: 800;
    line-height: 1.1;
    color: #0f1923;
    margin-bottom: 0.2rem;
}

@media (min-width: 480px) {
    .promo-title {
        font-size: 3rem;
    }
}

@media (min-width: 600px) {
    .promo-title {
        font-size: 3.5rem;
    }
}

.promo-title .highlight {
    color: #087d67;
    background: linear-gradient(145deg, #087d67, #0a9b7e);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    display: inline-block;
}

.promo-subtitle {
    font-size: 1rem;
    color: #4b5563;
    margin-bottom: 1.2rem;
    font-weight: 400;
}

@media (min-width: 480px) {
    .promo-subtitle {
        font-size: 1.2rem;
    }
}

/* Grille catégories */
.categories {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    justify-content: center;
    margin: 1.2rem 0;
}

.category {
    background: #f0fdf9;
    padding: 0.4rem 1rem;
    border-radius: 40px;
    display: flex;
    align-items: center;
    gap: 5px;
    font-weight: 600;
    color: #0f1923;
    border: 1px solid rgba(8, 125, 103, 0.2);
    font-size: 0.8rem;
}

@media (min-width: 480px) {
    .category {
        padding: 0.5rem 1.2rem;
        font-size: 0.9rem;
    }
}

.category i {
    color: #f97316;
    font-size: 1rem;
}

/* Badges secondaires */
.badges {
    display: flex;
    gap: 0.6rem;
    justify-content: center;
    margin: 1rem 0;
    flex-wrap: wrap;
}

.badge {
    background: #f0fdf9;
    padding: 0.3rem 1rem;
    border-radius: 40px;
    font-weight: 500;
    color: #0f1923;
    border: 1px solid rgba(8, 125, 103, 0.2);
    font-size: 0.75rem;
}

@media (min-width: 480px) {
    .badge {
        padding: 0.4rem 1.2rem;
        font-size: 0.85rem;
    }
}

.badge i {
    color: #087d67;
    margin-right: 4px;
    font-size: 0.8rem;
}

/* Instruction pour le code */
.code-instruction {
    font-size: 0.8rem;
    color: #087d67;
    font-weight: 600;
    margin-bottom: 0.3rem;
    text-align: left;
    padding-left: 0.5rem;
}

@media (min-width: 480px) {
    .code-instruction {
        font-size: 0.9rem;
        padding-left: 1rem;
    }
}

.code-instruction i {
    color: #f97316;
    margin-right: 5px;
}

/* Code promo - cliquable */
.code-container {
    background: linear-gradient(135deg, #0f1923, #1a2a38);
    border-radius: 60px;
    padding: 0.8rem 1.2rem;
    margin: 0.3rem 0 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: 2px solid #f97316;
    box-shadow: 0 10px 20px -8px rgba(249, 115, 22, 0.3);
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

@media (min-width: 480px) {
    .code-container {
        padding: 1rem 1.5rem;
        margin: 0.5rem 0 1.2rem;
    }
}

.code-container:hover {
    transform: scale(1.02);
    border-color: #fbbf24;
    box-shadow: 0 15px 25px -8px rgba(249, 115, 22, 0.5);
}

.code-container:active {
    transform: scale(0.98);
}

.code {
    font-family: 'Courier New', monospace;
    font-size: 1.4rem;
    font-weight: 800;
    letter-spacing: 3px;
    color: #fbbf24;
    text-shadow: 0 0 8px #f97316;
    user-select: all;
}

@media (min-width: 480px) {
    .code {
        font-size: 1.8rem;
        letter-spacing: 4px;
    }
}

@media (min-width: 600px) {
    .code {
        font-size: 2.2rem;
        letter-spacing: 6px;
    }
}

.code-container i {
    color: #f97316;
    font-size: 1.4rem;
    transition: all 0.3s;
}

@media (min-width: 480px) {
    .code-container i {
        font-size: 1.8rem;
    }
}

.code-container:hover i {
    color: #fbbf24;
    transform: rotate(20deg);
}

/* Tooltip de copie */
.copy-tooltip {
    position: absolute;
    top: -25px;
    left: 50%;
    transform: translateX(-50%);
    background: #087d67;
    color: white;
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 0.7rem;
    font-weight: 500;
    white-space: nowrap;
    opacity: 0;
    transition: opacity 0.3s;
    pointer-events: none;
}

.copy-tooltip.show {
    opacity: 1;
}

.copy-tooltip i {
    font-size: 0.6rem;
    margin-right: 4px;
    color: white !important;
}

/* Date limite */
.deadline {
    background: #fee2e2;
    color: #b91c1c;
    padding: 0.5rem 1.2rem;
    border-radius: 40px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-weight: 600;
    margin: 0.8rem auto 1.2rem;
    border: 1px solid #f97316;
    font-size: 0.8rem;
}

@media (min-width: 480px) {
    .deadline {
        padding: 0.6rem 1.5rem;
        font-size: 0.9rem;
        margin: 1rem auto 1.5rem;
    }
}

.deadline i {
    color: #f97316;
    font-size: 0.9rem;
}

/* Bouton CTA */
.cta-button {
    display: inline-block;
    background: linear-gradient(165deg, #087d67, #0a6b58);
    color: white;
    font-weight: 800;
    font-size: 1.4rem;
    padding: 0.9rem 1.5rem;
    border-radius: 60px;
    text-decoration: none;
    box-shadow: 0 15px 25px -10px #087d67;
    transition: all 0.3s;
    border: 1px solid #fbbf24;
    width: 100%;
    text-align: center;
}

@media (min-width: 480px) {
    .cta-button {
        font-size: 1.8rem;
        padding: 1.1rem 2rem;
    }
}

@media (min-width: 600px) {
    .cta-button {
        font-size: 2rem;
        padding: 1.3rem 2.5rem;
    }
}

.cta-button:hover {
    background: linear-gradient(165deg, #0a9b7e, #087d67);
    box-shadow: 0 20px 30px -8px #087d67;
    transform: scale(1.02);
    color: white;
}

.cta-button i {
    margin-left: 8px;
    transition: transform 0.3s;
    font-size: 1.2rem;
}

@media (min-width: 480px) {
    .cta-button i {
        margin-left: 10px;
        font-size: 1.5rem;
    }
}

.cta-button:hover i {
    transform: translateX(6px);
}

/* Note d'utilisation */
.usage-note {
    margin-top: 0.8rem;
    color: #4b5563;
    font-size: 0.75rem;
    background: #f9fafb;
    padding: 0.4rem;
    border-radius: 30px;
    border: 1px dashed #087d67;
}

@media (min-width: 480px) {
    .usage-note {
        margin-top: 1rem;
        font-size: 0.8rem;
        padding: 0.5rem;
    }
}

.usage-note i {
    color: #f97316;
    margin-right: 5px;
}

/* Import Google Fonts */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
</style>

<!-- Popup overlay -->
<div class="promo-overlay" id="promoPopup">
    <div class="promo-card">
        <div class="promo-strip"></div>
        <button class="close-promo" id="closePromoBtn"><i class="fas fa-times"></i></button>
        <div class="promo-content">
            <div class="flash-badge">
                <i class="fas fa-bolt"></i> FLASH -40% <i class="fas fa-bolt"></i>
            </div>
            
            <h2 class="promo-title">Profitez de <span class="highlight">-40%</span></h2>
            <p class="promo-subtitle">sur tous nos templates premium</p>
            
            <!--<div class="categories">-->
            <!--    <div class="category"><i class="fab fa-wordpress"></i> WordPress</div>-->
            <!--    <div class="category"><i class="fab fa-php"></i> PHP</div>-->
            <!--    <div class="category"><i class="fab fa-html5"></i> HTML</div>-->
            <!--    <div class="category"><i class="far fa-file-image"></i> PSD</div>-->
            <!--</div>-->
            
            <div class="badges">
                <span class="badge"><i class="fas fa-crown"></i> Designs pro</span>
                <span class="badge"><i class="fas fa-rocket"></i> Prêts à l'emploi</span>
            </div>
            
            <!-- Instruction claire pour le code -->
            <div class="code-instruction">
                <i class="fas fa-hand-pointer"></i> Cliquez pour copier le code :
            </div>
            
            <!-- Code promo cliquable -->
            <div class="code-container" id="codeContainer">
                <span class="code">NDIGIT20</span>
                <i class="fas fa-copy" id="copyIcon"></i>
                <span class="copy-tooltip" id="copyTooltip">
                    <i class="fas fa-check"></i> Copié !
                </span>
            </div>
            
            <!-- Explication d'utilisation -->
            <div class="usage-note">
                <i class="fas fa-info-circle"></i> Copiez ce code et utilisez-le lors de votre achat pour bénéficier de -40%
            </div>
            
            <div class="deadline">
                <i class="far fa-calendar-alt"></i> Jusqu'au 28/02/2026
            </div>
            
            <a href="https://www.ndigitmarket.com/shop" class="cta-button" target="_blank">
                J'EN PROFITE <i class="fas fa-arrow-right"></i>
            </a>
            
            <div class="note">
                <i class="fas fa-gift"></i> Offre exclusive - Valable sur toute la boutique
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<script>
(function() {
    // Configuration
    const PREMIER_DELAI = 5000;        // 5 secondes
    const DELAI_REAPPARITION = 120000;  // 2 minutes
    const DATE_LIMITE = '2026-02-28T23:59:59';
    
    const overlay = document.getElementById('promoPopup');
    const closeBtn = document.getElementById('closePromoBtn');
    const codeContainer = document.getElementById('codeContainer');
    const copyTooltip = document.getElementById('copyTooltip');
    const copyIcon = document.getElementById('copyIcon');
    let reappearTimer = null;
    
    // Vérifier si la date est dépassée
    function estOffreExpiree() {
        return new Date() > new Date(DATE_LIMITE);
    }
    
    // Afficher la popup
    function showPopup() {
        if (reappearTimer) {
            clearTimeout(reappearTimer);
            reappearTimer = null;
        }
        if (estOffreExpiree()) return;
        overlay.classList.add('active');
    }
    
    // Cacher et programmer la réapparition
    function hideAndSchedule() {
        overlay.classList.remove('active');
        
        if (estOffreExpiree()) return;
        
        if (reappearTimer) clearTimeout(reappearTimer);
        
        reappearTimer = setTimeout(() => {
            if (!estOffreExpiree()) {
                overlay.classList.add('active');
            }
            reappearTimer = null;
        }, DELAI_REAPPARITION);
    }
    
    // Fonction pour copier le code promo
    function copyPromoCode() {
        const code = 'NDIGIT20';
        
        // Utiliser l'API Clipboard moderne
        navigator.clipboard.writeText(code).then(() => {
            // Afficher le tooltip
            copyTooltip.classList.add('show');
            
            // Changer l'icône temporairement
            copyIcon.classList.remove('fa-copy');
            copyIcon.classList.add('fa-check');
            
            // Cacher le tooltip après 2 secondes
            setTimeout(() => {
                copyTooltip.classList.remove('show');
                
                // Remettre l'icône normale
                setTimeout(() => {
                    copyIcon.classList.remove('fa-check');
                    copyIcon.classList.add('fa-copy');
                }, 200);
            }, 1500);
        }).catch(err => {
            // Fallback pour les anciens navigateurs
            const textArea = document.createElement('textarea');
            textArea.value = code;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            
            // Afficher le tooltip
            copyTooltip.classList.add('show');
            copyIcon.classList.remove('fa-copy');
            copyIcon.classList.add('fa-check');
            
            setTimeout(() => {
                copyTooltip.classList.remove('show');
                setTimeout(() => {
                    copyIcon.classList.remove('fa-check');
                    copyIcon.classList.add('fa-copy');
                }, 200);
            }, 1500);
        });
    }
    
    // Événements
    if (closeBtn) {
        closeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            hideAndSchedule();
        });
    }
    
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
            hideAndSchedule();
        }
    });
    
    // Copie du code promo
    if (codeContainer) {
        codeContainer.addEventListener('click', (e) => {
            e.stopPropagation(); // Empêche la fermeture de la popup
            copyPromoCode();
        });
    }
    
    // Premier déclenchement
    window.addEventListener('load', () => {
        if (estOffreExpiree()) return;
        
        setTimeout(() => {
            if (!estOffreExpiree()) {
                overlay.classList.add('active');
            }
        }, PREMIER_DELAI);
    });
    
    // Vérification périodique
    setInterval(() => {
        if (estOffreExpiree() && overlay.classList.contains('active')) {
            overlay.classList.remove('active');
            if (reappearTimer) {
                clearTimeout(reappearTimer);
                reappearTimer = null;
            }
        }
    }, 60000);
})();
</script>