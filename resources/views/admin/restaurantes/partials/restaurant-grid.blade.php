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