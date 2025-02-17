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
        const formData = new FormData();
        formData.append('sort', sort);
        formData.append('order', order);

        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        fetch('/filter-restaurants', {
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
            const restaurantGrid = document.querySelector('.restaurant-grid');
            if (restaurantGrid) {
                restaurantGrid.innerHTML = html;
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
}

// -----------------------------------------------------------------------------------------------



