<div class="max-w-[1600px] mx-auto py-8 sm:px-6 lg:px-8">

    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-blue-600 text-white rounded-2xl flex items-center justify-center shadow-md shadow-blue-500/20">
                    <i class="fas fa-network-wired text-lg"></i>
                </div>
                Centro de Comando Clínico
            </h1>
            <p class="text-sm text-slate-500 mt-2 font-medium ml-1">Gestión integral de muestras, análisis y reportes en
                tiempo real.</p>
        </div>
    </div>

    <div class="flex flex-col gap-3 mb-8">

        @if (session()->has('mensaje') && trim((string) session('mensaje')) !== '')
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition.opacity.duration.500ms
                class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl shadow-sm flex items-center justify-between gap-3 animate-[pulse_2s_ease-in-out_3]">
                <div class="flex items-center gap-3">
                    <div
                        class="bg-emerald-500 text-white p-1.5 rounded-full flex items-center justify-center shadow-sm shrink-0">
                        <i class="fas fa-check text-sm"></i>
                    </div>
                    <span class="text-sm font-semibold">{{ session('mensaje') }}</span>
                </div>
                <button @click="show = false" type="button"
                    class="text-emerald-500 hover:text-emerald-700 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if (session()->has('error') && trim((string) session('error')) !== '')
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)" x-transition.opacity.duration.500ms
                class="bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-2xl shadow-sm flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div
                        class="bg-red-500 text-white p-1.5 rounded-full flex items-center justify-center shadow-sm shrink-0">
                        <i class="fas fa-exclamation-triangle text-sm"></i>
                    </div>
                    <span class="text-sm font-semibold">{{ session('error') }}</span>
                </div>
                <button @click="show = false" type="button" class="text-red-500 hover:text-red-700 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if (session()->has('warning') && trim((string) session('warning')) !== '')
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)" x-transition.opacity.duration.500ms
                class="bg-amber-50 border border-amber-200 text-amber-800 px-5 py-4 rounded-2xl shadow-sm flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div
                        class="bg-amber-500 text-white p-1.5 rounded-full flex items-center justify-center shadow-sm shrink-0">
                        <i class="fas fa-exclamation-circle text-sm"></i>
                    </div>
                    <span class="text-sm font-semibold">{{ session('warning') }}</span>
                </div>
                <button @click="show = false" type="button"
                    class="text-amber-500 hover:text-amber-700 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 items-stretch">

        <div
            class="bg-gradient-to-b from-amber-50/50 to-slate-50/80 rounded-3xl border border-amber-200/60 shadow-sm flex flex-col h-[calc(100vh-14rem)] min-h-[600px] overflow-hidden">
            <div
                class="bg-white/80 p-5 border-b border-amber-200/80 sticky top-0 z-10 backdrop-blur-xl flex items-center justify-between">
                <h2 class="font-black text-slate-800 flex items-center gap-2 text-sm uppercase tracking-wider">
                    <i class="fas fa-vial text-amber-500 text-lg drop-shadow-sm"></i> Esperando
                </h2>
                <span
                    class="bg-amber-500 text-white font-bold text-xs px-3 py-1 rounded-full shadow-sm shadow-amber-500/30">
                    {{ count($muestras_pendientes) }}
                </span>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-4 custom-scrollbar">
                @forelse($muestras_pendientes as $servicio)
                    <div
                        class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-lg hover:shadow-amber-500/10 hover:border-amber-300 transition-all duration-300 transform hover:-translate-y-1 group">
                        <div class="absolute top-0 left-0 w-1.5 h-full bg-amber-400"></div>

                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-11 h-11 rounded-full bg-amber-50 text-amber-600 font-black flex items-center justify-center text-sm border border-amber-100 shadow-sm">
                                    {{ substr($servicio->paciente->nombre_completo, 0, 2) }}
                                </div>
                                <div>
                                    <h3
                                        class="font-bold text-slate-900 text-sm leading-tight group-hover:text-amber-700 transition-colors">
                                        {{ $servicio->paciente->nombre_completo }}
                                    </h3>
                                    <p class="text-xs text-slate-500 font-medium mt-1">
                                        <i class="fas fa-id-card text-slate-400 mr-1"></i> {{ $servicio->paciente->ci }}
                                    </p>
                                </div>
                            </div>
                            <span
                                class="text-xs font-bold text-slate-500 bg-slate-50 border border-slate-200 px-2.5 py-1 rounded-lg">
                                {{ $servicio->codigo_unico }}
                            </span>
                        </div>

                        <div class="flex flex-col gap-1.5 mb-5">
                            @foreach ($servicio->tiposAnalisis as $analisis)
                                @if ($analisis->categoria && $analisis->categoria->es_cultivo)
                                    <div
                                        class="bg-emerald-50 text-emerald-700 text-[11px] px-3 py-1.5 rounded-lg border border-emerald-200/70 font-semibold flex items-center gap-2">
                                        <i class="fas fa-bacteria text-emerald-500"></i>
                                        <span class="truncate">{{ $analisis->nombre }}</span>
                                    </div>
                                @else
                                    <div
                                        class="bg-indigo-50 text-indigo-700 text-[11px] px-3 py-1.5 rounded-lg border border-indigo-200/70 font-semibold flex items-center gap-2">
                                        <i class="fas fa-flask text-indigo-400"></i>
                                        <span class="truncate">{{ $analisis->nombre }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <button wire:click="registrarMuestra({{ $servicio->id }})" wire:loading.attr="disabled"
                            class="w-full bg-white text-amber-700 hover:bg-gradient-to-r hover:from-amber-500 hover:to-orange-500 hover:text-white border-2 border-amber-100 hover:border-transparent font-bold py-3 px-4 rounded-xl text-xs transition-all duration-300 flex justify-center items-center gap-2 group-hover:shadow-md">
                            <span wire:loading.remove wire:target="registrarMuestra({{ $servicio->id }})">
                                <i class="fas fa-check-circle"></i> Recibir Muestra
                            </span>
                            <span wire:loading wire:target="registrarMuestra({{ $servicio->id }})">
                                <i class="fas fa-circle-notch fa-spin"></i> Procesando...
                            </span>
                        </button>
                    </div>
                @empty
                    <div
                        class="h-full flex flex-col items-center justify-center text-slate-400 opacity-70 border-2 border-dashed border-amber-200/50 rounded-2xl m-2">
                        <div class="bg-amber-50 p-4 rounded-full mb-4 text-amber-400">
                            <i class="fas fa-mug-hot text-3xl"></i>
                        </div>
                        <p class="text-sm font-medium text-center">No hay pacientes<br>en sala de espera.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div
            class="bg-gradient-to-b from-blue-50/50 to-slate-50/80 rounded-3xl border border-blue-200/60 shadow-sm flex flex-col h-[calc(100vh-14rem)] min-h-[600px] overflow-hidden">
            <div
                class="bg-white/80 p-5 border-b border-blue-200/80 sticky top-0 z-10 backdrop-blur-xl flex items-center justify-between">
                <h2 class="font-black text-slate-800 flex items-center gap-2 text-sm uppercase tracking-wider">
                    <i class="fas fa-microscope text-blue-500 text-lg drop-shadow-sm"></i> En Proceso
                </h2>
                <span
                    class="bg-blue-500 text-white font-bold text-xs px-3 py-1 rounded-full shadow-sm shadow-blue-500/30">
                    {{ count($muestras_recolectadas) }}
                </span>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-4 custom-scrollbar">
                @forelse($muestras_recolectadas as $servicio)
                    @php
                        // Separar rutinas de cultivos
                        $analisis_rutina = $servicio->tiposAnalisis->filter(function ($a) {
                            return !$a->categoria || !$a->categoria->es_cultivo;
                        });
                        $analisis_cultivos = $servicio->tiposAnalisis->filter(function ($a) {
                            return $a->categoria && $a->categoria->es_cultivo;
                        });

                        $tiene_analisis_normales = $analisis_rutina->isNotEmpty();
                        $tiene_cultivos = $analisis_cultivos->isNotEmpty();

                        // LÓGICA INTELIGENTE: Verificar si ya se completaron/enviaron parcialmente
                        $rutina_completada = false;
                        if ($tiene_analisis_normales) {
                            $rutina_completada =
                                \App\Models\ResultadoAnalisis::where('servicio_id', $servicio->id)
                                    ->whereIn('tipo_analisis_id', $analisis_rutina->pluck('id'))
                                    ->count() == $analisis_rutina->count();
                        }

                        $cultivo_completado = false;
                        if ($tiene_cultivos) {
                            $cultivo_completado =
                                \App\Models\Cultivo::where('servicio_id', $servicio->id)
                                    ->whereIn('estado_cultivo', ['negativo', 'positivo_identificado'])
                                    ->count() == $analisis_cultivos->count();
                        }
                    @endphp

                    <div
                        class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-lg hover:shadow-blue-500/10 hover:border-blue-300 transition-all duration-300 transform hover:-translate-y-1 group">
                        <div class="absolute top-0 left-0 w-1.5 h-full bg-blue-500"></div>

                        <div class="flex justify-between items-start mb-4">
                            <h3
                                class="font-bold text-slate-900 text-sm leading-tight flex-1 pr-3 group-hover:text-blue-700 transition-colors">
                                {{ $servicio->paciente->nombre_completo }}
                            </h3>
                            <span
                                class="text-xs font-bold text-slate-500 bg-slate-50 border border-slate-200 px-2.5 py-1 rounded-lg shrink-0">
                                {{ $servicio->codigo_unico }}
                            </span>
                        </div>

                        <div class="flex flex-wrap gap-2 mb-4">
                            @if ($tiene_analisis_normales)
                                <span
                                    class="text-[11px] px-2.5 py-1.5 rounded-lg font-bold flex items-center gap-1.5 {{ $rutina_completada ? 'bg-slate-100 text-slate-400 line-through' : 'bg-indigo-50 text-indigo-700 border border-indigo-200' }}">
                                    <i class="fas fa-flask"></i> Rutina/Clínica
                                </span>
                            @endif
                            @if ($tiene_cultivos)
                                <span
                                    class="text-[11px] px-2.5 py-1.5 rounded-lg font-bold flex items-center gap-1.5 {{ $cultivo_completado ? 'bg-slate-100 text-slate-400 line-through' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                    <i class="fas fa-bacteria"></i> Microbiología
                                </span>
                            @endif
                        </div>

                        <div class="space-y-2.5 mt-5 pt-4 border-t border-slate-100">

                            @if ($tiene_analisis_normales)
                                @if ($rutina_completada)
                                    <div
                                        class="w-full bg-emerald-50 border border-emerald-200 text-emerald-800 font-bold py-2.5 px-4 rounded-xl text-xs flex justify-between items-center shadow-sm">
                                        <span class="flex items-center gap-2"><i
                                                class="fas fa-check-circle text-emerald-500"></i> Clínicos
                                            Enviados</span>
                                        <a href="{{ route('laboratorio.pdf', $servicio->id) }}" target="_blank"
                                            class="text-indigo-600 hover:text-indigo-800 bg-white px-2 py-1 rounded shadow-sm border border-indigo-100 transition-colors">
                                            <i class="fas fa-file-pdf text-red-500"></i> Ver PDF
                                        </a>
                                    </div>
                                @else
                                    <a href="{{ route('laboratorio.procesar', $servicio->id) }}" wire:navigate
                                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl text-xs shadow-sm hover:shadow-md hover:shadow-indigo-500/30 transition-all flex justify-center items-center gap-2">
                                        <i class="fas fa-keyboard"></i> Transcribir Clínicos
                                    </a>
                                @endif
                            @endif

                            @if ($tiene_cultivos)
                                @if ($cultivo_completado)
                                    <div
                                        class="w-full bg-emerald-50 border border-emerald-200 text-emerald-800 font-bold py-2.5 px-4 rounded-xl text-xs flex justify-between items-center shadow-sm">
                                        <span class="flex items-center gap-2"><i
                                                class="fas fa-check-circle text-emerald-500"></i> Cultivo
                                            Enviado</span>
                                        <a href="{{ route('laboratorio.pdf_micro', $servicio->id) }}" target="_blank"
                                            class="text-indigo-600 hover:text-indigo-800 bg-white px-2 py-1 rounded shadow-sm border border-indigo-100 transition-colors">
                                            <i class="fas fa-file-pdf text-red-500"></i> Ver PDF
                                        </a>
                                    </div>
                                @else
                                    <a href="{{ route('laboratorio.cultivo', $servicio->id) }}" wire:navigate
                                        class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-4 rounded-xl text-xs shadow-sm hover:shadow-md hover:shadow-emerald-500/30 transition-all flex justify-center items-center gap-2">
                                        <i class="fas fa-disease"></i> Gestionar Incubación
                                    </a>
                                @endif
                            @endif

                        </div>
                    </div>
                @empty
                    <div
                        class="h-full flex flex-col items-center justify-center text-slate-400 opacity-70 border-2 border-dashed border-blue-200/50 rounded-2xl m-2">
                        <div class="bg-blue-50 p-4 rounded-full mb-4 text-blue-400">
                            <i class="fas fa-microscope text-3xl"></i>
                        </div>
                        <p class="text-sm font-medium text-center">No hay muestras<br>en procesamiento.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div
            class="bg-gradient-to-b from-slate-100/60 to-slate-50/80 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col h-[calc(100vh-14rem)] min-h-[600px] overflow-hidden opacity-95 hover:opacity-100 transition-opacity">
            <div
                class="bg-white/80 p-5 border-b border-slate-200 sticky top-0 z-10 backdrop-blur-xl flex flex-col xl:flex-row xl:items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <h2 class="font-black text-slate-800 flex items-center gap-2 text-sm uppercase tracking-wider">
                        <i class="fas fa-check-double text-slate-500 text-lg"></i> Finalizados
                    </h2>
                    <span class="bg-slate-700 text-white font-bold text-xs px-2.5 py-1 rounded-full shadow-sm">
                        {{ count($muestras_completadas) }}
                    </span>
                </div>

                <input type="date" wire:model.live="fechaFiltro"
                    class="bg-slate-50 border border-slate-200 text-slate-700 text-xs rounded-xl px-3 py-1.5 focus:ring-2 focus:ring-slate-400 focus:border-slate-400 outline-none transition-all shadow-sm w-full xl:w-auto">
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar">
                @forelse($muestras_completadas as $servicio)
                    <div
                        class="p-4 rounded-2xl shadow-sm border transition-all hover:shadow-md group relative overflow-hidden {{ $servicio->estado_muestra === 'rechazada' ? 'bg-red-50/30 border-red-200 hover:border-red-300' : 'bg-white border-slate-200/80 hover:border-slate-300' }}">
                        <div
                            class="absolute top-0 left-0 w-1 h-full {{ $servicio->estado_muestra === 'rechazada' ? 'bg-red-500' : 'bg-slate-300' }}">
                        </div>

                        <div class="flex justify-between items-start mb-3 pl-2">
                            <div class="flex flex-col">
                                <span
                                    class="text-xs font-bold text-slate-800 tracking-wide">{{ $servicio->paciente->nombre_completo }}</span>
                                <p class="text-[11px] text-slate-400 font-medium mt-0.5">Ref:
                                    {{ $servicio->codigo_unico }}</p>

                                <p class="text-[10px] text-slate-500 font-semibold mt-1.5 flex items-center gap-1">
                                    <i
                                        class="fas fa-calendar-check {{ $servicio->estado_muestra === 'rechazada' ? 'text-red-400' : 'text-emerald-500' }}"></i>
                                    Procesado el: <span
                                        class="text-slate-700">{{ $servicio->updated_at->format('d/m/Y - H:i') }}</span>
                                </p>
                            </div>

                            @if ($servicio->estado_muestra === 'rechazada')
                                <span
                                    class="text-[10px] text-red-700 bg-red-100 border border-red-200 px-2 py-1 rounded-lg font-bold tracking-wider shrink-0">RECHAZADA</span>
                            @else
                                <span
                                    class="text-[10px] text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-1 rounded-lg font-bold tracking-wider shrink-0">COMPLETA</span>
                            @endif
                        </div>

                        @if ($servicio->estado_muestra !== 'rechazada')
                            @php
                                $tiene_micro = $servicio->tiposAnalisis->contains(function ($a) {
                                    return $a->categoria && $a->categoria->es_cultivo;
                                });
                                $tiene_rutina = $servicio->tiposAnalisis->contains(function ($a) {
                                    return !$a->categoria || !$a->categoria->es_cultivo;
                                });
                            @endphp

                            <div class="grid grid-cols-1 gap-2 mt-3 pl-2">
                                @if ($tiene_rutina)
                                    <a href="{{ route('laboratorio.pdf', $servicio->id) }}" target="_blank"
                                        class="w-full bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold py-2.5 px-3 rounded-xl text-xs transition-colors flex justify-between items-center border border-slate-200 hover:border-slate-300">
                                        <span class="flex items-center gap-2">
                                            <i class="fas fa-file-pdf text-red-500 text-sm"></i> PDF General
                                        </span>
                                        <i class="fas fa-external-link-alt text-slate-400 text-[10px]"></i>
                                    </a>
                                @endif

                                @if ($tiene_micro)
                                    <a href="{{ route('laboratorio.pdf_micro', $servicio->id) }}" target="_blank"
                                        class="w-full bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold py-2.5 px-3 rounded-xl text-xs transition-colors flex justify-between items-center border border-slate-200 hover:border-slate-300">
                                        <span class="flex items-center gap-2">
                                            <i class="fas fa-file-pdf text-red-500 text-sm"></i> PDF Cultivo
                                        </span>
                                        <i class="fas fa-external-link-alt text-slate-400 text-[10px]"></i>
                                    </a>
                                @endif

                                <button type="button" wire:click="reenviarResultadosPorCorreo({{ $servicio->id }})"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-3 rounded-xl text-xs transition-colors flex justify-between items-center border border-blue-500 mt-1 shadow-sm">
                                    <span class="flex items-center gap-2">
                                        <i class="fas fa-paper-plane"></i> Enviar al correo
                                    </span>
                                    <i class="fas fa-envelope-open-text text-white/80 text-[10px]"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                @empty
                    <div
                        class="h-full flex flex-col items-center justify-center text-slate-400 opacity-70 border-2 border-dashed border-slate-200 rounded-2xl m-2">
                        <div class="bg-slate-100 p-4 rounded-full mb-4">
                            <i class="fas fa-calendar-times text-3xl"></i>
                        </div>
                        <p class="text-sm font-medium text-center">No hay registros completados<br>en la fecha
                            seleccionada.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        input[type="date"]::-webkit-inner-spin-button,
        input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            opacity: 0.6;
            transition: 0.2s;
        }

        input[type="date"]::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
        }
    </style>
</div>
