<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $restaurante->nombre_r }} - The Fork</title>
    @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js', 'resources/js/rating.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <a href="{{ route('principal') }}">
            <img src="{{ asset('images/TheFork.png') }}" alt="Logo de The Fork" class="logo">
        </a>
        <h1 class="restaurant-title">{{ $restaurante->nombre_r }}</h1>
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

    <div class="salida">
        <a href="{{ route('principal') }}"><img src="{{ asset('images/izquierda.png') }}" alt="Logo de The Fork" class="logo"></a>
    </div>

    <!-- Contenido principal -->
    <div class="restaurant-container">
        <!-- Imagen del restaurante -->
        <div class="restaurant-image-container">
            <img src="{{ asset('images/restaurantes/' . $restaurante->imagen) }}" alt="{{ $restaurante->nombre_r }}" class="restaurant-large-image">
        </div>

        <!-- Información del restaurante -->
        <div class="restaurant-info-container">
            <h2 class="restaurant-name">{{ $restaurante->nombre_r }}</h2>
            <p class="restaurant-type">Tipo de comida: {{ $restaurante->tipoCocina->nombre }}</p>
            <p class="restaurant-address">Dirección: {{ $restaurante->direccion }}</p>
            <p class="restaurant-price">Precio medio: {{ $restaurante->precio_promedio }}€</p>
            <div class="restaurant-rating">
                <span>Valoración: ⭐️ {{ $restaurante->ratings->avg('rating') ?? 0 }} ({{ $restaurante->ratings->count() }} comentarios)</span>
            </div>
            <div class="rating-system">
                <h3>Valora este restaurante:</h3>
                <form method="POST" action="{{ route('restaurante.rate', $restaurante->id) }}" id="rating-form">
                    @csrf
                    @if($userRating)
                        @method('PUT')
                    @endif
                    <div class="rating-inputs">
                        <textarea name="comentario" placeholder="Escribe tu comentario..." class="comment-input">{{ $userRating->comentario ?? '' }}</textarea>
                        <div class="stars">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}"
                                    {{ $userRating && $userRating->rating == $i ? 'checked' : '' }} />
                                <label for="star{{ $i }}" title="{{ $i }} estrellas">&#9733;</label>
                            @endfor
                        </div>
                    </div>
                    <div class="rating-buttons">
                        <button type="submit" class="submit-rating">Enviar valoración y comentario</button>
                    </div>
                </form>
            </div>
            <p class="restaurant-description">{{ $restaurante->descripcion }}</p>
        </div>
    </div>

    <!-- Opiniones -->
    <div class="reviews-container">
        <h2>Opiniones</h2>
        @foreach($restaurante->ratings as $rating)
            <div class="review">
                <div class="review-header">
                    <p class="review-user">{{ $rating->usuario->username }}</p>
                    <div class="review-stars">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $rating->rating)
                                <span class="star-filled">&#9733;</span>
                            @else
                                <span class="star-empty">&#9733;</span>
                            @endif
                        @endfor
                    </div>
                </div>
                <p class="review-date">{{ $rating->created_at->format('d/m/Y') }}</p>
                <p class="review-comment">{{ $rating->comentario }}</p>
            </div>
        @endforeach
    </div>
</body>
</html>
