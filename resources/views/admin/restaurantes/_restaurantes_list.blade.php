@foreach($restaurantes as $restaurante)
<div class="card restaurant-card" data-id="{{ $restaurante->id }}">
    <h2>{{ $restaurante->nombre_r }}</h2>
    <img src="{{ asset('images/restaurantes/' . $restaurante->imagen) }}" alt="{{ $restaurante->nombre_r }}">
    <div class="card-actions">
        <a href="#" class="button edit" data-bs-toggle="modal" data-bs-target="#editarRestauranteModal-{{ $restaurante->id }}">EDITAR</a>
        <form id="formEliminar-{{ $restaurante->id }}" method="POST" action="{{ route('restaurantes.destroy', $restaurante->id) }}">
            @csrf
            @method('DELETE')
            <button type="button" class="button delete" onclick="confirmarEliminacion('{{ $restaurante->id }}')">ELIMINAR</button>
        </form>
    </div>
</div>
@endforeach
