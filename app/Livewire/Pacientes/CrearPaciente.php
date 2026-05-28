<?php

namespace App\Livewire\Pacientes;

use App\Models\Paciente;
use App\Models\Responsable;
use Livewire\Component;

class CrearPaciente extends Component
{
    // Paciente
    public string $ci = '';
    public string $nombre_completo = '';
    public string $fecha_nacimiento = '';
    public string $sexo = 'M';
    public ?string $telefono = null;

    // Responsable (opcional)
    public bool $tiene_responsable = false;
    public string $responsable_nombre = '';
    public ?string $responsable_celular = null;
    public string $responsable_relacion = 'Familiar';

    protected function rules(): array
    {
        return [
            'ci' => 'required|string|max:20|unique:pacientes,ci',
            'nombre_completo' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|string|in:M,F',
            'telefono' => 'nullable|string|max:30',
            'tiene_responsable' => 'boolean',
            'responsable_nombre' => 'required_if:tiene_responsable,true|string|max:255',
            'responsable_celular' => 'nullable|string|max:30',
            'responsable_relacion' => 'required_if:tiene_responsable,true|string|max:100',
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        $responsableId = null;
        if (!empty($validated['tiene_responsable']) && trim($validated['responsable_nombre']) !== '') {
            $responsable = Responsable::create([
                'nombre_completo' => $validated['responsable_nombre'],
                'celular' => $validated['responsable_celular'] ?? 'Sin registro',
                'relacion' => $validated['responsable_relacion'] ?? 'Familiar',
            ]);
            $responsableId = $responsable->id;
        }

        Paciente::create([
            'responsable_id' => $responsableId,
            'ci' => $validated['ci'],
            'nombre_completo' => $validated['nombre_completo'],
            'fecha_nacimiento' => $validated['fecha_nacimiento'],
            'sexo' => $validated['sexo'],
            'telefono' => $validated['telefono'],
        ]);

        session()->flash('message', 'Paciente registrado correctamente.');
        $this->reset([
            'ci',
            'nombre_completo',
            'fecha_nacimiento',
            'sexo',
            'telefono',
            'tiene_responsable',
            'responsable_nombre',
            'responsable_celular',
            'responsable_relacion',
        ]);
    }

    public function render()
    {
        return view('livewire.pacientes.crear.paciente');
    }
}


