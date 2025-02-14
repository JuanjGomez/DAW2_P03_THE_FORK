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
        </div>
    </header>
    <div class="salida">
        <a href="{{ route('restaurantes.index') }}"><img src="{{ asset('images/izquierda.png') }}" alt="Logo de The Fork" class="logo"></a>
    </div>
    <form method="POST" action="{{ route('restaurantes.update', $restaurante->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-container">
            <div class="image-upload">
                <div class="file-upload">
                    <input type="file" name="imagen" id="file" class="file-input">
                    <label for="file" class="file-label">
                        <img src="{{ asset('images/upload-icon.png') }}" alt="Upload Icon" class="upload-icon" id="previewImage">
                        Subir Imagen
                    </label>
                </div>
            </div>
            <div class="form-fields">
                <label for="nombre_r">Nombre del restaurante<br>
                    <input type="text" name="nombre_r" value="{{ $restaurante->nombre_r }}" placeholder="Nombre del restaurante">
                </label>
                <label for="direccion">Dirección<br>
                    <input type="text" name="direccion" value="{{ $restaurante->direccion }}" placeholder="Dirección">
                </label>
                <label for="tipo_cocina_id">Tipo de cocina<br>
                    <select name="tipo_cocina_id">
                        @foreach($tiposCocina as $tipo)
                            <option value="{{ $tipo->id }}" {{ $restaurante->tipo_cocina_id == $tipo->id ? 'selected' : '' }}>
                                {{ $tipo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <label for="precio_promedio">Precio medio<br>
                    <input type="text" name="precio_promedio" value="{{ $restaurante->precio_promedio }}" placeholder="Precio medio">
                </label>
                <label for="descripcion">Descripción<br>
                    <textarea name="descripcion" placeholder="Descripción">{{ $restaurante->descripcion }}</textarea>
                </label>
                <button type="submit">GUARDAR CAMBIOS</button>
            </div>
        </div>
    </form>
    <script src="{{ asset('js/formEditRestaurant.js') }}"></script>
</body>
</html>
