<?php

namespace Database\Factories;

use App\Models\Notificacion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notificacion>
 */
class NotificacionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Notificacion::class;

    public function definition(): array
    {
        return [
            'mensaje' => $this->faker->sentence,
            'sent_at' => $this->faker->dateTimeThisYear,
            'restaurante_id' => \App\Models\Restaurante::factory(),
        ];
    }
}
