<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Canciones, Etiquetas};

class CancionesController extends Controller
{
    public function index() {
        $canciones = Canciones::with('nombreEtiqueta')->get();
        return view('canciones.index', compact('canciones'));
    }

    public function show($id) {
        $id = decrypt($id);
        $cancion = Canciones::findOrFail($id);
        return view('canciones.cancion', compact('cancion'));
    }

    public function create() {
        $etiquetas = Etiquetas::all();
        $nextId = Canciones::max('id') + 1;
        return view('canciones.create', compact('nextId', 'etiquetas'));
    }

    public function store(Request $request) {
        $request->validate([
            'nombre' => 'required|string|max:500',
            'introduccion' => 'nullable|string|max:2000',
            'letra1' => 'nullable|string|max:5000',
            'interludio' => 'nullable|string|max:2000',
            'letra2' => 'nullable|string|max:5000',
            'final' => 'nullable|string|max:2000',
            'etiqueta' => 'nullable|numeric|exists:etiquetas,id',
        ]);
    
        $cancion = new Canciones();
        $cancion->nombre = $request->nombre;
        $cancion->introduccion = $request->introduccion;
        $cancion->letra1 = $request->letra1;
        $cancion->interludio = $request->interludio;
        $cancion->letra2 = $request->letra2;
        $cancion->final = $request->final;
        $cancion->etiqueta = $request->etiqueta;
        $cancion->save();
    
        return redirect()->route('canciones.index')->with('success', 'Canción creada correctamente.');
    }    

    public function edit($id) {
        $id = decrypt($id);
        $cancion = Canciones::findOrFail($id);
        $etiquetas = Etiquetas::all();
        return view('canciones.edit', compact('cancion', 'etiquetas'));
    }

    public function update(Request $request, $id) {
        $id = decrypt($id);
    
        $request->validate([
            'nombre' => 'required|string|max:500',
            'introduccion' => 'nullable|string|max:2000',
            'letra1' => 'nullable|string|max:5000',
            'interludio' => 'nullable|string|max:2000',
            'letra2' => 'nullable|string|max:5000',
            'final' => 'nullable|string|max:2000',
            'etiqueta' => 'nullable|numeric|exists:etiquetas,id',
        ]);
    
        $cancion = Canciones::findOrFail($id);
        $cancion->nombre = $request->nombre;
        $cancion->introduccion = $request->introduccion;
        $cancion->letra1 = $request->letra1;
        $cancion->interludio = $request->interludio;
        $cancion->letra2 = $request->letra2;
        $cancion->final = $request->final;
        $cancion->etiqueta = $request->etiqueta;
    
        $cancion->save();
    
        return redirect()->route('canciones.index')->with('success', 'Canción actualizada correctamente.');
    }

    public function destroy($id) {
        $id = decrypt($id); // <- Agrega esta línea
        $cancion = Canciones::findOrFail($id);
        $cancion->delete();
    
        return redirect()->route('canciones.index')->with('success', 'Canción eliminada correctamente.');
    }    
}