<?php

namespace App\Livewire;

use App\Mail\ResultadosLaboratorioMail;
use App\Models\MedicoSolicitante;
use App\Models\Responsable;
use App\Models\ResultadoAnalisis;
use App\Models\Servicio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ProcesarResultados extends Component
{
    public Servicio $servicio;

    public $paciente_nombre = '';

    public $paciente_ci = '';

    public $fecha_servicio = '';

    public $paciente_sexo = 'M';

    public $valores = [];

    // --- Variables para el Modal y Correos ---
    public $mostrarModalPreview = false;

    public $tipo_email_paciente = 'paciente'; // Puede ser 'paciente' o 'responsable'

    public $email_paciente = '';

    public $email_medico = '';

    public $resultados_a_previsualizar = [];

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

        // Cargar parámetros a evaluar
        foreach ($modeloServicio->tiposAnalisis as $analisis) {
            $nombreCat = strtolower($analisis->categoria->nombre ?? '');
            if (str_contains($nombreCat, 'microbiolog') || str_contains($nombreCat, 'cultivo')) {
                continue;
            }

            $resultadoPrevio = ResultadoAnalisis::where('servicio_id', $modeloServicio->id)
                ->where('tipo_analisis_id', $analisis->id)
                ->first();

            $esFemenino = ($this->paciente_sexo === 'F' || $this->paciente_sexo === 'Femenino' || $this->paciente_sexo === 'Mujer');
            $minimo = $esFemenino ? $analisis->rango_min_femenino : $analisis->rango_min_masculino;
            $maximo = $esFemenino ? $analisis->rango_max_femenino : $analisis->rango_max_masculino;

            $opciones_cualitativas = [];
            if ($analisis->tipo_parámetro === 'cualitativo') {
                $ref = strtolower(trim($analisis->valor_referencia_cualitativo ?? ''));
                $nombre_prueba = strtolower(trim($analisis->nombre));

                if (str_contains($nombre_prueba, 'grupo sanguíneo') || str_contains($nombre_prueba, 'factor rh')) {
                    $opciones_cualitativas = ['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'];
                } elseif (str_contains($ref, 'negativo') || str_contains($ref, 'positivo')) {
                    $opciones_cualitativas = ['Negativo', 'Positivo'];
                } elseif (str_contains($ref, 'reactivo')) {
                    $opciones_cualitativas = ['No Reactivo', 'Reactivo'];
                }
            }

            $this->valores[$analisis->id] = [
                'valor' => $resultadoPrevio ? $resultadoPrevio->valor_registrado : '',
                'alerta' => $resultadoPrevio ? $resultadoPrevio->alerta_rango : 'normal',
                'nombre' => $analisis->nombre,
                'unidad' => $analisis->unidad_medida,
                'tipo' => $analisis->tipo_parámetro,
                'min' => $minimo,
                'max' => $maximo,
                'ref_cualitativa' => $analisis->valor_referencia_cualitativo,
                'opciones_cualitativas' => $opciones_cualitativas,
            ];
        }
    }

    public function updated($property, $value)
    {
        if (str_starts_with($property, 'valores.') && str_ends_with($property, '.valor')) {
            $partes = explode('.', $property);
            $analisis_id = $partes[1];
            $this->calcularAlertaAutomatica($analisis_id, $value);
        }
    }

    private function calcularAlertaAutomatica($id, $valor)
    {
        $data = $this->valores[$id];
        $valorLimpio = trim($valor);

        if ($data['tipo'] === 'numerico' && $data['min'] !== null && $data['max'] !== null) {
            if ($valorLimpio === '' || ! is_numeric($valorLimpio)) {
                $this->valores[$id]['alerta'] = 'normal';

                return;
            }
            $num = (float) $valorLimpio;
            $min = (float) $data['min'];
            $max = (float) $data['max'];

            if ($num < $min) {
                $this->valores[$id]['alerta'] = 'bajo';
            } elseif ($num > $max) {
                $this->valores[$id]['alerta'] = 'alto';
            } else {
                $this->valores[$id]['alerta'] = 'normal';
            }
        } elseif ($data['tipo'] === 'cualitativo') {
            $nombre_prueba = strtolower($data['nombre']);

            if (str_contains($nombre_prueba, 'embarazo') || str_contains($nombre_prueba, 'grupo sanguíneo') || $data['ref_cualitativa'] === 'N/A') {
                $this->valores[$id]['alerta'] = 'normal';

                return;
            }

            if ($valorLimpio === '' || $data['ref_cualitativa'] === null) {
                $this->valores[$id]['alerta'] = 'normal';

                return;
            }

            if (strtolower($valorLimpio) === strtolower(trim($data['ref_cualitativa']))) {
                $this->valores[$id]['alerta'] = 'normal';
            } else {
                $this->valores[$id]['alerta'] = 'critico';
            }
        }
    }

    public function previsualizarResultados()
    {
        $this->validate([
            'valores.*.valor' => 'nullable|string|max:255',
        ]);

        $this->resultados_a_previsualizar = array_filter($this->valores, function ($item) {
            return trim($item['valor']) !== '';
        });

        if (empty($this->resultados_a_previsualizar)) {
            session()->flash('error', 'Debe llenar al menos un resultado para previsualizar.');

            return;
        }

        $this->mostrarModalPreview = true;
    }

    public function cerrarModal()
    {
        $this->mostrarModalPreview = false;
        session()->forget('error_email'); // Limpiar error visual
    }

    public function confirmarYEnviar()
    {
        $this->validate([
            'email_paciente' => 'nullable|email|max:255',
            'email_medico' => 'nullable|email|max:255',
        ]);

        // REGLA DE ORO: No puede finalizar si todos los correos están vacíos
        if (empty(trim($this->email_paciente)) && empty(trim($this->email_medico))) {
            session()->flash('error_email', 'Debe asignar obligatoriamente al menos un correo (Paciente/Responsable o Médico) para enviar los resultados.');

            return;
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

            // Registrar los resultados transcritos
            foreach ($this->valores as $analisis_id => $data) {
                $valorLimpio = trim($data['valor']);
                if ($valorLimpio !== '') {
                    ResultadoAnalisis::updateOrCreate(
                        ['servicio_id' => $this->servicio->id, 'tipo_analisis_id' => $analisis_id],
                        ['valor_registrado' => $valorLimpio, 'alerta_rango' => $data['alerta'], 'bioquimico_id' => Auth::id() ?? 1]
                    );
                } else {
                    ResultadoAnalisis::where('servicio_id', $this->servicio->id)->where('tipo_analisis_id', $analisis_id)->delete();
                }
            }

            // Comprobar estado del Kanban
            $analisisRutinaIds = $this->servicio->tiposAnalisis()->whereDoesntHave('categoria', function ($q) {
                $q->where('nombre', 'like', '%microbiolog%')->orWhere('nombre', 'like', '%cultivo%');
            })->pluck('tipos_analisis.id');

            $resultadosCompletados = ResultadoAnalisis::where('servicio_id', $this->servicio->id)
                ->whereIn('tipo_analisis_id', $analisisRutinaIds)->count();

            $rutinaCompletada = ($resultadosCompletados === $analisisRutinaIds->count());

            $tieneCultivos = $this->servicio->tiposAnalisis()->whereHas('categoria', function ($q) {
                $q->where('nombre', 'like', '%microbiolog%')->orWhere('nombre', 'like', '%cultivo%');
            })->exists();

            $estado_final = 'recolectada';

            if ($rutinaCompletada && ! $tieneCultivos) {
                $estado_final = 'completada';
                $mensajeExito = 'Orden FINALIZADA con éxito.';
            } else {
                $mensajeExito = 'Guardado parcial. La orden sigue EN PROCESO.';
            }

            $this->servicio->update([
                'estado_muestra' => $estado_final,
            ]);

            // Enviar Correo a la lista de destinatarios
            if ($estado_final === 'completada') {
                $destinatarios = array_filter([$this->email_paciente, $this->email_medico]); // Elimina campos vacíos

                if (count($destinatarios) > 0) {
                    Mail::to($destinatarios)->send(new ResultadosLaboratorioMail($this->servicio));
                    $mensajeExito .= ' El PDF oficial fue enviado correctamente.';
                }
            }

            DB::commit();
            session()->flash('mensaje', $mensajeExito);

            return redirect()->route('laboratorio.panel');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->mostrarModalPreview = false;
            session()->flash('error', 'Error en la transacción: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.procesar-resultados');
    }
}
