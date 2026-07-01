/* ==============================================
   nDigitMarket - Digital Products Marketplace
   JavaScript - Main Application Logic
   ============================================== */

console.log('✅ script.js loaded!');

// ========== VARIABLES GLOBALES ==========
// Ces variables seront initialisées avec les données PHP
let PRODUCTS = [];
let SUBSCRIPTION_PLANS = [];
let CATEGORIES = [];

// ========== GESTION DU PANIER ==========

class ShoppingCart {
  constructor() {
    this.loadCart();
  }

  loadCart() {
    const cart = localStorage.getItem("cart");
    this.items = cart ? JSON.parse(cart) : [];
  }

  saveCart() {
    localStorage.setItem("cart", JSON.stringify(this.items));
  }

  addItem(product) {
    const existingItem = this.items.find((item) => item.id === product.id);

    if (existingItem) {
      existingItem.quantity += 1;
    } else {
      this.items.push({ ...product, quantity: 1 });
    }

    this.saveCart();
    this.showNotification(`${product.title} ajouté au panier!`);
  }

  removeItem(productId) {
    this.items = this.items.filter((item) => item.id !== productId);
    this.saveCart();
  }

  getTotal() {
    return this.items.reduce((total, item) => total + item.price * item.quantity, 0);
  }

  getItemCount() {
    return this.items.reduce((count, item) => count + item.quantity, 0);
  }

  clear() {
    this.items = [];
    this.saveCart();
  }

  showNotification(message) {
    const notification = document.createElement("div");
    notification.style.cssText = `
      position: fixed;
      top: 20px;
      right: 20px;
      background-color: #10b981;
      color: white;
      padding: 15px 20px;
      border-radius: 8px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
      z-index: 3000;
      animation: slideInRight 0.3s ease;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => {
      notification.style.animation = "slideOutRight 0.3s ease";
      setTimeout(() => notification.remove(), 300);
    }, 3000);
  }
}

// ========== GESTION UTILISATEUR ==========

class UserManager {
  constructor() {
    this.loadUser();
  }

  loadUser() {
    const user = localStorage.getItem("currentUser");
    this.currentUser = user ? JSON.parse(user) : null;
  }

  saveUser(user) {
    this.currentUser = user;
    localStorage.setItem("currentUser", JSON.stringify(user));
  }

  register(email, password, username) {
    // Cette fonction sera gérée par PHP côté serveur
    // On redirige vers la page d'inscription
    window.location.href = "inscrire.php";
  }

  login(email, password) {
    // Cette fonction sera gérée par PHP côté serveur
    // On redirige vers la page de connexion
    window.location.href = "login.php";
  }

  logout() {
    // Déconnexion via PHP
    window.location.href = "deconnexion.php";
  }

  isLoggedIn() {
    // Vérifier via une variable PHP passée au JavaScript
    return typeof IS_LOGGED_IN !== 'undefined' && IS_LOGGED_IN;
  }

  addDownload(productId) {
    // Cette fonction sera gérée par PHP côté serveur
    console.log("Download added:", productId);
  }
}

// ========== INITIALISATION GLOBALE ==========

let cart = new ShoppingCart();
let userManager = new UserManager();

// ========== FONCTION NOTIFICATION GLOBALE ==========

function showNotification(message, type = "success") {
  const notificationDiv = document.createElement("div");
  notificationDiv.className = "notification";
  notificationDiv.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 16px 24px;
    background: ${type === "success" ? "var(--success-color)" : type === "error" ? "var(--danger-color)" : "#5e72e4"};
    color: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 10000;
    animation: slideInRight 0.3s ease;
    font-weight: 500;
  `;
  
  const icon = type === "success" ? "✓" : type === "error" ? "✕" : "ℹ";
  notificationDiv.textContent = `${icon} ${message}`;
  
  document.body.appendChild(notificationDiv);
  
  setTimeout(() => {
    notificationDiv.style.animation = "slideOutRight 0.3s ease";
    setTimeout(() => notificationDiv.remove(), 300);
  }, 3000);
}

// ========== GESTION NAVIGATION ==========

function initNavigation() {
  const navToggle = document.querySelector(".navbar-toggle");
  const navMenu = document.querySelector(".navbar-menu");
  const userAvatar = document.querySelector(".user-avatar");
  const dropdownMenu = document.querySelector(".dropdown-menu");

  // Function to close menu with animation
  function closeMenu() {
    if (navMenu?.classList.contains("active")) {
      navMenu.classList.remove("active");
      document.body.style.overflow = "auto";
    }
  }

  // Function to open menu
  function openMenu() {
    navMenu?.classList.add("active");
    document.body.style.overflow = "hidden";
  }

  // Toggle menu on hamburger click
  if (navToggle) {
    navToggle.addEventListener("click", (e) => {
      e.stopPropagation();
      if (navMenu?.classList.contains("active")) {
        closeMenu();
      } else {
        openMenu();
      }
    });
  }

  // Close menu when clicking on a link
  if (navMenu) {
    const menuLinks = navMenu.querySelectorAll("a");
    menuLinks.forEach(link => {
      link.addEventListener("click", () => {
        closeMenu();
      });
    });
  }

  // Close menu on overlay click or outside
  document.addEventListener("click", (e) => {
    if (navMenu && navMenu.classList.contains("active")) {
      if (!e.target.closest(".navbar-menu") && !e.target.closest(".navbar-toggle")) {
        closeMenu();
      }
    }

    // Close dropdown menu
    if (!e.target.closest(".user-menu") && dropdownMenu) {
      dropdownMenu.classList.remove("active");
    }
  });

  // Close menu on escape key
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      closeMenu();
    }
  });

  // User avatar dropdown
  if (userAvatar) {
    userAvatar.addEventListener("click", (e) => {
      e.stopPropagation();
      dropdownMenu?.classList.toggle("active");
    });
  }

  updateAuthUI();

  initCategoriesDropdown();
}

