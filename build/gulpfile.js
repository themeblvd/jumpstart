const { task, series, parallel } = require('gulp');
const { clean } = require('./tasks/clean');
const { buildPot } = require('./tasks/build-pot');
const { copyTheme, zipTheme } = require('./tasks/build-theme');

const {
  buildFontAwesomeShim,
  buildFontAwesomeJson,
  buildFontAwesomeJs
} = require('./tasks/fontawesome');

const {
  buildAdminScripts,
  minifyAdminScripts,
  buildAdminStyles
} = require('./tasks/admin-assets');

// Build Font Awesome assets.
task('build-fontawesome-shim', buildFontAwesomeShim);
task('build-fontawesome-json', buildFontAwesomeJson);
task('build-fontawesome-js', buildFontAwesomeJs);

task(
  'build-fontawesome',
  series(
    'build-fontawesome-shim',
    'build-fontawesome-json',
    'build-fontawesome-js'
  )
);

// Build admin assets.
task('build-admin-script-options', () => buildAdminScripts('options.js'));
task('build-admin-script-utils', () => buildAdminScripts('utils.js'));
task('minify-admin-scripts', minifyAdminScripts);
task('build-admin-styles', buildAdminStyles);

task(
  'build-admin',
  parallel(
    'build-admin-script-options',
    'build-admin-script-utils',
    'minify-admin-scripts',
    'build-admin-styles'
  )
);

// Final build.
task('clean', clean);
task('build-pot', buildPot);
task('copy-theme', copyTheme);
task('zip-theme', zipTheme);

task(
  'build',
  series(
    'clean',
    parallel('build-fontawesome', 'build-admin', 'build-pot'),
    'copy-theme',
    'zip-theme'
  )
);
