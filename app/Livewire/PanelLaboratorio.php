<?php

namespace App\Livewire;

use App\Models\Servicio;
use Livewire\Component;

class PanelLaboratorio extends Component
{
    // Eliminamos las variables del modal, ya no las necesitamos.

    public function registrarMuestra($id)
    {
        $servicio = Servicio::find($id);

        if ($servicio) {
            // Pasamos directo a 'recolectada' sin preguntar
            $servicio->update([
                'estado_muestra' => 'recolectada',
            ]);

            // Un mensaje rápido que desaparece solo
            session()->flash('mensaje', 'Muestra del paciente '.($servicio->paciente->nombre_completo ?? '').' recepcionada.');
        }
    }

    public function render()
    {
        $relaciones = ['paciente', 'tiposAnalisis.categoria'];

        $muestras_pendientes = Servicio::with($relaciones)
            ->where('estado_pago', 'pagado')
            ->where('estado_muestra', 'pendiente')
            ->orderBy('created_at', 'asc')
            ->get();

        $muestras_recolectadas = Servicio::with($relaciones)
            ->where('estado_pago', 'pagado')
            ->where('estado_muestra', 'recolectada')
            ->orderBy('updated_at', 'desc')
            ->get();

        $muestras_completadas = Servicio::with($relaciones)
            ->where('estado_pago', 'pagado')
            ->whereIn('estado_muestra', ['completada', 'rechazada'])
            ->orderBy('updated_at', 'desc')
            ->take(50)
            ->get();

        return view('livewire.panel-laboratorio', [
            'muestras_pendientes' => $muestras_pendientes,
            'muestras_recolectadas' => $muestras_recolectadas,
            'muestras_completadas' => $muestras_completadas,
        ]);
    }
}
