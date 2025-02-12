<?php

namespace Database\Seeders;

use App\Models\TipoCocina;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoCocinaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        TipoCocina::factory()->count(5)->create();
    }
}
