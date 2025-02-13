<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.16.0/dist/sweetalert2.min.css" integrity="sha256-YiFT9lvNOGMbi29lCphiiB6iZOnEnj6SJ4R6Y1n8ukM=" crossorigin="anonymous">
    <title>Principal</title>
    @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js'])
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

    <!-- Header -->
    <header class="header">
        <img src="{{ asset('images/TheFork.png') }}" alt="Logo de The Fork" class="logo">
        <div class="filters">
            <input type="text" placeholder="Nombre del restaurante" class="filter-input">
            <select class="filter-input">
                <option value="">Tipo de comida</option>
                <!-- Aquí puedes agregar opciones dinámicas desde la base de datos -->
            </select>
            <select class="filter-input">
                <option value="">Precio</option>
                <option value="1">€</option>
                <option value="2">€€</option>
                <option value="3">€€€</option>
            </select>
            <input type="text" placeholder="Municipio" class="filter-input">
        </div>
        <img src="{{ asset('images/user.png') }}" alt="Foto de usuario" class="user-logo">
    </header>

    <!-- Restaurantes -->
    <div class="restaurant-grid">
        @foreach($restaurantes as $restaurante)
            <a href="{{ route('restaurante', $restaurante->id) }}" class="restaurant-card">
                <img src="{{ asset('images/restaurantes/' . $restaurante->imagen) }}" alt="{{ $restaurante->nombre_r }}" class="restaurant-image">
                <div class="restaurant-info">
                    <div class="restaurant-type">{{ $restaurante->tipoCocina->nombre }}</div>
                    <div class="restaurant-name">{{ $restaurante->nombre_r }}</div>
                    <div class="restaurant-address">{{ $restaurante->direccion }}</div>
                    <div class="restaurant-price">Precio medio: {{ $restaurante->precio_promedio }}€</div>
                    <div class="restaurant-rating">
                        ⭐️ {{ $restaurante->ratings->avg('rating') ?? 0 }} ({{ $restaurante->ratings->count() }} comentarios)
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</body>
</html>
