<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Difunto;
use Illuminate\Support\Facades\DB;

class FallecidoController extends Controller
{
    public function index()
    {
        $difuntos = Difunto::with(['persona', 'doliente'])
            ->whereNull('id_nicho')
            ->get();

        return view('fallecido.index', compact('difuntos'));
    }

    public function create()
    {
        $dolientes = Persona::whereHas('tipoPersona', function ($q) {
            $q->whereRaw('LOWER(nombre_tipo) = ?', ['doliente']);
        })->get();

        return view('fallecido.register', compact('dolientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'fecha_fallecimiento' => 'required|date',
            'id_doliente' => 'required|exists:persona,id_persona',
        ]);

        DB::transaction(function() use ($request) {
            $persona = Persona::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'ci' => $request->ci ?? null,
                'telefono' => null,
                'direccion' => null,
                'email' => null,
                'id_tipo_persona' => null,
            ]);

            Difunto::create([
                'id_persona' => $persona->id_persona,
                'id_doliente' => $request->id_doliente,
                'id_nicho' => null,
                'fecha_fallecimiento' => $request->fecha_fallecimiento,
                'fecha_entierro' => null,
                'estado' => 'registrado',
            ]);
        });

        return redirect()->route('fallecido.index')->with('success', 'Difunto registrado correctamente.');
    }

    public function edit($id)
    {
        $difunto = Difunto::with(['persona', 'doliente'])->findOrFail($id);

        $dolientes = Persona::whereHas('tipoPersona', function ($q) {
            $q->whereRaw('LOWER(nombre_tipo) = ?', ['doliente']);
        })->get();

        return view('fallecido.register', compact('difunto', 'dolientes'));
    }

    public function update(Request $request, $id)
    {
        $difunto = Difunto::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'fecha_fallecimiento' => 'required|date',
            'id_doliente' => 'required|exists:persona,id_persona',
        ]);

        DB::transaction(function() use ($request, $difunto) {
            $difunto->persona->update([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'ci' => $request->ci ?? null,
            ]);

            $difunto->update([
                'id_doliente' => $request->id_doliente,
                'fecha_fallecimiento' => $request->fecha_fallecimiento,
            ]);
        });

        return redirect()->route('fallecido.index')->with('success', 'Difunto actualizado correctamente.');
    }
}
