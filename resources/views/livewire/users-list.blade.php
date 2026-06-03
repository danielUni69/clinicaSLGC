<div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">

    {{-- ── Flash message ───────────────────────────────────────────────────── --}}
    @if (session('message'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3500)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="mb-5 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800
                    text-sm font-medium px-5 py-3.5 rounded-2xl shadow-sm">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0
                    00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0
                    001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('message') }}
        </div>
    @endif

    {{-- ── Header ──────────────────────────────────────────────────────────── --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-users text-blue-600"></i>
                Gestión de Usuarios
            </h1>
            <p class="text-sm text-gray-500 mt-1">Administra los usuarios del sistema.</p>
        </div>

        <a href="{{ route('register') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-5 rounded-xl
                  shadow-sm transition-all flex items-center gap-2 text-sm">
            <i class="fas fa-plus"></i> Nuevo Usuario
        </a>
    </div>

    {{-- ── Buscador ─────────────────────────────────────────────────────────── --}}
    <div class="bg-white shadow-sm rounded-2xl p-4 border border-gray-100 mb-6">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input
                type="text"
                wire:model.live="search"
                placeholder="Buscar por nombre, email o rol…"
                class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl
                       focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition-colors"
            >
        </div>
    </div>

    {{-- ── Tabla ────────────────────────────────────────────────────────────── --}}
    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden relative">
        <div class="absolute top-0 left-0 w-1 h-full bg-blue-500 rounded-l-2xl"></div>

        {{-- Cabecera de la tarjeta --}}
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
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b border-gray-50 last:border-b-0 hover:bg-gray-50/50 transition-colors
                                   {{ ! $user->active ? 'opacity-60' : '' }}">

                            {{-- ID --}}
                            <td class="px-6 py-4 text-sm text-gray-400">{{ $user->id }}</td>

                            {{-- Nombre + avatar --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0
                                                {{ $user->active ? 'bg-blue-100 text-blue-600' : 'bg-gray-200 text-gray-500' }}">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(strrchr($user->name, ' ') ?: ' ', 1, 1)) }}
                                    </div>
                                    <span class="text-sm font-semibold text-gray-800">{{ $user->name }}</span>
                                </div>
                            </td>

                            {{-- Email --}}
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $user->email }}</td>

                            {{-- Rol --}}
                            <td class="px-6 py-4">
                                @php
                                    $rolClases = match(strtolower($user->role)) {
                                        'admin'         => 'bg-blue-100 text-blue-800 border border-blue-200',
                                        'bioquimico'    => 'bg-teal-100 text-teal-800 border border-teal-200',
                                        'recepcionista' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                        default         => 'bg-gray-100 text-gray-700 border border-gray-200',
                                    };
                                    $rolIcono = match(strtolower($user->role)) {
                                        'admin'         => 'fa-shield-alt',
                                        'bioquimico'    => 'fa-flask',
                                        'recepcionista' => 'fa-calendar-alt',
                                        default         => 'fa-user',
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full {{ $rolClases }}">
                                    <i class="fas {{ $rolIcono }} text-[10px]"></i>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>

                            {{-- Estado --}}
                            <td class="px-6 py-4">
                                @if($user->active)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1
                                                 rounded-full bg-green-100 text-green-800 border border-green-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1
                                                 rounded-full bg-red-100 text-red-800 border border-red-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Inactivo
                                    </span>
                                @endif
                            </td>

                            {{-- Acciones --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">

                                    {{-- Botón editar --}}
                                    <button
                                        wire:click="abrirEditar({{ $user->id }})"
                                        title="Editar usuario"
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg
                                               bg-blue-50 text-blue-700 border border-blue-200
                                               hover:bg-blue-100 transition-colors">
                                        <i class="fas fa-pen text-[10px]"></i>
                                        Editar
                                    </button>

                                    {{-- Botón dar de baja / activar (sin confirmación aún) --}}
                                    @if($confirmando_baja_id !== $user->id)
                                        <button
                                            wire:click="toggleBaja({{ $user->id }})"
                                            title="{{ $user->active ? 'Dar de baja' : 'Activar usuario' }}"
                                            class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg border transition-colors
                                                   {{ $user->active
                                                        ? 'bg-orange-50 text-orange-700 border-orange-200 hover:bg-orange-100'
                                                        : 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100' }}">
                                            <i class="fas {{ $user->active ? 'fa-user-slash' : 'fa-user-check' }} text-[10px]"></i>
                                            {{ $user->active ? 'Dar de baja' : 'Activar' }}
                                        </button>

                                    {{-- Confirmación de baja / activación --}}
                                    @else
                                        <div class="flex items-center gap-1.5 bg-orange-50 border border-orange-200 rounded-xl px-3 py-1.5">
                                            <span class="text-xs text-orange-700 font-semibold">¿Confirmar?</span>
                                            {{-- Sí --}}
                                            <button
                                                wire:click="toggleBaja({{ $user->id }})"
                                                class="text-xs font-bold text-white bg-orange-500 hover:bg-orange-600
                                                       px-2 py-0.5 rounded-lg transition-colors">
                                                Sí
                                            </button>
                                            {{-- No --}}
                                            <button
                                                wire:click="cancelarBaja"
                                                class="text-xs font-bold text-gray-600 bg-gray-200 hover:bg-gray-300
                                                       px-2 py-0.5 rounded-lg transition-colors">
                                                No
                                            </button>
                                        </div>
                                    @endif

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                                    <i class="fas fa-user-slash text-4xl mb-3 opacity-20"></i>
                                    <p class="text-sm text-center">
                                        No se encontraron usuarios.<br>
                                        Intenta con otro término de búsqueda.
                                    </p>
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


    {{-- ══════════════════════════════════════════════════════════════════════
         MODAL — Editar usuario
    ══════════════════════════════════════════════════════════════════════ --}}
    @if($mostrarEditar)
        {{-- Overlay --}}
        <div
            class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm"
            wire:click="cerrarEditar"
        ></div>

        {{-- Panel del modal --}}
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">

                {{-- Cabecera modal --}}
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100
                            bg-gradient-to-r from-blue-50 to-white">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user-edit text-sm"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-base">Editar usuario</h3>
                            <p class="text-xs text-gray-400">ID #{{ $editando_id }}</p>
                        </div>
                    </div>
                    <button wire:click="cerrarEditar"
                            class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors text-gray-500">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                {{-- Cuerpo modal --}}
                <div class="px-6 py-6 space-y-5">

                    {{-- Nombre --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Nombre completo
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-blue-400 text-sm"></i>
                            </div>
                            <input
                                type="text"
                                wire:model="edit_name"
                                maxlength="60"
                                placeholder="Nombre del usuario"
                                class="block w-full pl-9 pr-3 py-2.5 bg-white border border-gray-200 rounded-xl
                                       text-gray-900 text-sm placeholder-gray-400
                                       focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent transition">
                        </div>
                        @error('edit_name')
                            <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Correo electrónico
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-blue-400 text-sm"></i>
                            </div>
                            <input
                                type="email"
                                wire:model="edit_email"
                                maxlength="255"
                                placeholder="usuario@ejemplo.com"
                                class="block w-full pl-9 pr-3 py-2.5 bg-white border border-gray-200 rounded-xl
                                       text-gray-900 text-sm placeholder-gray-400
                                       focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent transition">
                        </div>
                        @error('edit_email')
                            <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Rol --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Rol
                        </label>
                        <div class="grid grid-cols-3 gap-2">

                            @foreach([
                                ['value' => 'admin',         'label' => 'Admin',         'icon' => 'fa-shield-alt',   'color' => 'blue'],
                                ['value' => 'bioquimico',    'label' => 'Bioquímico',    'icon' => 'fa-flask',        'color' => 'teal'],
                                ['value' => 'recepcionista', 'label' => 'Recepcionista', 'icon' => 'fa-calendar-alt', 'color' => 'yellow'],
                            ] as $rol)

                                <label class="cursor-pointer">
                                    <input type="radio"
                                           wire:model="edit_role"
                                           value="{{ $rol['value'] }}"
                                           class="sr-only peer">
                                    <div class="flex flex-col items-center gap-1.5 p-3 rounded-xl border-2 border-gray-200 bg-white text-center
                                                peer-checked:border-blue-500 peer-checked:bg-blue-50
                                                hover:border-blue-300 transition-all cursor-pointer">
                                        <div class="w-8 h-8 rounded-full bg-gray-100 peer-checked:bg-blue-100 flex items-center justify-center">
                                            <i class="fas {{ $rol['icon'] }} text-gray-500 text-sm"></i>
                                        </div>
                                        <span class="text-xs font-semibold text-gray-700">{{ $rol['label'] }}</span>
                                    </div>
                                </label>

                            @endforeach

                        </div>
                        @error('edit_role')
                            <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                {{-- Footer modal --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button
                        wire:click="cerrarEditar"
                        class="px-4 py-2 text-sm font-semibold text-gray-600 bg-white border border-gray-200
                               rounded-xl hover:bg-gray-100 transition-colors">
                        Cancelar
                    </button>
                    <button
                        wire:click="guardarEdicion"
                        class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700
                               rounded-xl shadow-sm transition-colors flex items-center gap-2">
                        <i class="fas fa-save text-xs"></i>
                        Guardar cambios
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>