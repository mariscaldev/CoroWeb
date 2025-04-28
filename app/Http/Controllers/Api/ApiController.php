<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Canciones, Etiquetas, Listas};
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function canciones()
    {
        $canciones = Canciones::all();
        return response()->json($canciones);
    }

    public function cancion($id)
    {
        $cancion = Canciones::find($id);

        if (!$cancion) {
            return response()->json([
                'message' => 'La canción no fue encontrada.',
                'status' => 404
            ], 404);
        }

        return response()->json($cancion);
    }

    public function listas() {
        $listas = Listas::all(); // <<<<< sin with('canciones')
        return response()->json($listas);
    }

    public function lista($id) {
        $lista = Listas::find($id);

        if (!$lista) {
            return response()->json([
                'message' => 'La lista no fue encontrada.',
                'status' => 404
            ], 404);
        }

        return response()->json($lista);
    }

}
