<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cementerio General de Potosí</title>
</head>
<body style="margin:0; padding:0; font-family: Arial, sans-serif; background-color:#f4f4f4; color:#34495e;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background:#ecf0f1; border-radius:10px; padding:20px; margin-top:20px;">
                    <tr>
                        <td align="center">
                            <img src="{{ $logoUrl }}" alt="Logo" style="width:150px; margin-bottom:20px;">
                            <h2 style="color:#2c3e50; text-align:center;">Notificación de Alquiler por Vencer</h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px; font-size:14px; line-height:1.6; color:#34495e;">
                            @if($nicho && $difunto)
                                <p><strong>Nicho:</strong> {{ $nicho->fila }}{{ $nicho->columna }} ({{ $nicho->posicion }})</p>
                                <p><strong>Pabellón:</strong> {{ $nicho->pabellon->nombre ?? '—' }}</p>
                                <p><strong>Difunto:</strong> {{ $difunto->persona->nombre_completo ?? '—' }}</p>
                                <p><strong>Fecha de vencimiento:</strong> {{ \Carbon\Carbon::parse($nicho->fecha_vencimiento)->format('d/m/Y') }}</p>
                            @endif

                            @if($osario && $difunto)
                                <p><strong>Osario:</strong> {{ $osario->fila }}{{ $osario->columna }}</p>
                                <p><strong>Pabellón:</strong> {{ $osario->pabellon->nombre ?? '—' }}</p>
                                <p><strong>Difunto:</strong> {{ $difunto->persona->nombre_completo ?? '—' }}</p>
                                <p><strong>Fecha de vencimiento:</strong> {{ \Carbon\Carbon::parse($osario->fecha_salida)->format('d/m/Y') }}</p>
                            @endif

                            <p style="margin-top:15px;">
                                Esto es solo una notificación, por favor tome nota de la fecha de vencimiento.<br>
                                Si necesita más información, puede contactarnos en nuestras oficinas.
                            </p>

                            <p style="margin-top:20px; font-weight:bold; color:#16a085;">
                                Atentamente:<br>
                                Administración del <br>
                                Cementerio General de Potosí
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
