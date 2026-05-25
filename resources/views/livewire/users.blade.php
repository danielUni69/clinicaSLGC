<div class="p-6 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <input type="text" wire:model.live="search" placeholder="Buscar usuario por email..."
               class="w-full sm:w-1/3 px-4 py-2 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 transition">
        <button wire:click="confirmUserAddition"
                class="px-4 py-2 gold-gradient text-black font-semibold rounded-lg hover:shadow-lg transition flex items-center justify-center">
            <i class="fas fa-plus mr-2"></i> Nuevo Usuario
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($users as $user)
            <div class="bg-gray-800 rounded-xl shadow-lg p-5 flex flex-col justify-between transition hover:shadow-xl">
                <div>
                    <h3 class="text-lg font-semibold text-white mb-1">{{ $user->name }}</h3>
                    <p class="text-gray-300 text-sm mb-1">{{ $user->email }}</p>
                    <span class="inline-block px-2 py-1 bg-yellow-500/20 text-yellow-400 text-xs rounded-full font-medium">
                        {{ $user->tipo->tipo ?? 'N/A' }}
                    </span>
                </div>
                <div class="mt-4 flex justify-end space-x-3">
                    <button wire:click="confirmUserEdition({{ $user->id }})"
                            class="text-yellow-400 hover:text-yellow-300 transition">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button wire:click="confirmUserDeletion({{ $user->id }})"
                            class="text-red-400 hover:text-red-300 transition">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-400 py-10">
                No se encontraron usuarios.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>

    @if($confirmingUserAddition || $confirmingUserEdition)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-800 rounded-xl p-6 w-full max-w-md">
                <h2 class="text-xl font-bold text-white mb-4">
                    {{ $confirmingUserAddition ? 'Nuevo Usuario' : 'Editar Usuario' }}
                </h2>
                <form wire:submit.prevent="saveUser" class="space-y-4">
                    <div>
                        <label for="name" class="block text-gray-300 text-sm font-medium mb-1">Nombre</label>
                        <input wire:model="name" type="text" id="name"
                               class="w-full px-3 py-2 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 transition">
                        @error('name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-gray-300 text-sm font-medium mb-1">Email</label>
                        <input wire:model="email" type="email" id="email"
                               class="w-full px-3 py-2 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 transition">
                        @error('email') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-gray-300 text-sm font-medium mb-1">Contraseña</label>
                        <input wire:model="password" type="password" id="password"
                               class="w-full px-3 py-2 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 transition">
                        @error('password') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="tipo_usuario_id" class="block text-gray-300 text-sm font-medium mb-1">Tipo de Usuario</label>
                        <select wire:model="tipo_usuario_id" id="tipo_usuario_id"
                                class="w-full px-3 py-2 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 transition">
                            <option value="">Seleccione un tipo</option>
                            @foreach($tiposUsuario as $tipo)
                                <option value="{{ $tipo->id }}">{{ $tipo->tipo }}</option>
                            @endforeach
                        </select>
                        @error('tipo_usuario_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" wire:click="resetInputFields; $set('confirmingUserAddition', false); $set('confirmingUserEdition', false)"
                                class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="px-4 py-2 gold-gradient text-black font-semibold rounded-lg hover:shadow-lg transition">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($confirmingUserDeletion)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-800 rounded-xl p-6 w-full max-w-md">
                <h2 class="text-xl font-bold text-white mb-4">Eliminar Usuario</h2>
                <p class="text-gray-300 mb-6">¿Estás seguro de que deseas eliminar este usuario?</p>
                <div class="flex justify-end space-x-4">
                    <button wire:click="$set('confirmingUserDeletion', false)"
                            class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition">
                        Cancelar
                    </button>
                    <button wire:click="deleteUser"
                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if(session()->has('message'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg transition">
            {{ session('message') }}
        </div>
    @endif
</div>
