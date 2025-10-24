<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Bodega;
use App\Models\Difunto;
use App\Models\ContratoAlquiler;
use App\Models\Nicho;
use PDF;

class BodegaController extends Controller
{
    public function index()
    {
        $bodegas = Bodega::with(['difunto.persona', 'difunto.nicho.pabellon'])
            ->orderBy('fecha_ingreso', 'desc')
            ->get();

        return view('bodega.index', compact('bodegas'));
    }

    public function create()
    {
        $difuntosElegibles = Difunto::with(['persona', 'nicho.pabellon'])
            ->whereIn('estado', ['registrado', 'en_nicho', 'osario'])
            ->get();

        return view('bodega.register', compact('difuntosElegibles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_difunto' => 'required|exists:difunto,id_difunto',
            'destino' => 'nullable|string'
        ]);

        $difunto = Difunto::with(['nicho', 'contratos'])->findOrFail($request->id_difunto);
        if (!in_array($difunto->estado, ['registrado', 'en_nicho', 'osario'])) {
            return redirect()->route('bodega.create')->with('error', 'Solo se puede trasladar a bodega si el difunto está registrado, en nicho u osario.');
        }

        DB::transaction(function () use ($difunto, $request, &$bodega) {
            $now = now();
            $fechaSalida = $now->copy()->addMonth()->toDateString();
            $destino = $request->destino ?? 'incinerado';
            $contrato = ContratoAlquiler::where('id_difunto', $difunto->id_difunto)
                ->where('estado', 'activo')
                ->first();

            if ($contrato) {
                $contrato->update(['estado' => 'vencido']);
            }
            if ($difunto->id_nicho && $difunto->nicho) {
                $difunto->nicho->update([
                    'estado' => 'disponible',
                    'fecha_ocupacion' => null,
                    'fecha_vencimiento' => null,
                ]);
            }
            $difunto->update([
                'id_nicho' => null,
                'estado' => 'en_bodega',
            ]);
            $bodega = Bodega::create([
                'id_difunto' => $difunto->id_difunto,
                'fecha_ingreso' => $now->toDateString(),
                'fecha_salida' => $fechaSalida,
                'destino' => $destino,
            ]);
        });

        return redirect()->route('bodega.index')->with('success', 'Traslado a bodega realizado correctamente.');
    }

    public function retirar($id)
    {
        $bodega = Bodega::with('difunto')->findOrFail($id);
        $difunto = $bodega->difunto;

        if ($difunto->estado !== 'en_bodega') {
            return redirect()->route('bodega.index')->with('error', 'Solo se pueden retirar difuntos que estén en bodega.');
        }

        DB::transaction(function () use ($bodega, $difunto) {
            $bodega->update([
                'fecha_salida' => now()->toDateString()
            ]);

            $difunto->update([
                'estado' => 'retirado'
            ]);
        });

        return redirect()->route('bodega.index')->with('success', 'El difunto fue retirado correctamente.');
    }

    public function comprobante($id)
    {
        $bodega = Bodega::with(['difunto.persona', 'difunto.doliente'])->findOrFail($id);
        $responsable = auth()->user();

        return view('bodega.comprobante', compact('bodega', 'responsable'));
    }

    public function comprobantePdf($id)
    {
        $bodega = Bodega::with(['difunto.persona', 'difunto.doliente'])->findOrFail($id);
        $responsable = auth()->user();

        $pdf = \PDF::loadView('pdf.comprobante_retiro', compact('bodega', 'responsable'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('comprobante_retiro_'.$bodega->id_bodega.'.pdf');
    }

}
