<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contrato de Alquiler de Nicho - {{ $difunto->persona->nombreCompleto ?? ($difunto->persona->nombre . ' ' . $difunto->persona->apellido) }}</title>
    <style>
        @page { margin: 35px 35px; }
        body { font-family: "DejaVu Sans", sans-serif; color: #2c3e50; font-size: 12px; line-height: 1.45; }
        :root{
            --primary: #2c3e50;
            --secondary: #34495e;
            --accent: #16a085;
            --light: #ecf0f1;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 4px solid var(--accent);
            padding-bottom: 10px;
            margin-bottom: 18px;
        }
        .brand {
            display: flex;
            gap: 12px;
            align-items: center;
        }
        .brand img {
            width: 58px;
            height: 58px;
            border-radius: 6px;
        }
        .brand h1 {
            font-size: 16px;
            margin: 0;
            color: var(--primary);
            letter-spacing: 0.6px;
        }
        .brand small {
            display: block;
            color: var(--secondary);
            font-size: 10px;
            margin-top: 2px;
        }

        .meta {
            text-align: right;
            font-size: 11px;
            color: var(--secondary);
        }
        .meta .boleta {
            font-weight: 700;
            color: var(--primary);
            font-size: 13px;
        }

        .section {
            margin-top: 14px;
            margin-bottom: 12px;
        }
        .section-title {
            font-weight: 700;
            background: linear-gradient(90deg, rgba(44,62,80,0.08), rgba(52,73,94,0.04));
            padding: 8px 10px;
            border-left: 4px solid var(--accent);
            margin-bottom: 8px;
            color: var(--primary);
            font-size: 13px;
        }

        table.info {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        table.info td, table.info th {
            padding: 6px 8px;
            vertical-align: top;
            font-size: 12px;
            color: #2c3e50;
        }
        table.info th {
            text-align: left;
            width: 28%;
            color: var(--secondary);
            font-weight: 600;
        }
        .muted { color: #6c7a89; font-size: 11px; }

        .clausula {
            margin-bottom: 10px;
            text-align: justify;
            font-size: 12px;
        }
        .clausula strong { color: var(--primary); }

        .tabla-contrato {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .tabla-contrato th, .tabla-contrato td {
            border: 1px solid #e6e9eb;
            padding: 8px;
            font-size: 12px;
        }
        .tabla-contrato th {
            background: #f7fafb;
            color: var(--secondary);
            font-weight: 700;
        }

        .totales {
            margin-top: 10px;
            text-align: right;
            font-size: 13px;
            font-weight: 700;
            color: var(--primary);
        }

        .firmas {
            margin-top: 36px;
            display: flex;
            justify-content: space-between;
        }
        .firma-block {
            width: 45%;
            text-align: center;
            padding-top: 10px;
        }
        .firma-line {
            margin-top: 48px;
            border-top: 1px solid #aab6bd;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
            padding-top: 6px;
            font-weight: 700;
            color: var(--primary);
        }
        .small-note {
            font-size: 10px;
            color: #7f8c8d;
            margin-top: 6px;
        }

        footer {
            position: fixed;
            bottom: 20px;
            left: 35px;
            right: 35px;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            border-top: 1px dashed #e1e6e9;
            padding-top: 6px;
        }
    </style>
</head>
<body>

<header>
    <div class="brand">
        <div>
            <h1>Cementerio General de Potosí</h1>
            <small>Dirección administrativa · Sistema de Gestión</small>
        </div>
    </div>

    <div class="meta">
        <div class="boleta">Boleta N°: {{ $contrato->boleta_numero ?? ($contrato->id_contrato ? 'B-' . str_pad($contrato->id_contrato,4,'0',STR_PAD_LEFT) : '—') }}</div>
        <div>Fecha emisión: {{ now()->format('d/m/Y') }}</div>
        <div class="muted">Documento generado electrónicamente</div>
    </div>
</header>

<main>
    <h2 style="text-align:center;margin:0 0 6px 0;color:var(--secondary);font-size:15px;">CONTRATO DE ARRENDAMIENTO DE NICHO</h2>
    <section class="section">
        <div class="section-title">I. Comparecen</div>
        <table class="info">
            <tr>
                <th>Arrendador</th>
                <td>
                    <strong>Administrador del Cementerio General de Potosí</strong><br>
                </td>
            </tr>
            <tr>
                <th>Arrendatario / Doliente</th>
                <td>
                    {{ $difunto->doliente->nombreCompleto ?? '—' }}<br>
                    CI: {{ $difunto->doliente->ci ?? '—' }}<br>
                    Dirección: {{ $difunto->doliente->direccion ?? '—' }}<br>
                    Teléfono: {{ $difunto->doliente->telefono ?? '—' }}
                </td>
            </tr>
            <tr>
                <th>Difunto</th>
                <td>
                    {{ $difunto->persona->nombreCompleto ?? ($difunto->persona->nombre . ' ' . $difunto->persona->apellido) }}<br>
                    CI: {{ $difunto->persona->ci ?? '—' }}<br>
                    Fecha fallecimiento: {{ $difunto->fecha_fallecimiento ?? '—' }}
                </td>
            </tr>
        </table>
    </section>

    <section class="section">
        <div class="section-title">II. Objeto</div>
        <p class="clausula">
            Por el presente contrato, EL ARRENDADOR otorga en uso al ARRENDATARIO el <strong>nicho</strong> identificado como:
        </p>

        <table class="info">
            <tr><th>Pabellón</th><td>{{ $difunto->nicho->pabellon->nombre ?? '—' }}</td></tr>
            <tr><th>Ubicación</th><td>Fila {{ $difunto->nicho->fila ?? '—' }}, Columna {{ $difunto->nicho->columna ?? '—' }}</td></tr>
            <tr><th>Tipo</th><td>Nicho</td></tr>
        </table>
    </section>

    <section class="section">
        <div class="section-title">III. Duración</div>
        <p class="clausula">
            La vigencia del presente contrato comprende desde <strong>{{ $contrato->fecha_inicio ?? now()->toDateString() }}</strong> hasta <strong>{{ $contrato->fecha_fin ?? now()->addYears(5)->toDateString() }}</strong>,
            salvo renovación o terminación anticipada por las causas previstas en este contrato.
        </p>
    </section>

    <section class="section">
        <div class="section-title">IV. Monto y forma de pago</div>

        <table class="tabla-contrato">
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th>Moneda</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Arriendo nicho (plazo indicado)</td>
                    <td>Bs.</td>
                    <td style="text-align:right;">{{ number_format($contrato->monto ?? 0, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="totales">TOTAL: Bs. {{ number_format($contrato->monto ?? 0, 2) }}</div>

        <p class="clausula">
            El pago se efectúa conforme a la boleta indicada y queda registrado en el presente documento. Las partes reconocen la recepción del monto pactado.
        </p>
    </section>

    <section class="section">
        <div class="section-title">V. Obligaciones principales</div>
        <p class="clausula"><strong>Del Arrendador:</strong> Entregar el uso del nicho en las condiciones descritas y permitir el acceso conforme al reglamento del cementerio.</p>
        <p class="clausula"><strong>Del Arrendatario:</strong> Pagar puntualmente las sumas convenidas, respetar el reglamento, mantener el nicho en buen estado y notificar cualquier modificación en los datos personales.</p>
    </section>

    <section class="section">
        <div class="section-title">VI. Terminación y renovaciones</div>
        <p class="clausula">
            El contrato podrá renovarse por acuerdo expreso entre las partes. El vencimiento sin renovación dará lugar al trámite administrativo que corresponda según normativa vigente.
            El ARRENDADOR podrá resolver el contrato por incumplimiento de las obligaciones del ARRENDATARIO.
        </p>
    </section>

    <section class="section">
        <div class="section-title">VII. Legislación aplicable y jurisdicción</div>
        <p class="clausula">
            Las partes se someten a la legislación vigente en la jurisdicción territorial competente para la administración del Cementerio General de Potosí, renunciando a cualquier otro fuero que pudiera corresponderles.
        </p>
    </section>

    <section class="section">
        <div class="section-title">VIII. Aceptación</div>
        <p class="clausula">
            Leído que fue el presente contrato y enteradas las partes de su contenido y alcance jurídico, lo firman en señal de aceptación.
        </p>
    </section>

    <div class="firmas">
        <div class="firma-block">
            <div class="firma-line">Administrador del Cementerio General de Potosí</div>
            <div class="small-note">Firma y Sello del Arrendador</div>
        </div>

        <div class="firma-block">
            <div class="firma-line">{{ $difunto->doliente->nombreCompleto ?? 'Arrendatario' }}</div>
            <div class="small-note">Firma del Arrendatario / Doliente</div>
        </div>
    </div>

    <div style="margin-top:18px;font-size:10px;color:#7f8c8d;">
        <strong>Observaciones:</strong> {{ $contrato->observacion ?? '—' }}
    </div>
</main>

<footer>
    Documento impreso desde el Sistema de Gestión - Cementerio General de Potosí · Fecha: {{ now()->format('d/m/Y') }}
</footer>
</body>
</html>
