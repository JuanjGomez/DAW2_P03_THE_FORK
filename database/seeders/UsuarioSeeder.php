<?php

namespace Database\Seeders;

use App\Models\Usuario;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        // Usuarios administradores
        $usuarios = [
            [
                'username' => 'admin',
                'email' => 'admin@thefork.com',
                'password' => Hash::make('qweQWE123'),
                'rol_id' => 1, // admin
            ],
            [
                'username' => 'admin2',
                'email' => 'admin2@thefork.com',
                'password' => Hash::make('qweQWE123'),
                'rol_id' => 1, // admin
            ],
        ];

        // Usuarios gerentes
        $gerentes = [
            [
                'username' => 'gerente1',
                'email' => 'gerente1@thefork.com',
                'password' => Hash::make('qweQWE123'),
                'rol_id' => 2, // gerente
            ],
            [
                'username' => 'gerente2',
                'email' => 'gerente2@thefork.com',
                'password' => Hash::make('qweQWE123'),
                'rol_id' => 2, // gerente
            ],
            [
                'username' => 'gerente3',
                'email' => 'gerente3@thefork.com',
                'password' => Hash::make('qweQWE123'),
                'rol_id' => 2, // gerente
            ],
        ];

        // Usuarios estándar con nombres reales
        $usuariosEstandar = [
            [
                'username' => 'juanperez',
                'email' => 'juanperez@example.com',
                'password' => Hash::make('qweQWE123'),
                'rol_id' => 3, // standard
            ],
            [
                'username' => 'mariagarcia',
                'email' => 'mariagarcia@example.com',
                'password' => Hash::make('qweQWE123'),
                'rol_id' => 3, // standard
            ],
            [
                'username' => 'carloslopez',
                'email' => 'carloslopez@example.com',
                'password' => Hash::make('qweQWE123'),
                'rol_id' => 3, // standard
            ],
            [
                'username' => 'lauramartinez',
                'email' => 'lauramartinez@example.com',
                'password' => Hash::make('qweQWE123'),
                'rol_id' => 3, // standard
            ],
            [
                'username' => 'pedrosanchez',
                'email' => 'pedrosanchez@example.com',
                'password' => Hash::make('qweQWE123'),
                'rol_id' => 3, // standard
            ],
        ];

        // Crear todos los usuarios
        foreach (array_merge($usuarios, $gerentes, $usuariosEstandar) as $usuario) {
            Usuario::create($usuario);
        }
    }
}
