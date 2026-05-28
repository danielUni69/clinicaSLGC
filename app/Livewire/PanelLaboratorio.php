<?php

namespace App\Livewire;

use App\Models\Servicio;
use Livewire\Component;

class PanelLaboratorio extends Component
{
    // --- ESTADO DEL MODAL DE RECEPCIÓN ---
    public $modalVisible = false;

    public $servicio_seleccionado = null;

    // Opciones clínicas para la evaluación visual inicial
    public $calidad_muestra = 'Normal';

    public $opciones_calidad = [
        'Normal',
        'Lipémica (Leve)',
        'Lipémica (Severa)',
        'Ictérica',
        'Hemolizada (Leve)',
        'Muestra Inviable (Rechazada)',
    ];

    // --- MÉTODOS DEL FLUJO DE TRABAJO ---

    public function abrirModalRecepcion($servicio_id)
    {
        $this->servicio_seleccionado = $servicio_id;
        $this->calidad_muestra = 'Normal'; // Reseteamos al valor por defecto
        $this->modalVisible = true;
    }

    public function cerrarModal()
    {
        $this->reset(['modalVisible', 'servicio_seleccionado', 'calidad_muestra']);
    }

    public function registrarMuestra()
    {
        // Validamos que el servicio exista
        $servicio = Servicio::find($this->servicio_seleccionado);

        if ($servicio) {
            // Regla de Negocio: Si la muestra es inviable, se rechaza. Si no, pasa a recolectada.
            $nuevo_estado = ($this->calidad_muestra === 'Muestra Inviable (Rechazada)')
                            ? 'rechazada'
                            : 'recolectada';

            $servicio->update([
                'estado_muestra' => $nuevo_estado,
                'observaciones_calidad' => $this->calidad_muestra,
            ]);

            session()->flash('mensaje', 'Estado de la muestra actualizado correctamente.');
        }

        $this->cerrarModal();
    }

    // --- RENDERIZADO REACTIVO (KANBAN) ---

    public function render()
    {
        // Columna 1: Órdenes pagadas esperando al paciente en la sala de toma de muestras
        $muestras_pendientes = Servicio::with(['paciente', 'tiposAnalisis'])
            ->where('estado_pago', 'pagado')
            ->where('estado_muestra', 'pendiente')
            ->orderBy('created_at', 'asc') // Las más antiguas primero (FIFO)
            ->get();

        // Columna 2: Muestras ya recibidas, listas para transcribir resultados en el sistema
        $muestras_recolectadas = Servicio::with(['paciente', 'tiposAnalisis'])
            ->where('estado_pago', 'pagado')
            ->where('estado_muestra', 'recolectada')
            ->orderBy('updated_at', 'desc') // Las recién procesadas arriba
            ->get();

        return view('livewire.panel-laboratorio', [
            'muestras_pendientes' => $muestras_pendientes,
            'muestras_recolectadas' => $muestras_recolectadas,
        ]);
    }
}
