<?php

namespace App\Livewire;

use App\Mail\ResultadosLaboratorioMail;
use App\Models\Antibiograma;
use App\Models\Antibiotico;
use App\Models\Cultivo;
use App\Models\ReporteEvolucion;
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

    // --- NUEVAS VARIABLES PARA EL MODAL Y CORREO ---
    public $mostrarModalPreview = false;

    public $email_paciente = '';

    public $analisis_a_guardar = null; // ID del cultivo que se está previsualizando
    // -----------------------------------------------

    public function mount($id)
    {
        $modeloServicio = Servicio::with(['paciente', 'tiposAnalisis.categoria'])->findOrFail($id);
        $this->servicio = $modeloServicio;

        $this->paciente_nombre = $modeloServicio->paciente->nombre_completo ?? 'Desconocido';
        $this->paciente_ci = $modeloServicio->paciente->ci ?? 'Sin registro';
        $this->fecha_servicio = $modeloServicio->created_at ? $modeloServicio->created_at->format('d/m/Y H:i') : 'Sin fecha';
        $this->paciente_sexo = $modeloServicio->paciente->sexo ?? 'M';
        $this->email_paciente = $modeloServicio->paciente->email ?? '';

        $this->antibioticos_disponibles = Antibiotico::where('estado', true)->orderBy('nombre_antibiotico', 'asc')->get();

        $analisisCultivos = $modeloServicio->tiposAnalisis->filter(function ($analisis) {
            $nombreCat = strtolower($analisis->categoria->nombre ?? '');

            return str_contains($nombreCat, 'microbiolog') || str_contains($nombreCat, 'cultivo');
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

    // --- NUEVO: ABRIR EL MODAL ---
    public function previsualizarCultivo($analisis_id)
    {
        $this->analisis_a_guardar = $analisis_id;
        $this->mostrarModalPreview = true;
    }

    public function cerrarModal()
    {
        $this->mostrarModalPreview = false;
        $this->analisis_a_guardar = null;
    }

    // --- NUEVO: GUARDAR Y ENVIAR DESDE EL MODAL ---
    public function confirmarYEnviar()
    {
        $this->validate(['email_paciente' => 'nullable|email|max:255']);

        $analisis_id = $this->analisis_a_guardar;
        $data = $this->cultivos_data[$analisis_id];

        DB::beginTransaction();
        try {
            if (! empty($this->email_paciente)) {
                $this->servicio->paciente->update(['email' => $this->email_paciente]);
            }

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
            $analisisCultivoIds = $this->servicio->tiposAnalisis()->whereHas('categoria', function ($query) {
                $query->where('nombre', 'like', '%microbiolog%')->orWhere('nombre', 'like', '%cultivo%');
            })->pluck('tipos_analisis.id');

            $cultivosFinalizadosCount = Cultivo::where('servicio_id', $this->servicio->id)
                ->whereIn('tipo_analisis_id', $analisisCultivoIds)
                ->whereIn('estado_cultivo', ['negativo', 'positivo_identificado'])
                ->count();

            $todosCultivosListos = ($cultivosFinalizadosCount === $analisisCultivoIds->count());

            $analisisRutinaIds = $this->servicio->tiposAnalisis()->whereDoesntHave('categoria', function ($query) {
                $query->where('nombre', 'like', '%microbiolog%')->orWhere('nombre', 'like', '%cultivo%');
            })->pluck('tipos_analisis.id');

            $rutinaCompletada = true;
            if ($analisisRutinaIds->isNotEmpty()) {
                $resultadosRutinaCount = ResultadoAnalisis::where('servicio_id', $this->servicio->id)->whereIn('tipo_analisis_id', $analisisRutinaIds)->count();
                $rutinaCompletada = ($resultadosRutinaCount === $analisisRutinaIds->count());
            }

            if ($todosCultivosListos && $rutinaCompletada) {
                $this->servicio->update(['estado_muestra' => 'completada']);
                $mensajeFinal = 'Informe microbiológico firmado con éxito. Servicio FINALIZADO.';

                // ENVÍO DE CORREO AUTOMÁTICO
                if (! empty($this->email_paciente)) {
                    Mail::to($this->email_paciente)->send(new ResultadosLaboratorioMail($this->servicio));
                    $mensajeFinal .= ' Se envió el reporte al paciente.';
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
