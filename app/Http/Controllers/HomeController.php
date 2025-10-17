<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Nicho;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $hoy = Carbon::now();
        $unaSemana = $hoy->copy()->addDays(7);
        $totalNichos = Nicho::count();
        $nichosOcupados = Nicho::where('estado', 'ocupado')->count();
        $nichosDisponibles = Nicho::where('estado', 'disponible')->count();
        $nichosPorVencer = Nicho::whereNotNull('fecha_vencimiento')
            ->where('estado', '!=', 'vencido')
            ->whereBetween('fecha_vencimiento', [$hoy, $unaSemana])
            ->count();
        $nichosVencidos = Nicho::whereNotNull('fecha_vencimiento')
            ->where('fecha_vencimiento', '<', $hoy)
            ->count();
        $listaNichos = Nicho::with('pabellon')
            ->orderBy('id_nicho', 'desc')
            ->take(10)
            ->get();

        return view('dashboard', [
            'usuario' => Auth::user(),
            'totalNichos' => $totalNichos,
            'nichosOcupados' => $nichosOcupados,
            'nichosDisponibles' => $nichosDisponibles,
            'nichosPorVencer' => $nichosPorVencer,
            'nichosVencidos' => $nichosVencidos,
            'listaNichos' => $listaNichos,
        ]);
    }
}
