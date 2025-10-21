@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-fire me-2"></i> Incineraciones</h2>
        <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalRegistrarIncineracion">
            <i class="fas fa-plus-circle me-1"></i> Registrar Incineración
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
                        <th>Difunto</th>
                        <th>Responsable</th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>Costo</th>
                        <th>Registrado por</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($incineraciones as $inc)
                        <tr>
                            <td>{{ $inc->id_incineracion }}</td>
                            <td>{{ $inc->difunto->persona->nombre_completo ?? ($inc->difunto->persona->nombre ?? '—') }}</td>
                            <td>{{ $inc->responsable->nombre_completo ?? '—' }}</td>
                            <td>{{ ucfirst($inc->tipo) }}</td>
                            <td>{{ $inc->fecha_incineracion }}</td>
                            <td>Bs. {{ number_format($inc->costo, 2) }}</td>
                            <td>
                                {{-- intentar mostrar usuario si se guardó en el servicio o incineracion --}}
                                {{ optional($inc->created_at) ? $inc->created_at->format('Y-m-d') : '—' }}
                            </td>
                            <td>
                                <a href="{{ route('incineracion.index') }}?download={{ $inc->id_incineracion }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download"></i> PDF
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted">No hay registros de incineraciones.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Registrar Incineración -->
<div class="modal fade" id="modalRegistrarIncineracion" tabindex="-1" aria-labelledby="modalRegistrarIncineracionLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('incineracion.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fas fa-fire me-2"></i> Registrar Incineración</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body row g-3">
                <div class="col-md-6">
                    <label class="form-label">Difunto</label>
                    <select name="id_difunto" class="form-select" required>
                        <option value="">Seleccione...</option>
                        @foreach($difuntos as $d)
                            <option value="{{ $d->id_difunto }}">
                                #{{ $d->id_difunto }} — {{ $d->persona->nombre_completo ?? ($d->persona->nombre ?? '—') }}
                                (Estado: {{ $d->estado }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Responsable (Tipo: Responsable de Incineración)</label>
                    <select name="id_responsable" class="form-select" required>
                        <option value="">Seleccione...</option>
                        @foreach($trabajadores as $t)
                            <option value="{{ $t->id_persona }}">{{ $t->nombre_completo ?? ($t->nombre ?? '—') }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Fecha de Incineración</label>
                    <input type="date" name="fecha_incineracion" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tipo</label>
                    <select name="tipo" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <option value="individual">Individual</option>
                        <option value="colectiva">Colectiva</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Costo (Bs.)</label>
                    <input type="number" step="0.01" name="costo" class="form-control" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Observaciones (opcional)</label>
                    <textarea name="observaciones" class="form-control" rows="3"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary-custom">Registrar y Descargar PDF</button>
            </div>
        </form>
    </div>
</div>
@endsection
