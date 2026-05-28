<?php

use App\Http\Controllers\Auth\LoginController;
use App\Livewire\CreateServicio;
use App\Livewire\Dashboard;
use App\Livewire\MedicosSolicitantes\ListaMedicosSolicitantes;
use App\Livewire\Pacientes\CrearPaciente;
use App\Livewire\Pacientes\ListaPacientes;
use App\Livewire\PanelLaboratorio;
use App\Livewire\ProcesarResultados;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Tus rutas de laboratorio
Route::get('/laboratorio', PanelLaboratorio::class)->name('laboratorio.panel');
Route::get('/laboratorio/procesar/{id}', ProcesarResultados::class)->name('laboratorio.procesar');

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::get('/register', [LoginController::class, 'showRegister'])->name('register');
Route::post('/register', [LoginController::class, 'register'])->name('register.post');

// La ruta de servicio (dejamos solo una para evitar el duplicado)
Route::get('/service', CreateServicio::class)->name('create-servicio');

Route::get('/pacientes', ListaPacientes::class)->name('pacientes.listar');
Route::get('/pacientes/crear', CrearPaciente::class)->name('pacientes.crear');

Route::get('/medicos-solicitantes', ListaMedicosSolicitantes::class)->name('medicos.solicitantes');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
// Route::get("/dashboard", Dashboard::class)->name("dashboard");

Route::middleware(['auth'])->group(function () {});
