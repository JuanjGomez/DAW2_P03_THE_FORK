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
                    // Eliminar la tarjeta del usuario del DOM
                    const userCard = document.querySelector(`.user-card[data-id="${id}"]`);
                    if (userCard) {
                        userCard.remove();
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
                    text: 'Hubo un problema al eliminar el usuario.'
                });
            });
        }
    });

    return false;
}

// Editar usuario
document.addEventListener('DOMContentLoaded', function() {
    const editarForms = document.querySelectorAll('[id^="editarUsuarioForm-"]');
    editarForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const userId = this.id.split('-')[1];
            const modal = document.querySelector(`#editarUsuarioModal-${userId}`);

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

                    // Actualizar la tarjeta del usuario
                    actualizarTarjetaUsuario(data.data);

                    // Mostrar mensaje de éxito
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
                    text: error.message || 'Ocurrió un error al actualizar el usuario'
                });
            });
        });
    });

    // Función para actualizar la tarjeta de un usuario
    function actualizarTarjetaUsuario(usuario) {
        const card = document.querySelector(`.user-card[data-id="${usuario.id}"]`);
        if (card) {
            card.querySelector('h2').textContent = usuario.username;
            card.querySelector('p:nth-of-type(1)').innerHTML = `<strong>Rol:</strong> ${usuario.rol.rol}`;
            card.querySelector('p:nth-of-type(2)').innerHTML = `<strong>Email:</strong> ${usuario.email}`;
        }
    }

    // Crear usuario
    const crearForm = document.getElementById('crearUsuarioForm');
    if (crearForm) {
        crearForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const modal = document.getElementById('crearUsuarioModal');

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

                    // Añadir la nueva tarjeta de usuario
                    agregarUsuarioALista(data.data);

                    // Mostrar mensaje de éxito
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
                    text: error.message || 'Ocurrió un error al crear el usuario'
                });
            });
        });
    }

    // Función para añadir un nuevo usuario a la lista
    function agregarUsuarioALista(usuario) {
        const gridContainer = document.querySelector('.grid-container');
        const card = document.createElement('div');
        card.className = 'card user-card';
        card.setAttribute('data-id', usuario.id);

        card.innerHTML = `
            <div class="user-info">
                <h2>${usuario.username}</h2>
                <p><strong>Rol:</strong> ${usuario.rol.rol}</p>
                <p><strong>Email:</strong> ${usuario.email}</p>
            </div>
            <div class="card-actions">
                <a href="#" class="button edit" data-bs-toggle="modal" data-bs-target="#editarUsuarioModal-${usuario.id}">EDITAR</a>
                <form id="formEliminar-${usuario.id}" method="POST" action="/usuarios/${usuario.id}">
                    <button type="button" class="button delete" onclick="confirmarEliminacion('${usuario.id}')">ELIMINAR</button>
                </form>
            </div>
        `;

        gridContainer.insertBefore(card, gridContainer.firstChild);
    }
}); 