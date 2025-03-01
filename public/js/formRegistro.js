// Validacion de Formulario -----------------------------------------------------------------------------------------

// Validacion de username
document.getElementById("username").oninput = function() {
    let username = this.value.trim()
    let usernameRegex = /^[a-zA-Z0-9]+$/;
    let errorUsername = ""

    if (username.length === 0 || /^\s+$/.test(username) || username == null) {
        errorUsername = "El campo no puede estar vacio."
        this.style.border = "2px solid red"
    } else if(!usernameRegex.test(username)) {
        errorUsername = "Solo puede tener letras y numeros."
        this.style.border = "2px solid red"
    } else if(username.length > 30) {
        errorUsername = "Debe tener al menos 30 caracteres."
        this.style.border = "2px solid green"
    } else {
        this.style.border = "2px solid green"
    }

    document.getElementById("errorUsername").innerHTML = errorUsername
    verificarForm()
}

// Validacion de email
document.getElementById("email").oninput = function() {
    let email = this.value.trim()
    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    let errorEmail = ""

    if (email.length === 0 || /^\s+$/.test(email) || email == null) {
        errorEmail = "El campo no puede estar vacio."
        this.style.border = "2px solid red"
    } else if(!emailRegex.test(email)) {
        errorEmail = "El email no es valido."
        this.style.border = "2px solid red"
    } else if(email.length > 120) {
        errorEmail = "Debe tener al menos 120 caracteres."
        this.style.border = "2px solid green"
    } else {
        this.style.border = "2px solid green"
    }

    document.getElementById("errorEmail").innerHTML = errorEmail
    verificarForm()
}

// Validacion de password
document
document.getElementById("password").oninput = function() {
    let password = this.value.trim()
    let passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    let errorPwd = ""

    if (password.length === 0 || /^\s+$/.test(password) || password == null) {
        errorPwd = "El campo no puede estar vacio."
        this.style.border = "2px solid red"
    } else if(!passwordRegex.test(password)) {
        errorPwd = "Debe tener al menos 8 caracteres, letras mayúscula, minúscula, numero y simbolo."
        this.style.border = "2px solid red"
    } else {
        this.style.border = "2px solid green"
    }

    document.getElementById("errorPwd").innerHTML = errorPwd
    verificarForm()
}

// Validacion de confirmacion de password
document.getElementById("rPwd").oninput = function() {
    let password = document.getElementById("password").value.trim()
    let confirmPassword = this.value.trim()
    let errorRpwd = ""

    if (confirmPassword.length === 0 || /^\s+$/.test(confirmPassword) || confirmPassword == null) {
        errorRpwd = "El campo no puede estar vacio."
        this.style.border = "2px solid red"
    } else if(confirmPassword !== password) {
        errorRpwd = "Las contraseñas no coinciden."
        this.style.border = "2px solid red"
    } else {
        this.style.border = "2px solid green"
    }

    document.getElementById("errorRpwd").innerHTML = errorRpwd
    verificarForm()
}

function verificarForm() {
    let errores = [
        document.getElementById("errorUsername").innerHTML,
        document.getElementById("errorEmail").innerHTML,
        document.getElementById("errorPwd").innerHTML,
        document.getElementById("errorRpwd").innerHTML,
    ]
    let campos = [
        document.getElementById("username").value.trim(),
        document.getElementById("email").value.trim(),
        document.getElementById("password").value.trim(),
        document.getElementById("rPwd").value.trim(),
    ]

    const hayErrores = errores.some(error => error !== "")
    const camposVacios = campos.some(campo => campo === "")

    document.getElementById('btnRegister').disabled = hayErrores || camposVacios
}

// ------------------------------------------------------------------------------------------------------------------

// SweetAlerts ------------------------------------------------------------------------------------------------------

// Alert para decir que ya existe un correo igual
if(typeof errorMessage !== 'undefined' && errorMessage !== "") {
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: errorMessage,
    })
}

// ------------------------------------------------------------------------------------------------------------------
