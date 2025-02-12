<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form method="POST" action="{{ route('login') }}">
        @csrf
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
            <button type="submit" id="btnSesion" disabled>Login</button>
        </div>
        @if ($errors->any())
            <div>
                <strong>{{ $errors->first() }}</strong>
            </div>
        @endif
    </form>
    <script src="{{asset('js/formLoginVali.js')}}"></script>
</body>
</html>
