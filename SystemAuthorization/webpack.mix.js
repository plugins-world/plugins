const dotenvExpand = require('dotenv-expand');
dotenvExpand(require('dotenv').config({ path: '../../.env'/*, debug: true*/}));

const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.setPublicPath('../../public').mergeManifest();

mix.js(__dirname + '/resources/assets/js/app.js', 'assets/plugins/SystemAuthorization/js/system-authorization.js')
    .sass( __dirname + '/resources/assets/sass/app.scss', 'assets/plugins/SystemAuthorization/css/system-authorization.css');

if (mix.inProduction()) {
    mix.version();
}
