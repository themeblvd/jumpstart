const gulp = require('gulp');
const rename = require('gulp-rename');
const concat = require('gulp-concat');
const minifyjs = require('gulp-uglify');
const sass = require('gulp-sass');
const minifycss = require('gulp-clean-css');
const autoprefixer = require('gulp-autoprefixer');
const browserslist = require('../browserslist');
const dir = '../framework/admin/assets';

/**
 * Build admin scripts from partial files.
 *
 * @TODO In the future, this will be part of
 * a Webpack build.
 */
function buildAdminScripts($filename) {
  const partials = {
    'options.js': [
      dir + '/js/src/options/options-init.js',
      dir + '/js/src/options/options-setup.js',
      dir + '/js/src/options/options-bind.js',
      dir + '/js/src/options/options-media-uploader.js',
      dir + '/js/src/options/options-editor.js',
      dir + '/js/src/options/options-code-editor.js',
      dir + '/js/src/options/options-column-widths.js',
      dir + '/js/src/options/options-sortable.js',
      dir + '/js/src/options/options-browsers.js'
    ],
    'utils.js': [
      dir + '/js/src/utils/utils-init.js',
      dir + '/js/src/utils/utils-tools.js',
      dir + '/js/src/utils/utils-confirm.js',
      dir + '/js/src/utils/utils-jquery.js',
      dir + '/js/src/utils/utils-modal.js',
      dir + '/js/src/utils/utils-setup.js',
      dir + '/js/src/utils/utils-accordion.js',
      dir + '/js/src/utils/utils-widgets.js'
    ]
  };

  return gulp
    .src(partials[$filename])
    .pipe(concat($filename))
    .pipe(gulp.dest(dir + '/js'))
    .pipe(minifyjs({ output: { comments: /^!|@license/i } }))
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest(dir + '/js'));
}

/**
 * Minify admin scripts.
 *
 * For now, these are scripts already ready for
 * the browser that just need to be minified.
 *
 * @TODO In the future, this will be part of
 * a Webpack build.
 */
function minifyAdminScripts() {
  const files = [
    dir + '/js/src/base.js',
    dir + '/js/src/menu.js',
    dir + '/js/src/meta-box.js',
    dir + '/js/src/options-page.js'
  ];

  return gulp
    .src(files)
    .pipe(gulp.dest(dir + '/js'))
    .pipe(minifyjs())
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest(dir + '/js'));
}

function buildAdminStyles() {
  const files = [
    dir + '/scss/base.scss',
    dir + '/scss/global.scss',
    dir + '/scss/menu.scss',
    dir + '/scss/options-page.scss',
    dir + '/scss/options.scss',
    dir + '/scss/utils.scss'
  ];

  return gulp
    .src(files)
    .pipe(sass({ outputStyle: 'expanded' }).on('error', sass.logError))
    .pipe(
      autoprefixer({
        browsers: browserslist,
        cascade: false
      })
    )
    .pipe(gulp.dest(dir + '/css'))
    .pipe(minifycss())
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest(dir + '/css'));
}

module.exports = {
  buildAdminScripts,
  minifyAdminScripts,
  buildAdminStyles
};
