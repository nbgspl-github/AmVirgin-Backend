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

mix.react('resources/js/app.js', 'public/js').styles([
	'resources/css/admin/all.css',
	'resources/css/admin/animate.css',
	'resources/css/admin/app.css',
	'resources/css/admin/bootstrap.min.css',
	'resources/css/admin/bootstrap-duration-picker.css',
	'resources/css/admin/ckin.min.css',
	'resources/css/admin/custom.css',
	'resources/css/admin/dataTables.bootstrap4.min.css',
	'resources/css/admin/datedropper.min.css',
	'resources/css/admin/daterangepicker.css',
	'resources/css/admin/gijgo-time-picker.css',
	'resources/css/admin/icons.css',
	'resources/css/admin/jquery.tag-editor.css',
	'resources/css/admin/loading.min.css.css',
	'resources/css/admin/multipleselect2.css',
	'resources/css/admin/percircle.css',
	'resources/css/admin/style.css',
	'resources/css/admin/style2.css',
	'resources/css/admin/stylen.css',
	'resources/css/admin/tageditor.css',
	'resources/css/admin/timingfield.css',
	'resources/css/admin/typicons.css',
], 'public/css/compiled.css');
