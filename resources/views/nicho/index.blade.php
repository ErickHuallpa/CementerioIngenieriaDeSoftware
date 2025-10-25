@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4"><i class="fas fa-map-marked-alt me-2"></i>Mapa de Nichos y Osarios</h3>

    @forelse($pabellones as $pabellon)
        <div class="card mb-4 shadow border-0">
            <div class="card-header text-white" style="background: linear-gradient(90deg, #2c3e50, #16a085);">
                <h5 class="mb-0">
                    <i class="fas fa-building-columns me-2"></i>
                    {{ $pabellon->nombre ?? 'Pabellón '.$pabellon->id_pabellon }}
                    <small class="text-light">({{ ucfirst($pabellon->tipo) }})</small>
                </h5>
                <small>{{ $pabellon->descripcion ?? 'Sin descripción' }}</small>
            </div>

            <div class="card-body bg-light">
                <div class="row justify-content-center">
                    @php
                        $isOsario = $pabellon->tipo === 'osario';

                        // Filas y columnas
                        $maxFila   = $isOsario ? 5 : 3;
                        $columnas  = $isOsario ? range('A','J') : ['A','B','C','D','E','F'];

                        // Tamaños
                        $cardWidth  = $isOsario ? '90px'  : '120px';
                        $cardHeight = $isOsario ? '90px'  : '165px';
                        $fontSize   = $isOsario ? '0.65rem' : '0.8rem';
                    @endphp

                    @for($fila = 1; $fila <= $maxFila; $fila++)
                        <div class="col-12 mb-2">
                            <div class="d-flex justify-content-center gap-2 flex-wrap">

                                @foreach($columnas as $col)
                                    @php
                                        if($isOsario){
                                            $item = $pabellon->osarios->first(fn($o) => strtoupper($o->columna) === $col && $o->fila == $fila);
                                            $difunto = $item?->difunto;
                                        } else {
                                            $item = $pabellon->nichos->first(fn($n) => strtoupper($n->columna) === $col && $n->fila == $fila);
                                            $difunto = $item?->difuntos?->last();
                                        }

                                        $persona = $difunto?->persona;

                                        $color = match($item->estado ?? null) {
                                            'disponible'  => '#c8f7c5',
                                            'ocupado'     => '#f7c5c5',
                                            'por_vencer'  => '#fff6bf',
                                            'vencido'     => '#d6d8d9',
                                            default       => '#efefef'
                                        };
                                    @endphp

                                    <div class="card shadow-sm position-relative text-center"
                                         style="width: {{ $cardWidth }}; height: {{ $cardHeight }};
                                                border-radius: 10px;
                                                background: {{ $color }};
                                                border: 2px solid #bfbfbf;
                                                font-size: {{ $fontSize }};">

                                        <div style="font-weight: bold; margin-top: 4px;">
                                            {{ $col }}{{ $fila }}
                                        </div>

                                        @if($difunto)
                                            <div class="px-1">
                                                <div style="font-weight: bold;">
                                                    {{ $persona->nombre }} {{ $persona->apellido }}
                                                </div>
                                                @unless($isOsario)
                                                    <div class="text-muted" style="font-size: 0.65rem;">
                                                        ✝ {{ \Carbon\Carbon::parse($difunto->fecha_fallecimiento)->format('d/m/Y') }}
                                                    </div>
                                                @endunless
                                            </div>
                                        @else
                                            <div class="text-muted" style="margin-top: 5px;">
                                                {{ $item ? ucfirst($item->estado) : 'Sin registro' }}
                                            </div>
                                        @endif

                                        <div style="position:absolute; bottom:2px; left:0; right:0; color:#555; font-size:0.6rem;">
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
