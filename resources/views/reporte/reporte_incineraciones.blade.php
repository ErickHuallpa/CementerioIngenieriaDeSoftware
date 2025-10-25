@extends('layouts.app')

@section('content')
<style>
:root { --primary-color: #2c3e50; --secondary-color: #34495e; --accent-color: #16a085; --light-color: #ecf0f1; }
.card-header { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: var(--light-color); font-weight: bold; }
.table-dark { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: var(--light-color); }
</style>

<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header">Reporte de Incineraciones</div>
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Difunto</th>
                        <th>CI</th>
                        <th>Responsable</th>
                        <th>Fecha Incineración</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($datos as $incineracion)
                        <tr>
                            <td>{{ $incineracion->difunto->persona->nombre ?? '—' }} {{ $incineracion->difunto->persona->apellido ?? '' }}</td>
                            <td>{{ $incineracion->difunto->persona->ci ?? '—' }}</td>
                            <td>{{ $incineracion->responsable->nombre ?? '—' }} {{ $incineracion->responsable->apellido ?? '' }}</td>
                            <td>{{ $incineracion->fecha_incineracion ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No se encontraron registros.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
