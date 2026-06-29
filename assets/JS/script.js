 // Gestion de la sélection du rôle
    let selectedRole = null;
    const roleCards = document.querySelectorAll('.role-card');
    const selectedRoleInput = document.getElementById('selectedRole');
    const roleAlert = document.getElementById('roleAlert');
    const errorMsg = document.getElementById('errorMsg');
    const loginForm = document.getElementById('loginForm');

    // Comptes de test par rôle
    const demoUsers = [
    ];

    // Clic sur une carte rôle
    roleCards.forEach(card => {
      card.addEventListener('click', function() {
        // Retirer la sélection de toutes les cartes
        roleCards.forEach(c => c.classList.remove('selected'));
        // Ajouter la sélection à la carte cliquée
        this.classList.add('selected');
        // Stocker le rôle sélectionné
        selectedRole = this.getAttribute('data-role');
        selectedRoleInput.value = selectedRole;
        // Cacher l'alerte rôle si elle était visible
        roleAlert.classList.add('hidden');
      });
    });

    // Simulation de connexion
    loginForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Vérifier si un rôle est sélectionné
      if (!selectedRole) {
        roleAlert.classList.remove('hidden');
        errorMsg.classList.add('hidden');
        return;
      }
      
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;
      
      // Vérifier les identifiants
      const user = demoUsers.find(u => u.email === email && u.password === password && u.role === selectedRole);
      
      if (user) {
        // Redirection simulée selon le rôle
        let dashboardUrl = '';
        switch(user.role) {
          case 'admin':
            dashboardUrl = 'admin/dashboard.php';
            break;
          case 'medecin':
            dashboardUrl = 'medecins/dashboard.php';
            break;
          case 'infirmier':
            dashboardUrl = 'infirmiers/dashboard.php';
            break;
          case 'patient':
            dashboardUrl = 'patients/dashboard.php';
            break;
        }
        alert(`Bienvenue ${user.name} ! Redirection vers votre espace ${user.role}...`);
        // window.location.href = dashboardUrl;
      } else {
        errorMsg.classList.remove('hidden');
        roleAlert.classList.add('hidden');
        setTimeout(() => {
          errorMsg.classList.add('hidden');
        }, 3000);
      }
    });



        (function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const hamburger = document.getElementById('hamburgerBtn');

            function openSidebar() {
                sidebar.classList.add('open');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }

            hamburger.addEventListener('click', function(e) {
                e.stopPropagation();
                if (sidebar.classList.contains('open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });

            overlay.addEventListener('click', closeSidebar);

            // Fermer avec la touche Echap
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('open')) {
                    closeSidebar();
                }
            });

            // À l'agrandissement de l'écran, si la sidebar est ouverte en mobile, on la referme
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768 && sidebar.classList.contains('open')) {
                    closeSidebar();
                }
            });
        })();
    