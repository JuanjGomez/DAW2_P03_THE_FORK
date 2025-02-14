<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Perfil - The Fork</title>
    @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    @auth
        <!-- Header -->
        <header class="header">
            <a href="{{ route('principal') }}">
                <img src="{{ asset('images/TheFork.png') }}" alt="Logo de The Fork" class="logo">
            </a>
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

        <!-- Contenido principal -->
        <div class="profile-container">
            <h1 class="profile-title">Mi Perfil</h1>
            <form method="POST" action="{{ route('perfil.update') }}" class="profile-form">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="username">Nombre de usuario</label>
                    <input type="text" id="username" name="username" class="form-input" value="{{ auth()->user()->username }}" required>
                </div>
                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" class="form-input" value="{{ auth()->user()->email }}" required>
                </div>
                <button type="submit" class="profile-button">Guardar cambios</button>
            </form>
        </div>
    @else
        <script>window.location = "{{ route('login') }}";</script>
    @endauth
</body>
</html> 