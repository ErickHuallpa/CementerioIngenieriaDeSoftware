@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-fire me-2"></i> Incineraciones</h2>
        <a href="{{ route('incineracion.create') }}" class="btn btn-primary-custom">
            <i class="fas fa-plus-circle me-1"></i>  Incineración Individual</a>
        <a href="{{ route('incineracion.colectiva') }}" class="btn btn-primary-custom">
            <i class="fas fa-users-cog me-1"></i> Incineración Colectiva
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Difunto</th>
                        <th>Responsable</th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>Costo</th>
                        <th>Registrado por</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($incineraciones as $inc)
                        <tr>
                            <td>{{ $inc->difunto->persona->nombre_completo ?? ($inc->difunto->persona->nombre ?? '—') }}</td>
                            <td>{{ $inc->responsable->nombre_completo ?? '—' }}</td>
                            <td>{{ ucfirst($inc->tipo) }}</td>
                            <td>{{ $inc->fecha_incineracion }}</td>
                            <td>Bs. {{ number_format($inc->costo, 2) }}</td>
                            <td>{{ $inc->created_at ? $inc->created_at->format('Y-m-d') : '—' }}</td>
                            <td>
                                <a href="{{ route('incineracion.downloadPdf', $inc->id_incineracion) }}" target="_blank" class="btn btn-sm btn-success">PDF</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No hay registros de incineraciones.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
