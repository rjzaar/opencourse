var gulp        = require('gulp'),
    browserSync = require('browser-sync'),
    // sass        = require('gulp-sass'),
    // prefix      = require('gulp-autoprefixer'),
    concat      = require('gulp-concat'),
    babel       = require('gulp-babel'),
    cp          = require('child_process');
    less        = require('gulp-less');
    path        = require('path');
var watch      = require('gulp-watch');
var livereload = require('gulp-livereload');
var sourcemaps = require('gulp-sourcemaps');
/**
 * Launch the Server
 */
gulp.task('browser-sync', ['scripts'], function() {
    browserSync.init({
        // Change as required, also remember to set in theme settings
        proxy: "o.c/user/43",
        port: 3000
    });
});

/**
 * @task less
 * Compile files from less
 */
// gulp.task('less', function () {
//     return gulp.src('./less/**/*.less')
//         .pipe(less({
//             paths: [ path.join(__dirname, 'less', 'includes') ]
//         }))
//         .pipe(gulp.dest('./public/css'));
// });
gulp.task('less', function () {
    gulp.src('./less/**/*.less')
        .pipe(sourcemaps.init())
        .pipe(less())
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('css'))
        .pipe(livereload());
});

/**
 * @task scripts
 * Compile files from js
 */
gulp.task('scripts', function() {
    return gulp.src(['_js/*.js', '_js/custom.js'])
        .pipe(babel({
            presets: ['es2015']
        }))
        .pipe(concat('scripts.js'))
        .pipe(gulp.dest('js'))
        .pipe(browserSync.reload({stream:true}))
});

/**
 * @task clearcache
 * Clear all caches
 */
gulp.task('clearcache', function(done) {
    return cp.spawn('drush', ['cache-rebuild'], {stdio: 'inherit'})
        .on('close', done);
});

/**
 * @task reload
 * Refresh the page after clearing cache
 */
gulp.task('reload', function () {
    browserSync.reload();
});
// gulp.task('reload', ['clearcache'], function () {
//     browserSync.reload();
// });

/**
 * @task watch
 * Watch scss files for changes & recompile
 * Clear cache when Drupal related files are changed
 */
gulp.task('watch', function () {
    livereload.listen();
    gulp.watch(['_js/*.js'], ['scripts']);
    gulp.watch(['templates/*.html.twig', '**/*.yml'], ['reload']);
    gulp.watch(['templates/**/*.twig', '**/*.yml'], ['reload']);
    gulp.watch(['css/**/*.css', '**/*.css'], ['reload']);
    gulp.watch(['less/**/*.less', '**/*.less'], ['less']);
});

/**
 * Default task, running just `gulp` will
 * compile Sass files, launch BrowserSync, watch files.
 */
gulp.task('default', ['browser-sync', 'watch']);