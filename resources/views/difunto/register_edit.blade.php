@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">
        {{ isset($difunto) ? 'Editar Nicho del Difunto' : 'Registrar/Asignar Difunto' }}
    </h2>

    <form method="POST" action="{{ isset($difunto) ? route('difunto.update', $difunto->id_difunto) : route('difunto.store') }}">
        @csrf
        @if(isset($difunto)) @method('PUT') @endif

        @if(!isset($difunto))
            <div class="mb-3">
                <label class="form-label">Seleccione un fallecido ya registrado sin nicho</label>
                <select name="id_difunto_existente" class="form-select" id="difunto_existente">
                    <option value="">-- Ninguno --</option>
                    @foreach($difuntosSinNicho as $f)
                        <option value="{{ $f->id_difunto }}"
                            data-nombre="{{ $f->persona->nombre }}"
                            data-apellido="{{ $f->persona->apellido }}"
                            data-ci="{{ $f->persona->ci }}"
                            data-doliente="{{ $f->id_doliente }}"
                            data-trabajador="{{ optional($f->programacionesEntierro->first())->id_trabajador }}"
                            data-fecha="{{ $f->fecha_fallecimiento }}">
                            {{ $f->persona->nombre }} {{ $f->persona->apellido }} ({{ $f->fecha_fallecimiento }})
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Si selecciona un fallecido, los campos de registro se llenarán automáticamente.</small>
            </div>

            <div id="camposRegistro">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellido</label>
                        <input type="text" name="apellido" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">CI (Opcional)</label>
                        <input type="text" name="ci" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Doliente</label>
                        <select name="id_doliente" class="form-select">
                            <option value="">Seleccione...</option>
                            @foreach($dolientes as $doliente)
                                <option value="{{ $doliente->id_persona }}">
                                    {{ $doliente->nombre }} {{ $doliente->apellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Trabajador</label>
                        <select name="id_trabajador" class="form-select">
                            <option value="">Seleccione...</option>
                            @foreach($trabajadores as $trabajador)
                                <option value="{{ $trabajador->id_persona }}">
                                    {{ $trabajador->nombre }} {{ $trabajador->apellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha Fallecimiento</label>
                        <input type="date" name="fecha_fallecimiento" class="form-control">
                    </div>
                </div>
            </div>
        @endif

        <div class="col-md-6 mt-4">
            <label class="form-label">Nicho</label>
            <select name="id_nicho" class="form-select" required>
                <option value="">Seleccione...</option>
                @foreach($nichosDisponibles as $nicho)
                    <option value="{{ $nicho->id_nicho }}"
                        @if(isset($difunto) && $difunto->id_nicho == $nicho->id_nicho) selected @endif>
                        {{ optional($nicho->pabellon)->nombre ?? 'Sin Pabellón' }} - 
                        F{{ $nicho->fila }}C{{ $nicho->columna }} (Bs. {{ $nicho->costo_alquiler ?? 0 }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mt-4">
            <a href="{{ route('difunto.index') }}" class="btn btn-secondary">Cancelar</a>
            <button class="btn btn-success" type="submit">
                {{ isset($difunto) ? 'Guardar Cambios' : 'Registrar/Asignar' }}
            </button>
        </div>
    </form>
</div>

<script>
    const selectDifunto = document.getElementById('difunto_existente');
    const camposRegistro = document.getElementById('camposRegistro');

    selectDifunto.addEventListener('change', function() {
        if (this.value) {
            camposRegistro.style.display = 'block';
            const option = this.selectedOptions[0];
            camposRegistro.querySelector('input[name="nombre"]').value = option.dataset.nombre;
            camposRegistro.querySelector('input[name="apellido"]').value = option.dataset.apellido;
            camposRegistro.querySelector('input[name="ci"]').value = option.dataset.ci;
            camposRegistro.querySelector('select[name="id_doliente"]').value = option.dataset.doliente;
            camposRegistro.querySelector('select[name="id_trabajador"]').value = option.dataset.trabajador;
            camposRegistro.querySelector('input[name="fecha_fallecimiento"]').value = option.dataset.fecha;
        } else {
            camposRegistro.querySelectorAll('input, select').forEach(el => el.value = '');
        }
    });
</script>
@endsection
