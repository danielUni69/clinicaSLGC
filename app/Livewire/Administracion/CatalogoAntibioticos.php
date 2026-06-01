<?php

namespace App\Livewire\Administracion;

use App\Models\Antibiotico;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CatalogoAntibioticos extends Component
{
    public $mostrarModal = false;

    public $anti_id = null;

    public $nombre_antibiotico = '';

    public function render()
    {
        $antibioticos = Antibiotico::orderBy('nombre_antibiotico', 'asc')->get();

        return view('livewire.administracion.catalogo-antibioticos', compact('antibioticos'));
    }

    public function abrirModal($id = null)
    {
        $this->resetErrorBag();

        if ($id) {
            $anti = Antibiotico::find($id);
            $this->anti_id = $anti->id;
            $this->nombre_antibiotico = $anti->nombre_antibiotico;
        } else {
            $this->anti_id = null;
            $this->nombre_antibiotico = '';
        }

        $this->mostrarModal = true;
    }

    public function guardar()
    {
        $this->validate([
            'nombre_antibiotico' => 'required|string|max:255|unique:antibioticos,nombre_antibiotico,'.$this->anti_id,
        ], [
            'nombre_antibiotico.required' => 'El nombre del medicamento es obligatorio.',
            'nombre_antibiotico.unique' => 'Este antibiótico ya está registrado en el sistema.',
        ]);

        Antibiotico::updateOrCreate(
            ['id' => $this->anti_id],
            ['nombre_antibiotico' => trim($this->nombre_antibiotico)]
        );

        $this->mostrarModal = false;
        session()->flash('mensaje', 'Antibiótico guardado con éxito.');
    }

    public function toggleEstado($id)
    {
        $anti = Antibiotico::find($id);
        $anti->estado = ! $anti->estado;
        $anti->save();

        session()->flash('mensaje', 'Estado del antibiótico actualizado.');
    }

    public function eliminar($id)
    {
        try {
            // Buscamos si existe en la tabla pivote de antibiogramas (usando DB query directo para mayor rapidez)
            $enUso = DB::table('antibiogramas')->where('antibiotico_id', $id)->exists();

            if ($enUso) {
                session()->flash('error', 'No se puede eliminar. Este antibiótico ya se usó en el historial médico de un paciente. Si ya no lo tiene en el laboratorio, por favor póngalo como INACTIVO.');

                return;
            }

            Antibiotico::find($id)->delete();
            session()->flash('mensaje', 'Antibiótico eliminado del sistema.');

        } catch (\Exception $e) {
            session()->flash('error', 'Error en la base de datos al intentar eliminar.');
        }
    }
}
