const mix = require("laravel-mix");
mix
    .js("resources/js/react/index.js", "public/js")
    .react()
    .js("resources/js/app.js", "public/js")
    .copy("node_modules/bootstrap-dark-5/dist/js/darkmode.js", "public/js")
    .copy("resources/js/dataTables.bootstrap5.min.js", "public/js")
    .copy("resources/js/jquery.dataTables.min.js", "public/js")
    .sass("resources/sass/app.scss", "public/css")
