@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary-color: #2c3e50;
        --secondary-color: #34495e;
        --accent-color: #16a085;
        --light-color: #ecf0f1;
    }

    .table-dark th {
        background-color: var(--primary-color) !important;
        color: var(--light-color);
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: var(--light-color);
    }

    .badge-doliente {
        background-color: var(--accent-color);
        color: var(--light-color);
        font-weight: 500;
    }

    .btn-success {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
    }

    .btn-success:hover {
        background-color: #139673;
        border-color: #139673;
    }

    .modal-header.bg-success {
        background-color: var(--accent-color) !important;
    }

    .modal-header .btn-close-white {
        filter: invert(1);
    }
</style>

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
                            <td><span class="badge badge-doliente">{{ $cliente->tipoPersona->nombre_tipo }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">No hay clientes registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Agregar Cliente -->
<div class="modal fade" id="modalAgregarCliente" tabindex="-1" aria-labelledby="modalAgregarClienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('clientes.store') }}" method="POST" class="modal-content" id="clienteForm">
            @csrf
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalAgregarClienteLabel"><i class="fas fa-user-plus me-2"></i> Nuevo Cliente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
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
                <div class="col-md-6">
                    <label class="form-label">CI</label>
                    <input type="text" name="ci" class="form-control @error('ci') is-invalid @enderror" value="{{ old('ci') }}" required>
                    @error('ci')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono') }}">
                    @error('telefono')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion') }}">
                    @error('direccion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tipo de Persona</label>
                    <select name="id_tipo_persona" class="form-select" required>
                        @php
                            $tipos = \App\Models\TipoPersona::where('nombre_tipo', 'Doliente')->get();
                        @endphp
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id_tipo_persona }}" {{ old('id_tipo_persona') == $tipo->id_tipo_persona ? 'selected' : '' }}>
                                {{ $tipo->nombre_tipo }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success" id="btnGuardar">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('clienteForm');
    const modal = new bootstrap.Modal(document.getElementById('modalAgregarCliente'));
    const btnGuardar = document.getElementById('btnGuardar');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(form);
        formData.append('_token', '{{ csrf_token() }}');

        btnGuardar.disabled = true;
        btnGuardar.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...';

        fetch('{{ route("clientes.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                modal.hide();
                location.reload();
            } else {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger';
                alertDiv.innerHTML = data.message || 'Error al procesar el formulario.';
                form.querySelector('.modal-body').prepend(alertDiv);
                setTimeout(() => { alertDiv.remove(); }, 5000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger';
            alertDiv.innerHTML = 'Error inesperado al procesar la solicitud.';
            form.querySelector('.modal-body').prepend(alertDiv);
            setTimeout(() => { alertDiv.remove(); }, 5000);
        })
        .finally(() => {
            btnGuardar.disabled = false;
            btnGuardar.innerHTML = 'Guardar';
        });
    });

    document.getElementById('modalAgregarCliente').addEventListener('hidden.bs.modal', function() {
        form.reset();
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        form.querySelectorAll('.alert').forEach(el => el.remove());
    });
});
</script>
@endsection
