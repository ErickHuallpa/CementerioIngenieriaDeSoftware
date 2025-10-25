<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contrato Osario - {{ $difunto->persona->nombreCompleto }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 35px; color: #2c3e50; line-height: 1.5; }
        h1, h2, h4 { text-align: center; margin-bottom: 5px; color: #34495e; }
        h1 { font-size: 20px; margin-bottom: 10px; }
        h2 { font-size: 16px; }
        h4 { font-size: 14px; font-weight: normal; }
        .seccion { margin-top: 20px; padding: 10px; border-left: 4px solid #16a085; background-color: #ecf0f1; border-radius: 4px; }
        p { margin: 8px 0; text-align: justify; }
        .firma { margin-top: 60px; text-align: center; }
        .firma span { display: block; margin-top: 5px; border-top: 1px solid #2c3e50; width: 220px; margin-left: auto; margin-right: auto; padding-top: 5px; font-weight: bold; }
        .firma small { display: block; margin-top: 2px; font-size: 10px; color: #7f8c8d; }
        .resaltado { font-weight: bold; color: #16a085; }
    </style>
</head>
<body>

    <h1>CONTRATO DE ARRENDAMIENTO DE OSARIO</h1>
    <h2>Cementerio General de Potosí</h2>

    <p><strong>Boleta N°:</strong> {{ $contrato->boleta_numero }} <br>
    <strong>Fecha de emisión:</strong> {{ now()->format('d/m/Y') }}</p>

    <div class="seccion">
        <p>
            La Administración del <span class="resaltado">Cementerio General de Potosí</span> (EL ARRENDADOR),
            y el(a) Sr./Sra. <span class="resaltado">{{ $difunto->doliente->nombreCompleto ?? '—' }}</span>,
            CI: <span class="resaltado">{{ $difunto->doliente->ci ?? '—' }}</span> (EL ARRENDATARIO),
            suscriben el presente Contrato de Arrendamiento de Osario.
        </p>
    </div>

    <div class="seccion">
        <strong>PRIMERA – OBJETO</strong>
        <p>
            EL ARRENDADOR otorga en uso el osario: 
            <span class="resaltado">Pabellón {{ $osario->pabellon->nombre ?? '—' }}, Fila {{ $osario->fila }}, Columna {{ $osario->columna }}</span>,
            para el difunto <span class="resaltado">{{ $difunto->persona->nombreCompleto }}</span>.
        </p>
    </div>

    <div class="seccion">
        <strong>SEGUNDA – DURACIÓN</strong>
        <p>
            Vigencia desde <span class="resaltado">{{ $contrato->fecha_inicio }}</span> hasta <span class="resaltado">{{ $contrato->fecha_fin }}</span>.
        </p>
    </div>

    <div class="seccion">
        <strong>TERCERA – COSTO Y PAGO</strong>
        <p>
            Monto a pagar: <span class="resaltado">Bs. {{ number_format($contrato->monto, 2) }}</span>.
        </p>
    </div>

    <div class="seccion">
        <strong>CUARTA – OBLIGACIONES</strong>
        <p>
            El Arrendatario debe respetar el reglamento y mantener el osario en buen estado.
        </p>
    </div>

    <div class="seccion">
        <strong>QUINTA – ACEPTACIÓN</strong>
        <p>
            Ambas partes aceptan el presente contrato firmando al pie.
        </p>
    </div>

    <div class="firma">
        <span>Firma del Arrendatario</span>
    </div>

    <div class="firma" style="margin-top: 40px;">
        <span>Responsable - Cementerio</span>
    </div>

</body>
</html>
