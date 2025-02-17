<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Crear Restaurante - The Fork</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Font Awesome para el icono de flecha -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Styles / Scripts -->
    @vite(['resources/css/createRestaurante.css', 'resources/js/createRestaurante.js'])
</head>
<body>
    <header class="header">
        <img src="{{ asset('images/TheFork.png') }}" alt="Logo de The Fork" class="logo">
        <h1>Crear Restaurante</h1>
        <div class="user-menu">
            <img src="{{ asset('images/user.png') }}" alt="Foto de usuario" class="user-logo">
            <div class="dropdown-menu">
                <a href="{{ route('perfil') }}" class="dropdown-item">Perfil</a>
                <form method="POST" action="{{ route('logout') }}" class="dropdown-item">
                    @csrf
                    <button type="submit" class="logout-button">Cerrar sesión</button>
                </form>
            </div>
        </div>
    </header>

    <div class="main-container">
        <div class="back-section">
            <a href="{{ route('restaurantes.index') }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>

        <div class="create-form">
            <form method="POST" action="{{ route('restaurantes.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="nombre_r">NOMBRE</label>
                    <input type="text" id="nombre_r" name="nombre_r" required>
                </div>

                <div class="form-group">
                    <label for="direccion">DIRECCIÓN</label>
                    <input type="text" id="direccion" name="direccion">
                </div>

                <div class="form-group">
                    <label for="municipio">MUNICIPIO</label>
                    <input type="text" id="municipio" name="municipio">
                </div>

                <div class="form-group">
                    <label for="tipo_cocina_id">TIPO DE COMIDA</label>
                    <select id="tipo_cocina_id" name="tipo_cocina_id" required>
                        @foreach($tiposCocina as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="precio_promedio">PRECIO MEDIO POR PERSONA</label>
                    <input type="number" id="precio_promedio" name="precio_promedio" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="descripcion">DESCRIPCIÓN</label>
                    <textarea id="descripcion" name="descripcion"></textarea>
                </div>

                <div class="form-group">
                    <label for="imagen">IMAGEN</label>
                    <input type="file" id="imagen" name="imagen" accept="image/*" required>
                </div>

                <div class="form-group">
                    <label for="manager_id">GERENTE</label>
                    <select id="manager_id" name="manager_id">
                        <option value="">Seleccionar gerente</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}">{{ $manager->username }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="submit-button">AÑADIR RESTAURANTE</button>
            </form>
        </div>
    </div>
    @include('partials.footer')
</body>
</html> 