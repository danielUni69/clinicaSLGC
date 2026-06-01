<?php

namespace App\Mail;

use App\Models\Antibiograma;
use App\Models\Cultivo;
use App\Models\Servicio;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResultadosLaboratorioMail extends Mailable
{
    use Queueable, SerializesModels;

    public $servicio;

    public function __construct(Servicio $servicio)
    {
        $this->servicio = $servicio;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Resultados de Laboratorio Clínico - Orden '.$this->servicio->codigo_unico,
        );
    }

    public function content(): Content
    {
        // Puedes crear una vista HTML simple en resources/views/emails/resultados.blade.php
        return new Content(
            view: 'emails.resultados',
        );
    }

    /**
     * Matriz de adjuntos dinámicos en tiempo de ejecución
     */
    public function attachments(): array
    {
        $attachments = [];

        // Clasificamos qué exámenes tiene la orden para saber qué adjuntar
        $tiene_rutina = false;
        $tiene_micro = false;

        foreach ($this->servicio->tiposAnalisis as $analisis) {
            $nombreCat = strtolower($analisis->categoria->nombre ?? '');
            if (str_contains($nombreCat, 'microbiolog') || str_contains($nombreCat, 'cultivo')) {
                $tiene_micro = true;
            } else {
                $tiene_rutina = true;
            }
        }

        // --- ADJUNTO 1: PDF DE QUÍMICA / RUTINA (Si corresponde) ---
        if ($tiene_rutina) {
            // Recargamos el servicio con las relaciones exactas que usa el PDF de rutina
            $servicioRutina = Servicio::with(['paciente', 'tiposAnalisis', 'resultados'])->find($this->servicio->id);

            $pdfRutina = Pdf::loadView('laboratorio.pdf.resultados', ['servicio' => $servicioRutina]);

            $attachments[] = Attachment::fromData(fn () => $pdfRutina->output(), 'Resultados_Clinicos_'.$this->servicio->codigo_unico.'.pdf')
                ->withMime('application/pdf');
        }

        // --- ADJUNTO 2: PDF DE MICROBIOLOGÍA (Si corresponde) ---
        if ($tiene_micro) {
            $cultivos = Cultivo::with('tipoAnalisis')->where('servicio_id', $this->servicio->id)->get();

            foreach ($cultivos as $cultivo) {
                if ($cultivo->estado_cultivo === 'positivo_identificado') {
                    $cultivo->resultados_antibiograma = Antibiograma::join('antibioticos', 'antibiogramas.antibiotico_id', '=', 'antibioticos.id')
                        ->where('antibiogramas.cultivo_id', $cultivo->id)
                        ->select('antibioticos.nombre_antibiotico', 'antibiogramas.susceptibilidad')
                        ->orderBy('antibioticos.nombre_antibiotico', 'asc')
                        ->get();
                }
            }

            $pdfMicro = Pdf::loadView('laboratorio.pdf.microbiologia', [
                'servicio' => $this->servicio,
                'cultivos' => $cultivos,
            ])->setPaper('A4', 'portrait');

            $attachments[] = Attachment::fromData(fn () => $pdfMicro->output(), 'Informe_Microbiologico_'.$this->servicio->codigo_unico.'.pdf')
                ->withMime('application/pdf');
        }

        return $attachments;
    }
}
