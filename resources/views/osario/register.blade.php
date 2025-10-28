@extends('layouts.app')

@section('content')
<div class="header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Registrar Osario</h2>
        <p class="text-muted mb-0">Registro individual de osarios para pabellones tipo "osario"</p>
    </div>
</div>

<div class="card card-dashboard p-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('osario.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="id_pabellon" class="form-label">Pabellón</label>
            <select name="id_pabellon" id="id_pabellon" class="form-select" required>
                <option value="">Seleccione un pabellón</option>
                @foreach ($pabellones as $pabellon)
                    <option value="{{ $pabellon->id_pabellon }}">{{ $pabellon->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Fila</label>
            <div class="d-flex gap-2 flex-wrap">
                @for($i=1; $i<=5; $i++)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="fila" id="fila_{{ $i }}" value="{{ $i }}" required>
                        <label class="form-check-label" for="fila_{{ $i }}">{{ $i }}</label>
                    </div>
                @endfor
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Columna</label>
            <div class="d-flex gap-2 flex-wrap">
                @foreach(range('A','J') as $col)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="columna" id="columna_{{ $col }}" value="{{ $col }}" required>
                        <label class="form-check-label" for="columna_{{ $col }}">{{ $col }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary-custom">Registrar Osario</button>
    </form>

    @if(isset($osarioExistente))
        <div class="alert alert-warning mt-3">
            Este osario ya está registrado: Fila {{ $osarioExistente->fila }}, Columna {{ $osarioExistente->columna }}, Pabellón: {{ $osarioExistente->pabellon->nombre }}
        </div>
    @endif
</div>
@endsection
