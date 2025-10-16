<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Nicho;

class HomeController extends Controller
{
    public function index()
    {
        $totalNichos = Nicho::count();
        $nichosOcupados = Nicho::where('estado', 'Ocupado')->count();
        $nichosDisponibles = Nicho::where('estado', 'Disponible')->count();
        $nichosReservados = Nicho::where('estado', 'Reservado')->count();

        $listaNichos = Nicho::with('pabellon')
            ->orderBy('id_nicho', 'desc')
            ->take(10)
            ->get();

        return view('dashboard', [
            'usuario' => Auth::user(),
            'totalNichos' => $totalNichos,
            'nichosOcupados' => $nichosOcupados,
            'nichosDisponibles' => $nichosDisponibles,
            'nichosReservados' => $nichosReservados,
            'listaNichos' => $listaNichos,
        ]);
    }
}
