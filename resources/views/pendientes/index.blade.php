@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-tasks me-2"></i> Pendientes de Entierro</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            @if($programaciones->isEmpty())
                <div class="text-center text-muted">No tiene trabajos asignados.</div>
            @else
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Difunto</th>
                            <th>CI Difunto</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Nicho</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($programaciones as $p)
                            <tr>
                                <td>{{ $p->id_programacion }}</td>
                                <td>{{ optional($p->difunto->persona)->nombre ?? '—' }} {{ optional($p->difunto->persona)->apellido ?? '' }}</td>
                                <td>{{ optional($p->difunto->persona)->ci ?? '—' }}</td>
                                <td>{{ $p->fecha_programada }}</td>
                                <td>{{ \Carbon\Carbon::parse($p->hora_programada)->format('H:i') }}</td>
                                <td>
                                    @if($p->difunto && $p->difunto->nicho)
                                        {{ optional($p->difunto->nicho->pabellon)->nombre ?? '—' }} -
                                        F{{ $p->difunto->nicho->fila ?? '—' }}C{{ $p->difunto->nicho->columna ?? '—' }}
                                    @else
                                        <span class="text-muted">Sin nicho</span>
                                    @endif
                                </td>
                                <td>
                                    @if($p->estado === 'pendiente')
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                    @elseif($p->estado === 'completado')
                                        <span class="badge bg-success">Completado</span>
                                    @elseif($p->estado === 'retrasado')
                                        <span class="badge bg-danger">Retrasado</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $p->estado }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($p->estado === 'pendiente' || $p->estado === 'retrasado')
                                        <form action="{{ route('pendientes.complete', $p->id_programacion) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Confirmar marcar como completado?')">
                                                <i class="fas fa-check"></i> Completar
                                            </button>
                                        </form>
                                    @elseif($p->estado === 'completado')
                                        <a href="{{ route('pendientes.ticket', $p->id_programacion) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Ver Comprobante
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
