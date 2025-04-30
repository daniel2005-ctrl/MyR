import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import browserSync from 'vite-plugin-browser-sync';

export default defineConfig({
    plugins: [
        laravel([
            'resources/css/app.css',
            'resources/js/app.js',
        ]),
        browserSync({
            proxy: 'http://127.0.0.1:8000', // Asegúrate de que esté apuntando al servidor de Laravel
            files: [
                'public/**/*.css',
                'public/**/*.js',
                'resources/views/**/*.blade.php',
                'routes/**/*.php',
                'app/**/*.php'
            ],
            notify: false,
            open: true,  // Esto abrirá automáticamente el navegador
        }),
    ],
    server: {
        host: 'localhost',
        port: 5173,  // El puerto donde Vite está sirviendo los archivos
    },
});
