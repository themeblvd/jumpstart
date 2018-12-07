const del = require('del');

function clean() {
  return del(['../dist/jumpstart', '../dist/*.zip'], { force: true });
}

module.exports = { clean };
