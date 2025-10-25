@extends('layouts.app')

@section('content')
<div class="header d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0"><i class="fas fa-user-circle me-2"></i>Mi Perfil</h3>
</div>

@if(session('success'))
<div class="alert alert-success mt-2">{{ session('success') }}</div>
@endif

<div class="card shadow-sm p-4 mt-2" style="border-left: 6px solid var(--accent-color);">
    <div class="text-center mb-4">
        <i class="fas fa-user-circle" style="font-size: 70px; color: var(--primary-color);"></i>
        <h4 class="mt-2 mb-0">{{ $user->persona->nombre }} {{ $user->persona->apellido }}</h4>
        <small class="text-muted">{{ $user->persona->tipoPersona->nombre_tipo }}</small>
    </div>

    <hr>
    <div class="row">
        <div class="col-md-6 border-end">
            <h5 class="text-secondary mb-3">
                <i class="fas fa-id-card me-2"></i>Información Personal
            </h5>

            <p><strong>CI:</strong> {{ $user->persona->ci }}</p>
            <p><strong>Teléfono:</strong> {{ $user->persona->telefono ?? '—' }}</p>
            <p><strong>Dirección:</strong> {{ $user->persona->direccion ?? '—' }}</p>
        </div>
        <div class="col-md-6">
            <h5 class="text-secondary mb-3">
                <i class="fas fa-user-shield me-2"></i>Cuenta del Sistema
            </h5>
            <p><strong>Usuario:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
        </div>
    </div>
    <hr>
    <div class="text-center mt-2">
        <h5 class="text-secondary mb-2">
            <i class="fas fa-shield-alt me-2"></i>Rol en el Sistema
        </h5>
        <p class="text-muted" style="font-size: 0.9rem;">
            {{ $user->persona->tipoPersona->descripcion }}
        </p>
    </div>
    <div class="text-end">
        <a href="{{ route('profile.edit') }}" class="btn btn-primary-custom mt-3">
            <i class="fas fa-edit me-1"></i> Editar Perfil
        </a>
    </div>

</div>
@endsection
