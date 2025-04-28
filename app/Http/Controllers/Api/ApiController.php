<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; // <<<< Agrega esta línea
use App\Models\{Canciones, Etiquetas, Listas};
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function canciones() {
        $canciones = Canciones::all();
        return response()->json($canciones);
    }
    
    public function listas() {
        $listas = Listas::with('canciones')->get();
        return response()->json($listas);
    }    
}