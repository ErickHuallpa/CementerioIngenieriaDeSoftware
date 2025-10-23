@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-users me-2"></i> Gestión de Clientes</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarCliente">
            <i class="fas fa-plus-circle me-1"></i> Agregar Cliente
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre Completo</th>
                        <th>CI</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Email</th>
                        <th>Tipo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->nombre }} {{ $cliente->apellido }}</td>
                            <td>{{ $cliente->ci }}</td>
                            <td>{{ $cliente->telefono ?? '—' }}</td>
                            <td>{{ $cliente->direccion ?? '—' }}</td>
                            <td>{{ $cliente->email ?? '—' }}</td>
                            <td><span class="badge bg-info text-dark">{{ $cliente->tipoPersona->nombre_tipo }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">No hay clientes registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAgregarCliente" tabindex="-1" aria-labelledby="modalAgregarClienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('clientes.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalAgregarClienteLabel"><i class="fas fa-user-plus me-2"></i> Nuevo Cliente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Apellido</label>
                    <input type="text" name="apellido" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">CI</label>
                    <input type="text" name="ci" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" name="email" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tipo de Persona</label>
                    <select name="id_tipo_persona" class="form-select" required>
                        @php
                            $tipos = \App\Models\TipoPersona::whereIn('nombre_tipo', ['Doliente', 'Visitante'])->get();
                        @endphp
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id_tipo_persona }}">{{ $tipo->nombre_tipo }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection
