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
        $pabellones = Pabellon::where('tipo', 'comun')->get();
        return view('nicho.register', compact('pabellones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pabellon' => 'required|exists:pabellon,id_pabellon',
            'fila' => 'required|integer|in:1,2,3',
            'columna' => 'required|string|in:A,B,C,D,E,F',
            'posicion' => 'required|in:superior,medio,inferior',
        ]);
        $existe = Nicho::where('id_pabellon', $request->id_pabellon)
            ->where('fila', $request->fila)
            ->where('columna', $request->columna)
            ->where('posicion', $request->posicion)
            ->first();

        if ($existe) {
            return redirect()->back()->with('error', 'Este nicho ya está registrado.')
                ->with('nichoExistente', $existe);
        }
        $costo = match($request->posicion) {
            'superior' => 521,
            'medio' => 621,
            'inferior' => 721,
        };

        Nicho::create([
            'id_pabellon' => $request->id_pabellon,
            'fila' => $request->fila,
            'columna' => $request->columna,
            'posicion' => $request->posicion,
            'costo_alquiler' => $costo,
            'estado' => 'disponible',
            'fecha_ocupacion' => null,
            'fecha_vencimiento' => null,
        ]);

        return redirect()->back()->with('success', 'Nicho registrado correctamente.');
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
