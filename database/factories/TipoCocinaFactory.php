<?php

namespace Database\Factories;

use App\Models\TipoCocina;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TipoCocina>
 */
class TipoCocinaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = TipoCocina::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->randomElement(['Italiana', 'Mexicana', 'Japonesa', 'Vegana', 'Tradicional']),
        ];
    }
}
