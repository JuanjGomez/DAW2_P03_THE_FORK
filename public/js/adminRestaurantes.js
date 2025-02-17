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
    console.log('Función confirmarEliminacion ejecutada para el ID:', id); // Depuración
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
            console.log('Confirmado, enviando formulario para el ID:', id); // Depuración
            document.getElementById(`formEliminar-${id}`).submit();
        }
    });

    // Evita que el formulario se envíe automáticamente
    return false;
}


// ------------------------------------------------------------------------------------------------
