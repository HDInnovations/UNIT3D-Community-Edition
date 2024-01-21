let mix = require('laravel-mix');
require('laravel-mix-purgecss');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 |
 */

mix.version();
mix.options({
  processCssUrls: false
})

    /*
     * Sourced asset dependencies via node_modules and JS bootstrapping
     */
    .js('resources/js/app.js', 'public/js').vue({ version: 2 })
    .sass('resources/sass/main.scss', 'public/css/app.css')
    .purgeCss()

    /*
     * Themes
     */
    .sass('resources/sass/themes/_light.scss', 'public/css/themes/light.css')
    .sass('resources/sass/themes/_galactic.scss', 'public/css/themes/galactic.css')
    .sass('resources/sass/themes/_dark-blue.scss', 'public/css/themes/dark-blue.css')
    .sass('resources/sass/themes/_dark-green.scss', 'public/css/themes/dark-green.css')
    .sass('resources/sass/themes/_dark-pink.scss', 'public/css/themes/dark-pink.css')
    .sass('resources/sass/themes/_dark-purple.scss', 'public/css/themes/dark-purple.css')
    .sass('resources/sass/themes/_dark-red.scss', 'public/css/themes/dark-red.css')
    .sass('resources/sass/themes/_dark-teal.scss', 'public/css/themes/dark-teal.css')
    .sass('resources/sass/themes/_dark-yellow.scss', 'public/css/themes/dark-yellow.css')
    .sass('resources/sass/themes/_cosmic-void.scss', 'public/css/themes/cosmic-void.css')
    .sass('resources/sass/themes/_nord.scss', 'public/css/themes/nord.css')
    .sass('resources/sass/themes/_revel.scss', 'public/css/themes/revel.css')
    .sass('resources/sass/themes/_material-design-v3-light.scss', 'public/css/themes/material-design-v3-light.css')
    .sass('resources/sass/themes/_material-design-v3-dark.scss', 'public/css/themes/material-design-v3-dark.css')
    .sass('resources/sass/themes/_material-design-v3-amoled.scss', 'public/css/themes/material-design-v3-amoled.css')

    /*
     * Auth styles
     *
     * We compile each of these separately since they should only be loaded with the certain views
     */
    .sass('resources/sass/pages/_auth.scss', 'public/css/main/login.css')

    /*
     * Here we take all these scripts and compile them into a single 'unit3d.js' file that will be loaded after 'app.js'
     *
     * Note: The order of this array will matter, no different then linking these assets manually in the html
     */
    .babel(['resources/js/unit3d/tmdb.js', 'resources/js/unit3d/parser.js', 'resources/js/unit3d/helper.js'], 'public/js/unit3d.js')

    /*
     * Copy assets
     */
    .copy('resources/sass/vendor/webfonts/font-awesome', 'public/fonts/font-awesome')

    /*
     * Extra JS
     */
    .js('resources/js/unit3d/imgbb.js', 'public/js')
    .js('resources/js/vendor/alpine.js', 'public/js')
    .js('resources/js/vendor/virtual-select.js', 'public/js')
    .js('resources/js/unit3d/chat.js', 'public/js');
