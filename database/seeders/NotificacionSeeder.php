<?php

namespace Database\Seeders;

use App\Models\Notificacion;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $notificaciones = [
            [
                'mensaje' => 'Nuevo rating recibido en La Tagliatella',
                'sent_at' => now(),
                'restaurante_id' => 1,
            ],
            [
                'mensaje' => 'Nuevo rating recibido en Sushi Palace',
                'sent_at' => now(),
                'restaurante_id' => 2,
            ],
            [
                'mensaje' => 'Nuevo rating recibido en Green Bites',
                'sent_at' => now(),
                'restaurante_id' => 3,
            ],
        ];

        foreach ($notificaciones as $notificacion) {
            Notificacion::create($notificacion);
        }
    }
}
