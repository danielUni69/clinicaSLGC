<div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">

    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-vials text-purple-600"></i>
                Panel de Control de Laboratorio
            </h1>
            <p class="text-sm text-gray-500 mt-1">Gestión de muestras y transcripción de resultados.</p>
        </div>
    </div>

    @if (session()->has('mensaje'))
        <div
            class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl shadow-sm flex items-start gap-3">
            <i class="fas fa-check-circle text-green-500 mt-1 text-lg"></i>
            <span class="text-sm font-medium">{{ session('mensaje') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-200 shadow-inner flex flex-col h-[75vh]">
            <h2 class="font-bold text-gray-700 mb-4 pb-2 border-b border-gray-200 flex items-center justify-between">
                <span class="flex items-center gap-2"><i class="fas fa-hourglass-half text-amber-500"></i> Esperando
                    Toma de Muestra</span>
                <span
                    class="bg-amber-100 text-amber-800 text-xs px-2.5 py-1 rounded-full font-bold">{{ count($muestras_pendientes) }}</span>
            </h2>

            <div class="flex-1 overflow-y-auto space-y-4 pr-2 custom-scrollbar">
                @forelse($muestras_pendientes as $servicio)
                    <div
                        class="bg-white p-4 rounded-xl shadow-sm border border-l-4 border-l-amber-400 hover:shadow-md transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-xs font-bold text-gray-400">{{ $servicio->codigo_unico }}</span>
                            <span class="text-xs text-gray-400"><i class="far fa-clock"></i>
                                {{ $servicio->created_at->format('H:i') }}</span>
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg">{{ $servicio->paciente->nombre_completo }}</h3>
                        <p class="text-xs text-gray-500 mb-3">CI: {{ $servicio->paciente->ci }}</p>

                        <div class="flex flex-wrap gap-1 mb-4">
                            @foreach ($servicio->tiposAnalisis as $analisis)
                                <span
                                    class="bg-gray-100 text-gray-600 text-[10px] px-2 py-1 rounded-md border border-gray-200">{{ $analisis->nombre }}</span>
                            @endforeach
                        </div>

                        <button wire:click="abrirModalRecepcion({{ $servicio->id }})"
                            class="w-full bg-amber-50 text-amber-700 hover:bg-amber-100 hover:text-amber-800 border border-amber-200 font-bold py-2 px-4 rounded-lg text-sm transition-colors flex justify-center items-center gap-2">
                            <i class="fas fa-hand-holding-medical"></i> Confirmar Toma de Muestra
                        </button>
                    </div>
                @empty
                    <div class="text-center py-10 text-gray-400">
                        <i class="fas fa-check-double text-3xl mb-2 opacity-30"></i>
                        <p class="text-sm">No hay pacientes en sala de espera.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-200 shadow-inner flex flex-col h-[75vh]">
            <h2 class="font-bold text-gray-700 mb-4 pb-2 border-b border-gray-200 flex items-center justify-between">
                <span class="flex items-center gap-2"><i class="fas fa-microscope text-purple-500"></i> Muestras en
                    Proceso</span>
                <span
                    class="bg-purple-100 text-purple-800 text-xs px-2.5 py-1 rounded-full font-bold">{{ count($muestras_recolectadas) }}</span>
            </h2>

            <div class="flex-1 overflow-y-auto space-y-4 pr-2 custom-scrollbar">
                @forelse($muestras_recolectadas as $servicio)
                    <div
                        class="bg-white p-4 rounded-xl shadow-sm border border-l-4 border-l-purple-500 hover:shadow-md transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-xs font-bold text-gray-400">{{ $servicio->codigo_unico }}</span>
                            <span
                                class="bg-{{ $servicio->observaciones_calidad === 'Normal' ? 'green' : 'red' }}-100 text-{{ $servicio->observaciones_calidad === 'Normal' ? 'green' : 'red' }}-700 text-[10px] px-2 py-0.5 rounded-full font-bold">
                                {{ $servicio->observaciones_calidad }}
                            </span>
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg">{{ $servicio->paciente->nombre_completo }}</h3>

                        <div class="flex flex-wrap gap-1 mt-3 mb-4">
                            @foreach ($servicio->tiposAnalisis as $analisis)
                                <span
                                    class="bg-purple-50 text-purple-700 text-[10px] px-2 py-1 rounded-md border border-purple-100">{{ $analisis->nombre }}</span>
                            @endforeach
                        </div>

                        <button
                            class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg text-sm shadow-sm transition-colors flex justify-center items-center gap-2">
                            <a href="{{ route('laboratorio.procesar', $servicio->id) }}" wire:navigate
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg text-sm shadow-sm transition-colors flex justify-center items-center gap-2">
                                <i class="fas fa-edit"></i> Transcribir Resultados
                            </a>
                        </button>
                    </div>
                @empty
                    <div class="text-center py-10 text-gray-400">
                        <i class="fas fa-flask text-3xl mb-2 opacity-30"></i>
                        <p class="text-sm">No hay muestras pendientes de procesamiento.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    @if ($modalVisible)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"
                    wire:click="cerrarModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-amber-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-vial text-amber-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                                    Calidad Visual de la Muestra
                                </h3>
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Evaluación preliminar
                                        (Suero/Plasma)</label>
                                    <select wire:model="calidad_muestra"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3">
                                        @foreach ($opciones_calidad as $opcion)
                                            <option value="{{ $opcion }}">{{ $opcion }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-2">Nota: Si selecciona "Muestra Inviable", la
                                        orden será rechazada automáticamente.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                        <button type="button" wire:click="registrarMuestra"
                            class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Guardar y Continuar
                        </button>
                        <button type="button" wire:click="cerrarModal"
                            class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</div>
