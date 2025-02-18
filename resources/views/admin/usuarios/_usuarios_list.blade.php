@foreach($usuarios as $usuario)
<div class="card user-card" data-id="{{ $usuario->id }}">
    <div class="user-info">
        <h2>{{ $usuario->username }}</h2>
        <p><strong>Rol:</strong> {{ $usuario->rol->rol }}</p>
        <p><strong>Email:</strong> {{ $usuario->email }}</p>
    </div>
    <div class="card-actions">
        <a href="#" class="button edit" data-bs-toggle="modal" data-bs-target="#editarUsuarioModal-{{ $usuario->id }}">EDITAR</a>
        <form id="formEliminar-{{ $usuario->id }}" method="POST" action="{{ route('usuarios.destroy', $usuario->id) }}">
            <button type="button" class="button delete" onclick="confirmarEliminacion('{{ $usuario->id }}')">ELIMINAR</button>
        </form>
    </div>
</div>
@endforeach