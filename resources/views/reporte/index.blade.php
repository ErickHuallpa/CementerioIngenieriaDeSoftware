@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary-color: #2c3e50;
        --secondary-color: #34495e;
        --accent-color: #16a085;
        --light-color: #ecf0f1;
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: var(--light-color);
        font-weight: bold;
    }

    .btn-accent {
        background-color: var(--accent-color);
        color: #fff;
        border: none;
    }
    .btn-accent:hover {
        background-color: var(--secondary-color);
    }
</style>

<div class="container py-4">
    <h3 class="mb-4"><i class="fas fa-file-alt me-2"></i> Reportes</h3>

    <div class="card shadow-sm">
        <div class="card-header">Filtros de Reporte</div>
        <div class="card-body">
            <form action="{{ route('reportes.generar') }}" method="POST" id="formReporte">
                @csrf

                <div class="mb-3">
                    <label for="tipo_reporte" class="form-label">Tipo de Reporte</label>
                    <select name="tipo_reporte" id="tipo_reporte" class="form-select" required>
                        <option value="">Seleccione...</option>
                        @foreach($tipos as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="fecha_fin" class="form-label">Fecha Fin</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Atajos de Fecha</label>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-accent" onclick="setDateRange(7)">Última Semana</button>
                        <button type="button" class="btn btn-accent" onclick="setDateRange(30)">Último Mes</button>
                        <button type="button" class="btn btn-accent" onclick="setDateRange(90)">Últimos 3 Meses</button>
                        <button type="button" class="btn btn-accent" onclick="setDateRange(180)">Últimos 6 Meses</button>
                        <button type="button" class="btn btn-accent" onclick="setDateRange(365)">Último Año</button>
                        <button type="button" class="btn btn-accent" onclick="setDateRange('all')">Todos los Registros</button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fas fa-search me-2"></i> Generar Reporte</button>
            </form>
        </div>
    </div>
</div>

<script>
function formatDate(date) {
    let d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();
    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;
    return [year, month, day].join('-');
}

function setDateRange(days) {
    const today = new Date();
    document.getElementById('fecha_fin').value = formatDate(today);

    if(days === 'all') {
        document.getElementById('fecha_inicio').value = '';
    } else {
        const pastDate = new Date();
        pastDate.setDate(today.getDate() - days);
        document.getElementById('fecha_inicio').value = formatDate(pastDate);
    }
}
</script>
@endsection
