<?php

namespace App\Livewire;

use App\Models\ResultadoAnalisis;
use App\Models\Servicio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ProcesarResultados extends Component
{
    public Servicio $servicio;

    public $paciente_nombre = '';

    public $paciente_ci = '';

    public $fecha_servicio = '';

    public $paciente_sexo = 'M';

    public $valores = [];

    public function mount($id)
    {
        $modeloServicio = Servicio::with(['paciente', 'tiposAnalisis'])->findOrFail($id);
        $this->servicio = $modeloServicio;

        $this->paciente_nombre = $modeloServicio->paciente->nombre_completo ?? 'Desconocido';
        $this->paciente_ci = $modeloServicio->paciente->ci ?? 'Sin registro';
        $this->fecha_servicio = $modeloServicio->created_at ? $modeloServicio->created_at->format('d/m/Y H:i') : 'Sin fecha';

        // Obtenemos el sexo (si no existe la columna, asumimos Masculino por defecto)
        $this->paciente_sexo = $modeloServicio->paciente->sexo ?? 'M';

        foreach ($modeloServicio->tiposAnalisis as $analisis) {
            $resultadoPrevio = ResultadoAnalisis::where('servicio_id', $modeloServicio->id)
                ->where('tipo_analisis_id', $analisis->id)
                ->first();

            // Extraemos los rangos dinámicamente usando el operador lógico correcto (||)
            $esFemenino = ($this->paciente_sexo === 'F' || $this->paciente_sexo === 'Femenino' || $this->paciente_sexo === 'Mujer');
            $minimo = $esFemenino ? $analisis->rango_min_femenino : $analisis->rango_min_masculino;
            $maximo = $esFemenino ? $analisis->rango_max_femenino : $analisis->rango_max_masculino;

            $this->valores[$analisis->id] = [
                'valor' => $resultadoPrevio ? $resultadoPrevio->valor_registrado : '',
                'alerta' => $resultadoPrevio ? $resultadoPrevio->alerta_rango : 'normal',
                'nombre' => $analisis->nombre,
                'unidad' => $analisis->unidad_medida,
                'tipo' => $analisis->tipo_parámetro,
                'min' => $minimo,
                'max' => $maximo,
            ];
        }
    }

    // =========================================================================
    // MÉTODO GLOBAL DE LIVEWIRE: Atrapa cualquier tecla presionada en la vista
    // =========================================================================
    public function updated($property, $value)
    {
        // Detectamos si lo que se escribió fue en un campo de la tabla (ej. "valores.1.valor")
        if (str_starts_with($property, 'valores.') && str_ends_with($property, '.valor')) {
            $partes = explode('.', $property); // Divide en: [0 => 'valores', 1 => 'ID', 2 => 'valor']
            $analisis_id = $partes[1];

            $this->calcularAlertaAutomatica($analisis_id, $value);
        }
    }

    private function calcularAlertaAutomatica($id, $valor)
    {
        $data = $this->valores[$id];

        // Solo calculamos si el examen es numérico y tiene límites en la BD
        if ($data['tipo'] === 'numerico' && $data['min'] !== null && $data['max'] !== null) {

            // Si el campo está vacío, lo dejamos en normal
            if (trim($valor) === '' || ! is_numeric($valor)) {
                $this->valores[$id]['alerta'] = 'normal';

                return;
            }

            // Convertimos a decimales para evitar fallos matemáticos
            $num = (float) $valor;
            $min = (float) $data['min'];
            $max = (float) $data['max'];

            if ($num < $min) {
                $this->valores[$id]['alerta'] = 'bajo';
            } elseif ($num > $max) {
                $this->valores[$id]['alerta'] = 'alto';
            } else {
                $this->valores[$id]['alerta'] = 'normal';
            }
        }
    }

    public function guardarResultados()
    {
        $this->validate([
            'valores.*.valor' => 'required|string|max:255',
        ], [
            'valores.*.valor.required' => 'No puede dejar resultados en blanco.',
        ]);

        DB::beginTransaction();
        try {
            foreach ($this->valores as $analisis_id => $data) {
                ResultadoAnalisis::updateOrCreate(
                    [
                        'servicio_id' => $this->servicio->id,
                        'tipo_analisis_id' => $analisis_id,
                    ],
                    [
                        'valor_registrado' => $data['valor'],
                        'alerta_rango' => $data['alerta'],
                        'bioquimico_id' => Auth::id(),
                    ]
                );
            }
            DB::commit();
            session()->flash('mensaje', 'Resultados guardados y validados correctamente.');

            return redirect()->route('laboratorio.panel');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error crítico al guardar resultados: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.procesar-resultados');
    }
}
