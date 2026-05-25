<?php

namespace App\Livewire;

use App\Models\MedicoSolicitante;
use App\Models\Paciente;
use App\Models\Recibo;
use App\Models\Responsable;
use App\Models\Servicio;
use App\Models\TipoAnalisis;
use Illuminate\Support\Facades\DB; // <-- NUEVO MODELO
use Illuminate\Support\Str;
use Livewire\Component;

class CreateServicio extends Component
{
    // --- ESTADO DEL FORMULARIO ---

    // Datos del Paciente
    public $paciente_id = null;

    public $paciente_nombre = '';

    public $busqueda_ci = '';

    public $paciente_error = '';

    // Datos Opcionales del Responsable
    public $responsable_nombre = '';

    public $responsable_celular = '';

    public $responsable_relacion = '';

    // Datos del Médico
    public $medico_id = null;

    // Carrito de Análisis
    public $analisis_seleccionados = [];

    public $analisis_a_agregar = '';

    // Totales
    public $total_a_pagar = 0;

    public $metodo_pago = 'Efectivo';

    // --- MÉTODOS DE BÚSQUEDA Y CARRITO ---

    public function buscarPaciente()
    {
        $this->paciente_error = '';
        // Cargamos al paciente junto con su responsable si ya tiene uno
        $paciente = Paciente::with('responsable')->where('ci', $this->busqueda_ci)->first();

        if ($paciente) {
            $this->paciente_id = $paciente->id;
            $this->paciente_nombre = $paciente->nombre_completo;

            // Si el paciente ya tenía un tutor asignado antes, lo cargamos en pantalla
            if ($paciente->responsable) {
                $this->responsable_nombre = $paciente->responsable->nombre_completo;
                $this->responsable_celular = $paciente->responsable->celular;
                $this->responsable_relacion = $paciente->responsable->relacion;
            } else {
                $this->reset(['responsable_nombre', 'responsable_celular', 'responsable_relacion']);
            }
        } else {
            $this->paciente_id = null;
            $this->paciente_nombre = '';
            $this->reset(['responsable_nombre', 'responsable_celular', 'responsable_relacion']);
            $this->paciente_error = 'Paciente no encontrado. Por favor, registrelo primero.';
        }
    }

    public function agregarAnalisis()
    {
        if (empty($this->analisis_a_agregar)) {
            return;
        }

        $analisis = TipoAnalisis::find($this->analisis_a_agregar);
        $existe = collect($this->analisis_seleccionados)->contains('id', $analisis->id);

        if (! $existe && $analisis) {
            $this->analisis_seleccionados[] = [
                'id' => $analisis->id,
                'nombre' => $analisis->nombre,
                'costo' => $analisis->costo,
            ];
            $this->calcularTotal();
        }
        $this->analisis_a_agregar = '';
    }

    public function quitarAnalisis($indice)
    {
        unset($this->analisis_seleccionados[$indice]);
        $this->analisis_seleccionados = array_values($this->analisis_seleccionados);
        $this->calcularTotal();
    }

    private function calcularTotal()
    {
        $this->total_a_pagar = collect($this->analisis_seleccionados)->sum('costo');
    }

    // --- GUARDADO TRANSACCIONAL ---

    public function guardarServicio()
    {
        $this->validate([
            'paciente_id' => 'required',
            'medico_id' => 'required',
            'analisis_seleccionados' => 'required|array|min:1',
            'metodo_pago' => 'required',
        ], [
            'paciente_id.required' => 'Debe buscar y seleccionar un paciente.',
            'medico_id.required' => 'Debe elegir un médico solicitante.',
            'analisis_seleccionados.required' => 'Debe agregar al menos un análisis clínico.',
        ]);

        DB::beginTransaction();

        try {
            $paciente = Paciente::find($this->paciente_id);

            // A. Lógica del Responsable (Si se llenó el campo de nombre, se crea o actualiza)
            if (! empty(trim($this->responsable_nombre))) {
                if (! $paciente->responsable_id) {
                    // Es un paciente nuevo que necesita tutor
                    $responsable = Responsable::create([
                        'nombre_completo' => $this->responsable_nombre,
                        'celular' => $this->responsable_celular ?? 'Sin registro',
                        'relacion' => $this->responsable_relacion ?? 'Familiar',
                    ]);
                    $paciente->update(['responsable_id' => $responsable->id]);
                } else {
                    // Ya tenía tutor, solo actualizamos los datos por si cambiaron
                    $paciente->responsable->update([
                        'nombre_completo' => $this->responsable_nombre,
                        'celular' => $this->responsable_celular,
                        'relacion' => $this->responsable_relacion,
                    ]);
                }
            }

            // B. Crear la orden de Servicio
            $servicio = Servicio::create([
                'paciente_id' => $this->paciente_id,
                'medico_id' => $this->medico_id,
                'codigo_unico' => 'ILLAPA-'.date('Ymd').'-'.Str::random(4),
                'estado_pago' => 'pagado',
                'estado_muestra' => 'pendiente',
            ]);

            // C. Guardar los análisis en la tabla pivote
            $ids_analisis = collect($this->analisis_seleccionados)->pluck('id')->toArray();
            $servicio->tiposAnalisis()->attach($ids_analisis);

            // D. Generar el Recibo
            Recibo::create([
                'servicio_id' => $servicio->id,
                'numero_correlativo' => 'REC-'.str_pad($servicio->id, 6, '0', STR_PAD_LEFT),
                'subtotal' => $this->total_a_pagar,
                'descuento' => 0,
                'total' => $this->total_a_pagar,
                'medio_pago' => $this->metodo_pago,
            ]);

            DB::commit();

            session()->flash('mensaje', 'Servicio registrado y pagado con éxito. Correlativo: '.$servicio->codigo_unico);
            $this->reset(['paciente_id', 'paciente_nombre', 'busqueda_ci', 'responsable_nombre', 'responsable_celular', 'responsable_relacion', 'medico_id', 'analisis_seleccionados', 'total_a_pagar']);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error crítico al guardar: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.create-servicio', [
            'medicos' => MedicoSolicitante::all(),
            'analisis_disponibles' => TipoAnalisis::where('estado', true)->get(),
        ]);
    }
}
