<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="icon" href="{{ asset('img/logo.ico') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .celeste-gradient { background: linear-gradient(135deg, #38BDF8 0%, #60A5FA 50%, #22D3EE 100%); }
    </style>
</head>
<body class="bg-sky-50 min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-5xl">
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-sky-100">
        <div class="grid grid-cols-1 md:grid-cols-2">

            <!-- IZQUIERDA: formulario (igual estilo login) -->
            <div class="p-8 md:p-10 bg-gradient-to-b from-sky-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="absolute -inset-3 bg-sky-200/40 rounded-full blur-2xl"></div>
                        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="relative w-14 h-14 shadow-lg object-cover rounded-2xl bg-white p-2">
                    </div>
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-slate-900">Clínica Potosí</h2>
                        <p class="text-sm text-slate-500 mt-1">Gestión de Laboratorio • Turnos • Resultados</p>
                    </div>
                </div>

                <h3 class="mt-8 text-3xl font-bold text-slate-900">Crear cuenta</h3>
                <p class="mt-3 text-slate-600">Completa los datos para registrarte.</p>

                @if ($errors->any())
                    <div class="mt-6 mb-5 p-4 bg-red-50 border border-red-200 rounded-xl">
                        <p class="text-red-700 text-sm">{{ $errors->first() }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.post') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nombre</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 10-8 0 4 4 0 008 0zM12 14c-4.418 0-8 1.79-8 4v2h16v-2c0-2.21-3.582-4-8-4z" />
                                </svg>
                            </div>
                            <input
                                id="name"
                                name="name"
                                type="text"
                                required
                                value="{{ old('name') }}"
                                class="block w-full pl-10 pr-3 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-300 focus:border-transparent transition duration-200"
                                placeholder="Tu nombre">
                        </div>
                        @error('name')
                            <span class="text-red-500 text-xs mt-1 block"><i class="fas fa-times-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Correo</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                required
                                value="{{ old('email') }}"
                                class="block w-full pl-10 pr-3 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-300 focus:border-transparent transition duration-200"
                                placeholder="tu@email.com">
                        </div>
                        @error('email')
                            <span class="text-red-500 text-xs mt-1 block"><i class="fas fa-times-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Contraseña</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                class="block w-full pl-10 pr-3 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-300 focus:border-transparent transition duration-200"
                                placeholder="••••••••">
                        </div>
                        @error('password')
                            <span class="text-red-500 text-xs mt-1 block"><i class="fas fa-times-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">Confirmar contraseña</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                required
                                class="block w-full pl-10 pr-3 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-300 focus:border-transparent transition duration-200"
                                placeholder="••••••••">
                        </div>
                        @error('password_confirmation')
                            <span class="text-red-500 text-xs mt-1 block"><i class="fas fa-times-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>



                    <button
                        type="submit"
                        class="w-full celeste-gradient py-3 px-4 rounded-xl font-semibold text-white hover:shadow-lg transform hover:scale-[1.01] transition duration-200 focus:outline-none focus:ring-2 focus:ring-sky-300">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Crear cuenta
                        </span>
                    </button>

                </form>

                <div class="mt-7 pt-6 border-t border-slate-100">
                    <p class="text-center text-slate-500 text-sm">
                        ¿Ya tienes una cuenta?
                        <a href="{{ route('login') }}" class="text-sky-600 hover:text-sky-500 font-semibold transition duration-200 ml-1">
                            Volver al login
                        </a>
                    </p>
                </div>
            </div>

            <!-- DERECHA: panel visual (igual estilo login) -->
            <div class="hidden md:flex items-center justify-center p-10 bg-[radial-gradient(circle_at_top,rgba(56,189,248,0.22),transparent_55%)]">
                <div class="w-full max-w-sm rounded-3xl border border-sky-100 bg-white/70 backdrop-blur p-8 shadow-sm">
                    <div class="text-center">
                        <p class="text-xs font-semibold text-sky-700 uppercase tracking-wider">Registro seguro</p>
                        <h4 class="mt-2 text-xl font-bold text-slate-900">Tu acceso en pocos pasos</h4>
                        <p class="mt-2 text-slate-600 text-sm">Crea tu cuenta y comienza a gestionar.</p>
                    </div>

                    <div class="mt-7 flex items-center justify-center">
                        <img src="{{ asset('img/logo.png') }}" alt="Imagen" class="w-40 h-40 object-contain drop-shadow">
                    </div>

                    <div class="mt-6">
                        <div class="h-2 rounded-full bg-sky-100">
                            <div class="h-2 w-3/4 rounded-full celeste-gradient"></div>
                        </div>
                        <p class="mt-3 text-center text-xs text-slate-500">Listo para operar.</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="px-6 py-5 text-center text-slate-500 text-sm border-t border-slate-100 bg-white">
            <p>&copy; 2025 Clínica Potosí. Todos los derechos reservados.</p>
        </div>
    </div>
</div>

</body>
</html>

