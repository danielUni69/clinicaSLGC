<?php

namespace App\Livewire\Pacientes;

use Livewire\Component;

class DetalleHistorial extends Component
{
    public int $pacienteId;

    public function render()
    {
        // Servicios del paciente
        $servicios = \App\Models\Servicio::query()
            ->with(['tiposAnalisis', 'resultados.tipoAnalisis', 'cultivos.tipoAnalisis'])
            ->where('paciente_id', $this->pacienteId)
            ->orderByDesc('id')
            ->get();

        // Si necesitas ambos listados separados:
        $analisis = \App\Models\ResultadoAnalisis::query()
            ->with('tipoAnalisis')
            ->whereIn('servicio_id', $servicios->pluck('id'))
            ->orderByDesc('id')
            ->get();

        $cultivos = \App\Models\Cultivo::query()
            ->with('tipoAnalisis')
            ->whereIn('servicio_id', $servicios->pluck('id'))
            ->orderByDesc('id')
            ->get();

        return view('livewire.pacientes.detalle-historial', [
            'servicios' => $servicios,
            'analisis' => $analisis,
            'cultivos' => $cultivos,
        ]);
    }
}

