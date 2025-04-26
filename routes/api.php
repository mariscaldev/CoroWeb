<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

// ▶ API para la app móvil
Route::middleware('api')->group(function () {
    Route::get('/canciones', [ApiController::class, 'canciones']);
    Route::get('/listas', [ApiController::class, 'listas']);
});
