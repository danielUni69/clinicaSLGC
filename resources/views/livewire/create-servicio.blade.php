<div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
    <div class="">
        <h1 class="text-2xl font-black text-slate-800 flex items-center gap-3 uppercase tracking-wide">
            <i class="fas fa-cash-register text-blue-700"></i> Punto de Recepción y Caja
        </h1>
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
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

        <div class="lg:col-span-8 space-y-6">

            <div class="bg-white shadow-sm rounded-2xl p-6 border border-slate-200 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-blue-500"></div>

                <h2 class="text-lg font-black text-slate-800 mb-5 flex items-center gap-2">
                    <span
                        class="bg-blue-100 text-blue-700 w-8 h-8 rounded-full flex items-center justify-center text-sm shadow-inner">1</span>
                    Identificación del Paciente
                </h2>

                <div class="flex gap-3 items-end">
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Carnet de
                            Identidad (CI)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-id-card text-slate-400"></i>
                            </div>
                            <input type="text" wire:model="busqueda_ci" wire:keydown.enter="buscarPaciente"
                                class="pl-11 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm"
                                placeholder="Ingrese el CI y presione Enter...">
                        </div>
                    </div>
                    <button wire:click="buscarPaciente"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-sm hover:shadow-md transition-all flex items-center gap-2">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>

                @if ($paciente_nombre)
                    <div class="mt-6 p-5 bg-blue-50/50 border border-blue-100 rounded-xl transition-all">
                        <div class="flex items-center gap-4 mb-4">
                            <div
                                class="w-12 h-12 bg-white text-blue-600 border border-blue-200 rounded-full flex items-center justify-center text-xl shadow-sm font-black relative">
                                {{ substr($paciente_nombre, 0, 2) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <p class="text-[10px] text-blue-500 font-bold uppercase tracking-wider">Paciente
                                        Confirmado</p>
                                    @if ($es_menor)
                                        <span
                                            class="bg-amber-100 text-amber-700 text-[9px] px-2 py-0.5 rounded font-black uppercase tracking-wider"><i
                                                class="fas fa-child"></i> Menor de edad ({{ $paciente_edad }}
                                            años)</span>
                                    @else
                                        <span
                                            class="bg-blue-100 text-blue-700 text-[9px] px-2 py-0.5 rounded font-black uppercase tracking-wider"><i
                                                class="fas fa-user-check"></i> Adulto ({{ $paciente_edad }} años)</span>
                                    @endif
                                </div>
                                <p class="font-black text-lg text-slate-800">{{ $paciente_nombre }}</p>
                            </div>
                        </div>

                        <div class="bg-white p-4 rounded-lg border border-blue-100 shadow-sm">
                            <label class="block text-xs font-bold text-slate-500 mb-1">
                                Correo del Paciente
                                @if (!$es_menor && empty(trim($responsable_nombre)))
                                    <span class="text-red-500" title="Requerido si no hay tutor">* Obligatorio</span>
                                @else
                                    <span class="text-slate-400 font-normal normal-case ml-1">(Opcional)</span>
                                @endif
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-slate-400"></i>
                                </div>
                                <input type="email" wire:model="paciente_email"
                                    class="pl-10 w-full p-2.5 text-sm rounded-lg border border-slate-300 bg-white text-slate-900 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="paciente@correo.com">
                            </div>
                            @error('paciente_email')
                                <span class="text-red-500 text-[11px] mt-1.5 block font-bold"><i
                                        class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div
                        class="mt-6 bg-slate-50 p-5 rounded-xl border {{ $es_menor ? 'border-amber-200 shadow-sm bg-amber-50/30' : 'border-slate-200' }}">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                                <i class="fas fa-user-shield text-slate-400"></i> Datos del Responsable / Tutor
                                @if ($es_menor)
                                    <span
                                        class="bg-red-100 text-red-700 text-[9px] px-2 py-0.5 rounded-md uppercase font-black tracking-wider border border-red-200">Obligatorio
                                        por ley</span>
                                @else
                                    <span
                                        class="bg-slate-200 text-slate-600 text-[9px] px-2 py-0.5 rounded-md uppercase font-black tracking-wider">Opcional</span>
                                @endif
                            </h3>
                            @if ($tiene_responsable_previo && !$editar_responsable)
                                <button wire:click="habilitarEdicionResponsable"
                                    class="text-xs text-blue-600 hover:text-blue-800 font-bold flex items-center gap-1 bg-blue-50 px-2 py-1 rounded border border-blue-100 transition-colors">
                                    <i class="fas fa-unlock-alt"></i> Modificar Datos
                                </button>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">Nombre Completo @if ($es_menor)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                <input type="text" wire:model.live="responsable_nombre" placeholder="Ej. Juan Pérez"
                                    {{ !$editar_responsable ? 'disabled' : '' }}
                                    class="w-full p-2.5 text-sm rounded-lg border transition-colors {{ !$editar_responsable ? 'bg-slate-200 border-transparent text-slate-500 cursor-not-allowed' : 'bg-white border-slate-300 text-slate-900 focus:ring-blue-500 focus:border-blue-500' }}">
                                @error('responsable_nombre')
                                    <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">Celular @if ($es_menor)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                <input type="text" wire:model="responsable_celular" placeholder="Ej. 71234567"
                                    {{ !$editar_responsable ? 'disabled' : '' }}
                                    class="w-full p-2.5 text-sm rounded-lg border transition-colors {{ !$editar_responsable ? 'bg-slate-200 border-transparent text-slate-500 cursor-not-allowed' : 'bg-white border-slate-300 text-slate-900 focus:ring-blue-500 focus:border-blue-500' }}">
                                @error('responsable_celular')
                                    <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">Relación @if ($es_menor)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                <input type="text" wire:model="responsable_relacion"
                                    placeholder="Ej. Padre, Madre" {{ !$editar_responsable ? 'disabled' : '' }}
                                    class="w-full p-2.5 text-sm rounded-lg border transition-colors {{ !$editar_responsable ? 'bg-slate-200 border-transparent text-slate-500 cursor-not-allowed' : 'bg-white border-slate-300 text-slate-900 focus:ring-blue-500 focus:border-blue-500' }}">
                                @error('responsable_relacion')
                                    <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">
                                    Correo del Tutor
                                    @if ($es_menor || !empty(trim($responsable_nombre)))
                                        <span class="text-red-500" title="Requerido si se llena el tutor">*
                                            Obligatorio</span>
                                    @endif
                                </label>
                                <input type="email" wire:model="responsable_correo" placeholder="tutor@correo.com"
                                    {{ !$editar_responsable ? 'disabled' : '' }}
                                    class="w-full p-2.5 text-sm rounded-lg border transition-colors {{ !$editar_responsable ? 'bg-slate-200 border-transparent text-slate-500 cursor-not-allowed' : 'bg-white border-slate-300 text-slate-900 focus:ring-blue-500 focus:border-blue-500' }}">
                                @error('responsable_correo')
                                    <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif

                @if ($paciente_error)
                    <div
                        class="mt-5 p-4 bg-red-50 border border-red-100 text-red-700 rounded-xl flex items-center gap-3">
                        <i class="fas fa-exclamation-triangle text-red-500"></i>
                        <span class="text-sm font-medium">{{ $paciente_error }}</span>
                    </div>
                @endif
                @error('paciente_id')
                    <span class="text-red-500 text-xs mt-3 block font-bold bg-red-50 p-2 rounded border border-red-100"><i
                            class="fas fa-times-circle"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="bg-white shadow-sm rounded-2xl p-6 border border-slate-200 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-emerald-500"></div>

                <h2 class="text-lg font-black text-slate-800 mb-5 flex items-center gap-2">
                    <span
                        class="bg-emerald-100 text-emerald-700 w-8 h-8 rounded-full flex items-center justify-center text-sm shadow-inner">2</span>
                    Orden Médica y Catálogo
                </h2>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                        Médico Solicitante <span
                            class="bg-slate-200 text-slate-600 text-[9px] px-2 py-0.5 rounded-md uppercase font-black tracking-wider ml-1">Opcional</span>
                    </label>
                    <select wire:model="medico_id"
                        class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block w-full p-3 shadow-sm transition-colors">
                        <option value="">Ninguno (Paciente Particular / Sin Orden)</option>
                        @foreach ($medicos as $medico)
                            <option value="{{ $medico->id }}">{{ $medico->nombre_completo }}
                                ({{ $medico->especialidad }})
                            </option>
                        @endforeach
                    </select>
                    @error('medico_id')
                        <span class="text-red-500 text-xs mt-2 block font-medium"><i class="fas fa-times-circle"></i>
                            {{ $message }}</span>
                    @enderror
                </div>

                <div class="pt-6 border-t border-slate-100">
                    <label
                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i class="fas fa-microscope text-slate-400"></i> Seleccionar Exámenes
                    </label>

                    @error('analisis_ids')
                        <span
                            class="text-red-500 text-sm mb-4 block font-medium bg-red-50 p-2 rounded-lg border border-red-100"><i
                                class="fas fa-times-circle"></i> {{ $message }}</span>
                    @enderror

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @foreach ($categorias as $categoria)
                            <div
                                class="bg-white rounded-xl border {{ $categoria->es_cultivo ? 'border-emerald-200' : 'border-slate-200' }} overflow-hidden flex flex-col shadow-sm">
                                <div
                                    class="{{ $categoria->es_cultivo ? 'bg-emerald-50' : 'bg-slate-100' }} px-4 py-3 border-b {{ $categoria->es_cultivo ? 'border-emerald-100' : 'border-slate-200' }} flex justify-between items-center">
                                    <h4 class="font-black text-slate-700 text-xs uppercase tracking-wider">
                                        {{ $categoria->nombre }}
                                    </h4>
                                    @if ($categoria->es_cultivo)
                                        <i class="fas fa-bacteria text-emerald-500 text-sm"></i>
                                    @endif
                                </div>

                                <div class="p-2 flex-1 max-h-60 overflow-y-auto custom-scrollbar">
                                    @foreach ($categoria->tiposAnalisis as $analisis)
                                        <label
                                            class="flex items-start gap-3 p-3 hover:bg-slate-50 rounded-lg transition-colors cursor-pointer border border-transparent hover:border-slate-200">
                                            <div class="flex-shrink-0 mt-0.5">
                                                <input type="checkbox" wire:model.live="analisis_ids"
                                                    value="{{ $analisis->id }}"
                                                    class="w-4 h-4 text-emerald-600 bg-white border-slate-300 rounded focus:ring-emerald-500 transition-all cursor-pointer">
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-bold text-slate-800 leading-tight">
                                                    {{ $analisis->nombre }}</p>
                                                <p class="text-xs text-slate-500 mt-1 font-mono font-semibold">Bs.
                                                    {{ number_format($analisis->costo, 2) }}</p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

        <div class="lg:col-span-4">
            <div class="bg-white shadow-lg rounded-2xl p-6 sticky top-6 border border-slate-200">
                <h2
                    class="text-lg font-black text-slate-800 mb-5 pb-3 border-b border-slate-100 flex items-center justify-between">
                    <span class="flex items-center gap-2"><i class="fas fa-shopping-cart text-blue-500"></i>
                        Carrito</span>
                    <span
                        class="bg-slate-100 text-slate-600 text-xs py-1 px-2.5 rounded-lg font-bold">{{ count($analisis_seleccionados) }}
                        items</span>
                </h2>

                <div class="min-h-[150px] max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                    <ul class="space-y-3 mb-5">
                        @forelse($analisis_seleccionados as $item)
                            <li
                                class="p-3 bg-white border rounded-xl flex justify-between items-center group transition-colors shadow-sm {{ $item['es_cultivo'] ? 'border-emerald-200 bg-emerald-50/30' : 'border-slate-200 hover:border-blue-300' }}">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-bold text-slate-800">{{ $item['nombre'] }}</p>
                                        @if ($item['es_cultivo'])
                                            <span
                                                class="bg-emerald-100 text-emerald-700 text-[8px] px-1.5 py-0.5 rounded font-black uppercase tracking-wider"><i
                                                    class="fas fa-bacteria"></i> Cultivo</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1 font-mono font-bold">Bs.
                                        {{ number_format($item['costo'], 2) }}</p>
                                </div>
                                <button type="button" wire:click="quitarAnalisis({{ $item['id'] }})"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-red-50 hover:text-red-600 transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </li>
                        @empty
                            <div
                                class="h-full flex flex-col items-center justify-center text-slate-400 py-10 opacity-70 border-2 border-dashed border-slate-200 rounded-xl">
                                <i class="fas fa-receipt text-3xl mb-3"></i>
                                <p class="text-xs font-medium text-center">Seleccione exámenes<br>desde el catálogo.
                                </p>
                            </div>
                        @endforelse
                    </ul>
                </div>

                <div class="mb-5 pt-3 border-t border-slate-100">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Método de
                        Pago</label>
                    <div class="relative">
                        <select wire:model="metodo_pago"
                            class="bg-slate-50 border border-slate-200 text-slate-800 text-sm font-medium rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 appearance-none shadow-sm">
                            <option value="Efectivo">💵 Efectivo (Caja Física)</option>
                            <option value="QR">📱 Transferencia QR</option>
                            <option value="Tarjeta">💳 Tarjeta (POS)</option>
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900 text-white p-5 rounded-2xl shadow-inner mt-2 border border-slate-800">

                    <div class="flex justify-between items-end mb-5 border-b border-slate-700 pb-4">
                        <span class="text-slate-400 text-xs font-bold tracking-widest uppercase">Total Boleta</span>
                        <span class="text-3xl font-black text-white tracking-tight">Bs.
                            {{ number_format($total_a_pagar, 2) }}</span>
                    </div>

                    @if ($total_a_pagar > 0)
                        <div class="mb-5">
                            <label
                                class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Monto
                                Entregado</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-slate-500 font-bold">Bs.</span>
                                </div>
                                <input type="number" wire:model.live.debounce.300ms="monto_recibido" step="0.50"
                                    class="pl-12 bg-slate-800 border border-slate-600 text-white text-xl font-black rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors placeholder-slate-600 shadow-inner"
                                    placeholder="0.00">
                            </div>
                            @error('monto_recibido')
                                <span
                                    class="text-red-400 text-xs mt-2 block font-bold bg-red-900/30 p-2 rounded-lg border border-red-800/50"><i
                                        class="fas fa-exclamation-triangle"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        <div
                            class="flex justify-between items-center mb-6 bg-slate-800/80 p-3.5 rounded-xl border border-slate-700">
                            @if ($cambio_o_saldo >= 0)
                                <span class="text-[11px] text-emerald-400 font-black uppercase tracking-wider"><i
                                        class="fas fa-hand-holding-usd"></i> Vuelto</span>
                                <span class="text-xl font-black text-emerald-400">Bs.
                                    {{ number_format($cambio_o_saldo, 2) }}</span>
                            @else
                                <span class="text-[11px] text-red-400 font-black uppercase tracking-wider"><i
                                        class="fas fa-exclamation-circle"></i> Faltante</span>
                                <span class="text-xl font-black text-red-400">Bs.
                                    {{ number_format(abs($cambio_o_saldo), 2) }}</span>
                            @endif
                        </div>
                    @endif

                    <button type="button" wire:click="guardarServicio" wire:loading.attr="disabled"
                        class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black py-4 px-4 rounded-xl shadow-[0_0_15px_rgba(37,99,235,0.3)] hover:shadow-[0_0_20px_rgba(37,99,235,0.5)] transition-all flex justify-center items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="guardarServicio">
                            <i class="fas fa-print"></i> Cobrar y Generar Ticket
                        </span>
                        <span wire:loading wire:target="guardarServicio" class="flex items-center gap-2">
                            <i class="fas fa-circle-notch fa-spin"></i> Procesando...
                        </span>
                    </button>
                </div>

            </div>
        </div>

    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
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

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('abrir-ticket', (data) => {
                const url = data[0]?.url || data.url;
                if (url) {
                    window.open(url, '_blank', 'width=400,height=600');
                }
            });
        });
    </script>
</div>
