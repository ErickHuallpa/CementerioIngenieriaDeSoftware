<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Nicho;
use App\Models\Osario;
use App\Models\Difunto;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $hoy = Carbon::now();
        $unMes = $hoy->copy()->addMonth();
        $user = Auth::user();
        $pendientesUsuario = 0;
        if($user && $user->persona){
            $pendientesUsuario = $user->persona->programacionesEntierro()
                ->pendientes()
                ->count();
        }
        $totalNichos = Nicho::count();
        $nichosOcupados = Nicho::where('estado', 'ocupado')->count();
        $nichosDisponibles = Nicho::where('estado', 'disponible')->count();
        $nichosPorVencer = Nicho::where('estado', 'ocupado')
            ->whereNotNull('fecha_vencimiento')
            ->whereBetween('fecha_vencimiento', [$hoy, $unMes])
            ->count();
        $nichosVencidos = Nicho::where('estado', 'ocupado')
            ->whereNotNull('fecha_vencimiento')
            ->where('fecha_vencimiento', '<', $hoy)
            ->count();
        $totalOsarios = Osario::count();
        $osariosOcupados = Osario::where('estado', 'ocupado')->count();
        $osariosDisponibles = Osario::where('estado', 'disponible')->count();
        $osariosPorVencer = Osario::where('estado', 'ocupado')
            ->whereNotNull('fecha_salida')
            ->whereBetween('fecha_salida', [$hoy, $unMes])
            ->count();
        $osariosVencidos = Osario::where('estado', 'ocupado')
            ->whereNotNull('fecha_salida')
            ->where('fecha_salida', '<', $hoy)
            ->count();
        return view('dashboard', [
            'usuario' => $user,
            'pendientesUsuario' => $pendientesUsuario,
            'totalNichos' => $totalNichos,
            'nichosOcupados' => $nichosOcupados,
            'nichosDisponibles' => $nichosDisponibles,
            'nichosPorVencer' => $nichosPorVencer,
            'nichosVencidos' => $nichosVencidos,
            'totalOsarios' => $totalOsarios,
            'osariosOcupados' => $osariosOcupados,
            'osariosDisponibles' => $osariosDisponibles,
            'osariosPorVencer' => $osariosPorVencer,
            'osariosVencidos' => $osariosVencidos,
        ]);
    }


    public function buscarDifunto(Request $request)
    {
        $q = (string) $request->query('q', '');
        $q = trim($q);

        if ($q === '') return response()->json([]);

        $term = mb_strtolower($q);
        $query = Difunto::with([
            'persona',
            'doliente',
            'nicho.pabellon',
            'osario.pabellon',
            'bodega'
        ])->where(function($builder) use ($term) {
            $builder->whereHas('persona', function($p) use ($term) {
                $p->whereRaw('LOWER(nombre) LIKE ?', ["%{$term}%"])
                  ->orWhereRaw('LOWER(apellido) LIKE ?', ["%{$term}%"])
                  ->orWhereRaw("LOWER(CONCAT(nombre, ' ', apellido)) LIKE ?", ["%{$term}%"])
                  ->orWhereRaw("LOWER(CONCAT(apellido, ' ', nombre)) LIKE ?", ["%{$term}%"]);
            });
            $builder->orWhereHas('persona', function($p) use ($term) {
                $p->whereRaw('LOWER(ci) LIKE ?', ["%{$term}%"]);
            });
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $term)) {
                $builder->orWhere('fecha_fallecimiento', $term);
            }
        });

        $results = $query->take(30)->get();
        $out = $results->map(function($d) {
            $location = null;
            $estado_label = ucfirst(str_replace('_',' ', $d->estado));

            if ($d->id_nicho && $d->nicho) {
                $location = [
                    'tipo' => 'nicho',
                    'pabellon' => optional($d->nicho->pabellon)->nombre,
                    'fila' => $d->nicho->fila,
                    'columna' => $d->nicho->columna,
                    'posicion' => $d->nicho->posicion,
                ];
            } elseif ($d->osario) {
                $location = [
                    'tipo' => 'osario',
                    'pabellon' => optional($d->osario->pabellon)->nombre,
                    'fila' => $d->osario->fila,
                    'columna' => $d->osario->columna,
                ];
            } elseif ($d->bodega) {
                $location = [
                    'tipo' => 'bodega',
                    'destino' => $d->bodega->destino,
                    'fecha_ingreso' => $d->bodega->fecha_ingreso,
                ];
            }

            return [
                'id_difunto' => $d->id_difunto,
                'nombre_completo' => optional($d->persona)->nombre . ' ' . optional($d->persona)->apellido,
                'ci' => optional($d->persona)->ci,
                'fecha_fallecimiento' => $d->fecha_fallecimiento,
                'estado' => $d->estado,
                'estado_label' => $estado_label,
                'location' => $location,
            ];
        })->values();

        return response()->json($out);
    }
}
