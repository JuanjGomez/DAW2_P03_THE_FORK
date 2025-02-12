<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Registrarse</h1>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <label for="username">Nombre de usuario:</label>
            <input type="text" name="username" id="username" placeholder="joan123">
            <span id="errorUsername"></span>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" placeholder="example@gmail.com">
            <span id="errorEmail"></span>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="asdASD123">
            <span id="errorPwd"></span>
        </div>
        <div>
            <label for=rPwd>Confirmar Contrasena:</label>
            <input type="password" id="rPwd" name="rPrwd" placeholder="asdASD123">
            <span id="errorRpwd"></span>
        </div>
        <div>
            <button type="submit" id="btnRegister" disabled>Registrar</button>
        </div>
        @if ($errors->any())
            <div>
                <strong>{{ $errors->first() }}</strong>
            </div>
        @endif
    </form>
    <script src="{{asset('js/formRegistro.js')}}"></script>
</body>
</html>
