<?php

namespace App\Http\Controllers;

use App\Models\Antibiograma;
use App\Models\Cultivo;
use App\Models\Servicio;
use Barryvdh\DomPDF\Facade\Pdf;

class LaboratorioPdfController extends Controller
{
    // 1. PDF DE RUTINA
    public function descargar($id)
    {
        // CORRECCIÓN 1: Cargamos 'tiposAnalisis' y 'resultados' como relaciones separadas del Servicio
        $servicio = Servicio::with(['paciente', 'tiposAnalisis', 'resultados'])->findOrFail($id);

        // CORRECCIÓN 2: Apuntamos a la carpeta exacta según tu terminal
        $pdf = Pdf::loadView('laboratorio.pdf.resultados', compact('servicio'));

        return $pdf->stream('Resultados_Clinicos_'.$servicio->codigo_unico.'.pdf');
    }

    // 2. PDF EXCLUSIVO PARA MICROBIOLOGÍA
    public function pdfMicrobiologia($id)
    {
        $servicio = Servicio::with(['paciente', 'tiposAnalisis.categoria'])->findOrFail($id);

        $cultivos = Cultivo::with('tipoAnalisis')->where('servicio_id', $id)->get();

        foreach ($cultivos as $cultivo) {
            if ($cultivo->estado_cultivo === 'positivo_identificado') {
                $cultivo->resultados_antibiograma = Antibiograma::join('antibioticos', 'antibiogramas.antibiotico_id', '=', 'antibioticos.id')
                    ->where('antibiogramas.cultivo_id', $cultivo->id)
                    ->select('antibioticos.nombre_antibiotico', 'antibiogramas.susceptibilidad')
                    ->orderBy('antibioticos.nombre_antibiotico', 'asc')
                    ->get();
            }
        }

        // CORRECCIÓN 2: Apuntamos a la carpeta exacta según tu terminal
        $pdf = Pdf::loadView('laboratorio.pdf.microbiologia', compact('servicio', 'cultivos'));

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Microbiologia_'.$servicio->codigo_unico.'.pdf');
    }

    public function ticketTermico($id)
    {
        $servicio = \App\Models\Servicio::with(['paciente', 'tiposAnalisis', 'recibo'])->findOrFail($id);

        // Diseñamos el papel para impresora térmica (80mm de ancho = aprox 226pt)
        $customPaper = [0, 0, 226.77, 600]; // Ancho x Largo. El largo se adapta al contenido.

        $pdf = Pdf::loadView('laboratorio.pdf.ticket', compact('servicio'))
            ->setPaper($customPaper, 'portrait');

        return $pdf->stream('Ticket_'.$servicio->codigo_unico.'.pdf');
    }
}
