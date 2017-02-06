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
 * Build back css
 */
gulp.task('css-back', function () {
    gulp.src([
        '../icon-fonts/style.css',
        '../assets/css/reset.css',
        '../assets/css/grid.css',
        '../assets/css/components.css',
        '../assets/css/editor.css',
        '../assets/css/menu.css',
        '../assets/css/form.css',
        '../assets/css/back-base.css',
        '../assets/css/back.css',
        '../assets/css/responsive.css'
    ])
        .pipe(cleancss({ specialComments : 0 } ))
        .pipe(concat('back.min.css'))
        .pipe(gulp.dest('../css/'))
});

/**
 * Watch for back css changes
 */
gulp.task('css-back-watch', function() {
    gulp.watch(['../assets/css/*'], ['css-back']);
});

/**
 * Build back js
 */
gulp.task('js-back', function () {
    gulp.src([
        '../assets/js/plugins/jquery.customSelect.min.js',
        '../assets/js/back.js'
    ])
        .pipe(concat('back.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('../js/'))
});

/**
 * Watch for back js changes
 */
gulp.task('js-back-watch', function() {
    gulp.watch(['../assets/js/*'], ['js-back']);
});

/**
 * Watch for back assets ( css & js ) changes
 */
gulp.task('back-watch', ['css-back-watch', 'js-back-watch']);

/**
 * Build back assets ( css & js )
 */
gulp.task('back-build', ['css-back','js-back']);
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
 * Build back assets ( css & js )
 */
gulp.task('admin-build', ['css-admin','js-admin']);
/********************************** Admin **********************************/

/**
 * Build everything
 */
gulp.task('build', ['front-build', 'back-build', 'admin-build']);