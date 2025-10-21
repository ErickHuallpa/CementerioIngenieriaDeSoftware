<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PabellonController;
use App\Http\Controllers\NichoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DifuntoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Incineracion\IncineracionController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/pabellon/crear', [PabellonController::class, 'create'])->name('pabellon.create');
Route::post('/pabellon', [PabellonController::class, 'store'])->name('pabellon.store');
Route::get('/nicho/crear', [NichoController::class, 'create'])->name('nicho.create');
Route::post('/nicho', [NichoController::class, 'store'])->name('nicho.store');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::post('/users', [UserController::class, 'store'])->name('users.store');

Route::get('/nicho/mapa', [NichoController::class, 'mapa'])->name('nicho.mapa');

Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');

Route::get('/difuntos', [DifuntoController::class, 'index'])->name('difunto.index');
Route::post('/difuntos', [DifuntoController::class, 'store'])->name('difunto.store');

// RUTAS DE INCINERACIÓN - ACTUALIZADAS
Route::prefix('incineracion')->name('incineracion.')->group(function () {
    Route::get('/', [IncineracionController::class, 'index'])->name('index');
    Route::get('/create', [IncineracionController::class, 'create'])->name('create');
    Route::post('/', [IncineracionController::class, 'store'])->name('store');
    Route::get('/{id}', [IncineracionController::class, 'show'])->name('show');
    Route::get('/difunto/{id}', [IncineracionController::class, 'getDifuntoInfo'])->name('difunto.info');
});

// Rutas adicionales que tenías (las mantengo por si las necesitas)
Route::get('incineraciones/tipo/{tipo}', [IncineracionController::class, 'porTipo'])
    ->name('incineraciones.tipo');

Route::post('incineraciones/filtrar-fechas', [IncineracionController::class, 'entreFechas'])
    ->name('incineraciones.filtrar-fechas');

Route::get('mis-incineraciones', [IncineracionController::class, 'misIncineraciones'])
    ->name('incineraciones.mis-incineraciones');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
});
