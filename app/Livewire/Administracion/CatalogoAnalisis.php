<?php

namespace App\Livewire\Administracion;

use App\Models\Categoria;
use App\Models\TipoAnalisis;
use Livewire\Component;

class CatalogoAnalisis extends Component
{
    // --- VARIABLES DE INTERFAZ ---
    public $categoria_seleccionada_id = null;

    public $mostrarModalCategoria = false;

    public $mostrarModalAnalisis = false;

    // --- FORMULARIO CATEGORÍA ---
    public $cat_id = null;

    public $cat_nombre = '';

    // --- FORMULARIO ANÁLISIS ---
    public $ana_id = null;

    public $ana_categoria_id = '';

    public $ana_nombre = '';

    public $ana_costo = '';

    public $ana_unidad_medida = '';

    public $ana_tipo_parametro = 'numerico';

    public $ana_rango_min_m = '';

    public $ana_rango_max_m = '';

    public $ana_rango_min_f = '';

    public $ana_rango_max_f = '';

    public $ana_ref_cualitativa = '';

    public function render()
    {
        $categorias = Categoria::withCount('tiposAnalisis')->orderBy('nombre')->get();

        $analisis = [];
        if ($this->categoria_seleccionada_id) {
            $analisis = TipoAnalisis::where('categoria_id', $this->categoria_seleccionada_id)
                ->orderBy('nombre')
                ->get();
        } else {
            $analisis = TipoAnalisis::with('categoria')->orderBy('nombre')->get();
        }

        return view('livewire.administracion.catalogo-analisis', compact('categorias', 'analisis'));
    }

    public function seleccionarCategoria($id = null)
    {
        $this->categoria_seleccionada_id = $id;
    }

    // ==========================================
    // CRUD CATEGORÍAS
    // ==========================================
    public function abrirModalCategoria($id = null)
    {
        $this->resetErrorBag();
        if ($id) {
            $cat = Categoria::find($id);
            $this->cat_id = $cat->id;
            $this->cat_nombre = $cat->nombre;
        } else {
            $this->cat_id = null;
            $this->cat_nombre = '';
        }
        $this->mostrarModalCategoria = true;
    }

    public function guardarCategoria()
    {
        $this->validate([
            'cat_nombre' => 'required|string|max:255|unique:categorias,nombre,'.$this->cat_id,
        ], [
            'cat_nombre.required' => 'El nombre de la categoría es obligatorio.',
            'cat_nombre.unique' => 'Ya existe una categoría con ese nombre.',
        ]);

        Categoria::updateOrCreate(
            ['id' => $this->cat_id],
            ['nombre' => $this->cat_nombre]
        );

        $this->mostrarModalCategoria = false;
        session()->flash('mensaje', 'Categoría guardada con éxito.');
    }

    public function eliminarCategoria($id)
    {
        $cat = Categoria::withCount('tiposAnalisis')->find($id);
        if ($cat->tipos_analisis_count > 0) {
            session()->flash('error', 'No se puede eliminar la categoría porque tiene exámenes asociados.');

            return;
        }
        $cat->delete();
        $this->categoria_seleccionada_id = null;
        session()->flash('mensaje', 'Categoría eliminada.');
    }

    // ==========================================
    // CRUD TIPOS DE ANÁLISIS
    // ==========================================
    public function abrirModalAnalisis($id = null)
    {
        $this->resetErrorBag();
        if ($id) {
            $ana = TipoAnalisis::find($id);
            $this->ana_id = $ana->id;
            $this->ana_categoria_id = $ana->categoria_id;
            $this->ana_nombre = $ana->nombre;
            $this->ana_costo = $ana->costo;
            $this->ana_unidad_medida = $ana->unidad_medida;
            $this->ana_tipo_parametro = $ana->tipo_parámetro;
            $this->ana_rango_min_m = $ana->rango_min_masculino;
            $this->ana_rango_max_m = $ana->rango_max_masculino;
            $this->ana_rango_min_f = $ana->rango_min_femenino;
            $this->ana_rango_max_f = $ana->rango_max_femenino;
            $this->ana_ref_cualitativa = $ana->valor_referencia_cualitativo;
        } else {
            $this->ana_id = null;
            $this->ana_categoria_id = $this->categoria_seleccionada_id ?? '';
            $this->ana_nombre = '';
            $this->ana_costo = '';
            $this->ana_unidad_medida = '';
            $this->ana_tipo_parametro = 'numerico';
            $this->ana_rango_min_m = '';
            $this->ana_rango_max_m = '';
            $this->ana_rango_min_f = '';
            $this->ana_rango_max_f = '';
            $this->ana_ref_cualitativa = '';
        }
        $this->mostrarModalAnalisis = true;
    }

    public function guardarAnalisis()
    {
        // Reglas dinámicas: Si es numérico exige rangos, si es cualitativo exige referencia
        $rules = [
            'ana_categoria_id' => 'required',
            'ana_nombre' => 'required|string|max:255',
            'ana_costo' => 'required|numeric|min:0',
            'ana_tipo_parametro' => 'required|in:numerico,cualitativo',
        ];

        if ($this->ana_tipo_parametro === 'numerico') {
            $rules['ana_unidad_medida'] = 'required|string|max:50';
            $rules['ana_rango_min_m'] = 'nullable|numeric';
            $rules['ana_rango_max_m'] = 'nullable|numeric';
            $rules['ana_rango_min_f'] = 'nullable|numeric';
            $rules['ana_rango_max_f'] = 'nullable|numeric';
        } else {
            $rules['ana_ref_cualitativa'] = 'required|string|max:255';
        }

        $this->validate($rules, [
            'ana_categoria_id.required' => 'Debe seleccionar una categoría.',
            'ana_nombre.required' => 'El nombre del examen es obligatorio.',
            'ana_costo.required' => 'El costo es obligatorio.',
            'ana_unidad_medida.required' => 'La unidad de medida es obligatoria para exámenes numéricos.',
            'ana_ref_cualitativa.required' => 'Debe definir el valor esperado (Ej: Negativo, No Reactivo, N/A).',
        ]);

        TipoAnalisis::updateOrCreate(
            ['id' => $this->ana_id],
            [
                'categoria_id' => $this->ana_categoria_id,
                'nombre' => $this->ana_nombre,
                'costo' => $this->ana_costo,
                'unidad_medida' => $this->ana_tipo_parametro === 'numerico' ? $this->ana_unidad_medida : null,
                'tipo_parámetro' => $this->ana_tipo_parametro,
                'rango_min_masculino' => $this->ana_rango_min_m !== '' ? $this->ana_rango_min_m : null,
                'rango_max_masculino' => $this->ana_rango_max_m !== '' ? $this->ana_rango_max_m : null,
                'rango_min_femenino' => $this->ana_rango_min_f !== '' ? $this->ana_rango_min_f : null,
                'rango_max_femenino' => $this->ana_rango_max_f !== '' ? $this->ana_rango_max_f : null,
                'valor_referencia_cualitativo' => $this->ana_tipo_parametro === 'cualitativo' ? $this->ana_ref_cualitativa : null,
            ]
        );

        $this->mostrarModalAnalisis = false;
        session()->flash('mensaje', 'Examen guardado correctamente.');
    }

    public function toggleEstadoAnalisis($id)
    {
        $ana = TipoAnalisis::find($id);
        $ana->estado = ! $ana->estado;
        $ana->save();
        session()->flash('mensaje', 'Estado del examen actualizado.');
    }
}
