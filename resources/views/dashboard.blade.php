@extends('layouts.app')

@section('content')
<style>
:root {
    --primary-color: #2c3e50;
    --secondary-color: #34495e;
    --accent-color: #16a085;
    --light-color: #ecf0f1;
}

.badge-status { display: inline-block; padding: 6px 12px; border-radius: 12px; font-size: 0.85rem; font-weight: 600; }
.badge-available { background-color: #d4edda; color: #155724; }
.badge-occupied { background-color: #f8d7da; color: #721c24; }
.badge-warning { background-color: #fff3cd; color: #856404; }
.badge-danger { background-color: #f8f9fa; color: #383d41; }

.card-dashboard {
    border-radius: 12px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.08);
    transition: transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
}
.card-dashboard:hover { transform: translateY(-3px); box-shadow: 0 12px 25px rgba(0,0,0,0.12); }

.stats-number { font-size: 2rem; font-weight: 700; color: var(--primary-color); }
.stats-label { font-size: 0.95rem; color: var(--secondary-color); }
.card-icon { font-size: 2rem; color: var(--accent-color); }

.header { background-color: var(--light-color); border-radius: 12px; padding: 20px 25px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 25px; }
.btn-primary-custom { background-color: var(--accent-color); border-color: var(--accent-color); color: white; font-weight: 600; transition: background-color 0.3s, transform 0.2s; }
.btn-primary-custom:hover { background-color: #138a72; transform: translateY(-2px); }

.search-box { max-width: 520px; width: 100%; }
.search-input { border-radius: 999px 0 0 999px; border: 1px solid #d0d7db; }
.search-btn { border-radius: 0 999px 999px 0; background: linear-gradient(90deg, var(--primary-color), var(--accent-color)); color: #fff; border: none; padding: 0.5rem 1rem; }

.chart-container {
    padding: 20px;
    background-color: var(--light-color);
    border-radius: 12px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.08);
    margin-top: 25px;
}
</style>

<div class="header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h2 class="mb-0">Panel Principal</h2>
        <p class="text-muted mb-0">Bienvenido al sistema de gestión del cementerio</p>
    </div>

    <div class="search-box d-flex align-items-center me-2">
        <input id="buscarInput" type="text" class="form-control search-input" placeholder="Buscar difunto por nombre, apellido, CI o fecha (YYYY-MM-DD)">
        <button id="btnBuscar" class="btn search-btn"><i class="fas fa-search"></i></button>
    </div>
    <div class="d-flex gap-2 align-items-center flex-wrap">
        @php $tipo = auth()->user()->persona->tipoPersona->nombre_tipo ?? null; @endphp
        @if($tipo === 'Administrador')
            <a href="{{ route('pabellon.create') }}" class="btn btn-primary-custom"><i class="fas fa-plus-circle me-1"></i> Registrar Pabellón</a>
            <a href="{{ route('nicho.create') }}" class="btn btn-primary-custom"><i class="fas fa-layer-group me-1"></i> Registrar Nicho</a>
            <a href="{{ route('osario.create') }}" class="btn btn-primary-custom"><i class="fas fa-layer-group me-1"></i> Registrar Osario</a>
        @endif
    </div>
</div>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card card-dashboard p-3" data-bs-toggle="tooltip" data-bs-html="true" title="Nichos: {{ $totalNichos }}<br>Osarios: {{ $totalOsarios }}">
            <div class="d-flex justify-content-between align-items-center">
                <div><div class="stats-number">{{ $totalNichos + $totalOsarios }}</div><div class="stats-label">Totales</div></div>
                <div class="card-icon"><i class="fas fa-monument"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-dashboard p-3" data-bs-toggle="tooltip" data-bs-html="true" title="Nichos: {{ $nichosOcupados }}<br>Osarios: {{ $osariosOcupados }}">
            <div class="d-flex justify-content-between align-items-center">
                <div><div class="stats-number">{{ $nichosOcupados + $osariosOcupados }}</div><div class="stats-label">Ocupados</div></div>
                <div class="card-icon"><i class="fas fa-cross"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-dashboard p-3" data-bs-toggle="tooltip" data-bs-html="true" title="Nichos: {{ $nichosDisponibles }}<br>Osarios: {{ $osariosDisponibles }}">
            <div class="d-flex justify-content-between align-items-center">
                <div><div class="stats-number">{{ $nichosDisponibles + $osariosDisponibles }}</div><div class="stats-label">Disponibles</div></div>
                <div class="card-icon"><i class="fas fa-square"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <a href="{{ route('nicho.por_vencer') }}" class="text-decoration-none">
            <div class="card card-dashboard p-3" data-bs-toggle="tooltip" data-bs-html="true" title="Nichos: {{ $nichosPorVencer }}<br>Osarios: {{ $osariosPorVencer }}">
                <div class="d-flex justify-content-between align-items-center">
                    <div><div class="stats-number">{{ $nichosPorVencer + $osariosPorVencer }}</div><div class="stats-label">Por Vencer</div></div>
                    <div class="card-icon"><i class="fas fa-hourglass-half"></i></div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="chart-container">
            <h5 class="mb-3">Estado de Nichos y Osarios</h5>
            <canvas id="chartEstadoNichos"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="chart-container">
            <h5 class="mb-3">Por Vencer y Vencidos</h5>
            <canvas id="chartVencimientoNichos"></canvas>
        </div>
    </div>
</div>
<div class="modal fade" id="modalResultado" tabindex="-1" aria-labelledby="modalResultadoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(90deg, var(--primary-color), var(--secondary-color)); color: white;">
        <h5 class="modal-title" id="modalResultadoLabel">Resultado de búsqueda</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body"><div id="resultadoContent"></div></div>
      <div class="modal-footer">
        <a id="btnIrMapa" href="#" class="btn btn-secondary" style="display:none;">Ir a Mapa</a>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    const btnBuscar = document.getElementById('btnBuscar');
    const input = document.getElementById('buscarInput');
    const resultadoContent = document.getElementById('resultadoContent');
    const modalEl = document.getElementById('modalResultado');
    const bootstrapModal = new bootstrap.Modal(modalEl);
    const btnIrMapa = document.getElementById('btnIrMapa');

    async function buscar(q) {
        if(!q || q.trim().length === 0) return;
        btnBuscar.disabled = true;
        try {
            const url = "{{ route('dashboard.buscar') }}?q=" + encodeURIComponent(q.trim());
            const res = await fetch(url, { headers: { 'Accept': 'application/json' }});
            const data = await res.json();
            if(!data || data.length === 0) {
                resultadoContent.innerHTML = `<div class="alert alert-warning mb-0">No se encontraron resultados.</div>`;
                btnIrMapa.style.display = 'none';
            } else {
                const d = data[0];
                let html = `<h5 class="mb-2">${escapeHtml(d.nombre_completo)}</h5>`;
                html += `<p class="mb-1"><strong>CI:</strong> ${escapeHtml(d.ci ?? '—')}</p>`;
                html += `<p class="mb-1"><strong>Fecha fallecimiento:</strong> ${escapeHtml(d.fecha_fallecimiento ?? '—')}</p>`;
                html += `<p class="mb-1"><strong>Estado:</strong> ${escapeHtml(d.estado_label)}</p>`;

                if(d.estado === 'en_nicho' || d.estado === 'osario') {
                    btnIrMapa.style.display = 'inline-block';
                    btnIrMapa.href = "{{ route('nicho.mapa') }}";
                } else {
                    btnIrMapa.style.display = 'none';
                }

                resultadoContent.innerHTML = html;
            }
            bootstrapModal.show();
        } catch (err) {
            console.error(err);
            resultadoContent.innerHTML = `<div class="alert alert-danger mb-0">Ocurrió un error al buscar. Intente nuevamente.</div>`;
            btnIrMapa.style.display = 'none';
            bootstrapModal.show();
        } finally { btnBuscar.disabled = false; }
    }

    btnBuscar.addEventListener('click', () => buscar(input.value));
    input.addEventListener('keydown', (e) => { if(e.key === 'Enter') { e.preventDefault(); buscar(input.value); } });
    function escapeHtml(text) {
        if (!text && text !== 0) return '';
        return String(text).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');
    }
    const ctxEstado = document.getElementById('chartEstadoNichos').getContext('2d');
    new Chart(ctxEstado, {
        type: 'pie',
        data: {
            labels: ['Disponibles', 'Ocupados', 'Por Vencer', 'Vencidos'],
            datasets: [{
                data: [
                    {{ $nichosDisponibles + $osariosDisponibles }},
                    {{ $nichosOcupados + $osariosOcupados }},
                    {{ $nichosPorVencer + $osariosPorVencer }},
                    {{ $nichosVencidos + $osariosVencidos }}
                ],
                backgroundColor: ['#d4edda','#f8d7da','#fff3cd','#f8f9fa'],
                borderColor: ['#155724','#721c24','#856404','#383d41'],
                borderWidth: 1
            }]
        },
        options: { responsive:true, plugins: { legend: { position:'bottom' } } }
    });

    const ctxVencimiento = document.getElementById('chartVencimientoNichos').getContext('2d');
    new Chart(ctxVencimiento, {
        type: 'bar',
        data: {
            labels: ['Por Vencer','Vencidos'],
            datasets: [{
                label: 'Cantidad de Nichos y Osarios',
                data: [
                    {{ $nichosPorVencer + $osariosPorVencer }},
                    {{ $nichosVencidos + $osariosVencidos }}
                ],
                backgroundColor: ['#ffc107','#dc3545']
            }]
        },
        options: { responsive:true, plugins:{ legend:{ display:false } }, scales:{ y:{ beginAtZero:true } } }
    });
});
</script>
@endsection
