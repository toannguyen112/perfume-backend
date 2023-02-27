const path = require("path");
const mix = require("laravel-mix");
const webpackNodeExternals = require("webpack-node-externals");

require("laravel-mix-svg-vue");

mix.alias({
    ziggy: path.resolve("vendor/tightenco/ziggy/dist/vue"),
});

mix.options({ manifest: false })
    .js("resources/Frontend/js/ssr.js", "public/js")
    .vue({ version: 3, options: { optimizeSSR: true }, extractStyles: true })
    .alias({ "@": path.resolve("resources/Frontend/js") })
    .webpackConfig({
        target: "node",
        externals: [webpackNodeExternals()],
    });

mix.svgVue({
    svgPath: "resources/Frontend/svg",
    extract: false,
    svgoSettings: [
        { removeTitle: true },
        { removeViewBox: false },
        { removeDimensions: false },
    ],
});
