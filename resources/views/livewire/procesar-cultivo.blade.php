<div class="w-full max-w-7xl mx-auto py-8 px-8">

    <div class="w-full mb-6 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 flex items-center gap-3 tracking-wide">
                <i class="fas fa-bacteria text-emerald-600"></i>
                Estación de Trabajo de Microbiología
            </h1>
            <p class="text-sm text-slate-500 mt-1 font-medium italic">Gestione líneas de tiempo de incubación, bitácoras
                de desarrollo y antibiogramas.</p>
        </div>
        <a href="{{ route('laboratorio.panel') }}" wire:navigate
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-300 rounded-xl shadow-sm text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors whitespace-nowrap">
            <i class="fas fa-arrow-left"></i> Volver al Kanban
        </a>
    </div>

    <div class="w-full">
        @if (session()->has('mensaje') && trim((string) session('mensaje')) !== '')
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition.opacity.duration.500ms
                class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl shadow-sm flex items-center justify-between gap-3 animate-[pulse_2s_ease-in-out_3]">
                <div class="flex items-center gap-3">
                    <div
                        class="bg-emerald-500 text-white p-1.5 rounded-full flex items-center justify-center shadow-sm shrink-0">
                        <i class="fas fa-check text-sm"></i>
                    </div>
                    <span class="text-sm font-semibold flex-1">{{ session('mensaje') }}</span>
                </div>
                <button @click="show = false" type="button"
                    class="text-emerald-400 hover:text-emerald-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if (session()->has('error') && trim((string) session('error')) !== '')
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)" x-transition.opacity.duration.500ms
                class="mb-6 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl shadow-sm flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div
                        class="bg-red-500 text-white p-1.5 rounded-full flex items-center justify-center shadow-sm shrink-0">
                        <i class="fas fa-exclamation-triangle text-sm"></i>
                    </div>
                    <span class="text-sm font-semibold flex-1">{{ session('error') }}</span>
                </div>
                <button @click="show = false" type="button" class="text-red-400 hover:text-red-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
    </div>

    <div
        class="w-full bg-white rounded-2xl p-6 shadow-sm border border-slate-200 mb-6 flex items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-1.5 h-full bg-emerald-500"></div>

        <div class="flex items-center gap-4">
            <div
                class="w-14 h-14 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-full flex items-center justify-center text-xl shadow-inner font-black shrink-0">
                {{ substr($paciente_nombre, 0, 2) }}
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Paciente Asegurado</p>
                <h2 class="text-lg font-black text-slate-800 leading-tight">{{ $paciente_nombre }}</h2>
                <p class="text-xs text-slate-500 font-medium mt-1"><i class="fas fa-id-card text-slate-400"></i> CI:
                    {{ $paciente_ci }} | Sexo: {{ $paciente_sexo }}</p>
            </div>
        </div>

        <div class="text-right bg-slate-50 p-3 rounded-xl border border-slate-200 min-w-[200px]">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Orden de Servicio</p>
            <p class="text-lg font-black text-emerald-700 tracking-tight">{{ $servicio->codigo_unico }}</p>
            <p class="text-xs text-slate-500 font-medium mt-1">
                <i class="far fa-calendar-alt mr-1"></i> Recepción: {{ $fecha_servicio }}
            </p>
        </div>
    </div>

    @if (count($cultivos_data) > 1)
        <div class="w-full flex border-b border-slate-200 mb-6 bg-white p-2 rounded-xl shadow-sm gap-2">
            @foreach ($cultivos_data as $id_analisis => $cData)
                <button wire:click="cambiarPestaña({{ $id_analisis }})"
                    class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all flex items-center gap-2
                    {{ $analisis_activo_id === $id_analisis ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fas fa-flask"></i> {{ $cData['nombre_examen'] }}
                </button>
            @endforeach
        </div>
    @endif

    @foreach ($cultivos_data as $id_analisis => $cData)
        @if ($analisis_activo_id === $id_analisis)
            <div class="w-full grid grid-cols-12 gap-6" wire:key="cultivo-pane-{{ $id_analisis }}">

                <div class="col-span-5 space-y-6">
                    <div class="bg-white shadow-sm rounded-2xl p-6 border border-slate-200 flex flex-col h-[65vh]">
                        <h3
                            class="text-sm font-black text-slate-700 uppercase tracking-wider border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                            <i class="fas fa-history text-slate-400"></i> Bitácora de Evolución Diaria
                        </h3>

                        <div class="flex-1 overflow-y-auto space-y-4 pr-2 custom-scrollbar text-xs">
                            @forelse($cData['evoluciones'] as $evo)
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 relative">
                                    <div
                                        class="flex justify-between items-center text-[10px] text-slate-400 mb-2 font-bold uppercase tracking-wider">
                                        <span>Registro Automático</span>
                                        <span>{{ \Carbon\Carbon::parse($evo['created_at'])->format('d/m H:i') }}</span>
                                    </div>
                                    <p class="text-slate-700 leading-relaxed font-medium">{{ $evo['observacion'] }}</p>
                                </div>
                            @empty
                                <div
                                    class="h-full flex flex-col items-center justify-center text-slate-400 py-12 opacity-70">
                                    <i class="fas fa-clock text-4xl mb-3"></i>
                                    <p class="text-center font-medium">Sin novedades registradas.<br>Inicie la línea de
                                        tiempo abajo.</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="pt-4 border-t border-slate-100 mt-4">
                            <div class="flex gap-2">
                                <input type="text" wire:model="cultivos_data.{{ $id_analisis }}.nueva_observacion"
                                    wire:keydown.enter="agregarEvolucion({{ $id_analisis }})"
                                    class="flex-1 bg-white border border-slate-300 text-slate-900 text-xs rounded-xl focus:ring-emerald-500 focus:border-emerald-500 p-3 shadow-inner"
                                    placeholder="Ej: Día 1: Sin desarrollo bacteriano a las 24h...">
                                <button type="button" wire:click="agregarEvolucion({{ $id_analisis }})"
                                    class="bg-slate-800 hover:bg-black text-white px-5 rounded-xl text-xs font-bold transition-colors shadow-sm shrink-0">
                                    Anotar
                                </button>
                            </div>
                            @if (session()->has('mensaje_bitacora'))
                                <span class="text-[11px] text-emerald-600 font-bold mt-2 block animate-pulse">
                                    <i class="fas fa-check"></i> {{ session('mensaje_bitacora') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-span-7 space-y-6" x-data="{ estado: '{{ $cData['estado_cultivo'] }}' }">
                    <div class="bg-white shadow-sm rounded-2xl p-6 border border-slate-200">

                        <h2
                            class="text-lg font-black text-slate-800 mb-5 pb-3 border-b border-slate-100 flex justify-between items-center">
                            <span>Examen: <span class="text-emerald-700">{{ $cData['nombre_examen'] }}</span></span>
                        </h2>

                        <div class="grid grid-cols-2 gap-5 mb-6">
                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Estado
                                    Clínico del Cultivo</label>
                                <select wire:model.live="cultivos_data.{{ $id_analisis }}.estado_cultivo"
                                    x-model="estado"
                                    class="block w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm font-bold rounded-xl focus:ring-emerald-500 focus:border-emerald-500 p-3 transition-colors cursor-pointer shadow-sm">
                                    <option value="en_incubacion">🔬 En Incubación (Proceso Abierto)</option>
                                    <option value="negativo">❌ Negativo (Sin desarrollo bacteriano)</option>
                                    <option value="positivo_identificado">🧫 Positivo (Aisla Cepa Bacteriana)</option>
                                </select>
                            </div>

                            <div x-show="estado === 'positivo_identificado'" x-cloak
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                x-transition:enter-end="opacity-100 transform translate-y-0">
                                <label
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Germen
                                    / Cepa Aislada</label>
                                <input type="text" wire:model="cultivos_data.{{ $id_analisis }}.cepa_bacteriana"
                                    class="block w-full bg-white border border-slate-300 rounded-xl p-3 text-sm font-bold text-slate-900 focus:ring-emerald-500 focus:border-emerald-500 shadow-inner"
                                    placeholder="Ej: Escherichia coli, Staphylococcus aureus...">
                            </div>
                        </div>

                        <div x-show="estado === 'positivo_identificado'" x-cloak
                            x-transition:enter="transition ease-out duration-300 delay-75"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            class="pt-5 border-t border-slate-100">

                            <h3
                                class="text-xs font-black text-slate-700 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <i class="fas fa-shield-virus text-emerald-600"></i> Prueba de Susceptibilidad
                                Antibiótica (Antibiograma)
                            </h3>

                            <div
                                class="max-h-[35vh] overflow-y-auto pr-2 custom-scrollbar border border-slate-200 rounded-xl">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr
                                            class="bg-slate-100 text-[10px] font-black text-slate-500 border-b border-slate-200 uppercase tracking-wider sticky top-0 z-10">
                                            <th class="p-3">Antibiótico</th>
                                            <th class="p-3 text-center w-48">Nivel de Susceptibilidad</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                                        @foreach ($antibioticos_disponibles as $anti)
                                            <tr class="hover:bg-slate-50"
                                                wire:key="anti-{{ $id_analisis }}-{{ $anti->id }}">
                                                <td class="p-3 font-bold text-slate-800">
                                                    {{ $anti->nombre_antibiotico }}</td>
                                                <td class="p-3">
                                                    <div class="flex justify-center gap-2">
                                                        <label class="cursor-pointer">
                                                            <input type="radio"
                                                                wire:model="cultivos_data.{{ $id_analisis }}.antibiograma.{{ $anti->id }}"
                                                                value="S" class="sr-only peer">
                                                            <span
                                                                class="w-8 h-7 flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-400 text-[10px] font-black peer-checked:bg-emerald-100 peer-checked:border-emerald-400 peer-checked:text-emerald-700 transition-all shadow-sm">S</span>
                                                        </label>
                                                        <label class="cursor-pointer">
                                                            <input type="radio"
                                                                wire:model="cultivos_data.{{ $id_analisis }}.antibiograma.{{ $anti->id }}"
                                                                value="I" class="sr-only peer">
                                                            <span
                                                                class="w-8 h-7 flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-400 text-[10px] font-black peer-checked:bg-amber-100 peer-checked:border-amber-400 peer-checked:text-amber-700 transition-all shadow-sm">I</span>
                                                        </label>
                                                        <label class="cursor-pointer">
                                                            <input type="radio"
                                                                wire:model="cultivos_data.{{ $id_analisis }}.antibiograma.{{ $anti->id }}"
                                                                value="R" class="sr-only peer">
                                                            <span
                                                                class="w-8 h-7 flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-400 text-[10px] font-black peer-checked:bg-red-100 peer-checked:border-red-400 peer-checked:text-red-700 transition-all shadow-sm">R</span>
                                                        </label>
                                                        <button type="button"
                                                            wire:click="$set('cultivos_data.{{ $id_analisis }}.antibiograma.{{ $anti->id }}', '')"
                                                            class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-slate-200 text-slate-400 hover:text-slate-600 transition-colors">
                                                            <i class="fas fa-times text-[10px]"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-end gap-3">
                            <button type="button" wire:click="previsualizarCultivo({{ $id_analisis }})"
                                wire:loading.attr="disabled"
                                class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-sm rounded-xl shadow-md transition-colors flex items-center gap-2">
                                <span wire:loading.remove wire:target="previsualizarCultivo({{ $id_analisis }})">
                                    <i class="fas fa-search"></i> Previsualizar y Reportar
                                </span>
                                <span wire:loading wire:target="previsualizarCultivo({{ $id_analisis }})">
                                    <i class="fas fa-spinner fa-spin"></i> Cargando...
                                </span>
                            </button>
                        </div>

                    </div>
                </div>

            </div>
        @endif
    @endforeach

    <div class="fixed inset-0 z-50 overflow-y-auto {{ $mostrarModalPreview ? '' : 'hidden' }}"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 backdrop-blur-sm transition-opacity"
                wire:click="cerrarModal"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle max-w-2xl w-full relative z-10 animate-fadeIn border border-slate-200">

                @if ($analisis_a_guardar !== null && isset($cultivos_data[$analisis_a_guardar]))
                    @php
                        $previewData = $cultivos_data[$analisis_a_guardar];
                    @endphp
                    <div class="bg-emerald-600 px-6 py-4 flex items-center justify-between">
                        <h3 class="text-lg font-black text-white flex items-center gap-2" id="modal-title">
                            <i class="fas fa-search"></i> Previsualización: {{ $previewData['nombre_examen'] }}
                        </h3>
                        <button wire:click="cerrarModal" class="text-emerald-200 hover:text-white transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <div class="bg-white px-6 pt-5 pb-4">
                        <p class="text-sm text-slate-500 mb-4 font-medium">Verifique el diagnóstico microbiológico
                            antes de firmar el documento oficial.</p>

                        <div class="bg-slate-50 rounded-xl border border-slate-200 p-5 mb-5 text-sm shadow-sm">
                            <p class="mb-3 text-slate-600"><strong>Estado Final:</strong>
                                @if ($previewData['estado_cultivo'] === 'en_incubacion')
                                    <span class="text-amber-600 font-black tracking-wide ml-1">EN INCUBACIÓN (Borrador
                                        Parcial)</span>
                                @elseif($previewData['estado_cultivo'] === 'negativo')
                                    <span class="text-slate-800 font-black tracking-wide ml-1">NEGATIVO (Sin
                                        Desarrollo)</span>
                                @else
                                    <span class="text-emerald-700 font-black uppercase tracking-wide ml-1">Positivo
                                        Identificado</span>
                                @endif
                            </p>

                            @if ($previewData['estado_cultivo'] === 'positivo_identificado')
                                <p class="mb-4 text-slate-600"><strong>Germen Aislado:</strong> <span
                                        class="font-black text-slate-900 ml-1">{{ $previewData['cepa_bacteriana'] ?: 'No especificado' }}</span>
                                </p>

                                @php
                                    $antibiogramaFiltrado = array_filter($previewData['antibiograma']);
                                    $conteoAntibiograma = count($antibiogramaFiltrado);
                                @endphp

                                <div class="mt-4 pt-4 border-t border-slate-200">
                                    <p class="text-slate-600 mb-3 font-medium"><strong>Antibiograma:</strong> Se
                                        testearon <span
                                            class="font-black text-slate-800">{{ $conteoAntibiograma }}</span>
                                        antibióticos.</p>

                                    @if ($conteoAntibiograma > 0)
                                        <div
                                            class="bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm">
                                            <table class="w-full text-left text-xs">
                                                <thead
                                                    class="bg-slate-100 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-black text-[10px]">
                                                    <tr>
                                                        <th class="py-3 px-4">Antibiótico Evaluado</th>
                                                        <th class="py-3 px-4 text-center">Interpretación Clínica</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100">
                                                    @foreach ($antibiogramaFiltrado as $antiId => $resultado)
                                                        @php
                                                            $antibiotico = $antibioticos_disponibles->firstWhere(
                                                                'id',
                                                                $antiId,
                                                            );
                                                            $nombreAnti = $antibiotico
                                                                ? $antibiotico->nombre_antibiotico
                                                                : 'Desconocido';

                                                            $claseColor = '';
                                                            $textoResultado = '';

                                                            if ($resultado === 'S') {
                                                                $claseColor =
                                                                    'bg-emerald-50 text-emerald-700 border-emerald-200';
                                                                $textoResultado = 'Sensible (S)';
                                                            } elseif ($resultado === 'I') {
                                                                $claseColor =
                                                                    'bg-amber-50 text-amber-700 border-amber-200';
                                                                $textoResultado = 'Intermedio (I)';
                                                            } elseif ($resultado === 'R') {
                                                                $claseColor = 'bg-red-50 text-red-700 border-red-200';
                                                                $textoResultado = 'Resistente (R)';
                                                            }
                                                        @endphp
                                                        <tr class="hover:bg-slate-50 transition-colors">
                                                            <td class="py-3 px-4 font-bold text-slate-800">
                                                                {{ $nombreAnti }}</td>
                                                            <td class="py-3 px-4 text-center">
                                                                <span
                                                                    class="inline-block px-3 py-1 rounded-md text-[10px] font-black uppercase tracking-wider border shadow-sm {{ $claseColor }}">
                                                                    {{ $textoResultado }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="bg-emerald-50/50 border border-emerald-100 rounded-xl p-5 mt-5">
                            <h4
                                class="text-xs font-black text-emerald-800 uppercase tracking-wider mb-2 flex items-center gap-2">
                                <i class="fas fa-envelope-open-text"></i> Notificación de Resultados
                            </h4>
                            <p class="text-[11px] text-slate-600 mb-4 font-medium">
                                Para poder finalizar y firmar digitalmente el informe, el sistema requiere registrar
                                <strong>por lo menos un correo electrónico</strong> válido (sea del Paciente/Tutor o del
                                Médico).
                            </p>

                            @if (session()->has('error_email'))
                                <div
                                    class="mb-4 bg-red-50 border border-red-200 text-red-700 p-3 rounded-lg text-xs font-bold animate-pulse flex items-center gap-2 shadow-sm">
                                    <i class="fas fa-exclamation-triangle"></i> {{ session('error_email') }}
                                </div>
                            @endif

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs text-slate-700 font-bold mb-1.5">
                                        Correo del {{ ucfirst($tipo_email_paciente) }}:
                                    </label>
                                    <input type="email" wire:model="email_paciente"
                                        class="block w-full bg-white border border-slate-300 rounded-xl p-3 text-sm font-medium focus:ring-emerald-500 focus:border-emerald-500 shadow-sm transition-colors"
                                        placeholder="paciente@ejemplo.com">
                                </div>
                                <div>
                                    <label class="block text-xs text-slate-700 font-bold mb-1.5">
                                        Correo Médico Solicitante:
                                    </label>
                                    <input type="email" wire:model="email_medico"
                                        class="block w-full bg-white border border-slate-300 rounded-xl p-3 text-sm font-medium focus:ring-emerald-500 focus:border-emerald-500 shadow-sm transition-colors"
                                        placeholder="medico@ejemplo.com">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div
                        class="bg-slate-100 px-6 py-4 border-t border-slate-200 flex flex-row items-center justify-end gap-3">
                        <button type="button" wire:click="cerrarModal"
                            class="w-auto px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition-colors shadow-sm text-sm whitespace-nowrap">
                            Volver a Editar
                        </button>

                        <button type="button" wire:click="confirmarYEnviar" wire:loading.attr="disabled"
                            class="w-auto px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-xl shadow-md hover:shadow-emerald-500/30 transition-colors flex justify-center items-center gap-2 disabled:opacity-70 text-sm whitespace-nowrap">
                            <span wire:loading.remove wire:target="confirmarYEnviar">
                                <i class="fas fa-check-double"></i> Firmar y Guardar
                            </span>
                            <span wire:loading wire:target="confirmarYEnviar">
                                <i class="fas fa-spinner fa-spin"></i> Procesando...
                            </span>
                        </button>
                    </div>
                @endif

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

        [x-cloak] {
            display: none !important;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.2s ease-out forwards;
        }
    </style>
</div>
