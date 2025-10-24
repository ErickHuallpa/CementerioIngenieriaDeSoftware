@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-book me-2"></i> Programación de entierros</h2>
        <a href="{{ route('difunto.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle me-1"></i> Nueva Programación
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
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre Difunto</th>
                        <th>Doliente</th>
                        <th>Nicho</th>
                        <th>Fecha Fallecimiento</th>
                        <th>Fecha Entierro</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($difuntos as $difunto)
                        <tr>
                            <td>{{ $difunto->persona->nombre }} {{ $difunto->persona->apellido }}</td>
                            <td>{{ optional($difunto->doliente)->nombre }} {{ optional($difunto->doliente)->apellido }}</td>

                            <td>
                                @if($difunto->nicho)
                                    {{ optional($difunto->nicho->pabellon)->nombre ?? '—' }} -
                                    F{{ $difunto->nicho->fila ?? '—' }}C{{ $difunto->nicho->columna ?? '—' }}
                                @else
                                    <span class="text-muted">Sin nicho</span>
                                @endif
                            </td>

                            <td>{{ $difunto->fecha_fallecimiento ?? '—' }}</td>
                            <td>{{ $difunto->fecha_entierro ?? '—' }}</td>
                            <td>
                                <span class="badge bg-success">{{ ucfirst(str_replace('_', ' ', $difunto->estado ?? '—')) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('difunto.downloadPdf', $difunto->id_difunto) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No hay difuntos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
