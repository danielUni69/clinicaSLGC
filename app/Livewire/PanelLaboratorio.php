<?php

namespace App\Livewire;

use App\Models\Servicio;
use App\Models\Responsable;
use App\Models\MedicoSolicitante;
use App\Mail\ResultadosLaboratorioMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Livewire\Component;

class PanelLaboratorio extends Component
{
    // Propiedad para el filtro de fecha
    public $fechaFiltro;

    public function mount()
    {
        $this->fechaFiltro = Carbon::today()->toDateString();
    }

    public function registrarMuestra($id)
    {
        $servicio = Servicio::find($id);

        if ($servicio) {
            $servicio->update([
                'estado_muestra' => 'recolectada',
            ]);

            session()->flash('mensaje', 'Muestra del paciente ' . ($servicio->paciente->nombre_completo ?? '') . ' recepcionada correctamente.');
        }
    }

    public function reenviarResultadosPorCorreo(int $id)
{
    $servicio = Servicio::with([
        'paciente.responsable',
        'tiposAnalisis.categoria',
        'resultados',
        'paciente'
    ])->findOrFail($id);

    if (!in_array($servicio->estado_muestra, ['completada', 'rechazada'])) {
        session()->flash('mensaje', '❌ Solo se pueden enviar resultados de órdenes completadas o rechazadas.');
        return;
    }

    $destinatarios = [];

    if ($servicio->paciente?->email) {
        $destinatarios[] = $servicio->paciente->email;
    }

    if ($servicio->paciente?->responsable?->correo) {
        $destinatarios[] = $servicio->paciente->responsable->correo;
    }

    if ($servicio->medico_id) {
        $correoMedico = MedicoSolicitante::where('id', $servicio->medico_id)->value('correo');
        if ($correoMedico) $destinatarios[] = $correoMedico;
    }

    $destinatarios = array_unique(array_filter($destinatarios));

    if (empty($destinatarios)) {
        session()->flash('mensaje', '⚠️ No se encontraron correos electrónicos para enviar.');
        return;
    }

    try {
        $mail = new ResultadosLaboratorioMail($servicio);
        
        Mail::to($destinatarios)
            ->bcc('sistemasupds448@gmail.com')   // copia oculta para ti
            ->send($mail);

        $nombre = $servicio->paciente->nombre_completo ?? 'el paciente';

        session()->flash('mensaje', "✅ Resultados enviados correctamente a {$nombre}");

    } catch (\Throwable $e) {
        report($e);
        session()->flash('mensaje', '❌ Error al enviar correo: ' . $e->getMessage());
    }
}

    public function render()
    {
        $relaciones = ['paciente.responsable', 'tiposAnalisis.categoria'];

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
            ->whereDate('updated_at', $this->fechaFiltro)
            ->orderBy('updated_at', 'desc')
            ->take(100)
            ->get();

        return view('livewire.panel-laboratorio', [
            'muestras_pendientes' => $muestras_pendientes,
            'muestras_recolectadas' => $muestras_recolectadas,
            'muestras_completadas' => $muestras_completadas,
        ]);
    }
}