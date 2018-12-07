const gulp = require('gulp');
const zip = require('gulp-zip');
const { version } = require('../../package.json');

function copyTheme() {
  return gulp
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
    .pipe(gulp.dest('../dist/jumpstart'));
}

function zipTheme() {
  return gulp
    .src(['../dist/**', '!../dist/README.md'])
    .pipe(zip(`jumpstart-${version}.zip`))
    .pipe(gulp.dest('../dist'));
}

module.exports = { copyTheme, zipTheme };
