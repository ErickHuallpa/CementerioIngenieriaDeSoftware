@extends('layouts.app')

@section('content')
<div class="header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Registrar Pabellón</h2>
        <p class="text-muted mb-0">Crea un nuevo pabellón en el sistema</p>
    </div>
</div>

<div class="card card-dashboard p-4">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pabellon.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Pabellón</label>
            <input type="text" id="nombre" name="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea id="descripcion" name="descripcion" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo de Pabellón</label>
            <select id="tipo" name="tipo" class="form-select" required>
                <option value="">Seleccione un tipo</option>
                <option value="institucional">Institucional</option>
                <option value="comun">Común</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="institucion" class="form-label">Institución (opcional)</label>
            <input type="text" id="institucion" name="institucion" class="form-control">
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Guardar Pabellón
            </button>
        </div>
    </form>
</div>
@endsection
