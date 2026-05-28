<?php

use App\Http\Controllers\Auth\LoginController;
use App\Livewire\CreateServicio;
use App\Livewire\Dashboard;
use App\Livewire\Pacientes\CrearPaciente;
use App\Livewire\Pacientes\ListaPacientes;
use App\Livewire\MedicosSolicitantes\ListaMedicosSolicitantes;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::get('/register', [LoginController::class, 'showRegister'])->name('register');
Route::post('/register', [LoginController::class, 'register'])->name('register.post');

Route::get('/service', CreateServicio::class)->name('create-servicio');

Route::get('/pacientes', ListaPacientes::class)->name('pacientes.listar');
Route::get('/pacientes/crear', CrearPaciente::class)->name('pacientes.crear');

Route::get('/medicos-solicitantes', ListaMedicosSolicitantes::class)->name('medicos.solicitantes');



Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
// Route::get("/dashboard", Dashboard::class)->name("dashboard");

Route::middleware(['auth'])->group(function () {});
