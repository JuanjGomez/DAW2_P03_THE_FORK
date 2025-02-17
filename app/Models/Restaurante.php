<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Restaurante extends Model
{
    use HasFactory;
    protected $table = 'restaurantes';
    protected $fillable = ['nombre_r', 'descripcion', 'direccion', 'precio_promedio', 'imagen', 'municipio', 'tipo_cocina_id', 'manager_id'];

    // Relacion con la tabla tipo_cocina
    public function tipoCocina(){
        return $this->belongsTo(TipoCocina::class, 'tipo_cocina_id');
    }

    // Relacion con la tabla ratings
    public function ratings(){
        return $this->hasMany(Rating::class, 'restaurante_id');
    }

    // Relacion con la tabla notificaciones
    public function notificaciones(){
        return $this->hasMany(Notificacion::class, 'restaurante_id');
    }

    // Añadir la relación con el manager
    public function manager()
    {
        return $this->belongsTo(Usuario::class, 'manager_id');
    }

}
