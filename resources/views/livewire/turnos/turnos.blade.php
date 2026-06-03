<div x-data="turnosApp()" x-init="init()" class="relative">
    {{-- Toast --}}
    <div x-show="toast.show" x-transition.opacity
        :class="toast.ok ? 'bg-green-700 text-green-50' : 'bg-red-700 text-red-50'"
        class="fixed top-5 right-5 z-50 px-5 py-3 rounded-lg text-sm font-medium shadow" style="display:none"
        x-text="toast.msg" x-on:notify.window="showToast($event.detail)"></div>

    <div class="max-w-6xl mx-auto p-4 space-y-4">

        {{-- Tabs --}}
        <div class="flex gap-2 flex-wrap">
            @foreach ([['personal', 'fas fa-users', 'Personal'], ['calendar', 'fas fa-calendar-alt', 'Calendario'], ['list', 'fas fa-list', 'Turnos'], ['feriados', 'fas fa-flag', 'Feriados']] as [$tab, $icon, $label])
                <button @click="activeTab = '{{ $tab }}'"
                    :class="activeTab === '{{ $tab }}' ? 'bg-white border-gray-300 text-gray-900 shadow-sm' :
                        'text-gray-500 border-transparent hover:bg-white/60'"
                    class="flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium border rounded-lg transition">
                    <i class="{{ $icon }}" aria-hidden="true"></i> {{ $label }}
                </button>
            @endforeach
        </div>

        {{-- ==================== TAB: PERSONAL ==================== --}}
        <div x-show="activeTab === 'personal'" x-transition x-cloak>
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-3 border-b pb-2"><i
                        class="fas fa-chart-pie mr-1"></i> Estado de Carga Laboral del Equipo</p>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach ($users as $u)
                        @php
                            $count = $turnosCounts[$u->id] ?? 0;
                            $mood = $this->getMood($count);
                            $label = $this->getMoodLabel($count);
                            $color = $u->color ?? '#3266ad';
                            $badgeBg = $count <= 2 ? '#639922' : ($count <= 5 ? '#BA7517' : '#E24B4A');
                        @endphp
                        <div
                            class="border border-gray-200 rounded-xl p-4 text-center hover:border-blue-400 transition bg-gray-50/50">
                            <div class="flex justify-center mb-3">
                                @if ($mood === 'happy')
                                    <svg width="60" height="60" viewBox="0 0 56 56">
                                        <circle cx="28" cy="28" r="26" fill="#EAF3DE" stroke="#639922"
                                            stroke-width="2" />
                                        <circle cx="20" cy="23" r="3" fill="#3B6D11" />
                                        <circle cx="36" cy="23" r="3" fill="#3B6D11" />
                                        <path d="M18 34 Q28 43 38 34" stroke="#3B6D11" stroke-width="3" fill="none"
                                            stroke-linecap="round" />
                                    </svg>
                                @elseif($mood === 'mid')
                                    <svg width="60" height="60" viewBox="0 0 56 56">
                                        <circle cx="28" cy="28" r="26" fill="#FAEEDA" stroke="#BA7517"
                                            stroke-width="2" />
                                        <circle cx="20" cy="23" r="3" fill="#633806" />
                                        <circle cx="36" cy="23" r="3" fill="#633806" />
                                        <path d="M19 35 Q28 31 37 35" stroke="#633806" stroke-width="3" fill="none"
                                            stroke-linecap="round" />
                                    </svg>
                                @else
                                    <svg width="60" height="60" viewBox="0 0 56 56">
                                        <circle cx="28" cy="28" r="26" fill="#FCEBEB" stroke="#E24B4A"
                                            stroke-width="2" />
                                        <circle cx="20" cy="21" r="3.5" fill="#A32D2D" />
                                        <circle cx="36" cy="21" r="3.5" fill="#A32D2D" />
                                        <path d="M17 37 Q28 28 39 37" stroke="#A32D2D" stroke-width="3" fill="none"
                                            stroke-linecap="round" />
                                    </svg>
                                @endif
                            </div>
                            <div class="font-bold text-gray-800 text-sm truncate">{{ $u->name }}</div>
                            <div class="text-[10px] text-gray-500 uppercase tracking-wider mt-0.5">
                                {{ $u->role ?? 'Empleado' }}</div>
                            <div class="mt-2 inline-block px-3 py-1 text-xs font-black rounded-full"
                                style="background: {{ $badgeBg }}22; color: {{ $badgeBg }}; border: 1px solid {{ $badgeBg }}40">
                                {{ $count }} turnos
                            </div>
                            <div class="text-[11px] font-bold mt-1.5 {{ $label['color'] }}">{{ $label['txt'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ==================== TAB: CALENDARIO ==================== --}}
        <div x-show="activeTab === 'calendar'" x-transition>
            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
                    <h2 class="text-sm font-bold text-gray-600 uppercase tracking-wider"><i
                            class="fas fa-calendar-alt text-blue-500 mr-2"></i> Planificación Mensual</h2>
                    <div class="flex items-center gap-3 bg-gray-50 rounded-lg p-1 border border-gray-200">
                        <button wire:click="prevMonth"
                            class="px-3 py-1.5 hover:bg-white hover:shadow-sm rounded-md transition font-bold text-gray-600">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <span class="text-sm font-black w-40 text-center uppercase tracking-wide text-blue-800">
                            {{ \Carbon\Carbon::create($calYear, $calMonth + 1)->translatedFormat('F Y') }}
                        </span>
                        <button wire:click="nextMonth"
                            class="px-3 py-1.5 hover:bg-white hover:shadow-sm rounded-md transition font-bold text-gray-600">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-7 gap-1 mb-2">
                    @foreach (['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'] as $index => $dia)
                        <div
                            class="text-center text-[11px] font-black uppercase tracking-wider py-2 rounded-lg {{ $index === 0 ? 'text-red-500 bg-red-50' : ($index === 6 ? 'text-purple-600 bg-purple-50' : 'text-gray-500 bg-gray-50') }}">
                            {{ $dia }}
                        </div>
                    @endforeach
                </div>

                @php
                    $firstDay = \Carbon\Carbon::create($calYear, $calMonth + 1, 1);
                    $lastDay = $firstDay->copy()->endOfMonth();
                    $startDow = $firstDay->dayOfWeek;
                    $today = now()->toDateString();
                @endphp

                <div class="grid grid-cols-7 gap-2">
                    @for ($i = 0; $i < $startDow; $i++)
                        <div class="min-h-[110px] bg-gray-50/50 rounded-xl border border-dashed border-gray-200"></div>
                    @endfor

                    @for ($d = 1; $d <= $lastDay->day; $d++)
                        @php
                            $ds = \Carbon\Carbon::create($calYear, $calMonth + 1, $d)->toDateString();
                            $isF = $this->isFeriado($ds);
                            $isW = $this->isFinde($ds);
                            $ferN = $isF ? $feriados[\Carbon\Carbon::parse($ds)->format('m-d')] ?? '' : '';
                            $dayT = $allTurnos[$ds] ?? collect();
                            $bgCls =
                                $isF && $isW
                                    ? 'bg-red-50 border-red-200'
                                    : ($isF
                                        ? 'bg-amber-50 border-amber-200'
                                        : ($isW
                                            ? 'bg-purple-50/30 border-purple-100'
                                            : 'bg-white border-gray-200'));
                            $isSelected = $fecha === $ds;
                        @endphp

                        <div class="border rounded-xl p-2 min-h-[110px] cursor-pointer transition-all {{ $bgCls }} {{ $isSelected ? 'ring-2 ring-blue-500 shadow-md transform -translate-y-1' : 'hover:border-blue-400 hover:shadow-sm' }}"
                            x-on:click="$wire.set('fecha', '{{ $ds }}')">

                            <div class="flex justify-between items-start mb-2">
                                <span
                                    class="w-6 h-6 flex items-center justify-center rounded-full text-xs font-black {{ $ds === $today ? 'bg-blue-600 text-white shadow-sm' : ($isF || Carbon\Carbon::parse($ds)->dayOfWeek === 0 ? 'text-red-500' : 'text-gray-700') }}">
                                    {{ $d }}
                                </span>
                                @if ($ferN)
                                    <span
                                        class="text-[8px] bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded font-bold uppercase truncate max-w-[60px]"
                                        title="{{ $ferN }}"><i class="fas fa-star text-amber-500"></i>
                                        Feriado</span>
                                @endif
                            </div>

                            <div class="flex flex-col gap-1.5">
                                @foreach ($dayT as $t)
                                    @php $u = $t->user; @endphp
                                    @if ($u)
                                        <div class="inline-flex justify-between items-center text-[10px] px-2 py-1 rounded-lg font-bold shadow-sm group"
                                            style="background: {{ $u->color ?? '#3b82f6' }}15; color: {{ $u->color ?? '#1e40af' }}; border: 1px solid {{ $u->color ?? '#3b82f6' }}30">
                                            <span class="truncate pr-1"><i
                                                    class="fas fa-user-md opacity-50 mr-1"></i>{{ strtok($u->name, ' ') }}</span>

                                            <div class="flex items-center">
                                                <button wire:click.stop="editar({{ $t->id }})"
                                                    title="Editar turno"
                                                    class="w-4 h-4 rounded-full flex items-center justify-center text-blue-500 hover:bg-blue-500 hover:text-white transition-colors opacity-60 group-hover:opacity-100 mr-0.5">
                                                    <i class="fas fa-pencil-alt" style="font-size: 8px;"></i>
                                                </button>
                                                <button wire:click.stop="eliminar({{ $t->id }})"
                                                    title="Quitar turno"
                                                    class="w-4 h-4 rounded-full flex items-center justify-center text-red-400 hover:bg-red-500 hover:text-white transition-colors opacity-60 group-hover:opacity-100">
                                                    <i class="fas fa-times" style="font-size: 8px;"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- Formulario para asignar/editar turno --}}
            <div id="form-turno"
                class="{{ $turno_id ? 'bg-yellow-50 border-yellow-300' : 'bg-blue-50 border-blue-200' }} border rounded-xl p-5 mt-4 shadow-sm transition-colors duration-300">
                <h3
                    class="text-sm font-bold {{ $turno_id ? 'text-yellow-800 border-yellow-200/50' : 'text-blue-800 border-blue-200/50' }} mb-4 flex items-center gap-2 border-b pb-2">
                    <i class="fas {{ $turno_id ? 'fa-user-edit' : 'fa-user-plus' }}"></i>
                    {{ $turno_id ? 'Reasignar Turno Seleccionado' : 'Asignar Nuevo Turno' }}
                </h3>

                <form wire:submit.prevent="guardar" class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label
                            class="block text-xs font-bold {{ $turno_id ? 'text-yellow-700' : 'text-blue-700' }} uppercase tracking-wider mb-1.5">Personal
                            de Turno</label>
                        <select wire:model="user_id"
                            class="w-full bg-white text-gray-800 font-medium rounded-lg p-2.5 focus:ring-blue-500 shadow-sm border-gray-300">
                            <option value="">-- Seleccionar usuario --</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->role ?? 'Staff' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="min-w-[150px]">
                        <label
                            class="block text-xs font-bold {{ $turno_id ? 'text-yellow-700' : 'text-blue-700' }} uppercase tracking-wider mb-1.5">Fecha
                            Seleccionada</label>
                        <input type="date" wire:model="fecha"
                            class="w-full bg-white text-gray-800 font-bold rounded-lg p-2.5 shadow-sm border-gray-300 focus:ring-blue-500">
                    </div>
                    <div class="flex gap-2">
                        @if ($turno_id)
                            <button type="button" wire:click="cancelarEdicion"
                                class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2.5 rounded-lg font-bold shadow-md transition-all">
                                Cancelar
                            </button>
                        @endif
                        <button type="submit"
                            class="{{ $turno_id ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-blue-600 hover:bg-blue-700' }} text-white px-6 py-2.5 rounded-lg font-bold flex items-center gap-2 shadow-md transition-all">
                            <i class="fas {{ $turno_id ? 'fa-sync-alt' : 'fa-save' }}"></i>
                            {{ $turno_id ? 'Actualizar' : 'Guardar Asignación' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ==================== TAB: TURNOS (Lista) ==================== --}}
        <div x-show="activeTab === 'list'" x-transition x-cloak>
            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <h2 class="text-sm font-bold text-gray-600 uppercase tracking-wider mb-4 border-b pb-3"><i
                        class="fas fa-list text-purple-500 mr-2"></i> Listado Completo de Turnos</h2>
                <div class="relative mb-5">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Buscar por nombre de personal..."
                        class="w-full pl-10 border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-purple-500">
                </div>

                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-gray-50 text-[10px] font-black text-gray-500 uppercase tracking-wider border-b">
                                <th class="p-3">Personal Asignado</th>
                                <th class="p-3">Fecha del Turno</th>
                                <th class="p-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm font-medium text-gray-700">
                            @forelse($turnos as $t)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="p-3 flex items-center gap-2">
                                        <div
                                            class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-[10px] font-bold">
                                            {{ substr($t->user?->name ?? '?', 0, 2) }}
                                        </div>
                                        {{ $t->user?->name ?? 'Usuario Eliminado' }}
                                    </td>
                                    <td class="p-3">
                                        <div class="flex items-center gap-2">
                                            <i class="far fa-calendar text-gray-400"></i>
                                            {{ $t->fecha->format('d / m / Y') }}
                                        </div>
                                    </td>
                                    <td class="p-3">
                                        <div class="flex items-center justify-center gap-2">
                                            <button wire:click="editar({{ $t->id }})" title="Editar turno"
                                                class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white transition-colors flex items-center justify-center">
                                                <i class="fas fa-edit text-xs"></i>
                                            </button>
                                            <button wire:click="eliminar({{ $t->id }})" title="Eliminar turno"
                                                class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors flex items-center justify-center">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-8 text-center text-gray-400">
                                        <i class="fas fa-clipboard-list text-3xl mb-2 opacity-30"></i>
                                        <p>No hay turnos registrados que coincidan con la búsqueda.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $turnos->links() }}</div>
            </div>
        </div>

        {{-- ==================== TAB: FERIADOS ==================== --}}
        <div x-show="activeTab === 'feriados'" x-transition x-cloak>
            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">
                        <i class="fas fa-flag text-amber-500 mr-2"></i> Días Feriados Nacionales ({{ $calYear }})
                    </h3>
                    <span
                        class="bg-amber-100 text-amber-800 text-[10px] font-black px-2 py-1 rounded-lg uppercase tracking-widest">{{ count($feriadosBase) }}
                        Feriados Activos</span>
                </div>

                <p class="text-xs text-gray-500 mb-5 italic">Lista de feriados precargados y el personal que ha sido
                    asignado a trabajar en esas fechas específicas durante este año.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($feriadosBase as $fecha => $nombre)
                        @php
                            $partes = explode('-', $fecha);
                            $fechaFormateada = \Carbon\Carbon::createFromFormat('m-d', $fecha);
                            // Verificamos si en la base de datos hay turnos que caigan en esta fecha
                            $asignados = $turnosEnFeriados[$fecha] ?? collect();
                        @endphp

                        <div
                            class="flex flex-col p-4 bg-amber-50/50 border border-amber-200 rounded-xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-1 h-full bg-amber-400"></div>

                            <div class="flex items-center gap-3 mb-3 pl-2">
                                <div
                                    class="w-12 h-12 bg-white text-amber-600 font-black rounded-lg flex flex-col items-center justify-center border border-amber-200 shrink-0 shadow-inner">
                                    <span class="text-lg leading-none">{{ $partes[1] }}</span>
                                    <span
                                        class="text-[9px] leading-none border-t border-amber-100 mt-0.5 pt-0.5 uppercase tracking-widest">{{ $fechaFormateada->translatedFormat('M') }}</span>
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 text-sm leading-tight">{{ $nombre }}</p>
                                    <p class="text-[10px] font-bold text-amber-600 uppercase mt-0.5">Feriado Nacional
                                    </p>
                                </div>
                            </div>

                            <div class="mt-auto pt-3 border-t border-amber-100 pl-2">
                                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Personal
                                    en guardia:</p>

                                @if ($asignados->count() > 0)
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach ($asignados as $t)
                                            <div
                                                class="inline-flex items-center text-[10px] px-2 py-1 rounded bg-white border border-amber-200 text-amber-900 font-bold shadow-sm">
                                                <i class="fas fa-user-md text-amber-400 mr-1.5"></i>
                                                {{ $t->user?->name ?? 'Usuario Eliminado' }}
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span
                                        class="inline-block text-[10px] text-red-600 bg-red-50 border border-red-100 px-2 py-1 rounded font-bold uppercase tracking-wider">
                                        <i class="fas fa-exclamation-circle mr-1"></i> Sin asignar
                                    </span>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <script>
        function turnosApp() {
            return {
                activeTab: 'calendar',
                toast: {
                    show: false,
                    msg: '',
                    ok: true
                },
                init() {
                    // Escuchar el evento que manda el Livewire para scrollear hasta el formulario de edición
                    window.addEventListener('scrollToForm', () => {
                        this.activeTab = 'calendar'; // Asegurarnos de que estamos en la pestaña correcta
                        setTimeout(() => {
                            document.getElementById('form-turno').scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        }, 100);
                    });
                },
                showToast(data) {
                    // Compatible con Livewire 3 events
                    let detail = Array.isArray(data) ? data[0] : data;
                    this.toast = {
                        show: true,
                        msg: detail.message,
                        ok: detail.type === 'success'
                    };
                    setTimeout(() => this.toast.show = false, 3000);
                }
            }
        }
    </script>
</div>
