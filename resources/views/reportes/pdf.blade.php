<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Estacionamiento</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .header h1 {
            font-size: 26px;
            margin-bottom: 5px;
            color: #fbbf24;
        }

        .header .subtitle {
            font-size: 13px;
            color: #d1d5db;
        }

        .info-box {
            background: #f3f4f6;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #fbbf24;
            border-radius: 4px;
        }

        .info-box h3 {
            color: #1f2937;
            font-size: 14px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            padding: 3px 0;
        }

        .info-label {
            font-weight: bold;
            color: #4b5563;
        }

        .info-value {
            color: #1f2937;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .stat-card.blue {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .stat-card.orange {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        }

        .stat-card.purple {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        }

        .stat-label {
            font-size: 9px;
            margin-bottom: 5px;
            opacity: 0.95;
        }

        .stat-value {
            font-size: 18px;
            font-weight: bold;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #fbbf24;
        }

        .tipo-ingresos {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .tipo-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 12px;
            border-radius: 6px;
        }

        .tipo-card h4 {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .tipo-card .monto {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 3px;
        }

        .tipo-card .cantidad {
            font-size: 9px;
            color: #9ca3af;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: white;
        }

        table thead {
            background: #1f2937;
            color: white;
        }

        table th {
            padding: 10px 8px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            border-right: 1px solid #374151;
        }

        table th:last-child {
            border-right: none;
        }

        table th.text-center {
            text-align: center;
        }

        table th.text-right {
            text-align: right;
        }

        table td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9px;
        }

        table td.text-center {
            text-align: center;
        }

        table td.text-right {
            text-align: right;
        }

        table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: bold;
        }

        .badge-active {
            background: #fed7aa;
            color: #9a3412;
        }

        .badge-paid {
            background: #bbf7d0;
            color: #166534;
        }

        .total-row {
            background: #fef3c7 !important;
            font-weight: bold;
            font-size: 11px;
        }

        .total-row td {
            padding: 12px 8px;
            border-top: 2px solid #fbbf24;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            color: #6b7280;
            font-size: 9px;
        }

        .no-data {
            text-align: center;
            padding: 30px;
            color: #9ca3af;
            font-style: italic;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>🦙 ESTACIONAMIENTO JEMITA</h1>
        <div class="subtitle">Reporte de Registros de Estacionamiento</div>
    </div>

    <!-- Información del Reporte -->
    <div class="info-box">
        <h3>📋 Información del Reporte</h3>
        <div class="info-row">
            <span class="info-label">Período:</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tipo de Espacio:</span>
            <span class="info-value">{{ $filtroTipoEspacio }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Estado:</span>
            <span class="info-value">{{ $filtroEstado }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Fecha de Generación:</span>
            <span class="info-value">{{ $fechaGeneracion }}</span>
        </div>
    </div>

    <!-- Estadísticas Generales -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Ingresos</div>
            <div class="stat-value">Bs. {{ number_format($estadisticas['totalIngresos'], 2) }}</div>
        </div>
        <div class="stat-card blue">
            <div class="stat-label">Total Registros</div>
            <div class="stat-value">{{ $estadisticas['cantidadTickets'] }}</div>
        </div>
        <div class="stat-card orange">
            <div class="stat-label">Vehículos Activos</div>
            <div class="stat-value">{{ $estadisticas['ticketsActivos'] }}</div>
        </div>
        <div class="stat-card purple">
            <div class="stat-label">Promedio por Ticket</div>
            <div class="stat-value">Bs. {{ number_format($estadisticas['promedioIngreso'], 2) }}</div>
        </div>
    </div>

    <!-- Ingresos por Tipo de Espacio -->
    @if($estadisticas['ingresosPorTipo']->isNotEmpty())
        <div class="section-title">💰 Ingresos por Tipo de Espacio</div>
        <div class="tipo-ingresos">
            @foreach($estadisticas['ingresosPorTipo'] as $tipo => $datos)
                <div class="tipo-card">
                    <h4>{{ $tipo }}</h4>
                    <div class="monto">Bs. {{ number_format($datos['total'], 2) }}</div>
                    <div class="cantidad">{{ $datos['cantidad'] }} vehículos</div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Listado de Registros -->
    <div class="section-title">🚗 Registros de Estacionamiento ({{ $registros->count() }})</div>
    @if($registros->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Fecha Ingreso</th>
                    <th>Fecha Salida/Pago</th>
                    <th>Placa</th>
                    <th>Tipo Espacio</th>
                    <th>Espacio</th>
                    <th class="text-center">Estado</th>
                    <th class="text-right">Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registros as $registro)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($registro->horaIngreso)->format('d/m/Y H:i') }}</td>
                        <td>{{ $registro->horaSalida ? \Carbon\Carbon::parse($registro->horaSalida)->format('d/m/Y H:i') : '-' }}</td>
                        <td><strong>{{ $registro->placa }}</strong></td>
                        <td>{{ $registro->espacio->tipoEspacio->nombre ?? 'N/A' }}</td>
                        <td>{{ $registro->espacio->codigo ?? 'N/A' }}</td>
                        <td class="text-center">
                            @if($registro->estado === 'activo')
                                <span class="badge badge-active">Activo</span>
                            @else
                                <span class="badge badge-paid">Pagado</span>
                            @endif
                        </td>
                        <td class="text-right">
                            @if($registro->pago)
                                <strong>Bs. {{ number_format($registro->pago->monto, 2) }}</strong>
                            @else
                                <span style="color: #9ca3af;">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="6" style="text-align: right;"><strong>TOTAL INGRESOS:</strong></td>
                    <td style="text-align: right;"><strong>Bs. {{ number_format($estadisticas['totalIngresos'], 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>
    @else
        <div class="no-data">No se encontraron registros en el período seleccionado</div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p><strong>Estacionamiento JEMITA</strong> - Sistema de Gestión de Estacionamientos</p>
        <p>Generado automáticamente el {{ $fechaGeneracion }}</p>
        <p>🦙 Este documento es válido como comprobante de ingresos</p>
    </div>
</body>
</html>
