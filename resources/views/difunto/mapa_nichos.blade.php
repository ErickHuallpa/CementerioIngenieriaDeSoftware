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
    }

    .nicho-btn {
        width: 50px;
        height: 50px;
        font-weight: bold;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s;
    }

    .nicho-btn:hover {
        transform: scale(1.1);
    }

    .nicho-disponible {
        background-color: var(--accent-color);
        color: #fff;
    }

    .nicho-ocupado {
        background-color: #7f8c8d;
        color: #fff;
    }

    .nicho-por-vencer {
        background-color: #f1c40f;
        color: #2c3e50;
    }

    .estado-leyenda span {
        display: inline-block;
        width: 20px;
        height: 20px;
        margin-right: 5px;
        border-radius: 4px;
        vertical-align: middle;
    }

    .estado-disponible { background-color: var(--accent-color); }
    .estado-ocupado { background-color: #7f8c8d; }
    .estado-por-vencer { background-color: #f1c40f; }
</style>

<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-th-large me-2"></i> Seleccionar Nicho</h2>

    <div class="mb-3 estado-leyenda">
        <strong>Leyenda:</strong>
        <span class="estado-disponible"></span> Disponible ✔
        <span class="estado-ocupado ml-3"></span> Ocupado ✖
        <span class="estado-por-vencer ml-3"></span> Por Vencer ⚠
    </div>

    @foreach($pabellones as $pabellon)
        @if(strtolower($pabellon->tipo) == 'comun')
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <strong>{{ $pabellon->nombre }}</strong>
            </div>
            <div class="card-body text-center">
                @foreach([1,2,3] as $fila)
                    <div class="d-flex justify-content-center mb-2">
                        @foreach(['A','B','C','D','E','F'] as $columna)
                            @php
                                $nicho = $pabellon->nichos
                                    ->where('fila', $fila)
                                    ->where('columna', $columna)
                                    ->first();
                            @endphp

                            @if($nicho)
                                @php
                                    $clase = '';
                                    $icono = '';
                                    switch($nicho->estado) {
                                        case 'disponible':
                                            $clase = 'nicho-disponible';
                                            $icono = '✔';
                                            break;
                                        case 'ocupado':
                                            $clase = 'nicho-ocupado';
                                            $icono = '✖';
                                            break;
                                        case 'por_vencer':
                                            $clase = 'nicho-por-vencer';
                                            $icono = '⚠';
                                            break;
                                    }
                                @endphp

                                @if($nicho->estado == 'disponible' || $nicho->estado == 'por_vencer')
                                    <a href="{{ route('difunto.create', ['nicho' => $nicho->id_nicho]) }}"
                                       class="btn nicho-btn {{ $clase }} mx-1" title="Fila {{ $fila }} Col {{ $columna }}">
                                        {{ $fila }}{{ $columna }} {{ $icono }}
                                    </a>
                                @else
                                    <button class="btn nicho-btn {{ $clase }} mx-1" disabled title="Ocupado">
                                        {{ $fila }}{{ $columna }} {{ $icono }}
                                    </button>
                                @endif
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    @endforeach

    <div class="text-center mt-4">
        <a href="{{ route('difunto.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</div>
@endsection
