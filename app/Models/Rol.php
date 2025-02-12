<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rol extends Model
{
    use HasFactory;
    protected $table = 'roles';

    // Relacion con la tabla usuarios
    public function usuarios() {
        return $this->hasMany(Usuario::class, 'rol_id');
    }

}
