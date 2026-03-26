import gulp from 'gulp';
import plumber from 'gulp-plumber';
import notify from 'gulp-notify';
import gulpSass from 'gulp-sass';
import * as sass from 'sass';
import postcss from 'gulp-postcss';
import autoprefixer from 'autoprefixer';
import cssnano from 'cssnano';
import postcssUrl from 'postcss-url';
import rename from 'gulp-rename';
import sourcemaps from 'gulp-sourcemaps';
import svgSprite from 'gulp-svg-sprite';
import webpack from 'webpack';
import webpackStream from 'webpack-stream';
import webpackConfig from './webpack.config.cjs';
import browserSync from 'browser-sync';

const pathDir = './themes/zotico-2025/assets'
const sassCompiler = gulpSass(sass);

export function styles() { // Убираем параметр browserSync
  const postcssPlugins = [autoprefixer(), postcssUrl({
    assetsPath: '../../',
    url: 'rebase'
  }), cssnano({ preset: 'default' })];

  return gulp
    .src([`${pathDir}/css/scss/styles.scss`], { sourcemaps: true })
    .pipe(plumber({ errorHandler: notify.onError('Styles Error: <%= error.message %>') }))
    .pipe(sourcemaps.init())
    .pipe(sassCompiler.sync({
      outputStyle: 'expanded',
      includePaths: [`${pathDir}/css/scss`],
      sourceMap: true
    }).on('error', sassCompiler.logError))
    .pipe(postcss(postcssPlugins))
    .pipe(rename({ suffix: '.min' }))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(`${pathDir}/css`))
}

export function scripts() {
  return gulp
    .src(`${pathDir}/js/js-modules/main.js`)
    .pipe(plumber({
      errorHandler: notify.onError({
        title: 'Scripts Error',
        message: '<%= error.message %>',
        sound: false
      })
    }))
    .pipe(webpackStream(webpackConfig, webpack))
    .pipe(gulp.dest(`${pathDir}/js`))
}

export function createSprite(cb) {
  return gulp
    .src(`${pathDir}/images/sprite/**/*.svg`, { encoding: false })
    .pipe(plumber({ errorHandler: notify.onError('Sprite Error: <%= error.message %>') }))
    .pipe(svgSprite({
      mode: {
        stack: {
          dest: '.',
          sprite: 'sprite.svg'
        }
      }
    }))
    .pipe(gulp.dest(`${pathDir}/images`))
    .on('end', cb)
}

const server = browserSync.create();

const syncServer = () => {
  gulp.watch(`${pathDir}/css/scss/**/*.{scss,sass}`, gulp.series(styles));
  gulp.watch(`${pathDir}/js/js-modules/**/*.{js,json}`, gulp.series(scripts));
  gulp.watch(`${pathDir}/images/sprite/*.svg`, gulp.series(createSprite));
};

export const build = gulp.parallel(
  styles, scripts, createSprite
);

export const dev = gulp.series(build, syncServer);

export default dev;
