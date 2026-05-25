<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\TipoUsario;
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
            return redirect()->route('dashboard');
        }
        return back()->withErrors([
            'email' => 'Credenciales incorrectas',
        ]);
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('dashboard');
    }
    public function showRegister()
    {
        $tipos = TipoUsario::all();
        return view('auth.register', compact('tipos'));
    }
    public function register(Request $request)
    {
        $request->validate([
            'name'           => 'required',
            'email'          => 'required|email|unique:users',
            'password'       => 'required|confirmed|min:4',
            'tipo_usuario_id'=> 'required|exists:tipo_usuario,id',
        ]);

        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'tipo_usuario_id'=> $request->tipo_usuario_id,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
