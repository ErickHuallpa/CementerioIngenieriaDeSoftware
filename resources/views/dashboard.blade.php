@extends('layouts.app')

@section('content')
<style>
    .badge-status {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .badge-available {
        background-color: #d4edda;
        color: #155724;
    }
    .badge-occupied {
        background-color: #f8d7da;
        color: #721c24;
    }
    .badge-warning {
        background-color: #fff3cd;
        color: #856404;
    }
    .badge-danger {
        background-color: #e2e3e5;
        color: #383d41;
    }

    .card-dashboard {
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .stats-number {
        font-size: 1.8rem;
        font-weight: bold;
    }
    .stats-label {
        font-size: 0.9rem;
        color: #666;
    }
    .card-icon {
        font-size: 1.8rem;
        color: #007bff;
    }
</style>

<div class="header d-flex justify-content-between align-items-center">
    <div>
        <h2 class="mb-0">Panel Principal</h2>
        <p class="text-muted mb-0">Bienvenido al sistema de gestión del cementerio</p>
    </div>

    <div class="d-flex gap-2">
        @php
            $tipo = auth()->user()->persona->tipoPersona->nombre_tipo ?? null;
        @endphp

        @if($tipo === 'Administrador')
            <a href="{{ route('pabellon.create') }}" class="btn btn-primary-custom">
                <i class="fas fa-plus-circle me-1"></i> Registrar Pabellón
            </a>
            <a href="{{ route('nicho.create') }}" class="btn btn-success">
                <i class="fas fa-layer-group me-1"></i> Registrar Nicho
            </a>
        @endif
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-3">
        <div class="card card-dashboard">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stats-number">{{ $totalNichos }}</div>
                        <div class="stats-label">Nichos Totales</div>
                    </div>
                    <div class="card-icon"><i class="fas fa-monument"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-dashboard">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stats-number">{{ $nichosOcupados }}</div>
                        <div class="stats-label">Ocupados</div>
                    </div>
                    <div class="card-icon"><i class="fas fa-cross"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-dashboard">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stats-number">{{ $nichosDisponibles }}</div>
                        <div class="stats-label">Disponibles</div>
                    </div>
                    <div class="card-icon"><i class="fas fa-square"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-dashboard">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stats-number">{{ $nichosPorVencer }}</div>
                        <div class="stats-label">Por Vencer</div>
                    </div>
                    <div class="card-icon"><i class="fas fa-hourglass-half"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-dashboard mt-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Últimos Nichos Registrados</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Pabellón</th>
                        <th>Ubicación</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($listaNichos as $nicho)
                        <tr>
                            <td>{{ $nicho->pabellon->nombre ?? '—' }}</td>
                            <td>{{ $nicho->fila }}{{ $nicho->columna }}</td>
                            <td>
                                @if ($nicho->estado === 'disponible')
                                    <span class="badge-status badge-available">Disponible</span>
                                @elseif ($nicho->estado === 'ocupado')
                                    <span class="badge-status badge-occupied">Ocupado</span>
                                @elseif ($nicho->estado === 'por_vencer')
                                    <span class="badge-status badge-warning">Por Vencer</span>
                                @elseif ($nicho->estado === 'vencido')
                                    <span class="badge-status badge-danger">Vencido</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($nicho->estado) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-3 text-muted">
                                No hay nichos registrados aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
