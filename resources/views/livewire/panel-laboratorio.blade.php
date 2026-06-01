<div class="max-w-[1400px] mx-auto py-8 sm:px-6 lg:px-8">

    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-vials text-blue-600"></i>
                Kanban de Laboratorio
            </h1>
            <p class="text-sm text-gray-500 mt-1">Gestión integral de flujos de trabajo clínico y microbiológico.</p>
        </div>
    </div>

    @if (session()->has('mensaje'))
        <div
            class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl shadow-sm flex items-start gap-3">
            <i class="fas fa-check-circle text-green-500 mt-1 text-lg"></i>
            <span class="text-sm font-medium">{{ session('mensaje') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-200 shadow-sm flex flex-col h-[78vh]">
            <h2 class="font-bold text-gray-700 mb-4 pb-2 border-b border-gray-200 flex items-center justify-between">
                <span class="flex items-center gap-2"><i class="fas fa-user-clock text-amber-500"></i> 1.
                    Recepcionados</span>
                <span
                    class="bg-amber-100 text-amber-800 text-xs px-2.5 py-1 rounded-full font-bold shadow-inner">{{ count($muestras_pendientes) }}</span>
            </h2>

            <div class="flex-1 overflow-y-auto space-y-4 pr-2 custom-scrollbar">
                @forelse($muestras_pendientes as $servicio)
                    <div
                        class="bg-white p-4 rounded-xl shadow-sm border border-l-4 border-l-amber-400 hover:shadow-md transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <span
                                class="text-[10px] font-bold text-gray-400 bg-gray-50 px-2 py-0.5 rounded">{{ $servicio->codigo_unico }}</span>
                            <span class="text-xs text-gray-400"><i class="far fa-clock"></i>
                                {{ $servicio->created_at->format('H:i') }}</span>
                        </div>
                        <h3 class="font-bold text-gray-900 text-[15px] leading-tight">
                            {{ $servicio->paciente->nombre_completo }}</h3>
                        <p class="text-xs text-gray-500 mb-3">CI: {{ $servicio->paciente->ci }}</p>

                        <div class="flex flex-wrap gap-1 mb-4">
                            @foreach ($servicio->tiposAnalisis as $analisis)
                                <span
                                    class="bg-gray-100 text-gray-600 text-[9px] px-1.5 py-0.5 rounded border border-gray-200 truncate max-w-full">
                                    {{ $analisis->nombre }}
                                </span>
                            @endforeach
                        </div>

                        <button wire:click="registrarMuestra({{ $servicio->id }})" wire:loading.attr="disabled"
                            class="w-full bg-amber-50 text-amber-700 hover:bg-amber-100 hover:text-amber-800 border border-amber-200 font-bold py-2 px-3 rounded-lg text-xs transition-colors flex justify-center items-center gap-2">
                            <span wire:loading.remove wire:target="registrarMuestra({{ $servicio->id }})">
                                <i class="fas fa-hand-holding-medical"></i> Registrar Muestra Física
                            </span>
                            <span wire:loading wire:target="registrarMuestra({{ $servicio->id }})">
                                <i class="fas fa-spinner fa-spin"></i> Moviendo...
                            </span>
                        </button>
                    </div>
                @empty
                    <div class="text-center py-10 text-gray-400">
                        <i class="fas fa-check-double text-3xl mb-2 opacity-30"></i>
                        <p class="text-xs">No hay pacientes esperando extracción.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-blue-50/30 rounded-2xl p-4 border border-blue-100 shadow-sm flex flex-col h-[78vh]">
            <h2 class="font-bold text-blue-900 mb-4 pb-2 border-b border-blue-200 flex items-center justify-between">
                <span class="flex items-center gap-2"><i class="fas fa-microscope text-blue-500"></i> 2. En
                    Proceso</span>
                <span
                    class="bg-blue-100 text-blue-800 text-xs px-2.5 py-1 rounded-full font-bold shadow-inner">{{ count($muestras_recolectadas) }}</span>
            </h2>

            <div class="flex-1 overflow-y-auto space-y-4 pr-2 custom-scrollbar">
                @forelse($muestras_recolectadas as $servicio)
                    @php
                        $tiene_analisis_normales = false;
                        $tiene_cultivos = false;

                        foreach ($servicio->tiposAnalisis as $analisis) {
                            $nombreCat = strtolower($analisis->categoria->nombre ?? '');
                            if (str_contains($nombreCat, 'microbiolog') || str_contains($nombreCat, 'cultivo')) {
                                $tiene_cultivos = true;
                            } else {
                                $tiene_analisis_normales = true;
                            }
                        }
                    @endphp

                    <div
                        class="bg-white p-4 rounded-xl shadow-sm border border-l-4 border-l-blue-500 hover:shadow-md transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <span
                                class="text-[10px] font-bold text-gray-400 bg-gray-50 px-2 py-0.5 rounded">{{ $servicio->codigo_unico }}</span>
                            @if ($servicio->observaciones_calidad)
                                <span
                                    class="bg-{{ $servicio->observaciones_calidad === 'Normal' ? 'green' : 'red' }}-100 text-{{ $servicio->observaciones_calidad === 'Normal' ? 'green' : 'red' }}-700 text-[9px] px-2 py-0.5 rounded-full font-bold">
                                    {{ $servicio->observaciones_calidad }}
                                </span>
                            @endif
                        </div>
                        <h3 class="font-bold text-gray-900 text-[15px] leading-tight">
                            {{ $servicio->paciente->nombre_completo }}</h3>

                        <div class="mt-4 space-y-2">
                            @if ($tiene_analisis_normales)
                                <a href="{{ route('laboratorio.procesar', $servicio->id) }}" wire:navigate
                                    class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-3 rounded-lg text-xs shadow-sm transition-colors flex justify-center items-center gap-2">
                                    <i class="fas fa-edit"></i> Transcribir Resultados Clínicos
                                </a>
                            @endif

                            @if ($tiene_cultivos)
                                <a href="{{ route('laboratorio.cultivo', $servicio->id) }}" wire:navigate
                                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-3 rounded-lg text-xs shadow-sm transition-colors flex justify-center items-center gap-2">
                                    <i class="fas fa-bacteria"></i> Gestionar Cultivos
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-gray-400">
                        <i class="fas fa-flask text-3xl mb-2 opacity-30"></i>
                        <p class="text-xs">No hay muestras en el área de procesamiento.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div
            class="bg-gray-100/50 rounded-2xl p-4 border border-gray-200 shadow-sm flex flex-col h-[78vh] opacity-80 hover:opacity-100 transition-opacity">
            <h2 class="font-bold text-gray-600 mb-4 pb-2 border-b border-gray-200 flex items-center justify-between">
                <span class="flex items-center gap-2"><i class="fas fa-check-circle text-gray-400"></i> 3.
                    Finalizados</span>
                <span
                    class="bg-gray-200 text-gray-600 text-xs px-2.5 py-1 rounded-full font-bold">{{ count($muestras_completadas) }}</span>
            </h2>

            <div class="flex-1 overflow-y-auto space-y-4 pr-2 custom-scrollbar">
                @forelse($muestras_completadas as $servicio)
                    <div
                        class="bg-white p-3 rounded-xl border {{ $servicio->estado_muestra === 'rechazada' ? 'border-red-200' : 'border-gray-200' }}">
                        <div class="flex justify-between items-center mb-1">
                            <span
                                class="text-xs font-bold text-gray-800">{{ $servicio->paciente->nombre_completo }}</span>
                            @if ($servicio->estado_muestra === 'rechazada')
                                <span
                                    class="text-[9px] text-red-600 bg-red-50 px-1.5 py-0.5 rounded font-bold">RECHAZADA</span>
                            @else
                                <span
                                    class="text-[9px] text-green-600 bg-green-50 px-1.5 py-0.5 rounded font-bold">COMPLETA</span>
                            @endif
                        </div>
                        <p class="text-[10px] text-gray-500 mb-2">Orden: {{ $servicio->codigo_unico }}</p>

                        @if ($servicio->estado_muestra !== 'rechazada')
                            @php
                                $tiene_rutina = false;
                                $tiene_micro = false;
                                foreach ($servicio->tiposAnalisis as $analisis) {
                                    $nombreCat = strtolower($analisis->categoria->nombre ?? '');
                                    if (
                                        str_contains($nombreCat, 'microbiolog') ||
                                        str_contains($nombreCat, 'cultivo')
                                    ) {
                                        $tiene_micro = true;
                                    } else {
                                        $tiene_rutina = true;
                                    }
                                }
                            @endphp

                            <div class="mt-3 space-y-1.5">
                                @if ($tiene_rutina)
                                    <a href="{{ route('laboratorio.pdf', $servicio->id) }}" target="_blank"
                                        class="w-full bg-purple-50 hover:bg-purple-100 text-purple-700 font-bold py-1.5 px-3 rounded text-[10px] transition-colors flex justify-center items-center gap-1 border border-purple-200">
                                        <i class="fas fa-file-pdf"></i> PDF Química/Rutina
                                    </a>
                                @endif

                                @if ($tiene_micro)
                                    <a href="{{ route('laboratorio.pdf_micro', $servicio->id) }}" target="_blank"
                                        class="w-full bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold py-1.5 px-3 rounded text-[10px] transition-colors flex justify-center items-center gap-1 border border-emerald-200">
                                        <i class="fas fa-file-pdf"></i> PDF Microbiología
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-10 text-gray-400">
                        <i class="fas fa-archive text-3xl mb-2 opacity-30"></i>
                        <p class="text-xs">El historial reciente está vacío.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
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
