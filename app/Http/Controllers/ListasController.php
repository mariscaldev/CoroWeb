<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Canciones, Etiquetas, Listas};

class ListasController extends Controller
{
    public function index() {
        $listas = Listas::all();
        return view('listas.index', compact('listas'));
    }

    public function show($id) {
        $id = decrypt($id);
        $lista = Listas::findOrFail($id);
        return view('listas.lista', compact('lista'));
    }

    public function create() {
        $canciones = Canciones::orderBy('nombre', 'asc')->get();
        $nextId = Listas::max('id') + 1;
        return view('listas.create', compact('nextId', 'canciones'));
    }

    public function store(Request $request) {
        $request->validate([
            'nombre' => 'required|string|max:1000',
            'id_canciones' => 'nullable|string|max:1000',
        ]);
    
        $lista = new Listas();
        $lista->nombre = $request->nombre;
        $lista->fecha = now(); // Agrega timestamp actual
        $lista->id_canciones = $request->id_canciones;
        $lista->estado = true; // Campo booleano
        $lista->save();
    
        return redirect()->route('listas.index')->with('success', 'Lista creada correctamente.');
    }

    public function edit($id) {
        $id = decrypt($id);
        $lista = Listas::findOrFail($id);
        $canciones = Canciones::orderBy('nombre', 'asc')->get();
        return view('listas.edit', compact('lista', 'canciones'));
    }

    public function update(Request $request, $id) {
        $id = decrypt($id);
    
        $request->validate([
            'nombre' => 'required|string|max:500',
            'id_canciones' => 'nullable|string|max:1000',
            'estado' => 'required|boolean',
            'fecha' => 'required|date',
        ]);
    
        $lista = Listas::findOrFail($id);
        $lista->nombre = $request->nombre;
        $lista->fecha = $request->fecha;
        $lista->estado = $request->estado;
        $lista->id_canciones = $request->id_canciones;
        $lista->save();
    
        return redirect()->route('listas.index')->with('success', 'Lista actualizada correctamente.');
    }
    
    public function destroy($id) {
        $id = decrypt($id); // <- Agrega esta línea
        $lista = Listas::findOrFail($id);
        $lista->delete();
    
        return redirect()->route('listas.index')->with('success', 'Canción eliminada correctamente.');
    }

    public function toggleEstado($id) {
        $id = decrypt($id);
        $lista = Listas::findOrFail($id);
        $lista->estado = !$lista->estado; // alterna entre true/false
        $lista->save();

        return response()->json(['success' => true, 'estado' => $lista->estado]);
    }

}