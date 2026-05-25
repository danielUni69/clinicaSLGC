<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Pisos y Espacios</title>
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
            font-size: 24px;
            margin-bottom: 5px;
            color: #fbbf24;
        }

        .header .subtitle {
            font-size: 12px;
            color: #d1d5db;
        }

        .info-box {
            background: #f3f4f6;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #fbbf24;
            border-radius: 4px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            color: #4b5563;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .stat-card.green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .stat-card.red {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .stat-card.gray {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        }

        .stat-label {
            font-size: 9px;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 20px;
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

        .piso-container {
            margin-bottom: 25px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            page-break-inside: avoid;
        }

        .piso-header {
            background: #1f2937;
            color: white;
            padding: 15px;
        }

        .piso-header h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .piso-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 10px;
        }

        .piso-stat {
            background: rgba(255,255,255,0.1);
            padding: 8px;
            border-radius: 4px;
            text-align: center;
        }

        .piso-stat-label {
            font-size: 8px;
            opacity: 0.8;
        }

        .piso-stat-value {
            font-size: 16px;
            font-weight: bold;
            margin-top: 3px;
        }

        .espacios-grid {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 8px;
            padding: 15px;
            background: white;
        }

        .espacio-card {
            border: 2px solid;
            padding: 8px;
            border-radius: 4px;
            text-align: center;
            min-height: 60px;
        }

        .espacio-card.libre {
            background: #d1fae5;
            border-color: #10b981;
            color: #065f46;
        }

        .espacio-card.ocupado {
            background: #fee2e2;
            border-color: #ef4444;
            color: #991b1b;
        }

        .espacio-card.inactivo {
            background: #f3f4f6;
            border-color: #9ca3af;
            color: #4b5563;
        }

        .espacio-codigo {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .espacio-tipo {
            font-size: 7px;
            opacity: 0.8;
        }

        .tipo-espacios-grid {
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
            color: #1f2937;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .tipo-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            font-size: 9px;
        }

        .leyenda {
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .leyenda h4 {
            font-size: 12px;
            margin-bottom: 10px;
            color: #1f2937;
        }

        .leyenda-items {
            display: flex;
            gap: 20px;
        }

        .leyenda-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .leyenda-color {
            width: 20px;
            height: 20px;
            border-radius: 3px;
            border: 2px solid;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            color: #6b7280;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>🦙 ESTACIONAMIENTO JEMITA</h1>
        <div class="subtitle">Reporte de Pisos y Espacios</div>
    </div>

    <!-- Información del Reporte -->
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Piso:</span>
            <span>{{ $filtroPiso }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tipo de Espacio:</span>
            <span>{{ $filtroTipoEspacio }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Estado:</span>
            <span>{{ $filtroEstado }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Fecha de Generación:</span>
            <span>{{ $fechaGeneracion }}</span>
        </div>
    </div>

    <!-- Estadísticas Generales -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Espacios</div>
            <div class="stat-value">{{ $estadisticas['totalEspacios'] }}</div>
        </div>
        <div class="stat-card green">
            <div class="stat-label">Espacios Libres</div>
            <div class="stat-value">{{ $estadisticas['totalLibres'] }}</div>
        </div>
        <div class="stat-card red">
            <div class="stat-label">Espacios Ocupados</div>
            <div class="stat-value">{{ $estadisticas['totalOcupados'] }}</div>
        </div>
        <div class="stat-card gray">
            <div class="stat-label">Espacios Inactivos</div>
            <div class="stat-value">{{ $estadisticas['totalInactivos'] }}</div>
        </div>
    </div>

    <!-- Distribución por Tipo -->
    @if($estadisticas['espaciosPorTipo']->isNotEmpty())
        <div class="section-title">📊 Distribución por Tipo de Espacio</div>
        <div class="tipo-espacios-grid">
            @foreach($estadisticas['espaciosPorTipo'] as $tipo => $datos)
                <div class="tipo-card">
                    <h4>{{ $tipo }}</h4>
                    <div class="tipo-row">
                        <span>Total:</span>
                        <strong>{{ $datos['total'] }}</strong>
                    </div>
                    <div class="tipo-row">
                        <span style="color: #10b981;">Libres:</span>
                        <strong style="color: #10b981;">{{ $datos['libres'] }}</strong>
                    </div>
                    <div class="tipo-row">
                        <span style="color: #ef4444;">Ocupados:</span>
                        <strong style="color: #ef4444;">{{ $datos['ocupados'] }}</strong>
                    </div>
                    <div class="tipo-row">
                        <span style="color: #6b7280;">Inactivos:</span>
                        <strong style="color: #6b7280;">{{ $datos['inactivos'] }}</strong>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Listado de Pisos -->
    <div class="section-title">🏢 Detalle de Pisos y Espacios</div>
    @foreach($espaciosPorPiso as $pisoData)
        <div class="piso-container">
            <div class="piso-header">
                <h3>Piso {{ $pisoData['piso']->numero }}</h3>
                <p style="font-size: 10px; opacity: 0.9;">{{ $pisoData['total'] }} espacios - {{ $pisoData['porcentajeOcupacion'] }}% ocupación</p>

                <div class="piso-stats">
                    <div class="piso-stat">
                        <div class="piso-stat-label">Libres</div>
                        <div class="piso-stat-value" style="color: #10b981;">{{ $pisoData['libres'] }}</div>
                    </div>
                    <div class="piso-stat">
                        <div class="piso-stat-label">Ocupados</div>
                        <div class="piso-stat-value" style="color: #ef4444;">{{ $pisoData['ocupados'] }}</div>
                    </div>
                    <div class="piso-stat">
                        <div class="piso-stat-label">Inactivos</div>
                        <div class="piso-stat-value" style="color: #9ca3af;">{{ $pisoData['inactivos'] }}</div>
                    </div>
                </div>
            </div>

            <div class="espacios-grid">
                @foreach($pisoData['espacios'] as $espacio)
                    <div class="espacio-card {{ $espacio->estado }}">
                        <div class="espacio-codigo">{{ $espacio->codigo }}</div>
                        <div class="espacio-tipo">{{ $espacio->tipoEspacio->nombre ?? 'N/A' }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <!-- Leyenda -->
    <div class="leyenda">
        <h4>Leyenda de Estados</h4>
        <div class="leyenda-items">
            <div class="leyenda-item">
                <div class="leyenda-color" style="background: #d1fae5; border-color: #10b981;"></div>
                <span>Libre</span>
            </div>
            <div class="leyenda-item">
                <div class="leyenda-color" style="background: #fee2e2; border-color: #ef4444;"></div>
                <span>Ocupado</span>
            </div>
            <div class="leyenda-item">
                <div class="leyenda-color" style="background: #f3f4f6; border-color: #9ca3af;"></div>
                <span>Inactivo</span>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Estacionamiento JEMITA</strong> - Sistema de Gestión de Estacionamientos</p>
        <p>Generado automáticamente el {{ $fechaGeneracion }}</p>
    </div>
</body>
</html>
