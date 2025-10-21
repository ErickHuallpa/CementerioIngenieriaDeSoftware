<?php

namespace App\Http\Controllers\Incineracion;

use App\Http\Controllers\Controller;
use App\Models\Difunto;
use App\Models\Incineracion;
use App\Models\ContratoAlquiler;
use App\Models\ServicioExtra;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncineracionController extends Controller
{
    public function index()
    {
        $incineraciones = Incineracion::with(['difunto.persona', 'responsable'])
            ->orderBy('fecha_incineracion', 'desc')
            ->paginate(10);

        return view('incineracion.index', compact('incineraciones'));
    }

    public function create()
    {
        // Obtener difuntos que no están incinerados y tienen contrato activo
        $difuntos = Difunto::where('estado', '!=', 'incinerado')
            ->whereHas('contratos', function($query) {
                $query->where('estado', 'activo');
            })
            ->with(['persona', 'contratos' => function($query) {
                $query->where('estado', 'activo');
            }])
            ->get();

        // Obtener trabajadores/responsables (personas que son trabajadores)
        $responsables = Persona::where('tipo', 'trabajador')
            ->orWhere('tipo', 'empleado')
            ->orWhere('tipo', 'administrativo')
            ->get();

        return view('incineracion.create', compact('difuntos', 'responsables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_difunto' => 'required|exists:difunto,id_difunto',
            'id_responsable' => 'required|exists:persona,id_persona',
            'fecha_incineracion' => 'required|date',
            'tipo' => 'required|in:individual,colectiva',
            'costo_incineracion' => 'required|numeric|min:0',
            'costo_servicio_extra' => 'nullable|numeric|min:0',
            'observaciones_servicio' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Paso 1: Cambiar estado del difunto a "incinerado"
            $difunto = Difunto::findOrFail($request->id_difunto);
            $difunto->update(['estado' => 'incinerado']);

            // Paso 2: Cancelar contrato activo si existe
            $contratoActivo = ContratoAlquiler::where('id_difunto', $request->id_difunto)
                ->where('estado', 'activo')
                ->first();

            if ($contratoActivo) {
                $contratoActivo->update(['estado' => 'cancelado']);
            }

            // Paso 3: No se crea nuevo contrato (según especificación)

            // Paso 4: Registrar incineración
            $incineracion = Incineracion::create([
                'id_difunto' => $request->id_difunto,
                'id_responsable' => $request->id_responsable,
                'fecha_incineracion' => $request->fecha_incineracion,
                'tipo' => $request->tipo,
                'costo' => $request->costo_incineracion,
            ]);

            // Paso 5: Registrar servicio extra si hay costo adicional
            if ($request->costo_servicio_extra && $request->costo_servicio_extra > 0) {
                ServicioExtra::create([
                    'id_difunto' => $request->id_difunto,
                    'id_trabajador' => $request->id_responsable,
                    'tipo_servicio' => 'incineracion',
                    'fecha_servicio' => $request->fecha_incineracion,
                    'costo' => $request->costo_servicio_extra,
                    'observaciones' => $request->observaciones_servicio,
                ]);
            }

            DB::commit();

            return redirect()->route('incineracion.index')
                ->with('success', 'Proceso de incineración registrado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al registrar la incineración: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $incineracion = Incineracion::with([
            'difunto.persona',
            'responsable',
            'difunto.serviciosExtras' => function($query) {
                $query->where('tipo_servicio', 'incineracion');
            }
        ])->findOrFail($id);

        return view('incineracion.show', compact('incineracion'));
    }

    public function getDifuntoInfo($id)
    {
        $difunto = Difunto::with([
            'persona',
            'contratos' => function($query) {
                $query->where('estado', 'activo');
            },
            'contratos.nicho'
        ])->findOrFail($id);

        return response()->json([
            'difunto' => $difunto,
            'persona' => $difunto->persona,
            'contrato_activo' => $difunto->contratos->first()
        ]);
    }
}
