<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zona Admin Restaurantes</title>
    <link rel="stylesheet" href="{{ asset('css/crudRestaurante.css') }}">
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
        <button>BUSCAR</button>
    </div>

    <div class="actions">
        <button>CREAR RESTAURANTE</button>
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
</body>
</html>

