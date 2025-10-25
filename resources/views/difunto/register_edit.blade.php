@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary-color: #2c3e50;
        --secondary-color: #34495e;
        --accent-color: #16a085;
        --light-color: #ecf0f1;
    }

    .form-container {
        background-color: var(--light-color);
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    h2 {
        color: var(--primary-color);
        font-weight: 700;
    }

    .form-label {
        font-weight: 600;
        color: var(--secondary-color);
    }

    .form-control:disabled, .form-select:disabled {
        background-color: #f5f5f5;
        opacity: 1;
    }

    .btn-primary, .btn-success {
        background: var(--accent-color);
        border: none;
    }

    .btn-primary:hover, .btn-success:hover {
        background: #138d75;
    }

    .alert-success {
        background-color: #d1f2eb;
        border-color: #16a085;
        color: #2c3e50;
        font-weight: 600;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--secondary-color);
        margin-top: 20px;
        margin-bottom: 10px;
        border-bottom: 2px solid var(--accent-color);
        padding-bottom: 5px;
    }

    .small-text {
        font-size: 0.85rem;
        color: #7f8c8d;
    }
</style>

<div class="container py-4">
    <div class="form-container">
        <h2 class="mb-4 text-center">
            {{ isset($difunto) ? 'Editar Nicho del Difunto' : 'Registrar/Asignar Difunto' }}
        </h2>

        <form method="POST" action="{{ isset($difunto) ? route('difunto.update', $difunto->id_difunto) : route('difunto.store') }}">
            @csrf
            @if(isset($difunto)) @method('PUT') @endif
            <div class="section-title">1. Selección de Nicho</div>
            <div class="col-md-12 mb-4">
                @if(isset($nichoSeleccionado))
                    @php
                        $nicho = $nichosDisponibles->firstWhere('id_nicho', $nichoSeleccionado);
                    @endphp
                    <input type="hidden" name="id_nicho" value="{{ $nichoSeleccionado }}">
                    <div class="alert alert-success">
                        Seleccionado: {{ optional($nicho->pabellon)->nombre ?? 'Sin Pabellón' }} - F{{ $nicho->fila }}C{{ $nicho->columna }}
                    </div>
                    <a href="{{ route('difunto.mapa_nicho') }}" class="btn btn-primary">Cambiar Nicho</a>
                @else
                    <a href="{{ route('difunto.mapa_nicho') }}" id="btnSeleccionarNicho" class="btn btn-primary">
                        Seleccionar Nicho desde el mapa
                    </a>
                    <small class="small-text d-block mt-1">Debes seleccionar primero un nicho antes de registrar/editar al difunto.</small>
                @endif
            </div>
            <div id="camposDifunto" style="display: {{ isset($nichoSeleccionado) ? 'block' : 'none' }};">
                <div class="section-title">2. Datos del Difunto</div>
                @if(!isset($difunto))
                    <div class="mb-3">
                        <label class="form-label">Seleccione un fallecido ya registrado sin nicho</label>
                        <select name="id_difunto_existente" class="form-select" id="difunto_existente" {{ isset($nichoSeleccionado) ? '' : 'disabled' }}>
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
                        <small class="small-text">Si selecciona un fallecido, los campos de registro se llenarán automáticamente.</small>
                    </div>

                    <div id="camposRegistro">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="nombre" class="form-control" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Apellido</label>
                                <input type="text" name="apellido" class="form-control" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">CI (Opcional)</label>
                                <input type="text" name="ci" class="form-control" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Doliente</label>
                                <select name="id_doliente" class="form-select" disabled>
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
                                <select name="id_trabajador" class="form-select" disabled>
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
                                <input type="date" name="fecha_fallecimiento" class="form-control" disabled>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('difunto.index') }}" class="btn btn-secondary">Cancelar</a>
                <button class="btn btn-success" type="submit" {{ isset($nichoSeleccionado) ? '' : 'disabled' }}>
                    {{ isset($difunto) ? 'Guardar Cambios' : 'Registrar/Asignar' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const camposDifunto = document.getElementById('camposDifunto');
    const submitButton = document.querySelector('button[type="submit"]');
    const selectDifunto = document.getElementById('difunto_existente');
    const camposRegistro = document.getElementById('camposRegistro');
    window.addEventListener('load', () => {
        if ("{{ $nichoSeleccionado ?? '' }}") {
            desbloquearCampos();
        }
    });

    function desbloquearCampos() {
        camposDifunto.style.display = 'block';
        camposDifunto.querySelectorAll('input, select').forEach(el => el.disabled = false);
        submitButton.disabled = false;
    }
    if(selectDifunto){
        selectDifunto.addEventListener('change', function() {
            if (this.value) {
                camposRegistro.querySelector('input[name="nombre"]').value = this.selectedOptions[0].dataset.nombre;
                camposRegistro.querySelector('input[name="apellido"]').value = this.selectedOptions[0].dataset.apellido;
                camposRegistro.querySelector('input[name="ci"]').value = this.selectedOptions[0].dataset.ci;
                camposRegistro.querySelector('select[name="id_doliente"]').value = this.selectedOptions[0].dataset.doliente;
                camposRegistro.querySelector('select[name="id_trabajador"]').value = this.selectedOptions[0].dataset.trabajador;
                camposRegistro.querySelector('input[name="fecha_fallecimiento"]').value = this.selectedOptions[0].dataset.fecha;
            } else {
                camposRegistro.querySelectorAll('input, select').forEach(el => el.value = '');
            }
        });
    }
</script>
@endsection
