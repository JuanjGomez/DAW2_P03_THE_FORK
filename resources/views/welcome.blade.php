<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>The Fork</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js'])
    </head>
    <body style="font-family: 'Figtree', sans-serif; -webkit-font-smoothing: antialiased;">
        <!-- Header -->
        <header class="header">
            <img src="{{ asset('images/TheFork.png') }}" alt="Logo de The Fork" class="logo">
            <a href="{{ route('home') }}" class="login-button">
                Iniciar Sesión
            </a>
        </header>

        <!-- Hero Section -->
        <div class="hero-section">
            <div class="hero-container">
                <div class="hero-content">
                    <div class="hero-text">
                        <h1 class="hero-title">Descubre y reserva el mejor restaurante</h1>
                        <div class="search-form">
                            <input type="text" placeholder="Localidad" class="search-input">
                            <input type="text" placeholder="Nombre del restaurante" class="search-input">
                            <a href="{{ route('login') }}">
                                <button class="search-button">
                                    Buscar
                                </button>
                            </a>
                        </div>
                    </div>
                    <img src="{{ asset('images/restaurante.png') }}" alt="Restaurante">
                </div>
            </div>
        </div>

        <!-- Popular Restaurants Section -->
        <div class="popular-restaurants">
            <h2 class="popular-title">Restaurantes populares en Barcelona</h2>
            <div class="restaurant-list">
                @foreach($restaurantes as $restaurante)
                    <a href="{{ route('login') }}" class="restaurant-item">
                        <img src="{{ asset('images/restaurantes/' . $restaurante->imagen) }}" alt="{{ $restaurante->nombre_r }}" class="restaurant-image">
                        <div class="restaurant-name">{{ $restaurante->nombre_r }}</div>
                    </a>
                @endforeach
            </div>
        </div>
    </body>
</html>
