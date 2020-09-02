const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css', { implementation: require('node-sass') });
/*
mix.scripts([
	'public/js/jquery-3.4.1.min.js',
	'public/js/popper.min.js'
	'public/js/toastr.js'
	'public/slick/slick.min.js'
	'public/js/bootstrap.bundle.min.js'
], 'public/js/plugins.js');

mix.styles([
    'public/css/animate.css',
    'public/css/fontawesome/css/all.min.css'
    'public/slick/slick.css'
    'public/css/bootstrap.css'
], 'public/css/plugins.css');
*/