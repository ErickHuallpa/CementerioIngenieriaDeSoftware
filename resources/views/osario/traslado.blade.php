@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary-color: #2c3e50;
        --secondary-color: #34495e;
        --accent-color: #16a085;
        --light-color: #ecf0f1;
    }

    body {
        background-color: var(--light-color);
    }

    h3 {
        color: var(--primary-color);
        font-weight: 700;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .card-body p {
        color: var(--secondary-color);
    }

    .form-label {
        font-weight: 500;
        color: var(--primary-color);
    }

    .form-select, .form-control {
        border-radius: 8px;
        border: 1px solid #ccc;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    .form-select:focus, .form-control:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 0.2rem rgba(22, 160, 133, 0.25);
    }

    .input-group .btn-outline-secondary {
        border-radius: 0 8px 8px 0;
        border-color: var(--secondary-color);
        transition: 0.3s;
    }

    .input-group .btn-outline-secondary:hover {
        background-color: var(--accent-color);
        color: #fff;
        border-color: var(--accent-color);
    }

    .btn-success {
        background: linear-gradient(135deg, var(--accent-color), var(--primary-color));
        border: none;
        transition: 0.3s;
        border-radius: 8px;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
    }

    .btn-outline-secondary {
        border-radius: 8px;
        border-color: var(--secondary-color);
        color: var(--secondary-color);
        transition: 0.3s;
    }

    .btn-outline-secondary:hover {
        background-color: var(--secondary-color);
        color: #fff;
    }

    hr {
        border-top: 2px dashed var(--secondary-color);
    }

    ul li {
        margin-bottom: 5px;
        color: var(--secondary-color);
    }

    @media (max-width: 768px) {
        .input-group .btn-outline-secondary {
            margin-top: 5px;
        }
    }
</style>

<div class="container py-4">
    <h3 class="mb-4">Traslado a Osario</h3>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('osario.traslado.store') }}" method="POST">
                @csrf

                <div class="row gy-3">
                    <div class="col-md-6">
                        <label for="id_contrato" class="form-label">Difunto (contrato)</label>
                        <select name="id_contrato" id="id_contrato" class="form-select" required>
                            <option value="">-- Seleccione contrato --</option>
                            @foreach($contratosElegibles as $contrato)
                                @php
                                    $dif = $contrato->difunto;
                                    $persona = $dif->persona ?? null;
                                    $label = $persona ? "{$persona->nombre} {$persona->apellido} — Nicho: {$contrato->nicho?->fila}{$contrato->nicho?->columna} — Vence: ".\Carbon\Carbon::parse($contrato->fecha_fin)->format('d/m/Y')." — Renov: {$contrato->renovaciones}" : "Difunto #{$contrato->id_difunto}";
                                @endphp
                                <option value="{{ $contrato->id_contrato }}">
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_contrato') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="id_osario" class="form-label">Osario disponible</label>
                        <div class="input-group">
                            <select name="id_osario" id="id_osario" class="form-select" required>
                                <option value="">-- Seleccione osario --</option>
                                @foreach($osariosDisponibles as $osario)
                                    <option value="{{ $osario->id_osario }}"
                                        @if(isset($id_osario_preseleccionado) && $id_osario_preseleccionado == $osario->id_osario) selected @endif>
                                        Pabellón: {{ $osario->pabellon->nombre ?? $osario->id_pabellon }} —
                                        Ubicación: {{ $osario->fila }}{{ $osario->columna }} —
                                        Precio: {{ number_format($osario->costo ?? 0, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            <a href="{{ route('osario.mapa') }}" class="btn btn-outline-secondary">Elegir Osario</a>
                        </div>
                        @error('id_osario') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="id_trabajador" class="form-label">Trabajador encargado</label>
                        <select name="id_trabajador" id="id_trabajador" class="form-select" required>
                            <option value="">-- Seleccione trabajador --</option>
                            @foreach($trabajadores as $trabajador)
                                <option value="{{ $trabajador->id_persona }}">
                                    {{ $trabajador->nombre }} {{ $trabajador->apellido }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_trabajador') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label for="observacion" class="form-label">Observación (opcional)</label>
                        <textarea name="observacion" id="observacion" class="form-control" rows="2" placeholder="Motivo, notas..."></textarea>
                    </div>

                    <div class="col-12 text-end">
                        <a href="{{ route('osario.index') }}" class="btn btn-outline-secondary me-2">Volver</a>
                        <button type="submit" id="btn_confirmar" class="btn btn-success">Confirmar traslado</button>
                    </div>

                </div>
            </form>

            <hr>

            <h6 class="mt-3">Notas</h6>
            <ul>
                <li>Solo se permiten traslados si el contrato del nicho vence en <strong>1 mes o menos</strong> (o ya venció).</li>
                <li>El contrato actual se marcará como <strong>renovado</strong> y se incrementará el contador de renovaciones (+1). Límite máximo de renovaciones: 2.</li>
                <li>Se creará un nuevo contrato asociado al osario por <strong>5 años</strong> (fecha_fin = fecha_inicio + 5 años).</li>
            </ul>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const osarioSelect = document.getElementById('id_osario');
        const contratoSelect = document.getElementById('id_contrato');
        const trabajadorSelect = document.getElementById('id_trabajador');
        const observacionInput = document.getElementById('observacion');
        const btnConfirmar = document.getElementById('btn_confirmar');

        osarioSelect.addEventListener('change', function () {
            const habilitar = osarioSelect.value !== '';

            contratoSelect.disabled = !habilitar;
            trabajadorSelect.disabled = !habilitar;
            observacionInput.disabled = !habilitar;
            btnConfirmar.disabled = !habilitar;
        });
    });
</script>

@endsection
