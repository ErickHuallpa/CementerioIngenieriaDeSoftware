<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Entierro #{{ $programacion->id_programacion }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; margin: 25px; }
        header { text-align: center; margin-bottom: 10px; }
        h1 { font-size: 16px; margin: 0; }
        .sub { font-size: 11px; color: #555; margin-bottom: 8px; }
        .section { margin-top: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        td, th { padding: 6px 8px; border: 1px solid #ddd; vertical-align: top; }
        th { background: #f4f4f4; text-align: left; }
        .two-col td { border: none; padding: 2px 0; }
        .footer { margin-top: 24px; text-align: center; font-size: 11px; color: #666; }
    </style>
</head>
<body>
    <header>
        <h1>COMPROBANTE DE SERVICIO - ENTIERRO</h1>
        <div class="sub">Comprobante N°: {{ $programacion->id_programacion }} — Fecha emisión: {{ now()->format('d/m/Y') }}</div>
    </header>

    <div class="section">
        <strong>I. Datos del Difunto</strong>
        <table>
            <tr>
                <th>Nombre</th>
                <td>{{ $programacion->difunto->persona->nombre ?? '—' }} {{ $programacion->difunto->persona->apellido ?? '' }}</td>
                <th>CI</th>
                <td>{{ $programacion->difunto->persona->ci ?? '—' }}</td>
            </tr>
            <tr>
                <th>Fecha Fallecimiento</th>
                <td>{{ $programacion->difunto->fecha_fallecimiento ?? '—' }}</td>
                <th>Fecha Entierro</th>
                <td>{{ $programacion->difunto->fecha_entierro ?? '—' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <strong>II. Nicho</strong>
        <table>
            @if($programacion->difunto->nicho)
            <tr>
                <th>Pabellón</th>
                <td>{{ optional($programacion->difunto->nicho->pabellon)->nombre ?? '—' }}</td>
                <th>Ubicación</th>
                <td>Fila {{ $programacion->difunto->nicho->fila ?? '—' }}, Col {{ $programacion->difunto->nicho->columna ?? '—' }}</td>
            </tr>
            <tr>
                <th>Vencimiento</th>
                <td>{{ $programacion->difunto->nicho->fecha_vencimiento ?? '—' }}</td>
                <th>Costo</th>
                <td>Bs. {{ number_format(optional($programacion->difunto->nicho)->costo_alquiler ?? 0, 2) }}</td>
            </tr>
            @else
            <tr><td colspan="4">El difunto no tiene nicho asignado.</td></tr>
            @endif
        </table>
    </div>

    <div class="section">
        <strong>III. Programación</strong>
        <table>
            <tr>
                <th>Fecha programada</th>
                <td>{{ $programacion->fecha_programada }}</td>
                <th>Hora</th>
                <td>{{ \Carbon\Carbon::parse($programacion->hora_programada)->format('H:i') }}</td>
            </tr>
            <tr>
                <th>Estado</th>
                <td>{{ ucfirst($programacion->estado) }}</td>
                <th>Servicio</th>
                <td>Entierro municipal</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <strong>IV. Trabajador Responsable</strong>
        <table>
            @if($programacion->trabajador)
            <tr>
                <th>Nombre</th>
                <td>{{ $programacion->trabajador->nombre ?? '—' }} {{ $programacion->trabajador->apellido ?? '' }}</td>
                <th>CI</th>
                <td>{{ $programacion->trabajador->ci ?? '—' }}</td>
            </tr>
            @else
            <tr><td colspan="4">No hay trabajador registrado.</td></tr>
            @endif
        </table>
    </div>

    <div class="footer">
        Documento generado por el sistema de gestión del cementerio. Este comprobante acredita la prestación del servicio de entierro.
    </div>
</body>
</html>
