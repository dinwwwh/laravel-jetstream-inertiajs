import './bootstrap.ts';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';

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
        createApp({ render: () => h(app, props) })
            .use(plugin)
            .mixin({ methods: { route: window.route } })
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
