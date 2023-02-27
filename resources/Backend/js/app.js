require("./bootstrap");

import Button from "@/Components/Button.vue";
import Dropdown from "@/Components/Dropdown.vue";
import Fieldset from "@/Components/Fields/Fieldset.vue";
import MetaData from "@/Components/Fields/MetaData.vue";
import SelectSource from "@/Components/Fields/SelectSource.vue";
import SelectSourceV2 from "@/Components/Fields/SelectSourceV2.vue";
import Form from "@/Components/Form.vue";
import FormPage from "@/Components/FormPage.vue";
import Icon from "@/Components/Icon.vue";
import Pagination from "@/Components/Pagination.vue";
import Table from "@/Components/Table.vue";
import { createInertiaApp, Link } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
import SvgVue from "svg-vue3";
import { TinyEmitter } from "tiny-emitter";
import { createApp, h } from "vue";
const emitter = new TinyEmitter();

const appName =
    window.document.getElementsByTagName("title")[0]?.innerText ||
    "JAMstack Vietnam";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => import(`./Pages/${name}`),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(SvgVue)

            .component("Button", Button)
            .component("Dropdown", Dropdown)
            .component("Form", Form)
            .component("FormPage", FormPage)
            .component("Fieldset", Fieldset)
            .component("Link", Link)
            .component("SelectSource", SelectSource)
            .component("SelectSourceV2", SelectSourceV2)
            .component("MetaData", MetaData)
            .component("Table", Table)
            .component("Icon", Icon)
            .component("Pagination", Pagination)

            .mixin({
                methods: {
                    route,
                    toMoney: function (value) {
                        const formatter = new Intl.NumberFormat("vi", {
                            style: "currency",
                            currency: "VND",
                            minimumFractionDigits: 0,
                        });

                        return formatter.format(value);
                    },
                    currentModel: function () {
                        return window.location.pathname.split("/")[2];
                    },
                    pluck: function (array, key = "id") {
                        return array.map((x) => x[key]);
                    },
                    hasExtension(filename, exts) {
                        return new RegExp(
                            "(" + exts.join("|").replace(/\./g, "\\.") + ")$"
                        ).test(filename);
                    },
                    isImage(filename) {
                        return new RegExp(
                            "(" +
                                [".jpg", ".png", ".jpeg", ".svg", ".webp"]
                                    .join("|")
                                    .replace(/\./g, "\\.") +
                                ")$"
                        ).test(filename);
                    },
                    staticUrl(url) {
                        return url.includes("http") ? url : "/static/" + url;
                    },
                },
            });

        vueApp.config.globalProperties.$bus = emitter;
        vueApp.mount(el);
        return vueApp;
    },
});

InertiaProgress.init();
