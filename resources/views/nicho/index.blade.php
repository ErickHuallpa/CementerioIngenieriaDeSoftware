@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4"><i class="fas fa-map-marked-alt me-2"></i>Mapa de Nichos</h3>

    @forelse($pabellones as $pabellon)
        <div class="card mb-4 shadow-lg border-0">
            <div class="card-header text-white" style="background: linear-gradient(90deg, #2c3e50, #16a085);">
                <h5 class="mb-0"><i class="fas fa-building-columns me-2"></i>{{ $pabellon->nombre ?? 'Pabellón '.$pabellon->id_pabellon }}</h5>
                <small>{{ $pabellon->descripcion ?? 'Sin descripción' }}</small>
            </div>

            <div class="card-body bg-light">
                <div class="row justify-content-center">
                    @for($fila = 1; $fila <= 3; $fila++)
                        <div class="col-12 mb-3">
                            <div class="d-flex justify-content-center gap-3 flex-wrap">
                                @foreach(['A','B','C','D','E','F'] as $col)
                                    @php
                                        $nicho = $pabellon->nichos->first(fn($n) => strtoupper($n->columna) === $col && $n->fila == $fila);
                                        $difunto = $nicho?->difuntos?->last();
                                        $persona = $difunto?->persona;

                                        $porVencer = $nicho && $nicho->fecha_vencimiento
                                            && \Carbon\Carbon::parse($nicho->fecha_vencimiento)->between($hoy, $unMes);
                                    @endphp

                                    <div class="card text-center shadow-sm position-relative"
                                         style="width: 120px; height: 160px; border-radius: 10px;
                                                background: {{ $nicho ? match($nicho->estado) {
                                                    'disponible' => '#d4edda',
                                                    'ocupado' => '#f8d7da',
                                                    'por_vencer' => '#fff3cd',
                                                    'vencido' => '#d6d8d9',
                                                    default => '#f0f0f0'
                                                } : '#f0f0f0' }};
                                                border: 2px solid #ccc;">
                                        <div style="font-size: 0.8rem; font-weight: bold; margin-top: 5px;">
                                            {{ $col }}{{ $fila }}
                                        </div>

                                        @if($difunto)
                                            <div class="p-2" style="font-size: 0.8rem;">
                                                <i class="fas fa-cross text-danger mb-1"></i>
                                                <div style="font-weight: bold;">{{ $persona->nombre }} {{ $persona->apellido }}</div>
                                                <div class="text-muted" style="font-size: 0.7rem;">
                                                    ✝ {{ \Carbon\Carbon::parse($difunto->fecha_fallecimiento)->format('d/m/Y') }}
                                                </div>
                                                <div class="text-muted" style="font-size: 0.7rem;">
                                                    ⚰️ {{ \Carbon\Carbon::parse($difunto->fecha_entierro)->format('d/m/Y') }}
                                                </div>
                                            </div>
                                        @elseif($nicho)
                                            <div class="p-2">
                                                <div class="text-muted small">{{ ucfirst($nicho->estado) }}</div>
                                            </div>
                                        @else
                                            <div class="p-2">
                                                <div class="text-muted small">Sin registro</div>
                                            </div>
                                        @endif

                                        {{-- Icono de advertencia por vencer --}}
                                        @if($porVencer)
                                            <div style="position:absolute; top:5px; right:5px; color:#856404;">
                                                <i class="fas fa-exclamation-triangle" title="Por vencer en un mes"></i>
                                            </div>
                                        @endif

                                        {{-- Efecto de "piedra" --}}
                                        <div style="position:absolute; bottom:5px; left:0; right:0; font-size:0.7rem; color:#666;">
                                            <i class="fas fa-church"></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center">
            No hay pabellones registrados.
        </div>
    @endforelse
</div>
@endsection
