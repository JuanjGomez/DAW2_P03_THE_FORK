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
        Notificacion::factory()->count(30)->create();
    }
}
