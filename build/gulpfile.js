const gulp = require('gulp');

const {
  buildFontAwesomeShim,
  buildFontAwesomeJson,
  buildFontAwesome
} = require('./tasks/fontawesome');

const {
  buildAdminScripts,
  minifyAdminScripts,
  buildAdminStyles
} = require('./tasks/admin-assets');

const buildPot = require('./tasks/build-pot');
const packageTheme = require('./tasks/package-theme');

// Build Font Awesome assets.
gulp.task('build-fontawesome-shim', buildFontAwesomeShim);
gulp.task('build-fontawesome-json', ['build-fontawesome-shim'], buildFontAwesomeJson); // prettier-ignore
gulp.task('build-fontawesome', ['build-fontawesome-json'], buildFontAwesome);

// Build admin assets.
gulp.task('build-admin-script-options', () => buildAdminScripts('options.js'));
gulp.task('build-admin-script-utils', () => buildAdminScripts('utils.js'));
gulp.task('minify-admin-scripts', minifyAdminScripts);
gulp.task('build-admin-styles', buildAdminStyles);
gulp.task('build-admin', [
  'build-admin-script-options',
  'build-admin-script-utils',
  'minify-admin-scripts',
  'build-admin-styles'
]);

// Final build.
gulp.task('build-pot', buildPot);
gulp.task('build', ['build-fontawesome', 'build-admin', 'build-pot'], packageTheme); // prettier-ignore
