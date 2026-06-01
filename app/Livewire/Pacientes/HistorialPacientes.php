<?php

namespace App\Livewire\Pacientes;

use App\Models\Paciente;
use Livewire\Component;
use Livewire\WithPagination;

class HistorialPacientes extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $pacienteSeleccionadoId = null;


    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $pacientes = Paciente::query()
            ->when(trim($this->search) !== '', function ($q) {
                $term = trim($this->search);
                $q->where('ci', 'like', "%{$term}%")
                    ->orWhere('nombre_completo', 'like', "%{$term}%");
            })
            ->with('responsable')
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.pacientes.historial-pacientes', [
            'pacientes' => $pacientes,
        ]);
    }
}

