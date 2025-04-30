const mix = require('laravel-mix');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');

mix.setPublicPath('public');

// Si solo querés que observe los archivos, podés evitar compilar nada
mix.webpackConfig({
    plugins: [
        new BrowserSyncPlugin({
            proxy: 'localhost/MyR', // <--- ajustalo si tu ruta es diferente
            files: [
                'public/**/*.css',
                'public/**/*.js',
                'resources/views/**/*.blade.php',
                'routes/**/*.php',
                'app/**/*.php'
            ],
            notify: false,
            open: true,
            reloadDelay: 0
        })
    ]
});
