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