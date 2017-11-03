var Encore = require('@symfony/webpack-encore');

var devPath = '/symfony/snowtricks/snowtricks/web/build';
var prodPath = '/web/build';
var path = Encore.isProduction() ? prodPath : devPath;

Encore
    // directory where all compiled assets will be stored
    .setOutputPath('web/build/')

    // what's the public path to this directory (relative to your project's document root dir)
    .setPublicPath(path)

    // this is now needed so that you manifest.json keys are still 'build/foo.js' // i.e. you want need to change anything in your Symfony app
    .setManifestKeyPrefix('build')

    // empty the outputPath dir before each build
    .cleanupOutputBeforeBuild()

    // will output as web/build/app.js
    .addEntry('app', './app/Resources/assets/js/main.js')
    .addEntry('trick_form', './app/Resources/assets/js/trick.form.js')
    .addEntry('files', './app/Resources/assets/js/drag.files.management.js')
    .addEntry('trick', './app/Resources/assets/js/trick.js')
    .addEntry('users', './app/Resources/assets/js/users.js')

    // will output as web/build/global.css
    .addStyleEntry('global', './app/Resources/assets/css/global.scss')
    .addStyleEntry('trick_style', './app/Resources/assets/css/trick.scss')

    // allow sass/scss files to be processed
    .enableSassLoader()

    // allow legacy applications to use $/jQuery as a global variable    
    .autoProvidejQuery()

    .enableSourceMaps(!Encore.isProduction())

    // create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())
    .enableVersioning()
;

// export the final configuration
module.exports = Encore.getWebpackConfig();