var gulp 	= require('gulp'),
	zip 	= require('gulp-zip'),
	clean	= require('gulp-clean');

/**
 * Theme slug.
 */
var theme = 'jumpstart';

/**
 * Theme version.
 */
var version = '2.2.0';

/**
 * All required tasks for distribution.
 */
var serve = [
	'clean',
	'render-wp-theme',
	'render-wp-theme-zip'
];

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
 * Render WordPress theme.
 */
gulp.task('render-wp-theme', ['clean'], function() {

	return gulp.src('src/**')
    	.pipe(gulp.dest('dist/' + theme));

});

/**
 * Zip WordPress theme.
 */
gulp.task('render-wp-theme-zip', ['render-wp-theme'], function() {

	return gulp.src('dist/**')
		.pipe(zip(theme + '-' + version + '.zip'))
    	.pipe(gulp.dest('dist'));

});

/**
 * Serve all distrubtion tasks.
 */
gulp.task('serve', serve, function() {
	return;
});
