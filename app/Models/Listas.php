<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listas extends Model
{
    protected $connection = 'supabase';
    protected $table = 'listas';
    protected $primaryKey = 'id';
    public $timestamps = false; // <-- desactiva created_at y updated_at
}
