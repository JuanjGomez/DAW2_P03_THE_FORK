<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Restaurantes</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/css/crudUnificado.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.16.0/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="header">
        <img src="{{ asset('images/TheFork.png') }}" alt="Logo de The Fork" class="logo">
        <h1>Zona Admin Restaurantes</h1>
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

    <div class="search-bar">
        <form action="{{ route('restaurantes.index') }}" method="GET">
            <input type="text" name="nombre" placeholder="Nombre del restaurante..." value="{{ request('nombre') }}">
            <input type="text" name="tipo_comida" placeholder="Tipo de comida..." value="{{ request('tipo_comida') }}">
            <input type="text" name="precio" placeholder="Precio" value="{{ request('precio') }}">
            <select name="municipio">
                <option value="">Municipio</option>
                @foreach($municipios as $municipio)
                    <option value="{{ $municipio }}" {{ request('municipio') == $municipio ? 'selected' : '' }}>{{ $municipio }}</option>
                @endforeach
            </select>
            <button type="submit" id="buscarRestaurante">BUSCAR</button>
            <a href="{{ route('restaurantes.index') }}" class="button" id="limpiarFiltros">LIMPIAR FILTROS</a>
        </form>
    </div>

    <div class="actions">
        <a href="#" class="button" id="crearRestaurante" data-bs-toggle="modal" data-bs-target="#crearRestauranteModal">CREAR RESTAURANTE</a>
        <a href="{{ route('usuarios.index') }}" class="button" id="verUsuarios">VER USUARIOS</a>
    </div>

    <div class="grid-container">
        @foreach($restaurantes as $restaurante)
        <div class="card restaurant-card">
            <h2>{{ $restaurante->nombre_r }}</h2>
            <img src="{{ asset('images/restaurantes/' . $restaurante->imagen) }}" alt="{{ $restaurante->nombre_r }}">
            <div class="card-actions">
                <a href="{{ route('restaurantes.edit', $restaurante->id) }}" class="button edit">EDITAR</a>
                <form id="formEliminar-{{ $restaurante->id }}" method="POST" action="{{ route('restaurantes.destroy', $restaurante->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="button delete" onclick="confirmarEliminacion('{{ $restaurante->id }}')">ELIMINAR</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <div class="pagination">
        {{ $restaurantes->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>

    <!-- SweetAlerts -->
    @if (session('success'))
        <script>
            window.successMessage = "{{ session('success') }}";
        </script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.16.0/dist/sweetalert2.all.min.js"></script>
    <script src="{{asset('js/adminRestaurantes.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Modal Crear Restaurante -->
    <div class="modal fade" id="crearRestauranteModal" tabindex="-1" aria-labelledby="crearRestauranteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="crearRestauranteModalLabel">Crear Nuevo Restaurante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('restaurantes.store') }}" enctype="multipart/form-data" id="crearRestauranteForm">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="nombre_r">NOMBRE</label>
                            <input type="text" id="nombre_r" name="nombre_r" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="direccion">DIRECCIÓN</label>
                            <input type="text" id="direccion" name="direccion" class="form-control">
                        </div>

                        <div class="form-group mb-3">
                            <label for="municipio">MUNICIPIO</label>
                            <input type="text" id="municipio" name="municipio" class="form-control">
                        </div>

                        <div class="form-group mb-3">
                            <label for="tipo_cocina_id">TIPO DE COMIDA</label>
                            <select id="tipo_cocina_id" name="tipo_cocina_id" class="form-select" required>
                                @foreach($tiposCocina as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="precio_promedio">PRECIO MEDIO POR PERSONA</label>
                            <input type="number" id="precio_promedio" name="precio_promedio" class="form-control" step="0.01" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="descripcion">DESCRIPCIÓN</label>
                            <textarea id="descripcion" name="descripcion" class="form-control"></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="imagen">IMAGEN</label>
                            <input type="file" id="imagen" name="imagen" class="form-control" accept="image/*" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" form="crearRestauranteForm" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

