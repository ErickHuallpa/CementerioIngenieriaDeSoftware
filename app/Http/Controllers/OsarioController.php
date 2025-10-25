<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pabellon;
use App\Models\Osario;
use App\Models\ContratoAlquiler;
use App\Models\Difunto;
use App\Models\ProgramacionEntierro;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OsarioController extends Controller
{
    public function trasladoForm()
    {
        $contratosElegibles = ContratoAlquiler::with(['difunto', 'nicho'])
            ->where('estado', 'activo')
            ->whereDate('fecha_fin', '<=', Carbon::now()->addMonth())
            ->get();

        $osariosDisponibles = Osario::where('estado', 'disponible')->get();

        $trabajadores = \App\Models\Persona::where('id_tipo_persona', 6)->get();

        return view('osario.traslado', compact('contratosElegibles', 'osariosDisponibles', 'trabajadores'));
    }

    public function trasladoStore(Request $request)
    {
        $request->validate([
            'id_contrato' => 'required|exists:contrato_alquiler,id_contrato',
            'id_osario' => 'required|exists:osario,id_osario',
            'id_trabajador' => 'required|exists:persona,id_persona',
            'observacion' => 'nullable|string|max:255',
        ]);

        $contrato = ContratoAlquiler::with(['difunto', 'nicho'])->findOrFail($request->id_contrato);
        $osario = Osario::findOrFail($request->id_osario);
        $difunto = $contrato->difunto;

        if (!$difunto || !$contrato->nicho) {
            return redirect()->back()->with('error', 'Datos incompletos para realizar el traslado.');
        }

        $fechaFinContrato = Carbon::parse($contrato->fecha_fin);
        if ($fechaFinContrato->greaterThan(Carbon::now()->addMonth())) {
            return redirect()->back()->with('error', 'El contrato no puede ser trasladado todavía.');
        }

        DB::transaction(function () use ($difunto, $contrato, $osario, $request) {

            $renovaciones = $contrato->renovaciones ?? 0;
            $contrato->update([
                'estado' => 'renovado',
                'renovaciones' => $renovaciones + 1
            ]);

            if ($difunto->id_nicho && $difunto->nicho) {
                $difunto->nicho->update([
                    'estado' => 'disponible',
                    'fecha_ocupacion' => null,
                    'fecha_vencimiento' => null,
                ]);
            }

            $difunto->update([
                'id_nicho' => null,
                'estado' => 'osario',
            ]);

            $fechaIngreso = Carbon::now();
            $fechaSalida = $fechaIngreso->copy()->addYears(5);
            $osario->update([
                'id_difunto' => $difunto->id_difunto,
                'estado' => 'ocupado',
                'fecha_ingreso' => $fechaIngreso->toDateString(),
                'fecha_salida' => $fechaSalida->toDateString(),
                'costo' => $osario->costo ?? 0,
            ]);

            ContratoAlquiler::create([
                'id_difunto' => $difunto->id_difunto,
                'id_osario' => $osario->id_osario,
                'fecha_inicio' => $fechaIngreso->toDateString(),
                'fecha_fin' => $fechaSalida->toDateString(),
                'renovaciones' => 0,
                'monto' => $osario->costo ?? 0,
                'estado' => 'activo',
                'boleta_numero' => 'B-' . strtoupper(uniqid()),
            ]);

            ProgramacionEntierro::create([
                'id_difunto' => $difunto->id_difunto,
                'id_trabajador' => $request->id_trabajador,
                'fecha_programada' => $fechaIngreso->toDateString(),
                'hora_programada' => '10:00:00',
                'estado' => 'pendiente',
            ]);

        });

        return redirect()->route('osario.traslado.form')->with('success', 'Traslado a osario registrado correctamente.');
    }
}
