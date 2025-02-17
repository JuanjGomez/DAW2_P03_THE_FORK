<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Restaurante;

class RestauranteModificado extends Mailable
{
    use Queueable, SerializesModels;

    public $restaurante;
    public $cambios;

    public function __construct(Restaurante $restaurante, array $cambios)
    {
        $this->restaurante = $restaurante;
        $this->cambios = $cambios;
    }

    public function build()
    {
        return $this->view('emails.restaurante-modificado')
            ->subject('Cambios en el restaurante ' . $this->restaurante->nombre_r);
    }
}
