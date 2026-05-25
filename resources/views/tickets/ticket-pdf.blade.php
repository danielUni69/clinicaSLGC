<!DOCTYPE html>
<html>
<head>
    <title>Ticket de Salida - JEMITA</title>

    <style>
        /* Fuente elegante compatible con DOMPDF */
        body {
            font-family: Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .ticket-container {
            width: 320px;
            margin: 0 auto;
            padding: 15px;
            border: 1px solid #ddd;
            background-color: #fff;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #D4AF37;
            padding-bottom: 10px;
        }

        .header img {
            width: 70px;
            height: auto;
            margin-bottom: 5px;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 18px;
            color: #2D3748;
        }

        .header p {
            margin: 0;
            font-size: 12px;
            color: #718096;
        }

        .details {
            margin-bottom: 15px;
        }

        .details div {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .details span:first-child {
            color: #4A5568;
            font-weight: 500;
        }

        .details span:last-child {
            font-weight: 600;
        }

        .tarifas {
            background-color: #F7FAFC;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #E2E8F0;
        }

        .tarifa-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .tarifa-label {
            font-size: 12px;
            color: #4A5568;
        }

        .tarifa-valor {
            font-weight: 600;
        }

        .desglose {
            font-size: 11px;
            margin-top: 10px;
            padding: 8px;
            background-color: #F7FAFC;
            border-radius: 5px;
        }

        .desglose-title {
            font-weight: bold;
            color: #2D3748;
            margin-bottom: 5px;
        }

        .desglose-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .total-section {
            border-top: 1px dashed #E2E8F0;
            padding-top: 10px;
            margin-top: 10px;
        }

        .total {
            display: flex;
            justify-content: space-between;
            font-size: 16px;
            font-weight: bold;
            color: #2D3748;
            margin-top: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 11px;
            color: #718096;
            font-style: italic;
            border-top: 1px solid #E2E8F0;
            padding-top: 10px;
        }
    </style>

</head>
<body>
    <div class="ticket-container">

        <div class="header">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logoN.png'))) }}" alt="Logo JEMITA">
            <h2>ESTACIONAMIENTO JEMITA</h2>
            <p>TICKET DE SALIDA</p>
            <p>{{ now()->setTimezone('America/La_Paz')->format('d/m/Y H:i') }}</p>
        </div>

        <div class="details">
            <div>
                <span>Espacio:</span>
                <span>{{ $espacio->codigo }}</span>
            </div>

            <div>
                <span>Placa:</span>
                <span>{{ $ticket->placa }}</span>
            </div>

            <div>
                <span>Ingreso:</span>
                <span>{{ \Carbon\Carbon::parse($ticket->horaIngreso)->setTimezone('America/La_Paz')->format('d/m/Y H:i') }}</span>
            </div>

            <div>
                <span>Salida:</span>
                <span>{{ now()->setTimezone('America/La_Paz')->format('d/m/Y H:i') }}</span>
            </div>

            <div>
                <span>Tiempo:</span>
                <span>
                    {{ floor($horas) }} horas
                    ({{ floor($minutos) }} min)
                </span>
            </div>
        </div>

        <div class="tarifas">
            <div class="tarifa-row">
                <span class="tarifa-label">Tarifa Diurna (06:00-17:59):</span>
                <span class="tarifa-valor">Bs. {{ number_format($tarifaBase, 2) }}/hora</span>
            </div>

            <div class="tarifa-row">
                <span class="tarifa-label">Tarifa Nocturna (18:00-05:59):</span>
                <span class="tarifa-valor">Bs. {{ number_format($tarifaNocturna, 2) }}/hora</span>
            </div>

            <div class="desglose">
                <div class="desglose-title">Desglose de Horas:</div>

                <div class="desglose-row">
                    <span>Horas Diurnas:</span>
                    <span>{{ $desgloseHoras['normales'] ?? 0 }}</span>
                </div>

                <div class="desglose-row">
                    <span>Horas Nocturnas:</span>
                    <span>{{ $desgloseHoras['nocturnas'] ?? 0 }}</span>
                </div>
            </div>
        </div>

        <div class="total-section">
            <div class="total">
                <span>TOTAL A PAGAR:</span>
                <span>Bs. {{ number_format($monto, 2) }}</span>
            </div>
        </div>

        <div class="footer">
            <p>¡Gracias por su preferencia!</p>
            <p>Estacionamiento JEMITA - Sistema de Gestión</p>
        </div>

    </div>
</body>
</html>
