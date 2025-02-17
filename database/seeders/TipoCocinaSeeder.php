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
        $tipos = [
            ['nombre' => 'Italiana'],
            ['nombre' => 'Mexicana'],
            ['nombre' => 'Japonesa'],
            ['nombre' => 'Vegana'],
            ['nombre' => 'Tradicional'],
            ['nombre' => 'Mediterránea'],
            ['nombre' => 'Asiática'],
            ['nombre' => 'Fusión'],
        ];

        foreach ($tipos as $tipo) {
            TipoCocina::create($tipo);
        }
    }
}
