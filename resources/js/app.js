import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import ToastService from 'primevue/toastservice';
import PrimeVue from 'primevue/config';
import Tooltip from 'primevue/tooltip'; // Pastikan diimport
import ConfirmationService from 'primevue/confirmationservice';
import Aura from '@primevue/themes/aura';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) }); // Buat variabel baru di sini

        vueApp.use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue, {
                theme: {
                    preset: Aura,
                    options: {
                        darkModeSelector: '.none',
                    },
                }
            })
            .use(ConfirmationService)
            .use(ToastService);

        // DAFTARKAN DIRECTIVE DI SINI
        vueApp.directive('tooltip', Tooltip);

        return vueApp.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});