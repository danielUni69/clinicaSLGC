<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UsersList extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, fn($q) =>
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