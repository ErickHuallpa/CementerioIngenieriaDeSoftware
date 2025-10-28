<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\TipoPersona;

class ClienteController extends Controller
{
    public function index()
    {
        $tipos = TipoPersona::whereIn('nombre_tipo', ['Doliente', 'Visitante'])->pluck('id_tipo_persona');
        $clientes = Persona::whereIn('id_tipo_persona', $tipos)->with('tipoPersona')->get();
        
        return view('clientes.index', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|min:3|regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s\'-]+$/u',
            'apellido' => 'required|string|max:100|min:3|regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s\'-]+$/u',
            'ci' => 'required|string|max:20|unique:persona,ci|min:7',
            'telefono' => 'nullable|string|max:20|min:8',
            'direccion' => 'nullable|string|max:50|min:4',
            'email' => 'nullable|email|max:100',
            'id_tipo_persona' => 'required|exists:tipo_persona,id_tipo_persona',
        ], [
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'apellido.regex' => 'El apellido solo puede contener letras y espacios.',
            'ci.min' => 'La cédula de identidad debe tener al menos 7 caracteres.',
            'ci.unique' =>'El CI ya pertenece a otra persona.',
            'telefono.min' => 'El teléfono debe tener al menos 8 caracteres.',
            'direccion.min' => 'La dirección debe tener al menos 4 caracteres.',
        ]);

        try {
            Persona::create($request->all());

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Cliente registrado correctamente.']);
            }

            return redirect()->route('clientes.index')->with('success', 'Cliente registrado correctamente.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error al registrar el cliente: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->withInput()->with('error', 'Error al registrar el cliente: ' . $e->getMessage());
        }
    }
}
