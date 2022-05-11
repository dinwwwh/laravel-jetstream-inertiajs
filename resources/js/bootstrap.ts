/* eslint-disable no-unused-vars */
import lodash from 'lodash';
import axios from 'axios';
import ziggy from 'ziggy-js';
import { createHeadManager, Inertia, Page } from '@inertiajs/inertia';

type Trans = (
    key: string,
    replaces?: Record<string, string>,
    local?: string | null
) => string;
type TransChoice = (
    key: string,
    number: number,
    replaces?: Record<string, string>,
    local?: string | null
) => string;

declare global {
    interface Window {
        _: typeof lodash;
        axios: typeof axios;
        route: typeof ziggy;
        __: Trans;
        trans: Trans;
        transChoice: TransChoice;
    }
}

declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        $route: typeof ziggy;
        $inertia: typeof Inertia;
        $page: Page;
        $headManager: ReturnType<typeof createHeadManager>;
        $trans: Trans;
        $transChoice: TransChoice;
    }
}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window._ = lodash;
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster:  import.meta.env.VITE_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
