<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class UsersList extends Component
{
    use WithPagination;

    public string $search = '';

    // ── Modal editar ──────────────────────────────────────────────────────────
    public bool   $mostrarEditar = false;
    public ?int   $editando_id   = null;
    public string $edit_name     = '';
    public string $edit_email    = '';
    public string $edit_role     = '';

    // ── Confirmación dar de baja / activar ────────────────────────────────────
    public ?int $confirmando_baja_id = null;

    // ─────────────────────────────────────────────────────────────────────────

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // ── Abrir modal de edición ────────────────────────────────────────────────

    public function abrirEditar(int $id): void
    {
        $user = User::findOrFail($id);

        $this->editando_id = $id;
        $this->edit_name   = $user->name;
        $this->edit_email  = $user->email;
        $this->edit_role   = $user->role;
        $this->mostrarEditar = true;

        $this->resetErrorBag();
    }

    public function cerrarEditar(): void
    {
        $this->mostrarEditar   = false;
        $this->editando_id     = null;
        $this->edit_name       = '';
        $this->edit_email      = '';
        $this->edit_role       = '';
        $this->resetErrorBag();
    }

    // ── Guardar edición ───────────────────────────────────────────────────────

    public function guardarEdicion(): void
    {
        $this->validate([
            'edit_name'  => ['required', 'string', 'min:3', 'max:60', 'regex:/^[\pL\s]+$/u'],
            'edit_email' => ['required', 'email:rfc', 'max:255',
                              Rule::unique('users', 'email')->ignore($this->editando_id)],
            'edit_role'  => ['required', 'in:admin,bioquimico,recepcionista'],
        ], [
            'edit_name.required'  => 'El nombre es obligatorio.',
            'edit_name.min'       => 'El nombre debe tener al menos 3 caracteres.',
            'edit_name.max'       => 'El nombre no puede superar los 60 caracteres.',
            'edit_name.regex'     => 'El nombre solo puede contener letras y espacios.',
            'edit_email.required' => 'El correo es obligatorio.',
            'edit_email.email'    => 'Ingresa un correo válido.',
            'edit_email.unique'   => 'Este correo ya está en uso por otro usuario.',
            'edit_role.required'  => 'Debes seleccionar un rol.',
            'edit_role.in'        => 'El rol seleccionado no es válido.',
        ]);

        $user = User::findOrFail($this->editando_id);
        $user->update([
            'name'  => trim($this->edit_name),
            'email' => trim($this->edit_email),
            'role'  => $this->edit_role,
        ]);

        session()->flash('message', "Usuario {$user->name} actualizado correctamente.");
        $this->cerrarEditar();
    }

    // ── Dar de baja / Activar ─────────────────────────────────────────────────

    /**
     * Primer clic: pide confirmación.
     * Segundo clic en el mismo id: ejecuta el cambio.
     */
    public function toggleBaja(int $id): void
    {
        if ($this->confirmando_baja_id === $id) {
            // Confirmar: ejecutar
            $user = User::findOrFail($id);
            $nuevoEstado = ! $user->active;
            $user->update(['active' => $nuevoEstado]);

            $accion = $nuevoEstado ? 'activado' : 'dado de baja';
            session()->flash('message', "Usuario {$user->name} {$accion} correctamente.");

            $this->confirmando_baja_id = null;
        } else {
            // Primer clic: marcar para confirmación
            $this->confirmando_baja_id = $id;
        }
    }

    /**
     * Cancelar la confirmación de baja sin hacer nada.
     */
    public function cancelarBaja(): void
    {
        $this->confirmando_baja_id = null;
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function render()
    {
        $users = User::query()
            ->when($this->search, fn ($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
                  ->orWhere('role', 'like', "%{$this->search}%")
            )
            ->orderBy('id')
            ->paginate(10, ['id', 'name', 'email', 'role', 'active']);

        return view('livewire.users-list', [
            'users' => $users,
        ]);
    }
}