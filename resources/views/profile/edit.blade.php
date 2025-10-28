@extends('layouts.app')

@section('content')
<div class="header d-flex justify-content-between align-items-center">
    <h3 class="m-0"><i class="fas fa-user-pen me-2"></i>Editar Perfil</h3>
</div>

<form action="{{ route('profile.update') }}" method="POST" class="card p-4 shadow-sm mt-3" style="border-left: 5px solid var(--accent-color);">
    @csrf
    @method('PUT')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h5 class="mt-2 text-secondary">Datos Personales</h5>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label><strong>Nombre</strong></label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $user->persona->nombre) }}" required>
            @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4 mb-3">
            <label><strong>Apellido</strong></label>
            <input type="text" name="apellido" class="form-control @error('apellido') is-invalid @enderror" value="{{ old('apellido', $user->persona->apellido) }}" required>
            @error('apellido')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4 mb-3">
            <label><strong>CI</strong></label>
            <input type="text" name="ci" class="form-control @error('ci') is-invalid @enderror" value="{{ old('ci', $user->persona->ci) }}" required>
            @error('ci')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label><strong>Teléfono</strong></label>
            <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono', $user->persona->telefono) }}">
            @error('telefono')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label><strong>Dirección</strong></label>
            <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion', $user->persona->direccion) }}">
            @error('direccion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <h5 class="mb-3 text-secondary">Datos del Usuario</h5>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label><strong>Nombre de Usuario</strong></label>
            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->name) }}" required>
            @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label><strong>Email</strong></label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label><strong>Nueva Contraseña (opcional)</strong></label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="col-md-6 mb-3">
            <label><strong>Confirmar Contraseña</strong></label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>
    </div>
    <div class="d-flex justify-content-between mt-3">
        <a href="{{ route('profile.index') }}" class="btn btn-secondary">
            <i class="fas fa-undo me-1"></i> Cancelar
        </a>

        <button class="btn btn-primary-custom">
            <i class="fas fa-save me-1"></i> Guardar Cambios
        </button>
    </div>

</form>
@endsection
