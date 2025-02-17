<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.16.0/dist/sweetalert2.min.css" integrity="sha256-YiFT9lvNOGMbi29lCphiiB6iZOnEnj6SJ4R6Y1n8ukM=" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Principal</title>
    @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js'])
</head>
<body>
    @if (session('success'))
        <script>
            window.successMessage = "{{ session('success') }}";
        </script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.16.0/dist/sweetalert2.all.min.js" integrity="sha256-JxrPeaXEC22LUNm25PF02qeQ756a2XN/mxPJlfk9Lb8=" crossorigin="anonymous"></script>
    <script src="{{asset('js/toolsPrincipal.js')}}"></script>
    <script src="{{ asset('js/ajaxFilters.js') }}"></script>

    <!-- Header -->
    <header class="header">
        <img src="{{ asset('images/TheFork.png') }}" alt="Logo de The Fork" class="logo">
        <form id="filters-form" class="filters">
            <input type="text" name="nombre" id="nombre" placeholder="Nombre" class="filter-input" value="{{ request('nombre') }}">
            <input type="text" name="tipo_comida" id="tipo_comida" placeholder="Tipo" class="filter-input" value="{{ request('tipo_comida') }}">
            <div class="price-range">
                <input type="number" name="precio_min" id="precio_min" placeholder="Min" class="filter-input price-input" value="{{ request('precio_min') }}">
                <span>-</span>
                <input type="number" name="precio_max" id="precio_max" placeholder="Max" class="filter-input price-input" value="{{ request('precio_max') }}">
            </div>
            <select name="municipio" id="municipio" class="filter-input">
                <option value="">Municipio</option>
                @foreach($municipios as $municipio)
                    <option value="{{ $municipio }}" {{ request('municipio') == $municipio ? 'selected' : '' }}>{{ $municipio }}</option>
                @endforeach
            </select>
            <select name="valoracion_min" id="valoracion_min" class="filter-input">
                <option value="">⭐ Min</option>
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ request('valoracion_min') == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </form>
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

    <!-- Ordenación -->
    <div class="sorting-container">
        <div class="sort-buttons">
            <button type="button" class="sort-button" data-sort="precio_promedio" data-order="desc">
                <i class="fas fa-sort-amount-down"></i> Precio (Mayor a menor)
            </button>
            <button type="button" class="sort-button" data-sort="precio_promedio" data-order="asc">
                <i class="fas fa-sort-amount-up"></i> Precio (Menor a mayor)
            </button>
            <button type="button" class="sort-button" data-sort="rating" data-order="desc">
                <i class="fas fa-star"></i> Valoración (Mayor a menor)
            </button>
            <button type="button" class="sort-button" data-sort="rating" data-order="asc">
                <i class="fas fa-star"></i> Valoración (Menor a mayor)
            </button>
        </div>
    </div>

    <!-- Restaurantes -->
    <div class="restaurant-grid">
        @foreach($restaurantes as $restaurante)
            <a href="{{ route('restaurante', $restaurante->id) }}" class="restaurant-card">
                <img src="{{ asset('images/restaurantes/' . $restaurante->imagen) }}" alt="{{ $restaurante->nombre_r }}" class="restaurant-image">
                <div class="restaurant-info">
                    <div class="restaurant-type">{{ $restaurante->tipoCocina->nombre }}</div>
                    <div class="restaurant-name">{{ $restaurante->nombre_r }}</div>
                    <div class="restaurant-address">{{ $restaurante->direccion }}</div>
                    <div class="restaurant-price">Precio medio: {{ $restaurante->precio_promedio }}€</div>
                    <div class="restaurant-rating">
                        ⭐️ {{ $restaurante->ratings->avg('rating') ?? 0 }} ({{ $restaurante->ratings->count() }} comentarios)
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <div class="pagination">
        {{ $restaurantes->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</body>
</html>
