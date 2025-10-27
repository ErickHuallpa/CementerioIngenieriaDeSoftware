<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Entierro #{{ $programacion->id_programacion }}</title>
    <style>
        @page { margin: 35px 35px; }
        body { font-family: "DejaVu Sans", sans-serif; color: #2c3e50; font-size: 12px; line-height: 1.45; }

        :root {
            --primary: #2c3e50;
            --secondary: #34495e;
            --accent: #16a085;
            --light: #f7fafb;
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

        .title {
            text-align: center;
            font-size: 15px;
            font-weight: 700;
            color: var(--secondary);
            margin-bottom: 12px;
        }

        .section {
            margin-bottom: 12px;
            padding: 10px;
            background: var(--light);
            border-left: 4px solid var(--accent);
            border-radius: 4px;
        }

        .section h4 {
            margin: 0 0 6px 0;
            font-size: 13px;
            color: var(--primary);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        th, td {
            padding: 6px 8px;
            vertical-align: top;
            font-size: 12px;
        }

        th {
            background: #f7fafb;
            color: var(--secondary);
            font-weight: 700;
            text-align: left;
            border: 1px solid #e6e9eb;
        }

        td {
            border: 1px solid #e6e9eb;
        }

        .label { font-weight: 700; }

        .firma {
            margin-top: 40px;
            text-align: center;
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
        <img src="https://i.imgur.com/TpdeOo1_d.png" alt="Logo Cementerio">
        <div>
            <h1>Cementerio General de Potosí</h1>
            <small>Dirección administrativa · Sistema de Gestión Funeraria</small>
        </div>
    </div>
    <div class="meta">
        <div>Comprobante N°: {{ $programacion->id_programacion }}</div>
        <div>Fecha emisión: {{ now()->format('d/m/Y') }}</div>
        <div class="muted">Documento generado electrónicamente</div>
    </div>
</header>

<main>
    <h2 class="title">Comprobante de Servicio - Entierro</h2>

    <section class="section">
        <h4>I. Datos del Difunto</h4>
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
    </section>

    <section class="section">
        <h4>II. Nicho</h4>
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
    </section>

    <section class="section">
        <h4>III. Programación</h4>
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
    </section>

    <section class="section">
        <h4>IV. Trabajador Responsable</h4>
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
    </section>

    <div class="firma">
        <div class="firma-line">Firma Responsable</div>
    </div>
</main>

<footer>
    Cementerio General de Potosí — Sistema de Gestión Funeraria
</footer>

</body>
</html>
