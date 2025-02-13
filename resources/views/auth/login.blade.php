<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/css/formLogin.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.16.0/dist/sweetalert2.min.css">
    <title>Login - The Fork</title>
</head>
<body id="body">
    <div class="login-container">
        <div class="images-container">
            <img src="{{ asset('images/TheFork_blanco.png') }}" alt="The Fork Logo" class="logo-login">
            <div class="circular-image">
                <img src="{{ asset('images/restaurante.png') }}" alt="Plato de comida">
            </div>
        </div>

        <div class="form-container">
            <form method="POST" action="{{ route('login') }}" class="login-form">
                @csrf
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" placeholder="example@gmail.com">
                    <span id="errorEmail" class="error-message"></span>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" name="password" id="password" placeholder="asdASD123">
                    <span id="errorPwd" class="error-message"></span>
                </div>

                <div class="form-group">
                    <button type="submit" id="btnSesion" disabled>ENTRAR</button>
                    <a href="{{ route('register') }}" class="register-link">¿No tienes una cuenta? Regístrate</a>
                </div>

                @if ($errors->any())
                    <script>
                        let errorMessage = "{{$errors->first()}}";
                    </script>
                @endif
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.16.0/dist/sweetalert2.all.min.js"></script>
    <script src="{{asset('js/formLoginVali.js')}}"></script>
</body>
</html>
