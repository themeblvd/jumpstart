var gulp 	= require('gulp'),
	zip 	= require('gulp-zip'),
	clean	= require('gulp-clean');

/**
 * Theme slug.
 */
var theme = 'jumpstart';

/**
 * Theme name.
 */
var themeName = 'Jump Start';


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
 * Render WordPress theme.
 */
gulp.task('render-src', ['render-themeblvd'], function() {

	return gulp.src('src/**')
    	.pipe(gulp.dest('dist/' + theme));

});


/**
 * Zip WordPress theme.
 */
gulp.task('render-theme-zip', ['render-src'], function() {

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
