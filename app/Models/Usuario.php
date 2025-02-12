<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends Model
{
    use HasFactory;
    protected $table = 'usuarios';

    // Relacion con la tabla roles
    public function rol(){
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    // Relacion con la tabla ratings
    public function ratings(){
        return $this->hasMany(Rating::class, 'user_id');
    }

}
