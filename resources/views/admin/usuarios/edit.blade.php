<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Editar Usuario</h1>
        <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
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
            <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
        </form>
    </div>
</body>
</html>