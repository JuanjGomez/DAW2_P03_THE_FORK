<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/css/formEditRestaurant.css', 'resources/js/app.js'])
    <title>Document</title>
</head>
<body>
    <header class="header">
        <img src="{{ asset('images/TheFork.png') }}" alt="Logo de The Fork" class="logo">
        <h1>Editar Restaurante</h1>
        <div class="user-menu">
            <img src="{{ asset('images/user.png') }}" alt="Foto de usuario" class="user-logo">
            <div class="dropdown-menu">
                <form method="POST" action="{{ route('logout') }}" class="dropdown-item">
                    @csrf
                    <button type="submit" class="logout-button">Cerrar sesión</button>
                </form>
            </div>
        </div>
    </header>
    <form method="POST" action="{{ route('restaurantes.update', $restaurante->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="text" name="nombre_r" value="{{ $restaurante->nombre_r }}" placeholder="Nombre del restaurante">
        <input type="text" name="direccion" value="{{ $restaurante->direccion }}" placeholder="Dirección">
        <input type="text" name="tipo_cocina" value="{{ $restaurante->tipoCocina->nombre }}" placeholder="Tipo de cocina">
        <input type="text" name="precio_promedio" value="{{ $restaurante->precio_promedio }}" placeholder="Precio medio">
        <textarea name="descripcion" placeholder="Descripción">{{ $restaurante->descripcion }}</textarea>
        <div class="file-upload"></div>
            <input type="file" name="imagen" id="file" class="file-input">
            <label for="file" class="file-label">
                <img src="{{ asset('images/upload-icon.png') }}" alt="Upload Icon" class="upload-icon">
                Subir Imagen
            </label>
        </div>
        <button type="submit">GUARDAR CAMBIOS</button>
    </form>

</body>
</html>
