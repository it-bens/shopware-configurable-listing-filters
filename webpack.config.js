// Diese Konfiguration dient nur als Hilfe für PHPStorm
// Sie wird nicht für das tatsächliche Webpack-Build verwendet
const path = require('path');

module.exports = {
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'src/Resources/app/administration/src'),
      'Shopware': path.resolve(__dirname, 'vendor/shopware/administration/Resources/app/administration/src'),
      'vue$': path.resolve(__dirname, 'node_modules/vue/dist/vue.esm.js'),
      'module': path.resolve(__dirname, 'vendor/shopware/administration/Resources/app/administration/src/module'),
      'scss': path.resolve(__dirname, 'vendor/shopware/administration/Resources/app/administration/src/app/assets/scss'),
      'assets': path.resolve(__dirname, 'vendor/shopware/administration/Resources/app/administration/static')
    },
    extensions: ['.js', '.vue', '.json', '.ts', '.tsx']
  }
};
