@component('mail::message')
# Resultados de Laboratorio

Estimado(a) **{{ $servicio->paciente->nombre_completo }}**,

Nos complace informarle que sus resultados de laboratorio ya están disponibles.

**Código de Orden:** `{{ $servicio->codigo_unico }}`  
**Fecha:** {{ now()->format('d/m/Y') }}

Hemos adjuntado los reportes en PDF para su revisión.

Agradecemos su confianza en nuestro laboratorio. Si tiene alguna consulta, no dude en contactarnos.

**¡Gracias por elegirnos!**

Saludos cordiales,  
**Laboratorio UPDS**  
📞 Teléfono: (591) 79735565  
✉️ sistemasupds448@gmail.com
@endcomponent