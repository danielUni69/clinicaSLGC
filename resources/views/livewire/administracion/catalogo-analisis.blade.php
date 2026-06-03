<div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">

    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-book-medical text-blue-600"></i>
                Catálogo de Exámenes y Categorías
            </h1>
            <p class="text-sm text-gray-500 mt-1">Gestione los servicios ofertados, costos y parámetros de referencia.
            </p>
        </div>
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

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <div class="lg:col-span-4 space-y-4">
            <div class="bg-white shadow-sm rounded-2xl p-5 border border-gray-100 h-[75vh] flex flex-col">
                <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
                    <h2 class="font-bold text-gray-800 text-lg"><i class="fas fa-tags text-gray-400"></i> Categorías
                    </h2>
                    <button wire:click="abrirModalCategoria"
                        class="bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                        <i class="fas fa-plus"></i> Nueva
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar pb-4">
                    <div wire:click="seleccionarCategoria(null)"
                        class="mb-4 p-3 rounded-xl cursor-pointer border transition-all flex justify-between items-center {{ $categoria_seleccionada_id === null ? 'bg-blue-600 border-blue-600 text-white shadow-md' : 'bg-gray-50 border-gray-200 text-gray-700 hover:bg-gray-100' }}">
                        <span class="font-bold text-sm">Mostrar Todo el Catálogo</span>
                    </div>

                    <div class="mb-5">
                        <div class="flex items-center gap-2 mb-3 px-1">
                            <i class="fas fa-flask text-blue-500 text-xs"></i>
                            <h3 class="text-xs font-black text-gray-400 uppercase tracking-wider">Análisis Generales
                            </h3>
                        </div>
                        <div class="space-y-2">
                            @forelse ($categoriasNormales as $cat)
                                <div wire:key="categoria-{{ $cat->id }}"
                                    class="p-3 rounded-xl border transition-all flex justify-between items-center group {{ $categoria_seleccionada_id === $cat->id ? 'bg-blue-50 border-blue-200 text-blue-800 shadow-sm' : 'bg-white border-gray-200 text-gray-700 hover:border-blue-300' }}">
                                    <div wire:click="seleccionarCategoria({{ $cat->id }})"
                                        class="cursor-pointer flex-1">
                                        <p class="font-bold text-sm">{{ $cat->nombre }}</p>
                                        <p
                                            class="text-[10px] {{ $categoria_seleccionada_id === $cat->id ? 'text-blue-500' : 'text-gray-400' }}">
                                            {{ $cat->tipos_analisis_count }} exámenes</p>
                                    </div>
                                    <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button wire:click="abrirModalCategoria({{ $cat->id }})"
                                            class="w-7 h-7 rounded bg-white text-blue-600 hover:bg-blue-100 border border-blue-100 flex items-center justify-center transition-colors"><i
                                                class="fas fa-edit text-xs"></i></button>
                                        <button wire:click="eliminarCategoria({{ $cat->id }})"
                                            class="w-7 h-7 rounded bg-white text-red-600 hover:bg-red-100 border border-red-100 flex items-center justify-center transition-colors"><i
                                                class="fas fa-trash text-xs"></i></button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-gray-400 px-2 italic">No hay categorías generales.</p>
                            @endforelse
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center gap-2 mb-3 px-1 border-t border-gray-100 pt-4">
                            <i class="fas fa-bacteria text-green-500 text-xs"></i>
                            <h3 class="text-xs font-black text-gray-400 uppercase tracking-wider">Microbiología</h3>
                        </div>
                        <div class="space-y-2">
                            @forelse ($categoriasCultivo as $cat)
                                <div wire:key="categoria-{{ $cat->id }}"
                                    class="p-3 rounded-xl border transition-all flex justify-between items-center group {{ $categoria_seleccionada_id === $cat->id ? 'bg-green-50 border-green-200 text-green-800 shadow-sm' : 'bg-white border-gray-200 text-gray-700 hover:border-green-300' }}">
                                    <div wire:click="seleccionarCategoria({{ $cat->id }})"
                                        class="cursor-pointer flex-1">
                                        <p class="font-bold text-sm">{{ $cat->nombre }}</p>
                                        <p
                                            class="text-[10px] {{ $categoria_seleccionada_id === $cat->id ? 'text-green-600' : 'text-gray-400' }}">
                                            {{ $cat->tipos_analisis_count }} cultivos</p>
                                    </div>
                                    <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button wire:click="abrirModalCategoria({{ $cat->id }})"
                                            class="w-7 h-7 rounded bg-white text-blue-600 hover:bg-blue-100 border border-blue-100 flex items-center justify-center transition-colors"><i
                                                class="fas fa-edit text-xs"></i></button>
                                        <button wire:click="eliminarCategoria({{ $cat->id }})"
                                            class="w-7 h-7 rounded bg-white text-red-600 hover:bg-red-100 border border-red-100 flex items-center justify-center transition-colors"><i
                                                class="fas fa-trash text-xs"></i></button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-gray-400 px-2 italic">No hay categorías de cultivos.</p>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="lg:col-span-8 space-y-4">
            <div class="bg-white shadow-sm rounded-2xl p-5 border border-gray-100 h-[75vh] flex flex-col">
                <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
                    <h2 class="font-bold text-gray-800 text-lg">
                        <i class="fas fa-microscope text-gray-400"></i> Exámenes Registrados
                    </h2>
                    <button wire:click="abrirModalAnalisis"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-sm transition-colors flex items-center gap-2">
                        <i class="fas fa-plus-circle"></i> Nuevo Examen
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-gray-50 border-b border-gray-200 text-xs text-gray-500 uppercase tracking-wider">
                                <th class="p-3 font-bold">Examen</th>
                                <th class="p-3 font-bold text-center">Tipo</th>
                                <th class="p-3 font-bold text-right">Costo</th>
                                <th class="p-3 font-bold text-center">Estado</th>
                                <th class="p-3 font-bold text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse($analisis as $ana)
                                <tr wire:key="row-{{ $ana->id }}" class="hover:bg-gray-50/50 transition-colors">
                                    <td class="p-3">
                                        <p class="font-bold text-gray-800">{{ $ana->nombre }}</p>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase">
                                            {{ $ana->categoria->nombre ?? 'Sin Categoría' }}</p>
                                    </td>
                                    <td class="p-3 text-center">
                                        @if ($ana->categoria && $ana->categoria->es_cultivo)
                                            <span
                                                class="bg-green-50 text-green-700 text-[10px] px-2 py-1 rounded font-bold border border-green-200 flex items-center justify-center gap-1 w-max mx-auto">
                                                <i class="fas fa-bacteria"></i> Cultivo
                                            </span>
                                        @elseif ($ana->tipo_parámetro === 'numerico')
                                            <span
                                                class="bg-blue-50 text-blue-600 text-[10px] px-2 py-1 rounded font-bold border border-blue-100 w-max mx-auto block">123
                                                Numérico</span>
                                        @else
                                            <span
                                                class="bg-purple-50 text-purple-600 text-[10px] px-2 py-1 rounded font-bold border border-purple-100 w-max mx-auto block">Abc
                                                Cualitativo</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-right font-mono font-bold text-gray-700">Bs.
                                        {{ number_format($ana->costo, 2) }}</td>
                                    <td class="p-3 text-center">
                                        <button wire:click="toggleEstadoAnalisis({{ $ana->id }})"
                                            class="px-2 py-1 rounded text-[10px] font-bold transition-colors {{ $ana->estado ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200' }}">
                                            {{ $ana->estado ? 'ACTIVO' : 'INACTIVO' }}
                                        </button>
                                    </td>
                                    <td class="p-3 flex justify-center gap-2">
                                        <button wire:click.prevent="abrirModalAnalisis({{ $ana->id }})"
                                            class="w-8 h-8 rounded-lg bg-gray-100 text-gray-600 hover:bg-blue-100 hover:text-blue-600 transition-colors flex items-center justify-center"><i
                                                class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-gray-400">
                                        <i class="fas fa-box-open text-4xl mb-3 opacity-20"></i>
                                        <p>No hay exámenes registrados en esta vista.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    @if ($mostrarModalCategoria)
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"
                wire:click="$set('mostrarModalCategoria', false)"></div>
            <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-xl w-full max-w-md">
                <div class="bg-blue-600 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-white">{{ $cat_id ? 'Editar Categoría' : 'Nueva Categoría' }}
                    </h3>
                    <button wire:click="$set('mostrarModalCategoria', false)"
                        class="text-blue-200 hover:text-white"><i class="fas fa-times"></i></button>
                </div>
                <div class="p-6">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Nombre de la
                        Categoría</label>
                    <input type="text" wire:model="cat_nombre"
                        class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 focus:ring-blue-500 focus:border-blue-500 mb-4"
                        placeholder="Ej: Bacteriología">
                    @error('cat_nombre')
                        <span class="text-red-500 text-xs mb-4 block">{{ $message }}</span>
                    @enderror

                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-2 border-t pt-4">Configuración
                        de Flujo</label>
                    <label
                        class="relative inline-flex items-center cursor-pointer mt-1 bg-gray-50 p-3 rounded-lg border border-gray-200 w-full">
                        <input type="checkbox" wire:model="cat_es_cultivo" class="sr-only peer">
                        <div
                            class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[14px] after:left-[14px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500">
                        </div>
                        <span class="ml-3 text-sm font-bold text-gray-700">Esta categoría es exclusiva para Cultivos
                            (Microbiología)</span>
                    </label>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t">
                    <button wire:click="$set('mostrarModalCategoria', false)"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50">Cancelar</button>
                    <button wire:click="guardarCategoria"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold shadow-sm hover:bg-blue-700">Guardar</button>
                </div>
            </div>
        </div>
    @endif

    @if ($mostrarModalAnalisis)
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"
                wire:click="$set('mostrarModalAnalisis', false)"></div>
            <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-xl w-full max-w-3xl">
                <div class="bg-blue-800 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2"><i class="fas fa-microscope"></i>
                        {{ $ana_id ? 'Editar Examen' : 'Nuevo Examen' }}</h3>
                    <button wire:click="$set('mostrarModalAnalisis', false)" class="text-blue-200 hover:text-white"><i
                            class="fas fa-times"></i></button>
                </div>
                <div class="p-6 max-h-[70vh] overflow-y-auto custom-scrollbar">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6 pb-6 border-b border-gray-100">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Categoría</label>
                            <select wire:model.live="ana_categoria_id"
                                class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-blue-500">
                                <option value="">Seleccione una categoría...</option>
                                <optgroup label="Análisis Generales">
                                    @foreach ($categoriasNormales as $c)
                                        <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Cultivos / Microbiología">
                                    @foreach ($categoriasCultivo as $c)
                                        <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                            @error('ana_categoria_id')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Nombre del
                                Examen</label>
                            <input type="text" wire:model="ana_nombre"
                                class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:ring-blue-500"
                                placeholder="Ej: Glucosa Basal">
                            @error('ana_nombre')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Costo (Bs.)</label>
                            <input type="number" step="0.5" wire:model="ana_costo"
                                class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 font-mono focus:ring-blue-500"
                                placeholder="0.00">
                            @error('ana_costo')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        @if ($es_categoria_cultivo)
                            <div
                                class="col-span-1 md:col-span-2 bg-green-50 p-4 rounded-xl border border-green-200 flex items-start gap-3 mt-2">
                                <i class="fas fa-bacteria text-green-500 mt-1 text-xl"></i>
                                <div>
                                    <h4 class="font-bold text-green-800 text-sm">El sistema detectó un Cultivo
                                        Microbiológico</h4>
                                    <p class="text-xs text-green-700 mt-1">Como elegiste una categoría marcada para
                                        cultivos, no es necesario definir rangos numéricos. Este examen irá directamente
                                        a la bitácora de incubación y antibiogramas.</p>
                                </div>
                            </div>
                        @else
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Tipo de
                                    Parámetro</label>
                                <select wire:model.live="ana_tipo_parametro"
                                    class="w-full bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg p-2.5 text-sm font-bold focus:ring-yellow-500">
                                    <option value="numerico">🔢 Numérico (Con rangos)</option>
                                    <option value="cualitativo">🔤 Cualitativo / Texto (Positivo, Reactivo...)</option>
                                </select>
                            </div>
                        @endif
                    </div>

                    @if (!$es_categoria_cultivo)
                        @if ($ana_tipo_parametro === 'numerico')
                            <div class="bg-blue-50/50 p-5 rounded-xl border border-blue-100">
                                <h4 class="text-sm font-bold text-blue-800 mb-4 flex items-center gap-2"><i
                                        class="fas fa-sliders-h"></i> Rangos Fisiológicos de Referencia</h4>

                                <div class="mb-5">
                                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Unidad de
                                        Medida</label>
                                    <input type="text" wire:model="ana_unidad_medida"
                                        class="w-1/3 bg-white border border-gray-300 rounded-lg p-2 text-sm focus:ring-blue-500"
                                        placeholder="Ej: mg/dL, g/L">
                                    @error('ana_unidad_medida')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                                        <p
                                            class="text-xs font-bold text-gray-500 uppercase text-center mb-3 border-b pb-2">
                                            <i class="fas fa-male text-blue-400"></i> Rango Masculino</p>
                                        <div class="flex items-center gap-2">
                                            <input type="number" step="0.01" wire:model="ana_rango_min_m"
                                                class="w-1/2 border-gray-300 rounded p-2 text-sm"
                                                placeholder="Mínimo">
                                            <span class="text-gray-400">-</span>
                                            <input type="number" step="0.01" wire:model="ana_rango_max_m"
                                                class="w-1/2 border-gray-300 rounded p-2 text-sm"
                                                placeholder="Máximo">
                                        </div>
                                    </div>
                                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                                        <p
                                            class="text-xs font-bold text-gray-500 uppercase text-center mb-3 border-b pb-2">
                                            <i class="fas fa-female text-pink-400"></i> Rango Femenino</p>
                                        <div class="flex items-center gap-2">
                                            <input type="number" step="0.01" wire:model="ana_rango_min_f"
                                                class="w-1/2 border-gray-300 rounded p-2 text-sm"
                                                placeholder="Mínimo">
                                            <span class="text-gray-400">-</span>
                                            <input type="number" step="0.01" wire:model="ana_rango_max_f"
                                                class="w-1/2 border-gray-300 rounded p-2 text-sm"
                                                placeholder="Máximo">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-purple-50/50 p-5 rounded-xl border border-purple-100">
                                <h4 class="text-sm font-bold text-purple-800 mb-4 flex items-center gap-2"><i
                                        class="fas fa-spell-check"></i> Resultado Esperado (Referencia)</h4>
                                <p class="text-xs text-gray-600 mb-3">Escriba el valor que el sistema considerará como
                                    <strong>Normal</strong> para no activar la alerta roja en el PDF.</p>
                                <input type="text" wire:model="ana_ref_cualitativa"
                                    class="w-full bg-white border border-gray-300 rounded-lg p-2.5 font-bold text-purple-900 focus:ring-purple-500"
                                    placeholder="Ej: Negativo, No Reactivo, N/A">
                                <p class="text-[10px] text-gray-500 mt-1">Use "N/A" para exámenes que no tienen valores
                                    anormales (Ej: Grupo Sanguíneo).</p>
                                @error('ana_ref_cualitativa')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                    @endif

                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-200">
                    <button wire:click="$set('mostrarModalAnalisis', false)"
                        class="px-5 py-2.5 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50">Cancelar</button>
                    <button wire:click="guardarAnalisis"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-sm transition-colors flex items-center gap-2"><i
                            class="fas fa-save"></i> Guardar Examen</button>
                </div>
            </div>
        </div>
    @endif

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
