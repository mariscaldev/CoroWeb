<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etiquetas extends Model
{
    protected $connection = 'supabase'; // Usa tu conexión de Supabase
    protected $table = 'etiquetas';     // Nombre exacto de tu tabla
    protected $primaryKey = 'id';       // Campo clave primaria
    public $timestamps = false;         // Si tu tabla no tiene created_at / updated_at

    // 🔧 Relación con canciones
    public function canciones() {
        return $this->hasMany(Canciones::class, 'etiqueta', 'id');
    }
}
