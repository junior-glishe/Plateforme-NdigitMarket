        </div>
    </div>
</div>

<script>
    // Toggle sidebar sur mobile
    document.getElementById('menuToggle').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
        document.getElementById('sidebarOverlay').classList.toggle('active');
    });

    document.getElementById('sidebarOverlay').addEventListener('click', function() {
        document.getElementById('sidebar').classList.remove('active');
        this.classList.remove('active');
    });

    // Dropdown profil
    document.getElementById('adminProfile').addEventListener('click', function(e) {
        e.stopPropagation();
        document.getElementById('dropdownMenu').classList.toggle('show');
    });

    document.addEventListener('click', function() {
        document.getElementById('dropdownMenu').classList.remove('show');
    });

  
</script>

<!--<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>-->
</body>
</html>