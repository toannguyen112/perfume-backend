const path = require("path");
const mix = require("laravel-mix");
const tailwindcss = require("tailwindcss");
require("laravel-mix-merge-manifest");
require("laravel-mix-svg-vue");

mix.js("resources/Backend/js/app.js", "public/backend/js")
    .vue({ version: 3 })
    .sass("resources/Backend/scss/app.scss", "public/backend/css/app.css")
    .options({
        processCssUrls: false,
        postCss: [
            tailwindcss("./resources/Backend/tailwind.config.js"),
            require("autoprefixer"),
        ],
    })
    .webpackConfig({
        resolve: {
            alias: {
                '@': path.resolve(__dirname, 'resources/Backend/js/')
            }
        }
    })
    .mergeManifest()
    .version();

mix.svgVue({
    svgPath: "resources/Backend/svg",
    extract: false,
    svgoSettings: [
        { removeTitle: true },
        { removeViewBox: false },
        { removeDimensions: false },
    ],
});
