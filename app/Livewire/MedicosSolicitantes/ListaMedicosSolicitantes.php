<?php

namespace App\Livewire\MedicosSolicitantes;

use App\Models\MedicoSolicitante;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class ListaMedicosSolicitantes extends Component
{
    use WithPagination;

    public string $search = '';

    // --- Formulario ("pestaña escodita") ---
    public bool $mostrarFormulario = false;
    public string $modo = 'crear'; // 'crear' | 'editar'
    public ?int $editando_id = null;

    // Medico
    public string $nombre_completo = '';
    public ?string $especialidad = null;
    public string $matricula_profesional = '';
    public ?string $correo = null;

    // Borrado
    public ?int $confirmando_borrar_id = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function abrirCrear(): void
    {
        $this->resetFormulario();
        $this->modo = 'crear';
        $this->editando_id = null;
        $this->mostrarFormulario = true;
    }

    public function abrirEditar(int $id): void
    {
        $medico = MedicoSolicitante::query()->findOrFail($id);

        $this->modo = 'editar';
        $this->editando_id = $id;
        $this->mostrarFormulario = true;

        $this->nombre_completo = (string) $medico->nombre_completo;
        $this->especialidad = $medico->especialidad;
        $this->matricula_profesional = (string) $medico->matricula_profesional;
        $this->correo = $medico->correo;
    }

    public function cancelarFormulario(): void
    {
        $this->mostrarFormulario = false;
        $this->confirmando_borrar_id = null;
        $this->editando_id = null;
        $this->resetFormulario();
    }

    private function resetFormulario(): void
    {
        $this->nombre_completo = '';
        $this->especialidad = null;
        $this->matricula_profesional = '';
        $this->correo = null;
    }

    protected function rules(): array
    {
        if ($this->modo === 'editar') {
            return [
                'nombre_completo' => 'required|string|max:255',
                'especialidad' => 'nullable|string|max:255',
                'matricula_profesional' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('medicos_solicitantes', 'matricula_profesional')->ignore($this->editando_id),
                ],
                'correo' => 'nullable|string|email|max:255',
            ];
        }

        return [
            'nombre_completo' => 'required|string|max:255',
            'especialidad' => 'nullable|string|max:255',
            'matricula_profesional' => 'required|string|max:255|unique:medicos_solicitantes,matricula_profesional',
            'correo' => 'nullable|string|email|max:255',
        ];
    }

    public function guardar(): void
    {
        $validated = $this->validate();

        if ($this->modo === 'editar') {
            $medico = MedicoSolicitante::query()->findOrFail($this->editando_id);
            $medico->update([
                'nombre_completo' => $validated['nombre_completo'],
                'especialidad' => $validated['especialidad'] ?? null,
                'matricula_profesional' => $validated['matricula_profesional'],
                'correo' => $validated['correo'] ?? null,
            ]);

            session()->flash('message', "Médico {$medico->nombre_completo} actualizado correctamente.");
            $this->cancelarFormulario();
            return;
        }

        $medico = MedicoSolicitante::query()->create([
            'nombre_completo' => $validated['nombre_completo'],
            'especialidad' => $validated['especialidad'] ?? null,
            'matricula_profesional' => $validated['matricula_profesional'],
            'correo' => $validated['correo'] ?? null,
        ]);

        session()->flash('message', 'Médico registrado correctamente.');
        $this->cancelarFormulario();
    }

    public function confirmarBorrar(int $id): void
    {
        $this->confirmando_borrar_id = $this->confirmando_borrar_id === $id ? null : $id;
    }

    public function borrar(int $id): void
    {
        $medico = MedicoSolicitante::query()->findOrFail($id);
        $nombre = $medico->nombre_completo;

        // Si en tu BD existen servicios asociados, esto podría fallar por FK.
        // Lo dejamos como está para que se vea el error si aplica.
        $medico->delete();

        $this->confirmando_borrar_id = null;
        session()->flash('message', "Médico {$nombre} eliminado correctamente.");

        if ($this->editando_id === $id) {
            $this->cancelarFormulario();
        }
    }

    public function render()
    {
        $medicos = MedicoSolicitante::query()
            ->when(trim($this->search) !== '', function ($q) {
                $term = trim($this->search);
                $q->where('nombre_completo', 'like', "%{$term}%")
                    ->orWhere('especialidad', 'like', "%{$term}%")
                    ->orWhere('matricula_profesional', 'like', "%{$term}%")
                    ->orWhere('correo', 'like', "%{$term}%");
            })
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.medicos-solicitantes.medico', [
            'medicos' => $medicos,
        ]);
    }
}

