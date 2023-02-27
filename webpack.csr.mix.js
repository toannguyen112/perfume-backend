const mix = require("laravel-mix");
const path = require("path");
require("laravel-mix-merge-manifest");
require("laravel-mix-svg-vue");
const FilterWarningsPlugin = require("webpack-filter-warnings-plugin");

mix.js("resources/Frontend/js/app.js", "public/js")
    .vue({ extractStyles: true })
    .sass("resources/Frontend/scss/app.scss", "public/css/app.css")
    .options({
        processCssUrls: false,
        postCss: [
            require("tailwindcss")("./resources/Frontend/tailwind.config.js"),
            require("autoprefixer"),
        ],
    })
    .extract()
    .webpackConfig({
        resolve: {
            alias: {
                "@": path.resolve("resources/Frontend/js"),
            },
        },

        plugins: [
            new FilterWarningsPlugin({
                exclude:
                    /mini-css-extract-plugin[^]*Conflicting order. Following module has been added:/,
            }),
        ],
    })
    .extract()
    .mergeManifest()
    .version();

mix.svgVue({
    svgPath: "resources/Frontend/svg",
    extract: false,
    svgoSettings: [
        { removeTitle: true },
        { removeViewBox: false },
        { removeDimensions: false },
    ],
});
