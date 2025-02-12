<?php

namespace Database\Factories;

use App\Models\Rating;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rating>
 */
class RatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Rating::class;

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\Usuario::factory(),
            'restaurante_id' => \App\Models\Restaurante::factory(),
            'rating' => $this->faker->randomFloat(1, 1, 5),
            'comentario' => $this->faker->paragraph,
        ];
    }
}
