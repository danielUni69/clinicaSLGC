<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="{{ asset('img/logo.ico') }}" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .gold-gradient {
            background: linear-gradient(135deg, #D4AF37 0%, #FFD700 50%, #FFEC8B 100%);
        }
        .gold-text {
            background: linear-gradient(135deg, #D4AF37 0%, #FFD700 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-black min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="w-16 h-16 shadow-lg object-cover">
            </div>
            <h2 class="text-3xl font-bold gold-text">
                Estacionamiento JEMITA
            </h2>
            <p class="mt-2 text-gray-400">Sistema de Gestión de Parking</p>
        </div>

        <div class="bg-gray-900 rounded-2xl shadow-2xl p-8 border border-yellow-600/20">
            <h2 class="text-2xl font-bold text-center text-white mb-8">
                Iniciar Sesión
            </h2>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-900/50 border border-red-700 rounded-lg">
                    <p class="text-red-200 text-sm">{{ $errors->first() }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                        Correo Electrónico
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            required
                            class="block w-full pl-10 pr-3 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition duration-200"
                            placeholder="tu@email.com"
                        >
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                        Contraseña
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            class="block w-full pl-10 pr-3 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition duration-200"
                            placeholder="••••••••"
                        >
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full gold-gradient py-3 px-4 rounded-lg font-semibold text-black hover:shadow-lg transform hover:scale-105 transition duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-gray-900"
                >
                    <span class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Ingresar al Sistema
                    </span>
                </button>
            </form>

            @if(\App\Models\User::count() === 0)
                <div class="mt-8 pt-6 border-t border-gray-700">
                    <p class="text-center text-gray-400 text-sm">
                        ¿No tienes una cuenta?
                        <a href="{{ route('register') }}" class="text-yellow-500 hover:text-yellow-400 font-medium transition duration-200 ml-1">
                            Crear cuenta nueva
                        </a>
                    </p>
                </div>
            @endif
        </div>

        <div class="text-center text-gray-500 text-sm mt-8">
            <p>&copy; 2025 Estacionamiento Potosí. Todos los derechos reservados.</p>
        </div>
    </div>

    <div class="fixed top-10 left-10 opacity-5 text-8xl">🦙</div>
    <div class="fixed bottom-10 right-10 opacity-5 text-8xl">🦙</div>
    <div class="fixed top-1/4 right-20 opacity-5 text-6xl">🦙</div>
    <div class="fixed bottom-1/3 left-20 opacity-5 text-6xl">🦙</div>
</body>
</html>
