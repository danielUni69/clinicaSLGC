<div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">

     {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-users text-blue-600"></i>
                Gestión de Pacientes
            </h1>
            <p class="text-sm text-gray-500 mt-1">Listado de todos los usuarios del sistema.</p>
        </div>

        <button
    type="button"
    onclick="window.location.href='{{ route('register') }}'"
    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-5 rounded-xl shadow-sm transition-all flex items-center gap-2">
    <i class="fas fa-plus"></i> Nuevo Usuario
</button>
        
    </div>

    {{-- Buscador --}}
    <div class="bg-white shadow-sm rounded-2xl p-4 border border-gray-100 mb-6">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input
                type="text"
                wire:model.live="search"
                placeholder="Buscar usuario por nombre, email o rol..."
                class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition-colors"
            >
        </div>
    </div>

    {{-- Lista --}}
    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden relative">
        <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>

        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <span class="bg-blue-100 text-blue-700 w-8 h-8 rounded-full flex items-center justify-center text-sm">
                    <i class="fas fa-users text-xs"></i>
                </span>
                Usuarios
            </h2>
            <span class="bg-blue-100 text-blue-700 text-xs py-1 px-3 rounded-lg font-medium">
                {{ $users->total() }} usuarios
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Rol</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b border-gray-50 last:border-b-0 hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-400">{{ $user->id }}</td>

                            {{-- Nombre con avatar --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(strrchr($user->name, ' '), 1, 1)) }}
                                    </div>
                                    <span class="text-sm font-semibold text-gray-800">{{ $user->name }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-500">{{ $user->email }}</td>

                            {{-- Rol con badge de color --}}
                            <td class="px-6 py-4">
                                @php
                                    $rolClases = match(strtolower($user->role)) {
                                        'admin'      => 'bg-blue-100 text-blue-800 border border-blue-200',
                                        'medico'     => 'bg-green-100 text-green-800 border border-green-200',
                                        'recepcion'  => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                        default      => 'bg-gray-100 text-gray-700 border border-gray-200',
                                    };
                                @endphp
                                <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $rolClases }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>

                            {{-- Estado --}}
                            <td class="px-6 py-4">
                                @if($user->active)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full bg-green-100 text-green-800 border border-green-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span>
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full bg-red-100 text-red-800 border border-red-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span>
                                        Inactivo
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                                    <i class="fas fa-user-slash text-4xl mb-3 opacity-20"></i>
                                    <p class="text-sm text-center">No se encontraron usuarios.<br>Intente con otro término de búsqueda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>