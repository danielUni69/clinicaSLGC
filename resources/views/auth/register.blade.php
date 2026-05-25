<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Estacionamiento Potosí</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
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
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }
    </style>
</head>
<body class="bg-black min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 gold-gradient rounded-full flex items-center justify-center shadow-lg">
                    <span class="text-2xl">🦙</span>
                </div>
            </div>
            <h2 class="text-3xl font-bold gold-text font-['Playfair_Display']">
                Estacionamiento Potosí
            </h2>
            <p class="mt-2 text-gray-400">Crear Nueva Cuenta</p>
        </div>

        <!-- Registration Card -->
        <div class="bg-gray-900 rounded-2xl shadow-2xl p-8 border border-yellow-600/20">
            <h2 class="text-2xl font-bold text-center text-white mb-8">
                Registrar Usuario
            </h2>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-900/50 border border-red-700 rounded-lg">
                    <ul class="text-red-200 text-sm space-y-1">
                        @foreach ($errors->all() as $err)
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $err }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" class="space-y-6">
                @csrf

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                        Nombre Completo
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            required
                            class="block w-full pl-10 pr-3 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition duration-200 input-focus"
                            placeholder="Tu nombre completo"
                            value="{{ old('name') }}"
                        >
                    </div>
                </div>

                <!-- Email Field -->
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
                            class="block w-full pl-10 pr-3 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition duration-200 input-focus"
                            placeholder="tu@email.com"
                            value="{{ old('email') }}"
                        >
                    </div>
                </div>

                <!-- Password Field -->
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
                            class="block w-full pl-10 pr-3 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition duration-200 input-focus"
                            placeholder="••••••••"
                        >
                    </div>
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                        Repetir Contraseña
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            required
                            class="block w-full pl-10 pr-3 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition duration-200 input-focus"
                            placeholder="••••••••"
                        >
                    </div>
                </div>

                <!-- User Type Field -->
                <div>
                    <label for="tipo_usuario_id" class="block text-sm font-medium text-gray-300 mb-2">
                        Tipo de Usuario
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <select
                            id="tipo_usuario_id"
                            name="tipo_usuario_id"
                            required
                            class="block w-full pl-10 pr-3 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition duration-200 appearance-none"
                        >
                            @foreach ($tipos as $t)
                                <option value="{{ $t->id }}" {{ old('tipo_usuario_id') == $t->id ? 'selected' : '' }}>
                                    {{ $t->tipo }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <svg class="h-4 w-4 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Terms Agreement -->
                <div class="flex items-start space-x-3">
                    <input
                        id="terms"
                        name="terms"
                        type="checkbox"
                        required
                        class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-600 rounded bg-gray-800 mt-1"
                    >
                    <label for="terms" class="text-sm text-gray-300">
                        Acepto los <a href="#" class="text-yellow-500 hover:text-yellow-400 underline">términos y condiciones</a> y la <a href="#" class="text-yellow-500 hover:text-yellow-400 underline">política de privacidad</a>
                    </label>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full gold-gradient py-3 px-4 rounded-lg font-semibold text-black hover:shadow-lg transform hover:scale-105 transition duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-gray-900"
                >
                    <span class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Crear Cuenta
                    </span>
                </button>
            </form>

            <!-- Divider -->
            <div class="mt-8 pt-6 border-t border-gray-700">
                <p class="text-center text-gray-400 text-sm">
                    ¿Ya tienes una cuenta?
                    <a href="{{ route('login') }}" class="text-yellow-500 hover:text-yellow-400 font-medium transition duration-200 ml-1">
                        Volver al login
                    </a>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-gray-500 text-sm mt-8">
            <p>&copy; 2024 Estacionamiento Potosí. Sistema de gestión de parking.</p>
        </div>
    </div>

    <!-- Animal Pattern Decorations -->
    <div class="fixed top-10 left-10 opacity-5 text-8xl">🦙</div>
    <div class="fixed bottom-10 right-10 opacity-5 text-8xl">🦙</div>
    <div class="fixed top-1/4 right-20 opacity-5 text-6xl">🦙</div>
    <div class="fixed bottom-1/3 left-20 opacity-5 text-6xl">🦙</div>
</body>
</html>
