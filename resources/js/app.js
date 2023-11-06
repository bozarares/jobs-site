import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { autoAnimatePlugin } from '@formkit/auto-animate/vue';

import DefaultLayout from '@/Layouts/DefaultLayout/DefaultLayout.vue';
import { createPinia } from 'pinia';
import { i18n } from './Languages/i18n';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: async (name) => {
        const page = await resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        );
        page.default.layout ??= DefaultLayout;
        return page;
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.use(plugin);
        app.use(createPinia());
        app.use(autoAnimatePlugin);
        app.use(ZiggyVue, Ziggy);

        app.use(i18n);

        app.mount(el);

        return app;
    },
    progress: {
        color: '#4B5563',
    },
});
