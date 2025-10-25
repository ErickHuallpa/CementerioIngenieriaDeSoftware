@extends('layouts.app')

@section('content')
<div class="header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Registrar Osarios</h2>
        <p class="text-muted mb-0">Crea osarios automáticamente para los pabellones tipo "osario"</p>
    </div>
</div>

<div class="card card-dashboard p-4">
    <form action="{{ route('osario.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="id_pabellon" class="form-label">Pabellón</label>
            <select name="id_pabellon" id="id_pabellon" class="form-select" required>
                <option value="">Seleccione un pabellón</option>
                @foreach ($pabellones as $pabellon)
                    <option value="{{ $pabellon->id_pabellon }}">{{ $pabellon->nombre }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary-custom">Generar Osarios</button>
    </form>
</div>
@endsection
