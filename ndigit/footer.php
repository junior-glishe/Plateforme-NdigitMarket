<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1759468544873192" crossorigin="anonymous"></script>



<style>

/* ── WHATSAPP FLOAT ── */

#ndm-wa-container {

    position: fixed;

    bottom: 90px; left: 16px;

    z-index: 1000;

}

.ndm-wa-btn {

    width: 50px; height: 50px;

    background: linear-gradient(135deg,#25d366,#1db954);

    border-radius: 50%;

    display: flex; align-items: center; justify-content: center;

    box-shadow: 0 4px 16px rgba(37,211,102,.45);

    cursor: pointer;

    transition: transform .3s, box-shadow .3s;

    text-decoration: none;

}

.ndm-wa-btn:hover { transform: scale(1.1); box-shadow: 0 8px 24px rgba(37,211,102,.55); }

.ndm-wa-btn img { width: 27px; height: 27px; }



/* ── BACK TO TOP ── */

.ndm-back-top {

    position: fixed;

    bottom: 78px; right: 16px;

    width: 40px; height: 40px;

    background: #087d67;

    color: #fff;

    border-radius: 11px;

    display: flex; align-items: center; justify-content: center;

    font-size: 15px;

    text-decoration: none;

    z-index: 997;

    opacity: 0;

    transform: translateY(10px);

    transition: opacity .3s, transform .2s;

    box-shadow: 0 4px 16px rgba(8,125,103,.4);

}

.ndm-back-top.show { opacity: 1; transform: translateY(0); }

.ndm-back-top:hover { background: #065a4a; }



/* ── MOBILE BOTTOM NAV ── */

.ndm-mob-nav {

    position: fixed;

    bottom: 0; left: 0; right: 0;

    background: #fff;

    border-top: 1px solid #e5e7eb;

    z-index: 998;

    box-shadow: 0 -3px 16px rgba(0,0,0,.07);

    display: none;          /* affiché via media query */

}

.ndm-mob-nav ul {

    display: flex;

    align-items: center;

    justify-content: space-around;

    list-style: none;

    margin: 0;

    padding: 7px 0 10px;

}

.ndm-mob-nav ul li a,

.ndm-mob-nav ul li button {

    display: flex;

    flex-direction: column;

    align-items: center;

    gap: 3px;

    text-decoration: none;

    color: #6b7280;

    font-size: 10px;

    font-weight: 600;

    background: none;

    border: none;

    cursor: pointer;

    padding: 0;

    position: relative;

    transition: color .2s;

}

.ndm-mob-nav ul li a i,

.ndm-mob-nav ul li button i { font-size: 20px; }

.ndm-mob-nav ul li a:hover,

.ndm-mob-nav ul li button:hover,

.ndm-mob-nav ul li.active a { color: #087d67; }



.ndm-mob-badge {

    position: absolute;

    top: -3px; right: -7px;

    width: 16px; height: 16px;

    background: #ef4444;

    color: #fff;

    font-size: 9px; font-weight: 800;

    border-radius: 50%;

    display: flex; align-items: center; justify-content: center;

}



@media (max-width: 768px) {

    .ndm-mob-nav { display: block; }

    /* push page content above mobile nav */

    body { padding-bottom: 62px; }

}



/* ── SEARCH OVERLAY (mobile) ── */

.ndm-search-overlay {

    display: none;

    position: fixed; inset: 0;

    background: rgba(0,0,0,.75);

    z-index: 9999;

    align-items: center;

    justify-content: center;

}

.ndm-search-overlay.open { display: flex; }

.ndm-search-box {

    background: #fff;

    border-radius: 16px;

    padding: 28px 24px;

    width: 88%; max-width: 460px;

    position: relative;

    display: flex; flex-direction: column; gap: 14px;

}

.ndm-search-box h4 { font-weight: 700; color: #0f1923; margin: 0; font-size: 16px; }

.ndm-search-box input {

    width: 100%; padding: 12px 16px;

    border: 2px solid #e5e7eb;

    border-radius: 10px; font-size: 15px; outline: none;

    transition: border-color .2s;

}

.ndm-search-box input:focus { border-color: #087d67; }

.ndm-search-box button[type="submit"] {

    width: 100%; padding: 13px;

    background: #087d67; color: #fff;

    border: none; border-radius: 10px;

    font-size: 15px; font-weight: 700; cursor: pointer;

    transition: background .2s;

}

.ndm-search-box button[type="submit"]:hover { background: #065a4a; }

.ndm-search-close {

    position: absolute; top: 12px; right: 14px;

    background: none; border: none;

    font-size: 24px; color: #9ca3af; cursor: pointer;

}



/* ── FOOTER ── */

.ndm-footer { background: #0a0f1a; color: #cbd5e1; }



/* Community banner */

.ndm-footer-community {

    background: linear-gradient(135deg,#087d67 0%,#065a4a 60%,#044035 100%);

    padding: 56px 24px;

    text-align: center;

    position: relative;

    overflow: hidden;

}

.ndm-footer-community::before,

.ndm-footer-community::after {

    content: '';

    position: absolute;

    border-radius: 50%;

    background: rgba(255,255,255,.04);

}

.ndm-footer-community::before { width: 380px; height: 380px; top: -140px; right: -90px; }

.ndm-footer-community::after  { width: 260px; height: 260px; bottom: -90px; left: -50px; }

.ndm-footer-community h2 {

    font-size: clamp(20px,4vw,32px);

    font-weight: 800; color: #fff;

    margin-bottom: 10px;

    position: relative; z-index: 1;

}

.ndm-footer-community p {

    color: rgba(255,255,255,.76);

    font-size: 15px; max-width: 580px;

    margin: 0 auto 24px; line-height: 1.7;

    position: relative; z-index: 1;

}

.ndm-wa-join {

    display: inline-flex; align-items: center; gap: 10px;

    background: #fff; color: #087d67;

    padding: 14px 30px; border-radius: 50px;

    font-size: 15px; font-weight: 700;

    text-decoration: none;

    transition: all .3s;

    position: relative; z-index: 1;

    box-shadow: 0 4px 20px rgba(0,0,0,.2);

}

.ndm-wa-join i { font-size: 20px; color: #25d366; }

.ndm-wa-join:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(0,0,0,.3); }



/* Body grid */

.ndm-footer-body {

    max-width: 1200px; margin: 0 auto;

    padding: 56px 24px 36px;

    display: grid;

    grid-template-columns: 2fr 1fr 1fr 1.4fr;

    gap: 44px;

}

@media (max-width: 900px) { .ndm-footer-body { grid-template-columns: 1fr 1fr; gap: 32px; } }

@media (max-width: 540px) { .ndm-footer-body { grid-template-columns: 1fr; gap: 24px; } }



/* Brand */

.ndm-footer-logo {

    font-size: 20px; font-weight: 800;

    color: #fff; letter-spacing: -.5px;

    margin-bottom: 12px;

    display: flex; align-items: center; gap: 8px;

}

.ndm-footer-logo span {

    background: #087d67; color: #fff;

    padding: 2px 8px; border-radius: 6px; font-size: 12px;

}

.ndm-footer-brand p { font-size: 14px; line-height: 1.8; color: #94a3b8; margin-bottom: 18px; }

.ndm-footer-socials { display: flex; gap: 9px; }

.ndm-social-btn {

    width: 36px; height: 36px;

    border-radius: 9px;

    background: rgba(255,255,255,.06);

    border: 1px solid rgba(255,255,255,.08);

    display: flex; align-items: center; justify-content: center;

    color: #94a3b8; text-decoration: none; font-size: 14px;

    transition: all .2s;

}

.ndm-social-btn:hover { background: #087d67; color: #fff; border-color: #087d67; transform: translateY(-2px); }



/* Cols */

.ndm-footer-col h5 {

    font-size: 12px; font-weight: 700;

    text-transform: uppercase; letter-spacing: 1.2px;

    color: #fff;

    margin-bottom: 18px; padding-bottom: 10px;

    border-bottom: 1px solid rgba(255,255,255,.08);

}

.ndm-footer-col ul { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px; }

.ndm-footer-col ul li a {

    color: #94a3b8; text-decoration: none; font-size: 14px;

    display: flex; align-items: center; gap: 8px;

    transition: color .2s, gap .2s;

}

.ndm-footer-col ul li a::before { content: '›'; color: #087d67; font-size: 16px; line-height: 1; }

.ndm-footer-col ul li a:hover { color: #fff; gap: 12px; }



.ndm-contact-item {

    display: flex; align-items: flex-start; gap: 9px;

    font-size: 14px; color: #94a3b8; margin-bottom: 10px;

}

.ndm-contact-item i { color: #087d67; font-size: 14px; margin-top: 2px; flex-shrink: 0; }

.ndm-contact-item a { color: #94a3b8; text-decoration: none; }

.ndm-contact-item a:hover { color: #fff; }



/* Newsletter */

.ndm-nl-form { display: flex; flex-direction: column; gap: 9px; margin-top: 4px; }

.ndm-nl-form input[type="email"] {

    padding: 11px 14px;

    border-radius: 9px;

    border: 1px solid rgba(255,255,255,.1);

    background: rgba(255,255,255,.05);

    color: #fff; font-size: 14px; outline: none;

    transition: border-color .2s;

}

.ndm-nl-form input::placeholder { color: #64748b; }

.ndm-nl-form input:focus { border-color: #087d67; }

.ndm-nl-form button {

    padding: 11px;

    border-radius: 9px; border: none;

    background: #087d67; color: #fff;

    font-size: 14px; font-weight: 700; cursor: pointer;

    display: flex; align-items: center; justify-content: center; gap: 7px;

    transition: background .2s;

}

.ndm-nl-form button:hover { background: #065a4a; }



/* Divider + sub footer */

.ndm-footer-divider {

    height: 1px;

    background: rgba(255,255,255,.07);

    margin: 0 24px;

    max-width: 1200px;

    margin-left: auto; margin-right: auto;

}

.ndm-footer-bottom {

    max-width: 1200px; margin: 0 auto;

    padding: 18px 24px 24px;

    display: flex; align-items: center; justify-content: space-between;

    flex-wrap: wrap; gap: 10px;

}

@media (max-width: 560px) { .ndm-footer-bottom { flex-direction: column; text-align: center; } }

.ndm-footer-bottom p { font-size: 13px; color: #475569; }

.ndm-footer-bottom p strong { color: #087d67; }

.ndm-footer-bottom-links { display: flex; gap: 18px; }

.ndm-footer-bottom-links a { font-size: 13px; color: #475569; text-decoration: none; transition: color .2s; }

.ndm-footer-bottom-links a:hover { color: #fff; }

</style>



<!-- ============================================================
     CHATBOX NDIGITMARKET
============================================================ -->
<style>
/* ── CHAT BUBBLE ── */
#ndm-chat-bubble {
    position: fixed;
    bottom: 28px;
    right: 28px;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #25d366, #1db954);
    color: #fff;
    border-radius: 50%;
    box-shadow: 0 8px 30px rgba(37,211,102,0.4);
    font-size: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 9999;
    border: none;
    transition: all 0.4s cubic-bezier(.175,.885,.32,1.4);
    animation: ndm-pulse 2.5s ease-in-out infinite;
}
@keyframes ndm-pulse {
    0%, 100% { box-shadow: 0 8px 30px rgba(37,211,102,0.4), 0 0 0 0 rgba(37,211,102,0.3); }
    50% { box-shadow: 0 8px 30px rgba(37,211,102,0.4), 0 0 0 14px rgba(37,211,102,0); }
}
#ndm-chat-bubble:hover {
    transform: scale(1.1) translateY(-3px);
    box-shadow: 0 16px 45px rgba(37,211,102,0.5);
}
.ndm-chat-dot {
    position: absolute;
    bottom: 6px;
    right: 6px;
    width: 12px;
    height: 12px;
    background: #22C55E;
    border: 2.5px solid #fff;
    border-radius: 50%;
}

/* ── CHAT BOX ── */
#ndm-chat-box {
    position: fixed;
    bottom: 105px;
    right: 28px;
    width: 370px;
    max-width: 92vw;
    height: 520px;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 25px 70px rgba(0,0,0,0.25);
    overflow: hidden;
    z-index: 9998;
    transform: scale(0) translateY(20px);
    transform-origin: bottom right;
    transition: transform 0.4s cubic-bezier(.175,.885,.32,1.4);
    display: flex;
    flex-direction: column;
}
#ndm-chat-box.open {
    transform: scale(1) translateY(0);
}

.ndm-chat-header {
    background: linear-gradient(135deg, #087d67, #065a4a);
    color: #fff;
    padding: 16px 18px;
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
}
.ndm-chat-avatar {
    width: 44px; height: 44px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 1rem;
    flex-shrink: 0;
}
.ndm-chat-header-info h6 { font-size: 0.9rem; margin: 0 0 2px; font-weight: 700; }
.ndm-chat-header-info small { font-size: 0.7rem; opacity: 0.8; display: flex; align-items: center; gap: 5px; }
.ndm-chat-header-info small::before {
    content: ''; width: 7px; height: 7px;
    border-radius: 50%; background: #22C55E; display: inline-block;
}
.ndm-chat-close {
    margin-left: auto;
    background: rgba(255,255,255,0.15);
    border: none; color: #fff; font-size: 1rem;
    cursor: pointer; padding: 6px 10px; border-radius: 8px;
    transition: 0.2s;
}
.ndm-chat-close:hover { background: rgba(255,255,255,0.3); }

.ndm-chat-body {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    background: #f9fafb;
    display: flex; flex-direction: column; gap: 10px;
}
.ndm-chat-body::-webkit-scrollbar { width: 3px; }
.ndm-chat-body::-webkit-scrollbar-thumb { background: #087d67; border-radius: 2px; }

.ndm-bubble {
    max-width: 85%;
    padding: 11px 15px;
    border-radius: 16px;
    font-size: 0.85rem;
    line-height: 1.6;
    font-family: 'Inter', sans-serif;
}
.ndm-bubble.bot {
    background: #e8f5f2;
    color: #374151;
    align-self: flex-start;
    border-bottom-left-radius: 6px;
}
.ndm-bubble.usr {
    background: #087d67;
    color: #fff;
    align-self: flex-end;
    border-bottom-right-radius: 6px;
}

.ndm-wa-btn-chat {
    display: inline-flex;
    align-items: center; gap: 8px;
    margin-top: 10px;
    padding: 10px 18px;
    background: linear-gradient(135deg, #25d366, #1db954);
    color: #fff;
    border-radius: 30px;
    font-weight: 700; font-size: 0.82rem;
    text-decoration: none;
    transition: 0.25s;
    box-shadow: 0 4px 15px rgba(37,211,102,0.3);
}
.ndm-wa-btn-chat:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(37,211,102,0.4); color: #fff; }

.ndm-quick-btns {
    display: flex; flex-wrap: wrap; gap: 8px;
    padding: 12px 14px;
    background: #fff;
    border-top: 1px solid #e5e7eb;
    flex-shrink: 0;
}
.ndm-quick-btn {
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
    color: #374151;
    padding: 7px 13px;
    border-radius: 20px;
    font-size: 0.78rem; font-weight: 600;
    cursor: pointer; transition: 0.2s;
    font-family: 'Inter', sans-serif;
}
.ndm-quick-btn:hover { background: #087d67; color: #fff; border-color: #087d67; }

.ndm-chat-footer {
    padding: 10px 14px;
    border-top: 1px solid #e5e7eb;
    display: flex; gap: 8px;
    background: #fff;
    flex-shrink: 0;
}
.ndm-chat-footer input {
    flex: 1;
    border: 1px solid #e5e7eb;
    border-radius: 50px;
    padding: 10px 16px;
    font-size: 0.85rem;
    font-family: 'Inter', sans-serif;
    outline: none;
    transition: 0.2s;
}
.ndm-chat-footer input:focus { border-color: #087d67; }
.ndm-chat-footer input::placeholder { color: #9ca3af; }
.ndm-chat-send {
    width: 42px; height: 42px;
    background: #087d67;
    color: #fff;
    border: none; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: 0.2s;
    flex-shrink: 0;
}
.ndm-chat-send:hover { background: #065a4a; transform: scale(1.05); }

@media (max-width: 600px) {
    #ndm-chat-box { width: 90vw; height: 65vh; bottom: 145px; right: 14px; }
    #ndm-chat-bubble { width: 54px; height: 54px; bottom: 80px; left: 18px; font-size: 1.3rem; }
}
</style>

<!-- Bouton chat flottant -->
<button id="ndm-chat-bubble" onclick="ndmToggleChat()" title="Discuter avec nous" aria-label="Chat">
    <i class="fa-brands fa-whatsapp"></i>
    <div class="ndm-chat-dot"></div>
</button>

<!-- Chatbox -->
<div id="ndm-chat-box">
    <div class="ndm-chat-header">
        <div class="ndm-chat-avatar">
            <img src="assets/images/loo.png" alt="NDIGITMARKET" style="width:36px; height:36px; object-fit:contain;">
        </div>
        <div class="ndm-chat-header-info">
            <h6>NDIGITMARKET Support</h6>
            <small>En ligne • Réponse rapide</small>
        </div>
        <button class="ndm-chat-close" onclick="ndmToggleChat()">✕</button>
    </div>
    
    <div class="ndm-chat-body" id="ndm-chat-body">
        <div class="ndm-bubble bot">
            👋 Bonjour ! Bienvenue sur <strong>NDIGITMARKET</strong>.<br><br>
            Je suis là pour vous aider :<br>
            📦 Infos sur un produit<br>
            💰 Paiement & facturation<br>
            🏪 Devenir vendeur<br>
            ❓ Autre question<br><br>
            Comment puis-je vous aider ?
        </div>
    </div>
    
    <div class="ndm-quick-btns" id="ndm-quick-btns">
        <button class="ndm-quick-btn" onclick="ndmAsk('Comment acheter un produit ?')">🛒 Acheter</button>
        <button class="ndm-quick-btn" onclick="ndmAsk('Comment devenir vendeur ?')">🏪 Devenir vendeur</button>
        <button class="ndm-quick-btn" onclick="ndmAsk('Quels sont les moyens de paiement ?')">💳 Paiement</button>
        <button class="ndm-quick-btn" onclick="ndmAsk('Comment télécharger après achat ?')">📥 Télécharger</button>
    </div>
    
    <div class="ndm-chat-footer">
        <input type="text" id="ndm-chat-input" placeholder="Écrivez votre message..." autocomplete="off">
        <button class="ndm-chat-send" id="ndm-chat-send"><i class="fa-solid fa-paper-plane"></i></button>
    </div>
</div>

<script>
const WA_NUMBER = '+22998111986';
const WA_LINK = 'https://wa.me/' + WA_NUMBER + '?text=Bonjour%20NDIGITMARKET%20!%20';

function ndmAddBubble(text, type) {
    const body = document.getElementById('ndm-chat-body');
    const div = document.createElement('div');
    div.className = 'ndm-bubble ' + type;
    div.innerHTML = text;
    body.appendChild(div);
    body.scrollTop = body.scrollHeight;
}

function ndmBotReply(msg) {
    const m = msg.toLowerCase().trim();
    
    if (/bonjour|salut|hello|coucou|bjr|slt|hey/i.test(m)) {
        return 'Bonjour ! 😊 Comment puis-je vous aider aujourd\'hui ?';
    }
    if (/acheter|commander|achat|panier|payer/i.test(m)) {
        return '🛒 Pour acheter un produit :<br>1️⃣ Ajoutez-le au panier<br>2️⃣ Cliquez sur "Passer la commande"<br>3️⃣ Payez via Mobile Money ou Carte Bancaire<br>4️⃣ Téléchargez immédiatement !<br><br><a href="shop.php" style="color:#087d67;font-weight:700;">→ Voir la boutique</a>';
    }
    if (/vendeur|vendre|boutique|devenir/i.test(m)) {
        return '🏪 Pour devenir vendeur :<br>1️⃣ Créez un compte gratuit<br>2️⃣ Allez dans "Mon Compte" → "Devenir vendeur"<br>3️⃣ Remplissez le formulaire<br>4️⃣ Notre équipe valide sous 24-48h<br><br><a href="devenir_vendeur.php" style="color:#087d67;font-weight:700;">→ Page Devenir vendeur</a>';
    }
    if (/paiement|payer|momo|mobile money|carte|visa|mastercard/i.test(m)) {
        return '💳 Moyens de paiement acceptés :<br>📱 Mobile Money (MTN, Moov, Celtiis, Wave, Orange, etc.)<br>💳 Carte Bancaire (Visa, MasterCard)<br><br>Paiement 100% sécurisé via FedaPay !';
    }
    if (/télécharger|telecharger|download|zip|fichier/i.test(m)) {
        return '📥 Après l\'achat :<br>1️⃣ Allez dans "Mon Compte" → "Mes téléchargements"<br>2️⃣ Cliquez sur "Télécharger"<br>3️⃣ Décompressez le fichier ZIP<br><br>Le téléchargement est instantané après paiement !';
    }
    if (/contact|support|aide|help|problème|souci/i.test(m)) {
        return '📞 Contactez-nous directement sur WhatsApp en cliquant ci-dessous :<br><br><a href="' + WA_LINK + 'J\'ai besoin d\'aide" target="_blank" class="ndm-wa-btn-chat"><i class="fa-brands fa-whatsapp"></i> Écrire sur WhatsApp</a>';
    }
    if (/merci|super|top|parfait|nickel|ok/i.test(m)) {
        return 'Avec plaisir ! 😊 N\'hésitez pas si vous avez d\'autres questions.';
    }
    
    return 'Je suis là pour vous aider ! 😊<br><br>Posez-moi une question sur :<br>🛒 Comment acheter<br>🏪 Devenir vendeur<br>💳 Paiement<br>📥 Téléchargement<br><br>Ou écrivez-nous directement :<br><a href="' + WA_LINK + '" target="_blank" class="ndm-wa-btn-chat"><i class="fa-brands fa-whatsapp"></i> WhatsApp</a>';
}

function ndmSendMsg() {
    const inp = document.getElementById('ndm-chat-input');
    const msg = inp.value.trim();
    if (!msg) return;
    
    ndmAddBubble(msg, 'usr');
    inp.value = '';
    document.getElementById('ndm-quick-btns').style.display = 'none';
    
    const body = document.getElementById('ndm-chat-body');
    const typing = document.createElement('div');
    typing.className = 'ndm-bubble bot';
    typing.innerHTML = '<em style="opacity:0.6;">NDIGITMARKET écrit…</em>';
    body.appendChild(typing);
    body.scrollTop = body.scrollHeight;
    
    setTimeout(() => {
        typing.remove();
        ndmAddBubble(ndmBotReply(msg), 'bot');
    }, 800 + Math.random() * 500);
}

function ndmAsk(question) {
    document.getElementById('ndm-quick-btns').style.display = 'none';
    ndmAddBubble(question, 'usr');
    
    const body = document.getElementById('ndm-chat-body');
    const typing = document.createElement('div');
    typing.className = 'ndm-bubble bot';
    typing.innerHTML = '<em style="opacity:0.6;">NDIGITMARKET écrit…</em>';
    body.appendChild(typing);
    body.scrollTop = body.scrollHeight;
    
    setTimeout(() => {
        typing.remove();
        ndmAddBubble(ndmBotReply(question), 'bot');
    }, 800 + Math.random() * 500);
}

let ndmChatOpen = false;
function ndmToggleChat() {
    ndmChatOpen = !ndmChatOpen;
    document.getElementById('ndm-chat-box').classList.toggle('open', ndmChatOpen);
    if (ndmChatOpen) {
        setTimeout(() => document.getElementById('ndm-chat-input').focus(), 400);
    }
}

document.getElementById('ndm-chat-send').onclick = ndmSendMsg;
document.getElementById('ndm-chat-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') ndmSendMsg();
});
</script>


<!-- Back to top -->

<a href="#!" class="ndm-back-top" id="ndmBackTop">

    <i class="fas fa-chevron-up"></i>

</a>



<!-- ── MOBILE BOTTOM NAV ── -->

<?php

if (!isset($_SESSION['panier'])) $_SESSION['panier'] = array();

$total_articles = array_sum($_SESSION['panier']);

?>

<nav class="ndm-mob-nav">

    <ul>

        <li class="active">

            <a href="index">

                <i class="iconly-Home icli"></i>

                <span>Accueil</span>

            </a>

        </li>

        <!--<li>-->

        

        <!--    <button onclick="if(window.ndmOpenMenu) window.ndmOpenMenu()">-->

        <!--        <i class="iconly-Category icli"></i>-->

        <!--        <span>Catégories</span>-->

        <!--    </button>-->

        <!--</li>-->

        <li>

            <button id="ndmSearchTrigger">

                <i class="iconly-Search icli"></i>

                <span>Recherche</span>

            </button>

        </li>

        <li>

            <a href="cart">

                <i class="iconly-Bag-2 icli"></i>

                <span>Panier</span>

                <span class="ndm-mob-badge"><?php echo $total_articles; ?></span>

            </a>

        </li>

    </ul>

</nav>



<!-- Search overlay (mobile) -->

<div class="ndm-search-overlay" id="ndmSearchOverlay">

    <div class="ndm-search-box">

        <button class="ndm-search-close" id="ndmSearchClose">×</button>

        <h4>🔍 Rechercher</h4>

        <form action="recherche" method="GET">

            <input type="text" name="search_query"

                placeholder="Templates, plugins, scripts..."

                value="<?php echo isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : ''; ?>">

            <br><br>

            <button type="submit">

                <i class="fa-solid fa-magnifying-glass"></i> Rechercher

            </button>

        </form>

    </div>

</div>



<!-- ── FOOTER ── -->

<footer class="ndm-footer">



    <div class="ndm-footer-community">

        <h2>Rejoignez notre Communauté créative 🚀</h2>

        <p>Restez informé de nos dernières ressources, templates et conseils pour booster vos projets web.</p>

        <a href="https://chat.whatsapp.com/GUN7MamSdcqHivbsjbkD8X" class="ndm-wa-join" target="_blank">

            <i class="fab fa-whatsapp"></i> Rejoindre le groupe WhatsApp

        </a>

    </div>



    <div class="ndm-footer-body">



        <div class="ndm-footer-brand">

            <div class="ndm-footer-logo">NDIGIT <span>MARKET</span></div>

            <p>Votre bibliothèque de ressources web professionnelles. WordPress, HTML, PHP, React, PSD — tout ce qu'il faut pour lancer vos projets.</p>

            <div class="ndm-footer-socials">

                <a href="#" class="ndm-social-btn"><i class="fab fa-facebook-f"></i></a>

                <a href="#" class="ndm-social-btn"><i class="fab fa-instagram"></i></a>

                <a href="#" class="ndm-social-btn"><i class="fab fa-twitter"></i></a>

                <a href="#" class="ndm-social-btn"><i class="fab fa-linkedin-in"></i></a>

                <a href="https://wa.me/+22998111986" target="_blank" class="ndm-social-btn"><i class="fab fa-whatsapp"></i></a>

            </div>

        </div>



        <div class="ndm-footer-col">

            <h5>Navigation</h5>

            <ul>

                <li><a href="shop">Tous les templates</a></li>

                <li><a href="gratuit">Templates gratuits</a></li>

                <li><a href="abonnement">Abonnement premium</a></li>

                <li><a href="about">À propos</a></li>

                <li><a href="contact">Contact</a></li>

            </ul>

        </div>



        <div class="ndm-footer-col">

            <h5>Légal</h5>

            <ul>

                <li><a href="terme_condition">Termes & Conditions</a></li>

                <li><a href="privacy-policy">Confidentialité</a></li>

                <li><a href="mentions-legales">Mentions légales</a></li>

            </ul>

            <br>

            <div class="ndm-contact-item">

                <i class="fa-solid fa-envelope"></i>

                <a href="mailto:contact@ndigitmarket.com">contact@ndigitmarket.com</a>

            </div>

            <div class="ndm-contact-item">

                <i class="fab fa-whatsapp"></i>

                <a href="https://wa.me/+22998111986">+229 98 11 19 86</a>

            </div>

        </div>



        <div class="ndm-footer-col">

            <h5>Newsletter</h5>

            <p style="font-size:14px;color:#64748b;margin-bottom:14px;line-height:1.6;">Recevez nos nouveaux templates et promos directement par mail.</p>

            <div class="ndm-nl-form">

                <input type="email" placeholder="votre@email.com">

                <button type="submit">

                    <i class="fa-solid fa-paper-plane"></i> S'inscrire

                </button>

            </div>

        </div>



    </div>



    <div class="ndm-footer-divider"></div>



    <div class="ndm-footer-bottom">

        <p>© 2025 <strong>NDIGIT MARKET</strong> — Powered by NTECH DIGIT</p>

        <div class="ndm-footer-bottom-links">

            <a href="terme_condition">CGV</a>

            <a href="privacy-policy">Confidentialité</a>

            <a href="mentions-legales">Mentions légales</a>

        </div>

    </div>



</footer>



<!-- Deal Modal -->

<?php

$query = "SELECT * FROM produits WHERE prix_reduction > 0 ORDER BY date_ajout DESC LIMIT 5";

$statement = $database->prepare($query);

$statement->execute();

$produitsAvecReduction = $statement->fetchAll();

?>

<div class="modal fade theme-modal deal-modal" id="deal-box" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">

        <div class="modal-content">

            <div class="modal-header">

                <div>

                    <h5 class="modal-title">🔥 Deal aujourd'hui</h5>

                    <p class="mt-1 text-content">Offres recommandées pour vous.</p>

                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i></button>

            </div>

            <div class="modal-body">

                <div class="deal-offer-box">

                    <ul class="deal-offer-list">

                        <?php if (!empty($produitsAvecReduction)): ?>

                            <?php foreach ($produitsAvecReduction as $produit): ?>

                            <li class="list-1">

                                <div class="deal-offer-contain">

                                    <a href="product_detail.php?nom_article=<?php echo htmlspecialchars($produit['nom_article']); ?>" class="deal-image">

                                        <img src="back-end/apps/<?php echo $produit['image']; ?>" class="blur-up lazyload" alt="<?php echo $produit['nom_article']; ?>">

                                    </a>

                                    <a href="product_detail.php?nom_article=<?php echo htmlspecialchars($produit['nom_article']); ?>" class="deal-contain">

                                        <h5><?php echo $produit['nom_article']; ?></h5>

                                    </a>

                                </div>

                            </li>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <p>Aucune offre disponible.</p>

                        <?php endif; ?>

                    </ul>

                </div>

            </div>

        </div>

    </div>

</div>



<div class="bg-overlay"></div>

<script>

// PROTECTION 2025 – Version SÛRE & ULTRA-EFFICACE (pas de page blanche)

document.onkeydown = function(e) {

    if (

        e.keyCode === 123 || // F12

        (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 67 || e.keyCode === 74)) || // Ctrl+Shift+I/C/J

        (e.ctrlKey && e.keyCode === 85) || // Ctrl+U

        (e.ctrlKey && e.keyCode === 83) || // Ctrl+S

        (e.ctrlKey && e.shiftKey && e.keyCode === 75) // Ctrl+Shift+K (Firefox)

    ) {

        e.preventDefault();

        return false;

    }

};



// Bloque clic droit

document.addEventListener('contextmenu', e => e.preventDefault());



// Bloque drag & drop + téléchargement images

document.querySelectorAll('img').forEach(img => {

    img.draggable = false;

    img.ondragstart = () => false;

    img.oncontextmenu = () => false;

});



// Bloque sélection texte (enlève les 2 lignes si tu veux garder la sélection)

document.onselectstart = () => false;

document.onmousedown = (e) => { if (e.button === 2) return false; };



console.log("%cProtection NTechDigit activée", "color:#e45a0f;font-size:14px;");

</script>

<!-- Scripts -->

<script src="assets/js/jquery-3.6.0.min.js"></script>

<script src="assets/js/jquery-ui.min.js"></script>

<script src="assets/js/bootstrap/bootstrap.bundle.min.js"></script>

<script src="assets/js/bootstrap/bootstrap-notify.min.js"></script>

<script src="assets/js/bootstrap/popper.min.js"></script>

<script src="assets/js/feather/feather.min.js"></script>

<script src="assets/js/feather/feather-icon.js"></script>

<script src="assets/js/lazysizes.min.js"></script>

<script src="assets/js/slick/slick.js"></script>

<script src="assets/js/slick/slick-animation.min.js"></script>

<script src="assets/js/custom-slick-animated.js"></script>

<script src="assets/js/slick/custom_slick.js"></script>

<script src="assets/js/ion.rangeSlider.min.js"></script>

<script src="assets/js/filter-sidebar.js"></script>

<script src="assets/js/quantity-2.js"></script>

<script src="assets/js/jquery.elevatezoom.js"></script>

<script src="assets/js/zoom-filter.js"></script>

<script src="assets/js/timer1.js"></script>

<script src="assets/js/sticky-cart-bottom.js"></script>

<script src="assets/js/wow.min.js"></script>

<script src="assets/js/custom-wow.js"></script>

<script src="assets/js/script.js"></script>

<script src="assets/js/theme-setting.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">



<script>

// Back to top

window.addEventListener('scroll', function(){

    document.getElementById('ndmBackTop').classList.toggle('show', window.scrollY > 400);

});

document.getElementById('ndmBackTop').addEventListener('click', function(e){

    e.preventDefault(); window.scrollTo({top:0, behavior:'smooth'});

});



// Search overlay (mobile)

const searchTrigger = document.getElementById('ndmSearchTrigger');

const searchOverlay = document.getElementById('ndmSearchOverlay');

const searchClose   = document.getElementById('ndmSearchClose');

if(searchTrigger) searchTrigger.addEventListener('click', ()=>{ searchOverlay.classList.add('open'); });

if(searchClose)   searchClose.addEventListener('click',   ()=>{ searchOverlay.classList.remove('open'); });

if(searchOverlay) searchOverlay.addEventListener('click', function(e){

    if(e.target === searchOverlay) searchOverlay.classList.remove('open');

});

</script>