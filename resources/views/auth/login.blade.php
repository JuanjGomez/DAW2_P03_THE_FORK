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
    @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js'])
    <link rel="stylesshet" href="{{asset('css/')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.16.0/dist/sweetalert2.min.css" integrity="sha256-YiFT9lvNOGMbi29lCphiiB6iZOnEnj6SJ4R6Y1n8ukM=" crossorigin="anonymous">
    <title>Login</title>
</head>
<body style="font-family: 'Figtree', sans-serif; -webkit-font-smoothing: antialiased;">
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
            <script>
                let errorMessage = "{{$errors->first()}}";
            </script>
        @endif
    </form>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.16.0/dist/sweetalert2.all.min.js" integrity="sha256-JxrPeaXEC22LUNm25PF02qeQ756a2XN/mxPJlfk9Lb8=" crossorigin="anonymous"></script>
    <script src="{{asset('js/formLoginVali.js')}}"></script>
</body>
</html>
