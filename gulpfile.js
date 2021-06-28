// import des packages
var gulp = require('gulp');
var sass = require('gulp-sass');
var browserSync = require('browser-sync').create();

// tâche : sass ; src : source ; dest : destination ; 
gulp.task('sass', function() {
    return gulp.src('./sass/main.sass')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('./css'))
        .pipe(browserSync.stream())
})

// tâche : serve ; demarre le serveur  
gulp.task('serve', ['sass'], function() {

    browserSync.init({
        server: "./"
    });

    /* serveille les modifications sur main.sass et 
    les fichiers html et rafraîchit le navigateur */
    gulp.watch("./sass/main.sass", ['sass']);
    gulp.watch("./*.html").on('change', browserSync.reload);

});

gulp.task('default', ['serve']);