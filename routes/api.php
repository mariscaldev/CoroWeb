<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

// Rutas API para la app móvil
Route::get('/canciones', [ApiController::class, 'canciones']);
Route::get('/canciones/{id}', [ApiController::class, 'cancion']);
Route::get('/listas', [ApiController::class, 'listas']);
Route::get('/listas/{id}', [ApiController::class, 'lista']);
