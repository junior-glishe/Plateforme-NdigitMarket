 // Gestion de la sélection du rôle
    let selectedRole = null;
    const roleCards = document.querySelectorAll('.role-card');
    const selectedRoleInput = document.getElementById('selectedRole');
    const roleAlert = document.getElementById('roleAlert');
    const errorMsg = document.getElementById('errorMsg');
    const loginForm = document.getElementById('loginForm');

    const demoUsers = [
    ];

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
          case '':
            dashboardUrl = '/dashboard.php';
            break;
          case '':
            dashboardUrl = '/dashboard.php';
            break;
          case '':
            dashboardUrl = '/dashboard.php';
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



       
    