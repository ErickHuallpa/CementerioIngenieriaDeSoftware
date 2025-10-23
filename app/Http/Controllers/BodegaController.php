<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Bodega;
use App\Models\Difunto;
use App\Models\ContratoAlquiler;
use App\Models\Nicho;

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

    /**
     * Registrar traslado a bodega.
     *
     * Reglas:
     *  - Solo si difunto.estado es 'registrado', 'en_nicho' o 'osario'
     *  - Si tenia nicho -> liberar nicho (estado = disponible, limpiar fechas)
     *  - Si tenia contrato activo -> marcar contrato.estado = 'vencido'
     *  - Crear registro en bodega con fecha_ingreso now() y fecha_salida now()->addMonth()
     *  - Actualizar difunto:id_nicho = null y estado = 'en_bodega'
     */
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
}
