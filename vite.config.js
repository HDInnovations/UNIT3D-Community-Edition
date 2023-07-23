import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue2';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/sass/main/login.scss',
                'resources/sass/main/twostep.scss',
                'resources/sass/themes/cosmic-void.scss',
                'resources/sass/themes/dark-blue.scss',
                'resources/sass/themes/dark-green.scss',
                'resources/sass/themes/dark-pink.scss',
                'resources/sass/themes/dark-purple.scss',
                'resources/sass/themes/dark-red.scss',
                'resources/sass/themes/dark-teal.scss',
                'resources/sass/themes/dark-yellow.scss',
                'resources/sass/themes/galactic.scss',
                'resources/js/app.js',
                'resources/js/unit3d/chat.js',
                'resources/js/unit3d/imgbb.js',
                'resources/js/vendor/alpine.js',
                'resources/js/vendor/virtual-select.js',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});
