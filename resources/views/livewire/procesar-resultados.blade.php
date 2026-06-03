<div class="max-w-6xl mx-auto py-8 sm:px-6 lg:px-8">

    <!-- ENCABEZADO (Optimizado con flex-wrap) -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 flex items-center gap-3 tracking-wide">
                <i class="fas fa-edit text-indigo-600"></i>
                Transcripción de Resultados Clínicos
            </h1>
            <p class="text-sm text-slate-500 mt-1 font-medium italic">El sistema validará automáticamente los rangos
                fisiológicos y generará alertas.</p>
        </div>
        <a href="{{ route('laboratorio.panel') }}" wire:navigate
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-300 rounded-xl shadow-sm text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors whitespace-nowrap">
            <i class="fas fa-arrow-left"></i> Volver al Kanban
        </a>
    </div>

    <!-- NOTIFICACIONES AUTO-OCULTABLES -->
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

    <!-- TARJETA DEL PACIENTE (Optimizada) -->
    <div
        class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 mb-6 flex flex-wrap items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-1.5 h-full bg-indigo-500"></div>

        <div class="flex items-center gap-4">
            <div
                class="w-14 h-14 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-full flex items-center justify-center text-xl shadow-inner font-black shrink-0">
                {{ substr($paciente_nombre, 0, 2) }}
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Paciente</p>
                <h2 class="text-lg font-black text-slate-800 leading-tight">{{ $paciente_nombre }}</h2>
                <p class="text-xs text-slate-500 font-medium mt-1"><i class="fas fa-id-card text-slate-400"></i> CI:
                    {{ $paciente_ci }} | Sexo: {{ $paciente_sexo }}</p>
            </div>
        </div>

        <div class="text-left sm:text-right bg-slate-50 p-3 rounded-xl border border-slate-200 min-w-[200px]">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Orden de Servicio</p>
            <p class="text-lg font-black text-indigo-700 tracking-tight">{{ $servicio->codigo_unico }}</p>
            <p class="text-xs text-slate-500 font-medium mt-1">
                <i class="far fa-calendar-alt mr-1"></i> {{ $fecha_servicio }}
            </p>
        </div>
    </div>

    <!-- TABLA DE PARÁMETROS -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center gap-2">
            <i class="fas fa-flask text-indigo-500"></i>
            <h3 class="text-sm font-black text-slate-700 uppercase tracking-wider">Detalle de Parámetros Analíticos</h3>
        </div>

        <div>
            <div class="p-6">
                @if (count($valores) === 0)
                    <div class="text-center py-12 opacity-70">
                        <i class="fas fa-bacteria text-5xl text-slate-300 mb-4"></i>
                        <h3 class="text-lg font-bold text-slate-700">Esta orden solo contiene Cultivos Microbiológicos.
                        </h3>
                        <p class="text-sm font-medium text-slate-500 mt-2">Dirígete a la columna "En Proceso" y
                            selecciona el botón verde <br>"Gestionar Incubación" para proceder.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[600px]">
                            <thead>
                                <tr class="border-b-2 border-slate-200">
                                    <th class="pb-3 font-black text-slate-500 text-[11px] uppercase tracking-wider">
                                        Examen Solicitado</th>
                                    <th
                                        class="pb-3 font-black text-slate-500 text-[11px] uppercase tracking-wider w-1/3">
                                        Valor Registrado</th>
                                    <th
                                        class="pb-3 font-black text-slate-500 text-[11px] uppercase tracking-wider w-1/4">
                                        Evaluación / Alerta</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($valores as $analisis_id => $data)
                                    <tr class="hover:bg-slate-50 transition-colors group"
                                        wire:key="fila-analisis-{{ $analisis_id }}">

                                        <td class="py-5 align-middle pr-4">
                                            <p class="font-bold text-slate-800">{{ $data['nombre'] }}</p>
                                            @if ($data['tipo'] === 'numerico' && $data['unidad'])
                                                <span
                                                    class="inline-block mt-1 bg-slate-100 text-slate-600 text-[10px] px-2 py-0.5 rounded font-bold border border-slate-200">
                                                    Medida: {{ $data['unidad'] }}
                                                </span>
                                            @else
                                                <span
                                                    class="inline-block mt-1 bg-indigo-50 text-indigo-700 text-[10px] px-2 py-0.5 rounded font-bold border border-indigo-200">
                                                    Prueba Cualitativa
                                                </span>
                                            @endif
                                        </td>

                                        <td class="py-5 align-middle pr-4">
                                            @if ($data['tipo'] === 'cualitativo' && count($data['opciones_cualitativas']) > 0)
                                                <select wire:model.live="valores.{{ $analisis_id }}.valor"
                                                    class="block w-full bg-slate-50 border border-slate-300 text-slate-900 rounded-xl p-3 text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500 transition-colors cursor-pointer shadow-sm">
                                                    <option value="">Seleccione resultado...</option>
                                                    @foreach ($data['opciones_cualitativas'] as $index => $opcion)
                                                        <option value="{{ $opcion }}"
                                                            wire:key="opt-{{ $analisis_id }}-{{ $index }}">
                                                            {{ $opcion }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <div class="relative">
                                                    <input type="text"
                                                        wire:model.live.debounce.500ms="valores.{{ $analisis_id }}.valor"
                                                        class="block w-full bg-white border border-slate-300 rounded-xl p-3 text-slate-900 text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500 transition-colors shadow-inner"
                                                        placeholder="Ingrese resultado..." autocomplete="off">

                                                    @if ($data['tipo'] === 'numerico' && $data['unidad'])
                                                        <div
                                                            class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                            <span
                                                                class="text-slate-400 font-bold text-xs">{{ $data['unidad'] }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            @error('valores.' . $analisis_id . '.valor')
                                                <span class="text-red-500 text-[10px] mt-1.5 block font-bold"><i
                                                        class="fas fa-exclamation-triangle"></i> {{ $message }}</span>
                                            @enderror
                                        </td>

                                        <td class="py-5 align-middle">
                                            @if ($data['tipo'] === 'numerico')
                                                <select wire:model="valores.{{ $analisis_id }}.alerta"
                                                    class="block w-full bg-white border border-slate-300 rounded-xl p-3 text-sm font-black focus:ring-indigo-500 focus:border-indigo-500 transition-colors shadow-sm
                                                    {{ $data['alerta'] === 'normal' ? 'text-emerald-600' : '' }}
                                                    {{ $data['alerta'] === 'alto' || $data['alerta'] === 'bajo' ? 'text-amber-600 bg-amber-50' : '' }}
                                                    {{ $data['alerta'] === 'critico' ? 'text-red-600 bg-red-50' : '' }}">
                                                    <option value="normal">✅ Normal</option>
                                                    <option value="alto">⬆️ Alto</option>
                                                    <option value="bajo">⬇️ Bajo</option>
                                                    <option value="critico">🚨 Crítico</option>
                                                </select>
                                            @else
                                                @php
                                                    $nombreNormalizado = strtolower($data['nombre']);
                                                    $esEstadoFisiologico =
                                                        str_contains($nombreNormalizado, 'embarazo') ||
                                                        str_contains($nombreNormalizado, 'grupo sanguíneo') ||
                                                        $data['ref_cualitativa'] === 'N/A';
                                                @endphp

                                                @if ($esEstadoFisiologico)
                                                    <div
                                                        class="block w-full bg-slate-100 text-slate-700 border border-slate-200 rounded-xl p-3 text-sm font-bold text-center shadow-inner">
                                                        <i class="fas fa-check-circle mr-1 text-emerald-500"></i>
                                                        Parámetro Registrado
                                                    </div>
                                                @else
                                                    <select wire:model="valores.{{ $analisis_id }}.alerta"
                                                        class="block w-full bg-white border border-slate-300 rounded-xl p-3 text-sm font-black focus:ring-indigo-500 focus:border-indigo-500 transition-colors shadow-sm
                                                        {{ $data['alerta'] === 'normal' ? 'text-emerald-600' : '' }}
                                                        {{ $data['alerta'] === 'critico' ? 'text-red-600 bg-red-50' : '' }}">
                                                        <option value="normal">✅ No Reactivo / Normal</option>
                                                        <option value="critico">🚨 Reactivo / Atención</option>
                                                    </select>

                                                    @if ($data['ref_cualitativa'])
                                                        <div class="mt-2 text-center">
                                                            <span
                                                                class="text-[10px] text-slate-500 font-bold bg-slate-50 px-2.5 py-1 rounded-md border border-slate-200 block w-full truncate">
                                                                Esperado: <span
                                                                    class="text-emerald-600">{{ $data['ref_cualitativa'] }}</span>
                                                            </span>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- BOTONES DE ACCIÓN (Blindados contra el lag de Livewire Navigate) -->
            <div
                class="bg-slate-50 px-6 py-5 border-t border-slate-200 flex flex-wrap items-center justify-between gap-4">

                <a href="{{ route('laboratorio.panel') }}" wire:navigate
                    class="px-5 py-3 bg-white border border-slate-300 text-slate-700 font-bold rounded-xl hover:bg-slate-100 transition-colors shadow-sm text-sm whitespace-nowrap">
                    Cancelar
                </a>

                @if (count($valores) > 0)
                    <div class="flex flex-wrap items-center gap-3">
                        <button type="button" wire:click="guardarAvanceParcial" wire:loading.attr="disabled"
                            class="px-6 py-3 bg-white border-2 border-indigo-200 text-indigo-700 hover:bg-indigo-50 font-black rounded-xl shadow-sm transition-colors flex items-center gap-2 disabled:opacity-70 text-sm whitespace-nowrap">
                            <span wire:loading.remove wire:target="guardarAvanceParcial">
                                <i class="fas fa-save"></i> Guardar Avance
                            </span>
                            <span wire:loading wire:target="guardarAvanceParcial">
                                <i class="fas fa-spinner fa-spin"></i> Guardando...
                            </span>
                        </button>

                        <button type="button" wire:click="previsualizarResultados" wire:loading.attr="disabled"
                            class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-xl shadow-md hover:shadow-indigo-500/30 transition-colors flex items-center gap-2 disabled:opacity-70 text-sm whitespace-nowrap">
                            <span wire:loading.remove wire:target="previsualizarResultados">
                                <i class="fas fa-file-signature"></i> Previsualizar y Finalizar
                            </span>
                            <span wire:loading wire:target="previsualizarResultados">
                                <i class="fas fa-spinner fa-spin"></i> Validando...
                            </span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- MODAL DE PREVISUALIZACIÓN -->
    <div class="fixed inset-0 z-50 overflow-y-auto {{ $mostrarModalPreview ? '' : 'hidden' }}"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 backdrop-blur-sm transition-opacity"
                wire:click="cerrarModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full relative z-10 animate-fadeIn border border-slate-200">

                <div class="bg-indigo-600 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-lg font-black text-white flex items-center gap-2" id="modal-title">
                        <i class="fas fa-search-plus"></i> Confirmación de Firmas
                    </h3>
                    <button wire:click="cerrarModal" class="text-indigo-200 hover:text-white transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="bg-white px-6 pt-5 pb-4">
                    <div class="bg-indigo-50 border border-indigo-100 p-4 rounded-xl mb-5 flex items-start gap-3">
                        <i class="fas fa-info-circle text-indigo-500 text-lg mt-0.5"></i>
                        <div>
                            <h4 class="font-bold text-indigo-900 text-sm">Aprobación Final</h4>
                            <p class="text-xs text-indigo-700 mt-1">Al hacer clic en "Confirmar y Firmar", este reporte
                                se marcará como cerrado y no podrá ser alterado, enviando el PDF automáticamente al
                                paciente.</p>
                        </div>
                    </div>

                    <div class="bg-slate-50 rounded-xl border border-slate-200 overflow-hidden mb-5">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr
                                    class="border-b border-slate-200 bg-slate-100 text-slate-600 font-black text-[10px] uppercase tracking-wider">
                                    <th class="p-3">Examen Clínico</th>
                                    <th class="p-3 text-center">Valor Obtenido</th>
                                    <th class="p-3 text-center">Estado Médico</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @forelse ($resultados_a_previsualizar as $id => $res)
                                    <tr class="bg-white" wire:key="prev-{{ $id }}">
                                        <td class="p-3 font-bold text-slate-800 text-xs">{{ $res['nombre'] ?? '' }}
                                        </td>
                                        <td class="p-3 text-center font-black text-indigo-700">
                                            {{ $res['valor'] ?? '' }} <span
                                                class="text-[10px] text-slate-400 font-bold ml-1">{{ $res['unidad'] ?? '' }}</span>
                                        </td>
                                        <td class="p-3 text-center">
                                            <span
                                                class="px-2 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-wider
                                                {{ ($res['alerta'] ?? '') === 'normal' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : '' }}
                                                {{ ($res['alerta'] ?? '') === 'alto' || ($res['alerta'] ?? '') === 'bajo' ? 'bg-amber-100 text-amber-700 border border-amber-200' : '' }}
                                                {{ ($res['alerta'] ?? '') === 'critico' ? 'bg-red-100 text-red-700 border border-red-200' : '' }}">
                                                {{ $res['alerta'] ?? '' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="p-4 text-center text-slate-500 font-medium text-xs">
                                            Sin datos.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-5">
                        <h4
                            class="text-[11px] font-black text-slate-700 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <i class="fas fa-paper-plane text-slate-400"></i> Destinatarios del Reporte PDF
                        </h4>

                        @if (session()->has('error_email'))
                            <div
                                class="mb-4 bg-red-50 border border-red-200 text-red-700 p-3 rounded-lg text-xs font-bold animate-pulse flex gap-2">
                                <i class="fas fa-exclamation-triangle mt-0.5"></i>
                                <span>{{ session('error_email') }}</span>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-slate-600 font-bold mb-1.5">
                                    Correo del {{ ucfirst($tipo_email_paciente) }}:
                                </label>
                                <input type="email" wire:model="email_paciente"
                                    class="block w-full bg-white border border-slate-300 rounded-lg p-2.5 text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                                    placeholder="paciente@ejemplo.com">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-600 font-bold mb-1.5">
                                    Correo del Médico Solicitante:
                                </label>
                                <input type="email" wire:model="email_medico"
                                    class="block w-full bg-white border border-slate-300 rounded-lg p-2.5 text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                                    placeholder="medico@ejemplo.com">
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-slate-100 px-6 py-4 border-t border-slate-200 flex flex-wrap items-center justify-end gap-3">
                    <button type="button" wire:click="cerrarModal"
                        class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition-colors shadow-sm text-sm whitespace-nowrap">
                        Cancelar y Editar
                    </button>

                    <button type="button" wire:click="confirmarYEnviar" wire:loading.attr="disabled"
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-xl shadow-md hover:shadow-indigo-500/30 transition-colors flex items-center gap-2 text-sm whitespace-nowrap">
                        <span wire:loading.remove wire:target="confirmarYEnviar">
                            <i class="fas fa-signature"></i> Confirmar, Firmar y Enviar
                        </span>
                        <span wire:loading wire:target="confirmarYEnviar">
                            <i class="fas fa-spinner fa-spin"></i> Generando PDF...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
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
