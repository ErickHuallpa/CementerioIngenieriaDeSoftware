@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-boxes"></i> Trasladar Difunto a Bodega</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('bodega.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Seleccione el difunto a trasladar (registrado, en nicho u osario)</label>
                    <select name="id_difunto" class="form-select" id="selectDifunto" required>
                        <option value="">-- Seleccione --</option>
                        @foreach($difuntosElegibles as $d)
                            <option value="{{ $d->id_difunto }}"
                                data-ci="{{ $d->persona->ci ?? '' }}"
                                data-estado="{{ $d->estado }}"
                                data-pabellon="{{ optional(optional($d->nicho)->pabellon)->nombre ?? '' }}"
                                data-fila="{{ optional($d->nicho)->fila ?? '' }}"
                                data-columna="{{ optional($d->nicho)->columna ?? '' }}">
                                {{ $d->persona->nombre }} {{ $d->persona->apellido }} — {{ $d->estado }} 
                                @if($d->nicho)
                                    ({{ optional(optional($d->nicho)->pabellon)->nombre ?? '—' }} - F{{ optional($d->nicho)->fila ?? '—' }}C{{ optional($d->nicho)->columna ?? '—' }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="infoSeleccion" class="mb-3" style="display:none;">
                    <h6>Información seleccionada</h6>
                    <p><strong>CI:</strong> <span id="infoCi">—</span></p>
                    <p><strong>Estado actual:</strong> <span id="infoEstado">—</span></p>
                    <p id="infoNichoRow" style="display:none;"><strong>Nicho:</strong> <span id="infoNicho">—</span></p>
                </div>

                <div class="mb-3">
                    <label class="form-label">Destino (opcional)</label>
                    <select name="destino" class="form-select">
                        <option value="">Autodetectar (por estado actual)</option>
                        <option value="nicho">Nicho</option>
                        <option value="osario">Osario</option>
                    </select>
                    <small class="text-muted">Se usará para registrar el origen del traslado en el registro de bodega.</small>
                </div>

                <div class="mt-3">
                    <a href="{{ route('bodega.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-success">Trasladar a Bodega</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const select = document.getElementById('selectDifunto');
    const infoRow = document.getElementById('infoSeleccion');
    const infoCi = document.getElementById('infoCi');
    const infoEstado = document.getElementById('infoEstado');
    const infoNichoRow = document.getElementById('infoNichoRow');
    const infoNicho = document.getElementById('infoNicho');

    select.addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        if (!opt || !opt.value) {
            infoRow.style.display = 'none';
            return;
        }

        infoRow.style.display = 'block';
        infoCi.textContent = opt.dataset.ci || '—';
        infoEstado.textContent = opt.dataset.estado || '—';

        if (opt.dataset.pabellon) {
            infoNichoRow.style.display = 'block';
            infoNicho.textContent = `${opt.dataset.pabellon} - F${opt.dataset.fila}C${opt.dataset.columna}`;
        } else {
            infoNichoRow.style.display = 'none';
        }
    });
</script>
@endsection
