<?php

namespace App\Livewire;

use App\Models\Servicio;
use Carbon\Carbon;
use Livewire\Component;

class PanelLaboratorio extends Component
{
    // Propiedad para enlazar con el input de fecha
    public $fechaFiltro;

    public function mount()
    {
        // Por defecto, inicializamos con la fecha actual
        $this->fechaFiltro = Carbon::today()->toDateString();
    }

    public function registrarMuestra($id)
    {
        $servicio = Servicio::find($id);

        if ($servicio) {
            $servicio->update([
                'estado_muestra' => 'recolectada',
            ]);

            session()->flash('mensaje', 'Muestra del paciente '.($servicio->paciente->nombre_completo ?? '').' recepcionada correctamente.');
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
            // Filtramos por la fecha seleccionada en el UI
            ->whereDate('updated_at', $this->fechaFiltro)
            ->orderBy('updated_at', 'desc')
            ->take(100) // Límite de seguridad para no saturar la vista
            ->get();

        return view('livewire.panel-laboratorio', [
            'muestras_pendientes' => $muestras_pendientes,
            'muestras_recolectadas' => $muestras_recolectadas,
            'muestras_completadas' => $muestras_completadas,
        ]);
    }
}
