document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // Crear usuario
    document.getElementById('usernameCrear').oninput = function() {
        let username = this.value.trim()
        let usernameError = ""

        if (username.length === 0 || /^\s+$/.test(username) || username == null) {
            usernameError = "El nombre de usuario es obligatorio."
            this.style.border = "2px solid red"
        } else if (username.length < 3 || username.length > 20) {
            usernameError = "El nombre de usuario debe tener entre 3 y 20 caracteres."
            this.style.border = "2px solid red"
        } else if (!/^[a-zA-Z]+$/.test(username)) {
            usernameError = "El nombre de usuario solo puede contener letras."
            this.style.border = "2px solid red"
        } else {
            usernameError = ""
            this.style.border = "2px solid green"
        }

        document.getElementById("errorUsername").textContent = usernameError
        verificarForm()
    }

    document.getElementById("emailCrear").oninput = function() {
        let email = this.value.trim()
        let emailError = ""

        if (email.length === 0 || /^\s+$/.test(email) || email == null) {
            emailError = "El email es obligatorio."
            this.style.border = "2px solid red"
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            emailError = "El email no es válido."
            this.style.border = "2px solid red"
        } else {
            emailError = ""
            this.style.border = "2px solid green"
        }

        document.getElementById("errorEmail").textContent = emailError
        verificarForm()
    }

    document.getElementById("password").oninput = function() {
        let password = this.value.trim()
        let regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/
        let errorPwd = ""

        if (password.length === 0 || /^\s+$/.test(password) || password == null) {
            errorPwd = "El campo no puede estar vacío."
            this.style.border = "2px solid red"
        } else if (!regex.test(password)) {
            errorPwd = "Debe tener 8 caracteres, mayúscula, minúscula, número y carácter especial."
            this.style.border = "2px solid red"
        } else {
            this.style.border = "2px solid green"
            errorPwd = ""
        }

        document.getElementById("errorPassword").textContent = errorPwd
        verificarForm()
    }

    document.getElementById("password_confirmation").oninput = function() {
        let passwordConfirmation = this.value.trim()
        let password = document.getElementById("password").value.trim()
        let errorPasswordConfirmation = ""

        if(passwordConfirmation.length === 0 || /^\s+$/.test(passwordConfirmation) || passwordConfirmation == null){
            errorPasswordConfirmation = "La confirmación de contraseña es obligatoria."
            this.style.border = "2px solid red"
        } else if(passwordConfirmation !== password){
            errorPasswordConfirmation = "Las contraseñas no coinciden."
            this.style.border = "2px solid red"
        } else {
            this.style.border = "2px solid green"
            errorPasswordConfirmation = ""
        }

        document.getElementById("errorPasswordConfirmation").textContent = errorPasswordConfirmation
        verificarForm()
    }

    document.getElementById("rol_idCrear").onchange = function() {
        let rolId = this.value
        let errorRolId = ""

        if (rolId.length === 0 || /^\s+$/.test(rolId) || rolId == null) {
            errorRolId = "El rol es obligatorio."
            this.style.border = "2px solid red"
        } else {
            this.style.border = "2px solid green"
            errorRolId = ""
        }

        document.getElementById("errorRolId").textContent = errorRolId
        verificarForm()
    }

    function verificarForm() {
        let errores = [
            document.getElementById("errorUsername").textContent,
            document.getElementById("errorEmail").textContent,
            document.getElementById("errorPassword").textContent,
            document.getElementById("errorPasswordConfirmation").textContent,
            document.getElementById("errorRolId").textContent
        ]
        let campos = [
            document.getElementById("usernameCrear").value.trim(),
            document.getElementById("emailCrear").value.trim(),
            document.getElementById("password").value.trim(),
            document.getElementById("password_confirmation").value.trim(),
            document.getElementById("rol_idCrear").value.trim()
        ]

        const hayErrores = errores.some(error => error !== "")
        const camposVacios = campos.some(campo => campo === "")

        document.getElementById('btnCrearUsuario').disabled = hayErrores || camposVacios
    }

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
                console.log('Response data:', data);
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

        // Envío del formulario usando fetch
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
