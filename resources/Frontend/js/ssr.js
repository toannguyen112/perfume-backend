import { createInertiaApp, Head, Link } from "@inertiajs/inertia-vue3";
import createServer from "@inertiajs/server";
import { renderToString } from "@vue/server-renderer";
import SvgVue from "svg-vue3";
import { createSSRApp, h } from "vue";
import route from "ziggy-js";
// import ClientOnly from "./Components/Jam/ClientOnly.vue";

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        resolve: (name) => require(`./Pages/${name}`),
        setup({ app, props, plugin }) {
            return createSSRApp({
                render: () => h(app, props),
            })
                .use(plugin)
                .use(SvgVue)
                .mixin({
                    methods: {
                        route: (name, params, absolute, config = Ziggy) =>
                            route(name, params, absolute, config),
                    },
                })
                .component("Link", Link)
                .component("Head", Head)
                // .component("ClientOnly", ClientOnly);
        },
    })
);
