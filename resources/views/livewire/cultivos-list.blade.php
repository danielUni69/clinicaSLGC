{{-- resources/views/livewire/cultivos-list.blade.php --}}
<div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-flask text-blue-600"></i>
                Gestión de Cultivos
            </h1>
            <p class="text-sm text-gray-500 mt-1">Registro, seguimiento y antibiograma de cultivos.</p>
        </div>
        <button type="button" wire:click="abrirCrear"
            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-5 rounded-xl shadow-sm transition-all flex items-center gap-2">
            <i class="fas fa-plus"></i> Nuevo Cultivo
        </button>
    </div>

    {{-- Mensaje éxito --}}
    @if (session()->has('message'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-start gap-3">
            <i class="fas fa-check-circle text-green-500 mt-1"></i>
            <div>
                <p class="font-bold text-sm">¡Operación Exitosa!</p>
                <p class="text-sm">{{ session('message') }}</p>
            </div>
        </div>
    @endif

    {{-- Buscador + Filtro --}}
    <div class="bg-white shadow-sm rounded-2xl p-4 border border-gray-100 mb-6">
        <div class="flex gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" wire:model.live="search"
                    placeholder="Buscar por paciente, CI o cepa..."
                    class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
            <select wire:model.live="filtroEstado"
                class="bg-gray-50 border border-gray-300 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 p-2.5 min-w-[180px]">
                <option value="">Todos los estados</option>
                <option value="en_incubacion">En incubación</option>
                <option value="negativo">Negativo</option>
                <option value="positivo_identificado">Positivo identificado</option>
            </select>
        </div>
    </div>

    {{-- MODAL formulario --}}
    @if($mostrarFormulario)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="cancelarFormulario"></div>
            <div class="relative w-full max-w-3xl mx-4 bg-white shadow-2xl rounded-2xl overflow-hidden border border-gray-100 max-h-[90vh] overflow-y-auto">

                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white z-10">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <span class="w-9 h-9 rounded-full flex items-center justify-center bg-blue-100 text-blue-700">
                            <i class="fas fa-flask"></i>
                        </span>
                        {{ $modo === 'editar' ? 'Editar Cultivo' : 'Nuevo Cultivo' }}
                    </h2>
                    <button type="button" wire:click="cancelarFormulario"
                        class="px-4 py-2 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all flex items-center gap-2">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                </div>

                <div class="p-6">
                    <form wire:submit.prevent="guardar" class="space-y-5">

                        {{-- Servicio (paciente) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Servicio / Paciente</label>
                                <select wire:model="servicio_id"
                                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                    <option value="">— Seleccionar —</option>
                                    @foreach($servicios as $s)
                                        <option value="{{ $s->id }}">
                                            {{ $s->paciente->nombre_completo }} — {{ $s->codigo_unico }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('servicio_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Tipo de Análisis</label>
                                <select wire:model="tipo_analisis_id"
                                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                    <option value="">— Seleccionar —</option>
                                    @foreach($tiposAnalisis as $t)
                                        <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('tipo_analisis_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Cepa Bacteriana</label>
                                <input type="text" wire:model="cepa_bacteriana"
                                    placeholder="Ej. E. coli, S. aureus..."
                                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                @error('cepa_bacteriana') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Estado</label>
                                <select wire:model="estado_cultivo"
                                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                    <option value="en_incubacion">En incubación</option>
                                    <option value="negativo">Negativo</option>
                                    <option value="positivo_identificado">Positivo identificado</option>
                                </select>
                                @error('estado_cultivo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Bioquímico Responsable</label>
                                <select wire:model="bioquimico_id"
                                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                    <option value="">— Seleccionar —</option>
                                    @foreach($bioquimicos as $b)
                                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                                    @endforeach
                                </select>
                                @error('bioquimico_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Antibiograma --}}
                        <div class="bg-gray-50/80 p-4 rounded-xl border border-gray-200 border-dashed">
                            <h3 class="text-sm font-bold text-gray-700 flex items-center gap-2 mb-3">
                                <i class="fas fa-pills text-gray-400"></i>
                                Antibiograma
                            </h3>

                            <div class="grid grid-cols-12 gap-2 mb-2">
                                <span class="col-span-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Antibiótico</span>
                                <span class="col-span-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Susceptibilidad</span>
                            </div>

                            @foreach($filas_antibiograma as $i => $fila)
                                <div class="grid grid-cols-12 gap-2 mb-2">
                                    <div class="col-span-6">
                                        <select wire:model="filas_antibiograma.{{ $i }}.antibiotico_id"
                                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                            <option value="">— Seleccionar —</option>
                                            @foreach($antibioticos as $ab)
                                                <option value="{{ $ab->id }}">{{ $ab->nombre_antibiotico }}</option>
                                            @endforeach
                                        </select>
                                        @error("filas_antibiograma.{$i}.antibiotico_id")
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-span-5">
                                        <select wire:model="filas_antibiograma.{{ $i }}.susceptibilidad"
                                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                            <option value="S">Sensible</option>
                                            <option value="I">Intermedio</option>
                                            <option value="R">Resistente</option>
                                        </select>
                                    </div>
                                    <div class="col-span-1 flex items-center justify-center">
                                        @if(count($filas_antibiograma) > 1)
                                            <button type="button" wire:click="eliminarFila({{ $i }})"
                                                class="text-red-400 hover:text-red-600 transition-colors">
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            <button type="button" wire:click="agregarFila"
                                class="mt-2 w-full py-2 border border-dashed border-gray-300 text-gray-500 text-sm rounded-lg hover:bg-gray-100 transition flex items-center justify-center gap-2">
                                <i class="fas fa-plus"></i> Agregar antibiótico
                            </button>
                        </div>

                        <div class="flex justify-end gap-3 pt-3 border-t border-gray-200">
                            <button type="button" wire:click="cancelarFormulario"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-all flex items-center gap-2">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                            <button type="submit" wire:loading.attr="disabled"
                                class="px-5 py-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition-all flex items-center gap-2 disabled:opacity-70">
                                <i class="fas fa-save"></i>
                                {{ $modo === 'editar' ? 'Guardar Cambios' : 'Guardar Cultivo' }}
                                <span wire:loading wire:target="guardar">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Lista --}}
    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden relative">
        <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>

        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <span class="bg-blue-100 text-blue-700 w-8 h-8 rounded-full flex items-center justify-center text-sm">
                    <i class="fas fa-list text-xs"></i>
                </span>
                Cultivos Registrados
            </h2>
            <span class="bg-blue-100 text-blue-700 text-xs py-1 px-3 rounded-lg font-medium">
                {{ $cultivos->total() }} cultivos
            </span>
        </div>

        @forelse($cultivos as $cultivo)
            @php
                $estadoClase = match($cultivo->estado_cultivo) {
                    'en_incubacion'         => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                    'negativo'              => 'bg-gray-100 text-gray-700 border border-gray-200',
                    'positivo_identificado' => 'bg-green-100 text-green-800 border border-green-200',
                    default                 => 'bg-gray-100 text-gray-600',
                };
                $estadoLabel = match($cultivo->estado_cultivo) {
                    'en_incubacion'         => 'En incubación',
                    'negativo'              => 'Negativo',
                    'positivo_identificado' => 'Positivo identificado',
                    default                 => $cultivo->estado_cultivo,
                };
            @endphp

            <div class="px-6 py-4 border-b border-gray-50 last:border-b-0 hover:bg-gray-50/50 transition-colors">
                <div class="flex items-center gap-4">
                    {{-- Avatar --}}
                    <div class="w-11 h-11 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0 shadow-sm">
                        {{ strtoupper(substr($cultivo->servicio->paciente->nombre_completo, 0, 1)) }}{{ strtoupper(substr(strrchr($cultivo->servicio->paciente->nombre_completo, ' '), 1, 1)) }}
                    </div>

                    {{-- Datos --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-base font-bold text-gray-800 truncate">
                            {{ $cultivo->servicio->paciente->nombre_completo }}
                        </p>
                        <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1">
                            <span class="text-sm text-gray-500 flex items-center gap-1">
                                <i class="fas fa-flask text-gray-300 text-xs"></i>
                                <span class="text-gray-700 font-medium">{{ $cultivo->tipoAnalisis->nombre }}</span>
                            </span>
                            <span class="text-sm text-gray-500 flex items-center gap-1">
                                <i class="fas fa-bug text-gray-300 text-xs"></i>
                                <span class="text-gray-700">{{ $cultivo->cepa_bacteriana ?? '—' }}</span>
                            </span>
                            <span class="text-sm text-gray-500 flex items-center gap-1">
                                <i class="fas fa-user-md text-gray-300 text-xs"></i>
                                <span class="text-gray-700">{{ $cultivo->bioquimico->name }}</span>
                            </span>
                            <span class="text-sm text-gray-500 flex items-center gap-1">
                                <i class="fas fa-calendar text-gray-300 text-xs"></i>
                                <span class="text-gray-700">{{ $cultivo->created_at->format('d M Y') }}</span>
                            </span>
                        </div>
                    </div>

                    {{-- Acciones --}}
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $estadoClase }}">
                            {{ $estadoLabel }}
                        </span>

                        <button type="button" wire:click="verDetalle({{ $cultivo->id }})"
                            class="flex items-center gap-2 text-sm font-medium px-3 py-2 rounded-xl border border-blue-200 text-blue-700 hover:bg-blue-50 transition-all">
                            <i class="fas fa-eye"></i>
                        </button>

                        <button type="button" wire:click="abrirEditar({{ $cultivo->id }})"
                            class="flex items-center gap-2 text-sm font-medium px-4 py-2 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white transition-all shadow-sm">
                            <i class="fas fa-pen"></i> Editar
                        </button>

                        @if($confirmando_borrar_id === $cultivo->id)
                            <div class="flex items-center gap-2">
                                <button type="button" wire:click="borrar({{ $cultivo->id }})"
                                    class="flex items-center gap-2 text-sm font-bold px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white transition-all shadow-sm">
                                    <i class="fas fa-trash"></i> Confirmar
                                </button>
                                <button type="button" wire:click="confirmarBorrar({{ $cultivo->id }})"
                                    class="flex items-center gap-2 text-sm font-medium px-3 py-2 rounded-xl border border-red-200 text-red-700 hover:bg-red-50 transition-all">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @else
                            <button type="button" wire:click="confirmarBorrar({{ $cultivo->id }})"
                                class="flex items-center gap-2 text-sm font-medium px-4 py-2 rounded-xl border border-red-200 text-red-700 hover:bg-red-50 transition-all">
                                <i class="fas fa-trash"></i> Borrar
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Panel detalle inline --}}
            @if($cultivo_detalle_id === $cultivo->id && $detalleCultivo)
                <div class="mx-6 mb-4 bg-blue-50/50 border border-blue-100 rounded-2xl overflow-hidden">

                    {{-- Info general --}}
                    <div class="px-5 py-4 border-b border-blue-100 bg-blue-100/40 flex items-center justify-between">
                        <h3 class="font-bold text-blue-800 flex items-center gap-2">
                            <i class="fas fa-flask"></i>
                            Detalle — {{ $detalleCultivo->servicio->paciente->nombre_completo }}
                        </h3>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $estadoClase }}">{{ $estadoLabel }}</span>
                    </div>

                    <div class="p-5 grid grid-cols-2 md:grid-cols-3 gap-4 border-b border-blue-100">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo análisis</p>
                            <p class="text-sm font-medium text-gray-800 mt-1">{{ $detalleCultivo->tipoAnalisis->nombre }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Cepa</p>
                            <p class="text-sm font-medium text-gray-800 mt-1">{{ $detalleCultivo->cepa_bacteriana ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Bioquímico</p>
                            <p class="text-sm font-medium text-gray-800 mt-1">{{ $detalleCultivo->bioquimico->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Servicio</p>
                            <p class="text-sm font-medium text-gray-800 mt-1">{{ $detalleCultivo->servicio->codigo_unico }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Registrado</p>
                            <p class="text-sm font-medium text-gray-800 mt-1">{{ $detalleCultivo->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    {{-- Antibiograma --}}
                    @if($detalleCultivo->antibiogramas->count())
                        <div class="p-5 border-b border-blue-100">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <i class="fas fa-pills text-gray-400"></i> Antibiograma
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                @foreach($detalleCultivo->antibiogramas as $ab)
                                    @php
                                        $susClase = match($ab->susceptibilidad) {
                                            'S' => 'bg-green-100 text-green-800 border border-green-200',
                                            'I' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                            'R' => 'bg-red-100 text-red-800 border border-red-200',
                                            default => 'bg-gray-100 text-gray-600',
                                        };
                                        $susLabel = match($ab->susceptibilidad) {
                                            'S' => 'Sensible',
                                            'I' => 'Intermedio',
                                            'R' => 'Resistente',
                                            default => $ab->susceptibilidad,
                                        };
                                    @endphp
                                    <div class="flex items-center justify-between bg-white rounded-lg px-3 py-2 border border-gray-100">
                                        <span class="text-sm text-gray-700">{{ $ab->antibiotico->nombre_antibiotico }}</span>
                                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $susClase }}">{{ $susLabel }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Historial evolución --}}
                    <div class="p-5">
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <i class="fas fa-comments text-gray-400"></i> Historial de evolución
                        </h4>

                        @if($detalleCultivo->reportesEvolucion->count())
                            <div class="space-y-3 mb-4">
                                @foreach($detalleCultivo->reportesEvolucion->sortByDesc('created_at') as $reporte)
                                    <div class="flex gap-3">
                                        <div class="w-2 h-2 rounded-full bg-blue-500 flex-shrink-0 mt-2"></div>
                                        <div class="flex-1 bg-white rounded-lg px-3 py-2 border border-gray-100">
                                            <p class="text-sm text-gray-800">{{ $reporte->observacion }}</p>
                                            <p class="text-xs text-gray-400 mt-1">{{ $reporte->created_at->format('d M Y H:i') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-400 mb-4 flex items-center gap-2">
                                <i class="fas fa-info-circle"></i> Sin observaciones aún.
                            </p>
                        @endif

                        @if(session()->has('message_obs'))
                            <div class="mb-3 text-green-700 text-sm bg-green-50 border border-green-200 rounded-lg px-3 py-2">
                                <i class="fas fa-check-circle"></i> {{ session('message_obs') }}
                            </div>
                        @endif

                        <div class="flex gap-3">
                            <textarea wire:model="nueva_observacion" rows="2"
                                placeholder="Agregar observación o actualización..."
                                class="flex-1 bg-white border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 p-2.5 resize-none"></textarea>
                            <button type="button" wire:click="agregarObservacion"
                                class="self-end bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition flex items-center gap-2">
                                <i class="fas fa-paper-plane"></i> Agregar
                            </button>
                        </div>
                        @error('nueva_observacion')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            @endif

        @empty
            <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                <i class="fas fa-flask text-4xl mb-3 opacity-20"></i>
                <p class="text-sm text-center">No se encontraron cultivos.<br>Intente con otro término de búsqueda.</p>
            </div>
        @endforelse

        {{-- Paginación --}}
        @if($cultivos->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $cultivos->links() }}
            </div>
        @endif
    </div>
</div>