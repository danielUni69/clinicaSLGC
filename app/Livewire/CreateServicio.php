<?php

namespace App\Livewire;

use App\Models\Categoria;
use App\Models\MedicoSolicitante;
use App\Models\Paciente;
use App\Models\Recibo;
use App\Models\Responsable;
use App\Models\Servicio;
use App\Models\TipoAnalisis;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateServicio extends Component
{
    public $paciente_id = null;

    public $paciente_nombre = '';

    public $paciente_email = '';

    public $paciente_edad = 0;   // <-- NUEVO: Para saber la edad

    public $es_menor = false;    // <-- NUEVO: Bandera inteligente

    public $busqueda_ci = '';

    public $paciente_error = '';

    // VARIABLES DEL RESPONSABLE
    public $responsable_nombre = '';

    public $responsable_celular = '';

    public $responsable_relacion = '';

    public $responsable_correo = '';

    public $tiene_responsable_previo = false;

    public $editar_responsable = true;

    public $medico_id = null;

    public $analisis_ids = [];

    public $analisis_seleccionados = [];

    // --- VARIABLES DE CAJA ---
    public $total_a_pagar = 0;

    public $metodo_pago = 'Efectivo';

    public $monto_recibido = 0;

    public $cambio_o_saldo = 0;

    public function buscarPaciente()
    {
        $this->paciente_error = '';
        $paciente = Paciente::with('responsable')->where('ci', $this->busqueda_ci)->first();

        if ($paciente) {
            $this->paciente_id = $paciente->id;
            $this->paciente_nombre = $paciente->nombre_completo;
            $this->paciente_email = $paciente->email ?? '';

            // Evaluamos la edad del paciente usando el accesor que creaste en el modelo
            $this->paciente_edad = $paciente->edad;
            $this->es_menor = $this->paciente_edad < 18;

            if ($paciente->responsable) {
                $this->responsable_nombre = $paciente->responsable->nombre_completo;
                $this->responsable_celular = $paciente->responsable->celular;
                $this->responsable_relacion = $paciente->responsable->relacion;
                $this->responsable_correo = $paciente->responsable->correo ?? '';
                $this->tiene_responsable_previo = true;
                $this->editar_responsable = false; // Bloqueamos los inputs
            } else {
                $this->reset(['responsable_nombre', 'responsable_celular', 'responsable_relacion', 'responsable_correo']);
                $this->tiene_responsable_previo = false;
                $this->editar_responsable = true; // Dejamos abierto para escribir
            }
        } else {
            $this->paciente_id = null;
            $this->paciente_nombre = '';
            $this->reset(['paciente_email', 'paciente_edad', 'es_menor', 'responsable_nombre', 'responsable_celular', 'responsable_relacion', 'responsable_correo', 'tiene_responsable_previo']);
            $this->editar_responsable = true;
            $this->paciente_error = 'Paciente no encontrado. Por favor, regístrelo primero en el módulo de Pacientes.';
        }
    }

    public function habilitarEdicionResponsable()
    {
        $this->editar_responsable = true;
    }

    public function updatedAnalisisIds()
    {
        $this->sincronizarCarrito();
    }

    public function quitarAnalisis($id)
    {
        $this->analisis_ids = array_filter($this->analisis_ids, function ($val) use ($id) {
            return $val != $id;
        });
        $this->sincronizarCarrito();
    }

    private function sincronizarCarrito()
    {
        $this->analisis_seleccionados = [];
        $this->total_a_pagar = 0;

        if (! empty($this->analisis_ids)) {
            $analisis = TipoAnalisis::with('categoria')->whereIn('id', $this->analisis_ids)->get();

            foreach ($analisis as $item) {
                $this->analisis_seleccionados[] = [
                    'id' => $item->id,
                    'nombre' => $item->nombre,
                    'costo' => $item->costo,
                    'es_cultivo' => $item->categoria ? (bool) $item->categoria->es_cultivo : false,
                ];
                $this->total_a_pagar += $item->costo;
            }
        }

        $this->monto_recibido = $this->total_a_pagar;
        $this->calcularCaja();
    }

    public function updatedMontoRecibido()
    {
        $this->calcularCaja();
    }

    private function calcularCaja()
    {
        $monto = (float) $this->monto_recibido;
        $total = (float) $this->total_a_pagar;
        $this->cambio_o_saldo = $monto - $total;
    }

    public function guardarServicio()
    {
        $rules = [
            'paciente_id' => 'required',
            'medico_id' => 'nullable',
            'analisis_ids' => 'required|array|min:1',
            'metodo_pago' => 'required',
            'monto_recibido' => 'required|numeric|min:'.$this->total_a_pagar,
        ];

        // LÓGICA LEGAL MÉDICA (Menores vs Adultos)
        if ($this->es_menor) {
            // El paciente es menor, el tutor es OBLIGATORIO
            $rules['responsable_nombre'] = 'required|string|max:255';
            $rules['responsable_celular'] = 'required|string|max:20';
            $rules['responsable_relacion'] = 'required|string|max:100';
            $rules['responsable_correo'] = 'required|email';
        } else {
            // El paciente es adulto, el tutor es OPCIONAL
            if (! empty(trim($this->responsable_nombre))) {
                $rules['responsable_correo'] = 'required|email';
            } else {
                $rules['paciente_email'] = 'required|email';
            }
        }

        $messages = [
            'paciente_id.required' => 'Debe buscar y seleccionar un paciente obligatoriamente.',
            'analisis_ids.required' => 'El carrito está vacío. Seleccione al menos un análisis.',
            'analisis_ids.min' => 'Debe seleccionar al menos un análisis del catálogo.',
            'monto_recibido.required' => 'Ingrese el monto que el paciente está entregando.',
            'monto_recibido.min' => 'El monto recibido debe ser de al menos Bs. '.number_format($this->total_a_pagar, 2).' para cubrir el costo.',
            // Mensajes para menores de edad
            'responsable_nombre.required' => 'Por ley, un menor de edad debe tener un Responsable registrado.',
            'responsable_celular.required' => 'El celular del responsable es obligatorio para menores de edad.',
            'responsable_relacion.required' => 'Debe indicar el parentesco del responsable con el menor.',
            // Mensajes de correo
            'responsable_correo.required' => 'El correo del Responsable/Tutor es OBLIGATORIO para enviar los resultados.',
            'responsable_correo.email' => 'El correo del tutor no tiene un formato válido.',
            'paciente_email.required' => 'Si el paciente no tiene tutor, su propio correo es OBLIGATORIO para enviarle los resultados.',
            'paciente_email.email' => 'El correo del paciente no tiene un formato válido.',
        ];

        $this->validate($rules, $messages);

        DB::beginTransaction();

        try {
            $paciente = Paciente::find($this->paciente_id);

            if (! empty(trim($this->paciente_email))) {
                $paciente->update(['email' => $this->paciente_email]);
            }

            if ($this->editar_responsable && ! empty(trim($this->responsable_nombre))) {
                if (! $paciente->responsable_id) {
                    $responsable = Responsable::create([
                        'nombre_completo' => $this->responsable_nombre,
                        'celular' => $this->responsable_celular ?? 'Sin registro',
                        'relacion' => $this->responsable_relacion ?? 'Familiar',
                        'correo' => $this->responsable_correo,
                    ]);
                    $paciente->update(['responsable_id' => $responsable->id]);
                } else {
                    $paciente->responsable->update([
                        'nombre_completo' => $this->responsable_nombre,
                        'celular' => $this->responsable_celular,
                        'relacion' => $this->responsable_relacion,
                        'correo' => $this->responsable_correo,
                    ]);
                }
            }

            $servicio = Servicio::create([
                'paciente_id' => $this->paciente_id,
                'medico_id' => empty($this->medico_id) ? null : $this->medico_id,
                'codigo_unico' => 'SGLC-'.date('Ymd').'-'.Str::random(4),
                'estado_pago' => 'pagado',
                'estado_muestra' => 'pendiente',
            ]);

            $servicio->tiposAnalisis()->attach($this->analisis_ids);

            Recibo::create([
                'servicio_id' => $servicio->id,
                'numero_correlativo' => 'REC-'.str_pad($servicio->id, 6, '0', STR_PAD_LEFT),
                'subtotal' => $this->total_a_pagar,
                'descuento' => 0,
                'total' => $this->total_a_pagar,
                'medio_pago' => $this->metodo_pago,
            ]);

            DB::commit();

            session()->flash('mensaje', 'Transacción completada con éxito. Imprimiendo el ticket...');
            $this->dispatch('abrir-ticket', url: route('laboratorio.ticket', $servicio->id));

            $this->reset(['paciente_id', 'paciente_nombre', 'paciente_email', 'paciente_edad', 'es_menor', 'busqueda_ci', 'paciente_error', 'responsable_nombre', 'responsable_celular', 'responsable_relacion', 'responsable_correo', 'tiene_responsable_previo', 'medico_id', 'analisis_ids', 'analisis_seleccionados', 'total_a_pagar', 'monto_recibido', 'cambio_o_saldo']);
            $this->editar_responsable = true;

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Ocurrió un error al procesar el cobro: '.$e->getMessage());
        }
    }

    public function render()
    {
        $categoriasConAnalisis = Categoria::with(['tiposAnalisis' => function ($query) {
            $query->where('estado', true);
        }])->has('tiposAnalisis')->get();

        return view('livewire.create-servicio', [
            'medicos' => MedicoSolicitante::all(),
            'categorias' => $categoriasConAnalisis,
        ]);
    }
}
