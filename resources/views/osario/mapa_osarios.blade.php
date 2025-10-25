@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary-color: #2c3e50;
        --secondary-color: #34495e;
        --accent-color: #16a085;
        --ocupado-color: #7f8c8d;
        --vacío-color: #bdc3c7;
        --light-color: #ecf0f1;
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: var(--light-color);
    }

    .osario-btn {
        width: 60px;
        height: 60px;
        font-weight: bold;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s;
        font-size: 0.8rem;
    }

    .osario-btn:hover {
        transform: scale(1.1);
    }

    .osario-disponible {
        background-color: var(--accent-color);
        color: #fff;
    }

    .osario-ocupado {
        background-color: var(--ocupado-color);
        color: #fff;
    }

    .osario-vacio {
        background-color: var(--vacío-color);
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
    .estado-ocupado { background-color: var(--ocupado-color); }
    .estado-vacio { background-color: var(--vacío-color); }
</style>

<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-th-large me-2"></i> Seleccionar Osario</h2>

    <div class="mb-3 estado-leyenda">
        <strong>Leyenda:</strong>
        <span class="estado-disponible"></span> Disponible ✔
        <span class="estado-ocupado ml-3"></span> Ocupado ✖
        <span class="estado-vacio ml-3"></span> Vacío
    </div>

    @php
        $columnas = ['A','B','C','D','E','F','G','H','I','J'];
        $totalFilas = 5;
    @endphp

    @foreach($pabellones as $pabellon)
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <strong>{{ $pabellon->nombre }}</strong>
            </div>
            <div class="card-body text-center">
                @for($fila = 1; $fila <= $totalFilas; $fila++)
                    <div class="d-flex justify-content-center mb-2">
                        @foreach($columnas as $columna)
                            @php
                                $osario = $pabellon->osarios->first(function($o) use ($fila, $columna) {
                                    return $o->fila == $fila && $o->columna == $columna;
                                });

                                if($osario) {
                                    $clase = $osario->estado === 'ocupado' ? 'osario-ocupado' : 'osario-disponible';
                                } else {
                                    $clase = 'osario-vacio';
                                }
                            @endphp

                            @if($osario && $osario->estado !== 'ocupado')
                                <a href="{{ route('osario.traslado.form', ['id_osario' => $osario->id_osario]) }}"
                                   class="btn osario-btn {{ $clase }}"
                                   title="Fila {{ $fila }} Col {{ $columna }}">
                                    {{ $columna }}{{ $fila }}
                                </a>
                            @else
                                <button class="btn osario-btn {{ $clase }}" disabled title="{{ $osario ? 'Ocupado' : 'Vacío' }}">
                                    {{ $columna }}{{ $fila }}
                                </button>
                            @endif
                        @endforeach
                    </div>
                @endfor
            </div>
        </div>
    @endforeach

    <div class="text-center mt-4">
        <a href="{{ route('osario.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</div>
@endsection
