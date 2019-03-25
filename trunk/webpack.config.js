const externals = {
    lodash: 'lodash',
};

module.exports = {
    // Entry.
    entry: "./assets/js/block.js", // Import all JavaScript dependencies in this file.

    // Output.
    output: {
        path: __dirname + '/assets/js/', // Path to produce the output. Set to the current directory.
        filename: "block.build.js" // Filename of the file that webpack builds.
    },

    externals,

    // Loaders.
    // The configuration below has defined a rules property for a single module with
    // two required properties: test and use. This tells webpack's compiler the following:
    // "Hey webpack compiler, when you come across a '.js' or '.jsx' file inside of a
    // require()/import statement, use the babel-loader to transform it before you add
    // it to the bundle."
    module: {
        rules: [
            {
                test: /\.jsx?$/,
                exclude: /node_modules/,
                loader: 'babel-loader',
            },
        ]
    }
};