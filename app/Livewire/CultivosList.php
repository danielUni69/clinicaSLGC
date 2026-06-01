<?php
namespace App\Livewire;

use App\Models\Cultivo;
use App\Models\Servicio;
use App\Models\Antibiotico;
use App\Models\Antibiograma;
use App\Models\ReporteEvolucion;
use App\Models\TipoAnalisis;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CultivosList extends Component
{
    use WithPagination;

    // Búsqueda y filtros
    public string $search = '';
    public string $filtroEstado = '';

    // Modal crear/editar
    public bool $mostrarFormulario = false;
    public string $modo = 'crear';
    public ?int $cultivo_id = null;

    // Campos del cultivo
    public ?int $servicio_id = null;
    public ?int $tipo_analisis_id = null;
    public string $estado_cultivo = 'en_incubacion';
    public string $cepa_bacteriana = '';
    public ?int $bioquimico_id = null;

    // Antibiograma dinámico
    public array $filas_antibiograma = [];

    // Panel detalle
    public ?int $cultivo_detalle_id = null;
    public string $nueva_observacion = '';

    // Confirmación borrar
    public ?int $confirmando_borrar_id = null;

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFiltroEstado(): void { $this->resetPage(); }

    // ── Abrir crear ──────────────────────────────────────────────
    public function abrirCrear(): void
    {
        $this->reset([
            'cultivo_id', 'servicio_id', 'tipo_analisis_id',
            'cepa_bacteriana', 'filas_antibiograma',
        ]);
        $this->estado_cultivo = 'en_incubacion';
        $this->bioquimico_id  = Auth::id();
        $this->filas_antibiograma = [
            ['antibiotico_id' => '', 'susceptibilidad' => 'S'],
        ];
        $this->modo = 'crear';
        $this->mostrarFormulario = true;
    }

    // ── Abrir editar ─────────────────────────────────────────────
    public function abrirEditar(int $id): void
    {
        $cultivo = Cultivo::with('antibiogramas')->findOrFail($id);

        $this->cultivo_id        = $cultivo->id;
        $this->servicio_id       = $cultivo->servicio_id;
        $this->tipo_analisis_id  = $cultivo->tipo_analisis_id;
        $this->estado_cultivo    = $cultivo->estado_cultivo;
        $this->cepa_bacteriana   = $cultivo->cepa_bacteriana ?? '';
        $this->bioquimico_id     = $cultivo->bioquimico_id;
        $this->filas_antibiograma = $cultivo->antibiogramas
            ->map(fn($a) => [
                'antibiotico_id'  => $a->antibiotico_id,
                'susceptibilidad' => $a->susceptibilidad,
            ])->toArray();

        if (empty($this->filas_antibiograma)) {
            $this->filas_antibiograma = [
                ['antibiotico_id' => '', 'susceptibilidad' => 'S'],
            ];
        }

        $this->modo = 'editar';
        $this->mostrarFormulario = true;
    }

    // ── Filas antibiograma ───────────────────────────────────────
    public function agregarFila(): void
    {
        $this->filas_antibiograma[] = ['antibiotico_id' => '', 'susceptibilidad' => 'S'];
    }

    public function eliminarFila(int $index): void
    {
        array_splice($this->filas_antibiograma, $index, 1);
    }

    // ── Guardar ──────────────────────────────────────────────────
    public function guardar(): void
    {
        $this->validate([
            'servicio_id'              => 'required|exists:servicios,id',
            'tipo_analisis_id'         => 'required|exists:tipos_analisis,id',
            'estado_cultivo'           => 'required|in:en_incubacion,negativo,positivo_identificado',
            'cepa_bacteriana'          => 'nullable|string|max:255',
            'bioquimico_id'            => 'required|exists:users,id',
            'filas_antibiograma.*.antibiotico_id'  => 'required|exists:antibioticos,id',
            'filas_antibiograma.*.susceptibilidad' => 'required|in:S,I,R',
        ]);

        $data = [
            'servicio_id'      => $this->servicio_id,
            'tipo_analisis_id' => $this->tipo_analisis_id,
            'estado_cultivo'   => $this->estado_cultivo,
            'cepa_bacteriana'  => $this->cepa_bacteriana ?: null,
            'bioquimico_id'    => $this->bioquimico_id,
        ];

        if ($this->modo === 'crear') {
            $cultivo = Cultivo::create($data);
        } else {
            $cultivo = Cultivo::findOrFail($this->cultivo_id);
            $cultivo->update($data);
            $cultivo->antibiogramas()->delete();
        }

        foreach ($this->filas_antibiograma as $fila) {
            if (!empty($fila['antibiotico_id'])) {
                Antibiograma::create([
                    'cultivo_id'     => $cultivo->id,
                    'antibiotico_id' => $fila['antibiotico_id'],
                    'susceptibilidad'=> $fila['susceptibilidad'],
                ]);
            }
        }

        $this->mostrarFormulario = false;
        session()->flash('message', $this->modo === 'crear'
            ? 'Cultivo registrado correctamente.'
            : 'Cultivo actualizado correctamente.'
        );
    }

    // ── Detalle ──────────────────────────────────────────────────
    public function verDetalle(int $id): void
    {
        $this->cultivo_detalle_id = ($this->cultivo_detalle_id === $id) ? null : $id;
        $this->nueva_observacion = '';
    }

    public function agregarObservacion(): void
    {
        $this->validate([
            'nueva_observacion' => 'required|string|max:1000',
        ]);

        ReporteEvolucion::create([
            'cultivo_id'  => $this->cultivo_detalle_id,
            'observacion' => $this->nueva_observacion,
        ]);

        $this->nueva_observacion = '';
        session()->flash('message_obs', 'Observación agregada.');
    }

    // ── Borrar ───────────────────────────────────────────────────
    public function confirmarBorrar(int $id): void
    {
        $this->confirmando_borrar_id = ($this->confirmando_borrar_id === $id) ? null : $id;
    }

    public function borrar(int $id): void
    {
        Cultivo::findOrFail($id)->delete();
        $this->confirmando_borrar_id = null;
        if ($this->cultivo_detalle_id === $id) {
            $this->cultivo_detalle_id = null;
        }
        session()->flash('message', 'Cultivo eliminado.');
    }

    public function cancelarFormulario(): void
    {
        $this->mostrarFormulario = false;
    }

    // ── Render ───────────────────────────────────────────────────
    public function render()
    {
        $cultivos = Cultivo::with([
            'servicio.paciente',
            'tipoAnalisis',
            'bioquimico',
            'antibiogramas.antibiotico',
            'reportesEvolucion',
        ])
        ->when($this->search, fn($q) =>
            $q->whereHas('servicio.paciente', fn($p) =>
                $p->where('nombre_completo', 'like', "%{$this->search}%")
                  ->orWhere('ci', 'like', "%{$this->search}%")
            )->orWhere('cepa_bacteriana', 'like', "%{$this->search}%")
        )
        ->when($this->filtroEstado, fn($q) =>
            $q->where('estado_cultivo', $this->filtroEstado)
        )
        ->orderByDesc('created_at')
        ->paginate(10);

        $detalleCultivo = $this->cultivo_detalle_id
            ? Cultivo::with([
                'servicio.paciente',
                'tipoAnalisis',
                'bioquimico',
                'antibiogramas.antibiotico',
                'reportesEvolucion',
              ])->find($this->cultivo_detalle_id)
            : null;

        return view('livewire.cultivos-list', [
            'cultivos'       => $cultivos,
            'antibioticos'   => Antibiotico::where('estado', true)->orderBy('nombre_antibiotico')->get(),
            'tiposAnalisis'  => TipoAnalisis::where('estado', true)->orderBy('nombre')->get(),
            'servicios'      => Servicio::with('paciente')->orderByDesc('created_at')->get(),
            'bioquimicos'    => \App\Models\User::where('role', 'bioquimico')->orderBy('name')->get(),
            'detalleCultivo' => $detalleCultivo,
        ]);
    }
}