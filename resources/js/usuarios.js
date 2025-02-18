document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

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
                    'X-CSRF-TOKEN': csrfToken
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
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }

    // Eliminar usuario
    const eliminarButtons = document.querySelectorAll('.button.delete');
    eliminarButtons.forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.closest('.user-card').dataset.id;
            const modal = document.getElementById(`eliminarUsuarioModal-${userId}`);
            const form = modal.querySelector('form');

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-HTTP-Method-Override': 'DELETE'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Cerrar el modal
                        const bootstrapModal = bootstrap.Modal.getInstance(modal);
                        bootstrapModal.hide();

                        // Eliminar la tarjeta del usuario
                        const userCard = document.querySelector(`.card[data-id="${userId}"]`);
                        if (userCard) {
                            userCard.remove();
                        }

                        // Mostrar mensaje de éxito
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'Usuario eliminado correctamente'
                        });
                    } else {
                        console.error('Error:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error inesperado:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error inesperado'
                    });
                });
            });
        });
    });

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
                <form id="formEliminar-${usuario.id}" method="POST" action="{{ route('usuarios.destroy', '${usuario.id}') }}">
                    <button type="button" class="button delete" onclick="confirmarEliminacion('${usuario.id}')">ELIMINAR</button>
                </form>
            </div>
        `;

        gridContainer.prepend(card);
    }

    // Editar usuario
    const editarForms = document.querySelectorAll('[id^="editarUsuarioForm-"]');
    editarForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const modal = document.querySelector(`#editarUsuarioModal-${userId}`);

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
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
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Función para actualizar la tarjeta de un usuario
    function actualizarTarjetaUsuario(usuario) {
        const card = document.querySelector(`.card[data-id="${usuario.id}"]`);
        if (card) {
            card.querySelector('h2').textContent = usuario.username;
            card.querySelector('p:nth-of-type(1)').innerHTML = `<strong>Rol:</strong> ${usuario.rol.rol}`;
            card.querySelector('p:nth-of-type(2)').innerHTML = `<strong>Email:</strong> ${usuario.email}`;
        }
    }

    // Manejar la apertura del modal de creación
    document.getElementById('crearUsuario').addEventListener('click', function() {
        const modal = new bootstrap.Modal(document.getElementById('crearUsuarioModal'));
        modal.show();
    });

    // Manejar la apertura del modal de edición
    document.querySelectorAll('.button.edit').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.dataset.id;
            const modal = new bootstrap.Modal(document.getElementById(`editarUsuarioModal-${userId}`));
            modal.show();
        });
    });

    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');

    usernameInput.addEventListener('input', function() {
        validateUsername(this);
    });

    emailInput.addEventListener('input', function() {
        validateEmailInput(this);
    });

    passwordInput.addEventListener('input', function() {
        validatePassword(this);
    });

    passwordConfirmationInput.addEventListener('input', function() {
        validatePasswordConfirmation(this);
    });

    function validateUsername(input) {
        const errorSpan = document.getElementById('username-error');
        if (input.value.trim() === '') {
            errorSpan.textContent = 'El nombre de usuario es obligatorio.';
        } else {
            errorSpan.textContent = '';
        }
    }

    function validateEmailInput(input) {
        const errorSpan = document.getElementById('email-error');
        if (!validateEmail(input.value)) {
            errorSpan.textContent = 'Por favor, introduce un email válido.';
        } else {
            errorSpan.textContent = '';
        }
    }

    function validatePassword(input) {
        const errorSpan = document.getElementById('password-error');
        if (input.value.length < 8) {
            errorSpan.textContent = 'La contraseña debe tener al menos 8 caracteres.';
        } else {
            errorSpan.textContent = '';
        }
    }

    function validatePasswordConfirmation(input) {
        const errorSpan = document.getElementById('password-confirmation-error');
        const passwordInput = document.getElementById('password');
        if (input.value !== passwordInput.value) {
            errorSpan.textContent = 'Las contraseñas no coinciden.';
        } else {
            errorSpan.textContent = '';
        }
    }

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    const buscarBtn = document.getElementById('buscarUsuarioAjax');
    const limpiarBtn = document.getElementById('limpiarFiltrosUsuarios');
    
    if (buscarBtn) {
        buscarBtn.addEventListener('click', function(e) {
            e.preventDefault();
            aplicarFiltrosUsuarios();
        });
    }
    
    if (limpiarBtn) {
        limpiarBtn.addEventListener('click', limpiarFiltrosUsuarios);
    }

    function aplicarFiltrosUsuarios() {
        const form = document.getElementById('filtroFormUsuarios');
        const url = form.getAttribute('action');
        const params = new URLSearchParams(new FormData(form));
        
        fetch(`${url}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(html => {
            document.getElementById('resultadosUsuarios').innerHTML = html;
            updatePaginationLinksUsuarios();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un error al realizar la búsqueda');
        });
    }

    function limpiarFiltrosUsuarios(e) {
        e.preventDefault();
        window.location.href = e.target.href;
    }

    function updatePaginationLinksUsuarios() {
        document.querySelectorAll('.pagination a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                fetch(this.href, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    document.getElementById('resultadosUsuarios').innerHTML = html;
                    updatePaginationLinksUsuarios();
                })
                .catch(error => console.error('Error:', error));
            });
        });
    }
});
