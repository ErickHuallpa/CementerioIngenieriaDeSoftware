<?php

namespace App\Http\Controllers;

use App\Models\Nicho;
use App\Models\Pabellon;
use App\Models\Osario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PorVencerNotification;

class NichoController extends Controller
{
    public function create()
    {
        $pabellones = Pabellon::all();
        return view('nicho.register', compact('pabellones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pabellon' => 'required|exists:pabellon,id_pabellon',
            'fila' => 'required|integer|min:1',
            'columna' => 'required|string|max:1',
            'posicion' => 'required|in:superior,medio,inferior',
            'costo_alquiler' => 'required|numeric|min:0',
            'estado' => 'required|in:disponible,ocupado,por_vencer,vencido',
            'fecha_ocupacion' => 'nullable|date',
            'fecha_vencimiento' => 'nullable|date|after_or_equal:fecha_ocupacion',
        ]);

        Nicho::create($request->all());

        return redirect()->route('dashboard')->with('success', 'Nicho registrado correctamente.');
    }

    public function porVencer()
    {
        $hoy = now();
        $dosSemanas = $hoy->copy()->addWeeks(2);

        $nichosPorVencer = Nicho::with(['difuntos.persona', 'difuntos.doliente', 'pabellon'])
            ->where('estado', 'ocupado')
            ->whereNotNull('fecha_vencimiento')
            ->whereBetween('fecha_vencimiento', [$hoy, $dosSemanas])
            ->get();

        $osariosPorVencer = Osario::with(['difunto.persona', 'difunto.doliente', 'pabellon'])
            ->where('estado', 'ocupado')
            ->whereNotNull('fecha_salida')
            ->whereBetween('fecha_salida', [$hoy, $dosSemanas])
            ->get();

        return view('nicho.por_vencer', compact('nichosPorVencer', 'osariosPorVencer'));
    }

    public function enviarNotificacionNicho($id, $difunto_id)
    {
        $nicho = Nicho::with(['difuntos.persona', 'difuntos.doliente', 'pabellon'])
            ->findOrFail($id);

        // Buscar por id_difunto y no por id
        $difunto = $nicho->difuntos->where('id_difunto', $difunto_id)->first();

        if (!$difunto) {
            return back()->with('error', 'Difunto no encontrado en este nicho.');
        }

        if ($difunto->doliente && $difunto->doliente->email) {
            Mail::to($difunto->doliente->email)
                ->send(new PorVencerNotification($nicho, null, $difunto));
        }

        return back()->with('success', 'Notificación enviada correctamente al doliente seleccionado.');
    }

    public function enviarNotificacionOsario($id)
    {
        $osario = Osario::with(['difunto.persona', 'difunto.doliente', 'pabellon'])->findOrFail($id);

        if ($osario->difunto && $osario->difunto->doliente && $osario->difunto->doliente->email) {
            Mail::to($osario->difunto->doliente->email)
                ->send(new PorVencerNotification(null, $osario, $osario->difunto));
        }

        return back()->with('success', 'Notificación enviada correctamente al doliente del osario.');
    }

    public function mapa()
    {
        $pabellones = Pabellon::with(['nichos.difuntos.persona', 'osarios.difunto.persona'])->get();

        return view('nicho.index', compact('pabellones'));
    }

}
