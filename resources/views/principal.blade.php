<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.16.0/dist/sweetalert2.min.css" integrity="sha256-YiFT9lvNOGMbi29lCphiiB6iZOnEnj6SJ4R6Y1n8ukM=" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <form method="post" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Cerrar Sesión</button>
    </form>
    @if (session('success'))
        <script>
            window.successMessage = "{{ session('success') }}";
        </script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.16.0/dist/sweetalert2.all.min.js" integrity="sha256-JxrPeaXEC22LUNm25PF02qeQ756a2XN/mxPJlfk9Lb8=" crossorigin="anonymous"></script>
    <script src="{{asset('js/toolsPrincipal.js')}}"></script>
</body>
</html>
