@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h4>{{ isset($difunto) ? 'Editar Difunto' : 'Registrar Nuevo Difunto' }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ isset($difunto) ? route('fallecido.update', $difunto->id_difunto) : route('fallecido.store') }}" method="POST">
                @csrf
                @if(isset($difunto)) @method('PUT') @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="{{ $difunto->persona->nombre ?? old('nombre') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellido</label>
                        <input type="text" name="apellido" class="form-control" value="{{ $difunto->persona->apellido ?? old('apellido') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">CI (Opcional)</label>
                        <input type="text" name="ci" class="form-control" value="{{ $difunto->persona->ci ?? old('ci') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Doliente</label>
                        <select name="id_doliente" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach($dolientes as $doliente)
                                <option value="{{ $doliente->id_persona }}" 
                                    @if(isset($difunto) && $difunto->id_doliente == $doliente->id_persona) selected @endif>
                                    {{ $doliente->nombre }} {{ $doliente->apellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha de Fallecimiento</label>
                        <input type="date" name="fecha_fallecimiento" class="form-control" value="{{ $difunto->fecha_fallecimiento ?? old('fecha_fallecimiento') }}" required>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">{{ isset($difunto) ? 'Actualizar' : 'Registrar' }}</button>
                    <a href="{{ route('fallecido.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
