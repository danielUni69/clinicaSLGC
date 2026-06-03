<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LaboratorioPdfController;
use App\Livewire\Administracion\CatalogoAnalisis;
use App\Livewire\Administracion\CatalogoAntibioticos;
use App\Livewire\CreateServicio;
use App\Livewire\MedicosSolicitantes\ListaMedicosSolicitantes;
use App\Livewire\Pacientes\CrearPaciente;
use App\Livewire\Pacientes\ListaPacientes;
use App\Livewire\PanelLaboratorio;
use App\Livewire\ProcesarCultivo;
use App\Livewire\ProcesarResultados;
use App\Livewire\Reportes\ReportePacientes;
use App\Livewire\Turnos\Turnos;
use App\Livewire\UsersList;
use Illuminate\Support\Facades\Route;

// Ruta raíz redirige al login
Route::get('/', function () {
    return redirect()->route('login');
});

// ==========================================
// RUTAS PARA INVITADOS (Solo si NO has iniciado sesión)
// ==========================================
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('/register', [LoginController::class, 'showRegister'])->name('register');
Route::post('/register', [LoginController::class, 'register'])->name('register.post');

// ==========================================
// RUTAS PROTEGIDAS (Solo si SÍ has iniciado sesión)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // LOGOUT (Debe estar aquí adentro)
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // RECEPCIÓN
    Route::middleware(['role.recepcion'])->group(function () {
        Route::get('/service', CreateServicio::class)->name('create-servicio'); // <-- La moví aquí adentro por seguridad
        Route::get('/turnos', Turnos::class)->name('turnos.index');
        Route::get('/pacientes', ListaPacientes::class)->name('pacientes.listar');
        Route::get('/pacientes/crear', CrearPaciente::class)->name('pacientes.crear');
        Route::get('/medicos-solicitantes', ListaMedicosSolicitantes::class)->name('medicos.solicitantes');
        Route::get('/laboratorio/recibo/{id}/ticket', [LaboratorioPdfController::class, 'ticketTermico'])
            ->name('laboratorio.ticket');
    });

    // LABORATORIO
    Route::middleware(['role.bioquimico'])->group(function () {
        Route::get('/laboratorio', PanelLaboratorio::class)->name('laboratorio.panel');
        Route::get('/laboratorio/procesar/{id}', ProcesarResultados::class)->name('laboratorio.procesar');
        Route::get('/laboratorio/cultivo/{id}', ProcesarCultivo::class)->name('laboratorio.cultivo');
        Route::get('/laboratorio/pdf/{id}', [LaboratorioPdfController::class, 'descargar'])->name('laboratorio.pdf');
        Route::get('/laboratorio/pdf/cultivo/{id}', [LaboratorioPdfController::class, 'pdfMicrobiologia'])->name('laboratorio.pdf_micro');
        Route::get('/pacientes/historial', \App\Livewire\Pacientes\HistorialPacientes::class)->name('pacientes.historial');
        Route::get('/administracion/antibioticos', CatalogoAntibioticos::class)->name('admin.antibioticos');
    });

    // ADMINISTRACIÓN
    Route::middleware(['role.admin'])->group(function () {
        Route::get('/administracion/catalogo', CatalogoAnalisis::class)->name('admin.catalogo');
        Route::get('/administracion/usuarios', UsersList::class)->name('admin.users.list');
        Route::get('/administracion/reportes', ReportePacientes::class)->name('reportes.index');
    });

});
