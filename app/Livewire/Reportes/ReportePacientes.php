<?php

namespace App\Livewire\Reportes;

use App\Models\Paciente;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Servicio;
use App\Models\Recibo;
class ReportePacientes extends Component
{
    public $fechaInicio;
    public $fechaFin;

    public $tipoReporte = 'pacientes';

    public $resultados = [];

    public function mount()
    {
        $this->fechaInicio = now()->startOfMonth()->format('Y-m-d');
        $this->fechaFin = now()->format('Y-m-d');
    }

    public function generar()
{
    $this->validate([
        'fechaInicio' => 'required|date|before_or_equal:today',
        'fechaFin' => 'required|date|after_or_equal:fechaInicio|before_or_equal:today',
    ]);

    switch ($this->tipoReporte) {

        case 'pacientes':

            $this->resultados = Paciente::whereBetween('created_at', [
                $this->fechaInicio . ' 00:00:00',
                $this->fechaFin . ' 23:59:59',
            ])
            ->orderBy('created_at', 'desc')
            ->get();

            break;

        case 'servicios':

            $this->resultados = Servicio::with([
                'paciente',
                'medico'
            ])
            ->whereBetween('created_at', [
                $this->fechaInicio . ' 00:00:00',
                $this->fechaFin . ' 23:59:59',
            ])
            ->orderBy('created_at', 'desc')
            ->get();

            break;

        case 'ingresos':

            $this->resultados = Recibo::with('servicio')
            ->whereBetween('created_at', [
                $this->fechaInicio . ' 00:00:00',
                $this->fechaFin . ' 23:59:59',
            ])
            ->orderBy('created_at', 'desc')
            ->get();

            break;

        case 'general':

            $this->resultados = [
                'pacientes' => Paciente::whereBetween('created_at', [
                    $this->fechaInicio . ' 00:00:00',
                    $this->fechaFin . ' 23:59:59',
                ])->count(),

                'servicios' => Servicio::whereBetween('created_at', [
                    $this->fechaInicio . ' 00:00:00',
                    $this->fechaFin . ' 23:59:59',
                ])->count(),

                'ingresos' => Recibo::whereBetween('created_at', [
                    $this->fechaInicio . ' 00:00:00',
                    $this->fechaFin . ' 23:59:59',
                ])->sum('total'),
            ];

            break;
    }
}
public function exportarPdf()
{
    $pdf = Pdf::loadView(
        'pdf.reportes.pacientes',
        [
            'resultados' => $this->resultados,
            'fechaInicio' => $this->fechaInicio,
            'fechaFin' => $this->fechaFin,
            'tipoReporte' => $this->tipoReporte,
        ]
    );

    return response()->streamDownload(
        fn () => print($pdf->output()),
        'reporte_' . $this->tipoReporte . '.pdf'
    );
}

    public function render()
    {
        return view('livewire.reportes.reporte-pacientes');
    }
}