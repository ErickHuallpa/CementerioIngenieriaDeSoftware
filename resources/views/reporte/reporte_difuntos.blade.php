@extends('layouts.app')

@section('content')
<style>
:root {
    --primary-color: #2c3e50;
    --secondary-color: #34495e;
    --accent-color: #16a085;
    --light-color: #ecf0f1;
}
.card-header { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: var(--light-color); font-weight: bold; }
.table-dark { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: var(--light-color); }
</style>

<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header">Reporte de Difuntos</div>
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>CI</th>
                        <th>Doliente</th>
                        <th>Fecha Fallecimiento</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($datos as $difunto)
                        <tr>
                            <td>{{ $difunto->persona->nombre ?? '—' }} {{ $difunto->persona->apellido ?? '' }}</td>
                            <td>{{ $difunto->persona->ci ?? '—' }}</td>
                            <td>{{ optional($difunto->doliente)->nombre ?? '—' }} {{ optional($difunto->doliente)->apellido ?? '' }}</td>
                            <td>{{ $difunto->fecha_fallecimiento ?? '—' }}</td>
                            <td>{{ ucfirst($difunto->estado ?? '—') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No se encontraron registros.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
