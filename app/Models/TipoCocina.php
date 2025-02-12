<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoCocina extends Model
{
    use HasFactory;
    protected $table = 'tipo_cocina';

    // Relacion con la tabla restaurantes
    public function restaurantes(){
        return $this->hasMany(Restaurante::class, 'tipo_cocina_id');
    }

}
