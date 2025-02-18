document.addEventListener('DOMContentLoaded', function() {
    const filterInputs = document.querySelectorAll('#filters-form input, #filters-form select');
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
    
    filterInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            e.preventDefault(); // Prevenir el comportamiento por defecto
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                applyFilters();
            }, 300);
        });
    });

    function applyFilters(pageUrl = null) {
        const formData = new FormData(document.getElementById('filters-form'));
        
        // Si hay una URL de página específica, usar sus parámetros
        if (pageUrl) {
            const url = new URL(pageUrl);
            formData.set('page', url.searchParams.get('page'));
        }

        fetch('/restaurantes/filter', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken.content
            },
            body: formData
        })
        .then(response => response.text())
        .then(html => {
            const temp = document.createElement('div');
            temp.innerHTML = html;
            
            document.querySelector('.restaurant-grid').innerHTML = temp.querySelector('.restaurant-grid').innerHTML;
            document.querySelector('.pagination').innerHTML = temp.querySelector('.pagination').innerHTML;
            
            // Agregar listeners a los nuevos enlaces de paginación
            document.querySelectorAll('.pagination a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    applyFilters(this.href);
                });
            });
        })
        .catch(error => console.error('Error:', error));
    }
});