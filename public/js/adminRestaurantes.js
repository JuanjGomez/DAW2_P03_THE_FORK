// SweetAlerts -------------------------------------------------------------------------------------

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
            // Si el usuario confirma, enviamos el formulario manualmente
            document.getElementById(`formEliminar-${id}`).submit();
        }
    });

    // Evita que el formulario se envíe automáticamente
    return false;
}


// ------------------------------------------------------------------------------------------------
