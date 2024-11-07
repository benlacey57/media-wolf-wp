// gulpfile.js

const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const cleanCSS = require('gulp-clean-css');
const rename = require('gulp-rename');
const path = require('path');

// Paths
const scssFiles = 'assets/scss/**/*.scss';
const cssOutput = 'assets/css/';

// Task to compile SCSS files
function compileSass() {
    return gulp.src(scssFiles)
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest(cssOutput));
}

// Minify only non-minified CSS files
function minifyCss() {
    return gulp.src([path.join(cssOutput, '*.css'), '!' + path.join(cssOutput, '*.min.css')]) // Exclude .min.css files
        .pipe(cleanCSS())
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest(cssOutput));
}

// Watch task
function watchFiles() {
    gulp.watch(scssFiles, gulp.series(compileSass, minifyCss));
}

// Default task
exports.default = gulp.series(compileSass, minifyCss, watchFiles);
