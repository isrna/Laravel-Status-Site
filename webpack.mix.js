const mix = require('laravel-mix');
const del = require('del');
mix.disableNotifications();

// Disable mix-manifest.json
Mix.manifest.refresh = _ => void 0

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

//JavaScript
mix.js('resources/js/app.js', 'public/js').minify('public/js/app.js').then(() => {
    del('public/js/app.js'); // deletes the temp file
});

//SASS/CSS
mix.sass('resources/sass/app.scss', 'public/css').minify('public/css/app.css').then(() => {
        del('public/css/app.css'); // deletes the temp file
    });
