<div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">

    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-cash-register text-blue-600"></i>
                Punto de Recepción y Caja
            </h1>
            <p class="text-sm text-gray-500 mt-1">Registre pacientes, asigne órdenes médicas y procese el pago.</p>
        </div>
    </div>

    @if (session()->has('mensaje'))
        <div class="mb-6 animate-pulse bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl shadow-sm flex items-start gap-3" role="alert">
            <i class="fas fa-check-circle text-green-500 mt-1 text-lg"></i>
            <div>
                <p class="font-bold text-sm">¡Operación Exitosa!</p>
                <p class="text-sm">{{ session('mensaje') }}</p>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow-sm flex items-start gap-3" role="alert">
            <i class="fas fa-exclamation-circle text-red-500 mt-1 text-lg"></i>
            <div>
                <p class="font-bold text-sm">Error del Sistema</p>
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <div class="lg:col-span-8 space-y-6">

            <div class="bg-white shadow-sm rounded-2xl p-6 border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>

                <h2 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-700 w-8 h-8 rounded-full flex items-center justify-center text-sm">1</span>
                    Identificación del Paciente
                </h2>

                <div class="flex gap-3 items-end">
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Carnet de Identidad (CI)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-id-card text-gray-400"></i>
                            </div>
                            <input type="text" wire:model="busqueda_ci" wire:keydown.enter="buscarPaciente"
                                class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition-colors"
                                placeholder="Ingrese el CI y presione Enter...">
                        </div>
                    </div>
                    <button wire:click="buscarPaciente"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-5 rounded-xl shadow-sm transition-all flex items-center gap-2">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>

                @if($paciente_nombre)
                    <div class="mt-5 p-4 bg-gradient-to-r from-blue-50 to-white border border-blue-100 rounded-xl flex items-center gap-4 transition-all">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xl shadow-sm">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div>
                            <p class="text-xs text-blue-500 font-bold uppercase tracking-wider">Paciente Confirmado</p>
                            <p class="font-black text-xl text-gray-800">{{ $paciente_nombre }}</p>
                        </div>
                    </div>

                    <div class="mt-5 bg-gray-50/80 p-5 rounded-xl border border-gray-200 border-dashed">
                        <h3 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-user-shield text-gray-400"></i> Datos del Responsable / Tutor
                            <span class="bg-gray-200 text-gray-600 text-[10px] px-2 py-0.5 rounded-full uppercase font-bold tracking-wider">Opcional</span>
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Nombre Completo</label>
                                <input type="text" wire:model="responsable_nombre" placeholder="Ej. Juan Pérez"
                                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Celular</label>
                                <input type="text" wire:model="responsable_celular" placeholder="Ej. 71234567"
                                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Relación</label>
                                <input type="text" wire:model="responsable_relacion" placeholder="Ej. Padre, Madre"
                                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                            </div>
                        </div>
                    </div>
                @endif

                @if($paciente_error)
                    <div class="mt-5 p-4 bg-red-50 border border-red-100 text-red-700 rounded-xl flex items-center gap-3">
                        <i class="fas fa-exclamation-triangle text-red-500"></i>
                        <span class="text-sm font-medium">{{ $paciente_error }}</span>
                    </div>
                @endif
                @error('paciente_id') <span class="text-red-500 text-xs mt-2 block font-medium"><i class="fas fa-times-circle"></i> {{ $message }}</span> @enderror
            </div>

            <div class="bg-white shadow-sm rounded-2xl p-6 border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-green-500"></div>

                <h2 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2">
                    <span class="bg-green-100 text-green-700 w-8 h-8 rounded-full flex items-center justify-center text-sm">2</span>
                    Orden Médica y Análisis
                </h2>

                <div class="mb-6">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Médico Solicitante</label>
                    <select wire:model="medico_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-green-500 focus:border-green-500 block w-full p-3 transition-colors">
                        <option value="">Seleccione el médico que firma la orden...</option>
                        @foreach($medicos as $medico)
                            <option value="{{ $medico->id }}">{{ $medico->nombre_completo }} ({{ $medico->especialidad }})</option>
                        @endforeach
                    </select>
                    @error('medico_id') <span class="text-red-500 text-xs mt-2 block font-medium"><i class="fas fa-times-circle"></i> {{ $message }}</span> @enderror
                </div>

                <div class="pt-5 border-t border-gray-100">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Añadir Análisis Clínico</label>
                    <div class="flex gap-3">
                        <select wire:model="analisis_a_agregar" class="flex-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-green-500 focus:border-green-500 p-3 transition-colors">
                            <option value="">Buscar en el catálogo de exámenes...</option>
                            @foreach($analisis_disponibles as $analisis)
                                <option value="{{ $analisis->id }}">{{ $analisis->nombre }} — Bs. {{ number_format($analisis->costo, 2) }}</option>
                            @endforeach
                        </select>
                        <button wire:click="agregarAnalisis"
                            class="bg-gray-800 hover:bg-black text-white font-medium py-3 px-6 rounded-xl shadow-sm transition-all flex items-center gap-2">
                            <i class="fas fa-plus"></i> Agregar
                        </button>
                    </div>
                    @error('analisis_seleccionados') <span class="text-red-500 text-xs mt-2 block font-medium"><i class="fas fa-times-circle"></i> {{ $message }}</span> @enderror
                </div>
            </div>

        </div>

        <div class="lg:col-span-4">
            <div class="bg-white shadow-lg rounded-2xl p-6 sticky top-6 border border-gray-100">
                <h2 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b border-gray-100 flex items-center justify-between">
                    <span><i class="fas fa-shopping-cart text-gray-400 mr-2"></i> Detalle a Cobrar</span>
                    <span class="bg-blue-100 text-blue-700 text-xs py-1 px-2 rounded-lg">{{ count($analisis_seleccionados) }} items</span>
                </h2>

                <div class="min-h-[150px] max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                    <ul class="space-y-3 mb-6">
                        @forelse($analisis_seleccionados as $index => $item)
                            <li class="p-3 bg-gray-50 border border-gray-100 rounded-xl flex justify-between items-center group hover:border-red-200 transition-colors">
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $item['nombre'] }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Bs. {{ number_format($item['costo'], 2) }}</p>
                                </div>
                                <button wire:click="quitarAnalisis({{ $index }})"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-red-100 hover:text-red-600 transition-colors">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </li>
                        @empty
                            <div class="h-full flex flex-col items-center justify-center text-gray-400 py-8">
                                <i class="fas fa-microscope text-4xl mb-3 opacity-20"></i>
                                <p class="text-sm text-center">Aún no hay exámenes<br>en la orden.</p>
                            </div>
                        @endforelse
                    </ul>
                </div>

                <div class="mb-6 pt-4 border-t border-gray-100">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Método de Pago</label>
                    <div class="relative">
                        <select wire:model="metodo_pago" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 appearance-none">
                            <option value="Efectivo">💵 Efectivo (Caja Física)</option>
                            <option value="QR">📱 Transferencia QR</option>
                            <option value="Tarjeta">💳 Tarjeta (POS)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-900 text-white p-5 rounded-xl shadow-inner">
                    <div class="flex justify-between items-end mb-5">
                        <span class="text-gray-400 text-sm font-medium">TOTAL Bs.</span>
                        <span class="text-3xl font-black tracking-tight">{{ number_format($total_a_pagar, 2) }}</span>
                    </div>

                    <button wire:click="guardarServicio" wire:loading.attr="disabled"
                        class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3.5 px-4 rounded-xl shadow-[0_0_15px_rgba(37,99,235,0.4)] transition-all flex justify-center items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="guardarServicio">
                            <i class="fas fa-check-circle"></i> Confirmar y Generar Recibo
                        </span>
                        <span wire:loading wire:target="guardarServicio" class="flex items-center gap-2">
                            <i class="fas fa-spinner fa-spin"></i> Procesando Transacción...
                        </span>
                    </button>
                </div>

            </div>
        </div>

    </div>
<style>
    /* Estilos para hacer el scrollbar del carrito más delgado y elegante */
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


