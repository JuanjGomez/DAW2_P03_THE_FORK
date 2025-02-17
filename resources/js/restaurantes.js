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

                    // Actualizar la lista de restaurantes (opcional)
                    actualizarListaRestaurantes();
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
        fetch("{{ route('restaurantes.index') }}", {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            document.querySelector('.grid-container').innerHTML = html;
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
}); 