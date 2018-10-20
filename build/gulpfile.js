const gulp = require('gulp');

const {
  buildFontAwesomeShim,
  buildFontAwesomeJson,
  buildFontAwesome
} = require('./tasks/fontawesome');

// Build Font Awesome assets.
gulp.task('build-fontawesome-shim', buildFontAwesomeShim);
gulp.task('build-fontawesome-json', ['build-fontawesome-shim'], buildFontAwesomeShim); // prettier-ignore
gulp.task('build-fontawesome', ['build-fontawesome-json'], buildFontAwesome);
