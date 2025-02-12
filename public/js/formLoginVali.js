// Validacion formulario -------------------------------------------------------------------------------------------

// Valida campo email
document.getElementById("email").oninput = function() {
    let email = this.value.trim()
    let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    let errorEmail = ""

    if (email.length === 0 || /^\s+$/.test(email) || email == null) {
        errorEmail = "El campo no puede estar vacío."
        this.style.border = "2px solid red"
    } else if (!regex.test(email)) {
        errorEmail = "El email no es válido."
        this.style.border = "2px solid red"
    } else {
        this.style.border = "2px solid green"
        errorEmail = ""
    }

    document.getElementById("errorEmail").textContent = errorEmail
    verificarForm()
}

// Valida campo password
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

    document.getElementById("errorPwd").textContent = errorPwd
    verificarForm()
};


// Valida si todo el formulario esta bien
function verificarForm() {
    let errores = [
        document.getElementById("errorEmail").textContent,
        document.getElementById("errorPwd").textContent,
    ]
    let campos = [
        document.getElementById("email").value.trim(),
        document.getElementById("password").value.trim(),
    ]

    const hayErrores = errores.some(error => error !== "")
    const camposVacios = campos.some(campo => campo === "")

    document.getElementById('btnSesion').disabled = hayErrores || camposVacios
}
// -----------------------------------------------------------------------------------------------------------------
