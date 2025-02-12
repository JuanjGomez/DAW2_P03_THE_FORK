<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rating extends Model
{
    use HasFactory;
    protected $table = 'ratings';

    // Relacion con la tabla usuarios
    public function usuario(){
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    // Relacion con la tabla restaurante
    public function restaurante(){
        return $this->belongsTo(Restaurante::class, 'restaurante_id');
    }

}
