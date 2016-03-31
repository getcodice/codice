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
        './bower_components/jquery/dist/jquery.min.js',
        './bower_components/bootstrap-sass/assets/javascripts/bootstrap.min.js',
        './bower_components/select2/dist/js/select2.min.js',
        './bower_components/jscroll/jquery.jscroll.min.js',
        './bower_components/moment/min/moment.min.js',
        './bower_components/moment/locale/pl.js',
        './bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
        './resources/assets/scripts/codice.js',
    ]).pipe(concat('codice.js', { separator: ';' }))
      .pipe(gulp.dest('./public/assets/js'));
});

gulp.task('styles', function () {
    return gulp.src('./resources/assets/styles/codice.scss')
        .pipe(plugins.sass({
            includePaths: [
                './bower_components/bootstrap-sass/assets/stylesheets/',
            ],
            outputStyle: 'compressed'
        }))
        .on('error', onError)
        .pipe(plugins.csso())
        .pipe(gulp.dest('./public/assets/css'));
});

gulp.task('icons', function() {
    return gulp.src('./bower_components/font-awesome/fonts/**.*')
        .pipe(gulp.dest('./public/assets/fonts'));
});

gulp.task('assets', ['scripts', 'styles', 'icons']);

function onError(error) {
    gutil.log(gutil.colors.red('Error:'), error.toString());
    this.emit('end');
}
