@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-book me-2"></i> Fallecidos Registrados</h2>
        <a href="{{ route('fallecido.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle me-1"></i> Nuevo Registro
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nombre Difunto</th>
                        <th>Doliente</th>
                        <th>Fecha Fallecimiento</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($difuntos as $difunto)
                        <tr>
                            <td>{{ $difunto->id_difunto }}</td>
                            <td>{{ $difunto->persona->nombre }} {{ $difunto->persona->apellido }}</td>
                            <td>{{ optional($difunto->doliente)->nombre }} {{ optional($difunto->doliente)->apellido }}</td>
                            <td>{{ $difunto->fecha_fallecimiento ?? '—' }}</td>
                            <td><span class="badge bg-info">{{ ucfirst($difunto->estado) }}</span></td>
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
