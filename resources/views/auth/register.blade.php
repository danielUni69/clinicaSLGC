<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro — Clínica Potosí</title>
    <link rel="icon" href="{{ asset('img/logo.ico') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Sora:wght@700;800&display=swap" rel="stylesheet">

    {{-- CAPTCHA propio — sin dependencias externas --}}

    <style>
        :root {
            --sky:       #0EA5E9;
            --sky-light: #BAE6FD;
            --sky-dark:  #0369A1;
            --teal:      #0D9488;
            --green:     #16A34A;
            --amber:     #D97706;
            --red:       #DC2626;
            --slate-900: #0F172A;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #F0F9FF;
            min-height: 100vh;
        }

        /* ── Fondo animado ── */
        .bg-mesh {
            background:
                radial-gradient(ellipse 80% 60% at 10% -10%, rgba(14,165,233,.18) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 90% 110%, rgba(13,148,136,.14) 0%, transparent 55%),
                #F0F9FF;
        }

        /* ── Título display ── */
        .font-display { font-family: 'Sora', sans-serif; }

        /* ── Gradiente botón ── */
        .btn-primary {
            background: linear-gradient(135deg, #0EA5E9 0%, #0D9488 100%);
            transition: all .25s ease;
            box-shadow: 0 4px 20px rgba(14,165,233,.35);
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 28px rgba(14,165,233,.45);
        }
        .btn-primary:active { transform: translateY(0); }

        /* ── Inputs ── */
        .field-input {
            display: block; width: 100%;
            padding: .75rem .75rem .75rem 2.75rem;
            background: #fff;
            border: 1.5px solid #E2E8F0;
            border-radius: .875rem;
            color: #0F172A;
            font-size: .9rem;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }
        .field-input:focus {
            border-color: #0EA5E9;
            box-shadow: 0 0 0 3px rgba(14,165,233,.18);
        }
        .field-input.is-valid   { border-color: #16A34A; }
        .field-input.is-invalid { border-color: #DC2626; }

        /* ── Icono campo ── */
        .field-icon {
            position: absolute; left: .85rem; top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
        }

        /* ── Hint de validación ── */
        .hint { font-size: .72rem; margin-top: .3rem; display: flex; align-items: center; gap: .3rem; }
        .hint-ok  { color: #16A34A; }
        .hint-err { color: #DC2626; }
        .hint-neu { color: #64748B; }

        /* ── Barra de seguridad de contraseña ── */
        #strength-bar-wrap {
            height: 6px; border-radius: 9999px;
            background: #E2E8F0; overflow: hidden;
            margin-top: .5rem;
        }
        #strength-bar {
            height: 100%; border-radius: 9999px;
            width: 0%; transition: width .35s ease, background .35s ease;
        }

        /* ── Tarjeta de rol ── */
        .role-card {
            display: flex; align-items: center; gap: .75rem;
            padding: 1rem; border-radius: .875rem;
            border: 2px solid #E2E8F0;
            background: #fff; cursor: pointer;
            transition: border-color .2s, background .2s, box-shadow .2s;
            user-select: none;
        }
        .role-card:hover { border-color: var(--sky); background: #F0F9FF; }
        .role-card.selected {
            border-color: #16A34A !important;
            background: #F0FDF4 !important;
            box-shadow: 0 0 0 3px rgba(22,163,74,.15);
        }
        .role-card.selected .role-dot { background: #16A34A; }
        .role-dot {
            width: .65rem; height: .65rem; border-radius: 50%;
            background: #CBD5E1; flex-shrink: 0;
            transition: background .2s;
        }
        .role-icon-wrap {
            width: 2.5rem; height: 2.5rem; border-radius: 50%;
            background: #E0F2FE;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .role-card.selected .role-icon-wrap { background: #DCFCE7; }
        .role-card.selected .role-icon-wrap svg { color: #16A34A; }

        /* ── Barra de pasos ── */
        .step-bar { height: 4px; border-radius: 9999px; background: #BAE6FD; overflow: hidden; }
        .step-fill { height: 100%; background: linear-gradient(90deg,#0EA5E9,#0D9488); border-radius: 9999px; transition: width .4s ease; }

        /* ── Panel derecho decorativo ── */
        .panel-right {
            background:
                radial-gradient(ellipse 80% 70% at 50% 0%, rgba(14,165,233,.22), transparent 60%),
                linear-gradient(175deg, #F0F9FF 0%, #ECFDF5 100%);
        }

        /* ── Chips de características ── */
        .feat-chip {
            display: flex; align-items: center; gap: .5rem;
            padding: .55rem .9rem; border-radius: 9999px;
            background: #fff; border: 1px solid #BAE6FD;
            font-size: .78rem; font-weight: 600; color: #0369A1;
            box-shadow: 0 2px 8px rgba(14,165,233,.1);
        }
        .feat-chip svg { color: #0EA5E9; }

        /* ── Mostrar/ocultar contraseña ── */
        .toggle-pass {
            position: absolute; right: .85rem; top: 50%;
            transform: translateY(-50%);
            cursor: pointer; color: #94A3B8;
            background: none; border: none; padding: 0;
            line-height: 0;
        }
        .toggle-pass:hover { color: #0EA5E9; }

        /* ── Animación entrada ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp .5s ease both; }
        .fade-up-1 { animation-delay: .05s; }
        .fade-up-2 { animation-delay: .12s; }
        .fade-up-3 { animation-delay: .19s; }
        .fade-up-4 { animation-delay: .26s; }
        .fade-up-5 { animation-delay: .33s; }
        .fade-up-6 { animation-delay: .40s; }
        .fade-up-7 { animation-delay: .47s; }

        /* ── CAPTCHA propio ── */
        .captcha-box {
            display: flex; align-items: center; gap: .75rem;
            background: #fff; border: 1.5px solid #E2E8F0;
            border-radius: .875rem; padding: .85rem 1rem;
            transition: border-color .2s;
        }
        .captcha-box.ok  { border-color: #16A34A; }
        .captcha-box.err { border-color: #DC2626; }
        .captcha-canvas-wrap {
            position: relative; flex-shrink: 0;
            border-radius: .5rem; overflow: hidden;
            border: 1px solid #CBD5E1;
        }
        #captchaCanvas { display: block; width: 140px; height: 48px; cursor: default; user-select: none; }
        .captcha-refresh {
            background: none; border: none; cursor: pointer;
            color: #94A3B8; padding: .2rem; line-height: 0;
            transition: color .2s, transform .3s;
        }
        .captcha-refresh:hover { color: #0EA5E9; transform: rotate(90deg); }
        .captcha-input {
            flex: 1; padding: .55rem .75rem;
            border: 1.5px solid #E2E8F0; border-radius: .625rem;
            font-size: .9rem; color: #0F172A; outline: none;
            transition: border-color .2s, box-shadow .2s;
            text-transform: uppercase; letter-spacing: .12em; min-width: 0;
        }
        .captcha-input:focus { border-color: #0EA5E9; box-shadow: 0 0 0 3px rgba(14,165,233,.18); }
    </style>
</head>
<body class="bg-mesh min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-5xl fade-up">
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-sky-100">
        <div class="grid grid-cols-1 md:grid-cols-2">

            {{-- ══════════════════════════════════════
                 COLUMNA IZQUIERDA — Formulario
            ══════════════════════════════════════ --}}
            <div class="p-8 md:p-10 bg-gradient-to-b from-sky-50/60 to-white">

                {{-- Volver --}}
                <div class="mb-6 fade-up fade-up-1">
                    <a href="{{ url('/administracion/usuarios') }}"
                       class="inline-flex items-center gap-1.5 text-sm font-semibold text-sky-600 hover:text-sky-500 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Volver a usuarios
                    </a>
                </div>

                {{-- Logo + título --}}
                <div class="flex items-center gap-3 fade-up fade-up-2">
                    <div class="relative">
                        <div class="absolute -inset-3 bg-sky-200/40 rounded-full blur-2xl"></div>
                        <img src="{{ asset('img/logo.png') }}" alt="Logo"
                             class="relative w-12 h-12 shadow-md object-cover rounded-2xl bg-white p-1.5">
                    </div>
                    <div>
                        <h2 class="font-display text-xl font-bold text-slate-900">Clínica Potosí</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Laboratorio · Turnos · Resultados</p>
                    </div>
                </div>

                <h3 class="mt-7 font-display text-2xl font-extrabold text-slate-900 fade-up fade-up-3">Crear cuenta</h3>
                <p class="mt-1 text-slate-500 text-sm fade-up fade-up-3">Completa los datos para registrarte en el sistema.</p>

                {{-- Errores globales --}}
                @if ($errors->any())
                    <div class="mt-5 p-4 bg-red-50 border border-red-200 rounded-xl fade-up">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-red-700 text-sm">{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.post') }}" id="registerForm" class="space-y-5 mt-6" novalidate>
                    @csrf

                    {{-- ── Nombre ───────────────────────────────────── --}}
                    <div class="fade-up fade-up-3">
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Nombre completo
                        </label>
                        <div class="relative">
                            <div class="field-icon">
                                <svg class="h-5 w-5 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 10-8 0 4 4 0 008 0zM12 14c-4.418 0-8 1.79-8 4v2h16v-2c0-2.21-3.582-4-8-4z"/>
                                </svg>
                            </div>
                            <input id="name" name="name" type="text" required
                                   maxlength="30"
                                   value="{{ old('name') }}"
                                   class="field-input"
                                   placeholder="Ej: María González"
                                   oninput="validateName(this)">
                        </div>
                        <div class="flex items-center justify-between mt-1">
                            <span id="name-hint" class="hint hint-neu">
                                <svg id="name-icon" class="w-3.5 h-3.5 hidden" fill="currentColor" viewBox="0 0 20 20"></svg>
                                <span id="name-msg">Mínimo 3 letras, solo letras y espacios.</span>
                            </span>
                            <span id="name-counter" class="text-xs text-slate-400 ml-2 flex-shrink-0">
                                <span id="name-len">0</span>/30
                            </span>
                        </div>
                        @error('name')
                            <span class="hint hint-err">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- ── Correo ───────────────────────────────────── --}}
                    <div class="fade-up fade-up-4">
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Correo electrónico
                        </label>
                        <div class="relative">
                            <div class="field-icon">
                                <svg class="h-5 w-5 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" required
                                   maxlength="255"
                                   value="{{ old('email') }}"
                                   class="field-input"
                                   placeholder="usuario@ejemplo.com"
                                   oninput="validateEmail(this)">
                        </div>
                        <span id="email-hint" class="hint hint-neu">Ingresa un correo válido (ej: doctor@gmail.com).</span>
                        @error('email')
                            <span class="hint hint-err">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- ── Rol ──────────────────────────────────────── --}}
                    <div class="fade-up fade-up-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Rol <span class="text-red-400">*</span>
                        </label>
                        {{-- Input oculto real --}}
                        <input type="hidden" name="role" id="role-value" value="{{ old('role', '') }}">

                        <div class="grid grid-cols-2 gap-3">

                            {{-- Bioquímico --}}
                            <div class="role-card {{ old('role') === 'bioquimico' ? 'selected' : '' }}"
                                 id="card-bioquimico"
                                 onclick="selectRole('bioquimico')">
                                <div class="role-dot" id="dot-bioquimico"></div>
                                <div class="role-icon-wrap">
                                    <svg class="w-5 h-5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">Bioquímico</p>
                                    <p class="text-xs text-slate-500">Lab. y resultados</p>
                                </div>
                            </div>

                            {{-- Recepcionista --}}
                            <div class="role-card {{ old('role') === 'recepcionista' ? 'selected' : '' }}"
                                 id="card-recepcionista"
                                 onclick="selectRole('recepcionista')">
                                <div class="role-dot" id="dot-recepcionista"></div>
                                <div class="role-icon-wrap">
                                    <svg class="w-5 h-5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">Recepcionista</p>
                                    <p class="text-xs text-slate-500">Turnos y pacientes</p>
                                </div>
                            </div>

                        </div>
                        <span id="role-hint" class="hint hint-neu hidden">Selecciona un rol para continuar.</span>
                        @error('role')
                            <span class="hint hint-err">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- ── Contraseña ───────────────────────────────── --}}
                    <div class="fade-up fade-up-5">
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Contraseña
                        </label>
                        <div class="relative">
                            <div class="field-icon">
                                <svg class="h-5 w-5 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input id="password" name="password" type="password" required
                                   class="field-input pr-10"
                                   placeholder="Mínimo 8 caracteres"
                                   oninput="updateStrength(this.value); validatePassword(this)">
                            <button type="button" class="toggle-pass" onclick="togglePass('password','icon-pass1')" title="Mostrar / Ocultar">
                                <svg id="icon-pass1" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-.274.857-.67 1.662-1.17 2.389"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Barra de seguridad --}}
                        <div id="strength-bar-wrap" class="mt-2">
                            <div id="strength-bar"></div>
                        </div>
                        <div class="flex items-center justify-between mt-1">
                            <span id="strength-label" class="text-xs text-slate-400">Escribe tu contraseña…</span>
                            <span id="pass-hint" class="hint hint-neu text-right"></span>
                        </div>

                        @error('password')
                            <span class="hint hint-err">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- ── Confirmar contraseña ─────────────────────── --}}
                    <div class="fade-up fade-up-5">
                        <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Confirmar contraseña
                        </label>
                        <div class="relative">
                            <div class="field-icon">
                                <svg class="h-5 w-5 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                   class="field-input pr-10"
                                   placeholder="Repite tu contraseña"
                                   oninput="validateConfirm(this)">
                            <button type="button" class="toggle-pass" onclick="togglePass('password_confirmation','icon-pass2')" title="Mostrar / Ocultar">
                                <svg id="icon-pass2" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-.274.857-.67 1.662-1.17 2.389"/>
                                </svg>
                            </button>
                        </div>
                        <span id="confirm-hint" class="hint hint-neu"></span>
                        @error('password_confirmation')
                            <span class="hint hint-err">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- ── CAPTCHA propio ─────────────────────────────── --}}
                    <div class="fade-up fade-up-6 pt-1">
                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                            <p class="text-xs font-semibold text-slate-500 mb-3 text-center uppercase tracking-wider flex items-center justify-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                Verifica que eres humano
                            </p>
                            <div class="captcha-box" id="captchaBox">
                                {{-- Canvas con el texto distorsionado --}}
                                <div class="captcha-canvas-wrap">
                                    <canvas id="captchaCanvas" width="140" height="48"></canvas>
                                </div>
                                {{-- Botón refrescar --}}
                                <button type="button" class="captcha-refresh" onclick="generateCaptcha()" title="Nueva imagen">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </button>
                                {{-- Input respuesta --}}
                                <input type="text" id="captchaInput" class="captcha-input"
                                       placeholder="Escribe el código"
                                       maxlength="6"
                                       autocomplete="off"
                                       oninput="validateCaptchaInput()">
                                {{-- Input oculto que viaja con el form --}}
                                <input type="hidden" name="captcha_answer" id="captchaAnswer">
                                <input type="hidden" name="captcha_token" id="captchaToken">
                            </div>
                            <p id="captcha-hint" class="hint hint-neu mt-2 justify-center">Ingresa los caracteres que ves en la imagen.</p>
                        </div>
                    </div>

                    {{-- ── Botón enviar ─────────────────────────────── --}}
                    <div class="pt-1 fade-up fade-up-7">
                        <button type="submit" id="submitBtn"
                                class="btn-primary w-full py-3.5 px-4 rounded-xl font-semibold text-white text-sm
                                       focus:outline-none focus:ring-2 focus:ring-sky-300">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                                Crear cuenta
                            </span>
                        </button>
                    </div>

                </form>
            </div>

            {{-- ══════════════════════════════════════
                 COLUMNA DERECHA — Panel decorativo
            ══════════════════════════════════════ --}}
            <div class="hidden md:flex flex-col items-center justify-center p-10 panel-right relative overflow-hidden">

                {{-- Círculo decorativo fondo --}}
                <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full bg-sky-200/30 blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-20 -left-20 w-56 h-56 rounded-full bg-teal-200/30 blur-3xl pointer-events-none"></div>

                {{-- Tarjeta central --}}
                <div class="relative w-full max-w-xs rounded-3xl border border-sky-100 bg-white/80 backdrop-blur-sm p-8 shadow-xl">

                    <div class="text-center">
                        <span class="inline-flex items-center gap-1.5 bg-sky-100 text-sky-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Acceso seguro
                        </span>
                        <div class="mt-5 flex items-center justify-center">
                            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="w-28 h-28 object-contain drop-shadow-lg">
                        </div>
                        <h4 class="font-display mt-5 text-lg font-bold text-slate-900">Clínica Potosí</h4>
                        <p class="mt-1 text-slate-500 text-sm">Gestiona laboratorio, turnos y resultados desde un solo lugar.</p>
                    </div>

                    {{-- Barra de progreso decorativa --}}
                    <div class="mt-6 step-bar">
                        <div class="step-fill" id="sideProgress" style="width:0%"></div>
                    </div>
                    <p class="mt-2 text-center text-xs text-slate-400" id="sideProgressLabel">Completa el formulario</p>

                    {{-- Chips de características --}}
                    <div class="mt-6 flex flex-wrap gap-2 justify-center">
                        <div class="feat-chip">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Resultados en línea
                        </div>
                        <div class="feat-chip">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Turnos online
                        </div>
                        <div class="feat-chip">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Datos protegidos
                        </div>
                        <div class="feat-chip">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Reportes PDF
                        </div>
                    </div>

                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="px-6 py-4 text-center text-slate-400 text-xs border-t border-slate-100 bg-white">
            &copy; {{ date('Y') }} Clínica Potosí — Todos los derechos reservados.
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════
     JavaScript — Validaciones en tiempo real
════════════════════════════════════════════════ --}}
<script>
// ── Utilidades ──────────────────────────────────────────────────────────────

function setFieldState(input, state /* 'ok'|'err'|'neu' */) {
    input.classList.remove('is-valid', 'is-invalid');
    if (state === 'ok')  input.classList.add('is-valid');
    if (state === 'err') input.classList.add('is-invalid');
}

function setHint(el, state, text) {
    el.className = 'hint hint-' + state;
    el.textContent = text;
}

// ── Nombre ───────────────────────────────────────────────────────────────────

function validateName(input) {
    // Bloquear caracteres no permitidos en tiempo real
    input.value = input.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '');

    const val  = input.value.trim();
    const len  = input.value.length;
    const hint = document.getElementById('name-hint');

    document.getElementById('name-len').textContent = len;

    if (len === 0) {
        setFieldState(input, 'neu');
        setHint(hint, 'neu', 'Mínimo 3 letras, solo letras y espacios.');
    } else if (val.length < 3) {
        setFieldState(input, 'err');
        setHint(hint, 'err', 'Mínimo 3 caracteres.');
    } else if (len > 30) {
        setFieldState(input, 'err');
        setHint(hint, 'err', 'Máximo 30 caracteres.');
    } else {
        setFieldState(input, 'ok');
        setHint(hint, 'ok', '✓ Nombre válido.');
    }
    updateProgress();
}

// ── Correo ───────────────────────────────────────────────────────────────────

function validateEmail(input) {
    // Bloquear caracteres inválidos en tiempo real
    input.value = input.value.replace(/[^a-zA-Z0-9@._\-+]/g, '');

    // Solo un @
    const parts = input.value.split('@');
    if (parts.length > 2) {
        input.value = parts[0] + '@' + parts.slice(1).join('');
    }

    const val   = input.value.trim();
    const hint  = document.getElementById('email-hint');
    const regex = /^[^\s@]+@[^\s@]+\.[a-zA-Z]{2,20}$/;

    if (val === '') {
        setFieldState(input, 'neu');
        setHint(hint, 'neu', 'Ingresa un correo válido (ej: doctor@gmail.com).');
    } else if (!regex.test(val)) {
        setFieldState(input, 'err');
        setHint(hint, 'err', '✗ Correo inválido. Ej: usuario@dominio.com');
    } else {
        setFieldState(input, 'ok');
        setHint(hint, 'ok', '✓ Correo válido.');
    }
    updateProgress();
}

// ── Rol ──────────────────────────────────────────────────────────────────────

function selectRole(role) {
    document.getElementById('role-value').value = role;

    ['bioquimico', 'recepcionista'].forEach(r => {
        document.getElementById('card-' + r).classList.remove('selected');
    });

    document.getElementById('card-' + role).classList.add('selected');
    document.getElementById('role-hint').classList.add('hidden');
    updateProgress();
}

// ── Contraseña — fuerza ───────────────────────────────────────────────────────

function updateStrength(val) {
    const bar   = document.getElementById('strength-bar');
    const label = document.getElementById('strength-label');

    let score = 0;
    if (val.length >= 8)  score++;
    if (val.length >= 12) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const levels = [
        { pct: '0%',   color: '#E2E8F0', text: 'Escribe tu contraseña…' },
        { pct: '20%',  color: '#DC2626', text: 'Muy débil' },
        { pct: '40%',  color: '#EA580C', text: 'Débil' },
        { pct: '60%',  color: '#D97706', text: 'Aceptable' },
        { pct: '80%',  color: '#65A30D', text: 'Fuerte' },
        { pct: '100%', color: '#16A34A', text: '✓ Muy fuerte' },
    ];

    const lvl = val.length === 0 ? levels[0] : levels[score] || levels[levels.length - 1];
    bar.style.width      = lvl.pct;
    bar.style.background = lvl.color;
    label.textContent    = lvl.text;
    label.style.color    = lvl.color;
}

function validatePassword(input) {
    const val  = input.value;
    const hint = document.getElementById('pass-hint');

    if (val.length === 0) {
        setFieldState(input, 'neu');
        hint.textContent = '';
    } else if (val.length < 8) {
        setFieldState(input, 'err');
        setHint(hint, 'err', `Faltan ${8 - val.length} caracteres más.`);
    } else {
        setFieldState(input, 'ok');
        hint.textContent = '';
    }

    // Re-validar confirmación si ya tiene texto
    const conf = document.getElementById('password_confirmation');
    if (conf.value.length > 0) validateConfirm(conf);
    updateProgress();
}

// ── Confirmar contraseña ──────────────────────────────────────────────────────

function validateConfirm(input) {
    const pass = document.getElementById('password').value;
    const hint = document.getElementById('confirm-hint');

    if (input.value === '') {
        setFieldState(input, 'neu');
        hint.textContent = '';
    } else if (input.value !== pass) {
        setFieldState(input, 'err');
        setHint(hint, 'err', '✗ Las contraseñas no coinciden.');
    } else {
        setFieldState(input, 'ok');
        setHint(hint, 'ok', '✓ Las contraseñas coinciden.');
    }
    updateProgress();
}

// ── Mostrar / Ocultar contraseña ──────────────────────────────────────────────

function togglePass(inputId, iconId) {
    const input = document.getElementById(inputId);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';

    // Cambiar icono ojo
    const icon = document.getElementById(iconId);
    icon.innerHTML = isText
        ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
               d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.477
               0 8.268 2.943 9.542 7-.274.857-.67 1.662-1.17 2.389"/>`
        : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
               d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97
               9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88
               9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112
               5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;
}

// ── Progreso lateral ──────────────────────────────────────────────────────────

function updateProgress() {
    let done = 0;
    const total = 5;

    // Nombre
    const name = document.getElementById('name').value.trim();
    if (name.length >= 3) done++;

    // Correo
    const email = document.getElementById('email').value.trim();
    if (/^[^\s@]+@[^\s@]+\.[a-zA-Z]{2,20}$/.test(email)) done++;

    // Rol
    if (document.getElementById('role-value').value !== '') done++;

    // Contraseña
    const pass = document.getElementById('password').value;
    if (pass.length >= 8) done++;

    // Confirmación
    const conf = document.getElementById('password_confirmation').value;
    if (conf.length >= 8 && conf === pass) done++;

    const pct = Math.round((done / total) * 100);
    document.getElementById('sideProgress').style.width = pct + '%';

    const labels = ['Completa el formulario', 'Buen comienzo…', 'Ya casi…', 'Casi listo!', 'Casi listo!', '¡Listo para registrarse!'];
    document.getElementById('sideProgressLabel').textContent = labels[done] || labels[labels.length - 1];
}

// ══════════════════════════════════════════════════════
// CAPTCHA PROPIO — Canvas con texto distorsionado
// ══════════════════════════════════════════════════════

const CAPTCHA_CHARS = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
let _captchaCode = '';

function generateCaptcha() {
    // Generar código de 5 caracteres
    _captchaCode = Array.from({ length: 5 }, () =>
        CAPTCHA_CHARS[Math.floor(Math.random() * CAPTCHA_CHARS.length)]
    ).join('');

    // Guardar hash simple en el hidden (lo validará el servidor opcionalmente)
    document.getElementById('captchaToken').value = btoa(_captchaCode);

    drawCaptcha(_captchaCode);

    // Limpiar input
    const inp = document.getElementById('captchaInput');
    if (inp) { inp.value = ''; }
    const box = document.getElementById('captchaBox');
    if (box) { box.className = 'captcha-box'; }
    const hint = document.getElementById('captcha-hint');
    if (hint) { hint.className = 'hint hint-neu mt-2 justify-center'; hint.textContent = 'Ingresa los caracteres que ves en la imagen.'; }
}

function drawCaptcha(code) {
    const canvas = document.getElementById('captchaCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    const W = canvas.width, H = canvas.height;

    // Fondo con gradiente suave
    const grad = ctx.createLinearGradient(0, 0, W, H);
    grad.addColorStop(0, '#EFF6FF');
    grad.addColorStop(1, '#E0F2FE');
    ctx.fillStyle = grad;
    ctx.fillRect(0, 0, W, H);

    // Líneas de ruido de fondo
    for (let i = 0; i < 6; i++) {
        ctx.beginPath();
        ctx.moveTo(Math.random() * W, Math.random() * H);
        ctx.lineTo(Math.random() * W, Math.random() * H);
        ctx.strokeStyle = `rgba(${Math.floor(Math.random()*100+100)},${Math.floor(Math.random()*100+150)},${Math.floor(Math.random()*100+200)},0.35)`;
        ctx.lineWidth = 1 + Math.random();
        ctx.stroke();
    }

    // Puntos de ruido
    for (let i = 0; i < 40; i++) {
        ctx.beginPath();
        ctx.arc(Math.random() * W, Math.random() * H, Math.random() * 1.5, 0, Math.PI * 2);
        ctx.fillStyle = `rgba(14,165,233,${0.2 + Math.random() * 0.3})`;
        ctx.fill();
    }

    // Letras distorsionadas
    const colors = ['#0369A1','#0D9488','#0EA5E9','#1D4ED8','#065F46'];
    const fonts  = ['bold 22px Sora,sans-serif', 'bold 24px DM Sans,sans-serif', '800 22px Georgia,serif'];
    const stepX  = W / (code.length + 1);

    code.split('').forEach((ch, i) => {
        ctx.save();
        const x = stepX * (i + 1);
        const y = H / 2 + (Math.random() * 8 - 4);
        ctx.translate(x, y);
        ctx.rotate((Math.random() - 0.5) * 0.45);
        ctx.font  = fonts[Math.floor(Math.random() * fonts.length)];
        ctx.fillStyle = colors[Math.floor(Math.random() * colors.length)];
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        // Sombra sutil
        ctx.shadowColor = 'rgba(0,0,0,.15)';
        ctx.shadowOffsetX = 1; ctx.shadowOffsetY = 1; ctx.shadowBlur = 2;
        ctx.fillText(ch, 0, 0);
        ctx.restore();
    });
}

function validateCaptchaInput() {
    const inp   = document.getElementById('captchaInput');
    const box   = document.getElementById('captchaBox');
    const hint  = document.getElementById('captcha-hint');
    const val   = inp.value.toUpperCase().trim();

    document.getElementById('captchaAnswer').value = val;

    if (val === '') {
        box.className = 'captcha-box';
        hint.className = 'hint hint-neu mt-2 justify-center';
        hint.textContent = 'Ingresa los caracteres que ves en la imagen.';
    } else if (val === _captchaCode) {
        box.className = 'captcha-box ok';
        hint.className = 'hint hint-ok mt-2 justify-center';
        hint.textContent = '✓ Verificación correcta.';
    } else if (val.length >= _captchaCode.length) {
        box.className = 'captcha-box err';
        hint.className = 'hint hint-err mt-2 justify-center';
        hint.textContent = '✗ Código incorrecto. Intenta de nuevo.';
    } else {
        box.className = 'captcha-box';
        hint.className = 'hint hint-neu mt-2 justify-center';
        hint.textContent = `${val.length}/${_captchaCode.length} caracteres…`;
    }
}

// ── Validación antes de enviar ────────────────────────────────────────────────

document.getElementById('registerForm').addEventListener('submit', function (e) {
    let ok = true;

    // Rol obligatorio
    if (document.getElementById('role-value').value === '') {
        document.getElementById('role-hint').classList.remove('hidden');
        document.getElementById('role-hint').className = 'hint hint-err';
        document.getElementById('role-hint').textContent = '✗ Debes seleccionar un rol.';
        ok = false;
    }

    // Contraseña mínima
    const pass = document.getElementById('password').value;
    if (pass.length < 8) {
        document.getElementById('pass-hint').className = 'hint hint-err';
        document.getElementById('pass-hint').textContent = 'La contraseña debe tener al menos 8 caracteres.';
        ok = false;
    }

    // CAPTCHA obligatorio
    const captchaVal = document.getElementById('captchaInput').value.toUpperCase().trim();
    const captchaHint = document.getElementById('captcha-hint');
    const captchaBox  = document.getElementById('captchaBox');
    if (captchaVal !== _captchaCode) {
        captchaBox.className  = 'captcha-box err';
        captchaHint.className = 'hint hint-err mt-2 justify-center';
        captchaHint.textContent = '✗ Debes completar la verificación correctamente.';
        ok = false;
        if (captchaVal.length > 0) generateCaptcha(); // refrescar si hubo intento
    }

    if (!ok) e.preventDefault();
});

// ── Inicializar ───────────────────────────────────────────────────────────────
(function init() {
    const nameInput = document.getElementById('name');
    if (nameInput.value) validateName(nameInput);

    const emailInput = document.getElementById('email');
    if (emailInput.value) validateEmail(emailInput);

    const roleVal = document.getElementById('role-value').value;
    if (roleVal) selectRole(roleVal);

    generateCaptcha();   // ← genera el CAPTCHA al cargar
    updateProgress();
})();
</script>

</body>
</html>