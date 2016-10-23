'use strict';

var gulp    = require('gulp');
var gutil   = require('gulp-util');
var plugins = {
    concat: require('gulp-concat'),
    csso: require('gulp-csso'),
    sass: require('gulp-sass'),
};

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

        './resources/assets/scripts/helpers.js',
        './resources/assets/scripts/keyboard.js',
        './resources/assets/scripts/quickform.js',
        './resources/assets/scripts/ajax-note.js',
        './resources/assets/scripts/main.js',
    ]).pipe(plugins.concat('codice.js'))
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
