<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

// Rutas API para la app móvil
Route::get('/canciones', [ApiController::class, 'canciones']);
Route::get('/listas', [ApiController::class, 'listas']);
