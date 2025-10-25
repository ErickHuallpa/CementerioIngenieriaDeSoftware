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
        font-size: 1.2rem;
    }

    table thead {
        background: var(--primary-color);
        color: var(--light-color);
    }

    table tbody tr:hover {
        background-color: var(--light-color);
        transition: 0.3s;
    }

    .btn-pdf {
        background-color: var(--primary-color);
        color: #fff;
        transition: 0.3s;
    }

    .btn-pdf:hover {
        background-color: var(--accent-color);
        color: #fff;
    }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-cube me-2"></i> Difuntos en Osario</h2>
        <a href="{{ route('osario.traslado.form') }}" class="btn btn-success">
            <i class="fas fa-plus-circle me-1"></i> Registrar Traslado
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header">Lista de Difuntos en Osario</div>
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nombre Difunto</th>
                        <th>Doliente</th>
                        <th>Osario</th>
                        <th>Fecha Ingreso</th>
                        <th>Fecha Salida</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($osarios as $osario)
                        @php $difunto = $osario->difunto; @endphp
                        @if($difunto)
                        <tr>
                            <td>{{ $difunto->persona->nombre ?? '' }} {{ $difunto->persona->apellido ?? '' }}</td>
                            <td>{{ optional($difunto->doliente)->nombre }} {{ optional($difunto->doliente)->apellido }}</td>
                            <td>
                                {{ optional($osario->pabellon)->nombre ?? '—' }} -
                                F{{ $osario->fila ?? '—' }}C{{ $osario->columna ?? '—' }}
                            </td>
                            <td>{{ $osario->fecha_ingreso ?? '—' }}</td>
                            <td>{{ $osario->fecha_salida ?? '—' }}</td>
                            <td><span class="badge bg-info">En Osario</span></td>
                            <td>
                                <a href="{{ route('osario.downloadPdf', $osario->id_osario) }}" class="btn btn-sm btn-pdf">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </td>
                        </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No hay difuntos en osario.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
