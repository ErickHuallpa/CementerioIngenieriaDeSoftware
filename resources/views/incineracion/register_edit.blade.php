@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5>
                <i class="fas fa-fire me-2"></i>
                {{ isset($incineracion) ? 'Editar Incineración' : 'Registrar Incineración' }}
            </h5>
        </div>
        <form action="{{ isset($incineracion) ? route('incineracion.update', $incineracion->id_incineracion) : route('incineracion.store') }}" method="POST" class="card-body row g-3">
            @csrf
            @if(isset($incineracion))
                @method('PUT')
            @endif

            <div class="col-md-6">
                <label class="form-label">Difunto</label>
                <select name="id_difunto" class="form-select" required {{ isset($incineracion) ? 'disabled' : '' }}>
                    <option value="">Seleccione...</option>
                    @foreach($difuntos as $d)
                        <option value="{{ $d->id_difunto }}" 
                            {{ (isset($incineracion) && $incineracion->id_difunto == $d->id_difunto) ? 'selected' : '' }}>
                            #{{ $d->id_difunto }} — {{ $d->persona->nombre_completo ?? ($d->persona->nombre ?? '—') }}
                            (Estado: {{ $d->estado }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Responsable</label>
                <select name="id_responsable" class="form-select" required>
                    <option value="">Seleccione...</option>
                    @foreach($trabajadores as $t)
                        <option value="{{ $t->id_persona }}" 
                            {{ (isset($incineracion) && $incineracion->id_responsable == $t->id_persona) ? 'selected' : '' }}>
                            {{ $t->nombre_completo ?? ($t->nombre ?? '—') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Fecha de Incineración</label>
                <input type="date" name="fecha_incineracion" class="form-control" 
                    value="{{ old('fecha_incineracion', isset($incineracion) ? $incineracion->fecha_incineracion : date('Y-m-d')) }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Tipo</label>
                <select name="tipo" class="form-select" required>
                    <option value="">Seleccione...</option>
                    <option value="individual" {{ (isset($incineracion) && $incineracion->tipo=='individual') ? 'selected' : '' }}>Individual</option>
                    <option value="colectiva" {{ (isset($incineracion) && $incineracion->tipo=='colectiva') ? 'selected' : '' }}>Colectiva</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Costo (Bs.)</label>
                <input type="number" step="0.01" name="costo" class="form-control" 
                    value="{{ old('costo', $incineracion->costo ?? '') }}" required>
            </div>

            <div class="col-12">
                <label class="form-label">Observaciones (opcional)</label>
                <textarea name="observaciones" class="form-control" rows="3">{{ old('observaciones', $incineracion->observaciones ?? '') }}</textarea>
            </div>

            <div class="col-12 text-end">
                <a href="{{ route('incineracion.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary-custom">
                    {{ isset($incineracion) ? 'Actualizar' : 'Registrar y Descargar PDF' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
