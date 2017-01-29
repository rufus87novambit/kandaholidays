var gulp = require('gulp'),
    sass = require('gulp-ruby-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    cleancss = require('gulp-clean-css'),
    jshint = require('gulp-jshint'),
    uglify = require('gulp-uglify'),
    imagemin = require('gulp-imagemin'),
    rename = require('gulp-rename'),
    concat = require('gulp-concat'),
    notify = require('gulp-notify'),
    cache = require('gulp-cache'),
    livereload = require('gulp-livereload'),
    del = require('del');


/********************************** Front **********************************/
/**
 * Front CSS
 */
gulp.task('css-front', function () {
    gulp.src([
        '../assets/css/reset.css',
        '../assets/css/helper.css',
        '../assets/css/superslides.css',
        '../assets/css/front.css'
    ])
        .pipe(cleancss({ specialComments : 0 } ))
        .pipe(concat('front.min.css'))
        .pipe(gulp.dest('../css/'))
});

/**
 * Front JS
 */
gulp.task('js-front', function () {
    gulp.src([
        '../assets/js/plugins/easing.js',
        '../assets/js/plugins/animate-enhanced.min.js',
        '../assets/js/plugins/superslides.min.js',
        '../assets/js/front.js',
    ])
        .pipe(concat('front.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('../js/'))
});

gulp.task('css-front-watch', function() {
    gulp.watch(['../assets/css/*'], ['css-front']);
});

gulp.task('js-front-watch', function() {
    gulp.watch(['../assets/js/*'], ['js-front']);
});

gulp.task('front-watch', ['css-front-watch','js-front-watch']);


/********************************** /end Front **********************************/

/********************************** Portal **********************************/
/**
 * Front CSS
 */
gulp.task('css-portal', function () {
    gulp.src([
        '../assets/css/reset.css',
        '../assets/css/helper.css',
        '../assets/css/icon-fonts.css',
        '../assets/css/menu.css',
        '../assets/css/portal.css'
    ])
        .pipe(cleancss({ specialComments : 0 } ))
        .pipe(concat('portal.min.css'))
        .pipe(gulp.dest('../css/'))
});

gulp.task('js-portal', function () {
    gulp.src([
        '../assets/js/portal.js'
    ])
        .pipe(concat('portal.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('../js/'))
});

gulp.task('css-portal-watch', function() {
    gulp.watch(['../assets/css/*'], ['css-portal']);
});

gulp.task('js-portal-watch', function() {
    gulp.watch(['../assets/js/*'], ['js-portal']);
});

gulp.task('portal-watch', ['css-portal-watch', 'js-portal-watch']);

/********************************** /end Portal **********************************/







///**
// * Portal CSS
// */
//gulp.task('portal-css-build', function() {
//    return sass('../scss/portal/style.scss', { style: 'compressed' })
//        .pipe(rename({basename:'portal',suffix: '.min'}))
//        .pipe(gulp.dest('../css'))
//});
//gulp.task('portal-css-watch', function(){
//    gulp.watch('../scss/**/*', ['portal-css-build']);
//})
//
///**
// * Front JS
// */
//gulp.task('js-portal', function () {
//    gulp.src([
//        '../js/portal/clock.js',
//        '../js/portal/portal.js',
//    ])
//        .pipe(concat('portal.min.js'))
//        .pipe(uglify())
//        .pipe(gulp.dest('../js/portal/'))
//});
//
//gulp.task('js-watch', function() {
//    gulp.watch(['../js/*.js','!../js/zombify-scripts.min.js'], ['js-scripts']);
//});
//
//gulp.task('default', ['css-watch','js-watch']);