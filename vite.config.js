import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/scss/app.scss', 'resources/js/app.js', 'resources/scss/publicapp.scss', 'resources/js/public-app.js'],
            refresh: true,
        }),
    ],
});
