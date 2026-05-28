<div class="max-w-5xl mx-auto py-8 sm:px-6 lg:px-8">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-edit text-purple-600"></i>
                Transcripción de Resultados
            </h1>
            <p class="text-sm text-gray-500 mt-1">Ingrese los valores y el sistema validará los rangos.</p>
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
                <p class="text-sm text-gray-500">CI: {{ $paciente_ci }}</p>
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
                <i class="fas fa-microscope text-purple-500"></i> Parámetros a Evaluar
            </h3>
        </div>

        <form wire:submit.prevent="guardarResultados">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b-2 border-gray-100">
                                <th class="pb-3 font-semibold text-gray-600 text-sm">Examen</th>
                                <th class="pb-3 font-semibold text-gray-600 text-sm w-1/3">Valor Obtenido</th>
                                <th class="pb-3 font-semibold text-gray-600 text-sm w-1/4">Estado (Automático)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($valores as $analisis_id => $data)
                                <tr class="hover:bg-gray-50 transition-colors group">
                                    <td class="py-4 align-middle">
                                        <p class="font-bold text-gray-800">{{ $data['nombre'] }}</p>
                                        @if ($data['unidad'])
                                            <span
                                                class="inline-block mt-1 bg-gray-100 text-gray-500 text-[10px] px-2 py-0.5 rounded font-medium">
                                                Medida en: {{ $data['unidad'] }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="py-4 align-middle pr-4">
                                        <div class="relative">
                                            <input type="text"
                                                wire:model.live.debounce.500ms="valores.{{ $analisis_id }}.valor"
                                                class="block w-full bg-white border border-gray-300 rounded-lg p-2.5 text-gray-900 font-bold focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                                placeholder="Ej. 95.5" autocomplete="off">

                                            @if ($data['unidad'])
                                                <div
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-400 sm:text-xs">{{ $data['unidad'] }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        @error('valores.' . $analisis_id . '.valor')
                                            <span
                                                class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                                        @enderror
                                    </td>

                                    <td class="py-4 align-middle">
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
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('laboratorio.panel') }}" wire:navigate
                    class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors shadow-sm text-sm">
                    Cancelar
                </a>

                <button type="submit" wire:loading.attr="disabled"
                    class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-sm transition-colors flex items-center gap-2 disabled:opacity-70 text-sm">
                    <span wire:loading.remove wire:target="guardarResultados">
                        <i class="fas fa-save"></i> Guardar Resultados
                    </span>
                    <span wire:loading wire:target="guardarResultados">
                        <i class="fas fa-spinner fa-spin"></i> Guardando...
                    </span>
                </button>
            </div>
        </form>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
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
