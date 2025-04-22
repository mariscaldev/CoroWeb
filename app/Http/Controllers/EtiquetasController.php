<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Etiquetas};

class EtiquetasController extends Controller
{
    public function index() {
        $etiquetas = Etiquetas::all(); // Trae todas las canciones
        return view('etiquetas.index', compact('etiquetas'));
    }
    public function show($id) {
        $id = decrypt($id);
        $etiqueta = Etiquetas::findOrFail($id);
        return view('etiquetas.etiqueta', compact('etiqueta'));
    }

    public function create() {
        $nextId = Etiquetas::max('id') + 1;
        return view('etiquetas.create', compact('nextId'));
    }

    public function store(Request $request) {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $etiqueta = new Etiquetas();
        $etiqueta->nombre = $request->nombre;
        $etiqueta->save();

        return redirect()->route('etiquetas.create')->with('success', 'Etiqueta creada correctamente.');
    }

    public function edit($id) {
        $id = decrypt($id);
        $etiqueta = Etiquetas::findOrFail($id);
        return view('etiquetas.edit', compact('etiqueta'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:etiquetas,nombre,' . $id,
        ]);

        $etiqueta = Etiquetas::findOrFail($id);
        $etiqueta->nombre = $request->nombre;
        $etiqueta->save();

        return redirect()->route('etiquetas.index')->with('success', 'Etiqueta actualizada correctamente.');
    }
    
    public function destroy($id) {
        $etiqueta = Etiquetas::findOrFail($id);
        $etiqueta->delete();
    
        return redirect()->route('etiquetas.index')->with('success', 'Etiqueta eliminada correctamente.');
    }
}
