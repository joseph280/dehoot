const path = require('path');

module.exports = {
  resolve: {
    alias: {
      '@': path.resolve('resources/js'),
      '@components': path.resolve('resources/js/components'),
    },
  },
  output: {
    chunkFilename: 'js/[name].js?id=[chunkhash]',
  },
};
