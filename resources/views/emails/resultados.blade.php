<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Resultados Disponibles</title>
</head>

<body style="font-family: sans-serif; color: #333; line-height: 1.6; padding: 20px;">
    <h2 style="color: #2563eb;">Laboratorio Clínico SGLC</h2>
    <p>Estimado(a) <strong>{{ $servicio->paciente->nombre_completo }}</strong>,</p>
    <p>Le informamos que los análisis clínicos correspondientes a su orden de servicio
        <strong>{{ $servicio->codigo_unico }}</strong> han sido validados y firmados por el personal bioquímico
        responsable.</p>
    <p>Adjunto a este correo electrónico encontrará los informes oficiales en formato PDF listos para su descarga o impresión.</p>

    <p style="margin-top: 14px;">
        Para ver también sus resultados en línea, puede ingresar a:
        <br>
        <strong style="color:#2563eb;">http://127.0.0.1:8000/laboratorio</strong>
    </p>

    <br>
    <p style="font-size: 12px; color: #374151;">
        <strong>Gracias</strong> por confiar en nuestro servicio. Si tiene alguna consulta, por favor comuníquese con nuestro laboratorio.
    </p>

    <p style="font-size: 11px; color: #7c728a;"><em>Nota: Este es un mensaje automatizado, por favor no responda directamente a esta dirección de correo.</em></p>

</body>

</html>
