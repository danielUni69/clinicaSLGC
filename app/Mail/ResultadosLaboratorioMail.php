<?php

namespace App\Mail;

use App\Models\Servicio;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Storage;

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
        $nombre = $this->servicio->paciente?->nombre_completo ?? 'Paciente';
        return new Envelope(
            subject: "Resultados de Laboratorio - {$nombre}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.resultados-laboratorio',
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        $basePath = storage_path('app/public/resultados/');

        // PDF General
        $pdfGeneral = $basePath . $this->servicio->id . '_general.pdf';
        if (file_exists($pdfGeneral)) {
            $attachments[] = Attachment::fromPath($pdfGeneral)
                ->as('Resultados_Laboratorio.pdf')
                ->withMime('application/pdf');
        }

        // PDF Microbiología / Cultivo
        $pdfMicro = $basePath . $this->servicio->id . '_micro.pdf';
        if (file_exists($pdfMicro)) {
            $attachments[] = Attachment::fromPath($pdfMicro)
                ->as('Resultados_Cultivo.pdf')
                ->withMime('application/pdf');
        }

        return $attachments;
    }
}