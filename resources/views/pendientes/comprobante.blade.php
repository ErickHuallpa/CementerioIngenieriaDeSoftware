@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h3 class="mb-0">COMPROBANTE DE SERVICIO - ENTIERRO</h3>
                    <small class="text-muted">N° Servicio: {{ $programacion->id_programacion }}</small>
                </div>
                <div class="text-end">
                    <p class="mb-0"><strong>Fecha:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                    <p class="mb-0"><strong>Estado:</strong> {{ ucfirst($programacion->estado) }}</p>
                </div>
            </div>

            <hr>

            <h5>Datos del Difunto</h5>
            <table class="table table-sm">
                <tr>
                    <th style="width:180px;">Nombre</th>
                    <td>{{ $programacion->difunto->persona->nombre ?? '—' }} {{ $programacion->difunto->persona->apellido ?? '' }}</td>
                </tr>
                <tr>
                    <th>CI</th>
                    <td>{{ $programacion->difunto->persona->ci ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Fecha Fallecimiento</th>
                    <td>{{ $programacion->difunto->fecha_fallecimiento ?? '—' }}</td>
                </tr>
            </table>

            <h5>Datos del Nicho</h5>
            <table class="table table-sm">
                @if($programacion->difunto->nicho)
                <tr>
                    <th style="width:180px;">Pabellón</th>
                    <td>{{ optional($programacion->difunto->nicho->pabellon)->nombre ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Ubicación</th>
                    <td>Fila {{ $programacion->difunto->nicho->fila ?? '—' }}, Columna {{ $programacion->difunto->nicho->columna ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Fecha Vencimiento Nicho</th>
                    <td>{{ $programacion->difunto->nicho->fecha_vencimiento ?? '—' }}</td>
                </tr>
                @else
                <tr><td colspan="2">Sin nicho asignado</td></tr>
                @endif
            </table>

            <h5>Programación</h5>
            <table class="table table-sm">
                <tr>
                    <th style="width:180px;">Fecha programada</th>
                    <td>{{ $programacion->fecha_programada }}</td>
                </tr>
                <tr>
                    <th>Hora</th>
                    <td>{{ \Carbon\Carbon::parse($programacion->hora_programada)->format('H:i') }}</td>
                </tr>
            </table>

            <h5>Empleado Responsable</h5>
            <table class="table table-sm">
                @if($programacion->trabajador)
                    <tr>
                        <th style="width:180px;">Nombre</th>
                        <td>{{ $programacion->trabajador->nombre ?? '—' }} {{ $programacion->trabajador->apellido ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>CI</th>
                        <td>{{ $programacion->trabajador->ci ?? '—' }}</td>
                    </tr>
                @else
                    <tr><td colspan="2">No hay trabajador asignado</td></tr>
                @endif
            </table>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('pendientes.index') }}" class="btn btn-secondary">Volver</a>

                {{-- Descargar PDF (solo disponible cuando está completado; pero ya estamos aquí por esa razón) --}}
                <a href="{{ route('pendientes.factura', $programacion->id_programacion) }}" class="btn btn-primary">
                    <i class="fas fa-file-pdf"></i> Descargar PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
