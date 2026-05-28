<?php

namespace App\Livewire\Pacientes;

use App\Models\Paciente;
use App\Models\Responsable;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class ListaPacientes extends Component
{
    use WithPagination;

    public string $search = '';

    // --- Formulario ("pestaña escodita") ---
    public bool $mostrarFormulario = false;
    public string $modo = 'crear'; // 'crear' | 'editar'
    public ?int $editando_id = null;

    // Paciente
    public string $ci = '';
    public string $nombre_completo = '';
    public string $fecha_nacimiento = '';
    public string $sexo = 'M'; // M/F
    public ?string $telefono = null;

    // Responsable (opcional)
    public bool $tiene_responsable = false;
    public string $responsable_nombre = '';
    public ?string $responsable_celular = null;
    public string $responsable_relacion = 'Familiar';

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
        $paciente = Paciente::with('responsable')->findOrFail($id);

        $this->modo = 'editar';
        $this->editando_id = $id;
        $this->mostrarFormulario = true;

        $this->ci = (string) $paciente->ci;
        $this->nombre_completo = (string) $paciente->nombre_completo;
        $this->fecha_nacimiento = $paciente->fecha_nacimiento ? $paciente->fecha_nacimiento->format('Y-m-d') : '';
        $this->sexo = (string) $paciente->sexo;
        $this->telefono = $paciente->telefono;

        $this->tiene_responsable = (bool) $paciente->responsable;
        $this->responsable_nombre = $paciente->responsable?->nombre_completo ?? '';
        $this->responsable_celular = $paciente->responsable?->celular ?? null;
        $this->responsable_relacion = $paciente->responsable?->relacion ?? 'Familiar';
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
        $this->ci = '';
        $this->nombre_completo = '';
        $this->fecha_nacimiento = '';
        $this->sexo = 'M';
        $this->telefono = null;

        $this->tiene_responsable = false;
        $this->responsable_nombre = '';
        $this->responsable_celular = null;
        $this->responsable_relacion = 'Familiar';
    }

    protected function rules(): array
    {
        $sexoRule = 'required|in:M,F';

        if ($this->modo === 'editar') {
            return [
                'ci' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('pacientes', 'ci')->ignore($this->editando_id),
                ],
                'nombre_completo' => 'required|string|max:255',
                'fecha_nacimiento' => 'required|date',
                'sexo' => $sexoRule,
                'telefono' => 'nullable|string|max:30',
                'tiene_responsable' => 'boolean',
                'responsable_nombre' => 'required_if:tiene_responsable,true|string|max:255',
                'responsable_celular' => 'nullable|string|max:30',
                'responsable_relacion' => 'required_if:tiene_responsable,true|string|max:100',
            ];
        }

        return [
            'ci' => 'required|string|max:20|unique:pacientes,ci',
            'nombre_completo' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'sexo' => $sexoRule,
            'telefono' => 'nullable|string|max:30',
            'tiene_responsable' => 'boolean',
            'responsable_nombre' => 'required_if:tiene_responsable,true|string|max:255',
            'responsable_celular' => 'nullable|string|max:30',
            'responsable_relacion' => 'required_if:tiene_responsable,true|string|max:100',
        ];
    }

    public function guardar(): void
    {
        $validated = $this->validate();

        $responsableId = null;

        if (!empty($validated['tiene_responsable']) && trim($validated['responsable_nombre'] ?? '') !== '') {
            if ($this->modo === 'editar') {
                $paciente = Paciente::with('responsable')->findOrFail($this->editando_id);

                // Si el paciente NO tenía responsable, no podemos usar el nombre actual como llave.
                $where = $paciente->responsable ? ['id' => $paciente->responsable->id] : [];

                if (!empty($where)) {
                    $paciente->responsable()->updateOrCreate(
                        $where,
                        [
                            'nombre_completo' => $validated['responsable_nombre'],
                            'celular' => $validated['responsable_celular'] ?? 'Sin registro',
                            'relacion' => $validated['responsable_relacion'] ?? 'Familiar',
                        ]
                    );
                } else {
                    $nuevoResponsable = Responsable::create([
                        'nombre_completo' => $validated['responsable_nombre'],
                        'celular' => $validated['responsable_celular'] ?? 'Sin registro',
                        'relacion' => $validated['responsable_relacion'] ?? 'Familiar',
                    ]);
                    $responsableId = $nuevoResponsable->id;
                }

                $responsableId = $responsableId ?? $paciente->responsable()->first()?->id;
            } else {
                $responsable = Responsable::create([
                    'nombre_completo' => $validated['responsable_nombre'],
                    'celular' => $validated['responsable_celular'] ?? 'Sin registro',
                    'relacion' => $validated['responsable_relacion'] ?? 'Familiar',
                ]);

                $responsableId = $responsable->id;
            }
        }

        if ($this->modo === 'editar') {
            $paciente = Paciente::findOrFail($this->editando_id);

            $paciente->update([
                'responsable_id' => $paciente->responsable?->id ?? $responsableId,
                'ci' => $validated['ci'],
                'nombre_completo' => $validated['nombre_completo'],
                'fecha_nacimiento' => $validated['fecha_nacimiento'],
                'sexo' => $validated['sexo'],
                'telefono' => $validated['telefono'],
            ]);

            session()->flash('message', "Paciente {$paciente->nombre_completo} actualizado correctamente.");
            $this->cancelarFormulario();
            return;
        }

        $paciente = Paciente::create([
            'responsable_id' => $responsableId,
            'ci' => $validated['ci'],
            'nombre_completo' => $validated['nombre_completo'],
            'fecha_nacimiento' => $validated['fecha_nacimiento'],
            'sexo' => $validated['sexo'],
            'telefono' => $validated['telefono'],
        ]);

        session()->flash('message', 'Paciente registrado correctamente.');
        $this->cancelarFormulario();
    }

    public function confirmarBorrar(int $id): void
    {
        $this->confirmando_borrar_id = $this->confirmando_borrar_id === $id ? null : $id;
    }

    public function borrar(int $id): void
    {
        $paciente = Paciente::with('responsable')->findOrFail($id);

        if ($paciente->responsable) {
            $paciente->responsable->delete();
        }

        $nombre = $paciente->nombre_completo;
        $paciente->delete();

        $this->confirmando_borrar_id = null;
        session()->flash('message', "Paciente {$nombre} eliminado correctamente.");

        if ($this->editando_id === $id) {
            $this->cancelarFormulario();
        }
    }

    public function render()
    {
        $pacientes = Paciente::query()
            ->with('responsable')
            ->when(trim($this->search) !== '', function ($q) {
                $term = trim($this->search);
                $q->where('ci', 'like', "%{$term}%")
                    ->orWhere('nombre_completo', 'like', "%{$term}%");
            })
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.pacientes.paciente', [
            'pacientes' => $pacientes,
        ]);
    }
}

