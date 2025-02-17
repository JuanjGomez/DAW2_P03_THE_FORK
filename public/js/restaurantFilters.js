document.addEventListener('DOMContentLoaded', function() {
    const filterInputs = document.querySelectorAll('#filters-form input, #filters-form select');
    const restaurantGrid = document.querySelector('.grid-container');
    
    if (!restaurantGrid) {
        console.error('No se encontró el contenedor de restaurantes');
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('No se encontró el token CSRF');
        return;
    }

    let timeoutId;
    
    // Event listeners para los filtros
    filterInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                applyFilters();
            }, 300); // Aplica los filtros después de 300ms de inactividad
        });
    });

    function applyFilters() {
        const formData = new FormData(document.getElementById('filters-form'));
        
        fetch('/filter-restaurants', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken.content
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.text();
        })
        .then(html => {
            restaurantGrid.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
}); 