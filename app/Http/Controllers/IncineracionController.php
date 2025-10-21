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
        $incineraciones = Incineracion::with(['difunto.persona', 'responsable'])->orderBy('created_at', 'desc')->get();

        $difuntos = Difunto::with('persona')
            ->where('estado', '!=', 'incinerado')
            ->orderBy('id_difunto', 'desc')
            ->get();

        $trabajadores = Persona::where('id_tipo_persona', 4)->orderBy('nombre')->get();

        return view('incineracion.index', compact('incineraciones', 'difuntos', 'trabajadores'));
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

        $incineracion = null;

        DB::transaction(function () use ($request, &$incineracion) {
            $difunto = Difunto::findOrFail($request->id_difunto);
            $difunto->update(['estado' => 'incinerado']);
            $contratoActivo = ContratoAlquiler::where('id_difunto', $difunto->id_difunto)
                ->where('estado', 'activo')
                ->first();
            if ($contratoActivo) {
                $contratoActivo->update([
                    'estado' => 'cancelado',
                    'fecha_fin' => now()->toDateString(),
                ]);
            }
            if ($difunto->id_nicho) {
                $difunto->nicho->update([
                    'estado' => 'disponible',
                    'fecha_ocupacion' => null,
                    'fecha_vencimiento' => null,
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
        $incineracion = Incineracion::with(['difunto.persona', 'responsable'])
            ->find($incineracion->id_incineracion);

        $data = [
            'incineracion' => $incineracion,
            'difunto' => $incineracion->difunto,
            'responsable' => $incineracion->responsable,
            'usuario' => Auth::user(),
        ];

        $pdf = Pdf::loadView('pdf.incineracion', $data)->setPaper('a4', 'portrait');

        return $pdf->download(
            'incineracion_' . $incineracion->id_difunto . '_' . now()->format('Ymd_His') . '.pdf'
        );
    }

}
