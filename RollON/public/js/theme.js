// ROLL-ON Theme Manager
// Agregar al final de cada vista antes del cierre </body>

<script>
// Theme Manager - Agregar al final de cada página
(function() {
    // Aplicar tema guardado
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'light') {
        document.body.classList.add('light-mode');
    }

    // Crear botón de toggle si no existe
    if (!document.getElementById('theme-toggle-btn')) {
        const toggleBtn = document.createElement('button');
        toggleBtn.id = 'theme-toggle-btn';
        toggleBtn.className = 'theme-toggle';
        toggleBtn.innerHTML = '<i class="fas fa-moon"></i> Modo';
        toggleBtn.onclick = toggleTheme;
        
        // Insertar en el header si existe
        const headerRight = document.querySelector('.header-right');
        if (headerRight) {
            const logoutLink = headerRight.querySelector('a[href="/auth/logout"]');
            if (logoutLink) {
                headerRight.insertBefore(toggleBtn, logoutLink);
            } else {
                headerRight.appendChild(toggleBtn);
            }
        }
    }
})();

function toggleTheme() {
    document.body.classList.toggle('light-mode');
    localStorage.setItem('theme', document.body.classList.contains('light-mode') ? 'light' : 'dark');
}
</script>