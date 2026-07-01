<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popup - Dernières Commandes</title>
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        /* Style principal du popup */
        .orders-popup {
            position: fixed;
            bottom: 25px;
            left: 25px;
            width: 340px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(8, 125, 103, 0.15);
            z-index: 9999;
            overflow: hidden;
            opacity: 0;
            transform: translateY(20px);
            animation: popupAppear 0.5s ease forwards;
        }

        @keyframes popupAppear {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* En-tête élégant */
        .popup-header {
            background: linear-gradient(135deg, #0f1923 0%, #1a2634 100%);
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-icon {
            width: 36px;
            height: 36px;
            background: rgba(8, 125, 103, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #087d67;
            font-size: 18px;
        }

        .header-text h3 {
            color: white;
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 4px;
            letter-spacing: 0.3px;
        }

        .header-text p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 11px;
            font-weight: 400;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .header-text p i {
            color: #087d67;
            font-size: 10px;
        }

        /* Bouton fermeture */
        .close-popup {
            width: 30px;
            height: 30px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 10px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .close-popup:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: rotate(90deg);
        }

        /* Corps du popup */
        .popup-content {
            padding: 15px;
            background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
            min-height: 130px;
            position: relative;
        }

        /* Conteneur des commandes */
        .orders-carousel {
            position: relative;
            height: 110px;
            overflow: hidden;
        }

        /* Slide de commande */
        .order-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            background: white;
            border-radius: 16px;
            padding: 12px;
            border: 1px solid rgba(8, 125, 103, 0.15);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.03);
            display: flex;
            align-items: center;
            gap: 12px;
            opacity: 0;
            transform: translateX(30px);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: none;
        }

        .order-slide.active {
            opacity: 1;
            transform: translateX(0);
            pointer-events: auto;
        }

        .order-slide.exit {
            opacity: 0;
            transform: translateX(-30px);
        }

        /* Image du produit */
        .product-image {
            position: relative;
            flex-shrink: 0;
        }

        .product-image img {
            width: 60px;
            height: 60px;
            border-radius: 14px;
            object-fit: cover;
            border: 2px solid white;
            box-shadow: 0 6px 12px rgba(8, 125, 103, 0.15);
        }

        .product-category-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 22px;
            height: 22px;
            background: #f97316;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            border: 2px solid white;
        }

        /* Informations commande */
        .order-details {
            flex: 1;
        }

        .client-info {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 6px;
        }

        .client-name {
            font-weight: 700;
            font-size: 14px;
            color: #0f1923;
        }

        .purchase-icon {
            color: #f97316;
            font-size: 12px;
        }

  

     
        .order-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 11px;
            color: #9ca3af;
        }

        .order-meta i {
            color: #087d67;
            font-size: 10px;
        }

        /* Indicateur de progression */
        .slide-progress {
            position: absolute;
            bottom: -2px;
            left: 0;
            height: 3px;
            background: linear-gradient(90deg, #087d67, #f97316);
            border-radius: 0 0 0 3px;
            animation: progress 4s linear;
        }

        @keyframes progress {
            from { width: 100%; }
            to { width: 0%; }
        }

        /* Pied du popup */
        .popup-footer {
            padding: 12px 15px;
            background: white;
            border-top: 1px solid rgba(8, 125, 103, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .total-orders {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #4b5563;
            font-size: 12px;
        }

        .total-orders i {
            color: #f97316;
        }

        .view-shop-link {
            color: #087d67;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            background: rgba(8, 125, 103, 0.1);
            border-radius: 30px;
            transition: all 0.3s;
        }

        .view-shop-link:hover {
            background: #087d67;
            color: white;
            gap: 8px;
        }

        .view-shop-link:hover i {
            color: white;
        }

        /* État vide */
        .empty-state {
            text-align: center;
            padding: 25px;
            color: #9ca3af;
        }

        .empty-state i {
            font-size: 30px;
            color: #d1d5db;
            margin-bottom: 10px;
        }

        .empty-state p {
            font-size: 13px;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .orders-popup {
                left: 15px;
                right: 15px;
                width: auto;
                bottom: 15px;
            }
        }
    </style>
</head>
<body>

<!-- Popup des dernières commandes -->
<div id="ordersPopup" class="orders-popup">
    
    <!-- En-tête -->
    <div class="popup-header">
        <div class="header-left">
            <div class="header-icon">
                <i class="fa-solid fa-bag-shopping"></i>
            </div>
            <div class="header-text">
                <h3>Dernières commandes</h3>
                <p><i class="fa-regular fa-clock"></i> Achats récents</p>
            </div>
        </div>
        <button class="close-popup" id="closePopup">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
    
    <!-- Contenu -->
    <div class="popup-content">
        <div class="orders-carousel" id="ordersContainer">
            <!-- Les commandes seront injectées ici -->
        </div>
    </div>
    
    <!-- Pied -->
    <div class="popup-footer">
        <div class="total-orders">
            <i class="fa-solid fa-crown"></i>
            <span id="totalOrders">0 commandes</span>
        </div>
        <a href="shop.php" class="view-shop-link">
            Voir boutique <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
class OrdersCarousel {
    constructor() {
        this.orders = [];
        this.currentIndex = 0;
        this.container = document.getElementById('ordersContainer');
        this.totalSpan = document.getElementById('totalOrders');
        this.interval = null;
        this.popup = document.getElementById('ordersPopup');
        
        this.init();
    }

    async init() {
        await this.fetchOrders();
        this.setupCloseButton();
    }

    async fetchOrders() {
        try {
            const response = await fetch('get_orders.php');
            this.orders = await response.json();
            
            // Mettre à jour le compteur
            if (this.totalSpan) {
                this.totalSpan.textContent = `${this.orders.length} commande${this.orders.length > 1 ? 's' : ''}`;
            }
            
            if (this.orders.length === 0) {
                this.showEmptyState();
            } else {
                this.startCarousel();
            }
        } catch (error) {
            console.error('Erreur:', error);
            this.showErrorState();
        }
    }

    createOrderSlide(order) {
        const slide = document.createElement('div');
        slide.className = 'order-slide';
        
        // Extraire la catégorie (simulée ici - adaptez selon vos données)
        const category = order.categorie || 'Template';
        const categoryIcon = this.getCategoryIcon(category);
        
        slide.innerHTML = `
            <div class="product-image">
                <img src="back-end/apps/${order.image}" 
                     alt="${order.nom_article}"
                     onerror="this.src='back-end/apps/Blue.jpg'">
                <div class="product-category-badge">
                    <i class="${categoryIcon}"></i>
                </div>
            </div>
            <div class="order-details">
                <div class="client-info">
                    <span class="client-name">${order.user_nom} ${order.user_prenom}</span>
                    <i class="fa-solid fa-cart-arrow-down purchase-icon"></i>
                </div>
                <div class="product-name">
                    <i class="fa-regular fa-file"></i>
                    <span>${this.truncateText(order.nom_article, 25)}</span>
                </div>
                <div class="order-meta">
                  
                    <span><i class="fa-solid fa-tag"></i> ${category}</span>
                </div>
            </div>
            <div class="slide-progress"></div>
        `;
        
        // Ajouter le lien vers le produit
        const link = document.createElement('a');
        link.href = `product_detail.php?nom_article=${encodeURIComponent(order.nom_article)}`;
        link.style.cssText = 'position:absolute; inset:0; z-index:5;';
        slide.appendChild(link);
        
        return slide;
    }

    getCategoryIcon(category) {
        const icons = {
            'WordPress': 'fa-brands fa-wordpress',
            'HTML': 'fa-brands fa-html5',
            'PHP': 'fa-brands fa-php',
            'React': 'fa-brands fa-react',
            'PSD': 'fa-regular fa-file-image',
            'Plugin': 'fa-solid fa-puzzle-piece',
            'JavaScript': 'fa-brands fa-js'
        };
        return icons[category] || 'fa-regular fa-file-code';
    }

    truncateText(text, maxLength) {
        return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
    }

    showSlide(index) {
        // Vider le conteneur
        while (this.container.firstChild) {
            this.container.removeChild(this.container.firstChild);
        }
        
        const order = this.orders[index];
        if (!order) return;
        
        const slide = this.createOrderSlide(order);
        this.container.appendChild(slide);
        
        // Animation d'entrée
        setTimeout(() => {
            slide.classList.add('active');
        }, 50);
        
        // Programmer le changement suivant
        setTimeout(() => {
            slide.classList.add('exit');
            setTimeout(() => {
                this.nextSlide();
            }, 500);
        }, 4000);
    }

    nextSlide() {
        this.currentIndex = (this.currentIndex + 1) % this.orders.length;
        this.showSlide(this.currentIndex);
    }

    startCarousel() {
        if (this.orders.length > 0) {
            this.showSlide(0);
        }
    }

    showEmptyState() {
        this.container.innerHTML = `
            <div class="order-slide active" style="justify-content:center;">
                <div class="empty-state">
                    <i class="fa-regular fa-store"></i>
                    <p>Aucune commande récente</p>
                </div>
            </div>
        `;
    }

    showErrorState() {
        this.container.innerHTML = `
            <div class="order-slide active" style="justify-content:center;">
                <div class="empty-state">
                    <i class="fa-solid fa-circle-exclamation" style="color:#ef4444;"></i>
                    <p>Erreur de chargement</p>
                </div>
            </div>
        `;
    }

    setupCloseButton() {
        const closeBtn = document.getElementById('closePopup');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                this.popup.style.animation = 'popupAppear 0.5s reverse forwards';
                setTimeout(() => {
                    this.popup.style.display = 'none';
                }, 500);
            });
        }
    }
}

// Initialisation au chargement
document.addEventListener('DOMContentLoaded', () => {
    new OrdersCarousel();
});
</script>

</body>
</html>