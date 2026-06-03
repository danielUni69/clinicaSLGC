<?php

namespace App\Livewire;

use App\Mail\ResultadosLaboratorioMail;
use App\Models\Antibiograma;
use App\Models\Antibiotico;
use App\Models\Cultivo;
use App\Models\MedicoSolicitante;
use App\Models\ReporteEvolucion;
use App\Models\Responsable;
use App\Models\ResultadoAnalisis;
use App\Models\Servicio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ProcesarCultivo extends Component
{
    public Servicio $servicio;

    public $paciente_nombre = '';

    public $paciente_ci = '';

    public $fecha_servicio = '';

    public $paciente_sexo = 'M';

    public $cultivos_data = [];

    public $antibioticos_disponibles = [];

    public $analisis_activo_id = null;

    // --- VARIABLES PARA EL MODAL Y CORREO ---
    public $mostrarModalPreview = false;

    public $analisis_a_guardar = null;

    public $tipo_email_paciente = 'paciente'; // 'paciente' o 'responsable'

    public $email_paciente = '';

    public $email_medico = '';

    public function mount($id)
    {
        $modeloServicio = Servicio::with(['paciente', 'tiposAnalisis.categoria'])->findOrFail($id);
        $this->servicio = $modeloServicio;

        $this->paciente_nombre = $modeloServicio->paciente->nombre_completo ?? 'Desconocido';
        $this->paciente_ci = $modeloServicio->paciente->ci ?? 'Sin registro';
        $this->fecha_servicio = $modeloServicio->created_at ? $modeloServicio->created_at->format('d/m/Y H:i') : 'Sin fecha';
        $this->paciente_sexo = $modeloServicio->paciente->sexo ?? 'M';

        // 1. Lógica para el correo del Paciente o Responsable
        $paciente = $modeloServicio->paciente;
        if ($paciente->responsable_id) {
            $responsable = Responsable::find($paciente->responsable_id);
            $this->email_paciente = $responsable ? $responsable->correo : '';
            $this->tipo_email_paciente = 'responsable';
        } else {
            $this->email_paciente = $paciente->email ?? '';
            $this->tipo_email_paciente = 'paciente';
        }

        // 2. Lógica para el correo del Médico Solicitante
        if ($modeloServicio->medico_id) {
            $medico = MedicoSolicitante::find($modeloServicio->medico_id);
            $this->email_medico = $medico ? $medico->correo : '';
        }

        $this->antibioticos_disponibles = Antibiotico::where('estado', true)->orderBy('nombre_antibiotico', 'asc')->get();

        $analisisCultivos = $modeloServicio->tiposAnalisis->filter(function ($analisis) {
            return $analisis->categoria && $analisis->categoria->es_cultivo;
        });

        if ($analisisCultivos->isEmpty()) {
            return redirect()->route('laboratorio.panel')->with('error', 'Esta orden no contiene parámetros microbiológicos.');
        }

        $this->analisis_activo_id = $analisisCultivos->first()->id;

        foreach ($analisisCultivos as $analisis) {
            $cultivo = Cultivo::where('servicio_id', $modeloServicio->id)->where('tipo_analisis_id', $analisis->id)->first();

            $antibiogramaMapeado = [];
            foreach ($this->antibioticos_disponibles as $anti) {
                $antibiogramaMapeado[$anti->id] = '';
            }

            if ($cultivo) {
                $antiRegistrados = Antibiograma::where('cultivo_id', $cultivo->id)->get();
                foreach ($antiRegistrados as $ar) {
                    $antibiogramaMapeado[$ar->antibiotico_id] = $ar->susceptibilidad;
                }
            }

            $this->cultivos_data[$analisis->id] = [
                'cultivo_id' => $cultivo ? $cultivo->id : null,
                'nombre_examen' => $analisis->nombre,
                'estado_cultivo' => $cultivo ? $cultivo->estado_cultivo : 'en_incubacion',
                'cepa_bacteriana' => $cultivo ? $cultivo->cepa_bacteriana : '',
                'nueva_observacion' => '',
                'evoluciones' => $cultivo ? ReporteEvolucion::where('cultivo_id', $cultivo->id)->orderBy('created_at', 'asc')->get()->toArray() : [],
                'antibiograma' => $antibiogramaMapeado,
            ];
        }
    }

    public function cambiarPestaña($analisis_id)
    {
        $this->analisis_activo_id = $analisis_id;
    }

    public function agregarEvolucion($analisis_id)
    {
        $obs = trim($this->cultivos_data[$analisis_id]['nueva_observacion']);
        if (empty($obs)) {
            return;
        }

        DB::beginTransaction();
        try {
            $cultivo = Cultivo::updateOrCreate(
                ['servicio_id' => $this->servicio->id, 'tipo_analisis_id' => $analisis_id],
                ['estado_cultivo' => $this->cultivos_data[$analisis_id]['estado_cultivo'], 'cepa_bacteriana' => $this->cultivos_data[$analisis_id]['cepa_bacteriana'] ?: null, 'bioquimico_id' => Auth::id() ?? 1]
            );

            $this->cultivos_data[$analisis_id]['cultivo_id'] = $cultivo->id;
            ReporteEvolucion::create(['cultivo_id' => $cultivo->id, 'observacion' => $obs]);

            DB::commit();

            $this->cultivos_data[$analisis_id]['evoluciones'] = ReporteEvolucion::where('cultivo_id', $cultivo->id)->orderBy('created_at', 'asc')->get()->toArray();
            $this->cultivos_data[$analisis_id]['nueva_observacion'] = '';
            session()->flash('mensaje_bitacora', 'Evolución registrada.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error en bitácora: '.$e->getMessage());
        }
    }

    public function previsualizarCultivo($analisis_id)
    {
        $this->analisis_a_guardar = $analisis_id;
        $this->mostrarModalPreview = true;
    }

    public function cerrarModal()
    {
        $this->mostrarModalPreview = false;
        $this->analisis_a_guardar = null;
        session()->forget('error_email');
    }

    public function confirmarYEnviar()
    {
        $this->validate([
            'email_paciente' => 'nullable|email|max:255',
            'email_medico' => 'nullable|email|max:255',
        ]);

        $analisis_id = $this->analisis_a_guardar;
        $data = $this->cultivos_data[$analisis_id];

        // REGLA DE ORO: Validar correos si se va a completar el servicio
        $analisisCultivoIds = $this->servicio->tiposAnalisis->filter(function ($analisis) {
            return $analisis->categoria && $analisis->categoria->es_cultivo;
        })->pluck('id');

        $cultivosFinalizadosCount = Cultivo::where('servicio_id', $this->servicio->id)
            ->whereIn('tipo_analisis_id', $analisisCultivoIds)
            ->whereIn('estado_cultivo', ['negativo', 'positivo_identificado'])
            ->count();

        // Evaluamos si este guardado completará todos los cultivos
        $todosCultivosListosFuturo = ($cultivosFinalizadosCount + ($data['estado_cultivo'] !== 'en_incubacion' ? 1 : 0) >= $analisisCultivoIds->count());

        $analisisRutinaIds = $this->servicio->tiposAnalisis->filter(function ($analisis) {
            return ! $analisis->categoria || ! $analisis->categoria->es_cultivo;
        })->pluck('id');

        $rutinaCompletada = true;
        if ($analisisRutinaIds->isNotEmpty()) {
            $resultadosRutinaCount = ResultadoAnalisis::where('servicio_id', $this->servicio->id)->whereIn('tipo_analisis_id', $analisisRutinaIds)->count();
            $rutinaCompletada = ($resultadosRutinaCount === $analisisRutinaIds->count());
        }

        // Si este guardado va a finalizar el servicio completo, validamos los correos
        if ($todosCultivosListosFuturo && $rutinaCompletada) {
            if (empty(trim($this->email_paciente)) && empty(trim($this->email_medico))) {
                session()->flash('error_email', 'Debe asignar obligatoriamente al menos un correo (Paciente/Responsable o Médico) para enviar los resultados.');

                return;
            }
        }

        DB::beginTransaction();
        try {
            // Actualizar correos en la base de datos
            $paciente = $this->servicio->paciente;
            if ($this->tipo_email_paciente === 'responsable' && $paciente->responsable_id) {
                Responsable::where('id', $paciente->responsable_id)->update(['correo' => $this->email_paciente]);
            } else {
                $paciente->update(['email' => $this->email_paciente]);
            }

            if ($this->servicio->medico_id && ! empty($this->email_medico)) {
                MedicoSolicitante::where('id', $this->servicio->medico_id)->update(['correo' => $this->email_medico]);
            }

            // Registrar datos del cultivo
            $cultivo = Cultivo::updateOrCreate(
                ['servicio_id' => $this->servicio->id, 'tipo_analisis_id' => $analisis_id],
                [
                    'estado_cultivo' => $data['estado_cultivo'],
                    'cepa_bacteriana' => $data['estado_cultivo'] === 'positivo_identificado' ? $data['cepa_bacteriana'] : null,
                    'bioquimico_id' => Auth::id() ?? 1,
                ]
            );

            if ($data['estado_cultivo'] === 'positivo_identificado') {
                foreach ($data['antibiograma'] as $anti_id => $susceptibilidad) {
                    if (! empty($susceptibilidad)) {
                        Antibiograma::updateOrCreate(['cultivo_id' => $cultivo->id, 'antibiotico_id' => $anti_id], ['susceptibilidad' => $susceptibilidad]);
                    } else {
                        Antibiograma::where('cultivo_id', $cultivo->id)->where('antibiotico_id', $anti_id)->delete();
                    }
                }
            } else {
                Antibiograma::where('cultivo_id', $cultivo->id)->delete();
            }

            // MÁQUINA DE ESTADOS KANBAN
            $cultivosFinalizadosCount = Cultivo::where('servicio_id', $this->servicio->id)
                ->whereIn('tipo_analisis_id', $analisisCultivoIds)
                ->whereIn('estado_cultivo', ['negativo', 'positivo_identificado'])
                ->count();

            $todosCultivosListos = ($cultivosFinalizadosCount === $analisisCultivoIds->count());

            if ($todosCultivosListos && $rutinaCompletada) {
                $this->servicio->update(['estado_muestra' => 'completada']);
                $mensajeFinal = 'Informe microbiológico firmado con éxito. Servicio FINALIZADO.';

                $destinatarios = array_filter([$this->email_paciente, $this->email_medico]);
                if (count($destinatarios) > 0) {
                    Mail::to($destinatarios)->send(new ResultadosLaboratorioMail($this->servicio));
                    $mensajeFinal .= ' El PDF oficial fue enviado correctamente.';
                }
            } else {
                $this->servicio->update(['estado_muestra' => 'recolectada']);
                $mensajeFinal = 'Avance guardado. La orden continúa EN PROCESO por otras pruebas.';
            }

            DB::commit();
            $this->cerrarModal();
            session()->flash('mensaje', $mensajeFinal);

            return redirect()->route('laboratorio.panel');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->cerrarModal();
            session()->flash('error', 'Error en transacción: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.procesar-cultivo');
    }
}
