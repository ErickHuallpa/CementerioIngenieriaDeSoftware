<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Retiro</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; }
        h2 { text-align: center; }
        .section { margin-bottom: 15px; }
        .firma { margin-top: 50px; text-align: center; }
    </style>
</head>
<body>
    <h2>COMPROBANTE DE RETIRO DE DIFUNTO</h2>
    <p><strong>Fecha de emisión:</strong> {{ now()->toDateString() }}</p>

    <div class="section">
        <h4>Datos del Difunto</h4>
        <p><strong>Nombre:</strong> {{ $bodega->difunto->persona->nombre_completo }}</p>
        <p><strong>CI:</strong> {{ $bodega->difunto->persona->ci }}</p>
        <p><strong>Fecha de Retiro:</strong> {{ $bodega->fecha_salida }}</p>
        <p><strong>Estado Final:</strong> Retirado</p>
    </div>

    <div class="section">
        <h4>Doliente Responsable</h4>
        <p><strong>Nombre:</strong> {{ $bodega->difunto->doliente->nombre_completo ?? 'No registrado' }}</p>
        <p><strong>CI:</strong> {{ $bodega->difunto->doliente->ci ?? 'No registrado' }}</p>
    </div>

    <div class="section">
        <h4>Responsable del Servicio</h4>
        <p><strong>Nombre:</strong> {{ $responsable->nombre_completo }}</p>
    </div>

    <div class="firma">
        <p>______________________________</p>
        <p>Firma del Responsable</p>
    </div>
</body>
</html>
