<div x-data="turnosApp()" x-init="init()" class="relative">
    {{-- Toast --}}
    <div
        x-show="toast.show"
        x-transition.opacity
        :class="toast.ok ? 'bg-green-700 text-green-50' : 'bg-red-700 text-red-50'"
        class="fixed top-5 right-5 z-50 px-5 py-3 rounded-lg text-sm font-medium shadow"
        style="display:none"
        x-text="toast.msg"
        x-on:notify.window="showToast($event.detail)"
    ></div>

    <div class="max-w-6xl mx-auto p-4 space-y-4">

        {{-- Tabs --}}
        <div class="flex gap-2 flex-wrap">
            @foreach([['personal','ti-users','Personal'],['calendar','ti-calendar','Calendario'],['list','ti-list','Turnos'],['feriados','ti-flag','Feriados']] as [$tab,$icon,$label])
            <button
                @click="activeTab = '{{ $tab }}'"
                :class="activeTab === '{{ $tab }}' ? 'bg-white border-gray-300 text-gray-900 shadow-sm' : 'text-gray-500 border-transparent hover:bg-white/60'"
                class="flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium border rounded-lg transition">
                <i class="ti {{ $icon }}" aria-hidden="true"></i> {{ $label }}
            </button>
            @endforeach
        </div>

        {{-- ==================== TAB: PERSONAL ==================== --}}
        <div x-show="activeTab === 'personal'" x-transition>
            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-3">Estado del equipo</p>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach($users as $u)
                        @php
                            $count = $turnosCounts[$u->id] ?? 0;
                            $mood = $this->getMood($count);
                            $label = $this->getMoodLabel($count);
                            $color = $u->color ?? '#3266ad';
                            $badgeBg = $count <= 2 ? '#639922' : ($count <= 5 ? '#BA7517' : '#E24B4A');
                        @endphp
                        <div class="border border-gray-200 rounded-xl p-4 text-center hover:border-gray-400 transition">
                            <div class="flex justify-center mb-3">
                                @if($mood === 'happy')
                                    <svg width="70" height="70" viewBox="0 0 56 56"><circle cx="28" cy="28" r="26" fill="#EAF3DE" stroke="#639922" stroke-width="2"/><circle cx="20" cy="23" r="3" fill="#3B6D11"/><circle cx="36" cy="23" r="3" fill="#3B6D11"/><path d="M18 34 Q28 43 38 34" stroke="#3B6D11" stroke-width="3" fill="none" stroke-linecap="round"/></svg>
                                @elseif($mood === 'mid')
                                    <svg width="70" height="70" viewBox="0 0 56 56"><circle cx="28" cy="28" r="26" fill="#FAEEDA" stroke="#BA7517" stroke-width="2"/><circle cx="20" cy="23" r="3" fill="#633806"/><circle cx="36" cy="23" r="3" fill="#633806"/><path d="M19 35 Q28 31 37 35" stroke="#633806" stroke-width="3" fill="none" stroke-linecap="round"/></svg>
                                @else
                                    <svg width="70" height="70" viewBox="0 0 56 56"><circle cx="28" cy="28" r="26" fill="#FCEBEB" stroke="#E24B4A" stroke-width="2"/><circle cx="20" cy="21" r="3.5" fill="#A32D2D"/><circle cx="36" cy="21" r="3.5" fill="#A32D2D"/><path d="M17 37 Q28 28 39 37" stroke="#A32D2D" stroke-width="3" fill="none" stroke-linecap="round"/></svg>
                                @endif
                            </div>
                            <div class="font-semibold text-gray-800">{{ $u->name }}</div>
                            <div class="text-xs text-gray-500">{{ $u->role ?? 'Empleado' }}</div>
                            <div class="mt-2 inline-block px-3 py-1 text-xs font-medium rounded-full" 
                                 style="background: {{ $badgeBg }}22; color: {{ $badgeBg }}">
                                {{ $count }} turnos
                            </div>
                            <div class="text-xs mt-1 {{ $label['color'] }}">{{ $label['txt'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ==================== TAB: CALENDARIO ==================== --}}
        <div x-show="activeTab === 'calendar'" x-transition>
            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <!-- Navegación mes -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <button wire:click="prevMonth" class="p-2 hover:bg-gray-100 rounded-lg">
                            <i class="ti ti-chevron-left"></i>
                        </button>
                        <span class="text-lg font-semibold w-52 text-center">
                            {{ \Carbon\Carbon::create($calYear, $calMonth + 1)->translatedFormat('F Y') }}
                        </span>
                        <button wire:click="nextMonth" class="p-2 hover:bg-gray-100 rounded-lg">
                            <i class="ti ti-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Días de la semana -->
                <div class="grid grid-cols-7 gap-1 mb-2">
                    @foreach(['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'] as $dia)
                        <div class="text-center text-xs font-medium text-gray-400 py-2">{{ $dia }}</div>
                    @endforeach
                </div>

                <!-- Calendario -->
                @php
                    $firstDay = \Carbon\Carbon::create($calYear, $calMonth + 1, 1);
                    $lastDay = $firstDay->copy()->endOfMonth();
                    $startDow = $firstDay->dayOfWeek;
                    $today = now()->toDateString();
                @endphp

                <div class="grid grid-cols-7 gap-1">
                    @for($i = 0; $i < $startDow; $i++)
                        <div class="min-h-[100px]"></div>
                    @endfor

                    @for($d = 1; $d <= $lastDay->day; $d++)
                        @php
                            $ds = \Carbon\Carbon::create($calYear, $calMonth + 1, $d)->toDateString();
                            $isF = $this->isFeriado($ds);
                            $isW = $this->isFinde($ds);
                            $ferN = $isF ? ($feriados[\Carbon\Carbon::parse($ds)->format('m-d')] ?? '') : '';
                            $dayT = $allTurnos[$ds] ?? collect();
                            $bgCls = $isF && $isW ? 'bg-red-50' : ($isF ? 'bg-amber-50' : ($isW ? 'bg-purple-50' : ''));
                        @endphp

                        <div class="border rounded-xl p-2 min-h-[110px] cursor-pointer hover:border-blue-400 transition {{ $bgCls }}"
                             x-on:click="$wire.set('fecha', '{{ $ds }}')">
                            
                            <div class="flex justify-between items-start">
                                <span class="font-semibold {{ $ds === $today ? 'text-blue-600' : 'text-gray-700' }}">{{ $d }}</span>
                                @if($ferN)
                                    <span class="text-[10px] bg-amber-100 text-amber-700 px-1.5 rounded">{{ $ferN }}</span>
                                @endif
                            </div>

                            <!-- Usuarios asignados ese día -->
                            <div class="mt-2 flex flex-wrap gap-1">
                                @foreach($dayT as $t)
                                    @php $u = $t->user; @endphp
                                    @if($u)
                                        <span class="text-[10px] px-2 py-0.5 rounded-full font-medium"
                                              style="background: {{ $u->color ?? '#3b82f6' }}15; color: {{ $u->color ?? '#1e40af' }}; border: 1px solid {{ $u->color ?? '#3b82f6' }}30">
                                            {{ strtok($u->name, ' ') }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- Formulario para asignar turno --}}
            <div class="bg-white border border-gray-200 rounded-xl p-5 mt-4">
                <p class="text-sm font-medium text-gray-600 mb-4">Asignar Turno</p>
                <form wire:submit.prevent="guardar" class="flex flex-wrap gap-4">
                    <div class="flex-1">
                        <label class="block text-xs text-gray-500 mb-1">Personal</label>
                        <select wire:model="user_id" class="w-full border-gray-300 rounded-lg">
                            <option value="">Seleccionar usuario...</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->role ?? '' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Fecha</label>
                        <input type="date" wire:model="fecha" class="border-gray-300 rounded-lg">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium flex items-center gap-2">
                            <i class="ti ti-plus"></i> Asignar Turno
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ==================== TAB: TURNOS (Lista) ==================== --}}
        <div x-show="activeTab === 'list'" x-transition>
            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <input type="text" wire:model.live.debounce.300ms="search" 
                       placeholder="Buscar por nombre..." 
                       class="w-full border-gray-300 rounded-lg mb-4">

                <div class="divide-y">
                    @forelse($turnos as $t)
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <div class="font-medium">{{ $t->user?->name }}</div>
                                <div class="text-sm text-gray-500">{{ $t->fecha->format('d/m/Y') }}</div>
                            </div>
                            <button wire:click="eliminar({{ $t->id }})" 
                                    class="text-red-500 hover:text-red-700">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    @empty
                        <p class="text-gray-400 py-8 text-center">No hay turnos registrados</p>
                    @endforelse
                </div>
                <div class="mt-4">{{ $turnos->links() }}</div>
            </div>
        </div>

        {{-- ==================== TAB: FERIADOS ==================== --}}
        <div x-show="activeTab === 'feriados'" x-transition>
            <!-- Tu código de feriados (puedes dejarlo o decirme si quieres que lo mejore) -->
        </div>

    </div>

    <script>
    function turnosApp() {
        return {
            activeTab: 'calendar',
            toast: { show: false, msg: '', ok: true },
            init() {},
            showToast({ type, message }) {
                this.toast = { show: true, msg: message, ok: type === 'success' };
                setTimeout(() => this.toast.show = false, 3000);
            }
        }
    }
    </script>
</div>