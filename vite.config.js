import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // Tambahkan konfigurasi server agar Vite menghasilkan URL HTTPS di production
    server: {
        hmr: {
            host: 'localhost',
        },
    },
});