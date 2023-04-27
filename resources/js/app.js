import './bootstrap';
import '../scss/app.scss';
import Vue from 'vue';
import {
    App as InertiaApp,
    plugin as InertiaPlugin,
    Link,
} from '@inertiajs/inertia-vue';

import AppHead from "./Components/Shared/AppHead.vue";
import BreadCrumbs from "./Components/Shared/BreadCrumbs.vue";
import useMixin from "./Use/useMixin";
import vuetify from "./vuetify";

Vue.mixin(useMixin);
Vue.use(InertiaPlugin);

Vue.component("Link", Link);
Vue.component("AppHead", AppHead);
Vue.component("BreadCrumbs", BreadCrumbs);

const app = document.getElementById("app");

window.App = new Vue({
    vuetify,
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: async (name) => {
                    const pages = import.meta.glob('./Pages/**/*.vue');
                    return (await pages[`./Pages/${name}.vue`]()).default;
                },
                transformProps: (props) => {
                    return props;
                },
            },
        }),
}).$mount(app);
