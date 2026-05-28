<?php

namespace App\Livewire\Pacientes;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Paciente;

class ListaPacientes extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $editando_id = null;
    public string $edit_nombre = '';
    public string $edit_ci = '';
    public string $edit_telefono = '';
    public string $edit_sexo = '';
    public string $edit_responsable_nombre = '';
    public string $edit_responsable_relacion = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function toggleEditar(int $id): void
    {
        if ($this->editando_id === $id) {
            $this->editando_id = null;
            return;
        }

        $paciente = Paciente::with('responsable')->findOrFail($id);
        $this->editando_id               = $id;
        $this->edit_nombre               = $paciente->nombre_completo;
        $this->edit_ci                   = $paciente->ci;
        $this->edit_telefono             = $paciente->telefono ?? '';
        $this->edit_sexo                 = $paciente->sexo;
        $this->edit_responsable_nombre   = $paciente->responsable?->nombre_completo ?? '';
        $this->edit_responsable_relacion = $paciente->responsable?->relacion ?? '';
    }

    public function guardarEdicion(): void
    {
        $this->validate([
            'edit_nombre' => 'required|string|max:255',
            'edit_ci'     => 'required|string|max:20',
            'edit_sexo'   => 'required|in:Masculino,Femenino,Otro',
        ]);

        $paciente = Paciente::findOrFail($this->editando_id);

        $paciente->update([
            'nombre_completo' => $this->edit_nombre,
            'ci'              => $this->edit_ci,
            'telefono'        => $this->edit_telefono ?: null,
            'sexo'            => $this->edit_sexo,
        ]);

        if ($this->edit_responsable_nombre) {
            $paciente->responsable()->updateOrCreate([], [
                'nombre_completo' => $this->edit_responsable_nombre,
                'relacion'        => $this->edit_responsable_relacion,
            ]);
        }

        $this->editando_id = null;
        session()->flash('message', "Paciente {$paciente->nombre_completo} actualizado correctamente.");
    }

    public function render()
    {
        return view('livewire.pacientes.paciente', [
            'pacientes' => Paciente::with('responsable')
                ->where(function ($query) {
                    $query->where('nombre_completo', 'like', "%{$this->search}%")
                          ->orWhere('ci', 'like', "%{$this->search}%");
                })
                ->orderBy('nombre_completo')
                ->paginate(10),
        ]);
    }
}