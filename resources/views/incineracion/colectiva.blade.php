@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-users-cog me-2"></i> Incineración Colectiva</h2>

    <form action="{{ route('incineracion.storeColectiva') }}" method="POST">
        @csrf
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="mb-3">
                    <label for="id_responsable" class="form-label">Trabajador responsable</label>
                    <select name="id_responsable" id="id_responsable" class="form-select" required>
                        <option value="">-- Seleccione trabajador --</option>
                        @foreach($trabajadores as $trabajador)
                            <option value="{{ $trabajador->id_persona }}">
                                {{ $trabajador->nombre }} {{ $trabajador->apellido }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="fecha_incineracion" class="form-label">Fecha de incineración</label>
                    <input type="date" name="fecha_incineracion" id="fecha_incineracion" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Difuntos en bodega próximos a salir</label>
                    <div class="list-group">
                        @forelse($difuntos as $dif)
                            <label class="list-group-item">
                                <input type="checkbox" name="difuntos[]" value="{{ $dif->id_difunto }}">
                                {{ $dif->persona->nombre_completo ?? 'Difunto #' . $dif->id_difunto }}
                                (Bodega: {{ $dif->bodega->fecha_ingreso }} → {{ $dif->bodega->fecha_salida }})
                            </label>
                        @empty
                            <p class="text-muted">No hay difuntos elegibles para incineración colectiva.</p>
                        @endforelse
                    </div>
                </div>

                <div class="mb-3">
                    <label for="costo" class="form-label">Costo total</label>
                    <input type="number" step="0.01" name="costo" id="costo" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="observaciones" class="form-label">Observaciones (opcional)</label>
                    <textarea name="observaciones" id="observaciones" class="form-control" rows="2"></textarea>
                </div>

                <div class="text-end">
                    <a href="{{ route('incineracion.index') }}" class="btn btn-outline-secondary me-2">Volver</a>
                    <button type="submit" class="btn btn-warning">Incinerar seleccionados</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
