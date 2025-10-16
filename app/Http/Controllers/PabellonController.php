<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pabellon;

class PabellonController extends Controller
{
    public function create()
    {
        return view('pabellon.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:institucional,comun',
            'institucion' => 'nullable|string|max:255',
        ]);

        Pabellon::create($request->all());

        return redirect()->route('dashboard')->with('success', 'Pabellón registrado correctamente.');
    }

}
