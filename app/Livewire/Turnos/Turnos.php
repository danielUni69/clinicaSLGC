<?php

namespace App\Livewire\Turnos;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TurnoDomingoFeriado;
use App\Models\User;
use Carbon\Carbon;

class Turnos extends Component
{
    use WithPagination;

    // Tabs
    public $activeTab = 'calendar';

    // Calendario
    public $calYear;
    public $calMonth;
    public $allTurnos = [];   // Cambiado a array simple

    // Formulario asignar turno
    public $user_id;
    public $fecha;

    // Lista de turnos
    public $search = '';
    public $filterTipo = '';

    // Feriados
    public $newFeriadoNombre;
    public $newFeriadoFecha;

    // Datos
    public $users = [];
    public $feriados = [];
    public $feriadosBase = [];
    public $turnosCounts = [];

    public function mount()
    {
        $this->calYear  = now()->year;
        $this->calMonth = now()->month - 1;

        $this->loadUsers();
        $this->loadFeriados();
        $this->loadTurnos();
        $this->loadTurnosCounts();
    }

    public function loadUsers()
    {
        $this->users = User::where('active', true)
            ->orderBy('name')
            ->get();
    }

    public function loadFeriados()
    {
        $this->feriadosBase = [
            '01-01' => 'Año Nuevo',
            '02-22' => 'Carnaval',
            '03-23' => 'Día del Mar',
            '04-01' => 'Viernes Santo',
            '05-01' => 'Día del Trabajo',
            '06-21' => 'Año Nuevo Aymara',
            '08-06' => 'Independencia',
            '11-02' => 'Día de los Difuntos',
            '12-25' => 'Navidad',
        ];

        $this->feriados = $this->feriadosBase;
    }

    public function loadTurnos()
    {
        $turnos = TurnoDomingoFeriado::with('user')
            ->whereYear('fecha', $this->calYear)
            ->whereMonth('fecha', $this->calMonth + 1)
            ->get();

        // Convertimos a array simple para Livewire
        $this->allTurnos = [];
        foreach ($turnos as $t) {
            $fecha = $t->fecha->format('Y-m-d');
            $this->allTurnos[$fecha][] = $t;
        }
    }

    public function loadTurnosCounts()
    {
        $this->turnosCounts = TurnoDomingoFeriado::selectRaw('user_id, COUNT(*) as total')
            ->groupBy('user_id')
            ->pluck('total', 'user_id')
            ->toArray();
    }

    public function prevMonth()
    {
        $this->calMonth--;
        if ($this->calMonth < 0) {
            $this->calMonth = 11;
            $this->calYear--;
        }
        $this->loadTurnos();
    }

    public function nextMonth()
    {
        $this->calMonth++;
        if ($this->calMonth > 11) {
            $this->calMonth = 0;
            $this->calYear++;
        }
        $this->loadTurnos();
    }

    public function isFeriado($fecha)
    {
        $md = Carbon::parse($fecha)->format('m-d');
        return array_key_exists($md, $this->feriados);
    }

    public function isFinde($fecha)
    {
        $dow = Carbon::parse($fecha)->dayOfWeek;
        return $dow === 0 || $dow === 6;
    }

    public function getMood($count)
    {
        if ($count <= 2) return 'happy';
        if ($count <= 5) return 'mid';
        return 'sad';
    }

    public function getMoodLabel($count)
    {
        if ($count === 0) return ['txt' => 'Disponible', 'color' => 'text-green-600'];
        if ($count <= 2) return ['txt' => 'Descansado', 'color' => 'text-green-600'];
        if ($count <= 5) return ['txt' => 'Cargado', 'color' => 'text-amber-600'];
        return ['txt' => 'Agotado', 'color' => 'text-red-600'];
    }

    public function guardar()
    {
        $this->validate([
            'user_id' => 'required|exists:users,id',
            'fecha'   => 'required|date',
        ]);

        // Evitar duplicados
        $existe = TurnoDomingoFeriado::where('user_id', $this->user_id)
                    ->where('fecha', $this->fecha)
                    ->exists();

        if ($existe) {
            $this->dispatch('notify', type: 'error', message: 'Este usuario ya tiene turno ese día');
            return;
        }

        TurnoDomingoFeriado::create([
            'user_id' => $this->user_id,
            'fecha'   => $this->fecha,
        ]);

        $this->loadTurnos();
        $this->loadTurnosCounts();
        $this->reset('user_id');

        $this->dispatch('notify', type: 'success', message: 'Turno asignado correctamente');
    }

    public function eliminar($id)
    {
        TurnoDomingoFeriado::destroy($id);
        $this->loadTurnos();
        $this->loadTurnosCounts();
        $this->dispatch('notify', type: 'success', message: 'Turno eliminado');
    }

    public function render()
    {
        $turnos = TurnoDomingoFeriado::with('user')
            ->when($this->search, function($q) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$this->search}%"));
            })
            ->orderBy('fecha', 'desc')
            ->paginate(15);

        return view('livewire.turnos.turnos', [
            'turnos' => $turnos,
            'users'  => $this->users,
            'feriados' => $this->feriados,
            'feriadosBase' => $this->feriadosBase,
            'turnosCounts' => $this->turnosCounts,
        ]);
    }
}