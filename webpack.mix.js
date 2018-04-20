let mix = require('laravel-mix');

const CleanWebpackPlugin = require('clean-webpack-plugin');
const JavaScriptObfuscator = require('webpack-obfuscator');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');

let pathsToClean = [
    'public/js',
	'public/css',
	'public/fonts',
];

let cleanOptions = {
    verbose:  true,
    dry:      false
};

if (mix.inProduction()) {
    // mix.version();

    mix.webpackConfig({
        output: {
            publicPath: '/ITAM',
            // publicPath: '',
        },
        plugins: [
            new CleanWebpackPlugin(pathsToClean, cleanOptions),
            new JavaScriptObfuscator ({
                    rotateUnicodeArray: true
            }, ['excluded_bundle_name.js']),
            new UglifyJsPlugin({
                uglifyOptions: {
                    output: {
                        comments: false
                    }
                }
            })
        ]
    });

} else {

    // mix.webpackConfig({
    //     output: {
    //         publicPath: '/',
    //     },
    //     plugins: [
    //         new CleanWebpackPlugin(pathsToClean, cleanOptions),
    //         new UglifyJsPlugin({
    //             uglifyOptions: {
    //                 output: {
    //                     comments: false
    //                 }
    //             }
    //         })
    //     ]
    // });

}

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.sass', 'public/css');

mix.copy('node_modules/font-awesome/fonts', 'public/fonts/font-awesome');
