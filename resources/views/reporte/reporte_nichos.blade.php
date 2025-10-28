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
.table-dark {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: var(--light-color);
}
</style>

<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header">Reporte de Ocupación de Nichos</div>
            <div class="mb-3 text-end">
                <form action="{{ route('reportes.pdf') }}" method="POST" target="_blank">
                    @csrf
                    <input type="hidden" name="tipo_reporte" value="nichos">
                    <input type="hidden" name="fecha_inicio" value="{{ request('fecha_inicio') }}">
                    <input type="hidden" name="fecha_fin" value="{{ request('fecha_fin') }}">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-file-pdf me-2"></i> Exportar a PDF
                    </button>
                </form>
            </div>
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Pabellón</th>
                        <th>Fila</th>
                        <th>Columna</th>
                        <th>Posición</th>
                        <th>Difunto</th>
                        <th>Fecha Ocupación</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($datos as $nicho)
                        <tr>
                            <td>{{ $nicho->pabellon->nombre ?? '—' }}</td>
                            <td>{{ $nicho->fila }}</td>
                            <td>{{ $nicho->columna }}</td>
                            <td>{{ $nicho->posicion }}</td>
                            <td>
                                @foreach($nicho->difuntos as $difunto)
                                    {{ $difunto->persona->nombre }} {{ $difunto->persona->apellido }}<br>
                                @endforeach
                            </td>
                            <td>{{ $nicho->fecha_ocupacion ?? '—' }}</td>
                            <td>{{ ucfirst($nicho->estado ?? '—') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No se encontraron registros.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
