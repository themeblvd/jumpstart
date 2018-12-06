const gulp = require('gulp');
const zip = require('gulp-zip');
// const { version } = require('../../package.json');

function packageTheme() {
  gulp
    .src([
      '../**/*',
      '!../**/README.md',
      '!../{dist,dist/**/*}',
      '!../{node_modules,node_modules/**/*}',
      '!../{build,build/**/*}',
      '!../framework/admin/assets/js/{src,src/**/*}',
      '!../framework/admin/assets/{scss,scss/**/*}',
      '!../.editorconfig',
      '!../.gitignore',
      '!../*.json'
    ])
    .pipe(zip('jumpstart.zip'))
    .pipe(gulp.dest('../dist/'));
}

module.exports = packageTheme;
