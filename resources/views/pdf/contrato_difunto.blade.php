<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contrato de Alquiler - {{ $difunto->persona->nombreCompleto }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 35px; text-align: justify; }
        h2, h4 { text-align: center; margin-bottom: 5px; }
        .seccion { margin-top: 18px; }
        .firma { margin-top: 60px; text-align: center; }
        .firma span { display: block; margin-top: 5px; border-top: 1px solid #000; width: 220px; margin-left: auto; margin-right: auto; }
        p { line-height: 1.45; }
    </style>
</head>
<body>

    <h2>CONTRATO DE ARRENDAMIENTO DE NICHO</h2>
    <h4>Cementerio General de Potosí</h4>

    <p><strong>Boleta N°:</strong> {{ $contrato->boleta_numero }} <br>
    <strong>Fecha de emisión:</strong> {{ now()->format('d/m/Y') }}</p>

    <div class="seccion">
        <p>
            En la ciudad, a la fecha indicada, la Administración del Cementerio Municipal (en adelante
            <strong>EL ARRENDADOR</strong>), y el(a) Sr./Sra.
            <strong>{{ $difunto->doliente->nombreCompleto ?? '—' }}</strong>, con
            CI: <strong>{{ $difunto->doliente->ci ?? '—' }}</strong>,
            (en adelante <strong>EL ARRENDATARIO</strong>), suscriben el presente Contrato de Arrendamiento
            de Nicho, sujeto a las cláusulas siguientes:
        </p>
    </div>

    <div class="seccion">
        <strong>PRIMERA – OBJETO</strong>
        <p>
            EL ARRENDADOR otorga en uso el nicho identificado como:
            <strong>Pabellón {{ $difunto->nicho->pabellon->nombre ?? 'Sin Pabellón' }},
            Fila {{ $difunto->nicho->fila }}, Columna {{ $difunto->nicho->columna }}</strong>,
            para el entierro del difunto
            <strong>{{ $difunto->persona->nombreCompleto }}</strong>,
            fallecido el <strong>{{ $difunto->fecha_fallecimiento }}</strong> y cuyo entierro se realizará
            el <strong>{{ $difunto->fecha_entierro }}</strong>.
        </p>
    </div>

    <div class="seccion">
        <strong>SEGUNDA – DURACIÓN</strong>
        <p>
            El presente contrato tiene una vigencia desde el
            <strong>{{ $contrato->fecha_inicio }}</strong> hasta el
            <strong>{{ $contrato->fecha_fin }}</strong>. Vencido el plazo, el arrendatario deberá renovar
            o solicitar exhumación conforme al reglamento interno.
        </p>
    </div>

    <div class="seccion">
        <strong>TERCERA – COSTO Y PAGO</strong>
        <p>
            El arrendatario cancela la suma de
            <strong>Bs. {{ number_format($contrato->monto, 2) }}</strong>, correspondiente al uso del
            nicho por el período establecido.
        </p>
    </div>

    <div class="seccion">
        <strong>CUARTA – OBLIGACIONES</strong>
        <p>
            El Arrendatario se compromete a respetar el reglamento del cementerio y mantener el nicho en
            buen estado. El Arrendador garantiza el uso pacífico del mismo durante la vigencia del contrato.
        </p>
    </div>

    <div class="seccion">
        <strong>QUINTA – ACEPTACIÓN</strong>
        <p>
            Ambas partes aceptan el contenido del presente contrato, firmando al pie en señal de conformidad.
        </p>
    </div>
    <br>
    <div class="firma">
        <span>Firma del Arrendatario</span>
    </div>
    <br>
    <br>
    <div class="firma" style="margin-top: 40px;">
        <span>Responsable - Cementerio</span>
    </div>

</body>
</html>
