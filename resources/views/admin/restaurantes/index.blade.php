<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Zona Admin Restaurantes</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/css/crudRestaurante.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.16.0/dist/sweetalert2.min.css">
</head>
<body>
    <header class="header">
        <img src="{{ asset('images/TheFork.png') }}" alt="Logo de The Fork" class="logo">
        <h1>Zona Admin Restaurantes</h1>
        <div class="user-menu">
            <img src="{{ asset('images/user.png') }}" alt="Foto de usuario" class="user-logo">
        </div>
    </header>

    <div class="search-bar">
        <input type="text" placeholder="Nombre del restaurante...">
        <input type="text" placeholder="Tipo de comida...">
        <input type="text" placeholder="Precio">
        <select>
            <option value="">Municipio</option>
        </select>
        <button id="buscarRestaurante">BUSCAR</button>
    </div>

    <div class="actions">
        <a href="{{ route('restaurantes.create') }}" class="button" id="crearRestaurante">CREAR RESTAURANTE</a>
        <button>VER USUARIOS</button>
    </div>

    <div class="restaurant-grid">
        @foreach($restaurantes as $restaurante)
        <div class="restaurant-card">
            <h2>{{ $restaurante->nombre_r }}</h2>
            <img src="{{ asset('images/restaurantes/' . $restaurante->imagen) }}" alt="{{ $restaurante->nombre_r }}">
            <div class="restaurant-actions">
                <button>EDITAR</button>
                <button>ELIMINAR</button>
            </div>
        </div>
    @endforeach
    </div>

    <!-- SweetAlerts -->
    @if (session('success'))
        <script>
            window.successMessage = "{{ session('success') }}";
        </script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.16.0/dist/sweetalert2.all.min.js"></script>
    <script src="{{asset('js/adminRestaurantes.js')}}"></script>
</body>
</html>

