<div class="max-w-5xl mx-auto py-8 sm:px-6 lg:px-8">

    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-pills text-emerald-600"></i>
                Inventario de Antibióticos
            </h1>
            <p class="text-sm text-gray-500 mt-1">Gestione los medicamentos disponibles para las pruebas de Antibiograma
                en Cultivos.</p>
        </div>
        <button wire:click="abrirModal"
            class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-sm transition-colors flex items-center gap-2">
            <i class="fas fa-plus-circle"></i> Nuevo Antibiótico
        </button>
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

    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="p-4 font-bold">Nombre del Antibiótico / Químico</th>
                    <th class="p-4 font-bold text-center w-32">Estado en Lab</th>
                    <th class="p-4 font-bold text-center w-32">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($antibioticos as $anti)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4 font-bold text-gray-800">{{ $anti->nombre_antibiotico }}</td>
                        <td class="p-4 text-center">
                            <button wire:click="toggleEstado({{ $anti->id }})"
                                class="px-3 py-1 rounded-md text-[10px] font-bold uppercase transition-colors shadow-sm
                                {{ $anti->estado ? 'bg-green-100 text-green-700 hover:bg-green-200 border border-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200 border border-red-200' }}">
                                {{ $anti->estado ? 'Disponible' : 'Agotado' }}
                            </button>
                        </td>
                        <td class="p-4 flex justify-center gap-2">
                            <button wire:click="abrirModal({{ $anti->id }})"
                                class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 border border-blue-100 flex items-center justify-center transition-colors"
                                title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="eliminar({{ $anti->id }})"
                                class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 border border-red-100 flex items-center justify-center transition-colors"
                                title="Eliminar">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-12 text-center text-gray-400">
                            <i class="fas fa-pills text-4xl mb-3 opacity-20"></i>
                            <p>No hay antibióticos registrados en el inventario.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($mostrarModal)
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"
                wire:click="$set('mostrarModal', false)"></div>
            <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-xl w-full max-w-md">

                <div class="bg-emerald-600 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fas fa-capsules"></i> {{ $anti_id ? 'Editar Antibiótico' : 'Nuevo Antibiótico' }}
                    </h3>
                    <button wire:click="$set('mostrarModal', false)"
                        class="text-emerald-200 hover:text-white transition-colors"><i
                            class="fas fa-times text-xl"></i></button>
                </div>

                <div class="p-6">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Nombre
                        Comercial o Genérico</label>
                    <input type="text" wire:model="nombre_antibiotico" wire:keydown.enter="guardar"
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-xl p-3 focus:ring-emerald-500 focus:border-emerald-500 font-bold"
                        placeholder="Ej: Amoxicilina, Ciprofloxacina...">
                    @error('nombre_antibiotico')
                        <span class="text-red-500 text-xs mt-1.5 block font-semibold"><i
                                class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-100">
                    <button wire:click="$set('mostrarModal', false)"
                        class="px-5 py-2.5 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm transition-colors">Cancelar</button>
                    <button wire:click="guardar"
                        class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-sm transition-colors flex items-center gap-2">
                        <i class="fas fa-save"></i> Guardar Medicamento
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>
