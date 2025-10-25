<?php

namespace App\Http\Controllers;

use App\Models\Nicho;
use App\Models\Pabellon;
use App\Models\Osario;
use Illuminate\Http\Request;

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

    public function mapa()
    {
        $hoy = now();
        $unMes = $hoy->copy()->addMonth();

        $pabellones = Pabellon::with([
            'nichos.difuntos.persona',
            'osarios.difunto.persona'
        ])->get();

        return view('nicho.index', compact('pabellones', 'hoy', 'unMes'));
    }
}
