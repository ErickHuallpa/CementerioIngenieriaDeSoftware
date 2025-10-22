<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Comprobante Incineración</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #222; margin: 15px; }
        .header { display:flex; justify-content:center; align-items:center; margin-bottom:15px; flex-direction:column; }
        .header img { max-width:80px; margin-bottom:5px; }
        .company h2 { margin:0; color:#2c3e50; font-size:1.3rem; text-align:center; }
        hr { border:none; border-top:1px solid #eee; margin:12px 0; }
        table { width:100%; border-collapse:collapse; margin-bottom:10px; font-size:0.9rem; }
        th, td { padding:6px 8px; border:1px solid #eaeaea; text-align:left; }
        th { background:#f7f7f7; }
        .totals { float:right; width:250px; margin-top:5px; }
        .totals table td { border:none; padding:4px 6px; }
        .signature { margin-top:40px; display:flex; justify-content:space-between; }
        .signature .sig { width:45%; text-align:center; font-size:0.85rem; }
        .small { font-size:0.75rem; color:#666; text-align:center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company">
            <h2>Cementerio POTOSI</h2>
            <div class="small">
                Fecha: {{ now()->format('Y-m-d') }}
            </div>
        </div>
    </div>

    <hr>

    <h4>Datos del Difunto</h4>
    <table>
        <tr><th>Nombre</th><td>{{ $difunto->persona->nombre_completo ?? $difunto->persona->nombre ?? '—' }}</td></tr>
        <tr><th>Doliente</th><td>{{ optional($difunto->doliente)->nombre_completo ?? '—' }}</td></tr>
    </table>

    <h4>Detalles de la Incineración</h4>
    <table>
        <tr><th>Tipo</th><td>{{ ucfirst($incineracion->tipo) }}</td></tr>
        <tr><th>Fecha</th><td>{{ $incineracion->fecha_incineracion }}</td></tr>
        <tr><th>Responsable</th><td>{{ $responsable->nombre_completo ?? ($responsable->nombre ?? '—') }}</td></tr>
    </table>

    <h4>Servicio</h4>
    <table>
        <thead>
            <tr>
                <th>Descripción</th>
                <th style="width:80px; text-align:right;">Cantidad</th>
                <th style="width:100px; text-align:right;">Precio Unitario</th>
                <th style="width:100px; text-align:right;">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Servicio de incineración ({{ ucfirst($incineracion->tipo) }})</td>
                <td style="text-align:right;">1</td>
                <td style="text-align:right;">{{ number_format($incineracion->costo, 2) }}</td>
                <td style="text-align:right;">{{ number_format($incineracion->costo, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr><td style="text-align:right; font-weight:bold;">Total:</td><td style="text-align:right;">Bs. {{ number_format($incineracion->costo, 2) }}</td></tr>
        </table>
    </div>

    <div style="clear:both;"></div>

    <div class="signature">
        <div class="sig">Firma Responsable</div>
        <div class="sig">Firma Usuario que Registró</div>
    </div>

    <p class="small" style="margin-top:10px;">Documento generado electrónicamente por el sistema de gestión del cementerio.</p>
</body>
</html>
