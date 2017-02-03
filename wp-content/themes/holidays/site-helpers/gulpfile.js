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
 * Build front css
 */
gulp.task('css-front', function() {
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
 * Watch for front css changes
 */
gulp.task('css-front-watch', function() {
    gulp.watch(['../assets/css/*'], ['css-front']);
});

/**
 * Build front js
 */
gulp.task('js-front', function() {
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

/**
 * Watch for front js changes
 */
gulp.task('js-front-watch', function() {
    gulp.watch(['../assets/js/*'], ['js-front']);
});

/**
 * Watch for front assets ( css & js ) changes
 */
gulp.task('front-watch', ['css-front-watch','js-front-watch']);

/**
 * Build front assets ( css & js )
 */
gulp.task('front-build', ['css-front','js-front']);

/********************************** /end Front **********************************/



/********************************** Portal **********************************/
/**
 * Build portal css
 */
gulp.task('css-portal', function () {
    gulp.src([
        '../assets/css/reset.css',
        '../assets/css/helper.css',
        '../assets/css/icon-fonts.css',
        '../assets/css/grid.css',
        '../assets/css/menu.css',
        '../assets/css/portal.css'
    ])
        .pipe(cleancss({ specialComments : 0 } ))
        .pipe(concat('portal.min.css'))
        .pipe(gulp.dest('../css/'))
});

/**
 * Watch for portal css changes
 */
gulp.task('css-portal-watch', function() {
    gulp.watch(['../assets/css/*'], ['css-portal']);
});

/**
 * Build portal js
 */
gulp.task('js-portal', function () {
    gulp.src([
        '../assets/js/portal.js'
    ])
        .pipe(concat('portal.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('../js/'))
});

/**
 * Watch for portal js changes
 */
gulp.task('js-portal-watch', function() {
    gulp.watch(['../assets/js/*'], ['js-portal']);
});

/**
 * Watch for portal assets ( css & js ) changes
 */
gulp.task('portal-watch', ['css-portal-watch', 'js-portal-watch']);

/**
 * Build portal assets ( css & js )
 */
gulp.task('portal-build', ['css-portal','js-portal']);
/********************************** /end Portal **********************************/

/********************************** Admin **********************************/

/**
 * Build admin css
 */
gulp.task('css-admin', function () {
    gulp.src([
        '../assets/css/icon-fonts.css',
        '../assets/css/admin/admin.css'
    ])
        .pipe(cleancss({ specialComments : 0 } ))
        .pipe(concat('admin.min.css'))
        .pipe(gulp.dest('../css/'))
});

/**
 * Watch for admin css changes
 */
gulp.task('css-admin-watch', function() {
    gulp.watch(['../assets/css/*', '../assets/css/admin/*'], ['css-admin']);
});

/**
 * Build admin js
 */
gulp.task('js-admin', function () {
    gulp.src([
        '../assets/js/admin/admin.js'
    ])
        .pipe(concat('admin.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('../js/'))
});

/**
 * Watch for admin js changes
 */
gulp.task('js-admin-watch', function() {
    gulp.watch(['../assets/js/*', '../assets/js/admin/*'], ['js-admin']);
});

/**
 * Watch for admin assets ( css & js ) changes
 */
gulp.task('admin-watch', ['css-admin-watch', 'js-admin-watch']);

/**
 * Build portal assets ( css & js )
 */
gulp.task('admin-build', ['css-admin','js-admin']);
/********************************** Admin **********************************/

/**
 * Build everything
 */
gulp.task('build', ['front-build', 'portal-build', 'admin-build']);