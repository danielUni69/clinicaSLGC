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

    public $busqueda_ci = '';

    public $paciente_error = '';

    public $responsable_nombre = '';

    public $responsable_celular = '';

    public $responsable_relacion = '';

    public $medico_id = null;

    public $analisis_ids = [];

    public $analisis_seleccionados = [];

    // --- VARIABLES DE CAJA SIMPLIFICADAS ---
    public $total_a_pagar = 0;

    public $metodo_pago = 'Efectivo';

    public $monto_recibido = 0;

    public $cambio_o_saldo = 0; // Si es negativo, es "faltante"

    public function buscarPaciente()
    {
        $this->paciente_error = '';
        $paciente = Paciente::with('responsable')->where('ci', $this->busqueda_ci)->first();

        if ($paciente) {
            $this->paciente_id = $paciente->id;
            $this->paciente_nombre = $paciente->nombre_completo;

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
            $this->paciente_error = 'Paciente no encontrado. Por favor, regístrelo primero.';
        }
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
        // VALIDACIÓN ESTRICTA: El monto recibido debe ser igual o mayor al total a pagar
        $this->validate([
            'paciente_id' => 'required',
            'medico_id' => 'required',
            'analisis_ids' => 'required|array|min:1',
            'metodo_pago' => 'required',
            'monto_recibido' => 'required|numeric|min:'.$this->total_a_pagar,
        ], [
            'monto_recibido.min' => 'El monto recibido debe ser al menos Bs. '.number_format($this->total_a_pagar, 2).' para cubrir el total de la orden.',
        ]);

        DB::beginTransaction();

        try {
            $paciente = Paciente::find($this->paciente_id);

            if (! empty(trim($this->responsable_nombre))) {
                if (! $paciente->responsable_id) {
                    $responsable = Responsable::create([
                        'nombre_completo' => $this->responsable_nombre,
                        'celular' => $this->responsable_celular ?? 'Sin registro',
                        'relacion' => $this->responsable_relacion ?? 'Familiar',
                    ]);
                    $paciente->update(['responsable_id' => $responsable->id]);
                } else {
                    $paciente->responsable->update([
                        'nombre_completo' => $this->responsable_nombre,
                        'celular' => $this->responsable_celular,
                        'relacion' => $this->responsable_relacion,
                    ]);
                }
            }

            // Como se paga el 100%, el estado siempre es 'pagado'
            $servicio = Servicio::create([
                'paciente_id' => $this->paciente_id,
                'medico_id' => $this->medico_id,
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

            session()->flash('mensaje', 'Transacción exitosa. Imprimiendo Ticket...');
            $this->dispatch('abrir-ticket', url: route('laboratorio.ticket', $servicio->id));

            $this->reset(['paciente_id', 'paciente_nombre', 'busqueda_ci', 'responsable_nombre', 'responsable_celular', 'responsable_relacion', 'medico_id', 'analisis_ids', 'analisis_seleccionados', 'total_a_pagar', 'monto_recibido', 'cambio_o_saldo']);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error crítico al guardar: '.$e->getMessage());
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
