@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary-color: #2c3e50;
        --secondary-color: #34495e;
        --accent-color: #16a085;
        --light-color: #ecf0f1;
    }

    .badge-status {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 12px;
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
        background-color: #f8f9fa;
        color: #383d41;
    }

    .card-dashboard {
        border-radius: 12px;
        box-shadow: 0 6px 15px rgba(0,0,0,0.08);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card-dashboard:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.12);
    }

    .stats-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
    }
    .stats-label {
        font-size: 0.95rem;
        color: var(--secondary-color);
    }
    .card-icon {
        font-size: 2rem;
        color: var(--accent-color);
    }

    .header {
        background-color: var(--light-color);
        border-radius: 12px;
        padding: 20px 25px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        margin-bottom: 25px;
    }

    .btn-primary-custom {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
        color: white;
        font-weight: 600;
        transition: background-color 0.3s, transform 0.2s;
    }
    .btn-primary-custom:hover {
        background-color: #138a72;
        transform: translateY(-2px);
    }

    .table thead th {
        background-color: var(--primary-color);
        color: white;
        font-weight: 600;
        border: none;
    }

    .table tbody tr {
        transition: background-color 0.2s;
    }
    .table tbody tr:hover {
        background-color: #f1f1f1;
    }

    .card-header {
        background-color: var(--light-color);
        font-weight: 600;
        font-size: 1.1rem;
        border-bottom: none;
    }

    .table td, .table th {
        vertical-align: middle;
    }

    @media (max-width: 768px) {
        .stats-number {
            font-size: 1.5rem;
        }
        .card-icon {
            font-size: 1.5rem;
        }
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
            <a href="{{ route('nicho.create') }}" class="btn btn-primary-custom">
                <i class="fas fa-layer-group me-1"></i> Registrar Nicho
            </a>
            <a href="{{ route('osario.create') }}" class="btn btn-primary-custom">
                <i class="fas fa-layer-group me-1"></i> Registrar Osario
            </a>
        @endif
    </div>
</div>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card card-dashboard p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number">{{ $totalNichos }}</div>
                    <div class="stats-label">Nichos Totales</div>
                </div>
                <div class="card-icon"><i class="fas fa-monument"></i></div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-dashboard p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number">{{ $nichosOcupados }}</div>
                    <div class="stats-label">Ocupados</div>
                </div>
                <div class="card-icon"><i class="fas fa-cross"></i></div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-dashboard p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number">{{ $nichosDisponibles }}</div>
                    <div class="stats-label">Disponibles</div>
                </div>
                <div class="card-icon"><i class="fas fa-square"></i></div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <a href="{{ route('nicho.por_vencer') }}" class="text-decoration-none">
            <div class="card card-dashboard p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number">{{ $nichosPorVencer }}</div>
                        <div class="stats-label">Por Vencer</div>
                    </div>
                    <div class="card-icon"><i class="fas fa-hourglass-half"></i></div>
                </div>
            </div>
        </a>
    </div>

</div>

<div class="card card-dashboard mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
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
                            <td colspan="3" class="text-center py-3 text-muted">
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
