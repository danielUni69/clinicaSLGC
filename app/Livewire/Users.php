<?php

namespace App\Livewire;

use App\Models\TipoUsario;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;

    public $search = '';

    public $userId;

    public $name;

    public $email;

    public $password;

    public $tipo_usuario_id;

    public $confirmingUserDeletion = false;

    public $confirmingUserAddition = false;

    public $confirmingUserEdition = false;

    public $tiposUsuario;

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'tipo_usuario_id' => 'required|exists:tipo_usuario,id',
    ];

    public function mount()
    {
        $this->tiposUsuario = TipoUsario::all();
    }

    public function render()
    {
        $users = User::with('tipo')
            ->where('name', 'like', '%'.$this->search.'%')
            ->orWhere('email', 'like', '%'.$this->search.'%')
            ->paginate(10);

        return view('livewire.users', [
            'users' => $users,
        ]);
    }

    public function confirmUserAddition()
    {
        $this->resetInputFields();
        $this->confirmingUserAddition = true;
    }

    public function confirmUserEdition($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->tipo_usuario_id = $user->tipo_usuario_id;
        $this->confirmingUserEdition = true;
    }

    public function confirmUserDeletion($id)
    {
        $this->userId = $id;
        $this->confirmingUserDeletion = true;
    }

    public function saveUser()
    {
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email'.($this->userId ? ','.$this->userId : ''),
            'password' => $this->userId ? 'nullable|min:6' : 'required|min:6',
            'tipo_usuario_id' => 'required|exists:tipo_usuario,id',
        ];

        $this->validate($rules);

        if ($this->userId) {
            $user = User::find($this->userId);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password ? bcrypt($this->password) : $user->password,
                'tipo_usuario_id' => $this->tipo_usuario_id,
            ]);
        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => bcrypt($this->password),
                'tipo_usuario_id' => $this->tipo_usuario_id,
            ]);
        }

        $this->resetInputFields();
        $this->confirmingUserAddition = false;
        $this->confirmingUserEdition = false;
        session()->flash('message', $this->userId ? 'Usuario actualizado correctamente.' : 'Usuario creado correctamente.');
    }

    public function deleteUser()
    {
        User::find($this->userId)->delete();
        $this->confirmingUserDeletion = false;
        session()->flash('message', 'Usuario eliminado correctamente.');
    }

    public function resetInputFields()
    {
        $this->userId = '';
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->tipo_usuario_id = '';
    }
}
