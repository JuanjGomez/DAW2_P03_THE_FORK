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
                'resources/js/app.js',
                'resources/js/app.js',
                'resources/js/rating.js'
            ],
            refresh: true,
        }),
    ],
});
