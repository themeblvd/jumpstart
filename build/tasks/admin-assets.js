const gulp = require('gulp');
const rename = require('gulp-rename');
const concat = require('gulp-concat');
const minifyjs = require('gulp-uglify');
const sass = require('gulp-sass');
const minifycss = require('gulp-clean-css');
const autoprefixer = require('gulp-autoprefixer');
const browsers = require('../browsers');
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
      dir + '/js/options/options-init.js',
      dir + '/js/options/options-setup.js',
      dir + '/js/options/options-bind.js',
      dir + '/js/options/options-media-uploader.js',
      dir + '/js/options/options-editor.js',
      dir + '/js/options/options-code-editor.js',
      dir + '/js/options/options-column-widths.js',
      dir + '/js/options/options-sortable.js',
      dir + '/js/options/options-browsers.js'
    ],
    'utils.js': [
      dir + '/js/utils/utils-init.js',
      dir + '/js/utils/utils-tools.js',
      dir + '/js/utils/utils-confirm.js',
      dir + '/js/utils/utils-jquery.js',
      dir + '/js/utils/utils-modal.js',
      dir + '/js/utils/utils-setup.js',
      dir + '/js/utils/utils-accordion.js',
      dir + '/js/utils/utils-widgets.js'
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
    dir + '/js/base.js',
    dir + '/js/menu.js',
    dir + '/js/meta-box.js',
    dir + '/js/options-page.js'
  ];

  return gulp
    .src(files)
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
        browsers: browsers,
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
