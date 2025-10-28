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

    .card {
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
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

    .badge-tipo-nicho {
        background-color: #28a745;
        color: #fff;
        font-size: 0.85rem;
        padding: 0.35em 0.65em;
        border-radius: 12px;
    }

    .badge-tipo-osario {
        background-color: #6f42c1;
        color: #fff;
        font-size: 0.85rem;
        padding: 0.35em 0.65em;
        border-radius: 12px;
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
    <h3 class="mb-4"><i class="fas fa-dollar-sign me-2"></i> Flujo de Caja</h3>

    @if($inicio && $fin)
        <p>Periodo: <strong>{{ $inicio }}</strong> a <strong>{{ $fin }}</strong></p>
    @endif

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Fecha Contrato</th>
                        <th>Difunto</th>
                        <th>Doliente</th>
                        <th>Tipo</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($datos as $contrato)
                        <tr>
                            <td>{{ $contrato->fecha_inicio }}</td>
                            <td>{{ $contrato->difunto->persona->nombre }} {{ $contrato->difunto->persona->apellido }}</td>
                            <td>{{ optional($contrato->difunto->doliente)->nombre ?? '—' }} {{ optional($contrato->difunto->doliente)->apellido ?? '' }}</td>
                            <td>
                                @if($contrato->id_nicho)
                                    <span class="badge badge-tipo-nicho">Nicho</span>
                                @elseif($contrato->id_osario)
                                    <span class="badge badge-tipo-osario">Osario</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>Bs. {{ number_format($contrato->monto, 2) }}</td>
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
                        <th>Bs. {{ number_format($total, 2) }}</th>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
    <div class="mb-3 text-end">
        <form action="{{ route('reportes.pdf') }}" method="POST" target="_blank">
            @csrf
            <input type="hidden" name="tipo_reporte" value="flujo_caja">
            <input type="hidden" name="fecha_inicio" value="{{ request('fecha_inicio') }}">
            <input type="hidden" name="fecha_fin" value="{{ request('fecha_fin') }}">
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-file-pdf me-2"></i> Exportar a PDF
            </button>
        </form>
    </div>

    <div class="chart-container">
        <canvas id="flujoCajaChart"></canvas>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const contratos = @json($datos);
    const montosPorFecha = {};
    contratos.forEach(c => {
        const fecha = c.fecha_inicio;
        montosPorFecha[fecha] = (montosPorFecha[fecha] || 0) + parseFloat(c.monto);
    });

    const labels = Object.keys(montosPorFecha).sort();
    const data = labels.map(f => montosPorFecha[f]);

    const ctx = document.getElementById('flujoCajaChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Ingresos por Fecha',
                data: data,
                backgroundColor: 'rgba(22, 160, 133, 0.2)',
                borderColor: 'rgba(22, 160, 133, 1)',
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
                x: { title: { display: true, text: 'Fecha Contrato' } },
                y: { title: { display: true, text: 'Monto (Bs.)' }, beginAtZero: true }
            }
        }
    });
</script>
@endsection
