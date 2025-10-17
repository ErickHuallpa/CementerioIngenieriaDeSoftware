@extends('layouts.app')

@section('content')
<div class="header d-flex justify-content-between align-items-center">
    <div>
        <h2 class="mb-0">Panel Principal</h2>
        <p class="text-muted mb-0">Bienvenido al sistema de gestión del cementerio</p>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('pabellon.create') }}" class="btn btn-primary-custom">
            <i class="fas fa-plus-circle me-1"></i> Registrar Pabellón
        </a>
        <a href="{{ route('nicho.create') }}" class="btn btn-success">
            <i class="fas fa-layer-group me-1"></i> Registrar Nicho
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-danger">
                <i class="fas fa-sign-out-alt me-1"></i> Cerrar sesión
            </button>
        </form>
    </div>
</div>

<!-- Dashboard Stats -->
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
                        <th>ID</th>
                        <th>Pabellón</th>
                        <th>Fila</th>
                        <th>Columna</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($listaNichos as $nicho)
                        <tr>
                            <td>#{{ $nicho->id_nicho }}</td>
                            <td>{{ $nicho->pabellon->nombre ?? '—' }}</td>
                            <td>{{ $nicho->fila }}</td>
                            <td>{{ $nicho->columna }}</td>
                            <td>
                                @if ($nicho->estado === 'disponible')
                                    <span class="badge badge-status badge-available">Disponible</span>
                                @elseif ($nicho->estado === 'ocupado')
                                    <span class="badge badge-status badge-occupied">Ocupado</span>
                                @elseif ($nicho->estado === 'por_vencer')
                                    <span class="badge badge-status badge-warning">Por Vencer</span>
                                @elseif ($nicho->estado === 'vencido')
                                    <span class="badge badge-status badge-danger">Vencido</span>
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
