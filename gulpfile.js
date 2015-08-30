var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

var paths = {
  'bootstrap': './bower_components/bootstrap',
  'colpick': './bower_components/colpick',
  'fabric': './bower_components/fabric.js',
  'jquery': './bower_components/jquery',
  'jquery-ui': './bower_components/jquery-ui',
  'landing-page': './bower_components/startbootstrap-landing-page'

};

elixir(function(mix) {
  mix.styles([
        paths.colpick + '/css/colpick.css',
        paths.bootstrap + '/dist/css/bootstrap.min.css',
        paths.bootstrap + '/dist/css/bootstrap-theme.min.css',
        paths['landing-page'] + '/css/font-awesome.min.css',
        'landing-page.css',
        'canvas.css'
      ], 'public/css/')
      .copy([
        paths.bootstrap + 'fonts/bootstrap/**',
        paths['landing-page'] + '/font-awesome/**'
      ], 'public/fonts')
      .scripts([
        paths.jquery + '/dist/jquery.min.js',
        paths.bootstrap + '/dist/js/bootstrap.min.js',
        paths.fabric + '/dist/fabric.min.js',
        paths.colpick + '/js/colpick.js',
        paths['jquery-ui'] + '/jquery-ui.min.js',
        'canvas.js'
      ], 'public/js/app.js')
      .copy([
        'resources/assets/images/**',
        paths['landing-page'] + '/img/**'
      ], 'public/images');
});
