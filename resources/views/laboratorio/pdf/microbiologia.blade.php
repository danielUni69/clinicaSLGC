<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Informe Microbiológico</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header {
            border-bottom: 2px solid #10b981;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #065f46;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 14px;
            color: #6b7280;
            font-weight: bold;
            text-transform: uppercase;
        }

        .patient-box {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .patient-table {
            width: 100%;
            border-collapse: collapse;
        }

        .patient-table td {
            padding: 3px;
            font-size: 12px;
        }

        .cultivo-box {
            margin-bottom: 30px;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
            padding: 15px;
            page-break-inside: avoid;
        }

        .cultivo-title {
            font-size: 14px;
            font-weight: bold;
            color: #065f46;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .resultado-destacado {
            font-size: 14px;
            font-weight: bold;
            padding: 8px;
            background-color: #ecfdf5;
            color: #047857;
            margin-bottom: 15px;
            text-align: center;
            border: 1px solid #a7f3d0;
        }

        .resultado-negativo {
            font-size: 14px;
            font-weight: bold;
            padding: 8px;
            background-color: #f3f4f6;
            color: #4b5563;
            margin-bottom: 15px;
            text-align: center;
            border: 1px solid #e5e7eb;
        }

        table.antibiograma {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.antibiograma th,
        table.antibiograma td {
            border: 1px solid #d1d5db;
            padding: 6px 10px;
            text-align: left;
            font-size: 11px;
        }

        table.antibiograma th {
            background-color: #f9fafb;
            font-weight: bold;
            color: #374151;
        }

        .sus-S {
            color: #059669;
            font-weight: bold;
        }

        .sus-I {
            color: #d97706;
            font-weight: bold;
        }

        .sus-R {
            color: #dc2626;
            font-weight: bold;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
        }

        .firma-box {
            width: 200px;
            margin: 40px auto 0;
            text-align: center;
            border-top: 1px solid #9ca3af;
            padding-top: 5px;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="logo">LABORATORIO CLÍNICO SGLC</div>
        <div class="subtitle">Informe Microbiológico Confidencial</div>
    </div>

    <div class="patient-box">
        <table class="patient-table">
            <tr>
                <td width="15%"><strong>Paciente:</strong></td>
                <td width="50%">{{ $servicio->paciente->nombre_completo ?? 'N/A' }}</td>
                <td width="15%"><strong>Fecha:</strong></td>
                <td width="20%">{{ $servicio->updated_at->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td><strong>C.I.:</strong></td>
                <td>{{ $servicio->paciente->ci ?? 'N/A' }}</td>
                <td><strong>Orden N°:</strong></td>
                <td>{{ $servicio->codigo_unico }}</td>
            </tr>
        </table>
    </div>

    @foreach ($cultivos as $cultivo)
        <div class="cultivo-box">
            <div class="cultivo-title">Examen: {{ $cultivo->tipoAnalisis->nombre ?? 'Cultivo' }}</div>

            @if ($cultivo->estado_cultivo === 'negativo')
                <div class="resultado-negativo">
                    NEGATIVO A LAS 48/72 HORAS DE INCUBACIÓN.<br>
                    <span style="font-size: 11px; font-weight: normal;">No se observó desarrollo de flora
                        patógena.</span>
                </div>
            @elseif($cultivo->estado_cultivo === 'positivo_identificado')
                <div class="resultado-destacado">
                    POSITIVO - SE AISLÓ: {{ strtoupper($cultivo->cepa_bacteriana) }}
                </div>

                <strong>Prueba de Susceptibilidad (Antibiograma)</strong>
                <table class="antibiograma">
                    <thead>
                        <tr>
                            <th>ANTIBIÓTICO TESTEADO</th>
                            <th style="width: 150px; text-align: center;">INTERPRETACIÓN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cultivo->resultados_antibiograma as $anti)
                            <tr>
                                <td>{{ $anti->nombre_antibiotico }}</td>
                                <td style="text-align: center;">
                                    @if ($anti->susceptibilidad === 'S')
                                        <span class="sus-S">Sensible (S)</span>
                                    @elseif($anti->susceptibilidad === 'I')
                                        <span class="sus-I">Intermedio (I)</span>
                                    @elseif($anti->susceptibilidad === 'R')
                                        <span class="sus-R">Resistente (R)</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="margin-top: 10px; font-size: 10px; color: #6b7280;">
                    <em>Leyenda: (S) Sensible - El germen es inhibido por concentraciones normales. (I) Intermedio -
                        Sensibilidad dependiente de dosis. (R) Resistente - El germen no es inhibido.</em>
                </div>
            @else
                <div style="padding: 10px; color: #d97706; text-align: center;">
                    <em>Muestra en periodo de incubación activo. Resultados pendientes.</em>
                </div>
            @endif
        </div>
    @endforeach

    <div class="firma-box">
        @php
            // Obtenemos el bioquímico del primer cultivo disponible
            $bioquimico = $cultivos->first()?->bioquimico;
        @endphp

        @if ($bioquimico)
            <span style="font-size: 14px; text-transform: uppercase;">
                <strong>{{ $bioquimico->name }}</strong>
            </span><br>
        @endif
        <strong>Firma del Bioquímico</strong><br>
        Sello de Laboratorio
    </div>
    <div class="footer">
        Documento generado electrónicamente por SGLC - Sistema de Gestión de Laboratorio Clínico.
    </div>

</body>

</html>
