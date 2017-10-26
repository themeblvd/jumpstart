var gulp         = require('gulp'),
	zip          = require('gulp-zip'),
	clean        = require('gulp-clean'),
	replace      = require('gulp-replace'),
	rename       = require('gulp-rename'),
	concat 	     = require('gulp-concat'),
	minifyjs     = require('gulp-uglify'),
	sass         = require('gulp-sass'),
	minifycss    = require('gulp-clean-css'),
	autoprefixer = require('gulp-autoprefixer');

/**
 * Theme slug.
 */
var theme = 'jumpstart';

/**
 * Theme name.
 */
var themeName = 'Jump Start';

/**
 * Framework name.
 */
var frameworkName = 'Theme Blvd';

/**
 * Theme version.
 */
var version = '2.2.0';

/**
 * Supported browsers for CSS autoprefixer.
 *
 * Note: Loosely based on Bootstrap 4 browser
 * support policy.
 */
var browsers = [
	'Chrome >= 35',
	'Firefox >= 38',
	'Edge >= 12',
	'Explorer >= 10',
	'iOS >= 8',
	'Safari >= 8',
	'Android 2.3',
	'Android >= 4',
	'Opera >= 12'
];

/**
 * Empty out distribution before compiling
 * everything.
 */
gulp.task('clean', function() {

	return gulp.src('dist/*', {read: false})
        .pipe(clean());

});

/**
 * Merge theme framework into theme.
 */
gulp.task('render-themeblvd', ['clean'], function() {

	return gulp.src('lib/themeblvd/**')
    	.pipe(gulp.dest('dist/' + theme));

});

/**
 * Merge plugin manager into theme.
 */
gulp.task('render-plugin-manager', ['render-themeblvd'], function() {

	return gulp.src('lib/plugin-manager/**')
    	.pipe(gulp.dest('dist/' + theme + '/framework/admin/plugin-manager'));

});

/**
 * Render WordPress theme.
 */
gulp.task('render-src', ['render-plugin-manager'], function() {

	return gulp.src('src/**')
    	.pipe(gulp.dest('dist/' + theme));

});

/**
 * Render admin scripts (all except for options and
 * utils because they are built from partials).
 */
gulp.task('render-admin-js', ['render-src'], function() {

	var dir = 'dist/' + theme + '/framework/admin/assets/js/';

	var files = [
		dir + 'base.js',
		dir + 'menu.js',
		dir + 'meta-box.js',
		dir + 'modal.js',
		dir + 'options-page.js',
		dir + 'welcome.js'
	];

	return gulp.src(files)
		.pipe(minifyjs())
		.pipe(rename({ suffix: '.min' }))
    	.pipe(gulp.dest(dir));

});

/**
 * Render admin options scripts.
 */
gulp.task('render-admin-options-js', ['render-admin-js'], function() {

	var dir = 'dist/' + theme + '/framework/admin/assets/js/';

	var files = [
		dir + 'options/options-init.js',
		dir + 'options/options-setup.js',
		dir + 'options/options-bind.js',
		dir + 'options/options-media-uploader.js',
		dir + 'options/options-editor.js',
		dir + 'options/options-code-editor.js',
		dir + 'options/options-column-widths.js',
		dir + 'options/options-sortable.js',
		dir + 'options/options-browsers.js'
	];

	return gulp.src(files)
    	.pipe(concat('options.js'))
		.pipe(gulp.dest(dir))
		.pipe(minifyjs({output: {comments: /^!|@license/i}}))
		.pipe(rename({ suffix: '.min' }))
    	.pipe(gulp.dest(dir));

});

/**
 * Clear admin options partial scripts.
 */
gulp.task('clear-admin-options-js-partials', ['render-admin-options-js'], function() {

	var dir = 'dist/' + theme + '/framework/admin/assets/js/options/';

	return gulp.src(dir, {read: false})
        .pipe(clean());

});

/**
 * Render admin utility scripts.
 */
gulp.task('render-admin-utils-js', ['clear-admin-options-js-partials'], function() {

	var dir = 'dist/' + theme + '/framework/admin/assets/js/';

	var files = [
		dir + 'utils/utils-init.js',
		dir + 'utils/utils-tools.js',
		dir + 'utils/utils-confirm.js',
		dir + 'utils/utils-jquery.js',
		dir + 'utils/utils-modal.js',
		dir + 'utils/utils-setup.js',
		dir + 'utils/utils-accordion.js',
		dir + 'utils/utils-widgets.js'
	];

	return gulp.src(files)
    	.pipe(concat('utils.js'))
		.pipe(gulp.dest(dir))
		.pipe(minifyjs({output: {comments: /^!|@license/i}}))
		.pipe(rename({ suffix: '.min' }))
    	.pipe(gulp.dest(dir));

});

/**
 * Clear admin utility partial scripts.
 */
gulp.task('clear-admin-utils-js-partials', ['render-admin-utils-js'], function() {

	var dir = 'dist/' + theme + '/framework/admin/assets/js/utils/';

	return gulp.src(dir, {read: false})
        .pipe(clean());

});

/**
 * Render admin CSS.
 */
gulp.task('render-admin-css', ['clear-admin-utils-js-partials'], function() {

	var dir = 'dist/' + theme + '/framework/admin/assets/';

	var files = [
		dir + 'scss/base.scss',
		dir + 'scss/global.scss',
		dir + 'scss/menu.scss',
		dir + 'scss/options-page.scss',
		dir + 'scss/options.scss',
		dir + 'scss/utils.scss'
	];

	return gulp.src(files)
		.pipe(sass({outputStyle: 'expanded'}).on('error', sass.logError))
		.pipe(autoprefixer({
            browsers: browsers,
            cascade: false
        }))
		.pipe(gulp.dest(dir + '/css'))
		.pipe(minifycss())
		.pipe(rename({ suffix: '.min' }))
    	.pipe(gulp.dest(dir + '/css'));

});

/**
 * Clear admin Sass files from distributed
 * directory.
 */
gulp.task('clear-admin-scss', ['render-admin-css'], function() {

	var dir = 'dist/' + theme + '/framework/admin/assets/scss/';

	return gulp.src(dir, {read: false})
        .pipe(clean());

});

/**
 * Render theme info through PHP DocBlocks.
 */
gulp.task('render-info', ['clear-admin-scss'], function() {

	var packageSlug = themeName.replace(' ', '_'),
		frameworkSlug = frameworkName.replace(' ', '_');

	return gulp.src(['dist/**/*.php', 'dist/**/*.js'])
		.pipe(replace('@@name-package', packageSlug))
		.pipe(replace('@@name-framework', frameworkSlug))
    	.pipe(gulp.dest('dist'));

});

/**
 * Render text domain by replacing all instances of
 * @@text-domain with theme slug.
 */
gulp.task('render-text-domain', ['render-info'], function() {

	return gulp.src('dist/**/*.php')
		.pipe(replace('@@text-domain', theme))
    	.pipe(gulp.dest('dist'));

});

/**
 * Zip WordPress theme.
 */
gulp.task('render-theme-zip', ['render-text-domain'], function() {

	return gulp.src('dist/**')
		.pipe(zip(theme + '-' + version + '.zip'))
    	.pipe(gulp.dest('dist'));

});

/**
 * Serve all distrubtion tasks.
 */
gulp.task('serve', ['render-theme-zip'], function() {
	return;
});
