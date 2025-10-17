<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PabellonController;
use App\Http\Controllers\NichoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DifuntoController;
use App\Http\Controllers\UserController;


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


Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');

Route::get('/difuntos', [DifuntoController::class, 'index'])->name('difunto.index');
Route::post('/difuntos', [DifuntoController::class, 'store'])->name('difunto.store');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
});
