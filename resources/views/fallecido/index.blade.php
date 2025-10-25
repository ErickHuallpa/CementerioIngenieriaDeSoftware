@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary-color: #2c3e50;
        --secondary-color: #34495e;
        --accent-color: #16a085;
        --light-color: #ecf0f1;
    }
    .table-dark {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: var(--light-color);
    }
    .badge-estado-registrado { background-color: #17a2b8; }
    .badge-estado-en-nicho { background-color: #28a745; }
    .badge-estado-en-bodega { background-color: #ffc107; color: #000; }
    .badge-estado-osario { background-color: #6f42c1; }
    .badge-estado-incinerado { background-color: #dc3545; }

    .btn-success {
        background-color: var(--accent-color);
        border: none;
    }
    .btn-success:hover { background-color: var(--secondary-color); }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-book me-2"></i> Difuntos Registrados</h2>
        <a href="{{ route('fallecido.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle me-1"></i> Nuevo Registro
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre Difunto</th>
                        <th>Doliente</th>
                        <th>Fecha Fallecimiento</th>
                        <th>Estado</th>
                        <th width="100">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($difuntos as $difunto)

                        @php
                            $estadoClass = [
                                'registrado' => 'badge-estado-registrado',
                                'en_nicho' => 'badge-estado-en-nicho',
                                'en_bodega' => 'badge-estado-en-bodega',
                                'osario' => 'badge-estado-osario',
                                'incinerado' => 'badge-estado-incinerado'
                            ][$difunto->estado] ?? 'badge-estado-registrado';
                        @endphp

                        <tr>
                            <td>{{ $difunto->persona->nombre }} {{ $difunto->persona->apellido }}</td>
                            <td>{{ optional($difunto->doliente)->nombre }} {{ optional($difunto->doliente)->apellido }}</td>
                            <td>{{ $difunto->fecha_fallecimiento ?? '—' }}</td>
                            <td><span class="badge {{ $estadoClass }}">{{ ucfirst(str_replace('_', ' ', $difunto->estado)) }}</span></td>
                            <td>
                                <a href="{{ route('fallecido.edit', $difunto->id_difunto) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No hay fallecidos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
