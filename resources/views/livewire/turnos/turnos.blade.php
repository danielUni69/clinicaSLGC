<div>
    <div class="max-w-6xl mx-auto p-6">
        <h2 class="text-2xl font-bold mb-6 text-white">Turnos</h2>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Formulario -->
            <div class="lg:col-span-1 bg-white rounded-lg shadow p-4">
                <form wire:submit.prevent="guardar" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Bioquímico (usuario)</label>
                        <select wire:model="user_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Seleccione...</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->role }})</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha</label>
                        <input type="date" wire:model="fecha" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                        @error('fecha')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700">
                            Guardar turno
                        </button>
                    </div>
                </form>
            </div>

            <!-- Listado -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow p-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Buscar</label>
                        <input type="text" wire:model.live="search" class="mt-1 block w-full sm:w-80 border-gray-300 rounded-md shadow-sm" placeholder="nombre/email/rol" />
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">ID</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">user_id</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">fecha</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">created_at</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">updated_at</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($turnos as $t)
                                <tr>
                                    <td class="px-3 py-2 text-sm">{{ $t->id }}</td>
                                    <td class="px-3 py-2 text-sm">{{ $t->user_id }}</td>
                                    <td class="px-3 py-2 text-sm">{{ optional($t->fecha)->format('Y-m-d') }}</td>
                                    <td class="px-3 py-2 text-sm">{{ $t->created_at }}</td>
                                    <td class="px-3 py-2 text-sm">{{ $t->updated_at }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-6 text-center text-sm text-gray-500">
                                        No hay turnos.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $turnos->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

