<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte</title>

    <style>

        body{
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h1{
            text-align:center;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:20px;
        }

        th{
            background:#e5e7eb;
        }

        th,td{
            border:1px solid #ccc;
            padding:8px;
        }

    </style>
</head>
<body>

    <h1>Reporte de {{ ucfirst($tipoReporte) }}</h1>

    <p>
        Desde: {{ $fechaInicio }}
        -
        Hasta: {{ $fechaFin }}
    </p>

    @if($tipoReporte == 'pacientes')

        <table>

            <thead>
                <tr>
                    <th>CI</th>
                    <th>Nombre</th>
                    <th>Sexo</th>
                    <th>Teléfono</th>
                </tr>
            </thead>

            <tbody>

                @foreach($resultados as $paciente)

                    <tr>
                        <td>{{ $paciente->ci }}</td>
                        <td>{{ $paciente->nombre_completo }}</td>
                        <td>{{ $paciente->sexo }}</td>
                        <td>{{ $paciente->telefono }}</td>
                    </tr>

                @endforeach

            </tbody>

        </table>

    @endif

    @if($tipoReporte == 'servicios')

        <table>

            <thead>
                <tr>
                    <th>Código</th>
                    <th>Paciente</th>
                    <th>Estado Pago</th>
                    <th>Estado Muestra</th>
                </tr>
            </thead>

            <tbody>

                @foreach($resultados as $servicio)

                    <tr>
                        <td>{{ $servicio->codigo_unico }}</td>
                        <td>{{ $servicio->paciente?->nombre_completo }}</td>
                        <td>{{ $servicio->estado_pago }}</td>
                        <td>{{ $servicio->estado_muestra }}</td>
                    </tr>

                @endforeach

            </tbody>

        </table>

    @endif

    @if($tipoReporte == 'ingresos')

        <table>

            <thead>
                <tr>
                    <th>Recibo</th>
                    <th>Subtotal</th>
                    <th>Descuento</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>

                @foreach($resultados as $recibo)

                    <tr>
                        <td>{{ $recibo->numero_correlativo }}</td>
                        <td>{{ $recibo->subtotal }}</td>
                        <td>{{ $recibo->descuento }}</td>
                        <td>{{ $recibo->total }}</td>
                    </tr>

                @endforeach

            </tbody>

        </table>

    @endif

    @if($tipoReporte == 'general')

        <h3>Resumen General</h3>

        <p>Pacientes: {{ $resultados['pacientes'] }}</p>

        <p>Servicios: {{ $resultados['servicios'] }}</p>

        <p>Ingresos: Bs {{ number_format($resultados['ingresos'],2) }}</p>

    @endif

</body>
</html>