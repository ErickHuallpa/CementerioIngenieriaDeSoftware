<?php

namespace App\Http\Controllers;

use App\Models\Difunto;
use App\Models\Incineracion;
use App\Models\ContratoAlquiler;
use App\Models\ServicioExtra;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class IncineracionController extends Controller
{
    public function index()
    {
        $incineraciones = Incineracion::with(['difunto.persona', 'responsable'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('incineracion.index', compact('incineraciones'));
    }

    public function create()
    {
        $difuntos = Difunto::with('persona')
            ->where('estado', '!=', 'incinerado')
            ->orderBy('id_difunto', 'desc')
            ->get();

        $trabajadores = Persona::where('id_tipo_persona', 4)
            ->orderBy('nombre')
            ->get();

        return view('incineracion.register_edit', compact('difuntos', 'trabajadores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_difunto' => 'required|exists:difunto,id_difunto',
            'id_responsable' => 'required|exists:persona,id_persona',
            'fecha_incineracion' => 'required|date',
            'tipo' => 'required|in:individual,colectiva',
            'costo' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($request, &$incineracion) {
            $difunto = Difunto::with('nicho')->findOrFail($request->id_difunto);
            $nicho = $difunto->nicho;
            $difunto->update([
                'estado' => 'incinerado',
                'id_nicho' => null,
            ]);
            if ($nicho) {
                $nicho->update([
                    'estado' => 'disponible',
                    'fecha_ocupacion' => null,
                    'fecha_vencimiento' => null,
                ]);
            }
            if ($contrato = ContratoAlquiler::where('id_difunto', $difunto->id_difunto)
                ->where('estado', 'activo')->first()) {
                $contrato->update([
                    'estado' => 'cancelado',
                    'fecha_fin' => now()->toDateString(),
                ]);
            }
            $incineracion = Incineracion::create([
                'id_difunto' => $difunto->id_difunto,
                'id_responsable' => $request->id_responsable,
                'fecha_incineracion' => $request->fecha_incineracion,
                'tipo' => $request->tipo,
                'costo' => $request->costo,
            ]);
            ServicioExtra::create([
                'id_difunto' => $difunto->id_difunto,
                'id_trabajador' => $request->id_responsable,
                'tipo_servicio' => 'incineracion',
                'fecha_servicio' => $request->fecha_incineracion,
                'costo' => $request->costo,
                'observaciones' => $request->observaciones,
            ]);
        });

        return redirect()->route('incineracion.index')
            ->with('success', 'Incineración registrada correctamente.');
    }

    public function downloadPdf($id)
    {
        $incineracion = Incineracion::with(['difunto.persona', 'responsable'])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.incineracion', [
            'incineracion' => $incineracion,
            'difunto' => $incineracion->difunto,
            'responsable' => $incineracion->responsable,
            'usuario' => Auth::user(),
        ])->setPaper('a4', 'portrait');

        ini_set('memory_limit', '256M');

        return $pdf->download('incineracion_' . now()->format('Ymd_His') . '.pdf');
    }
}
