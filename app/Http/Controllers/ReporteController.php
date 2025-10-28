<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Nicho;
use App\Models\Osario;
use App\Models\Difunto;
use App\Models\Bodega;
use App\Models\Incineracion;
use App\Models\ContratoAlquiler;
use App\Models\ProgramacionEntierro;

class ReporteController extends Controller
{
    public function index()
    {
        $tipos = [
            'nichos' => 'Ocupación de Nichos',
            'osarios' => 'Ocupación de Osarios',
            'difuntos' => 'Listado de Difuntos',
            'bodega' => 'Difuntos en Bodega',
            'incineraciones' => 'Incineraciones',
            'entierros' => 'Programación de Entierros',
            'flujo_caja' => 'Flujo de Caja',
        ];

        return view('reporte.index', compact('tipos'));
    }

    public function generar(Request $request)
    {
        $request->validate([
            'tipo_reporte' => 'required|string',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        $tipo = $request->tipo_reporte;
        $inicio = $request->fecha_inicio;
        $fin = $request->fecha_fin;
        $datos = [];

        switch ($tipo) {
            case 'nichos':
                $query = Nicho::with('difuntos.persona', 'pabellon');
                if ($inicio && $fin) $query->whereBetween('fecha_ocupacion', [$inicio, $fin]);
                $datos = $query->orderBy('fecha_ocupacion', 'asc')->get();
                break;

            case 'osarios':
                $query = Osario::with('difunto.persona', 'pabellon');
                if ($inicio && $fin) $query->whereBetween('fecha_ingreso', [$inicio, $fin]);
                $datos = $query->orderBy('fecha_ingreso', 'asc')->get();
                break;

            case 'difuntos':
                $query = Difunto::with('persona', 'doliente', 'nicho', 'osario', 'bodega');
                if ($inicio && $fin) $query->whereBetween('fecha_fallecimiento', [$inicio, $fin]);
                $datos = $query->join('persona', 'difunto.id_persona', '=', 'persona.id_persona')
                               ->orderBy('persona.apellido', 'asc')
                               ->select('difunto.*')
                               ->get();
                break;

            case 'bodega':
                $query = Bodega::with('difunto.persona');
                if ($inicio && $fin) $query->whereBetween('fecha_ingreso', [$inicio, $fin]);
                $datos = $query->orderBy('fecha_ingreso', 'asc')->get();
                break;

            case 'incineraciones':
                $query = Incineracion::with('difunto.persona', 'responsable');
                if ($inicio && $fin) $query->whereBetween('fecha_incineracion', [$inicio, $fin]);
                $datos = $query->orderBy('fecha_incineracion', 'asc')->get();
                break;

            case 'contratos':
                $query = ContratoAlquiler::with('difunto.persona', 'nicho', 'osario');
                if ($inicio && $fin) $query->whereBetween('fecha_inicio', [$inicio, $fin]);
                $datos = $query->orderBy('fecha_inicio', 'asc')->get();
                break;

            case 'entierros':
                $query = ProgramacionEntierro::with('difunto.persona', 'trabajador')
                            ->where('estado', 'completado');
                if ($inicio && $fin) {
                    $query->whereBetween('fecha_programada', [$inicio, $fin]);
                }
                $datos = $query->orderBy('fecha_programada', 'asc')->get();
                break;


            case 'flujo_caja':
                $query = ContratoAlquiler::with('difunto.persona');
                if ($inicio && $fin) $query->whereBetween('fecha_inicio', [$inicio, $fin]);
                $datos = $query->orderBy('fecha_inicio', 'asc')->get();
                $total = $datos->sum('monto');
                return view('reporte.reporte_flujo_caja', compact('datos', 'total', 'inicio', 'fin'));
                break;

            default:
                return back()->with('error', 'Tipo de reporte no válido');
        }

        return view("reporte.reporte_$tipo", compact('datos'));
    }

    public function generarPDF(Request $request)
    {
        $request->validate([
            'tipo_reporte' => 'required|string',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        $tipo = $request->tipo_reporte;
        $inicio = $request->fecha_inicio;
        $fin = $request->fecha_fin;
        $datos = [];

        switch ($tipo) {
            case 'difuntos':
                $query = Difunto::with('persona', 'doliente', 'nicho', 'osario', 'bodega');
                if ($inicio && $fin) $query->whereBetween('fecha_fallecimiento', [$inicio, $fin]);
                $datos = $query->join('persona', 'difunto.id_persona', '=', 'persona.id_persona')
                            ->orderBy('persona.apellido', 'asc')
                            ->select('difunto.*')
                            ->get();
                $view = 'reporte.pdf_difuntos';
                break;

            case 'bodega':
                $query = Bodega::with('difunto.persona', 'difunto.doliente');
                if ($inicio && $fin) $query->whereBetween('fecha_ingreso', [$inicio, $fin]);
                $datos = $query->orderBy('fecha_ingreso', 'asc')->get();
                $pdf = Pdf::loadView("reporte.pdf_bodega", compact('datos', 'inicio', 'fin'));
                return $pdf->stream("reporte-bodega.pdf");


            case 'nichos':
                $query = Nicho::with('difuntos.persona', 'pabellon');
                if ($inicio && $fin) $query->whereBetween('fecha_ocupacion', [$inicio, $fin]);
                $datos = $query->get();
                $view = 'reporte.pdf_nichos';
                break;

            case 'osarios':
                $query = Osario::with('difunto.persona', 'pabellon');
                if ($inicio && $fin) $query->whereBetween('fecha_ingreso', [$inicio, $fin]);
                $datos = $query->get();
                $view = 'reporte.pdf_osarios';
                break;

            case 'incineraciones':
                $query = Incineracion::with('difunto.persona', 'responsable');
                if ($inicio && $fin) $query->whereBetween('fecha_incineracion', [$inicio, $fin]);
                $datos = $query->get();
                $view = 'reporte.pdf_incineracion';
                break;

            case 'entierros':
                $query = ProgramacionEntierro::with('difunto.persona', 'trabajador')
                            ->where('estado', 'completado');
                if ($inicio && $fin) $query->whereBetween('fecha_programada', [$inicio, $fin]);
                $datos = $query->orderBy('fecha_programada', 'asc')->get();
                $pdf = Pdf::loadView("reporte.pdf_entierros", compact('datos', 'inicio', 'fin'));
                return $pdf->stream("reporte-entierros.pdf");

            case 'flujo_caja':
                $query = ContratoAlquiler::with('difunto.persona', 'difunto.doliente');
                if ($inicio && $fin) $query->whereBetween('fecha_inicio', [$inicio, $fin]);
                $datos = $query->orderBy('fecha_inicio', 'asc')->get();
                $total = $datos->sum('monto');
                $pdf = Pdf::loadView("reporte.pdf_flujo_caja", compact('datos', 'total', 'inicio', 'fin'));
                return $pdf->stream("reporte-flujo-caja.pdf");


            default:
                return back()->with('error', 'Tipo de reporte no válido');
        }

        $pdf = Pdf::loadView($view, compact('datos', 'inicio', 'fin'));

        return $pdf->stream("reporte-$tipo.pdf");
    }


}
