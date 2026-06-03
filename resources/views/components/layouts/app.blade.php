<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('img/logo-clinica.ico') }}" type="image/x-icon">

    <title>{{ $title ?? 'SGLC - Clínica Illapa' }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        .clinic-gradient {
            background: linear-gradient(135deg, #1E3A8A 0%, #2563EB 50%, #60A5FA 100%);
        }

        .clinic-text {
            background: linear-gradient(135deg, #60A5FA 0%, #38BDF8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-item.active {
            background: rgba(37, 99, 235, 0.15);
            border-bottom: 3px solid #3B82F6;
        }

        .nav-item:hover {
            background: rgba(37, 99, 235, 0.05);
        }

        .bg-gray-750 {
            background-color: #374151;
        }

        /* Dropdowns */
        .dropdown-menu {
            position: absolute;
            top: 100%;
            background: #1f2937;
            border: 1px solid #374151;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            z-index: 3000;
        }
    </style>

    @livewireStyles
</head>

<body class="bg-gray-900">
    <div class="flex flex-col h-screen">
        <header class="bg-gray-800 border-b border-gray-700">
            <div class="flex items-center justify-between px-6 py-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 flex items-center justify-center bg-blue-100 rounded-full">
                        <i class="fas fa-heartbeat text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-medium clinic-text">Clínica Regional Illapa</h1>
                        <p class="text-xs text-gray-400">Potosí, Bolivia - SGLC</p>
                    </div>
                </div>

                <div class="flex items-center space-x-6">
                    @auth
                        <form method="POST" action="{{ route('logout') }}" class="m-0 p-0 inline-block">
                            @csrf
                            <button type="submit" onclick="return confirm('¿Estás seguro de cerrar sesión?')"
                                class="flex items-center space-x-2 text-red-400 hover:text-red-500 transition-colors bg-transparent border-none cursor-pointer">
                                <i class="fas fa-sign-out-alt"></i>
                                <span class="text-sm font-bold">Cerrar Sesión</span>
                            </button>
                        </form> <!-- Dropdown Perfil -->
                        <div class="relative" x-data="{ open: false }">
                            <div @click="open = !open" class="flex items-center space-x-3 cursor-pointer select-none">
                                <div class="text-right">
                                    <p class="text-sm font-medium text-white flex items-center justify-end space-x-2">
                                        <span>{{ Auth::user()->name }}</span>
                                        @if (Auth::user()->role === 'administrador')
                                            <i class="fas fa-star text-yellow-400 text-xs"></i>
                                        @else
                                            <i class="fas fa-check-circle text-blue-400 text-xs"></i>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-400">{{ ucfirst(Auth::user()->role) }}</p>
                                </div>
                                <div
                                    class="w-8 h-8 clinic-gradient rounded-full flex items-center justify-center shadow-lg cursor-pointer">
                                    <span class="text-white font-bold text-sm">{{ substr(Auth::user()->name, 0, 2) }}</span>
                                </div>
                            </div>

                            <div x-show="open" @click.outside="open = false" class="dropdown-menu right-0 mt-2"
                                style="display: none;">
                                <div class="px-4 py-3 border-b border-gray-600">
                                    <p class="text-sm text-white font-medium">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('profile.show') }}"
                                    class="block px-4 py-2 text-gray-200 hover:bg-gray-700 hover:text-white transition">
                                    <i class="fas fa-user-md mr-2 w-4"></i> Mi Perfil
                                </a>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow">
                            <i class="fas fa-sign-in-alt mr-2"></i> Iniciar Sesión
                        </a>
                    @endauth
                </div>
            </div>

            @auth
                <nav class="bg-gray-750 border-t border-gray-700">
                    <div class="px-6 flex justify-center">
                        <div class="flex space-x-1">

                            @if (in_array(Auth::user()->role, ['administrador', 'recepcionista']))
                                <a href="{{ route('create-servicio') }}"
                                    class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg {{ request()->routeIs('create-servicio') ? 'active' : '' }}">
                                    <i class="fas fa-cash-register w-4 text-green-400"></i>
                                    <span class="text-sm">Punto de Caja</span>
                                </a>

                                <a href="{{ route('pacientes.listar') }}"
                                    class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg {{ request()->routeIs('pacientes.listar*') ? 'active' : '' }}">
                                    <i class="fas fa-users w-4 text-blue-400"></i>
                                    <span class="text-sm">Pacientes</span>
                                </a>

                                <a href="{{ route('medicos.solicitantes') }}"
                                    class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg {{ request()->routeIs('medicos.solicitantes') ? 'active' : '' }}">
                                    <i class="fas fa-user-md w-4 text-indigo-400"></i>
                                    <span class="text-sm">Médicos</span>
                                </a>
                            @endif


                            @if (in_array(Auth::user()->role, ['bioquimico']))
                                <a href="{{ route('laboratorio.panel') }}"
                                    class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg {{ request()->routeIs('laboratorio.*') ? 'active' : '' }}">
                                    <i class="fas fa-microscope w-4 text-purple-400"></i>
                                    <span class="text-sm">Área de Laboratorio</span>
                                </a>
                                <a href="{{ route('admin.catalogo') }}"
                                    class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg {{ request()->routeIs('admin.catalogo') ? 'active' : '' }}">
                                    <i class="fas fa-vials w-4 mr-2 text-blue-400"></i>
                                    <span class="text-sm">Catálogo de Exámenes</span>
                                </a>
                                <a href="{{ route('pacientes.historial') }}"
                                    class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg {{ request()->routeIs('pacientes.historial') ? 'active' : '' }}">
                                    <i class="fas fa-notes-medical w-4 text-indigo-400"></i>
                                    <span class="text-sm">Historial Clínico</span>
                                </a>
                                <a href="{{ route('admin.antibioticos') }}"
                                    class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg {{ request()->routeIs('admin.antibioticos') ? 'active' : '' }}">
                                    <i class="fas fa-pills w-4 mr-2 text-emerald-400"></i>
                                    <span class="text-sm">Inventario de
                                        Antibióticos
                                    </span>
                                </a>
                            @endif
                            <!-- ==================== DROPDOWN ADMINISTRACIÓN ==================== -->
                            @if (Auth::user()->role === 'administrador')
                                <div class="relative" x-data="{ adminOpen: false }">
                                    <button @click="adminOpen = !adminOpen"
                                        class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg cursor-pointer focus:outline-none {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                                        <i class="fas fa-cogs w-4 text-yellow-400"></i>
                                        <span class="text-sm">Administración</span>
                                        <i class="fas fa-chevron-down w-3 h-3 ml-1 text-gray-400"></i>
                                    </button>

                                    <div x-show="adminOpen" @click.outside="adminOpen = false"
                                        class="dropdown-menu left-0 mt-0 w-56 py-2" style="display: none;">

                                        <a href="{{ route('admin.catalogo') }}"
                                            class="block px-4 py-2 text-gray-200 hover:bg-gray-700 hover:text-white transition text-sm">
                                            <i class="fas fa-vials w-4 mr-2 text-blue-400"></i> Catálogo de Exámenes
                                        </a>
                                        <a href="{{ route('admin.antibioticos') }}"
                                            class="block px-4 py-2 text-gray-200 hover:bg-gray-700 hover:text-white transition text-sm">
                                            <i class="fas fa-pills w-4 mr-2 text-emerald-400"></i> Inventario de
                                            Antibióticos
                                        </a>
                                        <hr class="border-gray-600 my-1">
                                        <a href="{{ route('admin.users.list') }}"
                                            class="block px-4 py-2 text-gray-200 hover:bg-gray-700 hover:text-white transition text-sm">
                                            <i class="fas fa-users-cog w-4 mr-2 text-yellow-400"></i> Gestión de Usuarios
                                        </a>

                                        {{-- Botón Nuevo: Turnos (Recepción / Administrador) --}}
                                        <a href="{{ route('turnos.index') }}"
                                            class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg {{ request()->routeIs('turnos.*') ? 'active' : '' }}">
                                            <i class="fas fa-calendar-check w-4 text-teal-400"></i>
                                            <span class="text-sm">Turnos</span>
                                        </a>

                                        <a href="{{ route('reportes.index') }}"
                                            class="block px-4 py-2 text-gray-200 hover:bg-gray-700 hover:text-white transition text-sm">
                                            <i class="fas fa-chart-line w-4 mr-2 text-cyan-400"></i>
                                            Reportes
                                        </a>

                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </nav>
            @endauth
        </header>

        <main class="w-full h-full flex-1 overflow-y-auto bg-white">
            <div class="relative w-full h-full min-h-screen">
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts
    @yield('scripts')

    <!-- Alpine.js -->
</body>

</html>
