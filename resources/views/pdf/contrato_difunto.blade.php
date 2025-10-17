<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contrato de Alquiler - {{ $difunto->persona->nombreCompleto }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 30px; color: #333; }
        h2, h3 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        td, th { padding: 8px; border: 1px solid #555; vertical-align: top; }
        .no-border td { border: none; }
        .firma { margin-top: 60px; text-align: center; }
        .firma span { display: block; margin-top: 5px; border-top: 1px solid #000; width: 200px; margin-left: auto; margin-right: auto; }
    </style>
</head>
<body>

    <h2>Municipalidad del Cementerio</h2>
    <h3>Contrato de Asignación de Nicho</h3>

    <p><strong>Fecha de emisión:</strong> {{ now()->format('d/m/Y H:i') }}</p>
    <p><strong>N° de Boleta:</strong> {{ $contrato->boleta_numero }}</p>

    <h4>Datos del Difunto</h4>
    <table>
        <tr><td><strong>Nombre:</strong></td><td>{{ $difunto->persona->nombreCompleto }}</td></tr>
        <tr><td><strong>Fecha de fallecimiento:</strong></td><td>{{ $difunto->fecha_fallecimiento }}</td></tr>
        <tr><td><strong>Fecha de entierro:</strong></td><td>{{ $difunto->fecha_entierro }}</td></tr>
    </table>

    <h4>Datos del Doliente</h4>
    <table>
        <tr><td><strong>Nombre:</strong></td><td>{{ $difunto->doliente->nombreCompleto ?? '—' }}</td></tr>
        <tr><td><strong>CI:</strong></td><td>{{ $difunto->doliente->ci ?? '—' }}</td></tr>
    </table>

    <h4>Datos del Nicho</h4>
    <table>
        <tr><td><strong>Pabellón:</strong></td><td>{{ $difunto->nicho->pabellon->nombre ?? 'Sin Pabellón' }}</td></tr>
        <tr><td><strong>Ubicación:</strong></td><td>Fila {{ $difunto->nicho->fila }}, Columna {{ $difunto->nicho->columna }}</td></tr>
        <tr><td><strong>Estado:</strong></td><td>{{ ucfirst($difunto->nicho->estado) }}</td></tr>
        <tr><td><strong>Costo de alquiler:</strong></td><td>Bs. {{ number_format($difunto->nicho->costo_alquiler, 2) }}</td></tr>
        <tr><td><strong>Fecha de vencimiento:</strong></td><td>{{ $difunto->nicho->fecha_vencimiento }}</td></tr>
    </table>

    <h4>Datos del Contrato</h4>
    <table>
        <tr><td><strong>Fecha inicio:</strong></td><td>{{ $contrato->fecha_inicio }}</td></tr>
        <tr><td><strong>Fecha fin:</strong></td><td>{{ $contrato->fecha_fin }}</td></tr>
        <tr><td><strong>Monto total:</strong></td><td>Bs. {{ number_format($contrato->monto, 2) }}</td></tr>
        <tr><td><strong>Registrado por:</strong></td><td>{{ $usuario->nombre ?? $usuario->name ?? '—' }}</td></tr>
    </table>

    <div class="firma">
        <span>Firma del Responsable</span>
    </div>

</body>
</html>
