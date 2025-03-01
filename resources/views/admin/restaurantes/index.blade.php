<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Restaurantes</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/css/crudUnificado.css', 'resources/js/app.js', 'resources/js/restaurantes.js'])
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
        <form id="filtroForm" action="{{ route('restaurantes.index') }}" method="GET">
            <input type="text" name="nombre" id="nombre" placeholder="Nombre del restaurante..." value="{{ request('nombre') }}">
            <input type="text" name="tipo_comida" id="tipo_comida" placeholder="Tipo de comida..." value="{{ request('tipo_comida') }}">
            <input type="text" name="precio" id="precio" placeholder="Precio" value="{{ request('precio') }}">
            <select name="municipio" id="municipio">
                <option value="">Municipio</option>
                @foreach($municipios as $municipio)
                    <option value="{{ $municipio }}" {{ request('municipio') == $municipio ? 'selected' : '' }}>{{ $municipio }}</option>
                @endforeach
            </select>
            <button type="button" id="buscarRestauranteAjax">BUSCAR</button>
            <a href="{{ route('restaurantes.index') }}" class="button" id="limpiarFiltros">LIMPIAR FILTROS</a>
        </form>
    </div>

    <div class="actions">
        <a href="#" class="button" id="crearRestaurante" data-bs-toggle="modal" data-bs-target="#crearRestauranteModal">CREAR RESTAURANTE</a>
        <a href="{{ route('usuarios.index') }}" class="button" id="verUsuarios">VER USUARIOS</a>
    </div>

    <div class="grid-container" id="resultadosRestaurantes" data-url="{{ route('restaurantes.index') }}">
        @include('admin.restaurantes._restaurantes_list', ['restaurantes' => $restaurantes])
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
    <script src="{{asset('js/restaurantes.js')}}"></script>
    <script src="{{ asset('js/adminRestaurantes.js') }}"></script>
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
                            <span class="error-message" id="errorNombre"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label for="direccion">DIRECCIÓN</label>
                            <input type="text" id="direccion" name="direccion" class="form-control">
                            <span class="error-message" id="errorDireccion"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label for="municipio">MUNICIPIO</label>
                            <input type="text" id="municipioR" name="municipio" class="form-control">
                            <span class="error-message" id="errorMunicipio"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label for="tipo_cocina_id">TIPO DE COMIDA</label>
                            <select id="tipo_cocina_id" name="tipo_cocina_id" class="form-select" required>
                                @foreach($tiposCocina as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                            <span class="error-message" id="errorTipoCocina"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label for="precio_promedio">PRECIO MEDIO POR PERSONA</label>
                            <input type="number" id="precio_promedioR" name="precio_promedio" class="form-control" step="0.01" required>
                            <span class="error-message" id="errorPrecioPromedio"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label for="descripcion">DESCRIPCIÓN</label>
                            <textarea id="descripcionR" name="descripcion" class="form-control"></textarea>
                            <span class="error-message" id="errorDescripcion"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label for="imagen">IMAGEN</label>
                            <input type="file" id="imagenR" name="imagen" class="form-control" accept="image/*" required>
                            <span class="error-message" id="errorImagen"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label for="manager_id">GERENTE</label>
                            <select id="manager_id" name="manager_id" class="form-control">
                                <option value="">Seleccionar gerente</option>
                                @foreach($managersDisponibles as $manager)
                                    <option value="{{ $manager->id }}">{{ $manager->username }}</option>
                                @endforeach
                            </select>
                            <span class="error-message" id="errorManager"></span>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" form="crearRestauranteForm" class="btn btn-primary" id="btnCrearRestaurante" disabled>Guardar</button>
                </div>
            </div>
        </div>
    </div>

    @foreach($restaurantes as $restaurante)
    <!-- Modal Editar Restaurante -->
    <div class="modal fade" id="editarRestauranteModal-{{ $restaurante->id }}" tabindex="-1" aria-labelledby="editarRestauranteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarRestauranteModalLabel">Editar Restaurante: {{ $restaurante->nombre_r }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('restaurantes.update', $restaurante->id) }}" enctype="multipart/form-data" id="editarRestauranteForm-{{ $restaurante->id }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label for="nombre_r">NOMBRE</label>
                            <input type="text" id="nombre_r" name="nombre_r" class="form-control" value="{{ $restaurante->nombre_r }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="direccion">DIRECCIÓN</label>
                            <input type="text" id="direccion" name="direccion" class="form-control" value="{{ $restaurante->direccion }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="municipio">MUNICIPIO</label>
                            <input type="text" id="municipio" name="municipio" class="form-control" value="{{ $restaurante->municipio }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="tipo_cocina_id">TIPO DE COMIDA</label>
                            <select id="tipo_cocina_id" name="tipo_cocina_id" class="form-select" required>
                                @foreach($tiposCocina as $tipo)
                                    <option value="{{ $tipo->id }}" {{ $restaurante->tipo_cocina_id == $tipo->id ? 'selected' : '' }}>
                                        {{ $tipo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="precio_promedio">PRECIO MEDIO POR PERSONA</label>
                            <input type="number" id="precio_promedio" name="precio_promedio" class="form-control" value="{{ $restaurante->precio_promedio }}" step="0.01" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="descripcion">DESCRIPCIÓN</label>
                            <textarea id="descripcion" name="descripcion" class="form-control">{{ $restaurante->descripcion }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="imagen">IMAGEN</label>
                            <input type="file" id="imagen" name="imagen" class="form-control" accept="image/*">
                            <small class="form-text text-muted">Deja en blanco para mantener la imagen actual</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="manager_id_{{ $restaurante->id }}">GERENTE</label>
                            <select id="manager_id_{{ $restaurante->id }}" name="manager_id" class="form-control">
                                <option value="">Seleccionar gerente</option>
                                @foreach($managersEdicion as $manager)
                                    <option value="{{ $manager->id }}"
                                        {{ $restaurante->manager_id == $manager->id ? 'selected' : '' }}>
                                        {{ $manager->username }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" form="editarRestauranteForm-{{ $restaurante->id }}" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</body>
</html>
