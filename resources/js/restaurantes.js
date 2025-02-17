document.addEventListener('DOMContentLoaded', function() {
    // Crear restaurante
    const crearForm = document.getElementById('crearRestauranteForm');
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
                    const crearModal = bootstrap.Modal.getInstance(document.getElementById('crearRestauranteModal'));
                    crearModal.hide();

                    // Limpiar el formulario
                    crearForm.reset();

                    // Agregar el nuevo restaurante dinámicamente
                    agregarRestauranteALista(data.data);
                } else {
                    console.error('Error:', data.message);
                }
            })
            .catch(error => {
                console.error('Error inesperado:', error);
            });
        });
    }

    // Editar restaurante
    const editarForms = document.querySelectorAll('[id^="editarRestauranteForm-"]');
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
                    const editarModal = bootstrap.Modal.getInstance(document.getElementById(`editarRestauranteModal-${data.data.id}`));
                    editarModal.hide();

                    // Actualizar la tarjeta del restaurante editado
                    actualizarTarjetaRestaurante(data.data);
                } else {
                    console.error('Error:', data.message);
                }
            })
            .catch(error => {
                console.error('Error inesperado:', error);
            });
        });
    });

    // Función para actualizar la lista de restaurantes
    function actualizarListaRestaurantes() {
        const gridContainer = document.querySelector('.grid-container');
        const url = gridContainer.getAttribute('data-url');

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            gridContainer.innerHTML = html;
        })
        .catch(error => {
            console.error('Error al actualizar la lista:', error);
        });
    }

    // Función para actualizar la tarjeta de un restaurante
    function actualizarTarjetaRestaurante(restaurante) {
        const card = document.querySelector(`.card[data-id="${restaurante.id}"]`);
        if (card) {
            card.querySelector('h2').textContent = restaurante.nombre_r;
            card.querySelector('img').src = `{{ asset('images/restaurantes') }}/${restaurante.imagen}`;
        }
    }

    // Función para agregar un restaurante a la lista
    function agregarRestauranteALista(restaurante) {
        const gridContainer = document.querySelector('.grid-container');

        // Crear la tarjeta del restaurante
        const card = document.createElement('div');
        card.className = 'card restaurant-card';
        card.setAttribute('data-id', restaurante.id);

        card.innerHTML = `
            <h2>${restaurante.nombre_r}</h2>
            <img src="{{ asset('images/restaurantes') }}/${restaurante.imagen}" alt="${restaurante.nombre_r}">
            <div class="card-actions">
                <a href="#" class="button edit" data-bs-toggle="modal" data-bs-target="#editarRestauranteModal-${restaurante.id}">EDITAR</a>
                <form id="formEliminar-${restaurante.id}" method="POST" action="{{ route('restaurantes.destroy', '${restaurante.id}') }}">
                    <button type="button" class="button delete" onclick="confirmarEliminacion('${restaurante.id}')">ELIMINAR</button>
                </form>
            </div>
        `;

        // Agregar el CSRF token y el método DELETE dinámicamente
        const form = card.querySelector(`#formEliminar-${restaurante.id}`);
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
}); 