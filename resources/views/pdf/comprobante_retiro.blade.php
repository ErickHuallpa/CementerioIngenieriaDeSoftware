<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Retiro - {{ $bodega->difunto->persona->nombre_completo }}</title>
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
    <h2 class="title">Comprobante de Retiro de Difunto</h2>

    <section class="section">
        <h4>Datos del Difunto</h4>
        <div class="info">
            <p><span class="label">Nombre:</span> {{ $bodega->difunto->persona->nombre_completo }}</p>
            <p><span class="label">CI:</span> {{ $bodega->difunto->persona->ci }}</p>
            <p><span class="label">Fecha de Retiro:</span> {{ $bodega->fecha_salida }}</p>
            <p><span class="label">Estado Final:</span> Retirado</p>
        </div>
    </section>

    <section class="section">
        <h4>Doliente Responsable</h4>
        <div class="info">
            <p><span class="label">Nombre:</span> {{ $bodega->difunto->doliente->nombre_completo ?? 'No registrado' }}</p>
            <p><span class="label">CI:</span> {{ $bodega->difunto->doliente->ci ?? 'No registrado' }}</p>
        </div>
    </section>

    <section class="section">
        <h4>Responsable del Servicio</h4>
        <div class="info">
            <p><span class="label">Nombre:</span> {{ $responsable->nombre_completo }}</p>
        </div>
    </section>

    <div class="firma">
        <div class="firma-line">Firma del Responsable</div>
    </div>
</main>

<footer>
    Cementerio General de Potosí — Sistema de Gestión Funeraria
</footer>

</body>
</html>
