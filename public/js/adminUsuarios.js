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
            // Si el usuario confirma, enviamos el formulario manualmente
            document.getElementById(`formEliminar-${id}`).submit();
        }
    });

    // Evita que el formulario se envíe automáticamente
    return false;
}

document.addEventListener('DOMContentLoaded', function() {
    // Crear usuario
    const crearForm = document.getElementById('crearUsuarioForm');
    if (crearForm) {
        crearForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cerrar el modal de creación
                    const crearModal = bootstrap.Modal.getInstance(document.getElementById('crearUsuarioModal'));
                    crearModal.hide();

                    // Limpiar el formulario
                    crearForm.reset();

                    // Agregar el nuevo usuario dinámicamente
                    agregarUsuarioALista(data.data);
                } else {
                    console.error('Error:', data.message);
                }
            })
            .catch(error => {
                console.error('Error inesperado:', error);
            });
        });
    }

    // Editar usuario
    const editarForms = document.querySelectorAll('[id^="editarUsuarioForm-"]');
    editarForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-HTTP-Method-Override': 'PUT'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cerrar el modal de edición
                    const editarModal = bootstrap.Modal.getInstance(document.getElementById(`editarUsuarioModal-${data.data.id}`));
                    editarModal.hide();

                    // Actualizar la tarjeta del usuario editado
                    actualizarTarjetaUsuario(data.data);
                } else {
                    console.error('Error:', data.message);
                }
            })
            .catch(error => {
                console.error('Error inesperado:', error);
            });
        });
    });

    // Función para agregar un usuario a la lista
    function agregarUsuarioALista(usuario) {
        const gridContainer = document.querySelector('.grid-container');

        // Crear la tarjeta del usuario
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

        // Agregar el CSRF token y el método DELETE dinámicamente
        const form = card.querySelector(`#formEliminar-${usuario.id}`);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';

        form.appendChild(csrfInput);
        form.appendChild(methodInput);

        // Agregar la tarjeta al contenedor
        gridContainer.prepend(card);
    }

    // Función para actualizar la tarjeta de un usuario
    function actualizarTarjetaUsuario(usuario) {
        const card = document.querySelector(`.card[data-id="${usuario.id}"]`);
        if (card) {
            card.querySelector('h2').textContent = usuario.username;
            card.querySelector('p:nth-of-type(1)').innerHTML = `<strong>Rol:</strong> ${usuario.rol.rol}`;
            card.querySelector('p:nth-of-type(2)').innerHTML = `<strong>Email:</strong> ${usuario.email}`;
        }
    }
}); 