import './bootstrap.ts';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';

/** @ts-ignore */
import DefaultLayout from './Layouts/Default.vue';

/**
 * ! A minimize downside, it will import unnecessary partial files
 * (./Pages/ ** /Partials/*.vue)
 */
const pages = import.meta.globEager('./Pages/**/*.vue');

const appName =
    window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name: string) => {
        const page = pages[`./Pages/${name}.vue`].default;

        if (page.layout !== null) page.layout ??= DefaultLayout;

        return page as any;
    },
    setup({ el, app, props, plugin }) {
        const createdApp = createApp({ render: () => h(app, props) }).use(
            plugin
        );

        createdApp.config.globalProperties.$route = window.route;
        createdApp.config.globalProperties.$trans = window.trans;
        createdApp.config.globalProperties.$transChoice = window.transChoice;

        createdApp.mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
