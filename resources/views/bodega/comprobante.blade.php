@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3><i class="fas fa-file-alt me-2"></i> Comprobante de Retiro</h3>

    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <h5><strong>Datos del Difunto</strong></h5>
            <p><strong>Nombre:</strong> {{ $bodega->difunto->persona->nombre_completo }}</p>
            <p><strong>CI:</strong> {{ $bodega->difunto->persona->ci }}</p>
            <p><strong>Fecha de Retiro:</strong> {{ $bodega->fecha_salida }}</p>
            <hr>

            <h5><strong>Doliente Responsable del Retiro</strong></h5>
            <p><strong>Nombre:</strong> {{ $bodega->difunto->doliente->nombre_completo ?? 'No registrado' }}</p>
            <p><strong>CI:</strong> {{ $bodega->difunto->doliente->ci ?? 'No registrado' }}</p>
            <hr>

            <h5><strong>Responsable del Servicio</strong></h5>
            <p><strong>Nombre:</strong> {{ $responsable->nombre_completo }}</p>

            <div class="mt-4">
                <a href="{{ route('bodega.comprobante.pdf', $bodega->id_bodega) }}" class="btn btn-primary">
                    <i class="fas fa-file-download me-1"></i> Descargar PDF
                </a>
                <a href="{{ route('bodega.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>
</div>
@endsection
