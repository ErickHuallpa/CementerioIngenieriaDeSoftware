<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Incineración - {{ $difunto->persona->nombre_completo ?? $difunto->persona->nombre ?? '—' }}</title>
    <style>
        @page { margin: 35px 35px; }
        body { font-family: "DejaVu Sans", sans-serif; color: #2c3e50; font-size: 12px; line-height: 1.45; }

        :root {
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

        h2.title {
            text-align: center;
            font-size: 15px;
            font-weight: 700;
            color: var(--secondary);
            margin-bottom: 12px;
        }

        .section {
            margin-bottom: 12px;
            padding: 10px;
            background: #f7fafb;
            border-left: 4px solid var(--accent);
            border-radius: 4px;
        }
        .section h4 {
            margin: 0 0 6px 0;
            font-size: 13px;
            color: var(--primary);
        }
        .info p {
            margin: 4px 0;
            font-size: 12px;
        }
        .label {
            font-weight: 700;
        }

        .tabla-contrato {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        .tabla-contrato th, .tabla-contrato td {
            border: 1px solid #e6e9eb;
            padding: 6px 8px;
            font-size: 12px;
            text-align: left;
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

        .firma {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .firma-line {
            width: 45%;
            text-align: center;
            border-top: 1px solid #aab6bd;
            padding-top: 6px;
            font-weight: 700;
            color: var(--primary);
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
            <small>Dirección administrativa · Sistema de Gestión Funeraria</small>
        </div>
    </div>

    <div class="meta">
        <div>Fecha de emisión: {{ now()->format('d/m/Y') }}</div>
        <div class="muted">Documento generado electrónicamente</div>
    </div>
</header>

<main>
    <h2 class="title">Comprobante de Incineración</h2>

    <section class="section">
        <h4>Datos del Difunto</h4>
        <div class="info">
            <p><span class="label">Nombre:</span> {{ $difunto->persona->nombre_completo ?? $difunto->persona->nombre ?? '—' }}</p>
            <p><span class="label">Doliente:</span> {{ optional($difunto->doliente)->nombre_completo ?? '—' }}</p>
        </div>
    </section>

    <section class="section">
        <h4>Detalles de la Incineración</h4>
        <div class="info">
            <p><span class="label">Tipo:</span> {{ ucfirst($incineracion->tipo) }}</p>
            <p><span class="label">Fecha:</span> {{ $incineracion->fecha_incineracion }}</p>
            <p><span class="label">Responsable:</span> {{ $responsable->nombre_completo ?? ($responsable->nombre ?? '—') }}</p>
        </div>
    </section>

    <section class="section">
        <h4>Servicio</h4>
        <table class="tabla-contrato">
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th style="text-align:right">Cantidad</th>
                    <th style="text-align:right">Precio Unitario</th>
                    <th style="text-align:right">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Servicio de incineración ({{ ucfirst($incineracion->tipo) }})</td>
                    <td style="text-align:right">1</td>
                    <td style="text-align:right">{{ number_format($incineracion->costo,2) }}</td>
                    <td style="text-align:right">{{ number_format($incineracion->costo,2) }}</td>
                </tr>
            </tbody>
        </table>
        <div class="totales">TOTAL: Bs. {{ number_format($incineracion->costo,2) }}</div>
    </section>

    <div class="firma">
        <br>
        <br>
        <br>
        <div class="firma-line">Firma Responsable</div>
        <br>
        <br>
        <br>
        <br>
        <div class="firma-line">Firma Usuario que Registró</div>
    </div>
</main>

<footer>
    Cementerio General de Potosí — Sistema de Gestión Funeraria
</footer>

</body>
</html>
