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

    public bool $mostrarFormulario = false;
    public string $modo = 'crear';
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
        $baseRules = [
            'nombre_completo'       => ['required', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
            'especialidad'          => ['nullable', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
            'matricula_profesional' => ['required', 'string', 'max:14'],
            'correo'                => ['nullable', 'string', 'email', 'max:255'],
        ];

        if ($this->modo === 'editar') {
            $baseRules['matricula_profesional'][] = Rule::unique('medicos_solicitantes', 'matricula_profesional')->ignore($this->editando_id);
        } else {
            $baseRules['matricula_profesional'][] = 'unique:medicos_solicitantes,matricula_profesional';
        }

        return $baseRules;
    }

    protected function messages(): array
    {
        return [
            'nombre_completo.regex'          => 'El nombre solo puede contener letras y espacios.',
            'especialidad.regex'             => 'La especialidad solo puede contener letras y espacios.',
            'matricula_profesional.max'      => 'La matrícula no puede tener más de 14 caracteres.',
            'matricula_profesional.unique'   => 'Esta matrícula ya está registrada.',
        ];
    }

    public function guardar(): void
    {
        $validated = $this->validate();

        if ($this->modo === 'editar') {
            $medico = MedicoSolicitante::query()->findOrFail($this->editando_id);
            $medico->update([
                'nombre_completo'       => $validated['nombre_completo'],
                'especialidad'          => $validated['especialidad'] ?? null,
                'matricula_profesional' => $validated['matricula_profesional'],
                'correo'                => $validated['correo'] ?? null,
            ]);

            session()->flash('message', "Médico {$medico->nombre_completo} actualizado correctamente.");
            $this->cancelarFormulario();
            return;
        }

        $medico = MedicoSolicitante::query()->create([
            'nombre_completo'       => $validated['nombre_completo'],
            'especialidad'          => $validated['especialidad'] ?? null,
            'matricula_profesional' => $validated['matricula_profesional'],
            'correo'                => $validated['correo'] ?? null,
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