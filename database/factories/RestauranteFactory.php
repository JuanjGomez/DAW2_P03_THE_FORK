<?php

namespace Database\Factories;

use App\Models\Restaurante;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Restaurante>
 */
class RestauranteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Restaurante::class;

    public function definition(): array
    {
        return [
            'nombre_r' => $this->faker->company,
            'descripcion' => $this->faker->paragraph,
            'direccion' => $this->faker->address,
            'precio_promedio' => $this->faker->randomFloat(2, 5, 50),
            'imagen' => $this->faker->imageUrl,
            'municipio' => $this->faker->city,
            'tipo_cocina_id' => \App\Models\TipoCocina::factory(),
        ];
    }
}
