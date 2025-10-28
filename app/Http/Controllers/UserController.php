<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Persona;
use App\Models\TipoPersona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $personas = Persona::whereIn('id_tipo_persona', [1, 2, 4, 6, 7])
            ->with(['tipoPersona', 'user'])
            ->get();
        $tipos = TipoPersona::whereIn('id_tipo_persona', [1, 2, 4, 6, 7])->get();

        return view('users.index', compact('personas', 'tipos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,name',
            'nombre' => 'required|string|max:255|min:3|regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s\'-]+$/u',
            'apellido' => 'required|string|max:255|min:3|regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s\'-]+$/u',
            'ci' => 'required|string|max:20|unique:persona,ci|min:7',
            'telefono' => 'nullable|string|max:20|min:8',
            'direccion' => 'nullable|string|max:255',
            'email' => 'required|email|unique:persona,email|unique:users,email',
            'id_tipo_persona' => 'required|integer|in:1,2,4,6,7',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'apellido.regex' => 'El apellido solo puede contener letras y espacios.',
            'ci.min' => 'La cédula de identidad debe tener al menos 7 caracteres.',
            'telefono.min' => 'El teléfono debe tener al menos 8 caracteres.',
        ]);

        $persona = Persona::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'ci' => $request->ci,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'email' => $request->email,
            'id_tipo_persona' => $request->id_tipo_persona,
        ]);

        User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_persona' => $persona->id_persona,
        ]);

        return redirect()->route('users.index')->with('success', 'Personal registrado correctamente.');
    }
}
