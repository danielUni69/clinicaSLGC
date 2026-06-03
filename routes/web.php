<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LaboratorioPdfController;
use App\Livewire\Administracion\CatalogoAnalisis;
use App\Livewire\Administracion\CatalogoAntibioticos;
use App\Livewire\CreateServicio;
use App\Livewire\CultivosList;
use App\Livewire\MedicosSolicitantes\ListaMedicosSolicitantes;
use App\Livewire\Pacientes\CrearPaciente;
use App\Livewire\Pacientes\ListaPacientes;
use App\Livewire\PanelLaboratorio;
use App\Livewire\Turnos\Turnos;
use App\Livewire\ProcesarCultivo;
use App\Livewire\ProcesarResultados;
use App\Livewire\UsersList;
use App\Livewire\Reportes\ReportePacientes;
use App\Livewire\Reportes\IndexReportes;
use Illuminate\Support\Facades\Route;

// Rutas Públicas (Sin Login)
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('/register', [LoginController::class, 'showRegister'])->name('register');
Route::post('/register', [LoginController::class, 'register'])->name('register.post');

Route::get('/service', CreateServicio::class)->name('create-servicio');

// Rutas Protegidas
Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // RECEPCIÓN
    Route::middleware(['role.recepcion'])->group(function () {

        Route::get('/turnos', Turnos::class)->name('turnos.index');

        Route::get('/pacientes', ListaPacientes::class)->name('pacientes.listar');
        Route::get('/pacientes/historial', \App\Livewire\Pacientes\HistorialPacientes::class)->name('pacientes.historial');
        Route::get('/pacientes/crear', CrearPaciente::class)->name('pacientes.crear');

        Route::get('/medicos-solicitantes', ListaMedicosSolicitantes::class)->name('medicos.solicitantes');

        Route::get('/laboratorio/recibo/{id}/ticket', [LaboratorioPdfController::class, 'ticketTermico'])
            ->name('laboratorio.ticket');
    });

    // LABORATORIO
    Route::middleware(['role.bioquimico'])->group(function () {

        Route::get('/laboratorio', PanelLaboratorio::class)
            ->name('laboratorio.panel');

        Route::get('/laboratorio/procesar/{id}', ProcesarResultados::class)
            ->name('laboratorio.procesar');

        Route::get('/laboratorio/cultivo/{id}', ProcesarCultivo::class)
            ->name('laboratorio.cultivo');

        // NUEVO MÓDULO DE CULTIVOS
        Route::get('/cultivos', CultivosList::class)
            ->name('cultivos');

        Route::get('/laboratorio/pdf/{id}', [LaboratorioPdfController::class, 'descargar'])
            ->name('laboratorio.pdf');

        Route::get('/laboratorio/pdf/cultivo/{id}', [LaboratorioPdfController::class, 'pdfMicrobiologia'])
            ->name('laboratorio.pdf_micro');
    });

    // ADMINISTRACIÓN
    Route::middleware(['role.admin'])->group(function () {

        Route::get('/administracion/catalogo', CatalogoAnalisis::class)
            ->name('admin.catalogo');

        Route::get('/administracion/antibioticos', CatalogoAntibioticos::class)
            ->name('admin.antibioticos');
        Route::get('/administracion/usuarios', UsersList::class)
            ->name('admin.users.list');

        Route::get('/administracion/reportes', ReportePacientes::class)
            ->name('reportes.index');
        
    });
    
});