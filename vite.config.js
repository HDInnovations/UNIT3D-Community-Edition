import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel([
            'resources/js/app.js',
            'resources/js/unit3d/chat.js',
            'resources/js/unit3d/imgbb.js',
            'resources/js/vendor/alpine.js',
            'resources/js/vendor/virtual-select.js',
        ]),
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
