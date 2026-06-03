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

    // ─── Bloqueo en tiempo real ───────────────────────────────────────────────

    /**
     * Nombre: solo letras/espacios Unicode, mínimo 3, máximo 30.
     */
    public function updatingNombreCompleto(string $value): void
    {
        $limpio = preg_replace('/[^\pL\s]/u', '', $value);
        $this->nombre_completo = mb_substr($limpio, 0, 30);
    }

    /**
     * Especialidad: solo letras/espacios Unicode, máximo 60.
     */
    public function updatingEspecialidad(?string $value): void
    {
        if ($value !== null) {
            $limpio = preg_replace('/[^\pL\s]/u', '', $value);
            $this->especialidad = mb_substr($limpio, 0, 60);
        }
    }

    /**
     * Matrícula: solo dígitos, letras y guión, máximo 14.
     */
    public function updatingMatriculaProfesional(string $value): void
    {
        $limpio = preg_replace('/[^0-9A-Za-z\-]/u', '', $value);
        $this->matricula_profesional = strtoupper(substr($limpio, 0, 14));
    }

    /**
     * Correo: solo caracteres válidos para email.
     * - Bloquea símbolos inválidos (#, $, !, %, etc.)
     * - Solo un @ permitido
     * - Extensión del dominio máximo 20 caracteres (bloquea .bosssssss...)
     * - Máximo 255 caracteres en total
     */
    public function updatingCorreo(?string $value): void
    {
        if ($value === null) {
            return;
        }

        // 1. Solo caracteres válidos en un email
        $limpio = preg_replace('/[^a-zA-Z0-9@._\-+]/', '', $value);

        // 2. Máximo un solo @
        $partes = explode('@', $limpio, 3);
        if (count($partes) > 2) {
            $limpio = $partes[0] . '@' . implode('', array_slice($partes, 1));
        }

        // 3. Si ya hay dominio, validar que la extensión no sea absurda (máx 20 chars)
        if (str_contains($limpio, '@')) {
            [, $dominio] = explode('@', $limpio, 2);

            if ($dominio !== '') {
                $partesDominio = explode('.', $dominio);
                $extension = end($partesDominio);

                // Si la extensión supera 20 caracteres, bloqueamos la escritura
                if (strlen($extension) > 20) {
                    return;
                }
            }
        }

        // 4. Límite global de longitud
        $this->correo = substr($limpio, 0, 255);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // ─── Formulario ───────────────────────────────────────────────────────────

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

        $this->nombre_completo       = (string) $medico->nombre_completo;
        $this->especialidad          = $medico->especialidad;
        $this->matricula_profesional = (string) $medico->matricula_profesional;
        $this->correo                = $medico->correo;
    }

    public function cancelarFormulario(): void
    {
        $this->mostrarFormulario     = false;
        $this->confirmando_borrar_id = null;
        $this->editando_id           = null;
        $this->resetFormulario();
    }

    private function resetFormulario(): void
    {
        $this->nombre_completo       = '';
        $this->especialidad          = null;
        $this->matricula_profesional = '';
        $this->correo                = null;
    }

    // ─── Validación ───────────────────────────────────────────────────────────

    protected function rules(): array
    {
        $baseRules = [
            // Nombre: solo letras, mínimo 3, máximo 30
            'nombre_completo' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^[\pL\s]+$/u',
            ],

            // Especialidad: solo letras, opcional, mínimo 3, máximo 60
            'especialidad' => [
                'nullable',
                'string',
                'min:3',
                'max:60',
                'regex:/^[\pL\s]+$/u',
            ],

            // Matrícula: alfanumérica con guión, máximo 14
            'matricula_profesional' => [
                'required',
                'string',
                'max:14',
            ],

            // Correo: email válido con formato RFC, extensión de dominio máx 20 chars
            'correo' => [
                'nullable',
                'string',
                'max:255',
                'email:rfc',
                // Extensión de dominio entre 2 y 20 caracteres (bloquea .bosssssss...)
                'regex:/^[^@]+@[^@]+\.[a-zA-Z]{2,20}$/',
            ],
        ];

        if ($this->modo === 'editar') {
            $baseRules['matricula_profesional'][] = Rule::unique('medicos_solicitantes', 'matricula_profesional')
                ->ignore($this->editando_id);
        } else {
            $baseRules['matricula_profesional'][] = 'unique:medicos_solicitantes,matricula_profesional';
        }

        return $baseRules;
    }

    protected function messages(): array
    {
        return [
            'nombre_completo.required'         => 'El nombre es obligatorio.',
            'nombre_completo.min'              => 'El nombre debe tener al menos 3 letras.',
            'nombre_completo.max'              => 'El nombre no puede tener más de 30 caracteres.',
            'nombre_completo.regex'            => 'El nombre solo puede contener letras y espacios.',

            'especialidad.min'                 => 'La especialidad debe tener al menos 3 letras.',
            'especialidad.max'                 => 'La especialidad no puede tener más de 60 caracteres.',
            'especialidad.regex'               => 'La especialidad solo puede contener letras y espacios.',

            'matricula_profesional.required'   => 'La matrícula es obligatoria.',
            'matricula_profesional.max'        => 'La matrícula no puede tener más de 14 caracteres.',
            'matricula_profesional.unique'     => 'Esta matrícula ya está registrada.',

            'correo.email'                     => 'Ingrese un correo electrónico válido (ej: doctor@gmail.com).',
            'correo.regex'                     => 'El dominio del correo no es válido (ej: doctor@gmail.com).',
            'correo.max'                       => 'El correo no puede superar los 255 caracteres.',
        ];
    }

    // ─── Guardar ──────────────────────────────────────────────────────────────

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

    // ─── Borrar ───────────────────────────────────────────────────────────────

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

    // ─── Render ───────────────────────────────────────────────────────────────

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