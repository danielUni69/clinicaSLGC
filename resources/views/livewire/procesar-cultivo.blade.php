<div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-bacteria text-emerald-600"></i>
                Estación de Trabajo de Microbiología
            </h1>
            <p class="text-sm text-gray-500 mt-1">Gestione líneas de tiempo de incubación, bitácoras de desarrollo y
                antibiogramas.</p>
        </div>
        <a href="{{ route('laboratorio.panel') }}" wire:navigate
            class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left"></i> Panel de Control
        </a>
    </div>

    @if (session()->has('mensaje'))
        <div
            class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl shadow-sm flex items-start gap-3">
            <i class="fas fa-check-circle text-emerald-500 mt-1 text-lg"></i>
            <span class="text-sm font-medium">{{ session('mensaje') }}</span>
        </div>
    @endif

    <div
        class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500"></div>
        <div class="flex items-center gap-4">
            <div
                class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center text-xl shadow-inner">
                <i class="fas fa-id-card"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Paciente Asegurado</p>
                <h2 class="text-lg font-bold text-gray-900">{{ $paciente_nombre }}</h2>
                <p class="text-sm text-gray-500">CI: {{ $paciente_ci }} | Sexo: {{ $paciente_sexo }}</p>
            </div>
        </div>
        <div class="flex flex-col md:text-right">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Orden de Servicio</p>
            <p class="text-lg font-black text-gray-800 font-mono text-emerald-700">{{ $servicio->codigo_unico }}</p>
            <p class="text-sm text-gray-500 border-t border-gray-100 mt-1 pt-1">
                <i class="far fa-calendar-alt mr-1"></i> Recepción: {{ $fecha_servicio }}
            </p>
        </div>
    </div>

    @if (count($cultivos_data) > 1)
        <div class="flex border-b border-gray-200 mb-6 bg-white p-2 rounded-xl shadow-sm gap-2">
            @foreach ($cultivos_data as $id_analisis => $cData)
                <button wire:click="cambiarPestaña({{ $id_analisis }})"
                    class="px-4 py-2.5 rounded-lg text-sm font-bold transition-all flex items-center gap-2
                    {{ $analisis_activo_id === $id_analisis ? 'bg-emerald-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-flask"></i> {{ $cData['nombre_examen'] }}
                </button>
            @endforeach
        </div>
    @endif

    @foreach ($cultivos_data as $id_analisis => $cData)
        @if ($analisis_activo_id === $id_analisis)
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6" wire:key="cultivo-pane-{{ $id_analisis }}">

                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white shadow-sm rounded-2xl p-6 border border-gray-100 flex flex-col h-[65vh]">
                        <h3
                            class="text-sm font-bold text-gray-700 uppercase tracking-wider border-b pb-3 mb-4 flex items-center gap-2">
                            <i class="fas fa-history text-gray-400"></i> Bitácora de Evolución Diaria
                        </h3>

                        <div class="flex-1 overflow-y-auto space-y-4 pr-2 custom-scrollbar text-xs">
                            @forelse($cData['evoluciones'] as $evo)
                                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 relative">
                                    <div
                                        class="flex justify-between items-center text-[10px] text-gray-400 mb-1 font-semibold">
                                        <span>REGISTRO AUTOMÁTICO</span>
                                        <span>{{ \Carbon\Carbon::parse($evo['created_at'])->format('d/m H:i') }}</span>
                                    </div>
                                    <p class="text-gray-700 leading-relaxed font-medium">{{ $evo['observacion'] }}</p>
                                </div>
                            @empty
                                <div class="h-full flex flex-col items-center justify-center text-gray-400 py-12">
                                    <i class="fas fa-clock text-3xl mb-2 opacity-20"></i>
                                    <p class="text-center">Sin novedades registradas.<br>Inicie la línea de tiempo
                                        abajo.</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="pt-4 border-t border-gray-100 mt-4">
                            <div class="flex gap-2">
                                <input type="text" wire:model="cultivos_data.{{ $id_analisis }}.nueva_observacion"
                                    wire:keydown.enter="agregarEvolucion({{ $id_analisis }})"
                                    class="flex-1 bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-emerald-500 focus:border-emerald-500 p-2.5"
                                    placeholder="Ej: Día 1: Sin desarrollo bacteriano a las 24h...">
                                <button type="button" wire:click="agregarEvolucion({{ $id_analisis }})"
                                    class="bg-gray-800 hover:bg-black text-white px-4 rounded-xl text-xs font-bold transition-colors">
                                    Anotar
                                </button>
                            </div>
                            @if (session()->has('mensaje_bitacora'))
                                <span class="text-[11px] text-emerald-600 font-bold mt-1.5 block">
                                    <i class="fas fa-check"></i> {{ session('mensaje_bitacora') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-7 space-y-6" x-data="{ estado: '{{ $cData['estado_cultivo'] }}' }">
                    <div class="bg-white shadow-sm rounded-2xl p-6 border border-gray-100">

                        <h2
                            class="text-lg font-black text-gray-800 mb-5 pb-3 border-b border-gray-100 flex justify-between items-center">
                            <span>Examen: <span class="text-emerald-700">{{ $cData['nombre_examen'] }}</span></span>
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label
                                    class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Estado
                                    Clínico del Cultivo</label>

                                <select wire:model.live="cultivos_data.{{ $id_analisis }}.estado_cultivo"
                                    x-model="estado"
                                    class="block w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm font-medium rounded-xl focus:ring-emerald-500 focus:border-emerald-500 p-3 transition-colors cursor-pointer">
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
                                    class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Germen
                                    / Cepa Bacteriana Aislada</label>
                                <input type="text" wire:model="cultivos_data.{{ $id_analisis }}.cepa_bacteriana"
                                    class="block w-full bg-white border border-gray-300 rounded-xl p-3 text-sm font-bold text-gray-900 focus:ring-emerald-500 focus:border-emerald-500 shadow-inner"
                                    placeholder="Ej: Escherichia coli, Staphylococcus aureus...">
                            </div>
                        </div>

                        <div x-show="estado === 'positivo_identificado'" x-cloak
                            x-transition:enter="transition ease-out duration-300 delay-75"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            class="pt-5 border-t border-gray-100">

                            <h3
                                class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <i class="fas fa-shield-virus text-emerald-600"></i> Prueba de Susceptibilidad
                                Antibiótica (Antibiograma)
                            </h3>

                            <div
                                class="max-h-[35vh] overflow-y-auto pr-2 custom-scrollbar border border-gray-100 rounded-xl">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr
                                            class="bg-gray-50 text-[10px] font-bold text-gray-500 border-b uppercase tracking-wider sticky top-0 z-10">
                                            <th class="p-3">Antibiótico</th>
                                            <th class="p-3 text-center w-48">Nivel de Susceptibilidad</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 text-xs font-semibold text-gray-700">
                                        @foreach ($antibioticos_disponibles as $anti)
                                            <tr class="hover:bg-gray-50/50"
                                                wire:key="anti-{{ $id_analisis }}-{{ $anti->id }}">
                                                <td class="p-3 font-bold text-gray-800">{{ $anti->nombre_antibiotico }}
                                                </td>
                                                <td class="p-3">
                                                    <div class="flex justify-center gap-2">
                                                        <label class="cursor-pointer">
                                                            <input type="radio"
                                                                wire:model="cultivos_data.{{ $id_analisis }}.antibiograma.{{ $anti->id }}"
                                                                value="S" class="sr-only peer">
                                                            <span
                                                                class="w-8 h-7 flex items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-400 text-[10px] font-black peer-checked:bg-green-100 peer-checked:border-green-400 peer-checked:text-green-700 transition-all">S</span>
                                                        </label>
                                                        <label class="cursor-pointer">
                                                            <input type="radio"
                                                                wire:model="cultivos_data.{{ $id_analisis }}.antibiograma.{{ $anti->id }}"
                                                                value="I" class="sr-only peer">
                                                            <span
                                                                class="w-8 h-7 flex items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-400 text-[10px] font-black peer-checked:bg-amber-100 peer-checked:border-amber-400 peer-checked:text-amber-700 transition-all">I</span>
                                                        </label>
                                                        <label class="cursor-pointer">
                                                            <input type="radio"
                                                                wire:model="cultivos_data.{{ $id_analisis }}.antibiograma.{{ $anti->id }}"
                                                                value="R" class="sr-only peer">
                                                            <span
                                                                class="w-8 h-7 flex items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-400 text-[10px] font-black peer-checked:bg-red-100 peer-checked:border-red-400 peer-checked:text-red-700 transition-all">R</span>
                                                        </label>
                                                        <button type="button"
                                                            wire:click="$set('cultivos_data.{{ $id_analisis }}.antibiograma.{{ $anti->id }}', '')"
                                                            class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-gray-200 text-gray-400 hover:text-gray-600 transition-colors">
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

                        <div class="mt-6 pt-5 border-t border-gray-100 flex items-center justify-end gap-3">
                            <button type="button" wire:click="previsualizarCultivo({{ $id_analisis }})"
                                wire:loading.attr="disabled"
                                class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-sm transition-colors flex items-center gap-2">
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

            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" wire:click="cerrarModal"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full relative z-10 animate-fadeIn">

                @if ($analisis_a_guardar !== null && isset($cultivos_data[$analisis_a_guardar]))
                    @php
                        $previewData = $cultivos_data[$analisis_a_guardar];
                    @endphp
                    <div class="bg-emerald-600 px-6 py-4 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2" id="modal-title">
                            <i class="fas fa-search"></i> Previsualización: {{ $previewData['nombre_examen'] }}
                        </h3>
                        <button wire:click="cerrarModal" class="text-emerald-200 hover:text-white transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <div class="bg-white px-6 pt-5 pb-4">
                        <p class="text-sm text-gray-500 mb-4">Verifique el diagnóstico microbiológico antes de firmar
                            el documento.</p>

                        <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 mb-5 text-sm">
                            <p class="mb-2 text-gray-600"><strong>Estado Final:</strong>
                                @if ($previewData['estado_cultivo'] === 'en_incubacion')
                                    <span class="text-amber-600 font-bold">En Incubación (Borrador parcial)</span>
                                @elseif($previewData['estado_cultivo'] === 'negativo')
                                    <span class="text-gray-800 font-bold">Negativo (Sin desarrollo)</span>
                                @else
                                    <span class="text-emerald-700 font-bold uppercase">Positivo</span>
                                @endif
                            </p>

                            @if ($previewData['estado_cultivo'] === 'positivo_identificado')
                                <p class="mb-3 text-gray-600"><strong>Germen Aislado:</strong> <span
                                        class="font-bold text-gray-900">{{ $previewData['cepa_bacteriana'] ?: 'No especificado' }}</span>
                                </p>

                                @php
                                    $antibiogramaFiltrado = array_filter($previewData['antibiograma']);
                                    $conteoAntibiograma = count($antibiogramaFiltrado);
                                @endphp

                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <p class="text-gray-600 mb-3"><strong>Antibiograma:</strong> Se testearon <span
                                            class="font-bold">{{ $conteoAntibiograma }}</span> antibióticos.</p>

                                    @if ($conteoAntibiograma > 0)
                                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                            <table class="w-full text-left text-xs">
                                                <thead
                                                    class="bg-gray-50 border-b border-gray-200 text-gray-500 uppercase tracking-wider">
                                                    <tr>
                                                        <th class="py-2.5 px-4 font-bold">Antibiótico</th>
                                                        <th class="py-2.5 px-4 font-bold text-center">Interpretación
                                                            Clínica</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-100">
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
                                                                    'bg-green-50 text-green-700 border-green-200';
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
                                                        <tr class="hover:bg-gray-50 transition-colors">
                                                            <td class="py-2.5 px-4 font-bold text-gray-700">
                                                                {{ $nombreAnti }}</td>
                                                            <td class="py-2.5 px-4 text-center">
                                                                <span
                                                                    class="inline-block px-2.5 py-1 rounded-md text-[10px] font-black tracking-wide border {{ $claseColor }}">
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

                        <div class="bg-emerald-50/50 border border-emerald-100 rounded-xl p-4">
                            <h4
                                class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-2 flex items-center gap-2">
                                <i class="fas fa-envelope-open-text"></i> Notificación de Resultados
                            </h4>
                            <p class="text-[11px] text-gray-600 mb-4">
                                Para poder finalizar el informe y firmarlo, el sistema requiere registrar <strong>por lo
                                    menos un correo electrónico</strong> válido (sea del Paciente/Responsable o del
                                Médico).
                            </p>

                            @if (session()->has('error_email'))
                                <div
                                    class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-3 rounded-r-lg text-xs font-bold animate-pulse">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error_email') }}
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs text-gray-700 font-bold mb-1">
                                        Correo del {{ ucfirst($tipo_email_paciente) }}:
                                    </label>
                                    <input type="email" wire:model="email_paciente"
                                        class="block w-full bg-white border border-gray-300 rounded-lg p-2.5 text-sm font-medium focus:ring-emerald-500 focus:border-emerald-500"
                                        placeholder="paciente@ejemplo.com">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-700 font-bold mb-1">
                                        Correo Médico Solicitante:
                                    </label>
                                    <input type="email" wire:model="email_medico"
                                        class="block w-full bg-white border border-gray-300 rounded-lg p-2.5 text-sm font-medium focus:ring-emerald-500 focus:border-emerald-500"
                                        placeholder="medico@ejemplo.com">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3">
                        <button type="button" wire:click="cerrarModal"
                            class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors shadow-sm text-sm">
                            Volver a Editar
                        </button>

                        <button type="button" wire:click="confirmarYEnviar" wire:loading.attr="disabled"
                            class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-sm transition-colors flex items-center gap-2 disabled:opacity-70 text-sm">
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
