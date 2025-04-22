<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{HomeController, CancionesController, ListasController, EtiquetasController};

Route::get('/', function () {
    return Auth::check() ? redirect('/home') : view('auth.login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // ▶ Canciones
    Route::get('/canciones', [CancionesController::class, 'index'])->name('canciones.index');
    Route::get('/canciones/crear', [CancionesController::class, 'create'])->name('canciones.create');
    Route::post('/canciones', [CancionesController::class, 'store'])->name('canciones.store');
    Route::get('/canciones/{id}', [CancionesController::class, 'show'])->name('canciones.show');
    Route::get('/canciones/{id}/edit', [CancionesController::class, 'edit'])->name('canciones.edit');
    Route::put('/canciones/{id}', [CancionesController::class, 'update'])->name('canciones.update');
    Route::delete('/canciones/{id}', [CancionesController::class, 'destroy'])->name('canciones.destroy');

    // ▶ Etiquetas
    Route::get('/etiquetas', [EtiquetasController::class, 'index'])->name('etiquetas.index');
    Route::get('/etiquetas/crear', [EtiquetasController::class, 'create'])->name('etiquetas.create');
    Route::post('/etiquetas', [EtiquetasController::class, 'store'])->name('etiquetas.store');
    Route::get('/etiquetas/{id}', [EtiquetasController::class, 'show'])->name('etiquetas.show');
    Route::get('/etiquetas/{id}/edit', [EtiquetasController::class, 'edit'])->name('etiquetas.edit');
    Route::put('/etiquetas/{id}', [EtiquetasController::class, 'update'])->name('etiquetas.update');
    Route::delete('/etiquetas/{id}', [EtiquetasController::class, 'destroy'])->name('etiquetas.destroy');

    // ▶ Lista semanal
    Route::get('/lista-semanal', [ListasController::class, 'index'])->name('listas.index');
    Route::get('/lista-semanal/crear', [ListasController::class, 'create'])->name('listas.create');
    Route::post('/lista-semanal', [ListasController::class, 'store'])->name('listas.store');
    Route::get('/lista-semanal/{id}', [ListasController::class, 'show'])->name('listas.show');
    Route::get('/lista-semanal/{id}/edit', [ListasController::class, 'edit'])->name('listas.edit');
    Route::put('/lista-semanal/{id}', [ListasController::class, 'update'])->name('listas.update');
    Route::delete('/lista-semanal/{id}', [ListasController::class, 'destroy'])->name('listas.destroy');
    Route::patch('/lista-semanal/{id}/estado', [ListasController::class, 'toggleEstado'])->name('listas.estado');

});
