const gulp = require('gulp');
const remoteSrc = require('gulp-remote-src');
const concat = require('gulp-concat');
const replace = require('gulp-replace');
const minifyjs = require('gulp-uglify');
const rename = require('gulp-rename');
const { dependencies } = require('../../package.json');

/**
 * Merge FontAwesome v4 shim into theme.
 */
function buildFontAwesomeShim() {
  const files = [
    '../node_modules/@fortawesome/fontawesome-free/js/v4-shims.js',
    '../node_modules/@fortawesome/fontawesome-free/js/v4-shims.min.js'
  ];

  return gulp.src(files).pipe(gulp.dest('../framework/assets/js/'));
}

/**
 * Merge FontAwesome json.
 */
function buildFontAwesomeJson() {
  const faVersion = dependencies['@fortawesome/fontawesome-free'].replace('^', '');

  return remoteSrc(['icons.json'], {
    base: `https://raw.githubusercontent.com/FortAwesome/Font-Awesome/${faVersion}/advanced-options/metadata/`
  }).pipe(gulp.dest('../framework/admin/assets/data'));
}

/**
 * Build a custom FontAwesome JavaScript file.
 */
function buildFontAwesome() {
  const dir = '../framework/assets/js';

  const src = [
    '../node_modules/@fortawesome/fontawesome-free/js/brands.js',
    '../node_modules/@fortawesome/fontawesome-free/js/solid.js',
    '../node_modules/@fortawesome/fontawesome-free/js/fontawesome.js'
  ];

  const find_icons = [
    // Original icons from fa-solid.js
    // angle-down
    'M143 352.3L7 216.3c-9.4-9.4-9.4-24.6 0-33.9l22.6-22.6c9.4-9.4 24.6-9.4 33.9 0l96.4 96.4 96.4-96.4c9.4-9.4 24.6-9.4 33.9 0l22.6 22.6c9.4 9.4 9.4 24.6 0 33.9l-136 136c-9.2 9.4-24.4 9.4-33.8 0z',
    // angle-left
    'M31.7 239l136-136c9.4-9.4 24.6-9.4 33.9 0l22.6 22.6c9.4 9.4 9.4 24.6 0 33.9L127.9 256l96.4 96.4c9.4 9.4 9.4 24.6 0 33.9L201.7 409c-9.4 9.4-24.6 9.4-33.9 0l-136-136c-9.5-9.4-9.5-24.6-.1-34z',
    // angle-right
    'M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z',
    // angle-up
    'M177 159.7l136 136c9.4 9.4 9.4 24.6 0 33.9l-22.6 22.6c-9.4 9.4-24.6 9.4-33.9 0L160 255.9l-96.4 96.4c-9.4 9.4-24.6 9.4-33.9 0L7 329.7c-9.4-9.4-9.4-24.6 0-33.9l136-136c9.4-9.5 24.6-9.5 34-.1z'
  ];

  const replace_icons = [
    // Same icons, but taken from pro's fa-regular.js
    // angle-down
    'M151.5 347.8L3.5 201c-4.7-4.7-4.7-12.3 0-17l19.8-19.8c4.7-4.7 12.3-4.7 17 0L160 282.7l119.7-118.5c4.7-4.7 12.3-4.7 17 0l19.8 19.8c4.7 4.7 4.7 12.3 0 17l-148 146.8c-4.7 4.7-12.3 4.7-17 0z',
    // angle-left
    'M4.2 247.5L151 99.5c4.7-4.7 12.3-4.7 17 0l19.8 19.8c4.7 4.7 4.7 12.3 0 17L69.3 256l118.5 119.7c4.7 4.7 4.7 12.3 0 17L168 412.5c-4.7 4.7-12.3 4.7-17 0L4.2 264.5c-4.7-4.7-4.7-12.3 0-17z',
    // angle-right
    'M187.8 264.5L41 412.5c-4.7 4.7-12.3 4.7-17 0L4.2 392.7c-4.7-4.7-4.7-12.3 0-17L122.7 256 4.2 136.3c-4.7-4.7-4.7-12.3 0-17L24 99.5c4.7-4.7 12.3-4.7 17 0l146.8 148c4.7 4.7 4.7 12.3 0 17z',
    // angle-up
    'M168.5 164.2l148 146.8c4.7 4.7 4.7 12.3 0 17l-19.8 19.8c-4.7 4.7-12.3 4.7-17 0L160 229.3 40.3 347.8c-4.7 4.7-12.3 4.7-17 0L3.5 328c-4.7-4.7-4.7-12.3 0-17l148-146.8c4.7-4.7 12.3-4.7 17 0z'
  ];

  return gulp
    .src(src)
    .pipe(concat('themeblvd-fontawesome.js'))
    .pipe(replace(find_icons[0], replace_icons[0]))
    .pipe(replace(find_icons[1], replace_icons[1]))
    .pipe(replace(find_icons[2], replace_icons[2]))
    .pipe(replace(find_icons[3], replace_icons[3]))
    .pipe(gulp.dest(dir))
    .pipe(minifyjs({ output: { comments: /^!|@license/i } }))
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest(dir));
}

module.exports = {
  buildFontAwesomeShim,
  buildFontAwesomeJson,
  buildFontAwesome
};
