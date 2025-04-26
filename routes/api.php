<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::get('/canciones', [ApiController::class, 'canciones']);
Route::get('/listas', [ApiController::class, 'listas']);
