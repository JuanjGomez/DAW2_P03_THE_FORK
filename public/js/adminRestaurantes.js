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



// ------------------------------------------------------------------------------------------------
