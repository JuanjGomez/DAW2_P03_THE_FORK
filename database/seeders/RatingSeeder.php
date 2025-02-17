<?php

namespace Database\Seeders;

use App\Models\Rating;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $ratings = [
            [
                'user_id' => 3, // usuario1
                'restaurante_id' => 1, // La Tagliatella
                'rating' => 4.5,
                'comentario' => 'Excelente comida italiana, muy buen servicio.',
            ],
            [
                'user_id' => 3, // usuario1
                'restaurante_id' => 2, // Sushi Palace
                'rating' => 5.0,
                'comentario' => 'El mejor sushi que he probado en la ciudad.',
            ],
            [
                'user_id' => 3, // usuario1
                'restaurante_id' => 3, // Green Bites
                'rating' => 4.0,
                'comentario' => 'Muy buena opción vegana, platos creativos.',
            ],
        ];

        foreach ($ratings as $rating) {
            Rating::create($rating);
        }
    }
}
