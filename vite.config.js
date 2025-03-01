import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/custom.css',
                'resources/css/formLogin.css',
                'resources/css/register.css',
                'resources/css/crudRestaurante.css',
                'resources/css/crudUnificado.css',
                'resources/css/formEditRestaurant.css',
                'resources/css/createRestaurante.css',
                'resources/css/crudUsuario.css',
                'resources/css/principal.css',
                'resources/js/app.js',
                'resources/js/app.js',
                'resources/js/rating.js',
                'resources/js/createRestaurante.js',
                'resources/js/restaurantes.js',
                'resources/js/restaurantFilters.js',
                'resources/js/usuarios.js'
            ],
            refresh: true,
        }),
    ],
});
