import './bootstrap';
import '../css/app.css';
import '@mdi/font/css/materialdesignicons.css';
import { primariesAndSecondariesColorsHDX, primariesAndSecondariesColorsBUAP, handleClubSettings } from './management-tailwind';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import 'vuetify/styles';
import { createVuetify } from 'vuetify';
import { VDateInput } from 'vuetify/labs/VDateInput';
import { VFileUpload } from 'vuetify/labs/VFileUpload';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import Toast from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import Aos from 'aos';
import 'aos/dist/aos.css';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const vuetify = createVuetify({
    components: {
        VDateInput,
        VFileUpload,
        ...components,
    },
    directives,
    icons: {
        iconfont: 'mdi',
    },
});

const toastOptions = {
    position: 'top-right',
    autoClose: 5000,
};

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(vuetify)
            .use(Toast, toastOptions)
            .mount(el);
    },
    progress: {
        color: '#a855f7',
    },
});

/*
* |--------------------------------------------------------------------------|
* | Management content after to load | DOM
* |--------------------------------------------------------------------------|
*/

document.addEventListener('DOMContentLoaded', () => {
    /*
    * Identify location
    */
    identifyLocation();
 /*
* |--------------------------------------------------------------------------|
* | Insert the Leaflet map when the page is loaded | Club
* |--------------------------------------------------------------------------|
*/
    Aos.init();
});


/*
* |--------------------------------------------------------------------------|
* | Management user localitation | Club
* |--------------------------------------------------------------------------|
*/
function identifyLocation() {

    const colors = primariesAndSecondariesColorsHDX();
    for (const color in colors) {
        document.documentElement.style.setProperty(`--${color}`, colors[color]);
    }

}


