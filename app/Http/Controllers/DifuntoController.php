<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Difunto;
use App\Models\Nicho;
use App\Models\ContratoAlquiler;
use App\Models\ProgramacionEntierro;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class DifuntoController extends Controller
{
    public function index()
    {
        $difuntos = Difunto::with(['persona', 'doliente', 'nicho.pabellon'])->get();
        $dolientes = Persona::whereHas('tipoPersona', function ($q) {
            $q->whereRaw('LOWER(nombre_tipo) = ?', ['doliente']);
        })->get();
        $nichosDisponibles = Nicho::with('pabellon')
            ->whereRaw('LOWER(estado) = ?', ['disponible'])
            ->get();
        $trabajadores = Persona::where('id_tipo_persona', 2)->get();

        return view('difunto.index', compact('difuntos', 'dolientes', 'nichosDisponibles', 'trabajadores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            // if provided, CI must be unique in persona table
            'ci' => 'nullable|string|max:20|unique:persona,ci',
            'fecha_fallecimiento' => 'required|date',
            'id_doliente' => 'required|exists:persona,id_persona',
            'id_nicho' => 'required|exists:nicho,id_nicho',
            'id_trabajador' => 'required|exists:persona,id_persona',
        ], [
            'ci.unique' => 'La C.I. ingresada ya existe en el sistema. Si es necesario, edite la persona existente o use otra C.I.',
        ]);

        $contrato = null;
        $difunto = null;

        $result = DB::transaction(function () use ($request) {
            // Create a new Persona (CI uniqueness is validated above, so an insert won't violate constraints)
            $persona = Persona::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'ci' => $request->ci ?? null,
                'telefono' => null,
                'direccion' => null,
                'email' => null,
                'id_tipo_persona' => null,
            ]);

            $fechaFallecimiento = $request->fecha_fallecimiento;
            $fechaEntierro = date('Y-m-d', strtotime('+1 day'));
            $fechaFin = date('Y-m-d', strtotime($fechaEntierro . ' +5 years'));

            $difunto = Difunto::create([
                'id_persona' => $persona->id_persona,
                'id_doliente' => $request->id_doliente,
                'id_nicho' => $request->id_nicho,
                'fecha_fallecimiento' => $fechaFallecimiento,
                'fecha_entierro' => $fechaEntierro,
                'estado' => 'en_nicho',
            ]);

            $nicho = Nicho::findOrFail($request->id_nicho);
            $nicho->update([
                'estado' => 'ocupado',
                'fecha_ocupacion' => now(),
                'fecha_vencimiento' => $fechaFin,
            ]);

            $contrato = ContratoAlquiler::create([
                'id_difunto' => $difunto->id_difunto,
                'id_nicho' => $nicho->id_nicho,
                'fecha_inicio' => $fechaEntierro,
                'fecha_fin' => $fechaFin,
                'renovaciones' => 0,
                'monto' => $nicho->costo_alquiler ?? 0,
                'estado' => 'activo',
                'boleta_numero' => 'B-' . strtoupper(uniqid()),
            ]);

            ProgramacionEntierro::create([
                'id_difunto' => $difunto->id_difunto,
                'id_trabajador' => $request->id_trabajador,
                'fecha_programada' => $fechaEntierro,
                'hora_programada' => '10:00:00',
                'estado' => 'pendiente',
            ]);

            return [
                'difunto' => $difunto,
                'contrato' => $contrato,
            ];
        });

        $difunto = $result['difunto'];
        $contrato = $result['contrato'];

        $usuario = Auth::user();
        $difunto->load(['persona', 'doliente', 'nicho.pabellon']);
        $contrato->load('nicho');
        $pdf = Pdf::loadView('pdf.contrato_difunto', compact('difunto', 'contrato', 'usuario'));

        return $pdf->download('Contrato_Difunto_' . $difunto->persona->nombreCompleto . '.pdf');
    }
}
