@extends('layouts.app')

@section('content')
<div class="header d-flex justify-content-between align-items-center">
    <div>
        <h2 class="mb-0">Gestión de Personal</h2>
        <p class="text-muted mb-0">Lista de empleados y administradores del sistema</p>
    </div>
    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalRegistrar">
        <i class="fas fa-user-plus me-1"></i> Registrar Personal
    </button>
</div>

@if (session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif

<div class="card card-dashboard mt-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">Lista de Personal</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nombre de Usuario</th>
                        <th>Nombre Completo</th>
                        <th>CI</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Tipo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($personas as $p)
                        <tr>
                            <td>{{ $p->user?->name ?? '—' }}</td>
                            <td>{{ $p->nombre_completo }}</td>
                            <td>{{ $p->ci }}</td>
                            <td>{{ $p->email }}</td>
                            <td>{{ $p->telefono ?? '—' }}</td>
                            <td><span class="badge bg-info">{{ $p->tipoPersona->nombre_tipo ?? '—' }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">No hay personal registrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Registrar -->
<div class="modal fade @if($errors->any()) show @endif" id="modalRegistrar" tabindex="-1" aria-labelledby="modalRegistrarLabel" aria-hidden="{{ $errors->any() ? 'false' : 'true' }}" style="{{ $errors->any() ? 'display:block;' : '' }}">
  <div class="modal-dialog modal-lg">
    <form method="POST" action="{{ route('users.store') }}">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i> Registrar Personal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre de Usuario</label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellido</label>
                        <input type="text" name="apellido" class="form-control @error('apellido') is-invalid @enderror" value="{{ old('apellido') }}" required>
                        @error('apellido')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">CI</label>
                        <input type="text" name="ci" class="form-control @error('ci') is-invalid @enderror" value="{{ old('ci') }}" required>
                        @error('ci')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono') }}">
                        @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion') }}">
                        @error('direccion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tipo de Persona</label>
                        <select name="id_tipo_persona" class="form-select @error('id_tipo_persona') is-invalid @enderror" required>
                            <option value="">Seleccione...</option>
                            @foreach ($tipos as $tipo)
                                <option value="{{ $tipo->id_tipo_persona }}" {{ old('id_tipo_persona') == $tipo->id_tipo_persona ? 'selected' : '' }}>
                                    {{ $tipo->nombre_tipo }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_tipo_persona')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary-custom">Guardar</button>
            </div>
        </div>
    </form>
  </div>
</div>

@if($errors->any())
<script>
    var modal = new bootstrap.Modal(document.getElementById('modalRegistrar'));
    modal.show();
</script>
@endif
@endsection
