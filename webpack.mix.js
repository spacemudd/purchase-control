let mix = require('laravel-mix');

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.sass', 'public/css');

mix.copy('node_modules/font-awesome/fonts', 'public/fonts/font-awesome');
