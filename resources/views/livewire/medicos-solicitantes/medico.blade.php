<div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-user-md text-blue-600"></i>
                Gestión de Médicos Solicitantes
            </h1>
            <p class="text-sm text-gray-500 mt-1">Consulte, busque y administre los médicos registrados.</p>
        </div>

        <button
            type="button"
            wire:click="abrirCrear"
            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-5 rounded-xl shadow-sm transition-all flex items-center gap-2">
            <i class="fas fa-plus"></i> Nuevo Médico
        </button>
    </div>

    {{-- Mensaje éxito --}}
    @if (session()->has('message'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl shadow-sm flex items-start gap-3" role="alert">
            <i class="fas fa-check-circle text-green-500 mt-1 text-lg"></i>
            <div>
                <p class="font-bold text-sm">¡Operación Exitosa!</p>
                <p class="text-sm">{{ session('message') }}</p>
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
                placeholder="Buscar por nombre, especialidad, matrícula o correo..."
                class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition-colors"
            >
        </div>
    </div>

    {{-- MODAL formulario --}}
    @if($mostrarFormulario)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            {{-- overlay --}}
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="cancelarFormulario"></div>

            {{-- panel --}}
            <div class="relative w-full max-w-3xl mx-4 bg-white shadow-2xl rounded-2xl overflow-hidden border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <span class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold bg-blue-100 text-blue-700">
                            <i class="fas {{ $modo === 'editar' ? 'fa-user-edit' : 'fa-user-plus' }}"></i>
                        </span>
                        {{ $modo === 'editar' ? 'Editar Médico' : 'Nuevo Médico' }}
                    </h2>

                    <button
                        type="button"
                        wire:click="cancelarFormulario"
                        class="px-4 py-2 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-all flex items-center gap-2">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                </div>

                <div class="p-6">
                    <form wire:submit.prevent="guardar" class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Nombre Completo</label>
                                <input type="text" wire:model="nombre_completo"
                                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                @error('nombre_completo')
                                    <span class="text-red-500 text-xs mt-1 block"><i class="fas fa-times-circle"></i> {{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Matrícula Profesional</label>
                                <input type="text" wire:model="matricula_profesional"
                                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                @error('matricula_profesional')
                                    <span class="text-red-500 text-xs mt-1 block"><i class="fas fa-times-circle"></i> {{ $message }}</span>
                                @enderror
                            </div>

                            <div class="md:col-span-3">
                                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Especialidad</label>
                                <input type="text" wire:model="especialidad"
                                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                @error('especialidad')
                                    <span class="text-red-500 text-xs mt-1 block"><i class="fas fa-times-circle"></i> {{ $message }}</span>
                                @enderror
                            </div>

                            <div class="md:col-span-3">
                                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Correo</label>
                                <input type="email" wire:model="correo"
                                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                @error('correo')
                                    <span class="text-red-500 text-xs mt-1 block"><i class="fas fa-times-circle"></i> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-3 border-t border-gray-200">
                            <button
                                type="button"
                                wire:click="cancelarFormulario"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-all flex items-center gap-2">
                                <i class="fas fa-times"></i> Cancelar
                            </button>

                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="px-5 py-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition-all flex items-center gap-2 disabled:opacity-70">
                                <i class="fas fa-save"></i>
                                {{ $modo === 'editar' ? 'Guardar Cambios' : 'Guardar Médico' }}
                                <span wire:loading wire:target="guardar" class="flex items-center gap-2">
                                    <i class="fas fa-spinner fa-spin"></i> Procesando...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Lista --}}
    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden relative">
        <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>

        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <span class="bg-blue-100 text-blue-700 w-8 h-8 rounded-full flex items-center justify-center text-sm">
                    <i class="fas fa-list text-xs"></i>
                </span>
                Médicos Solicitantes
            </h2>
            <span class="bg-blue-100 text-blue-700 text-xs py-1 px-3 rounded-lg font-medium">
                {{ $medicos->total() }} médicos
            </span>
        </div>

        @forelse($medicos as $medico)
            <div class="px-6 py-4 border-b border-gray-50 last:border-b-0 hover:bg-gray-50/50 transition-colors">
                <div class="flex items-center gap-4">
                    {{-- Avatar --}}
                    <div class="w-11 h-11 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0 shadow-sm">
                        {{ strtoupper(substr($medico->nombre_completo, 0, 1)) }}{{ strtoupper(substr(strrchr($medico->nombre_completo, ' '), 1, 1)) }}
                    </div>

                    {{-- Datos --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-base font-bold text-gray-800 truncate">{{ $medico->nombre_completo }}</p>

                        <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1">
                            <span class="text-sm text-gray-500 flex items-center gap-1">
                                <i class="fas fa-id-badge text-gray-300 text-xs"></i>
                                Matrícula: <span class="text-gray-700 font-medium ml-1">{{ $medico->matricula_profesional }}</span>
                            </span>

                            @if($medico->especialidad)
                                <span class="text-sm text-gray-500 flex items-center gap-1">
                                    <i class="fas fa-stethoscope text-gray-300 text-xs"></i>
                                    <span class="text-gray-700 ml-1">{{ $medico->especialidad }}</span>
                                </span>
                            @endif

                            @if($medico->correo)
                                <span class="text-sm text-gray-500 flex items-center gap-1">
                                    <i class="fas fa-envelope text-gray-300 text-xs"></i>
                                    <span class="text-gray-700 ml-1">{{ $medico->correo }}</span>
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Acciones --}}
                    <div class="flex items-center gap-2">
                        {{-- Editar --}}
                        <button
                            type="button"
                            wire:click="abrirEditar({{ $medico->id }})"
                            class="flex items-center gap-2 text-sm font-medium px-4 py-2 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white transition-all shadow-sm">
                            <i class="fas fa-pen"></i> Editar
                        </button>

                        {{-- Borrar --}}
                        @if($confirmando_borrar_id === $medico->id)
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    wire:click="borrar({{ $medico->id }})"
                                    class="flex items-center gap-2 text-sm font-bold px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white transition-all shadow-sm">
                                    <i class="fas fa-trash"></i> Confirmar
                                </button>
                                <button
                                    type="button"
                                    wire:click="confirmarBorrar({{ $medico->id }})"
                                    class="flex items-center gap-2 text-sm font-medium px-3 py-2 rounded-xl border border-red-200 text-red-700 hover:bg-red-50 transition-all">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @else
                            <button
                                type="button"
                                wire:click="confirmarBorrar({{ $medico->id }})"
                                class="flex items-center gap-2 text-sm font-medium px-4 py-2 rounded-xl border border-red-200 text-red-700 hover:bg-red-50 transition-all">
                                <i class="fas fa-trash"></i> Borrar
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                <i class="fas fa-user-doctor text-4xl mb-3 opacity-20"></i>
                <p class="text-sm text-center">No se encontraron médicos.<br>Intente con otro término de búsqueda.</p>
            </div>
        @endforelse

        {{-- Paginación --}}
        @if($medicos->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $medicos->links() }}
            </div>
        @endif
    </div>
</div>

