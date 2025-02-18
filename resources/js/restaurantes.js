document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('filters-form');
    const restaurantGrid = document.querySelector('.restaurant-grid');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        const queryString = new URLSearchParams(formData).toString();

        fetch(`/admin/restaurantes?${queryString}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newRestaurantGrid = doc.querySelector('.restaurant-grid');
            restaurantGrid.innerHTML = newRestaurantGrid.innerHTML;
        })
        .catch(error => console.error('Error:', error));
    });

    // Limpiar filtros
    document.getElementById('limpiarFiltros').addEventListener('click', function (e) {
        e.preventDefault();
        form.reset();
        form.dispatchEvent(new Event('submit'));
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const buscarBtn = document.getElementById('buscarRestauranteAjax');
    const limpiarBtn = document.getElementById('limpiarFiltros');
    
    if (buscarBtn) {
        buscarBtn.addEventListener('click', aplicarFiltros);
    }
    
    if (limpiarBtn) {
        limpiarBtn.addEventListener('click', limpiarFiltros);
    }
});

function aplicarFiltros() {
    const form = document.getElementById('filtroForm');
    const url = form.action;
    const params = new URLSearchParams(new FormData(form));
    
    fetch(`${url}?${params.toString()}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById('resultadosRestaurantes').innerHTML = html;
        updatePaginationLinks();
    })
    .catch(error => console.error('Error:', error));
}

function limpiarFiltros(e) {
    e.preventDefault();
    window.location.href = e.target.href;
}

function updatePaginationLinks() {
    document.querySelectorAll('.pagination a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            fetch(this.href, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('resultadosRestaurantes').innerHTML = html;
                updatePaginationLinks();
            })
            .catch(error => console.error('Error:', error));
        });
    });
} 