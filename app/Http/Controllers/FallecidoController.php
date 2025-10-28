<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Difunto;
use Illuminate\Support\Facades\DB;

class FallecidoController extends Controller
{
    public function index(Request $request)
    {
        $orden = $request->get('orden', 'apellido');
        $tipo = $request->get('tipo', 'asc');

        $difuntos = Difunto::with(['persona', 'doliente'])
            ->join('persona', 'difunto.id_persona', '=', 'persona.id_persona')
            ->leftJoin('persona as dol', 'difunto.id_doliente', '=', 'dol.id_persona');
        switch ($orden) {
            case 'nombre_difunto':
            case 'apellido_difunto':
                $difuntos = $difuntos->orderBy('persona.apellido', $tipo)
                                     ->orderBy('persona.nombre', $tipo);
                break;
            case 'doliente':
                $difuntos = $difuntos->orderBy('dol.apellido', $tipo)
                                     ->orderBy('dol.nombre', $tipo);
                break;
            case 'fecha_fallecimiento':
                $difuntos = $difuntos->orderBy('difunto.fecha_fallecimiento', $tipo);
                break;
            case 'estado':
                $difuntos = $difuntos->orderBy('difunto.estado', $tipo);
                break;
            default:
                $difuntos = $difuntos->orderBy('persona.apellido', 'asc');
        }

        $difuntos = $difuntos->select('difunto.*')->get();
        $nextTipo = $tipo === 'asc' ? 'desc' : 'asc';

        return view('fallecido.index', compact('difuntos', 'orden', 'tipo', 'nextTipo'));
    }

    public function create()
    {
        $difuntosIds = Difunto::pluck('id_persona')->toArray();
        $dolientes = Persona::where('id_tipo_persona', 3)
            ->whereNotIn('id_persona', $difuntosIds)
            ->get();

        return view('fallecido.register', compact('dolientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_persona_existente' => 'nullable|exists:persona,id_persona',
            'nombre' => 'required_without:id_persona_existente|string|max:100|min:3|regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s\'-]+$/u',
            'apellido' => 'required_without:id_persona_existente|string|max:100|min:3|regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s\'-]+$/u',
            'ci' => 'nullable|string|max:20|min:7',
            'id_doliente' => 'required|exists:persona,id_persona',
            'fecha_fallecimiento' => 'required|date',
            'documento_defuncion' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ], [
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'apellido.regex' => 'El apellido solo puede contener letras y espacios.',
            'ci.min' => 'La cédula de identidad debe tener al menos 7 caracteres.',
            'documento_defuncion.mimes' => 'El documento debe ser un archivo de tipo: PDF, JPG, JPEG, PNG, DOC, DOCX.',
            'documento_defuncion.max' => 'El documento no debe superar los 5MB.',
        ]);

        DB::transaction(function() use ($request) {
            if ($request->id_persona_existente) {
                $persona = Persona::findOrFail($request->id_persona_existente);
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

            $documentoPath = null;
            if ($request->hasFile('documento_defuncion')) {
                $documentoPath = $request->file('documento_defuncion')->store('documentos_defuncion', 'public');
            }

            Difunto::create([
                'id_persona' => $persona->id_persona,
                'id_doliente' => $request->id_doliente,
                'id_nicho' => null,
                'fecha_fallecimiento' => $request->fecha_fallecimiento,
                'fecha_entierro' => null,
                'estado' => 'registrado',
                'documento_defuncion' => $documentoPath,
            ]);
        });

        return redirect()->route('fallecido.index')->with('success', 'Difunto registrado correctamente.');
    }

    public function edit($id)
    {
        $difunto = Difunto::with(['persona', 'doliente'])->findOrFail($id);
        $difuntosIds = Difunto::pluck('id_persona')->toArray();

        $dolientes = Persona::where('id_tipo_persona', 3)
            ->whereNotIn('id_persona', $difuntosIds)
            ->get();

        return view('fallecido.register', compact('difunto', 'dolientes'));
    }

    public function update(Request $request, $id)
    {
        $difunto = Difunto::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100|min:3|regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s\'-]+$/u',
            'apellido' => 'required|string|min:3|max:100|regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s\'-]+$/u',
            'ci' => 'nullable|string|max:20|min:7',
            'id_doliente' => 'required|exists:persona,id_persona',
            'fecha_fallecimiento' => 'required|date',
            'documento_defuncion' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ], [
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'apellido.regex' => 'El apellido solo puede contener letras y espacios.',
            'ci.min' => 'La cédula de identidad debe tener al menos 7 caracteres.',
            'documento_defuncion.mimes' => 'El documento debe ser un archivo de tipo: PDF, JPG, JPEG, PNG, DOC, DOCX.',
            'documento_defuncion.max' => 'El documento no debe superar los 5MB.',
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

            $documentoPath = $difunto->documento_defuncion; // Mantener el documento existente por defecto
            if ($request->hasFile('documento_defuncion')) {
                $documentoPath = $request->file('documento_defuncion')->store('documentos_defuncion', 'public');
            }

            $difunto->update([
                'id_doliente' => $request->id_doliente,
                'fecha_fallecimiento' => $request->fecha_fallecimiento,
                'documento_defuncion' => $documentoPath,
            ]);
        });

        return redirect()->route('fallecido.index')->with('success', 'Difunto actualizado correctamente.');
    }
}
