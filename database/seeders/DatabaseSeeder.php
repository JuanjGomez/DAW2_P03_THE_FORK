<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RolSeeder::class,
            UsuarioSeeder::class,
            TipoCocinaSeeder::class,
            RestauranteSeeder::class,
            RatingSeeder::class,
            NotificacionSeeder::class,
        ]);
    }
}
