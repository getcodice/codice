'use strict';

var plugins = require('gulp-load-plugins')();
var gulp    = require('gulp');
var gutil   = require('gulp-util');
var concat  = require('gulp-concat-util');

gulp.task('scripts', function () {
    // Copy JS locales
    gulp.src('./resources/assets/scripts/locales/*.js')
    .pipe(gulp.dest('./public/assets/js/locales'));

    return gulp.src([
        './node_modules/jquery/dist/jquery.min.js',
        './node_modules/bootstrap-sass/assets/javascripts/bootstrap.min.js',
        './node_modules/select2/dist/js/select2.min.js',
        './node_modules/jscroll/jquery.jscroll.min.js',
        './node_modules/flatpickr/dist/flatpickr.min.js',
        './node_modules/bootbox/bootbox.min.js',
        './resources/assets/scripts/codice.js',
    ]).pipe(concat('codice.js', { separator: ';' }))
      .pipe(gulp.dest('./public/assets/js'));
});

gulp.task('styles', function () {
    return gulp.src('./resources/assets/styles/codice.scss')
        .pipe(plugins.sass({
            includePaths: [
                './node_modules/bootstrap-sass/assets/stylesheets/',
            ],
            outputStyle: 'compressed'
        }))
        .on('error', onError)
        .pipe(plugins.csso())
        .pipe(gulp.dest('./public/assets/css'));
});

gulp.task('icons', function() {
    return gulp.src('./node_modules/font-awesome/fonts/**.*')
        .pipe(gulp.dest('./public/assets/fonts'));
});

gulp.task('assets', ['scripts', 'styles', 'icons']);

function onError(error) {
    gutil.log(gutil.colors.red('Error:'), error.toString());
    this.emit('end');
}
