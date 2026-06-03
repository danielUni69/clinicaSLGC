<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Resultados de Laboratorio</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header {
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 14px;
            color: #6b7280;
            font-weight: bold;
            text-transform: uppercase;
        }

        .patient-box {
            border: 1px solid #e5e7eb;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .patient-table {
            width: 100%;
            border-collapse: collapse;
        }

        .patient-table td {
            padding: 4px;
            font-size: 12px;
        }

        /* Estilos de la tabla de resultados */
        table.resultados {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table.resultados th {
            border-bottom: 2px solid #333;
            padding: 8px 4px;
            text-align: left;
            font-size: 11px;
            color: #333;
            text-transform: uppercase;
        }

        table.resultados td {
            border-bottom: 1px solid #e5e7eb;
            padding: 8px 4px;
            font-size: 12px;
        }

        .categoria-titulo {
            font-weight: bold;
            color: #1e40af;
            background-color: #f3f4f6;
            padding: 8px 4px;
            margin-top: 15px;
            font-size: 12px;
            text-transform: uppercase;
        }

        /* Alertas visuales sutiles para el médico */
        .resultado-anormal {
            font-weight: bold;
        }

        .asterisco {
            font-weight: bold;
            font-size: 14px;
        }

        .footer {
            margin-top: 50px;
            font-size: 10px;
            color: #9ca3af;
            text-align: center;
        }

        .firma-box {
            width: 200px;
            float: right;
            text-align: center;
            border-top: 1px solid #333;
            padding-top: 5px;
            margin-top: 40px;
        }

        .clear {
            clear: both;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="logo">LABORATORIO CLÍNICO SGLC</div>
        <div class="subtitle">Informe de Resultados Analíticos</div>
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

    <table class="resultados">
        <thead>
            <tr>
                <th width="40%">DETERMINACIÓN</th>
                <th width="20%" style="text-align: center;">RESULTADO</th>
                <th width="15%" style="text-align: center;">UNIDADES</th>
                <th width="25%" style="text-align: right;">VALOR DE REFERENCIA</th>
            </tr>
        </thead>
        <tbody>
            @php
                // Agrupamos los resultados por categoría para que el PDF se vea ordenado (Química junta, Hemato junta)
                $resultadosPorCategoria = $servicio->resultados->groupBy(function ($res) {
                    return $res->tipoAnalisis->categoria->nombre ?? 'EXÁMENES GENERALES';
                });
            @endphp

            @foreach ($resultadosPorCategoria as $categoria => $resultados)
                <tr>
                    <td colspan="4">
                        <div class="categoria-titulo">{{ $categoria }}</div>
                    </td>
                </tr>

                @foreach ($resultados as $res)
                    @php
                        $analisis = $res->tipoAnalisis;
                        $esAnormal = in_array($res->alerta_rango, ['alto', 'bajo', 'critico']);
                        $esFemenino = in_array($servicio->paciente->sexo, ['F', 'Femenino', 'Mujer']);
                    @endphp
                    <tr>
                        <td>{{ $analisis->nombre }}</td>

                        <td style="text-align: center;" class="{{ $esAnormal ? 'resultado-anormal' : '' }}">
                            {{ $res->valor_registrado }}
                            @if ($esAnormal)
                                <span class="asterisco">*</span>
                            @endif
                        </td>

                        <td style="text-align: center; color: #6b7280;">
                            {{ $analisis->tipo_parámetro === 'numerico' ? $analisis->unidad_medida : '' }}
                        </td>

                        <td style="text-align: right; color: #6b7280; font-size: 11px;">
                            @if ($analisis->tipo_parámetro === 'numerico')
                                {{ $esFemenino ? $analisis->rango_min_femenino : $analisis->rango_min_masculino }} -
                                {{ $esFemenino ? $analisis->rango_max_femenino : $analisis->rango_max_masculino }}
                            @else
                                @if ($analisis->valor_referencia_cualitativo && $analisis->valor_referencia_cualitativo !== 'N/A')
                                    {{ $analisis->valor_referencia_cualitativo }}
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 15px; font-size: 10px; color: #6b7280;">
        <span class="asterisco">*</span> <em>Indica valores fuera del rango de referencia esperado.</em>
    </div>

    <div class="clear"></div>

    <div class="firma-box">
        @php
            // Obtenemos el bioquímico del primer resultado disponible
            $bioquimico = $servicio->resultados->first()?->bioquimico;
        @endphp

        @if ($bioquimico)
            <span style="font-size: 14px; text-transform: uppercase;">
                <strong>{{ $bioquimico->name }}</strong>
            </span><br>
        @endif
        <strong>Firma y Sello del Bioquímico</strong><br>
    </div>

    <div class="clear"></div>

    <div class="footer">
        Documento generado electrónicamente por SGLC - Sistema de Gestión de Laboratorio Clínico.
    </div>

</body>

</html>
