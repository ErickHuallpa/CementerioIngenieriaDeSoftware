@extends('layouts.app')

@section('content')
<style>
:root {
    --primary-color: #2c3e50;
    --secondary-color: #34495e;
    --accent-color: #16a085;
    --light-color: #ecf0f1;
    --highlight-color: #f1c40f;
}

.card-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: var(--light-color);
    font-weight: bold;
}

.table-dark th {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: var(--light-color);
    font-weight: 600;
    border: none;
}

.table-hover tbody tr:hover {
    background-color: rgba(22, 160, 133, 0.08);
}

tfoot tr th {
    background-color: var(--highlight-color);
    color: #000;
    font-size: 1rem;
}

.chart-container {
    margin-top: 2rem;
    padding: 1rem;
    background-color: #f8f9fa;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}
</style>

<div class="container py-4">
    <h3 class="mb-4"><i class="fas fa-fire me-2"></i> Reporte de Incineraciones</h3>

    <div class="card shadow-sm">
        <div class="card-header">Detalle de Incineraciones</div>
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Difunto</th>
                        <th>CI</th>
                        <th>Responsable</th>
                        <th>Fecha Incineración</th>
                        <th>Costo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($datos as $incineracion)
                        <tr>
                            <td>{{ $incineracion->difunto->persona->nombre ?? '—' }} {{ $incineracion->difunto->persona->apellido ?? '' }}</td>
                            <td>{{ $incineracion->difunto->persona->ci ?? '—' }}</td>
                            <td>{{ $incineracion->responsable->nombre ?? '—' }} {{ $incineracion->responsable->apellido ?? '' }}</td>
                            <td>{{ $incineracion->fecha_incineracion ?? '—' }}</td>
                            <td>Bs. {{ number_format($incineracion->costo, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No se encontraron registros.</td>
                        </tr>
                    @endforelse
                </tbody>

                @if(!empty($datos))
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">TOTAL:</th>
                        <th>Bs. {{ number_format($datos->sum('costo'), 2) }}</th>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
    <div class="chart-container">
        <canvas id="incineracionesChart"></canvas>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const incineraciones = @json($datos);
const costosPorFecha = {};
incineraciones.forEach(i => {
    const fecha = i.fecha_incineracion;
    costosPorFecha[fecha] = (costosPorFecha[fecha] || 0) + parseFloat(i.costo);
});

const labels = Object.keys(costosPorFecha).sort();
const data = labels.map(f => costosPorFecha[f]);

const ctx = document.getElementById('incineracionesChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Costo por Fecha',
            data: data,
            backgroundColor: 'rgba(231, 76, 60, 0.2)',
            borderColor: 'rgba(231, 76, 60, 1)',
            borderWidth: 2,
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true, position: 'top' },
            tooltip: { mode: 'index', intersect: false }
        },
        scales: {
            x: { title: { display: true, text: 'Fecha Incineración' } },
            y: { title: { display: true, text: 'Costo (Bs.)' }, beginAtZero: true }
        }
    }
});
</script>
@endsection
