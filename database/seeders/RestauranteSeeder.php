<?php

namespace Database\Seeders;

use App\Models\Restaurante;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RestauranteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        Restaurante::factory()->count(20)->create();
    }
}
