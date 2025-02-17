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
    @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/css/crudUnificado.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.16.0/dist/sweetalert2.min.css">
</head>
<body>
    <header class="header">
        <img src="{{ asset('images/TheFork.png') }}" alt="Logo de The Fork" class="logo">
        <h1>Administración de Usuarios</h1>
        <div class="user-menu">
            <img src="{{ asset('images/user.png') }}" alt="Foto de usuario" class="user-logo">
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
                <a href="{{ route('usuarios.edit', $usuario) }}" class="button edit">EDITAR</a>
                <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="d-inline" id="formEliminar-{{ $usuario->id }}">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="button delete" onclick="confirmarEliminacion('{{ $usuario->id }}')">ELIMINAR</button>
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
    <script src="{{ asset('js/adminUsuarios.js') }}"></script>
</body>
</html>