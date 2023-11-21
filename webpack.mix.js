const mix = require('laravel-mix');
const webpackConfig = require('./webpack.config');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

const url = process.env.APP_URL
  ? process.env.APP_URL.replace(/(^\w+:|^)\/\//, '')
  : 'localhost';

mix
  .ts('resources/js/App.tsx', 'public/js')
  .react()
  .postCss('resources/css/app.css', 'public/css', [require('tailwindcss')])
  .version()
  .webpackConfig(webpackConfig);

mix.options({
  hmrOptions: {
    host: url,
    port: 8080,
  },
});

mix.copy('resources/assets', 'public/assets').version();
