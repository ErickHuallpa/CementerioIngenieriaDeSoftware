<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\AdminMiddleware;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PabellonController;
use App\Http\Controllers\NichoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DifuntoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IncineracionController;
use App\Http\Controllers\FallecidoController;
use App\Http\Controllers\PendienteController;
use App\Http\Controllers\BodegaController;


Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/pabellon', [PabellonController::class, 'store'])->name('pabellon.store');
Route::post('/nicho', [NichoController::class, 'store'])->name('nicho.store');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/nicho/mapa', [NichoController::class, 'mapa'])->name('nicho.mapa');
Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
Route::get('/difuntos', [DifuntoController::class, 'index'])->name('difunto.index');
Route::get('/difuntos/create', [DifuntoController::class, 'create'])->name('difunto.create');
Route::post('/difuntos', [DifuntoController::class, 'store'])->name('difunto.store');
Route::get('/difuntos/{id}/edit', [DifuntoController::class, 'edit'])->name('difunto.edit');
Route::put('/difuntos/{id}', [DifuntoController::class, 'update'])->name('difunto.update');
Route::get('/difuntos/{id}/pdf', [DifuntoController::class, 'downloadPdf'])->name('difunto.downloadPdf');
Route::get('incineraciones', [IncineracionController::class, 'index'])->name('incineracion.index');
Route::get('incineraciones/create', [IncineracionController::class, 'create'])->name('incineracion.create');
Route::post('incineraciones', [IncineracionController::class, 'store'])->name('incineracion.store');
Route::get('incineraciones/{id}/edit', [IncineracionController::class, 'edit'])->name('incineracion.edit');
Route::put('incineraciones/{id}', [IncineracionController::class, 'update'])->name('incineracion.update');
Route::get('incineraciones/{id}/pdf', [IncineracionController::class, 'downloadPdf'])->name('incineracion.downloadPdf');
Route::get('/fallecidos', [FallecidoController::class, 'index'])->name('fallecido.index');
Route::get('/fallecidos/create', [FallecidoController::class, 'create'])->name('fallecido.create');
Route::post('/fallecidos', [FallecidoController::class, 'store'])->name('fallecido.store');
Route::get('/fallecidos/{id}/edit', [FallecidoController::class, 'edit'])->name('fallecido.edit');
Route::put('/fallecidos/{id}', [FallecidoController::class, 'update'])->name('fallecido.update');
Route::get('/personas/buscar', [FallecidoController::class, 'buscarPersona'])->name('personas.buscar');
Route::get('/pendientes', [PendienteController::class, 'index'])->name('pendientes.index');
Route::post('/pendientes/{id}/complete', [PendienteController::class, 'complete'])->name('pendientes.complete');
Route::get('/pendientes/{id}/ticket', [PendienteController::class, 'verComprobante'])->name('pendientes.ticket');
Route::get('/pendientes/{id}/factura', [PendienteController::class, 'downloadFactura'])->name('pendientes.factura');
Route::get('/bodega', [BodegaController::class, 'index'])->name('bodega.index');
Route::get('/bodega/create', [BodegaController::class, 'create'])->name('bodega.create');
Route::post('/bodega', [BodegaController::class, 'store'])->name('bodega.store');
Route::post('/bodega/{id}/retirar', [App\Http\Controllers\BodegaController::class, 'retirar'])->name('bodega.retirar');
Route::get('/bodega/{id}/comprobante', [App\Http\Controllers\BodegaController::class, 'comprobante'])
    ->name('bodega.comprobante');
Route::get('/bodega/{id}/comprobante/pdf', [App\Http\Controllers\BodegaController::class, 'comprobantePDF'])
    ->name('bodega.comprobante.pdf');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
});

Route::middleware([AdminMiddleware::class])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/pabellon/crear', [PabellonController::class, 'create'])->name('pabellon.create');
    Route::get('/nicho/crear', [NichoController::class, 'create'])->name('nicho.create');

});