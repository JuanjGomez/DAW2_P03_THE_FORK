document.addEventListener('DOMContentLoaded', function() {
    const filterInputs = document.querySelectorAll('#filters-form input, #filters-form select');
    const sortButtons = document.querySelectorAll('.sort-button');
    const restaurantGrid = document.querySelector('.restaurant-grid');
    
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
            }, 300);
        });
    });

    // Event listeners para los botones de ordenación
    sortButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remover la clase active de todos los botones
            sortButtons.forEach(btn => btn.classList.remove('active'));
            // Añadir la clase active al botón clickeado
            this.classList.add('active');
            applyFilters();
        });
    });

    function applyFilters() {
        const formData = new FormData(document.getElementById('filters-form'));
        
        // Añadir parámetros de ordenación
        const activeSortButton = document.querySelector('.sort-button.active');
        if (activeSortButton) {
            formData.append('sort', activeSortButton.dataset.sort);
            formData.append('order', activeSortButton.dataset.order);
        }

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