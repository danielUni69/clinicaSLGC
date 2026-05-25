<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Estacionamiento Potosí')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/heropatterns/1.0.0/heropatterns.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Solo necesitas esta línea para Heroicons (outline) -->
    <script src="https://cdn.jsdelivr.net/npm/heroicons@2.1.1/dist/heroicons.min.js"></script>
    <style>
        .gold-gradient {
            background: linear-gradient(135deg, #D4AF37 0%, #FFD700 50%, #FFEC8B 100%);
        }
        .gold-text {
            background: linear-gradient(135deg, #D4AF37 0%, #FFD700 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .nav-item.active {
            background: rgba(212, 175, 55, 0.1);
            border-bottom: 3px solid #D4AF37;
        }
        .nav-item:hover {
            background: rgba(212, 175, 55, 0.05);
        }
        .bg-gray-750 {
            background-color: #374151;
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: #1f2937;
            border: 1px solid #374151;
            border-radius: 8px;
            min-width: 200px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            z-index: 1000;
        }
        .dropdown:hover .dropdown-menu {
            display: block;
        }
    </style>
</head>
<body class="bg-gray-900 font-sans">
    <!-- Layout Container -->
    <div class="flex flex-col h-screen">
        <!-- Top Navigation Bar -->
        <header class="bg-gray-800 border-b border-gray-700">
            <!-- Top Row - Logo and User Info -->
            <div class="flex items-center justify-between px-6 py-3">
                <!-- Logo Section -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 gold-gradient rounded-full flex items-center justify-center">
                        <span class="text-lg">🦙</span>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold gold-text font-['Playfair_Display']">Estacionamiento Potosí</h1>
                        <p class="text-xs text-gray-400">Sistema de Gestión</p>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <div class="relative">
                        <button class="p-2 text-gray-400 hover:text-yellow-500 transition" id="notifications-btn">
                            <i class="fas fa-bell"></i>
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full" id="notification-badge"></span>
                        </button>
                    </div>

                    <!-- User Profile -->
                    <div class="flex items-center space-x-3">
                        <div class="text-right">
                            <p class="text-sm font-medium text-white" id="user-name">Admin User</p>
                            <p class="text-xs text-gray-400" id="user-role">Administrador</p>
                        </div>
                        <div class="w-8 h-8 gold-gradient rounded-full flex items-center justify-center cursor-pointer" id="user-profile">
                            <span class="text-white font-semibold text-sm">AU</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu - Horizontal -->
            <nav class="bg-gray-750 border-t border-gray-700">
                <div class="px-6">
                    <div class="flex items-center space-x-1">
                        <!-- Dashboard -->
                        <a href="#" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg dashboard-link">
                            <i class="fas fa-tachometer-alt w-4 text-yellow-500"></i>
                            <span class="text-sm">Dashboard</span>
                        </a>

                                                <!-- Espacios de Parking -->
                        <a href="#" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg espacios-link">
                            <i class="fas fa-parking w-4 text-yellow-500"></i>
                            <span class="text-sm">Parking</span>
                        </a>
                        <!-- Pagos y Facturación -->
                        <div class="dropdown relative">
                            <a href="#" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg pagos-link">
                                <i class="fas fa-credit-card w-4 text-yellow-500"></i>
                                <span class="text-sm">Pagos</span>
                                <i class="fas fa-chevron-down text-xs ml-1"></i>
                            </a>
                            <div class="dropdown-menu">
                                <a href="#" class="block px-4 py-2 text-gray-200 hover:bg-gray-700 hover:text-white transition">
                                    <i class="fas fa-receipt mr-2 w-4"></i>
                                    Facturación
                                </a>
                                <a href="#" class="block px-4 py-2 text-gray-200 hover:bg-gray-700 hover:text-white transition">
                                    <i class="fas fa-history mr-2 w-4"></i>
                                    Historial de Pagos
                                </a>
                                <a href="#" class="block px-4 py-2 text-gray-200 hover:bg-gray-700 hover:text-white transition">
                                    <i class="fas fa-chart-line mr-2 w-4"></i>
                                    Reportes Financieros
                                </a>
                            </div>
                        </div>

                        <!-- Reportes -->
                        <a href="#" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg reportes-link">
                            <i class="fas fa-chart-bar w-4 text-yellow-500"></i>
                            <span class="text-sm">Reportes</span>
                        </a>

                        <!-- Usuarios -->
                        <a href="#" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg usuarios-link">
                            <i class="fas fa-users w-4 text-yellow-500"></i>
                            <span class="text-sm">Usuarios</span>
                        </a>

                    </div>
                </div>
            </nav>

            <!-- Page Header -->
            <div class="bg-gray-800 border-t border-gray-700">
                <div class="flex items-center justify-between px-6 py-4">
                    <!-- Page Title and Breadcrumbs -->
                    <div class="flex items-center space-x-4">
                        <h2 class="text-xl font-bold text-white" id="page-title">@yield('page-title', 'Dashboard')</h2>
                        <div class="flex items-center space-x-2 text-sm text-gray-400" id="breadcrumbs-container">
                            @hasSection('breadcrumbs')
                                @yield('breadcrumbs')
                            @else
                                <span class="text-yellow-500"><i class="fas fa-home"></i></span>
                                <span><i class="fas fa-chevron-right text-xs"></i></span>
                                <span>Inicio</span>
                            @endif
                        </div>
                    </div>

                    <!-- Search Bar -->
                    <div class="flex-1 max-w-md mx-4">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input
                                type="text"
                                placeholder="Buscar vehículo, reserva o usuario..."
                                class="w-full pl-10 pr-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                id="global-search"
                            >
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="flex items-center space-x-2" id="quick-actions">
                        <button class="px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">
                            <i class="fas fa-plus mr-1"></i>
                            Nuevo Ingreso
                        </button>
                        <button class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm">
                            <i class="fas fa-sign-out-alt mr-1"></i>
                            Registrar Salida
                        </button>
                        <button class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                            <i class="fas fa-print mr-1"></i>
                            Reporte
                        </button>
                    </div>
                </div>

           </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-gray-900">
           @yield('content')
           {{-- {{ $slot }} --}}
        </main>
    </div>

    <!-- Animal Pattern Decorations -->
    <div class="fixed top-10 left-10 opacity-5 text-8xl">🦙</div>
    <div class="fixed bottom-10 right-10 opacity-5 text-8xl">🦙</div>

        @yield('scripts')
</body>
</html>
