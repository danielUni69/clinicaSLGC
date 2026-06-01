<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Ticket de Pago</title>
    <style>
        body {
            font-family: monospace;
            font-size: 10px;
            margin: 0;
            padding: 5px;
            width: 100%;
            color: #000;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .border-top {
            border-top: 1px dashed #000;
            padding-top: 5px;
            margin-top: 5px;
        }

        .border-bottom {
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            vertical-align: top;
        }

        .item-name {
            max-width: 130px;
            word-wrap: break-word;
        }
    </style>
</head>

<body>
    <div class="text-center border-bottom">
        <h2 style="margin:0; font-size: 14px;">LABORATORIO SGLC</h2>
        <p style="margin:2px 0;">Ticket de Caja</p>
        <p style="margin:2px 0;">Fecha: {{ $servicio->created_at->format('d/m/Y H:i') }}</p>
        <p style="margin:2px 0;">Orden: {{ $servicio->codigo_unico }}</p>
    </div>

    <div style="margin-bottom: 5px;">
        <p style="margin:2px 0;"><span class="bold">Paciente:</span> {{ $servicio->paciente->nombre_completo }}</p>
        <p style="margin:2px 0;"><span class="bold">CI:</span> {{ $servicio->paciente->ci }}</p>
        <p style="margin:2px 0;"><span class="bold">Estado:</span> {{ strtoupper($servicio->estado_pago) }}</p>
    </div>

    <table class="border-top border-bottom">
        <thead>
            <tr>
                <th style="text-align: left;">CANT/DETALLE</th>
                <th style="text-align: right;">SUBT.</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($servicio->tiposAnalisis as $analisis)
                <tr>
                    <td class="item-name">1 x {{ $analisis->nombre }}</td>
                    <td class="text-right">{{ number_format($analisis->costo, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table>
        <tr>
            <td class="bold">TOTAL A PAGAR:</td>
            <td class="text-right bold">Bs. {{ number_format($servicio->recibo->total, 2) }}</td>
        </tr>
        <tr>
            <td>Medio de pago:</td>
            <td class="text-right">{{ $servicio->recibo->medio_pago }}</td>
        </tr>
    </table>

    <div class="text-center border-top" style="margin-top: 10px; font-size: 9px;">
        <p style="margin:2px 0;">¡Gracias por su preferencia!</p>
        <p style="margin:2px 0;">Puede descargar sus resultados en nuestro portal web.</p>
    </div>
</body>

</html>
