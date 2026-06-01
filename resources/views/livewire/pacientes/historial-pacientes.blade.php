<div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-history text-blue-600"></i>
                Historial Clínico de Pacientes
            </h1>
            <p class="text-sm text-gray-500 mt-1">Listado total de pacientes. Busque por CI o nombre.</p>
        </div>

        <div class="hidden sm:flex items-center gap-2 text-sm text-gray-500">
            <span class="inline-flex items-center gap-2 bg-blue-50 border border-blue-100 px-3 py-1.5 rounded-xl">
                <i class="fas fa-database text-blue-500"></i>
                {{ $pacientes->total() }} pacientes
            </span>
        </div>
    </div>

    @if($pacienteSeleccionadoId)
        {{-- Modal emergente (no desplegable) --}}
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50" wire:click="$set('pacienteSeleccionadoId', null)" role="button" tabindex="0"></div>

            <div class="relative w-full max-w-4xl bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="min-w-0">
                        <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-layer-group text-blue-500"></i>
                            Historial Clínico
                        </h2>
                        <p class="text-sm text-gray-500 truncate">
                            Paciente: {{ $pacientes->firstWhere('id', $pacienteSeleccionadoId)?->nombre_completo ?? '—' }}
                        </p>
                    </div>

                    <button type="button" wire:click="$set('pacienteSeleccionadoId', null)" class="text-sm font-medium px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 transition">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                </div>

                <div class="max-h-[70vh] overflow-auto">
                    @livewire('pacientes.detalle-historial', ['pacienteId' => $pacienteSeleccionadoId], key('detalle-historial-'.$pacienteSeleccionadoId))
                </div>
            </div>
        </div>
    @endif



    {{-- Buscador --}}
    <div class="bg-white shadow-sm rounded-2xl p-4 border border-gray-100 mb-6">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input
                type="text"
                wire:model.live="search"
                placeholder="Buscar paciente por CI o nombre..."
                class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition-colors"
            >
        </div>
    </div>

    {{-- Lista --}}
    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden relative">
        <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>

        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <span class="bg-blue-100 text-blue-700 w-8 h-8 rounded-full flex items-center justify-center text-sm">
                    <i class="fas fa-notes-medical"></i>
                </span>
                Historial (Lista Total)
            </h2>
            <span class="bg-blue-100 text-blue-700 text-xs py-1 px-3 rounded-lg font-medium">
                {{ $pacientes->total() }} pacientes
            </span>
        </div>

        @forelse($pacientes as $paciente)
            <div class="px-6 py-4 border-b border-gray-50 last:border-b-0 hover:bg-gray-50/50 transition-colors">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-11 h-11 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0 shadow-sm">
                            {{ strtoupper(substr($paciente->nombre_completo, 0, 1)) }}{{ strtoupper(substr(strrchr($paciente->nombre_completo, ' '), 1, 1)) }}
                        </div>

                        <div class="min-w-0">
                            <p class="text-base font-bold text-gray-800 truncate">{{ $paciente->nombre_completo }}</p>
                            <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1">
                                <span class="text-sm text-gray-500 flex items-center gap-1">
                                    <i class="fas fa-id-card text-gray-300 text-xs"></i>
                                    CI: <span class="text-gray-700 font-medium ml-1">{{ $paciente->ci }}</span>
                                </span>
                                <span class="text-sm text-gray-500 flex items-center gap-1">
                                    <i class="fas fa-venus-mars text-gray-300 text-xs"></i>
                                    {{ $paciente->sexo }}
                                </span>
                                <span class="text-sm text-gray-500 flex items-center gap-1">
                                    <i class="fas fa-phone text-gray-300 text-xs"></i>
                                    {{ $paciente->telefono ?? '—' }}
                                </span>
                            </div>

                            @if($paciente->responsable)
                                <div class="mt-2 text-xs text-gray-600">
                                    <i class="fas fa-user-shield text-gray-400 mr-2"></i>
                                    Responsable: {{ $paciente->responsable->nombre_completo }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Acciones --}}
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            wire:click="$set('pacienteSeleccionadoId', {{ $paciente->id }})"
                            class="flex items-center gap-2 text-sm font-medium px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white transition-all shadow-sm"
                            title="Ver historial del paciente"
                        >
                            <i class="fas fa-notes-medical"></i>
                            Ver Historial
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                <i class="fas fa-user-slash text-4xl mb-3 opacity-20"></i>
                <p class="text-sm text-center">No se encontraron pacientes.<br>Intente con otro término de búsqueda.</p>
            </div>
        @endforelse

        {{-- Paginación --}}
        @if($pacientes->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $pacientes->links() }}
            </div>
        @endif
    </div>
</div>

