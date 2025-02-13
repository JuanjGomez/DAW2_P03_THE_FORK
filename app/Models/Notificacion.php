<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notificacion extends Model
{
    use HasFactory;
    protected $table = 'notificaciones';
    protected $fillable = ['mensaje', 'sent_at', 'restaurante_id'];

    // Relacion con la tabla restaurantes
    public function restaurante(){
        return $this->belongsTo(Restaurante::class, 'restaurante_id');
    }

}
