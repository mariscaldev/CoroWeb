<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Canciones extends Model
{
    protected $connection = 'supabase'; // Usa tu conexión de Supabase
    protected $table = 'canciones';     // Nombre exacto de tu tabla
    protected $primaryKey = 'id';       // Campo clave primaria
    public $timestamps = false;         // Si tu tabla no tiene created_at / updated_at

    public function nombreEtiqueta() {
        return $this->belongsTo(Etiquetas::class, 'etiqueta', 'id');
    }

}
