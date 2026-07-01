<?php
// footer.php
?>
<!-- Footer -->
<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-section about">
                <h3>À Propos de nDigitMarket</h3>
                <p class="footer-text">
                    Votre marketplace de confiance pour des produits numériques de qualité supérieure. Nous connectons les créateurs avec des professionnels du monde entier.
                </p>
                <div class="footer-contact">
                    <p><i class="fas fa-envelope"></i> support@ndigitmarket.com</p>
                    <p><i class="fas fa-phone"></i> +221 77 123 45 67</p>
                </div>
            </div>
            <div class="footer-section">
                <h3>Produits</h3>
                <ul>
                    <li><a href="products.php?category=wordpress">WordPress</a></li>
                    <li><a href="products.php?category=html">Templates HTML</a></li>
                    <li><a href="products.php?category=react">React</a></li>
                    <li><a href="products.php?category=graphics">Graphics</a></li>
                    <li><a href="products.php?category=plugins">Plugins</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Liens utiles</h3>
                <ul>
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="about.php">À propos</a></li>
                    <li><a href="abonnement.php">Abonnements</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="conditions.php">Conditions d'utilisation</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Newsletter</h3>
                <p class="footer-newsletter-text">
                    Recevez nos meilleures offres et les dernières nouveautés.
                </p>
                <div class="footer-newsletter">
                    <form action="newsletter.php" method="POST">
                        <input type="email" name="newsletter-email" id="newsletter-email-index" placeholder="Votre email" required>
                        <button type="submit"><i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
        </div>

        <div class="footer-divider"></div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> nDigitMarket. Tous droits réservés. Conçu avec <i class="fas fa-heart" style="color: #ef4444;"></i> pour les créateurs.</p>
            <div class="footer-social">
                <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="https://twitter.com" target="_blank" rel="noopener noreferrer" title="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" title="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
    </div>
</footer>

<!-- Modals -->
<div class="modal cart-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Mon Panier</h2>
            <button class="modal-close">✕</button>
        </div>

        <div class="cart-empty">
            <div class="cart-empty-icon"><i class="fas fa-shopping-cart"></i></div>
            <p>Votre panier est vide</p>
        </div>

        <ul class="cart-items-list hidden"></ul>

        <div class="cart-total hidden">
            <span class="cart-total-label">Total:</span>
            <span class="cart-total-price">0 CFA</span>
        </div>

        <div class="modal-footer hidden">
            <button class="btn-outline">Continuer vos achats</button>
            <button class="btn-primary btn-checkout">Passer la commande</button>
        </div>
    </div>
</div>

<div class="modal auth-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Se connecter</h2>
            <button class="modal-close">✕</button>
        </div>
        <form class="auth-form" action="login_process.php" method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-primary" style="width: 100%;">Connexion</button>
            <div class="auth-toggle">
                Pas encore inscrit? <a href="inscrire.php">S'inscrire maintenant</a>
            </div>
        </form>
    </div>
</div>

<!-- Mobile Bottom Navigation -->
<nav class="mobile-bottom-nav" id="mobileBottomNav">
    <a href="index.php" class="bottom-nav-item active" data-page="home">
        <i class="fas fa-home"></i>
        <span>Accueil</span>
    </a>
    <div class="bottom-nav-categories">
        <button class="bottom-nav-item" id="categoriesBtn">
            <i class="fas fa-th"></i>
            <span>Catégories</span>
        </button>
        <div class="categories-dropdown hidden" id="categoriesDropdown">
            <a href="products.php?category=wordpress" class="category-item" data-category="wordpress"><i class="fab fa-wordpress"></i> WordPress</a>
            <a href="products.php?category=html" class="category-item" data-category="html"><i class="fas fa-code"></i> HTML/CSS</a>
            <a href="products.php?category=react" class="category-item" data-category="react"><i class="fab fa-react"></i> React</a>
            <a href="products.php?category=graphics" class="category-item" data-category="graphics"><i class="fas fa-palette"></i> Graphics</a>
            <a href="products.php?category=plugins" class="category-item" data-category="plugins"><i class="fas fa-plug"></i> Plugins</a>
            <a href="products.php?category=psd" class="category-item" data-category="psd"><i class="fas fa-image"></i> PSD</a>
            <a href="products.php?category=powerpoint" class="category-item" data-category="powerpoint"><i class="fas fa-chart-bar"></i> PowerPoint</a>
        </div>
    </div>
    <button class="bottom-nav-item bottom-nav-cart" id="cartBtnBottom">
        <i class="fas fa-shopping-cart"></i>
        <span>Panier</span>
        <span class="cart-count <?php echo isset($total_articles) && $total_articles > 0 ? '' : 'hidden'; ?>"><?php echo $total_articles ?? 0; ?></span>
    </button>
    <?php if (isset($_SESSION['user_id'])): ?>
    <a href="deconnexion.php" id="bottom-logout-btn" class="bottom-nav-item">
        <i class="fas fa-sign-out-alt"></i>
        <span>Déconnexion</span>
    </a>
    <?php else: ?>
    <button id="bottom-logout-btn" class="bottom-nav-item hidden">
        <i class="fas fa-sign-out-alt"></i>
        <span>Déconnexion</span>
    </button>
    <?php endif; ?>
</nav>

<!-- Scripts -->
<script src="script.js"></script>
</body>
</html>