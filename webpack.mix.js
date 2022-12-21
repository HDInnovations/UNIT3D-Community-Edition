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
    .sass('resources/sass/app.scss', 'public/css')
    .purgeCss()

    /*
     * Themes
     */
    .sass('resources/sass/themes/galactic.scss', 'public/css/themes/galactic.css')
    .sass('resources/sass/themes/dark-blue.scss', 'public/css/themes/dark-blue.css')
    .sass('resources/sass/themes/dark-green.scss', 'public/css/themes/dark-green.css')
    .sass('resources/sass/themes/dark-pink.scss', 'public/css/themes/dark-pink.css')
    .sass('resources/sass/themes/dark-purple.scss', 'public/css/themes/dark-purple.css')
    .sass('resources/sass/themes/dark-red.scss', 'public/css/themes/dark-red.css')
    .sass('resources/sass/themes/dark-teal.scss', 'public/css/themes/dark-teal.css')
    .sass('resources/sass/themes/dark-yellow.scss', 'public/css/themes/dark-yellow.css')
    .sass('resources/sass/themes/cosmic-void.scss', 'public/css/themes/cosmic-void.css')

    /*
     * Login and TwoStep Auth styles
     *
     * We compile each of these separately since they should only be loaded with the certain views
     */
    .sass('resources/sass/main/login.scss', 'public/css/main/login.css')
    .sass('resources/sass/main/twostep.scss', 'public/css/main/twostep.css')

    /*
     * Here we take all these scripts and compile them into a single 'unit3d.js' file that will be loaded after 'app.js'
     *
     * Note: The order of this array will matter, no different then linking these assets manually in the html
     */
    .babel(['resources/js/unit3d/tmdb.js', 'resources/js/unit3d/parser.js', 'resources/js/unit3d/helper.js', 'resources/js/unit3d/custom.js'], 'public/js/unit3d.js')

    /*
     * Copy assets
     */
    .copy('resources/sass/vendor/webfonts/font-awesome', 'public/fonts/font-awesome')
    .copy('resources/sass/vendor/webfonts/bootstrap', 'public/fonts/bootstrap')

    /*
     * Extra JS
     */
    .js('resources/js/unit3d/imgbb.js', 'public/js')
    .js('resources/js/vendor/alpine.js', 'public/js')
    .js('resources/js/vendor/virtual-select.js', 'public/js')
    .js('resources/js/unit3d/chat.js', 'public/js');
