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
        ).copy(
            bowerLoc + 'admin-lte/plugins/iCheck/square/blue.css',
            resourceLoc + 'less/blue.less'
        ).copy(
            bowerLoc + 'CodeMirror/lib/codemirror.css',
            resourceLoc + 'less/codemirror.less'
        ).copy(
            bowerLoc + 'datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css',
            resourceLoc + 'less/dataTables.less'
        ).copy(
            bowerLoc + 'datatables-buttons/css',
            resourceLoc + 'sass/'
        ).copy(
            bowerLoc + 'select2/dist/css/select2.min.css',
            resourceLoc + 'less/select2.less'
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
            bowerLoc + 'admin-lte/plugins/iCheck/icheck.min.js',
            resourceLoc + 'js/icheck.min.js'
        ).copy(
            bowerLoc + 'admin-lte/dist/js/pages/dashboard2.js',
            resourceLoc + 'js/dashboard2.js'
        ).copy(
            bowerLoc + 'CodeMirror/lib/codemirror.js',
            resourceLoc + 'js/codemirror.js'
        ).copy(
            bowerLoc + 'CodeMirror/mode/sql/sql.js',
            resourceLoc + 'js/codemirror-sql.js'
        ).copy(
            bowerLoc + 'datatables/media/js/jquery.dataTables.js',
            resourceLoc + 'js/dataTables.js'
        ).copy(
            bowerLoc + 'datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.js',
            resourceLoc + 'js/dataTables.bootstrap.js'
        ).copy(
            bowerLoc + 'datatables-buttons/js',
            resourceLoc + 'js/'
        ).copy(
            bowerLoc + 'select2/dist/js/select2.full.min.js',
            resourceLoc + 'js/select2.js'
        ).copy(
            bowerLoc + 'bootstrap-table/src/bootstrap-table.js',
            resourceLoc + 'js/bootstrap-table.js'
        ).copy(
            bowerLoc + 'bootstrap-table/src/extensions/export/bootstrap-table-export.js',
            resourceLoc + 'js/bootstrap-table-export.js'
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
        'icheck.min.js',
        'codemirror.js',
        'codemirror-sql.js',
        'dataTables.js',
        'dataTables.bootstrap.js',
        'dataTables.buttons.js',
        'buttons.bootstrap.js',
        'buttons.colVis.js',
        'buttons.flash.js',
        'buttons.html5.js',
        'buttons.jqueryui.js',
        'buttons.print.js',
        'select2.js',
        'bootstrap-table.js',
        'bootstrap-table-export.js',
        'app.js'
    ], 'public/assets/js/app.js', 'resources/assets/js/');

    // Compile Less
    mix.less([
        'skin-blue.min.less',
        'blue.less',
        'codemirror.less',
        'dataTables.less',
        'select2.less',
        'fresh-bootstrap-table.less',
        'AdminLTE.less',
        'app.less'
    ], 'public/assets/css');

    // Compile Sass
    mix.sass([
        'app.scss',
        'buttons.bootstrap.scss',
        'buttons.dataTables.scss',
        'common.scss',
        'mixins.scss'

    ], 'public/assets/css/sass');

});
