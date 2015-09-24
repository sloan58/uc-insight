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

elixir(function(mix) {

    var bowerLoc = 'bower_components/';
    var resourceLoc = 'resources/assets/';

    // Copy Style Sheet Dependencies
    mix.copy(
            bowerLoc + 'bootstrap/less',
            resourceLoc + 'less/bootstrap'
        ).copy(
            bowerLoc + 'ionicons/less',
            resourceLoc + 'less/ionicons'
        ).copy(
            bowerLoc + 'fontawesome/less',
            resourceLoc + 'less/fontawesome'
        ).copy(
            bowerLoc + 'admin-lte/dist/css/AdminLTE.min.css',
            resourceLoc + 'less/AdminLTE.less'
        ).copy(
            bowerLoc + 'admin-lte/dist/css/skins/skin-blue.min.css',
            resourceLoc + 'less/skin-blue.min.less'
    );

    //Copy Javascript Dependencies
    mix.copy(
            bowerLoc + 'admin-lte/dist/js/app.min.js',
            resourceLoc + 'js/admin-lte.min.js'
        ).copy(
            bowerLoc + 'jquery/dist/jquery.js',
            resourceLoc + '/js/jquery.js'
        ).copy(
            bowerLoc + 'bootstrap/dist/js/bootstrap.js',
            resourceLoc + 'js/bootstrap.js'
        ).copy(
            bowerLoc + 'admin-lte/dist/js/pages/dashboard.js',
            resourceLoc + 'js/dashboard.js'
        ).copy(
            bowerLoc + 'admin-lte/dist/js/pages/dashboard2.js',
            resourceLoc + 'js/dashboard2.js'
    );

    //Copy Font Dependencies
    mix.copy(
            bowerLoc + 'admin-lte/dist/img',
            'public/assets/fonts'
        ).copy(
            bowerLoc + 'bootstrap/dist/fonts',
            'public/assets/fonts'
        ).copy(
            bowerLoc + 'fontawesome/fonts',
            'public/assets/fonts'
    );

    // Combine scripts
    mix.scripts([
        'jquery.js',
        'bootstrap.js',
        'admin-lte.min.js',
        'app.js'
    ], 'public/assets/js/app.js', 'resources/assets/js/');

    // Compile Less
    mix.less([
        'AdminLTE.less',
        'skin-blue.min.less',
        'app.less'
    ], 'public/assets/css');

});
