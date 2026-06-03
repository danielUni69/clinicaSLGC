<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de {{ ucfirst($tipoReporte) }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #334155;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #0284c7;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #0284c7;
            font-size: 22px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #64748b;
        }

        .fecha-emision {
            text-align: right;
            font-size: 10px;
            color: #94a3b8;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
        }

        th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .text-green {
            color: #16a34a;
        }

        .text-red {
            color: #dc2626;
        }

        .text-blue {
            color: #2563eb;
        }

        /* Estilos específicos para el reporte General */
        .summary-container {
            width: 100%;
            margin-top: 20px;
        }

        .summary-box {
            width: 30%;
            display: inline-block;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            background-color: #f8fafc;
            margin-right: 1%;
            box-sizing: border-box;
        }

        .summary-title {
            font-size: 11px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .summary-value {
            font-size: 24px;
            font-weight: bold;
            color: #0f172a;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Clínica Regional Illapa - SGLC</h1>
        <p><strong>REPORTE OFICIAL DE {{ strtoupper($tipoReporte) }}</strong></p>
        <p>Periodo de evaluación: {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} al
            {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</p>
    </div>

    <div class="fecha-emision">
        Documento generado el: {{ now()->format('d/m/Y H:i') }}
    </div>

    {{-- ==============================
         TABLA: PACIENTES
    ============================== --}}
    @if ($tipoReporte == 'pacientes')
        <table>
            <thead>
                <tr>
                    <th>N°</th>
                    <th>CI / Documento</th>
                    <th>Nombre Completo</th>
                    <th>Sexo</th>
                    <th>Teléfono</th>
                    <th>Fecha Registro</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($resultados as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->ci }}</td>
                        <td class="font-bold">{{ $item->nombre_completo }}</td>
                        <td class="text-center">{{ $item->sexo }}</td>
                        <td>{{ $item->telefono ?? 'Sin registro' }}</td>
                        <td class="text-center">{{ $item->created_at->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- ==============================
         TABLA: SERVICIOS
    ============================== --}}
    @if ($tipoReporte == 'servicios')
        <table>
            <thead>
                <tr>
                    <th>Código Servicio</th>
                    <th>Paciente</th>
                    <th>Médico Solicitante</th>
                    <th>Estado Pago</th>
                    <th>Estado Muestra</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($resultados as $item)
                    <tr>
                        <td class="font-bold">{{ $item->codigo_unico }}</td>
                        <td>{{ $item->paciente?->nombre_completo }}</td>
                        <td>{{ $item->medico?->nombre ?? ($item->medico?->nombre_completo ?? 'Independiente') }}</td>
                        <td class="text-center" style="text-transform: uppercase; font-size: 10px;">
                            {{ $item->estado_pago }}</td>
                        <td class="text-center" style="text-transform: uppercase; font-size: 10px;">
                            {{ $item->estado_muestra }}</td>
                        <td class="text-center">{{ $item->created_at->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- ==============================
         TABLA: INGRESOS
    ============================== --}}
    @if ($tipoReporte == 'ingresos')
        <table>
            <thead>
                <tr>
                    <th>N° Recibo</th>
                    <th>Servicio Ref.</th>
                    <th class="text-right">Subtotal</th>
                    <th class="text-right">Descuento</th>
                    <th class="text-center">Medio Pago</th>
                    <th class="text-right">Total Final</th>
                </tr>
            </thead>
            <tbody>
                @php $granTotal = 0; @endphp
                @foreach ($resultados as $item)
                    @php $granTotal += $item->total; @endphp
                    <tr>
                        <td class="font-bold">{{ $item->numero_correlativo }}</td>
                        <td>{{ $item->servicio?->codigo_unico ?? 'S/R' }}</td>
                        <td class="text-right">Bs. {{ number_format($item->subtotal, 2) }}</td>
                        <td class="text-right text-red">Bs. {{ number_format($item->descuento, 2) }}</td>
                        <td class="text-center">{{ strtoupper($item->medio_pago) }}</td>
                        <td class="text-right font-bold text-green">Bs. {{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-right" style="background-color: #e2e8f0; font-size: 12px;">TOTAL
                        INGRESOS DEL PERIODO:</th>
                    <th class="text-right font-bold text-green" style="background-color: #e2e8f0; font-size: 12px;">Bs.
                        {{ number_format($granTotal, 2) }}</th>
                </tr>
            </tfoot>
        </table>
    @endif

    {{-- ==============================
         TABLA: ANÁLISIS MÁS SOLICITADOS
    ============================== --}}
    @if ($tipoReporte == 'analisis')
        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 10%;">Ranking</th>
                    <th style="width: 40%;">Examen Clínico</th>
                    <th style="width: 20%;">Categoría</th>
                    <th class="text-center" style="width: 15%;">Solicitudes</th>
                    <th class="text-right" style="width: 15%;">Ingresos Estimados</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($resultados as $index => $item)
                    <tr>
                        <td class="text-center font-bold text-blue">#{{ $index + 1 }}</td>
                        <td class="font-bold">{{ $item['nombre'] }}</td>
                        <td>{{ $item['categoria'] }}</td>
                        <td class="text-center font-bold">{{ $item['solicitudes'] }}</td>
                        <td class="text-right text-green">Bs. {{ number_format($item['ingresos'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- ==============================
         TABLA: MÉDICOS DERIVADORES
    ============================== --}}
    @if ($tipoReporte == 'medicos')
        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 10%;">Ranking</th>
                    <th style="width: 45%;">Médico Solicitante</th>
                    <th style="width: 25%;">Especialidad</th>
                    <th class="text-center" style="width: 20%;">Total Pacientes Derivados</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($resultados as $index => $item)
                    <tr>
                        <td class="text-center font-bold text-blue">#{{ $index + 1 }}</td>
                        <td class="font-bold">Dr(a). {{ $item['nombre'] }}</td>
                        <td>{{ $item['especialidad'] }}</td>
                        <td class="text-center font-bold">{{ $item['derivaciones'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- ==============================
         REPORTE GENERAL (CAJAS DE RESUMEN)
    ============================== --}}
    @if ($tipoReporte == 'general')
        <div class="summary-container">
            <div class="summary-box">
                <div class="summary-title">Pacientes Atendidos</div>
                <div class="summary-value text-blue">{{ $resultados['pacientes'] }}</div>
            </div>
            <div class="summary-box">
                <div class="summary-title">Órdenes de Servicio</div>
                <div class="summary-value text-blue">{{ $resultados['servicios'] }}</div>
            </div>
            <div class="summary-box">
                <div class="summary-title">Ingresos Totales (Bs)</div>
                <div class="summary-value text-green">{{ number_format($resultados['ingresos'], 2) }}</div>
            </div>
        </div>

        <p style="margin-top: 40px; text-align: center; color: #64748b; font-size: 11px;">
            Este reporte ejecutivo resume la actividad central del Laboratorio Clínico Illapa dentro del periodo
            seleccionado.<br>
            Los datos económicos reflejados representan únicamente los recibos confirmados.
        </p>
    @endif

</body>

</html>