function updateAuthUI() {
  const authContainer = document.querySelector(".navbar-auth");
  if (!authContainer) return;

  const loginBtn = authContainer.querySelector(".btn-login");
  const userMenu = authContainer.querySelector(".user-menu");
  const mobileLoginBtn = document.getElementById("mobile-login-btn");
  const bottomLogoutBtn = document.getElementById("bottom-logout-btn");

  if (userManager.isLoggedIn()) {
    if (loginBtn) loginBtn.style.display = "none";
    if (userMenu) {
      userMenu.classList.remove("hidden");
      const avatar = userMenu.querySelector(".user-avatar");
      if (avatar && typeof USER_NAME !== 'undefined') {
        avatar.textContent = USER_NAME.charAt(0).toUpperCase();
      }
    }
    if (mobileLoginBtn) {
      mobileLoginBtn.style.display = "none";
    }
    if (bottomLogoutBtn) {
      bottomLogoutBtn.classList.remove("hidden");
    }
  } else {
    if (loginBtn) loginBtn.style.display = "block";
    if (userMenu) userMenu.classList.add("hidden");
    if (mobileLoginBtn) {
      mobileLoginBtn.style.display = "block";
    }
    if (bottomLogoutBtn) {
      bottomLogoutBtn.classList.add("hidden");
    }
  }
}

// ========== RECHERCHE ==========

function initSearch() {
  const searchInput = document.getElementById("search-input");
  const searchBtn = document.getElementById("search-btn");

  if (!searchInput || !searchBtn) return;

  function performSearch() {
    const query = searchInput.value.trim();
    if (!query) {
      showNotification("Veuillez entrer un terme de recherche", "info");
      return;
    }

    // Rediriger vers la page de recherche PHP
    window.location.href = "recherche.php?search_query=" + encodeURIComponent(query);
  }

  searchBtn.addEventListener("click", performSearch);
  searchInput.addEventListener("keypress", (e) => {
    if (e.key === "Enter") {
      performSearch();
    }
  });
}

// ========== GESTION DU PANIER ==========

function addToCart(productId) {
  // Cette fonction sera gérée par PHP
  // On utilise fetch pour envoyer la requête au serveur
  fetch('ajouter_panier.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'product_id=' + productId
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      showNotification(data.message);
      updateCartUI();
      // Mettre à jour le compteur du panier
      const cartCount = document.querySelector(".cart-count");
      if (cartCount) {
        cartCount.textContent = data.cart_count;
        cartCount.classList.remove('hidden');
      }
    } else {
      showNotification(data.message, 'error');
    }
  });
}

