<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ProgramacionEntierro;

class PendienteController extends Controller
{
    // Mostrar programaciones asignadas al trabajador autenticado (solo con nicho)
    public function index()
    {
        $user = Auth::user();

        if (!$user || !$user->persona) {
            return redirect()->route('login')->with('error', 'Usuario no tiene persona asociada.');
        }

        $idTrabajador = $user->persona->id_persona;

        $programaciones = ProgramacionEntierro::with([
                'difunto.persona',
                'difunto.nicho.pabellon',
                'trabajador' // por si quieres mostrar datos del trabajador
            ])
            ->where('id_trabajador', $idTrabajador)
            ->whereHas('difunto.nicho') // aseguramos solo los que tienen nicho
            ->orderBy('fecha_programada', 'asc')
            ->orderBy('hora_programada', 'asc')
            ->get();

        return view('pendientes.index', compact('programaciones'));
    }

    // Marcar como completado (solo si pertenece al trabajador)
    public function complete(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user || !$user->persona) {
            return redirect()->route('pendientes.index')->with('error', 'Acceso no autorizado.');
        }

        $idTrabajador = $user->persona->id_persona;
        $programacion = ProgramacionEntierro::findOrFail($id);

        if ($programacion->id_trabajador != $idTrabajador) {
            abort(403, 'No autorizado.');
        }

        if ($programacion->estado === 'completado') {
            return redirect()->route('pendientes.index')->with('info', 'La tarea ya está completada.');
        }

        $programacion->estado = 'completado';
        $programacion->save();

        return redirect()->route('pendientes.index')->with('success', 'Trabajo marcado como completado.');
    }

    // Mostrar comprobante en HTML (vista). Debe estar completado.
    public function verComprobante($id)
    {
        $user = Auth::user();

        if (!$user || !$user->persona) {
            return redirect()->route('pendientes.index')->with('error', 'Acceso no autorizado.');
        }

        $idTrabajador = $user->persona->id_persona;

        $programacion = ProgramacionEntierro::with([
                'difunto.persona',
                'difunto.doliente',
                'difunto.nicho.pabellon',
                'trabajador'
            ])->findOrFail($id);

        if ($programacion->id_trabajador != $idTrabajador) {
            abort(403, 'No autorizado.');
        }

        if ($programacion->estado !== 'completado') {
            return redirect()->route('pendientes.index')->with('error', 'No hay comprobante disponible. Complete la tarea primero.');
        }

        return view('pendientes.comprobante', compact('programacion'));
    }

    // Descargar PDF del comprobante (genera a partir de la misma vista)
    public function downloadFactura($id)
    {
        $user = Auth::user();

        if (!$user || !$user->persona) {
            return redirect()->route('pendientes.index')->with('error', 'Acceso no autorizado.');
        }

        $idTrabajador = $user->persona->id_persona;

        $programacion = ProgramacionEntierro::with([
                'difunto.persona',
                'difunto.doliente',
                'difunto.nicho.pabellon',
                'trabajador'
            ])->findOrFail($id);

        if ($programacion->id_trabajador != $idTrabajador) {
            abort(403, 'No autorizado.');
        }

        if ($programacion->estado !== 'completado') {
            return redirect()->route('pendientes.index')->with('error', 'Debe completar el entierro antes de generar la factura.');
        }

        $pdf = Pdf::loadView('pdf.factura_entierro', compact('programacion'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Comprobante_Entierro_'.$programacion->id_programacion.'.pdf');
    }
}
