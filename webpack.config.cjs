const path = require('path');
const CircularDependencyPlugin = require('circular-dependency-plugin');
const DuplicatePackageCheckerPlugin = require('duplicate-package-checker-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');

const THEME_ASSETS = path.resolve(__dirname, 'themes/zotico-2025/assets');

module.exports = {
  context: THEME_ASSETS,
  mode: 'development',
  entry: {
    main: './js/js-modules/main.js'
  },
  devtool: 'source-map',
  output: {
    filename: '[name].min.js',
    path: path.resolve(THEME_ASSETS, 'js'),
  },
  optimization: {
    minimize: true,
    minimizer: [
      new TerserPlugin({
        extractComments: false,
      })
    ]
  },
  module: {
    rules: [{
      test: /\.js$/,
      exclude: /node_modules/,
      loader: 'babel-loader',
      options: {
        presets: ['@babel/preset-env']
      }
    }]
  },
  plugins: [new DuplicatePackageCheckerPlugin(), new CircularDependencyPlugin()]
};
