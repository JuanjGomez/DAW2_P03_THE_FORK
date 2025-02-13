<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'usuarios';
    protected $fillable = ['username', 'email', 'password', 'rol_id'];

    // Relacion con la tabla roles
    public function rol(){
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    // Relacion con la tabla ratings
    public function ratings(){
        return $this->hasMany(Rating::class, 'user_id');
    }

}
