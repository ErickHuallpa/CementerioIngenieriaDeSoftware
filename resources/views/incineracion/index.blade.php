@extends('layouts.app')

@section('title', 'Registro de Incineraciones')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Registro de Incineraciones</h3>
                    <div class="card-tools">
                        <a href="{{ route('incineracion.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nueva Incineración
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Difunto</th>
                                    <th>Responsable</th>
                                    <th>Fecha Incineración</th>
                                    <th>Tipo</th>
                                    <th>Costo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($incineraciones as $incineracion)
                                <tr>
                                    <td>{{ $incineracion->id_incineracion }}</td>
                                    <td>{{ $incineracion->difunto->persona->nombre_completo ?? 'N/A' }}</td>
                                    <td>{{ $incineracion->responsable->nombre_completo ?? 'N/A' }}</td>
                                    <td>{{ $incineracion->fecha_incineracion->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $incineracion->tipo == 'individual' ? 'primary' : 'warning' }}">
                                            {{ ucfirst($incineracion->tipo) }}
                                        </span>
                                    </td>
                                    <td>S/ {{ number_format($incineracion->costo, 2) }}</td>
                                    <td>
                                        <a href="{{ route('incineracion.show', $incineracion->id_incineracion) }}"
                                           class="btn btn-info btn-sm" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No hay registros de incineración</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $incineraciones->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.table').DataTable({
            "paging": false,
            "searching": true,
            "ordering": true,
            "info": false,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "search": "Buscar:",
                "zeroRecords": "No se encontraron registros",
                "emptyTable": "No hay datos disponibles"
            }
        });
    });
</script>
@endsection
