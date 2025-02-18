// SweetAlerts -------------------------------------------------------------------------------------

// Alert de un buen inicio de sesion
if (window.successMessage) {
    Swal.fire({
        icon: 'success',
        title: window.successMessage,
        text: window.successMessage,
    });

    // Limpiar la variable successMessage para evitar que se repita el mensaje
    window.successMessage = undefined;
}

// Alert de confirmacion de eliminacion de restaurante
// Función para confirmar eliminación con SweetAlert2
function confirmarEliminacion(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: '¡No podrás revertir esto!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(`formEliminar-${id}`);
            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-HTTP-Method-Override': 'DELETE'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Eliminar la tarjeta del restaurante del DOM
                    const restaurantCard = document.querySelector(`.restaurant-card[data-id="${id}"]`);
                    if (restaurantCard) {
                        restaurantCard.remove();
                    }

                    Swal.fire({
                        icon: 'success',
                        title: '¡Eliminado!',
                        text: data.message
                    });
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al eliminar el restaurante.'
                });
            });
        }
    });

    return false;
}


// ------------------------------------------------------------------------------------------------

document.addEventListener('DOMContentLoaded', function() {
    // Crear restaurante
    const crearForm = document.getElementById('crearRestauranteForm');

    document.getElementById('nombre_r').oninput = function() {
        let nombre = this.value.trim();
        let errorNombre = "";

        if (nombre.length === 0 || /^\s+$/.test(nombre) || nombre == null) {
            errorNombre = "El nombre es obligatorio.";
            this.style.border = "2px solid red";
        } else if(nombre.length < 3 || nombre.length > 50) {
            errorNombre = "El nombre debe tener al menos 3 caracteres.";
            this.style.border = "2px solid red";
        } else {
            this.style.border = "2px solid green";
        }

        document.getElementById('errorNombre').textContent = errorNombre;
        verificarForm();
    }

    function verificarForm() {

    }

    if (crearForm) {
        crearForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const modal = document.getElementById('crearRestauranteModal');

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cerrar el modal
                    const bootstrapModal = bootstrap.Modal.getInstance(modal);
                    bootstrapModal.hide();

                    // Limpiar el formulario
                    this.reset();

                    // Añadir la nueva tarjeta de restaurante
                    agregarRestauranteALista(data.data);

                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: data.message
                    });
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Ocurrió un error al crear el restaurante'
                });
            });
        });
    }

    // Editar restaurante
    const editarForms = document.querySelectorAll('[id^="editarRestauranteForm-"]');
    editarForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const restauranteId = this.id.split('-')[1];
            const modal = document.querySelector(`#editarRestauranteModal-${restauranteId}`);

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cerrar el modal
                    const bootstrapModal = bootstrap.Modal.getInstance(modal);
                    bootstrapModal.hide();

                    // Actualizar la tarjeta del restaurante
                    actualizarTarjetaRestaurante(data.data);

                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: data.message
                    });
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Ocurrió un error al actualizar el restaurante'
                });
            });
        });
    });

    // Funciones auxiliares
    function agregarRestauranteALista(restaurante) {
        const gridContainer = document.querySelector('.grid-container');
        const card = document.createElement('div');
        card.className = 'card restaurant-card';
        card.setAttribute('data-id', restaurante.id);

        card.innerHTML = `
            <h2>${restaurante.nombre_r}</h2>
            <img src="/images/restaurantes/${restaurante.imagen}" alt="${restaurante.nombre_r}">
            <div class="card-actions">
                <a href="#" class="button edit" data-bs-toggle="modal" data-bs-target="#editarRestauranteModal-${restaurante.id}">EDITAR</a>
                <form id="formEliminar-${restaurante.id}" method="POST" action="/restaurantes/${restaurante.id}">
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="button" class="button delete" onclick="confirmarEliminacion('${restaurante.id}')">ELIMINAR</button>
                </form>
            </div>
        `;

        gridContainer.insertBefore(card, gridContainer.firstChild);
    }

    function actualizarTarjetaRestaurante(restaurante) {
        const card = document.querySelector(`.restaurant-card[data-id="${restaurante.id}"]`);
        if (card) {
            card.querySelector('h2').textContent = restaurante.nombre_r;
            card.querySelector('img').src = `/images/restaurantes/${restaurante.imagen}`;
            card.querySelector('img').alt = restaurante.nombre_r;
        }
    }
});
