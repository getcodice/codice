'use strict';

var plugins = require('gulp-load-plugins')();
var gulp    = require('gulp');
var gutil   = require('gulp-util');

gulp.task('scripts', function () {
    return gulp.src([
        './bower_components/jquery/dist/jquery.min.js',
        './bower_components/bootstrap-sass/assets/javascripts/bootstrap.min.js',
        './resources/assets/scripts/codice.js',
    ]).pipe(gulp.dest('./public/assets/js'));
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

function onError(error) {
    gutil.log(gutil.colors.red('Error:'), error.toString());
    this.emit('end');
}
