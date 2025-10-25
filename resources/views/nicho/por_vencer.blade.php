@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Nichos y Osarios por Vencer (2 semanas)</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Nichos --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>Nichos por Vencer</strong>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Pabellón</th>
                            <th>Ubicación</th>
                            <th>Difunto</th>
                            <th>Doliente</th>
                            <th>Fecha Vencimiento</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nichosPorVencer as $nicho)
                            @foreach($nicho->difuntos as $difunto)
                                <tr>
                                    <td>{{ $nicho->pabellon->nombre ?? '—' }}</td>
                                    <td>{{ $nicho->fila }}{{ $nicho->columna }} ({{ $nicho->posicion }})</td>
                                    <td>{{ $difunto->persona->nombre_completo ?? '—' }}</td>
                                    <td>{{ $difunto->doliente->nombre_completo ?? '—' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($nicho->fecha_vencimiento)->format('d/m/Y') }}</td>
                                    <td>
                                        <form action="{{ route('nicho.enviarNotificacion', [$nicho->id_nicho, $difunto->id_difunto]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">Enviar Notificación</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No hay nichos por vencer.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Osarios --}}
    <div class="card">
        <div class="card-header">
            <strong>Osarios por Vencer</strong>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Pabellón</th>
                            <th>Ubicación</th>
                            <th>Difunto</th>
                            <th>Doliente</th>
                            <th>Fecha Vencimiento</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($osariosPorVencer as $osario)
                            <tr>
                                <td>{{ $osario->pabellon->nombre ?? '—' }}</td>
                                <td>{{ $osario->fila }}{{ $osario->columna }}</td>
                                <td>{{ $osario->difunto->persona->nombre_completo ?? '—' }}</td>
                                <td>{{ $osario->difunto->doliente->nombre_completo ?? '—' }}</td>
                                <td>{{ \Carbon\Carbon::parse($osario->fecha_salida)->format('d/m/Y') }}</td>
                                <td>
                                    <form action="{{ route('osario.enviarNotificacion', $osario->id_osario) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">Enviar Notificación</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No hay osarios por vencer.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
