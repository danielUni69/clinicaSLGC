<div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-user-md text-blue-600"></i>
                Gestión de Médicos Solicitantes
            </h1>
            <p class="text-sm text-gray-500 mt-1">Consulte, busque y administre los médicos registrados.</p>
        </div>

        <button type="button" wire:click="abrirCrear"
            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-5 rounded-xl shadow-sm transition-all flex items-center gap-2">
            <i class="fas fa-plus"></i> Nuevo Médico
        </button>
    </div>

    {{-- Mensaje éxito --}}
    @if (session()->has('message'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl shadow-sm flex items-start gap-3" role="alert">
            <i class="fas fa-check-circle text-green-500 mt-1 text-lg"></i>
            <div>
                <p class="font-bold text-sm">¡Operación Exitosa!</p>
                <p class="text-sm">{{ session('message') }}</p>
            </div>
        </div>
    @endif

    {{-- Buscador --}}
    <div class="bg-white shadow-sm rounded-2xl p-4 border border-gray-100 mb-6">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" wire:model.live="search"
                placeholder="Buscar por nombre, especialidad, matrícula o correo..."
                class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition-colors">
        </div>
    </div>

    {{-- MODAL formulario --}}
    @if ($mostrarFormulario)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="cancelarFormulario"></div>

            <div class="relative w-full max-w-3xl mx-4 bg-white shadow-2xl rounded-2xl overflow-hidden border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <span class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold bg-blue-100 text-blue-700">
                            <i class="fas {{ $modo === 'editar' ? 'fa-user-edit' : 'fa-user-plus' }}"></i>
                        </span>
                        {{ $modo === 'editar' ? 'Editar Médico' : 'Nuevo Médico' }}
                    </h2>
                    <button type="button" wire:click="cancelarFormulario"
                        class="px-4 py-2 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-all flex items-center gap-2">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                </div>

                <div class="p-6">
                    <form wire:submit.prevent="guardar" class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            {{-- Nombre Completo --}}
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">
                                    Nombre Completo
                                    <span class="text-gray-400 font-normal normal-case tracking-normal ml-1">mín. 3 · máx. 30 letras</span>
                                </label>
                                <div class="relative">
                                    <input type="text" wire:model.live="nombre_completo"
                                        placeholder="Solo letras y espacios"
                                        maxlength="30"
                                        class="border text-gray-900 text-sm rounded-lg block w-full p-2 pr-16 transition-colors focus:ring-2 focus:outline-none
                                            {{ $errors->has('nombre_completo')
                                                ? 'bg-red-50 border-red-400 focus:ring-red-200 focus:border-red-400'
                                                : (strlen(trim($nombre_completo)) >= 3 && !$errors->has('nombre_completo')
                                                    ? 'bg-green-50 border-green-400 focus:ring-green-200 focus:border-green-400'
                                                    : 'bg-white border-gray-300 focus:ring-blue-200 focus:border-blue-400') }}">
                                    <span class="absolute inset-y-0 right-2 flex items-center gap-1 pointer-events-none">
                                        @if (strlen(trim($nombre_completo)) >= 3 && !$errors->has('nombre_completo'))
                                            <i class="fas fa-check-circle text-green-500"></i>
                                        @endif
                                        <span class="text-xs {{ strlen($nombre_completo) >= 28 ? 'text-orange-400 font-bold' : 'text-gray-400' }}">
                                            {{ strlen($nombre_completo) }}/30
                                        </span>
                                    </span>
                                </div>
                                @error('nombre_completo')
                                    <span class="text-red-500 text-xs mt-1 block flex items-center gap-1">
                                        <i class="fas fa-times-circle"></i> {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            {{-- Matrícula Profesional --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">
                                    Matrícula Profesional
                                    <span class="text-gray-400 font-normal normal-case tracking-normal ml-1">máx. 14</span>
                                </label>
                                <div class="relative">
                                    <input type="text" wire:model.live="matricula_profesional"
                                        placeholder="Ej: MP-12345"
                                        maxlength="14"
                                        class="border text-gray-900 text-sm rounded-lg block w-full p-2 pr-8 transition-colors focus:ring-2 focus:outline-none
                                            {{ $errors->has('matricula_profesional')
                                                ? 'bg-red-50 border-red-400 focus:ring-red-200 focus:border-red-400'
                                                : (strlen(trim($matricula_profesional)) >= 3 && !$errors->has('matricula_profesional')
                                                    ? 'bg-green-50 border-green-400 focus:ring-green-200 focus:border-green-400'
                                                    : 'bg-white border-gray-300 focus:ring-blue-200 focus:border-blue-400') }}">
                                    @if (strlen(trim($matricula_profesional)) >= 3 && !$errors->has('matricula_profesional'))
                                        <span class="absolute inset-y-0 right-2 flex items-center text-green-500 pointer-events-none">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                    @endif
                                </div>
                                @error('matricula_profesional')
                                    <span class="text-red-500 text-xs mt-1 block flex items-center gap-1">
                                        <i class="fas fa-times-circle"></i> {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            {{-- Especialidad --}}
                            <div class="md:col-span-3">
                                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">
                                    Especialidad
                                    <span class="text-gray-400 font-normal normal-case tracking-normal ml-1">opcional · solo letras</span>
                                </label>
                                <div class="relative">
                                    <input type="text" wire:model.live="especialidad"
                                        placeholder="Ej: Cardiología"
                                        maxlength="60"
                                        class="border text-gray-900 text-sm rounded-lg block w-full p-2 pr-8 transition-colors focus:ring-2 focus:outline-none
                                            {{ $errors->has('especialidad')
                                                ? 'bg-red-50 border-red-400 focus:ring-red-200 focus:border-red-400'
                                                : ($especialidad && strlen(trim($especialidad)) >= 3 && !$errors->has('especialidad')
                                                    ? 'bg-green-50 border-green-400 focus:ring-green-200 focus:border-green-400'
                                                    : 'bg-white border-gray-300 focus:ring-blue-200 focus:border-blue-400') }}">
                                    @if ($especialidad && strlen(trim($especialidad)) >= 3 && !$errors->has('especialidad'))
                                        <span class="absolute inset-y-0 right-2 flex items-center text-green-500 pointer-events-none">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                    @endif
                                </div>
                                @error('especialidad')
                                    <span class="text-red-500 text-xs mt-1 block flex items-center gap-1">
                                        <i class="fas fa-times-circle"></i> {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            {{-- Correo --}}
                            <div class="md:col-span-3">
                                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">
                                    Correo
                                    <span class="text-gray-400 font-normal normal-case tracking-normal ml-1">opcional</span>
                                </label>

                                @php
                                    // Detectar dominio para mostrar ícono
                                    $correoValido = $correo && str_contains($correo, '@') && str_contains(explode('@', $correo)[1] ?? '', '.');
                                    $dominio = $correoValido ? strtolower(explode('@', $correo)[1] ?? '') : '';
                                    $esGoogle    = str_contains($dominio, 'gmail') || str_contains($dominio, 'google');
                                    $esMicrosoft = str_contains($dominio, 'outlook') || str_contains($dominio, 'hotmail') || str_contains($dominio, 'live') || str_contains($dominio, 'microsoft');
                                    $esInstitucional = str_contains($dominio, '.edu') || str_contains($dominio, '.gob') || str_contains($dominio, '.gov') || str_contains($dominio, '.edu.bo');
                                @endphp

                                <div class="relative">
                                    {{-- Ícono de dominio a la izquierda --}}
                                    @if ($correoValido)
                                        <div class="absolute inset-y-0 left-2.5 flex items-center pointer-events-none">
                                            @if ($esGoogle)
                                                {{-- G de Google en azul --}}
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                                </svg>
                                            @elseif ($esMicrosoft)
                                                {{-- Logo Microsoft cuadraditos --}}
                                                <svg class="w-4 h-4" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="1" y="1" width="9" height="9" fill="#F25022"/>
                                                    <rect x="11" y="1" width="9" height="9" fill="#7FBA00"/>
                                                    <rect x="1" y="11" width="9" height="9" fill="#00A4EF"/>
                                                    <rect x="11" y="11" width="9" height="9" fill="#FFB900"/>
                                                </svg>
                                            @elseif ($esInstitucional)
                                                {{-- Ícono institucional morado --}}
                                                <i class="fas fa-university text-purple-600" style="font-size:14px;"></i>
                                            @else
                                                {{-- Genérico --}}
                                                <i class="fas fa-envelope text-gray-400" style="font-size:13px;"></i>
                                            @endif
                                        </div>
                                    @endif

                                    <input type="email" wire:model.live="correo"
                                        placeholder="Ej: doctor@gmail.com"
                                        class="border text-gray-900 text-sm rounded-lg block w-full p-2 pr-8 transition-colors focus:ring-2 focus:outline-none
                                            {{ $correoValido ? 'pl-8' : 'pl-2' }}
                                            {{ $errors->has('correo')
                                                ? 'bg-red-50 border-red-400 focus:ring-red-200 focus:border-red-400'
                                                : ($correoValido && !$errors->has('correo')
                                                    ? 'bg-green-50 border-green-400 focus:ring-green-200 focus:border-green-400'
                                                    : 'bg-white border-gray-300 focus:ring-blue-200 focus:border-blue-400') }}">

                                    @if ($correoValido && !$errors->has('correo'))
                                        <span class="absolute inset-y-0 right-2 flex items-center text-green-500 pointer-events-none">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                    @endif
                                </div>

                                {{-- Badge del proveedor --}}
                                @if ($correoValido)
                                    <div class="mt-1.5 flex items-center gap-1.5">
                                        @if ($esGoogle)
                                            <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                                                <i class="fab fa-google text-xs"></i> Google
                                            </span>
                                        @elseif ($esMicrosoft)
                                            <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full bg-orange-50 text-orange-700 border border-orange-100">
                                                <i class="fab fa-microsoft text-xs"></i> Microsoft
                                            </span>
                                        @elseif ($esInstitucional)
                                            <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full bg-purple-50 text-purple-700 border border-purple-100">
                                                <i class="fas fa-university text-xs"></i> Institucional
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                                <i class="fas fa-envelope text-xs"></i> {{ $dominio }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                @error('correo')
                                    <span class="text-red-500 text-xs mt-1 block flex items-center gap-1">
                                        <i class="fas fa-times-circle"></i> {{ $message }}
                                    </span>
                                @enderror
                                <p class="text-xs text-gray-400 mt-1">Solo se permiten letras, números, @, punto, guión y guión bajo.</p>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-3 border-t border-gray-200">
                            <button type="button" wire:click="cancelarFormulario"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-all flex items-center gap-2">
                                <i class="fas fa-times"></i> Cancelar
                            </button>

                            <button type="submit" wire:loading.attr="disabled"
                                class="px-5 py-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition-all flex items-center gap-2 disabled:opacity-70">
                                <i class="fas fa-save"></i>
                                {{ $modo === 'editar' ? 'Guardar Cambios' : 'Guardar Médico' }}
                                <span wire:loading wire:target="guardar" class="flex items-center gap-2">
                                    <i class="fas fa-spinner fa-spin"></i> Procesando...
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
                Médicos Solicitantes
            </h2>
            <span class="bg-blue-100 text-blue-700 text-xs py-1 px-3 rounded-lg font-medium">
                {{ $medicos->total() }} médicos
            </span>
        </div>

        @forelse($medicos as $medico)
            <div class="px-6 py-4 border-b border-gray-50 last:border-b-0 hover:bg-gray-50/50 transition-colors">
                <div class="flex items-center gap-4">
                    {{-- Avatar --}}
                    <div class="w-11 h-11 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0 shadow-sm">
                        {{ strtoupper(substr($medico->nombre_completo, 0, 1)) }}{{ strtoupper(substr(strrchr($medico->nombre_completo, ' '), 1, 1)) }}
                    </div>

                    {{-- Datos --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-base font-bold text-gray-800 truncate">{{ $medico->nombre_completo }}</p>
                        <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1">
                            <span class="text-sm text-gray-500 flex items-center gap-1">
                                <i class="fas fa-id-badge text-gray-300 text-xs"></i>
                                Matrícula: <span class="text-gray-700 font-medium ml-1">{{ $medico->matricula_profesional }}</span>
                            </span>

                            @if ($medico->especialidad)
                                <span class="text-sm text-gray-500 flex items-center gap-1">
                                    <i class="fas fa-stethoscope text-gray-300 text-xs"></i>
                                    <span class="text-gray-700 ml-1">{{ $medico->especialidad }}</span>
                                </span>
                            @endif

                            @if ($medico->correo)
                                <span class="text-sm text-gray-500 flex items-center gap-1">
                                    <i class="fas fa-envelope text-gray-300 text-xs"></i>
                                    <span class="text-gray-700 ml-1">{{ $medico->correo }}</span>
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Acciones --}}
                    <div class="flex items-center gap-2">
                        <button type="button" wire:click="abrirEditar({{ $medico->id }})"
                            class="flex items-center gap-2 text-sm font-medium px-4 py-2 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white transition-all shadow-sm">
                            <i class="fas fa-pen"></i> Editar
                        </button>

                        @if ($confirmando_borrar_id === $medico->id)
                            <div class="flex items-center gap-2">
                                <button type="button" wire:click="borrar({{ $medico->id }})"
                                    class="flex items-center gap-2 text-sm font-bold px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white transition-all shadow-sm">
                                    <i class="fas fa-trash"></i> Confirmar
                                </button>
                                <button type="button" wire:click="confirmarBorrar({{ $medico->id }})"
                                    class="flex items-center gap-2 text-sm font-medium px-3 py-2 rounded-xl border border-red-200 text-red-700 hover:bg-red-50 transition-all">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @else
                            <button type="button" wire:click="confirmarBorrar({{ $medico->id }})"
                                class="flex items-center gap-2 text-sm font-medium px-4 py-2 rounded-xl border border-red-200 text-red-700 hover:bg-red-50 transition-all">
                                <i class="fas fa-trash"></i> Borrar
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                <i class="fas fa-user-doctor text-4xl mb-3 opacity-20"></i>
                <p class="text-sm text-center">No se encontraron médicos.<br>Intente con otro término de búsqueda.</p>
            </div>
        @endforelse

        @if ($medicos->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $medicos->links() }}
            </div>
        @endif
    </div>
</div>