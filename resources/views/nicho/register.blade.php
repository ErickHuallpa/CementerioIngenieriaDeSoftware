@extends('layouts.app')

@section('content')
<div class="header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Registrar Nicho</h2>
        <p class="text-muted mb-0">Crea un nuevo nicho en el sistema</p>
    </div>
</div>

<div class="card card-dashboard p-4">
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>¡Error!</strong> Corrige los siguientes campos:
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('nicho.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="id_pabellon" class="form-label">Pabellón</label>
            <select id="id_pabellon" name="id_pabellon" class="form-select" required>
                <option value="">Seleccione un pabellón</option>
                @foreach ($pabellones as $pabellon)
                    <option value="{{ $pabellon->id_pabellon }}">{{ $pabellon->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="fila" class="form-label">Fila</label>
                <input type="number" id="fila" name="fila" class="form-control" min="1" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="columna" class="form-label">Columna</label>
                <input type="text" id="columna" name="columna" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="posicion" class="form-label">Posición</label>
            <select id="posicion" name="posicion" class="form-select" required>
                <option value="">Seleccione una posición</option>
                <option value="superior">Superior</option>
                <option value="medio">Medio</option>
                <option value="inferior">Inferior</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="costo_alquiler" class="form-label">Costo de Alquiler (Bs.)</label>
            <input type="number" id="costo_alquiler" name="costo_alquiler" class="form-control" min="0" step="0.01" required>
        </div>

        <input type="hidden" name="estado" value="disponible">

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="fecha_ocupacion" class="form-label">Fecha de Ocupación</label>
                <input type="date" id="fecha_ocupacion" name="fecha_ocupacion" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                <input type="date" id="fecha_vencimiento" name="fecha_vencimiento" class="form-control">
            </div>
        </div>

        <div class="text-end">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-success">Registrar</button>
        </div>
    </form>
</div>
@endsection