function toggleWishlist(productId) {
  // Cette fonction sera gérée par PHP
  fetch('toggle_wishlist.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'product_id=' + productId
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      showNotification(data.message);
      const btn = event.target.closest(".btn-wishlist");
      if (btn) {
        if (data.in_wishlist) {
          btn.classList.add('active');
          btn.innerHTML = '<i class="fas fa-heart" style="color: #ef4444;"></i>';
        } else {
          btn.classList.remove('active');
          btn.innerHTML = '<i class="far fa-heart"></i>';
        }
      }
    }
  });
}

function updateCartUI() {
  const cartCount = document.querySelector(".cart-count");
  const cartItemsList = document.querySelector(".cart-items-list");
  const cartEmpty = document.querySelector(".cart-empty");
  const cartTotal = document.querySelector(".cart-total-price");
  const cartTotalContainer = document.querySelector(".cart-total");
  const modalFooter = document.querySelector(".modal-footer");

  // Récupérer le panier depuis le serveur
  fetch('get_cart.php')
    .then(response => response.json())
    .then(data => {
      if (cartCount) {
        cartCount.textContent = data.item_count;
        if (data.item_count > 0) {
          cartCount.classList.remove('hidden');
        } else {
          cartCount.classList.add('hidden');
        }
      }

      if (data.items.length === 0) {
        if (cartItemsList) {
          cartItemsList.innerHTML = "";
          cartItemsList.style.display = "none";
        }
        if (cartEmpty) cartEmpty.style.display = "block";
        if (cartTotalContainer) cartTotalContainer.style.display = "none";
        if (modalFooter) modalFooter.style.display = "none";
      } else {
        if (cartEmpty) cartEmpty.style.display = "none";

        if (cartItemsList) {
          cartItemsList.style.display = "block";
          cartItemsList.innerHTML = data.items
            .map(
              (item) => `
            <li class="cart-item">
              <div class="cart-item-info">
                <div class="cart-item-name">${item.title}</div>
                <div class="cart-item-price">${item.price} CFA</div>
              </div>
              <span class="cart-item-quantity">x${item.quantity}</span>
              <button class="cart-item-remove" onclick="removeFromCart(${item.id})">✕</button>
            </li>
          `
            )
            .join("");
        }

        if (cartTotal) {
          cartTotal.textContent = data.total.toLocaleString() + " CFA";
        }

        if (cartTotalContainer) cartTotalContainer.style.display = "flex";
        if (modalFooter) modalFooter.style.display = "flex";
      }
    });
}

function removeFromCart(productId) {
  fetch('retirer_panier.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'product_id=' + productId
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      showNotification(data.message);
      updateCartUI();
    }
  });
}

// ========== MODAL PANIER ==========

function initCartModal() {
  const cartBtns = document.querySelectorAll("#cartBtn, #cartBtnBottom");
  const cartModal = document.querySelector(".cart-modal");
  const closeBtn = cartModal?.querySelector(".modal-close");
  const checkoutBtn = cartModal?.querySelector(".btn-checkout");

  cartBtns.forEach(btn => {
    if (btn) {
      btn.addEventListener("click", () => {
        cartModal?.classList.add("active");
        updateCartUI();
      });
    }
  });

  if (closeBtn) {
    closeBtn.addEventListener("click", () => {
      cartModal?.classList.remove("active");
    });
  }

  if (checkoutBtn) {
    checkoutBtn.addEventListener("click", () => {
      if (!userManager.isLoggedIn()) {
        showNotification("Veuillez vous connecter", "info");
        window.location.href = "login.php";
        return;
      }
      
      // Rediriger vers la page de checkout
      window.location.href = "checkout.php";
    });
  }

  // Fermer le modal en cliquant en dehors
  document.addEventListener("click", (e) => {
    if (e.target === cartModal) {
      cartModal?.classList.remove("active");
    }
  });
}

// ========== AUTHENTIFICATION ==========

function showAuthModal(mode = "login") {
  if (mode === "login") {
    window.location.href = "login.php";
  } else {
    window.location.href = "inscrire.php";
  }
}

function initAuthModal() {
  const loginBtn = document.querySelector(".btn-login");

  if (loginBtn) {
    loginBtn.addEventListener("click", () => showAuthModal("login"));
  }

  // Bouton logout
  document.addEventListener("click", (e) => {
    if (e.target.classList.contains("btn-logout")) {
      window.location.href = "deconnexion.php";
    }
  });

  // Bouton logout mobile
  const bottomLogoutBtn = document.getElementById("bottom-logout-btn");
  if (bottomLogoutBtn) {
    bottomLogoutBtn.addEventListener("click", () => {
      window.location.href = "deconnexion.php";
    });
  }
}

