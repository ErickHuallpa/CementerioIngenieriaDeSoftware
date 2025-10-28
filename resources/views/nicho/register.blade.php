@extends('layouts.app')

@section('content')
<div class="header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Registrar Nicho</h2>
        <p class="text-muted mb-0">Registro individual de nichos por pabellón</p>
    </div>
</div>

<div class="card card-dashboard p-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('nicho.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="id_pabellon" class="form-label">Pabellón</label>
            <select id="id_pabellon" name="id_pabellon" class="form-select" required>
                <option value="">Seleccione un pabellón</option>
                @foreach ($pabellones as $pabellon)
                    <option value="{{ $pabellon->id_pabellon }}">{{ $pabellon->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Fila</label>
            <div class="d-flex gap-2 flex-wrap">
                @foreach(range(1,3) as $fila)
                    <div class="form-check">
                        <input class="form-check-input fila-radio" type="radio" name="fila" id="fila_{{ $fila }}" value="{{ $fila }}" required>
                        <label class="form-check-label" for="fila_{{ $fila }}">{{ $fila }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Columna</label>
            <div class="d-flex gap-2 flex-wrap">
                @foreach(range('A','F') as $col)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="columna" id="columna_{{ $col }}" value="{{ $col }}" required>
                        <label class="form-check-label" for="columna_{{ $col }}">{{ $col }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <input type="hidden" name="posicion" id="posicion">

        <div class="text-end">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-success">Registrar Nicho</button>
        </div>
    </form>

    @if(isset($nichoExistente))
        <div class="alert alert-warning mt-3">
            Este nicho ya existe: Fila {{ $nichoExistente->fila }}, Columna {{ $nichoExistente->columna }}, Posición: {{ $nichoExistente->posicion }}, Pabellón: {{ $nichoExistente->pabellon->nombre }}
        </div>
    @endif
</div>

<script>
document.querySelectorAll('.fila-radio').forEach(radio => {
    radio.addEventListener('change', function() {
        let posicion = '';
        switch(this.value) {
            case '1': posicion = 'superior'; break;
            case '2': posicion = 'medio'; break;
            case '3': posicion = 'inferior'; break;
        }
        document.getElementById('posicion').value = posicion;
    });
});
</script>
@endsection
