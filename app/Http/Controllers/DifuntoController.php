<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pabellon;
use App\Models\Persona;
use App\Models\Difunto;
use App\Models\Nicho;
use App\Models\ContratoAlquiler;
use App\Models\ProgramacionEntierro;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class DifuntoController extends Controller
{
    public function index()
    {
        $difuntos = Difunto::with(['persona', 'doliente', 'nicho.pabellon'])
            ->whereIn('estado', ['registrado', 'en_nicho'])
            ->get();

        return view('difunto.index', compact('difuntos'));
    }


    public function create(Request $request)
    {
        $nichoSeleccionado = $request->nicho;
        $difuntosSinNicho = Difunto::with('persona', 'doliente', 'programacionesEntierro')
            ->whereNull('id_nicho')
            ->where('estado', 'registrado')
            ->get();

        $dolientes = Persona::whereHas('tipoPersona', function ($q) {
            $q->whereRaw('LOWER(nombre_tipo) = ?', ['doliente']);
        })
        ->orderBy('apellido', 'asc')
        ->orderBy('nombre', 'asc')
        ->get();


        $nichosDisponibles = Nicho::with('pabellon')
            ->whereRaw('LOWER(estado) = ?', ['disponible'])
            ->get();

        $trabajadores = Persona::where('id_tipo_persona', 6)->get();

        return view('difunto.register_edit', compact(
            'difuntosSinNicho', 'dolientes', 'nichosDisponibles', 'trabajadores', 'nichoSeleccionado'
        ));
    }

    public function mapaNichos()
    {
        $pabellones = Pabellon::with(['nichos' => function($q) {
            $q->orderBy('fila')->orderBy('columna');
        }])
        ->where('tipo', 'comun')
        ->get();

        return view('difunto.mapa_nichos', compact('pabellones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_nicho' => 'required|exists:nicho,id_nicho',
            'id_trabajador' => 'required|exists:persona,id_persona',
        ]);

        DB::transaction(function () use ($request) {

            if ($request->filled('id_difunto_existente')) {
                $difunto = Difunto::with('persona')->findOrFail($request->id_difunto_existente);
                $difunto->update([
                    'id_nicho' => $request->id_nicho,
                    'fecha_entierro' => date('Y-m-d', strtotime('+1 day')),
                    'estado' => 'en_nicho',
                ]);
                ProgramacionEntierro::create([
                    'id_difunto' => $difunto->id_difunto,
                    'id_trabajador' => $request->id_trabajador,
                    'fecha_programada' => $difunto->fecha_entierro,
                    'hora_programada' => '10:00:00',
                    'estado' => 'pendiente',
                ]);

            } else {
                $request->validate([
                    'nombre' => 'required|string|max:100|min:3|regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s\'-]+$/u',
                    'apellido' => 'required|string|max:100|min:3|regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s\'-]+$/u',
                    'ci' => 'nullable|string|max:20|min:7',
                    'fecha_fallecimiento' => 'required|date',
                    'id_doliente' => 'required|exists:persona,id_persona',
                    'id_trabajador' => 'required|exists:persona,id_persona',
                ], [
                    'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
                    'apellido.regex' => 'El apellido solo puede contener letras y espacios.',
                    'ci.min' => 'La cédula de identidad debe tener al menos 7 caracteres.',
                ]);

                $persona = Persona::create([
                    'nombre' => $request->nombre,
                    'apellido' => $request->apellido,
                    'ci' => $request->ci ?? null,
                    'telefono' => null,
                    'direccion' => null,
                    'email' => null,
                    'id_tipo_persona' => null,
                ]);

                $difunto = Difunto::create([
                    'id_persona' => $persona->id_persona,
                    'id_doliente' => $request->id_doliente,
                    'id_nicho' => $request->id_nicho,
                    'fecha_fallecimiento' => $request->fecha_fallecimiento,
                    'fecha_entierro' => date('Y-m-d', strtotime('+1 day')),
                    'estado' => 'en_nicho',
                ]);

                ProgramacionEntierro::create([
                    'id_difunto' => $difunto->id_difunto,
                    'id_trabajador' => $request->id_trabajador,
                    'fecha_programada' => $difunto->fecha_entierro,
                    'hora_programada' => '10:00:00',
                    'estado' => 'pendiente',
                ]);
            }
            $fechaEntierro = $difunto->fecha_entierro;
            $fechaFin = date('Y-m-d', strtotime($fechaEntierro . ' +5 years'));

            $nicho = Nicho::findOrFail($request->id_nicho);
            $nicho->update([
                'estado' => 'ocupado',
                'fecha_ocupacion' => now(),
                'fecha_vencimiento' => $fechaFin,
            ]);
            ContratoAlquiler::create([
                'id_difunto' => $difunto->id_difunto,
                'id_nicho' => $nicho->id_nicho,
                'fecha_inicio' => $fechaEntierro,
                'fecha_fin' => $fechaFin,
                'renovaciones' => 0,
                'monto' => $nicho->costo_alquiler ?? 0,
                'estado' => 'activo',
                'boleta_numero' => 'B-' . strtoupper(uniqid()),
            ]);
        });

        return redirect()->route('difunto.index')->with('success', 'Difunto registrado o asignado correctamente.');
    }

    public function edit($id)
    {
        $difunto = Difunto::with(['persona', 'nicho.pabellon'])->findOrFail($id);

        $nichosDisponibles = Nicho::with('pabellon')
            ->whereRaw('LOWER(estado) = ?', ['disponible'])
            ->orWhere('id_nicho', $difunto->id_nicho)
            ->get();

        return view('difunto.register_edit', compact('difunto', 'nichosDisponibles'));
    }

    public function update(Request $request, $id)
    {
        $difunto = Difunto::findOrFail($id);
        $nichoAnterior = Nicho::findOrFail($difunto->id_nicho);

        $request->validate([
            'id_nicho' => 'required|exists:nicho,id_nicho',
        ]);

        DB::transaction(function () use ($difunto, $nichoAnterior, $request) {
            $nichoAnterior->update(['estado' => 'disponible', 'fecha_ocupacion' => null, 'fecha_vencimiento' => null]);

            $difunto->update(['id_nicho' => $request->id_nicho]);

            $nuevoNicho = Nicho::findOrFail($request->id_nicho);
            $nuevoNicho->update(['estado' => 'ocupado', 'fecha_ocupacion' => now()]);
        });

        return redirect()->route('difunto.index')->with('success', 'Nicho actualizado correctamente.');
    }
    public function downloadPdf($id)
    {
        $difunto = Difunto::with(['persona', 'doliente', 'nicho.pabellon'])->findOrFail($id);
        $contrato = ContratoAlquiler::where('id_difunto', $difunto->id_difunto)->first();
        $usuario = Auth::user();

        if (!$contrato) {
            return redirect()->route('difunto.index')->with('error', 'No existe contrato para este difunto.');
        }

        $pdf = Pdf::loadView('pdf.contrato_difunto', [
            'difunto' => $difunto,
            'contrato' => $contrato,
            'usuario' => $usuario
        ])->setPaper('letter');

        $nombrePDF = 'Contrato_' . $difunto->persona->nombre . '_' . $difunto->persona->apellido . '.pdf';

        return $pdf->download($nombrePDF);
    }

}