// ========== CARROUSEL PRODUITS ==========

class ProductCarousel {
  constructor(carouselTrackId, prevBtnId, nextBtnId, indicatorsId) {
    this.track = document.getElementById(carouselTrackId);
    this.prevBtn = document.getElementById(prevBtnId);
    this.nextBtn = document.getElementById(nextBtnId);
    this.indicatorsContainer = document.getElementById(indicatorsId);
    this.currentIndex = 0;
    this.items = [];
    
    if (this.track && this.prevBtn && this.nextBtn) {
      this.init();
    }
  }

  init() {
    this.items = this.track.querySelectorAll('.carousel-item');
    
    if (this.items.length === 0) return;

    this.createIndicators();

    this.prevBtn.addEventListener('click', () => this.slide(-1));
    this.nextBtn.addEventListener('click', () => this.slide(1));

    this.autoSlide();
  }

  createIndicators() {
    this.indicatorsContainer.innerHTML = '';
    const groupCount = Math.ceil(this.items.length / 4);
    for (let i = 0; i < groupCount; i++) {
      const dot = document.createElement('div');
      dot.className = 'carousel-dot' + (i === 0 ? ' active' : '');
      dot.addEventListener('click', () => this.goToSlide(i));
      this.indicatorsContainer.appendChild(dot);
    }
  }

  slide(direction) {
    this.currentIndex += direction * 4;

    const maxIndex = Math.max(0, this.items.length - 4);
    if (this.currentIndex >= this.items.length) {
      this.currentIndex = 0;
    } else if (this.currentIndex < 0) {
      this.currentIndex = maxIndex;
    }

    this.updateCarousel();
  }

  goToSlide(index) {
    this.currentIndex = index * 4;
    this.updateCarousel();
  }

  updateCarousel() {
    const offset = -this.currentIndex * 25;
    this.track.style.transform = `translateX(${offset}%)`;

    document.querySelectorAll('.carousel-dot').forEach((dot, index) => {
      dot.classList.toggle('active', index === Math.floor(this.currentIndex / 4));
    });
  }

  autoSlide() {
    setInterval(() => {
      this.slide(1);
    }, 8000);
  }
}

// ========== ANIMATIONS CSS ==========

function injectAnimations() {
  const style = document.createElement("style");
  style.textContent = `
    @keyframes slideInRight {
      from {
        transform: translateX(100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @keyframes slideOutRight {
      from {
        transform: translateX(0);
        opacity: 1;
      }
      to {
        transform: translateX(100%);
        opacity: 0;
      }
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }
  `;
  document.head.appendChild(style);
}

// ========== INITIALISATION PAGE ==========

document.addEventListener("DOMContentLoaded", () => {
  console.log('📄 DOMContentLoaded fired!');
  
  // Animations
  injectAnimations();

  // Navigation
  initNavigation();

  // Recherche
  initSearch();

  // Mettre à jour l'état d'authentification
  updateAuthUI();

  // Panier
  initCartModal();
  updateCartUI();

  // Authentification
  initAuthModal();

  // Observer pour les animations au scroll
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.style.animation = "fadeIn 0.6s ease";
      }
    });
  });

  document.querySelectorAll(".section").forEach((section) => {
    observer.observe(section);
  });
});

// ========== CATÉGORIES DROPDOWN ==========

function initCategoriesDropdown() {
  const categoriesBtn = document.getElementById('categoriesBtn');
  const categoriesDropdown = document.getElementById('categoriesDropdown');
  
  if (!categoriesBtn || !categoriesDropdown) return;
  
  categoriesBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    const isOpen = categoriesDropdown.classList.contains('hidden');
    if (isOpen) {
      categoriesDropdown.classList.remove('hidden');
    } else {
      categoriesDropdown.classList.add('hidden');
    }
  });
  
  const categoryItems = categoriesDropdown.querySelectorAll('.category-item');
  categoryItems.forEach(item => {
    item.addEventListener('click', () => {
      categoriesDropdown.classList.add('hidden');
    });
  });
  
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.bottom-nav-categories')) {
      categoriesDropdown.classList.add('hidden');
    }
  });
}