import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue2';
import purge from '@erbelion/vite-plugin-laravel-purgecss'

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/sass/main.scss',
        'resources/sass/pages/_auth.scss',
        'resources/sass/themes/_cosmic-void.scss',
        'resources/sass/themes/_dark-blue.scss',
        'resources/sass/themes/_dark-green.scss',
        'resources/sass/themes/_dark-pink.scss',
        'resources/sass/themes/_dark-purple.scss',
        'resources/sass/themes/_dark-red.scss',
        'resources/sass/themes/_dark-teal.scss',
        'resources/sass/themes/_dark-yellow.scss',
        'resources/sass/themes/_galactic.scss',
        'resources/sass/themes/_light.scss',
        'resources/sass/themes/_material-design-v3-amoled.scss',
        'resources/sass/themes/_material-design-v3-dark.scss',
        'resources/sass/themes/_material-design-v3-light.scss',
        'resources/sass/themes/_nord.scss',
        'resources/sass/themes/_revel.scss',
        'resources/js/app.js',
        'resources/js/unit3d/chat.js',
        'resources/js/unit3d/unit3d.js',
        'resources/js/unit3d/imgbb.js',
        'resources/js/vendor/alpine.js',
        'resources/js/vendor/virtual-select.js',
      ],
      refresh: true,
    }),
    purge({
      templates: ['blade'],
      paths: ['resources/views/**/*.blade.php']
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
  resolve: {
    alias: {
      vue: 'vue/dist/vue.esm.js',
    }
  }
});