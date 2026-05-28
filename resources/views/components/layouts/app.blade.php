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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Roboto', sans-serif; }
        /* Paleta Clínica: Azules y Celestes */
        .clinic-gradient { background: linear-gradient(135deg, #1E3A8A 0%, #2563EB 50%, #60A5FA 100%); }
        .clinic-text { background: linear-gradient(135deg, #60A5FA 0%, #38BDF8 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .nav-item.active { background: rgba(37, 99, 235, 0.15); border-bottom: 3px solid #3B82F6; }
        .nav-item:hover { background: rgba(37, 99, 235, 0.05); }
        .bg-gray-750 { background-color: #374151; }

        /* Estilos del Dropdown */
        /*
          El dropdown del perfil usa CSS hover.
          En páginas con modales/overlays Livewire, el hover se pierde al mover el mouse
          y el menú se “cierra” (por eso se ve y luego desaparece al acercarte).
          Hacemos la apertura estable para que puedas clicar opciones (Perfil / Cerrar sesión).
        */
        .dropdown-menu { display: none; position: absolute; top: 100%; left: 0; background: #1f2937; border: 1px solid #374151; border-radius: 8px; min-width: 200px; box-shadow: 0 10px 25px rgba(0,0,0,0.3); z-index: 1000; }
        /* Abrir/cerrar por hover es inestable con overlays/modales.
           En lugar de cerrar al mover el mouse, mantenemos el dropdown abierto cuando está visible. */
/* Abrir solo al estar enfocado/usar clic (evita cierres al pasar mouse) */
        .dropdown:hover .dropdown-menu { display: none; }
        .dropdown:focus-within .dropdown-menu { display: block; }

        /* Asegura que el dropdown quede por encima siempre */
        .dropdown-menu { z-index: 2000; }

        /* Quitar barras de desplazamiento “raras”/extra que aparecen por overflow en contenedores */
        html, body { overflow: hidden; }
        main { overflow: auto; }
        ::-webkit-scrollbar { width: 0px; height: 0px; }
        * { scrollbar-width: none; }
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

            <div class="flex items-center space-x-4">
                @auth
                    <div class="dropdown relative">
                        <div class="flex items-center space-x-3 cursor-pointer">
                            <div class="text-right">
                                <p class="text-sm font-medium text-white flex items-center justify-end space-x-2">
                                    <span>{{ Auth::user()->name }}</span>
                                    @if(Auth::user()->role === 'administrador')
                                        <i class="fas fa-star text-yellow-400 text-xs"></i>
                                    @else
                                        <i class="fas fa-check-circle text-blue-400 text-xs"></i>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-400">{{ ucfirst(Auth::user()->role) }}</p>
                            </div>
                            <div class="w-8 h-8 clinic-gradient rounded-full flex items-center justify-center shadow-lg">
                                <span class="text-white font-bold text-sm">{{ substr(Auth::user()->name, 0, 2) }}</span>
                            </div>
                        </div>
                        <div class="dropdown-menu right-0 mt-2">
                            <div class="px-4 py-3 border-b border-gray-600">
                                <p class="text-sm text-white font-medium">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-gray-200 hover:bg-gray-700 hover:text-white transition">
                                <i class="fas fa-user-md mr-2 w-4"></i> Mi Perfil
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-red-400 hover:bg-red-900 hover:text-white transition">
                                    <i class="fas fa-sign-out-alt mr-2 w-4"></i> Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow">
                        <i class="fas fa-sign-in-alt mr-2"></i> Iniciar Sesión
                    </a>
                @endauth
            </div>
        </div>

        @auth
        <nav class="bg-gray-750 border-t border-gray-700">
            <div class="px-6 flex justify-center">
                <div class="flex space-x-1">

                    <a href="{{ route('create-servicio') }}" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg {{ request()->routeIs('create-servicio') ? 'active' : '' }}">
                        <i class="fas fa-chart-line w-4 text-blue-400"></i>
                        <span class="text-sm">Panel Principal</span>
                    </a>

                    <a href="{{ route('pacientes.listar') }}" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg {{ request()->routeIs('pacientes.listar') ? 'active' : '' }}">
                        <i class="fas fa-users w-4 text-blue-400"></i>
                        <span class="text-sm">Pacientes</span>
                    </a>

                    <a href="{{ route('medicos.solicitantes') }}" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg {{ request()->routeIs('medicos.solicitantes') ? 'active' : '' }}">
                        <i class="fas fa-user-md w-4 text-indigo-400"></i>
                        <span class="text-sm">Doctor Solicitante</span>
                    </a>

                    @if(in_array(Auth::user()->role, ['administrador', 'recepcionista']))
                    <a href="#" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg">
                        <i class="fas fa-notes-medical w-4 text-green-400"></i>
                        <span class="text-sm">Caja y Servicios</span>
                    </a>
                    @endif

                    @if(in_array(Auth::user()->role, ['administrador', 'bioquimico']))
                    <a href="#" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg">
                        <i class="fas fa-flask w-4 text-purple-400"></i>
                        <span class="text-sm">Laboratorio</span>
                    </a>
                    @endif

                    @if(Auth::user()->role === 'administrador')
                    <div class="dropdown relative group">
                        <button class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg w-full h-full cursor-pointer focus:outline-none">
                            <i class="fas fa-cogs w-4 text-gray-400"></i>
                            <span class="text-sm">Administración</span>
                            <i class="fas fa-chevron-down w-3 h-3 ml-1 text-gray-400"></i>
                        </button>

                        <div class="dropdown-menu left-0 mt-0 w-48 py-2">
                            <a href="#" class="block px-4 py-2 text-gray-200 hover:bg-gray-700 hover:text-white transition text-sm">
                                <i class="fas fa-vials w-4 mr-2 text-blue-400"></i> Catálogo Análisis
                            </a>
                            <a href="#" class="block px-4 py-2 text-gray-200 hover:bg-gray-700 hover:text-white transition text-sm">
                                <i class="fas fa-users w-4 mr-2 text-blue-400"></i> Usuarios
                            </a>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </nav>
        @endauth
    </header>

    <main class="flex-1 overflow-y-auto bg-gray-900">
        {{-- Contenedor del contenido (no tocar z-index para no romper interacciones del perfil) --}}
        <div class="relative">
            {{ $slot }}
        </div>
    </main>
</div>

@livewireScripts
@yield('scripts')
</body>
</html>
