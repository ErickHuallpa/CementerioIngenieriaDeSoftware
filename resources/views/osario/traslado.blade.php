@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Traslado a Osario</h3>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <p class="text-muted">Seleccione un difunto con contrato de nicho próximo a vencer (1 mes o menos) y un osario disponible.</p>

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
                        <select name="id_osario" id="id_osario" class="form-select" required>
                            <option value="">-- Seleccione osario --</option>
                            @foreach($osariosDisponibles as $osario)
                                <option value="{{ $osario->id_osario }}">
                                    Pabellón: {{ $osario->pabellon->nombre ?? $osario->id_pabellon }} —
                                    Ubicación: {{ $osario->fila }}{{ $osario->columna }} —
                                    Precio: {{ number_format($osario->costo ?? 0, 2) }}
                                </option>
                            @endforeach
                        </select>
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
                        <a href="{{ route('osario.create') }}" class="btn btn-outline-secondary me-2">Volver</a>
                        <button type="submit" class="btn btn-success">Confirmar traslado</button>
                    </div>
                </div>
            </form>

            <hr>

            <h6 class="mt-3">Notas</h6>
            <ul>
                <li>Solo se permiten traslados si el contrato del nicho vence en **1 mes o menos** (o ya venció).</li>
                <li>El contrato actual se marcará como <strong>renovado</strong> y se incrementará el contador de renovaciones (+1). Límite máximo de renovaciones: 2.</li>
                <li>Se creará un nuevo contrato asociado al osario por <strong>5 años</strong> (fecha_fin = fecha_inicio + 5 años).</li>
            </ul>
        </div>
    </div>
</div>
@endsection
