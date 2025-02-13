<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.16.0/dist/sweetalert2.min.css">
    @vite(['resources/css/register.css'])
    <title>Registro - The Fork</title>
</head>
<body>
    <div class="container">
        <div class="logo-section">
            <img src="{{ asset('images/TheFork_blanco.png') }}" alt="The Fork Logo" class="logo">
            <img src="{{ asset('images/restaurante.png') }}" alt="Plato de comida" class="food-image">
        </div>
        
        <div class="form-section">
            <div class="form-container">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-group">
                        <label for="username">Nombre:</label>
                        <input type="text" name="username" id="username" placeholder="Introduce tu nombre">
                        <span class="error-message" id="errorUsername"></span>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" placeholder="ejemplo@correo.com">
                        <span class="error-message" id="errorEmail"></span>
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" name="password" id="password" placeholder="********">
                        <span class="error-message" id="errorPwd"></span>
                    </div>

                    <div class="form-group">
                        <label for="rPwd">Confirmar Contraseña:</label>
                        <input type="password" name="password_confirmation" id="rPwd" placeholder="********">
                        <span class="error-message" id="errorRpwd"></span>
                    </div>

                    <button type="submit" id="btnRegister" disabled>ENTRAR</button>
                </form>
                <div class="login-link">
                    <a href="{{ route('login') }}">¿Ya tienes cuenta? Inicia Sesión</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.16.0/dist/sweetalert2.all.min.js"></script>
    <script src="{{asset('js/formRegistro.js')}}"></script>
</body>
</html>
