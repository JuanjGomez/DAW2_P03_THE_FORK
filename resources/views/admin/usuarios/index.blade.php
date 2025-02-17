<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Usuarios</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/css/crudUnificado.css', 'resources/js/app.js', 'resources/js/usuarios.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.16.0/dist/sweetalert2.min.css">
</head>
<body>
    <header class="header">
        <img src="{{ asset('images/TheFork.png') }}" alt="Logo de The Fork" class="logo">
        <h1>Administración de Usuarios</h1>
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
        <form action="{{ route('usuarios.index') }}" method="GET">
            <input type="text" name="username" placeholder="Nombre de usuario..." value="{{ request('username') }}">
            <input type="text" name="email" placeholder="Email..." value="{{ request('email') }}">
            <select name="rol_id">
                <option value="">Rol</option>
                @foreach($roles as $rol)
                    <option value="{{ $rol->id }}" {{ request('rol_id') == $rol->id ? 'selected' : '' }}>{{ $rol->rol }}</option>
                @endforeach
            </select>
            <button type="submit" id="buscarUsuario">BUSCAR</button>
            <a href="{{ route('usuarios.index') }}" class="button" id="limpiarFiltros">LIMPIAR FILTROS</a>
        </form>
    </div>

    <div class="actions">
        <a href="{{ route('usuarios.create') }}" class="button" id="crearUsuario">CREAR USUARIO</a>
        <a href="{{ route('restaurantes.index') }}" class="button" id="verRestaurantes">VER RESTAURANTES</a>
    </div>

    <div class="grid-container">
        @foreach($usuarios as $usuario)
        <div class="card user-card">
            <div class="user-info">
                <h2>{{ $usuario->username }}</h2>
                <p><strong>Rol:</strong> {{ $usuario->rol->rol }}</p>
                <p><strong>Email:</strong> {{ $usuario->email }}</p>
            </div>
            <div class="card-actions">
                <a href="#" class="button edit" data-bs-toggle="modal" data-bs-target="#editarUsuarioModal-{{ $usuario->id }}">EDITAR</a>
                <form id="formEliminar-{{ $usuario->id }}" method="POST" action="{{ route('usuarios.destroy', $usuario->id) }}">
                    <button type="button" class="button delete" data-bs-toggle="modal" data-bs-target="#eliminarUsuarioModal-{{ $usuario->id }}">ELIMINAR</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Paginación -->
    <div class="pagination">
        {{ $usuarios->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>

    <!-- SweetAlerts -->
    @if (session('success'))
        <script>
            window.successMessage = "{{ session('success') }}";
        </script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.16.0/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('js/usuarios.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Modal Crear Usuario -->
    <div class="modal fade" id="crearUsuarioModal" tabindex="-1" aria-labelledby="crearUsuarioModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="crearUsuarioModalLabel">Crear Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="crearUsuarioForm" method="POST" action="{{ route('usuarios.store') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="username">Nombre de usuario</label>
                            <input type="text" name="username" id="username" class="form-control" required oninput="validateUsername(this)">
                            <span class="error-message" id="username-error"></span>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required oninput="validateEmailInput(this)">
                            <span class="error-message" id="email-error"></span>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password">Contraseña</label>
                            <input type="password" name="password" id="password" class="form-control" required oninput="validatePassword(this)">
                            <span class="error-message" id="password-error"></span>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password_confirmation">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required oninput="validatePasswordConfirmation(this)">
                            <span class="error-message" id="password-confirmation-error"></span>
                        </div>
                        <div class="form-group mb-3">
                            <label for="rol_id">Rol</label>
                            <select name="rol_id" id="rol_id" class="form-control" required>
                                @foreach($roles as $rol)
                                    <option value="{{ $rol->id }}">{{ $rol->rol }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" form="crearUsuarioForm" class="btn btn-primary">Crear Usuario</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Usuario -->
    @foreach($usuarios as $usuario)
    <div class="modal fade" id="editarUsuarioModal-{{ $usuario->id }}" tabindex="-1" aria-labelledby="editarUsuarioModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarUsuarioModalLabel">Editar Usuario: {{ $usuario->username }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editarUsuarioForm-{{ $usuario->id }}" method="POST" action="{{ route('usuarios.update', $usuario->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label for="username">Nombre de usuario</label>
                            <input type="text" name="username" id="username" class="form-control" value="{{ $usuario->username }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ $usuario->email }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password">Nueva Contraseña (dejar en blanco para mantener la actual)</label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="rol_id">Rol</label>
                            <select name="rol_id" id="rol_id" class="form-control" required>
                                @foreach($roles as $rol)
                                    <option value="{{ $rol->id }}" {{ $usuario->rol_id == $rol->id ? 'selected' : '' }}>{{ $rol->rol }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" form="editarUsuarioForm-{{ $usuario->id }}" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Modal Eliminar Usuario -->
    @foreach($usuarios as $usuario)
    <div class="modal fade" id="eliminarUsuarioModal-{{ $usuario->id }}" tabindex="-1" aria-labelledby="eliminarUsuarioModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eliminarUsuarioModalLabel">Eliminar Usuario: {{ $usuario->username }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar este usuario?</p>
                    <form method="POST" action="{{ route('usuarios.destroy', $usuario->id) }}" id="formEliminar-{{ $usuario->id }}">
                        @csrf
                        @method('DELETE')
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: "{{ session('success') }}"
        });
    </script>
    @endif
</body>
</html>
