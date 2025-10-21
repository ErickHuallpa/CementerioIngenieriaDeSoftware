@extends('layouts.app')

@section('title', 'Registrar Incineración')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Registrar Nueva Incineración</h3>
                    <div class="card-tools">
                        <a href="{{ route('incineracion.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <form id="incineracionForm" action="{{ route('incineracion.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Sección: Selección de Difunto -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id_difunto">Seleccionar Difunto *</label>
                                    <select name="id_difunto" id="id_difunto" class="form-control select2" required>
                                        <option value="">Seleccione un difunto</option>
                                        @foreach($difuntos as $difunto)
                                            <option value="{{ $difunto->id_difunto }}">
                                                {{ $difunto->persona->nombre_completo }} -
                                                Fallecido: {{ \Carbon\Carbon::parse($difunto->fecha_fallecimiento)->format('d/m/Y') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Información del Difunto (se carga via AJAX) -->
                        <div id="difuntoInfo" class="card card-info" style="display: none;">
                            <div class="card-header">
                                <h3 class="card-title">Información del Difunto</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>Nombre:</strong> <span id="info_nombre"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Fecha Fallecimiento:</strong> <span id="info_fecha_fallecimiento"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Nicho:</strong> <span id="info_nicho"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección: Datos de la Incineración -->
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="id_responsable">Responsable *</label>
                                    <select name="id_responsable" id="id_responsable" class="form-control" required>
                                        <option value="">Seleccione responsable</option>
                                        @foreach($responsables as $responsable)
                                            <option value="{{ $responsable->id_persona }}">
                                                {{ $responsable->nombre_completo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fecha_incineracion">Fecha de Incineración *</label>
                                    <input type="date" name="fecha_incineracion" id="fecha_incineracion"
                                           class="form-control" value="{{ old('fecha_incineracion', date('Y-m-d')) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tipo">Tipo de Incineración *</label>
                                    <select name="tipo" id="tipo" class="form-control" required>
                                        <option value="individual">Individual</option>
                                        <option value="colectiva">Colectiva</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Sección: Costos -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="costo_incineracion">Costo de Incineración (S/) *</label>
                                    <input type="number" name="costo_incineracion" id="costo_incineracion"
                                           class="form-control" step="0.01" min="0"
                                           value="{{ old('costo_incineracion') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="costo_servicio_extra">Costo de Servicio Extra (S/)</label>
                                    <input type="number" name="costo_servicio_extra" id="costo_servicio_extra"
                                           class="form-control" step="0.01" min="0"
                                           value="{{ old('costo_servicio_extra') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Sección: Observaciones -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="observaciones_servicio">Observaciones del Servicio</label>
                                    <textarea name="observaciones_servicio" id="observaciones_servicio"
                                              class="form-control" rows="3"
                                              placeholder="Observaciones adicionales...">{{ old('observaciones_servicio') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Registrar Incineración
                        </button>
                        <a href="{{ route('incineracion.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Inicializar Select2
        $('.select2').select2();

        // Cargar información del difunto cuando se selecciona
        $('#id_difunto').change(function() {
            var difuntoId = $(this).val();

            if (difuntoId) {
                $.ajax({
                    url: '{{ url("incineracion/difunto") }}/' + difuntoId,
                    type: 'GET',
                    success: function(response) {
                        if (response.difunto) {
                            $('#info_nombre').text(response.persona.nombre_completo);
                            $('#info_fecha_fallecimiento').text(
                                new Date(response.difunto.fecha_fallecimiento).toLocaleDateString('es-ES')
                            );

                            if (response.contrato_activo && response.contrato_activo.nicho) {
                                $('#info_nicho').text(response.contrato_activo.nicho.codigo_nicho);
                            } else {
                                $('#info_nicho').text('No asignado');
                            }

                            $('#difuntoInfo').show();
                        }
                    },
                    error: function() {
                        alert('Error al cargar la información del difunto');
                    }
                });
            } else {
                $('#difuntoInfo').hide();
            }
        });

        // Validación del formulario
        $('#incineracionForm').validate({
            rules: {
                id_difunto: { required: true },
                id_responsable: { required: true },
                fecha_incineracion: { required: true, date: true },
                tipo: { required: true },
                costo_incineracion: { required: true, number: true, min: 0 }
            },
            messages: {
                id_difunto: "Por favor seleccione un difunto",
                id_responsable: "Por favor seleccione un responsable",
                fecha_incineracion: "Por favor ingrese una fecha válida",
                tipo: "Por favor seleccione el tipo de incineración",
                costo_incineracion: "Por favor ingrese un costo válido"
            }
        });
    });
</script>
@endsection
