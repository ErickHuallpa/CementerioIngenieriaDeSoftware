@extends('layouts.app')

@section('title', 'Detalles de Incineración')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detalles de Incineración</h3>
                    <div class="card-tools">
                        <a href="{{ route('incineracion.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Información del Difunto</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Nombre:</th>
                                    <td>{{ $incineracion->difunto->persona->nombre_completo ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Fecha Fallecimiento:</th>
                                    <td>{{ $incineracion->difunto->fecha_fallecimiento ? \Carbon\Carbon::parse($incineracion->difunto->fecha_fallecimiento)->format('d/m/Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Estado Actual:</th>
                                    <td>
                                        <span class="badge badge-success">Incinerado</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h4>Información de la Incineración</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Responsable:</th>
                                    <td>{{ $incineracion->responsable->nombre_completo ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Fecha Incineración:</th>
                                    <td>{{ $incineracion->fecha_incineracion->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Tipo:</th>
                                    <td>
                                        <span class="badge badge-{{ $incineracion->tipo == 'individual' ? 'primary' : 'warning' }}">
                                            {{ ucfirst($incineracion->tipo) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Costo Incineración:</th>
                                    <td>S/ {{ number_format($incineracion->costo, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($incineracion->difunto->serviciosExtras->count() > 0)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h4>Servicios Extra Relacionados</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tipo Servicio</th>
                                            <th>Fecha</th>
                                            <th>Costo</th>
                                            <th>Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($incineracion->difunto->serviciosExtras as $servicio)
                                        <tr>
                                            <td>{{ ucfirst($servicio->tipo_servicio) }}</td>
                                            <td>{{ $servicio->fecha_servicio->format('d/m/Y') }}</td>
                                            <td>S/ {{ number_format($servicio->costo, 2) }}</td>
                                            <td>{{ $servicio->observaciones ?? 'Sin observaciones' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
