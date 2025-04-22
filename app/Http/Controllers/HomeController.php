<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Canciones, Etiquetas};

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        $datos = Etiquetas::leftJoin('canciones', 'etiquetas.id', '=', 'canciones.etiqueta')
            ->select('etiquetas.nombre', \DB::raw('COUNT(canciones.id) as cantidad'))
            ->groupBy('etiquetas.nombre')
            ->get()
            ->toArray();
    
        $sinEtiqueta = Canciones::whereNull('etiqueta')->count();
        $totalCanciones = Canciones::count();
        $ultimas = Canciones::orderByDesc('id')->take(5)->get();
    
        return view('home', compact('datos', 'sinEtiqueta', 'totalCanciones', 'ultimas'));
    }
    
}
