import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import babel from '@rollup/plugin-babel';
import copy from 'rollup-plugin-copy';
import { createVuePlugin } from 'vite-plugin-vue2';
import ViteComponents from 'vite-plugin-components';

export default defineConfig({
    plugins: [
        laravel([
            /*
             * Sourced asset dependencies via node_modules and JS bootstrapping
             */
            'resources/js/app.js',
            'resources/sass/app.scss',

            /*
             * Themes
             *
             * Note: Default wysibb theme is compiled into public/css/app.css from resources/sass/app.scss
             */
            'resources/sass/themes/galactic.scss',
            'resources/sass/themes/dark-blue.scss',
            'resources/sass/themes/dark-green.scss',
            'resources/sass/themes/dark-pink.scss',
            'resources/sass/themes/dark-purple.scss',
            'resources/sass/themes/dark-red.scss',
            'resources/sass/themes/dark-teal.scss',
            'resources/sass/themes/dark-yellow.scss',
            'resources/sass/themes/cosmic-void.scss',

            /*
             * Login and TwoStep Auth styles
             *
             * We compile each of these separately since they should only be loaded with the certain views
             */
            'resources/sass/main/login.scss',
            'resources/sass/main/twostep.scss',

            /* Babel File  */
            'resources/js/unit3d.js',
        ]),
        babel({ babelHelpers: 'bundled' }),
        copy({
            targets: [
                { src: 'resources/sass/vendor/webfonts/wysibb', dest: 'public/fonts/wysibb' },
                { src: 'resources/sass/vendor/webfonts/font-awesome', dest: 'public/fonts/font-awesome' },
                { src: 'resources/sass/vendor/webfonts/bootstrap', dest: 'public/fonts/bootstrap' },
            ],
        }),
        createVuePlugin(),
        ViteComponents({ transformer: 'vue2' }),
    ],
});
