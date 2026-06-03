<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();

            // CAMBIAMOS ->rol por ->role
            if ($user->role === 'bioquimico') {
                return redirect()->route('laboratorio.panel');
            }
            if ($user->role === 'recepcionista') {
                return redirect()->route('create-servicio');
            }
            if ($user->role === 'administrador') {
                return redirect()->route('create-servicio');
            }

            return redirect('/');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // CAMBIAMOS ->rol por ->role
            if ($user->role === 'bioquimico') {
                return redirect()->route('laboratorio.panel');
            }

            if ($user->role === 'recepcionista') {
                return redirect()->route('create-servicio');
            }

            return redirect('/');
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas.'])->onlyInput('email');
    }

    // --- MÉTODO LOGOUT CORREGIDO Y BLINDADO ---
    public function logout(Request $request)
    {
        Auth::logout();

        // 1. Invalida la sesión activa destruyendo todos los datos guardados en ella
        $request->session()->invalidate();

        // 2. Regenera el token CSRF para evitar ataques de falsificación
        $request->session()->regenerateToken();

        // 3. Redirige al login
        return redirect()->route('login');
    }

    public function showRegister()
    {
        $tipos = [];

        return view('auth.register', compact('tipos'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:4',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/administracion/usuarios')->with('message', 'Usuario registrado exitosamente.');
    }
}
