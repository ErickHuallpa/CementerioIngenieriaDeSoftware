<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || !$user->persona || $user->persona->tipoPersona->nombre_tipo !== 'Administrador') {
            return redirect()->route('dashboard')->with('error', 'Acceso restringido. Solo administradores.');
        }

        return $next($request);
    }
}
