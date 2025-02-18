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
}); 