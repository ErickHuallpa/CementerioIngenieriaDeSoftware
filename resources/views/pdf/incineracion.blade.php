<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Factura Incineración #{{ $incineracion->id_incineracion }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #222; }
        .header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
        .logo { width:140px; }
        .company { text-align:right; }
        .company h2 { margin:0; color:#2c3e50; }
        .meta { margin-top:8px; font-size:0.9rem; color:#555; }
        hr { border:none; border-top:1px solid #eee; margin:18px 0; }
        .section { margin-bottom:12px; }
        table { width:100%; border-collapse:collapse; font-size:0.95rem; }
        th, td { padding:8px 10px; border:1px solid #eaeaea; text-align:left; vertical-align:top; }
        th { background:#f7f7f7; }
        .totals { margin-top:12px; float:right; width:320px; }
        .totals table td { border:none; padding:6px 10px; }
        .signature { margin-top:60px; display:flex; justify-content:space-between; align-items:center; }
        .signature .sig { width:45%; text-align:center; }
        .small { font-size:0.85rem; color:#666; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <!-- Si tienes un logo local usa: <img src="{{ public_path('images/logo.png') }}" style="max-width:140px;"> -->
            <strong style="font-size:1.2rem; color:#16a085;">SISTEMA CEMENTERIO</strong><br>
            <span class="small">Comprobante de Servicio - Incineración</span>
        </div>

        <div class="company">
            <h2>Panteón Municipal</h2>
            <div class="meta">
                Fecha: {{ now()->format('Y-m-d') }}<br>
                Documento: INC-{{ str_pad($incineracion->id_incineracion, 6, '0', STR_PAD_LEFT) }}
            </div>
        </div>
    </div>

    <hr>

    <div class="section">
        <table>
            <tr>
                <th style="width:30%;">Difunto</th>
                <td>
                    {{ $difunto->persona->nombre_completo ?? $difunto->persona->nombre ?? '—' }} <br>
                    ID Difunto: #{{ $difunto->id_difunto }} <br>
                    Nicho: {{ optional($difunto->nicho->pabellon)->nombre ?? '—' }}
                    F{{ $difunto->nicho->fila ?? '—' }} C{{ $difunto->nicho->columna ?? '—' }}
                </td>
            </tr>
            <tr>
                <th>Doliente</th>
                <td>
                    {{-- tratar de mostrar doliente si existe --}}
                    {{ optional($difunto->doliente)->nombre_completo ?? '—' }}
                </td>
            </tr>
            <tr>
                <th>Incineración</th>
                <td>
                    Tipo: {{ ucfirst($incineracion->tipo) }} <br>
                    Fecha programada: {{ $incineracion->fecha_incineracion }} <br>
                    Responsable: {{ $responsable->nombre_completo ?? ($responsable->nombre ?? '—') }}
                </td>
            </tr>
            <tr>
                <th>Registrado por</th>
                <td>
                    {{ optional($usuario)->name ?? '—' }} <br>
                    Email: {{ optional($usuario)->email ?? '—' }}
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <table>
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th style="width:120px; text-align:right;">Cantidad</th>
                    <th style="width:140px; text-align:right;">Precio Unitario (Bs.)</th>
                    <th style="width:160px; text-align:right;">Total (Bs.)</th>
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
    </div>

    <div class="totals">
        <table>
            <tr>
                <td style="text-align:right; font-weight:bold;">Subtotal:</td>
                <td style="text-align:right; width:120px;">Bs. {{ number_format($incineracion->costo, 2) }}</td>
            </tr>
            <tr>
                <td style="text-align:right; font-weight:bold;">Descuento:</td>
                <td style="text-align:right;">Bs. 0.00</td>
            </tr>
            <tr>
                <td style="text-align:right; font-weight:bold; font-size:1.05rem;">Total:</td>
                <td style="text-align:right; font-size:1.05rem;">Bs. {{ number_format($incineracion->costo, 2) }}</td>
            </tr>
        </table>
    </div>

    <div style="clear:both"></div>

    <div class="signature">
        <div class="sig">
            <div style="border-top:1px solid #ccc; padding-top:6px;">Firma Responsable</div>
        </div>
        <div class="sig">
            <div style="border-top:1px solid #ccc; padding-top:6px;">Firma Usuario que Registró</div>
        </div>
    </div>

    <p class="small" style="margin-top:20px;">Este documento es generado electrónicamente por el sistema de gestión del cementerio. Conserva este comprobante como respaldo del servicio.</p>
</body>
</html>
