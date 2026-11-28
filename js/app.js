document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');

    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        document.addEventListener('click', (e) => {
            if (sidebar.classList.contains('active') && !sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });
    }

    const criterioBusqueda = document.getElementById('criterio_busqueda');
    const valorBusqueda = document.getElementById('valor_busqueda');

    if (criterioBusqueda && valorBusqueda) {
        criterioBusqueda.addEventListener('change', () => {
            valorBusqueda.setAttribute('list', criterioBusqueda.value === 'equipo' ? 'lista_equipos' : 'lista_solicitantes');
            valorBusqueda.value = '';
        });
    }
});