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
use App\Http\Controllers\OsarioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
    Route::prefix('difuntos')->group(function () {
        Route::get('/', [DifuntoController::class, 'index'])->name('difunto.index');
        Route::get('/create', [DifuntoController::class, 'create'])->name('difunto.create');
        Route::post('/', [DifuntoController::class, 'store'])->name('difunto.store');
        Route::get('/{id}/edit', [DifuntoController::class, 'edit'])->name('difunto.edit');
        Route::put('/{id}', [DifuntoController::class, 'update'])->name('difunto.update');
        Route::get('/{id}/pdf', [DifuntoController::class, 'downloadPdf'])->name('difunto.downloadPdf');
        Route::get('/mapa-nicho', [DifuntoController::class, 'mapaNichos'])->name('difunto.mapa_nicho');
    });
    Route::prefix('incineraciones')->group(function () {
        Route::get('/', [IncineracionController::class, 'index'])->name('incineracion.index');
        Route::get('/create', [IncineracionController::class, 'create'])->name('incineracion.create');
        Route::post('/', [IncineracionController::class, 'store'])->name('incineracion.store');
        Route::get('/{id}/edit', [IncineracionController::class, 'edit'])->name('incineracion.edit');
        Route::put('/{id}', [IncineracionController::class, 'update'])->name('incineracion.update');
        Route::get('/{id}/pdf', [IncineracionController::class, 'downloadPdf'])->name('incineracion.downloadPdf');
        Route::get('/colectiva', [IncineracionController::class, 'colectiva'])->name('incineracion.colectiva');
        Route::post('/colectiva', [IncineracionController::class, 'storeColectiva'])->name('incineracion.storeColectiva');
    });
    Route::prefix('fallecidos')->group(function () {
        Route::get('/', [FallecidoController::class, 'index'])->name('fallecido.index');
        Route::get('/create', [FallecidoController::class, 'create'])->name('fallecido.create');
        Route::post('/', [FallecidoController::class, 'store'])->name('fallecido.store');
        Route::get('/{id}/edit', [FallecidoController::class, 'edit'])->name('fallecido.edit');
        Route::put('/{id}', [FallecidoController::class, 'update'])->name('fallecido.update');
        Route::get('/personas/buscar', [FallecidoController::class, 'buscarPersona'])->name('personas.buscar');
    });
    Route::prefix('pendientes')->group(function () {
        Route::get('/', [PendienteController::class, 'index'])->name('pendientes.index');
        Route::post('/{id}/complete', [PendienteController::class, 'complete'])->name('pendientes.complete');
        Route::get('/{id}/ticket', [PendienteController::class, 'verComprobante'])->name('pendientes.ticket');
        Route::get('/{id}/factura', [PendienteController::class, 'downloadFactura'])->name('pendientes.factura');
    });
    Route::prefix('bodega')->group(function () {
        Route::get('/', [BodegaController::class, 'index'])->name('bodega.index');
        Route::get('/create', [BodegaController::class, 'create'])->name('bodega.create');
        Route::post('/', [BodegaController::class, 'store'])->name('bodega.store');
        Route::post('/{id}/retirar', [BodegaController::class, 'retirar'])->name('bodega.retirar');
        Route::get('/{id}/comprobante', [BodegaController::class, 'comprobante'])->name('bodega.comprobante');
        Route::get('/{id}/comprobante/pdf', [BodegaController::class, 'comprobantePDF'])->name('bodega.comprobante.pdf');
    });
    Route::prefix('osario')->group(function () {
        Route::get('/', [OsarioController::class, 'index'])->name('osario.index');
        Route::get('/traslado', [OsarioController::class, 'trasladoForm'])->name('osario.traslado.form');
        Route::post('/traslado', [OsarioController::class, 'trasladoStore'])->name('osario.traslado.store');
        Route::get('/{id}/pdf', [OsarioController::class, 'downloadPdf'])->name('osario.downloadPdf');
        Route::get('/mapa', [OsarioController::class, 'mapa'])->name('osario.mapa');
    });
    Route::get('/nicho/por_vencer', [NichoController::class, 'porVencer'])->name('nicho.por_vencer');
    Route::get('/nicho/mapa', [NichoController::class, 'mapa'])->name('nicho.mapa');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/nicho/{id}/notificar/{difunto_id}', [NichoController::class, 'enviarNotificacionNicho'])->name('nicho.enviarNotificacion');
    Route::post('/osario/{id}/notificar', [NichoController::class, 'enviarNotificacionOsario'])->name('osario.enviarNotificacion');

});

Route::middleware([AdminMiddleware::class, 'auth'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/pabellon/crear', [PabellonController::class, 'create'])->name('pabellon.create');
    Route::post('/pabellon', [PabellonController::class, 'store'])->name('pabellon.store');
    Route::get('/nicho/crear', [NichoController::class, 'create'])->name('nicho.create');
    Route::post('/nicho', [NichoController::class, 'store'])->name('nicho.store');
    Route::get('/osario/crear', [OsarioController::class, 'create'])->name('osario.create');
    Route::post('/osario', [OsarioController::class, 'store'])->name('osario.store');
    Route::prefix('reportes')->group(function () {
        Route::get('/', [ReporteController::class, 'index'])->name('reportes.index');
        Route::post('/generar', [ReporteController::class, 'generar'])->name('reportes.generar');
    });
});
