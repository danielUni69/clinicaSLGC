<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);
        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('create-servicio');
        }
        return back()->withErrors([
            'email' => 'Credenciales incorrectas',
        ]);
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('create-servicio');
    }
    public function showRegister()
    {
        // En este proyecto no existe la tabla `tipo_usuario`.
        // El registro se hace solo con name/email/password.
        $tipos = [];
        return view('auth.register', compact('tipos'));
    }
    public function register(Request $request)
    {
        $request->validate([
            'name'           => 'required',
            'email'          => 'required|email|unique:users',
            'password'       => 'required|confirmed|min:4',
        ]);

        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('create-servicio');
    }
}
