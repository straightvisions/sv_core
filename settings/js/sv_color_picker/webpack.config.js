const path = require('path');
const HtmlWebpackPlugin = require('html-webpack-plugin');

module.exports = {
    entry: './src/index.js',
    output: {
        path: path.join(__dirname, '/sv_color_picker_min'),
        filename: 'sv_color_picker.min.js',
    },
    externals: {
        'react': 'React',
        'react-dom': 'ReactDOM',
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader'
                }
            }
        ]
    },
    /* Remove comment, when using npm start for local development
    plugins: [
        new HtmlWebpackPlugin({
            template: './src/index.html'
        })
    ]
    */
}