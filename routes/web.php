<?php

use App\Http\Controllers\Auth\LoginController;
use App\Livewire\CreateServicio;
use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('create-servicio');
});
Route::get('/service', CreateServicio::class)->name('create-servicio');

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::get('/register', [LoginController::class, 'showRegister'])->name(
    'register',
);
Route::post('/register', [LoginController::class, 'register'])->name(
    'register.post',
);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
// Route::get("/dashboard", Dashboard::class)->name("dashboard");

Route::middleware(['auth'])->group(function () {});
