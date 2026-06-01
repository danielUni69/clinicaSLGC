<?php

namespace App\Livewire\Turnos;

use App\Models\TurnoDomingoFeriado;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Turnos extends Component
{
    use WithPagination;

    public string $fecha = '';
    public ?int $user_id = null;

    public string $search = '';

    protected function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id'),
            ],
            'fecha' => ['required', 'date'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function guardar(): void
    {
        $validated = $this->validate();

        TurnoDomingoFeriado::create([
            'user_id' => $validated['user_id'],
            'fecha' => $validated['fecha'],
        ]);

        $this->reset(['fecha', 'user_id']);
        $this->dispatch('notify', type: 'success', message: 'Turno guardado.');
    }

    public function render()
    {
        $users = User::query()
            // Recepción puede registrar turnos para bioquímicos y/o recepcionistas (pero no admin)
            ->where('role', '!=', 'administrador')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role']);


        $turnos = TurnoDomingoFeriado::query()
            ->with('bioquimico')
            ->when(trim($this->search) !== '', function ($q) {
                $term = trim($this->search);
                $q->whereHas('bioquimico', function ($qq) use ($term) {
                    $qq->where('name', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%")
                        ->orWhere('role', 'like', "%{$term}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.turnos.turnos', [
            'users' => $users,
            'turnos' => $turnos,
        ]);
    }
}

