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
    public function index()
    {
        $osarios = Osario::with(['difunto.persona', 'difunto.doliente', 'pabellon'])->whereNotNull('id_difunto')->get();
        return view('osario.index', compact('osarios'));
    }
    public function create()
    {
        $pabellones = Pabellon::where('tipo', 'osario')->get();
        return view('osario.register', compact('pabellones'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'id_pabellon' => 'required|exists:pabellon,id_pabellon',
            'fila' => 'required|integer|min:1|max:5',
            'columna' => 'required|in:A,B,C,D,E,F,G,H,I,J',
        ]);

        $existe = Osario::where('id_pabellon', $request->id_pabellon)
            ->where('fila', $request->fila)
            ->where('columna', $request->columna)
            ->first();

        if ($existe) {
            return redirect()->back()->with('error', 'Este osario ya está registrado.')
                ->with('osarioExistente', $existe);
        }

        Osario::create([
            'id_pabellon' => $request->id_pabellon,
            'fila' => $request->fila,
            'columna' => $request->columna,
            'estado' => 'disponible',
            'costo' => 400,
            'fecha_ingreso' => null,
            'fecha_salida' => null,
        ]);

        return redirect()->back()->with('success', 'Osario registrado correctamente.');
    }

    public function downloadPdf($id)
    {
        $osario = Osario::with(['difunto.persona', 'difunto.doliente', 'pabellon'])->findOrFail($id);
        $difunto = $osario->difunto;
        $contrato = ContratoAlquiler::where('id_difunto', $difunto->id_difunto ?? 0)
            ->where('id_osario', $osario->id_osario)
            ->latest()
            ->first();

        $usuario = auth()->user();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.contrato_osario', compact('osario', 'contrato', 'usuario', 'difunto'));
        return $pdf->download('contrato_osario_'.$osario->id_osario.'.pdf');
    }
    public function mapa()
    {
        $pabellones = Pabellon::with(['osarios' => function($q) {
            $q->orderBy('fila')->orderBy('columna');
        }])->where('tipo', 'osario')->get();

        return view('osario.mapa_osarios', compact('pabellones'));
    }
    public function trasladoForm(Request $request)
    {
        $contratosElegibles = ContratoAlquiler::with(['difunto', 'nicho'])
            ->where('estado', 'activo')
            ->whereDate('fecha_fin', '<=', Carbon::now()->addMonth())
            ->get();
        $osariosDisponibles = Osario::where('estado', 'disponible')->get();
        $trabajadores = \App\Models\Persona::where('id_tipo_persona', 6)->get();
        $id_osario_preseleccionado = $request->id_osario ?? null;

        return view('osario.traslado', compact('contratosElegibles', 'osariosDisponibles', 'trabajadores', 'id_osario_preseleccionado'));
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
                'boleta_numero' => $this->generarNumeroBoleta(),
            ]);
            ProgramacionEntierro::create([
                'id_difunto' => $difunto->id_difunto,
                'id_trabajador' => $request->id_trabajador,
                'fecha_programada' => $fechaIngreso->toDateString(),
                'hora_programada' => '10:00:00',
                'estado' => 'pendiente',
            ]);

        });
        return redirect()->route('osario.index')->with('success', 'Traslado a osario registrado correctamente.');
    }
    private function generarNumeroBoleta()
    {
        $ultimo = \App\Models\ContratoAlquiler::latest('id_contrato')->first();
        $numero = $ultimo ? intval(substr($ultimo->boleta_numero, 2)) + 1 : 1;
        return 'B-' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }

}
