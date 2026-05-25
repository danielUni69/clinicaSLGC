<!DOCTYPE html>
<html>
<head>
    <title>Ticket de Pago #{{ $pago->id }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9px; /* Reducimos ligeramente el tamaño para asegurar que quepa */
            margin: 0;
            padding: 0; /* Quitamos el padding del body */
            width: 80mm;
        }
        .ticket {
            width: 100%; /* El ticket usa todo el ancho disponible */
            max-width: 80mm;
            margin: 0 auto;
            padding: 5px 10px; /* Pequeño padding interno para el texto */
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 5px;
        }
        .separator {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .details-table td {
            padding: 2px 0;
        }
        .details-table .label {
            text-align: left;
            width: 70%;
        }
        .details-table .value {
            text-align: right;
            width: 30%;
            font-weight: bold;
        }
        .total-section {
            text-align: right;
            margin-top: 10px;
        }
        .total-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .total-amount {
            font-size: 16px;
            font-weight: bold;
            border-top: 1px solid #000; /* Línea para destacar el total */
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <div class="ticket">
        <div class="header">
            <strong>PARKING X</strong><br>
            <small>NIT: 123456789</small><br>
            <small>Av. Principal #123 - Ciudad</small>
        </div>

        <div class="separator"></div>

        <p style="text-align: center; margin: 5px 0; font-weight: bold;">RECIBO DE PAGO</p>

        <!-- INFORMACIÓN GENERAL -->
        <table class="details-table">
            <tr><td colspan="2"><strong>TICKET Nro:</strong> {{ $ticket->id }}</td></tr>
            <tr><td colspan="2"><strong>ESPACIO:</strong> {{ $espacio->nombre }} ({{ $tipoEspacio->nombre }})</td></tr>
            <tr><td colspan="2"><strong>FECHA/HORA PAGO:</strong> {{ $pago->fecha->format('d/m/Y H:i:s') }}</td></tr>
        </table>

        <div class="separator"></div>

        <!-- DETALLES DE TIEMPO -->
        <table class="details-table">
            <tr><td colspan="2"><strong>INGRESO:</strong> {{ Carbon\Carbon::parse($ticket->horaIngreso)->format('d/m/Y H:i:s') }}</td></tr>
            <tr><td colspan="2"><strong>SALIDA:</strong> {{ Carbon\Carbon::parse($ticket->horaSalida)->format('d/m/Y H:i:s') }}</td></tr>
        </table>

        <div class="separator"></div>

        <!-- TIEMPO COBRADO Y DETALLE DE HORAS -->
        <table class="details-table">
            <tr>
                <td class="label">Tiempo Real:</td>
                <td class="value">{{ $minutosReales }} minutos</td>
            </tr>
            <tr>
                <td class="label"><strong>TIEMPO COBRADO:</strong></td>
                <td class="value"><strong>{{ $horasCobro }} horas</strong></td>
            </tr>
            <tr>
                <td class="label">Servicio de Parqueo</td>
                <td class="value">{{ number_format($pago->monto, 2) }} Bs.</td>
            </tr>
        </table>

        <div class="separator"></div>

        <!-- TOTAL PAGADO -->
        <div class="total-section">
            <div class="total-title">TOTAL PAGADO:</div>
            <div class="total-amount">{{ number_format($pago->monto, 2) }} Bs.</div>
        </div>

        <div class="footer" style="margin-top: 15px;">
            <p style="font-size: 8px; margin: 0;">¡GRACIAS POR SU PREFERENCIA!</p>
            <p style="font-size: 8px; margin: 0;">Conserve este comprobante.</p>
        </div>
    </div>

</body>
</html>
