<?php

namespace App\Livewire\Reportes;

use App\Models\Paciente;
use App\Models\Recibo;
use App\Models\Servicio;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;

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

    public function updatedFechaInicio()
    {
        if ($this->fechaFin && $this->fechaInicio > $this->fechaFin) {
            $this->fechaInicio = $this->fechaFin;
            session()->flash('warning', 'La fecha de inicio no puede ser posterior a la fecha fin.');
        }
    }

    public function updatedFechaFin()
    {
        if ($this->fechaInicio && $this->fechaFin < $this->fechaInicio) {
            $this->fechaFin = $this->fechaInicio;
            session()->flash('warning', 'La fecha fin no puede ser anterior a la fecha de inicio.');
        }
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
                    $this->fechaInicio.' 00:00:00',
                    $this->fechaFin.' 23:59:59',
                ])
                    ->orderBy('created_at', 'desc')
                    ->get();
                break;

            case 'servicios':
                $this->resultados = Servicio::with(['paciente', 'medico'])
                    ->whereBetween('created_at', [
                        $this->fechaInicio.' 00:00:00',
                        $this->fechaFin.' 23:59:59',
                    ])
                    ->orderBy('created_at', 'desc')
                    ->get();
                break;

            case 'ingresos':
                $this->resultados = Recibo::with('servicio')
                    ->whereBetween('created_at', [
                        $this->fechaInicio.' 00:00:00',
                        $this->fechaFin.' 23:59:59',
                    ])
                    ->orderBy('created_at', 'desc')
                    ->get();
                break;

                // --- NUEVO REPORTE: EXÁMENES MÁS SOLICITADOS ---
            case 'analisis':
                $servicios = Servicio::with('tiposAnalisis.categoria')
                    ->whereBetween('created_at', [
                        $this->fechaInicio.' 00:00:00',
                        $this->fechaFin.' 23:59:59',
                    ])
                    ->get();

                $estadisticas = [];
                foreach ($servicios as $servicio) {
                    foreach ($servicio->tiposAnalisis as $analisis) {
                        if (! isset($estadisticas[$analisis->id])) {
                            $estadisticas[$analisis->id] = [
                                'nombre' => $analisis->nombre,
                                'categoria' => $analisis->categoria ? $analisis->categoria->nombre : 'Sin Categoría',
                                'solicitudes' => 0,
                                'ingresos' => 0,
                            ];
                        }
                        $estadisticas[$analisis->id]['solicitudes'] += 1;
                        $estadisticas[$analisis->id]['ingresos'] += $analisis->costo;
                    }
                }

                // Ordenar de mayor a menor por cantidad de solicitudes
                usort($estadisticas, function ($a, $b) {
                    return $b['solicitudes'] <=> $a['solicitudes'];
                });

                $this->resultados = $estadisticas;
                break;

                // --- NUEVO REPORTE: MËDICOS QUE MÁS DERIVAN ---
            case 'medicos':
                $servicios = Servicio::with('medico')
                    ->whereNotNull('medico_id')
                    ->whereBetween('created_at', [
                        $this->fechaInicio.' 00:00:00',
                        $this->fechaFin.' 23:59:59',
                    ])
                    ->get();

                $estadisticasMed = [];
                foreach ($servicios as $servicio) {
                    $medicoId = $servicio->medico_id;
                    if (! isset($estadisticasMed[$medicoId])) {
                        $estadisticasMed[$medicoId] = [
                            // Intenta tomar 'nombre', 'nombre_completo' u otro que tengas en tu BD
                            'nombre' => $servicio->medico->nombre ?? $servicio->medico->nombre_completo ?? 'Dr(a).',
                            'especialidad' => $servicio->medico->especialidad ?? '-',
                            'derivaciones' => 0,
                        ];
                    }
                    $estadisticasMed[$medicoId]['derivaciones'] += 1;
                }

                // Ordenar de mayor a menor
                usort($estadisticasMed, function ($a, $b) {
                    return $b['derivaciones'] <=> $a['derivaciones'];
                });

                $this->resultados = $estadisticasMed;
                break;

            case 'general':
                $this->resultados = [
                    'pacientes' => Paciente::whereBetween('created_at', [
                        $this->fechaInicio.' 00:00:00',
                        $this->fechaFin.' 23:59:59',
                    ])->count(),

                    'servicios' => Servicio::whereBetween('created_at', [
                        $this->fechaInicio.' 00:00:00',
                        $this->fechaFin.' 23:59:59',
                    ])->count(),

                    'ingresos' => Recibo::whereBetween('created_at', [
                        $this->fechaInicio.' 00:00:00',
                        $this->fechaFin.' 23:59:59',
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
            fn () => print ($pdf->output()),
            'reporte_'.$this->tipoReporte.'.pdf'
        );
    }

    public function render()
    {
        return view('livewire.reportes.reporte-pacientes');
    }
}

