<div class="max-w-5xl mx-auto py-8 sm:px-6 lg:px-8">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-edit text-purple-600"></i>
                Transcripción de Resultados Clínicos
            </h1>
            <p class="text-sm text-gray-500 mt-1">El sistema validará automáticamente los rangos y valores de referencia.
            </p>
        </div>
        <a href="{{ route('laboratorio.panel') }}" wire:navigate
            class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left"></i> Volver al Panel
        </a>
    </div>

    @if (session()->has('mensaje'))
        <div
            class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl shadow-sm flex items-start gap-3">
            <i class="fas fa-check-circle text-green-500 mt-1 text-lg"></i>
            <span class="text-sm font-medium">{{ session('mensaje') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div
            class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow-sm flex items-start gap-3">
            <i class="fas fa-exclamation-triangle text-red-500 mt-1 text-lg"></i>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div
        class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
        <div class="flex items-center gap-4">
            <div
                class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center text-xl shadow-inner">
                <i class="fas fa-user"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Paciente</p>
                <h2 class="text-lg font-bold text-gray-900">{{ $paciente_nombre }}</h2>
                <p class="text-sm text-gray-500">CI: {{ $paciente_ci }} | Sexo: {{ $paciente_sexo }}</p>
            </div>
        </div>
        <div class="flex flex-col md:text-right">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Orden de Servicio</p>
            <p class="text-lg font-black text-gray-800">{{ $servicio->codigo_unico }}</p>
            <p class="text-sm text-gray-500 border-t border-gray-100 mt-1 pt-1">
                <i class="far fa-calendar-alt mr-1"></i> {{ $fecha_servicio }}
            </p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider flex items-center gap-2">
                <i class="fas fa-microscope text-purple-500"></i> Detalle de Parámetros
            </h3>
        </div>

        <div>
            <div class="p-6">
                @if (count($valores) === 0)
                    <div class="text-center py-12">
                        <i class="fas fa-bacteria text-4xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900">Esta orden no contiene exámenes clínicos de
                            rutina.</h3>
                        <p class="text-gray-500 mt-1">Los exámenes de microbiología y cultivos se procesan de forma
                            aislada desde el botón verde del Kanban.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b-2 border-gray-100">
                                    <th class="pb-3 font-semibold text-gray-600 text-sm">Examen Solicitado</th>
                                    <th class="pb-3 font-semibold text-gray-600 text-sm w-1/3">Valor Registrado</th>
                                    <th class="pb-3 font-semibold text-gray-600 text-sm w-1/4">Evaluación / Alerta</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($valores as $analisis_id => $data)
                                    <tr class="hover:bg-gray-50 transition-colors group"
                                        wire:key="fila-analisis-{{ $analisis_id }}">

                                        <td class="py-4 align-middle">
                                            <p class="font-bold text-gray-800">{{ $data['nombre'] }}</p>
                                            @if ($data['tipo'] === 'numerico' && $data['unidad'])
                                                <span
                                                    class="inline-block mt-1 bg-gray-100 text-gray-500 text-[10px] px-2 py-0.5 rounded font-medium">
                                                    Medida: {{ $data['unidad'] }}
                                                </span>
                                            @else
                                                <span
                                                    class="inline-block mt-1 bg-purple-50 text-purple-600 text-[10px] px-2 py-0.5 rounded font-medium border border-purple-100">
                                                    Prueba Cualitativa
                                                </span>
                                            @endif
                                        </td>

                                        <td class="py-4 align-middle pr-4">
                                            @if ($data['tipo'] === 'cualitativo' && count($data['opciones_cualitativas']) > 0)
                                                <select wire:model.live="valores.{{ $analisis_id }}.valor"
                                                    class="block w-full bg-purple-50 border border-purple-200 text-purple-900 rounded-lg p-2.5 font-bold focus:ring-purple-500 focus:border-purple-500 transition-colors cursor-pointer">
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
                                                        class="block w-full bg-white border border-gray-300 rounded-lg p-2.5 text-gray-900 font-bold focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                                        placeholder="Ingrese resultado..." autocomplete="off">

                                                    @if ($data['tipo'] === 'numerico' && $data['unidad'])
                                                        <div
                                                            class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                            <span
                                                                class="text-gray-400 sm:text-xs">{{ $data['unidad'] }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            @error('valores.' . $analisis_id . '.valor')
                                                <span
                                                    class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                                            @enderror
                                        </td>

                                        <td class="py-4 align-middle">
                                            @if ($data['tipo'] === 'numerico')
                                                <select wire:model="valores.{{ $analisis_id }}.alerta"
                                                    class="block w-full bg-white border border-gray-300 rounded-lg p-2.5 text-sm font-medium focus:ring-purple-500 focus:border-purple-500 transition-colors
                                                    {{ $data['alerta'] === 'normal' ? 'text-green-600' : '' }}
                                                    {{ $data['alerta'] === 'alto' || $data['alerta'] === 'bajo' ? 'text-amber-600' : '' }}
                                                    {{ $data['alerta'] === 'critico' ? 'text-red-600 font-bold bg-red-50' : '' }}">
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
                                                        class="block w-full bg-blue-50 text-blue-700 border border-blue-200 rounded-lg p-2.5 text-sm font-bold text-center shadow-inner">
                                                        <i class="fas fa-check-circle mr-1"></i> Registrado
                                                    </div>
                                                @else
                                                    <select wire:model="valores.{{ $analisis_id }}.alerta"
                                                        class="block w-full bg-white border border-gray-300 rounded-lg p-2.5 text-sm font-medium focus:ring-purple-500 focus:border-purple-500 transition-colors
                                                        {{ $data['alerta'] === 'normal' ? 'text-green-600' : '' }}
                                                        {{ $data['alerta'] === 'critico' ? 'text-red-600 font-bold bg-red-50' : '' }}">
                                                        <option value="normal">✅ No Reactivo / Negativo</option>
                                                        <option value="critico">🚨 Reactivo / Atención</option>
                                                    </select>

                                                    @if ($data['ref_cualitativa'])
                                                        <div class="mt-1.5 text-center">
                                                            <span
                                                                class="text-[10px] text-gray-500 font-medium bg-gray-50 px-2 py-0.5 rounded border border-gray-200 block w-full truncate">
                                                                Esperado: <span
                                                                    class="text-green-600 font-bold">{{ $data['ref_cualitativa'] }}</span>
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

            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('laboratorio.panel') }}" wire:navigate
                    class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors shadow-sm text-sm">
                    Volver al Panel
                </a>

                @if (count($valores) > 0)
                    <button type="button" wire:click="previsualizarResultados" wire:loading.attr="disabled"
                        class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-sm transition-colors flex items-center gap-2 disabled:opacity-70 text-sm">
                        <span wire:loading.remove wire:target="previsualizarResultados">
                            <i class="fas fa-search"></i> Previsualizar Resultados
                        </span>
                        <span wire:loading wire:target="previsualizarResultados">
                            <i class="fas fa-spinner fa-spin"></i> Procesando...
                        </span>
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="fixed inset-0 z-50 overflow-y-auto {{ $mostrarModalPreview ? '' : 'hidden' }}"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" wire:click="cerrarModal"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full relative z-10 animate-fadeIn">

                <div class="bg-purple-600 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2" id="modal-title">
                        <i class="fas fa-search"></i> Previsualización y Firma
                    </h3>
                    <button wire:click="cerrarModal" class="text-purple-200 hover:text-white transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="bg-white px-6 pt-5 pb-4">
                    <p class="text-sm text-gray-500 mb-4">Verifique que los valores ingresados son correctos antes de
                        firmar el documento.</p>

                    <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden mb-5">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="border-b bg-gray-100 text-gray-600 font-semibold text-xs uppercase">
                                    <th class="p-3">Examen</th>
                                    <th class="p-3 text-center">Valor</th>
                                    <th class="p-3 text-center">Alerta</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($resultados_a_previsualizar as $id => $res)
                                    <tr class="bg-white" wire:key="prev-{{ $id }}">
                                        <td class="p-3 font-medium text-gray-800">{{ $res['nombre'] ?? '' }}</td>
                                        <td class="p-3 text-center font-bold">{{ $res['valor'] ?? '' }} <span
                                                class="text-xs text-gray-500 font-normal">{{ $res['unidad'] ?? '' }}</span>
                                        </td>
                                        <td class="p-3 text-center">
                                            <span
                                                class="px-2 py-1 rounded-md text-[10px] font-bold uppercase
                                                {{ ($res['alerta'] ?? '') === 'normal' ? 'bg-green-50 text-green-700' : '' }}
                                                {{ ($res['alerta'] ?? '') === 'alto' || ($res['alerta'] ?? '') === 'bajo' ? 'bg-amber-50 text-amber-700' : '' }}
                                                {{ ($res['alerta'] ?? '') === 'critico' ? 'bg-red-50 text-red-700' : '' }}">
                                                {{ $res['alerta'] ?? '' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="p-4 text-center text-gray-500">Sin datos para
                                            previsualizar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-purple-50/50 border border-purple-100 rounded-xl p-4">
                        <h4
                            class="text-xs font-bold text-purple-800 uppercase tracking-wider mb-2 flex items-center gap-2">
                            <i class="fas fa-envelope-open-text"></i> Notificación de Resultados
                        </h4>
                        <p class="text-[11px] text-gray-600 mb-4">
                            Para poder finalizar el informe y firmarlo, el sistema requiere registrar <strong>por lo
                                menos un correo electrónico</strong> válido (sea del Paciente/Responsable o del Médico).
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
                                    class="block w-full bg-white border border-gray-300 rounded-lg p-2.5 text-sm font-medium focus:ring-purple-500 focus:border-purple-500"
                                    placeholder="paciente@ejemplo.com">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-700 font-bold mb-1">
                                    Correo Médico Solicitante:
                                </label>
                                <input type="email" wire:model="email_medico"
                                    class="block w-full bg-white border border-gray-300 rounded-lg p-2.5 text-sm font-medium focus:ring-purple-500 focus:border-purple-500"
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
                        class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-sm transition-colors flex items-center gap-2 disabled:opacity-70 text-sm">
                        <span wire:loading.remove wire:target="confirmarYEnviar">
                            <i class="fas fa-check-double"></i> Confirmar, Firmar y Enviar
                        </span>
                        <span wire:loading wire:target="confirmarYEnviar">
                            <i class="fas fa-spinner fa-spin"></i> Procesando...
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
    </style>
</div>
