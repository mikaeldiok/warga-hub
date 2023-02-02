const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js').postCss('resources/css/app.css', 'public/css', [
    require('postcss-import'),
    require('tailwindcss'),
]);

/**
 *
 * Copy Assets
 *
 * -----------------------------------------------------------------------------
 */
// jquery and icon fonts
mix.copy("node_modules/jquery/dist/jquery.min.js", "public/js/jquery.min.js")
    .copy("node_modules/@fortawesome/fontawesome-free/webfonts/*", "public/webfonts")
    .copy('node_modules/@coreui/icons/fonts', 'public/fonts');

/**
 *
 * Backend
 *
 * -----------------------------------------------------------------------------
 */
// Build Backend SASS
mix.sass("resources/sass/backend.scss", "public/css/backend-theme.css");

// Backend CSS
mix.styles(
    [
        "public/css/backend-theme.css",
        "node_modules/@coreui/icons/css/all.css",
        "node_modules/@fortawesome/fontawesome-free/css/all.min.css",
        "resources/css/custom-backend.css"
    ],
    "public/css/backend.css"
);

// Backend JS
mix.scripts(
    [
        "node_modules/jquery/dist/jquery.min.js",
        "node_modules/bootstrap/dist/js/bootstrap.min.js",
        "node_modules/perfect-scrollbar/dist/perfect-scrollbar.min.js",
        "node_modules/@coreui/coreui/dist/js/coreui.bundle.js",
        "resources/js/laravel.js",
        "resources/js/custom-backend.js"
    ],
    "public/js/backend.js"
);

/**
 *
 * Frontend
 *
 * -----------------------------------------------------------------------------
 */
// frontend-theme
mix.styles(
    [
        "node_modules/@fortawesome/fontawesome-free/css/all.min.css",
        
        "resources/css/Frontend/open-iconic-bootstrap.min.css",
        "resources/css/Frontend/animate.css",
        "resources/css/Frontend/owl.carousel.min.css",
        "resources/css/Frontend/owl.theme.default.min.css",
        "resources/css/Frontend/magnific-popup.css",
        "resources/css/Frontend/aos.css",
        "resources/css/Frontend/bootstrap-datepicker.css",
        "resources/css/Frontend/jquery.timepicker.css",
        "resources/css/Frontend/flaticon.css",
        "resources/css/Frontend/fancybox.min.css",
        "resources/css/Frontend/bootstrap.css",

        "resources/css/custom-frontend.css",
    ],
    "public/css/frontend.css"
);

// frontend js
mix.scripts(
    [
        "node_modules/jquery/dist/jquery.min.js",
        "node_modules/popper.js/dist/umd/popper.min.js",
        "node_modules/bootstrap/dist/js/bootstrap.min.js",
        "node_modules/headroom.js/dist/headroom.min.js",
        "node_modules/onscreen/dist/on-screen.umd.min.js",
        "node_modules/waypoints/lib/jquery.waypoints.min.js",
        "node_modules/jarallax/dist/jarallax.min.js",
        "node_modules/smooth-scroll/dist/smooth-scroll.polyfills.min.js",

        "resources/js/Frontend/jquery-migrate-3.0.1.min.js",
        "resources/js/Frontend/popper.min.js",
        "resources/js/Frontend/jquery.easing.1.3.js",
        "resources/js/Frontend/jquery.waypoints.min.js",
        "resources/js/Frontend/jquery.stellar.min.js",
        "resources/js/Frontend/owl.carousel.min.js",
        "resources/js/Frontend/jquery.magnific-popup.min.js",
        "resources/js/Frontend/bootstrap-datepicker.js",
        "resources/js/Frontend/jquery.fancybox.min.js",
        "resources/js/Frontend/aos.js",
        "resources/js/Frontend/jquery.animateNumber.min.js",
        "resources/js/Frontend/google-map.js",

        "resources/js/custom-frontend.js"
    ],
    "public/js/frontend.js"
);


// frontend-dashboard-theme
mix.styles(
    [
        "node_modules/@fortawesome/fontawesome-free/css/all.min.css",
        "public/vendor/impact-design/dashboard/css/dashboard.css",
        "resources/css/custom-dashboard.css",
    ],
    "public/css/dashboard.css"
);

// frontend-dashboard js
mix.scripts(
    [
        "node_modules/jquery/dist/jquery.min.js",
        "node_modules/popper.js/dist/umd/popper.min.js",
        "node_modules/bootstrap/dist/js/bootstrap.min.js",
        "public/vendor/impact-design/dashboard/assets/vendor/js-cookie/js.cookie.js",
        "public/vendor/impact-design/dashboard/assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js",
        "public/vendor/impact-design/dashboard/assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js",
        "public/vendor/impact-design/dashboard/assets/vendor/chart.js/dist/Chart.min.js",
        "public/vendor/impact-design/dashboard/assets/vendor/chart.js/dist/Chart.extension.js",
        "public/vendor/impact-design/dashboard/assets/js/dashboard.js",
        "resources/js/custom-dashboard.js"
    ],
    "public/js/dashboard.js"
);

if (mix.inProduction()) {
    mix.version();
}
