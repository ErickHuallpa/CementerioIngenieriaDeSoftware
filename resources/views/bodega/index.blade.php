@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-warehouse me-2"></i> Bodega - Traslados</h2>
        <a href="{{ route('bodega.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle me-1"></i> Trasladar a Bodega
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            @if($bodegas->isEmpty())
                <div class="text-center text-muted">No hay registros en bodega.</div>
            @else
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Difunto</th>
                            <th>CI</th>
                            <th>Fecha Ingreso</th>
                            <th>Fecha Salida</th>
                            <th>Destino (origen)</th>
                            <th>Estado Difunto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bodegas as $b)
                            <tr>
                                <td>{{ optional($b->difunto->persona)->nombre ?? '—' }} {{ optional($b->difunto->persona)->apellido ?? '' }}</td>
                                <td>{{ optional($b->difunto->persona)->ci ?? '—' }}</td>
                                <td>{{ $b->fecha_ingreso ?? '—' }}</td>
                                <td>{{ $b->fecha_salida ?? '—' }}</td>
                                <td>{{ ucfirst($b->destino ?? '—') }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $b->difunto->estado ?? '—')) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
