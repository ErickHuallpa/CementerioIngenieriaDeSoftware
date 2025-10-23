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
        $difuntos = Difunto::with(['persona', 'doliente'])->whereNull('id_nicho')->get();
        return view('fallecido.index', compact('difuntos'));
    }

    public function create()
    {
        $difuntosIds = Difunto::pluck('id_persona')->toArray();

        // Solo personas que aún tienen un tipo definido (ej. dolientes, empleados)
        $dolientes = Persona::whereHas('tipoPersona', fn($q) => $q->whereNotNull('id_tipo_persona'))
            ->whereNotIn('id_persona', $difuntosIds)
            ->get();

        return view('fallecido.register', compact('dolientes'));
    }

    // Buscar persona existente por CI
    public function buscarPersona(Request $request)
    {
        $ci = $request->get('query');
        if(!$ci) return response()->json([]);

        $persona = Persona::where('ci', $ci)
            ->whereNotNull('id_tipo_persona')
            ->first();

        return response()->json($persona ? [$persona] : []);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_persona_existente' => 'nullable|exists:persona,id_persona',
            'nombre' => 'required_without:id_persona_existente|string|max:100',
            'apellido' => 'required_without:id_persona_existente|string|max:100',
            'ci' => 'nullable|string|max:20',
            'id_doliente' => 'required|exists:persona,id_persona',
            'fecha_fallecimiento' => 'required|date',
        ]);

        DB::transaction(function() use ($request) {
            if ($request->id_persona_existente) {
                $persona = Persona::findOrFail($request->id_persona_existente);

                // Marcar como difunto → id_tipo_persona null
                $persona->update([
                    'ci' => $request->ci ?? $persona->ci,
                    'email' => null,
                    'direccion' => null,
                    'telefono' => null,
                    'id_tipo_persona' => null,
                ]);
            } else {
                $persona = Persona::create([
                    'nombre' => $request->nombre,
                    'apellido' => $request->apellido,
                    'ci' => $request->ci ?? null,
                    'email' => null,
                    'direccion' => null,
                    'telefono' => null,
                    'id_tipo_persona' => null,
                ]);
            }

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
        $difuntosIds = Difunto::pluck('id_persona')->toArray();

        $dolientes = Persona::whereHas('tipoPersona', fn($q) => $q->whereNotNull('id_tipo_persona'))
            ->whereNotIn('id_persona', $difuntosIds)
            ->get();

        return view('fallecido.register', compact('difunto', 'dolientes'));
    }

    public function update(Request $request, $id)
    {
        $difunto = Difunto::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'ci' => 'nullable|string|max:20',
            'id_doliente' => 'required|exists:persona,id_persona',
            'fecha_fallecimiento' => 'required|date',
        ]);

        DB::transaction(function() use ($request, $difunto) {
            $difunto->persona->update([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'ci' => $request->ci ?? null,
                'email' => null,
                'direccion' => null,
                'telefono' => null,
                'id_tipo_persona' => null,
            ]);

            $difunto->update([
                'id_doliente' => $request->id_doliente,
                'fecha_fallecimiento' => $request->fecha_fallecimiento,
            ]);
        });

        return redirect()->route('fallecido.index')->with('success', 'Difunto actualizado correctamente.');
    }
}
