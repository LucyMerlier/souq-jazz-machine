const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    // .setManifestKeyPrefix('build/')

    .copyFiles({
        from: './assets/images',

        // optional target path, relative to the output dir
        // to: 'images/[path][name].[ext]',

        // if versioning is enabled, add the file hash too
        to: 'images/[path][name].[hash:8].[ext]',

        // only copy files matching this pattern
        // pattern: /\.(png|jpg|jpeg)$/
    })

    .copyFiles([
        {
            from: './node_modules/ckeditor/', to: 'ckeditor/[path][name].[ext]', pattern: /\.(js|css)$/, includeSubdirectories: false,
        },
        { from: './node_modules/ckeditor/adapters', to: 'ckeditor/adapters/[path][name].[ext]' },
        { from: './node_modules/ckeditor/lang', to: 'ckeditor/lang/[path][name].[ext]' },
        { from: './node_modules/ckeditor/plugins', to: 'ckeditor/plugins/[path][name].[ext]' },
        { from: './node_modules/ckeditor/skins', to: 'ckeditor/skins/[path][name].[ext]' },
        { from: './node_modules/ckeditor/vendor', to: 'ckeditor/vendor/[path][name].[ext]' },
    ])

    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/app.js')

    .addEntry('concert', './assets/scripts/concert.js')

    .addEntry('user', './assets/scripts/user.js')

    .addEntry('partner', './assets/scripts/partner.js')

    .addEntry('song', './assets/scripts/song.js')

    .addStyleEntry('admin', './assets/styles/admin.scss')

    .addStyleEntry('showcase', './assets/styles/showcase.scss')

    .addStyleEntry('error', './assets/styles/error.scss')

    // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
    .enableStimulusBridge('./assets/controllers.json')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
    })

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    // enables Sass/SCSS support
    .enableSassLoader();

module.exports = Encore.getWebpackConfig();
