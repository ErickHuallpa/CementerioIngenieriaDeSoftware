<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('profile.index', compact('user'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $persona = $user->persona;

        $request->validate([
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'name')->ignore($user->id)
            ],
            'ci' => 'required|string|max:20|min:7',
            'nombre' => 'required|string|max:100|min:3|regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s\'-]+$/u',
            'apellido' => 'required|string|max:100|min:3|regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s\'-]+$/u',
            'telefono' => 'nullable|string|max:20|min:8',
            'direccion' => 'nullable|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id)
            ],
            'password' => 'nullable|min:6|confirmed',
        ], [
            'ci.min' => 'La cédula de identidad debe tener al menos 7 caracteres.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'apellido.regex' => 'El apellido solo puede contener letras y espacios.',
            'telefono.min' => 'El teléfono debe tener al menos 8 caracteres.',
        ]);

        $persona->update([
            'ci' => $request->ci,
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'email' => $request->email
        ]);

        $user->name = $request->username;
        $user->email = $request->email;

        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile.index')->with('success', 'Perfil actualizado correctamente.');
    }
}
