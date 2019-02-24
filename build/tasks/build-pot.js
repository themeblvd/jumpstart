const gulp = require('gulp');
const wpPot = require('gulp-wp-pot');
const { version } = require('../../package.json');

/**
 * Generate .pot localization file.
 */
function buildPot() {
  return gulp
    .src('./**/*.php')
    .pipe(
      wpPot({
        domain: 'jumpstart',
        package: `Jump Start ${version}`,
        relativeTo: '../languages'
      })
    )
    .pipe(gulp.dest('./languages/jumpstart.pot'));
}

module.exports = { buildPot };
