// SweetAlerts -----------------------------------------------------------------------------------

    // Alert de un buen inicio de sesion
    if (window.successMessage) {
        Swal.fire({
            icon: 'success',
            title: '¡Bienvenido!',
            text: window.successMessage,
        });

        // Limpiar la variable successMessage para evitar que se repita el mensaje
        window.successMessage = undefined;
    }

// -----------------------------------------------------------------------------------------------

// Ordenación ------------------------------------------------------------------------------------

// select de la ordenacion
function sortRestaurants(selectElement) {
    const [sort, order] = selectElement.value.split('-');
    if (sort && order) {
        // Obtener todos los filtros actuales
        const formData = new FormData(document.getElementById('filters-form'));
        // Añadir parámetros de ordenación
        formData.append('sort', sort);
        formData.append('order', order);

        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const restaurantGrid = document.querySelector('.restaurant-grid');
        const paginationContainer = document.querySelector('.pagination');

        fetch('/restaurantes/filter', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
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
            const temp = document.createElement('div');
            temp.innerHTML = html;
            
            const newGrid = temp.querySelector('.restaurant-grid');
            const newPagination = temp.querySelector('.pagination');
            
            if (restaurantGrid && newGrid) {
                restaurantGrid.innerHTML = newGrid.innerHTML;
            }
            if (paginationContainer && newPagination) {
                paginationContainer.innerHTML = newPagination.innerHTML;
            }

            // Actualizar la URL con los parámetros de ordenación
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('sort', sort);
            currentUrl.searchParams.set('order', order);
            window.history.pushState({}, '', currentUrl);
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al ordenar los restaurantes.'
            });
        });
    }
}

// -----------------------------------------------------------------------------------------------



