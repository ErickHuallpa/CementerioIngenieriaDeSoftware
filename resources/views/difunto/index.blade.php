@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-book me-2"></i> Registro de Difuntos</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRegistrarDifunto">
            <i class="fas fa-plus-circle me-1"></i> Nuevo Registro
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nombre Difunto</th>
                        <th>Doliente</th>
                        <th>Nicho</th>
                        <th>Fecha Fallecimiento</th>
                        <th>Fecha Entierro</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($difuntos as $difunto)
                        <tr>
                            <td>{{ $difunto->id_difunto }}</td>
                            <td>{{ $difunto->persona->nombreCompleto ?? '—' }}</td>
                            <td>{{ $difunto->doliente->nombreCompleto ?? '—' }}</td>
                            <td>{{ $difunto->nicho->pabellon->nombre ?? '—' }} - F{{ $difunto->nicho->fila }}C{{ $difunto->nicho->columna }}</td>
                            <td>{{ $difunto->fecha_fallecimiento }}</td>
                            <td>{{ $difunto->fecha_entierro }}</td>
                            <td><span class="badge bg-success">{{ ucfirst(str_replace('_', ' ', $difunto->estado)) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">No hay difuntos registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRegistrarDifunto" tabindex="-1" aria-labelledby="modalRegistrarDifuntoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('difunto.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fas fa-cross me-2"></i> Nuevo Registro de Difunto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre del Difunto</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Apellido</label>
                    <input type="text" name="apellido" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">CI (Opcional)</label>
                    <input type="text" name="ci" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Doliente</label>
                    <select name="id_doliente" class="form-select" required>
                        <option value="">Seleccione...</option>
                        @foreach($dolientes as $doliente)
                            <option value="{{ $doliente->id_persona }}">{{ $doliente->nombreCompleto }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nicho</label>
                    <select name="id_nicho" class="form-select" required>
                        <option value="">Seleccione...</option>
                        @foreach($nichosDisponibles as $nicho)
                            <option value="{{ $nicho->id_nicho }}">
                                {{ $nicho->pabellon->nombre ?? 'Sin Pabellón' }} - F{{ $nicho->fila }}C{{ $nicho->columna }}
                                (Bs. {{ $nicho->costo_alquiler ?? '0' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Fecha de Fallecimiento</label>
                    <input type="date" name="fecha_fallecimiento" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Trabajador Encargado</label>
                    <select name="id_trabajador" class="form-select" required>
                        <option value="">Seleccione...</option>
                        @foreach($trabajadores as $trabajador)
                            <option value="{{ $trabajador->id_persona }}">{{ $trabajador->nombreCompleto }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Fecha de Entierro (automática)</label>
                    <input type="text" class="form-control" value="{{ date('Y-m-d', strtotime('+1 day')) }}" disabled>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success">Registrar</button>
            </div>
        </form>
    </div>
</div>
@endsection
