 // Gestion de la sélection du rôle
let selectedRole = null;
const roleCards = document.querySelectorAll('.role-card');
const selectedRoleInput = document.getElementById('selectedRole');
const roleAlert = document.getElementById('roleAlert');
const errorMsg = document.getElementById('errorMsg');
const loginForm = document.getElementById('loginForm');

roleCards.forEach(card => {
  card.addEventListener('click', function() {
    roleCards.forEach(c => c.classList.remove('selected'));
    this.classList.add('selected');
    selectedRole = this.getAttribute('data-role');
    if (selectedRoleInput) {
      selectedRoleInput.value = selectedRole;
    }
    if (roleAlert) {
      roleAlert.classList.add('hidden');
    }
  });
});

if (loginForm) {
  loginForm.addEventListener('submit', function(e) {
    if (roleCards.length > 0 && !selectedRole) {
      e.preventDefault();
      if (roleAlert) {
        roleAlert.classList.remove('hidden');
      }
      if (errorMsg) {
        errorMsg.classList.add('hidden');
      }
      return;
    }
    if (selectedRoleInput && selectedRole) {
      selectedRoleInput.value = selectedRole;
    }
  });
}
    