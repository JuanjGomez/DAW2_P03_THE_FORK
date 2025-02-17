<?php

namespace Database\Seeders;

use App\Models\Restaurante;
use Illuminate\Database\Seeder;

class RestauranteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $restaurantes = [
            [
                'nombre_r' => 'Disfrutar',
                'descripcion' => 'Cocina creativa mediterránea con técnicas vanguardistas.',
                'direccion' => 'Carrer de Villarroel, 163',
                'precio_promedio' => 190.00,
                'imagen' => 'disfrutar.webp',
                'municipio' => 'Barcelona',
                'tipo_cocina_id' => 6, // Alta cocina
                'manager_id' => 1,
            ],
            [
                'nombre_r' => 'Asador Etxebarri',
                'descripcion' => 'Especializado en parrilla con ingredientes de alta calidad.',
                'direccion' => 'Plaza San Juan, 1',
                'precio_promedio' => 180.00,
                'imagen' => 'asador_etxebarri.jpg',
                'municipio' => 'Atxondo',
                'tipo_cocina_id' => 5, // Tradicional
                'manager_id' => 2,
            ],
            [
                'nombre_r' => 'DiverXO',
                'descripcion' => 'Experiencia gastronómica vanguardista y sorprendente.',
                'direccion' => 'NH Collection Eurobuilding, C/ Padre Damián, 23',
                'precio_promedio' => 300.00,
                'imagen' => 'diverxo.png',
                'municipio' => 'Madrid',
                'tipo_cocina_id' => 6, // Alta cocina
                'manager_id' => 3,
            ],
            [
                'nombre_r' => 'El Celler de Can Roca',
                'descripcion' => 'Alta cocina creativa con influencias catalanas.',
                'direccion' => 'Carrer de Can Sunyer, 48',
                'precio_promedio' => 250.00,
                'imagen' => 'el_celler_de_can_roca.jpg',
                'municipio' => 'Girona',
                'tipo_cocina_id' => 6, // Alta cocina
                'manager_id' => 4,
            ],
            [
                'nombre_r' => 'Arzak',
                'descripcion' => 'Cocina vasca innovadora con estrella Michelin.',
                'direccion' => 'Avenida del Alcalde Elósegui, 273',
                'precio_promedio' => 210.00,
                'imagen' => 'arzak.jpg',
                'municipio' => 'San Sebastián',
                'tipo_cocina_id' => 6, // Alta cocina
                'manager_id' => 5,
            ],
            [
                'nombre_r' => 'Azurmendi',
                'descripcion' => 'Cocina sostenible y vanguardista con raíces vascas.',
                'direccion' => 'Barrio Legina s/n',
                'precio_promedio' => 220.00,
                'imagen' => 'azurmendi.jpg',
                'municipio' => 'Larrabetzu',
                'tipo_cocina_id' => 6, // Alta cocina
                'manager_id' => 6,
            ],
            [
                'nombre_r' => 'Quique Dacosta',
                'descripcion' => 'Cocina de autor con influencias mediterráneas.',
                'direccion' => 'Carrer Rascassa, 1',
                'precio_promedio' => 230.00,
                'imagen' => 'quique_dacosta.webp',
                'municipio' => 'Dénia',
                'tipo_cocina_id' => 6, // Alta cocina
                'manager_id' => 7,
            ],
            [
                'nombre_r' => 'Mugaritz',
                'descripcion' => 'Cocina experimental que desafía los sentidos.',
                'direccion' => 'Aldura Aldea, 20',
                'precio_promedio' => 240.00,
                'imagen' => 'mugaritz.webp',
                'municipio' => 'Errenteria',
                'tipo_cocina_id' => 6, // Alta cocina
                'manager_id' => 8,
            ],
            [
                'nombre_r' => 'Martín Berasategui',
                'descripcion' => 'Cocina vasca moderna con técnicas innovadoras.',
                'direccion' => 'Loidi Kalea, 4',
                'precio_promedio' => 250.00,
                'imagen' => 'martin_berasategui.webp',
                'municipio' => 'Lasarte-Oria',
                'tipo_cocina_id' => 6, // Alta cocina
                'manager_id' => 9,
            ],
            [
                'nombre_r' => 'Aponiente',
                'descripcion' => 'Alta cocina del mar con influencia andaluza.',
                'direccion' => 'Calle Francisco Cossi Ochoa, s/n',
                'precio_promedio' => 220.00,
                'imagen' => 'aponiente.webp',
                'municipio' => 'El Puerto de Santa María',
                'tipo_cocina_id' => 6, // Alta cocina
                'manager_id' => 10,
            ],
            [
                'nombre_r' => 'Etxebarri',
                'descripcion' => 'Parrilla vasca con ingredientes de primera calidad.',
                'direccion' => 'Plaza San Juan, 1',
                'precio_promedio' => 180.00,
                'imagen' => 'etxebarri.jpg',
                'municipio' => 'Axpe',
                'tipo_cocina_id' => 5, // Tradicional
                'manager_id' => null,
            ],
            [
                'nombre_r' => 'Cenador de Amós',
                'descripcion' => 'Cocina cántabra contemporánea en un entorno histórico.',
                'direccion' => 'Barrio Villaverde de Pontones, s/n',
                'precio_promedio' => 200.00,
                'imagen' => 'cenador_de_amos.jpg',
                'municipio' => 'Pontones',
                'tipo_cocina_id' => 6, // Alta cocina
                'manager_id' => null,
            ],
            [
                'nombre_r' => 'Lasarte',
                'descripcion' => 'Cocina de autor con influencias vascas y mediterráneas.',
                'direccion' => 'Carrer de Mallorca, 259',
                'precio_promedio' => 240.00,
                'imagen' => 'lasarte.jpg',
                'municipio' => 'Barcelona',
                'tipo_cocina_id' => 6, // Alta cocina
                'manager_id' => null,
            ]
        ];

        foreach ($restaurantes as $restaurante) {
            Restaurante::create($restaurante);
        }
    }
}