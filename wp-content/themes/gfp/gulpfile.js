var gulp          = require('gulp'),
    postcss       = require('gulp-postcss'),
    sass          = require('gulp-sass'),
    sourcemaps    = require('gulp-sourcemaps'),
    atImport      = require('postcss-import'),
    autoprefixer  = require('autoprefixer'),
    mqpacker      = require('css-mqpacker'),
    cssnano       = require('cssnano'),
    imagemin      = require('gulp-imagemin'),
    concat        = require('gulp-concat'),
    uglify        = require('gulp-uglify'),
    pump          = require('pump'),
    browserSync   = require('browser-sync'),
    clean = require('gulp-clean');

gulp.task('css', function () {
  var processors = [
    atImport,
    autoprefixer({browsers: ['last 6 versions', 'ie 9', 'ie 10', 'ie 11']}),
    mqpacker,
    cssnano
  ];
  gulp.src('./src/css/*.css')
    .pipe(gulp.dest('./dist/css'))
    .pipe(browserSync.stream());
  gulp.src('./src/scss/**/*.scss')
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(postcss(processors))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('./dist/css'))
    .pipe(browserSync.stream());
});

gulp.task('js', function () {
  gulp.src([
    'src/js/lib/atomic.min.js',
    'src/js/lib/dompurify.min.js',
    'node_modules/moment/min/moment.min.js',
    'node_modules/countdown/countdown.js',
    'node_modules/tiny-slider/dist/tiny-slider.js',
    'src/js/lib/moment-countdown.min.js',
    'src/js/modules/helpers.js',
    'src/js/modules/site-header.js',
    'src/js/modules/single-post.js',
    'src/js/modules/single-product.js',
    'src/js/modules/single-comments.js',
    'src/js/modules/ajax-add-to-cart.js',
    'src/js/modules/accordian.js',
    'src/js/modules/tooltip.js',
    'src/js/modules/modal.js',
    'src/js/modules/sign-up-form.js',
    'src/js/modules/sticky-navigation.js',
    'src/js/modules/sticky.js',
    'src/js/modules/cart.js',
    'src/js/modules/checkout.js',
    'src/js/modules/search-bar.js',
    'src/js/modules/current-promos.js',
    'src/js/modules/check-order-status.js',
    'src/js/modules/alerts.js',
    'src/js/modules/account.js',
    'src/js/modules/category.js',
  ])
    .pipe(sourcemaps.init())
    .pipe(concat('global.js'))
    .pipe(uglify())
    .pipe(sourcemaps.write())
    .pipe(gulp.dest('./dist/js'))
    .pipe(browserSync.stream());
});

// gulp.task('js-libs', function () {
//   gulp.src('node_modules/vue/dist/vue.js')
//     .pipe(gulp.dest('./dist/js'));
// });

gulp.task('img', function() {
  gulp.src('src/img/*')
      .pipe(gulp.dest('dist/img'))
});

gulp.task('watch', function() {
  gulp.watch('src/scss/**/*.scss', ['css']);
  gulp.watch('src/css/*.css', ['css']);
  gulp.watch('src/js/**/*.js', ['js']);
  gulp.watch('src/img/*.{png,jpg,gif,svg}', ['img']).on('change', browserSync.reload);
  gulp.watch(['*.php', 'page-templates/*.php',  'partials/**/*.php', 'woocommerce/**/*.php']).on('change', browserSync.reload);
});

gulp.task('browser-sync', function() {
    browserSync.init({
        proxy: "localhost:8888"
    });
});

gulp.task('default', ['css', 'js', 'img', 'watch', 'browser-sync']);