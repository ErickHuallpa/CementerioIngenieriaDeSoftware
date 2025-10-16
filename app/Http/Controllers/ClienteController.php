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
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'ci' => 'required|string|max:20|unique:persona,ci',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:100',
            'id_tipo_persona' => 'required|exists:tipo_persona,id_tipo_persona',
        ]);

        Persona::create($request->all());

        return redirect()->route('clientes.index')->with('success', 'Cliente registrado correctamente.');
    }
}
