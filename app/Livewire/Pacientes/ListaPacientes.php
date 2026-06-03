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

    #[Url]
    public string $search = '';

    public bool $mostrarFormulario = false;

    public string $modo = 'crear';

    public ?int $editando_id = null;

    // Paciente
    public string $ci = '';

    public string $nombre_completo = '';

    public string $fecha_nacimiento = '';

    public string $sexo = 'M';

    public ?string $telefono = null;

    public ?string $email = null;

    // Responsable (opcional)
    public bool $tiene_responsable = false;

    public string $responsable_nombre = '';

    public ?string $responsable_celular = null;

    public ?string $responsable_email = null;

    public string $responsable_relacion = 'Familiar';

    // Borrado
    public ?int $confirmando_borrar_id = null;

    // ─── Bloqueo en tiempo real ───────────────────────────────────────────────

    /**
     * Nombre: solo letras/espacios Unicode, máximo 30 caracteres.
     * Cualquier carácter inválido se elimina automáticamente.
     */
    public function updatingNombreCompleto(string $value): void
    {
        $limpio = preg_replace('/[^\pL\s]/u', '', $value);
        $this->nombre_completo = mb_substr($limpio, 0, 30);
    }

    /**
     * CI: solo dígitos, letras A-Z y un espacio.
     * Formato esperado: 12345678 LP (8 dígitos + espacio + 1-2 letras).
     * Se bloquea cualquier carácter fuera de ese juego.
     */
    public function updatingCi(string $value): void
    {
        // Permitir solo dígitos, letras mayúsculas y un espacio
        $limpio = preg_replace('/[^0-9A-Za-z\s]/', '', $value);
        $limpio = strtoupper($limpio);

        // Limitar a 11 caracteres máximo ("12345678 LP")
        $this->ci = substr($limpio, 0, 11);
    }

    /**
     * Teléfono: solo dígitos, máximo 8.
     */
    public function updatingTelefono(?string $value): void
    {
        if ($value !== null) {
            $this->telefono = substr(preg_replace('/\D/', '', $value), 0, 8);
        }
    }

    /**
     * Nombre del responsable: solo letras/espacios Unicode, máximo 30 caracteres.
     */
    public function updatingResponsableNombre(string $value): void
    {
        $limpio = preg_replace('/[^\pL\s]/u', '', $value);
        $this->responsable_nombre = mb_substr($limpio, 0, 30);
    }

    /**
     * Celular del responsable: solo dígitos, máximo 8.
     */
    public function updatingResponsableCelular(?string $value): void
    {
        if ($value !== null) {
            $this->responsable_celular = substr(preg_replace('/\D/', '', $value), 0, 8);
        }
    }

    // ─── Responsable toggle ───────────────────────────────────────────────────

    public function tiene_responsable_set(bool $valor): void
    {
        $this->tiene_responsable = $valor;

        if (! $valor) {
            $this->responsable_nombre = '';
            $this->responsable_celular = null;
            $this->responsable_email = null;
            $this->responsable_relacion = 'Familiar';
        }
    }

    // ─── Búsqueda ─────────────────────────────────────────────────────────────

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
        $paciente = Paciente::with('responsable')->findOrFail($id);

        $this->modo = 'editar';
        $this->editando_id = $id;
        $this->mostrarFormulario = true;

        $this->ci = (string) $paciente->ci;
        $this->nombre_completo = (string) $paciente->nombre_completo;
        $this->fecha_nacimiento = $paciente->fecha_nacimiento
            ? $paciente->fecha_nacimiento->format('Y-m-d')
            : '';
        $this->sexo = (string) $paciente->sexo;
        $this->telefono = $paciente->telefono;
        $this->email = $paciente->email;

        $this->tiene_responsable = (bool) $paciente->responsable;
        $this->responsable_nombre = $paciente->responsable?->nombre_completo ?? '';
        $this->responsable_celular = $paciente->responsable?->celular ?? null;
        $this->responsable_email = $paciente->responsable?->correo ?? null;
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
        $this->email = null;

        $this->tiene_responsable = false;
        $this->responsable_nombre = '';
        $this->responsable_celular = null;
        $this->responsable_email = null;
        $this->responsable_relacion = 'Familiar';
    }

    // ─── Validación ───────────────────────────────────────────────────────────

    protected function rules(): array
    {
        // Fecha máxima: ayer (ni hoy ni futuro)
        $ayer = now()->subDay()->format('Y-m-d');

        $baseRules = [
            // CI: exactamente 8 dígitos + espacio + 1-2 letras mayúsculas
            'ci' => ['required', 'string', 'max:11', 'regex:/^\d{8}( [A-Z]{1,2})?$/'],

            // Nombre: solo letras Unicode y espacios, máximo 30 caracteres
            'nombre_completo' => ['required', 'string', 'min:3', 'max:30', 'regex:/^[\pL\s]+$/u'],

            // Fecha: no puede ser hoy ni futura
            'fecha_nacimiento' => "required|date|before_or_equal:{$ayer}",

            'sexo' => 'required|in:M,F',

            // Teléfono: solo dígitos, máximo 8
            'telefono' => 'nullable|digits_between:1,8',

            // Email paciente (opcional): máximo 30, 1 @, dominio con punto
            'email' => [
                'nullable',
                'string',
                'max:30',
                'regex:/^[^@\s]{1,64}@[A-Za-z0-9.-]+\.[A-Za-z]{2,10}$/',
            ],

            'tiene_responsable' => 'boolean',

            // Nombre responsable: solo letras Unicode y espacios, máximo 30
            'responsable_nombre' => [
                'required_if:tiene_responsable,true',
                'nullable', 'string', 'min:3', 'max:30',
                'regex:/^[\pL\s]+$/u',
            ],

            // Celular responsable: solo dígitos, máximo 8
            'responsable_celular' => 'nullable|digits_between:1,8',
            'responsable_relacion' => 'required_if:tiene_responsable,true|nullable|string|max:100',

            // Email responsable (opcional): máximo 30, formato válido
            'responsable_email' => [
                'nullable',
                'string',
                'max:30',
                'regex:/^[^@\s]{1,64}@[A-Za-z0-9.-]+\.[A-Za-z]{2,10}$/',
            ],
        ];

        if ($this->modo === 'editar') {
            $baseRules['ci'] = [
                'required', 'string', 'max:11',
                'regex:/^\d{8}( [A-Z]{1,2})?$/',
                Rule::unique('pacientes', 'ci')->ignore($this->editando_id),
            ];
        } else {
            $baseRules['ci'] = [
                'required', 'string', 'max:11',
                'regex:/^\d{8}( [A-Z]{1,2})?$/',
                'unique:pacientes,ci',
            ];
        }

        return $baseRules;
    }

    protected function messages(): array
    {
        return [
            'ci.regex' => 'El CI debe tener exactamente 8 dígitos. Las letras son opcionales (ej: 12345678 o 12345678 LP).',
            'ci.unique' => 'Este CI ya está registrado.',
            'nombre_completo.min' => 'El nombre debe tener al menos 3 letras.',
            'nombre_completo.regex' => 'El nombre solo puede contener letras y espacios.',
            'nombre_completo.max' => 'El nombre no puede tener más de 30 caracteres.',
            'fecha_nacimiento.before_or_equal' => 'La fecha de nacimiento no puede ser hoy ni una fecha futura.',
            'telefono.digits_between' => 'El teléfono no puede tener más de 8 dígitos.',
            'email.regex' => 'El correo del paciente no tiene un formato válido.',
            'responsable_nombre.min' => 'El nombre del responsable debe tener al menos 3 letras.',
            'responsable_nombre.regex' => 'El nombre del responsable solo puede contener letras y espacios.',
            'responsable_nombre.max' => 'El nombre del responsable no puede tener más de 30 caracteres.',
            'responsable_celular.digits_between' => 'El celular del responsable no puede tener más de 8 dígitos.',
            'responsable_email.regex' => 'El correo del responsable no tiene un formato válido.',
        ];
    }

    // ─── Guardar ──────────────────────────────────────────────────────────────

    public function guardar(): void
    {
        $validated = $this->validate();

        $responsableId = null;

        $tieneResponsable = (bool) ($validated['tiene_responsable'] ?? false);

        // ── Si quitaron el responsable en modo editar, borrarlo de BD ──────────
        if (! $tieneResponsable && $this->modo === 'editar') {
            $pac = Paciente::with('responsable')->find($this->editando_id);
            if ($pac?->responsable) {
                $pac->responsable->delete();
                $pac->update(['responsable_id' => null]);
            }
        }

        if ($tieneResponsable && trim($validated['responsable_nombre'] ?? '') !== '') {

            if ($this->modo === 'editar') {
                $paciente = Paciente::with('responsable')->findOrFail($this->editando_id);

                $where = $paciente->responsable ? ['id' => $paciente->responsable->id] : [];

                if (! empty($where)) {
                    $paciente->responsable()->updateOrCreate(
                        $where,
                        [
                            'nombre_completo' => $validated['responsable_nombre'],
                            'celular' => $validated['responsable_celular'] ?? 'Sin registro',
                            'correo' => $validated['responsable_email'] ?? null,
                            'relacion' => $validated['responsable_relacion'] ?? 'Familiar',
                        ]
                    );
                } else {
                    $nuevoResponsable = Responsable::create([
                        'nombre_completo' => $validated['responsable_nombre'],
                        'celular' => $validated['responsable_celular'] ?? 'Sin registro',
                        'correo' => $validated['responsable_email'] ?? null,
                        'relacion' => $validated['responsable_relacion'] ?? 'Familiar',
                    ]);

                    $responsableId = $nuevoResponsable->id;
                }

                $responsableId = $responsableId ?? $paciente->responsable()->first()?->id;

            } else {
                $responsable = Responsable::create([
                    'nombre_completo' => $validated['responsable_nombre'],
                    'celular' => $validated['responsable_celular'] ?? 'Sin registro',
                    'correo' => $validated['responsable_email'] ?? null,
                    'relacion' => $validated['responsable_relacion'] ?? 'Familiar',
                ]);

                $responsableId = $responsable->id;
            }
        }

        if ($this->modo === 'editar') {
            $paciente = Paciente::findOrFail($this->editando_id);

            $paciente->update([
                'responsable_id' => $tieneResponsable ? ($paciente->responsable?->id ?? $responsableId) : null,
                'ci' => $validated['ci'],
                'nombre_completo' => $validated['nombre_completo'],
                'fecha_nacimiento' => $validated['fecha_nacimiento'],
                'sexo' => $validated['sexo'],
                'telefono' => $validated['telefono'],
                'email' => $validated['email'] ?? null,
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
            'email' => $validated['email'] ?? null,
        ]);

        session()->flash('message', 'Paciente registrado correctamente.');
        $this->cancelarFormulario();
    }

    // ─── Borrar ───────────────────────────────────────────────────────────────

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

    // ─── Render ───────────────────────────────────────────────────────────────

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
